@extends('manager.header')


@section('activity')
    #other {
        color: #06C;
    }
@endsection
@section('body')
    <div id="app">
        <show-other></show-other>
    </div>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection
