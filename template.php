<?php
	ob_start('ob_gzhandler');
	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	require_once( $_Config_absolute_path . "/includes/func.class.php" );
	
	$DB = mosConnectADODB();
	$msObj = new MS($DB);
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $_Config_sitename?></title>
<meta name="description" content="<?php echo $_Config_description?>">
<meta name="keywords" content="<?php echo $_Config_keyword?>">
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet"href="js/bootstrap/css/bootstrap.min.css" > 
<link rel="stylesheet" type="text/css" href="css/font.css" />
<link href="css/screen.css" rel="stylesheet" type="text/css">
<?php include("inc-top-head.php");?>
</head>
<body>
<div id="main">
 <?php include("inc-header.php")?>   
 <div id="content" class="site-content">Content for  id "content" Goes Here</div>
 <?php include("inc-footer.php")?>
</div>
</body>
</html>