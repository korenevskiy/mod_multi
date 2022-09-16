<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Slideshow CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die;

/** 
 * NOTE : THIS FILE IS USED FOR B/C OF THE V1 FEATURES ONLY !! 
 */
 
 
//$com_path = JPATH_SITE . '/components/com_content/';
//require_once $com_path . 'router.php';
//require_once $com_path . 'helpers/route.php';
//JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class modSlideshowckHelper {

	private static $_params;

	private static $folderLabels = array();

	private static $imagesOrderByLabels = array();

	/**
	 * Get a list of the items.
	 *
	 * @param	JRegistry	$params	The module options.
	 *
	 * @return	array
	 */
	static function getItems(&$params) {
		// Initialise variables.
		self::$_params = $params;
		$db = JFactory::getDbo();
		$document = JFactory::getDocument();

		// load the libraries
		//jimport('joomla.application.module.helper');
		$items = json_decode(str_replace("|qq|", "\"", $params->get('slides')));
		foreach ($items as $i => $item) {
			if (!$item->imgname) {
				unset($items[$i]);
				continue;
			}

			// check if the slide is published
			if (isset($item->state) && $item->state == '0') {
				unset($items[$i]);
				continue;
			}

			// check the slide start date
			if (isset($item->startdate) && $item->startdate) {
				// if (date("d M Y") < $item->startdate) {
				if (time() < strtotime($item->startdate)) {
					unset($items[$i]);
					continue;
				}
			}

			// check the slide end date
			if (isset($item->enddate) && $item->enddate) {
				// if (date("d M Y") > $item->enddate) {
				if (time() > strtotime($item->enddate)) {
					unset($items[$i]);
					continue;
				}
			}

			if (isset($item->slidearticleid) && $item->slidearticleid) {
				$item = self::getArticle($item, $params);
			} else {
				$item->article = null;
			}
			// create new images for mobile
			if ($params->get('usemobileimage', '0')) { 
				$resolutions = explode(',', $params->get('mobileimageresolution', '640'));
				foreach ($resolutions as $resolution) {
					self::resizeImage($item->imgname, (int)$resolution, '', (int)$resolution, '');
				}
			}

			if (stristr($item->imgname, "http")) {
				$item->imgthumb = $item->imgname;
			} else {
				// renomme le fichier
				$thumbext = explode(".", $item->imgname);
				$thumbext = end($thumbext);
				// crée la miniature
				if ($params->get('thumbnails', '1') == '1' && $params->get('autocreatethumbs','1')) {
					$item->imgthumb = JURI::base(true) . '/' . self::resizeImage($item->imgname, $params->get('thumbnailwidth', '182'), $params->get('thumbnailheight', '187'));
				} else {
					$thumbfile = str_replace(JFile::getName($item->imgname), "th/" . JFile::getName($item->imgname), $item->imgname);
					$thumbfile = str_replace("." . $thumbext, "_th." . $thumbext, $thumbfile);
					$item->imgthumb = JURI::base(true) . '/' . $thumbfile;
				}
				$item->imgname = JURI::base(true) . '/' . $item->imgname;
			}

			// set the videolink
			if ($item->imgvideo)
				$item->imgvideo = self::setVideolink($item->imgvideo);

			// manage the title and description
			if (stristr($item->imgcaption, "||")) {
				$splitcaption = explode("||", $item->imgcaption);
				$item->imgcaption = '<div class="slideshowck_title">' . $splitcaption[0] . '</div><div class="slideshowck_description">' . $splitcaption[1] . '</div>';
			}
			
			// route the url
			if (strcasecmp(substr($item->imglink, 0, 4), 'http') && (strpos($item->imglink, 'index.php?') !== false)) {
				$item->imglink = JRoute::_($item->imglink, true, false);
			} else {
				$item->imglink = JRoute::_($item->imglink);
			}
			
			if (!isset($item->imgtitle)) $item->imgtitle = '';
		}

		return $items;
	}

	static function getArticle(&$item, $params) {
		$com_path = JPATH_SITE . '/components/com_content/';
		require_once $com_path . 'router.php';
		require_once $com_path . 'helpers/route.php';
		JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
		self::$_params = $params;
		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		// Get an instance of the generic articles model
		$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$articles->setState('params', $appParams);
		$articles->setState('filter.published', 1);
		$articles->setState('filter.article_id', $item->slidearticleid);
		$items2 = $articles->getItems();
		$item->article = $items2[0];
		$item->article->text = JHTML::_('content.prepare', $item->article->introtext);
		$item->article->text = self::truncate($item->article->text, $params->get('articlelength', '150'));
		// $item->article->text = JHTML::_('string.truncate',$item->article->introtext,'150');
		// set the item link to the article depending on the user rights
		if ($access || in_array($item->article->access, $authorised)) {
			// We know that user has the privilege to view the article
			$item->slug = $item->article->id . ':' . $item->article->alias;
			$item->catslug = $item->article->catid ? $item->article->catid . ':' . $item->article->category_alias : $item->article->catid;
			$item->article->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
		} else {
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$menuitems = $menu->getItems('link', 'index.php?option=com_users&view=login');
			if (isset($menuitems[0])) {
				$Itemid = $menuitems[0]->id;
			} elseif (JRequest::getInt('Itemid') > 0) {
				$Itemid = JRequest::getInt('Itemid');
			}
			$item->article->link = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $Itemid);
		}
		return $item;
	}

	/**
	 * Get a list of the items.
	 *
	 * @param	JRegistry	$params	The module options.
	 *
	 * @return	array
	 */
	static function getItemsFromfolder(&$params) {
		self::$_params = $params;
		$authorisedExt = array('png', 'jpg', 'JPG', 'JPEG', 'jpeg', 'bmp', 'tiff', 'gif');
		$items = json_decode(str_replace("|qq|", "\"", $params->get('slidesfromfolder')));
		foreach ($items as & $item) {
//			$item->imgname = str_replace(JUri::base(), '', $item->imgname);
			$item->imgthumb = '';
			$item->imgname = trim($item->imgname, '/');
			$item->imgname = trim($item->imgname, '\\');
			// create new images for mobile
			if ($params->get('usemobileimage', '0')) { 
				self::resizeImage($item->imgname, $params->get('mobileimageresolution', '640'), '', $params->get('mobileimageresolution', '640'), '');
			}
			if ($params->get('thumbnails', '1') == '1')
				$item->imgthumb = JURI::base(true) . '/' . self::resizeImage($item->imgname, $params->get('thumbnailwidth', '100'), $params->get('thumbnailheight', '75'));
			$thumbext = explode(".", $item->imgname);
			$thumbext = end($thumbext);
			// set the variables
			$item->imgvideo = null;
			$item->slideselect = null;
			$item->slideselect = null;
			$item->imgcaption = null;
			$item->article = null;
			$item->slidearticleid = null;
			$item->imgalignment = null;
			$item->imgtarget = 'default';
			$item->imgtime = null;
			$item->imglink = null;
			$item->imgtitle = null;

			if (!in_array(strToLower(JFile::getExt($item->imgname)), $authorisedExt))
				continue;

			// load the image data from txt
			$item = self::getImageDataFromfolder($item, $params);
			$item->imgname = JURI::base(true) . '/' . $item->imgname;
			
			// route the url
			if (strcasecmp(substr($item->imglink, 0, 4), 'http') && (strpos($item->imglink, 'index.php?') !== false)) {
				$item->imglink = JRoute::_($item->imglink, true, false);
			} else {
				$item->imglink = JRoute::_($item->imglink);
			}
		}

		return $items;
	}
	
	static function getItemsAutoloadfolder(&$params) {
		self::$_params = $params;
		$authorisedExt = array('png', 'jpg', 'JPG', 'JPEG', 'jpeg', 'bmp', 'tiff', 'gif');
		$folder = trim($params->get('autoloadfoldername'), '/');
		if (file_exists($folder . '/labels.txt')) {
			$items = self::loadImagesFromFolder($folder);
		} else {
			$items = JFolder::files($folder, '.jpg|.png|.jpeg|.gif|.JPG|.JPEG|.jpeg', false, true);
			foreach ($items as $i => $name) {
				$item = new stdClass();
				// $item->imgname = str_replace(JUri::base(),'', $item->imgname);
				$item->imgthumb = '';
				$item->imgname = trim(str_replace('\\','/',$name), '/');
				$item->imgname = trim($item->imgname, '\\');
				// create new images for mobile
				if ($params->get('usemobileimage', '0')) { 
					self::resizeImage($item->imgname, $params->get('mobileimageresolution', '640'), '', $params->get('mobileimageresolution', '640'), '');
				}
				if ($params->get('thumbnails', '1') == '1')
					$item->imgthumb = JURI::base(true) . '/' . self::resizeImage($item->imgname, $params->get('thumbnailwidth', '100'), $params->get('thumbnailheight', '75'));
				$thumbext = explode(".", $item->imgname);
				$thumbext = end($thumbext);
				// set the variables
				$item->imgvideo = null;
				$item->slideselect = null;
				$item->slideselect = null;
				$item->imgcaption = null;
				$item->article = null;
				$item->slidearticleid = null;
				$item->imgalignment = null;
				$item->imgtarget = 'default';
				$item->imgtime = null;
				$item->imglink = null;
				$item->imgtitle = null;

				if (!in_array(strToLower(JFile::getExt($item->imgname)), $authorisedExt))
					continue;

				// load the image data from txt
				$item = self::getImageDataFromfolder($item, $params);
				$item->imgname = JURI::base(true) . '/' . $item->imgname;
				$items[$i] = $item;

				// route the url
				if (strcasecmp(substr($item->imglink, 0, 4), 'http') && (strpos($item->imglink, 'index.php?') !== false)) {
					$item->imglink = JRoute::_($item->imglink, true, false);
				} else {
					$item->imglink = JRoute::_($item->imglink);
				}
			}
		}
		return $items;
	}

	/*
	 * Load the image from the specified folder 
	 */
	public static function loadImagesFromFolder($directory) {

		// encode the folder path, needed if contains an accent
		try {
			$translatedDirectory = iconv("UTF-8", "ISO-8859-1//TRANSLIT", urldecode($directory));
			if ($translatedDirectory) $directory = $translatedDirectory;
		} catch (Exception $e) {
			echo 'CK Message : ',  $e->getMessage(), "\n";
		}

		// load the files from the folder
		$files = JFolder::files(trim(trim($directory), '/'), '.', false, true);

		if (! $files) return 'CK message : No files found in the directory : ' . $directory;

		self::$imagesOrderByLabels = array();
		// load the labels from the folder
		self::getImageLabelsFromFolder($directory);

		$order = self::$_params->get('displayorder');
		// set the images order
		if ($order == 'shuffle') {
			shuffle($files);
		} else 
//			if(isset($params->order) && $params->order == 'labels') 
				{
			natsort($files);
			$files = array_map(array(__CLASS__, 'formatPath'), $files);
			$baseDir = self::formatPath($directory);
			$labelsOrder = array_reverse(self::$imagesOrderByLabels);
			foreach ($labelsOrder as $name) {
				$imgFile = $baseDir . '/' . $name;
				array_unshift($files, $imgFile);
			}
			// now make it unique
			$files = array_unique($files);
		} 
//		else {
//			natsort($files);
//		}

		$authorisedExt = array('png','jpg','jpeg','bmp','tiff','gif');
		$items = array();
		$i = 0;
		foreach ($files as $file) {
			$fileExt = JFile::getExt($file);
			if (!in_array(strToLower($fileExt),$authorisedExt)) continue;

			$item = new stdClass();
			// set the variables
			$item->imgvideo = null;
			$item->slideselect = null;
			$item->slideselect = null;
			$item->imgcaption = null;
			$item->article = null;
			$item->slidearticleid = null;
			$item->imgalignment = null;
			$item->imgtarget = 'default';
			$item->imgtime = null;
			$item->imglink = null;
			$item->imgtitle = null;

			// limit the number of images
//			if (isset($params->number) && $params->number > 0 && $i > (int)$params->number) $show = false;

			// get the data for the image
			$filedata = self::getImageDataFromfolder2($file, $directory);

			$file = str_replace("\\", "/", utf8_encode($file));
			if (isset($filedata->link) && $filedata->link) {
				$item->imglink = $filedata->link;
			} else {
				$videoFile = str_replace($fileExt, 'mp4', $file);
				$hasVideo = file_exists($videoFile);
				$item->imglink = $hasVideo ? $videoFile : $file;
			}

			$item->imgname = JURI::base(true) . '/' . $file;
			$item->imgthumb = $item->imgname;
			$item->imgtitle = $filedata->title;
			$item->imgcaption = $filedata->desc;
			$item->imgvideo = $filedata->video;
//			$linktitle = $filedata->title || $filedata->desc ? ($filedata->desc ? $filedata->title . '::' . $filedata->desc : $filedata->title) : $title;

			$items[$i] = $item;
			$i++;
		}

		return $items;
	}

	/*
	 * Remove special character
	 */
	private static function cleanName($path) {
		return preg_replace('/[^a-z0-9]/i', '_', $path);
	}

	public static function formatPath($p) {
		return trim(str_replace("\\", "/", $p), "/");
	}

	private static function getImageLabelsFromFolder($directory) {
		$dirindex = self::cleanName($directory);
		if (! empty(self::$folderLabels[$dirindex])) return;

		$items = array();
		$item = new stdClass();

		// get the language
		$lang = JFactory::getLanguage();
		$langtag = $lang->getTag(); // returns fr-FR or en-GB

		// load the image data from txt
		if (file_exists(JPATH_ROOT . '/' . $directory . '/labels.' . $langtag . '.txt')) {
			$data = file_get_contents(JPATH_ROOT . '/' . $directory . '/labels.' . $langtag . '.txt');
		} else if (file_exists(JPATH_ROOT . '/' . $directory . '/labels.txt')) {
			$data = file_get_contents(JPATH_ROOT . '/' . $directory . '/labels.txt');
		} else {
			return null;
		}

		$doUTF8encode = true;
		// remove UTF-8 BOM and normalize line endings
		if (!strcmp("\xEF\xBB\xBF", substr($data,0,3))) {  // file starts with UTF-8 BOM
			$data = substr($data, 3);  // remove UTF-8 BOM
			$doUTF8encode = false;
		}
		$data = str_replace("\r", "\n", $data);  // normalize line endings

		// if no data found, exit
		if(! $data) return null;

		// explode the file into rows
		// $imgdatatmp = explode("\n", $data);
		$imgdatatmp = preg_split("/\r\n|\n|\r/", $data, -1, PREG_SPLIT_NO_EMPTY);

		$parmsnumb = count($imgdatatmp);
		for ($i = 0; $i < $parmsnumb; $i++) {
			$imgdatatmp[$i] = trim($imgdatatmp[$i]);
			$line = explode('|', $imgdatatmp[$i]);

			// store the order or files from the TXT file
			self::$imagesOrderByLabels[] = $line[0];

			$item = new stdClass();
			$item->index = self::cleanName($line[0]);
			$item->title = (isset($line[1])) ? ( $doUTF8encode ? (utf8_encode($line[1])) : ($line[1]) ) : '';
			$item->desc = (isset($line[2])) ? ( $doUTF8encode ? (utf8_encode($line[2])) : ($line[2]) ) : '';
			$item->link = (isset($line[3])) ? ( $doUTF8encode ? (utf8_encode($line[3])) : ($line[3]) ) : '';
			$item->video = (isset($line[4])) ? ( $doUTF8encode ? (utf8_encode($line[4])) : ($line[4]) ) : '';

			$items[$item->index] = $item;
		}

		self::$folderLabels[$dirindex] = $items;
	}

	/*
	 * Load the data for the image (title and description)
	 */
	private static function getImageDataFromfolder2($file, $directory) {
		$filename = explode('/', $file);
		$filename = end($filename);
		$dirindex = self::cleanName($directory);
		$fileindex = self::cleanName($filename);

		if (! empty(self::$folderLabels[$dirindex]) && ! empty(self::$folderLabels[$dirindex][$fileindex])) {
				$item = self::$folderLabels[$dirindex][$fileindex];
		} else {
			$item = new stdClass();
			$item->title = null;
			$item->desc = null;
			$item->video = null;
			// old method, get image data from txt file with image name // TODO : remove
			// $item = self::getImageDataFromImageTxt($file)
		}

		return $item;
	}

	/**
	 * Get a list of the items.
	 *
	 * @param	JRegistry	$params	The module options.
	 *
	 * @return	array
	 */
	static function getItemsAutoloadflickr(&$params) {
		self::$_params = $params;

		$url = 'https://api.flickr.com/services/rest/?format=json&method=flickr.photosets.getPhotos&extras=description,original_format,url_sq,url_t,url_s,url_m,url_o&nojsoncallback=1';
		$url .= '&api_key=' . $params->get('flickr_apikey');
		$url .= '&photoset_id=' . $params->get('flickr_photoset');

		if (ini_get('allow_url_fopen') && function_exists('file_get_contents')) {  
			$result = file_get_contents($url);  
		}
		// look for curl
		if ($result == '' && extension_loaded('curl')) {
			$ch = curl_init();  
			$timeout = 30;  
			curl_setopt($ch, CURLOPT_URL, $url);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
			$result = curl_exec($ch);  
			curl_close($ch);  
		}

		$images = json_decode($result)->photoset->photo;
		$items = Array();
		$i = 0;
		$flickrSuffixes = array('o', 'k', 'h', 'b', 'z', 'sq', 't', 'sm', 'm');
		foreach ($images as & $image) {
			$items[$i] = new stdClass();
			$item = $items[$i];
			$suffix = 'o';
			foreach ($flickrSuffixes as $flickrSuffixe) {
				if (isset($image->{'url_' . $flickrSuffixe})) {
					$suffix = $flickrSuffixe;
					break;
				}
			}
			$item->imgname = $image->{'url_' . $suffix};
			$item->imgthumb = $item->imgname;
			// create new images for mobile
			// if ($params->get('usemobileimage', '0')) { 
				// self::resizeImage($item->imgname, $params->get('mobileimageresolution', '640'), '', $params->get('mobileimageresolution', '640'), '');
			// }
			// if ($params->get('thumbnails', '1') == '1')
				// $item->imgthumb = JURI::base(true) . '/' . self::resizeImage($item->imgname, $params->get('thumbnailwidth', '100'), $params->get('thumbnailheight', '75'));
			// $thumbext = explode(".", $item->imgname);
			// $thumbext = end($thumbext);
			// set the variables
			$item->imgvideo = null;
			$item->slideselect = null;
			$item->slideselect = null;
			$item->imgcaption = null;
			$item->article = null;
			$item->slidearticleid = null;
			$item->imgalignment = null;
			$item->imgtarget = 'default';
			$item->imgtime = null;
			$item->imglink = null;
			$item->imgtitle = null;

			// show the title and description of the image
			if ($params->get('flickr_showcaption', '1')) {
				$item->imgtitle = $image->title;
				$item->imgcaption = $image->description->_content;
			}

			// set the link to the image
			if ($params->get('flickr_autolink', '0')) {
				$item->imglink = $image->{'url_' . $suffix};
			}

			$i++;
		}

		return $items;
	}

	static function getItemsAutoloadarticlecategory(&$params) {
		$com_path = JPATH_SITE . '/components/com_content/';
		require_once $com_path . 'router.php';
		require_once $com_path . 'helpers/route.php';
		JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
		// Get an instance of the generic articles model
		$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$articles->setState('params', $appParams);

		// Set the filters based on the module params
		$articles->setState('list.start', 0);
//		$articles->setState('list.limit', (int) $params->get('count', 0)); // must check if the image exists
		$articles->setState('list.limit', 0);
		$articles->setState('filter.published', 1);

		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$articles->setState('filter.access', $access);

		// Prep for Normal or Dynamic Modes
		$mode = $params->get('mode', 'normal');
		switch ($mode)
		{
			case 'dynamic':
				$option = JRequest::getCmd('option');
				$view = JRequest::getCmd('view');
				if ($option === 'com_content') {
					switch($view)
					{
						case 'category':
							$catids = array(JRequest::getInt('id'));
							break;
						case 'categories':
							$catids = array(JRequest::getInt('id'));
							break;
						case 'article':
							if ($params->get('show_on_article_page', 1)) {
								$article_id = JRequest::getInt('id');
								$catid = JRequest::getInt('catid');

								if (!$catid) {
									// Get an instance of the generic article model
									$article = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));

									$article->setState('params', $appParams);
									$article->setState('filter.published', 1);
									$article->setState('article.id', (int) $article_id);
									$item = $article->getItem();

									$catids = array($item->catid);
								}
								else {
									$catids = array($catid);
								}
							}
							else {
								// Return right away if show_on_article_page option is off
								return;
							}
							break;

						case 'featured':
						default:
							// Return right away if not on the category or article views
							return;
					}
				}
				else {
					// Return right away if not on a com_content page
					return;
				}

				break;

			case 'normal':
			default:
				$catids = $params->get('catid');
				$articles->setState('filter.category_id.include', (bool) $params->get('category_filtering_type', 1));
				break;
		}

		// Category filter
		if ($catids) {
			if ($params->get('show_child_category_articles', 0) && (int) $params->get('levels', 0) > 0) {
				// Get an instance of the generic categories model
				$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
				$categories->setState('params', $appParams);
				$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
				$categories->setState('filter.get_children', $levels);
				$categories->setState('filter.published', 1);
				$categories->setState('filter.access', $access);
				$additional_catids = array();

				foreach($catids as $catid)
				{
					$categories->setState('filter.parentId', $catid);
					$recursive = true;
					$items = $categories->getItems($recursive);

					if ($items)
					{
						foreach($items as $category)
						{
							$condition = (($category->level - $categories->getParent()->level) <= $levels);
							if ($condition) {
								$additional_catids[] = $category->id;
							}

						}
					}
				}

				$catids = array_unique(array_merge($catids, $additional_catids));
			}

			$articles->setState('filter.category_id', $catids);
		}

		// Ordering
		$articles->setState('list.ordering', $params->get('article_ordering', 'a.ordering'));
		$articles->setState('list.direction', $params->get('article_ordering_direction', 'ASC'));

		// New Parameters
		$articles->setState('filter.featured', $params->get('show_front', 'show'));
