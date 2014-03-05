<?php

//Detect special conditions devices
$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

//do something with this information
if( $iPod || $iPhone || $iPad || $Android ){
   echo "Mobile"; 
}else if($webOS){
    //browser reported as a webOS device -- do something here
	 
}

if (preg_match('/iPhone|iPod|iPad|BlackBerry|Android/', $_SERVER['HTTP_USER_AGENT'])) {
   echo "This is one of the mentioned mobile device browsers ";
}else{
	echo "PC";
	}
	
	function chkBrowser(){
		preg_match('/iPhone|iPod|iPad|BlackBerry|Android/', $_SERVER['HTTP_USER_AGENT']);
	}
	$chkBrowserFn = chkBrowser();
	
	if($chkBrowserFn){
		 echo "This is one of the mentioned mobile device browsers ";
	}else{
	      echo "PC";
	}
?> 