<div class="container">
	<h2>Home</h2>
	<?php
if (! isset($_SESSION['userid'])) {
    echo "You must be logged in to view this page";
    exit();
}
?>
	<p>You have successfully logged in!</p>
	
	<a href="login">Return to login</a>
	
	<!--  <form action="processLogout" method="POST">
		<input type="hidden" name="_token" value="<?php echo csrf_token()?>" />
		<button type="submit" class="btn btn-dark">Submit</button>
	</form>-->

</div>