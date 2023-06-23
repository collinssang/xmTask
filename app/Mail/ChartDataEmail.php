<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;

class ChartDataEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $companySymbol;
    public $chartImageFilePath;
    public $pdfFilePath;
    public $startDate;
    public $endDate;

    public function __construct($pdfFilePath, $chartImageFilePath, $companySymbol, $startDate, $endDate)
    {
        $this->companySymbol = $companySymbol;
        $this->pdfFilePath = $pdfFilePath;
        $this->chartImageFilePath = $chartImageFilePath;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function build()
    {
        $companySymbol = $this->companySymbol;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        return $this->view('emails', compact('companySymbol','startDate','endDate'))
            ->attach($this->pdfFilePath, ['as' => 'chart_data.pdf', 'mime' => 'application/pdf'])
            ->attach($this->chartImageFilePath, ['as' => 'charts.png', 'mime' => 'image/png'])
            ->subject('Historical Chart and Data');
    }
}
