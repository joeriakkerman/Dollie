@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}">
@endsection

@section('content')


            <div class="container">
           
           <body>
    {!! $calendar->calendar() !!}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="{{ asset('/js/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('/js/' . app()->getLocale() . '.js') }}"></script>
    {!! $calendar->script() !!}
</body>
            </div>
@endsection 
