
<div class="navbar">
	<div class="navbar-inner">

		<a id="#">Logo Placeholder</a>

		<ul class="nav">
			<li><a href="home">Home</a></li>
			@if(Session::get('user_id'))
				<li><a href="profile">User Profile</a></li>
				
				@if(Session::get('role') != 0)
				<li><a href="admin">Admin</a></li>
				@endif
				
				<li><a href="processLogout">Logout</a></li>
			@else 
			<li><a href="login">Login</a></li>
			<li><a href="register">Register</a></li>
				
			@endif
		</ul>
	</div>
</div>