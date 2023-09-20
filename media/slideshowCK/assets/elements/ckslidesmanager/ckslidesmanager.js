/**
 * @copyright	Copyright (C) 2012 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Slideshow CK
 * @license		GNU/GPL
 * */

function ckSelectFile(file, field) {
	if (! field) {
		alert('ERROR : no field given in the function ckSelectFile');
		return;
	}
	$ck('#'+field).val(file).trigger('change');
	ckUpdateThumbnail(file, '#'+field);
}

// pour gestion editeur d'images
function ckInsertMedia(text, editor) {
	var valeur = jQuery(text).attr('src');
	jQuery('#'+editor).val(valeur);
	ckUpdateThumbnail(valeur, '#'+editor);
}

function ckUpdateThumbnail(imgsrc, editor) {
	var slideimg = jQuery(editor).parent().parent().find('img');
	var testurl = 'http';
	if (imgsrc.toLowerCase().indexOf(testurl.toLowerCase()) != -1) {
		slideimg.attr('src', imgsrc);
	} else {
		slideimg.attr('src', SLIDESHOWCK.URIROOTABS + imgsrc);
	}
}

function ckAddSlide(slide, position) {
	if (! slide) slide = [];
	if (! position) position = false;
	var imgname = slide['imgname'] || '';
	var imgcaption = slide['imgcaption'] || '';
	var imgthumb = slide['imgthumb'] || '';
	if (!imgthumb) {
		imgthumb = SLIDESHOWCK.URIROOTABS + 'media/com_slideshowck/images/unknown.png';
	} else {
		imgthumb = SLIDESHOWCK.URIROOTABS + imgname;
	}
	var imglink = slide['imglink'] || '';
	var imgtarget = slide['imgtarget'] || '';
	var videoautoplay = slide['videoautoplay'] || '';
	var videoloop = slide['videoloop'] || '';
	var videocontrols = slide['videocontrols'] || '';
	var imgvideo = slide['imgvideo'] || '';
	var slideselect = slide['slideselect'] || '';
	var imgalignment = slide['imgalignment'] || '';
	var articleid = slide['slidearticleid'] || '';
	var pagebuilderckid = slide['slidepageid'] || '';
	var imgtime = slide['imgtime'] || '';
	var articlename = slide['slidearticlename'] || '';
	var pagebuilderckname = slide['slidepagename'] || '';
	var imgtitle = slide['imgtitle'] || '';
	var state = slide['state'] || '';
	var startdate = slide['startdate'] || '';
	var enddate = slide['enddate'] || '';
	var texttype = slide['texttype'] || 'custom';

	imgcaption = imgcaption.replace(/\|dq\|/g, "&quot;");
	if (!imglink)
		imglink = '';
	if (!imgvideo)
		imgvideo = '';
	if (!imgtarget || imgtarget == 'default') {
		imgtarget = '';
		imgtargetoption = '<option value="default" selected="selected">' + Joomla.JText._('SLIDESHOWCK_DEFAULT', 'default') + '</option><option value="_parent">' + Joomla.JText._('SLIDESHOWCK_SAMEWINDOW', 'same window') + '</option><option value="_blank">' + Joomla.JText._('SLIDESHOWCK_NEWWINDOW', 'new window') + '</option><option value="lightbox">' + Joomla.JText._('SLIDESHOWCK_LIGHTBOX', 'in a Lightbox') + '</option>';
	} else {
		if (imgtarget == '_parent') {
			imgtargetoption = '<option value="default">' + Joomla.JText._('SLIDESHOWCK_DEFAULT', 'default') + '</option><option value="_parent" selected="selected">' + Joomla.JText._('SLIDESHOWCK_SAMEWINDOW', 'same window') + '</option><option value="_blank">' + Joomla.JText._('SLIDESHOWCK_NEWWINDOW', 'new window') + '</option><option value="lightbox">' + Joomla.JText._('SLIDESHOWCK_LIGHTBOX', 'in a Lightbox') + '</option>';
		} else if (imgtarget == 'lightbox') {
			imgtargetoption = '<option value="default">' + Joomla.JText._('SLIDESHOWCK_DEFAULT', 'default') + '</option><option value="_parent">' + Joomla.JText._('SLIDESHOWCK_SAMEWINDOW', 'same window') + '</option><option value="_blank">' + Joomla.JText._('SLIDESHOWCK_NEWWINDOW', 'new window') + '</option><option value="lightbox" selected="selected">' + Joomla.JText._('SLIDESHOWCK_LIGHTBOX', 'in a Lightbox') + '</option>';
		} else {
			imgtargetoption = '<option value="default">' + Joomla.JText._('SLIDESHOWCK_DEFAULT', 'default') + '</option><option value="_parent">' + Joomla.JText._('SLIDESHOWCK_SAMEWINDOW', 'same window') + '</option><option value="_blank" selected="selected">' + Joomla.JText._('SLIDESHOWCK_NEWWINDOW', 'new window') + '</option><option value="lightbox">' + Joomla.JText._('SLIDESHOWCK_LIGHTBOX', 'in a Lightbox') + '</option>';
		}
	}
	if (!videoautoplay || videoautoplay == '0') {
		videoautoplay = '';
		ckslidevideoautoplayoption = '<option value="0" selected="selected">' + Joomla.JText._('JNO', 'No') + '</option>'
									+'<option value="1">' + Joomla.JText._('JYES', 'Yes') + '</option>';
	} else {
		ckslidevideoautoplayoption = '<option value="0">' + Joomla.JText._('JNO', 'No') + '</option>'
									+'<option value="1" selected="selected">' + Joomla.JText._('JYES', 'Yes') + '</option>';
	}
	if (!videoloop || videoloop == '0') {
		videoloop = '';
		ckslidevideoloopoption = '<option value="0" selected="selected">' + Joomla.JText._('JNO', 'No') + '</option>'
									+'<option value="1">' + Joomla.JText._('JYES', 'Yes') + '</option>';
	} else {
		ckslidevideoloopoption = '<option value="0">' + Joomla.JText._('JNO', 'No') + '</option>'
									+'<option value="1" selected="selected">' + Joomla.JText._('JYES', 'Yes') + '</option>';
	}
	if (videocontrols == '0') {
		videocontrols = '';
		ckslidevideocontrolsoption = '<option value="0" selected="selected">' + Joomla.JText._('JNO', 'No') + '</option>'
									+'<option value="1">' + Joomla.JText._('JYES', 'Yes') + '</option>';
	} else {
		ckslidevideocontrolsoption = '<option value="0">' + Joomla.JText._('JNO', 'No') + '</option>'
									+'<option value="1" selected="selected">' + Joomla.JText._('JYES', 'Yes') + '</option>';
	}
	if (!slideselect) {
		slideselect = '';
		slideselectoption = '<option value="image" selected="selected">' + Joomla.JText._('SLIDESHOWCK_IMAGE', 'Image') + '</option><option value="video">' + Joomla.JText._('SLIDESHOWCK_VIDEO', 'Video') + '</option>';
	} else {
		if (slideselect == 'image') {
			slideselectoption = '<option value="image" selected="selected">' + Joomla.JText._('SLIDESHOWCK_IMAGE', 'Image') + '</option><option value="video">' + Joomla.JText._('SLIDESHOWCK_VIDEO', 'Video') + '</option>';
		} else {
			slideselectoption = '<option value="image">' + Joomla.JText._('SLIDESHOWCK_IMAGE', 'Image') + '</option><option value="video" selected="selected">' + Joomla.JText._('SLIDESHOWCK_VIDEO', 'Video') + '</option>';
		}
	}

	if (!imgalignment) {
		imgalignment = '';
		imgdataalignmentoption = '<option value="default" selected="selected">Default</option>'
				+ '<option value="topLeft">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
				+ '<option value="topCenter">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
				+ '<option value="topRight">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
				+ '<option value="centerLeft">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
				+ '<option value="center">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
				+ '<option value="centerRight">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
				+ '<option value="bottomLeft">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
				+ '<option value="bottomCenter">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
				+ '<option value="bottomRight">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
	} else {
		if (imgalignment == 'topLeft') {
			imgdataalignmentoption = '<option value="default">Default</option>'
					+ '<option value="topLeft" selected="selected">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
					+ '<option value="topCenter">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
					+ '<option value="topRight">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
					+ '<option value="centerLeft">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
					+ '<option value="center">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
					+ '<option value="centerRight">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
					+ '<option value="bottomLeft">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
					+ '<option value="bottomCenter">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
					+ '<option value="bottomRight">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
		} else if (imgalignment == 'topCenter') {
			imgdataalignmentoption = '<option value="default">Default</option>'
					+ '<option value="topLeft">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
					+ '<option value="topCenter" selected="selected">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
					+ '<option value="topRight">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
					+ '<option value="centerLeft">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
					+ '<option value="center">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
					+ '<option value="centerRight">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
					+ '<option value="bottomLeft">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
					+ '<option value="bottomCenter">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
					+ '<option value="bottomRight">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
		} else if (imgalignment == 'topRight') {
			imgdataalignmentoption = '<option value="default">Default</option>'
					+ '<option value="topLeft">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
					+ '<option value="topCenter">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
					+ '<option value="topRight" selected="selected">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
					+ '<option value="centerLeft">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
					+ '<option value="center">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
					+ '<option value="centerRight">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
					+ '<option value="bottomLeft">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
					+ '<option value="bottomCenter">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
					+ '<option value="bottomRight">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
		} else if (imgalignment == 'centerLeft') {
			imgdataalignmentoption = '<option value="default">Default</option>'
					+ '<option value="topLeft">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
					+ '<option value="topCenter">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
					+ '<option value="topRight">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
					+ '<option value="centerLeft" selected="selected">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
					+ '<option value="center">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
					+ '<option value="centerRight">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
					+ '<option value="bottomLeft">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
					+ '<option value="bottomCenter">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
					+ '<option value="bottomRight">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
		} else if (imgalignment == 'center') {
			imgdataalignmentoption = '<option value="default">Default</option>'
					+ '<option value="topLeft">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
					+ '<option value="topCenter">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
					+ '<option value="topRight">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
					+ '<option value="centerLeft">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
					+ '<option value="center" selected="selected">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
					+ '<option value="centerRight">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
					+ '<option value="bottomLeft">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
					+ '<option value="bottomCenter">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
					+ '<option value="bottomRight">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
		} else if (imgalignment == 'centerRight') {
			imgdataalignmentoption = '<option value="default">Default</option>'
					+ '<option value="topLeft">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
					+ '<option value="topCenter">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
					+ '<option value="topRight">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
					+ '<option value="centerLeft">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
					+ '<option value="center">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
					+ '<option value="centerRight" selected="selected">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
					+ '<option value="bottomLeft">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
					+ '<option value="bottomCenter">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
					+ '<option value="bottomRight">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
		} else if (imgalignment == 'bottomLeft') {
			imgdataalignmentoption = '<option value="default">Default</option>'
					+ '<option value="topLeft">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
					+ '<option value="topCenter">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
					+ '<option value="topRight">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
					+ '<option value="centerLeft">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
					+ '<option value="center">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
					+ '<option value="centerRight">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
					+ '<option value="bottomLeft" selected="selected">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
					+ '<option value="bottomCenter">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
					+ '<option value="bottomRight">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
		} else if (imgalignment == 'bottomCenter') {
			imgdataalignmentoption = '<option value="default">Default</option>'
					+ '<option value="topLeft">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
					+ '<option value="topCenter">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
					+ '<option value="topRight">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
					+ '<option value="centerLeft">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
					+ '<option value="center">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
					+ '<option value="centerRight">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
					+ '<option value="bottomLeft">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
					+ '<option value="bottomCenter" selected="selected">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
					+ '<option value="bottomRight">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
		} else if (imgalignment == 'bottomRight') {
			imgdataalignmentoption = '<option value="default">Default</option>'
					+ '<option value="topLeft">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
					+ '<option value="topCenter">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
					+ '<option value="topRight">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
					+ '<option value="centerLeft">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
					+ '<option value="center">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
					+ '<option value="centerRight">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
					+ '<option value="bottomLeft">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
					+ '<option value="bottomCenter">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
					+ '<option value="bottomRight" selected="selected">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
		} else {
			imgdataalignmentoption = '<option value="default" selected="selected">Default</option>'
					+ '<option value="topLeft">' + Joomla.JText._('SLIDESHOWCK_TOPLEFT', 'top left') + '</option>'
					+ '<option value="topCenter">' + Joomla.JText._('SLIDESHOWCK_TOPCENTER', 'top center') + '</option>'
					+ '<option value="topRight">' + Joomla.JText._('SLIDESHOWCK_TOPRIGHT', 'top right') + '</option>'
					+ '<option value="centerLeft">' + Joomla.JText._('SLIDESHOWCK_MIDDLELEFT', 'center left') + '</option>'
					+ '<option value="center">' + Joomla.JText._('SLIDESHOWCK_CENTER', 'center') + '</option>'
					+ '<option value="centerRight">' + Joomla.JText._('SLIDESHOWCK_MIDDLERIGHT', 'center right') + '</option>'
					+ '<option value="bottomLeft">' + Joomla.JText._('SLIDESHOWCK_BOTTOMLEFT', 'bottom left') + '</option>'
					+ '<option value="bottomCenter">' + Joomla.JText._('SLIDESHOWCK_BOTTOMCENTER', 'bottom center') + '</option>'
					+ '<option value="bottomRight">' + Joomla.JText._('SLIDESHOWCK_BOTTOMRIGHT', 'bottom right') + '</option>';
		}
	}
	if (!state || state == '1') {
		state = '1';
		statetxt = 'ON';
	} else {
		state = '0';
		statetxt = 'OFF';
	}

	index = ckCheckIndex(0);
	var ckslide = jQuery('<li class="ckslide" id="ckslide' + index + '" />');

	ckslide.html('<div class="ckslidehandle"><div class="ckslidenumber">' + index + '</div></div>'
			+ '<div class="ckslidedelete cktip" title="' + Joomla.JText._('SLIDESHOWCK_REMOVE2', '') + '" onclick="javascript:ckRemoveSlide(jQuery(this).parent());"><i class="fas fa-times"></i></a></div>'
			+ '<div class="ckslidetoggle" data-state="' + state + '"><div class="ckslidetoggler">' + statetxt + '</div></div>'
			+ '<div class="ckslidecontainer">'
			+ '<div class="cksliderow"><div class="ckslideimgcontainer">'
			+ '<img src="' + imgthumb + '" width="64" height="64" onclick="ckCallImageManagerPopup(\'ckslideimgname' + index + '\')"/></div>'

			+ '<div class="ckslideimgnamewrap ckbutton-group">'
				+ '<input name="ckslideimgname' + index + '" id="ckslideimgname' + index + '" class="ckslideimgname" type="text" value="' + imgname + '" onchange="javascript:ckUpdateThumbnail(this.value, this);" />'
				+ '<a class="ckbutton cktip" onclick="ckCallImageManagerPopup(\'ckslideimgname' + index + '\')" href="javascript:void(0)" title="' + Joomla.JText._('SLIDESHOWCK_SELECTIMAGE', 'select image') + '"><i class="fas fa-edit"></i></a></div>'
			+ '</div>'

			+ '<div class="cksliderow2">'
			// + '<span class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_USETOSHOW', 'Display') + '</span><select class="ckslideselect">' + slideselectoption + '</select>'
			+ '<span><i class="fas fa-hourglass-half cktip" title="' + Joomla.JText._('SLIDESHOWCK_SLIDETIME', 'enter a specific time value for this slide, else it will be the default time') + '" style="color: #555;font-size: 16px;padding: 5px;"></i><input name="ckslideimgtime' + index + '" class="ckslideimgtime" type="text" value="' + imgtime + '" /></span><span>ms</span>'
			+ '</div>'
			
			+ '<div class="cksliderow"><div id="ckslideaccordion' + index + '">'
			+ '<span class="ckbutton ckslideaccordeonbutton ckinterfacetablink" data-group="main" data-tab="tab_maintext">' + Joomla.JText._('SLIDESHOWCK_TEXT', 'Text') + '</span>'
			+ '<span class="ckbutton ckslideaccordeonbutton ckinterfacetablink" data-group="main" data-tab="tab_mainimage">' + Joomla.JText._('SLIDESHOWCK_IMAGE', 'Image') + '</span>'
			+ '<span class="ckbutton ckslideaccordeonbutton ckinterfacetablink" data-group="main" data-tab="tab_mainlink">' + Joomla.JText._('SLIDESHOWCK_LINK', 'Link') + '</span>'
			+ '<span class="ckbutton ckslideaccordeonbutton ckinterfacetablink" data-group="main" data-tab="tab_mainvideo">' + Joomla.JText._('SLIDESHOWCK_VIDEO', 'Video') + '</span>'
			+ '<span class="ckbutton ckslideaccordeonbutton ckinterfacetablink" data-group="main" data-tab="tab_maindates">' + Joomla.JText._('SLIDESHOWCK_DATES', 'Dates') + '</span>'
			+ '<div style="clear:both;"></div>'

			+ '<div class="ckslideaccordeoncontent ckinterfacetab" data-group="main" id="tab_maintext">'
				+ '<span class="ckbutton ckslideaccordeonbutton ckinterfacetablink ' + (texttype == 'custom' ? 'active open' : '') + '" data-allowclose="false" data-group="text" data-tab="tab_textcustom" data-value="custom">' + Joomla.JText._('SLIDESHOWCK_TEXT_CUSTOM', 'Custom text') + '</span>'
				+ '<span class="ckbutton ckslideaccordeonbutton ckinterfacetablink ' + (texttype == 'article' ? 'active open' : '') + '" data-allowclose="false" data-group="text" data-tab="tab_textarticle" data-value="article">' + Joomla.JText._('SLIDESHOWCK_ARTICLE', 'Article') + '</span>'
				+ '<span class="ckbutton ckslideaccordeonbutton ckinterfacetablink ' + (texttype == 'pagebuilderck' ? 'active open' : '') + '" data-allowclose="false" data-group="text" data-tab="tab_textpagebuilderck" data-value="pagebuilderck">' + Joomla.JText._('SLIDESHOWCK_PAGEBUILDERCK', 'Page Builder CK') + '</span>'
				+ '<div style="clear:both;"></div>'
				+ '<div class="ckslideaccordeoncontent ckinterfacetab ' + (texttype == 'custom' ? 'current' : '') + '" data-group="text" id="tab_textcustom">'
					+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_TITLE', 'Title') + '</span><input name="ckslidetitletext' + index + '" class="ckslidetitletext" type="text" value="' + imgtitle + '" /></div>'
					+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_CAPTION', 'Caption') + '</span><input name="ckslidecaptiontext' + index + '" class="ckslidecaptiontext" type="text" value="' + imgcaption + '" /></div>'
				+ '</div>'
				+ '<div class="ckslideaccordeoncontent ckinterfacetab ' + (texttype == 'article' ? 'current' : '') + '" data-group="text" id="tab_textarticle">'
					+ '<div class="cksliderow ckbutton-group" id="cksliderowarticle' + index + '"><label class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_ARTICLE_ID', 'Article ID') + '</label><input name="ckslidearticleid' + index + '" class="ckslidearticleid input-medium" id="ckslidearticleid' + index + '" style="width:20px" type="text" value="' + articleid + '" disabled="disabled" /><input name="ckslidearticlename' + index + '" class="ckslidearticlename input-medium" id="ckslidearticlename' + index + '" type="text" value="' + articlename + '" disabled="disabled" /><a id="ckslidearticlebuttonSelect" class="ckmodal ckbutton cktip" title="' + Joomla.JText._('SLIDESHOWCK_SELECT', 'Clear') + '" href="index.php?option=com_content&amp;layout=modal&amp;view=articles&amp;tmpl=component&amp;function=jSelectArticle_ckslidearticleid' + index + '&' + SLIDESHOWCK.TOKEN + '" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="fas fa-mouse-pointer"></i></a><a class="ckbutton" href="javascript:void(0)" onclick="document.getElementById(\'ckslidearticleid' + index + '\').value=\'\';document.getElementById(\'ckslidearticlename' + index + '\').value=\'\';document.getElementById(\'ckslidearticlebuttonEdit' + index + '\').style.display=\'none\';">' + Joomla.JText._('SLIDESHOWCK_CLEAR', 'Clear') + '</a>'
					+ '<a id="ckslidearticlebuttonEdit' + index + '" class="ckbutton" href="javascript:void(0)" onclick="ckCallArticleEditionPopup(document.getElementById(\'ckslidearticleid' + index + '\').value)" ' + (articleid != '' ? '' : 'style="display:none;"') + '>'+Joomla.JText._('SLIDESHOWCK_EDIT', 'Edit')+'</a>'
					+'</div>'
				+ '</div>'
				+ '<div class="ckslideaccordeoncontent ckinterfacetab ' + (texttype == 'pagebuilderck' ? 'current' : '') + '" data-group="text" id="tab_textpagebuilderck">'
//					+ '<div class="cksliderow ckbutton-group" id="cksliderowpage' + index + '">'
//						+ '<label class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_PAGEBUILERCK_PAGE_ID', 'Page ID') + '</label>'
//						+ '<input name="ckslidepageid' + index + '" class="ckslidepageid input-medium" id="ckslidepageid' + index + '" style="width:20px" type="text" value="' + pagebuilderckid + '" disabled="disabled" />'
//						+ '<input name="ckslidepagename' + index + '" class="ckslidepagename input-medium" id="ckslidepagename' + index + '" type="text" value="' + pagebuilderckname + '" disabled="disabled" />'
//						+ '<a id="ckslidepagebuttonSelect' + index + '" class="ckmodal ckbutton cktip" title="' + Joomla.JText._('SLIDESHOWCK_SELECT', 'Clear') + '" href="index.php?option=com_pagebuilderck&amp;layout=modal&amp;view=pages&amp;tmpl=component&amp;function=jSelectPage_ckslidepageid' + index + '&' + SLIDESHOWCK.TOKEN + '" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="fas fa-mouse-pointer"></i></a>'
//						+ '<a class="ckbutton" href="javascript:void(0)" onclick="document.getElementById(\'ckslidepageid' + index + '\').value=\'\';document.getElementById(\'ckslidepagename' + index + '\').value=\'\';">' + Joomla.JText._('SLIDESHOWCK_CLEAR', 'Clear') + '</a>'
//						+ '<a id="ckslidepagebuttonEdit' + index + '" class="ckbutton" href="javascript:void(0)" onclick="ckCallPagebuilderckEditionPopup(document.getElementById(\'ckslidepageid' + index + '\').value)" style="' + (pagebuilderckid != '' ? 'display:inline-block;' : 'display:none;') + '">'+Joomla.JText._('SLIDESHOWCK_EDIT', 'Edit')+'</a>'
//					+'</div>'
					+'<div class="ckinfo"><i class="fas fa-info"></i><a href="https://www.joomlack.fr/en/joomla-extensions/slideshow-ck" target="_blank">' + Joomla.JText._('SLIDESHOWCK_ONLY_PRO') + '</a></div>'
				+ '</div>'

			
			+ '</div>'
			+ '<div class="ckslideaccordeoncontent ckinterfacetab" data-group="main" id="tab_mainimage">'
			+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_ALIGNEMENT_LABEL', 'Image alignment') + '</span><select name="ckslidedataalignmenttext' + index + '" class="ckslidedataalignmenttext" >' + imgdataalignmentoption + '</select></div>'
			+ '</div>'
			+ '<div class="ckslideaccordeoncontent ckinterfacetab" data-group="main" id="tab_mainlink">'
				
				+ '<div class="cksliderow">'
					+ '<div class="ckbutton-group">'
					+ '<label class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_LINK', 'Link url') + '</label><input id="ckslidelinktext' + index + '" name="ckslidelinktext' + index + '" class="ckslidelinktext" type="text" value="' + imglink + '" />'
					+ '<a class="ckbutton cktip" onclick="ckCallMenusSelectionPopup(\'ckslidelinktext' + index + '\')" href="javascript:void(0)" title="' + Joomla.JText._('SLIDESHOWCK_SELECT_LINK', 'select image') + '"><i class="fas fa-edit"></i></a>'
					+ '</div>'
				+ '</div>'
				+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_TARGET', 'Target') + '</span><select name="ckslidetargettext' + index + '" class="ckslidetargettext" >' + imgtargetoption + '</select></div>'
			+ '</div>'
			+ '<div class="ckslideaccordeoncontent ckinterfacetab" data-group="main" id="tab_mainvideo">'
			+ '<div class="cksliderow">'
				+ '<div class="ckbutton-group">'
				+' <label class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_VIDEOURL', 'Video url') + '</label>'
				+'<input id="ckslidevideotext' + index + '" name="ckslidevideotext' + index + '" class="ckslidevideotext" type="text" value="' + imgvideo + '" />'
				+'<a class="ckbutton cktip" title="' + Joomla.JText._('SLIDESHOWCK_SELECT', 'Clear') + '" href="javascript:void(0)" onclick="ckCallVideoManagerPopup(\'ckslidevideotext' + index + '\')"><i class="fas fa-edit"></i></a>'
				+'</div>'
			+ '</div>'
			+ '<div class="cksliderow">'
				+'<span class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_VIDEO_AUTOPLAY', 'Autoplay') + '</span><select name="ckslidevideoautoplay' + index + '" class="ckslidevideoautoplay" >' + ckslidevideoautoplayoption + '</select>'
			+'</div>'
	+ '<div class="cksliderow">'
				+'<span class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_VIDEO_LOOP', 'Loop') + '</span><select name="ckslidevideoloop' + index + '" class="ckslidevideoloop" >' + ckslidevideoloopoption + '</select>'
			+'</div>'
	+ '<div class="cksliderow">'
				+'<span class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_VIDEO_CONTROLS', 'Controls') + '</span><select name="ckslidevideocontrols' + index + '" class="ckslidevideocontrols" >' + ckslidevideocontrolsoption + '</select>'
			+'</div>'
			+ '</div>'
			+ '<div class="ckslideaccordeoncontent ckinterfacetab" data-group="main" id="tab_maindates">'
			+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_STARTDATE', 'Start date') + '</span><input name="ckslidestartdate' + index + '" class="ckslidestartdate ckdatepicker" type="text" value="' + startdate + '" /></div>'
			+ '<div class="cksliderow"><span class="ckslidelabel">' + Joomla.JText._('SLIDESHOWCK_ENDDATE', 'End date') + '</span><input name="ckslideenddate' + index + '" class="ckslideenddate ckdatepicker" type="text" value="' + enddate + '" /></div>'
			+ '</div>'
			+ '</div></div>'
			+ '</div><div style="clear:both;"></div>');

	if (position == 'top') {
		jQuery('#ckslideslist').prepend(ckslide);
	} else {
		jQuery('#ckslideslist').append(ckslide);
	}

	script = document.createElement("script");
	script.setAttribute('type', 'text/javascript');
	script.text = "function jSelectArticle_ckslidearticleid" + index + "(id, title, catid, object) {"
			+ "document.getElementById('ckslidearticleid" + index + "').value = id;"
			+ "document.getElementById('ckslidearticlename" + index + "').value = title;"
			+ "document.getElementById('ckslidearticlebuttonEdit" + index + "').style.display = 'inline-block';"
			+ "CKBox.close();"
			+ "}"
			+ "function jSelectPage_ckslidepageid" + index + "(id, type, title) {"
			+ "document.getElementById('ckslidepageid" + index + "').value = id;"
			+ "document.getElementById('ckslidepagename" + index + "').value = title;"
			+ "document.getElementById('ckslidepagebuttonEdit" + index + "').style.display = 'inline-block';"
			+ "CKBox.close();"
			+ "}";

	document.body.appendChild(script);

	ckStoreSlides();
	ckMakeSlidesSortable();

	CKBox.assign(jQuery('#ckslide' + index + ' a.ckmodal'), {
		parse: 'rel'
	});
