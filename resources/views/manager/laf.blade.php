@extends('manager.header')

@section('activity')
    #post {
        color: #06C;
    }
@endsection
@section('body')
    <div id="app">
        <show-post></show-post>
    </div>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection
