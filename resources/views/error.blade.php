<!-- Pass in: $process, $errorMsg, $back -->
@extends('layouts.appmaster')
@section('title', '{{$process}} Error')

@section('content')
	<h2>{{$process}} Failed</h2>
	<p><a href="{{$back}}">Back</a></p>
@endsection