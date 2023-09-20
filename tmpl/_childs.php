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

if(empty($item->childs))
{
	return;
}

$childs = $item->childs;
?>
<ul>
	<?php foreach ($childs as $item):
		$title = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8');
		$cat_id = $item->cat_id ? "id=$item->cat_id" : '';
	?>
	<li>
		<a title="<?= $title?>" href="<?= $item->link;?>">

		<?php if ($image_display && isset($item->images)) :
			$item->params = new \Reg($item->params);
			$item->images = json_decode($item->images, false);

			$src	= htmlspecialchars($item->images->image_intro, ENT_COMPAT, 'UTF-8');
			if($src)
			{
				$layoutAttr = [
					'src'	=> $src,
					'class' => 'tag-image ' . (empty($item->images->float_intro) ? $item->params->get('float_intro',false) : $item->images->float_intro),
					'alt'	=> empty($item->images->image_intro_alt) && empty($item->images->image_intro_alt_empty) ? false : $item->images->image_intro_alt,
				];

				echo LayoutHelper::render('joomla.html.image', array_merge($layoutAttr, ['itemprop' => 'thumbnail',]));
			}
		endif; ?>

		<?php if ($title_display) : ?>
			<span class="tag-title"><?= $title; ?></span>
		<?php endif; ?>

			<?php if ($count_display) : ?>
				<span class="tag-count badge bg-info"><?php echo $item->count; ?></span>
			<?php endif; ?>

			<?php if ($categories_titles) : ?>
				<span class="tag-category badge bg-info"><?php echo $item->cat_title; ?></span>
			<?php endif; ?>
		</a>

		<?php require ModuleHelper::getLayoutPath('mod_category_tags', '_childs'); ?>
	</li>
	<?php endforeach; ?>
</ul>
