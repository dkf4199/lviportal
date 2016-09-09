<?php
session_start();
// Include the database sessions file:
// The file starts the session.
//require('includes/db_sessions.inc.php');
// If no session variable exists, redirect the user:
if ( !isset($_SESSION['driver_agent']) OR ($_SESSION['driver_agent'] != md5($_SERVER['HTTP_USER_AGENT'])) ) {

	require_once ('./includes/phpfunctions.php');
	$url = absolute_url('./driver-login');
	//javascript redirect using window.location
	echo '<script language="Javascript">';
	echo 'window.location="' . $url . '"';
	echo '</script>';
	exit();
	
} else { // Cancel the session.

	$_SESSION = array(); // Clear the variables.
	session_destroy(); // Destroy the session itself.
	setcookie ('PHPSESSID', '', time()-3600, '/', '', 0, 0); // Destroy the cookie.
	
	//session_destroy();
	//Reset the timezone to mine....because I live here on west coast
	date_default_timezone_set('America/Los_Angeles');
}
?>

<?php
	include('./includes/html/main_header.php');
?>

<!-- Main content -->

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">LVI Portal</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-sm-4 col-md-4">
			<!-- <div class="panel panel-default">
				<div class="panel-heading">
					Default Panel
				</div>
				<div class="panel-body">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
				</div>
				<div class="panel-footer">
					Panel Footer
				</div>
			</div> -->
		</div>   <!-- /.col-md-4 -->
		
		<div class="col-sm-4 col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Logout
				</div>
				<div class="panel-body">
					<p>You are now logged out.</p>
				</div>
				
			</div>
		</div>   <!-- /.col-lg-4 -->
		
		<div class="col-sm-4 col-md-4">
			<!-- <div class="panel panel-success">
				<div class="panel-heading">
					Success Panel
				</div>
				<div class="panel-body">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
				</div>
				<div class="panel-footer">
					Panel Footer
				</div>
			</div> -->
		</div>    <!-- /.col-lg-4 -->
		
	</div>    <!-- /.row -->

<?php include('./includes/html/footer.html'); ?>