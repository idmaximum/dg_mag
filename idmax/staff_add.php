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
	
	$action = mosGetParam( $_FORM, 'action', '' );
	//=======================================================
	if( isset($action) && !empty($action)){
		if( $action == "บันทึก | Save" ){
			
			#Get Parameter
			$username = mosGetParam( $_FORM, 'username', '' );	
			$password = mosGetParam( $_FORM, 'password', '' );	
			$name = mosGetParam( $_FORM, 'name', '' );	
			$level = mosGetParam( $_FORM, 'level', '' );	
			$status = mosGetParam( $_FORM, 'status', '' );	
			$email = mosGetParam( $_FORM, 'email', '' );
			
			$password = "idmax".$password."pass";
	        $passwordHash = hash('sha256', $password);	
				
			$qryCheck = "select * from $_Config_table[profile]  where username = '$username'";
			//echo $qryCheck;
			$rsCheck = $DB->Execute($qryCheck);	
			if( $rsCheck->RecordCount()> 0){
				echo FU::alert_mesg("ไม่สามารถดำเนินการได้ เนื่องจากมีผู้ใช้ login นี้แล้ว กรุณาบันทึกใหม่");
			}else{
				$now = DT::currentDateTime();
				$qryAdd = "insert into $_Config_table[profile] (name,  username, password, email, status, group_id, create_date ) 
				values( 
				$DB->qstr('$name'),  
				$DB->qstr('$username'), 
				$DB->qstr('$passwordHash'), 
				$DB->qstr('$email'), 
				$DB->qstr('$status'), 
				$DB->qstr('$level'), 
				$db->DBTimeStamp('$now') ) ";
				//echo $qryAdd;
				
				//echo "Add :".$qryAdd;
				$DB->Execute($qryAdd);	
				
				echo FU::alert_mesg("ดำเนินการเรียบร้อยแล้ว");						
			}
			mosRedirect( "staff.php#admin" );
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
        <form method="post"id="formID1"  class="form-horizontal form-page formular" role="form">
          <div class="form-group">
            <label for="name" class="col-lg-3 control-label">Name :</label>
            <div class="col-lg-9">
              <input name="name" type="text" class="validate[required] form-control" id="name" placeholder="Name">
            </div>
          </div>
          <div class="form-group">
            <label for="email" class="col-lg-3 control-label">Username :</label>
            <div class="col-lg-9">
              <input name="username" type="text" class="validate[required] form-control" id="username" placeholder="username">
            </div>
          </div>
           
          <div class="form-group">
            <label for="password" class="col-lg-3 control-label">Password :</label>
            <div class="col-lg-9">
              <input name="password" type="password" class="validate[required] form-control" id="password" placeholder="Password">
            </div>
          </div> 
          <div class="form-group">
            <label for="email" class="col-lg-3 control-label">E-mail :</label>
            <div class="col-lg-9">
              <input name="email" type="text" class="form-control" id="email" placeholder="E-mail">
            </div>
          </div>
          <div class="form-group">
            <label for="email" class="col-lg-3 control-label">Level :</label>
            <div class="col-lg-9">
              <select name="level"  class="form-control">
                <option value="1" >Administor</option>
                <option value="2" selected="selected" >Staff</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="email" class="col-lg-3 control-label">Status :</label>
            <div class="col-lg-9">
              <select name="status" class="form-control" >
                <option value="N" >ปิดการใช้งาน</option>
                <option value="Y" >เปิดให้ใช้งาน</option>
              </select>
              
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
              <input type="submit" name="action" value="บันทึก | Save" class="btn btn-primary" /> 
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