//		$articles->setState('filter.author_id', $params->get('created_by', ""));
//		$articles->setState('filter.author_id.include', $params->get('author_filtering_type', 1));
//		$articles->setState('filter.author_alias', $params->get('created_by_alias', ""));
//		$articles->setState('filter.author_alias.include', $params->get('author_alias_filtering_type', 1));
		$excluded_articles = $params->get('excluded_articles', '');

		if ($excluded_articles) {
			$excluded_articles = explode("\r\n", $excluded_articles);
			$articles->setState('filter.article_id', $excluded_articles);
			$articles->setState('filter.article_id.include', false); // Exclude
		}

		$date_filtering = $params->get('date_filtering', 'off');
		if ($date_filtering !== 'off') {
			$articles->setState('filter.date_filtering', $date_filtering);
			$articles->setState('filter.date_field', $params->get('date_field', 'a.created'));
			$articles->setState('filter.start_date_range', $params->get('start_date_range', '1000-01-01 00:00:00'));
			$articles->setState('filter.end_date_range', $params->get('end_date_range', '9999-12-31 23:59:59'));
			$articles->setState('filter.relative_date', $params->get('relative_date', 30));
		}

		// Filter by language
		$articles->setState('filter.language', $app->getLanguageFilter());

		$items = $articles->getItems();

		// Display options
		$show_date = $params->get('show_date', 0);
		$show_date_field = $params->get('show_date_field', 'created');
		$show_date_format = $params->get('show_date_format', 'Y-m-d H:i:s');
		$show_category = $params->get('show_category', 0);
		$show_hits = $params->get('show_hits', 0);
		$show_author = $params->get('show_author', 0);
		$show_introtext = $params->get('show_introtext', 0);
		$introtext_limit = $params->get('introtext_limit', 100);

		// Find current Article ID if on an article page
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');

		if ($option === 'com_content' && $view === 'article') {
			$active_article_id = JRequest::getInt('id');
		}
		else {
			$active_article_id = 0;
		}

		// Prepare data for display using display options
		$slideItems = Array();
		foreach ($items as &$item)
		{
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid ? $item->catid .':'.$item->category_alias : $item->catid;

			if ($access || in_array($item->access, $authorised)) {
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			}
			 else {
				// Angie Fixed Routing
				$app	= JFactory::getApplication();
				$menu	= $app->getMenu();
				$menuitems	= $menu->getItems('link', 'index.php?option=com_users&view=login');
				if(isset($menuitems[0])) {
						$Itemid = $menuitems[0]->id;
					} elseif (JRequest::getInt('Itemid') > 0) { //use Itemid from requesting page only if there is no existing menu
						$Itemid = JRequest::getInt('Itemid');
					}

				$item->link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$Itemid);
			}

			// Used for styling the active article
			$item->active = $item->id == $active_article_id ? 'active' : '';

			$item->displayDate = '';
			if ($show_date) {
				$item->displayDate = JHTML::_('date', $item->$show_date_field, $show_date_format);
			}

			if ($item->catid) {
				$item->displayCategoryLink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid));
				$item->displayCategoryTitle = $show_category ? '<a href="'.$item->displayCategoryLink.'">'.$item->category_title.'</a>' : '';
			}
			else {
				$item->displayCategoryTitle = $show_category ? $item->category_title : '';
			}

			$item->displayHits = $show_hits ? $item->hits : '';
			$item->displayAuthorName = $show_author ? $item->author : '';
