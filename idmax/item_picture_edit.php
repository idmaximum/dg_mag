<?	header ('Content-type: text/html; charset=utf-8');
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
	}
	
	#Create Obj
	$DB = mosConnectADODB();
	$msObj = new MS($DB);
	
	#Init. value
	$errCode = "0";
	$flagSuccess = "0";

	//Get Parameter.
	$news_id = trim( mosGetParam( $_FORM, 'news_id', ''  ));
	$news_image_id = trim( mosGetParam( $_FORM, 'news_image_id', ''  ));
	$action = mosGetParam( $_FORM, 'action', '' );	
	
	if( isset($action) && !empty($action) && $action == "Add_New" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){
		
		$news_image_description	= trim( mosGetParam( $_POST, 'news_image_description', '', 2 ));
		$oldTitleImage = trim(mosGetParam($_FORM,'oldTitleImage',''));
		$oldTitleThumb = trim(mosGetParam($_FORM,'oldTitleThumb',''));
		//Set Parameter for Upload
		#==============================================================================================
		$sServerDir = $_Config_absolute_path."/uploads/news/";
	
		$sType = "Image";
		$SetMaxSize = '1048576';	// 1MB
		
		#Image Allowed & Denied
		$_Config['AllowedExtensions'][$sType] = array('jpg', 'jpeg', 'gif') ;
		$_Config['DeniedExtensions'][$sType] = array('zip', 'pdf', 'php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;		
	
		$SetMaxWidth = "800";	
		$SetReduceWidth = "155";
		$SetReduceHeight = "100";
		#==============================================================================================
	
		#Upload Picture 
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
			$qryIns = "update $_Config_table[newsimage] set 
			news_image_thumb = '$upload1[sThumbnail]',
			news_image_name =  '$upload1[sFileName]',
			news_image_description = $DB->qstr('$news_image_description'),
			news_id = '$news_id' 
			where news_image_id = '$news_image_id' ";
			
			$DB->Execute($qryIns);
			$flagSuccess = "1";
		}
		if( $flagSuccess == "1")
		{
			FU::alert_mesg("บันทึกข้อมูลเรียบร้อยแล้ว");
			mosRedirect( "news_picture.php?news_id=$news_id" );
		}
	}
	//===========================================================
	$qrySelPic = "select * from $_Config_table[newsimage] where news_image_id = '$news_image_id'";
	$rsSelPic = $DB->Execute($qrySelPic);
	$rowPic = $rsSelPic->FetchRow();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
 <HEAD>	
	<META http-equiv=Content-Type content="text/html; charset=utf-8">
	<TITLE><?=$_Config_sitename?>'s Backoffice</TITLE>
 <style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
</style>
	
    <!-- jQuery -->
	<script type="text/javascript" src="../js/<?php echo $_Config_jquery_version; ?>.js"></script>
	<!-- markItUp! -->
	<script type="text/javascript" src="../js/markup/markitup/jquery.markitup.pack.js"></script>
	<!-- markItUp! toolbar settings -->
	<script type="text/javascript" src="../js/markup/markitup/sets/html/set.js"></script>
	<!-- markItUp! skin -->
	<link rel="stylesheet" type="text/css" href="../js/markup/markitup/skins/markitup/style.css" />
	<!--  markItUp! toolbar skin -->
	<link rel="stylesheet" type="text/css" href="../js/markup/markitup/sets/html/style.css" />
	<script type="text/javascript">
    <!--
    jQuery(document).ready(function() {
    
        jQuery("img#photoposition").hide();
    
        jQuery("a#showhide").toggle(function(){
            jQuery("img#photoposition").hide();
        },function(){
            jQuery("img#photoposition").show();
        });
     
        jQuery("a#slide").toggle(function(){
            jQuery("img#photoposition").slideUp();
        },function(){
            jQuery("img#photoposition").slideDown();
        });
     
        jQuery("a#fade").toggle(function(){
            jQuery("img#photoposition").fadeOut();
        },function(){
            jQuery("img#photoposition").fadeIn();
        });
    });
    //-->
    </script>
	<script language="javascript">
		function Trim(s) 
		{
			 while ((s.substring(0,1) == ' ') || (s.substring(0,1) == '\n') || (s.substring(0,1) == '\r'))
			 {
			   s = s.substring(1,s.length);
			 }
			 while ((s.substring(s.length-1,s.length) == ' ') || (s.substring(s.length-1,s.length) == '\n') || (s.substring(s.length-1,s.length) == '\r'))
			 {
			   s = s.substring(0,s.length-1);
			 }
			 return s;
		}

		 //===========================================================
		 // Main Function
		 //===========================================================
		 function checkFrm()
		 {
		 	with(document.formUpload)
			{		   
				if(Trim(file1.value) == '')
				{
					alert('รูปประกอบ');
					file1.focus();
					return false;
				}						
				return true;
			}
		 }
	</script>
 </HEAD>
