<?
	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "../configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );

	//	#Admin Only
	if( empty($_SESSION['_LOGIN']) || empty($_SESSION['_GRPID']) || empty($_SESSION['_ID']) ||  $_SESSION['_GRPLEVEL'] != "Administrator"  ){
		mosRedirect("_execlogout.php");
	}	

	#Create Obj
	$DB = mosConnectADODB();
	$msObj = new MS($DB);

	$action = mosGetParam( $_FORM, 'action', '' );
	$id = mosGetParam( $_FORM, 'id', '' );
	$keyword = mosGetParam( $_FORM, 'keyword', '' );

	if( isset($action) && !empty($action)){
		if($action == "ลบ | Delete"){
			$num = count($id);
			$query = "delete from $_Config_table[profile] where ";
			for( $i = 0; $i < $num; $i++){
				$query .= "(profile_id = $id[$i])";
				if($i < $num-1){
					$query .= " or ";
				}
			}	
			$DB->Execute($query);			
				
			}
		}
	
	#####//Set Show Page ///////////////////////////////////////////////////////////////
	$limit = '15';		// How many results should be shown at a time						
	$scroll = '20'; 	// How many elements to the record bar are shown at a time

	$display = (!isset ($_GET['show']) || $_GET['show']==0)? 1 : $_GET['show'];
	if( !empty( $userkey ) )
		$display = 1;

	$start = (($display * $limit) - $limit);
	################///////////////////////////////////////////////////////////////
//=======================================================
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
    <td width="70%"><h1 class="pagetitle">ผู้ดูแล / STAFF</h1></td>
    <td width="30%" class="text-center">
   
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
                  <a class="btn btn-success" href="staff_add.php#admin">เพิ่ม | Add New</a>
                  </td>
                  <td width="18%" align="left">
                  <select name="action" class="form-control"id="action" style="float:right">
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
              <td width="33%"><strong>&nbsp;<strong>Username</strong> </strong></td>
              <td width="20%"><strong> Level</strong></td>
              <td width="10%"><strong>Status</strong>&nbsp;</td>
              <td width="11%"><strong>Last login</strong></td>
              <td width="14%"><strong>Edit</strong></td>
              <td width="12%"><strong>Func.</strong></td>
            </tr>
          </thead>
          <tbody>
		  <?php						
          $qrySel1 = "select * from $_Config_table[profile] where group_id != '88'";
          $qrySel1 .= ( !empty( $keyword ) )? 
                          " and username like '%$keyword%'" :
                          "" ;	

          $rsSel1 = $DB->Execute($qrySel1);
          $numrows = $rsSel1->RecordCount();

          $qrySel2 = $qrySel1. " order by profile_id DESC" ;
          //echo $qrySel2;
                  
          $rsSel2 = $DB->SelectLimit($qrySel2, $limit, $start);
          While($row = $rsSel2->FetchRow()){
          ?>   
          <tr>
            <td><?php echo mosStripslashes($row["username"]); ?></td>
            <td align="center">
              <? switch($row["group_id"]){
						case "1" : 
						echo "Administrator";
						break;
						case "2" : 
						echo "Staff";
						break;
				}
				?></td>
            <td align="center"> <? switch($row["status"]){
						case "Y" : 
						echo "เปิดใช้งาน";
						break;
						case "N" : 
						echo "ปิดใช้งาน";
						break;
				}
				?>  </td>
            <td align="center"><?=( $row["lastlogin"] != "0000-00-00 00:00:00" )? DT::DateTimeShortFormat($row["lastlogin"]) : "-";?></td>
            <td align="center"><a href="staff_edit.php?_id=<?=$row["profile_id"]?>#admin"><img src="images/png/glyphicons_030_pencil.png" width="20" height="20" /></a></td>
            <td align="center"><label class="checkbox-inline">
                <input type="checkbox"name="id[]" value="<?=$row["profile_id"]?>"> 
              </label>
           </td>
          </tr>
          <? }//while 	?>  
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