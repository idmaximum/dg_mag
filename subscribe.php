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
	  $email_news = trim(mosGetParam($_FORM,'email_news',''));
	  
	  $qryChk = "select * from $_Config_table[mail_subscribe] where email = '$email_news'";
	  $rsChk = $DB->Execute($qryChk);
	  if( $rsChk->RecordCount() <= 0 ){
		  $qryInsRegis = "insert into $_Config_table[mail_subscribe]( email, signup_date) 
						  values ( 
							  $DB->qstr('$email_news'),
							  $DB->DBTimeStamp('$now')
						  )";
		  $DB->Execute($qryInsRegis);
		  FU::alert_mesg("ขอบคุณค่ะ ระบบได้รับข้อมูลของคุณเรียบร้อยแล้ว");
	  }else {
		  FU::alert_mesg("อีเมลนี้ไม่สามารถใช้ได้เพราะมีอยู่ในระบบแล้วค่ะ");
	   }
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
  
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="13%"><img src="idmax/images/png/glyphicons_120_message_full.png" width="24" height="28"> &nbsp;</td>
    <td width="87%" style="padding-top:16px"><span class="txtBlack18 font-gentfont">SUBSCRIBE E-NEWSLETTERS</span>&nbsp;</td>
  </tr>
</table>
<form role="form"id="formID"  name="formID"class="formular"  method="post" action=""style="margin-top:15px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><input name="email_news" type="text" class="validate[required,custom[email]] form-control" id="email" placeholder="E-mail" style="width:265px; " ></td>
      </tr>
      <tr>
        <td valign="top" style="padding-top:10px"> <input class="btn btn-primary  btn-block "  type="submit" value="Subscribe" style="width:290px"></p>
          <input name="url" type="text"  class="url"/>
          <input name="action" type="hidden" value="mail_signup_news" /></td>
      </tr>
    </table>
    
  </form>
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