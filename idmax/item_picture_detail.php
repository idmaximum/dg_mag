<?	

	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "../configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );

	#Login ?
	if( empty($_SESSION['_LOGIN']) || empty($_SESSION['_GRPID']) || empty($_SESSION['_ID'])){
		mosRedirect("_execlogout.php");
	}
	
	#Create Obj
	$DB = mosConnectADODB();

	//Get Parameter.
	$image_id		= trim( mosGetParam( $_FORM, 'image_id', '' ) );	
	//$largepic		= trim( mosGetParam( $_FORM, 'largepic', '' ) );	

	$qrySelPic = "select * from $_Config_table[newsimage] where news_image_id = '$image_id'";
	$rsSelPic = $DB->Execute($qrySelPic);
	$rowPic = $rsSelPic->FetchRow();
	//=======================================================

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<TITLE>Large picture</TITLE>
		<META http-equiv=Content-Type content="text/html; charset=utf-8">
		<link href="css_bof.css" rel="stylesheet" type="text/css">
	</HEAD>
	<BODY leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
		<div align="center" class="medium">
		<img src="../uploads/news/<?=$rowPic["news_image_name"];?>"><br />
		<?=$rowPic["news_image_description"];?>
		</div>
	</BODY>
</HTML>
