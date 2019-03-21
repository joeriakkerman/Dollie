@extends('layouts.app')



@section('content')
<div class="container">
<table class="table">
<tr>
<th>Bankrekeningnummer</th>
<th>Saldo</th>
</tr>
<tr>
@foreach($bankAccounts as $accounts)
<td>{{$accounts->account_number}}</td>
<td>{{$accounts->balance}}</td>
<!-- <td>{{$accounts->balance}}</td> -->
@endforeach
</tr>
</table>
</div>

<div class="container">
<form action="/action_page.php">
  Rekeningnummer:<br>
  <input type="text" name="firstname" value="Mickey">
  <br>
  <input type="submit" value="Submit">
</form> 
</div>
@endsection