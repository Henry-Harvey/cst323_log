<!-- This view displays a form that may be filled out and submitted, resulting in a the user logging in and setting the session or failing login -->
@extends('layouts.appmasterLoggedOut') 
@section('title', 'Account Login')

@section('content')

<h2>Login Form</h2>

<!-- Form takes in login info and uses http post to persist it to the controller -->
<form action="processLogin" method="POST">
		{{ csrf_field() }}

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
