@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $dollie->name }}</div>
                <div class="card-body">
                    <strong>{{ __('Description:') }}</strong> {{ $dollie->description }}<br>
                    <strong>{{ __('Currency:') }}</strong> {{ $dollie->currency }}<br>
                    <strong>{{ __('Amount:') }}</strong> {{ $dollie->amount }}<br>
                    <strong>{{ __('Bankaccount:') }}</strong> {{ $dollie->account_number }}<br>
                    <strong>{{ __('Dollie date:') }}</strong> {{ $dollie->dollie_date }}<br>

                    @if($dollie->extras() !== null && isset($dollie->extras()->filename))
                        <img src="{{ route('dollie.image', ['filename' => $dollie->extras()->filename]) }}"/>
                    @endif

                    <form action="{{ route('prepare', app()->getLocale()) }}" method="POST">
                        <input type="hidden" name="dollie_id" value="{{ $dollie->id }}">
                        <input class="btn" type="submit" value="__('Delete')">
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