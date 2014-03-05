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
	
	 $action = mosGetParam( $_FORM, 'action', '' ); 
	$sel_dealerid = mosGetParam( $_FORM, 'sel_dealerid', '' );
	
	 
	//echo $_POST["actionSel"];
	if( isset($action) && !empty($action) && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){
		
			switch($action){
				case "ลบ | Delete" : 
					
					 
					
					$num = count($sel_dealerid);					
					for( $i = 0; $i < $num; $i++){ 
						
						#Delete special
						$qrySelSpecial = "select * from $_Config_table[buynow] where buynow_id='$sel_dealerid[$i]'";
						$rsSelSpecial = $DB->Execute($qrySelSpecial);
						if( $rsSelSpecial->RecordCount() > 0){
							$SelSpecial = $rsSelSpecial->FetchRow();
												
							
							$qryDelSpecial = "delete from $_Config_table[buynow] where buynow_id='$sel_dealerid[$i]'";
							$DB->Execute($qryDelSpecial);
										
											
						}												
					}//for												
					break;
			 
			}
	}
	
	#####//Set Show Page ///////////////////////////////////////////////////////////////
	$limit = '30';		// How many results should be shown at a time						
	$scroll = '20'; 	// How many elements to the record bar are shown at a time

	$display = (!isset ($_GET['show']) || $_GET['show']==0)? 1 : $_GET['show'];
	if( !empty( $userkey ) )
		$display = 1;

	$start = (($display * $limit) - $limit);
	################///////////////////////////////////////////////////////////////
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
        <li>Buynow</li>
      </ul>      
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="70%"><h1 class="pagetitle">รายชื่อทำการสั่งซื้อ</h1></td>
    <td width="30%" class="text-center">
     
    </td>
  </tr>
</table>

      <p class="line-brown"></p>
      <div class="detail-page">
        <form action="buynow.php#news" method="post"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="4" cellpadding="0">
                <tr>
                  <td width="71%" align="left">
                
                  </td>
                  <td width="18%" align="left">
                  <select name="action" class="form-control"id="action" style="float:right">
                    <option value="" selected="selected">-เลือก-</option>
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
              <td width="29%"><strong>ชื่อ / อีเมล / เบอร์โทร / ที่อยู่</strong></td>
              <td width="21%"><strong> หัวข้อเรื่อง</strong></td>
              <td width="28%"><strong>รายละเอียด</strong></td>
              <td width="15%"><strong>วันที่</strong></td>
              <td width="7%"><strong>Func.</strong></td>
            </tr>
          </thead>
          <tbody>
           <?php
				$qrySel1 = "select * from $_Config_table[buynow]";
	
				//echo $qrySel1;
				$rsSel1 = $DB->Execute($qrySel1);
				$numrows = $rsSel1->RecordCount();
					$qrySel2 = $qrySel1 . " order by buynow_id desc" ;
				$rsSel2 = $DB->SelectLimit($qrySel2, $limit, $start);
										
				While($row_dealer = $rsSel2->FetchRow()){
				?>
          <tr>
            <td><strong>Name :</strong> <?php echo mosStripslashes($row_dealer["buynow_name"]);?><br />
                <strong>Tel :</strong> <?php echo mosStripslashes($row_dealer["buynow_tel"]);?><br />
                <strong>Email :</strong> <?php echo mosStripslashes($row_dealer["buynow_email"]);?>  <br />
				  <strong>Address :</strong> <?php echo mosStripslashes($row_dealer["buynow_address"]);?>  
           </td>
            <td align="center">
			  <p>
			    <?php 
					 $newsRow = $msObj->selectTable("news","news_title,news_abstract","news_id","$row_dealer[newsID_FK]"); 
					 $news_title = $newsRow["news_title"];	
					 $news_abstract = $newsRow["news_abstract"];			
			?>  
			    </p>
			  <p><span class="txtblue14"><?php echo $news_title?></span><br />
			    <span class="txtBrown14"><?php echo $news_abstract?></span></p></td>
            <td align="center"><?php echo nl2br($row_dealer["buynow_detail"]);?></td>
            <td align="center"><?=(DT::isDate($row_dealer["buynow_create_date"]))? DT::DateTimeShortFormat($row_dealer["buynow_create_date"], 0, 1, "Th") : "-" ;?></td>
            <td align="center"><label class="checkbox-inline"> 
                <input type="checkbox" name="sel_dealerid[]" value="<?=$row_dealer["buynow_id"];?>" />
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