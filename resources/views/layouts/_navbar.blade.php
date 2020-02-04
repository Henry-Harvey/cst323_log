<div class="navbar-clc">
	<div class="inner-nav">

		<img src="{{URL::asset('image/logo-placeholder.png')}}" alt="No Image Found">

		<ul class="navbar-menu-items">
			<li class="item"><a href="home">Home</a></li>
			@if(Session::get('user_id'))
			<li class="item"><a href="profile">User Profile</a></li>
			
				@if(Session::get('role') != 0)
				<li class="item"><a class="item" href="admin">Admin</a></li>
				@endif
				
				<li class="item"><a href="processLogout">Logout</a></li>
			@else
			<li class="item"><a href="login">Login</a></li>
			<li class="item"><a href="register">Register</a></li>
			@endif
			
		</ul>
	</div>
</div>