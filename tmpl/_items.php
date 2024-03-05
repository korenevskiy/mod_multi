<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_popular
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Tags\Site\Helper\RouteHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Helper\ModuleHelper;

if(empty($item->items))
{
	return;
}

?>
<ul>
	<?php foreach ($item->items as $item):
		$title = htmlspecialchars( $item->link_title ?? $item->title, ENT_COMPAT, 'UTF-8');
		if(!isset($item->cat_id))
			$item->cat_id = 0;
		$cat_id = $item->cat_id ? "id=$item->cat_id" : '';
		
		$link_title = $title ? "title='$title'" : '';
		
		$link_class = $item->link_class ?? '';
		$link_class = $link_class  ? "class='$link_class'" : '';
	?>
	<li>
		<a <?= $link_title?> <?= $link_class?> href="<?= $item->link;?>">

		<?php if ($param->items_image && isset($item->image)) :
			$item->params = new \Reg($item->params);

			$src	= htmlspecialchars($item->image, ENT_COMPAT, 'UTF-8');
			if($src)
			{
				$layoutAttr = [
					'src'	=> $src,
					'class' => 'tag-image ',
					'alt'	=> $item->title,
				];

				echo LayoutHelper::render('joomla.html.image', array_merge($layoutAttr, ['itemprop' => 'thumbnail',]));
			}
		endif; ?>

		<?php if ($header_tag3) : ?>
			<span class="item-title"><?= $item->title; ?></span>
		<?php endif; ?>

			<?php if ($param->tags_count && isset($item->count)) : ?>
				<span class="tag-count badge bg-info"><?= $item->count; ?></span>
			<?php endif; ?>

			<?php if ($param->tags_category_title && isset($item->cat_title)) : ?>
				<span class="item-category badge bg-info"><?= $item->cat_title; ?></span>
			<?php endif; ?>
		</a>

		<?php 
		if(isset($item->items) && is_array($item->items) && $item->items)
			require ModuleHelper::getLayoutPath('mod_multi', '_items'); 
		?>
	</li>
	<?php endforeach; ?>
</ul>
