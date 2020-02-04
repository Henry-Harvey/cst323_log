@extends('layouts.appmasterLoggedOut') 
@section('title', 'Account Login')

@section('content')

<h2>Login Form</h2>

<form action="processLogin" method="POST">
		<input type="hidden" name="_token" value="<?php echo csrf_token()?>" />

		<div class="form-group">
			<label for="username">Username</label> <input style="width: 30%" type="text"
				class="form-control" id="username" placeholder="Username"
				name="username">
		</div>

		<div class="form-group">
			<label for="password">Password</label> <input style="width: 30%" type="text"
				class="form-control" id="password" placeholder="Password"
				name="password">
		</div>

		<button type="submit" class="btn btn-dark">Submit</button>

	</form>
	
	Don't have an account? Click <a href="register">here</a> to register

@endsection
