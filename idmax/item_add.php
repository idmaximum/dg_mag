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
	$category_id = mosGetParam( $_FORM, 'category_id', '' );
	$sub_category_id = mosGetParam( $_FORM, 'sub_category_id', '' ); 	
	
	$category_name = $msObj->selectTable("item_category","category_name", "category_id", $category_id);
	$category_name = $category_name["category_name"];
	
	$subCategoryName = $msObj->selectTable("item_subcategory","sub_category_name", "sub_category_id", $sub_category_id);
	$subCategoryName = $subCategoryName["sub_category_name"];
	
	if( isset($action) && !empty($action) && $action == "Add_item" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){	
		
		#Set Up for upload about us picture.
		#=========================================================================
		$sServerDir = $_Config_absolute_path."/uploads/item/";

		$sType = "Image";
		$SetMaxSize = '22048576';// 1MB
		
		#Image Allowed & Denied
		$_Config['AllowedExtensions'][$sType] = array('png','jpg', 'jpeg', 'gif') ;
		$_Config['DeniedExtensions'][$sType] = array( 'zip', 'pdf', 'php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;		
	
		$SetMaxWidth = "3000";	
		$SetReduceWidth = "220";
		$SetReduceHeight = "192";	
		#=========================================================================		
		$item_title = mosGetParam( $_FORM, 'item_title', '');	
		$item_youtube = mosGetParam( $_FORM, 'item_youtube', '');		
		$item_abstract = mosGetParam( $_FORM, 'item_abstract', '');
		$item_detail = mosGetParam( $_FORM, 'item_detail', '', 2 );
		
		$item_title_en = mosGetParam( $_FORM, 'item_title_en', '');		
		$item_abstract_en = mosGetParam( $_FORM, 'item_abstract_en', '');
		$item_detail_en = mosGetParam( $_FORM, 'item_detail_en', '', 2 );
		
		$item_date = mosGetParam( $_FORM, 'item_date', '');
	  	$item_publish = mosGetParam( $_FORM, 'item_publish', '');
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
			"insert into $_Config_table[item] (item_title, item_abstract, item_detail, 
			  item_image, item_image_thumb, 
			  item_category_id_FK, item_sub_category_id_FK, 
			  item_publish, item_create_date, item_create_user,item_update_date,item_youtube) 
			values
			($DB->qstr('$item_title'), $DB->qstr('$item_abstract'), $DB->qstr('$item_detail'),  
			'$upload1[sFileName]','$upload1[sThumbnail]', 
			 $DB->qstr('$category_id'), $DB->qstr('$sub_category_id'),  
			'$item_publish', now(), '$_SESSION[_ID]',  now(), $DB->qstr('$item_youtube'))";
			 // echo $qryHeader;
			$DB->Execute($qryHeader);	
			 		
			$flagSuccess = "1";
		}
		
		if( $flagSuccess == "1")
		{
			FU::alert_mesg("บันทึกข้อมูลเรียบร้อยแล้ว");
		   mosRedirect("item.php?category_id=$category_id&sub_category_id=$sub_category_id#$category_name");
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
        <li><?php echo $category_name?> <?php if($subCategoryName!=''){ echo "&gt; ".$subCategoryName;}?></li>
      </ul>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="70%"><h1 class="pagetitle"><?php echo $category_name?> <?php if($subCategoryName!=''){ echo "&gt; ".$subCategoryName;}?></h1></td>
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
            <label for="item_title" class="col-lg-3 control-label">หัวเรื่อง</label>
            <div class="col-lg-9">
              <input name="item_title" type="text" class="validate[required] form-control" id="item_title" placeholder="หัวเรื่อง" value="<?php echo $item_title?>">
            </div>
          </div>
          <?php if($languageMulti == 2){?>
          <div class="form-group">
            <label for="item_title_en" class="col-lg-3 control-label">หัวเรื่อง(EN)</label>
            <div class="col-lg-9">
              <input name="item_title_en" type="text" class="validate[required] form-control" id="item_title_en" placeholder="หัวเรื่อง (EN)" value="<?php echo $item_title?>">
            </div>
          </div>
          <?php }?>
          <div class="form-group">
            <label for="item_abstract" class="col-lg-3 control-label">รายละเอียด</label>
            <div class="col-lg-9"> 
              <textarea name="item_abstract" id="item_abstract" cols="90" rows="10" class="validate[required] form-control"  placeholder="รายละเอียด"><?php echo $item_abstract?></textarea>
            </div>
          </div>
          <?php if($languageMulti == 2){?>
          <div class="form-group">
            <label for="item_abstract_en" class="col-lg-3 control-label">รายละเอียด(EN)</label>
            <div class="col-lg-9"> 
              <textarea name="item_abstract_en" id="item_abstract_en" cols="90" rows="5" class="validate[required] form-control"  placeholder="คำเกริ่น(EN)"><?php echo $item_abstract?></textarea>
            </div>
          </div>
           <?php }?>
           
             <div class="form-group">
            <label for="item_youtube" class="col-lg-3 control-label">Link Youtube</label>
            <div class="col-lg-9">
              <input name="item_youtube" type="text" class="form-control" id="item_youtube" placeholder="ตัวอย่าง http://www.youtube.com/watch?v=jT_TOhyfEXE" value="<?php echo $item_youtube?>">
            </div>
          </div>
           
           
          <div class="form-group">
            <label for="exampleInputFile" class="col-lg-3 control-label">อัพโหลดรูปประกอบ</label>
            <div class="col-lg-9">
              <input name="file1" type="file" id="exampleInputFile">
    <p class="help-block">ขนาดไฟล์ไม่เกิน 2 MB. (อนุญาติเฉพาะไฟล์ .gif  .png .jpg .jpeg เท่านั้น)</p>
            </div>
          </div>
       
          <div class="form-group">
            <label for="item_title" class="col-lg-3 control-label">เผยแพร่</label>
            <div class="col-lg-9">
               <input name="item_publish" type="radio" id="item_publish" value="1" checked="checked" />
	      ใช่, ตอนนี้
	      <input type="radio" name="item_publish" id="item_publish" value="0" />
	      ไม่, ยังไม่ใช่ตอนนี้
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
              <input type="hidden" name="action" value="Add_item" />
              <input type="submit"  value="บันทึก | Save" class="btn btn-primary" />
             <input name="category_id" type="hidden" id="category_id" value="<? echo $category_id?>"/> 
             <input name="sub_category_id" type="hidden" id="sub_category_id" value="<? echo $sub_category_id?>"/>  
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