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
              <div class="img-issue-top1 item-img-frist-top1"> <img src="images/12_shopping_03.jpg" />
                <div class="bg-thumb-item" style="width:460px"><a href="#">Textile Lamp Concepture</a></div>
                <div class="mask">
                  <a href="itemDetail.php" class="info"></a>
                  <p style=" padding-left:120px"><a href="pinItem.php" class="pinToBoard"><img src="images/add-to-pin.png" width="165" height="40"></a></p></div>
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
            <td width="80%">2353</td>
            <td width="6%"><img src="images/3_hover_thumbnail_15.png" alt="comment" width="18" height="14"></td>
            <td width="9%">76</td>
          </tr>
        </table> 
      </div>
      <div class="item-top5-issue-right"> 
      <?php for($i=1;$i<=4;$i++){?>
        <div class="row-issue-top5">
          <div class="img-thumb-content">
            <div class="item-img item-img-frist"> <img src="images/11_kitchen_09.jpg" />
              <div class="bg-thumb-item"><a href="#">Textile Lamp Concepture</a></div>
              <div class="mask">
                <p><a href="pinItem.php" class="pinToBoard"><img src="images/add-to-pin.png" width="165" height="40"></a></p>
                <a href="itemDetail.php" class="info"></a> </div>
            </div>
          </div> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="13%"><img src="images/3_hover_thumbnail_13.png" alt="like" width="17" height="14"></td>
              <td width="55%">2353</td>
              <td width="14%"><img src="images/3_hover_thumbnail_15.png" alt="comment" width="18" height="14"></td>
              <td width="18%">76</td>
            </tr>
          </table> 
          <p class="clear"></p>
        </div>
        <?php }?>
      </div>
      <p class="clear" style="height:0px"></p>
    </div>
    <div class="content-item" style="margin-top:0"><a href="#"><img src="images/-banner-big.jpg" width="940" height="140" class="hoverImg09"></a></div>
    <p style="padding:15px 0  0px 0"><img src="images/recent_post_like_share_09.gif" alt="top hit" width="940" height="28"></p> 
    <div class="content-item">
    <?php for($i=1;$i<=12;$i++){ ?>
      <div class="row-like-share <?php if($i%4 == 0){?>frist-row-item<?php }?>">
          <div class="img-thumb-content">
            <div class="item-img item-img-frist"> <img src="images/11_kitchen_09.jpg" />
              <div class="bg-thumb-item"><a href="#">Textile Lamp Concepture</a></div>
              <div class="mask">
                <p><a href="pinItem.php" class="pinToBoard"><img src="images/add-to-pin.png" width="165" height="40"></a></p>
                <a href="itemDetail.php" class="info"></a> </div>
            </div>
          </div> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="13%"><img src="images/3_hover_thumbnail_13.png" alt="like" width="17" height="14"></td>
              <td width="55%">2353</td>
              <td width="14%"><img src="images/3_hover_thumbnail_15.png" alt="comment" width="18" height="14"></td>
              <td width="18%">76</td>
            </tr>
          </table> 
          <p class="clear"></p>
        </div>
      <?php }?>
      <p class="clear"></p>
    </div>
 </div>
 <?php include("inc-footer.php")?>
</div>
<script src="js/bxslider-4-master/jquery.bxslider.min.js"></script> 
<script>
jQuery(document).ready(function(){
   jQuery('.bxslider').bxSlider({
	auto: true ,controls : false,pause : 10000
  });
  jQuery('.bxslider-item').bxSlider({
	  auto: true , controls : false, minSlides: 2,maxSlides: 3,slideWidth: 220,slideMargin: 20, pause :10000,autoHover: true
	});
});
</script>
</body>
</html>