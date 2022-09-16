<?php
/**
 * @copyright	Copyright (C) 2012-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Slideshow CK
 * @license		GNU/GPL
 * */

// no direct access
defined('_JEXEC') or die;

include_once JPATH_ROOT . '/administrator/components/com_slideshowck/helpers/defines.php';
include_once JPATH_ROOT . '/administrator/components/com_slideshowck/helpers/helper.php';

if (version_compare(JVERSION, '4', '<')) include_once dirname(__FILE__) . '/helper.php';
if (! defined('SLIDESHOWCK_PATH')) define('SLIDESHOWCK_PATH', JPATH_ROOT . '/administrator/components/com_slideshowck');

// load the items
$source = $params->get('source', 'slidesmanager');
if ($source != 'slidesmanager') {
	$sourceFile = JPATH_ROOT . '/plugins/slideshowck/' . strtolower($source) . '/helper/helper_' . strtolower($source) . '.php';
	if (! file_exists($sourceFile)) {
		echo '<p syle="color:red;">Error : File plugins/slideshowck/' . strtolower($source) . '/helper/helper_' . strtolower($source) . '.php not found !</p>';
		return;
	}
	include_once $sourceFile;
} else {
	include_once SLIDESHOWCK_PATH . '/helpers/source/' . $source . '.php';
}
// store the module ID in the params
$params->set('moduleid', $module->id);
$loaderClass = 'SlideshowckHelpersource' . ucfirst($source);
$items = $loaderClass::getItems($params);

// load items for B/C if the save action has not yet been triggered
if (version_compare(JVERSION, '4', '<')) require dirname(__FILE__) . '/legacy.php';

if (empty($items) || $items === false) {
	if ($params->get('debug', true) === true) echo '<p>SLIDESHOW CK : No items found.</p>';
	return;
}

if ($params->get('displayorder', 'normal') == 'shuffle')
	shuffle($items);

$doc = JFactory::getDocument();
JHTML::_("jquery.framework", true);
if ($params->get('loadjqueryeasing', '1')) {
	$doc->addScript(SLIDESHOWCK_MEDIA_URI . '/assets/jquery.easing.1.3.js');
}

$debug = false;
if ($debug) {
	$doc->addScript(SLIDESHOWCK_MEDIA_URI . '/assets/camera.js');
} else {
	$doc->addScript(SLIDESHOWCK_MEDIA_URI . '/assets/camera.min.js?ver=' . SLIDESHOWCK_VERSION);
}

$theme = $params->get('theme', 'default');
$langdirection = $doc->getDirection();

if ($theme == 'default' && file_exists(JPATH_ROOT . '/templates/' . $doc->template . '/css/camera.css')) {
	if ($langdirection == 'rtl' && file_exists(JPATH_ROOT . '/templates/' . $doc->template . '/css/camera_rtl.css')) {
		$cssfilesrc = 'templates/' . $doc->template . '/css/camera_rtl.css';
	} else {
		$cssfilesrc = 'templates/' . $doc->template . '/css/camera.css';
	}
} else {
	if ($langdirection == 'rtl' && file_exists(JPATH_ROOT . '/modules/mod_slideshowck/themes/' . $theme . '/css/camera_rtl.css')) {
		$cssfilesrc = 'modules/mod_slideshowck/themes/' . $theme . '/css/camera_rtl.css';
	} else {
		$cssfilesrc = 'modules/mod_slideshowck/themes/' . $theme . '/css/camera.css';
	}
}
$doc->addStylesheet(JUri::root(true) . '/' . $cssfilesrc);

// set the navigation variables
if (count($items) == 1) { // for only one slide, no navigation, no button
	$navigation = "navigationHover: false,
			mobileNavHover: false,
			navigation: false,
			playPause: false,";
} else {
	switch ($params->get('navigation', '2')) {
		case 0:
			// aucune
			$navigation = "navigationHover: false,
				mobileNavHover: false,
				navigation: false,
				playPause: false,";
			break;
		case 1:
			// toujours
			$navigation = "navigationHover: false,
				mobileNavHover: false,
				navigation: true,
				playPause: true,";
			break;
		case 2:
		default:
			// on mouseover
			$navigation = "navigationHover: true,
				mobileNavHover: true,
				navigation: true,
				playPause: true,";
			break;
	}
}

