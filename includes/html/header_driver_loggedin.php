<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Deron Frederickson Ingenuity2 LLC 2015">

    <title><?php echo $page_title;?></title>

	<!-- Base LVI Portal CSS -->
    <link href="./css/lviportal.css" rel="stylesheet">
	
    <!-- Bootstrap Core CSS -->
    <link href="./bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <!-- jquery ui css SMOOTHNESS-->
	<link rel="stylesheet" href="./css/smoothness/jquery-ui-1.10.3.custom.css">
	
	<!-- MetisMenu CSS -->
    <link href="./bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
	
	<!-- Timepicker CSS -->
	<link rel="stylesheet" type="text/css" href="./css/jquery.timepicker.css" />
  
    <!-- Custom CSS -->
    <link href="./dist/css/sb-admin-2.css" rel="stylesheet">
    
    <!-- Custom Fonts -->
    <link href="./bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<!-- jQuery -->
    <script src="./bower_components/jquery/dist/jquery.min.js"></script>
	
	<!-- TimePicker JS -->
	<script type="text/javascript" src="./js/jquery.timepicker.js"></script>
	
	<!-- ANGULAR -->
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
	
</head>

<body>

	<!-- BEGIN MAIN BODY WRAPPER -->
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
				<img src="./img/logo.jpg" height="50px" width="50px" />
                <a class="navbar-brand" href="index.php">Las Vegas Insider Portal</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li>
					<a href="./driver-dashboard"><i class="fa fa-dashboard fa-fw"></i>Home</a>
				</li>
				<li>
					<a href="./driver-logout"><i class="fa fa-edit fa-fw"></i> Logout</a>
				</li>
				
            </ul>  <!-- /.navbar-top-links -->
        </nav>

        <div id="page-wrapper" style="margin:0">