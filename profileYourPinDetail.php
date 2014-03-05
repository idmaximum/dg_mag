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
	
	 if( empty($_SESSION['_CUTOMERID']) || empty($_SESSION['_USERNAME']) ){
		mosRedirect("login.php");
		exit();
	}
	$customerID = $_SESSION['_CUTOMERID'];	
	
	$boardCategoryID = trim(mosGetParam($_FORM,'boardCategoryID','')); 	
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
<?php include("inc-top-head.php");?>
</head>
<body>
<div id="main">
 <?php include("inc-header.php")?>   
 <div id="content" class="site-content" style=""> 
   <?php include("inc-portfolio-pin.php");?>
    <p>&nbsp;</p>
    <?php 
        	$qrySelBoardNum = "select commentID,itemID_FK
                               from $_Config_table[pin_article]  
                              where memberID_FK = '$customerID' and  boardCategoryID_FK = '$boardCategoryID'
							  order by commentID desc";	
            $rsSelPicBoardNum = $DB->Execute($qrySelBoardNum); 
		 $i = 1;
        While($rowBoardPin = $rsSelPicBoardNum->FetchRow()){  
			//*********** select num item in board
			
			$commentID = $rowBoardPin["commentID"];
			$itemID = $rowBoardPin["itemID_FK"];
			
			//Select item update
			 $itemRow = $msObj->selectTable("item","item_id,item_title,item_image_thumb","item_id","$itemID"); 
			  $item_image_thumb = $itemRow["item_image_thumb"];
			  $item_title = $itemRow["item_title"];
			  $item_id = $itemRow["item_id"];
			
			//************
	?>
    <div class="row-like-share <?php if($i%4 == 0){?>frist-row-item<?php }?>">
      <div  class="connerBox"></div>
     <div><a href="itemDetail_profile.php?item_id=<?php echo $item_id?>&commentID=<?php echo $commentID?>"class="info"><img src="uploads/item/<?php echo $item_image_thumb;?>" alt="<?php echo $boardName;?>" width="220" height="192" class="hoverImg09"></a></div>
     <div class="border-bottom-board"> </div>
      <?php echo $item_title;?>
      </div>
    <?php $i++;}?>
    <p class="clear"></p>
    <a href="profileYourPin.php" target="_self">Back
 To All Board</a></div> 
 <?php include("inc-footer.php")?>
</div>
</body>
</html>