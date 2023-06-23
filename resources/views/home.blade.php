@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Stock Data Form</h1>
        <form name="stockForm" method="POST" action="{{url('/xm_task')}}" onsubmit="return validateForm()">
            @csrf
            <div class="form-group">
                <label for="companySymbol">Company Symbol:</label>
                <select id="companySymbol" name="companySymbol" class="form-control"></select>
            </div>
            <div class="form-group">
                <label for="startDate">Start Date:</label>
                <input type="date" name="startDate" value="<?php echo isset($startDate) ? $startDate : ''; ?>"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="endDate">End Date:</label>
                <input type="date" name="endDate" value="<?php echo isset($endDate) ? $endDate : ''; ?>"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>"
                       class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" value="Submit" class="btn btn-primary">
            </div>
        </form>

        <div id="errorMessages"></div>
    </div>
@endsection
