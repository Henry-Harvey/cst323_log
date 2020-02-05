<!-- This view shows the results of a failed login. The error message is received from the login method in the account controller -->
@extends('layouts.appmasterLoggedOut')
@section('title', 'Error')

@section('content')
	<h2>Login Failed</h2>
	<!-- $errorMsg comes from AccountController.onLogin() -->
	<p>{{$errorMsg}}</p>
<br>
@endsection