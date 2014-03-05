<?php 
	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	require_once( $_Config_absolute_path . "/includes/func.class.php" );
	
	#Create Obj
	$DB = mosConnectADODB();
	$msObj = new MS($DB);  
	
	 $action = trim(mosGetParam($_FORM,'action',''));	
     $url = trim(mosGetParam($_FORM,'url','')); 
	  
	 if($url == ""){# เช็ดดูว่าใช่ spam หรือป่าว 
	  if( isset($action) && !empty($action) && $action == "mail_signup_news" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){
	   $now = DT::currentDateTime();
	   $email = trim(mosGetParam($_FORM,'email',''));
	  
		 $qryChkUser = "select * from $_Config_table[member]
		 				 where username = $DB->qstr('$email')";
			$rsChkUser = $DB->Execute($qryChkUser);	
		if( $rsChkUser->RecordCount() > 0 ){			
				
			$newPassword =   FU::alphanumeric_rand(8);
			
			$genPasswordNew = "idmax".$newPassword."pass";
	        $genPasswordNew = hash('sha256', $genPasswordNew);	
			 
			  $qryEditRegis = "update $_Config_table[member] set  
							   password = $DB->qstr('$genPasswordNew')							    
							   where username= '$email'"; 
			  $DB->Execute($qryEditRegis);
			  
			  	//******************
				$msg.="<!doctype html>";
				$msg.="<html>";
				$msg.="<head>";
				$msg.="<meta charset=\"utf-8\">";
				$msg.="<title>Untitled Document</title>";
				$msg.="<style type=\"text/css\">";
				$msg.="body {";
				$msg.="	font-family: Arial, Helvetica, sans-serif;";
				$msg.="	margin:0;";
				$msg.="	padding:0;";
				$msg.="	font-size:13px;";
				$msg.="}";
				$msg.="</style>";
				$msg.="</head>";
				$msg.="<body>";
				$msg.="<p>ท่านสามารถใช้ข้อมูลนี้ลงทะเบียนเข้าสู่ระบบ<br>";
				$msg.="รหัสผ่านใหม่ :  $newPassword</p>";
				$msg.="<p>โดยการคลิกที่ลิ้งค์นี้ <a href=\"$_Config_live_site/login.php\">$_Config_live_site/login.php</a></p>";
				$msg.="</body>";
				$msg.="</html>"; 
				//***************** 
			  
			    $subject = "DG Magazine account";
				$from_name = "DG Magazine";
				$from_email = "noreply@idmaxdev.com";
				
				$to_name = "$email";
				$to_email = $email;
			  
			  	$flagSendMail = FU::send_mail($subject, $msg, $to_name, $to_email, $from_name, $from_email,1 );	
			  
			  FU::alert_mesg("ขอบคุณค่ะ ระบบได้รับข้อมูลของคุณเรียบร้อยแล้ว");
		  }else {
			  FU::alert_mesg("E-mail ที่ท่านระบุ ไม่มีอยู่ในระบบค่ะ");
		   }#chk Email
	 }//end isset($action)*********************** 
   }# end เช็ดดูว่าใช่ spam หรือป่าว  
   
	
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>subscribe</title>
<link rel="stylesheet"href="js/bootstrap/css/bootstrap_2.css" >
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/font.css" />
<link rel="stylesheet" href="js/formValidator/css/validationEngine.jquery.css" type="text/css"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<style type="text/css">

@font-face {
	font-family: 'HelveticaNeue-Bold';
	src: url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.eot?') format('eot'), 
	url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.woff') format('woff'), 
	url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.ttf') format('truetype'),
	url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.svg#HelveticaNeue-Bold') format('svg');
}
.font-gentfont{font-family: "HelveticaNeue-Bold";direction: ltr;}
 body{ background-color:#efefef}
 .main-enews{
	padding: 20px;
	margin: auto;
	width: 320px
}.form-control{ height:24px}
 .formError { left: 60px !important; }
 #email{ height:24px!important; }
</style>
</head>

<body>
<div class="main-enews">
  
  <table width="80%" border="0" cellspacing="0" cellpadding="0" style="margin:auto">
  <tr>
    <td width="21%" valign="bottom"><img src="images/5_register_10.gif" width="34" height="33"> &nbsp;</td>
    <td width="79%" valign="top" style="padding-top:12px"><span class="txtBlack18 font-gentfont">Forget Password</span>&nbsp;</td>
  </tr>
</table>
  <p class="txtBlack13">&nbsp;</p>
  <p class="txtBlack13">&nbsp;</p>
  <p class="txtBlack13">&nbsp;</p>
  <p class="txtBlack13" style="text-align:center">ระบบได้ทำการส่งรหัสผ่านของท่านเข้าสู่อีเมล์เรียบร้อยแล้ว<br />
                   กรุณาเช็คอีเมล์ เพื่อรับรหัสผ่าน
  </p>
</div>

<script type="text/javascript" src="js/jquery-1.8.js"></script>  
<script src="js/formValidator/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/formValidator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
  jQuery(document).ready(function(){ 
	  jQuery("#formID").validationEngine();
	  
  });  
</script>
</body>
</html>