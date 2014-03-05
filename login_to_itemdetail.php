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
	
	$Fbid = $_GET["id"];
	$FBemail = $_GET["email"];
	$fbName = $_GET["fbName"];
	
	 if($url == ""){# เช็ดดูว่าใช่ spam หรือป่าว ############### Vote	
		if( isset($action) && !empty($action) && $action == "login_member" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){
	  $inc_username = mosGetParam( $_FORM, 'inc_username', '' );
	  $password = mosGetParam( $_FORM, 'inc_password', '' );
	  
	  $password = "idmax".$password."pass";
	    $password = hash('sha256', $password);	
  
	   $qryChk = "select * from $_Config_table[member] 
	  			 where username = '$inc_username' and password = '$password' ";
	  // echo $qryChk;
	  $rsChk = $DB->Execute($qryChk);
		  if (!$result) 	print $DB->ErrorMsg();	
  
			  if( $rsChk->RecordCount()){	
				  $chkuser = $rsChk->FetchRow();	
						  
				  $_SESSION['_CUTOMERID'] = $chkuser["customer_id"];
				  $_SESSION['_USERNAME'] = $chkuser["username"];
				  $_SESSION['_FNAME'] = $chkuser["customer_fname"];
				  $_SESSION['_EMAIL'] = $chkuser["primary_email"];
				  $_SESSION['_LASTACCESS'] = $chkuser["last_access"];
				  
				  $_SESSION['fbID'] = $chkuser["fbID"];
				  $_SESSION['NameDisplay'] = $chkuser["customer_fname"];
				  $_SESSION['customerImg'] = $chkuser["customer_img"];
				  
				  $lastlogin = DT::currentDateTime();
				  
				  $qryUpdate = "update $_Config_table[member] set last_access = $db->DBTimeStamp('$lastlogin') 
				  where customer_id = $chkuser[customer_id]";
				  //echo $qryUpdate;
				  $rs = $DB->Execute( $qryUpdate );
				  
				  $errCode = "refresh";
				   mosRedirect("login_to_itemdetail_complate.php?item_id=$item_id#formComment");
			  }
			  else {
				$errCode = "ErrorReservedWord_login";
				$errBox["status"] = "ERROR";
				$errBox["text"] = "Username or password is wrong <br>Please try again!";
			  }
	  
		}#end if
	 }#end if 
	 
	 //***************
	 if($Fbid != "" && $FBemail != "" && $fbName != ""){
		
		
	 
	  $qryChkUser = "select username from $_Config_table[member] where username = $DB->qstr('$FBemail')";
	  $rsChkUser = $DB->Execute($qryChkUser);	
	  if( $rsChkUser->RecordCount() <= 0 ){
		  $Rip = FU::GetIP();  
		 
		     $qryInsRegis = "insert into $_Config_table[member]( 				
							username,primary_email, fbID, customer_fname,  created_dt,rip ) 
							values (
							$DB->qstr('$FBemail'),
							$DB->qstr('$FBemail'),
							$DB->qstr('$Fbid'),		
							$DB->qstr('$fbName'),		 			 
							now(),'$Rip' )";
			// echo $qryInsRegis; 
			$DB->Execute($qryInsRegis);
				   
	   } #end if member RecordCount
		 
		 	//************
			 $qryChk = "select * from $_Config_table[member] where username = '$FBemail'  ";
			$rsChk = $DB->Execute($qryChk);
			 if( $rsChk->RecordCount()){	
				  $chkuser = $rsChk->FetchRow();	
						  
				  $_SESSION['_CUTOMERID'] = $chkuser["customer_id"];
				  $_SESSION['_USERNAME'] = $chkuser["username"]; 
				  $_SESSION['_EMAIL'] = $chkuser["primary_email"];
				  $_SESSION['fbID'] = $chkuser["fbID"];
				  $_SESSION['NameDisplay'] = $chkuser["customer_fname"];
				  $_SESSION['customerImg'] = $chkuser["customer_img"];
				  
				  $lastlogin = DT::currentDateTime();
				  
				   $qryUpdate = "update $_Config_table[member] set last_access = now() 
								  where customer_id = $chkuser[customer_id]";
				  //echo $qryUpdate;
				  $rs = $DB->Execute( $qryUpdate );
				  
			  }
			//************
			 mosRedirect("login_to_itemdetail_complate.php?item_id=$item_id#formComment");
	 } 
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
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="17%"><img src="images/5_register_10.gif" width="34" height="33"></td>
      <td width="83%" class="txtBlack14"><strong>เข้าสู่ระบบ</strong></td>
    </tr>
    </table>
  <p>&nbsp;</p>
  <?php if( $errCode == "ErrorReservedWord_login" ){ ?>
   <p class="txtRed12" style="padding:10px"><?php echo $errBox["text"]; ?></p>
  <?php } ?>
  <form action="" method="post"id="formID" class="formular">
    <p>
      <input name="inc_username" type="email" class="validate[required,custom[email]] form-control width200" id="email" placeholder="อีเมล">
    </p>
    <div class="txtBlack12" id="chkUser"></div>
    <p>
      <input name="inc_password" type="password" class="validate[required] form-control width200" id="password" placeholder="รหัสผ่าน">
    </p>
    <p>
      <input class="btn btn-primary btn-lg btn-block width200" type="submit" value="เข้าสู่ระบบ" style="margin:auto">
    </p>
    <p>
      <input name="url" type="text" class="url" id="url" />
      <input name="action" type="hidden" value="login_member" />
      <input name="item_id" type="hidden" value="<?php echo $item_id?>" />
    </p>
    <p class="txtBrown12" style="text-align:center"><strong>หรือ</strong></p>
    <p class="txtBrown12" style="text-align:center"><a href="#"onclick="ShowFaceookLogin();"><img src="images/btn-fb.jpg" width="202" height="37"></a></p>
    <div style="background-color:#CCC; height:1px; margin:10px 0"></div>
  
    <p style="text-align:center" class="txtBlack12">  <a href="register.php" target="_parent">สมัครสมาชิก</a> | <a href="#">ลืมรหัสผ่าน</a></p>
    <p>&nbsp;</p>
  </form>
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
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '598574500225155', // App ID
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });
  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>
<script type="text/javascript">
function ShowFaceookLogin(){
	FB.login(function(response) {
		 if (response.authResponse) {
		   	 
			   FB.api('/me', function(response) {
				// console.log('Good to see you, ' + response.name + '.');
				// alert(response.name);
				 window.location = "<?php echo $_Config_live_site?>/login_to_itemdetail.php?id="+response.id+"&email="+response.email+"&fbName="+response.name+"&item_id="+<?php echo $item_id;?>;  
			   });
		 } 
   }, {scope: 'publish_stream,email,user_likes'});
}
</script> 
</body>
</html>