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
	
    $item_category_id = mosGetParam( $_FORM, 'item_category_id', '' );
	$item_sub_category_id = mosGetParam( $_FORM, 'item_sub_category_id', '' );
	
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
<link href="js/bxslider-4-master/jquery.bxslider.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="js/OriginalHoverEffects/css/style1.css" />
<link rel="stylesheet" type="text/css" href="js/inserthtml/style.css" />
<?php include("inc-top-head.php");?>
</head>
<body>
<div id="main">
 <?php include("inc-header.php")?>   
 <div id="content" class="site-content"> 
 	<div class="item-top5-issue">
 	  <p style="padding:10px 0"><img src="images/title_like_share.gif" alt="top hit" width="940" height="24"></p> 
      <p class="clear"></p>
      <div class="item-top5-issue-left">
      	<div class="img-thumb-content">
         <?php
		 		$sqlSubCate = ($item_sub_category_id!= "")? "and item_sub_category_id_FK = '$item_sub_category_id'" : "" ;
				$qrySelTop1 = "select item_image,item_title ,item_id,item_like,item_comment
							from $_Config_table[item] 
							where item_publish != '0' and item_category_id_FK = '$item_category_id' $sqlSubCate
							order by item_like desc limit 0,1 ";	
				//echo $qrySel1;
				$rsSelTop1 = $DB->Execute($qrySelTop1); 
				$row_itemTop1 = $rsSelTop1->FetchRow(); 
			?> 
              <div class="img-issue-top1 item-img-frist-top1">  
                <img src="uploads/item/<?php echo $row_itemTop1["item_image"];?>" alt="<?php echo $row_itemTop1["item_title"];?>" />
                <div class="bg-thumb-item" style="width:460px"><a href="#"><?php echo FU::strCrop($row_itemTop1["item_title"],80);?></a></div>
                <div class="mask">
                   <a href="itemDetail.php?item_id=<?php echo $row_itemTop1["item_id"];?>" class="info"></a>
                  <p style=" padding-left:120px"><a href="pinItem.php?item_id=<?php echo $row_itemTop1["item_id"];?>" class="pinToBoard"><img src="images/add-to-pin.png" width="165" height="40"></a></p>
                </div>
              </div>
        </div> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td style="height:10px"> </td>
        </tr>
      </table> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
          <tr>
            <td width="5%"><img src="images/3_hover_thumbnail_13.png" alt="like" width="17" height="14"></td>
            <td width="80%"><?php echo $row_itemTop1["item_like"];?></td>
            <td width="6%"><img src="images/3_hover_thumbnail_15.png" alt="comment" width="18" height="14"></td>
            <td width="9%"><?php echo $row_itemTop1["item_comment"];?></td>
          </tr>
        </table> 
      </div>
      <div class="item-top5-issue-right"> 
        <?php
			$sqlSubCate = ($item_sub_category_id!= "")? "and item_sub_category_id_FK = '$item_sub_category_id'" : "" ;
			$qrySel1 = "select item_image_thumb,item_title ,item_id,item_like,item_comment
						from $_Config_table[item] 
						where item_publish != '0' and item_category_id_FK = '$item_category_id'  $sqlSubCate
						order by item_like desc limit 1,4 ";	
			//echo $qrySel1;
			$rsSel1 = $DB->Execute($qrySel1); 
			While($row_item = $rsSel1->FetchRow()){ 
		?> 
        <div class="row-issue-top5">
          <div class="img-thumb-content">
            <div class="item-img item-img-frist">
              <img src="uploads/item/<?php echo $row_item["item_image_thumb"];?>" alt="<?php echo $row_item["item_title"];?>" />
              <div class="bg-thumb-item"><a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>"><?php echo FU::strCrop($row_item["item_title"],28);?></a></div>
              <div class="mask">
                 <a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>" class="info"></a>
                 <p><a href="pinItem.php?item_id=<?php echo $row_item["item_id"];?>" class="pinToBoard"><img src="images/add-to-pin.png" alt="add to pin" width="165" height="40"></a></p>
              </div>
            </div>
          </div> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="12%"><img src="images/3_hover_thumbnail_13.png" alt="like" width="17" height="14"></td>
                <td width="65%"><?php echo $row_item["item_like"];?></td>
                <td width="15%"><img src="images/3_hover_thumbnail_15.png" alt="comment" width="18" height="14"></td>
                <td width="8%"><?php echo $row_item["item_comment"];?></td>
              </tr>
          </table> 
          <p class="clear"></p>
        </div>
        <?php }?>
      </div>
      <p class="clear" style="height:0px"></p>
    </div>
    <div class="content-item" style="margin-top:0">
    
   	 <div class="div-sponsored">
       <?php
	   		if($item_category_id == 1){
				 $bannerCategoryID_FK = '3';
		    }else if($item_category_id == 2){
				 $bannerCategoryID_FK = '5';	
			 }else if($item_category_id == 3){	
				 $bannerCategoryID_FK = '10';
			 }else if($item_category_id == 4){
				 $bannerCategoryID_FK = '7'; 
			 }
	   
          $qrySelBanner = "select banner_url,banner_name,banner_swf,banner_pic,banner_id
                               from $_Config_table[banner]  
                              where publish != '0' and bannerCategoryID_FK = '$bannerCategoryID_FK'
                              order by rand()";	
          $rsSelPicBanner = $DB->Execute($qrySelBanner);	 
          $rowPicBanner = $rsSelPicBanner->FetchRow();
       ?>
        <a href="<?php echo $rowPicBanner["banner_url"];?>" id="<?php echo $rowPicBanner["banner_id"];?>" target="_blank" class="banner-front-page banner-page"><img src="images/banner-langer.png" width="940" height="140" class="hoverImg09"></a>
        <?php if($rowPicBanner["banner_swf"] == ""){ ?>
          <img src="uploads/banner/<?php echo $rowPicBanner["banner_pic"];?>" alt="banner <?php echo $rowPicBanner["banner_name"];?>" class="hoverImg09"/>
        <?php }else{?>
          <a  class="media {width:940, height:140}" href="uploads/banner/<?php echo $rowPicBanner["banner_swf"];?>"></a>
        <?php  } #end if?>
      </div>
    
    </div>
    <p style="padding:15px 0  0px 0"><img src="images/recent_post_like_share_09.gif" alt="top hit" width="940" height="28"></p> 
    <div class="content-item">
   		<div id="content-item"></div>
         <p class="clear"></p>
    </div> 
      <p class="clear"></p>
  </div>
