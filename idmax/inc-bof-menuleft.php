<div class="content-bof-left">
  <div id="wrap">
    <div class="nav-page" style="color:#999">&nbsp;&nbsp; เมนูหลัก</div>
    
    <?php
            $qrycategory = "select category_id,category_name 
                            from $_Config_table[item_category]
							order by category_order";
            $rscategory = $DB->Execute($qrycategory);
           while( $list_category = $rscategory->FetchRow()){ 
		 ?>
    <h3 class="toggler"><a href="#<?php echo $list_category["category_name"]; ?>" onfocus="this.blur();"><strong><?php echo $list_category["category_name"]; ?></strong></a></h3>
    <div class="accordion">
      <ul>
      <?php   #qry_subCategory
		   $qry_subCategory = "select * from $_Config_table[item_subcategory] 
									where category_id = $list_category[category_id]";
			$rs_subCategory = $DB->Execute($qry_subCategory);
		    $numrow_menu = $rs_subCategory->RecordCount();
		    if($numrow_menu >0){
			while( $list_subCategory = $rs_subCategory->FetchRow()){ 
		?>
                <li><a href="item.php?category_id=<?php echo $list_category["category_id"]; ?>&sub_category_id=<?php echo $list_subCategory["sub_category_id"]; ?>#<?php echo $list_category["category_name"]; ?>" onfocus="this.blur();"> <img src="images/png/glyphicons_029_notes_2.png" alt="" width="15" height="20" /> <span>จัดการ <?php echo $list_subCategory["sub_category_name"]; ?></span></a> </li>   
        <?php }#end Have sub cate
		   }else{
		?>
          <li><a href="item.php?category_id=<?php echo $list_category["category_id"]; ?>#<?php echo $list_category["category_name"]; ?>" onfocus="this.blur();"> <img src="images/png/glyphicons_029_notes_2.png" alt="" width="15" height="20" /> <span>จัดการ <?php echo $list_category["category_name"]; ?></span></a> </li>
        <?php }#end Numrow?>
      </ul>
    </div>
   <?php	}#end category?> 
   <br/><br/>
     <h3 class="toggler"><a href="#member" onfocus="this.blur();"><strong>รายชื่อสมาชิก</strong></a></h3>
    <div class="accordion">
      <ul> 
           <li><a href="member.php#member" onfocus="this.blur();"> <img src="images/png/glyphicons_029_notes_2.png" alt="" width="15" height="20" /><span>รายชื่อสมาชิก</span></a> </li>
           <li><a href="enews_member.php#member" onfocus="this.blur();"> <img src="images/png/glyphicons_029_notes_2.png" alt="" width="15" height="20" /><span>รายชื่อสมาชิก Subscribe</span></a> </li> 
           
        
      </ul>
    </div>  
     <h3 class="toggler last"><a href="#news" onfocus="this.blur();"><strong>ปกหนังสือ / การสั่งซื้อ</strong></a></h3>
    <div class="accordion">
      <ul> 
        <li><a href="news.php#news" onFocus="this.blur();"><img src="images/png/glyphicons_263_bank.png" alt="" width="20" height="20" /><span>จัดการปกหนังสือ </span></a></li> 
         <li><a href="buynow.php#news" onFocus="this.blur();"><img src="images/png/glyphicons_263_bank.png" alt="" width="20" height="20" /><span>รายชื่อทำการสั่งซื้อ</span></a></li> 
        
      </ul>
    </div> 
     <h3 class="toggler last"><a href="#banner" onfocus="this.blur();"><strong>จัดการแบนเนอร์</strong></a></h3>
    <div class="accordion">
      <ul>
       <?php
            $qrycategory = "select category_id,category_name 
                            from $_Config_table[banner_category]
							order by category_order";
            $rscategory = $DB->Execute($qrycategory);
           while( $list_category = $rscategory->FetchRow()){ 
		 ?>
        <li><a href="banner.php?category_id=<?php echo $list_category["category_id"]; ?>#banner" onFocus="this.blur();"><img src="images/png/glyphicons_335_pushpin.png" alt="" width="15" /><span><?php echo $list_category["category_name"]; ?></span></a></li>
        <?php	}#end category?>     
      </ul>
    </div> 
    
    <? if($_SESSION['_GRPLEVEL'] == "Administrator") { ?>
    <h3 class="toggler last"><a href="#admin" onfocus="this.blur();"><strong>ผู้ดูแลระบบ</strong></a></h3>
    <div class="accordion">
      <ul>
        <li><a href="staff.php#admin" onFocus="this.blur();"><img src="accordion/images/icons/User Accounts.png" alt="" /><span>เจ้าหน้าที่ / STAFF</span></a></li>
      </ul>
    </div>
    <? } else {?>
    <h3 class="toggler"><strong>ปรับแต่ง | Site Building</strong><br />
      <span class="headSm">< ! Not Access ! ></span></h3>
    <h3 class="toggler last"><strong>Administration </strong><br />
      <span class="headSm">< ! Not Access ! ></span></h3>
    <? } ?>
  </div>
</div>
