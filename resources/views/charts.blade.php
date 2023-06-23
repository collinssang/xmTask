@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Stock Data Chart For Company <i><em><u>{{$companySymbol}}</u></em></i></h1>
        <a href="{{url('/')}}" class="btn btn-primary">Go Back Home</a>
        <a href="{{url('send_email/'.$companySymbol)}}" class="btn btn-success" symbol="{{$companySymbol}}" id="link2" uri="{{url('/generateImage')}}">Send Data To Email</a>
        <br/>
        <canvas id="priceChart"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var dates = <?php echo json_encode($labels); ?>;
            var openPrices = <?php echo json_encode($openPrices); ?>;
            var closePrices = <?php echo json_encode($closePrices); ?>;

            // Create the chart
            var ctx = document.getElementById('priceChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Open Price',
                        data: openPrices,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                        {
                            label: 'Close Price',
                            data: closePrices,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Price'
                            }
                        }
                    }
                }
            });

        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{url('/')}}/js/chartSave.js"></script>
        <script>
            $("#link2").click(function (e) {
                var url = chart.toBase64Image();
                $("#link2").attr("data-src", url);
                e.preventDefault();
                var image = $(this).attr('data-src');
                var companySymbol = $(this).attr('data-src');
                var  data = {'image': image, 'companySymbol':$(this).attr('symbol')};
                if (image) {
                    $.ajax({
                        type: "post",
                        url: $(this).attr('uri'),
                        cache: false,
                        data: data,
                        success: function (json) {
                            console.log(json);
                            window.location.replace('/');
                        },
                        error: function () {
                            console.log('Error while request..');
                        }
                    });
                }
            });

        </script>

@endsection
