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
*** ------------------------ Просто плитки с картинками -------------------------------------------- ***
*/

$param = $params;//*** new \Reg($params)->toObject()

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

    if(in_array($style, ['System-none','none','no','0',0,''],true))
        echo $titlea;
    else
        ${$mod}->title = $titlea;
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

$keys = array_keys($elements);

$count = count($elements);

echo "<div id='gallery' xid='multislideshowid$param->id'  class='gallery items count$count $moduleclass_sfx  id$param->id b1njPolaroidGallery' style='height: 90vh;'>";
echo "<ul>";
foreach ($elements as $i => $module){

    if(empty($module->link))
        $module->link = $module->image;

    if(empty($module->title))
        $module->title = $module->image;
    echo '<li>';
    echo "<a class='xfancybox' data-fancybox='gallery' rel='group' href='$module->link' title='$module->title'  >";
    echo "<img class='$module->moduleclass_sfx thumb' src='$module->image' alt='$module->title' title='$module->title' style=' max-width: 220px; max-height: 320px'>";

    echo "</a>";
    echo '</li>';

}
 echo "</ul>";

echo "</div>";

if($module_tag2)
    echo "</$module_tag2>";

static $script;

JHtml::_('jquery.framework');
JFactory::getApplication()->getDocument()->getWebAssetManager()->useStyle('jquery.ui')->useScript('jquery.ui');

if(empty($script)) {

    $mod_path = Juri::base() . "modules/mod_multi/media/";

    JHtml::script($mod_path . "b1njPolaroidGallerry/jquery.b1njPolaroidGallerry.js");
    JHtml::stylesheet($mod_path . "b1njPolaroidGallerry/b1njPolaroidGallery.css");

    JHtml::script($mod_path . "fancybox_3/jquery.fancybox.min.js");
    JHtml::stylesheet($mod_path . "fancybox_3/jquery.fancybox.min.css");

$script = <<< script

/* MultiModule ModuleID:$param->id - $style_layout */
jQuery(function($){
    $(".fancybox").fancybox();
    $("#multislideshowid$param->id,#gallery").b1njPolaroidGallery();
});

script;

JFactory::getDocument()->addScriptDeclaration($script);

}

?>

