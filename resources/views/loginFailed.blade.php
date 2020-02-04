@extends('layouts.appmasterLoggedOut')
@section('title', 'Error')

@section('content')
	<h2>Login Failed</h2>
	<p>{{$errorMsg}}</p>
<br>
@endsection