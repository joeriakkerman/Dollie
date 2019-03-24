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
                            </tr>
                        <?php } ?>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
