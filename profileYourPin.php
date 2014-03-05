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
		 $i = 1;
        While($rowBoard = $rsSelPicBoard->FetchRow()){ 
		    $boardCategoryID = $rowBoard["boardCategoryID_FK"];
		    $boardRow = $msObj->selectTable("board_category","category_name","category_id","$boardCategoryID");
			$boardName = $boardRow["category_name"];
			
			//*********** select num item in board
			$qrySelBoardNum = "select commentID,itemID_FK
                               from $_Config_table[pin_article]  
                              where memberID_FK = '$customerID' and  boardCategoryID_FK = '$boardCategoryID'
							  order by commentID desc";	
            $rsSelPicBoardNum = $DB->Execute($qrySelBoardNum);
			$numBoard = $rsSelPicBoardNum->RecordCount();			
			$rowPicUpdate = $rsSelPicBoardNum->FetchRow();
			
			$itemID = $rowPicUpdate["itemID_FK"];
			
			//Select item update
			 $itemRow = $msObj->selectTable("item","item_title,item_image_thumb","item_id","$itemID"); 
			 $item_image_thumb = $itemRow["item_image_thumb"];
			
			//************
	?>
    <div class="row-like-share <?php if($i%4 == 0){?>frist-row-item<?php }?>">
      <div  class="connerBox"></div>
     <div><a href="profileYourPinDetail.php?boardCategoryID=<?php echo $boardCategoryID?>"><img src="uploads/item/<?php echo $item_image_thumb;?>" alt="<?php echo $boardName;?>" width="220" height="192" class="hoverImg09"></a></div>
     <div class="border-bottom-board"> </div>
  
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="txtBlack13 font-gentfont">
        <tr>
          <td width="50%"><strong><?php echo $boardName;?></strong></td>
          <td width="50%" align="right"><?php echo $numBoard?> Items</td>
        </tr>
      </table>
      </div>
    <?php $i++;}?>
    <p class="clear"></p>
 </div> 
 <?php include("inc-footer.php")?>
</div>
</body>
</html>