</div>
 <?php include("inc-footer.php")?>
</div> 
<script src="js/bxslider-4-master/jquery.bxslider.min.js"></script> 
<script src="js/inserthtml/javascript.js"> </script> 
<script type="text/javascript">try{Typekit.load();}catch(e){}</script> 
<script>
jQuery(document).ready(function(){
   jQuery('.bxslider').bxSlider({
	auto: true ,controls : false,pause : 10000
  });
  jQuery('.bxslider-item').bxSlider({
	  auto: true , controls : false, minSlides: 2,maxSlides: 3,slideWidth: 220,slideMargin: 20, pause :10000,autoHover: true
	});
	
	jQuery('#content-item').scrollPagination({ 
		nop     : 8, // The number of posts per scroll to be loaded
		<?php if($item_category_id != ""){?>varItemCate   : <?php echo $item_category_id?>,<?php }?>
		<?php if($item_sub_category_id != ""){?>varItemSubCate   : <?php echo $item_sub_category_id?>,<?php }?>
		offset  : 0, // Initial offset, begins at 0 in this case
		error   : '', // When the user reaches the end this is the message that is
		                            // displayed. You can change this if you want.
		delay   : 1000, // When you scroll down the posts will load after a delayed amount of time.
		               // This is mainly for usability concerns. You can alter this as you see fit
		scroll  : true // The main bit, if set to false posts will not load as the user scrolls. 
		               // but will still load if the user clicks. 
	});
	

});
</script>
</body>
</html>