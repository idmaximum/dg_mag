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
    $commentID = mosGetParam($_FORM,'commentID','');
	
	//**************  start insert ***********
	$action = trim(mosGetParam($_FORM,'action',''));
	$url = trim(mosGetParam($_REQUEST,'url','')); 
	$customerID = $_SESSION['_CUTOMERID'];	
	
	//***********
	  $qryDetailPin = "select comment from $_Config_table[pin_article] where commentID = '$commentID'";
	$rsDetailPin = $DB->Execute($qryDetailPin);
	$detailitemPin = $rsDetailPin->FetchRow();
	
	$comment = $detailitemPin["comment"];
	
	 //*************** item
	$qryDetail = "select * from $_Config_table[item] where item_id = '$item_id'";
	$rsDetail = $DB->Execute($qryDetail );
	$detailitem = $rsDetail->FetchRow($rsDetail);	
	
	$item_title = $detailitem["item_title"];
	 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $item_title?></title> 
<link rel="stylesheet"href="js/bootstrap/css/bootstrap.css" >
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/font.css" /> 
<link href="css/itemDetail.css" rel="stylesheet" type="text/css" />
 <link href="js/bxslider-4-master/jquery.bxslider_2.css" rel="stylesheet" />
</head> 
<body> 
<div id="loading-page"></div>
<div id="main-content">
  <p class="txtBlack18 font-gentfont"><?php echo $item_title?></p>
  <p>&nbsp;</p>
  <p style="text-align:center"></p>
  <ul class="bxslider"> 
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
      <td width="10%" align="center">
   
       <?php
            	$qryPostMember = "select customer_fname,customer_img,primary_email,fbID
							from $_Config_table[member] 
					        where customer_id ='$customerID'
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
      </td>
      <td width="90%" style="padding-left:5px"> <p class="txtBlack14">
    
    		 <?php if($spPostMember['customer_fname'] != ""){?>
               <?php echo $spPostMember['customer_fname']?>
            <?php }else{?>
              <?php echo $spPostMember['primary_email']?>
            <?php }?>
     
      </p>
      <p class="txtblue14" style="padding-top:10px"><?php   echo $comment?></p></td> 
     </tr>
  </table>
   
  <p>&nbsp;</p>
</div> 
<script type="text/javascript" src="js/jquery-1.8.js"></script>  
<script src="js/bxslider-4-master/jquery.bxslider.min.js"></script> 
<script type="text/javascript" src="js/jquery.corner.js"></script>
<script>
  jQuery(document).ready(function(){
	  // binds form submission and fields to the validation engine
	  jQuery("#loading-page").show(100).delay(800).fadeOut(600);
	  
	  jQuery('.bxslider').bxSlider({
		auto: true ,
		pager : false,
		pause : 10000
	  });
	 
  });
</script>
</body>
</html>