<!DOCTYPE HTML>
<html>
<head>

<title>Scroll Pagination</title>

<link rel="stylesheet" type="text/css" href="style.css" />

<script src="jquery.js"> </script>
<script src="javascript.js"> </script> 
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

<style type="text/css">

</style>
<script>

$(document).ready(function() {

	$('#content-item').scrollPagination({

		nop     : 5, // The number of posts per scroll to be loaded
		varItemCate   : 222,
		offset  : 0, // Initial offset, begins at 0 in this case
		error   : 'No More Posts!', // When the user reaches the end this is the message that is
		                            // displayed. You can change this if you want.
		delay   : 500, // When you scroll down the posts will load after a delayed amount of time.
		               // This is mainly for usability concerns. You can alter this as you see fit
		scroll  : true // The main bit, if set to false posts will not load as the user scrolls. 
		               // but will still load if the user clicks.
					   
					   
		
	});
	
});

</script>

</head>
<body>

<div id="travel">
	<a class="sub-head" href="http://www.inserthtml.com/2013/01/scroll-pagination/">&laquo; BACK TO ARTICLE</a>
</div>

<div id="content-item">
	
	

</div>

</body>
</html>