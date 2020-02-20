<!-- This layout is for regular users that are logged in -->
<html>
<head>
	<title>@yield('title')</title>
	@include('layouts._bootstrap')
</head>

<body>
	@include('layouts._navbar')
	<div align="center">
		@if(Session::get('sp'))
			@yield('content')
		@else
			<h2>You must be logged in to view this page</h2>
		@endif
	</div>
	@include('layouts._footer')
</body>

</html>