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

echo "<div id='multislideshowid$param->id'  class='-gallery items count$count $moduleclass_sfx  id$param->id slider slider_clock ' "
        . "style='height: 80vh;'>";

foreach ($elements as $i => $module):

/* Show content modules   */
    
    $base = JUri::base();

    echo "<div id='bu$i'>";

    echo "<img class='$module->moduleclass_sfx thumb' src='$base/$module->image' alt='$module->title' title='$module->title'  oncontextmenu='return false;'  style=' '>";

    echo '</div>';

endforeach;

 echo '<div style="background-color: rgba(113, 0, 0, 0.7) !important;     border-left-color: white;" class="prev_button"> </div>';
 echo '<div style="background-color: rgba(113, 0, 0, 0.7) !important;   "  class="next_button"> </div>';

echo "</div>";

if($module_tag2)
    echo "</$module_tag2>";


static $script;

// <editor-fold defaultstate="collapsed" desc="Scrypt Carousel for count 5">
if(empty($script)):

JHtml::_('jquery.framework');
JFactory::getApplication()->getDocument()->getWebAssetManager()->useStyle('jquery.ui')->useScript('jquery.ui');

    $mod_path = Juri::base() . "modules/mod_multi/media/carousel-EasySlides/";
    JHtml::script($mod_path . "jquery.easy_slides.js");
    JHtml::stylesheet($mod_path . "jquery.easy_slides.css");

?>
<script type="text/javascript">
/* MultiModule ModuleID:<?= $param->id .' - '. $param->layout ?> */
jQuery(function($){
    $('.id<?=$param->id?>.slider_clock').EasySlides({
        <?= $param->json_layout ?? '' ?>
    })

});
</script>
<?php

endif;

// </editor-fold>
?>
<style type="text/css">
.slider_clock .next_button:after{
  border-left-color: white;
}
.slider_clock .prev_button:after{
  border-right-color: white;
}
</style>
