/*

	BSCSlider - Slideshow jQuery Plugin
	Version : 1.1.1
	Author	: BS-Créa
	
*/

(function($) {
	$.bscSlider = function(settings, base){
		var el = $(base);
		el.mainWrap = $(base);
		el.items = el.find('img');
		el.default = {
				autoplay		: true,		// 
			 	navigation		: true,    // Display navigation arrows
				duration		: 6000,		// Length between transitions
				effect			: 1,		// 0-None, 1-Fade, 2-Slide Over to Top, 3-Slide Over to Right, 4-Slide Over to Bottom, 5-Slide Over to Left, 6-Slide to Right, 7-Slide to Left, 8-Slide to Top, 9-Slide to Bottom, 10-Slide Remove to Right, 11-Slide Remove to Left, 12-Slide Remove to Top, 13-Slide Remove to Bottom, 14-Parallax to Right, 15-Parallax to Left, 16-Parallax to Top, 17-Parallax to Bottom
 				effect_speed	: 750,		// Speed of transition
				easing			: 'swing',	//
				height			: 300,		// Height of the slider
		};
		el.settings = $.extend(
			{},
			el.default,
			settings
		);
		if ( isNaN( el.settings.height ) ) {
			el.settings.height = el.settings.height.replace(/\D/g, '');
		}
		el.mainWrap.css({ position: 'relative' });

		el.mainWrap.width = el.width();
		el.wrapInner('<div class="slider-wrapper"></div>');
		el.subWrap = el.find('.slider-wrapper');
		$(el.subWrap).height(el.settings.height);

		el.slidecount = el.items.length;
		if (el.settings.autoplay) {
			if (el.slidecount > 1) {
				el.settings.interval = setInterval(function() {
					el.nextSlide();
				}, el.settings.duration);
			}
		}
		el.items.last().prependTo(el.subWrap);

		el.items.each(function(i) {
			$(this).wrap('<div class="slider-item" style="' + (i!=0 ? 'opacity: 0;' : 'opacity: 1;') + '"><div style="background-image: url(\''+$(this).attr('src')+'\');""></div></div>');
			el.newelt = $(this).parent();
			$(this).remove();
		});
		if ( ( el.settings.navigation ) && (el.slidecount > 1) ) {
			el.append('<div class="slider-navigation" style="z-index: 12;"><span class="slider-prev"></span><span class="slider-next"></span></div>');
		}

		el.nextSlide = function(){
			el.debind();
			el.mainWrap.find('.slider-item').first().css('opacity', '0').appendTo(el.subWrap);
			el.slideCurr = el.mainWrap.find('.slider-item').first().css('z-index', 8);
			el.slideNext = el.mainWrap.find('.slider-item').eq(1).css('z-index', 10);
			clearInterval(el.settings.interval);

			el.animEffects(el.slideNext);
		}
		
		el.prevSlide = function() {
			el.debind();
			el.slideCurr = el.mainWrap.find('.slider-item').css('opacity', 0).eq(1).css('opacity', 1);
			el.slidePrev = el.mainWrap.find('.slider-item').first().css('z-index', 10);
			el.mainWrap.find('.slider-item').last().css({ 'opacity' : '0' }).prependTo(el.subWrap);
			clearInterval(el.settings.interval);

			el.animEffects(el.slidePrev, true);
		}

		el.afterAnim = function() {
			el.mainWrap.find('.slider-item').first().css('opacity', '0');
			el.slide.css({'z-index': ''});
			el.slideCurr.css({'z-index': '', 'opacity': '0'});
			if (el.settings.autoplay) {
				el.settings.interval = setInterval(function() {
					el.nextSlide();
				}, el.settings.duration);
			}
			$(base).on('click','.slider-prev', function(){
				el.prevSlide();
			});
			$(base).on('click','.slider-next', function(){
				el.nextSlide();
			});
		}
		el.debind = function() {
			$(base).off('click','.slider-prev');
			$(base).off('click','.slider-next');
		}
		$(base).on('click','.slider-prev', function(){
			el.prevSlide();
		});
		$(base).on('click','.slider-next', function(){
			el.nextSlide();
		});
		el.animEffects = function(slide, prev = false) {
			el.dir = (prev ? -1 : 1 );
			el.slide = slide;
			switch(el.settings.effect){
				case 0: case 'none':			// No transition
					el.slide.css('opacity', 1);
					el.afterAnim();
					//nextSlide();
					break;
				case 1: case 'fade':			// Fade
					el.slide.animate({ opacity: 1 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;

				// Slide Over
				case 2: case 'slideOverTop':		// Slide Over to Top
					el.slide.css('z-index', 10).animate({ opacity: 1, top: el.settings.height*el.dir }, 0).animate({ top: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;
				case 3: case 'slideOverRight':		// Slide Over to Right
					el.slide.css('z-index', 10).animate({ opacity: 1, left: -el.mainWrap.width*el.dir }, 0).animate({ left: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;
				case 4: case 'slideOverBottom':		// Slide Over to Bottom
					el.slide.css('z-index', 10).animate({ opacity: 1, top: -el.settings.height*el.dir }, 0).animate({ top: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;
				case 5: case 'slideOverLeft':		// Slide Over to Left
					el.slide.css('z-index', 10).animate({ opacity: 1, left: el.mainWrap.width*el.dir }, 0).animate({ left: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;

				// Simple Slide
				case 6: case 'slideRight':	// Slide to Right
					el.slideCurr.animate({ left: el.mainWrap.width*el.dir }, el.settings.effect_speed, el.settings.easing);
					el.slide.animate({ opacity: 1, left: -el.mainWrap.width*el.dir }, 0).animate({ left: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;
				case 7: case 'slideLeft':	// Slide to Left
					el.slideCurr.animate({ left: -el.mainWrap.width*el.dir }, el.settings.effect_speed, el.settings.easing);
					el.slide.animate({ opacity: 1, left: el.mainWrap.width*el.dir }, 0).animate({ left: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;
				case 8: case 'slideTop':	// Slide to Top
					el.slideCurr.animate({ top: -el.settings.height*el.dir }, el.settings.effect_speed, el.settings.easing);
					el.slide.animate({ opacity: 1, top: el.settings.height*el.dir }, 0).animate({ top: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;
				case 9: case 'slideBottom':	// Slide to Bottom
					el.slideCurr.animate({ top: el.settings.height*el.dir }, el.settings.effect_speed, el.settings.easing );
					el.slide.animate({ opacity: 1, top: -el.settings.height*el.dir }, 0).animate({ top: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;

				// Slide Remove
				case 10: case 'slideRemoveRight':	// Slide Remove to Right
					el.slide.css('z-index', 8).animate({ opacity: 1 }, 0, el.settings.easing );
					el.slideCurr.css('z-index', 10).animate({ left: el.mainWrap.width*el.dir }, el.settings.effect_speed, el.settings.easing, function(){ $(this).css({ 'z-index' : '', 'left' : '' }); el.afterAnim(); });
					break;
				case 11: case 'slideRemoveLeft':	// Slide Remove to Left
					el.slide.css('z-index', 8).animate({ opacity: 1 }, 0, el.settings.easing );
					el.slideCurr.css('z-index', 10).animate({ left: -el.mainWrap.width*el.dir }, el.settings.effect_speed, el.settings.easing, function(){ $(this).css({ 'z-index' : '', 'left' : '' }); el.afterAnim(); });
					break;
				case 12: case 'slideRemoveTop':	// Slide Remove to Top
					el.slide.css('z-index', 8).animate({ opacity: 1 }, 0, el.settings.easing );
					el.slideCurr.css('z-index', 10).animate({ top: -el.settings.height*el.dir }, el.settings.effect_speed, el.settings.easing, function(){ $(this).css({ 'z-index' : '', 'top' : '' }); el.afterAnim(); });
					break;
				case 13: case 'slideRemoveBottom':	// Slide Remove to Bottom
					el.slide.css('z-index', 8).animate({ opacity: 1 }, 0, el.settings.easing );
					el.slideCurr.css('z-index', 10).animate({ top: el.settings.height*el.dir }, el.settings.effect_speed, el.settings.easing, function(){ $(this).css({ 'z-index' : '', 'top' : '' }); el.afterAnim(); });
					break;

				// Parallax Slide
				case 14: case 'parallaxRight':	// Parallax to Right
					el.slideCurr.css('z-index', 8).animate({ left: el.mainWrap.width/3*el.dir }, el.settings.effect_speed, el.settings.easing);
					el.slide.css('z-index', 10).animate({ opacity: 1, left: -el.mainWrap.width*el.dir  }, 0).animate({ left: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;
				case 15: case 'parallaxLeft':	// Parallax to Left
					el.slideCurr.css('z-index', 8).animate({ left: -el.mainWrap.width/3*el.dir }, el.settings.effect_speed, el.settings.easing);
					el.slide.css('z-index', 10).animate({ opacity: 1, left: el.mainWrap.width*el.dir }, 0).animate({ left: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;
				case 16: case 'parallaxTop':	// Parallax to Top
					el.slideCurr.css('z-index', 8).animate({ top: -el.settings.height/3*el.dir }, el.settings.effect_speed, el.settings.easing);
					el.slide.css('z-index', 10).animate({ opacity: 1, top: el.settings.height*el.dir }, 0).animate({ top: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;
				case 17: case 'parallaxBottom':	// Parallax to Bottom
					el.slideCurr.css('z-index', 8).animate({ top: el.settings.height/3*el.dir }, el.settings.effect_speed, el.settings.easing);
					el.slide.css('z-index', 10).animate({ opacity: 1, top: -el.settings.height*el.dir }, 0).animate({ top: 0 }, el.settings.effect_speed, el.settings.easing, function(){ el.afterAnim(); });
					break;
				endif;
			}

		}
	
	};
	 $.fn.bscSlider = function(options){
        return this.each(function(){
            (new $.bscSlider(options, this));
        });
    };
})(jQuery);
