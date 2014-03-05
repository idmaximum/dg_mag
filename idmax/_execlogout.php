<?
	session_start();

	session_unregister( "_ID" );
	session_unregister( "_LOGIN" );
	session_unregister( "_GRPID" );
	session_unregister( "_LASTLOGIN" );

	if (session_is_registered( "_ID" ))		session_destroy();
	if (session_is_registered( "_LOGIN" ))	session_destroy();
	if (session_is_registered( "_GRPID" )) 	session_destroy();
	if (session_is_registered( "_LASTLOGIN" )) 	session_destroy();
	
	unset($_SESSION['_ID']);
	unset($_SESSION['_LOGIN']);
	unset($_SESSION['_GRPID']);
	unset($_SESSION['_LASTLOGIN']);
	
	session_unset();
	
	header("Location:../admin.php");
	exit;
?>