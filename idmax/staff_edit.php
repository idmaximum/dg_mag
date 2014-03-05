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
	
	$_id =mosGetParam( $_FORM, '_id', '' );
	$action =mosGetParam( $_FORM, 'action', '' );
		
	if( isset($action) && !empty($action) && $action == "บันทึก | Save"){
		
		$chk_password = mosGetParam( $_FORM, 'chk_password', '' );	
		$password = mosGetParam( $_FORM, 'password', '' );	
		$password = "idmax".$password."pass";
	    $passwordHash = hash('sha256', $password);	
			
		$name = mosGetParam( $_FORM, 'name', '' );	
		$level = mosGetParam( $_FORM, 'level', '' );	
		$status = mosGetParam( $_FORM, 'status', '' );	
		$_id = mosGetParam( $_FORM, '_id', '' );	
						
		$qryEdit = "update $_Config_table[profile] set name =$DB->qstr('$name'), status= $DB->qstr('$status'), group_id=$DB->qstr('$level') ";
		//echo $qryEdit;
		if( $chk_password == "on" )
		{
			$qryEdit .= ", password =  $DB->qstr('$passwordHash')";
			//echo $qryEdit;
		}
		$now = DT::currentDateTime();
		$qryEdit .= ", update_date=$db->DBTimeStamp('$now') where profile_id = $_id";
		//echo $qryEdit;
		$DB->Execute($qryEdit);
						
		echo FU::alert_mesg("ดำเนินการเรียบร้อยแล้ว");					
		mosRedirect( "staff.php#admin" );
	}
	$qryEdit = "select * from $_Config_table[profile] where profile_id=$_id";
	$rsEdit = $DB->Execute($qryEdit);
	$data = $rsEdit->FetchRow();
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
        <li>ผู้ดูแล / Staff</li>
      </ul>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="70%"><h1 class="pagetitle">ผู้ดูแล / Staff</h1></td>
          <td width="30%" class="text-center">&nbsp;</td>
        </tr>
      </table>
      <p class="line-brown"></p>
      <div class="detail-page">
        <form action="staff_edit.php#admin" method="post"  class="form-horizontal form-page formular"id="formID1" role="form">
          <div class="form-group">
            <label for="name" class="col-lg-3 control-label">Name :</label>
            <div class="col-lg-9">
              <input name="name" type="text" class="validate[required] form-control" id="name" placeholder="Name" value="<?php echo clearText($data["name"]); ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="email" class="col-lg-3 control-label">Username :</label>
            <div class="col-lg-9">
              <input name="username" type="text" class="validate[required] form-control" id="username" placeholder="username" value="<?php echo clearText($data["username"]); ?>" disabled="disabled">
            </div>
          </div>
           
          <div class="form-group">
            <label for="password" class="col-lg-3 control-label">Password :</label>
            <div class="col-lg-9">
               <input name="password" type="password" id="password" value="<?=$data["password"]?>" />
           	   <input name="chk_password" type="checkbox" id="chk_password" value="on" />
               Change password
            </div>
          </div> 
          
          <div class="form-group">
            <label for="email" class="col-lg-3 control-label">Level :</label>
            <div class="col-lg-9">
           <select name="level">
                <option value="1" <?=($data["group_id"] == "1")? "selected" : ""?>>Administor</option>
                <option value="2" <?=($data["group_id"] == "2")? "selected" : ""?>>Staff</option>
            </select>
            </div>
          </div>
          <div class="form-group">
            <label for="email" class="col-lg-3 control-label">Status :</label>
            <div class="col-lg-9">
             <select name="status">
                <option value="N" <?=($data["status"] == "N")? "selected" : ""?>>ปิดการใช้งาน</option>
                <option value="Y" <?=($data["status"] == "Y")? "selected" : ""?>>เปิดให้ใช้งาน</option>
            </select>
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
              <input type="submit" name="action" value="บันทึก | Save" class="btn btn-primary" /> 
                <input type="hidden" name="_id" value="<?=$_id?>" />
            <input type="hidden" name="username" value="<?=$edit->username?>" />
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