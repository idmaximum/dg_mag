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
	$errCode = '0';
	$flagSuccess = 0;
	
	 if( empty($_SESSION['_CUTOMERID']) || empty($_SESSION['_USERNAME']) ){
		mosRedirect("login.php");
		exit();
	}
	
	 $action = trim(mosGetParam($_REQUEST,'action',''));
	 $url = trim(mosGetParam($_REQUEST,'url','')); 
	  if($url == "" ){# เช็ดดูว่าใช่ spam หรือป่าว ############### Vote	
		if( isset($action) && !empty($action) && $action == "addPost" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){ 
		
		$title = trim(mosGetParam($_FORM,'title',''));
		$itemSubCategory = trim(mosGetParam($_FORM,'itemSubCategory',''));
		$description = trim(mosGetParam($_FORM,'description',''));	
		$customerID = $_SESSION['_CUTOMERID'];		
		
		$sServerDir = $_Config_absolute_path."/uploads/item/";

		$sType = "Image";
		$SetMaxSize = '30048576';// 1MB
		
		#Image Allowed & Denied
		$_Config['AllowedExtensions'][$sType] = array('png','jpg', 'jpeg', 'gif') ;
		$_Config['DeniedExtensions'][$sType] = array( 'zip', 'pdf', 'php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;		
	
		$SetMaxWidth = "4500";	
		$SetReduceWidth = "220";
		$SetReduceHeight = "192";	
		
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
			$catetogoryPost = 4; // 4= Like & Share
			
			$qryHeader = 
			"insert into $_Config_table[item] 
			(item_title, item_category_id_FK,item_sub_category_id_FK, item_abstract, memberID_FK,			
			  item_image, item_image_thumb, 			  
			  item_create_date,item_publish) 
			values
			($DB->qstr('$title'),'4', $DB->qstr('$itemSubCategory'),$DB->qstr('$description'),$DB->qstr('$customerID'), 
			'$upload1[sFileName]','$upload1[sThumbnail]', 
			 now(), '1')";
			 // echo $qryHeader;
			$DB->Execute($qryHeader);	
			
			$flagSuccess = "1";
		}#end Uploads
		 
		if( $flagSuccess  == "1")
		{
			 FU::alert_mesg("You have successfully posted."); 
			   mosRedirect("item.php?item_category_id=4");
		}	
		
		}//end isset($action)*********************** 
	 }#end if
	
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
<link rel="stylesheet" href="js/formValidator/css/validationEngine.jquery.css" type="text/css"/>
<style type="text/css">
.pic-img {
	background-image: url(images/bg_post_img.png);
	background-repeat: no-repeat;
	background-position: left top;
	vertical-align: middle;
	display: table-cell;
	height: 285px;
	width: 285px;
	text-align: center; 
}#target{ display:none}
</style>
<?php include("inc-top-head.php");?> 
</head>
<body>
<div id="main">
 <?php include("inc-header.php")?>   
  
 <div id="content" class="site-content">
   <div class="content-register">
     <p><img src="images/title-new-post.gif" width="138" height="39"></p>
     <p>&nbsp;</p>
     <form action="" method="post" enctype="multipart/form-data" name="formID" id="formID"><table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td width="40%" valign="top">
           <div class="form-group"> 
            <input name="title" type="text" class="validate[required] form-control" id="inputEmail3" placeholder="Title">
          </div>
          <div class="form-group"> 
            <select name="itemSubCategory" class="validate[required] form-control" id="itemSubCategory">
              <option value="">Select Category</option>
              <option value="3">Decoration</option>
              <option value="4">Kitchen</option>
              <option value="5">Shopping</option> 
            </select>
          </div>
          <div class="form-group"> 
           <textarea name="description" rows="11" class="form-control" id="description" placeholder="Description"></textarea>
          </div>
  
         </td>
         <td width="20%" style="background-image:url(images/bg-post.png); background-position:top center; background-repeat:repeat-y">&nbsp;</td>
         <td width="40%" valign="top">
          <div class="pic-img">
           <img id="target" src="#" alt="your image"style="max-width:250px;  max-height:250px"/>
          </div>	
          <p>&nbsp;</p>	  
          <p><input name="file1" type='file' class="validate[required] form-control" id="imgInp" style="width:285px;"  /></p>    
          
        </td>
       </tr>
     </table>
       <p class="width220" style="margin:auto; padding-top:20px">
       <input class="btn btn-primary btn-lg btn-block" type="submit" value="Post"></p>
       <input name="url" type="text" class="url" id="url" />
       <input name="action" type="hidden" value="addPost" />
     </form>
     <p>&nbsp; </p>
   </div>
 </div>
 <?php include("inc-footer.php")?>
</div>
<script src="js/formValidator/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/formValidator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
  jQuery(document).ready(function(){
	 jQuery("#formID").validationEngine();
	 
	 function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();            
            reader.onload = function (e) {
				jQuery('#target').fadeIn(1500);
                jQuery('#target').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    jQuery("#imgInp").change(function(){
        readURL(this);
    });
  });
</script>
</body>
</html>