$autoAdvance = (count($items) > 1) ? $params->get('autoAdvance', '1') : '0';
// load the slideshow script
$js = "
		jQuery(document).ready(function(){
			new Slideshowck('#camera_wrap_" . $module->id . "', {
				height: '" . $params->get('height', '400') . "',
				minHeight: '" . $params->get('minheight', '150') . "',
				pauseOnClick: false,
				hover: " . $params->get('hover', '1') . ",
				fx: '" . implode(",", $params->get('effect', array('linear'))) . "',
				loader: '" . $params->get('loader', 'pie') . "',
				pagination: " . $params->get('pagination', '1') . ",
				thumbnails: " . $params->get('thumbnails', '1') . ",
				thumbheight: " . $params->get('thumbnailheight', '100') . ",
				thumbwidth: " . $params->get('thumbnailwidth', '75') . ",
				time: " . $params->get('time', '7000') . ",
				transPeriod: " . $params->get('transperiod', '1500') . ",
				alignment: '" . $params->get('alignment', 'center') . "',
				autoAdvance: " . $autoAdvance . ",
				mobileAutoAdvance: " . $params->get('autoAdvance', '1') . ",
				portrait: " . $params->get('portrait', '0') . ",
				barDirection: '" . $params->get('barDirection', 'leftToRight') . "',
				imagePath: '" . JUri::base(true) . "/media/com_slideshowck/images/',
				lightbox: '" . $params->get('lightboxtype', 'mediaboxck') . "',
				fullpage: " . $params->get('fullpage', '0') . ",
				mobileimageresolution: '" . ($params->get('usemobileimage', '0') ? $params->get('mobileimageresolution', '640') : '0') . "',
				" . $navigation . "
				barPosition: '" . $params->get('barPosition', 'bottom') . "',
				responsiveCaption: " . ($params->get('usecaptionresponsive') == '2' ? '1' : '0') . ",
				keyboardNavigation: " . $params->get('keyboardnavigation', '0') . ",
				titleInThumbs: " . $params->get('titleInThumbs', '0') . ",
				container: '" . $params->get('container', '') . "'
		});
}); 
";

if ($params->get('loadinline', '0') == '1') {
	echo '<script>' . $js . '</script>';
} else {
	$doc->addScriptDeclaration($js);
}

$css = '';
// load some css
$css = "#camera_wrap_" . $module->id . " .camera_pag_ul li img, #camera_wrap_" . $module->id . " .camera_thumbs_cont ul li > img {height:" . SlideshowckHelper::testUnit($params->get('thumbnailheight', '75')) . ";}";

// load the caption styles
if (version_compare(JVERSION, '4', '<')) {
$captioncss = modSlideshowckHelper::createCss($params, 'captionstyles');
$fontfamily = ($params->get('captionstylesusefont','0') && $params->get('captionstylestextgfont', '0')) ? "font-family:'" . $params->get('captionstylestextgfont', 'Droid Sans') . "';" : '';
if ($fontfamily) {
	$gfonturl = str_replace(" ", "+", $params->get('captionstylestextgfont', 'Droid Sans'));
	$doc->addStylesheet('https://fonts.googleapis.com/css?family=' . $gfonturl);
}

$css .= "
#camera_wrap_" . $module->id . " .camera_caption {
	display: block;
	position: absolute;
}
#camera_wrap_" . $module->id . " .camera_caption > div {
	" . $captioncss['padding'] . $captioncss['margin'] . $captioncss['background'] . $captioncss['gradient'] . $captioncss['borderradius'] . $captioncss['shadow'] . $captioncss['border'] . $fontfamily . "
}
#camera_wrap_" . $module->id . " .camera_caption > div div.camera_caption_title {
	" . $captioncss['fontcolor'] . $captioncss['fontsize'] . "
}
#camera_wrap_" . $module->id . " .camera_caption > div div.camera_caption_desc {
	" . $captioncss['descfontcolor'] . $captioncss['descfontsize'] . "
}
";
}

if ($params->get('usecaptionresponsive') == '1' || $params->get('usecaptionresponsive') == '2') {
	$css .= "
@media screen and (max-width: " . str_replace("px", "", $params->get('captionresponsiveresolution', '480')) . "px) {
		#camera_wrap_" . $module->id . " .camera_caption {
			" . ( $params->get('captionresponsivehidecaption', '0') == '1' ? "display: none !important;" : ($params->get('usecaptionresponsive') == '1' ? "font-size: " . $params->get('captionresponsivefontsize', '0.6em') ." !important;" : "") ) . "
		}
}";
}

// load the style 
if ($styleId = $params->get('styles', '')) {
	$layoutcss = str_replace('|ID|', '#camera_wrap_' . $module->id, SlideshowckHelper::getStyleLayoutcss($styleId) );
	$css .= $layoutcss;
}

$doc->addStyleDeclaration($css);

// load the php Class for the html fixer
if ($params->get('fixhtml', '0') == '1') include_once SLIDESHOWCK_PATH . '/helpers/htmlfixer.php';

// display the module
require JModuleHelper::getLayoutPath('mod_slideshowck', $params->get('layout', 'default'));
