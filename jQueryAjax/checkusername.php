<?
	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "../../configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	require_once( $_Config_absolute_path . "/includes/func.class.php" );


	$DB = mosConnectADODB();
	$msObj = new MS($DB);
	
	$username = trim( mosGetParam( $_GET, 'username', ''  ));

	$ch_username = ereg("^[a-zA-Z0-9_]{3,16}$",$username);
	
	if ($username == ""){
		echo "โปรดกรอก Username";
	}	

	else{

		if ( $ch_username === FALSE ) { 
   			echo "ควรใช้ภาษาอังกฤษตัวอักษร(a-z, A-Z) ตัวเลข(0-9) เท่านั้น <br/>และห้ามมีเว้นวรรค" ;
   		} 
		
		else {
			$qryAcct = "select * from $_Config_table[member] where username = '$username'";
			$rsAcct = $DB->Execute($qryAcct);
		
			if( $rsAcct->RecordCount() > 0 ){
				echo "มีผู้ใช้ชื่อล็อกอินนี้แล้ว / Exist User";
			}
			else
			{
				echo "<span class=\"txt_tahoma_11_006\">ไม่พบชื่อล็อกอินนี้ ท่านสามารถใช้ชื่อนี้ได้ <br/> Not found, you can take this username</span>";
			}
		}	
	}
?>