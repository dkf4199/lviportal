<?php
/**************************************/
/*  FUNCTIONS FOR EAPCRM.COM  */
/**************************************/

//  ABSOLUTE_URL()
//*********************
function absolute_url ($page = 'index.php'){

	//url is http://+hostname+currentdirectory

	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

	//remove trailing slashes

	$url = rtrim($url, '/\\');

	//add page to redirect to
	$url .= '/' . $page;

	return $url;

}  //end absolute_url()

//This function separates the extension from the rest of a file name and returns it 
 function get_file_extension($filename) { 
	 $filename = strtolower($filename); 
	 $exts = explode(".", $filename); 
	 $n = count($exts)-1; 
	 $exts = $exts[$n]; 
	 return $exts; 
 } 

 /* (yyyy-mm-dd to mm/dd/yyyy)*/
function mysql_date_to_display($dt){
		$display_dt = substr($dt,5,2).'/'.substr($dt,8,2).'/'.substr($dt,0,4);
		return $display_dt;
}

/*(yyyy-mm-dd to mm-dd-yyyy)*/
function display_date_to_mysql($dt){
		$mysql_dt = substr($dt,6,4).'-'.substr($dt,0,2).'-'.substr($dt,3,2);
		return $mysql_dt;
}

//  VALID CREDIT CARD NUMBER
//***************************
function validateCreditcard_number($credit_card_number)
    {
        $firstnumber = substr($credit_card_number, 0, 1);

        switch ($firstnumber)
        {
            case 3:
                if (!preg_match('/^3\d{3}[ \-]?\d{6}[ \-]?\d{5}$/', $credit_card_number))
                {
                    return false;
                }
                break;
            case 4:
                if (!preg_match('/^4\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number))
                {
                    return false;
                }
                break;
            case 5:
                if (!preg_match('/^5\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number))
                {
                    return false;
                }
                break;
            case 6:
                if (!preg_match('/^6011[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number))
                {
                    return false;
                }
                break;
            default:
                return false;
        }

        $credit_card_number = str_replace('-', '', $credit_card_number);
        $map = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 2, 4, 6, 8, 1, 3, 5, 7, 9);
        $sum = 0;
        $last = strlen($credit_card_number) - 1;
        for ($i = 0; $i <= $last; $i++)
        {
            $sum += $map[$credit_card_number[$last - $i] + ($i & 1) * 10];
        }
        if ($sum % 10 != 0)
        {
            return false;
        }

        return true;
    }

//  VALID CCV
//***************************
function validateCVV($cardNumber, $cvv)
    {
        $firstnumber = (int) substr($cardNumber, 0, 1);
        if ($firstnumber === 3)
        {
            if (!preg_match("/^\d{4}$/", $cvv))
            {
                return false;
            }
        }
        else if (!preg_match("/^\d{3}$/", $cvv))
        {
            return false;
        }
        return true;
    }

//  VALID_FIRSTNAME()
//********************
function valid_firstname($name){

	if (!preg_match("/^[A-Za-z]+$/", trim($name))) {
		return false;
	} else {
		return true;
	}
}

//  VALID_LASTAME()
//*********************
function valid_lastname($name){

	if (!preg_match("/^[A-Za-z -]+$/", trim($name))) {
		return false;
	} else {
		return true;
	}

}

//  VALID_EMAIL()
//**********************
function valid_email($em){

	if (!preg_match("/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/", trim($em))) {
		return false;
	} else {
		return true;
	}

}

//  VALID_PHONE()
//*******************************
//second parm is the part of the phone
//number to validate
function valid_phone($phone, $part){

	if ($part == "area" || $part == "prefix"){
		if (!preg_match("/^[0-9]{3}$/", trim($phone))){
			return false;
		} else {
			return true;
		}
	} elseif ($part == "suffix"){
		if (!preg_match("/^[0-9]{4}$/", trim($phone))){
			return false;
		}else {
			return true;
		}
	}
}

function status_desc($status = ''){
	
	//status vals are V, N, P, B
	$status_desc = '';
	switch ($status) {
		case 'V':
			$status_desc = 'Verified';
			break;
		case 'N':
			$status_desc = 'Not Reachable';
			break;
		case 'P':
			$status_desc = 'Pending';
			break;
		case 'B':
			$status_desc = 'Bad Lead';
			break;
		default:
			$status_desc = 'Unknown';
			break;
	}
	return $status_desc;
}


/*   LVI_DRIVER_LOGIN()
//****************************************************************
/* This function validates the staff login data (the email and password).
 * If both are present, the database is queried.
 * The function requires a database connection.
 * The function returns an array of information, including:
 * - a TRUE/FALSE variable indicating success
 * - an array of either errors or the database result
 */
function lvi_driver_login($pdo, $uname = '', $pass = '') {

	$errors = array(); // Initialize error array.
	
	// Validate the Username:
	if (empty($uname)) {
		$errors[] = 'Enter your Username.';
	} else {
		$un = $uname;
	}
	
	// Validate the password:
	if (empty($pass)) {
		$errors[] = 'Enter your password.';
	} else {
		$pwd = $pass;
	}

	if (empty($errors)) { // If everything's OK.

		// Retrieve the company_name and uniqueid using login credentials:
		$q = $pdo->prepare("SELECT b.driver_id, b.first_name, b.last_name,  
					b.email, b.phone, b.company
				FROM drivers_login a INNER JOIN drivers b ON a.driver_id = b.driver_id
				WHERE a.driver_id = ? AND a.password = ?");
		$q->execute([$un, $pwd]);
		$resultSet = $q->fetch();
		if ($resultSet) { // here! as simple as that
			// Return true and the record:
			return array(true, $resultSet);	
		} else { // Not a match!
			$errors[] = 'Username/Password entered do not match those on file.';
			//$errors[] = mysqli_error($dbc);
		}
		
	} // End of empty($errors) IF.
	
	// Return false and the errors:
	return array(false, $errors);

} // End of rep_login() function.


/*   REP_FORGOT_PASS()
//****************************************************************
/* This function retrieves agent's password (the email and password).
 * If both are present, the database is queried.
 * The function requires a database connection.
 * The function returns an array of information, including:
 * - a TRUE/FALSE variable indicating success
 * - an array of either errors or the database result
 */
function rep_forgot_pass($dbc, $email = '', $uid = '') {

	$errors = array(); // Initialize error array.
	$repid = '';
	
	// Validate the Username:
	if (empty($email)) {
		$errors[] = 'Enter your Gmail on record.';
	} else {
		$em = mysqli_real_escape_string($dbc, trim($email));
		/*list($addr, $box) = explode('@',$em);
		if ($box != 'gmail.com'){
			$errors[] = 'Gmail account entered is not a gmail address.';
		}*/
	}
	
	// Validate the username:
	if (empty($uid)) {
		$errors[] = 'Enter your Username.';
	} else {
		$username = mysqli_real_escape_string($dbc, strtoupper(trim($uid)));
		
		//Is this the rep's EMAIL AND VFG ID on record?
		$query = "SELECT rep_id, username FROM reps 
				  WHERE gmail_acct = '$em' 
				  AND username = '$username' LIMIT 1";
		//RUN QUERY
		$rs = @mysqli_query ($dbc, $query);
		if (mysqli_num_rows($rs) != 1) {
			//vfgid is not the one on reps record
			$errors[] = 'Gmail and/or Username not found.';
		} else {
			while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
				$repid = $row['rep_id'];
			}
		}
		mysqli_free_result($rs);
				
	}

	if (empty($errors)) { // If everything's OK.

		// Retrieve the company_name and uniqueid using login credentials:
		$q = "SELECT password 
			    FROM rep_login_id
			   WHERE rep_id='$repid'";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		
		// Check the result:
		if (mysqli_num_rows($r) == 1) {
		
			// Fetch the record:
			$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
			
			// Return true and the record:
			return array(true, $row);
			
		} 
		
	} // End of empty($errors) IF.
	
	// Return false and the errors:
	return array(false, $errors);

} // End of rep_get_password() function.

// IMAGE_SPECS()
//**************************************
//function takes the image name and returns
//the true width and height specs for the image
//from the images/ directory
function image_specs($imagename){

	// images directory path
	$dir = './images';
			
	$imagewh = getimagesize("$dir/$imagename");

	return $imagewh;

} //close image_specs()

/* function getStandardTZname(reptz):
	Takes 1 argument and returns the standard designation
	for timezones. Eastern, Mountain, Pacific, Central..etc
*/
function getStandardTZname($tzone){

	$standard_tz = '';
	foreach($americaTimeZones as $id=>$name){
		if ($tzone == $id){
			return $name;
		}
	}
	return $standard_tz;
}
//**********************************************************************
/* The function takes one argument: a string.
* The function returns a clean version of the string.
* The clean version may be either an empty string or
* just the removal of all newline characters.
*/
function spam_scrubber($value) {

	// List of very bad values:
	$very_bad = array('to:', 
					  'cc:', 
					  'bcc:', 
					  'content-type:', 
					  'mime-version:', 
					  'multipart-mixed:', 
					  'content-transfer-encoding:');
		
	// If any of the very bad strings are in 
	// the submitted value, return an empty string:
	foreach ($very_bad as $v) {
		if (stripos($value, $v) !== false) return '';
	}
		
	// Replace any newline characters with spaces:
	$value = str_replace(array( "\r", "\n", "%0a", "%0d"), ' ', $value);
		
	// Return the value:
	return trim($value);
	
} // End of spam_scrubber() function.


  
