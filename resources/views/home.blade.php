@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Mijn Dollies</div>
                <div class="card-body">
                    <form id="filterform" action="/" method="POST">
                        @csrf
                        <input type="text" name="search" placeholder="Search" @if(!empty($search)) value="{{ $search }}" @endif>
                        <select name="filter" onchange="event.preventDefault(); document.getElementById('filterform').submit();">
                            <option value="outgoing" @if($filter == "outgoing") selected @endif>Outgoing</option>
                            <option value="incoming" @if($filter == "incoming") selected @endif>Incoming</option>
                        </select>
                    </form>
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            @if($filter == "incoming")
                                <th>From</th>
                            @endif
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Created At</th>
                            @if($filter == "incoming")
                                <th>Payed</th>
                            @else
                                <th>Copy Link</th>
                                <th>Delete</th>
                            @endif
                        </tr>
                        @foreach($dollies as $dollie)
                        <?php if(empty($_POST["search"]) || (!empty($_POST['search']) && $dollie->searchRelevant($_POST["search"]))){?>
                            <tr>
                                <td>{{ $dollie->name }}</td>
                                <td>{{ $dollie->description }}</td>
                                @if($filter == "incoming")
                                    <td>{{ $dollie->user->name }}</td>
                                @endif
                                <td>{{ $dollie->currency }}</td>
                                <td>{{ $dollie->amount }}</td>
                                <td><?php $date = new DateTime($dollie->created_at); echo $date->format("d-m-Y"); ?></td>
                                @if($filter == "incoming")
                                    @foreach($dollie->payments as $payment)
                                        @if($payment->payer_id == Illuminate\Support\Facades\Auth::user()->id)
                                            @if($payment->payed)
                                                <td>Payed</td>
                                            @else
                                                <td>
                                                    <form method="POST" action="/payment">
                                                        @csrf
                                                        <input type="hidden" name="dollie_id" value="{{ $payment->dollie_id }}">
                                                        <div class="form-group">
                                                            <input type="submit" class="btn" value="Pay">
                                                        </div>
                                                    </form>
                                                </td>
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    <td><button class="btn" onclick="copyLink({{ $dollie->id }})">Copy</button></td>
                                    <td>
                                        <form method="POST" action="{{ route('dollie.delete') }}">
                                            @csrf
                                            <input type="hidden" name="dollie_id" value="{{ $dollie->id }}">
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-danger delete-account" value="Delete">
                                            </div>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        <?php } ?>
                        @endforeach
                    </table>
                    @if($errors->any())
                        {{$errors->first()}}
                    @endif

                    <input type="hidden" value="none" id="myInput">

                    <script>
                        function copyLink(id){
                            var tempInput = document.createElement("input");
                            tempInput.style = "position: absolute; left: -1000px; top: -1000px";
                            tempInput.value = "dollie.local/payment/?dollie_id=" + id;
                            document.body.appendChild(tempInput);
                            tempInput.select();
                            document.execCommand("copy");
                            document.body.removeChild(tempInput);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
