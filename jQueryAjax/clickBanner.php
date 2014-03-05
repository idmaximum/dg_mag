<?
	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "../configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	require_once( $_Config_absolute_path . "/includes/func.class.php" );
 
	$DB = mosConnectADODB();
	$msObj = new MS($DB);
	
	//echo $_SESSION[SESS_ID]; 
	    
	$banner_id = trim( mosGetParam( $_POST, 'banner_id', ''  )); 
	
	//*****************
	$getIp = $_SERVER['REMOTE_ADDR'];
		
	$Rip = FU::GetIP();
	$ua  = FU::getBrowser(); 
    $ipCountry =  FU::ipToCountry($getIp);	
    $dateNow = date("Y-m-d H:i:s"); 
  
    $sess_id = $_SESSION[SESS_ID];
  
    $browser =$ua['name'];
    $browser_version = $ua['version']; 
	 
	 //**********************
	  
     $qrySelView = "select * from $_Config_table[click] 
		 				where ip = '$Rip'  and  session_id = '$_SESSION[SESS_ID]' and  banner_id = '$banner_id'
						order by date_user desc ";
		  $rsSelView = $DB->Execute($qrySelView);
		  $rowView = $rsSelView->FetchRow();
			   $dateUser = $rowView["date_user"];
			   $timePost = date('Y-m-d H:i:s', strtotime("+1 hours", strtotime("$dateUser"))) ;  //เช็คว่าต้องหลัง 1 ชมแล้วถึงจะโพสได้
	
			 if(date("Y-m-d H:i:s") >= "$timePost"){ //เช็คว่าต้องหลัง 1 ชมแล้วถึงจะโพสได้
			//******************
			$qryInsPD = "insert into  $_Config_table[click] ( 
			banner_id, ip, country, browser,
			browser_version,session_id,date_user,date_graph)
			values(
			'$banner_id','$Rip','$ipCountry','$browser',
			'$browser_version','$sess_id', '$dateNow', '$dateNow' )";
			
			$DB->Execute($qryInsPD);//***/
			
			// start Update ************
			echo  $qryUpdate = "update $_Config_table[banner] set click = click + 1 
			  				where banner_id = '$banner_id'";
			  $DB->Execute($qryUpdate); 
			// End Update *************
			//******************
			 }#end if date
?> 