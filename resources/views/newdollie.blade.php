@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if(!empty($success))
            Name: {{ $name }}
            Description: {{ $description }}
            Currency: {{ $currency }}
            Amount: {{ $name }}

            <form action="{{ route('newdollie.save') }}" method="POST">
                <input type="submit" name="submit" value="Save">
            </form>
        @else
            <form action="{{ route('newdollie.save') }}" method="POST">
                @csrf
                Name: <input type="text" name="name" @if(!empty($name)) value="{{ $name }}" @endif><br>
                Description: <input type="text" name="description"><br>
                Currency: <select name="currency">
                    <option value="euro">Euro</option>
                </select><br>
                Amount: <input type="number" name="amount"><br>
                <input type="submit" name="submit" value="Submit">
            </form>
            @if(!empty($message))
                {{ $message }}
            @endif
            @if(!empty($error))
                {{ $error }}
            @endif
        @endif
    </div>
</div>
@endsection