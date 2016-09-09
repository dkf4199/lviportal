<?php
session_start();
$page_title = "Driver Login";
include('./includes/html/main_header.php');
?>

<?php
	include ('./includes/phpfunctions.php');
	include ('./includes/config.inc.php');
	
	$errors = '';
	//check form submission
	if (isset($_POST['submitted']) && $_POST['submitted'] == "login"){
		
		//DB Connection
		require_once (MYSQL);
		
		//PDO Connection
		$opt = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		$pdo = new PDO(PDO_HOSTSTRING, PDO_USER, PDO_PASS, $opt);
		//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Check the login:  USER and PASS are post vars
		list ($check, $driverdata) = lvi_driver_login($pdo, strtoupper($_POST['driver_id']), $_POST['password']);

		if ($check) { // OK! it's true
		
			//Store the HTTP_USER_AGENT
			$_SESSION['driver_agent'] = md5($_SERVER['HTTP_USER_AGENT']);
			//$data is the list returned from rep_login in phpfunctions.php
			$_SESSION['driver_id'] = $driverdata['driver_id'];
			$_SESSION['driver_firstname'] = $driverdata['first_name'];
			$_SESSION['driver_lastname'] = $driverdata['last_name'];
			$_SESSION['driver_phone'] = $driverdata['phone'];
			$_SESSION['driver_email'] = $driverdata['email'];
			$_SESSION['driver_company'] = $driverdata['company'];
			
			// Redirect to driver-dashboard:
			$url = absolute_url ('driver-dashboard');
			//javascript redirect using window.location - CANT USE HEADER, ALREADY BEEN SENT
			echo '<script language="Javascript">';
			echo 'window.location="' . $url . '"';
			echo '</script>';
			exit(); // Quit the script.
		
		} else { // Unsuccessful
			// Assign $data to $errors for error reporting
			$errors = $driverdata;
		}
					
	}	//end if submitted
?>

<!-- Main content -->       			
<div class="row">
	<div class="col-md-5 col-md-offset-3">
		<div class="login-panel panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Driver Login</h3>
			</div>
			<div class="panel-body">
				<form role="form" name="driver_login" action="./driver-login" method="POST">
					<fieldset>
						<div class="form-group">
							<label for="driver_id">Driver ID:</label>	
							<input class="form-control" name="driver_id" type="text" autofocus
								value="<?php if (isset($_POST['driver_id'])) echo $_POST['driver_id']; ?>" />
						</div>
						<div class="form-group">
							<label for="password">Password:</label>	
							<input class="form-control" name="password" type="password" 
								value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>" />
						</div>
						
						<!-- Change this to a button or input when using this as a form -->
						<!--<a href="index.html" class="btn btn-lg btn-primary btn-block">Login</a>-->
						<input type="hidden" name="submitted" value="login" />
						<input type="submit" class="btn btn-lg btn-primary btn-block" value="Login" />
					</fieldset>
					
				</form>
				<div id="messages">
					<?php
						//Display and error messages
						if (!empty($errors)) {
							echo 'ERROR:<br />';
							foreach ($errors as $msg) {
								echo " - $msg<br />\n";
							}
						}
					?>
				</div>
			</div> <!-- /#panel-body -->
		</div> <!-- /#login-panel panel panel-default -->
	</div> <!-- /#col-md-4 -->

</div>  <!-- /.row -->

<?php include('./includes/html/footer.html'); ?>
</body>
</html>
