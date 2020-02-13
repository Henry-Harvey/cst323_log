<!-- This layout is for users that are admins  -->
<html>
<head>
	<title>@yield('title')</title>
	@include('layouts._bootstrap')
</head>

<body>
	@include('layouts._navbar')
	@include('layouts._header')
	<div align="center">
		@if(Session::get('sp'))
			@if(Session::get('sp')->getRole() != 0)
				@yield('content')
			@else
		<h2>You must be an admin to view this page</h2>
		@endif
		@else
			<h2>You must be logged in to view this page</h2>
		@endif
	</div>
	@include('layouts._footer')
</body>

</html>