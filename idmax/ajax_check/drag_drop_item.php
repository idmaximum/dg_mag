<?php
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "../../configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	require_once( $_Config_absolute_path . "/includes/func.class.php" );

	#Create Obj
	$DB = mosConnectADODB();
	$msObj = new MS($DB);

		$result = $_REQUEST["table-1"];
		//$num = count($result);
		$order  = 0;
		foreach($result as $value) {
			$order++;
			//echo "ลำดับที่".$order ." ID".$value;
			//echo "<br>";
			$qryUpd = "update  $_Config_table[item] SET order_by= '$order' WHERE item_id='$value' ";
			$DB->Execute($qryUpd);	
			//echo "<br>";
		}
		//http://www.isocra.com/2008/02/table-drag-and-drop-jquery-plugin/
?>