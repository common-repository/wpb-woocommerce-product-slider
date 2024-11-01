+(function($) {

	
	function wpb_wps_slider_init(){
		$(".wpb-woo-products-slider").each(function() {

		    var t = $(this),
		        auto 			= t.data("autoplay") ? !0 : !1,
		        hoverpause 		= t.data("hoverpause") ? !0 : !1,
		        rtl 			= t.data("direction") ? !0 : !1,
		        items 			= t.data("items") ? parseFloat(t.data("items")) : '',
		        desktopsmall 	= t.data("desktopsmall") ? parseFloat(t.data("desktopsmall")) : '',
		        tablet 			= t.data("tablet") ? parseFloat(t.data("tablet")) : '',
		        mobile 			= t.data("mobile") ? parseFloat(t.data("mobile")) : '',
		        nav 			= t.data("navigation") ? !0 : !1,
		        slideBy 		= t.data("slideby"),
		        pag 			= t.data("pagination") ? !0 : !1,
		        loop 			= t.data("sliderloop") ? !0 : !1,
		        navTextLeft 	= t.data("direction") ? 'right' : 'left',
		        navTextRight 	= t.data("direction") ? 'left' : 'right';
		        
		    $(this).owlCarousel({
		        autoplay: auto,
		        autoplayHoverPause: hoverpause,
		        rtl: rtl,
		        items : items,
		        responsiveClass:true,
			    responsive:{
			    	0:{
			            items: mobile,
			        },
			        480:{
			            items: mobile,
			        },
			        768:{
			            items: tablet,
			        },
			        1170:{
			            1024: desktopsmall,
			        },
			        1200:{
			            items: items,
			        }
			    },
	            nav: nav,
	            navText : ['<i class="wpb-wps-fa-angle-'+navTextLeft+'"></i>','<i class="wpb-wps-fa-angle-'+navTextRight+'"></i>'],
	            slideBy: slideBy,
	            dots: pag,
	            loop: loop,
	            margin: 10,
		    });
		});
	}

	$(document).ready(function(){
		wpb_wps_slider_init();
	});

	// refresh the slider on ajax complete
	$(document).ajaxComplete(function( event,xhr,options ){
		// $('.wpb-woo-products-slider').owlCarousel('destroy');
		// wpb_wps_slider_init();
	});

	// refresh the slider on WC cart page update
	$( document.body ).on( 'updated_cart_totals', function() { 
		$('.wpb-woo-products-slider').owlCarousel('destroy');
		wpb_wps_slider_init(); 
	});

})(jQuery);