//			if ($show_introtext) {
//				$item->introtext = JHtml::_('content.prepare', $item->introtext, '', 'mod_articles_category.content');
//				$item->introtext = self::_cleanIntrotext($item->introtext);
//			}
			$item->displayIntrotext = $show_introtext ? self::truncate($item->introtext, $introtext_limit) : '';
			$item->displayReadmore = $item->alternative_readmore;
			
			// add the article to the slide
			$registry = new JRegistry;
			$registry->loadString($item->images);
			$item->images = $registry->toArray();
			$article_image  =false;
			$slideItem_article_text = '';
			switch ($params->get('articleimgsource', 'introimage')) {
				case 'firstimage':
					$search_images = preg_match('/<img(.*?)src="(.*?)"(.*?)\/>/is', $item->introtext, $imgresult);
					$article_image = (isset($imgresult[2]) && $imgresult[2] != '') ? $imgresult[2] : false;
					$slideItem_article_text = (isset($imgresult[2])) ? str_replace($imgresult[0], '', $item->introtext) : $item->introtext;
					break;
				case 'fullimage':
					$article_image = (isset($item->images['image_fulltext']) && $item->images['image_fulltext']) ? $item->images['image_fulltext'] : false;
					$slideItem_article_text = $item->introtext;
					break;
				case 'introimage':
				default:
					$article_image = (isset($item->images['image_intro']) && $item->images['image_intro']) ? $item->images['image_intro'] : false;
					$slideItem_article_text = $item->introtext;
					break;
			}
			
			if ( $article_image
					 && (count($slideItems) < (int) $params->get('count', 0) || (int) $params->get('count', 0) == 0)) {
				$slideItem = new stdClass();
				$slideItem->imgname = $article_image;
//				$slideItem->imgname = trim(str_replace('\\', '/', $item->images['image_intro']), '/');
				$slideItem->imgname = trim($slideItem->imgname, '\\');
				$slideItem->imgthumb = JURI::base(true) . '/' . $slideItem->imgname;
				$slideItem->imgname = JURI::base(true) . '/' . $slideItem->imgname;
				$slideItem->imgvideo = null;
				$slideItem->slideselect = null;
				$slideItem->imgcaption = null;
				$slideItem->article = new stdClass();
				$slideItem->slidearticleid = null;
				$slideItem->imgalignment = null;
				$slideItem->imgtarget = 'default';
				$slideItem->imgtime = null;
				$slideItem->imglink = null;
				$slideItem->imgtitle = null;
				$slideItem->article->title = $item->title;
				$slideItem->article->text = JHTML::_('content.prepare', $slideItem_article_text);
				if ($params->get('striptags', '0') == '1') {
					$slideItem->article->text = strip_tags($slideItem->article->text);
				}
				$slideItem->article->text = self::truncate($slideItem->article->text, $params->get('articlelength', '150'));
				$slideItem->article->link = $item->link;
				
				$slideItems[] = $slideItem;
			}
		}

		return $slideItems;
	}

	static function getImageDataFromfolder(&$item, $params) {
		$item->imgvideo = null;
		$item->slideselect = null;
		$item->imgcaption = null;
		$item->article = null;
		$item->imgalignment = null;
		$item->imgtarget = 'default';
		$item->imgtime = null;
		$item->imglink = null;
		// load the image data from txt
		$datafile = JPATH_ROOT . '/' . str_replace(JFile::getExt($item->imgname), 'txt', $item->imgname);
		$data = JFile::exists($datafile) ? file_get_contents($datafile) : '';
		$imgdatatmp = explode("\n", $data);

		$parmsnumb = count($imgdatatmp);
		for ($i = 0; $i < $parmsnumb; $i++) {
			$imgdatatmp[$i] = trim($imgdatatmp[$i]);
			$item->imgcaption = stristr($imgdatatmp[$i], "caption=") ? str_replace('caption=', '', $imgdatatmp[$i]) : $item->imgcaption;
			$item->slidearticleid = stristr($imgdatatmp[$i], "articleid=") ? str_replace('articleid=', '', $imgdatatmp[$i]) : $item->slidearticleid;
			$item->imgvideo = stristr($imgdatatmp[$i], "video=") ? str_replace('video=', '', $imgdatatmp[$i]) : $item->imgvideo;
			$item->imglink = stristr($imgdatatmp[$i], "link=") ? str_replace('link=', '', $imgdatatmp[$i]) : $item->imglink;
			$item->imgtime = stristr($imgdatatmp[$i], "time=") ? str_replace('time=', '', $imgdatatmp[$i]) : $item->imgtime;
			$item->imgtarget = stristr($imgdatatmp[$i], "target=") ? str_replace('target=', '', $imgdatatmp[$i]) : $item->imgtarget;
		}

		if ($item->imgvideo)
			$item->slideselect = 'video';
		
		// manage the title and description
		if (stristr($item->imgcaption, "||")) {
			$splitcaption = explode("||", $item->imgcaption);
			$item->imgcaption = '<div class="slideshowck_title">' . $splitcaption[0] . '</div><div class="slideshowck_description">' . $splitcaption[1] . '</div>';
		}

		if (isset($item->slidearticleid) && $item->slidearticleid) {
			$item = self::getArticle($item, $params);
		}

		return $item;
	}

	/**
	 * Set the correct video link
	 *
	 * $videolink string the video path
	 *
	 * @return string the new video path
	 */
	static function setVideolink($videolink) {
		// youtube
		if (stristr($videolink, 'youtu.be')) {
			$videolink = str_replace('youtu.be', 'www.youtube.com/embed', $videolink);
		} else if (stristr($videolink, 'www.youtube.com') AND !stristr($videolink, 'embed')) {
			$videolink = str_replace('youtube.com', 'youtube.com/embed', $videolink);
		}

		$videolink .= ( stristr($videolink, '?')) ? '&wmode=transparent' : '?wmode=transparent';

		return $videolink;
	}

	/**
	 * Create the list of all modules published as Object
	 *
	 * $file string the image path
	 * $x integer the new image width
	 * $y integer the new image height
	 *
	 * @return Boolean True on Success
	 */
	static function resizeImage($file, $x, $y = '', $thumbpath = 'th', $thumbsuffix = '_th') {

		if (!$file)
			return;

		$params = self::$_params;
		if (!$params->get('autocreatethumbs','1'))
			return;
			
		$thumbext = explode(".", $file);
		$thumbext = end($thumbext);
		$thumbfile = str_replace(JFile::getName($file), $thumbpath . "/" . JFile::getName($file), $file);
		$thumbfile = str_replace("." . $thumbext, $thumbsuffix . "." . $thumbext, $thumbfile);
		
		$filetmp = JPATH_ROOT . '/' . $file;
		$filetmp = str_replace("%20", " ", $filetmp);
		if (!Jfile::exists($filetmp))
			return;
		$size = getimagesize($filetmp);

		if ($size[0] > $size[1]) // paysage
		{
			$y = $x * $size[1] / $size[0];
		} else 
		{
//			$tmpx = $x;
//			$x = $y;
//			$y = $tmpx * $size[0] / $size[1];
			$x = $y * $size[0] / $size[1];
		}

		
		if ($size) {
			if (JFile::exists($thumbfile)) {
				return $thumbfile;
				// $thumbsize = getimagesize(JPATH_ROOT . '/' . $thumbfile);
				// if ($thumbsize[0] == $x || $thumbsuffix == '') {
					// return $thumbfile;
				// }
			}
			
			$thumbfolder = str_replace(JFile::getName($file), $thumbpath . "/", $filetmp);
			if (!JFolder::exists($thumbfolder)) { 
				JFolder::create($thumbfolder);
				JFile::copy(JPATH_ROOT . '/modules/mod_slideshowck/index.html', $thumbfolder . 'index.html' );
			}

			if ($size['mime'] == 'image/jpeg') {
				$img_big = imagecreatefromjpeg($filetmp); # On ouvre l'image d'origine
				$img_new = imagecreate($x, $y);
				# création de la miniature
				$img_mini = imagecreatetruecolor($x, $y) or $img_mini = imagecreate($x, $y);
				// copie de l'image, avec le redimensionnement.
				imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);

				imagejpeg($img_mini, JPATH_ROOT . '/' . $thumbfile);
			} elseif ($size['mime'] == 'image/png') {
				$img_big = imagecreatefrompng($filetmp); # On ouvre l'image d'origine
				$img_new = imagecreate($x, $y);
				# création de la miniature
				$img_mini = imagecreatetruecolor($x, $y) or $img_mini = imagecreate($x, $y);
				// copie de l'image, avec le redimensionnement.
				imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);

				imagepng($img_mini, JPATH_ROOT . '/' . $thumbfile);
			} elseif ($size['mime'] == 'image/gif') {
				$img_big = imagecreatefromgif($filetmp); # On ouvre l'image d'origine
				$img_new = imagecreate($x, $y);
				# création de la miniature
				$img_mini = imagecreatetruecolor($x, $y) or $img_mini = imagecreate($x, $y);
				// copie de l'image, avec le redimensionnement.
				imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);

				imagegif($img_mini, JPATH_ROOT . '/' . $thumbfile);
			}
			//echo 'Image redimensionnée !';
		}

		return $thumbfile;
	}

	/**
	 * Create the css
	 *
	 * $params JRegistry the module params
	 * $prefix integer the prefix of the params
	 *
	 * @return Array of css
	 */
	static function createCss($params, $prefix = 'menu') {
		$css = Array();
		$csspaddingtop = ($params->get($prefix . 'paddingtop') AND $params->get($prefix . 'usemargin')) ? 'padding-top: ' . self::testUnit($params->get($prefix . 'paddingtop', '0')) . ';' : '';
		$csspaddingright = ($params->get($prefix . 'paddingright') AND $params->get($prefix . 'usemargin')) ? 'padding-right: ' . self::testUnit($params->get($prefix . 'paddingright', '0')) . ';' : '';
		$csspaddingbottom = ($params->get($prefix . 'paddingbottom') AND $params->get($prefix . 'usemargin') ) ? 'padding-bottom: ' . self::testUnit($params->get($prefix . 'paddingbottom', '0')) . ';' : '';
		$csspaddingleft = ($params->get($prefix . 'paddingleft') AND $params->get($prefix . 'usemargin')) ? 'padding-left: ' . self::testUnit($params->get($prefix . 'paddingleft', '0')) . ';' : '';
		$css['padding'] = $csspaddingtop . $csspaddingright . $csspaddingbottom . $csspaddingleft;
		$cssmargintop = ($params->get($prefix . 'margintop') AND $params->get($prefix . 'usemargin')) ? 'margin-top: ' . self::testUnit($params->get($prefix . 'margintop', '0')) . ';' : '';
		$cssmarginright = ($params->get($prefix . 'marginright') AND $params->get($prefix . 'usemargin')) ? 'margin-right: ' . self::testUnit($params->get($prefix . 'marginright', '0')) . ';' : '';
		$cssmarginbottom = ($params->get($prefix . 'marginbottom') AND $params->get($prefix . 'usemargin')) ? 'margin-bottom: ' . self::testUnit($params->get($prefix . 'marginbottom', '0')) . ';' : '';
		$cssmarginleft = ($params->get($prefix . 'marginleft') AND $params->get($prefix . 'usemargin')) ? 'margin-left: ' . self::testUnit($params->get($prefix . 'marginleft', '0')) . ';' : '';
		$css['margin'] = $cssmargintop . $cssmarginright . $cssmarginbottom . $cssmarginleft;
		$bgcolor1 = ($params->get($prefix . 'bgcolor1') && $params->get($prefix . 'bgopacity')) ? self::hex2RGB($params->get($prefix . 'bgcolor1'), $params->get($prefix . 'bgopacity')) : $params->get($prefix . 'bgcolor1');
		$css['background'] = '';
		if ($params->get($prefix . 'bgopacity') == '0' AND $params->get($prefix . 'usebackground')) {
			$css['background'] = 'background: transparent;';
		} else if ($params->get($prefix . 'bgcolor1') AND $params->get($prefix . 'usebackground')) {
			$css['background'] = 'background: ' . $bgcolor1 . ';';
		}
//		$css['background'] = ($params->get($prefix . 'bgcolor1') AND $params->get($prefix . 'usebackground')) ? 'background: ' . $bgcolor1 . ';' : '';
		$css['background'] .= ( $params->get($prefix . 'bgimage') AND $params->get($prefix . 'usebackground')) ? 'background-image: url("' . JURI::ROOT() . $params->get($prefix . 'bgimage') . '");' : '';
		$css['background'] .= ( $params->get($prefix . 'bgimage') AND $params->get($prefix . 'usebackground')) ? 'background-repeat: ' . $params->get($prefix . 'bgimagerepeat') . ';' : '';
		$css['background'] .= ( $params->get($prefix . 'bgimage') AND $params->get($prefix . 'usebackground')) ? 'background-position: ' . $params->get($prefix . 'bgpositionx') . ' ' . $params->get($prefix . 'bgpositiony') . ';' : '';
		$css['gradient'] = ($css['background'] AND $params->get($prefix . 'bgcolor2') AND $params->get($prefix . 'usegradient')) ?
				"background: -moz-linear-gradient(top,  " . $params->get($prefix . 'bgcolor1', '#f0f0f0') . " 0%, " . $params->get($prefix . 'bgcolor2', '#e3e3e3') . " 100%);"
				. "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%," . $params->get($prefix . 'bgcolor1', '#f0f0f0') . "), color-stop(100%," . $params->get($prefix . 'bgcolor2', '#e3e3e3') . ")); "
				. "background: -webkit-linear-gradient(top,  " . $params->get($prefix . 'bgcolor1', '#f0f0f0') . " 0%," . $params->get($prefix . 'bgcolor2', '#e3e3e3') . " 100%);"
				. "background: -o-linear-gradient(top,  " . $params->get($prefix . 'bgcolor1', '#f0f0f0') . " 0%," . $params->get($prefix . 'bgcolor2', '#e3e3e3') . " 100%);"
				. "background: -ms-linear-gradient(top,  " . $params->get($prefix . 'bgcolor1', '#f0f0f0') . " 0%," . $params->get($prefix . 'bgcolor2', '#e3e3e3') . " 100%);"
				. "background: linear-gradient(top,  " . $params->get($prefix . 'bgcolor1', '#f0f0f0') . " 0%," . $params->get($prefix . 'bgcolor2', '#e3e3e3') . " 100%); "
				. "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $params->get($prefix . 'bgcolor1', '#f0f0f0') . "', endColorstr='" . $params->get($prefix . 'bgcolor2', '#e3e3e3') . "',GradientType=0 );" : '';
		$css['borderradius'] = ($params->get($prefix . 'useroundedcorners')) ?
				'-moz-border-radius: ' . $params->get($prefix . 'roundedcornerstl', '0') . 'px ' . $params->get($prefix . 'roundedcornerstr', '0') . 'px ' . $params->get($prefix . 'roundedcornersbr', '0') . 'px ' . $params->get($prefix . 'roundedcornersbl', '0') . 'px;'
				. '-webkit-border-radius: ' . $params->get($prefix . 'roundedcornerstl', '0') . 'px ' . $params->get($prefix . 'roundedcornerstr', '0') . 'px ' . $params->get($prefix . 'roundedcornersbr', '0') . 'px ' . $params->get($prefix . 'roundedcornersbl', '0') . 'px;'
				. 'border-radius: ' . $params->get($prefix . 'roundedcornerstl', '0') . 'px ' . $params->get($prefix . 'roundedcornerstr', '0') . 'px ' . $params->get($prefix . 'roundedcornersbr', '0') . 'px ' . $params->get($prefix . 'roundedcornersbl', '0') . 'px;' : '';
		$shadowinset = $params->get($prefix . 'shadowinset', 0) ? 'inset ' : '';
		$css['shadow'] = ($params->get($prefix . 'shadowcolor') AND $params->get($prefix . 'shadowblur') AND $params->get($prefix . 'useshadow')) ?
				'-moz-box-shadow: ' . $shadowinset . $params->get($prefix . 'shadowoffsetx', '0') . 'px ' . $params->get($prefix . 'shadowoffsety', '0') . 'px ' . $params->get($prefix . 'shadowblur', '') . 'px ' . $params->get($prefix . 'shadowspread', '0') . 'px ' . $params->get($prefix . 'shadowcolor', '') . ';'
				. '-webkit-box-shadow: ' . $shadowinset . $params->get($prefix . 'shadowoffsetx', '0') . 'px ' . $params->get($prefix . 'shadowoffsety', '0') . 'px ' . $params->get($prefix . 'shadowblur', '') . 'px ' . $params->get($prefix . 'shadowspread', '0') . 'px ' . $params->get($prefix . 'shadowcolor', '') . ';'
				. 'box-shadow: ' . $shadowinset . $params->get($prefix . 'shadowoffsetx', '0') . 'px ' . $params->get($prefix . 'shadowoffsety', '0') . 'px ' . $params->get($prefix . 'shadowblur', '') . 'px ' . $params->get($prefix . 'shadowspread', '0') . 'px ' . $params->get($prefix . 'shadowcolor', '') . ';' : '';
		$css['border'] = ($params->get($prefix . 'bordercolor') AND $params->get($prefix . 'borderwidth') AND $params->get($prefix . 'useborders')) ?
				'border: ' . $params->get($prefix . 'bordercolor', '#efefef') . ' ' . $params->get($prefix . 'borderwidth', '1') . 'px solid;' : '';
		$css['fontsize'] = ($params->get($prefix . 'usefont') AND $params->get($prefix . 'fontsize')) ?
				'font-size: ' . $params->get($prefix . 'fontsize') . ';' : '';
		$css['fontcolor'] = ($params->get($prefix . 'usefont') AND $params->get($prefix . 'fontcolor')) ?
				'color: ' . $params->get($prefix . 'fontcolor') . ';' : '';
		$css['fontweight'] = ($params->get($prefix . 'usefont') AND $params->get($prefix . 'fontweight')) ?
				'font-weight: ' . $params->get($prefix . 'fontweight') . ';' : '';
		/* $css['fontcolorhover'] = ($params->get($prefix . 'usefont') AND $params->get($prefix . 'fontcolorhover')) ?
		  'color: ' . $params->get($prefix . 'fontcolorhover') . ';' : ''; */
		$css['descfontsize'] = ($params->get($prefix . 'usefont') AND $params->get($prefix . 'descfontsize')) ?
				'font-size: ' . $params->get($prefix . 'descfontsize') . ';' : '';
		$css['descfontcolor'] = ($params->get($prefix . 'usefont') AND $params->get($prefix . 'descfontcolor')) ?
				'color: ' . $params->get($prefix . 'descfontcolor') . ';' : '';
		return $css;
	}

	/**
	 * Truncates text blocks over the specified character limit and closes
	 * all open HTML tags. The method will optionally not truncate an individual
	 * word, it will find the first space that is within the limit and
	 * truncate at that point. This method is UTF-8 safe.
	 *
	 * @param   string   $text       The text to truncate.
	 * @param   integer  $length     The maximum length of the text.
	 * @param   boolean  $noSplit    Don't split a word if that is where the cutoff occurs (default: true).
	 * @param   boolean  $allowHtml  Allow HTML tags in the output, and close any open tags (default: true).
	 *
	 * @return  string   The truncated text.
	 *
	 * @since   11.1
	 */
	public static function truncate($text, $length = 0, $noSplit = true, $allowHtml = true) {
		if ($length == 0) return '';
		// Check if HTML tags are allowed.
		if (!$allowHtml) {
			// Deal with spacing issues in the input.
			$text = str_replace('>', '> ', $text);
			$text = str_replace(array('&nbsp;', '&#160;'), ' ', $text);
			$text = JString::trim(preg_replace('#\s+#mui', ' ', $text));

			// Strip the tags from the input and decode entities.
			$text = strip_tags($text);
			$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

			// Remove remaining extra spaces.
			$text = str_replace('&nbsp;', ' ', $text);
			$text = JString::trim(preg_replace('#\s+#mui', ' ', $text));
		}

		// Truncate the item text if it is too long.
		if ($length > 0 && JString::strlen($text) > $length) {
			// Find the first space within the allowed length.
			$tmp = JString::substr($text, 0, $length);

			if ($noSplit) {
				$offset = JString::strrpos($tmp, ' ');
				if (JString::strrpos($tmp, '<') > JString::strrpos($tmp, '>')) {
					$offset = JString::strrpos($tmp, '<');
				}
				$tmp = JString::substr($tmp, 0, $offset);

				// If we don't have 3 characters of room, go to the second space within the limit.
				if (JString::strlen($tmp) > $length - 3) {
					$tmp = JString::substr($tmp, 0, JString::strrpos($tmp, ' '));
				}
			}

			if ($allowHtml) {
				// Put all opened tags into an array
				preg_match_all("#<([a-z][a-z0-9]*)\b.*?(?!/)>#i", $tmp, $result);
				$openedTags = $result[1];
				$openedTags = array_diff($openedTags, array("img", "hr", "br"));
				$openedTags = array_values($openedTags);

				// Put all closed tags into an array
				preg_match_all("#</([a-z]+)>#iU", $tmp, $result);
				$closedTags = $result[1];

				$numOpened = count($openedTags);

				// All tags are closed
				if (count($closedTags) == $numOpened) {
					return $tmp . '...';
				}
				$tmp .= '...';
				$openedTags = array_reverse($openedTags);

				// Close tags
				for ($i = 0; $i < $numOpened; $i++) {
					if (!in_array($openedTags[$i], $closedTags)) {
						$tmp .= "</" . $openedTags[$i] . ">";
					} else {
						unset($closedTags[array_search($openedTags[$i], $closedTags)]);
					}
				}
			}

			$text = $tmp;
		}

		return $text;
	}
	
	/**
	 * Convert a hexa decimal color code to its RGB equivalent
	 *
	 * @param string $hexStr (hexadecimal color value)
	 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
	 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
	 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
	 */
	static function hex2RGB($hexStr, $opacity) {
		if (!stristr($opacity, '.'))
			$opacity = $opacity / 100;
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		$rgbacolor = "rgba(" . $rgbArray['red'] . "," . $rgbArray['green'] . "," . $rgbArray['blue'] . "," . $opacity . ")";

		return $rgbacolor;
	}

	/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	static function testUnit($value) {
		if ((stristr($value, 'px')) OR (stristr($value, 'em')) OR (stristr($value, '%'))) {
			return $value;
		}

		if ($value == '') {
			$value = 0;
		}

		return $value . 'px';
	}

	/*
	 * Make empty slide object
	 */
	public static function initItem() {
		$item = new stdClass();
		$item->imgname = null;
		$item->imgthumb = null;
		$item->imgvideo = null;
		$item->slideselect = null;
		$item->imgcaption = null;
		$item->article = new stdClass();
		$item->slidearticleid = null;
		$item->imgalignment = null;
		$item->imgtarget = 'default';
		$item->imgtime = null;
		$item->imglink = null;
		$item->imgtitle = null;
		$item->article->title = null;
		$item->article->text = null;

		return $item;
	}

	/**
	 * Get a subtring with the max word setting
	 *
	 * @param string $text;
	 * @param int $length limit characters showing;
	 * @param string $replacer;
	 * @return tring;
	 */

	public static function substrword($text, $length = 100, $replacer = '...', $isStrips = true, $stringtags = '') {
		if($isStrips){
			$text = preg_replace('/\<p.*\>/Us','',$text);
			$text = str_replace('</p>','<br/>',$text);
			$text = strip_tags($text, $stringtags);
		}
		$tmp = explode(" ", $text);

		if (count($tmp) < $length)
			return $text;

		$text = implode(" ", array_slice($tmp, 0, $length)) . $replacer;

		return $text;
	}

	/**
	 * Get a subtring with the max length setting.
	 *
	 * @param string $text;
	 * @param int $length limit characters showing;
	 * @param string $replacer;
	 * @return tring;
	 */
	public static function substring($text, $length = 100, $replacer = '...', $isStrips = true, $stringtags = '') {
	
		if($isStrips){
			$text = preg_replace('/\<p.*\>/Us','',$text);
			$text = str_replace('</p>','<br/>',$text);
			$text = strip_tags($text, $stringtags);
		}
		
		if(function_exists('mb_strlen')){
			if (mb_strlen($text) < $length)	return $text;
			$text = mb_substr($text, 0, $length);
		}else{
			if (strlen($text) < $length)	return $text;
			$text = substr($text, 0, $length);
		}
		
		return $text . $replacer;
	}
}
