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
        <li>Dashboard</li>
      </ul>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="70%"><h1 class="pagetitle">เพิ่มแม่สี</h1></td>
          <td width="30%" class="text-center">&nbsp;</td>
        </tr>
      </table>
      <p class="line-brown"></p>
      <div class="detail-page">
        <form method="post"id="formID1"  class="form-horizontal form-page formular" role="form">
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-3 control-label">Code Name</label>
            <div class="col-lg-9">
              <input type="text" class="validate[required] form-control" id="inputEmail1" placeholder="Code Name">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-3 control-label">Color Name</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" id="inputEmail1" placeholder="Color Name">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-3 control-label">Color (image)</label>
            <div class="col-lg-9">
              <input type="file" id="exampleInputFile">
    <p class="help-block">ขนาดความกว้าง 150x100 พิกเซล ขนาดไฟล์ไม่เกิน 1 MB. (อนุญาติเฉพาะไฟล์ .gif  .png .jpg .jpeg เท่านั้น)</p>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-3 control-label">ถ.พ.</label>
            <div class="col-lg-9">
              <input type="email" class="form-control" id="inputEmail1" placeholder="ถ.พ.">
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