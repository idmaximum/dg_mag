<?php
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
	
    $item_id = mosGetParam($_FORM,'item_id','');
	
	//**************  start insert ***********
	$action = trim(mosGetParam($_FORM,'action',''));
	$url = trim(mosGetParam($_REQUEST,'url','')); 
	$customerID = $_SESSION['_CUTOMERID'];	
	
	 if($url == ""){# เช็ดดูว่าใช่ spam หรือป่าว ############### Vote	
		if( isset($action) && !empty($action) && $action == "commentWebboard" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){
			
			$CreateNewBoard = trim(mosGetParam($_FORM,'CreateNewBoard',''));
			$message = trim(mosGetParam($_FORM,'message',''));
			$Rip = FU::GetIP();
			
			 $qryAddCateBoard =  "insert into $_Config_table[comment]
								  ( itemID_FK, memberID_FK,comment,
								     dateTimeComment,userIP) 
							 	 values
						 	   ( $DB->qstr('$item_id'),$DB->qstr('$customerID'),$DB->qstr('$message')
							    ,now(),$DB->qstr('$Rip') )";
			 $DB->Execute($qryAddCateBoard);	
			 
			 $qryUpdateItem = "update $_Config_table[item] 
			 			   set item_comment = item_comment + 1 where item_id = '$item_id'";
			 $DB->Execute($qryUpdateItem);
			 
			 FU::alert_mesg("Add a comment you have completed.");
			 mosRedirect("itemDetail.php?item_id=$item_id");
			 exit();
			
		}#end if
	 }#end if 
	//*************** End insert ***************
	
	$qryUpdate = "update $_Config_table[item] set item_visited = item_visited + 1 where item_id = '$item_id'";
	$DB->Execute($qryUpdate);
	
	$qryDetail = "select * from $_Config_table[item] where item_id = '$item_id'";
	$rsDetail = $DB->Execute($qryDetail );
	$detailitem = $rsDetail->FetchRow($rsDetail);	
	
	$item_title = $detailitem["item_title"];
	 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $item_title?></title> 
<meta name="description" content="<?php echo $detailitem["item_abstract"] ;?>" />

<link rel="stylesheet"href="js/bootstrap/css/bootstrap.css" >
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/font.css" />
<link rel="stylesheet" href="js/formValidator/css/validationEngine.jquery.css" type="text/css"/>
<link href="js/bxslider-4-master/jquery.bxslider_2.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="js/jquery.fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" /> 
<link href="css/itemDetail.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#main-content .bxslider li {
	vertical-align: middle!important;
	display: table-cell!important;
}
</style>
</head> 
<body> 
<div id="loading-page"></div>
<div id="main-content"> 
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><p class="txtBlack18 font-gentfont"><?php echo $item_title?></p></td>
    <td align="right"><div class="fb-share-button" data-href="http://idmaxdev.com/dg_magazine_pr/itemDetailShare.php?item_id=<?php echo $item_id?>" data-type="button_count"></div></td>
  </tr>
