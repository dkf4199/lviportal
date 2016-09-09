<?php
$page_title = "Customer Call In Form";
include('./includes/html/main_header.php');
?>

<?php
	//Processing Section
	include('./includes/selectlists.php');
	include ('./includes/config.inc.php');
	
	//PDO Connection
	$opt = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];
	$pdo = new PDO(PDO_HOSTSTRING, PDO_USER, PDO_PASS, $opt);
	//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	//check form submission
	if (isset($_POST['submitted']) && $_POST['submitted'] == "processorder"){
	
		
			
		$errors = array();		//initialize an error array
		$messages = array();
		
		//VALIDATE FIELDS
		//*******************************************************
		if (isset($_POST['event_time'])){
			$messages[] = $_POST['event_time'];
		}
		
	}
?>


<!-- Main content -->
<br />

<div class="col-md-9 col-md-offset-1">
	<div class="register-panel panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">LV Insider Deal Form</h3>
		</div>
		<div class="panel-body">
			<form role="form" name="signup_form" action="customer_callin.php" method="POST">
			
				<!-- Driver ID -->
				<div class="row">
					<div class="col-sm-4 col-md-4">
						<!-- Driver's ID -->
						<div class="form-group">
							<label for="drivercode">Driver's Code:</label>	
								<input type="text" name="drivercode" class="form-control" id="drivercode"
									value="<?php if (isset($_POST['drivercode'])) echo $_POST['drivercode']; ?>" />
						</div>
					</div>
					<div class="col-sm-4 col-md-4">
						<!-- Driver's ID -->
						<div class="form-group">
							<label for="drivername">Driver Name:</label>	
								<input type="text" name="drivername" class="form-control" id="drivername" value="" READONLY />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-4">
						<div id="results"></div>
					</div>
				</div> <!-- /.row -->
				
				<div class="row">
					<hr />
				</div>
				
				<div class="row">
					<div class="col-sm-4 col-md-4">
						<!-- First Name -->
						<div class="form-group">
							<label for="firstname">First Name:</label>	
								<input type="text" name="first_name" class="form-control" id="first_name" 
									value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" />
						</div>
					</div>
				
					<div class="col-sm-4 col-md-4">
						<!-- Last Name -->
						<div class="form-group">
							<label for="lastname">Last Name:</label>	
								<input type="text" name="last_name" class="form-control" id="last_name" 
									value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-4">
						<!-- Last Name -->
						<div class="form-group">
							<label for="phone">Phone:</label>	
								<input type="text" name="phone" class="form-control" id="phone" 
									value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>" />
						</div>
					</div>
					
				</div> <!-- /.row -->
				
				<div class="row">
					<div class="col-sm-4 col-md-4">
						<!-- Last Name -->
						<div class="form-group">
							<label for="email">Email:</label>	
								<input type="text" name="email" class="form-control" id="email" 
									value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
						</div>
					</div>
				
					<div class="col-sm-4 col-md-4">
						<!-- Last Name -->
						<div class="form-group">
							<label for="hotel">Hotel:</label>	
								<input type="text" name="hotel" class="form-control" id="hotel" 
									value="<?php if (isset($_POST['hotel'])) echo $_POST['hotel']; ?>" />
						</div>
					</div>
					
				</div> <!-- /.row -->
				
				<div class="row">
					<hr />
				</div>
				
				<div class="row">
					<div class="col-sm-3 col-md-3">
						<!-- Show/Event -->
						<div class="form-group">
							<label for="eventname">Show/Event:</label>	
								<input type="text" name="eventname" class="form-control" id="eventname" 
									value="<?php if (isset($_POST['eventname'])) echo $_POST['eventname']; ?>" />
						</div>
					</div>
				
					<div class="col-sm-3 col-md-3">
						<!-- Event Date -->
						<div class="form-group">
							<label for="eventdate">Date:</label>	
								<input type="text" name="eventdate" class="form-control" id="eventdatepicker" 
									value="<?php if (isset($_POST['eventdate'])) echo $_POST['eventdate']; ?>" />
						</div>
					</div>
					
					<div class="col-sm-3 col-md-3">
						<!-- Event Time -->
						<label for="event_time">Event Time:</label>
								<?php
									$selected_et = '';
									if (isset($_POST['event_time'])){
										$selected_et = $_POST['event_time'];
									}							 
								?>
								<select class="form-control" name="event_time" id="event_time">
									<?php
										foreach($calendartime as $id=>$name){
											if($selected_et == $id){
												$sel = 'selected="selected"';
											}
											else{
												$sel = '';
											}
											echo "<option $sel value=\"$id\">$name</option>";
										}
									?>
								</select>
					</div>
					
					<div class="col-sm-3 col-md-3">
						<!-- # Of Guests -->
						<label for="attendees">Guests:</label>
								<?php
									$selected_gu = '';
									if (isset($_POST['attendees'])){
										$selected_gu = $_POST['attendees'];
									}							 
								?>
								<select class="form-control" name="attendees" id="attendees">
									<?php
										foreach($guests_number as $id=>$name){
											if($selected_gu == $id){
												$sel = 'selected="selected"';
											}
											else{
												$sel = '';
											}
											echo "<option $sel value=\"$id\">$name</option>";
										}
									?>
								</select>
					</div>
					
				</div> <!-- /.row -->
				
				
				<div class="row">
					<div class="col-sm-4 col-md-4">
						&nbsp;
					</div>
					<div class="col-sm-4 col-md-4">
						<input type="hidden" name="submitted" value="processorder" />
						<input type="hidden" name="driversid" id="driversid" value="" />
						<input type="submit" class="btn btn-primary btn-block" value="Process Order" />
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

</div>  <!-- /.col-md-9 -->

<?php //include('drivers_json.php');?>

<?php include('./includes/html/footer.html'); ?>

</body>
</html>