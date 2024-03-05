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

$prepare = function ( $item, $param = null, $context = 'com_content.article'){
    return ModMultiHelper::preparePlugin($item, $param, $context);
};

$link_show = $params->get('link_show');
$link = $params->get('link');

if($tag = $params->get('modules_tag3')){
    $tgs = explode('/', $tag);
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item  = $tgs[3] ?? FALSE;

}

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

$elements = [];
$order = 0;
$after_text = '';
$count = count($modules);

foreach ($modules as $type => &$items){
    $order++;
    if(is_string($items)){

        if(1 == $order){
            echo $items;
            continue;
        }
        if($count == $order){
            $after_text .= $items;
            continue;
        }

        $elements[$type.sprintf("%03d", $order)] = (object)[
            'content'=>$items,
            'title'=>$title,
            'type'=>'description',
            'id'=>$i,
            'header_tag'=>'',
            'header_class'=>'',
            'module_tag'=>$params->get('description_tag'),
            'moduleclass_sfx'=>'',
            'module'=>'description',
            'showtitle'=>FALSE,
            'style'=>'',
            'position'=>'',
            'link'=>'',
            'image'=>'',
        ];
        continue;
    }

    foreach ($items as $i => &$module){
        $elements[$type.sprintf("%03d", $i)] = $module;
    }

}

$count = count($elements);

if(isset($tag_block) && $tag_block)
    echo "<$tag_block  id='exTabs$id'  class=\"items count$count     \">";

$current = '';
$json_tabbootstrap = $params->get('json_tabbootstrap');
echo "<ul class='nav nav-tabs $json_tabbootstrap       panel-tabs' role='tablist'>";
foreach ($elements as $id => $module){
    $current = empty($current)?"active":"noactive";
    echo " <li class='nav-item'><a  class='$current nav-link' href='#tabmod$id$module->id' data-toggle='tab' role='tab'><strong>$module->title</strong></a></li>";
}
echo "</ul>";

$current = '';
$i = 0;

echo "<div class='tab-content'>";
foreach ($elements as $id => $module){
    $module->text = $module->content = $prepare($module->content ?? '');

    $i++;
    $current = empty($current)?"in show active ":"noactive";
    echo "<div id='tabmod$id$module->id' role='tabpanel' class=\"item  tab-pane fade $current  i$i type_$module->type sfx$module->moduleclass_sfx id$module->id $module->module  \">";

    $header_tag3 = $params->get('header_tag3','');
    if($header_tag3 == 'default' ){
        $header_tag3 = $module->showtitle? ($module->header_tag?:'span') : '';
    }
    if($header_tag3 == 'item'){
        $header_tag3 = $module->header_tag ?? 'div';
    }
    if($header_tag3){
        echo "<$header_tag3 class=\"$module->header_class\">";
        echo $module->title;
        echo "</$header_tag3>";
    }

    $content_tag3 = $params->get('content_tag3','');
    if($content_tag3 == 'default' ){
        $content_tag3 = $module->style? ($module->module_tag?:'div') : '';
    }
    if($content_tag3 == 'item'){
        $content_tag3 = $module->module_tag ?? '';
    }

    if($content_tag3)
        echo "<$content_tag3 class=\" $module->moduleclass_sfx\">";
    echo $prepare($module->content ?? '') ;
    if($content_tag3)
        echo "</$content_tag3>";

    echo "</div>";
}
echo "</div>";

    if(isset($tag_block) && $tag_block)
        echo "</$tag_block>";

echo $after_text;

if($module_tag2 = $params->get('module_tag2'))
    echo "</$module_tag2>";

?>
