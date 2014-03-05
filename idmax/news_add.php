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
	$errCode = '0';
	//=======================================================
	$_id = mosGetParam( $_FORM, '_id', '' );
	  $action = mosGetParam( $_FORM, 'action', '' );	 	
	
	if( isset($action) && !empty($action) && $action == "Add_News" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){	
		
		#Set Up for upload about us picture.
		#=========================================================================
		$sServerDir = $_Config_absolute_path."/uploads/news/";

		$sType = "Image";
		$SetMaxSize = '1048576';// 1MB
		
		#Image Allowed & Denied
		$_Config['AllowedExtensions'][$sType] = array('png','jpg', 'jpeg', 'gif') ;
		$_Config['DeniedExtensions'][$sType] = array( 'zip', 'pdf', 'php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;		
	
		$SetMaxWidth = "800";	
		$SetReduceWidth = "137";
		$SetReduceHeight = "186";			
		#=========================================================================		
		$news_title = mosGetParam( $_FORM, 'news_title', '');		
		$news_abstract = mosGetParam( $_FORM, 'news_abstract', '');
		$news_detail = mosGetParam( $_FORM, 'news_detail', '', 2 );
		 
		$subscribe = mosGetParam( $_FORM, 'subscribe', '');	
		$issue_ebook = mosGetParam( $_FORM, 'issue_ebook', '');	
		$issue_pdf = mosGetParam( $_FORM, 'issue_pdf', '');	
		$take_a_peak = mosGetParam( $_FORM, 'take_a_peak', '');	
		
		$news_title_en = mosGetParam( $_FORM, 'news_title_en', '');		
		$news_abstract_en = mosGetParam( $_FORM, 'news_abstract_en', '');
		$news_detail_en = mosGetParam( $_FORM, 'news_detail_en', '', 2 );
		
		$news_date = mosGetParam( $_FORM, 'news_date', '');
	  	$news_publish = mosGetParam( $_FORM, 'news_publish', '');
		$now = DT::currentDateTime();

		#Upload New About Picture
		$upload1 = FU::uploadNewImage_cropXY( 
			$_FILES['file1'], 
			$sServerDir, 
			$_Config['AllowedExtensions'][$sType], 
			$_Config['DeniedExtensions'][$sType], 
			$SetMaxSize, 
			$SetMaxWidth, 
			$SetReduceWidth, 
			$SetReduceHeight);
		
		if( $upload1["Flag"] == "1" ){//Error
			$errCode = "A100"; //Upload Failed
			$errMsg = $upload1["Msg"];
			$flagSuccess = "0";
		}else{
			#Insert Record
			$now = DT::currentDateTime();
			$qryHeader = 
			"insert into $_Config_table[news] (news_title, news_abstract, news_detail,
			subscribe,issue_ebook,issue_pdf,take_a_peak, 
			news_title_en, news_abstract_en, news_detail_en,
			  news_image, news_image_thumb, 
			  news_publish, news_create_date, news_create_user,news_date) 
			values($DB->qstr('$news_title'), $DB->qstr('$news_abstract'), $DB->qstr('$news_detail'), 
			
			$DB->qstr('$subscribe'), $DB->qstr('$issue_ebook'), $DB->qstr('$issue_pdf'), $DB->qstr('$take_a_peak'), 
			
			$DB->qstr('$news_title_en'), $DB->qstr('$news_abstract_en'), $DB->qstr('$news_detail_en'),
			'$upload1[sFileName]','$upload1[sThumbnail]', 
			'$news_publish', $db->DBTimeStamp('$now'), '$_SESSION[_ID]', $DB->qstr('$news_date'))";
			//echo $qryHeader;
			$DB->Execute($qryHeader);	
			
			//$news_id = $DB->Insert_ID();			
			$flagSuccess = "1";
		}
		
		if( $flagSuccess == "1")
		{
			FU::alert_mesg("บันทึกข้อมูลเรียบร้อยแล้ว");
			mosRedirect("news.php#news");
		}	
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?=$_Config_sitename?>
's Backoffice</title>
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
          <td width="70%"><h1 class="pagetitle">เพิ่มปกหนังสือ</h1></td>
          <td width="30%" class="text-center">&nbsp;</td>
        </tr>
      </table>
      <p class="line-brown"></p>
      <div class="detail-page">
      <?php if( $errCode != "0" ){ ?>
   
     <h4 class="alert_warning"><?php echo $errMsg; ?></h4>    
      <br /> 
  	<?php } ?>
        <form method="post" enctype="multipart/form-data"  class="form-horizontal form-page formular"id="formID1" role="form">
          <div class="form-group">
            <label for="news_title" class="col-lg-3 control-label">หัวเรื่อง</label>
            <div class="col-lg-9">
              <input name="news_title" type="text" class="validate[required] form-control" id="news_title" placeholder="ตัวอย่างเช่น vol. 14 no. 137" value="<?php echo $news_title?>">
            </div>
          </div>
          <div class="form-group">
            <label for="news_abstract" class="col-lg-3 control-label">ประจำเดือน</label>
            <div class="col-lg-9">
              <input name="news_abstract" type="text" class="validate[required] form-control" id="news_abstract" placeholder="ตัวอย่างเช่น February 2014" value="<?php echo $news_abstract?>">
            </div>
          </div>
          <div class="form-group">
            <label for="take_a_peak" class="col-lg-3 control-label">Take a peak</label>
            <div class="col-lg-9">
              <input name="take_a_peak" type="text" class="validate[required] form-control" id="take_a_peak" placeholder="ตัวอย่างเช่น http://www.home.co.th/EMagazine/DecorationGuide/dg137/index.html" value="<?php echo $take_a_peak?>">
            </div>
          </div>          
        <? /*    <div class="form-group">
            <label for="issue_ebook" class="col-lg-3 control-label">Buy Now</label>
            <div class="col-lg-9">
              <input name="issue_ebook" type="text" class="validate[required] form-control" id="issue_ebook" placeholder="E-book" value="<?php echo $issue_ebook?>">
            </div>
          </div>          
            <div class="form-group">
            <label for="subscribe" class="col-lg-3 control-label">Subscribe Now</label>
            <div class="col-lg-9">
              <input name="subscribe" type="text" class="validate[required] form-control" id="subscribe" placeholder="subscribe" value="<?php echo $subscribe?>">
            </div>
          </div>*/?>         
          <div class="form-group">
            <label for="exampleInputFile" class="col-lg-3 control-label">อัพโหลดรูปหน้าปก</label>
            <div class="col-lg-9">
              <input name="file1" type="file" id="exampleInputFile">
    <p class="help-block">ขนาดความกว้าง 800x600 พิกเซล ขนาดไฟล์ไม่เกิน 1 MB. (อนุญาติเฉพาะไฟล์ .gif  .png .jpg .jpeg เท่านั้น)</p>
            </div>
          </div>
            
       
          <div class="form-group">
            <label for="news_publish" class="col-lg-3 control-label">เผยแพร่</label>
            <div class="col-lg-9">
               <input name="news_publish" type="radio" id="news_publish" value="1" checked="checked" />
	      ใช่, ตอนนี้
	      <input type="radio" name="news_publish" id="news_publish" value="0" />
	      ไม่, ยังไม่ใช่ตอนนี้
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
              <input type="hidden" name="action" value="Add_News" />
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