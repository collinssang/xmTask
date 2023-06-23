<?php

namespace App\Http\Controllers;

use App\Mail\ChartDataEmail;
use App\Models\ProcessData;
use App\Http\Requests\StoreProcessDataRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use ConsoleTVs\Charts\Classes\Highcharts\Chart;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use ImageCharts;

class ProcessDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('home');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProcessDataRequest $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), $request->rules());

        // If there are validation errors, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $currentDate = date('Y-m-d');
        if ($request->startDate > $currentDate) {
            $errors[] = 'Start Date cannot be greater than the current date.';
        }
        if ($request->startDate > $request->endDate) {
            $errors[] = 'Start Date cannot be greater than the end date.';
        }
        if (function_exists(isValidDate($request->endDate))) {
            if (!isValidDate($request->startDate)) {
                $errors[] = 'Start Date is not a valid date.';
            }
        }

        if ($request->endDate > $currentDate) {
            $errors[] = 'End Date cannot be greater than the current date.';
        }
        if (function_exists(isValidDate($request->endDate))) {
            if (!isValidDate($request->endDate)) {
                $errors[] = 'End Date is not a valid date.';
            }
        }
        if ($request->startDate > $request->endDate) {
            $errors[] = 'End Date must be greater than or equal to Start Date.';
        }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is invalid.';
        }

        /*
         * Store extra data for later use if it will be needed using Cache
         */
        Cache::put('startDate', $request->startDate);
        Cache::put('endDate', $request->endDate);
        Cache::put('email', $request->email);
        //end Storing extra data

        $companySymbol = $request->companySymbol;
        $responseData = $this->fetchData($request->companySymbol, $request->startDate, $request->endDate);
        // Check if the API request was successful
        if (isset($responseData['prices'])) {
            // Prepare the table header
            return \view('data', compact('responseData', 'companySymbol'));
        } else {
            $error[] = 'no data';
            return redirect()->back()->withErrors($error)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function showChart($companySymbol)
    {
        $responseData = $this->fetchData($companySymbol, date('Y-m-d'), date('Y-m-d'));
        // Check if the API request was successful
        if (isset($responseData['prices'])) {
            // Prepare the chart data
            $labels = [];
            $openPrices = [];
            $closePrices = [];

            foreach ($responseData['prices'] as $quote) {
                $date = $quote['date'];
                $open = $quote['open'];
                $close = $quote['close'];

                $labels[] = $date;
                $openPrices[] = $open;
                $closePrices[] = $close;
            }
            return \view('charts', compact('companySymbol', 'labels', 'openPrices', 'closePrices'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function fetchData($companySymbol, $startDate, $endDate)
    {

        /* Perform your desired actions with the form data
         * fetching and displaying historical quotes

         * API configuration
         *
         */
        $apiKey = env('X_RAPIDAPI_KEY', '36d92f7874mshcffc999e2012edap1a0d6fjsn6ffcf8c2b1d2');
        $host = env('X_RAPIDAPI_HOST', 'yh-finance.p.rapidapi.com');

        // API endpoint and query parameters
        $endpoint = env('X_RAPIDAPI_ENDPOINT', 'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data');

        $queryParams = [
            'region' => 'US',
            'symbol' => $companySymbol,
            'from' => $startDate,
            'to' => $endDate
        ];
        // Build the query string
        $queryString = http_build_query($queryParams);

        // Prepare the API request
        $url = $endpoint . '?' . $queryString;
        $headers = [
            'X-RapidAPI-Key: ' . $apiKey,
            'X-RapidAPI-Host: ' . $host
        ];
        $context = stream_context_create([
            'http' => [
                'header' => $headers
            ]
        ]);
        // Send API request and retrieve the response
        $response = file_get_contents($url, false, $context);
        return json_decode($response, true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function sendEmail($companySymbol,$output)
    {
        $email = Cache::get('email');
        $startDate = Cache::get('startDate');
        $endDate = Cache::get('endDate');
        $companySymbol = $companySymbol;
        $responseData = $this->fetchData($companySymbol, $startDate, $endDate);

        $labels = [];
        $openPrices = [];
        $closePrices = [];
        // Check if the API request was successful
        if (isset($responseData['prices'])) {

            $table = '<table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Open</th>
                                <th>High</th>
                                <th>Low</th>
                                <th>Close</th>
                                <th>Volume</th>
                            </tr>
                        </thead>
                        <tbody>';

            // Loop through the historical data and add rows to the table
            foreach ($responseData['prices'] as $quote) {
                $date = $quote['date'];
                $open = $quote['open'];
                $high = $quote['high'];
                $low = $quote['low'];
                $close = $quote['close'];
                $volume = $quote['volume'];
                $labels[] = $date;
                $openPrices[] = $open;
                $closePrices[] = $close;
                // Add a row to the table
                $table .= "<tr>
                                <td>$date</td>
                                <td>$open</td>
                                <td>$high</td>
                                <td>$low</td>
                                <td>$close</td>
                                <td>$volume</td>
                            </tr>";
            }

            // Close the table
            $table .= '</tbody></table>';

            // Generate a PDF file for the table
            $pdfFilePath = storage_path('app/chart_data.pdf');
            $pdf = PDF::loadView('chart_data', compact('table'));
            $pdf->save($pdfFilePath);

            // Send the email with the chart and table
            Mail::to($email)->send(new ChartDataEmail($pdfFilePath, $output, $companySymbol, $startDate, $endDate));

            // Delete the temporary chart image file
//            unlink($chartImageFilePath);
            Cache::flush();
            return redirect('/');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function generateImage(Request $request){
        $image = $request->image;
        $folderPath = public_path("uploads/");
        if(!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $image_parts = explode(";base64,", $image);
        $image_type_aux = explode("uploads/", $image_parts[0]);
        $image_base64 = base64_decode($image_parts[1]);
        $name = uniqid() . '.png';
        $file = $folderPath . $name;
        $output = file_put_contents($file, $image_base64);
      return  $this->sendEmail($request->companySymbol, $file);
    }
}
