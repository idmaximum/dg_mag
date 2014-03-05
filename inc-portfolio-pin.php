<div  id="profileTop">
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td width="15%"><? if($_SESSION['customerImg'] != ""){ ?>
           <a href="register_edit.php"><img src="uploads/member/<?php echo $_SESSION['customerImg'];?>" style="max-height:100px; max-width:100px  "/></a> 
           <?  }else if($_SESSION['fbID'] != ""){ ?>   
           <a href="register_edit.php" ><img src="https://graph.facebook.com/<?php echo $_SESSION['fbID']?>/picture?type=large"  style="max-height:100px; max-width:100px  "/></a> 
           <?php }else{?>
           <a href="register_edit.php" ><img src="images/personal.png"  style="max-height:100px; max-width:100px "/></a> 
           <?php }?></td>
         <td width="57%"><strong><span class="txtBlack14"> 
		 <?php if($_SESSION['NameDisplay'] != ""){?>
            <?php echo $_SESSION['NameDisplay']?>
            <?php }else{?>
            <?php echo $_SESSION['_EMAIL']?>
            <?php }?></span></strong><br>
          <a href="register_edit.php">Edit Profile</a></td>
          <?php
          $qrySelBoard = "select boardCategoryID_FK
                               from $_Config_table[pin_article]  
                              where memberID_FK = '$customerID'
                               GROUP BY boardCategoryID_FK";	
          $rsSelPicBoard = $DB->Execute($qrySelBoard);	
		  $numBoardRow = $rsSelPicBoard->RecordCount();		 
		 
		 $qrySelBoardPin = "select boardCategoryID_FK 
		                    from $_Config_table[pin_article]  
                             where memberID_FK = '$customerID'";
		 $rsSelPicBoardPin = $DB->Execute($qrySelBoardPin);	
		  $numBoardRowPin = $rsSelPicBoardPin->RecordCount();
		  
		  ?>
         <td width="13%" align="center" class="font-gentfont"><?php echo $numBoardRow?> Boards </td>
         <td width="2%" align="center"><img src="images/line-pin-board.png" width="9" height="43"></td>
         <td width="13%" align="center" class="font-gentfont"><?php echo $numBoardRowPin?> Pins</td>
        </tr>
     </table>
   </div>