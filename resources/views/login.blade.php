<!-- Displays a form for loggin in -->
@extends('layouts.appmasterLoggedOut') 
@section('title', 'Account Login')

@section('content')

<h2>Login Form</h2>

<!-- Form takes in login info and uses http post to persist it to the controller -->
<form action="processLogin" method="POST">
		{{ csrf_field() }}

	<div class="form-group">
		<label for="username">Username</label> 
		<input style="width: 30%" type="text" class="form-control" id="username" placeholder="Username" name="username">
		{{$errors->first('username')}}
	</div>

	<div class="form-group">
		<label for="password">Password</label> 
		<input style="width: 30%" type="password" class="form-control" id="password" placeholder="Password" name="password">
		{{$errors->first('password')}}
	</div>

	<button type="submit" class="btn btn-dark">Submit</button>

</form>
	
	Don't have an account? Click <a href="register">here</a> to register

@endsection
