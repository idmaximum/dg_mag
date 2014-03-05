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
	$errCode = 0;
	
	$category_id = mosGetParam( $_FORM, 'category_id', '' );
	 $category_row = $msObj->selectTable("banner_category","category_name,category_size_img_des,category_size", "category_id", $category_id);
	
	 $category_name = $category_row["category_name"];
	 $category_size_img_des = $category_row["category_size_img_des"];
	 $category_size = $category_row["category_size"];
	
	#Get Param
	  $action = mosGetParam( $_FORM, 'action', '' );

	if( isset($action) && !empty($action) && $action == "Add_New" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){	
	
		#Get Param
		 $title 		= trim(mosGetParam($_FORM,'title'));	
		  $url		= trim(mosGetParam($_FORM,'url',''));
		$publish		= trim(mosGetParam($_FORM,'publish',''));
		
		$bannerStartDate	= trim(mosGetParam($_FORM,'bannerStartDate',''));
		$bannerEndDate		= trim(mosGetParam($_FORM,'bannerEndDate',''));
	 
		//Set Parameter for Upload
		#======================================================================
		$sServerDir = $_Config_absolute_path."/uploads/banner/";
		
		$url = str_replace("http://","",$url);
		$url_new = "http://$url";

		$sType = "Image";
		$SetMaxSize = '2040000';	// 1MB
		
		#Image Allowed & Denied
		$_Config['AllowedExtensions'][$sType] = array('jpg', 'jpeg', 'gif','png') ;
		$_Config['DeniedExtensions'][$sType] = array('zip', 'pdf', 'php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;		
	
		$SetMaxWidth="$category_size";
		
		$_Config['AllowedExtensions']['File'] = array( 'swf') ;
		$_Config['DeniedExtensions']['File'] = array('zip','php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi','doc','xls','docx','xlsx') ;
		
		$SetFileSize = ' 52428800';	// 200MB
		#==============================================================================================
		//UPLOAD File
		
		$upload1 = FU::uploadNewImage( $_FILES['file1'], $sServerDir, $_Config['AllowedExtensions'][$sType], $_Config['DeniedExtensions'][$sType], $SetMaxSize, $SetMaxWidth);
		
		$upload2 = FU::uploadNewFile( $_FILES['file2'], $sServerDir, $_Config['AllowedExtensions']['File'], $_Config['DeniedExtensions']['File'], $SetFileSize, 0);
		//==============================================================================================
		
		if( $upload1["Flag"] == "1" ){//Error
			$errCode = "A100"; //Upload Failed
			$errMsg = $upload1["Msg"];
			$flagSuccess = "0";
		}else if( $upload2["Flag"] == "1" ){//Error
				$errCode = "A100"; //Upload Failed
				$errMsg = $upload2["Msg"];
				$flagSuccess = "0";
	 	 }else{ 
			

		 	 $qryInsPD = "insert into  $_Config_table[banner] ( 
			banner_name, banner_url, banner_pic, banner_swf, publish,bannerCategoryID_FK,CreateDate
			,bannerStartDate,bannerEndDate) 
			values(
			$DB->qstr('$title'), 
			$DB->qstr('$url_new'),	
			'$upload1[sFileName]',
			'$upload2[sFileName]',
			$DB->qstr('$publish'),
			$DB->qstr('$category_id'),now(),
			$DB->qstr('$bannerStartDate'),
			$DB->qstr('$bannerEndDate') )";
			
			$DB->Execute($qryInsPD);
			$flagSuccess = "1";
		}
		if( $flagSuccess == "1"){
			FU::alert_mesg("บันทึกข้อมูลสำเร็จ");
			  mosRedirect("banner.php?category_id=$category_id#banner");
		}
		
		
	
	}
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
<link rel="stylesheet" href="../js/jquery-ui/development-bundle/themes/base/jquery.ui.all.css">
<style type="text/css">  
.ui-datepicker{  
    width:180px;  
    font-family:tahoma;  
    font-size:12px;  
    text-align:center;  
}  
</style>  
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
        <li>Add Banner</li>
      </ul>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="70%"><h1 class="pagetitle">Add Banner</h1></td>
          <td width="30%" class="text-center">&nbsp;</td>
        </tr>
      </table>
      <p class="line-brown"></p>
      <div class="detail-page">
       <div class="detail-page">
      <?php if( $errCode != "0" ){ ?>
   
     <h4 class="alert_warning"><?php echo $errMsg; ?></h4>    
      <br /> 
  	<?php } ?>
        <form method="post" enctype="multipart/form-data"  class="form-horizontal form-page formular"id="formID1" role="form">
          <div class="form-group">
            <label for="title" class="col-lg-3 control-label">ชื่อแบนเนอร์ *</label>
            <div class="col-lg-9">
              <input name="title" type="text" class="validate[required] form-control" id="title" placeholder="ชื่อแบนเนอร์ / Banner Name">
            </div>
          </div>
          <div class="form-group">
            <label for="url" class="col-lg-3 control-label">Url *</label>
            <div class="col-lg-9">
              <input name="url" type="text" class="validate[required] form-control" id="url" placeholder="Url">
            </div>
          </div>
          
            <div class="form-group">
            <label for="bannerStartDate" class="col-lg-3 control-label">วันที่เริ่มต้นแสดง *</label>
            <div class="col-lg-9">
              <input name="bannerStartDate" type="text" class="validate[required] form-control" id="bannerStartDate" placeholder="วันที่เริ่มต้นแสดง">
            </div>
          </div>
            <div class="form-group">
            <label for="bannerEndDate" class="col-lg-3 control-label">วันที่สิ้นสุดการแสดง *</label>
            <div class="col-lg-9">
              <input name="bannerEndDate" type="text" class="validate[required] form-control" id="bannerEndDate" placeholder="วันที่สิ้นสุดการแสดง">
            </div>
          </div>
          
          <div class="form-group">
            <label for="file1" class="col-lg-3 control-label">รูปประกอบ *</label>
            <div class="col-lg-9">
              <input name="file1" type="file" class="validate[required]" id="exampleInputFile">
    <p class="help-block">ขนาดความกว้าง <?php echo $category_size_img_des?> พิกเซล ขนาดไฟล์ไม่เกิน 1 MB. <br />
      (อนุญาติเฉพาะไฟล์ .gif  .png .jpg .jpeg เท่านั้น)</p>
            </div>
          </div>
          <div class="form-group">
            <label for="file2" class="col-lg-3 control-label">ไฟล์ แฟลต</label>
            <div class="col-lg-9">
              <input name="file2" type="file" id="file2">
    <p class="help-block">ขนาดความกว้าง <?php echo $category_size_img_des?> พิกเซล ขนาดไฟล์ไม่เกิน 1 MB. <br />
      (อนุญาติเฉพาะไฟล์ .swf เท่านั้น)</p>
            </div>
          </div>
            
          <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
               <input type="hidden" name="action" value="Add_New" />
              <input name="category_id" type="hidden" id="category_id" value="<? echo $category_id?>"/> 
              <input type="submit" name="" value="บันทึก | Save" class="btn btn-primary" /> 
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
	  
	  //**************
	  jQuery( "#bannerStartDate" ).datepicker({
			defaultDate: "+1w",
			minDate: 0,
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			numberOfMonths: 3,
			onClose: function( selectedDate ) {
				jQuery( "#bannerEndDate" ).datepicker( "option", "minDate", selectedDate );
			}
		});
		jQuery( "#bannerEndDate" ).datepicker({
			defaultDate: "+1w", 
			changeMonth: true,
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 3,
			onClose: function( selectedDate ) {
				jQuery( "#bannerStartDate" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
	  //**************	     
	});
</script>
</body>
</html>