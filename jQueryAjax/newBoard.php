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
	    
	  $catagoryBoard = trim( mosGetParam( $_POST, 'catagoryBoard', ''  )); 
	
?>
<?php if($catagoryBoard == "Create New Board"){?>
<input name="CreateNewBoard" type="text" class="validate[required] form-control " style="width:255px; height:24px"id="CreateNewBoard" placeholder="Create New Board">
<?php }else{?>
<?php }?>