<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @stack('styles')

    @yield('css')
</head>

<body class="skin-blue   ">
<div id="content-wrapper">
    <div class="container-fluid" id="base_u">
        <div class="row">
            <div class="col-lg-12">
                @yield('content')
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{url('/')}}/js/custom.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        populateSymbols();
    });

    function populateSymbols() {
        $.getJSON('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json', function (data) {
            var symbols = [];
            console.log("me");
            $.each(data, function (key, value) {
                symbols.push(value.Symbol);
                console.log(value.Symbol);
            });
            symbols.sort();
            var options = '<option value=""></option>';
            for (var i = 0; i < symbols.length; i++) {
                options += '<option value="' + symbols[i] + '">' + symbols[i] + '</option>';
            }
            $('#companySymbol').html(options);
        });
    }

</script>
</body>
</html>


