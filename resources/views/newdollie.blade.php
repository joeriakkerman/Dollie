@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if(!empty($name) && !empty($description) && !empty($currency) && !empty($amount))
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $name }}</div>
                    <div class="card-body">
                        <strong>Description:</strong> {{ $description }}<br>
                        <strong>Currency:</strong> {{ $currency }}<br>
                        <strong>Amount:</strong> {{ $amount }}<br>

                        Send Dollie to:
                        <table id="user_table" class="table">
                            <tr>
                                <th>Name</th>
                                <th>Delete</th>
                            </tr>
                            @foreach($payers as $payer)
                            <tr>
                                <td>{{ $payer->name }}</td>
                                <form method="POST" action="/newdollie">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <input type="hidden" name="name" value="{{ $name }}">
                                    <input type="hidden" name="description" value="{{ $description }}">
                                    <input type="hidden" name="currency" value="{{ $currency }}">
                                    <input type="hidden" name="amount" value="{{ $amount }}">
                                    <input type="hidden" name="deletepayer" value="{{$payer->id}}">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-danger delete-account" value="Delete">
                                    </div>
                                </form>
                            </tr>
                            @endforeach
                        </table>

                        <div id="userDropdown">
                            <input type="text" placeholder="Search.." id="findUser" onkeyup="myFunction()">
                            
                        </div>

                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                        <script>
                            window.name = '<?=$_POST['name']?>';
                            window.description = '<?=$_POST['description']?>';
                            window.currency = '<?=$_POST['currency']?>';
                            window.value = '<?=$_POST['value']?>';
                            window.payers = '<?=$_POST['payers']?>';

                            function myFunction(){
                                $(document).ready(function(){
                                    $.ajax({
                                        type: 'POST',
                                        url: "{{ route('users')}}",
                                        dataType: 'text',
                                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        data: {
                                            'filter': $("#findUser").val(),
                                            '_token': "{{ csrf_token() }}"
                                        },

                                        success: function (data) {
                                            $(".user").remove();
                                            var o = JSON.parse(data);
                                            var dd = document.getElementById("userDropdown");
                                            for(var i = 0; i < o.length; i++){
                                                dd.innerHTML += "<a class='user' onclick='addUserToTable(" + o[i].id + ")'>" + o[i].name + "</a>";
                                            }
                                        },
                                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                                            alert("Could not find the users...");
                                        }
                                    });
                                });
                            }

                            function addUserToTable(var id){
                                $(document).ready(function(){
                                    $.ajax({
                                        type: 'POST',
                                        url: "{{ route('newdollie.verify')}}",
                                        dataType: 'text',
                                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        data: {
                                            'name': window.name,
                                            'description': window.description,
                                            'currency': window.currency,
                                            'value': window.value,
                                            'payers': window.payers,
                                            'addpayer': id
                                            '_token': "{{ csrf_token() }}"
                                        },

                                        success: function (data) {
                                            alert("added");
                                        },
                                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                                            alert("Could not find the users...");
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
                            <input type="submit" name="save" value="Save">
                        </form>
                    </div>
                </div>
            </div>
        @else
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
        @endif

        @if(!empty($message))
             {{ $message }}
        @endif
        @if(!empty($error))
            {{ $error }}
        @endif
    </div>
</div>
@endsection