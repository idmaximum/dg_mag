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
	
	 $action = trim(mosGetParam($_REQUEST,'action',''));
	 $url = trim(mosGetParam($_REQUEST,'url','')); 
	  if($url == "" ){# เช็ดดูว่าใช่ spam หรือป่าว ############### Vote	
		if( isset($action) && !empty($action) && $action == "registerMember" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){ 
			//**/
		$email = trim(mosGetParam($_FORM,'email',''));
		$password = trim(mosGetParam($_FORM,'password',''));
		 
		$password = "idmax".$password."pass";
	    $password = hash('sha256', $password);		
		  
			$qryChkUser = "select * from $_Config_table[member] where username = $DB->qstr('$email')";
			$rsChkUser = $DB->Execute($qryChkUser);	
			 if( $rsChkUser->RecordCount() <= 0 ){
				$Rip = FU::GetIP();
					
				  $qryInsRegis = "insert into $_Config_table[member]( 				
								 username,primary_email, password,rip,   created_dt) 
								values (
								$DB->qstr('$email'),
								$DB->qstr('$email'),
								$DB->qstr('$password'),	
								$DB->qstr('$Rip'),				 			 
								now())";
				// echo $qryInsRegis; 
				$DB->Execute($qryInsRegis);
				
			 	$qryChk = "select * from $_Config_table[member] 
							where username = '$email' 
							and password = '$password' ";
				$rsChk = $DB->Execute($qryChk);
				if (!$result) 	print $DB->ErrorMsg();	
	
				  if( $rsChk->RecordCount()){	
					  $chkuser = $rsChk->FetchRow();	
							  
					  $_SESSION['_CUTOMERID'] = $chkuser["customer_id"];
					  $_SESSION['_USERNAME'] = $chkuser["username"]; 
					  $_SESSION['_EMAIL'] = $chkuser["primary_email"];
					  $_SESSION['_LASTACCESS'] = $chkuser["last_access"];
					  
					  $lastlogin = DT::currentDateTime();
					  
					  $qryUpdate = "update $_Config_table[member] set last_access = now() 
					  where customer_id = $chkuser[customer_id]";
					  //echo $qryUpdate;
					  $rs = $DB->Execute( $qryUpdate );
					  
				  }
				$errCode = "Success";
				$errBox["status"] = "Success";
				$errBox["text"]= "การสมัครสมาชิกสำเร็จ";								
					
				mosRedirect("index.php");
				exit; 
			 
			  }else{ # $rsChkUser->RecordCount() <= 0
			    $errCode = "ErrorReservedWord";
				$errBox["status"] = "ERROR";
				$errBox["text"]= "E-mail your application to register it. <br>Please try again later."; 
			 }#end if  $rsChkUser->RecordCount() <= 0
	
		}//end isset($action)*********************** 
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
<?php include("inc-top-head.php");?>
</head>
<body>
<div id="main">
 <?php include("inc-header.php")?>   
 <div id="content" class="site-content">
 	<div class="content-register">
 	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
 	    <tr>
 	      <td width="43%" valign="top">
           <?php if( $errCode == "ErrorReservedWord" ){ ?>
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
 	          <td width="86%" class="txtBlack14"><strong>สมัครสมาชิกด้วยอีเมล</strong></td>
 	          </tr>
          </table>
            <p>&nbsp;</p>
            <form action="" method="post"id="formID" class="formular">
              <div class="form-group">
              <input name="email" type="email" class="validate[required,custom[email]] form-control" id="email" placeholder="อีเมล"></div>
              <p class="txtBlack12" id="chkUser"></p>
              <p><input name="password" type="password" class="validate[required,minSize[6]] form-control" id="password" placeholder="ระบุรหัสผ่าน"></p>
              <p><input type="password" class="validate[required,equals[password]]  form-control" id="confirm" placeholder="ยืนยันุรหัสผ่านอีกครั้ง"></p>
              <p><input class="btn btn-primary btn-lg btn-block" type="submit" value="Register"></p>
             <input name="url" type="text" class="url" id="url" />
             <input name="action" type="hidden" value="registerMember" />
            </form> 
            
          </td>
 	      <td width="14%" align="center"><img src="images/5_register_07.gif" width="44" height="381"></td>
 	      <td width="43%" valign="top">
          <table width="80%" border="0" cellspacing="0" cellpadding="0" style="margin:auto">
 	        <tr>
 	          <td width="17%"><img src="images/5_register_13.gif" width="36" height="33"></td>
 	          <td width="83%" class="txtBlack14"><strong class="txtblue14">สมัครสมาชิกด้วย Facebook</strong></td>
 	          </tr>
          </table>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p> 
          <p class="txtblue14" style="text-align:center">ความเพื่อความสะดวก<br>
            ท่านสามารถใช้บัญชีของ Facebook<br>
            สมัครใช้บริการได้เลยทันที</p>
          <p class="txtblue14" style="text-align:center; padding-top:8px"><a href="#"onclick="ShowFaceookLogin();"><img src="images/btn-fb.jpg" width="203" height="37"></a></p>
          </td>
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
	  
	   jQuery("#email").blur(function(){	
			var email = jQuery(this).attr("value");
			jQuery.ajax({
			 
				 url : 'jQueryAjax/checkemail.php',
				type:"GET",
				cache: false,
				data : "email="+email,		
				success :function(data){ 				
					jQuery("#chkUser").html(data);  
					}
			});	//End jQuery.ajax	*/	
		}); // End  $("#username") blur	  
  });
</script>
</body>
</html>