@if(!Session::get('user_id'))
<div class="navbar">
	<div class="navbar-inner">

		<a id="#">Logo Placeholder</a>

		<ul class="nav">
			<li><a href="home">Home</a></li>

			<li><a href="login">Login</a></li>
			<li><a href="register">Register</a></li>


		</ul>
	</div>
</div>
@else 
	@if(Session::get('role') == 0)
	<div class="navbar">
		<div class="navbar-inner">

			<a id="#">Logo Placeholder</a>

			<ul class="nav">
				<li><a href="home">Home</a></li>

				<li><a href="profile">User Profile</a></li>
				<li><a href="processLogout">Logout</a></li>

			</ul>
		</div>
	</div>
	@else
		<div class="navbar">
		<div class="navbar-inner">

			<a id="#">Logo Placeholder</a>

			<ul class="nav">
				<li><a href="home">Home</a></li>

				<li><a href="admin">Admin</a></li>
				<li><a href="profile">User Profile</a></li>
				<li><a href="processLogout">Logout</a></li>

			</ul>
		</div>
	</div>
	@endif 

@endif
