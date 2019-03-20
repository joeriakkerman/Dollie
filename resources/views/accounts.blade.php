@extends('layouts.app')



@section('content')

<div class="container">
<table class="table">
<tr>
<th>Bankrekeningnummer</th>
<th>Saldo</th>
</tr>
<tr>
<td>NL58RABO2421442</td>
<td>15MILJOEN EUROS</td>
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