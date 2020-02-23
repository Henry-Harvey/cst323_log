<!-- Displays a message for notifying the user if something didnt work and provides an easy way back -->
<!-- Pass in: $process & $back -->
@extends('layouts.appmaster')
@section('title', '{{$process}} Error')

@section('content')
	<h2>{{$process}} Failed</h2>
	<p><a href="{{$back}}">Back</a></p>
@endsection