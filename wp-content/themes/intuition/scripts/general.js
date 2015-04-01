//SLIDESHOW
jQuery(document).ready(function(){
	
	//HOMEPAGE SLIDES
	jQuery('.slider-slides').cycle({
        speed: 1000,
		timeout: 8000,
		fx: 'scrollHorz',
		next: '.slider-next', 
    	prev: '.slider-prev',
		pager: '.slider-pages',
		pause: true,
		pauseOnPagerHover: true,
		containerResize: false,
		slideResize: false,
		fit: 1
    });
	
	jQuery('.slider-prev, .slider-next').click(function(){
		jQuery('.slider-slides').cycle('pause');
	});
	
	jQuery(window).scroll(function(){
		if(jQuery(document).scrollTop() > 500)
			jQuery('#toplink').addClass('active');
		else
			jQuery('#toplink').removeClass('active');
	});
	
});

function slide_resize(curr, next, opts, fwd) {
	var ht = jQuery(this).height();
	jQuery(this).parent().animate({height: ht});
}