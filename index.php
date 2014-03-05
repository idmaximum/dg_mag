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
	
	 if( !isset($_SESSION[SESS_ID] )){
		$_SESSION[SESS_ID] = session_id();
	}  
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $_Config_sitename?></title>
<meta name="description" content="<?php echo $_Config_description?>">
<meta name="keywords" content="<?php echo $_Config_keyword?>">
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" href="js/bootstrap/css/bootstrap.min.css" >
<link rel="stylesheet" type="text/css" href="css/font.css" />
<link href="css/screen.css" rel="stylesheet" type="text/css">
<link href="js/bxslider-4-master/jquery.bxslider.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="js/OriginalHoverEffects/css/style1.css" />
<?php include("inc-top-head.php");?>
</head>
<body>
<div id="main">
  <?php include("inc-header.php")?>
  <div id="content" class="site-content">
    <div id="content-hilight" >
      <div class="content-side-left" id="banner-hilight">
        <ul class="bxslider">
          <?php						
		    $qrySelPicHeader = "select banner_url,banner_name,banner_swf,banner_pic,banner_id
								 from $_Config_table[banner]  
								where publish != '0' and bannerCategoryID_FK = '8'
								order by banner_order asc limit 0,3";	
			$rsSelPicHeader = $DB->Execute($qrySelPicHeader);	
			$i = 1;			
			While($rowPicHeader = $rsSelPicHeader->FetchRow()){			
            ?>
          <li>
            <?php if($rowPicHeader["banner_url"] !="" && $rowPicHeader["banner_url"] !="http://") { ?>
            <a href="<?=$rowPicHeader["banner_url"];?>" class="banner-front-page banner-page" target="_blank" title="<?=$rowPicHeader["banner_name"];?>" id="<?php echo $rowPicHeader["banner_id"];?>" >
            <?php }?>
            <img src="images/banner-front.png" alt="<?=$rowPicHeader["banner_name"];?>" width="700" height="400"/>
            <?php if($rowPicHeader["banner_url"] !="" && $rowPicHeader["banner_url"] !="http://") { ?>
            </a>
            <?php }?>
            <?php 	if($rowPicHeader["banner_swf"] == ""){ ?>
            <img src="uploads/banner/<?php echo $rowPicHeader["banner_pic"];?>" alt="banner <?=$rowPicHeader["banner_name"];?>" class="hoverImg09"/>
            <?php }else{?>
            <a class="media {width:700, height:400}" href="uploads/banner/<?php echo $rowPicHeader["banner_swf"];?>"></a>
            <?php  } #end if?>
          </li>
          <?php }#end while?>
        </ul>
      </div>
      <div class="content-side-right">
       <?php
				$qrySel1 = "select news_image_thumb,news_title,take_a_peak,news_id,news_abstract
							from $_Config_table[news] 
							where news_publish != '0'  
							order by order_by asc,news_id desc limit 0,1 ";	
				//echo $qrySel1;
				$rsSel1 = $DB->Execute($qrySel1); 
			 $row_news = $rsSel1->FetchRow(); 
			?>
        <div class="issue-content"><img src="uploads/news/<?php echo $row_news["news_image_thumb"];?>" alt="<?php echo $row_news["news_title"];?>" width="137" height="186"/> 
          <div class="issue-icontent">                 
             <p><a href="buynow.php?news_id=<?php echo $row_news["news_id"]?>" class="buynow"><img src="images/ic-buynow.png" alt="ic-buynow" width="96" height="40" class="hoverImg09"/></a></p> 
             <p><a href="subscribe.php" class="subscribe"><img src="images/ic-subscribe.png" alt="ic-subscribe" width="96" height="40" class="hoverImg09"/></a></p> 
             <p><a href="<?php echo $row_news["take_a_peak"]?>" target="_blank"><img src="images/ic-take-a-peek.png" alt="ic-take-a-peek" width="96" height="40" class="hoverImg09"></a></p>         
          
          </div>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="80%"><span class="txtpink12"><strong><a href="<?php echo $row_news["take_a_peak"]?>" target="_blank"><?php echo FU::strCrop($row_news["news_title"],28);?></a></strong></span><strong><br>
              <span class="txtBlack14"><?php echo $row_news["news_abstract"]?></span></strong></td>
            <td width="20%" align="right" valign="top"><a href="<?php echo $row_news["take_a_peak"]?>"target="_blank"><img src="images/ic-book.png" width="31" height="27"class="hoverImg08" alt="ic-book" /></a></td>
          </tr>
        </table>
        <p><img src="images/1_homepage_33.png" width="220" height="13" alt="1_homepage_33"/></p>
        <p style="text-align:center"> <a href="https://www.facebook.com/" target="_blank"><img src="images/ic-fb.gif" width="40" height="40" class="hoverImg08" alt="ic-fb"/></a> &nbsp; <a href="https://plus.google.com" target="_blank"><img src="images/ic-goole.gif" width="40" height="40" class="hoverImg08" alt="ic-goole"/></a> &nbsp; <a href="https://twitter.com/" target="_blank"><img src="images/ic-tw.gif" width="40" height="40" class="hoverImg08" alt="ic-tw"/></a> &nbsp; <a href="http://www.youtube.com/" target="_blank"><img src="images/ic-yt.gif" alt="ic-yt" width="40" height="40" class="hoverImg08"/></a></p>
      </div>
      <div class="clear"></div>
    </div>
    <div class="content-item">
      <div class="content-side-left">
        <div><span class="title-category">Decoration</span> &nbsp; &nbsp; <span class="txtpink14"><a href="item.php?item_category_id=1">View all  &nbsp; <img src="images/bullet-view.gif" width="11" height="12"/></a></span></div>
        <ul class="bxslider-item">
          <?php
				$qrySel1 = "select item_image_thumb,item_title ,item_id,item_like,item_comment
							from $_Config_table[item] 
							where item_publish != '0' and item_category_id_FK = '1'
							order by order_by, item_id desc limit 0,9 ";	
				//echo $qrySel1;
				$rsSel1 = $DB->Execute($qrySel1); 
				While($row_item = $rsSel1->FetchRow()){ 
			?>
          <li>
            <div class="img-thumb-content">
              <div class="item-img item-img-frist"> <img src="uploads/item/<?php echo $row_item["item_image_thumb"];?>" alt="<?php echo $row_item["item_title"];?>" />
                <div class="bg-thumb-item"><a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>"><?php echo FU::strCrop($row_item["item_title"],28);?></a></div>
                <div class="mask"> <a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>" class="info"></a>
                  <p><a href="pinItem.php?item_id=<?php echo $row_item["item_id"];?>" class="pinToBoard"><img src="images/add-to-pin.png" alt="add to pin" width="165" height="40"/></a></p>
                </div>
              </div>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="12%"><img src="images/3_hover_thumbnail_13.png" alt="like" width="17" height="14"/></td>
                <td width="65%"><?php echo $row_item["item_like"];?></td>
                <td width="15%"><img src="images/3_hover_thumbnail_15.png" alt="comment" width="18" height="14"/></td>
                <td width="8%"><?php echo $row_item["item_comment"];?></td>
              </tr>
            </table>
            <p class="clear"></p>
          </li>
          <?php }?>
        </ul>
      </div>
      <div class="content-side-right">
        <p style="text-align:right"><a href="#"><img src="images/sponsored-item.gif" width="85" height="18"class="hoverImg09"/></a></p>
        <div class="div-sponsored">
          <?php
            $qrySelBanner = "select banner_url,banner_name,banner_swf,banner_pic,banner_id
								 from $_Config_table[banner]  
								where publish != '0' and bannerCategoryID_FK = '2'
								order by rand()";	
			$rsSelPicBanner = $DB->Execute($qrySelBanner);	 
			$rowPicBanner = $rsSelPicBanner->FetchRow();
		 ?>
          <a href="<?php echo $rowPicBanner["banner_url"];?>" target="_blank" id="<?php echo $rowPicBanner["banner_id"];?>" class="banner-front-page banner-page"><img src="images/banner-thumb-page.png" width="220" height="220" class="hoverImg09"/></a>
          	<?php if($rowPicBanner["banner_swf"] == ""){ ?>
          <img src="uploads/banner/<?php echo $rowPicBanner["banner_pic"];?>" alt="banner <?php echo $rowPicBanner["banner_name"];?>" class="hoverImg09"/>
          <?php }else{?>
          <a  class="media {width:220, height:220}" href="uploads/banner/<?php echo $rowPicBanner["banner_swf"];?>"></a>
          <?php  } #end if?>
        </div>
      </div>
      <p class="clear"></p>
    </div>
    <div class="content-item">
      <div class="content-side-left">
        <div><span class="title-category">Kitchen</span> &nbsp; &nbsp; <span class="txtpink14"><a href="item.php?item_category_id=2">View all  &nbsp; <img src="images/bullet-view.gif" width="11" height="12"/></a></span></div>
        <ul class="bxslider-item">
          <?php
				$qrySel1 = "select item_image_thumb,item_title ,item_id,item_like,item_comment
							from $_Config_table[item] 
							where item_publish != '0' and item_category_id_FK = '2'
							order by order_by, item_id desc limit 0,9 ";	
				//echo $qrySel1;
				$rsSel1 = $DB->Execute($qrySel1); 
				While($row_item = $rsSel1->FetchRow()){ 
			?>
          <li>
            <div class="img-thumb-content">
              <div class="item-img item-img-frist"> <img src="uploads/item/<?php echo $row_item["item_image_thumb"];?>" alt="<?php echo $row_item["item_title"];?>" />
                <div class="bg-thumb-item"><a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>"><?php echo FU::strCrop($row_item["item_title"],28);?></a></div>
                <div class="mask"> <a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>" class="info"></a>
                  <p><a href="pinItem.php?item_id=<?php echo $row_item["item_id"];?>" class="pinToBoard"><img src="images/add-to-pin.png" alt="add to pin" width="165" height="40"></a></p>
                </div>
              </div>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="12%"><img src="images/3_hover_thumbnail_13.png" alt="like" width="17" height="14"/></td>
                <td width="65%"><?php echo $row_item["item_like"];?></td>
                <td width="15%"><img src="images/3_hover_thumbnail_15.png" alt="comment" width="18" height="14"/></td>
                <td width="8%"><?php echo $row_item["item_comment"];?></td>
              </tr>
            </table>
            <p class="clear"></p>
          </li>
          <?php }?>
        </ul>
      </div>
      <div class="content-side-right">
        <p style="text-align:right"><a href="#"><img src="images/sponsored-item.gif" width="85" height="18"class="hoverImg09"/></a></p>
        <div class="div-sponsored">
          <?php
            $qrySelBanner = "select banner_url,banner_name,banner_swf,banner_pic,banner_id
								 from $_Config_table[banner]  
								where publish != '0' and bannerCategoryID_FK = '4'
								order by rand()";	
			$rsSelPicBanner = $DB->Execute($qrySelBanner);	 
			$rowPicBanner = $rsSelPicBanner->FetchRow();
		 ?>
          <a href="<?php echo $rowPicBanner["banner_url"];?>" target="_blank" id="<?php echo $rowPicBanner["banner_id"];?>" class="banner-front-page banner-page"><img src="images/banner-thumb-page.png" width="220" height="220" class="hoverImg09"/></a>
          <?php if($rowPicBanner["banner_swf"] == ""){ ?>
          <img src="uploads/banner/<?php echo $rowPicBanner["banner_pic"];?>" alt="banner <?php echo $rowPicBanner["banner_name"];?>" class="hoverImg09"/>
          <?php }else{?>
          <a  class="media {width:220, height:220}" href="uploads/banner/<?php echo $rowPicBanner["banner_swf"];?>"></a>
          <?php  } #end if?>
        </div>
      </div>
      <p class="clear"></p>
    </div>
    <div class="content-item">
      <div class="content-side-left">
        <div><span class="title-category">Shopping</span> &nbsp; &nbsp; <span class="txtpink14"><a href="item.php?item_category_id=3">View all  &nbsp; <img src="images/bullet-view.gif" width="11" height="12"/></a></span></div>
        <ul class="bxslider-item">
          <?php
				$qrySel1 = "select item_image_thumb,item_title ,item_id,item_like,item_comment
							from $_Config_table[item] 
							where item_publish != '0' and item_category_id_FK = '3'
							order by order_by, item_id desc limit 0,9 ";	
				//echo $qrySel1;
				$rsSel1 = $DB->Execute($qrySel1); 
				While($row_item = $rsSel1->FetchRow()){ 
			?>
          <li>
            <div class="img-thumb-content">
              <div class="item-img item-img-frist"> <img src="uploads/item/<?php echo $row_item["item_image_thumb"];?>" alt="<?php echo $row_item["item_title"];?>" />
                <div class="bg-thumb-item"><a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>"><?php echo FU::strCrop($row_item["item_title"],28);?></a></div>
                <div class="mask"> <a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>" class="info"></a>
                  <p><a href="pinItem.php?item_id=<?php echo $row_item["item_id"];?>" class="pinToBoard"><img src="images/add-to-pin.png" alt="add to pin" width="165" height="40"/></a></p>
                </div>
              </div>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="12%"><img src="images/3_hover_thumbnail_13.png" alt="like" width="17" height="14"/></td>
                <td width="65%"><?php echo $row_item["item_like"];?></td>
                <td width="15%"><img src="images/3_hover_thumbnail_15.png" alt="comment" width="18" height="14"/></td>
                <td width="8%"><?php echo $row_item["item_comment"];?></td>
              </tr>
            </table>
            <p class="clear"></p>
          </li>
          <?php }?>
        </ul>
      </div>
      <div class="content-side-right">
        <p style="text-align:right"><a href="#"><img src="images/sponsored-item.gif" width="85" height="18"class="hoverImg09"/></a></p>
        <div class="div-sponsored">
          <?php
            $qrySelBanner = "select banner_url,banner_name,banner_swf,banner_pic,banner_id
								 from $_Config_table[banner]  
								where publish != '0' and bannerCategoryID_FK = '6'
								order by rand()";	
			$rsSelPicBanner = $DB->Execute($qrySelBanner);	 
			$rowPicBanner = $rsSelPicBanner->FetchRow();
		 ?>
          <a href="<?php echo $rowPicBanner["banner_url"];?>" id="<?php echo $rowPicBanner["banner_id"];?>" target="_blank" class="banner-front-page banner-page"><img src="images/banner-thumb-page.png" width="220" height="220" class="hoverImg09" alt=" <?php echo $rowPicBanner["banner_name"];?>"/></a>
          <?php if($rowPicBanner["banner_swf"] == ""){ ?>
          <img src="uploads/banner/<?php echo $rowPicBanner["banner_pic"];?>" alt="banner <?php echo $rowPicBanner["banner_name"];?>" class="hoverImg09"/>
          <?php }else{?>
          <a  class="media {width:220, height:220}" href="uploads/banner/<?php echo $rowPicBanner["banner_swf"];?>"></a>
          <?php  } #end if?>
        </div>
      </div>
      <p class="clear"></p>
    </div>
    <div class="content-item">
      <div class="content-side-left">
        <div><span class="title-category">Like &amp; Share</span> &nbsp; &nbsp; <span class="txtpink14"><a href="item.php?item_category_id=4">View all  &nbsp; <img src="images/bullet-view.gif" width="11" height="12" alt="bullet-view"/></a></span></div>
        <ul class="bxslider-item">
          <?php
				$qrySel1 = "select item_image_thumb,item_title ,item_id,item_like,item_comment
							from $_Config_table[item] 
							where item_publish != '0' and item_category_id_FK = '4'
							order by item_id desc,item_like desc limit 0,9 ";	
				//echo $qrySel1;
				$rsSel1 = $DB->Execute($qrySel1); 
				While($row_item = $rsSel1->FetchRow()){ 
			?>
          <li>
            <div class="img-thumb-content">
              <div class="item-img item-img-frist"> <img src="uploads/item/<?php echo $row_item["item_image_thumb"];?>" alt="<?php echo $row_item["item_title"];?>" />
                <div class="bg-thumb-item"><a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>"><?php echo FU::strCrop($row_item["item_title"],28);?></a></div>
                <div class="mask"> <a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>" class="info"></a>
                  <p><a href="pinItem.php?item_id=<?php echo $row_item["item_id"];?>" class="pinToBoard"><img src="images/add-to-pin.png" alt="add to pin" width="165" height="40"/></a></p>
                </div>
              </div>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="12%"><img src="images/3_hover_thumbnail_13.png" alt="like" width="17" height="14"/></td>
                <td width="65%"><?php echo $row_item["item_like"];?></td>
                <td width="15%"><img src="images/3_hover_thumbnail_15.png" alt="comment" width="18" height="14"/></td>
                <td width="8%"><?php echo $row_item["item_comment"];?></td>
              </tr>
            </table>
            <p class="clear"></p>
          </li>
          <?php }?>
        </ul>
      </div>
      <div class="content-side-right">
        <p style="text-align:right"><a href="#"><img src="images/sponsored-item.gif" width="85" height="18"class="hoverImg09" alt="sponsored"/></a></p>
        <div class="div-sponsored">
          <?php
          $qrySelBanner = "select banner_url,banner_name,banner_swf,banner_pic,banner_id
                               from $_Config_table[banner]  
                              where publish != '0' and bannerCategoryID_FK = '9'
                              order by rand()";	
          $rsSelPicBanner = $DB->Execute($qrySelBanner);	 
          $rowPicBanner = $rsSelPicBanner->FetchRow();
       ?>
          <a href="<?php echo $rowPicBanner["banner_url"];?>" id="<?php echo $rowPicBanner["banner_id"];?>" target="_blank" class="banner-front-page banner-page"><img src="images/banner-thumb-page.png" width="220" height="220" class="hoverImg09"/></a>
          <?php if($rowPicBanner["banner_swf"] == ""){ ?>
          <img src="uploads/banner/<?php echo $rowPicBanner["banner_pic"];?>" alt="banner <?php echo $rowPicBanner["banner_name"];?>" class="hoverImg09"/>
          <?php }else{?>
          <a  class="media {width:220, height:220}" href="uploads/banner/<?php echo $rowPicBanner["banner_swf"];?>"></a>
          <?php  } #end if?>
        </div>
      </div>
      <p class="clear"></p>
    </div>
    <div  class="content-item" id="banner-footer-index">
      <div class="div-sponsored">
        <?php
          $qrySelBanner = "select banner_url,banner_name,banner_swf,banner_pic,banner_id
                               from $_Config_table[banner]  
                              where publish != '0' and bannerCategoryID_FK = '1'
                              order by rand()";	
          $rsSelPicBanner = $DB->Execute($qrySelBanner);	 
          $rowPicBanner = $rsSelPicBanner->FetchRow();
       ?>
        <a href="<?php echo $rowPicBanner["banner_url"];?>" id="<?php echo $rowPicBanner["banner_id"];?>" target="_blank" class="banner-front-page banner-page"><img src="images/banner-langer.png" width="940" height="140" class="hoverImg09"/></a>
        <?php if($rowPicBanner["banner_swf"] == ""){ ?>
        <img src="uploads/banner/<?php echo $rowPicBanner["banner_pic"];?>" alt="banner <?php echo $rowPicBanner["banner_name"];?>" class="hoverImg09"/>
        <?php }else{?>
        <a  class="media {width:940, height:140}" href="uploads/banner/<?php echo $rowPicBanner["banner_swf"];?>"></a>
        <?php  } #end if?>
      </div>
    </div>
  </div>
  <?php include("inc-footer.php")?>
</div>
<script src="js/bxslider-4-master/jquery.bxslider.min.js"></script> 
<script src="js/js-webpage-index.js"></script>
</body>
</html>