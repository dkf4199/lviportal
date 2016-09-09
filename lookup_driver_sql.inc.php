<?php

// We will use PDO to execute database stuff. 
// This will return the connection to the database and set the parameter
// to tell PDO to raise errors when something bad happens
function getDbConnection() {
  //$db = new PDO(DB_DRIVER . ":dbname=" . DB_DATABASE . ";host=" . DB_SERVER . ";charset=utf8", DB_USER, DB_PASSWORD);
  //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
  //return $db;
  $opt = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];
	$pdo = new PDO(PDO_HOSTSTRING, PDO_USER, PDO_PASS, $opt);
	return $pdo;
}

// This is the 'search' function that will return all possible rows starting with the keyword sent by the user
function serachForKeyword($keyword) {
  
    $db = getDbConnection();
    //$stmt = $db->prepare("SELECT driver_id, CONCAT(first_name,' ',last_name) as name FROM `drivers` 
	//					  WHERE driver_id LIKE ? ORDER BY name");
	$stmt = $db->prepare("SELECT driver_id, CONCAT(first_name,' ',last_name) as name FROM `drivers` 
	 					  WHERE driver_id = ? ");
	
    //$keyword = $keyword . '%';
    $stmt->bindParam(1, $keyword, PDO::PARAM_STR, 100);

    $isQueryOk = $stmt->execute();
  
    $results = array();
    
	//ASSOCIATIVE ARRAY METHOD
	$jsonArray = array();
	$rowArray = array();
	
    if ($isQueryOk) {
		//$results = $stmt->fetchAll(PDO::FETCH_COLUMN);
		foreach($stmt as $row){
			$rowArray ['DriverId'] = $row['driver_id'];
			$rowArray ['Name'] = $row['name'];		
			//Push to jsonArray
			array_push($jsonArray,$rowArray);
		}

    } else {
      
      trigger_error('Error executing statement.', E_USER_ERROR);
    }

    $db = null; 

    //return $results;
	return $jsonArray;
}