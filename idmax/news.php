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
	$action_up = mosGetParam( $_FORM, 'action_up', '' );	
	$sel_newsid = mosGetParam( $_FORM, 'sel_newsid', '' );
	
	if ($action_up !==""){
		$action = $action_up;
	}	
		//echo $_POST["actionSel"];
	if( isset($action) && !empty($action) && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){
			switch($action){
				case "ลบ | Delete" : 
					
					$absPath=$_Config_absolute_path."/uploads/news/";
					
					$num = count($sel_newsid);					
					for( $i = 0; $i < $num; $i++){
						/*$qrySelImg = "select * from $_Config_table[newsimage] where news_id='$sel_newsid[$i]'";
						//echo $qrySelImg;
						$rsSelImg = $DB->Execute($qrySelImg);
						if( $rsSelImg->RecordCount() > 0){
							
							while($selImg = $rsSelImg->FetchRow()){
								$image_thumb = $selImg["news_image_thumb"];
								$image_large = $selImg["news_image_name"];
							
							
								$qryDelTipsimage = "delete from $_Config_table[newsimage] where news_image_id='$selImg[news_image_id]'";
								$DB->Execute($qryDelTipsimage);
										
								if( $DB->Affected_Rows()){
									#UnLink Image
									@FU::unlinkImage($absPath, $image_thumb);
									@FU::unlinkImage($absPath, $image_large);
								}
							}
						}*/
						
						#Delete special
						$qrySelSpecial = "select * from $_Config_table[news] where news_id='$sel_newsid[$i]'";
						$rsSelSpecial = $DB->Execute($qrySelSpecial);
						if( $rsSelSpecial->RecordCount() > 0){
							$SelSpecial = $rsSelSpecial->FetchRow();
							$image_thumb = $SelSpecial["news_image_thumb"];
							$image_large = $SelSpecial["news_image"];							
							
							$qryDelSpecial = "delete from $_Config_table[news] where news_id='$sel_newsid[$i]'";
							$DB->Execute($qryDelSpecial);
										
							if( $DB->Affected_Rows()){
								#UnLink Image
								@FU::unlinkImage($absPath, $image_thumb);
								@FU::unlinkImage($absPath, $image_large);
							}					
						}												
					}//for												
					break;
							
				case "เผยแพร่ | Publish" :
					$num = count($sel_newsid);
					$query = "update $_Config_table[news] set news_publish='1' where ";
					for( $i = 0; $i < $num; $i++){
						$query .= "(news_id = $sel_newsid[$i])";
						if($i < $num-1){
							$query .= " or ";
						}
					}
					$DB->Execute($query);				
					break;
				case "ซ่อน | Unpublish" :
					$num = count($sel_newsid);
					$query = "update $_Config_table[news] set news_publish='0' where ";
					for( $i = 0; $i < $num; $i++){
						$query .= "(news_id = $sel_newsid[$i])";
						if($i < $num-1){
							$query .= " or ";
						}
					}	
					$DB->Execute($query);				
					break;
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
        <li>ISSUE</li>
      </ul>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="70%"><h1 class="pagetitle">จัดการปกหนังสือ</h1></td>
          <td width="30%" class="text-center"></td>
        </tr>
      </table>
      <p class="line-brown"></p>
      <div class="detail-page">
        <form   method="post" action="news.php#content">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table width="100%" border="0" cellspacing="4" cellpadding="0">
                  <tr>
                    <td width="71%" align="left"><a class="btn btn-success" href="news_add.php#content">เพิ่ม | Add New</a></td>
                    <td width="18%" align="left"><select name="action_up" class="form-control"id="action_up" style="float:right">
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
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped">
                  <thead>
                    <tr align="center">
                      <td width="28%"><strong>หัวเรื่อง</strong></td>
                      <td width="23%"><strong> รูปประกอบ</strong></td>
                      <td width="18%"><strong>วันที่สร้าง/
                        วันที่แก้ไข </strong></td>
                      <td width="10%"><strong>สถานะ</strong></td>
                      <td width="12%"><strong>แก้ไข</strong></td>
                      <td width="9%"><strong>Func.</strong></td>
                    </tr>
                  </thead>
                  <?php
				$qrySel1 = "select * from $_Config_table[news]";
	
				//echo $qrySel1;
				$rsSel1 = $DB->Execute($qrySel1);
				$numrows = $rsSel1->RecordCount();
				?>
                  <tbody id="table-1">
                    <?php
				$qrySel2 = $qrySel1 . " order by order_by" ;
				$rsSel2 = $DB->Execute($qrySel2);
				//$rsSel2 = $DB->SelectLimit($qrySel2, $limit, $start); 
				While($row_news = $rsSel2->FetchRow()){
				?>
                    <tr  id="<?php echo $row_news["news_id"];?>">
                      <td><?php echo mosStripslashes($row_news["news_title"]);?><br />
                          <?php echo mosStripslashes($row_news["news_abstract"]);?><br /></td>
                      <td align="center"> 
                       <img src="<?php echo "../uploads/news/$row_news[news_image_thumb]"?>"  />  <br />
                        </td>
                      <td align="center"><?=(DT::isDate($row_news["news_create_date"]))? DT::DateTimeShortFormat($row_news["news_create_date"], 0, 0, "Th") : "-" ;?>
                        <br />
                        <?=(DT::isDate($row_news["news_update_date"]))? DT::DateTimeShortFormat($row_news["news_update_date"], 0, 0, "Th") : "-" ;?></td>
                      <td><?php echo ($row_news[news_publish]=="1")? "เผยแพร่" : "ไม่เผยแพร่"; ?></td>
                      <td align="center"><a href="news_edit.php?news_id=<?=$row_news["news_id"]?>#content"><img src="images/png/glyphicons_030_pencil.png" width="20" height="20" /></a></td>
                      <td align="center"><label class="checkbox-inline">
                          <input type="checkbox" name="sel_newsid[]" value="<?=$row_news["news_id"];?>" />
                        </label></td>
                    </tr>
                    <?php	}?>
                  </tbody>
                </table></td>
            </tr>
          </table>
        </form>
      </div>
      <p class="line-brown clear"></p>
    </div>
  </div>
  <p class="clear"></p>
  <?php include("inc-bof-footer.php");?>
</div>
<?php include("inc-script.php");?>
<script type="text/javascript" src="../js/jquery.tablednd_0_5.js"></script> 
<script type="text/javascript">
	jQuery(document).ready(function(){ 
		 
		jQuery('#table-1').tableDnD({
        	onDrop: function(table, row) {
			var order= jQuery.tableDnD.serialize('id');
			jQuery.ajax({type: "POST",url: "ajax_check/drag_drop_news.php", data: order,  success: jQuery("#result").fadeIn(2500).toggle("slow")});
    		}
    	});
	}); 
</script> 
<script type="text/javascript">
	function MM_openBrWindow(theURL,winName,features) {
  		window.open(theURL,winName,features);
	}
</script>
</body>
</html>