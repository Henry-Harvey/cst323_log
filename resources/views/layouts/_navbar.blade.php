<!-- This partial view is used to show the navbar for navigating to other pages -->
<div class="navbar-clc">
	<div class="inner-nav">

		<ul class="navbar-menu-items">
			@if(Session::get('sp'))			
			<li class="item"><a>{{Session::get('sp')->getFirst_name()}} | </a></li>
			@endif
		
			<li class="item"><a href="home">Home</a></li>
			
			@if(Session::get('sp'))
			<li class="item"><a href="profile">User Profile</a></li>
			<li class="item"><a class="item" href="jobPostings">Job Postings</a></li>
			
				@if(Session::get('sp')->getRole() != 0)
				<li class="item"><a class="item" href="admin">All Users</a></li>
				@endif
				
				<li class="item"><a href="processLogout">Logout</a></li>
			@else
			<li class="item"><a href="login">Login</a></li>
			<li class="item"><a href="register">Register</a></li>
			@endif
			
		</ul>
	</div>
</div>