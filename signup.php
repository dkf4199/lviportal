<?php
$page_title = "Driver Signup";
include('./includes/html/main_header.php');
?>

<?php
	//PROCESSING CODE
	include ('./includes/config.inc.php');

	//check form submission
	if (isset($_POST['submitted']) && $_POST['submitted'] == "signup"){
		
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
			
		$errors = array();		//initialize an error array
		$messages = array();
		
		//VALIDATE FIELDS
		//*******************************************************
		//FIRST NAME
		if (empty($_POST['first_name'])){
			$errors[] = 'Please enter your first name.';
		} elseif (!preg_match("/^[A-Za-z]+$/", trim($_POST['first_name']))) {
			$errors[] = 'Your first name contains at least 1 invalid character.';
		} else {
			$fname = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
			$fn = ucwords(strtolower($fname));
		}
		
		//LAST NAME
		if (empty($_POST['last_name'])){
			$errors[] = 'Please enter your last name.';
		} elseif (!preg_match("/^[A-Za-z -]+$/", trim($_POST['last_name']))) {
			$errors[] = 'Your last name contains at least 1 invalid character.';
		} else {
			$lname = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
			$ln = ucwords(strtolower($lname));
		}
		
		//EMAIL
		if (empty($_POST['email'])){
			$errors[] = 'Please enter email.';
		} elseif (!preg_match("/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/", trim($_POST['email']))) {
			$errors[] = 'Email - invalid format.';
		} else {
			$email = strip_tags(strtolower(trim($_POST['email'])));
			
			/*list($addr, $box) = explode('@',$gmail_acct);
			if ($box != 'gmail.com'){
				$errors[] = 'Gmail account entered is not a gmail address.';
			}*/
			
			//IS EMAIL ALREADY IN ANOTHER'S RECORD?
			/*
			$stmt = $pdo->prepare("SELECT email FROM drivers WHERE email = ? ");
			$stmt->execute([$email]);
			$emailExists = $stmt->fetchColumn();
			
			if($emailExists){
				$errors[] = 'Email address already exists in another\'s record.';
			}
			*/
		}
		
		//phone
		if (empty($_POST['phone'])){
			$errors[] = 'Please provide your phone number.';
		} elseif ( preg_match("/^\d{3}[-]\d{3}[-]\d{4}$/", trim($_POST['phone'])) || preg_match("/^\d{10}$/", trim($_POST['phone'])) ) {
			if (strlen(trim($_POST['phone'])) == 10) {	//no dashes ##########
				$formattedphone = trim($_POST['phone']);
				$phone = substr($formattedphone,0,3).'-'.substr($formattedphone,3,3).'-'.substr($formattedphone,6,4);
			}
			if (strlen(trim($_POST['phone'])) == 12) {	//dashes ###-###-####
				$phone = trim($_POST['phone']);
			}	
		} else{
			$errors[] = 'Invalid phone number format. Use ###-###-####.';
		}
		
		//Company Drive For
		if (empty($_POST['optcompany'])){
			$errors[] = 'Please select who you drive for.';
		} else {
			$company = $_POST['optcompany'];
		}
		
		// Referring Driver
		$referring_driver = '';
		if(!empty($_POST['referring_driver'])){
			$referring_driver = strtoupper($_POST['referring_driver']);
		}
		
		// CHECK TO SEE IF DRIVER IS ALREADY SIGNED UP
		// USE FIRSTNAME, LASTNAME, EMAIL, PHONE #
		if ( isset($fn) && isset($ln) && isset($email) && isset($phone) ){
			$stmt = $pdo->prepare("SELECT * FROM drivers
								   WHERE first_name = ?
								   AND last_name = ? 
								   AND email = ? 
								   AND phone = ? ");
			$stmt->execute([$fn, $ln, $email, $phone]);
			$driverExists = $stmt->fetch();
			
			if($driverExists){
				$errors[] = 'Driver already exists in database.';
			}
		}
		//*****************************************************************************************
		
		if (empty($errors)){
		
			//Create the Driver's ID
			$idexists = true;
			do {
				$randnum = mt_rand(1,99999);
				$strnum = strval($randnum);

				switch (strlen($strnum)) {
					case 1:
						$finalnum = '0000'.$strnum;
						break;
					case 2:
						$finalnum = '000'.$strnum;
						break;
					case 3:
						$finalnum = '00'.$strnum;
						break;
					case 4:
						$finalnum = '0'.$strnum;
						break;
					case 5:
						$finalnum = $strnum;
						break;
				}

				//make the rep's id
				$driver_id = substr($fn,0,1).substr($ln,0,1).$finalnum;
				
				//IS DRIVER_ID ALREADY IN DB?
				$stmt = $pdo->prepare("SELECT driver_id FROM drivers WHERE driver_id = ? ");
				$stmt->execute([$driver_id]);
				$userExists = $stmt->fetchColumn();
				
				if ($userExists){
					//keep going
				} else {
					$idexists = false;
					//$messages[] = $driver_id;
				}
				
			} while ($idexists);
			
			//Got the driver id now - INSERT DATA
			try{
				//$pdo->beginTransaction();
				$stmt = $pdo->prepare("INSERT INTO drivers 
					(driver_id, first_name, last_name, email, phone, company, signup_dt, referring_driver) VALUES (?,?,?,?,?,?,NOW(),?)");
				$stmt->execute([$driver_id, $fn, $ln, $email, $phone, $company, $referring_driver]);
				//$last_id = $dbh->lastInsertId();
				if ($stmt->rowCount() == 1){
					$password = "password1";
					$istmt = $pdo->prepare("INSERT INTO drivers_login (driver_id, password) VALUES(?,?)");
					$istmt->execute([$driver_id, $password]);
				}
				$messages[] = 'Driver Added.';
				$messages[] = 'Your ID is: '.$driver_id;
				$messages[] = 'Check your email for details.';
				//Reset Form with unset
				$_POST = array();
				
				//Email ME there was a new signup
				//
				//TURN ON WHEN THIS HITS PRODUCTION
				$headers = "From: no-reply@lviportal.com";
				$to = $email;
				$sub = "Las Vegas Inside Deals Portal: Sign Up.";
				$body = $fn." ".$ln.",\n";
				$body .= "Thank you for signing up at lviportal.com.  Here are your details.\n\n";
				$body .= "Firstname: ".$fn."\n";
				$body .= "Lastname: ".$ln."\n";
				$body .= "Phone: ".$phone."\n";
				$body .= "Email: ".$email."\n";
				$body .= "DRIVER CODE: ".$driver_id."\n\n";
									
				$body = wordwrap($body, 70);
				mail($to, $sub, $body, $headers);
		
			}
			catch(PDOException $ex){
				//$dbh->rollback();
				$messages[] = $ex;
			}
			
			
		}	//close empty($errors)
		
	
	} //close submitted
?>
<!-- Main content -->
<br />
<div class="col-md-6 col-md-offset-2">
	<div class="register-panel panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Register</h3>
		</div>
		<div class="panel-body">
			<form role="form" name="signup_form" action="./signup" method="POST">
			
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<!-- First Name -->
						<div class="form-group">
							<label for="firstname">First Name:</label>	
							<input type="text" name="first_name" class="form-control" id="first_name" 
								value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<!-- Last Name -->
						<div class="form-group">
							<label for="lastname">Last Name:</label>	
							<input type="text" name="last_name" class="form-control" id="last_name" 
								value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<!-- Last Name -->
						<div class="form-group">
							<label for="email">Email:</label>	
							<input type="text" name="email" class="form-control" id="email" 
								value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<!-- Last Name -->
						<div class="form-group">
							<label for="phone">Phone:</label>	
							<input type="text" name="phone" class="form-control" id="phone" 
								value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>" />
						</div>
					</div>
				</div>
				<?php
					$var = ( isset($_POST['optcompany'])) ? $_POST['optcompany'] : '';
					$s_uber = ($var == 'uber') ? 'checked="checked"' : '';
					$s_lyft = ($var == 'lyft') ? 'checked="checked"' : '';
					$s_both = ($var == 'both') ? 'checked="checked"' : '';
				?>
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<label for="">Drive For:</label>
						<div class="radio">
						  <label><input type="radio" name="optcompany" value="uber" <?php echo $s_uber;?> >Uber</label>
						</div>
						<div class="radio">
						  <label><input type="radio" name="optcompany" value="lyft" <?php echo $s_lyft;?> >Lyft</label>
						</div>
						<div class="radio">
						  <label><input type="radio" name="optcompany" value="both" <?php echo $s_both;?> >Both</label>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<!-- Last Name -->
						<div class="form-group">
							<label for="referring_driver">Referring Driver Code:</label>	
								<input type="text" name="referring_driver" class="form-control" id="referring_driver" 
									value="<?php if (isset($_POST['referring_driver'])) echo $_POST['referring_driver']; ?>" />
						</div>
					</div>
				</div>
				
				<!--
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<label for="">Drive For:</label>
						<label class="radio-inline">
							<input type="radio" name="optcompany" value="uber" <?php //echo $s_uber;?> >Uber
						<label class="radio-inline">
							<input type="radio" name="optcompany" value="lyft" <?php //echo $s_lyft;?> >Lyft
						</label>
						<label class="radio-inline">
							<input type="radio" name="optcompany" value="both" <?php //echo $s_both;?> >Both
						</label>
					</div>	
				</div>
				-->
				
				<div class="row">
					<div class="col-sm-4 col-md-4">
						&nbsp;
					</div>
					<div class="col-sm-4 col-md-4">
						<input type="hidden" name="submitted" value="signup" />
						<input type="submit" class="btn btn-primary btn-block" value="Signup" />
					</div>
					<div class="col-sm-4 col-md-4">
						&nbsp;
					</div>	
				</div> <!-- /row -->
				<br />
				
				<div class="row">		
					<div class="col-sm-8 col-md-8 col-md-offset-3">
						<div id="messages">
							<?php
								//Display and error messages
								if (!empty($errors)) {
									echo 'ERRORS:<br />';
									foreach ($errors as $msg) {
										echo " - $msg<br />\n";
									}
								}
								//Display script messages, if any.
								if (!empty($messages)) {
									foreach ($messages as $msg) {
										echo "$msg<br />\n";
									}
								}
							?>
						</div>
					</div>
				</div> <!-- /row -->
			</form>
		</div> <!-- /#panel-body -->
	</div> <!-- /#register-panel -->

</div>  <!-- /.col-md-6 -->
<?php include('./includes/html/footer.html'); ?>
</body>
</html>