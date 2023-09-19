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

$items = $item->items;
?>
<ul>
	<?php foreach ($items as $item):
		$title = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8');
		$cat_id = $item->cat_id ? "id=$item->cat_id" : '';
	?>
	<li>
		<a title="<?= $title?>" href="<?= $item->link;?>">

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
				<span class="tag-count badge bg-info"><?php echo $item->count; ?></span>
			<?php endif; ?>

			<?php if ($param->tags_category_title && isset($item->cat_title)) : ?>
				<span class="item-category badge bg-info"><?php echo $item->cat_title; ?></span>
			<?php endif; ?>
		</a>

		<?php require ModuleHelper::getLayoutPath('mod_multi', '_items'); ?>
	</li>
	<?php endforeach; ?>
</ul>
