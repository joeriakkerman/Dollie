@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <table id="myTable" class="table">
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Group Members') }}</th>
            <th>{{ __('Add Member') }}</th>
            <th>{{ __('Delete') }}</th>
        </tr>
        @foreach($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td>{{ $group->getFormattedMembers() }}</td>
                <td>
                    <div id="userDropdown{{ $group->id }}">
                        <input type="text" placeholder="{{ __('Search...') }}" id="findUser{{ $group->id }}" onkeyup="myFunction({{ $group->id }})">
                    </div>

                    <p id="errorMessage{{ $group->id }}"></p>
                </td>
                <td>
                    <form method="POST" action="{{ route('group.delete', app()->getLocale()) }}">
                        @csrf
                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                        <div class="form-group">
                            <input type="submit" class="btn btn-danger delete-account" value="{{ __('Delete') }}">
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
        </table>
        <button id='myButton' class='btn' onclick="addGroup()">{{ __('New Group') }}</button>

        <script>
            function addGroup(){
                var table = document.getElementById("myTable");
                var row = table.insertRow(-1);
                var cell1 = row.insertCell(0);
                cell1.innerHTML = '<form action="{{ route("groups", app()->getLocale()) }}" method="POST">@csrf <input type="text" name="groupname" placeholder="{{ __('Group Name') }}"> <input type="submit" name="submit" value="{{ __('Create') }}"></form>';
                document.getElementById("myButton").disabled = true;
            }

            function myFunction(id){
                            $(document).ready(function(){
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('users', app()->getLocale()) }}",
                                    dataType: 'text',
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                    data: {
                                        'filter': $("#findUser"+id).val(),
                                        '_token': "{{ csrf_token() }}"
                                    },

                                    success: function (data) {
                                        $(".user"+id).remove();
                                        var o = JSON.parse(data);
                                        if(o.length <= 0){
                                            $("#errorMessage"+id).html("Could not find users with a name like this...");
                                        }else{
                                            $("#errorMessage"+id).html("");
                                        }
                                        var dd = document.getElementById("userDropdown"+id);
                                        for(var i = 0; i < o.length; i++){
                                            dd.innerHTML += '<form class="user' + id + '" method="POST" action="{{ route("group.addmember", app()->getLocale()) }}"> @csrf <input type="hidden" name="group_id" value="' + id + '"> <input type="hidden" name="user_id" value="' + o[i].id + '"> <input type="submit" value="' + o[i].name + '"> </form>';
                                        }
                                    },
                                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                                        $("#errorMessage").html("Could not find users with a name like this...");
                                    }
                                });
                            });
                        }
        </script>

        @if($errors->any())
            {{$errors->first()}}
        @endif
    </div>
</div>
@endsection