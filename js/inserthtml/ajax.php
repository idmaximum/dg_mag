<?php

mysql_connect('localhost', 'root', '12345') or die();
mysql_select_db('dg_magazine');

$offset = is_numeric($_POST['offset']) ? $_POST['offset'] : die();
$postnumbers = is_numeric($_POST['number']) ? $_POST['number'] : die();
	echo 	$_POST['itemCate'];



$run = mysql_query("SELECT * FROM item  
					  LIMIT ".$postnumbers." OFFSET ".$offset);


while($row = mysql_fetch_array($run)) {
	
	$content = substr(strip_tags($row['item_abstract']), 0, 500);
	
	echo '<h1><a href="'.$row['guid'].'">'.$row['item_title'].'</a></h1><hr />';
	echo '<p>'.$content.'...</p><hr />';

}

?>