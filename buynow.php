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
	
    $news_id = mosGetParam($_FORM,'news_id','');
	
	//**************  start insert ***********
	$action = trim(mosGetParam($_FORM,'action',''));
	$url = trim(mosGetParam($_REQUEST,'url',''));  
	
	 if($url == ""){# เช็ดดูว่าใช่ spam หรือป่าว ############### Vote	
		if( isset($action) && !empty($action) && $action == "commentWebboard" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){    
			
			$buynow_order = trim(mosGetParam($_FORM,'buynow_order',''));
			$name = trim(mosGetParam($_FORM,'name',''));
			$tel = trim(mosGetParam($_FORM,'tel',''));
			$email = trim(mosGetParam($_FORM,'email',''));
			$address = trim(mosGetParam($_FORM,'address',''));
			$detail = trim(mosGetParam($_FORM,'detail',''));
			$Rip = FU::GetIP();
			
			$qryAddBuyNow =  "insert into $_Config_table[buynow]
								  ( newsID_FK, buynow_name,buynow_email,buynow_tel,
								   buynow_address,buynow_detail,buynow_create_date,buynow_ip,buynow_order) 
							 	 values
						 	   ( $DB->qstr('$news_id'),$DB->qstr('$name'),$DB->qstr('$email'),$DB->qstr('$tel')
							   ,$DB->qstr('$address'),$DB->qstr('$detail') ,now(),$DB->qstr('$Rip'),$DB->qstr('$buynow_order') )";
			 $DB->Execute($qryAddBuyNow);	
			 
			  mosRedirect("buynowComplate.php");
			
		}#end if
	 }#end if 
	//*************** End insert ***************
	
	 
	
	$qryDetail = "select news_image_thumb,news_title,take_a_peak,news_id,news_abstract
				 from $_Config_table[news] where news_id = '$news_id'";
	$rsDetail = $DB->Execute($qryDetail );
	$detailnews = $rsDetail->FetchRow($rsDetail);	
	
	$news_title = $detailnews["news_title"];
	 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet"href="js/bootstrap/css/bootstrap_2.css" >
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/font.css" />
<link rel="stylesheet" href="js/formValidator/css/validationEngine.jquery.css" type="text/css"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<style type="text/css">
@font-face {
	font-family: 'HelveticaNeue-Bold';
	src: url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.eot?') format('eot'), url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.woff') format('woff'), url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.ttf') format('truetype'), url('js/HelveticaNeue-Bold/HelveticaNeue-Bold.svg#HelveticaNeue-Bold') format('svg');
}
.font-gentfont {
	font-family: "HelveticaNeue-Bold";
	direction: ltr;
}
body {
	background-color: #efefef
}
.main-buynow {
	padding: 20px;
	margin: auto;
	width: 560px;
}
.form-control {
	height: 24px
}
.formError {
	left: 200px !important;
} .form-group{ margin-bottom:10px}
</style>
</head>

<body>
<div class="main-buynow">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:8px">
    <tr>
      <td width="7%"><img src="idmax/images/png/glyphicons_202_shopping_cart.png" width="26" height="23"> &nbsp;</td>
      <td width="93%" style="padding-top:16px"><span class="txtBlack18 font-gentfont">Buy Now</span></td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="31%" align="left" valign="top"><p><img src="uploads/news/<?php echo $detailnews["news_image_thumb"];?>" alt="<?php echo $detailnews["news_title"];?>" width="137" height="186"> </p>
        <p class="txtpink12" style="padding:10px 0"><strong><?php echo  $detailnews["news_title"] ;?></strong></p>
        <p class="txtBlack14"><strong><?php echo  $detailnews["news_abstract"] ;?></strong></p></td>
      <td width="69%" valign="top">
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="100%" style=" ">
            <form action="" method="post" id="formID"  role="form">
              <div class="form-group"> 
               
               <select class="validate[required] form-control" name="buynow_order" id="buynow_order" style="height:32px; width:325px">
                <option value="">---เลือกการสั่งซื้อ---</option>
                <option value="สั่งซื้อรายเดือน">สั่งซื้อรายเดือน</option>
                <option value="สั่งซื้อรายปี">สั่งซื้อรายปี</option> 
              </select>
              </div>
              <div class="form-group"> 
               <input name="name" type="text" class="validate[required] form-control width300" id="name" placeholder="Name" value="<?php echo $_SESSION['NameDisplay']?>">
              </div>
              <div class="form-group"> 
               <input name="tel" type="text" class="validate[required] form-control width300" id="tel" placeholder="Tel">
              </div>
              <div class="form-group"> 
               <input name="email" type="email" class="validate[required,custom[email]] form-control width300" id="inputEmail3" placeholder="Email" value="<?php echo $_SESSION['_EMAIL']?>">
              </div>
              <div class="form-group"> 
              <textarea name="address" rows="3" class="validate[required] form-control width300" id="address"  placeholder="Address"></textarea>
              </div>
              <div class="form-group"> 
              <textarea name="detail" rows="3" class="form-control width300" id="detail"  placeholder="Detail"></textarea>
              </div>
               <div class="form-group"> 
                <input class="btn btn-primary  btn-block width300 "  type="submit" value="Buy Now"  >
              </div> 
             <input type="hidden" name="news_id" value="<?php echo $news_id; ?>" />
             <input name="action" type="hidden" value="commentWebboard" />
             <input name="url" type="text" class="url" />
            </form>
            
            </td>
          </tr>
        </table> </td>
    </tr>
  </table>
</div>
<script type="text/javascript" src="js/jquery-1.8.js"></script> 
<script src="js/formValidator/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/formValidator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
<script>
  jQuery(document).ready(function(){
	  // binds form submission and fields to the validation engine
	  jQuery("#formID").validationEngine(); 
	  
  });
</script>
</body>
</html>