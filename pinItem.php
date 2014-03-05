<?php
	ob_start('ob_gzhandler');
	header ('Content-type: text/html; charset=utf-8');
	define( '_VALID_ACCESS', 1 );
	session_start();

	require_once( "configuration.php" );
	require_once( $_Config_absolute_path . "/includes/ms_com.php" );
	require_once( $_Config_absolute_path . "/includes/ms.class.php" );
	require_once( $_Config_absolute_path . "/includes/datetime.class.php" );
	require_once( $_Config_absolute_path . "/includes/func.class.php" );
	
	$DB = mosConnectADODB();
	$msObj = new MS($DB);
	
	$item_id = mosGetParam($_FORM,'item_id','');
	 
	 if( empty($_SESSION['_CUTOMERID']) || empty($_SESSION['_USERNAME']) ){
		mosRedirect("login_to_board.php?item_id=$item_id");
		exit();
	}
	 
	$action = trim(mosGetParam($_FORM,'action',''));
	$url = trim(mosGetParam($_REQUEST,'url','')); 
	$customerID = $_SESSION['_CUTOMERID'];	
	 
	  if($url == ""){# เช็ดดูว่าใช่ spam หรือป่าว ############### Vote	
		if( isset($action) && !empty($action) && $action == "pinToBoard" && eregi($_SERVER['HTTP_HOST'],$_SERVER['HTTP_REFERER']) ){
			$CreateNewBoard = trim(mosGetParam($_FORM,'CreateNewBoard',''));
			$catagoryBoard = trim(mosGetParam($_FORM,'catagoryBoard',''));
			$message = trim(mosGetParam($_FORM,'message',''));
			
			$Rip = FU::GetIP();
			
			if($CreateNewBoard != ""){//มีการเพิ่มห้องใหม่
				//********* insert Category
				 $qryAddCateBoard =  "insert into $_Config_table[board_category]
								  ( category_name, memberID_FK) 
							  values
						 	   ( $DB->qstr('$CreateNewBoard'),$DB->qstr('$customerID') )";
			    $DB->Execute($qryAddCateBoard);	
				
				//******* Select New cate ***********
				$qryNewCateBoard = "select * from $_Config_table[board_category] 
						         	where memberID_FK = '$customerID' order by category_id desc";
				$rsNewCateBoard = $DB->Execute($qryNewCateBoard);
				$rowCateBoard = $rsNewCateBoard->FetchRow(); 
				$categoryNameNewCate = $rowCateBoard['category_id'];
				
				//********* insert pin to acticle **********
				 $qryAddPinToBoard =  "insert into $_Config_table[pin_article]
										( itemID_FK, memberID_FK,comment,
										   dateTimeComment,userIP,boardCategoryID_FK) 
									values
									  ( $DB->qstr('$item_id'),$DB->qstr('$customerID'),$DB->qstr('$message') 
									    ,now() ,$DB->qstr('$Rip') ,$DB->qstr('$categoryNameNewCate')  )";
			    $DB->Execute($qryAddPinToBoard);
				
			}else{//else no CreateNewBoard
				//********* insert pin to acticle **********
				 $qryAddPinToBoard =  "insert into $_Config_table[pin_article]
										( itemID_FK, memberID_FK,comment,
										   dateTimeComment,userIP,boardCategoryID_FK) 
									values
									  ( $DB->qstr('$item_id'),$DB->qstr('$customerID'),$DB->qstr('$message') 
									    ,now() ,$DB->qstr('$Rip')  ,$DB->qstr('$catagoryBoard') )";
			    $DB->Execute($qryAddPinToBoard);	

			}//if CreateNewBoard

			  mosRedirect("pinComplate.php?item_id=$item_id");
			
		}#end if
	 }#end if 
	 
	 //***************
	$qryDetail = "select * from $_Config_table[item] where item_id = '$item_id'";
	$rsDetail = $DB->Execute($qryDetail );
	$detailitem = $rsDetail->FetchRow($rsDetail);	
	
	$item_title = $detailitem["item_title"]; 
?><!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PIN</title>

<link rel="stylesheet"href="js/bootstrap/css/bootstrap_2.css" >
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" href="js/formValidator/css/validationEngine.jquery.css" type="text/css"/>
 
<style type="text/css">
body {
	background-image: url(images/bg-pin-to-item.png);
	background-repeat: repeat-x;
	background-position: left top; 
}
.main-pin {
	padding: 50px;
	text-align:center
}
.main-pin form p {
	padding: 10px;
}
.formError { left: 60px !important; }
.imgCrop img {
	background-color: #FFF;
	padding: 5px;
	border: 1px solid #fcfcfc;
	box-shadow: 1px 1px 1px #ccc;
}
</style>
</head>

<body>
<div class="main-pin">
<form action="" method="post"role="form"id="formID" class="formular" >
  <div class="imgCrop"><img src="uploads/item/<?php echo $detailitem["item_image_thumb"];?>" width="102"   ></div>
  <p>
  <select name="catagoryBoard" class="validate[required] form-control" id="catagory-board"  >
    <option value="">เลือกเก็บเข้าบอร์ด</option>
    <option value="Create New Board">Create New Board</option>
     <?php
		  $qryBoardCate = "select * from $_Config_table[board_category] 
		  				where memberID_FK ='0' or( memberID_FK ='$customerID')";
		  $rsBoardCate = $DB->Execute($qryBoardCate);
		  if( $rsBoardCate->RecordCount() > 0 ){
			  while($spBoardCate = $rsBoardCate->FetchRow()){							
	   ?>  
    <option value="<?php echo $spBoardCate["category_id"];?>"><?php echo $spBoardCate["category_name"];?></option>
   <?php } }#end sub pic?>  
  </select>
  </p>
  <div class="content-pin" style="padding-left:10px"></div>
  <p><textarea name="message" rows="4" class="validate[required] form-control" id="message" style="width:255px"placeholder="พิมพ์ข้อความที่ต้องการ" ></textarea></p>
  <p>
    <input class="btn btn-primary btn-lg btn-block" type="submit" value="ตกลง" style="outline:none"></p>  
    <input name="action" type="hidden" value="pinToBoard" />
    <input name="item_id" type="hidden" value="<?php echo $item_id?>" />
    <input name="url" type="text" class="url" id="url" />
  </form>
<script type="text/javascript" src="js/jquery-1.8.js"></script> 
<script type="text/javascript" src="js/jquery.corner.js"></script> 
<script src="js/formValidator/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/formValidator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
  jQuery(document).ready(function(){
	  // binds form submission and fields to the validation engine
	  jQuery(".imgCrop img").corner();
	  jQuery("#formID").validationEngine();
	  
	  //******* new category
	  jQuery( "#catagory-board" ).change(function() {
		 var newboard =  jQuery( this ).val();
		 
		  jQuery.ajax({
			 type: "POST",
			 url: "jQueryAjax/newBoard.php",
			 data: "catagoryBoard="+newboard,
			 cache: false,
			  success: function(data){   
				 jQuery(".content-pin").html(data);
				}
			});	 
	  }); 
  });  
</script>
</div> 
</body>
</html>