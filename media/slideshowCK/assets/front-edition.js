/**
 * @copyright	Copyright (C) 2018 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Slideshow CK
 * @license		GNU/GPL
 * */

jQuery(document).ready(function() {
	ckFrontEdition();
});

var $ck = $ck || jQuery.noConflict();

// tool to improve the edition when viewing in front end
function ckFrontEdition() {
//	$ck('#options .accordion-heading .accordion-toggle > img, .ckslideaccordeonbutton img').each(function() {
//		var newsrc = $ck(this).attr('src').replace('../', JURI);
//		$ck(this).attr('src', newsrc);
//	});
	$ck('.form-horizontal .control-label').css('margin-right', '10px');
	$ck('.form-horizontal .controls').css('margin-left', '0');
}