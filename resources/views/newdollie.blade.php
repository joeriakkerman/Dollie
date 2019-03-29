@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <form action="{{ route('newdollie.verify') }}" method="POST">
            @csrf
            Name: <input type="text" name="name"><br>
            Description: <input type="text" name="description"><br>
            Currency: <select name="currency">
                <option value="AUD">Australian dollar</option>
                <option value="BRL">Brazilian real</option>
                <option value="CAD">Canadian dollar</option>
                <option value="CHF">Swiss franc</option>
                <option value="CZK">Czech koruna</option>
                <option value="DKK">Danish krone</option>
                <option value="EUR" selected>Euro</option>
                <option value="GBP">British pound</option>
                <option value="HKD">Hong Kong dollar</option>
                <option value="HUF">Hungarian forint</option>
                <option value="ILS">Israeli new shekel</option>
                <option value="JPY">Japanese yen</option>
                <option value="MXN">Mexican peso</option>
                <option value="MYR">Malaysian ringgit</option>
                <option value="NOK">Norwegian krone</option>
                <option value="NZD">New Zealand dollar</option>
                <option value="PHP">Philippine piso</option>
                <option value="PLN">Polish złoty</option>
                <option value="RUB">Russian ruble</option>
                <option value="SEK">Swedish krona</option>
                <option value="SGD">Singapore dollar</option>
                <option value="THB">Thai baht</option>
                <option value="TWD">New Taiwan dollar</option>
                <option value="USD">United States dollar</option>
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