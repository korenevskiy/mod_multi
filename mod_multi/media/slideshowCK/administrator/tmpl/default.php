<?php
/**
 * @copyright	Copyright (C) 2012-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Slideshow CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

// get the slideshow width
$width = ($params->get('width') AND $params->get('width') != 'auto') ? ' style="width:' . SlideshowckHelper::testUnit($params->get('width')) . ';"' : '';
?>
<div class="slideshowck <?php echo $params->get('moduleclass_sfx'); ?> camera_wrap <?php echo $params->get('skin'); ?>" id="camera_wrap_<?php echo $module->id; ?>"<?php echo $width; ?>>
	<?php
	$i = 0;
	foreach ($items as $item) {
		if ($params->get('limitslides', '') && $i >= $params->get('limitslides', ''))
			break;

		// B/C for V1
		if (isset($item->imgname) && ! isset($item->image)) SlideshowckHelper::legacyUpdateItem($item);

		// automatically create the minified thumb and use it
		$item->thumb = $item->image;
		if ($params->get('thumbnails', '1') == '1' && $params->get('autocreatethumbs','1') && $params->get('usethumbstype', 'mini') == 'mini') {
			$item->thumb = SlideshowckHelper::resizeImage($item->image, $params->get('thumbnailwidth', '182'), $params->get('thumbnailheight', '187'));
		}
		// use the minified thumb but don't create it
		else if ($params->get('thumbnails', '1') == '1' && $params->get('usethumbstype','mini') == 'mini'){
			$thumbext = explode(".", $item->image);
			$thumbext = end($thumbext);
			$thumbfile = str_replace(basename($item->image), "th/" . basename($item->image), $item->image);
			$thumbfile = str_replace("." . $thumbext, "_th." . $thumbext, $thumbfile);
			if (JUri::root(true) && substr($thumbfile, 0, (int)strlen(JUri::root(true) . '/')) == (JUri::root(true) . '/')) {
				$thumbfile = str_replace(JUri::root(true) . '/', '', $thumbfile);
			}
			if (file_exists(JPATH_ROOT . '/' . trim($thumbfile, '/'))) {
				$item->thumb = JUri::root(true) . '/' . trim($thumbfile, '/');
			}
		}

		// create new images for mobile
		if ($params->get('usemobileimage', '0') && $params->get('autocreatethumbs','1')) { 
			$resolutions = explode(',', $params->get('mobileimageresolution', '640'));
			foreach ($resolutions as $resolution) {
				SlideshowckHelper::resizeImage($item->image, (int)$resolution, '', (int)$resolution, '');
			}
		}

		if ($item->alignment != 'default') {
			$alignment = ' data-alignment="' . $item->alignment . '"';
		} else {
			$alignment = '';
		}
		$datacaptiontitle = str_replace("|dq|", "\"", $item->title);
		$datacaptiontext = str_replace("|dq|", "\"", $item->text);
		$datacaptionforlightbox = $datacaptiontitle . ( $datacaptiontext ? '::' . $datacaptiontext : '');
		$dataalt = htmlspecialchars(str_replace("\"", "&quot;", str_replace(">", "&gt;", str_replace("<", "&lt;", $datacaptiontitle))));
		$datatitle = ($params->get('lightboxcaption', 'caption') != 'caption') ? 'data-title="' . htmlspecialchars(str_replace("\"", "&quot;", str_replace(">", "&gt;", str_replace("<", "&lt;", $datacaptionforlightbox)))) . '" ' : '';
		$album = ($params->get('lightboxgroupalbum', '0')) ? '[albumslideshowck' .$module->id .']' : '';
		$target = ($item->target == 'default') ? $params->get('linktarget') : $item->target;
		$datarel = ($target == 'lightbox') ? 'data-rel="lightbox' . $album . '" ' : '';
		$datarel .= ($target == '_blank') ? 'data-rel="noopener noreferrer" ' : '';
		$datatime = ($item->time) ? ' data-time="' . $item->time . '"' : '';
		$link = $params->get('linkautoimage', '0') == '1' && $item->image && !$item->link ? $item->image : $item->link;

		if ($params->get('lightboxautolinkimages', '0') == '1') {
			$item->link = $item->link ? $item->link : $item->image;
		}

		$linkposition = $params->get('linkposition', 'fullslide');
		$linkClass = ( $linkposition == 'button' ? $params->get('linkbuttonclass', '') . ' camera-button' : ' camera-link' );
		$linkTarget = ( $target == '_blank' ? ' target="_blank" rel="noopener noreferrer"' : '' );
		$startLink = '<a class="' . $linkClass .'" href="' . $link . '"' . $linkTarget . '>';
		?>
		<div <?php echo $datarel . $datatitle; ?>data-alt="<?php echo $dataalt; ?>" data-thumb="<?php echo $item->thumb; ?>" data-src="<?php echo $item->image; ?>" <?php if ($link && $linkposition == 'fullslide') echo 'data-link="' . $link . '" data-target="' . $target . '"'; echo $alignment . $datatime; ?>>
			<?php if ($params->get('imageforseo', '0')) { ?>
				<img src="<?php echo $item->image; ?>" style="display:none" alt="<?php echo htmlspecialchars($item->title) ?>" />
			<?php } ?>
			<?php if ($item->video) { ?>
				<?php if (strpos($item->video, 'http') !== 0) {
				$autoplay = $item->videoautoplay == '1' ? 'data-autoplay="1" muted="muted"' : '';
				$videoloop = $item->videoloop == '1' ? ' loop' : '';
				$videocontrols = $item->videocontrols == '0' ? ($autoplay ? '' : ' onclick="this.play()"') : ' controls';
				?>
				<video src="<?php echo $item->video; ?>" width="100%" height="100%" playsinline <?php echo $autoplay ?><?php echo $videoloop ?><?php echo $videocontrols ?>>
					<source src="<?php echo $item->video ?>" >
				</video>
				<?php } else { ?>
				<iframe src="<?php echo $item->video; ?>" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
				<?php } ?>
			<?php
			}
			if (($params->get('usecaption', '1') == '1') && ($item->title || $item->text) && (($params->get('lightboxcaption', 'caption') != 'title' || $target != 'lightbox') || !$link)) {
			?>
				<?php if ($params->get('usecaption', '1')) { ?>
				<div class="camera_caption <?php echo $params->get('captioneffect', 'moveFromBottom')?>">
					<?php 
//					$showcaption = $params->get('usecaption', '1') == '1' && ($item->title || $item->desc);
					$showtitle = $params->get('usetitle', '1') == '1' && $item->title;
					$showdescription = $params->get('usecaptiondesc', '1') == '1' && $item->text;
					if ($showtitle) { ?>
					<div class="camera_caption_title">
						<?php 
						$item->title = str_replace("|dq|", "\"", $item->title);
						if ($link && $linkposition == 'title') {
							echo $startLink . $item->title . '</a>';
						} else {
							echo $item->title;
						} ?>
					</div>
					<?php } ?>
					<?php if ($showdescription) { ?>
					<div class="camera_caption_desc">
						<?php 
						$caption = str_replace("|dq|", "\"", $item->text);
						if ($params->get('content_prepare', 0)) $caption = JHTML::_('content.prepare', $caption);
						$textlength = (int)$params->get('textlength', '0');
						if ($params->get('fixhtml', '0') == '1' && trim($caption)) {
							// Parse the html code of the text into a fixer to avoid bad rendering issues
							$htmlfixer = new SlideshowCKHtmlFixer();
							$captionFixed = $htmlfixer->getFixedHtml(trim($caption));
							$caption = $captionFixed;
						}
						if ($params->get('striptags', '0') == '1' && $item->texttype != 'pagebuilderck') {
							$caption = strip_tags($caption);
						}
						if ($textlength > 0) {
							$caption = SlideshowckHelper::substring($caption, $textlength, '...', false);
						}
						echo $caption;
						?>
					<?php
					if (isset($item->more) && count($item->more)) {
						foreach ($item->more as $m) {
							echo $m;
						}
					}
					?>
					</div>
					<?php } ?>
					<?php if ($link && $linkposition == 'caption') {
						echo $startLink . '</a>';
					} ?>
					<?php if ($link && $linkposition == 'button') { ?>
						<?php echo $startLink . JText::_($params->get('linkbuttontext', 'MOD_SLIDESHOWCK_LINK_BUTTON_TEXT')) . '</a>'; ?>
					<?php } ?>
					</div>
				<?php } ?>
			<?php
			}
			?>
		</div>
<?php 
		$i++;
	}
?>
</div>
<div style="clear:both;"></div>
