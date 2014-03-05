<?
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
	
	$email = trim( mosGetParam( $_GET, 'email', ''  ));
	
	$mag.="<style type=\"text/css\">";
	$mag.=".btn-primary {";
	$mag.="	display: none;";
	$mag.="}";
	$mag.="</style>";

	if ($email == ""){
		echo "Please Enter your e-mail";
	}else{
	function verify_email($email){   
    list($email_user,$email_host)=explode("@",$email);   
    $host_ip=gethostbyname($email_host);   
    if(eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $email) && !ereg($host_ip,$email_host)) {   
      return "อีเมลนี้ใช้ในการสมัครได้";  
		  //return "<span class=\"txtGreen12\">This email address used to sign up.</span>";
		 
    }else{   
       // return "อีเมลนี้ไม่สามารถใช้ในการสมัครได้";   
		return "<span class=\"txtRed12\">"."อีเมลนี้ไม่สามารถใช้ในการสมัครได้"."</span>";
    }   
}   
  
   //*************
	$qryAcct = "select * from $_Config_table[member] where primary_email = '$email'";
	$rsAcct = $DB->Execute($qryAcct);		
	  if( $rsAcct->RecordCount() > 0 ){			
	  	  echo $mag;
		   echo "<span class=\"txtRed12\">"."อีเมลนี้ไม่สามารถใช้ในการสมัครได้"."</span>"; 
		   echo $errorEmail ==1;
	  }
	  else
	  {	echo verify_email("$email");   	}		
	//************
	} //end email ""
?>