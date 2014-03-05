<?php
	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "../configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	require_once( $_Config_absolute_path . "/includes/func.class.php" ); 

	#Login ?
	if( empty($_SESSION['_LOGIN']) || empty($_SESSION['_GRPID']) || empty($_SESSION['_ID'])){
		mosRedirect("_execlogout.php");
		exit();
	}
	
	$DB = mosConnectADODB();
	$msObj = new MS($DB);
	
	//=======================================================
	$_id = mosGetParam( $_FORM, '_id', '' ); 	
	$action = mosGetParam( $_FORM, 'action', '' );	 
	$news_id = mosGetParam( $_FORM, 'news_id', '' );
	
	if( isset($action) && !empty($action) && $action == "Edit_news" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){	
		
		#Set Up for upload about us picture.
		#=========================================================================
		$sServerDir = $_Config_absolute_path."/uploads/news/";

		$sType = "Image";
		$SetMaxSize = '1048576';// 1MB
		
		#Image Allowed & Denied
		$_Config['AllowedExtensions'][$sType] = array('png','jpg', 'jpeg', 'gif') ;
		$_Config['DeniedExtensions'][$sType] = array('png', 'zip', 'pdf', 'php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;		
	
		$SetMaxWidth = "800";	
		$SetReduceWidth = "137";
		$SetReduceHeight = "186";		
		#=========================================================================		
		$news_title = mosGetParam( $_FORM, 'news_title', '', 2 );		
		$news_abstract = mosGetParam( $_FORM, 'news_abstract', '', 2 );
		$news_detail = mosGetParam( $_FORM, 'news_detail', '', 2 );
		
		$news_title_en = mosGetParam( $_FORM, 'news_title_en', '', 2 );		
		$news_abstract_en = mosGetParam( $_FORM, 'news_abstract_en', '', 2 );
		$news_detail_en = mosGetParam( $_FORM, 'news_detail_en', '', 2 );
		
		$news_date = mosGetParam( $_FORM, 'news_date', '');
	  	$news_publish = mosGetParam( $_FORM, 'news_publish', '');		
		$oldTitleImage = trim(mosGetParam($_FORM,'oldTitleImage',''));
		$oldTitleThumb = trim(mosGetParam($_FORM,'oldTitleThumb',''));
		
		$subscribe = mosGetParam( $_FORM, 'subscribe', '');	
		$issue_ebook = mosGetParam( $_FORM, 'issue_ebook', '');	
		$issue_pdf = mosGetParam( $_FORM, 'issue_pdf', '');	
		$take_a_peak = mosGetParam( $_FORM, 'take_a_peak', '');	

		#Upload New About Picture
		$upload1 = FU::uploadEditImage_cropXY( 
			$_FILES['file1'], 
			$sServerDir, 
			$_Config['AllowedExtensions'][$sType], 
			$_Config['DeniedExtensions'][$sType], 
			$SetMaxSize, 
			$SetMaxWidth, 
			$SetReduceWidth, 
			$SetReduceHeight,
			$oldTitleThumb,
			$oldTitleImage);		
			
		if( $upload1["Flag"] == "1" ){//Error
			$errCode = "A100"; //Upload Failed
			$errMsg = $upload1["Msg"];
			$flagSuccess = "0";
		}else{
		 
			#Insert Record
			$now = DT::currentDateTime();
			$qryHeader = 
			"update $_Config_table[news] set 
			news_title=$DB->qstr('$news_title'), 
			news_abstract=$DB->qstr('$news_abstract'), 
			news_detail=$DB->qstr('$news_detail'),
			
			subscribe=$DB->qstr('$subscribe'),
			issue_ebook=$DB->qstr('$issue_ebook'),
			issue_pdf=$DB->qstr('$issue_pdf'),
			take_a_peak=$DB->qstr('$take_a_peak'),
			
			news_title_en=$DB->qstr('$news_title_en'), 
			news_abstract_en =$DB->qstr('$news_abstract_en'), 
			news_detail_en=$DB->qstr('$news_detail_en'),
			
			news_date=$DB->qstr('$news_date'),			
			news_image='$upload1[sFileName]', 
			news_image_thumb='$upload1[sThumbnail]',
			news_publish='$news_publish', 
			news_update_date=$db->DBTimeStamp('$now'), 
			news_update_user= '$_SESSION[_ID]' where news_id='$news_id'";
			//echo $qryHeader;
			$DB->Execute($qryHeader);	
			$flagSuccess = "1";
		}
		
		if( $flagSuccess == "1")
		{
			FU::alert_mesg("บันทึกข้อมูลเรียบร้อยแล้ว");
			mosRedirect("news.php#news");
		}	
	}

	
	$qryDetail = "select * from $_Config_table[news] where news_id = '$news_id'";
	//echo $qryDetail;
	$rsDetail = $DB->Execute($qryDetail );
	$detail = $rsDetail->FetchRow($rsDetail);	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$_Config_sitename?>'s Backoffice</title>
<link rel="stylesheet" href="../css/font.css">
<link rel="stylesheet" href="../js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../js/bootstrap/css/bootstrap-theme.css">
<link rel="stylesheet" href="../css/css-bof.css"type="text/css">
<link rel="stylesheet" href="accordion/style.css"type="text/css"  media="screen">
<?php include("inc-bof-header-top.php");?>
</head>
<body>
<div id="main">
  <?php include("inc-bof-heder.php");?>
  <div class="content">
    <?php include("inc-bof-menuleft.php");?>
    <div class="content-bof-right">
      <ul class="breadcrumbs">
        <li><a href="index.php">Home &gt;<i class="iconfa-home"></i></a> <span class="separator"></span></li>
        <li>ISSUE</li>
      </ul>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="70%"><h1 class="pagetitle">แก้ไขข้อมูล</h1></td>
          <td width="30%" class="text-center">&nbsp;</td>
        </tr>
      </table>
      <p class="line-brown"></p>
      <div class="detail-page">
        <form method="post" enctype="multipart/form-data"  class="form-horizontal form-page formular"id="formID1" role="form">
          <div class="form-group">
            <label for="news_title" class="col-lg-3 control-label">หัวเรื่อง</label>
            <div class="col-lg-9">
              <input name="news_title" type="text" class="validate[required] form-control" id="news_title" value="<?php echo clearText($detail["news_title"]); ?>"placeholder="หัวเรื่อง">
            </div>
          </div>
          <div class="form-group">
            <label for="news_abstract" class="col-lg-3 control-label">ประจำเดือน</label>
            <div class="col-lg-9">
              <input name="news_abstract" type="text" class="validate[required] form-control" id="news_abstract" value="<?php echo clearText($detail["news_abstract"]); ?>"placeholder="หัวเรื่อง">
            </div>
          </div>
          
          <div class="form-group">
            <label for="take_a_peak" class="col-lg-3 control-label">Take a peak</label>
            <div class="col-lg-9">
              <input name="take_a_peak" type="text" class="validate[required] form-control" id="take_a_peak" value="<?php echo clearText($detail["take_a_peak"]); ?>"placeholder="Take a peak">
            </div>
          </div>
             <?  /*
            <div class="form-group">
            <label for="news_title_en" class="col-lg-3 control-label">Buy Now</label>
            <div class="col-lg-9">
              <input name="issue_ebook" type="text" class="validate[required] form-control" id="issue_ebook" value="<?php echo clearText($detail["issue_ebook"]); ?>"placeholder="E-book">
            </div>
          </div>          
     		<div class="form-group">
            <label for="issue_pdf" class="col-lg-3 control-label">PDF</label>
            <div class="col-lg-9">
              <input name="issue_pdf" type="text" class="validate[required] form-control" id="issue_pdf" value="<?php echo clearText($detail["issue_pdf"]); ?>"placeholder="PDF">
            </div>
          </div>        
            <div class="form-group">
            <label for="subscribe" class="col-lg-3 control-label">Subscribe Now</label>
            <div class="col-lg-9">
              <input name="subscribe" type="text" class="validate[required] form-control" id="subscribe" value="<?php echo clearText($detail["subscribe"]); ?>"placeholder="subscribe">
            </div>
          </div>
          */ ?> 
          
          <div class="form-group">
            <label for="exampleInputFile" class="col-lg-3 control-label">อัพโหลดรูปปกหนังสือ</label>
            <div class="col-lg-9">
              <input name="file1" type="file" id="exampleInputFile">
              <p class="help-block">ขนาดความกว้าง 800x600 พิกเซล ขนาดไฟล์ไม่เกิน 1 MB. (อนุญาติเฉพาะไฟล์ .gif  .png .jpg .jpeg เท่านั้น)</p>
              (หากต้องการเปลี่ยนรูปที่อัพโหลดไปแล้ว ให้อัพโหลดรูปใหม่ไปแทน)<br />
              <?php if( !empty($detail["news_image_thumb"])){ ?>
              <table width="74" border="0">
                <tr>
                  <td align="center"><img src="<?php echo "../uploads/news/".$detail["news_image_thumb"]; ?>" /></td>
                </tr>
                <tr>
                  <td align="center">รูปปัจจุบัน</td>
                </tr>
              </table>
              <?php } ?>
            </div>
          </div>
         
          <div class="form-group">
            <label for="news_title" class="col-lg-3 control-label">เผยแพร่</label>
            <div class="col-lg-9">
              <input name="news_publish" type="radio" id="news_publish" value="1" <?php echo ($detail["news_publish"]=="1")? "checked" : ""; ?> />
              ใช่, ตอนนี้
              <input type="radio" name="news_publish" id="news_publish" value="0"<?php echo ($detail["news_publish"]=="0")? "checked" : ""; ?>  />
              ไม่, ยังไม่ใช่ตอนนี้ </div>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
              <input type="hidden" name="action" value="Edit_news" />
              <input type="hidden" name="news_id" value="<?php echo $detail["news_id"]; ?>" />
              <input type="hidden" name="oldTitleImage" value="<?php echo $detail["news_image"]; ?>" />
              <input type="hidden" name="oldTitleThumb" value="<?php echo $detail["news_image_thumb"]; ?>" />
              <input type="submit"  value="บันทึก | Save" class="btn btn-primary" />
            </div>
          </div>
        </form>
      </div>
      <p class="line-brown clear"></p>
    </div>
    <p class="clear"></p>
  </div>
  <p class="clear"></p>
  <?php include("inc-bof-footer.php");?>
</div>
<?php include("inc-script.php");?>
<script>
jQuery(document).ready(function(){
	  // binds form submission and fields to the validation engine
	  jQuery("#formID1").validationEngine('attach', {promptPosition : "topLeft"});		     
	});
</script>
</body>
</html>