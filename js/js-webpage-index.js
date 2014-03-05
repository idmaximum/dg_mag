// JavaScript Document
jQuery(document).ready(function(){
   jQuery('.bxslider').bxSlider({
	auto: true ,controls : false,pause : 10000
  });
  jQuery('.bxslider-item').bxSlider({
	  auto: true , controls : false, minSlides: 2,maxSlides: 3,slideWidth: 220,slideMargin: 20, pause :10000,autoHover: true
	});
	// mousse over issue  
	  jQuery('.issue-content').hover(function(){
		  jQuery('.issue-icontent').fadeIn(500);
	    },
      function(){
		  jQuery('.issue-icontent').fadeOut(500);
	  });
	  
	   jQuery(".buynow").fancybox({
		'width'				: 600,
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
	
	 jQuery(".subscribe").fancybox({
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
	
	
});