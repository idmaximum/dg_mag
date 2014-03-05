<?php
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet"href="js/bootstrap/css/bootstrap_2.css" >
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/font.css" />
<link rel="stylesheet" href="js/formValidator/css/validationEngine.jquery.css" type="text/css"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<style type="text/css">
@font-face {
	font-family: 'HelveticaNeue-Bold';
	src: url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.eot?') format('eot'), url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.woff') format('woff'), url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.ttf') format('truetype'), url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.svg#HelveticaNeue-Bold') format('svg');
}

.font-gentfont {
	font-family: "HelveticaNeue-Bold";
	direction: ltr;
}
body {background-color: #efefef;}
.main-buynow {
	padding: 20px;
	margin: auto;
	width: 560px;
}
.form-control {
	height: 24px
}
.formError {
	left: 200px !important;
}
</style>
</head>

<body>
<div class="main-buynow">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:8px">
    <tr>
      <td width="7%"><img src="idmax/images/png/glyphicons_202_shopping_cart.png" width="26" height="23"> &nbsp;</td>
      <td width="93%" style="padding-top:16px"><span class="txtBlack18 font-gentfont">Buy Now</span></td>
    </tr>
  </table>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <p  style="text-align:center"><img src="images/ic-complate.png" width="108" height="109"></p><br />

   <p   style="text-align:center"class="txtpink14">ท่านได้ทำการสั่งซื้อสินค้าเรียบร้อยแล้วค่ะ</p>
</div>
<script type="text/javascript" src="js/jquery-1.8.js"></script> 
<script src="js/formValidator/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/formValidator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
<script>
  jQuery(document).ready(function(){
	  // binds form submission and fields to the validation engine
	  jQuery("#formID").validationEngine(); 
	  
  });
</script>
</body>
</html>