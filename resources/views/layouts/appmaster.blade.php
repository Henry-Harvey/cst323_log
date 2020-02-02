<html>
<head>
	<title>@yield('title')</title>
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