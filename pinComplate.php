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
	
	$item_id = mosGetParam($_FORM,'item_id','');
	$qryDetail = "select * from $_Config_table[item] where item_id = '$item_id'";
	$rsDetail = $DB->Execute($qryDetail );
	$detailitem = $rsDetail->FetchRow($rsDetail);	
	
?><!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PIN</title>

<link rel="stylesheet"href="js/bootstrap/css/bootstrap_2.css" >
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/font.css" />
 
<style type="text/css">
body {
	background-image: url(images/bg-pin-to-item.png);
	background-repeat: repeat-x;
	background-position: left top; 
}
.main-pin {
	padding: 50px;
	text-align:center
}
.main-pin   p {
	padding: 10px;
}
.imgCrop img {
	background-color: #FFF;
	padding: 5px;
	border: 1px solid #fcfcfc;
	box-shadow: 1px 1px 1px #ccc;
}
</style>
</head>

<body>
<div class="main-pin">
<div class="imgCrop"><img src="uploads/item/<?php echo $detailitem["item_image_thumb"];?>" width="102"   ></div>
<p>&nbsp;</p> 
<p><img src="images/icon-complate.gif" width="106" height="108"></p>
<p class="txtpink14">เก็บเข้าบอร์ดแล้วเรียบร้อย</p>
</div> 
<script type="text/javascript" src="js/jquery-1.8.js"></script> 
<script type="text/javascript" src="js/jquery.corner.js"></script> 
<script>
  jQuery(document).ready(function(){
	  // binds form submission and fields to the validation engine
	  jQuery(".imgCrop img").corner();
	 
  });  
</script>
</body>
</html>