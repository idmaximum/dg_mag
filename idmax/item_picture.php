<?	header ('Content-type: text/html; charset=utf-8');
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
	}
	
	#Create Obj
	$DB = mosConnectADODB();
	$msObj = new MS($DB);

	#Get Param
	$action = mosGetParam( $_FORM, 'action', '' );
	$item_id = mosGetParam( $_FORM, 'item_id', '' ); //product id
	$id = mosGetParam( $_FORM, 'id', '' ); //check box

	#####//Set Show Page ////////////////////////////////////////////////////////
	$limit = '30';		// How many results should be shown at a time						
	$scroll = '20'; 	// How many elements to the record bar are shown at a time

	$display = (!isset ($_GET['show']) || $_GET['show']==0)? 1 : $_GET['show'];
	if( !empty( $userkey ) )
		$display = 1;
		
	$start = (($display * $limit) - $limit);

	//Take Action
	//echo "Action ".$action;
	if( isset($action) && !empty($action)){
		switch($action){
			case "Delete" :
				$num = count($id);
				for( $i = 0; $i < $num; $i++){
					$qrySel = "select * from $_Config_table[itemimage] where item_image_id='$id[$i]'";
					//echo $qrySel;
					$rsSel = $DB->Execute($qrySel );
					$gallery = $rsSel->FetchRow(  );

					$galleryPicThumb = $gallery["item_image_thumb"]; 
					$galleryPicLarge = $gallery["item_image_name"]; 

					//Delete Main Table guide
					$qryDel = "delete from $_Config_table[itemimage] where item_image_id='$id[$i]'";
					//echo "<br>". $qryDel;
					$rsDel = $DB->Execute($qryDel );
					if ($DB->Affected_Rows() ){
						//???????????? guide ??????? ?????????????? ??????????????????????????????
						if( !empty($galleryPicThumb)){
							if (file_exists($_Config_absolute_path."/uploads/item/".$galleryPicThumb))
								@unlink( $_Config_absolute_path."/uploads/item/".$galleryPicThumb );						
						}
						if( !empty($galleryPicLarge)){
							if (file_exists($_Config_absolute_path."/uploads/item/".$galleryPicLarge))
								@unlink( $_Config_absolute_path."/uploads/item/".$galleryPicLarge );						
						}
					}
				}
				break;
		}//end switch
	}//end if
//=======================================================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HTML>
<HEAD>
<TITLE><?=$_Config_sitename?>'s Backoffice</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<link href="../css/font.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
	function confirmSubmit(msg)
	{
		var agree=confirm(msg);
		if (agree)
			return true ;
		else
			return false ;
	}
