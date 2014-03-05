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
	$sel_itemid = mosGetParam( $_FORM, 'sel_itemid', '' );
	 
	$category_id = mosGetParam( $_FORM, 'category_id', '' );
	$sub_category_id = mosGetParam( $_FORM, 'sub_category_id', '' );
	
	if ($action_up !==""){
		$action = $action_up;
	}	
		//echo $_POST["actionSel"];
	if( isset($action) && !empty($action) && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){
			switch($action){
				case "ลบ | Delete" : 
					
					$absPath=$_Config_absolute_path."/uploads/item/";
					
					$num = count($sel_itemid);					
					for( $i = 0; $i < $num; $i++){
						$qrySelImg = "select * from $_Config_table[itemimage] where item_id='$sel_itemid[$i]'";
						//echo $qrySelImg;
						$rsSelImg = $DB->Execute($qrySelImg);
						if( $rsSelImg->RecordCount() > 0){
							
							while($selImg = $rsSelImg->FetchRow()){
								$image_thumb = $selImg["item_image_thumb"];
								$image_large = $selImg["item_image_name"];
							
							
								$qryDelTipsimage = "delete from $_Config_table[itemimage] where item_image_id='$selImg[item_image_id]'";
								$DB->Execute($qryDelTipsimage);
										
								if( $DB->Affected_Rows()){
									#UnLink Image
									@FU::unlinkImage($absPath, $image_thumb);
									@FU::unlinkImage($absPath, $image_large);
								}
							}
						}
						
						#Delete special
						$qrySelSpecial = "select * from $_Config_table[item] where item_id='$sel_itemid[$i]'";
						$rsSelSpecial = $DB->Execute($qrySelSpecial);
						if( $rsSelSpecial->RecordCount() > 0){
							$SelSpecial = $rsSelSpecial->FetchRow();
							$image_thumb = $SelSpecial["item_image_thumb"];
							$image_large = $SelSpecial["item_image"];							
							
							$qryDelSpecial = "delete from $_Config_table[item] where item_id='$sel_itemid[$i]'";
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
					$num = count($sel_itemid);
					$query = "update $_Config_table[item] set item_publish='1' where ";
					for( $i = 0; $i < $num; $i++){
						$query .= "(item_id = $sel_itemid[$i])";
						if($i < $num-1){
							$query .= " or ";
						}
					}
					$DB->Execute($query);				
					break;
				case "ซ่อน | Unpublish" :
					$num = count($sel_itemid);
					$query = "update $_Config_table[item] set item_publish='0' where ";
					for( $i = 0; $i < $num; $i++){
						$query .= "(item_id = $sel_itemid[$i])";
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
	
	$category_name = $msObj->selectTable("item_category","category_name", "category_id", $category_id);
	$category_name = $category_name["category_name"];
	
	$subCategoryName = $msObj->selectTable("item_subcategory","sub_category_name", "sub_category_id", $sub_category_id);
	$subCategoryName = $subCategoryName["sub_category_name"];
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
<link rel="stylesheet" type="text/css" href="../js/jquery.fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" /> 
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
        <li><?php echo $category_name?>  <?php if($subCategoryName!=''){ echo "&gt; ".$subCategoryName;}?></li>
      </ul>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="70%"><h1 class="pagetitle"><?php echo $category_name?> <?php if($subCategoryName!=''){ echo "&gt; ".$subCategoryName;}?></h1></td>
          <td width="30%" class="text-center"></td>
        </tr>
      </table>
      <p class="line-brown"></p>
      <div class="detail-page">
        <form   method="post" action="item.php#content">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table width="100%" border="0" cellspacing="4" cellpadding="0">
                  <tr>
                    <td width="71%" align="left"><?php if($category_id != '4'){?><a class="btn btn-success" href="item_add.php?category_id=<?php echo $category_id; ?>&sub_category_id=<?php echo $sub_category_id; ?>#<?php echo $category_name; ?>">เพิ่ม | Add New</a><?php }?></td>
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
                      <td width="27%"><strong>หัวเรื่อง</strong></td>
                      <?php if($category_id == '4'){?><td width="22%"><strong>รายละเอียดผู้โพส</strong></td><?php }?>
                      <td width="10%"><strong> รูปประกอบ</strong></td>
                      <td width="11%"><strong>วันที่สร้าง/
                        วันที่แก้ไข </strong></td>
                      <td width="15%"><strong>เผยแพร่</strong></td>
                      <?php if($category_id != '4'){?><td width="7%"><strong>Edit</strong></td><?php }?>
                      <td width="8%"><strong>Func.</strong></td>
                    </tr>
                  </thead>
                  <?php
				   $sub_category_Sql = ($sub_category_id !="")? "and item_sub_category_id_FK ='$sub_category_id'" : "";
				 	
					$qrySel1 = "select * from $_Config_table[item] 
							where item_category_id_FK = '$category_id' $sub_category_Sql";
	
				//echo $qrySel1;
				$rsSel1 = $DB->Execute($qrySel1);
				$numrows = $rsSel1->RecordCount();
				?>
                  <tbody id="table-1">
                    <?php
				$qrySel2 = $qrySel1 . " order by order_by,item_id desc" ;
				$rsSel2 = $DB->Execute($qrySel2);
				//$rsSel2 = $DB->SelectLimit($qrySel2, $limit, $start); 
				While($row_item = $rsSel2->FetchRow()){
				?>
                    <tr  id="<?php echo $row_item["item_id"];?>">
                      <td><?php echo mosStripslashes($row_item["item_title"]);?><br />
                      <div style="height:15px"></div>
                        Like : <?php echo $row_item["item_like"]?><br />
                        <a href="#">Comment : <?php echo $row_item["item_comment"]?></a></td>
                       <?php if($category_id == '4'){?>
                       <td>
                       <?php 
					      $memberID = $row_item["memberID_FK"];
					   
					   	 $rowMember = $msObj->selectTable("member","customer_fname,primary_email","customer_id","$memberID");
					      $customerFname =  $rowMember["customer_fname"];
						 $customerEmail =  $rowMember["primary_email"];
					   ?>
                         Name : <?php echo $customerFname?><br />
                         Email : <?php echo $customerEmail?></td>
                       <?php }?>
                      <td align="center"><a href="itemPicture.php?item_id=<?=$row_item["item_id"];?>" class="galleryPage"><img src="<?php echo "../uploads/item/$row_item[item_image_thumb]"?>"  /></a>  <br />
                        <?php if($category_id != '4'){?>
                       <a href="#" class="list2" onclick="MM_openBrWindow('item_picture.php?item_id=<?php echo $row_item["item_id"]?>','','toolbar=yes,status=yes,scrollbars=yes,resizable=yes,width=880,height=660')"><img src="images/icon_add.png" alt="Add New" width="16" height="16" border="0" /></a> เพิ่มรูป <?php }?></td>
                      <td align="center"><?=(DT::isDate($row_item["item_create_date"]))? DT::DateTimeShortFormat($row_item["item_create_date"], 0, 0, "Th") : "-" ;?>
                        <br />
                        <?=(DT::isDate($row_item["item_update_date"]))? DT::DateTimeShortFormat($row_item["item_update_date"], 0, 0, "Th") : "-" ;?></td>
                      <td align="center"><?php echo ($row_item["item_publish"]=="1")? "เผยแพร่" : "ไม่เผยแพร่"; ?></td>
                      <?php if($category_id != '4'){?><td align="center"><a href="item_edit.php?item_id=<?=$row_item["item_id"]?>&category_id=<?php echo $row_item["item_category_id_FK"]; ?>&sub_category_id=<?php echo $row_item["item_sub_category_id_FK"]; ?>#<?php echo $list_category["category_name"]; ?>"><img src="images/png/glyphicons_030_pencil.png" width="20" height="20" /></a></td><?php }?>
                      <td align="center"><label class="checkbox-inline">
                          <input type="checkbox" name="sel_itemid[]" value="<?=$row_item["item_id"];?>" />
                        </label></td>
                    </tr>
                    <?php	}?>
                  </tbody>
                </table></td>
            </tr>
          </table>
          <input name="category_id" type="hidden" id="category_id" value="<?=$category_id?>"/> 
          <input name="sub_category_id" type="hidden" id="sub_category_id" value="<?=$sub_category_id?>"/>
          <input name="category_name" type="hidden" id="sub_category_id" value="<?=$category_name?>"/>
       
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
<script type="text/javascript" src="../js/jquery.fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script> 
<script type="text/javascript">
	jQuery(document).ready(function(){ 
	
	 jQuery(".galleryPage").fancybox({
		'width'				: 900, 
		 'height'			: 560,
		 'padding'	: 0,
		'autoScale'     	: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});
		 
		jQuery('#table-1').tableDnD({
        	onDrop: function(table, row) {
			var order= jQuery.tableDnD.serialize('id');
			jQuery.ajax({type: "POST",url: "ajax_check/drag_drop_item.php", data: order,  success: jQuery("#result").fadeIn(2500).toggle("slow")});
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