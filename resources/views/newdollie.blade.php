@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <form action="{{ route('newdollie.verify') }}" method="POST">
            @csrf
            Name: <input type="text" name="name"><br>
            Description: <input type="text" name="description"><br>
            Currency: <select name="currency">
                <option value="EUR">Euro</option>
            </select><br>
            Amount: <input type="number" name="amount"><br>
            Bank Account:
            <select name="account_number">
            @foreach($bankaccounts as $bankaccount)
                <option value="{{ $bankaccount->account_number }}">{{ $bankaccount->account_number }}</option>;
            @endforeach
            </select>
            <input type="submit" name="submit" value="Submit">
        </form>

        @if($errors->any())
            {{$errors->first()}}
        @endif
    </div>
</div>
@endsection