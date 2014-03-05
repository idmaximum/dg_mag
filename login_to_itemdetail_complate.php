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
	
	$action = trim(mosGetParam($_FORM,'action',''));
	$url = trim(mosGetParam($_REQUEST,'url','')); 
   
    $item_id = mosGetParam($_FORM,'item_id','');
	
	 
	 //****************
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>LOGIN</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet"href="js/bootstrap/css/bootstrap_2.css" >
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/font.css" />
<link rel="stylesheet" href="js/formValidator/css/validationEngine.jquery.css" type="text/css"/>
<style type="text/css">
 body {background-color: #efefef;}
#main-login{
	width: 240px;
	margin: auto;
	padding-top: 20px;
}
#formID p {padding-bottom: 10px;}
.formError{ left:70px!important}
</style>
</head> 
<body>
<div id="main-login">  
<p>&nbsp;</p> 
<p>&nbsp;</p> 
<table width="70%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:8px; margin:auto">
    <tr>
      <td width="30%" align="center"><img src="images/5_register_10.gif" width="34" height="33"> &nbsp;</td>
      <td width="70%" style="padding-top:16px"><span class="txtBlack18 font-gentfont">เข้าสู่ระบบ</span></td>
    </tr>
  </table>
<p>&nbsp;</p> 
<p>&nbsp;</p> 
<p>&nbsp;</p> 
<p>&nbsp;</p> 
<p>&nbsp;</p> 
<p style="text-align:center"><img src="images/ic-complate.png" width="108" height="109"></p>
<p  style="text-align:center"class="txtpink14">Login successfully</p>
</div>

<script type="text/javascript" src="js/jquery-1.8.js"></script> 
 
</body>
</html>