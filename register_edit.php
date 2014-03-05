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
	
	 if( empty($_SESSION['_CUTOMERID']) || empty($_SESSION['_USERNAME']) ){
		mosRedirect("login.php");
		exit();
	}
	 $action = trim(mosGetParam($_REQUEST,'action',''));
	 $url = trim(mosGetParam($_REQUEST,'url','')); 
	  if($url == "" ){# เช็ดดูว่าใช่ spam หรือป่าว ############### Vote	  
     	if( isset($action) && !empty($action) && $action == "Edit_member" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){
			$password_new = trim(mosGetParam($_FORM,'password_new',''));
			$password_new = "idmax".$password_new."pass";
	        $password_new = hash('sha256', $password_new);	
		
		
		    $chk_password = trim(mosGetParam($_FORM,'chk_password',''));
			$display = trim(mosGetParam($_FORM,'display','')); 
		
			$sServerDir = $_Config_absolute_path."/uploads/member/";

		$sType = "Image";
		$SetMaxSize = '1048576';// 1MB
		
		#Image Allowed & Denied
		$_Config['AllowedExtensions'][$sType] = array('png','jpg', 'jpeg', 'gif') ;
		$_Config['DeniedExtensions'][$sType] = array( 'zip', 'pdf', 'php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;		
	
		$SetMaxWidth = "3000";	
		$SetReduceWidth = "100";
		$SetReduceHeight = "100";	
		
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
			 $qryEditRegis = "update $_Config_table[member] set  
			customer_fname = $DB->qstr('$display'), 
			customer_img = '$upload1[sThumbnail]'";
			#echo $qryEditRegis;
			if( $chk_password == "on" )
			{
			   $qryEditRegis .= ", password = $DB->qstr('$password_new')";
			}
		 	$qryEditRegis .= ", last_upd = $DB->DBTimeStamp('$now')
			where customer_id= '$_SESSION[_CUTOMERID]'";
			
			$DB->Execute($qryEditRegis);			
		   $flagSuccess = "1";
		   
		    $_SESSION['NameDisplay'] = $display;
		    $_SESSION['customerImg'] = "$upload1[sThumbnail]";
			
		}#end Uploads
		if( $flagSuccess  == "1")
		{
			 FU::alert_mesg("Update Profile successfully"); 
		}
		}#end action
	 }#end if
		 
		
	$qry_customer = "select * from $_Config_table[member] where customer_id = '$_SESSION[_CUTOMERID]'";
	$rs_customer = $DB->Execute($qry_customer);
	$row_customer = $rs_customer->FetchRow();  
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
 
<style type="text/css">
.table-register td{
	padding: 10px;
}
.pic-img {
	background-image: url(images/bg_post_img.png);
	background-repeat: no-repeat;
	background-position: left top;
	vertical-align: middle;
	display: table-cell;
	height: 285px;
	width: 285px;
	text-align: center; 
} 
</style>
<?php include("inc-top-head.php");?>
</head>
<body>
<div id="main">
 <?php include("inc-header.php")?>   
 <div id="content" class="site-content">
 	  <div class="content-register">
       <form action="" method="post" enctype="multipart/form-data" name="frm_register" class="formular"id="formID"  >
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="11%">&nbsp;</td>
                <td width="6%"><img src="images/5_register_10.gif" width="34" height="33"></td>
                <td width="83%" class="txtBlack14"><strong>Edit Profile</strong></td>
              </tr>
            </table>
      	<table width="100%" border="0" cellspacing="10" cellpadding="0"class="txtpink13  "  >
                <tr>
                  <td width="26%" class="txtpink13">&nbsp;</td>
                  <td width="32%">&nbsp;</td>
                  <td width="42%" rowspan="6">
                    <?php if($row_customer["customer_img"] != ""){?>
					<?php $dispalyImage = "uploads/member/".$row_customer["customer_img"]?>
                    <?php }else if($_SESSION['fbID'] != ""){?>
                    <?php $dispalyImage = "https://graph.facebook.com/$_SESSION[fbID]/picture?type=large"?>
                    
                    <?php }else{
						   $dispalyImage = "images/personal.png";
					}?>
                    
                  <div class="pic-img">
                    <img id="target" src="<?php echo $dispalyImage;?>" alt="your image"style="max-width:250px;  max-height:250px"/>
                  </div> 

                  <input name="file1" type='file' class="validate[required] form-control" id="imgInp" style="width:285px;"  />    
                   </td>
                </tr>
                <tr>
                  <td align="left" valign="middle" class="txtpink13"><strong>Your E-mail Address *</strong></td>
                  <td><input name="email" type="text" id="email" onblur="checkemail();" class="validate[required,custom[email]]  width220 form-control"autocomplete="off"value="<?=$row_customer["username"]?>"disabled="disabled"/>
                 </td>
                </tr>
                <tr>
                  <td align="left" valign="middle" class="txtpink13"><strong>Password *</strong></td>
                  <td>
                    <input name="password" type="password" id="password"  class="validate[required,length[6,11]]   form-control width220" value="xxxxxxxxxxx" disabled />
            </td>
                </tr>
                <tr>
                  <td align="left" valign="middle" class="txtpink13"> <span class="txtBlack12">To change your Password. <br />
Click Change and enter a new Password.</span>  </td>
                  <td> 
<input name="chk_password" type="checkbox" id="chk_password" value="on" onclick="checkpass_edit();" />
              <span class="txtBlack12">Change Password<br>
                         </span></td>
                </tr>
                <tr>
                  <td align="left" valign="middle" class="txtpink13"><strong>New Password *</strong></td>
                  <td> 
              <input name="password_new" type="password" class=" form-control width220" id="password_new" title=" "   />
   	          </td>
                </tr>
                <tr>
                  <td align="left" valign="middle" class="txtpink13"><strong>Display Name</strong></td>
                  <td>
                   <?php if($row_customer["customer_fname"] != ""){?>
					<?php $dispalyname = $row_customer["customer_fname"]?>
                    <?php }else{?>
                    <?php $dispalyname = $row_customer["username"]?>
                    <?php }?>
                  <input name="display" type="text" class="validate[required] form-control width220" id="password" placeholder="Display Name" value="<?=$dispalyname?>"></td>
                </tr>
                <tr>
                  <td class="txtpink13">&nbsp;</td>
                  <td><p><input class="btn btn-primary btn-lg btn-block txtWhite12" type="submit" value="Update profile" style="width:150px"></p></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td class="txtpink13">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
               <input name="url" type="text" class="url" id="url" />
                <input name="action" type="hidden" id="action" value="Edit_member" />
       </form>
    </div>
  </div>
 <?php include("inc-footer.php")?>
</div>
<script>
  jQuery(document).ready(function(){ 
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