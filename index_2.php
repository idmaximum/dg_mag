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
	
	  $Fbid = $_GET["id"];
	$FBemail = $_GET["email"];
	$fbName = $_GET["fbName"];
	
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
			 mosRedirect("index.php");
	 } 
	
?>
