@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
       
        <form action="{{ route('newdollie.verify', app()->getLocale()) }}" method="POST">
            @csrf
            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
            <input id='name' type="text" name="name"><br>
            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
            <input id='description' type="text" name="description"><br>
            <label for="currency" class="col-md-4 col-form-label text-md-right">{{ __('Currency') }}</label>
            <select id='currency' name="currency">
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
            <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('Amount') }}</label>
            <input id='amount' type="number" name="amount"><br>
            <label for="bankaccount" class="col-md-4 col-form-label text-md-right">{{ __('Bankaccount') }}</label>
            <select id='bankaccount' name="account_number">
            @foreach($bankaccounts as $bankaccount)
                <option value="{{ $bankaccount->account_number }}">{{ $bankaccount->account_number }}</option>;
            @endforeach
            </select>
            <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Send date') }}</label>
            <input id='date' type="date" name = "dollie_date"><br>
            <label for="recurring" class="col-md-4 col-form-label text-md-right">{{ __('Recurring') }}</label>
            <select id='recurring' name="recurring">
                <option value="none">{{ __('none') }}</option>
                <option value="weekly">{{ __('weekly') }}</option>
                <option value="monthly">{{ __('monthly') }}</option>
            </select><br>
            <label for="recurring_amount" class="col-md-4 col-form-label text-md-right">{{ __('Times recurring') }}</label>
            <input id='recurring_amount' type="number" name="amount_recurring"><br>
        </div>
 
    <br>
            <div class="col-md-0 text-center">
            <input type="submit" class="btn btn-primary" name="submit" value="{{ __('Submit') }}">
            </div>
        </form>

        @if($errors->any())
            {{$errors->first()}}
        @endif
  
</div>
@endsection