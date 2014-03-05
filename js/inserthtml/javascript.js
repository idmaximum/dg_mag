(function($) {

	$.fn.scrollPagination = function(options) {
		
		var settings = { 
			nop     : 10, // The number of posts per scroll to be loaded
		 
			offset  : 0, // Initial offset, begins at 0 in this case
			error   : 'No More Posts!', // When the user reaches the end this is the message that is
			                            // displayed. You can change this if you want.
			delay   : 500, // When you scroll down the posts will load after a delayed amount of time.
			               // This is mainly for usability concerns. You can alter this as you see fit
			scroll  : true // The main bit, if set to false posts will not load as the user scrolls. 
			               // but will still load if the user clicks.
		}
		
		// Extend the options so they work with the plugin
		if(options) {
			$.extend(settings, options);
		}
		
		// For each so that we keep chainability.
		return this.each(function() {		
			
			// Some variables 
			$this = $(this);
			$settings = settings;
			var offset = $settings.offset;
			var varItemCate = $settings.varItemCate; 
			var varItemSubCate = $settings.varItemSubCate; 
			var busy = false; // Checks if the scroll action is happening 
			                  // so we don't run it multiple times
			
			// Custom messages based on settings
			if($settings.scroll == true) $initmessage = 'Scroll for more or click here';
			else $initmessage = 'Click for more';
			
			// Append custom messages and extra UI
			$this.append('<div class="content"></div><div class="loading-bar">'+$initmessage+'</div>');
			
			function getData() {
				
				// Post data to ajax.php
				$.post('jQueryAjax/ajax.php', {
						
					action        : 'scrollpagination',
				    number        : $settings.nop,
				    offset        : offset,
					itemCate        : varItemCate,
					itemSubCate        : varItemSubCate,
					    
				}, function(data) {
					
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
						
					// Change loading bar content (it may have been altered)
					$this.find('.loading-bar').html($initmessage);
					//********
						jQuery(".pinToBoard").fancybox({
						  'width'				: 400,
						  'hideOnOverlayClick': true,
						   'height'			: 460,
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
						//********
						
					// If there is no data returned, there are no more posts to be shown. Show error
					if(data == "") { 
						$this.find('.loading-bar').html($settings.error);
							
					}
					else {
						
						// Offset increases
					    offset = offset+$settings.nop; 
						    
						// Append the data to the content div
					   	$this.find('.content').append(data);
						
						// No longer busy!	
						busy = false;
					}	
						
				});
					
			}	
			
			getData(); // Run function initially
			
			// If scrolling is enabled
			if($settings.scroll == true) {
				// .. and the user is scrolling
				$(window).scroll(function() {
					
					// Check the user is at the bottom of the element
					if($(window).scrollTop() + $(window).height() > $this.height() && !busy) {
						
						// Now we are working, so busy is true
						busy = true;
						
						// Tell the user we're loading posts
						$this.find('.loading-bar').html('<img src="images/loading_2.GIF" width="160" height="20">');
						 
						
						// Run the function to fetch the data inside a delay
						// This is useful if you have content in a footer you
						// want the user to see.
						setTimeout(function() {
							
							getData();
							
						}, $settings.delay);
							
					}	
				});
			}
			
			// Also content can be loaded by clicking the loading bar/
			$this.find('.loading-bar').click(function() {
			
				if(busy == false) {
					busy = true;
					getData();
				}
			
			});
			
		});
	}

})(jQuery);
