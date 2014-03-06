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
<style type="text/css">
.issue-icontent {
	z-index: 999;
}
</style>
<?php include("inc-top-head.php");?>
</head>
<body>
<div id="main">
 <?php include("inc-header.php")?>   
 <div id="content" class="site-content">
 <?php
		$qrySel1 = "select news_image_thumb,news_title,take_a_peak,news_id,news_abstract
					from $_Config_table[news] 
					where news_publish != '0'  
					order by order_by asc,news_id desc  ";	
		//echo $qrySel1;
		$rsSel1 = $DB->Execute($qrySel1); 
		$i= 1;
	 while($row_news = $rsSel1->FetchRow()){
	?>
     <div class="row-like-share <?php if($i%4 == 0){?>frist-row-item<?php }?>">
 	   <div class="issue-content" id="rowBlock<?php echo $i?>"><img src="uploads/news/<?php echo $row_news["news_image_thumb"];?>" alt="<?php echo $row_news["news_title"];?>" width="137" height="186"/> 
    <div class="issue-icontent rowBlock<?php echo $i?>">                 
      <p><a href="buynow.php?news_id=<?php echo $row_news["news_id"]?>" class="buynow"><img src="images/ic-buynow.png" alt="ic-buynow" width="96" height="40" class="hoverImg09"/></a></p>      
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
     </div>
    <?php $i++;}?>
     <p class="clear"></p>
 </div>
 <?php include("inc-footer.php")?>
</div>
<script>
jQuery(document).ready(function(){
    jQuery(".buynow").fancybox({
		'width'				: 600,
		'hideOnOverlayClick': true,
		 'height'			: '90%',
		 'padding'	: 0, 
		'autoScale'     	: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe',	  
		  onStart: function(){
			disable_scroll();		
		  },		  
		  onClosed: function(){
			enable_scroll();		  
		  } 		
		});  
	 
	// mousse over issue  
	  jQuery('.issue-content').hover(function(){
		 var IDOver =  jQuery(this).attr( "id" )
		//  alert(IDOver);
		  var divOver =  IDOver;
		  
		  jQuery("."+divOver).fadeIn(500);
	    },
      function(){
		  jQuery('.issue-icontent').fadeOut(500);
	  });
	 
});
</script>
</body>
</html>