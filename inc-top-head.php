<link rel="stylesheet" type="text/css" href="js/jquery.fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link rel="stylesheet" href="js/formValidator/css/validationEngine.jquery.css" type="text/css"/>
<!--[if lt IE 9]> 
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
   <link rel="stylesheet" type="text/css" href="css/css8.css" />
<![endif]-->
<style type="text/css">
.displayProfile {<? if($_SESSION['customerImg'] != ""){ ?>	
  background-image: url(uploads/member/<?php echo $_SESSION['customerImg'];?>); 
<?  }else if($_SESSION['fbID'] != ""){ ?>  
background-image: url(https://graph.facebook.com/<?php echo $_SESSION['fbID']?>/picture?type=large); 
<?php }else{?> 
background-image: url(images/user-psd-png.png); 
<?php }?>
}
</style> 