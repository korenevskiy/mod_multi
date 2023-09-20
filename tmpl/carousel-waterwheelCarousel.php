<?php defined('_JEXEC') or die;
/**------------------------------------------------------------------------
# mod_multi - Modules Conatinier
# ------------------------------------------------------------------------
# author    Sergei Borisovich Korenevskiy
# Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
# @package  mod_multi
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: //explorer-office.ru/download/joomla/category/view/1
# Technical Support:  Forum - //fb.com/groups/multimodule
# Technical Support:  Forum - //vk.com/multimodule
-------------------------------------------------------------------------*/

/*
*** ------------------------ Карусель с вращающимся фотками/карточками  -------------------------------------------- ***
 * https://www.jqueryscript.net/slider/jQuery-Waterwheel-Carousel-Plugin.html
*/

$param = new \Reg($params);//*** ->toObject()

$id      = $params->get('id');
$positon = $params->get('position');

$style=$params->get('style');
$mod_show = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$showtitle = $params->get('showtitle');

$title = ($params->get('title'));

$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));

$link_show = $params->get('link_show');
$link = $params->get('link');

$modules;
$modules_tag = $params->get('modules_tag');

if($module_tag2 = $params->get('module_tag2'))
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$mod_show id$id $style\"  >";

if($showtitle):
    $titlea = "";
    if($link_show == 'ha')
        $titlea = "<$header_tag class=\"$header_class\"><a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\">$title</a></$header_tag>";
    elseif($link_show || $link_show == 'ah')
        $titlea = "<a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\"><$header_tag class=\"$header_class\">$title</$header_tag></a>";
    elseif(empty($link_show))
        $titlea =  "<$header_tag class=\"$header_class\">$title</$header_tag>";

    if(in_array($style, ['System-none','none','no','0',0,'']))
        echo $titlea;
    else
        $$mod->title = $titlea;
endif;

if($tag = $params->get('modules_tag3')){
    $tgs = explode('/', $tag);
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item  = $tgs[3] ?? FALSE;

}

$elements = [];

foreach ($modules as $type => $items){
    $order =  substr($type, 0, 2);
    $type = substr($type, 2);
    if(is_string($items)){
        echo $items;
        continue;
    }
    $count = count($items);
    foreach ($items as $id => $module){
        $module->moduleclass_sfx = $module->moduleclass_sfx??'';
        $module->moduleclass_sfx .= "  countype$count order$order $type  ";
        $elements[] = $module;
    }
}

$count = count($elements);

echo "<div id='multislideshowid$param->id'  class='-gallery items count$count $moduleclass_sfx  id$param->id waterWheelCarousel' "
        . "style='height: 80vh;'>";
echo "<div  id='carousel' style=' position: relative; /*  margin-top: 200px;*/
    width: 100%;
    min-width: 900px;
    height: 700px;
    position: absolute !importasnt;
    clear: both;'>";
foreach ($elements as $i => $module):

    $base = JUri::base();

    if(empty($module->link))
        $module->link = $module->image;

    if(empty($module->title))
        $module->title = $module->image;

    echo "<a class=' '   rel='group' href='$module->link' title='$module->title'  >";
    echo "<img class='$module->moduleclass_sfx thumb' src='$base/$module->image' alt='$module->title' title='$module->title'  oncontextmenu='return false;'  style=' max-width: 220px; max-height: 320px'>";

    echo "</a>";

endforeach;
 echo "</div>";

 echo '<a href="#" id="prev">Prev</a>';
 echo '<a href="#" id="next">Next</a>';

echo "</div>";

if($module_tag2)
    echo "</$module_tag2>";


static $script;

if(empty($script)):

	JHtml::_('jquery.framework');
	JFactory::getApplication()->getDocument()->getWebAssetManager()->useStyle('jquery.ui')->useScript('jquery.ui');
//    $mod_path = Juri::base() . "/modules/mod_multi/media/carousel-waterwheelCarousel/";
    JHtml::script( Juri::base() . "modules/mod_multi/media/carousel-waterwheelCarousel/js/jquery.waterwheelCarousel.min.js");

$script = true;
endif;

?>


<script type="text/javascript">
/* MultiModule ModuleID:<?= $param->id .' - '. $style_layout?> */
jQuery(function($){
        var carousel = $("#carousel").waterwheelCarousel({
            <?= $param->json_layout ?? '' ?>

        });

        $('#prev').bind('click', function () {
          carousel.prev();
          return false
        });

        $('#next').bind('click', function () {
          carousel.next();
          return false;
        });

        $('#reload').bind('click', function () {
          newOptions = eval("(" + $('#newoptions').val() + ")");
          carousel.reload(newOptions);
          return false;
        });
});
</script>