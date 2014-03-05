<?php
	// Turn on all error reporting
	error_reporting(1);

	session_start();

	/** Set flag that this is a parent file */
	define( '_VALID_ACCESS', 1 );

	require_once( "../globals.php" );
	require_once( "../configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	
	$DB = mosConnectADODB();
	
	$loginname 	= $DB->qstr( trim( mosGetParam( $_POST, 'giFormUsername', '' ) ) );
	$loginpwd 	= $DB->qstr( trim( mosGetParam( $_POST, 'giFormPassword', '' ) ) );

	$validUrl = "new_order.php#order";
	$invalidUrl = "../WebLogin.htm";
	if( !empty($loginname) && !empty($loginpwd)){	

		$qryChk = "select * from tom_profile where username = $loginname and password = MD5($loginpwd) and status='Y'";
		//echo $qryChk;
		$rsChk = $DB->Execute($qryChk);
		if (!$result) 	print $DB->ErrorMsg();	

		if( $rsChk->RecordCount()){
			$chkuser = $rsChk->FetchRow();	
					
			$_SESSION['_ID'] = $chkuser["profile_id"];
			$_SESSION['_LOGIN'] = $chkuser["username"];
			$_SESSION['_GRPID'] = $chkuser["group_id"]; //1=Admin, 2=Staff
			$_SESSION['_LASTLOGIN'] = $chkuser["lastlogin"];
			
			$lastlogin = DT::currentDateTime();
			
			$qryUpdate = "update tom_profile set lastlogin = $db->DBTimeStamp('$lastlogin') 
			where profile_id = $chkuser[profile_id]";
			//echo $qryUpdate;
			$rs = $DB->Execute( $qryUpdate );

			//echo "Go to....2";
			mosRedirect( $validUrl);
		}else{
			$msg = 'Invalid the username and password to gain access to the backend';
			echo "Go to....3";
			mosRedirect( $invalidUrl, $msg);
		}
	}
	else{
		$msg = 'Invalid the username and password to gain access to the backend';
		echo "Go to....4";
		mosRedirect( $invalidUrl, $msg);
	}
?>