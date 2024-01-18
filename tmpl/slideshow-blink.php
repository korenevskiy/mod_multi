<?php
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

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JFactory::getApplication()->getDocument()->getWebAssetManager()->useStyle('jquery.ui')->useScript('jquery.ui');
//JHtml::_('jquery.ui', array('core', 'sortable'));

$param = new \Reg($params);//*** ->toObject()

$id = $mod_id   = $params->get('id');
$positon        = $params->get('position');

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

$prepare = function ( $item, $param = null, $context = 'com_content.article'){
    return modMultiHelper::preparePlugin($item, $param, $context);
};

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

$keys = array_keys($modules);

$elements = [];

foreach ($modules as $type => $items){
    if(is_string($items)){
        echo $prepare($items) ;
        unset($modules[$type]);
        continue;
    }
    $order =  substr($type, 0, 2);
    $type = substr($type, 2);
    $count = count($items);
    foreach ($items as $id => $module){
        $module->moduleclass_sfx = $module->moduleclass_sfx??'';
        $module->moduleclass_sfx .= "  countype$count order$order $type  ";
        $elements[] = $module;
    }
}

$keys = array_keys($elements);

$count = count($elements);

echo "<div class=\"slider items blink blink-view count$count $moduleclass_sfx \"  itemscope itemtype=\"http://schema.org/ImageGallery\">";

foreach ($elements as $i => & $module){
    $module->text =  $module->content = $prepare($module->content ?? '');

        echo "<div class=\"item viewSlide i$i $type sfx$module->moduleclass_sfx id$module->id $module->module  \">";

    if(empty($module->link))
        $module->link = $module->image;

    if(empty($module->title))
        $module->title = pathinfo($module->image, PATHINFO_FILENAME);

$isImage = function($url){
    $ext = strtolower(substr($url, strrpos($url, '.') + 1)) ;

    return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])?' imagelink '.$ext:' url ';
};
$class = $isImage($module->link);

if($params->get('items_image') && $module->image):

        echo ("<img class=\"image $module->moduleclass_sfx\" width=\"300\"  src=\"$module->image\">");
endif;

        echo "</div>";
}

echo "</div>";

if($module_tag2)
    echo "</$module_tag2>";

/* Оригинал скрипта
 * https://www.jqueryscript.net/slideshow/Image-Slideshow-Blink-Slider.html
 * https://github.com/fermercadal/jquery.blink.js
 */

JHtml::stylesheet("modules/mod_multi/media/Blink/css/blink.css");
JHtml::script("modules/mod_multi/media/Blink/js/jquery.blink.js");

$id      = $params->get('id');
$json_blink      = $params->get('json_layout','') ?: $params->get('json_blinkSlideshow','');

 $script = <<< script

jQuery( function() {

        console.log('jQuery:',jQuery.fn.jquery,' -BlinkSlideShow -MultiModule:',{$id});

        jQuery(".slider.items.blink.id{$id}").blink({
            {$json_blink}
        });
        return;

});

script;

JFactory::getDocument()->addScriptDeclaration($script);

return;
?>

