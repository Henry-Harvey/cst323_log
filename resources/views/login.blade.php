@extends('layouts.appmaster') 
@section('title', 'Account Login')

@section('content')
<h2>Login Form</h2>

<form action="processLogin" method="POST">
		<input type="hidden" name="_token" value="<?php echo csrf_token()?>" />

		<div class="form-group">
			<label for="username">Username</label> <input type="text"
				class="form-control" id="username" placeholder="Username"
				name="username">
		</div>

		<div class="form-group">
			<label for="password">Password</label> <input type="text"
				class="form-control" id="password" placeholder="Password"
				name="password">
		</div>

		<button type="submit" class="btn btn-dark">Submit</button>

	</form>
	
	<a href="register">Register</a>

<a href="login">Login</a>
@endsection