</table> 
  <p>&nbsp;</p>
  <p style="text-align:center"></p> 
  <ul class="bxslider"> 
    <?php if($detailitem["item_youtube"] != ""){
		   $item_youtube = substr($detailitem["item_youtube"],-11,11);
		?><li><iframe width="640" height="480" src="//www.youtube.com/embed/<?php echo $item_youtube?>" frameborder="0" allowfullscreen></iframe></li><?php }?>
    <li><img src="uploads/item/<?php echo $detailitem["item_image"];?>" /></li>
      <?php
		  $qryTipsImg = "select * from $_Config_table[itemimage] where item_id='$item_id' ";
		  $rsTipsImg = $DB->Execute($qryTipsImg);
		  if( $rsTipsImg->RecordCount() > 0 ){
			  while($spImg = $rsTipsImg->FetchRow()){							
	   ?>  
    <li><img src="uploads/item/<?php echo $spImg["item_image_name"];?>" ></li>
   <?php } }#end sub pic?>  
  </ul>
  <p>&nbsp;</p>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="6%">
      <?php if($detailitem["memberID_FK"] == 0){?>
       <a href="" class="navlink"><img src="images/logo_ss.jpg" style="max-height:50px; max-width:50px "/></a> 
      <?php }else{?>
       <?php
            	$qryPostMember = "select customer_fname,customer_img,primary_email,fbID
							from $_Config_table[member] 
					        where customer_id ='$detailitem[memberID_FK]'
						    order by customer_id desc";
				$rsPostMember = $DB->Execute($qryPostMember);
			    $spPostMember = $rsPostMember->FetchRow();
			?>
         <? if($spPostMember['customer_img'] != ""){ ?>
           <a href="" class="navlink"><img src="uploads/member/<?php echo $spPostMember['customer_img'];?>" style="max-height:50px; max-width:50px "/></a> 
        <?  }else if($spPostMember['fbID'] != ""){ ?>   
           <a href="" class="navlink"><img src="https://graph.facebook.com/<?php echo $spPostMember['fbID']?>/picture?type=large"style="max-height:50px; max-width:50px "/></a> 
        <?php }else{?>
         <a href="" class="navlink"><img src="images/user-psd-png.png" style="max-height:50px; max-width:50px "/></a> 
        <?php }?>
        <?php } #end check memberID_FK?>
         </td>
      <td width="46%" style="padding-left:5px"> <p class="txtBlack14">
       <?php if($detailitem["memberID_FK"] == 0){?>
       	 DG MMagazine
      <?php }else{?>
    		 <?php if($spPostMember['customer_fname'] != ""){?>
               <?php echo $spPostMember['customer_fname']?>
            <?php }else{?>
              <?php echo $spPostMember['primary_email']?>
            <?php }?>
       <?php } #end check memberID_FK?>
      </p>
      <p class="txtblue14"><?php   echo date("d F Y", strtotime("$detailitem[item_create_date]")); // gives 201101?></p></td>
      <td width="15%">
      <div id="divLike">
       <?php if($_SESSION['_CUTOMERID']=="" && $_SESSION['_USERNAME']==""){ ?> 
         <div id="apDiv1">       
         <a href="login_to_itemdetail.php?item_id=<?php echo $item_id;?>" class="loginBoard"><img src="images/btn-before-login.png" width="80" height="42" /></a>      
        </div>
          <?php }?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="68%"> 
            <?php
				  $customerID = $_SESSION['_CUTOMERID'];	
            	  $qrySelView = "select likeID from $_Config_table[like] 
		 				where   memberID_FK ='$customerID' and  itemID_FK = '$item_id'
						order by likeID desc ";
				 $rsSelView = $DB->Execute($qrySelView);
				 $numrow =  $rsSelView->RecordCount();
				if($numrow <= 0){	
			?>
              <img src="images/btn_like.png" width="74" height="42" class="btnLike" id="like" />   
             <?php }else{?> 
             <img src="images/btn_unlike.png" width="74" height="42" class="btnLike" id="unLike" />     
              <?php }?>
                 
              </td>
            <td width="32%" align="center"><div class="txtpink12"><?php echo $detailitem["item_like"];?></div></td>
            </tr>
        </table>
      </div>
      </td>
      <td width="1%"><img src="images/14_view_detail_12.gif" width="1" height="30" /></td>
      <td width="4%" align="right"><img src="images/3_hover_thumbnail_15.png" width="18" height="14" /></td>
      <td width="6%" align="center" class="txtpink12"><?php echo $detailitem["item_comment"];?></td>
      <td width="1%"><img src="images/14_view_detail_12.gif" width="1" height="30" /></td>
      <td width="21%" align="center"><a href="pinItem.php?item_id=<?php echo $detailitem["item_id"];?>" class="pinToBoard"><img src="images/pin-to-board.gif" width="168" height="42" /></a></td>
    </tr>
  </table>
  <?php if($detailitem["item_abstract"] != ""){?>
  <div  class="txtpink13"style="padding:30px;line-height: 1.5;"><?php echo nl2br($detailitem["item_abstract"]);?></div>
  <?php }?>
  <p>&nbsp;</p>
