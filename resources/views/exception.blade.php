<!-- Displays the error message from an exception in a controller method -->
@extends('layouts.appmaster')
@section('title', 'Error')

@section('content')
	<h2>Error</h2>
	<p>{{$errorMsg}}</p>
<br>
@endsection