<?php
	// Turn on all error reporting
	//error_reporting(1);

	session_start();

	/** Set flag that this is a parent file */
	define( '_VALID_ACCESS', 1 );

	require_once( "globals.php" );
	require_once( "configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	
	$DB = mosConnectADODB();
	$msObj = new MS($DB);
	
	$action =  trim(mosGetParam($_FORM, 'action', ''));
	$msg = '';
	
	if( isset($action) && !empty($action) && $action == "login"  && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER'])) {
		$msg = "";
	
		$loginname 	= $DB->qstr( trim( mosGetParam( $_POST, 'giFormUsername', '' ) ) );
	    $loginpwd = mosGetParam($_FORM,'giFormPassword','');		
		$loginpwd = "idmax".$loginpwd."pass";
	    $loginpwd = hash('sha256', $loginpwd);
		
		$validUrl = "idmax/item.php?category_id=1#Decoration";
		$invalidUrl = "admin.php";
		if( !empty($loginname) && !empty($loginpwd)){	
	
			  $qryChk = "select * from $_Config_table[profile] 
			  			 where username = $loginname and password = '$loginpwd' and status='Y' ";
			//echo $qryChk;
			$rsChk = $DB->Execute($qryChk);
			if (!$result) 	print $DB->ErrorMsg();	
	
			if( $rsChk->RecordCount()){
				$chkuser = $rsChk->FetchRow();	
						
				$_SESSION['_ID'] = $chkuser["profile_id"];
				$_SESSION['_LOGIN'] = $chkuser["username"];
				$_SESSION['_GRPID'] = $chkuser["group_id"]; 	//1=Admin, 2=Staff
				$_SESSION['_GRPLEVEL'] = ($_SESSION['_GRPID']=="1" || $_SESSION['_GRPID']=="88")? "Administrator" : "Officer";
				$_SESSION['_LASTLOGIN'] = $chkuser["lastlogin"];
				
				$lastlogin = DT::currentDateTime();
				
				$qryUpdate = "update $_Config_table[profile] set lastlogin = $db->DBTimeStamp('$lastlogin') 
				where profile_id = $chkuser[profile_id]";
				//echo $qryUpdate;
				$DB->Execute( $qryUpdate );
	
				//echo "Go to....2";
				mosRedirect( $validUrl);
			}else{
				$msg = 'Invalid the username and password to gain access to the backend';
				 
				//echo "Go to....3";
				 #mosRedirect( $invalidUrl, $msg);
			}
		}
		
		
	}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/font.css">
<link rel="stylesheet" href="js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="js/bootstrap/css/bootstrap-theme.css">
<link href="css/css-bof.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	background-image: none;
	background-color:#0866c6
}
</style>
<!--[if lt IE 9]>
      <script src="js/bootstrap/js/html5shiv.js"></script>
      <script src="js/bootstrap/js/respond.min.js"></script>
    <![endif]-->
<title>Admin panel</title>
</head>

<body>

<div class="container">
 
     <div class="forms-div">
      <h2 class="form-signin-heading" style="text-align:center">Please sign in</h2>
      <p class="txtOrange14"><?php echo (!empty($msg))? $msg : ""; ?></p>
       <form action="admin.php" method="post" class="form-signin" id="login">
       
         <div class="login-alert margin-fot-10">Invalid username or password</div>
         <input name="giFormUsername" type="text" autofocus   class="form-control margin-fot-10" id="username" placeholder="Username">
         <input name="giFormPassword" type="password" class="form-control margin-fot-10" id="password" placeholder="Password" >
         <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
           <input type="hidden" name="action" value="login">
         </form>
     </div>

    </div>
<?php include("idmax/inc-bof-footer.php");?>
<script src="js/jquery.js"></script>
<script src="js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
		<?php if($msg != ''){?>
		 jQuery('.login-alert').fadeIn();
		<?php }?>
        jQuery('#login').submit(function(){
            var u = jQuery('#username').val();
            var p = jQuery('#password').val();
            if(u == '' && p == '') {
                jQuery('.login-alert').fadeIn();
                return false;
            }
        });
    });
</script>
</body>
</html>