<BODY>
<script type="text/javascript">
<!--
jQuery(document).ready(function()	{
	// Add markItUp! to your textarea in one line
	// jQuery('textarea').markItUp( { Settings }, { OptionalExtraSettings } );
	jQuery('#html').markItUp(myHtmlSettings);

	// You can add content from anywhere in your page
	// jQuery.markItUp( { Settings } );	
	jQuery('.add').click(function() {
 		jQuery.markItUp( { 	openWith:'<opening tag>',
						closeWith:'<\/closing tag>',
						placeHolder:"New content"
					}
				);
 		return false;
	});
	
	// And you can add/remove markItUp! whenever you want
	// jQuery(textarea).markItUpRemove();
	jQuery('.toggle').click(function() {
		if (jQuery("#markItUp.markItUpEditor").length === 1) {
 			jQuery("#markItUp").markItUpRemove();
			jQuery("span", this).text("get markItUp! back");
		} else {
			jQuery('#markItUp').markItUp(mySettings);
			jQuery("span", this).text("remove markItUp!");
		}
 		return false;
	});
	
});
//-->
</script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="medium">
              <tr valign="top"> 
                <td height="332"> <img src="images/1pix.gif" width="1" height="1"><img src="images/1pix.gif" width="8" height="8"> 
                  <table width="98%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                      <td><div align="left" class="medium"><a href="news_picture.php?news_id=<?php echo $rowPic["news_id"]; ?>" class="list2">News picture Main Page</a></div></td>
                      <td class="headlineth2">&nbsp;</td>
                    </tr>
                  </table>
                  <BR clear="all">

                  <form name="formUpload" method="post" action="" enctype="multipart/form-data">
				  <!-- Error Message -->	
				  <?php if( $errCode != "0" ){ ?>
                    <br />
                    <table width="95%" border="0" align="center" class="table">
                      <tr>
                        <td width="5%" align="center" valign="middle">
                          <span class="headSmGray"><img src="images/info.png" alt="" width="32" height="32" /></span>
                          </td>
                        <td width="95%" align="left" valign="middle">
                        <span class="headSmGray">การดำเนินการ : </span>
                        <span class="headSmRed"><br />
                        <?php echo $errMsg; ?></span></td>
                      </tr>
                    </table>    
                    <?php } ?>
                    <!-- Error Message --><br>			  
                    <table width="98%" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#C9C9C9" class="medium">
                      <tr bgcolor="#EBEBEB"> 
                        <td colspan="2" align="center" class="menuBig" background="images/bg_bar_1.jpg">แก้ไขรูปประกอบ</td>
                      </tr>
                      <tr> 
                        <td align="right" valign="top" bgcolor="#FFFFFF" class="headSmGray"><strong>อัพโหลดรูป
                          :</strong></td>
                        <td valign="top" bgcolor="#FFFFFF"><input name="file1" type="file" class="medium2">
                        (หากต้องการเปลี่ยนรูปที่อัพโหลดไปแล้ว ให้อัพโหลดรูปใหม่ไปแทน)</td>
                      </tr>
                      <tr class="desTh">
                        <td align="right" valign="top" bgcolor="#FFFFFF" class="headSmGray">&nbsp;</td>
                        <td align="left" valign="top" bgcolor="#FFFFFF">
						<?php if( !empty($rowPic["news_image_name"])){ ?>
                          <table width="74" border="0">
                            <tr>
                              <td align="center"><img src="<?php echo "../uploads/news/".$rowPic["news_image_name"]; ?>" /></td>
                            </tr>
                            <tr>
                              <td align="center" class="medium2">รูปปัจจุบัน</td>
                            </tr>
                          </table>
                          <?php } ?></td>
                      </tr>
                      <tr valign="top" bgcolor="#FFFFFF">
                        <td align="right" class="headSmGray">คำแนะนำ : </td>
                        <td align="left"><span class="mediumSm"><span class="medium2"> <font size="2" face="Tahoma" class="name_product_1">ความกว้างไม่เกิน  800*600 พิกเซล, ขนาดไฟล์ไม่เกิน 1 MB., อนุญาติเฉพาะไฟล์ .gif .jpg .jpeg เท่านั้น</font></span></span></td>
                      </tr>
                      <tr class="desTh">
                        <td align="right" valign="top" bgcolor="#FFFFFF" class="headSmGray"><font class="medium"><strong><span class="headSmGray">คำอธิบายรูป</span> :</strong></font></td>
                        <td align="left" valign="top" bgcolor="#FFFFFF"><textarea name="news_image_description" id="html" cols="70" rows="5"><?=$rowPic["news_image_description"];?></textarea></td>
                      </tr>
                      <tr bgcolor="#EBEBEB"> 
                        <td width="153" valign="top">&nbsp;</td>
                        <td valign="top"> 
                        	<input type="hidden" name="action" value="Add_New" />
                            <input type="hidden" name="oldTitleImage" value="<?php echo $rowPic["news_image_name"]; ?>" />
	    					<input type="hidden" name="oldTitleThumb" value="<?php echo $rowPic["news_image_thumb"]; ?>" />
                            <input type="hidden" name="news_image_id" value="<?=$news_image_id;?>">
                          	<input type="hidden" name="news_id" value="<?=$rowPic["news_id"];?>">
						  	<input type="submit" name="action2" value="บันทึก | Save" class="medium2"></td>
                      </tr>
                    </table>
                  </form>
                  <font size="2" face="Tahoma">&nbsp; </font> </td>
                <td width="10">&nbsp;</td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    <td width="8" valign="top">&nbsp;</td>
  </tr>
</table>
</BODY></HTML>
