<!-- This view shows the error message from a failed controller method -->
@extends('layouts.appmaster')
@section('title', 'Error')

@section('content')
	<h2>Error</h2>
	<p>{{$errorMsg}}</p>
<br>
@endsection