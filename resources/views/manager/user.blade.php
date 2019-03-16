@extends('manager.header')


@section('activity')
    #manager {
    color: #06C;
    }
@endsection
@section('body')
    <div id="app">
        <show-user></show-user>
    </div>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection
