@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('My Dollies') }}</div>
                <div class="card-body">
                    <form id="filterform" action="{{ route('filter', app()->getLocale()) }}" method="POST">
                        @csrf
                        <input type="text" name="search" placeholder="{{ __('Search') }}" @if(!empty($search)) value="{{ $search }}" @endif>
                        <select name="filter" onchange="event.preventDefault(); document.getElementById('filterform').submit();">
                            <option value="outgoing" @if($filter == "outgoing") selected @endif>{{ __('Outgoing') }}</option>
                            <option value="incoming" @if($filter == "incoming") selected @endif>{{ __('Incoming') }}</option>
                        </select>
                    </form>
                    <table class="table">
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            @if($filter == "incoming")
                                <th>{{ __('From') }}</th>
                            @endif
                            <th>{{ __('Currency') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Created at') }}</th>
                            @if($filter == "incoming")
                                <th>{{ __('Paid') }}</th>
                            @else
                                <th>{{ __('Copy Link') }}</th>
                                <th>{{ __('Delete') }}</th>
                            @endif
                        </tr>
                        @foreach($dollies as $dollie)
                        <?php if(empty($_POST["search"]) || (!empty($_POST['search']) && $dollie->searchRelevant($_POST["search"]))){?>
                            <style>
                                .clickable-row2:hover {
                                    background-color: #d8d8d8;
                                }
                            </style>
                            <tr class="clickable-row2">
                                <form id="form{{ $dollie->id }}" action="{{ route('dollie.show', app()->getLocale()) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="dollie_id" value="{{ $dollie->id }}">
                                </form>
                                <td id="{{ $dollie->id }}" class="clickable-row">{{ $dollie->name }}</td>
                                <td id="{{ $dollie->id }}" class="clickable-row">{{ $dollie->description }}</td>
                                @if($filter == "incoming")
                                    <td>{{ $dollie->user->name }}</td>
                                @endif
                                <td id="{{ $dollie->id }}" class="clickable-row">{{ $dollie->currency }}</td>
                                <td id="{{ $dollie->id }}" class="clickable-row">{{ $dollie->amount }}</td>
                                <td id="{{ $dollie->id }}" class="clickable-row"><?php $date = new DateTime($dollie->dollie_date); echo $date->format("d-m-Y"); ?></td>
                                @if($filter == "incoming")
                                    @foreach($dollie->payments as $payment)
                                        @if($payment->payer_id == Illuminate\Support\Facades\Auth::user()->id)
                                            @if($payment->payed)
                                                <td id="{{ $dollie->id }}" class="clickable-row">{{ __('Paid') }}</td>
                                            @else
                                                <td>
                                                    <form method="POST" action="{{ route('prepare', app()->getLocale()) }}">
                                                        @csrf
                                                        <input type="hidden" name="dollie_id" value="{{ $payment->dollie_id }}">
                                                        <div class="form-group">
                                                            <input type="submit" class="btn" value="{{ __('Pay') }}">
                                                        </div>
                                                    </form>
                                                </td>
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    <td><input type="button" value ="{{ __('Copy') }}" class="btn" onclick="event.preventDefault(); copyLink({{ $dollie->id }})"></td>
                                    <td>
                                        <form method="POST" action="{{ route('dollie.delete', app()->getLocale()) }}">
                                            @csrf
                                            <input type="hidden" name="dollie_id" value="{{ $dollie->id }}">
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-danger delete-account" value="{{ __('Delete') }}">
                                            </div>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        <?php } ?>
                        @endforeach
                    </table>

                    <script>
                        jQuery(document).ready(function($) {
                            $(".clickable-row").click(function() {
                                var id = $(this).attr("id");
                                document.getElementById("form"+id).submit();
                                return false;
                            });
                        });
                    </script>

                    @if($errors->any())
                        {{$errors->first()}}
                    @endif

                    <input type="hidden" value="none" id="myInput">
                        
                    <script>
                        function copyLink(id){
                            var tempInput = document.createElement("input");
                            tempInput.style = "position: absolute; left: -1000px; top: -1000px";
                            tempInput.value = "{{ route('payment.link', app()->getLocale()) }}/?dollie_id=" + id;
                            document.body.appendChild(tempInput);
                            tempInput.select();
                            document.execCommand("copy");
                            document.body.removeChild(tempInput);
                            
                            if (!e) var e = window.event;
                            e.cancelBubble = true;
                            if (e.stopPropagation) e.stopPropagation();
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
