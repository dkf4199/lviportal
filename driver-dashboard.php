<?php
session_start();
// If no session variable exists, redirect the user:
if ( !isset($_SESSION['driver_agent']) OR ($_SESSION['driver_agent'] != md5($_SERVER['HTTP_USER_AGENT'])) ) {
	require_once ('./includes/phpfunctions.php');
	$url = absolute_url('./driver-login');
	//javascript redirect using window.location
	echo '<script language="Javascript">';
	echo 'window.location="' . $url . '"';
	echo '</script>';
	exit();
	
}
$page_title = "Driver Dashboard";
include('./includes/html/header_driver_loggedin.php');
?>

<?php
	//PROCESSING CODE
?>
<!-- Main Content -->
<div class="row">
	<p>Welcome <?php echo $_SESSION['driver_firstname'].' '.$_SESSION['driver_lastname'];?></p>
</div>

<?php include('./includes/html/footer.html'); ?>
</body>
</html>
