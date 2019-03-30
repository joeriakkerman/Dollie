@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <table id="myTable" class="table">
        <tr>
            <th>Name</th>
            <th>Group members</th>
            <th>Add member</th>
            <th>Delete</th>
        </tr>
        @foreach($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td>{{ $group->getFormattedMembers() }}</td>
                <td>
                    <div id="userDropdown{{ $group->id }}">
                        <input type="text" placeholder="Search.." id="findUser{{ $group->id }}" onkeyup="myFunction({{ $group->id }})">
                    </div>

                    <p id="errorMessage{{ $group->id }}"></p>
                </td>
                <td>
                    <form method="POST" action="{{ route('group.delete') }}">
                        @csrf
                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                        <div class="form-group">
                            <input type="submit" class="btn btn-danger delete-account" value="Delete">
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
        </table>
        <button id='myButton' class='btn' onclick="addGroup()">New Group</button>

        <script>
            function addGroup(){
                var table = document.getElementById("myTable");
                var row = table.insertRow(-1);
                var cell1 = row.insertCell(0);
                cell1.innerHTML = '<form action="{{ route("groups") }}" method="POST">@csrf <input type="text" name="groupname" placeholder="Group Name"> <input type="submit" name="submit" value="Create"></form>';
                document.getElementById("myButton").disabled = true;
            }

            function myFunction(id){
                            $(document).ready(function(){
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('users') }}",
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
                                            dd.innerHTML += '<form class="user' + id + '" method="POST" action="{{ route("group.addmember") }}"> @csrf <input type="hidden" name="group_id" value="' + id + '"> <input type="hidden" name="user_id" value="' + o[i].id + '"> <input type="submit" value="' + o[i].name + '"> </form>';
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