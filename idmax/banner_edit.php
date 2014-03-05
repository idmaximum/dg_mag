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
	 $banner_id = mosGetParam( $_FORM, 'banner_id', '' ); 

	if( isset($action) && !empty($action) && $action == "Edit_banner" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){	

		$title 		= trim(mosGetParam($_FORM,'title'));	
		$url		= trim(mosGetParam($_FORM,'url',''));
		$oldTitleThumb = trim(mosGetParam($_FORM,'oldTitleThumb',''));
		$oldfile = mosGetParam($_FORM,'oldfile',''); 
		$publish		= trim(mosGetParam($_FORM,'publish',''));
		
	    $bannerStartDate	= trim(mosGetParam($_FORM,'bannerStartDate',''));
		$bannerEndDate		= trim(mosGetParam($_FORM,'bannerEndDate',''));
		
		$url 		= str_replace("http://","",$url);
		$url		= "http://$url";
		//Set Parameter for Upload
		#==============================================================================================
		$sServerDir = $_Config_absolute_path."/uploads/banner/";
	
		$sType = "Image";
		$SetMaxSize = '2040000';	// 1MB
		
		#Image Allowed & Denied
		$_Config['AllowedExtensions'][$sType] = array('jpg', 'jpeg', 'gif','png') ;
		$_Config['DeniedExtensions'][$sType] = array('zip', 'pdf', 'php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;		
		$SetFileSize = ' 52428800';	// 200MB
		$SetMaxWidth="$category_size";
		#==============================================================================================
		//UPLOAD File
		$upload1 = FU::uploadEditImage( $_FILES['file1'], $sServerDir, $_Config['AllowedExtensions'][$sType], $_Config['DeniedExtensions'][$sType], $SetMaxSize, $SetMaxWidth, $oldTitleThumb);
		
		$upload2 = FU::uploadEditFile( $_FILES['file2'], $sServerDir, $_Config['AllowedExtensions']['File'], $_Config['DeniedExtensions']['File'], $SetFileSize,$oldfile, 0);	
		//==============================================================================================
		
		if( $upload1["Flag"] == "1" ){//Error
			$errCode = "A100"; //Upload Failed
			$errMsg = $upload1["Msg"];
			$flagSuccess = "0";
		}else{
			$now = DT::currentDateTime();
			
			$qryUpdatePD = "update $_Config_table[banner]
			set  
			banner_name = $DB->qstr('$title'), 
			banner_url = $DB->qstr('$url'), 
			bannerStartDate = $DB->qstr('$bannerStartDate'), 
			bannerEndDate = $DB->qstr('$bannerEndDate'), 
			banner_pic = '$upload1[sFileName]', 
			banner_swf = '$upload2[sFileName]', 
			publish = $DB->qstr('$publish')
			where banner_id='$banner_id'";
			//echo $qryUpdatePD;
			$DB->Execute($qryUpdatePD);	
			$flagSuccess = "1";
		}
		if( $flagSuccess == "1"){
			FU::alert_mesg("แก้ไขข้อมูลสำเร็จ");
			mosRedirect("banner.php?category_id=$category_id#banner");
		} 
	}
	$qry_pd="select * from $_Config_table[banner] where banner_id='$banner_id' ";
    $rspd = $DB->Execute($qry_pd);
    $row = $rspd->FetchRow() ;
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
              <input name="title" type="text" class="validate[required] form-control" id="title" placeholder="ชื่อแบนเนอร์ / Banner Name" value="<?=$row["banner_name"];?>">
            </div>
          </div>
          <div class="form-group">
            <label for="url" class="col-lg-3 control-label">Url *</label>
            <div class="col-lg-9">
              <input name="url" type="text" class="validate[required] form-control" id="url" placeholder="Url" value="<?=$row["banner_url"];?>">
            </div>
          </div>
          
          <div class="form-group">
            <label for="bannerStartDate" class="col-lg-3 control-label">วันที่เริ่มต้นแสดง *</label>
            <div class="col-lg-9">
             
              <input name="bannerStartDate" type="text" class="validate[required] form-control" id="bannerStartDate" placeholder="วันที่เริ่มต้นแสดง" value="<?php echo date("Y-m-d", strtotime($row["bannerStartDate"]));  ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="bannerEndDate" class="col-lg-3 control-label">วันที่สิ้นสุดการแสดง *</label>
            <div class="col-lg-9">
              <input name="bannerEndDate" type="text" class="validate[required] form-control" id="bannerEndDate" placeholder="วันที่สิ้นสุดการแสดง" value="<?php echo date("Y-m-d", strtotime($row["bannerEndDate"]));  ?>">
            </div>
          </div>
          
          <div class="form-group">
            <label for="file1" class="col-lg-3 control-label">รูปประกอบ *</label>
            <div class="col-lg-9">
              <input name="file1" type="file"  id="exampleInputFile">
    <p class="help-block">ขนาดความกว้าง <?php echo $category_size_img_des?> พิกเซล ขนาดไฟล์ไม่เกิน 1 MB. <br />
      (อนุญาติเฉพาะไฟล์ .gif  .png .jpg .jpeg เท่านั้น)</p>
   		 <?php if($row["banner_pic"] != ""){?><div><img src="<?php echo "../uploads/banner/".$row["banner_pic"];?>" /></div><?php }?> 
            </div>
          </div>
          <div class="form-group">
            <label for="file2" class="col-lg-3 control-label">ไฟล์ แฟลต</label>
            <div class="col-lg-9">
              <input name="file2" type="file" id="file2">
    <p class="help-block">ขนาดความกว้าง <?php echo $category_size_img_des?> พิกเซล ขนาดไฟล์ไม่เกิน 1 MB. <br />
      (อนุญาติเฉพาะไฟล์ .swf เท่านั้น)</p>
    		  <?php if($row["banner_swf"] != ""){?><a class="media {width:220, height:220}" href="<?php echo "../uploads/banner/".$row["banner_swf"];?>">SWF File</a><?php }?> 
            </div>
          </div>
            
          <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
             	<input type="hidden" name="action" value="Edit_banner" />
              <input type="hidden" name="banner_id" value="<?=$banner_id;?>" />
              <input type="hidden" name="oldTitleThumb" value="<?=$row["banner_pic"];?>" />
               <input type="hidden" name="oldfile" value="<?=$row["banner_swf"];?>" />
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
<script type="text/javascript" src="../js/swf/jquery.media.js"></script>
<script>
jQuery(document).ready(function(){
	  jQuery('a.media').media();
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
		    minDate: 0,
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 3,
			onClose: function( selectedDate ) {
				jQuery( "#bannerStartDate" ).datepicker( "option", "maxDate", selectedDate );
			}
		});		     
	});
</script>
</body>
</html>