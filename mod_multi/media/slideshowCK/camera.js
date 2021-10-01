// Camera slideshow v1.4.9 - a jQuery slideshow with many effects, transitions, easy to customize, using canvas and mobile ready, based on jQuery 1.4+
// Copyright (c) 2012 by Manuel Masia - www.pixedelic.com
// Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
// Updated by Cédric KEIFLIN - https://www.joomlack.fr - https://www.ceikay.com

// v1.4.12	- 13/06/19 : fix issue on thumbs container width
// v1.4.11	- 12/06/19 : add aria-label on anchor links
// v1.4.10	- 11/06/19 : update for the V2 of slideshow CK, modify the margin bottom on thumbs
//						, remove the effect on the caption is no effect is selected
// v1.4.9	- 04/11/18 : check if the html code exists in the page
// v1.4.8	- 02/05/18 : fix issue with fullpage background and image dimensions
// v1.4.7	- 12/03/18 : add option for keyboard controls, arrows left - right and P
// v1.4.6	- 09/01/18 : fix issue with image alignment option when using fullpage option
// v1.4.5	- 08/01/18 : add accessibility with tabuliation and enter keypress : Thanks to Adam !
// v1.4.4	- 14/09/17 : fix issue when the instance is called multiple times on the same slideshow
// v1.4.3	- 23/06/17 : remove the time stamp in image url (again)
// v1.4.2	- 03/05/17 : add the alt tag on images
// v1.4.1	- 22/02/17 : improve the load as container background feature to work in the same way as full page background
// v1.4.0	- 16/02/17 : separate the slideshow instance from the jQuery instance. Load it as object and not as jquery plugin
// v1.3.18 - 22/11/16 : add the event cameraupdate
// v1.3.17 - 08/11/16 : improve the transition in full page background mode
// v1.3.16 - 06/10/16 : remove the time stamp in image url
// v1.3.15 - 03/10/16 : improve the full background ratios and display again
// v1.3.14 - 28/03/16 : improve the full background ratios and display
// v1.3.13 - 04/03/16 : remove dependency to jquery mobile, add custom touch event handler
// v1.3.12 - 28/09/15 : added KenBurns effect
// v1.3.11 - 18/06/15 : fix an issue with the squeezebox if not loaded
// v1.3.10 - 10/02/15 : fix an issue with the thumb container width
// v1.3.9 - 22/12/14 : added option to load the slideshow as background of any container
// v1.3.8 - 03/07/13 : added option to load specific images depending on the resolution of the screen
// v1.3.7 : improved the loading time of the first image and the slideshow height at start, fix an issue with the lightbox
// v1.3.6 : added lightbox support
// v1.3.5 : update for jQuery1.9
// from cedric keiflin alias ced1870
;(function($){

var Slideshowck = function (container, opts, callback) {
// $.fn.camera = function(opts, callback) {

	var defaults = {
		alignment			: 'center', //topLeft, topCenter, topRight, centerLeft, center, centerRight, bottomLeft, bottomCenter, bottomRight

		autoAdvance			: true,	//true, false

		mobileAutoAdvance	: true, //true, false. Auto-advancing for mobile devices

		barDirection		: 'leftToRight',	//'leftToRight', 'rightToLeft', 'topToBottom', 'bottomToTop'

		barPosition			: 'bottom',	//'bottom', 'left', 'top', 'right'

		cols				: 6,

		easing				: 'easeInOutExpo',	//for the complete list http://jqueryui.com/demos/effect/easing.html

		mobileEasing		: '',	//leave empty if you want to display the same easing on mobile devices and on desktop etc.

		fx					: 'random',	//'random','simpleFade', 'curtainTopLeft', 'curtainTopRight', 'curtainBottomLeft', 'curtainBottomRight', 'curtainSliceLeft', 'curtainSliceRight', 'blindCurtainTopLeft', 'blindCurtainTopRight', 'blindCurtainBottomLeft', 'blindCurtainBottomRight', 'blindCurtainSliceBottom', 'blindCurtainSliceTop', 'stampede', 'mosaic', 'mosaicReverse', 'mosaicRandom', 'mosaicSpiral', 'mosaicSpiralReverse', 'topLeftBottomRight', 'bottomRightTopLeft', 'bottomLeftTopRight', 'bottomLeftTopRight', 'kenburns'
										//you can also use more than one effect, just separate them with commas: 'simpleFade, scrollRight, scrollBottom'

		mobileFx			: '',	//leave empty if you want to display the same effect on mobile devices and on desktop etc.

		gridDifference		: 250,	//to make the grid blocks slower than the slices, this value must be smaller than transPeriod

		height				: '50%',	//here you can type pixels (for instance '300px'), a percentage (relative to the width of the slideshow, for instance '50%') or 'auto'

		imagePath			: 'images/',	//he path to the image folder (it serves for the blank.gif, when you want to display videos)

		hover				: true,	//true, false. Puase on state hover. Not available for mobile devices

		loader				: 'pie',	//pie, bar, none (even if you choose "pie", old browsers like IE8- can't display it... they will display always a loading bar)

		loaderColor			: '#eeeeee',

		loaderBgColor		: '#222222',

		loaderOpacity		: .8,	//0, .1, .2, .3, .4, .5, .6, .7, .8, .9, 1

		loaderPadding		: 2,	//how many empty pixels you want to display between the loader and its background

		loaderStroke		: 7,	//the thickness both of the pie loader and of the bar loader. Remember: for the pie, the loader thickness must be less than a half of the pie diameter

		minHeight			: '200px',	//you can also leave it blank

		navigation			: true,	//true or false, to display or not the navigation buttons

		navigationHover		: true,	//if true the navigation button (prev, next and play/stop buttons) will be visible on hover state only, if false they will be visible always

		mobileNavHover		: true,	//same as above, but only for mobile devices

		opacityOnGrid		: false,	//true, false. Decide to apply a fade effect to blocks and slices: if your slideshow is fullscreen or simply big, I recommend to set it false to have a smoother effect

		overlayer			: true,	//a layer on the images to prevent the users grab them simply by clicking the right button of their mouse (.camera_overlayer)

		pagination			: true,

		playPause			: false,	//true or false, to display or not the play/pause buttons

		pauseOnClick		: true,	//true, false. It stops the slideshow when you click the sliders.

		pieDiameter			: 38,

		piePosition			: 'rightTop',	//'rightTop', 'leftTop', 'leftBottom', 'rightBottom'

		portrait			: false, //true, false. Select true if you don't want that your images are cropped

		rows				: 4,

		slicedCols			: 12,	//if 0 the same value of cols

		slicedRows			: 8,	//if 0 the same value of rows

		slideOn				: 'random',	//next, prev, random: decide if the transition effect will be applied to the current (prev) or the next slide

		thumbnails			: false,
		
		thumbheight			: '100',
		
		thumbwidth			: '75',

		time				: 7000,	//milliseconds between the end of the sliding effect and the start of the nex one

		transPeriod			: 1500,	//lenght of the sliding effect in milliseconds

		fullpage			: false,

		lightbox			: 'none',

		mobileimageresolution: 0,

		container: '',

		responsiveCaption: false,

		keyboardNavigation: false,

////////callbacks

		onEndTransition		: function() {  },	//this callback is invoked when the transition effect ends

		onLoaded			: function() {  },	//this callback is invoked when the image on a slide has completely loaded

		onStartLoading		: function() {  },	//this callback is invoked when the image on a slide start loading

		onStartTransition	: function() {  }	//this callback is invoked when the transition effect starts

	};

	// check if the slideshow does not exists in the page
	if (! $(container).length) return;

	if (!(this instanceof Slideshowck)) return new Slideshowck(container, opts, callback);
	var slideshowcks = window.slideshowcks || [];
	if (slideshowcks.indexOf(container) > -1) return;
	slideshowcks.push(container);
	window.slideshowcks = slideshowcks;


	function isMobile() {
		if( navigator.userAgent.match(/Android/i) ||
			navigator.userAgent.match(/webOS/i) ||
			navigator.userAgent.match(/iPad/i) ||
			navigator.userAgent.match(/iPhone/i) ||
			navigator.userAgent.match(/iPod/i)
			){
				return true;
		}
	}

	var opts = $.extend({}, defaults, opts);

	var wrap = $(container).addClass('camera_wrap');

		if (opts.fullpage == true) {
			$(document.body).css('background','none').prepend(wrap);
			wrap.css({
				'height' : '100%',
				'margin-left' : 0,
				'margin-right' : 0,
				'margin-top' : 0,
				'position' : 'fixed',
				'visibility' : 'visible',
				'left' : 0,
				'right' : 0,
				'top' : 0,
				'min-width': '100%',
				'min-height' : '100%',
				'width' : '100%',
				'z-index' : '-1'
			});
		} else if (opts.container != false) {
			$(opts.container).css('background','none').prepend('<div class="slideshowck_container_wrap"></div>'); //v1.3.9
			$('.slideshowck_container_wrap', opts.container).prepend(wrap);
			if ($(opts.container).css('position') != 'relative' && $(opts.container).css('position') != 'absolute') {
				$(opts.container).css('position', 'relative').css('z-index', '0');
			}
			wrap.parent('.slideshowck_container_wrap').css({
				'height' : '100%',
				'margin-left' : 0,
				'margin-right' : 0,
				'margin-top' : 0,
				'position' : 'absolute',
				'visibility' : 'visible',
				'left' : 0,
				'right' : 0,
				'min-width': '100%',
				'min-height' : '100%',
				'width' : '100%',
				'overflow' : 'hidden',
				'z-index' : '-1'
			});
		}

	wrap.wrapInner(
        '<div class="camera_src" />'
		).wrapInner(
	    '<div class="camera_fakehover" />'
		);

	var fakeHover = $('.camera_fakehover',wrap);

	fakeHover.append(
		'<div class="camera_target"></div>'
		);
	if(opts.overlayer == true){
		fakeHover.append(
			'<div class="camera_overlayer"></div>'
			)
	}
		fakeHover.append(
        '<div class="camera_target_content"></div>'
		);

	var loader;

        if ((navigator.userAgent.match(/MSIE 8.0/i)
            || navigator.userAgent.match(/MSIE 7.0/i)
            || navigator.userAgent.match(/MSIE 6.0/i))
			&& opts.loader != 'none'){
//	if(opts.loader=='pie' && $.browser.msie && $.browser.version < 9){
		loader = 'bar';
	} else {
		loader = opts.loader;
	}

	if(loader == 'pie'){
		fakeHover.append(
			'<div class="camera_pie"></div>'
			)
	} else if (loader == 'bar') {
		fakeHover.append(
			'<div class="camera_bar"></div>'
			)
	} else {
		fakeHover.append(
			'<div class="camera_bar" style="display:none"></div>'
			)
	}

	if(opts.playPause==true){
		fakeHover.append(
        '<div class="camera_commands"></div>'
		)
	}

	if(opts.navigation==true){
		fakeHover.append(
			'<div class="camera_prev" tabindex="0"><span></span></div>'
			).append(
			'<div class="camera_next" tabindex="0"><span></span></div>'
			);
	}

	if(opts.thumbnails==true){
		wrap.append(
			'<div class="camera_thumbs_cont" />'
			);
	}

	if(opts.thumbnails==true && opts.pagination!=true){
		$('.camera_thumbs_cont',wrap).wrap(
			'<div />'
			).wrap(
				'<div class="camera_thumbs" />'
			).wrap(
				'<div />'
			).wrap(
				'<div class="camera_command_wrap" />'
			);
	}

	if(opts.pagination==true){
		wrap.append(
			'<div class="camera_pag"></div>'
			);
	}

	wrap.append(
		'<div class="camera_loader"></div>'
		);

	$('.camera_caption',wrap).each(function(){
		$(this).wrapInner('<div />');
	});


	var pieID = 'pie_'+wrap.attr('id'), // modif ced1870 version e
		elem = $('.camera_src',wrap),
		target = $('.camera_target',wrap),
		content = $('.camera_target_content',wrap),
		pieContainer = $('.camera_pie',wrap),
		barContainer = $('.camera_bar',wrap),
		prevNav = $('.camera_prev',wrap),
		nextNav = $('.camera_next',wrap),
		commands = $('.camera_commands',wrap),
		pagination = $('.camera_pag',wrap),
		thumbs = $('.camera_thumbs_cont',wrap);


	var w,
		h;

	var screenwidth = parseInt($(document.body).width());
	// var imageprefix = '';
	// if (opts.mobileimageresolution && screenwidth <= opts.mobileimageresolution) imageprefix = opts.mobileimageresolution + '_';

	imgresolution = 0;
	if (opts.mobileimageresolution) {
		var resolutions = opts.mobileimageresolution.split(',');
		for (i=0; i < resolutions.length ; i++) {
			if (screenwidth <= resolutions[i] && 
				( (imgresolution != 0 && resolutions[i] <= imgresolution)
				|| (imgresolution == 0 && screenwidth < Math.max.apply( Math, resolutions )) )
				) {
				imgresolution = resolutions[i];
			}
		}
	}

	var allImg = new Array();
	var imgsrc;
	$('> div', elem).each( function() {
		imgsrc = $(this).attr('data-src');
		
		if (imgresolution) {
			imgsrctmp = imgsrc.split('\/');
			imgnametmp = imgsrctmp[imgsrctmp.length - 1];
			imgsrctmp[imgsrctmp.length - 1] = imgresolution + '/' + imgnametmp;
			imgsrc = imgsrctmp.join('\/');
		}
		allImg.push(imgsrc);
	});

//        newImg = new Image(); // test pour afficher image directement au chargement de la page, mais manque dimensions
//        newImg.src = allImg[0];
//        wrap.append(newImg);

	var allLinks = new Array();
	var allRels = new Array();
	var allTitles = new Array();
	var allAlts = new Array();
	$('> div', elem).each( function() {
		if($(this).attr('data-link')){
			allLinks.push($(this).attr('data-link'));
		} else {
			allLinks.push('');
		}
		if($(this).attr('data-rel')){
			allRels.push('rel="'+$(this).attr('data-rel')+'" ');
		} else {
			allRels.push('');
		}
		if($(this).attr('data-title')){
			allTitles.push('title="'+$(this).attr('data-title')+'" ');
		} else {
			allTitles.push('');
		}
		if($(this).attr('data-alt')){
			allAlts.push($(this).attr('data-alt'));
		} else {
			allAlts.push('');
		}
	});

	var allTargets = new Array();
	$('> div', elem).each( function() {
		if($(this).attr('data-target')){
			allTargets.push($(this).attr('data-target'));
		} else {
			allTargets.push('');
		}
	});

	var allPor = new Array();
	$('> div', elem).each( function() {
		if($(this).attr('data-portrait')){
			allPor.push($(this).attr('data-portrait'));
		} else {
			allPor.push('');
		}
	});

	var allAlign= new Array();
	$('> div', elem).each( function() {
		if($(this).attr('data-alignment')){
			allAlign.push($(this).attr('data-alignment'));
		} else {
			allAlign.push('');
		}
	});


	var allThumbs = new Array();
	$('> div', elem).each( function() {
		if($(this).attr('data-thumb')){
			allThumbs.push($(this).attr('data-thumb'));
		} else {
			allThumbs.push('');
		}
	});

	var amountSlide = allImg.length;

	$(content).append('<div class="cameraContents" />');
	var loopMove;
	for (loopMove=0;loopMove<amountSlide;loopMove++)
	{
		$('.cameraContents',content).append('<div class="cameraContent" />');
		if(allLinks[loopMove]!=''){
			//only for Wordpress plugin
			var dataBox = $('> div ',elem).eq(loopMove).attr('data-box');
			if(typeof dataBox !== 'undefined' && dataBox !== false && dataBox != '') {
				dataBox = 'data-box="'+$('> div ',elem).eq(loopMove).attr('data-box')+'"';
			} else {
				dataBox = '';
			}
			$('.camera_target_content .cameraContent:eq('+loopMove+')',wrap).append('<a class="camera_link" aria-label="Link for : ' + allAlts[loopMove] + '" '+allRels[loopMove]+allTitles[loopMove]+'href="'+allLinks[loopMove]+'" '+dataBox+' target="'+allTargets[loopMove]+'"></a>');
		}

	}
	var allAltText = new Array();
	$('.camera_caption',wrap).each(function(){
		var ind = $(this).parent().index(),
			cont = wrap.find('.cameraContent').eq(ind);
		var title = $(this).find('.camera_caption_title').text().trim();
		var desc = $(this).find('.camera_caption_desc').text().trim();
		var altText = title.length ? title : (desc ? desc : allImg[ind]);
		allAltText.push(altText);
		$(this).appendTo(cont);
	});

	target.append('<div class="cameraCont" />');
	var cameraCont = $('.cameraCont',wrap);



	var loop;
	for (loop=0;loop<amountSlide;loop++)
	{
		cameraCont.append('<div class="cameraSlide cameraSlide_'+loop+'" />');
		var div = $('> div:eq('+loop+')',elem);
		target.find('.cameraSlide_'+loop).clone(div);
	}


	function thumbnailVisible() {
		calculateThumbsWidth();
		var wTh = $(thumbs).width();
		$('li', thumbs).removeClass('camera_visThumb');
		$('li', thumbs).each(function(){
			var pos = $(this).position(),
				ulW = $('ul', thumbs).outerWidth(),
				offUl = $('ul', thumbs).offset().left,
				offDiv = $('> div',thumbs).offset().left,
				ulLeft = offDiv-offUl;
//				if(ulLeft>0){
					$('.camera_prevThumbs',camera_thumbs_wrap).removeClass('hideNav').css('visibility','visible');
//				} else {
//					$('.camera_prevThumbs',camera_thumbs_wrap).addClass('hideNav');
//				}
//				if((ulW-ulLeft)>wTh){
					$('.camera_nextThumbs',camera_thumbs_wrap).removeClass('hideNav').css('visibility','visible');
//				} else {
//					$('.camera_nextThumbs',camera_thumbs_wrap).addClass('hideNav');
//				}
				var left = pos.left,
					right = pos.left+($(this).width());
				if(right-ulLeft<=wTh && left-ulLeft>=0){
					$(this).addClass('camera_visThumb');
				}
		});
	}

	$(window).bind('load resize pageshow',function(){
		thumbnailPos();
		thumbnailVisible();
		if (opts.responsiveCaption) resizeFont();
	});

	// trigger to update the slideshow width
	$(window).bind('cameraupdate',function(){
		calculateThumbsWidth();
		$(window).trigger('resize');
	});

	function calculateThumbsWidth() {
		ulthumbwidth = 0;
		$.each(allThumbs, function(i, val) {
				ulthumbwidth += parseFloat($('li.pix_thumb_'+i,thumbs).outerWidth()) + parseFloat($('li.pix_thumb_'+i,thumbs).css('marginLeft')) + parseFloat($('li.pix_thumb_'+i,thumbs).css('marginRight'));
		});
		$('ul', thumbs).width(ulthumbwidth);
	}
	cameraCont.append('<div class="cameraSlide cameraSlide_'+loop+'" />');


	var started;

	wrap.show();
	var w = target.width();
	var h = target.height();

	var setPause;

	$(window).bind('resize pageshow',function(){
		if(started == true) {
			resizeImage();
			// addLightbox();
		}
		$('ul', thumbs).animate({'margin-top':0},0,thumbnailPos);
		if(!elem.hasClass('paused')){
			elem.addClass('paused');
			if($('.camera_stop',camera_thumbs_wrap).length){
				$('.camera_stop',camera_thumbs_wrap).hide()
				$('.camera_play',camera_thumbs_wrap).show();
				if(loader!='none'){
					$('#'+pieID).hide();
				}
			} else {
				if(loader!='none'){
					$('#'+pieID).hide();
				}
			}
			clearTimeout(setPause);
			setPause = setTimeout(function(){
				elem.removeClass('paused');
				if($('.camera_play',camera_thumbs_wrap).length){
					$('.camera_play',camera_thumbs_wrap).hide();
					$('.camera_stop',camera_thumbs_wrap).show();
					if(loader!='none'){
						$('#'+pieID).fadeIn();
					}
				} else {
					if(loader!='none'){
						$('#'+pieID).fadeIn();
					}
				}
			},1500);
		}
	});

	function resizeFont() {
		var fontRatio = wrap.width() / 700;
		$('.camera_caption > div').css('font-size', fontRatio + 'em');
	}

	function addLightbox() {
		if (opts.lightbox == 'mediaboxck' && typeof(Mediabox) != "undefined") {
			Mediabox.scanPage();
		} else if (opts.lightbox == 'squeezebox' && typeof(SqueezeBox) != "undefined") {
			SqueezeBox.initialize({});
			SqueezeBox.assign($$('a.camera_link[rel=lightbox]'), {
				/*parse: 'rel'*/
			});
		}
	}
	
	setWrappersize();
	
	function setWrappersize() {
		w = wrap.width();
		if(opts.height.indexOf('%')!=-1) {
			var startH = Math.round(w / (100/parseFloat(opts.height)));
			if(opts.minHeight != '' && startH < parseFloat(opts.minHeight)){
				h = parseFloat(opts.minHeight);
			} else {
				h = startH;
			}
			wrap.css({height:h});
		} else if (opts.height=='auto') {
			h = wrap.height();
		} else {
			h = parseFloat(opts.height);
			wrap.css({height:h});
		}
		if (opts.fullpage == true) {
			h = $(window).height();
			wrap.css({height:h});
		}
		else if (opts.container != false) {
			h = $(opts.container).height();
			
	}
		wrap.css({height:h});
	}

	function resizeImage(){
		var res;
		function resizeImageWork(){
			w = wrap.width();
			setWrappersize();
			$('.camerarelative',target).css({'width':w,'height':h});
			$('.imgLoaded',target).each(function(){
				var t = $(this),
					wT = t.attr('width'),
					hT = t.attr('height'),
					imgLoadIn = t.index(),
					mTop,
					mLeft,
					alignment = t.attr('data-alignment'),
					portrait =  t.attr('data-portrait');

					if (opts.fx == 'kenburns') {
						t.css({
							'position' : 'absolute',
							'visibility' : 'visible',
						});
					} else {
						if(typeof alignment === 'undefined' || alignment === false || alignment === ''){
							alignment = opts.alignment;
						}

						if(typeof portrait === 'undefined' || portrait === false || portrait === ''){
							portrait = opts.portrait;
						}

						if (opts.fullpage == true) { // modif ced1870 rev c
							// var imgRatio = h/w;
							var imgRatio = hT/wT;
							var winH = $(window).height();
							var winW = $(window).width();
							var windowRatio = winH/winW;
//							mTop = 0;
							// calculate mTop to position the image
							var r = w / wT;
							var d = (Math.abs(h - (hT*r)))*0.5;
							switch(alignment){
								case 'topLeft':
									mTop = 0;
									break;
								case 'topCenter':
									mTop = 0;
									break;
								case 'topRight':
									mTop = 0;
									break;
								case 'centerLeft':
									mTop = '-'+d+'px';
									break;
								case 'center':
									mTop = '-'+d+'px';
									break;
								case 'centerRight':
									mTop = '-'+d+'px';
									break;
								case 'bottomLeft':
									mTop = '-'+d*2+'px';
									break;
								case 'bottomCenter':
									mTop = '-'+d*2+'px';
									break;
								case 'bottomRight':
									mTop = '-'+d*2+'px';
									break;
							}
							if (windowRatio > imgRatio) {
								imgH = winH - (2*parseInt(mTop));
								imgW = imgH / imgRatio;
								mLeft = Math.abs((winW - imgW) / 2);
//								mTop = 0;
							} else {
								imgW = winW;
								imgH = imgW * imgRatio;
								mLeft = 0;
//								mTop = 0;
							}
							t.css({
								'height' : imgH,
								'margin-left' : -mLeft,
								'margin-right' : -mLeft,
								'margin-top' : mTop,
								'position' : 'absolute',
								'visibility' : 'visible',
								'left' : 0,
								'top' : 0,
								'width' : imgW
							});
						} 
						else if (opts.container != false) {
							var imgRatio = hT/wT;
							var winH = $(opts.container).height();
							var winW = $(opts.container).width();
							var windowRatio = winH/winW;
							mTop = 0;
							if (windowRatio > imgRatio) {
								imgH = winH;
								imgW = imgH / imgRatio;
								mLeft = Math.abs((winW - imgW) / 2);
								mTop = 0;
							} else {
								imgW = winW;
								imgH = imgW * imgRatio;
								mLeft = 0;
								mTop = 0;
							}
							t.css({
								'height' : imgH,
								'margin-left' : -mLeft,
								'margin-right' : -mLeft,
								'margin-top' : mTop,
								'position' : 'absolute',
								'visibility' : 'visible',
								'left' : 0,
								'top' : 0,
								'width' : imgW
							});
						}
						else if(portrait==false||portrait=='false'){
							if((wT/hT)<(w/h)) {
								var r = w / wT;
								var d = (Math.abs(h - (hT*r)))*0.5;
								switch(alignment){
									case 'topLeft':
										mTop = 0;
										break;
									case 'topCenter':
										mTop = 0;
										break;
									case 'topRight':
										mTop = 0;
										break;
									case 'centerLeft':
										mTop = '-'+d+'px';
										break;
									case 'center':
										mTop = '-'+d+'px';
										break;
									case 'centerRight':
										mTop = '-'+d+'px';
										break;
									case 'bottomLeft':
										mTop = '-'+d*2+'px';
										break;
									case 'bottomCenter':
										mTop = '-'+d*2+'px';
										break;
									case 'bottomRight':
										mTop = '-'+d*2+'px';
										break;
								}
//								if (opts.fullpage == true) { // modif ced1870 rev c
//									t.css({
////									'height' : hT*r,
//									'height' : $(window).height(),
//									'margin-left' : mLeft,
//									'margin-right' : mLeft,
//									'margin-top' : mTop,
//									'position' : 'absolute',
//									'visibility' : 'visible',
//									'left' : 0, // ced1870
//									'width' : wT*r2
//									});
//								} else {
									t.css({
									'height' : hT*r,
									'margin-left' : 0,
									'margin-right' : 0,
									'margin-top' : mTop,
									'position' : 'absolute',
									'visibility' : 'visible',
									'width' : w
									});
//								}
							}
							else {
								var r = h / hT;
								var d = (Math.abs(w - (wT*r)))*0.5;
								switch(alignment){
									case 'topLeft':
										mLeft = 0;
										break;
									case 'topCenter':
										mLeft = '-'+d+'px';
										break;
									case 'topRight':
										mLeft = '-'+d*2+'px';
										break;
									case 'centerLeft':
										mLeft = 0;
										break;
									case 'center':
										mLeft = '-'+d+'px';
										break;
									case 'centerRight':
										mLeft = '-'+d*2+'px';
										break;
									case 'bottomLeft':
										mLeft = 0;
										break;
									case 'bottomCenter':
										mLeft = '-'+d+'px';
										break;
									case 'bottomRight':
										mLeft = '-'+d*2+'px';
										break;
								}
								t.css({
									'height' : h,
									'margin-left' : mLeft,
									'margin-right' : mLeft,
									'margin-top' : 0,
									'position' : 'absolute',
									'visibility' : 'visible',
									'width' : wT*r
								});
							}
						} else {
							if((wT/hT)<(w/h)) {
								var r = h / hT;
								var d = (Math.abs(w - (wT*r)))*0.5;
								switch(alignment){
									case 'topLeft':
										mLeft = 0;
										break;
									case 'topCenter':
										mLeft = d+'px';
										break;
									case 'topRight':
										mLeft = d*2+'px';
										break;
									case 'centerLeft':
										mLeft = 0;
										break;
									case 'center':
										mLeft = d+'px';
										break;
									case 'centerRight':
										mLeft = d*2+'px';
										break;
									case 'bottomLeft':
										mLeft = 0;
										break;
									case 'bottomCenter':
										mLeft = d+'px';
										break;
									case 'bottomRight':
										mLeft = d*2+'px';
										break;
								}
								t.css({
									'height' : h,
									'margin-left' : mLeft,
									'margin-right' : mLeft,
									'margin-top' : 0,
									'position' : 'absolute',
									'visibility' : 'visible',
									'width' : wT*r
								});
							}
							else {
								var r = w / wT;
								var d = (Math.abs(h - (hT*r)))*0.5;
								switch(alignment){
									case 'topLeft':
										mTop = 0;
										break;
									case 'topCenter':
										mTop = 0;
										break;
									case 'topRight':
										mTop = 0;
										break;
									case 'centerLeft':
										mTop = d+'px';
										break;
									case 'center':
										mTop = d+'px';
										break;
									case 'centerRight':
										mTop = d+'px';
										break;
									case 'bottomLeft':
										mTop = d*2+'px';
										break;
									case 'bottomCenter':
										mTop = d*2+'px';
										break;
									case 'bottomRight':
										mTop = d*2+'px';
										break;
								}
								t.css({
									'height' : hT*r,
									'margin-left' : 0,
									'margin-right' : 0,
									'margin-top' : mTop,
									'position' : 'absolute',
									'visibility' : 'visible',
									'width' : w
								});
							}
						}
					}
			});
		}
		if (started == true) {
			clearTimeout(res);
			res = setTimeout(resizeImageWork,200);
		} else {
			resizeImageWork();
		}

		started = true;
	}


	var u,
		setT;

	var clickEv,
		autoAdv,
		navHover,
		commands,
		pagination;

	var videoHover,
		videoPresent;

	if(isMobile() && opts.mobileAutoAdvance!=''){
		autoAdv = opts.mobileAutoAdvance;
	} else {
		autoAdv = opts.autoAdvance;
	}

	if(autoAdv==false){
		elem.addClass('paused');
	}

	if(isMobile() && opts.mobileNavHover!=''){
		navHover = opts.mobileNavHover;
	} else {
		navHover = opts.navigationHover;
	}

	if(elem.length!=0){

		var selector = $('.cameraSlide',target);
		selector.wrapInner('<div class="camerarelative" />');

		var navSlide;

		var barDirection = opts.barDirection;

		var camera_thumbs_wrap = wrap;


        $('iframe',fakeHover).each(function(){
			var t = $(this);
			var src = t.attr('src');
			t.attr('data-src',src);
			var divInd = t.parent().index('#'+wrap.attr('id')+' .camera_src > div'); // ced1870 modif in subversion a
			$('.camera_target_content .cameraContent:eq('+divInd+')',wrap).append(t);
		});
		function imgFake() {
				$('iframe',fakeHover).each(function(){
					$('.camera_caption',fakeHover).show();
					var t = $(this);
					var cloneSrc = t.attr('data-src');
					t.attr('src',cloneSrc);
					var imgFakeUrl = opts.imagePath+'blank.gif';
					var imgFake = new Image();
					imgFake.src = imgFakeUrl;
					setWrappersize();
					// if(opts.height.indexOf('%')!=-1) {
						// var startH = Math.round(w / (100/parseFloat(opts.height)));
						// if(opts.minHeight != '' && startH < parseFloat(opts.minHeight)){
							// h = parseFloat(opts.minHeight);
						// } else {
							// h = startH;
						// }
					// } else if (opts.height=='auto') {
						// h = wrap.height();
					// } else {
						// h = parseFloat(opts.height);
					// }
					t.after($(imgFake).attr({'class':'imgFake','width':w,'height':h}));
					var clone = t.clone();
					t.remove();
					$(imgFake).bind('click',function(){
						if($(this).css('position')=='absolute') {
							$(this).remove();
							autoplay = '';
							if(cloneSrc.indexOf('vimeo') != -1 || cloneSrc.indexOf('youtube') != -1) {
								if(cloneSrc.indexOf('?') != -1){
									autoplay = '&autoplay=1';
								} else {
									autoplay = '?autoplay=1';
								}
							} else if(cloneSrc.indexOf('dailymotion') != -1) {
								if(cloneSrc.indexOf('?') != -1){
									autoplay = '&autoPlay=1';
								} else {
									autoplay = '?autoPlay=1';
								}
							}
							clone.attr('src',cloneSrc+autoplay);
							videoPresent = true;
						} else {
							$(this).css({position:'absolute',top:0,left:0,zIndex:10}).after(clone);
							clone.css({position:'absolute',top:0,left:0,zIndex:9});
						}
					});
				});
		}

		imgFake();


		if(opts.hover==true){
			if(!isMobile() && opts.fx != 'kenburns'){
				fakeHover.hover(function(){
					elem.addClass('hovered');
				},function(){
					elem.removeClass('hovered');
				});
			}
		}

		if(navHover==true){
			$(prevNav,wrap).animate({opacity:0},0);
			$(nextNav,wrap).animate({opacity:0},0);
			$(commands,wrap).animate({opacity:0},0);
			if(isMobile()){
				fakeHover.on('vmouseover',function(){
					$(prevNav,wrap).animate({opacity:1},200);
					$(nextNav,wrap).animate({opacity:1},200);
					$(commands,wrap).animate({opacity:1},200);
				});
				fakeHover.on('vmouseout',function(){
					$(prevNav,wrap).delay(500).animate({opacity:0},200);
					$(nextNav,wrap).delay(500).animate({opacity:0},200);
					$(commands,wrap).delay(500).animate({opacity:0},200);
				});
			} else {
				fakeHover.hover(function(){
					$(prevNav,wrap).animate({opacity:1},200);
					$(nextNav,wrap).animate({opacity:1},200);
					$(commands,wrap).animate({opacity:1},200);
				},function(){
					$(prevNav,wrap).animate({opacity:0},200);
					$(nextNav,wrap).animate({opacity:0},200);
					$(commands,wrap).animate({opacity:0},200);
				});
			}
		}


		camera_thumbs_wrap.on('click keypress','.camera_stop',function(e){
			if ((e.type == 'keypress' && e.which == 13) || e.type == 'click') {
				stop();
			}
		});

		camera_thumbs_wrap.on('click keypress','.camera_play',function(e){
			if ((e.type == 'keypress' && e.which == 13) || e.type == 'click') {
				play();
			}
		});

		function play() {
			autoAdv = true;
			elem.removeClass('paused');
			if($('.camera_play',camera_thumbs_wrap).length){
				$('.camera_play',camera_thumbs_wrap).hide();
				$('.camera_stop',camera_thumbs_wrap).show();
				if(loader!='none'){
					$('#'+pieID).show();
				}
			} else {
				if(loader!='none'){
					$('#'+pieID).show();
				}
			}
		}

		function stop() {
			autoAdv = false;
			elem.addClass('paused');
			if($('.camera_stop',camera_thumbs_wrap).length){
				$('.camera_stop',camera_thumbs_wrap).hide()
				$('.camera_play',camera_thumbs_wrap).show();
				if(loader!='none'){
					$('#'+pieID).hide();
				}
			} else {
				if(loader!='none'){
					$('#'+pieID).hide();
				}
			}
		}

		// add keyboard navigation left/right
		if (opts.keyboardNavigation == true) {
			$(document).keydown(function(e){
				if ( (e.which == 37 || e.which == 39 || e.which == 80) && checkIsVisible()) {
					// 37 = left, 39 = right, 80 = P
					if ((e.type == 'keydown' && e.which == 37)) {
						prev();
					}
					if ((e.type == 'keydown' && e.which == 39)) {
						next();
					}
					if ((e.type == 'keydown' && e.which == 80)) {
						if (elem.hasClass('paused')) {
							play();
						} else {
							stop();
						}
					}
				}
			});
		}

		function checkIsVisible() {
			var $window = $(window);
			var winY = $window.scrollTop();
			var slideshowPosTop = wrap.offset().top;

			var isNotUpper = slideshowPosTop + (wrap.height() /2) > winY;
			var isNotLower = slideshowPosTop + (wrap.height() /2) < winY + $window.height();

			return (isNotUpper && isNotLower);
		}

		if(opts.pauseOnClick==true){
			$('.camera_target_content',fakeHover).mouseup(function(){
				autoAdv = false;
				elem.addClass('paused');
				$('.camera_stop',camera_thumbs_wrap).hide()
				$('.camera_play',camera_thumbs_wrap).show();
				$('#'+pieID).hide();
			});
		}

		$('.cameraContent, .imgFake',fakeHover).hover(function(){
			videoHover = true;
		},function(){
			videoHover = false;
		});

		$('.cameraContent, .imgFake',fakeHover).bind('click',function(){
			if(videoPresent == true && videoHover == true) {
				autoAdv = false;
				$('.camera_caption',fakeHover).hide();
				elem.addClass('paused');
				$('.camera_stop',camera_thumbs_wrap).hide()
				$('.camera_play',camera_thumbs_wrap).show();
				$('#'+pieID).hide();
			}
		});


	}


		function shuffle(arr) {
			for(
			  var j, x, i = arr.length; i;
			  j = parseInt(Math.random() * i),
			  x = arr[--i], arr[i] = arr[j], arr[j] = x
			);
			return arr;
		}

		function isInteger(s) {
			return Math.ceil(s) == Math.floor(s);
		}

		if (loader != 'pie') {
			barContainer.append('<span class="camera_bar_cont" />');
			$('.camera_bar_cont',barContainer)
				.animate({opacity:opts.loaderOpacity},0)
				.css({'position':'absolute', 'left':0, 'right':0, 'top':0, 'bottom':0, 'background-color':opts.loaderBgColor})
				.append('<span id="'+pieID+'" />');
			$('#'+pieID).animate({opacity:0},0);
			var canvas = $('#'+pieID);
			canvas.css({'position':'absolute', 'background-color':opts.loaderColor});
			switch(opts.barPosition){
				case 'left':
					barContainer.css({right:'auto',width:opts.loaderStroke});
					break;
				case 'right':
					barContainer.css({left:'auto',width:opts.loaderStroke});
					break;
				case 'top':
					barContainer.css({bottom:'auto',height:opts.loaderStroke});
					break;
				case 'bottom':
					barContainer.css({top:'auto',height:opts.loaderStroke});
					break;
			}
			switch(barDirection){
				case 'leftToRight':
					canvas.css({'left':0, 'right':0, 'top':opts.loaderPadding, 'bottom':opts.loaderPadding});
					break;
				case 'rightToLeft':
					canvas.css({'left':0, 'right':0, 'top':opts.loaderPadding, 'bottom':opts.loaderPadding});
					break;
				case 'topToBottom':
					canvas.css({'left':opts.loaderPadding, 'right':opts.loaderPadding, 'top':0, 'bottom':0});
					break;
				case 'bottomToTop':
					canvas.css({'left':opts.loaderPadding, 'right':opts.loaderPadding, 'top':0, 'bottom':0});
					break;
			}
		} else {
			pieContainer.append('<canvas id="'+pieID+'"></canvas>');
			var G_vmlCanvasManager;
			var canvas = document.getElementById(pieID);
			canvas.setAttribute("width", opts.pieDiameter);
			canvas.setAttribute("height", opts.pieDiameter);
			var piePosition;
			switch(opts.piePosition){
				case 'leftTop' :
					piePosition = 'left:0; top:0;';
					break;
				case 'rightTop' :
					piePosition = 'right:0; top:0;';
					break;
				case 'leftBottom' :
					piePosition = 'left:0; bottom:0;';
					break;
				case 'rightBottom' :
					piePosition = 'right:0; bottom:0;';
					break;
			}
			canvas.setAttribute("style", "position:absolute; z-index:1002; "+piePosition);
			var rad;
			var radNew;

			if (canvas && canvas.getContext) {
				var ctx = canvas.getContext("2d");
				ctx.rotate(Math.PI*(3/2));
				ctx.translate(-opts.pieDiameter,0);
			}

		}
		if(loader=='none' || autoAdv==false) {
			$('#'+pieID).hide();
			$('.camera_canvas_wrap',camera_thumbs_wrap).hide();
		}

		if($(pagination).length) {
			$(pagination).append('<ul class="camera_pag_ul" />');
			var li;
			for (li = 0; li < amountSlide; li++){
				$('.camera_pag_ul',wrap).append('<li class="pag_nav_'+li+'" style="position:relative; z-index:1002" tabindex="0" aria-label="Show slide '+(li+1)+'"><span><span>'+li+'</span></span></li>');
			}
			$('.camera_pag_ul li',wrap).hover(function(){
				$(this).addClass('camera_hover');
				if($('.camera_thumb',this).length){
					var wTh = $('.camera_thumb',this).outerWidth(),
					hTh = $('.camera_thumb',this).outerHeight(),
					wTt = $(this).outerWidth();
					$('.camera_thumb',this).show().css({'top':'-'+hTh+'px','left':'-'+(wTh-wTt)/2+'px'}).animate({'opacity':1,'margin-top':'-3px'},200);
					$('.thumb_arrow',this).show().animate({'opacity':1,'margin-top':'-3px'},200);
				}
			},function(){
				$(this).removeClass('camera_hover');
				$('.camera_thumb',this).animate({'margin-top':'-20px','opacity':0},200,function(){
					$(this).css({marginTop:'5px'}).hide();
				});
				$('.thumb_arrow',this).animate({'margin-top':'-20px','opacity':0},200,function(){
					$(this).css({marginTop:'5px'}).hide();
				});
			});
		}
		opts.onStartLoading.call(this);
		nextSlide();
		loadThumbs();
		
		function loadThumbs() {
			if($(thumbs).length) {
				var thumbUrl;
				if(!$(pagination).length) {
					$(thumbs).append('<div />');
					$(thumbs).before('<div class="camera_prevThumbs hideNav"><div></div></div>').before('<div class="camera_nextThumbs hideNav"><div></div></div>');
					$('> div',thumbs).append('<ul />');
					$('ul', thumbs).width(allThumbs.length * (parseInt(opts.thumbwidth)+2)); // added by ced1870 v1.3.5
					$('ul', thumbs).height(parseInt(opts.thumbheight)); // added by ced1870 v1.3.5
					ulthumbwidth = 0; // added by ced1870 v1.3.10
					$.each(allThumbs, function(i, val) {
						if($('> div', elem).eq(i).attr('data-thumb')!='') {
							var thumbUrl = $('> div', elem).eq(i).attr('data-thumb'),
								newImg = new Image();
							newImg.src = thumbUrl;
							$('ul',thumbs).append('<li class="pix_thumb pix_thumb_'+i+'" />');
							$('li.pix_thumb_'+i,thumbs).append($(newImg).attr('class','camera_thumb'));
							ulthumbwidth += parseFloat($('li.pix_thumb_'+i,thumbs).outerWidth()) + parseFloat($('li.pix_thumb_'+i,thumbs).css('marginLeft')) + parseFloat($('li.pix_thumb_'+i,thumbs).css('marginRight')); // added by ced1870 v1.3.10
						}
					});
					$('ul', thumbs).width(ulthumbwidth); // added by ced1870 v1.3.10
				} else {
					$.each(allThumbs, function(i, val) {
						if($('> div', elem).eq(i).attr('data-thumb')!='') {
							var thumbUrl = $('> div', elem).eq(i).attr('data-thumb'),
								newImg = new Image();
							newImg.src = thumbUrl;
							$('li.pag_nav_'+i,pagination).append($(newImg).attr('class','camera_thumb').css({'position':'absolute'}).animate({opacity:0},0));
							$('li.pag_nav_'+i+' > img',pagination).after('<div class="thumb_arrow" />');
							$('li.pag_nav_'+i+' > .thumb_arrow',pagination).animate({opacity:0},0);
						}
					});
					wrap.css({marginBottom:$(pagination).outerHeight()});
				}
			} else if(!$(thumbs).length && $(pagination).length) {
				wrap.css({marginBottom:$(pagination).outerHeight()});
			}
		}


		var firstPos = true;

		function thumbnailPos() {
			if($(thumbs).length && !$(pagination).length) {
				var wTh = $(thumbs).outerWidth(),
					owTh = $('ul > li',thumbs).outerWidth(),
					pos = $('li.cameracurrent', thumbs).length ? $('li.cameracurrent', thumbs).position() : '',
					ulW = ($('ul > li', thumbs).length * $('ul > li', thumbs).outerWidth()),
					offUl = $('ul', thumbs).offset().left,
					offDiv = $('> div', thumbs).offset().left,
					ulLeft;

					if(offUl<0){
						ulLeft = '-'+ (offDiv-offUl);
					} else {
						ulLeft = offDiv-offUl;
					}



				if(firstPos == true) {
					// $('ul', thumbs).width($('ul > li', thumbs).length * $('ul > li', thumbs).outerWidth());
					ulthumbwidth = 0; // added by ced1870 v1.3.10
					$.each(allThumbs, function(i, val) { // added by ced1870 v1.3.10
							ulthumbwidth += parseFloat($('li.pix_thumb_'+i,thumbs).outerWidth()) + parseFloat($('li.pix_thumb_'+i,thumbs).css('marginLeft')) + parseFloat($('li.pix_thumb_'+i,thumbs).css('marginRight'));
					});
					$('ul', thumbs).width(ulthumbwidth + 2); // added by ced1870 v1.3.10
					if($(thumbs).length && !$(pagination).length) {
						wrap.css({paddingBottom:$(thumbs).outerHeight()});
					}
					thumbnailVisible();
					/*I repeat this two lines because of a problem with iPhones*/
					// $('ul', thumbs).width($('ul > li', thumbs).length * $('ul > li', thumbs).outerWidth()); // removed by ced1870 v1.3.5
					if($(thumbs).length && !$(pagination).length) {
						wrap.css({paddingBottom:$(thumbs).outerHeight()});
					}
					/*...*/
				}
				firstPos = false;

					var left = $('li.cameracurrent', thumbs).length ? pos.left : '',
						right = $('li.cameracurrent', thumbs).length ? pos.left+($('li.cameracurrent', thumbs).outerWidth()) : '';
					if(left<$('li.cameracurrent', thumbs).outerWidth()) {
						left = 0;
					}
                                        // ced1870 dev
                                        //$(document.body).click(function(){$('ul', thumbs).animate({'margin-left':'-'+($('ul', thumbs).outerWidth())+'px'},500,thumbnailVisible);});
					if(right-ulLeft>wTh){
						if((left+wTh)<ulW){
							$('ul', thumbs).animate({'margin-left':'-'+(left)+'px'},500,thumbnailVisible);
						} else {
							$('ul', thumbs).animate({'margin-left':'-'+($('ul', thumbs).outerWidth()-wTh)+'px'},500,thumbnailVisible);
						}
					} else if(left-ulLeft<0) {
						$('ul', thumbs).animate({'margin-left':'-'+(left)+'px'},500,thumbnailVisible);
					} else {
						$('ul', thumbs).css({'margin-left':'auto', 'margin-right':'auto'});
						setTimeout(thumbnailVisible,100);
					}

			}
		}

		if($(commands).length) {
			$(commands).append('<div class="camera_play" tabindex="0" aria-label="Start the slideshow"></div>').append('<div class="camera_stop" tabindex="0" aria-label="Pause the slideshow"></div>');
			if(autoAdv==true){
				$('.camera_play',camera_thumbs_wrap).hide();
				$('.camera_stop',camera_thumbs_wrap).show();
			} else {
				$('.camera_stop',camera_thumbs_wrap).hide();
				$('.camera_play',camera_thumbs_wrap).show();
			}

		}


		function canvasLoader() {
			rad = 0;
			var barWidth = $('.camera_bar_cont',camera_thumbs_wrap).width(),
				barHeight = $('.camera_bar_cont',camera_thumbs_wrap).height();

			if (loader != 'pie') {
				switch(barDirection){
					case 'leftToRight':
						$('#'+pieID).css({'right':barWidth});
						break;
					case 'rightToLeft':
						$('#'+pieID).css({'left':barWidth});
						break;
					case 'topToBottom':
						$('#'+pieID).css({'bottom':barHeight});
						break;
					case 'bottomToTop':
						$('#'+pieID).css({'top':barHeight});
						break;
				}
			} else {
				ctx.clearRect(0,0,opts.pieDiameter,opts.pieDiameter);
			}
		}


		canvasLoader();


		$('.moveFromLeft, .moveFromRight, .moveFromTop, .moveFromBottom, .fadeIn, .fadeFromLeft, .fadeFromRight, .fadeFromTop, .fadeFromBottom',fakeHover).each(function(){
			$(this).css('visibility','hidden');
		});

		// opts.onStartLoading.call(this);

		// nextSlide();
		addLightbox();


	/*************************** FUNCTION nextSlide() ***************************/

	function nextSlide(navSlide){
		elem.addClass('camerasliding');

		videoPresent = false;
		var vis = parseFloat($('div.cameraSlide.cameracurrent',target).index());

		if(navSlide>0){
			var slideI = navSlide-1;
		} else if (vis == amountSlide-1) {
			var slideI = 0;
		} else {
			var slideI = vis+1;
		}


		var slide = $('.cameraSlide:eq('+slideI+')',target);
		var slideNext = $('.cameraSlide:eq('+(slideI+1)+')',target).addClass('cameranext');
		if( vis != slideI+1 ) {
			slideNext.hide();
		}

//		$('.cameraContent',fakeHover).fadeOut(600);
		$('.cameraContent',fakeHover).removeClass('cameravisible').hide();
		$('.camera_caption',fakeHover).show();

		$('.camerarelative',slide).append($('> div ',elem).eq(slideI).find('> div.camera_effected'));

		$('.camera_target_content .cameraContent:eq('+slideI+')',wrap).append($('> div ',elem).eq(slideI).find('> div'));

		if(!$('.imgLoaded',slide).length){
			var imgUrl = allImg[slideI];
			var imgLoaded = new Image();
			// imgLoaded.src = imgUrl +"?"+ new Date().getTime();
			imgLoaded.src = imgUrl;
			slide.css('visibility','hidden');
			slide.prepend($(imgLoaded).attr('class','imgLoaded').css('visibility','hidden'));
			var wT, hT;
			if (!$(imgLoaded).get(0).complete || wT == '0' || hT == '0' || typeof wT === 'undefined' || wT === false || typeof hT === 'undefined' || hT === false) {
				$('.camera_loader',wrap).delay(500).fadeIn(400);
				imgLoaded.onload = function() {
					wT = imgLoaded.naturalWidth;
					hT = imgLoaded.naturalHeight;
					$(imgLoaded).attr('data-alignment',allAlign[slideI]).attr('data-portrait',allPor[slideI]);
					$(imgLoaded).attr('width',wT);
					$(imgLoaded).attr('height',hT);
					$(imgLoaded).attr('alt',allAltText[slideI]);
					target.find('.cameraSlide_'+slideI).css('visibility','visible');
					resizeImage();
					nextSlide(slideI+1);
					if($('.camera_loader',wrap).is(':visible')){
						$('.camera_loader',wrap).fadeOut(400);
					} else {
						$('.camera_loader',wrap).css({'visibility':'hidden'});
						$('.camera_loader',wrap).fadeOut(400,function(){
							$('.camera_loader',wrap).css({'visibility':'visible'});
						});
					}
				};
			}
		} else {
			if( allImg.length > (slideI+1) && !$('.imgLoaded',slideNext).length ){
				var imgUrl2 = allImg[(slideI+1)];
				var imgLoaded2 = new Image();
				imgLoaded2.src = imgUrl2;
				slideNext.prepend($(imgLoaded2).attr('class','imgLoaded').css('visibility','hidden'));
				imgLoaded2.onload = function() {
					wT = imgLoaded2.naturalWidth;
					hT = imgLoaded2.naturalHeight;
					$(imgLoaded2).attr('data-alignment',allAlign[slideI+1]).attr('data-portrait',allPor[slideI+1]);
					$(imgLoaded2).attr('width',wT);
					$(imgLoaded2).attr('height',hT);
					$(imgLoaded2).attr('alt',allAltText[slideI+1]);
					resizeImage();
				};
			}
			opts.onLoaded.call(this);
			var rows = opts.rows,
				cols = opts.cols,
				couples = 1,
				difference = 0,
				dataSlideOn,
				time,
				transPeriod,
				fx,
				easing,
				randomFx = new Array('simpleFade','curtainTopLeft','curtainTopRight','curtainBottomLeft','curtainBottomRight','curtainSliceLeft','curtainSliceRight','blindCurtainTopLeft','blindCurtainTopRight','blindCurtainBottomLeft','blindCurtainBottomRight','blindCurtainSliceBottom','blindCurtainSliceTop','stampede','mosaic','mosaicReverse','mosaicRandom','mosaicSpiral','mosaicSpiralReverse','topLeftBottomRight','bottomRightTopLeft','bottomLeftTopRight','topRightBottomLeft','scrollLeft','scrollRight','scrollTop','scrollBottom','scrollHorz');
				marginLeft = 0,
				marginTop = 0,
				opacityOnGrid = 0;

				if(opts.opacityOnGrid==true){
					opacityOnGrid = 0;
				} else {
					opacityOnGrid = 1;
				}



			var dataFx = $(' > div',elem).eq(slideI).attr('data-fx');

			if(isMobile()&&opts.mobileFx!=''&&opts.mobileFx!='default'){
				fx = opts.mobileFx;
			} else {
				if(typeof dataFx !== 'undefined' && dataFx!== false && dataFx!== 'default'){
					fx = dataFx;
				} else {
					fx = opts.fx;
				}
			}

			if(fx=='random') {
				fx = shuffle(randomFx);
				fx = fx[0];
			} else {
				fx = fx;
				if(fx.indexOf(',')>0){
					fx = fx.replace(/ /g,'');
					fx = fx.split(',');
					fx = shuffle(fx);
					fx = fx[0];
				}
			}

			dataEasing = $(' > div',elem).eq(slideI).attr('data-easing');
			mobileEasing = $(' > div',elem).eq(slideI).attr('data-mobileEasing');

			if(isMobile()&&opts.mobileEasing!=''&&opts.mobileEasing!='default'){
				if(typeof mobileEasing !== 'undefined' && mobileEasing!== false && mobileEasing!== 'default') {
					easing = mobileEasing;
				} else {
					easing = opts.mobileEasing;
				}
			} else {
				if(typeof dataEasing !== 'undefined' && dataEasing!== false && dataEasing!== 'default') {
					easing = dataEasing;
				} else {
					easing = opts.easing;
				}
			}

			dataSlideOn = $(' > div',elem).eq(slideI).attr('data-slideOn');
			if(typeof dataSlideOn !== 'undefined' && dataSlideOn!== false){
				slideOn = dataSlideOn;
			} else {
				if(opts.slideOn=='random'){
					var slideOn = new Array('next','prev');
					slideOn = shuffle(slideOn);
					slideOn = slideOn[0];
				} else {
					slideOn = opts.slideOn;
				}
			}

			var dataTime = $(' > div',elem).eq(slideI).attr('data-time');
			if(typeof dataTime !== 'undefined' && dataTime!== false && dataTime!== ''){
				time = parseFloat(dataTime);
			} else {
				time = opts.time;
			}

			var dataTransPeriod = $(' > div',elem).eq(slideI).attr('data-transPeriod');
			if(typeof dataTransPeriod !== 'undefined' && dataTransPeriod!== false && dataTransPeriod!== ''){
				transPeriod = parseFloat(dataTransPeriod);
			} else {
				transPeriod = opts.transPeriod;
			}

			if(!$(elem).hasClass('camerastarted')){
				if (fx != 'kenburns') fx = 'simpleFade';
				slideOn = 'next';
				easing = '';
				transPeriod = 400;
				$(elem).addClass('camerastarted')
			}

			switch(fx){
				case 'simpleFade':
					cols = 1;
					rows = 1;
						break;
				case 'kenburns':
					cols = 1;
					rows = 1;
						break;
				case 'curtainTopLeft':
					if(opts.slicedCols == 0) {
						cols = opts.cols;
					} else {
						cols = opts.slicedCols;
					}
					rows = 1;
						break;
				case 'curtainTopRight':
					if(opts.slicedCols == 0) {
						cols = opts.cols;
					} else {
						cols = opts.slicedCols;
					}
					rows = 1;
						break;
				case 'curtainBottomLeft':
					if(opts.slicedCols == 0) {
						cols = opts.cols;
					} else {
						cols = opts.slicedCols;
					}
					rows = 1;
						break;
				case 'curtainBottomRight':
					if(opts.slicedCols == 0) {
						cols = opts.cols;
					} else {
						cols = opts.slicedCols;
					}
					rows = 1;
						break;
				case 'curtainSliceLeft':
					if(opts.slicedCols == 0) {
						cols = opts.cols;
					} else {
						cols = opts.slicedCols;
					}
					rows = 1;
						break;
				case 'curtainSliceRight':
					if(opts.slicedCols == 0) {
						cols = opts.cols;
					} else {
						cols = opts.slicedCols;
					}
					rows = 1;
						break;
				case 'blindCurtainTopLeft':
					if(opts.slicedRows == 0) {
						rows = opts.rows;
					} else {
						rows = opts.slicedRows;
					}
					cols = 1;
						break;
				case 'blindCurtainTopRight':
					if(opts.slicedRows == 0) {
						rows = opts.rows;
					} else {
						rows = opts.slicedRows;
					}
					cols = 1;
						break;
				case 'blindCurtainBottomLeft':
					if(opts.slicedRows == 0) {
						rows = opts.rows;
					} else {
						rows = opts.slicedRows;
					}
					cols = 1;
						break;
				case 'blindCurtainBottomRight':
					if(opts.slicedRows == 0) {
						rows = opts.rows;
					} else {
						rows = opts.slicedRows;
					}
					cols = 1;
						break;
				case 'blindCurtainSliceTop':
					if(opts.slicedRows == 0) {
						rows = opts.rows;
					} else {
						rows = opts.slicedRows;
					}
					cols = 1;
						break;
				case 'blindCurtainSliceBottom':
					if(opts.slicedRows == 0) {
						rows = opts.rows;
					} else {
						rows = opts.slicedRows;
					}
					cols = 1;
						break;
				case 'stampede':
					difference = '-'+transPeriod;
						break;
				case 'mosaic':
					difference = opts.gridDifference;
						break;
				case 'mosaicReverse':
					difference = opts.gridDifference;
						break;
				case 'mosaicRandom':
						break;
				case 'mosaicSpiral':
					difference = opts.gridDifference;
					couples = 1.7;
						break;
				case 'mosaicSpiralReverse':
					difference = opts.gridDifference;
					couples = 1.7;
						break;
				case 'topLeftBottomRight':
					difference = opts.gridDifference;
					couples = 6;
						break;
				case 'bottomRightTopLeft':
					difference = opts.gridDifference;
					couples = 6;
						break;
				case 'bottomLeftTopRight':
					difference = opts.gridDifference;
					couples = 6;
						break;
				case 'topRightBottomLeft':
					difference = opts.gridDifference;
					couples = 6;
						break;
				case 'scrollLeft':
					cols = 1;
					rows = 1;
						break;
				case 'scrollRight':
					cols = 1;
					rows = 1;
						break;
				case 'scrollTop':
					cols = 1;
					rows = 1;
						break;
				case 'scrollBottom':
					cols = 1;
					rows = 1;
						break;
				case 'scrollHorz':
					cols = 1;
					rows = 1;
						break;
			}

			var cycle = 0;
			var blocks = rows*cols;
			var leftScrap = w-(Math.floor(w/cols)*cols);
			var topScrap = h-(Math.floor(h/rows)*rows);
			var addLeft;
			var addTop;
			var tAppW = 0;
			var tAppH = 0;
			var arr = new Array();
			var delay = new Array();
			var order = new Array();
			while(cycle < blocks){
				arr.push(cycle);
				delay.push(cycle);
				cameraCont.append('<div class="cameraappended" style="display:none; overflow:hidden; position:absolute; z-index:1000" />');
				var tApp = $('.cameraappended:eq('+cycle+')',target);
				if(fx=='scrollLeft' || fx=='scrollRight' || fx=='scrollTop' || fx=='scrollBottom' || fx=='scrollHorz'){
					selector.eq(slideI).clone().show().appendTo(tApp);
				} else if(fx != 'kenburns') {
					if(slideOn=='next'){
						selector.eq(slideI).clone().show().appendTo(tApp);
					} else {
						selector.eq(vis).clone().show().appendTo(tApp);
					}
				}

				if(cycle%cols<leftScrap){
					addLeft = 1;
				} else {
					addLeft = 0;
				}
				if(cycle%cols==0){
					tAppW = 0;
				}
				if(Math.floor(cycle/cols)<topScrap){
					addTop = 1;
				} else {
					addTop = 0;
				}
				tApp.css({
					'height': Math.floor((h/rows)+addTop+1),
					'left': tAppW,
					'top': tAppH,
					'width': Math.floor((w/cols)+addLeft+1)
				});
				$('> .cameraSlide', tApp).css({
					'height': h,
					'margin-left': '-'+tAppW+'px',
					'margin-top': '-'+tAppH+'px',
					'width': w
				});
				tAppW = tAppW+tApp.width()-1;
				if(cycle%cols==cols-1){
					tAppH = tAppH + tApp.height() - 1;
				}
				cycle++;
			}



			switch(fx){
				case 'curtainTopLeft':
						break;
				case 'curtainBottomLeft':
						break;
				case 'curtainSliceLeft':
						break;
				case 'curtainTopRight':
					arr = arr.reverse();
						break;
				case 'curtainBottomRight':
					arr = arr.reverse();
						break;
				case 'curtainSliceRight':
					arr = arr.reverse();
						break;
				case 'blindCurtainTopLeft':
						break;
				case 'blindCurtainBottomLeft':
					arr = arr.reverse();
						break;
				case 'blindCurtainSliceTop':
						break;
				case 'blindCurtainTopRight':
						break;
				case 'blindCurtainBottomRight':
					arr = arr.reverse();
						break;
				case 'blindCurtainSliceBottom':
					arr = arr.reverse();
						break;
				case 'stampede':
					arr = shuffle(arr);
						break;
				case 'mosaic':
						break;
				case 'mosaicReverse':
					arr = arr.reverse();
						break;
				case 'mosaicRandom':
					arr = shuffle(arr);
						break;
				case 'mosaicSpiral':
					var rows2 = rows/2, x, y, z, n=0;
						for (z = 0; z < rows2; z++){
							y = z;
							for (x = z; x < cols - z - 1; x++) {
								order[n++] = y * cols + x;
							}
							x = cols - z - 1;
							for (y = z; y < rows - z - 1; y++) {
								order[n++] = y * cols + x;
							}
							y = rows - z - 1;
							for (x = cols - z - 1; x > z; x--) {
								order[n++] = y * cols + x;
							}
							x = z;
							for (y = rows - z - 1; y > z; y--) {
								order[n++] = y * cols + x;
							}
						}

						arr = order;

						break;
				case 'mosaicSpiralReverse':
					var rows2 = rows/2, x, y, z, n=blocks-1;
						for (z = 0; z < rows2; z++){
							y = z;
							for (x = z; x < cols - z - 1; x++) {
								order[n--] = y * cols + x;
							}
							x = cols - z - 1;
							for (y = z; y < rows - z - 1; y++) {
								order[n--] = y * cols + x;
							}
							y = rows - z - 1;
							for (x = cols - z - 1; x > z; x--) {
								order[n--] = y * cols + x;
							}
							x = z;
							for (y = rows - z - 1; y > z; y--) {
								order[n--] = y * cols + x;
							}
						}

						arr = order;

						break;
				case 'topLeftBottomRight':
					for (var y = 0; y < rows; y++)
					for (var x = 0; x < cols; x++) {
						order.push(x + y);
					}
						delay = order;
						break;
				case 'bottomRightTopLeft':
					for (var y = 0; y < rows; y++)
					for (var x = 0; x < cols; x++) {
						order.push(x + y);
					}
						delay = order.reverse();
						break;
				case 'bottomLeftTopRight':
					for (var y = rows; y > 0; y--)
					for (var x = 0; x < cols; x++) {
						order.push(x + y);
					}
						delay = order;
						break;
				case 'topRightBottomLeft':
					for (var y = 0; y < rows; y++)
					for (var x = cols; x > 0; x--) {
						order.push(x + y);
					}
						delay = order;
						break;
			}



			$.each(arr, function(index, value) {

				if(value%cols<leftScrap){
					addLeft = 1;
				} else {
					addLeft = 0;
				}
				if(value%cols==0){
					tAppW = 0;
				}
				if(Math.floor(value/cols)<topScrap){
					addTop = 1;
				} else {
					addTop = 0;
				}

				switch(fx){
					case 'simpleFade':
						height = h;
						width = w;
						opacityOnGrid = 0;
							break;
					case 'kenburns':
						height = h;
						width = w;
						opacityOnGrid = 0;
							break;
					case 'curtainTopLeft':
						height = 0,
						width = Math.floor((w/cols)+addLeft+1),
						marginTop = '-'+Math.floor((h/rows)+addTop+1)+'px';
							break;
					case 'curtainTopRight':
						height = 0,
						width = Math.floor((w/cols)+addLeft+1),
						marginTop = '-'+Math.floor((h/rows)+addTop+1)+'px';
							break;
					case 'curtainBottomLeft':
						height = 0,
						width = Math.floor((w/cols)+addLeft+1),
						marginTop = Math.floor((h/rows)+addTop+1)+'px';
							break;
					case 'curtainBottomRight':
						height = 0,
						width = Math.floor((w/cols)+addLeft+1),
						marginTop = Math.floor((h/rows)+addTop+1)+'px';
							break;
					case 'curtainSliceLeft':
						height = 0,
						width = Math.floor((w/cols)+addLeft+1);
						if(value%2==0){
							marginTop = Math.floor((h/rows)+addTop+1)+'px';
						} else {
							marginTop = '-'+Math.floor((h/rows)+addTop+1)+'px';
						}
							break;
					case 'curtainSliceRight':
						height = 0,
						width = Math.floor((w/cols)+addLeft+1);
						if(value%2==0){
							marginTop = Math.floor((h/rows)+addTop+1)+'px';
						} else {
							marginTop = '-'+Math.floor((h/rows)+addTop+1)+'px';
						}
							break;
					case 'blindCurtainTopLeft':
						height = Math.floor((h/rows)+addTop+1),
						width = 0,
						marginLeft = '-'+Math.floor((w/cols)+addLeft+1)+'px';
							break;
					case 'blindCurtainTopRight':
						height = Math.floor((h/rows)+addTop+1),
						width = 0,
						marginLeft = Math.floor((w/cols)+addLeft+1)+'px';
							break;
					case 'blindCurtainBottomLeft':
						height = Math.floor((h/rows)+addTop+1),
						width = 0,
						marginLeft = '-'+Math.floor((w/cols)+addLeft+1)+'px';
							break;
					case 'blindCurtainBottomRight':
						height = Math.floor((h/rows)+addTop+1),
						width = 0,
						marginLeft = Math.floor((w/cols)+addLeft+1)+'px';
							break;
					case 'blindCurtainSliceBottom':
						height = Math.floor((h/rows)+addTop+1),
						width = 0;
						if(value%2==0){
							marginLeft = '-'+Math.floor((w/cols)+addLeft+1)+'px';
						} else {
							marginLeft = Math.floor((w/cols)+addLeft+1)+'px';
						}
							break;
					case 'blindCurtainSliceTop':
						height = Math.floor((h/rows)+addTop+1),
						width = 0;
						if(value%2==0){
							marginLeft = '-'+Math.floor((w/cols)+addLeft+1)+'px';
						} else {
							marginLeft = Math.floor((w/cols)+addLeft+1)+'px';
						}
							break;
					case 'stampede':
						height = 0;
						width = 0;
						marginLeft = (w*0.2)*(((index)%cols)-(cols-(Math.floor(cols/2))))+'px';
						marginTop = (h*0.2)*((Math.floor(index/cols)+1)-(rows-(Math.floor(rows/2))))+'px';
							break;
					case 'mosaic':
						height = 0;
						width = 0;
							break;
					case 'mosaicReverse':
						height = 0;
						width = 0;
						marginLeft = Math.floor((w/cols)+addLeft+1)+'px';
						marginTop = Math.floor((h/rows)+addTop+1)+'px';
							break;
					case 'mosaicRandom':
						height = 0;
						width = 0;
						marginLeft = Math.floor((w/cols)+addLeft+1)*0.5+'px';
						marginTop = Math.floor((h/rows)+addTop+1)*0.5+'px';
							break;
					case 'mosaicSpiral':
						height = 0;
						width = 0;
						marginLeft = Math.floor((w/cols)+addLeft+1)*0.5+'px';
						marginTop = Math.floor((h/rows)+addTop+1)*0.5+'px';
							break;
					case 'mosaicSpiralReverse':
						height = 0;
						width = 0;
						marginLeft = Math.floor((w/cols)+addLeft+1)*0.5+'px';
						marginTop = Math.floor((h/rows)+addTop+1)*0.5+'px';
							break;
					case 'topLeftBottomRight':
						height = 0;
						width = 0;
							break;
					case 'bottomRightTopLeft':
						height = 0;
						width = 0;
						marginLeft = Math.floor((w/cols)+addLeft+1)+'px';
						marginTop = Math.floor((h/rows)+addTop+1)+'px';
							break;
					case 'bottomLeftTopRight':
						height = 0;
						width = 0;
						marginLeft = 0;
						marginTop = Math.floor((h/rows)+addTop+1)+'px';
							break;
					case 'topRightBottomLeft':
						height = 0;
						width = 0;
						marginLeft = Math.floor((w/cols)+addLeft+1)+'px';
						marginTop = 0;
							break;
					case 'scrollRight':
						height = h;
						width = w;
						marginLeft = -w;
							break;
					case 'scrollLeft':
						height = h;
						width = w;
						marginLeft = w;
							break;
					case 'scrollTop':
						height = h;
						width = w;
						marginTop = h;
							break;
					case 'scrollBottom':
						height = h;
						width = w;
						marginTop = -h;
							break;
					case 'scrollHorz':
						height = h;
						width = w;
						if(vis==0 && slideI==amountSlide-1) {
							marginLeft = -w;
						} else if(vis<slideI  || (vis==amountSlide-1 && slideI==0)) {
							marginLeft = w;
						} else {
							marginLeft = -w;
						}
							break;
					}


				var tApp = $('.cameraappended:eq('+value+')',target);

				if(typeof u !== 'undefined'){
					clearInterval(u);
					clearTimeout(setT);
					setT = setTimeout(canvasLoader,transPeriod+difference);
				}


				if($(pagination).length){
					$('.camera_pag li',wrap).removeClass('cameracurrent');
					$('.camera_pag li',wrap).eq(slideI).addClass('cameracurrent');
				}

				if($(thumbs).length){
					$('li', thumbs).removeClass('cameracurrent');
					$('li', thumbs).eq(slideI).addClass('cameracurrent');
					$('li', thumbs).not('.cameracurrent').find('img').animate({opacity:.5},0);
					$('li.cameracurrent img', thumbs).animate({opacity:1},0);
					$('li', thumbs).hover(function(){
						$('img',this).stop(true,false).animate({opacity:1},150);
					},function(){
						if(!$(this).hasClass('cameracurrent')){
							$('img',this).stop(true,false).animate({opacity:.5},150);
						}
					});
				}


				var easedTime = parseFloat(transPeriod)+parseFloat(difference);

				function cameraeased() {
$('.cameravisible').removeClass('cameravisible');
					$(this).addClass('cameraeased');
					if($('.cameraeased',target).length>=0){
						$(thumbs).css({visibility:'visible'});
					}
					if($('.cameraeased',target).length==blocks){

						thumbnailPos();

						$('.moveFromLeft, .moveFromRight, .moveFromTop, .moveFromBottom, .fadeIn, .fadeFromLeft, .fadeFromRight, .fadeFromTop, .fadeFromBottom',fakeHover).each(function(){
							$(this).css('visibility','hidden');
						});

						selector.eq(slideI).show().css('z-index','999').removeClass('cameranext').addClass('cameracurrent');
						selector.eq(vis).css('z-index','1').removeClass('cameracurrent');
						$('.cameraContent',fakeHover).eq(slideI).addClass('cameracurrent');
						if (vis >= 0) {
							$('.cameraContent',fakeHover).eq(vis).removeClass('cameracurrent');
						}

						opts.onEndTransition.call(this);

						if($('> div', elem).eq(slideI).attr('data-video')!='hide' && $('.cameraContent.cameracurrent .imgFake',fakeHover).length ){
							$('.cameraContent.cameracurrent .imgFake',fakeHover).click();
						}


						var lMoveIn = selector.eq(slideI).find('.fadeIn').length;
						var lMoveInContent = $('.cameraContent',fakeHover).eq(slideI).find('.moveFromLeft, .moveFromRight, .moveFromTop, .moveFromBottom, .fadeIn, .fadeFromLeft, .fadeFromRight, .fadeFromTop, .fadeFromBottom').length;

						if (lMoveIn!=0){
							$('.cameraSlide.cameracurrent .fadeIn',fakeHover).each(function(){
								if($(this).attr('data-easing')!=''){
									var easeMove = $(this).attr('data-easing');
								} else {
									var easeMove = easing;
								}
								var t = $(this);
								if(typeof t.attr('data-outerWidth') === 'undefined' || t.attr('data-outerWidth') === false || t.attr('data-outerWidth') === '') {
									var wMoveIn = t.outerWidth();
									t.attr('data-outerWidth',wMoveIn);
								} else {
									var wMoveIn = t.attr('data-outerWidth');
								}
								if(typeof t.attr('data-outerHeight') === 'undefined' || t.attr('data-outerHeight') === false || t.attr('data-outerHeight') === '') {
									var hMoveIn = t.outerHeight();
									t.attr('data-outerHeight',hMoveIn);
								} else {
									var hMoveIn = t.attr('data-outerHeight');
								}
								//t.css('width',wMoveIn);
								var pos = t.position();
								var left = pos.left;
								var top = pos.top;
								var tClass = t.attr('class');
								var ind = t.index();
								var hRel = t.parents('.camerarelative').outerHeight();
								var wRel = t.parents('.camerarelative').outerWidth();
								if(tClass.indexOf("fadeIn") != -1) {
									t.animate({opacity:0},0).css('visibility','visible').delay((time/lMoveIn)*(0.1*(ind-1))).animate({opacity:1},(time/lMoveIn)*0.15,easeMove);
								} else {
									t.css('visibility','visible');
								}
							});
						}

						$('.cameraContent.cameracurrent',fakeHover).show().addClass('cameravisible');
						if (lMoveInContent!=0){

							$('.cameraContent.cameracurrent .moveFromLeft, .cameraContent.cameracurrent .moveFromRight, .cameraContent.cameracurrent .moveFromTop, .cameraContent.cameracurrent .moveFromBottom, .cameraContent.cameracurrent .fadeIn, .cameraContent.cameracurrent .fadeFromLeft, .cameraContent.cameracurrent .fadeFromRight, .cameraContent.cameracurrent .fadeFromTop, .cameraContent.cameracurrent .fadeFromBottom',fakeHover).each(function(){
								if($(this).attr('data-easing')!=''){
									var easeMove = $(this).attr('data-easing');
								} else {
									var easeMove = easing;
								}
								var t = $(this);
								var pos = t.position();
								var left = pos.left;
								var top = pos.top;
								var tClass = t.attr('class');
								var ind = t.index();
								var thisH = t.outerHeight();
								if(tClass.indexOf("moveFromLeft") != -1) {
									t.css({'left':'-'+(w)+'px','right':'auto'});
									t.css('visibility','visible').delay((time/lMoveInContent)*(0.1*(ind-1))).animate({'left':pos.left},(time/lMoveInContent)*0.15,easeMove);
								} else if(tClass.indexOf("moveFromRight") != -1) {
									t.css({'left':w+'px','right':'auto'});
									t.css('visibility','visible').delay((time/lMoveInContent)*(0.1*(ind-1))).animate({'left':pos.left},(time/lMoveInContent)*0.15,easeMove);
								} else if(tClass.indexOf("moveFromTop") != -1) {
									t.css({'top':'-'+h+'px','bottom':'auto'});
									t.css('visibility','visible').delay((time/lMoveInContent)*(0.1*(ind-1))).animate({'top':pos.top},(time/lMoveInContent)*0.15,easeMove,function(){
										t.css({top:'auto',bottom:0});
									});
								} else if(tClass.indexOf("moveFromBottom") != -1) {
									t.css({'top':h+'px','bottom':'auto'});
									t.css('visibility','visible').delay((time/lMoveInContent)*(0.1*(ind-1))).animate({'top':pos.top},(time/lMoveInContent)*0.15,easeMove);
								} else if(tClass.indexOf("fadeFromLeft") != -1) {
									t.animate({opacity:0},0).css({'left':'-'+(w)+'px','right':'auto'});
									t.css('visibility','visible').delay((time/lMoveInContent)*(0.1*(ind-1))).animate({'left':pos.left,opacity:1},(time/lMoveInContent)*0.15,easeMove);
								} else if(tClass.indexOf("fadeFromRight") != -1) {
									t.animate({opacity:0},0).css({'left':(w)+'px','right':'auto'});
									t.css('visibility','visible').delay((time/lMoveInContent)*(0.1*(ind-1))).animate({'left':pos.left,opacity:1},(time/lMoveInContent)*0.15,easeMove);
								} else if(tClass.indexOf("fadeFromTop") != -1) {
									t.animate({opacity:0},0).css({'top':'-'+(h)+'px','bottom':'auto'});
									t.css('visibility','visible').delay((time/lMoveInContent)*(0.1*(ind-1))).animate({'top':pos.top,opacity:1},(time/lMoveInContent)*0.15,easeMove,function(){
										t.css({top:'auto',bottom:0});
									});
								} else if(tClass.indexOf("fadeFromBottom") != -1) {
									t.animate({opacity:0},0).css({'bottom':'-'+thisH+'px'});
									t.css('visibility','visible').delay((time/lMoveInContent)*(0.1*(ind-1))).animate({'bottom':'0',opacity:1},(time/lMoveInContent)*0.15,easeMove);
								} else if(tClass.indexOf("fadeIn") != -1) {
									t.animate({opacity:0},0).css('visibility','visible').delay((time/lMoveInContent)*(0.1*(ind-1))).animate({opacity:1},(time/lMoveInContent)*0.15,easeMove);
								} else {
									t.css('visibility','visible');
								}
							});
						}


						$('.cameraappended',target).remove();
						elem.removeClass('camerasliding');
							selector.eq(vis).hide();
							var barWidth = $('.camera_bar_cont',camera_thumbs_wrap).width(),
								barHeight = $('.camera_bar_cont',camera_thumbs_wrap).height(),
								radSum;
							if (loader != 'pie') {
								radSum = 0.05;
							} else {
								radSum = 0.005;
							}
							$('#'+pieID).animate({opacity:opts.loaderOpacity},200);
							u = setInterval(
								function(){
									if(elem.hasClass('stopped')){
										clearInterval(u);
									}
									if (loader != 'pie') {
										if(rad<=1.002 && !elem.hasClass('stopped') && !elem.hasClass('paused') && !elem.hasClass('hovered')){
											rad = (rad+radSum);
										} else if (rad<=1 && (elem.hasClass('stopped') || elem.hasClass('paused') || elem.hasClass('stopped') || elem.hasClass('hovered'))){
											rad = rad;
										} else {
											if(!elem.hasClass('stopped') && !elem.hasClass('paused') && !elem.hasClass('hovered')) {
												clearInterval(u);
												imgFake();
												$('#'+pieID).animate({opacity:0},200,function(){
													clearTimeout(setT);
													setT = setTimeout(canvasLoader,easedTime);
													nextSlide();
													opts.onStartLoading.call(this);
												});
											}
										}
										switch(barDirection){
											case 'leftToRight':
												$('#'+pieID).animate({'right':barWidth-(barWidth*rad)},(time*radSum),'linear');
												break;
											case 'rightToLeft':
												$('#'+pieID).animate({'left':barWidth-(barWidth*rad)},(time*radSum),'linear');
												break;
											case 'topToBottom':
												$('#'+pieID).animate({'bottom':barHeight-(barHeight*rad)},(time*radSum),'linear');
												break;
											case 'bottomToTop':
												$('#'+pieID).animate({'bottom':barHeight-(barHeight*rad)},(time*radSum),'linear');
												break;
										}

									} else {
										radNew = rad;
										ctx.clearRect(0,0,opts.pieDiameter,opts.pieDiameter);
										ctx.globalCompositeOperation = 'destination-over';
										ctx.beginPath();
										ctx.arc((opts.pieDiameter)/2, (opts.pieDiameter)/2, (opts.pieDiameter)/2-opts.loaderStroke,0,Math.PI*2,false);
										ctx.lineWidth = opts.loaderStroke;
										ctx.strokeStyle = opts.loaderBgColor;
										ctx.stroke();
										ctx.closePath();
										ctx.globalCompositeOperation = 'source-over';
										ctx.beginPath();
										ctx.arc((opts.pieDiameter)/2, (opts.pieDiameter)/2, (opts.pieDiameter)/2-opts.loaderStroke,0,Math.PI*2*radNew,false);
										ctx.lineWidth = opts.loaderStroke-(opts.loaderPadding*2);
										ctx.strokeStyle = opts.loaderColor;
										ctx.stroke();
										ctx.closePath();

										if(rad<=1.002 && !elem.hasClass('stopped') && !elem.hasClass('paused') && !elem.hasClass('hovered')){
											rad = (rad+radSum);
										} else if (rad<=1 && (elem.hasClass('stopped') || elem.hasClass('paused') || elem.hasClass('hovered'))){
											rad = rad;
										} else {
											if(!elem.hasClass('stopped') && !elem.hasClass('paused') && !elem.hasClass('hovered')) {
												clearInterval(u);
												imgFake();
												$('#'+pieID+', .camera_canvas_wrap',camera_thumbs_wrap).animate({opacity:0},200,function(){
													clearTimeout(setT);
													setT = setTimeout(canvasLoader,easedTime);
													nextSlide();
													opts.onStartLoading.call(this);
												});
											}
										}
									}
								},time*radSum
							);
						}

				}



			if (fx != 'kenburns') {
				if(fx=='scrollLeft' || fx=='scrollRight' || fx=='scrollTop' || fx=='scrollBottom' || fx=='scrollHorz'){
					opts.onStartTransition.call(this);
					easedTime = 0;
					tApp.delay((((transPeriod+difference)/blocks)*delay[index]*couples)*0.5).css({
							'display' : 'block',
							'height': height,
							'margin-left': marginLeft,
							'margin-top': marginTop,
							'width': width
						}).animate({
							'height': Math.floor((h/rows)+addTop+1),
							'margin-top' : 0,
							'margin-left' : 0,
							'width' : Math.floor((w/cols)+addLeft+1)
						},(transPeriod-difference),easing,cameraeased);
					selector.eq(vis).delay((((transPeriod+difference)/blocks)*delay[index]*couples)*0.5).animate({
							'margin-left': marginLeft*(-1),
							'margin-top': marginTop*(-1)
						},(transPeriod-difference),easing,function(){
							$(this).css({'margin-top' : 0,'margin-left' : 0});
						});
				} else {
					opts.onStartTransition.call(this);
					easedTime = parseFloat(transPeriod)+parseFloat(difference);
					if(slideOn=='next'){
						tApp.delay((((transPeriod+difference)/blocks)*delay[index]*couples)*0.5).css({
								'display' : 'block',
								'height': height,
								'margin-left': marginLeft,
								'margin-top': marginTop,
								'width': width,
								'opacity' : opacityOnGrid
							}).animate({
								'height': Math.floor((h/rows)+addTop+1),
								'margin-top' : 0,
								'margin-left' : 0,
								'opacity' : 1,
								'width' : Math.floor((w/cols)+addLeft+1)
							},(transPeriod-difference),easing,cameraeased);
					} else {
						selector.eq(slideI).show().css('z-index','999').addClass('cameracurrent');
						selector.eq(vis).css('z-index','1').removeClass('cameracurrent');
						$('.cameraContent',fakeHover).eq(slideI).addClass('cameracurrent');
						$('.cameraContent',fakeHover).eq(vis).removeClass('cameracurrent');
						tApp.delay((((transPeriod+difference)/blocks)*delay[index]*couples)*0.5).css({
								'display' : 'block',
								'height': Math.floor((h/rows)+addTop+1),
								'margin-top' : 0,
								'margin-left' : 0,
								'opacity' : 1,
								'width' : Math.floor((w/cols)+addLeft+1)
							}).animate({
								'height': height,
								'margin-left': marginLeft,
								'margin-top': marginTop,
								'width': width,
								'opacity' : opacityOnGrid
							},(transPeriod-difference),easing,cameraeased);
					}
				}
			} else {
				opts.onStartTransition.call(this);
					easedTime = 0;
					tApp.delay((((transPeriod+difference)/blocks)*delay[index]*couples)*0.5).css({
							'display' : 'block',
							'margin-left': marginLeft,
							'margin-top': marginTop,
						}).animate({
							'margin-top' : 0,
							'margin-left' : 0,
						},(transPeriod-difference),easing,cameraeased);
						
					// selector.eq(vis).animate({
							// 'margin-left': '-50px',
							// 'margin-top': '-50px'
						// },(transPeriod-difference),easing,function(){
							// $(this).css({'margin-top' : 0,'margin-left' : 0});
						// });
					var dirh = Math.random()<.5;
					if (dirh) {
						var left = '0';
						var right = 'auto';
					} else {
						var left = 'auto';
						var right = '0';
					}

					var dirv = Math.random()<.5;
					if (dirv) {
						var top = '0';
						var bottom = 'auto';
					} else {
						var top = 'auto';
						var bottom = '0';
					}
					selector.eq(slideI).css({
						'opacity': '0',
						'display' : 'block',
						'z-index': selector.eq(vis).css('z-index')+1,
						// 'margin-top' : '0',
						// 'margin-left' : '0',
					}).animate({
							'opacity': '1',
						}, {
							duration: (transPeriod-difference),
							easing : 'linear',
							    complete: function() {
									cameraeased();
							  // $( this ).after( "<div>Animation complete.</div>" );
							},
							  start: function( now, fx ) {
								$(this).find('img').stop(true,true).css({
									'width': '100%', 
									'height': '100%',
									'margin-top' : '0',
									'margin-left' : '0',
									'left' : left,
									'right' : right,
									'top' : top,
									'bottom' : bottom,
									}).animate({
										'width': '130%', 
										'height': '130%',
										// 'margin-top' : '0',
										// 'margin-left' : '0',
									}, time*2);
							  }
						});
			}





			});




		}
	}


				if ($(prevNav).length){
					$(prevNav).on('click keypress', function(e){
						if ((e.type == 'keypress' && e.which == 13) || e.type == 'click') {
							prev();
						}
					});
				}

				

				if($(nextNav).length){
					$(nextNav).on('click keypress', function(e){
						if ((e.type == 'keypress' && e.which == 13) || e.type == 'click') {
							next();
						}
					});
				}

				function prev() {
					if(!elem.hasClass('camerasliding')){
						var idNum = parseFloat($('.cameraSlide.cameracurrent',target).index());
						clearInterval(u);
						imgFake();
						$('#'+pieID+', .camera_canvas_wrap',wrap).animate({opacity:0},0);
						canvasLoader();
						if(idNum!=0){
							nextSlide(idNum);
						} else {
							nextSlide(amountSlide);
					   }
					   opts.onStartLoading.call(this);
					}
				}

				function next() {
					if(!elem.hasClass('camerasliding')){
						var idNum = parseFloat($('.cameraSlide.cameracurrent',target).index());
						clearInterval(u);
						imgFake();
						$('#'+pieID+', .camera_canvas_wrap',camera_thumbs_wrap).animate({opacity:0},0);
						canvasLoader();
						if(idNum==amountSlide-1){
							nextSlide(1);
						} else {
							nextSlide(idNum+2);
					   }
					   opts.onStartLoading.call(this);
					}
				}
/*==================== Touch swipe functions =================*/

// method to get the touch device interaction
	// TOUCH-EVENTS SINGLE-FINGER SWIPE-SENSING JAVASCRIPT
	// Courtesy of PADILICIOUS.COM and MACOSXAUTOMATION.COM
	// win8 : MSPointerDown // TODO : check if it works
	
	var triggerElementID = null; // this variable is used to identity the triggering element
	var fingerCount = 0;
	var startX = 0;
	var startY = 0;
	var curX = 0;
	var curY = 0;
	var deltaX = 0;
	var deltaY = 0;
	var horzDiff = 0;
	var vertDiff = 0;
	var minLength = 72; // the shortest distance the user may swipe
	var swipeLength = 0;
	var swipeAngle = null;
	var swipeDirection = null;
	var initialPosX = null;
	var initialPosY = null;
	var imageMarginLeft = 0;
	var imageMarginTop = 0;
	
	// The 4 Touch Event Handlers
	
	// NOTE: the touchStart handler should also receive the ID of the triggering element
	// make sure its ID is passed in the event call placed in the element declaration, like:

	function touchStart(event) {
		// disable the standard ability to select the touched object
		// event.preventDefault();
		// get the total number of fingers touching the screen
		fingerCount = event.touches.length;
		// since we're looking for a swipe (single finger) and not a gesture (multiple fingers),
		// check that only one finger was used
		if ( fingerCount == 1 ) {
			// get the coordinates of the touch
			startX = event.touches[0].pageX;
			startY = event.touches[0].pageY;
			// store the triggering element ID
			triggerElementID = '';
		} else {
			// more than one finger touched so cancel
			touchCancel(event);
		}
	}

	function touchMove(event) {
		// event.preventDefault();
		if ( event.touches.length == 1 ) {
			curX = event.touches[0].pageX;
			curY = event.touches[0].pageY;
		} else {
			touchCancel(event);
		}
	}
	
	function touchEnd(event) {
		// event.preventDefault();
		// check to see if more than one finger was used and that there is an ending coordinate
		if ( fingerCount == 1 && curX != 0 ) {
			// use the Distance Formula to determine the length of the swipe
			swipeLength = Math.round(Math.sqrt(Math.pow(curX - startX,2) + Math.pow(curY - startY,2)));
			// if the user swiped more than the minimum length, perform the appropriate action
			if ( swipeLength >= minLength) {
				caluculateAngle();
				determineSwipeDirection();
				processingRoutine();
				touchCancel(event); // reset the variables
			} else {
				touchCancel(event);
			}
		} else {
			touchCancel(event);
		}
	}

	function touchCancel(event) {
		// reset the variables back to default values
		fingerCount = 0;
		startX = 0;
		startY = 0;
		curX = 0;
		curY = 0;
		deltaX = 0;
		deltaY = 0;
		horzDiff = 0;
		vertDiff = 0;
		swipeLength = 0;
		swipeAngle = null;
		swipeDirection = null;
		triggerElementID = null;
		initialPosX = null;
		initialPosY = null;
	}
	
	function caluculateAngle() {
		var X = startX-curX;
		var Y = curY-startY;
		var Z = Math.round(Math.sqrt(Math.pow(X,2)+Math.pow(Y,2))); //the distance - rounded - in pixels
		var r = Math.atan2(Y,X); //angle in radians (Cartesian system)
		swipeAngle = Math.round(r*180/Math.PI); //angle in degrees
		if ( swipeAngle < 0 ) { swipeAngle =  360 - Math.abs(swipeAngle); }
	}
	
	function determineSwipeDirection() {
		if ( (swipeAngle <= 45) && (swipeAngle >= 0) ) {
			swipeDirection = 'left';
		} else if ( (swipeAngle <= 360) && (swipeAngle >= 315) ) {
			swipeDirection = 'left';
		} else if ( (swipeAngle >= 135) && (swipeAngle <= 225) ) {
			swipeDirection = 'right';
		} else if ( (swipeAngle > 45) && (swipeAngle < 135) ) {
			swipeDirection = 'down';
		} else {
			swipeDirection = 'up';
		}
	}
	
	function processingRoutine() {
		if ( swipeDirection == 'left' ) {
			if(!elem.hasClass('camerasliding')){
				var idNum = parseFloat($('.cameraSlide.cameracurrent',target).index());
				clearInterval(u);
				imgFake();
				$('#'+pieID+', .camera_canvas_wrap',camera_thumbs_wrap).animate({opacity:0},0);
				canvasLoader();
				if(idNum==amountSlide-1){
					nextSlide(1);
				} else {
					nextSlide(idNum+2);
				}
				opts.onStartLoading.call(this);
			}
		} else if ( swipeDirection == 'right' ) {
			if(!elem.hasClass('camerasliding')){
				var idNum = parseFloat($('.cameraSlide.cameracurrent',target).index());
				clearInterval(u);
				imgFake();
				$('#'+pieID+', .camera_canvas_wrap',camera_thumbs_wrap).animate({opacity:0},0);
				canvasLoader();
				if(idNum!=0){
					nextSlide(idNum);
				} else {
					nextSlide(amountSlide);
				}
				opts.onStartLoading.call(this);
			}
		}
	}

/*===================== End touch swipe =======================*/

				if(isMobile()){
					fakeHover[0].addEventListener("touchstart", touchStart, false);
					fakeHover[0].addEventListener("touchmove", touchMove, false);
					fakeHover[0].addEventListener("touchend", touchEnd, false);
					fakeHover[0].addEventListener("touchcancel", touchCancel, false);
				}

				if($(pagination).length){
					$('.camera_pag li',wrap).on('click keypress', function(e){
						if ((e.type == 'keypress' && e.which == 13) || e.type == 'click') {
							if(!elem.hasClass('camerasliding')){
								var idNum = parseFloat($(this).index());
								var curNum = parseFloat($('.cameraSlide.cameracurrent',target).index());
								if(idNum!=curNum) {
									clearInterval(u);
									imgFake();
									$('#'+pieID+', .camera_canvas_wrap',camera_thumbs_wrap).animate({opacity:0},0);
									canvasLoader();
									nextSlide(idNum+1);
									opts.onStartLoading.call(this);
								}
							}
						}
					});
				}

				if($(thumbs).length) {

					$('.pix_thumb img',thumbs).click(function(){
						if(!elem.hasClass('camerasliding')){
							var idNum = parseFloat($(this).parents('li').index());
							var curNum = parseFloat($('.cameracurrent',target).index());
							if(idNum!=curNum) {
								clearInterval(u);
								imgFake();
								$('#'+pieID+', .camera_canvas_wrap',camera_thumbs_wrap).animate({opacity:0},0);
								$('.pix_thumb',thumbs).removeClass('cameracurrent');
								$(this).parents('li').addClass('cameracurrent');
								canvasLoader();
								nextSlide(idNum+1);
								thumbnailPos();
								opts.onStartLoading.call(this);
							}
						}
					});

					$('.camera_thumbs_cont .camera_prevThumbs',camera_thumbs_wrap).hover(function(){
						$(this).stop(true,false).animate({opacity:1},250);
					},function(){
						$(this).stop(true,false).animate({opacity:.7},250);
					});
					$('.camera_prevThumbs',camera_thumbs_wrap).click(function(){
						var sum = 0,
							wTh = $(thumbs).outerWidth(),
							offUl = $('ul', thumbs).offset().left,
							offDiv = $('> div', thumbs).offset().left,
							ulLeft = offDiv-offUl;
							$('.camera_visThumb',thumbs).each(function(){
								var tW = $(this).outerWidth();
								sum = sum+tW;
							});
							if(ulLeft-sum>0){
								$('ul', thumbs).animate({'margin-left':'-'+(ulLeft-sum)+'px'},500,thumbnailVisible);
							} else {
								$('ul', thumbs).animate({'margin-left':0},500,thumbnailVisible);
							}
					});

					$('.camera_thumbs_cont .camera_nextThumbs',camera_thumbs_wrap).hover(function(){
						$(this).stop(true,false).animate({opacity:1},250);
					},function(){
						$(this).stop(true,false).animate({opacity:.7},250);
					});
					$('.camera_nextThumbs',camera_thumbs_wrap).click(function(){
						var sum = 0,
							wTh = $(thumbs).outerWidth(),
							ulW = $('ul', thumbs).outerWidth(),
							offUl = $('ul', thumbs).offset().left,
							offDiv = $('> div', thumbs).offset().left,
							ulLeft = offDiv-offUl;
							$('.camera_visThumb',thumbs).each(function(){
								var tW = $(this).outerWidth();
								sum = sum+tW;
							});
							if(ulLeft+sum+sum<ulW){
								$('ul', thumbs).animate({'margin-left':'-'+(ulLeft+sum)+'px'},500,thumbnailVisible);
							} else {
								$('ul', thumbs).animate({'margin-left':'-'+(ulW-wTh)+'px'},500,thumbnailVisible);
							}
					});

				}



}

window.Slideshowck = Slideshowck;
})(jQuery);

;(function($){$.fn.cameraStop = function() {
	var wrap = $(this),
		elem = $('.camera_src',wrap),
		pieID = 'pie_'+wrap.index();
	elem.addClass('stopped');
	if($('.camera_showcommands').length) {
		var camera_thumbs_wrap = $('.camera_thumbs_wrap',wrap);
	} else {
		var camera_thumbs_wrap = wrap;
	}
}
})(jQuery);

;(function($){$.fn.cameraPause = function() {
	var wrap = $(this);
	var elem = $('.camera_src',wrap);
	elem.addClass('paused');
}
})(jQuery);

;(function($){$.fn.cameraResume = function() {
	var wrap = $(this);
	var elem = $('.camera_src',wrap);
	if(typeof autoAdv === 'undefined' || autoAdv!==true){
		elem.removeClass('paused');
	}
}
})(jQuery);