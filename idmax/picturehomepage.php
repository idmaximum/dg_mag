<?	
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
    <td width="70%"><h1 class="pagetitle">Dashboard</h1></td>
    <td width="30%" class="text-center">
    <form class="form-inline" role="form">
        <div class="form-group">
          <label class="sr-only" for="exampleInputEmail2">Keyword</label>
          <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Keyword">
        </div>
        <button type="submit" class="btn btn-default">Search</button>
      </form>
    </td>
  </tr>
</table>

      <p class="line-brown"></p>
      <div class="detail-page">
        <form action="" method="post"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="4" cellpadding="0">
                <tr>
                  <td width="71%" align="left">
                  <a class="btn btn-success" href="primary_colors_add.php#color">เพิ่ม | Add New</a>
                  </td>
                  <td width="18%" align="left">
                  <select name="action_up" class="form-control"id="action_up" style="float:right">
                    <option value="" selected="selected">-เลือก-</option>
                    <option value="เผยแพร่ | Publish">เผยแพร่ | Publish</option>
                    <option value="ซ่อน | Unpublish">ซ่อน | Unpublish</option>
                    <option value="ลบ | Delete">ลบ | Delete</option>
                  </select></td>
                  <td width="11%" align="left"><input type="submit" name="submit" value="Apply" class="btn btn-primary" style="margin-left:5px" /></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped">
          <thead>
            <tr align="center">
              <td width="33%"><strong>Name Color</strong></td>
              <td width="41%"><strong> Color</strong></td>
              <td width="14%"><strong>Edit</strong></td>
              <td width="12%"><strong>Func.</strong></td>
            </tr>
          </thead>
          <tbody>
          <tr>
            <td>ddddddd</td>
            <td>&nbsp;</td>
            <td align="center"><img src="images/png/glyphicons_030_pencil.png" width="20" height="20" /></td>
            <td align="center"><label class="checkbox-inline">
                <input type="checkbox" value="option2"> 
              </label>
           </td>
          </tr>
          </tbody>
          
        </table>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></form>
        
      </div>
        <p class="line-brown clear"></p>
    </div>
   
  </div>
  <p class="clear"></p>
  <?php include("inc-bof-footer.php");?>
</div>
<?php include("inc-script.php");?>
</body>
</html>