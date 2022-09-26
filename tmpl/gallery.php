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

/*
*** ------------------------ Просто плитки с картинками -------------------------------------------- ***
*/

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JHtml::_('jquery.ui');

$param = (new Joomla\Registry\Registry($params))->toObject();//***

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

echo "<div class=\"gallery items count$count $moduleclass_sfx  \">";

foreach ($elements as $i => $module){

    if($tag_container)
        echo "<$tag_container class=\"item i$i $type sfx$module->moduleclass_sfx id$module->id $module->module  \">";

    if(empty($module->link))
        $module->link = $module->image;

    if(empty($module->title))
        $module->title = $module->image;

    echo "<a class=\"linkthumb\"  target=\"_blank\" href=\"$module->link\"  title=\"$module->title\" >";
    echo "<img class=\" $module->moduleclass_sfx thumb \" src=\"$module->image\" style=\" width: 220px;\">";
if($module->title) echo  "<span class='title'>$module->title</span>";
if($module->content) echo  "<span class='info'>$module->content</span>";
    echo "</a>";

    if($tag_container)
        echo "</$tag_container>";
}

echo "</div>";

if($module_tag2)
    echo "</$module_tag2>";

if (FALSE && empty($script)) {

    $mod_path = Juri::base() . "modules/mod_multi/media/";

    JHtml::stylesheet($mod_path . "gallery.css");

    static $script = <<< script

jQuery( function() {

        console.log(jQuery.fn.jquery);

});

script;

JFactory::getDocument()->addScriptDeclaration($script);

}

?>
