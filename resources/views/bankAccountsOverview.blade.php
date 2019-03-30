@extends('layouts.app')



@section('content')
<div class="container">
<table class="table" id= "mytable">
<tr>
<th>{{ __('Bankaccount') }}</th>
<th>{{ __('Balance') }}</th>
<th>{{ __('Delete') }}</th>
</tr>
@foreach($bankAccounts as $accounts)
<tr>
<td>{{$accounts->account_number}}</td>
<td>{{$accounts->balance}}</td>
<!-- <td>{{$accounts->balance}}</td> -->

<td> 
    <form method="POST" action="/bankAccountsOverview">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="hidden" name="bank_account" value="{{$accounts->account_number}}">
        <div class="form-group">
            <input type="submit" class="btn btn-danger delete-account" value="Delete">
        </div>
    </form>
</td>

</tr>
@endforeach
</table>
</div>

<div class="container">
<form action="/bankAccountsOverview" method='POST'>
@csrf
<br>
  <h4>{{ __('Add new bankaccount') }}</h2>
  <input type="text" size="30" style="color: #d4cfcd" name="newaccount" placeholder="NL65RABO0844637524">
  <br>
  @if ($errors->any())
  {{ implode('', $errors->all(':message')) }}
  <br>
@endif
  <input type="submit" value="Toevoegen">
</form> 
</div> 

@endsection

