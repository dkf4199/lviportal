<?php #  config.inc.php for lviportal.com
/* This script:
 * - define constants and settings
 * - dictates how errors are handled
 * - defines useful functions
 */
 
// Document who created this site, when, why, etc.


// ********************************** //
// ************ SETTINGS ************ //

// Flag variable for site status:
define('LIVE', FALSE);

// Admin contact address:
//***********************************
define('EMAIL', 'dkf4199@gmail.com');

// Site URL (base for all redirections):
//*******************************************************
#test
define ('BASE_URL', 'http://localhost/lviportal');
#prod
//define ('BASE_URL', 'http://www.lviportal.com');

// Location of the MySQL connection script:
//*************************************************************
#test
define ('MYSQL', '../../lvi_connect.php'); 				// in public_html
define ('MYSQLAJAX', '../../../lvi_connect.php'); 		// in c:/ - from ajax_reports folder
#prod
//define ('MYSQL', '../../../thecontactsystem_connect.php');	// in /

// Adjust the time zone for PHP 5.1 and greater:
date_default_timezone_set ('America/Los_Angeles');

// ************ SETTINGS ************ //

/* PDO CONFIG */
#TEST
define ('PDO_HOSTSTRING', 'mysql:host=localhost;dbname=dkffouon_lviportal;charset=utf8'); 
define ('PDO_USER', 'dkffouon_lviuser'); 
define ('PDO_PASS', 'lviuser^99'); 

#PROD
/*define ('PDO_HOSTSTRING', 'mysql:host=localhost;dbname=dkffouon_lviportal;charset=utf8'); 
define ('PDO_USER', 'dkffouon_lviuser'); 
define ('PDO_PASS', 'lviuser^99');  
*/

// ********************************** //

define ('GWAPI_USER', 'Ingenuity2');
define ('GWAPI_PASS', 'shamus4199');


// ****************************************** //
// ************ ERROR MANAGEMENT ************ //

// Create the error handler:
function my_error_handler ($e_number, $e_message, $e_file, $e_line, $e_vars) {

	// Build the error message.
	$message = "<p>An error occurred in script '$e_file' on line $e_line: $e_message\n<br />";
	
	// Add the date and time:
	$message .= "Date/Time: " . date('n-j-Y H:i:s') . "\n<br />";
	
	// Append $e_vars to the $message:
	$message .= "<pre>" . print_r ($e_vars, 1) . "</pre>\n</p>";
	
	if (!LIVE) { // Development (print the error).
	
		echo '<div class="error">' . $message . '</div><br />';
		
	} else { // Don't show the error:
	
		// Send an email to the admin:
		mail(EMAIL, 'Site Error!', $message, 'From: email@example.com');
		
		// Only print an error message if the error isn't a notice:
		if ($e_number != E_NOTICE) {
			echo '<div class="error">A system error occurred. We apologize for the inconvenience.</div><br />';
		}
	} // End of !LIVE IF.

} // End of my_error_handler() definition.

// Use my error handler.
//set_error_handler ('my_error_handler');

// ************ ERROR MANAGEMENT ************ //
// ****************************************** //

?>
