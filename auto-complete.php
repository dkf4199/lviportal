<?php
require('constant.php');
require('database.php');

if (!isset($_GET['drivercode'])) {
	die("");
}

$keyword = strtoupper($_GET['drivercode']);
$data = serachForKeyword($keyword);
echo json_encode($data);