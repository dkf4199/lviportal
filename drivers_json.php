<?php
	//Produce JSON array for Driver Lookup in AngularJS
	
	//Get Data from drivers table first
	//include ('./includes/config.inc.php');
	
	//PDO Connection - Already Set....
	/*$opt = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];
	$pdo = new PDO(PDO_HOSTSTRING, PDO_USER, PDO_PASS, $opt);
	//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	*/
	//Get rows and create the following JSON structure
	/*
		var employees = [
			{"firstName":"John", "lastName":"Doe"},
			{"firstName":"Anna", "lastName":"Smith"},
			{"firstName":"Peter","lastName": "Jones"}
		];
	*/
	//$json_var = "var drivers = [";
	//foreach($pdo->query('SELECT driver_id, first_name, last_name FROM drivers ORDER BY driver_id') as $row) {
		//echo $row['driver_id'].' '.$row['first_name'].' '.$row['last_name'].'<br />';
	//	$json_var .= '{"driverid":"'.$row['driver_id'].'", "firstname":"'.$row['first_name'].'", "lastname":"'.$row['last_name'].'"},';
	//}
	
	//ASSOCIATIVE ARRAY METHOD
	$jsonArray = array();
	$rowArray = array();
	foreach($pdo->query('SELECT driver_id, first_name, last_name FROM drivers ORDER BY driver_id') as $row) {
		//echo $row['driver_id'].' '.$row['first_name'].' '.$row['last_name'].'<br />';
		//$json_var .= '{"driverid":"'.$row['driver_id'].'", "firstname":"'.$row['first_name'].'", "lastname":"'.$row['last_name'].'"},';
		$rowArray ['DriverId'] = $row['driver_id'];
		$rowArray ['FirstName'] = $row['first_name'];
		$rowArray ['LastName'] = $row['last_name'];
		
		//Push to jsonArray
		array_push($jsonArray,$rowArray);
	}
	
	$js_out = "<script>\n";
	$js_out .= "var drivers = ".json_encode($jsonArray).";\n";
	$js_out .= "</script>";
	echo $js_out;
	//header('Content-Type: application/json');
	//echo json_encode($jsonArray);
	
?>