<!-- This layout is for views that can be viewed no matter login status or role, i.e. Home & Exception -->
<html>
<head>
	<title>@yield('title')</title>	
	@include('layouts._bootstrap')
</head>

<body>
	@include('layouts._navbar')
	@include('layouts._header')
	<div align="center">
		@yield('content')
	</div>
	@include('layouts._footer')
</body>

</html>