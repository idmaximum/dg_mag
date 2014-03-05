<?
	session_start();

	session_unregister( "_CUTOMERID" );
	session_unregister( "_USERNAME" );
	session_unregister( "_FNAME" );
	session_unregister( "_EMAIL" );
	session_unregister( "_LASTACCESS" );	
	
	session_unregister( "fbID" );
	session_unregister( "NameDisplay" );
	session_unregister( "customerImg" );
	

	if (session_is_registered( "_CUTOMERID" ))		session_destroy();
	if (session_is_registered( "_USERNAME" ))	session_destroy();
	if (session_is_registered( "_FNAME" )) 	session_destroy();
	if (session_is_registered( "_EMAIL" ))	session_destroy();
	if (session_is_registered( "_LASTACCESS" )) 	session_destroy();
	if (session_is_registered( "fbID" )) 	session_destroy();
	if (session_is_registered( "NameDisplay" )) 	session_destroy();
	if (session_is_registered( "customerImg" )) 	session_destroy();
	
	
	unset($_SESSION['_CUTOMERID']);
	unset($_SESSION['_USERNAME']);
	unset($_SESSION['_FNAME']);
	unset($_SESSION['_EMAIL']);
	unset($_SESSION['_LASTACCESS']);
	unset($_SESSION['fbID']);
	unset($_SESSION['NameDisplay']);
	unset($_SESSION['customerImg']);
		
	session_unset();
	
	header("Location:index.php");
	exit;
?>