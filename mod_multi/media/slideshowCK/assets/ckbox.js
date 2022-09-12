/**
 * @copyright	Copyright (C) 2015 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * @version		1.0.1 
 * */

CKBox = window.CKBox || {};

// load the CKBox only if this is a newer version or no one exists
if ( (CKBox.version && CKBox.version < '1.0.1') || ! CKBox.version) {

(function($) {
CKBox.version = '1.0.1'

CKBox.open = function(options) {
	var defaults = {
		id: '',
		handler: 'iframe',					// load external page or inline code : 'iframe' or 'inline'
		fullscreen: true,					// 
		size: {x: null, y: null},			// size of the box : {x: 800px, y: 500px}
		style: {padding: '0'},			// size of the box : {x: 800px, y: 500px}
		url: '',							// url or the external content
		content: '',						// html ID (without #) of the inline content
		closeText: '×',						// set the text for the close button
		headerHtml: '',						// add any code to the header
		footerHtml: '',						// ad any code to the footer
		onCKBoxLoaded: function() { }		//this callback is invoked when the transition effect ends
	}
	var options = $.extend(defaults, options);
	var modalclosebutton = options.closeText ? '<a class="ckboxmodal-button" href="javascript:void(0);" onclick="CKBox.close(this)">'+options.closeText+'</a>' : '';
	var i = $('.ckboxmodal').length+1;
	// ckboxmodal = $('#ckboxmodal'+i);
	var boxid = options.id ? options.id : 'ckboxmodal'+i;
	if ($('#'+boxid).length) $('#'+boxid).remove();
	if (options.handler == 'inline' && options.content && $('.ckboxmodal #' + options.content).length) {
		$('.ckboxmodal').each(function(j, box) {
			if ($(box).find('#' + options.content).length) {
				ckboxmodal = $(box);
				// $(box).show();
			}
		});
	} else {
	//	if (! $('#ckboxmodal').length) {
		var styles = '';
		if (options.size.x) styles += 'width:'+options.size.x+';';
		if (options.size.y) styles += 'height:'+(parseInt(options.size.y)+50)+'px;';
		if (options.size.x || options.size.y) options.fullscreen = false;
		if (! options.fullscreen) {
			styles += 'margin-left:-'+(parseInt(options.size.x)/2)+'px;';
			styles += 'margin-top:-'+((parseInt(options.size.y)+50)/2)+'px;';
			styles += 'left:50%;';
			styles += 'top:' + ($(window).scrollTop() + $(window).height()/2) + 'px';
		}

		if (styles) styles = 'style="' + styles + '"';
		var modalhtml = $(
			'<div id="'+boxid+'" data-index="'+i+'" class="ckboxmodal '+(options.fullscreen?'fullscreen':'')+'" '+styles+' data-sizex="' + options.size.x + '" data-sizey="' + options.size.y + '">'
				+'<div class="ckboxmodal-header"></div>'
				+'<div class="ckboxmodal-body" style="padding:'+options.style.padding+';"></div>'
				+'<div class="ckboxmodal-footer">'+modalclosebutton+'</div>'
			+'</div>');
		$(document.body).append(modalhtml);
		if (! $('.ckboxmodal-back').length) $(document.body).append('<div class="ckboxmodal-back" onclick="CKBox.close()"/>');
//	}
		ckboxmodal = $('#'+boxid);
		var ckboxmodalbody = ckboxmodal.find('.ckboxmodal-body');
		ckboxmodalbody.empty();
		ckboxmodal.find('.ckboxmodal-header').empty().append(options.headerHtml);
		ckboxmodal.find('.ckboxmodal-footer').empty().append(modalclosebutton).append(options.footerHtml);
		if (options.handler == 'inline') {
				if (options.content) {
					$('#ckboxmodalwrapper'+i).remove();
					$('#' + options.content).after('<div id="ckboxmodalwrapper'+i+'" />')
					ckboxmodalbody.append($('#' + options.content).show());
				}
		} else {
			ckboxmodalbody.append('<iframe id="' + boxid + '-iframe" class="ckwait" src="'+options.url+'" width="100%" height="auto" />');
		}
	}
	// if (!options.fullscreen) ckboxmodal.css('top', $(window).scrollTop()+10);
	CKBox.resize();
	ckboxmodal.show();
	$('.ckboxmodal-back').show();
	options.onCKBoxLoaded.call(this);
}

CKBox.close = function(button, aftersaveiframe) {
	if(! aftersaveiframe) aftersaveiframe = false;
	if (button) {
		if ($(button).hasClass('ckboxmodal')) {
			ckboxmodal = $(button);
		} else {
			ckboxmodal = $($(button).parents('.ckboxmodal')[0]);
		}
	} else {
		ckboxmodal = $('.ckboxmodal');
	}
	var i = ckboxmodal.attr('data-index');
	ckboxmodal.hide();
	if ($('.ckboxmodal').length < 2) {
		$('.ckboxmodal-back').hide();
	}
	if ($('#ckboxmodalwrapper'+i).length && !aftersaveiframe) {
		$('#ckboxmodalwrapper'+i).before(ckboxmodal.find('.ckboxmodal-body').children().first().hide());
	}
	if (aftersaveiframe) {
		ckboxmodal.find('iframe').load(function() {
			ckboxmodal.remove();
		});
	} else {
		ckboxmodal.remove();
	}
}

CKBox.resize = function() {
	var ckboxmodals = $('.ckboxmodal');
	ckboxmodals.each(function(i, ckboxmodal) {
		ckboxmodal = $(ckboxmodal);
		if (!ckboxmodal.length) return;

		var ckboxmodalbody = ckboxmodal.find('.ckboxmodal-body');
		var h = ckboxmodal.innerHeight() - ckboxmodal.find('.ckboxmodal-header').outerHeight() - ckboxmodal.find('.ckboxmodal-footer').outerHeight();
		ckboxmodalbody.css('height', h);
		// switch to fullscreen if bigger than screen
		if ($(window).width() - ckboxmodal.width() < 10) {
			if (!ckboxmodal.hasClass('fullscreen')) {
				ckboxmodal.addClass('fullscreen')
					.addClass('autofullscreen')
					.css('left', '')
					.css('top', '')
					.css('margin-left', '')
					.css('margin-top', '')
					.css('width', '')
					.css('height', '')
					.data('normalWidth', ckboxmodal.width());
			} else if (ckboxmodal.hasClass('autofullscreen')) {
				
	//			styles += 'margin-left:-'+(parseInt(options.size.x)/2)+'px;';
	//			styles += 'margin-top:-'+((parseInt(options.size.y)+50)/2)+'px;';
	//			styles += 'left:50%;';
	//			styles += 'top:' + ($(window).scrollTop() + $(window).height()/2) + 'px';
			}
		}
		if ($(window).width() > (parseInt(ckboxmodal.attr('data-sizex')) + 10) && ckboxmodal.hasClass('autofullscreen')) {
			console.log($(window).width());
			console.log(ckboxmodal.attr('data-sizex'));
			var sizex = ckboxmodal.attr('data-sizex');
			var sizey = ckboxmodal.attr('data-sizey');
			ckboxmodal.removeClass('fullscreen')
				.removeClass('autofullscreen')
				.css('left', '50%')
				.css('top', ($(window).scrollTop() + $(window).height()/2) + 'px')
				.css('margin-left', '-'+(parseInt(sizex)/2)+'px')
				.css('margin-top', '-'+((parseInt(sizey)+50)/2)+'px')
				.css('width', sizex)
				.css('height', sizey);
		}
	});
}

/* BC for SqueezeBox functions */
CKBox.initialize = function() {
	// not used
}

CKBox.assign = function (to, options) {
	var $options = options;
	$(to).click(function(e) {
		e.preventDefault();
		CKBox.launch(this, $options);
	});
}

CKBox.fromElement = function(from, options) {
	CKBox.launch(from, options);
}

CKBox.launch = function(from, options) {

	options.url = (($(from).length) ? ($(from).attr('href')) : from) || options.url || '';
	options.style = {padding: '10px'};

	if (options.parse !== false) options = CKBox.parseOtions(from, options);

	CKBox.open(options);
}

CKBox.parseOtions = function(from, opts) {
	var toParse = $(from).attr(opts.parse);

	newOptions = (new Function('return ' + toParse))(); // used to convert the string to object
	opts = $.extend(opts, newOptions);
	return opts;
}

})(jQuery);

/* Bind the modal resizing on page resize */
jQuery(window).bind('resize',function(){
	CKBox.resize();
});

}