<div class="container">
	<h2>Login</h2>

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
		
		<div class="form-group">
			<input type="hidden" class="form-control" id="role"
				value=1 name="role">
		</div>

		<button type="submit" class="btn btn-dark">Submit</button>

	</form>
	
	<a href="register">Register</a>

</div>