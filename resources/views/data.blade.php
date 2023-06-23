@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Stock Data Form For Company <i><em><u>{{$companySymbol}}</u></em></i></h1>
        <a href="{{url('xm_task/'.$companySymbol)}}" class="btn btn-primary">View Close & open chart for {{$companySymbol}} </a>
        <br/>
        <table class="table table-striped">
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
            <tbody>
            <?php
            foreach ($responseData['prices'] as $quote) {
            $date = $quote['date'];
            $open = $quote['open'];
            $high = $quote['high'];
            $low = $quote['low'];
            $close = $quote['close'];
            $volume = $quote['volume'];
            ?>

            <tr>
                <td><?php echo $date; ?></td>
                <td><?php echo $open; ?></td>
                <td><?php echo $high; ?></td>
                <td><?php echo $low; ?></td>
                <td><?php echo $close; ?></td>
                <td><?php echo $volume; ?></td>
            </tr>

            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
@endsection
