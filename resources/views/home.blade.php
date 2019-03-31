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
                            <tr id="{{ $dollie->id }}" class="clickable-row">
                                <form id="form{{ $dollie->id }}" action="{{ route('dollie.show', app()->getLocale()) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="dollie_id" value="{{ $dollie->id }}">
                                </form>
                                <td>{{ $dollie->name }}</td>
                                <td>{{ $dollie->description }}</td>
                                @if($filter == "incoming")
                                    <td>{{ $dollie->user->name }}</td>
                                @endif
                                <td>{{ $dollie->currency }}</td>
                                <td>{{ $dollie->amount }}</td>
                                <td><?php $date = new DateTime($dollie->dollie_date); echo $date->format("d-m-Y"); ?></td>
                                @if($filter == "incoming")
                                    @foreach($dollie->payments as $payment)
                                        @if($payment->payer_id == Illuminate\Support\Facades\Auth::user()->id)
                                            @if($payment->payed)
                                                <td>{{ __('Paid') }}</td>
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
                                    <td><button class="btn" onclick="copyLink({{ $dollie->id }})">{{ __('Copy') }}</button></td>
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
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
