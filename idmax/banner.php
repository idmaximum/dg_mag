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
	$id = mosGetParam( $_FORM, 'id', '' );
	$keyword = mosGetParam( $_FORM, 'keyword', '' );
	
	$category_id = mosGetParam( $_FORM, 'category_id', '' );
	$category_name = $msObj->selectTable("banner_category","category_name", "category_id", $category_id);
	$category_name = $category_name["category_name"];	

	if ($action_up !==""){
	$action = $action_up;
	}
	
	if( isset($action) && !empty($action)){
		switch($action){
			case "ลบ | Delete" : 
			
			$num = count($id);
			$absPath=$_Config_absolute_path."/uploads/banner/";

			for( $i = 0; $i < $num; $i++){
				$query = "select *  from $_Config_table[banner]  where banner_id = $id[$i]";
				//echo $query;
				$rsSelImage = $DB->Execute($query);
				
				if( $rsSelImage->RecordCount() > 0){
					$Image_del = $rsSelImage->FetchRow();
					$imageThumb = $Image_del['banner_pic'];
					$imageSWF = $Image_del['banner_swf'];
				
					$Del_category = "delete from $_Config_table[banner]  where banner_id = $id[$i]";
					$DB->Execute($Del_category);
					
					if( $DB->Affected_Rows()){
						FU::unlinkImage($absPath, $imageThumb);
						FU::unlinkImage($absPath, $imageSWF);
					}
				}

			}
			break;
		
		
			case "เผยแพร่ | Publish" :
				$num = count($id);
				$query = "update  $_Config_table[banner] set publish='1' where ";
				for( $i = 0; $i < $num; $i++){
					$query .= "(banner_id = $id[$i])";
					if($i < $num-1){
						$query .= " or ";
					}
				}
				$DB->Execute($query);
				break;
				
			case "ซ่อน | Unpublish" :
				$num = count($id);
				$query = "update  $_Config_table[banner] set publish='0' where ";
				for( $i = 0; $i < $num; $i++){
					$query .= "(banner_id = $id[$i])";
					if($i < $num-1){
						$query .= " or ";
					}
				}
				$DB->Execute($query);
				break; 
		
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
        <li>Banner &gt;</li>
        <li><?php echo $category_name?></li>
      </ul>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="70%"><h1 class="pagetitle"><?php echo $category_name?></h1></td>
          <td width="30%" class="text-center"></td>
        </tr>
      </table>
      <p class="line-brown"></p>
      <div class="detail-page">
        <form action="" method="post">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table width="100%" border="0" cellspacing="4" cellpadding="0">
                  <tr>
                    <td width="71%" align="left"><a class="btn btn-success" href="banner_add.php?category_id=<?php echo $category_id; ?>#banner">เพิ่ม | Add New</a></td>
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
                      <td width="33%"><strong>Banner Name</strong></td>
                      <td width="41%"><strong> Images</strong></td>
                      <td width="14%"><strong>Edit</strong></td>
                      <td width="12%"><strong>Func.</strong></td>
                    </tr>
                  </thead>
                  <tbody id="table-1">
                    <?php 
					$qrySel1 = "select * from $_Config_table[banner] 
							where bannerCategoryID_FK = '$category_id'
							 order by banner_order  "; 
				//echo $qrySel1;
				$rsSel1 = $DB->Execute($qrySel1);
				$numrows = $rsSel1->RecordCount();
				While($row_banner = $rsSel1->FetchRow()){
				?>
                    <tr id="<?php echo $row_banner["banner_id"];?>">
                      <td><?php echo $row_banner["banner_name"];?> <em><span class="txtRed14"><?php echo ($row_banner["publish"]=="0")? "ไม่เผยแพร่" : ""; ?></span></em><br />
                        จำนวนคลิก : <?php echo $row_banner["click"];?> </td>
                      <td align="center"><img src="<?php echo "../uploads/banner/$row_banner[banner_pic]"?>" style="max-width:400px"  /></td>
                      <td align="center"><a href="banner_edit.php?banner_id=<?=$row_banner["banner_id"]?>&category_id=<?php echo $category_id; ?>#banner"><img src="images/png/glyphicons_030_pencil.png" width="20" height="20" /></a></td>
                      <td align="center"><input name="id[]" type="checkbox" class="medium2" value="<?=$row_banner["banner_id"]?>" /> </td>
                    </tr>
                   <?php	}?>  
                  </tbody>
                </table></td>
            </tr>
           
          </table>
          <input name="category_id" type="hidden" id="category_id" value="<?=$category_id?>"/>
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
			jQuery.ajax({type: "POST",url: "ajax_check/drag_drop_banner.php", data: order,  success: jQuery("#result").fadeIn(2500).toggle("slow")});
    		}
    	});
	}); 
</script>  
</body>
</html>