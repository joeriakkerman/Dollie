@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $name }}</div>
                <div class="card-body">
                    <strong>Description:</strong> {{ $description }}<br>
                    <strong>Currency:</strong> {{ $currency }}<br>
                    <strong>Amount:</strong> {{ $amount }}<br>
                    <strong>Bank Account:</strong> {{ $account_number }}<br>
                    <br>
                    Send Dollie to:
                    <table id="user_table" class="table">
                        <tr>
                            <th>Name</th>
                            <th>Delete</th>
                        </tr>
                        @foreach($payers as $payer)
                        <tr>
                            <td>{{App\User::getName($payer)}}</td>
                            <td>
                                <form method="POST" action="/newdollie">
                                    @csrf
                                    <input type="hidden" name="name" value="{{ $name }}">
                                    <input type="hidden" name="description" value="{{ $description }}">
                                    <input type="hidden" name="currency" value="{{ $currency }}">
                                    <input type="hidden" name="amount" value="{{ $amount }}">
                                    <input type="hidden" name="account_number" value="{{ $account_number }}">
                                    <input type="hidden" name="payers" value="{{ json_encode($payers) }}">
                                    <input type="hidden" name="deletepayer" value="{{ $payer }}">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-danger delete-account" value="Delete">
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    <div id="userDropdown">
                        <input type="text" placeholder="Search.." id="findUser" onkeyup="myFunction()">
                    </div>

                    <p id="errorMessage"></p>

                    <script>
                        function myFunction(){
                            $(document).ready(function(){
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('users') }}",
                                    dataType: 'text',
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                    data: {
                                        'filter': $("#findUser").val(),
                                        '_token': "{{ csrf_token() }}"
                                    },

                                    success: function (data) {
                                        $(".user").remove();
                                        var o = JSON.parse(data);
                                        if(o.length <= 0){
                                            $("#errorMessage").html("Could not find users with a name like this...");
                                        }else{
                                            $("#errorMessage").html("");
                                        }
                                        var dd = document.getElementById("userDropdown");
                                        for(var i = 0; i < o.length; i++){
                                            dd.innerHTML += '<form class="user" method="POST" action="/newdollie"> @csrf <input type="hidden" name="name" value="{{ $name }}"> <input type="hidden" name="description" value="{{ $description }}"> <input type="hidden" name="currency" value="{{ $currency }}"> <input type="hidden" name="amount" value="{{ $amount }}"> <input type="hidden" name="account_number" value="{{ $account_number }}"> <input type="hidden" name="payers" value="{{ json_encode($payers) }}"> <input type="hidden" name="addpayer" value="' + o[i].id + '"> <input type="submit" value="' + o[i].name + '"> </form>';
                                        }
                                    },
                                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                                        $("#errorMessage").html("Could not find users with a name like this...");
                                    }
                                });
                            });
                        }
                    </script>

                    <form action="{{ route('newdollie.save') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="{{ $name }}">
                        <input type="hidden" name="description" value="{{ $description }}">
                        <input type="hidden" name="currency" value="{{ $currency }}">
                        <input type="hidden" name="amount" value="{{ $amount }}">
                        <input type="hidden" name="account_number" value="{{ $account_number }}">
                        <input type="hidden" name="payers" value="{{ json_encode($payers) }}">
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