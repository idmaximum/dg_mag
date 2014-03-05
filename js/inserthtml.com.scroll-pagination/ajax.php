<?php
	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "../configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	require_once( $_Config_absolute_path . "/includes/func.class.php" );


	$DB = mosConnectADODB();
	$msObj = new MS($DB);

	$offset = is_numeric($_POST['offset']) ? $_POST['offset'] : die();
	$postnumbers = is_numeric($_POST['number']) ? $_POST['number'] : die();
	$itemCate = 	$_POST['itemCate']; 

 function strCrop($txt,$num) { #ข้อความ,จำนวน
		if(strlen($txt) >= $num ) {
			$txt = iconv_substr($txt, 0, $num,"UTF-8")."...";
		}
		return $txt;
	}	

$sqlSubCate = ($itemCate!= "")? "" : "" ;
echo $run = mysql_query("SELECT * FROM item  
					where item_publish != '0' 
				    LIMIT ".$postnumbers." OFFSET ".$offset); 
$i = 1; 
while($row_item = mysql_fetch_array($run)) { 
?>
<div class="row-like-share <?php if($i%4 == 0){?>frist-row-item<?php }?>">
  <div class="img-thumb-content">
    <div class="item-img item-img-frist"><img src="uploads/item/<?php echo $row_item["item_image_thumb"];?>" alt="<?php echo $row_item["item_title"];?>" />
      <div class="bg-thumb-item"><a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>"><?php echo strCrop($row_item["item_title"],28);?></a></div>
      <div class="mask">
        <a href="itemDetail.php?item_id=<?php echo $row_item["item_id"];?>" class="info"></a>
        <p><a href="pinItem.php?item_id=<?php echo $row_item["item_id"];?>" class="pinToBoard"><img src="images/add-to-pin.png" alt="add to pin" width="165" height="40"></a></p>
        </div>
    </div>
  </div> 
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="12%"><img src="images/3_hover_thumbnail_13.png" alt="like" width="17" height="14"></td>
                <td width="65%"><?php echo $row_item["item_like"];?></td>
                <td width="15%"><img src="images/3_hover_thumbnail_15.png" alt="comment" width="18" height="14"></td>
                <td width="8%"><?php echo $row_item["item_comment"];?></td>
    </tr>
  </table> 
  <p class="clear"></p>
</div>
<?php $i++;}?>