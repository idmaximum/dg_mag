<footer id="footer">
  <div class="site-content">
    <div class="column-footer"> 
      <p><strong>เมนูหลัก</strong></p>
      <div class="sub-menu-bottom txtpink14">
        <p><a href="index.php" title="Home" target="_self">Home</a></p>
        <p><a href="decoration.php" title="Decoration" target="_self">Decoration</a></p>
        <p><a href="kitchen.php" title="Kitchen" target="_self">Kitchen</a></p>
        <p><a href="shopping.php" title="Shopping" target="_self">Shopping</a></p>
        <p><a href="item.php?item_category_id=4" title="Like &amp; Share" target="_self">Like &amp; Share</a></p>
      </div>
    </div>
    <div class="column-footer">
      <p><strong>ส่วนสมาชิก</strong></p>
      <div class="sub-menu-bottom txtpink14">
        <p><a href="register.php">Register </a></p>
        <p><a href="login.php">Login by email</a></p>
        <p><a href="forget_password.php" class="forgetPawword">Forget Password?</a></p>
      </div>
    </div>
    <div class="column-footer">
      <p><strong>เกี่ยวกับเรา</strong></p>
      <div class="sub-menu-bottom txtpink14">
        <p><a href="#"> ติดต่อลงโฆษณา</a></p>
        <p><a href="#">เกี่ยวกับเรา</a></p>
        <p><a href="#">ติดต่อเรา</a></p>
      </div>
      <p>&nbsp;</p>
      <p><strong>เว็บไซต์ในเครือ</strong></p>
      <div class="sub-menu-bottom txtpink14" style="padding-top:0">
        <p><a href="http://www.home.co.th/" target="_blank">www.home.co.th</a></p>
        <p><a href="http://www.dglikes.com/" target="_blank">www.dglikes.com</a></p>
      </div>
    </div>
    <div class="column-footer width220">
      <div id="fb-root"></div>
      <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=272725069541356";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
      <div class="fb-like-box" data-href="https://www.facebook.com/pages/Dglikes/312418312227547" data-width="220" data-height="250" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
    </div>
    <div class="clear"></div>
    <span class="txtBrown13"> © Copyright 2014 by Home Buyers Guide Co., Ltd.</span> </div>
    
</footer><div id="loading-page"></div><!---->
 <div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '598574500225155', // App ID
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });
  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>
<script type="text/javascript">
function ShowFaceookLogin(){
	FB.login(function(response) {
		 if (response.authResponse) {
		   	 
			   FB.api('/me', function(response) {
				// console.log('Good to see you, ' + response.name + '.');
				// alert(response.name);
				 window.top.location = "<?php echo $_Config_live_site?>/index_2.php?id="+response.id+"&email="+response.email+"&fbName="+response.name;  
			   });
		 } 
   }, {scope: 'publish_stream,email,user_likes'});
}
</script> 
<script type="text/javascript" src="js/jquery-1.8.js"></script> 
<script type="text/javascript" src="js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/swf/jquery.media.js"></script>
<script type="text/javascript" src="js/jquery.fancybox/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="js/menuDrop/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/menuDrop/jquery.hoverIntent.minified.js"></script>
<script type="text/javascript" src="js/menuDrop/jquery.naviDropDown.js"></script>
<script type="text/javascript" src="js/jquery.corner.js"></script>
<script type="text/javascript" src="js/js-webpage.js"></script>
<script src="js/formValidator/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/formValidator/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="js/InFieldLabels/jquery.infieldlabel.min.js" type="text/javascript" charset="utf-8"></script>