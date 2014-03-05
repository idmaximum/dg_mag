<?php
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
	
	$item_id = mosGetParam($_FORM,'item_id','');
	
	$qryDetail = "select * from $_Config_table[item] where item_id = '$item_id'";
	$rsDetail = $DB->Execute($qryDetail );
	$detailitem = $rsDetail->FetchRow($rsDetail);
	
	$item_title = $detailitem["item_title"];
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet"href="../js/bootstrap/css/bootstrap.css" >
<link href="../js/bxslider-4-master/jquery.bxslider_2.css" rel="stylesheet" />
<style type="text/css">
.bxslider {
	text-align: center;
}
</style>
</head>

<body>
<div style="width:800px; margin:auto; padding:10px">
  <p style="font-size:14px; font-weight:bold"><?php echo $item_title;?></p>
  <ul class="bxslider"> 
    <li><img src="../uploads/item/<?php echo $detailitem["item_image"];?>" /></li>
    <?php
		  $qryTipsImg = "select * from $_Config_table[itemimage] where item_id='$item_id' ";
		  $rsTipsImg = $DB->Execute($qryTipsImg);
		  if( $rsTipsImg->RecordCount() > 0 ){
			  while($spImg = $rsTipsImg->FetchRow()){							
	   ?>  
    <li><img src="../uploads/item/<?php echo $spImg["item_image_name"];?>" ></li>
    <?php } }#end sub pic?>  
    </ul>
</div>

<script src="../js/jquery-1.8.js"></script> 
<script src="../js/bootstrap/js/bootstrap.min.js"></script>
<script src="../js/bxslider-4-master/jquery.bxslider.min.js"></script>
<script>
  jQuery(document).ready(function(){ 
	  jQuery('.bxslider').bxSlider({
		auto: true , 
		pause : 10000
	  });
	 
  });
</script>
</body>
</html>