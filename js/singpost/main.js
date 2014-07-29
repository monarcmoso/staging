var js = $.noConflict();
js(document).ready(function(){
	js(document).on('click','.scrollup',function(){
	  js("html, body").animate({ scrollTop: 0 }, "slow");
	  return false;
	});
	js(window).scroll(function(){
		if(js(this).scrollTop() > 100) {
		    js('.scrollup').fadeIn();
		} else {
			js('.scrollup').fadeOut();
		}
	}); 
	//faq toggle answers
	 js('#faq h2').click(function() {            
	    js(this).next('.answer').slideToggle(500);
	    js(this).toggleClass('close2');
	                
	  });
	
	//tabs
	js('#tab-wrapper').easyTabs({defaultContent:1});
	js('#tab-wrapper2').easyTabs({defaultContent:1});
	
	//custom upload file button
	js(":file").jfilestyle({input: false});
	
        
       //monarc modal
       //js('#modal1').on('click', function ( e ) {
       js(document).on('click','.btn',function(){
	    js.fn.custombox( this, {
	        effect:         'blur',
	        position:       'center',
	        customClass:    'customslide',
	        speed:          200
	    });
	    e.preventDefault();
	});
        
	//modalbox
//	js('#modal1').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#modal2').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#modal3').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#modal4').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#modal5').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#modal6').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#modal7').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#modal8').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#edit1').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#edit2').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#edit3').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#edit4').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#edit5').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#edit6').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
//	js('#edit7').on('click', function ( e ) {
//	    js.fn.custombox( this, {
//	        effect:         'blur',
//	        position:       'center',
//	        customClass:    'customslide',
//	        speed:          200
//	    });
//	    e.preventDefault();
//	});
	
	//sticky ad
	function fixDiv() {
	  var jscache = js('#sticker'); 
	  if (js(window).scrollTop() > 410) 
	    jscache.css({'position': 'fixed', 'top': '0px','left': '0px', 'background-color':'#fff', 'z-index': '9000'}); 
	  else
	    jscache.css({'position': 'relative', 'top': 'auto'});
	}
	js(window).scroll(fixDiv);
	fixDiv();
	
	//dropdown menu effect
	 js( '.dropdown' ).hover(
		function(){
	    	js(this).children('.sub-menu').slideDown(120);
	    },
	    function(){
	    	js(this).children('.sub-menu').slideUp(120);
	    }
	 );
	
	 js( '.logged-in' ).hover(
		function(){
	    	js(this).children('.sub-menu').slideDown(120);
	    },
	    function(){
	    	js(this).children('.sub-menu').slideUp(120);
	    }
	 );
	 
	 
	 //index more/less button
	 js('#category-toggle').on('click', function(e){
		e.stopPropagation();e.preventDefault();
		  if(!js("#category-container").hasClass("expanded")){
			js("#category-container").addClass("expanded");
			js(this).addClass("expanded");
		  } else {
			js("#category-container").removeClass("expanded");
			js(this).removeClass("expanded");
		  }
	 });
	 
	 js('#home').on('click', function ( e ) {
	    js.fn.custombox( this, {
	        effect:         'fade',
	        position:       'center',
	        customClass:    'customslide',
	        speed:          200,
	        overlayClose:	false
	    });
	    e.preventDefault();
	});
	
	js("#home").trigger('click');
	js('.banner').unslider({
		fluid: false,
		dots: true,
		speed: 500,
		slideTransitionEffect: 'fade'
	});
	
}); // end of document ready