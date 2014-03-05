<?
	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "../configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	require_once( $_Config_absolute_path . "/includes/func.class.php" );
	
	if( empty($_SESSION['_CUTOMERID']) || empty($_SESSION['_USERNAME']) ){ 
		exit();
	}
 
	$DB = mosConnectADODB();
	$msObj = new MS($DB);		
	
	//echo $_SESSION[SESS_ID]; 
	    
	$btnLike = trim( mosGetParam( $_POST, 'btnLike', ''  )); 
	$item_id = trim( mosGetParam( $_POST, 'item_id', ''  ));
	$Rip = FU::GetIP();
	$customerID = $_SESSION['_CUTOMERID'];	  
	
	
	 if($btnLike == "like"){
		 
		    $qrySelView = "select likeID from $_Config_table[like] 
		 				where   memberID_FK = $customerID and  itemID_FK = '$item_id'
						order by likeID desc ";
		   $rsSelView = $DB->Execute($qrySelView);
		  $numrow =  $rsSelView->RecordCount();
		  if($numrow <= 0){	
		  		//***********
				$qryInsLike = "insert into  $_Config_table[like] ( 
							itemID_FK, memberID_FK, dateTimeLike, userIP )
							values(
							'$item_id','$customerID',now(),'$Rip' )";
							
							$DB->Execute($qryInsLike);//***/
				//***********
				$qryUpdateItem = "update $_Config_table[item] 
			 			   set item_like = item_like + 1 where item_id = '$item_id'";
			    $DB->Execute($qryUpdateItem);
		  		//************
		  }#end numrow
		 
		 
	}else if($btnLike == "unLike"){	
	
		//******
		 $Del_Like = "delete from $_Config_table[like]  where  memberID_FK = $customerID and  itemID_FK = '$item_id'";
		 $DB->Execute($Del_Like);
		
		//*****************
		  $qrySelView = "select likeID from $_Config_table[like] 
		 				where   itemID_FK = '$item_id'
						order by likeID desc ";
		   $rsSelView = $DB->Execute($qrySelView);
		   $numrow =  $rsSelView->RecordCount();
		//*****************
	 
		 $qryUpdateItem = "update $_Config_table[item] 
			 			   set item_like = '$numrow' 
						   
						   where item_id = '$item_id'";
		 $DB->Execute($qryUpdateItem);/**/
			  
	}#end if
	
	$qryDetail = "select item_like from $_Config_table[item] where item_id = '$item_id'";
	$rsDetail = $DB->Execute($qryDetail);
	$detailitem = $rsDetail->FetchRow($rsDetail); 
	
	 
?> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td width="68%"> 
   <?php if($btnLike == "like"){?>
       <img src="images/btn_unlike.png" width="74" height="42" class="btnLike" id="unLike" />                 
       <?php }else if($btnLike == "unLike"){ ?>                 
        <img src="images/btn_like.png" width="74" height="42" class="btnLike" id="like" />   
       <?php }?>   
    </td>
  <td width="32%" align="center"><div class="txtpink12"><?php echo $detailitem["item_like"];?></div></td>
  </tr>
</table>
