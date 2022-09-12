<?php

/**
 * @copyright	Copyright (C) 2012-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Slideshow CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die;

if ($params->get('slideshowckhikashop_enable', '0') == '1') {
	if (JFile::exists(JPATH_ROOT . '/plugins/system/slideshowckhikashop/helper/helper_slideshowckhikashop.php')) {
		require_once JPATH_ROOT . '/plugins/system/slideshowckhikashop/helper/helper_slideshowckhikashop.php';
		$items = modSlideshowckhikashopHelper::getItems($params);
	} else {
		echo '<p style="color:red;font-weight:bold;">File /plugins/system/slideshowckhikashop/helper/helper_slideshowckhikashop.php not found ! Please download the patch for Slideshow CK - Hikashop on <a href="https://www.joomlack.fr">https://www.joomlack.fr</a></p>';
		return false;
	}
} else if ($params->get('slideshowckjoomgallery_enable', '0') == '1') {
	if (JFile::exists(JPATH_ROOT . '/plugins/system/slideshowckjoomgallery/helper/helper_slideshowckjoomgallery.php')) {
		require_once JPATH_ROOT . '/plugins/system/slideshowckjoomgallery/helper/helper_slideshowckjoomgallery.php';
		$items = modSlideshowckjoomgalleryHelper::getItems($params);
	} else {
		echo '<p style="color:red;font-weight:bold;">File /plugins/system/slideshowckjoomgallery/helper/helper_slideshowckjoomgallery.php not found ! Please download the patch for Slideshow CK - Joomgallery on <a href="https://www.joomlack.fr">https://www.joomlack.fr</a></p>';
		return false;
	}
} else if ($params->get('slideshowckvirtuemart_enable', '0') == '1') {
	if (JFile::exists(JPATH_ROOT . '/plugins/system/slideshowckvirtuemart/helper/helper_slideshowckvirtuemart.php')) {
		require_once JPATH_ROOT . '/plugins/system/slideshowckvirtuemart/helper/helper_slideshowckvirtuemart.php';
		$items = modSlideshowckvirtuemartHelper::getItems($params);
	} else {
		echo '<p style="color:red;font-weight:bold;">File /plugins/system/slideshowckvirtuemart/helper/helper_slideshowckvirtuemart.php not found ! Please download the patch for Slideshow CK - Virtuemart on <a href="https://www.joomlack.fr">https://www.joomlack.fr</a></p>';
		return false;
	}
} else if ($params->get('slideshowckk2_enable', '0') == '1') {
	if (JFile::exists(JPATH_ROOT . '/plugins/system/slideshowckk2/helper/helper_slideshowckk2.php')) {
		require_once JPATH_ROOT . '/plugins/system/slideshowckk2/helper/helper_slideshowckk2.php';
		$items = modSlideshowckk2Helper::getItems($params);
	} else {
		echo '<p style="color:red;font-weight:bold;">File /plugins/system/slideshowckk2/helper/helper_slideshowckk2.php not found ! Please download the patch for Slideshow CK - K2 on <a href="https://www.joomlack.fr">https://www.joomlack.fr</a></p>';
		return false;
	}
} 

else {
	switch ($params->get('slidesssource', 'slidesmanager')) {
		case 'folder':
			$items = modSlideshowckHelper::getItemsFromfolder($params);

			break;
		case 'autoloadfolder':
			$items = modSlideshowckHelper::getItemsAutoloadfolder($params);

			break;
		case 'autoloadarticlecategory':
			$items = modSlideshowckHelper::getItemsAutoloadarticlecategory($params);
			break;
		case 'flickr':
			$items = modSlideshowckHelper::getItemsAutoloadflickr($params);
			break;
		case 'googlephotos':
			include_once(JPATH_SITE. '/plugins/system/slideshowckparams/helper/class-helpersource-google.php');
			$items = SlideshowckHelpersourceGoogle::getItems($params);
			break;
		default:
//			$items = modSlideshowckHelper::getItems($params);
			break;
	}

//	if ($params->get('displayorder', 'normal') == 'shuffle')
//		shuffle($items);
}
