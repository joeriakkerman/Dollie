@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if(!empty($name) && !empty($description) && !empty($currency) && !empty($amount))
            <div>
                <strong>Name:</strong> {{ $name }}<br>
                <strong>Description:</strong> {{ $description }}<br>
                <strong>Currency:</strong> {{ $currency }}<br>
                <strong>Amount:</strong> {{ $amount }}<br>
            </div><br>

            <form action="{{ route('newdollie.save') }}" method="POST">
                @csrf
                <input type="hidden" name="name" value="{{ $name }}">
                <input type="hidden" name="description" value="{{ $description }}">
                <input type="hidden" name="currency" value="{{ $currency }}">
                <input type="hidden" name="amount" value="{{ $amount }}">
                <input type="submit" name="save" value="Save">
            </form>
        @else
            <form action="{{ route('newdollie.verify') }}" method="POST">
                @csrf
                Name: <input type="text" name="name"><br>
                Description: <input type="text" name="description"><br>
                Currency: <select name="currency">
                    <option value="euro">Euro</option>
                </select><br>
                Amount: <input type="number" name="amount"><br>
                <input type="submit" name="submit" value="Submit">
            </form>
        @endif

        @if(!empty($message))
             {{ $message }}
        @endif
        @if(!empty($error))
            {{ $error }}
        @endif
    </div>
</div>
@endsection