<?php
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
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>

<?php 
	  $qryDetailUnlike  = "select * from $_Config_table[item] where item_id = '7'";
		$rsDetailUnlike   = $DB->Execute($qryDetailUnlike);
		$detailitemUnlike = $rsDetailUnlike->FetchRow(); 
		
		$item_like = $detailitemUnlike["item_like"];
		
	echo $item_like =$item_like+(-1);
?>
<div style="border: 3px solid white; border-radius: 25px;
height:40px; width:40px;
background: #aaa url(https://graph.facebook.com/100000169811660/picture?type=small); background-position:center center; background-repeat:no-repeat">

</div>
<img src="https://graph.facebook.com/100000169811660/picture?type=large" height="40"  align="absmiddle"/> 
 
<?
	 
	
	
	$number='0123456789'; // ตัวแปรตัวเลข ที่จะเอาไปสุ่ม
	for($i==1;$i<5;$i++){ // จำนวนหลักที่ต้องการสามารถเปลี่ยนได้ตามใจชอบนะครับ จาก 5 เป็น 3 หรือ 6 หรือ 10 เป็นต้น
		$random=rand(0,strlen($number)-1); //สุ่มตัวเลข
		
		$cut_txt=substr($number,$random,1); //ตัดตัวเลข หรือ ตัวอักษรจากตำแหน่งที่สุ่มได้มา 1 ตัว
		$resultNumber.=substr($number,$random,1); // เก็บค่าที่ตัดมาแล้วใส่ตัวแปร
		$number=str_replace($cut_txt,'',$number); // ลบ หรือ แทนที่ตัวอักษร หรือ ตัวเลขนั้นด้วยค่า ว่าง
	}
	
	echo $resultNumber;
	
	 
?> <br>
<br>


<?php
	function alphanumeric_rand($num_require=8) {
	$alphanumeric = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',0,1,2,3,4,5,6,7,8,9);
	if($num_require > sizeof($alphanumeric)){
		echo "Error alphanumeric_rand(\$num_require) : \$num_require must less than " . sizeof($alphanumeric) . ", $num_require given";
		return;
	}
	$rand_key = array_rand($alphanumeric , $num_require);
	for($i=0;$i<sizeof($rand_key);$i++) $randomstring .= $alphanumeric[$rand_key[$i]];
	return $randomstring;
}

#echo  alphanumeric_rand(8);


$password = "idmax69pass";	
		
		$password = "idmax".$password."pass";
	   echo $passwordHash = hash('sha256', $password);	

?>
</body>
</html>