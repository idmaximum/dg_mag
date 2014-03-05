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
	$item_id = trim( mosGetParam( $_FORM, 'item_id', ''  ));
	$action = mosGetParam( $_FORM, 'action', '' );	
	
	if( isset($action) && !empty($action) && $action == "Add_New" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){
		
		$file1 = trim( mosGetParam( $_FORM, 'file1', ''  ));
		$file2 = trim( mosGetParam( $_FORM, 'file2', ''  ));
		$file3 = trim( mosGetParam( $_FORM, 'file3', ''  ));
		$file4 = trim( mosGetParam( $_FORM, 'file4', ''  ));
		$file5 = trim( mosGetParam( $_FORM, 'file5', ''  ));
	
		$item_image_description1	= trim( mosGetParam( $_POST, 'item_image_description1', '', 2 ));
		$item_image_description2	= trim( mosGetParam( $_POST, 'item_image_description2', '', 2 ));
		$item_image_description3	= trim( mosGetParam( $_POST, 'item_image_description3', '', 2 ));
		$item_image_description4	= trim( mosGetParam( $_POST, 'item_image_description4', '', 2 ));
		$item_image_description5	= trim( mosGetParam( $_POST, 'item_image_description5', '', 2 ));
		//Set Parameter for Upload
		#==============================================================================================
		$sServerDir = $_Config_absolute_path."/uploads/item/";
	
		$sType = "Image";
		$SetMaxSize = '1048576';	// 1MB
		
		#Image Allowed & Denied
		$_Config['AllowedExtensions'][$sType] = array('png','jpg', 'jpeg', 'gif') ;
		$_Config['DeniedExtensions'][$sType] = array('zip', 'pdf', 'php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;		
	
		$SetMaxWidth = "3000";	
		$SetReduceWidth = "220";
		$SetReduceHeight = "192";		
		#==============================================================================================
	
		#Upload Picture 
		$upload1 = FU::uploadNewImage_cropXY( 
		$_FILES['file1'], 
		$sServerDir, 
		$_Config['AllowedExtensions'][$sType], 
		$_Config['DeniedExtensions'][$sType], 
		$SetMaxSize, 
		$SetMaxWidth, 
		$SetReduceWidth, 
		$SetReduceHeight);	

		$upload2 = FU::uploadNewImage_cropXY( 
		$_FILES['file2'], 
		$sServerDir, 
		$_Config['AllowedExtensions'][$sType], 
		$_Config['DeniedExtensions'][$sType], 
		$SetMaxSize, 
		$SetMaxWidth, 
		$SetReduceWidth, 
		$SetReduceHeight);	
		
		$upload3 = FU::uploadNewImage_cropXY( 
		$_FILES['file3'], 
		$sServerDir, 
		$_Config['AllowedExtensions'][$sType], 
		$_Config['DeniedExtensions'][$sType], 
		$SetMaxSize, 
		$SetMaxWidth, 
		$SetReduceWidth, 
		$SetReduceHeight);
				
		$upload4 = FU::uploadNewImage_cropXY( 
		$_FILES['file4'], 
		$sServerDir, 
		$_Config['AllowedExtensions'][$sType], 
		$_Config['DeniedExtensions'][$sType], 
		$SetMaxSize, 
		$SetMaxWidth, 
		$SetReduceWidth, 
		$SetReduceHeight);	
				
		$upload5 = FU::uploadNewImage_cropXY( 
		$_FILES['file5'], 
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
			$qryIns = "insert into $_Config_table[itemimage] (item_image_thumb, item_image_name, item_image_description, item_id) 
			values(  '$upload1[sThumbnail]', '$upload1[sFileName]', $DB->qstr('$item_image_description1'), '$item_id')";
			$DB->Execute($qryIns);
			
			if($upload2[sThumbnail] != ""){				
				$qryIns = "insert into $_Config_table[itemimage] (item_image_thumb, item_image_name, item_image_description, item_id) 
				values(  '$upload2[sThumbnail]', '$upload2[sFileName]', $DB->qstr('$item_image_description2'), '$item_id')";
				$DB->Execute($qryIns);
			}
			if($upload3[sThumbnail] != ""){				
				$qryIns = "insert into $_Config_table[itemimage] (item_image_thumb, item_image_name, item_image_description, item_id) 
				values(  '$upload3[sThumbnail]', '$upload3[sFileName]', $DB->qstr('$item_image_description2'), '$item_id')";
				$DB->Execute($qryIns);
			}
			if($upload4[sThumbnail] != ""){				
				$qryIns = "insert into $_Config_table[itemimage] (item_image_thumb, item_image_name, item_image_description, item_id) 
				values(  '$upload4[sThumbnail]', '$upload4[sFileName]', $DB->qstr('$item_image_description2'), '$item_id')";
				$DB->Execute($qryIns);
			}
			if($upload5[sThumbnail] != ""){				
				$qryIns = "insert into $_Config_table[itemimage] (item_image_thumb, item_image_name, item_image_description, item_id) 
				values(  '$upload5[sThumbnail]', '$upload5[sFileName]', $DB->qstr('$item_image_description2'), '$item_id')";
				$DB->Execute($qryIns);
			}			
			$flagSuccess = "1";			
		}		
		 
		  
		if( $flagSuccess == "1")
		{
			FU::alert_mesg("บันทึกข้อมูลเรียบร้อยแล้ว");
			mosRedirect( "item_picture.php?item_id=$item_id" );
		}
	}
	//===========================================================
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<TITLE><?=$_Config_sitename?>'s Backoffice</TITLE>
	<link rel="stylesheet" href="../css/font.css">
<link rel="stylesheet" href="../js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../js/bootstrap/css/bootstrap-theme.css">
<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px!important;
}
</style> 
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

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="txtBlack12">
              <tr valign="top">
                <td height="332"><img src="images/1pix.gif" width="1" height="1"><img src="images/1pix.gif" width="8" height="8">
                  <table width="98%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                      <td><div align="left" ><a href="item_picture.php?item_id=<?php echo $item_id; ?>" class="list2">item picture  Main Page</a></div></td>
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
                        <td width="5%" align="center" valign="middle"><span><img src="images/png/glyphicons_063_power.png" alt="" width="22" height="24" /></span></td>
                        <td width="95%" align="left" valign="middle"><span>การดำเนินการ : </span> <span class="headSmRed"><br />
                          <?php echo $errMsg; ?></span></td>
                      </tr>
                    </table>
                    <?php } ?>
                    <!-- Error Message --><br>
                    <table width="98%" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#C9C9C9" class="txtBlack12" >
                      <tr bgcolor="#EBEBEB">
                        <td colspan="2" align="center"  background="images/bg_bar_1.jpg">เพิ่มรูปประกอบ</td>
                      </tr>
                    <?php for($i=1;$i<=5;$i++){?>  
                      <tr>
                        <td align="right" valign="top" bgcolor="#FFFFFF"><strong>อัพโหลดรูป <?php echo $i?>
                          :</strong></td>
                        <td valign="top" bgcolor="#FFFFFF"><input name="file<?php echo $i?>" type="file" ></td>
                      </tr>                     
                      <tr class="desTh">
                        <td align="right" valign="top" bgcolor="#FFFFFF"><font ><strong><span>คำอธิบายรูป</span> :</strong></font></td>
                        <td align="left" valign="top" bgcolor="#FFFFFF"><textarea name="item_image_description<?php echo $i?>" id="html" cols="70" rows="5"></textarea></td>
                      </tr>
                   <?php }?>   
                       <tr valign="top" bgcolor="#FFFFFF">
                        <td align="right"><strong>คำแนะนำ</strong> : </td>
                        <td align="left"><span class="mediumSm"><span > <font size="2" face="Tahoma" class="name_product_1"> ขนาดไฟล์ไม่เกิน 2 MB., อนุญาติเฉพาะไฟล์ .gif .jpg .jpeg .png เท่านั้น</font></span></span></td>
                      </tr>
                      
                      <tr bgcolor="#EBEBEB">
                        <td width="153" valign="top">&nbsp;</td>
                        <td valign="top"><input type="hidden" name="action" value="Add_New" />
                          <input type="hidden" name="item_id" value="<?=$item_id?>">
                          <input type="submit" name="action2" value="บันทึก | Save" ></td>
                      </tr>
                    </table>
                  </form>
                  <font size="2" face="Tahoma">&nbsp; </font></td>
                <td width="10">&nbsp;</td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    <td width="8" valign="top">&nbsp;</td>
  </tr>
    </table>
    <?php include("inc-script.php");?>
</BODY>
</HTML>