//-->
</script>
<script src="../js/jquery-1.8.js"></script>
<script type="text/javascript" src="../js/fancybox/jquery.metadata.js"></script>
<script type="text/javascript" src="../js/fancybox/jquery.pngFix.pack.js"></script>
<script type="text/javascript" src="../js/fancybox/jquery.fancybox-1.0.0.js"></script>
<link rel="stylesheet" type="text/css" href="../js/fancybox/fancy.css" media="screen" />
<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
}
</style>
<script type="text/javascript"> 
$(document).ready(function() {
	//Activate FancyBox
	$("p#test1 a").fancybox({
		'hideOnContentClick': true
	});					
});</script>
</HEAD>
<BODY>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#FFFFFF" height="1"><img src="../../images/1pix.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td bgcolor="#256A00"></td>
        </tr>
        <tr>
          <td height="1" bgcolor="#FFFFFF"><img src="../../images/1pix.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td height="8" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr valign="top">
                <td width="1" height="332" background="images/line_menu2.gif"><img src="images/1pix.gif" width="1" height="1"></td>
                <td width="10"><img src="images/1pix.gif" width="8" height="8"></td>
                <td ><!-- Form : Start--> 
                  <br>
                  <form name="form1" method="post" action="item_picture.php?item_id=<?php echo $item_id;?>" onSubmit="return confirmSubmit('<?php echo "คุณต้องการลบหรือไม่"; ?>')" >
                    <TABLE width=95% border=0 align=center cellPadding=2 cellSpacing=1 bgColor=#C9C9C9 class="txtBlack12" marginwidth="0">
                      <TBODY>
                        <TR bgcolor="#EBEBEB">
                          <TD height=23 align="center" class="menuBig" background="images/bg_bar_1.jpg"> More Picture</TD>
                        </TR>
                        <TR bgcolor="#EBEBEB">
                          <TD align="middle" bgcolor="#CCCCCC"><div align="right">
                              <input name="action" type="submit" onClick="MM_goToURL('parent','item_picture_add.php?item_id=<?=$item_id?>');return document.MM_returnValue" value="Add New" class="medium2">
                              <input type="submit" name="action" value="Delete" class="medium2">
                            </div></TD>
                        </TR>
                        
                        <!-- Loop : start -->
                        <?php						
						$limit = '10';		// How many results should be shown at a time						
						$scroll = '20'; 	// How many elements to the record bar are shown at a time


						$qryguide1 = "select * from $_Config_table[itemimage] where item_id = '$item_id' ";
						$rsguide1 = $DB->Execute($qryguide1);
						$numrows = $rsguide1->RecordCount();

						//include "nav_productgroup.php";

					  	$qryguide2 = $qryguide1." order by item_image_id desc limit $start,$limit" ;
						//echo $qryguide2;
						$rsguide2 = $DB->Execute($qryguide2 );							
                      ?>
                        <TR valign="top">
                          <TD height="37" align=center bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="5" class="mediumBk">
                              <tr>
                                <?
								$i = 1;
								while( $gallery = $rsguide1->FetchRow( )){
									//$arrSize=@getImagesize("../uploads/gallery/".$gallery->gallery_large);
									if(( $i != 1) && (($i % 2) == 1)){
										$i = 1;
										echo '</TR><TR valign="top">';
									}
							?>
                                <td valign="top" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="5" class="medium">
                                    <tr>
                                      <td align="center" valign="top" class="desTh"><p id="test1"> <a href="../uploads/item/<?=$gallery["item_image_name"]?>"><img src="../uploads/item/<?=$gallery["item_image_thumb"]?>" border="0"></a> </p></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="top" class="desTh"><a href="javascript:;" onclick="MM_goToURL('parent','item_picture_edit.php?item_image_id=<?=$gallery["item_image_id"]?>#content');return document.MM_returnValue"> <img src="images/document_edit.png" alt="Edit" width="16" height="16" border="0" bproduct="0" /> </a>
                                        <input type="checkbox" name="id[]" value="<?=$gallery["item_image_id"]?>"></td>
                                    </tr>
                                  </table></td>
                                <?
									$i++;
								}
							?>
                            </table></TD>
                        <TR valign="middle" bgColor=#EBEBEB>
                          <TD colspan="2" align=center bgcolor="#CCCCCC"><div align="right">
                              <input type="hidden" name="show" value="<?=$display?>">
                              <input type="hidden" name="item_id" value="<?=$item_id?>">
                              <input name="action" type="submit" onClick="MM_goToURL('parent','item_picture_add.php?item_id=<?=$item_id?>');return document.MM_returnValue" value="Add New" class="medium2">
                              <input type="submit" name="action" value="Delete" class="medium2">
                            </div></TD>
                        </TR>
                    </TABLE>
                  </form>
                  
                  <!-- Form : end --> 
                  <BR clear="all">
                  <font size="2" face="Tahoma"> 
                  <!-- Page : start -->
                  <?
				$_self = $_SERVER['PHP_SELF'];
				echo "<br>";
				$paging = ceil ($numrows / $limit);

				// Display the navigation
				if ($display > 1) {			
					$previous = $display - 1;	
					printf("<a href=\"%s?show=1&item_id=%s\"><img src=\"images/i.p.firstpg.gif\" border=0></a> ", $_self, $item_id);
					printf("<a href=\"%s?show=%s&item_id=%s\"><img src=\"images/i.p.prevpg.gif\" border=0></a> ", $_self, $previous, $item_id);
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
								printf("[<a href=\"%s?show=%s&item_id=%s\">%s</a>]", $_self, $i, $item_id, $i);
						}
					}

					if ($display < $paging) {
						$next = $display + 1;
						printf(" <a href=\"%s?show=%s&item_id=%s\"><img src=\"images/i.p.nextpg.gif\" border=0></a>", $_self, $next, $item_id);
						printf(" <a href=\"%s?show=%s&item_id=%s\"><img src=\"images/i.p.lastpg.gif\" border=0></a>", $_self, $paging, $item_id);
					}
				?>
                  <!-- Page : end  --> 
                  </font></td>
                <td width="10"><img src="../../images/1pix.gif" width="1" height="1"></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="8" valign="top">&nbsp;</td>
  </tr>
</table>
<script language="JavaScript">
<!--
function MM_goToURL() { //v3.0
var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
</BODY>
</HTML>