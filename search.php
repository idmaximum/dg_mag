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
	
	$url = mosGetParam( $_FORM, 'url', '' );
	$keyword = mosGetParam( $_FORM, 'keyword', '' );
	$item_category_id = mosGetParam( $_FORM, 'item_category_id', '' );
	
	$qrySel_searchAll = "select item_id from $_Config_table[item] 
					 where item_publish != '0' and  (item_title like '%$keyword%' )";
	$rsSel_searchAll = $DB->Execute($qrySel_searchAll);
	$numrowsAll = $rsSel_searchAll->RecordCount();
	
	$qrySel_searchDes = "select item_id from $_Config_table[item] 
					 	 where item_publish != '0' and item_category_id_FK = '1'
					 	 and  (item_title like '%$keyword%' )";
	$rsSel_searchDes = $DB->Execute($qrySel_searchDes);
	$numrowsDes = $rsSel_searchDes->RecordCount();
	
	$qrySel_searchKit = "select item_id from $_Config_table[item] 
					 	 where item_publish != '0' and item_category_id_FK = '2'
					 	 and  (item_title like '%$keyword%' )";
	$rsSel_searchKit = $DB->Execute($qrySel_searchKit);
	$numrowsKit = $rsSel_searchKit->RecordCount();
	
	$qrySel_searchHowTo = "select item_id from $_Config_table[item] 
					 	 where item_publish != '0' and item_category_id_FK = '5'
					 	 and  (item_title like '%$keyword%' )";
	$rsSel_searchHowTo = $DB->Execute($qrySel_searchHowTo);
	$numrowsHowTo = $rsSel_searchHowTo->RecordCount();
	
	
	$qrySel_searchShopping = "select item_id from $_Config_table[item] 
					 	 where item_publish != '0' and item_category_id_FK = '3'
					 	 and  (item_title like '%$keyword%' )";
	$rsSel_searchShopping = $DB->Execute($qrySel_searchShopping);
	$numrowsShopping = $rsSel_searchShopping->RecordCount();
	
	$qrySel_searchLike = "select item_id from $_Config_table[item] 
					 	 where item_publish != '0' and item_category_id_FK = '4'
					 	 and  (item_title like '%$keyword%' )";
	$rsSel_searchLike = $DB->Execute($qrySel_searchLike);
	$numrowsLike = $rsSel_searchLike->RecordCount();
	
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
<link rel="stylesheet" type="text/css" href="js/inserthtml/style.css" />
<link rel="stylesheet" type="text/css" href="js/OriginalHoverEffects/css/style1.css" />
<style type="text/css">
.content-item .content-side-right {background-color: #efefef;margin-left:0px;padding:15px; width:190px;}
.content-item .content-side-right .col-md-4 { text-align:right; color:#217de2; }
 .line-search{background-color: #d7d7d7;height: 1px;margin:15px 0;}
</style>
<?php include("inc-top-head.php");?>
</head>
<body>
<div id="main">
 <?php include("inc-header.php")?>   
 <div id="content" class="site-content">
 	 <div class="content-item" style="margin-top:0">
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="4%"><img src="images/ic-search.png" width="27" height="26"></td>
          <td width="67%">ผลลัพท์การค้นหา <span class="txtBlack14">&quot;<?php echo $keyword;?>&quot;</span></td>
          <td width="29%">&nbsp;</td>
        </tr>
      </table>
        <br> 
        <div class="content-side-right font-gentfont">
        	<div class="row">
              <div class="col-md-8"><a href="search.php?keyword=<?php echo $keyword;?>" class="txtpink14">All Item</a></div>
              <div class="col-md-4"><?php echo $numrowsAll?></div>
            </div>
          <p class="line-search"></p> 

            <div class="row">
              <div class="col-md-8"><a href="search.php?keyword=<?php echo $keyword;?>&item_category_id=1" class="txtpink14">Decoration</a></div>
              <div class="col-md-4"><?php echo $numrowsDes?></div>
            </div>
            <p class="line-search"></p>
            
             <div class="row">
              <div class="col-md-8"><a href="search.php?keyword=<?php echo $keyword;?>&item_category_id=2" class="txtpink14">Kitchen</a></div>
              <div class="col-md-4"><?php echo $numrowsKit?></div>
            </div>
            <p class="line-search"></p>
            
              <div class="row">
              <div class="col-md-8"><a href="search.php?keyword=<?php echo $keyword;?>&item_category_id=5" class="txtpink14">How To</a></div>
              <div class="col-md-4"><?php echo $numrowsHowTo?></div>
            </div>
            <p class="line-search"></p>
            
             <div class="row">
              <div class="col-md-8"><a href="search.php?keyword=<?php echo $keyword;?>&item_category_id=3" class="txtpink14">Shopping</a></div>
              <div class="col-md-4"><?php echo $numrowsShopping?></div>
            </div>
            <p class="line-search"></p>
            
          <div class="row">
              <div class="col-md-8"><a href="search.php?keyword=<?php echo $keyword;?>&item_category_id=4" class="txtpink14">Like & Share</a></div>
              <div class="col-md-4"><?php echo $numrowsLike?></div>
            </div> 
       </div> 
   	   <div class="content-side-left" style="margin-left:20px">
        	<div id="content-item"></div>
         <p class="clear"></p>
       </div> 
       <p class="clear"></p>
     </div>
 </div>
 <?php include("inc-footer.php")?>
</div> 
<script src="js/inserthtml/javascript_search.js"> </script> 
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

<script>
jQuery(document).ready(function(){
    
	jQuery('#content-item').scrollPagination({ 
		nop     :6, // The number of posts per scroll to be loaded
		<?php if($item_category_id != ""){?>varItemCate   : <?php echo $item_category_id?>,<?php }?>
		<?php if($keyword != ""){?>varkeyword   : "<?php echo $keyword?>",<?php }?>
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
<div id="divBottom"></div>
</body>
</html>