</div>
<div class="content-comment">
  <div class="content-width"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="23%"><img src="images/14_view_detail_03.gif" alt="add comment" width="165" height="27" /></td>
        <td width="77%" align="left" valign="middle" class="txtblue14" style="padding-top:5px">  
		<?php if($_SESSION['_CUTOMERID']=="" && $_SESSION['_USERNAME']==""){ ?> 
          <a href="login_to_itemdetail.php?item_id=<?php echo $item_id;?>" class="loginBoard">Please log in to post.</a>
        <?php }?></td>
      </tr>
    </table>
   
     <?php if($_SESSION['_CUTOMERID']!="" && $_SESSION['_USERNAME']!=""){ ?> 
    <div class="form-comment" id="formComment">
      <form action="" method="post" style="padding:10px 0" id="formID" class="formular">
        <p> <textarea name="message" id="message" cols="90" rows="10" class="validate[required] form-control"  placeholder="Comment..."><?php echo $message?></textarea></p>
        <p><input class="btn btn-primary btn-lg btn-block width300 txtWhite14" type="submit" value="Post a Comment"></p>
         <input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />
         <input name="action" type="hidden" value="commentWebboard" />
         <input name="url" type="text" class="url" />
      </form>
    </div>
     <?php }#end session?>
    <div class="detail-comment">
   <?php
		$qryitemComment = "select comment ,dateTimeComment,memberID_FK
							from $_Config_table[comment] 
					      where itemID_FK ='$item_id'
						  order by commentID desc";
		$rsitemComment = $DB->Execute($qryitemComment);
	    while($spitemComment = $rsitemComment->FetchRow()){							
	   ?> 
      <div class="row-comment">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="9%" align="center"> 
            <?php
            	$qryCommentMember = "select customer_fname,customer_img,primary_email,fbID
							from $_Config_table[member] 
					        where customer_id ='$spitemComment[memberID_FK]'
						    order by customer_id desc";
				$rsCommentMember = $DB->Execute($qryCommentMember);
			    $spCommentMember = $rsCommentMember->FetchRow();
			?>
            <div class="wrapper-crop">               
                 <? if($spCommentMember['customer_img'] != ""){ ?>
                 <a href="" class="navlink"><img src="uploads/member/<?php echo $spCommentMember['customer_img'];?>" style="max-height:50px; max-width:50px "/></a> 
              <?  }else if($spCommentMember['fbID'] != ""){ ?>   
                 <a href="" class="navlink"><img src="https://graph.facebook.com/<?php echo $spCommentMember['fbID']?>/picture?type=large"style="max-height:50px; max-width:50px "/></a> 
              <?php }else{?>
               <a href="" class="navlink"><img src="images/user-psd-png.png" style="max-height:50px; max-width:50px "/></a> 
              <?php }?>
            </div>
            </td>
            <td width="91%">
            <p style="margin-bottom:5px">
            <strong><span class="txtBlack14">
             <?php if($spCommentMember['customer_fname'] != ""){?>
               <?php echo $spCommentMember['customer_fname']?>
            <?php }else{?>
              <?php echo $spCommentMember['primary_email']?>
            <?php }?>
          </span></strong>
            <span class="txtBrown14"><?php   echo date("d F Y  H:i", strtotime("$spitemComment[dateTimeComment]"));?></span></p>
            <p class="txtBlack14"><?php echo nl2br($spitemComment["comment"])?></p></td>
          </tr>
        </table>
      </div>
	<?php }#end while?>
    </div> 
  </div>
</div>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/th_TH/all.js#xfbml=1&appId=598574500225155";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script type="text/javascript" src="js/jquery-1.8.js"></script> 
<script src="js/formValidator/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/formValidator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="js/bxslider-4-master/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="js/jquery.fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="js/jquery.corner.js"></script>
<script type="text/javascript" src="js/swf/jquery.media.js"></script> 
<script>
  jQuery(document).ready(function(){
	  jQuery('a.media').media();
	  jQuery("#loading-page").show(100).delay(800).fadeOut(600);
	  // binds form submission and fields to the validation engine
	  jQuery("#formID").validationEngine(); 
	  jQuery('.bxslider').bxSlider({
		auto: true ,
		pager : false,
		pause : 10000
	  });
	  jQuery(".pinToBoard").fancybox({
		'width'				: 400, 
		 'height'			: 480,
		 'padding'	: 0,
		'autoScale'     	: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});
	 jQuery(".loginBoard").fancybox({
		'width'				: 400, 
		 'height'			: 480,
		 'padding'	: 0,
		'autoScale'     	: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe',
		 onClosed: function(){   
		  window.open('<?php echo "itemDetail_2.php?item_id=$item_id"?>','_self');  
		} 
	});
	 jQuery(".navlink img").corner("4px"); 
	 
	 fnLike(); 
	 fnUnLike();
	 //**************
	  function fnLike(){
	    jQuery(".btnLike").click(function(){	
			var btnLike = jQuery(this).attr("id");
		    //alert(btnLike);
			
			jQuery.ajax({			 
				 url : 'jQueryAjax/clickLike.php',
				type:"POST",
				cache: false,
				data : "btnLike="+btnLike+"&item_id="+<?php echo $item_id;?>,		
				success :function(data){ 				
					jQuery("#divLike").html(data); 
					 fnUnLike(); 
			   }
			});	//End jQuery.ajax	*/				 
		}); // End  $("#username") blur	 
	  }// End fn Like		
		
		 //**************
		 function fnUnLike(){
			jQuery(".btnLike").click(function(){	
				var btnLike = jQuery(this).attr("id");
				 
				jQuery.ajax({			 
					 url : 'jQueryAjax/clickLike.php',
					type:"POST",
					cache: false,
					data : "btnLike="+btnLike+"&item_id="+<?php echo $item_id;?>,		
					success :function(data){ 				
						jQuery("#divLike").html(data); 
						fnLike(); 
				   }
				});	//End jQuery.ajax	*/				 
			}); // End  $("#username") blur	 
		 }// End   fnUnLike
		 
	 //**************
  });
</script>
</body>
</html>