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
				  mosRedirect("index.php");
			  }
			  else {
			  $errCode = "ErrorReservedWord_login";
			  $errBox["status"] = "ERROR";
			  $errBox["text"] = "Username or password is wrong Please try again!";
			  }
	  
		}#end if
	 }#end if 
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
<link rel="stylesheet" href="js/formValidator/css/validationEngine.jquery.css" type="text/css"/>
<style type="text/css"></style>
<style type="text/css"></style>
<?php include("inc-top-head.php");?>
</head>
<body>
<div id="main">
  <?php  include("inc-header.php")?>
  <div id="content" class="site-content">
    <div class="content-register">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="43%" valign="top"><?php if( $errCode == "ErrorReservedWord" ){ ?>
            <table width="100%" border="0" cellspacing="5" class="txtBlack14" style=" background-color:#FFC">
              <tr>
                <td width="14%" align="center" valign="middle"><span class="headSmGray"><img src="images/close-fancybox.png" alt="" width="36" height="36" style="padding:5px" /></span></td>
                <td width="86%" align="left" valign="middle" style="vertical-align:middle"><span class="txtBlack12"><?php echo $errBox["text"]; ?></span></td>
              </tr>
            </table>
            <br />
            <?php } ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="14%"><img src="images/5_register_10.gif" width="34" height="33"></td>
                <td width="86%" class="txtBlack14"><strong>Login by E-mail</strong></td>
              </tr>
            </table>
            <p>&nbsp;</p>
            <?php if( $errCode == "ErrorReservedWord_login" ){ ?>
            <p class="txtRed14" style="padding:10px"><?php echo $errBox["text"]; ?></p>
            <?php } ?>
            <form action="" method="post"id="formID" class="formular">
              <p>
                <input name="inc_username" type="email" class="validate[required,custom[email]] form-control" id="email" placeholder="อีเมล">
              </p>
              <p class="txtBlack12" id="chkUser"></p>
              <p>
                <input name="inc_password" type="password" class="validate[required] form-control" id="password" placeholder="รหัสผ่าน">
              </p>
              <p>
                <input class="btn btn-primary btn-lg btn-block" type="submit" value="เข้าสู่ระบบ">
              </p>
              <input name="url" type="text" class="url" id="url" />
              <input name="action" type="hidden" value="login_member" />
            </form></td>
          <td width="14%" align="center"><img src="images/5_register_07.gif" width="44" height="381"></td>
          <td width="43%" valign="top"><table width="80%" border="0" cellspacing="0" cellpadding="0" style="margin:auto">
              <tr>
                <td width="17%"><img src="images/5_register_13.gif" width="36" height="33"></td>
                <td width="83%" class="txtBlack14"><strong class="txtblue14">สมัครสมาชิกด้วย Facebook</strong></td>
              </tr>
            </table>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p class="txtblue14" style="text-align:center">ความเพื่อความสะดวก<br>
              ท่านสามารถใช้บัญชีของ Facebook<br>
              สมัครใช้บริการได้เลยทันที</p>
            <p class="txtblue14" style="text-align:center; padding-top:8px"><a href="#" onclick="ShowFaceookLogin();"><img src="images/btn-fb.jpg" width="203" height="37"></a></p></td>
        </tr>
      </table>
    </div>
  </div>
  <?php include("inc-footer.php")?>
</div>
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