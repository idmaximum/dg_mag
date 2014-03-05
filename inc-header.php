<header id="header">
  <?php
   function curPageName() {
		 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		}
	$chkBrowserFn = 	FU::chkBrowser(); 
	?> 
  <div class="site-content" id="header-content">
    <div id="logo-website"><a href="index.php"><img src="images/logo.gif" alt="logo Dg Magazine" width="103" height="66" class="hoverImg08"/></a></div> 
    <div id="top-menu">
      <ul>
        <li <?php if(curPageName()=="index.php"){?>class="select"<?php }?>><a href="index.php">Home</a></li>
        <li <?php if(curPageName()=="decoration.php"){?>class="select"<?php }?>><a href="decoration.php">Decoration</a></li>
        <li <?php if(curPageName()=="kitchen.php"){?>class="select"<?php }?>><a href="kitchen.php">Kitchen</a></li>
        <li <?php if(curPageName()=="shopping.php"){?>class="select"<?php }?>><a href="shopping.php">Shopping</a></li>
        <li <?php if(curPageName()=="howto.php"){?>class="select"<?php }?>><a href="howto.php">How To</a></li>
        <li <?php if(curPageName()=="item.php"){?>class="select"<?php }?>><a href="item.php?item_category_id=4">Like &amp; Share</a></li>
      </ul>      
    </div>
     <form action="search.php" method="post" id="searchForm">
      <div id="top-search">         
       	<label for="keyword-search" id="labelSearch">Search</label>
        <input name="keyword" type="text" class="form-search txtBrown12_2" id="keyword-search" >        
      </div>
      <div id="btn-search">
        <input name="url" type="hidden" value="" class="url" />
        <input type="image" src="images/btn-search.gif" alt="submit" value="submit" id="btn-search-keyword">
      </div>
</form>
    <?php if($_SESSION['_CUTOMERID']=="" && $_SESSION['_USERNAME']==""){ ?>  
   <div id="top-register" class="txtpink13 font-gentfont"><a href="register.php"><strong>Register</strong></a> &nbsp; <span style="color:#CCC">or</span>  &nbsp;  <a href="login.php"><strong>Log in by email</strong></a></div>
    <div id="top-login-facebook"><a href="#" onclick="ShowFaceookLogin();"><img src="images/btn-fb.jpg" alt="btn-fb" width="203" height="37" class="hoverImg09"></a></div>  
     <?php }else { ?>  
      <div id="newPost" class="txtBrown14"><a href="post_board.php"><strong>New Post</strong></a></div>
      <div id="div-after-login">
      	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center" class="txtBrown14" style="padding-top:10px"><strong>
           <?php if($_SESSION['NameDisplay'] != ""){?>
            <?php echo $_SESSION['NameDisplay']?>
            <?php }else{?>
            <?php echo $_SESSION['_EMAIL']?>
            <?php }?>
            </strong></td>
            <td>
             <div id="navigation_horiz">
              <ul>
               <li> 
                   <a href="profileYourPin.php" class="displayProfile"></a>  
                  <div class="dropdown" id="dropdown_one"> 
                    <div style="height:14px; width:160px; display:block"><img src="images/sorn.png" alt="sorn" width="160" height="6" /></div>
					<p><a href="profileYourPin.php" title="Your Board">Your Board</a></p>
                    <p><a href="register_edit.php">Edit Profile</a></p>
                    <p><a href="logout.php">Logout</a></p> 
                  </div> 
              </li> 
                </ul>
             </div>  
            </td>
          </tr>
        </table> 
      </div>
      <?php }?>
  </div>
</header>