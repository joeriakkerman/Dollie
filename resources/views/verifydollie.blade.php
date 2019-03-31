@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $name }}</div>
                <div class="card-body">
                    <strong>{{ __('Description:') }}</strong> {{ $description }}<br>
                    <strong>{{ __('Currency:') }}</strong> {{ $currency }}<br>
                    <strong>{{ __('Amount:') }}</strong> {{ $amount }}<br>
                    <strong>{{ __('Bankaccount:') }}</strong> {{ $account_number }}<br>
                    <strong>{{ __('Dollie date:') }}</strong> {{ $dollie_date }}<br>

                    @if(isset($filename) && !empty($filename))
                        <img src="{{ route('dollie.image', ['filename' => $filename]) }}"/>
                    @endif

                    <br>
                    {{ __('Send Dollie to:') }}
                    <table id="user_table" class="table">
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Delete') }}</th>
                        </tr>
                        @foreach($payers as $payer)
                        <tr>
                            <td>{{App\User::getName($payer)}}</td>
                            <td>
                                <form method="POST" action="{{ route('newdollie.verify', app()->getLocale()) }}">
                                    @csrf
                                    <input type="hidden" name="name" value="{{ $name }}">
                                    <input type="hidden" name="description" value="{{ $description }}">
                                    <input type="hidden" name="currency" value="{{ $currency }}">
                                    <input type="hidden" name="amount" value="{{ $amount }}">
                                    <input type="hidden" name="account_number" value="{{ $account_number }}">
                                    <input type="hidden" name="payers" value="{{ json_encode($payers) }}">
                                    <input type="hidden" name="deletepayer" value="{{ $payer }}">
                                    <input type="hidden" name="dollie_date" value="{{ $dollie_date }}">
                                    <input type="hidden" name="recurring" value="{{ $recurring }}">
                                    <input type="hidden" name="amount_recurring" value="{{ $recurring_amount }}">
                                    <input type="hidden" name="filename" value="{{ $filename }}">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-danger delete-account" value="{{ __('Delete') }}">
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    <div id="userDropdown">
                        <input type="text" placeholder="{{ __('Search:') }}" id="findUser" onkeyup="myFunction()">
                    </div>

                    <p id="errorMessage"></p>

                    <script>
                        function myFunction(){
                            var found = 0;
                            var done = 0;
                            $(document).ready(function(){
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('users', app()->getLocale()) }}",
                                    dataType: 'text',
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                    data: {
                                        'filter': $("#findUser").val(),
                                        '_token': "{{ csrf_token() }}"
                                    },

                                    success: function (data) {
                                        done++;
                                        $(".user").remove();
                                        var o = JSON.parse(data);
                                        if(o.length > 0){
                                            found = 1;
                                        }
                                        
                                        if(done == 2){
                                            if(found == 1) $("#errorMessage").html("");
                                            else $("#errorMessage").html("Could not find users/groups with a name like this...");
                                        }
                                        var dd = document.getElementById("userDropdown");
                                        for(var i = 0; i < o.length; i++){
                                            dd.innerHTML += '<form class="user" method="POST" action="{{ route('newdollie.verify', app()->getLocale()) }}"> @csrf <input type="hidden" name="name" value="{{ $name }}"> <input type="hidden" name="description" value="{{ $description }}"> <input type="hidden" name="recurring" value="{{ $recurring }}"> <input type="hidden" name="amount_recurring" value="{{ $recurring_amount }}"> <input type="hidden" name="currency" value="{{ $currency }}"> <input type="hidden" name="dollie_date" value="{{ $dollie_date }}"> <input type="hidden" name="amount" value="{{ $amount }}"> <input type="hidden" name="account_number" value="{{ $account_number }}"> <input type="hidden" name="filename" value="{{ $filename }}"> <input type="hidden" name="payers" value="{{ json_encode($payers) }}"> <input type="hidden" name="addpayer" value="' + o[i].id + '"> <input type="submit" value="' + o[i].name + '"> </form>';
                                        }
                                    },
                                    error: function(xhr, errDesc, exception) {
                                        done++;
                                        if(done == 2){
                                            if(found == 1) $("#errorMessage").html("");
                                            else $("#errorMessage").html("Could not find users/groups with a name like this...");
                                        }
                                    }
                                });

                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('getgroups', app()->getLocale()) }}",
                                    dataType: 'text',
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                    data: {
                                        'filter': $("#findUser").val(),
                                        '_token': "{{ csrf_token() }}"
                                    },

                                    success: function (data) {
                                        done++;
                                        $(".group").remove();
                                        var o = JSON.parse(data);
                                        if(o.length > 0){
                                            found = 1;
                                        }
                                        
                                        if(done == 2){
                                            if(found == 1) $("#errorMessage").html("");
                                            else $("#errorMessage").html("Could not find users/groups with a name like this...");
                                        }
                                        var dd = document.getElementById("userDropdown");
                                        for(var i = 0; i < o.length; i++){
                                            dd.innerHTML += '<form class="group" method="POST" action="{{ route('newdollie.verify', app()->getLocale()) }}"> @csrf <input type="hidden" name="name" value="{{ $name }}"> <input type="hidden" name="description" value="{{ $description }}"> <input type="hidden" name="currency" value="{{ $currency }}"> <input type="hidden" name="amount" value="{{ $amount }}"> <input type="hidden" name="account_number" value="{{ $account_number }}"> <input type="hidden" name="filename" value="{{ $filename }}"> <input type="hidden" name="payers" value="{{ json_encode($payers) }}"> <input type="hidden" name="addgroup" value="' + o[i].id + '"> <input type="submit" value="Group: ' + o[i].name + '"> </form>';
                                        }
                                    },
                                    error: function(xhr, errDesc, exception) {
                                        done++;
                                        if(done == 2){
                                            if(found == 1) $("#errorMessage").html("");
                                            else $("#errorMessage").html("Could not find users/groups with a name like this...");
                                        }
                                    }
                                });

                                
                            });
                        }
                    </script>

                    <form action="{{ route('newdollie.save', app()->getLocale()) }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="{{ $name }}">
                        <input type="hidden" name="description" value="{{ $description }}">
                        <input type="hidden" name="currency" value="{{ $currency }}">
                        <input type="hidden" name="amount" value="{{ $amount }}">
                        <input type="hidden" name="account_number" value="{{ $account_number }}">
                        <input type="hidden" name="payers" value="{{ json_encode($payers) }}">
                        <input type="hidden" name="dollie_date" value="{{ $dollie_date }}">
                        <input type="hidden" name="recurring" value="{{ $recurring }}">
                        <input type="hidden" name="amount_recurring" value="{{ $recurring_amount }}">
                        <input type="hidden" name="filename" value="{{ $filename }}">
                        <input type="submit" name="save" value="Save">
                    </form>

                    @if($errors->any())
                        {{$errors->first()}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection