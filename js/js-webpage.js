// JavaScript Document

  //*************
  var keys = [37, 38, 39, 40];

  function preventDefault(e) {
	e = e || window.event;
	if (e.preventDefault) e.preventDefault();
	e.returnValue = false;  
  }

  function keydown(e) {
	  for (var i = keys.length; i--;) {
		  if (e.keyCode === keys[i]) {
			  preventDefault(e);
			  return;
		  }
	  }
  }

  function wheel(e) {
	preventDefault(e);
  }

  function disable_scroll() {
	if (window.addEventListener) {
		window.addEventListener('DOMMouseScroll', wheel, false);
	}
	window.onmousewheel = document.onmousewheel = wheel;
	document.onkeydown = keydown;
   jQuery("body").css({'overflow-y':'hidden'});
  }

  function enable_scroll() {
	  if (window.removeEventListener) {
		  window.removeEventListener('DOMMouseScroll', wheel, false);
	  }
	  window.onmousewheel = document.onmousewheel = document.onkeydown = null;  
	  jQuery("body").css({'overflow-y':'visible'});
  }
  //****************
	
jQuery(function() { 
	jQuery("#searchForm").validationEngine();
    jQuery("#searchForm div label").inFieldLabels();
	jQuery("#loading-page").show(100).delay(1400).fadeOut(600);
	jQuery('a.media').media();
	jQuery(".pinToBoard").fancybox({
	  'width'				: 400,
	  'hideOnOverlayClick': true,
	   'height'			: 480,
	   'padding'	: 0,
	  'autoScale'     	: false,
	  'transitionIn'		: 'none',
	  'transitionOut'		: 'none',
	  'type'				: 'iframe',	  
	  onStart: function(){
		disable_scroll();		
	  },		  
	  onClosed: function(){
		enable_scroll();		  
	  } 		
  });
  jQuery(".info").fancybox({
	  'width'				: 900,
	  'hideOnOverlayClick': true,
	   'height'			: '90%',
	   'padding'	: 0,
	  'autoScale'     	: false,
	  'transitionIn'		: 'none',
	  'transitionOut'		: 'none',	  
	  'type'				: 'iframe',	  
	  onStart: function(){
		disable_scroll();		
	  },		  
	  onClosed: function(){
		enable_scroll();		  
	  } 		
  });  
   jQuery(".forgetPawword").fancybox({
		'width'				: 400,
		'hideOnOverlayClick': true,
		 'height'			: 220,
		 'padding'	: 0,
		'autoScale'     	: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe',	  
	  onStart: function(){
		disable_scroll();		
	  },		  
	  onClosed: function(){
		enable_scroll();		  
	  } 		
	});  
   jQuery(".banner-page").click(function(){ 
	  //****************** 		 
	  var element = jQuery(this);		
	  var IdTabs = element.attr("id");  
	   jQuery.ajax({
		   type: "POST", url: "jQueryAjax/clickBanner.php",data: "banner_id="+IdTabs,cache: false 
		  });	/* */
	  });// end click		  
 jQuery('#navigation_horiz').naviDropDown({dropDownWidth: '160px'	}); 	
 jQuery(".navlink img,.displayProfile").corner("4px"); 
});