//	create_tabs_in_slide(jQuery('#ckslide' + index));
	ckInitTabs(jQuery('#ckslide' + index), true);
	CKApi.Tooltip(jQuery('#ckslide' + index + ' .cktip'));
	jQuery('#ckslide' + index + ' .ckdatepicker').datepicker({"dateFormat": "d MM yy"});

	// add code to toggle the slide state
	jQuery('#ckslide' + index + ' .ckslidetoggle').click(function() {
		if (jQuery(this).attr('data-state') == '0') {
			jQuery(this).attr('data-state', '1');
			jQuery(this).find('.ckslidetoggler').text('ON');
		} else {
			jQuery(this).attr('data-state', '0');
			jQuery(this).find('.ckslidetoggler').text('OFF');
		}
	});
}

function ckCheckIndex(i) {
	while (jQuery('#ckslide' + i).length)
		i++;
	return i;
}


function ckRemoveSlide(slide) {
	if (confirm(Joomla.JText._('SLIDESHOWCK_REMOVE', 'Remove this slide') + ' ?')) {
		jQuery(slide).remove();
		ckStoreSlides();
	}
	jQuery('.cktooltip').remove();
}

function ckStoreSlides() {
	var i = 0;
	var slides = new Array();
	jQuery('#ckslideslist .ckslide').each(function(i, el) {
		el = jQuery(el);
		slide = new Object();
		slide['imgname'] = el.find('.ckslideimgname').val();
		slide['imgcaption'] = el.find('.ckslidecaptiontext').val();
		slide['imgcaption'] = slide['imgcaption'].replace(/"/g, "|dq|");
		slide['imgtitle'] = el.find('.ckslidetitletext').val();
		slide['imgtitle'] = slide['imgtitle'].replace(/"/g, "|dq|");
		slide['imgthumb'] = el.find('img').attr('src');
		slide['imglink'] = el.find('.ckslidelinktext').val();
		slide['imglink'] = slide['imglink'].replace(/"/g, "|dq|");
		slide['imgtarget'] = el.find('.ckslidetargettext').val();
		slide['videoautoplay'] = el.find('.ckslidevideoautoplay').val();
		slide['videoloop'] = el.find('.ckslidevideoloop').val();
		slide['videocontrols'] = el.find('.ckslidevideocontrols').val();
		slide['imgalignment'] = el.find('.ckslidedataalignmenttext').val();
		slide['imgvideo'] = el.find('.ckslidevideotext').val();
		// slide['slideselect'] = el.find('.ckslideselect').val();
		slide['slidearticleid'] = el.find('.ckslidearticleid').val();
		slide['slidepageid'] = el.find('.ckslidepageid').val();
		slide['slidearticlename'] = el.find('.ckslidearticlename').val();
		slide['slidepagename'] = el.find('.ckslidepagename').val();
		slide['imgtime'] = el.find('.ckslideimgtime').val();
		slide['state'] = el.find('.ckslidetoggle').attr('data-state');
		slide['startdate'] = el.find('.ckslidestartdate').val();
		slide['enddate'] = el.find('.ckslideenddate').val();
		slide['texttype'] = el.find('.ckbutton[data-group="text"].active').attr('data-value');
		slides[i] = slide;
		i++;
	});

	slides = JSON.stringify(slides);
	slides = slides.replace(/"/g, "|qq|");
	jQuery('#ckslides').val(slides);

}

function ckCallSlides() {
	var slides = jQuery.parseJSON(jQuery('#ckslides').val().replace(/\|qq\|/g, "\""));
	if (slides.length) {
		jQuery(slides).each(function(i, slide) {
			ckAddSlide(slide);
		});
	}
}


function ckMakeSlidesSortable() {
	jQuery("#ckslideslist").sortable({
//		placeholder: "ui-state-highlight",
		handle: ".ckslidehandle",
		items: ".ckslide",
		axis: "y",
		forcePlaceholderSize: true,
		forceHelperSize: true,
		dropOnEmpty: true,
		tolerance: "pointer",
		placeholder: "placeholder",
		connectWith: '',
		zIndex: 9999,
		update: function(event, ui) {
			ckRenumberSlides();
		},
		sort: function(event, ui) {
			jQuery(ui.placeholder).height(jQuery(ui.helper).height());
		}
	});
}

function ckRenumberSlides() {
	var index = 0;
	jQuery('.ckslide').each(function(i, slide) {
		jQuery('.ckslidenumber', jQuery(slide)).html(i);
		index++;
	});
}

jQuery(document).ready(function() {
	ckCallSlides();

	var script = document.createElement("script");
	script.setAttribute('type', 'text/javascript');
	script.text = "var SlideshowCK = {};"
			+ "SlideshowCK.submitbutton = Joomla.submitbutton;"
			+ "Joomla.submitbutton = function(task){"
			+ "ckStoreSlides();"
			+ "SlideshowCK.submitbutton(task);"
			+ "};"
			+ "jInsertEditorText = function(text, editor) {ckInsertMedia(text, editor)};";

	document.body.appendChild(script);
});
