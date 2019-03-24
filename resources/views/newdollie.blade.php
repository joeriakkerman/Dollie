@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
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

        @if($errors->any())
            {{$errors->first()}}
        @endif
    </div>
</div>
@endsection