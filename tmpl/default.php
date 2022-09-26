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

$param = (new Joomla\Registry\Registry($params))->toObject();//***
$param->id = $module->id;

$base = JUri::base();

$id      = $params->get('id');
$positon = $params->get('position');

$mod_show = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$showtitle = $params->get('showtitle');

$title = ($params->get('title'));

$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));

$prepare = function ( $item, $param = null, $context = 'com_content.article'){
    return modMultiHelper::preparePlugin($item, $param, $context);
};

$params->get('items_link');
$params->get('items_image');

$params->get('content_tag3');

if($tag = $params->get('modules_tag3')){
    $tgs = explode('/', $tag);
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item  = $tgs[3] ?? FALSE;
}

$link_show = $params->get('link_show');
$link = $params->get('link');

$modules;
$modules_tag = $params->get('modules_tag');

$count_items = 0;
foreach ($modules as $items){
    if(is_array($items))
    $count_items += count($items);
}

if($module_tag2 = $params->get('module_tag2'))
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$count_items id$id $param->style\"  >";
else
    $param->moduleclass_sfx = $params->set('moduleclass_sfx',$params->get('moduleclass_sfx')." count$count_items ");

if($showtitle):
    $titlea = "";
    if($link_show == 'ha')
        $titlea = "<$header_tag class=\"$header_class \"><a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\">$title</a></$header_tag>";
    elseif($link_show || $link_show == 'ah')
        $titlea = "<a  href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\"><$header_tag class=\"$header_class\">$title</$header_tag></a>";
    elseif(empty($link_show))
        $titlea =  "<$header_tag class=\"$header_class\">$title</$header_tag>";

    if(in_array($param->style, ['System-none','none','no','0',0,'']))
        echo $titlea;

    else
        $$mod->title = "<a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\">$title</a>";
endif;

foreach ($modules as $type => $items){
    if(is_string($items)){
        echo $items;
        unset($modules[$type]);
        continue;
    }
    $order =  substr($type, 0, 2);
    $type = substr($type, 2);

    $count = count($items);
    $i = -1;

    if(isset($tag_block) && $tag_block)
        echo "<$tag_block class=\"items count$count order$order $type  \">";

foreach ($items as $id => $module){
    $module->text = $module->content =  $prepare($module->content);

    $i++;

    if(isset($tag_container) && $tag_container)
        echo "<$tag_container class=\"item i$i $type moduletable$module->moduleclass_sfx  id$module->id $module->type  \">";

    if($tag_item)
        echo "<$tag_item class=\" item_tag3 $module->moduleclass_sfx\">";

    if(empty($param->style_tag3) || $param->style_tag3 == '0'):
        echo $module->content;
    else:

    $content_tag3 = $params->get('content_tag3');

    if($module->module_tag == 'default'){
        $module->module_tag = 'div';
    }

    if($param->content_tag3 == 'default' && $module->module_tag && $module->style){
        echo "<$module->module_tag class='$module->moduleclass_sfx ' >";
        $content_tag3 = '';
    }
    elseif($param->content_tag3== 'item' && $module->module_tag){
        echo "<$module->module_tag class='$module->moduleclass_sfx ' >";
        $content_tag3 = '';
    }

    $link = $link_ = "";

    $module_title = $module->title;

    $isImage = function($url){
        $ext = strtolower(substr($url, strrpos($url, '.') + 1)) ;

        return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])?' imagelink '.$ext:' url ';
    };
    $class = $isImage($module->link);

$header_tag3 = $params->get('header_tag3');
$items_link = $params->get('items_link');

if($params->get('header_tag3') == 'default'){
    $header_tag3 = '';
}
if($params->get('header_tag3') == 'default' && ($module->showtitle??FALSE)){
    $header_tag3 = $module->header_tag ?: 'div';
}
if($params->get('header_tag3') == 'item'){
    $header_tag3 = $module->header_tag ?? '';
}

if($header_tag3 && $module->title){
    $module_title = $module->title;

    if($params->get('items_link')=='ha'){
        $module_title = "<$header_tag3 class=\" item_title $module->header_class\"><a href='$module->link' class='$class' title='$module->title' >$module->title</a></$header_tag3>";
    }
    if($params->get('items_link')=='ah'){
        $module_title = "<a href='$module->link' class='$class item_title' title='$module->title' ><$header_tag3 class=\"  $module->header_class\">$module->title</$header_tag3></a>";
    }
    if($params->get('items_link')=='a'){
        $module_title = "<a href='$module->link' class='$class item_title $module->header_class' title='$module->title' >$module->title</a>";
    }
    if($params->get('items_link')=='0'){
        $module_title = "<$header_tag3 class=\"$class item_title $module->header_class\" title='$module->title' >$module->title</$header_tag3>";
    }
    echo $prepare($module_title);
}

    if($module->fields??FALSE){
        echo $module->fields;
    }

    $isImage = function($url){
        $ext = strtolower(substr($url, strrpos($url, '.') + 1)) ;

        return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])?' imagelink '.$ext:' url ';
    };
    $class = $isImage($module->link);

    if($params->get('items_image') && $module->image):
    if($params->get('items_image') == 'ai')
        echo $prepare("<a class='$class image'  target='_blank' href='$module->link'  title='$module->title'
            data-toggle='lightbox'   data-gallery='gallery_$mod_id' data-type='image' onclick='return true;'>
            <img class=' item_image $module->moduleclass_sfx '   src='$base$module->image'></a>");
    if($params->get('items_image') == 'ii')
        echo $prepare("<a class='$class image'  target='_blank' href='$base$module->image'  title='$module->title'
            data-toggle='lightbox'   data-gallery='gallery_$mod_id' data-type='image' onclick='return true;'>
            <img class=' item_image $module->moduleclass_sfx '   src='$base$module->image'></a>");
    if($params->get('items_image') == 'di') {
        echo $prepare("<div class='$class image'><img  class='   item_image  $module->moduleclass_sfx '  data-action='zoom' src='$base$module->image'></div>");
    }
    if($params->get('items_image') == 'i')
        echo $prepare("<img class='$class image item_image $module->moduleclass_sfx'    src='$base$module->image'>");
    endif;

    if($param->content_tag3 != 'none'){
    if($content_tag3)
        echo "<$content_tag3 class=\" item_content  $module->moduleclass_sfx\">";
    echo  $module->content;

	if(isset($module->items) && is_array($module->items) && $module->items){
		$item = $module;
		require \Joomla\CMS\Helper\ModuleHelper::getLayoutPath('mod_multi', '_items');
	}

    if($content_tag3)
        echo "</$content_tag3>";
    }

    if($param->content_tag3== 'default' && $module->module_tag && $module->style){
        echo "</$module->module_tag>";
    }
    if($param->content_tag3== 'item' && $module->module_tag){
        echo "</$module->module_tag>";
    }

    endif;

    if($tag_item)
        echo "</$tag_item>";

    if($tag_container)
        echo "</$tag_container>";
}

    if(isset($tag_block) && $tag_block)
        echo "</$tag_block>";
}

if($module_tag2)
    echo "</$module_tag2>";

?>
