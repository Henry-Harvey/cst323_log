@extends('layouts.appmaster') 
@section('title', 'Home')

@section('content')
<div class="container">
	<h2>Home</h2>
	<?php
if (! Session::get('user_id')) {
    echo "You must be logged in to view this page";
    exit();
}
?>
	<p>You have successfully logged in!</p>

</div>
@endsection
