<?	
	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "../configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );

	#Login ?
	if( empty($_SESSION['_LOGIN']) || empty($_SESSION['_GRPID']) || empty($_SESSION['_ID'])){
		mosRedirect("_execlogout.php");
	}	

	#Create Obj
	$DB = mosConnectADODB();
	$msObj = new MS($DB);

	$action	= trim( mosGetParam( $_FORM, 'action', '' ) );
	$sel = trim( mosGetParam( $_FORM, 'sel', ''));
	$id 			= $_FORM['id'];

	if( isset($action) && !empty($action)){
		if($action == "ลบ | Delete"){
			$num = count($id);
			$query = "delete from $_Config_table[mail_subscribe] where ";
			for( $i = 0; $i < $num; $i++){
				$query .= "(newsletter_id = $id[$i])";
				if($i < $num-1){
					$query .= " or ";
				}
			}	
			$DB->Execute($query);			
				
			}
		}
//=======================================================
	
	#####//Set Show Page ///////////////////////////////////////////////////////////////
	$limit = '10';		// How many results should be shown at a time						
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
        <li>รายชื่อสมาชิก</li>
      </ul>      
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="70%"><h1 class="pagetitle">รายชื่อสมาชิก Subscribe</h1></td>
    <td width="30%" class="text-center">
     
    </td>
  </tr>
</table>

      <p class="line-brown"></p>
      <div class="detail-page">
        <form action="enews_member.php#member" method="post"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="4" cellpadding="0">
                <tr>
                  <td width="71%" align="left">
              <?  /*  <a class="btn btn-success" href="member_add.php#content">เพิ่ม | Add New</a>*/ ?>
                  </td>
                  <td width="18%" align="left">
                  <select name="action" class="form-control"id="action" style="float:right">
                    <option value="" selected="selected">-เลือก-</option>
               <?   /*  <option value="เผยแพร่ | Publish">อนุมัติ | Approved</option>
                    <option value="ซ่อน | Unpublish">ไม่อนุมัติ | Approved</option>*/ ?>
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
              <td width="11%"><strong>รหัส </strong></td>
              <td width="78%" align="left"><strong>อีเมล์</strong></td>
              <td width="11%"><strong>Func.</strong></td>
            </tr>
          </thead>
          <tbody>
        <?php

						$qrySel1 = "select * from  $_Config_table[mail_subscribe]";	
				
						$rsSel1 = $DB->Execute($qrySel1);
						$numrows =  $rsSel1->RecordCount();

						$qrySel2 = $qrySel1 .= " order by newsletter_id desc limit $start,$limit" ;												
						//echo $qrySel2;
						$rsSel2 = $DB->Execute($qrySel2);
						While  ($row = $rsSel2->FetchRow()){
						?>
          <tr>
            <td><strong>  </strong><?=$row["newsletter_id"];?></td>
            <td align="left"><?=$row["email"];?></td>
            <td align="center"><label class="checkbox-inline"> 
               <input type="checkbox" name="id[]" value="<?=$row["newsletter_id"];?>" />
              </label>
            </td>
          </tr>
          <? }?>  
          </tbody>
        </table>
            </td>
          </tr>
          <tr>
            <td>   <?
$_self = $_SERVER['PHP_SELF'];
					echo "<br>";
					$paging = ceil ($numrows / $limit);

					// Display the navigation
					if ($display > 1) {			
						$previous = $display - 1;	
						printf("<a href=\"%s?show=1\"><img src=\"images/i.p.firstpg.gif\" border=0></a> ", $_self);
						printf("<a href=\"%s?show=%s\"><img src=\"images/i.p.prevpg.gif\" border=0></a> ", $_self, $previous);
					}

					if ($numrows != $limit) {					
						if ($paging > $scroll) {											// remove this to get rid of the scroll feature						
							$first = $_GET['show'];
							$last = ($scroll - 1) + $_GET['show'];
						} else {	
							$first = 1;
							$last = $paging;
						}// REMOVE THIS TO GET RID OF THE SCROLL FEATURE					
						if ($last > $paging ) {	
							$first = $paging - ($scroll - 1);	
							$last = $paging;	
						}
						
							for ($i = $first;$i <= $last;$i++){
								if ($display == $i)
									echo "[<b>$i</b>]";
								else
									printf("[<a href=\"%s?show=%s\">%s</a>]", $_self, $i, $i);
							}
						}

						if ($display < $paging) {
							$next = $display + 1;
							printf(" <a href=\"%s?show=%s\"><img src=\"images/i.p.nextpg.gif\" border=0></a>", $_self, $next);
							printf(" <a href=\"%s?show=%s\"><img src=\"images/i.p.lastpg.gif\" border=0></a>", $_self, $paging);
						}
					?></td>
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