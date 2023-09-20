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


//$wa = new \Joomla\CMS\WebAsset\WebAssetManager;
//$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();
//$wa->registerAndUseScript('Instascan', 'https://rawgit.com/schmich/instascan-builds/master/instascan.min.js', [], ['defer' => true]);
//$wa->registerAndUseStyle('slideshowck','modules/mod_multi/media/slideshowCK/administrator/themes/default/css/camera.css');

//$wa->registerScript('jquery','modules/mod_multi/media/jquery/jquery-3.7.0.min.js',
//		['version'=>'3.6.0','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],[]);
//$wa->registerScript('jquery-migrate-old','modules/mod_multi/media/jquery/jquery-migrate-1.4.1.min.js',
//		['version'=>'1.4.1','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],['jquery']);
//$wa->registerScript('jquery-migrate','modules/mod_multi/media/jquery/jquery.migrate-3.4.1.min.js',
//		['version'=>'3.4.0','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],['jquery']);
//$wa->registerScript('jquery-ui','modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.js',
//		['version'=>'1.13.2','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],['jquery']);
//$wa->registerStyle('jquery-ui','modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.css',
//		['version'=>'1.13.2','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],['jquery']);

//JFactory::getApplication()->getDocument()->getWebAssetManager()->useStyle('jquery-ui')->useScript('jquery-ui');
//$wa->registerScript('jquery-ui', 'modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.js');
//$wa->registerStyle('jquery-ui', 'modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.css');
//$wa->addInlineScript('document.addEventListener("DOMContentLoaded", function(){form_QR_'.$id.'.t = "'.JFactory::getApplication()->getFormToken().'"});');

$param = new \Reg($params);//*** ->toObject()
$param->id = $module->id;

$base = JUri::base();

$id      = $param->id;
$positon = $param->position;

$mod_show = count($modules);

$module_tag 		= $param->module_tag ?? 'div';
$moduleclass_sfx 	= $param->moduleclass_sfx;

//$showtitle = $params->get('showtitle');
$showtitle = $module->showtitle;

$title = $param->title ?? '';

$header_tag = $param->header_tag ?? 'h3';
$header_class = htmlspecialchars($param->header_class ?? 'module-header');

$prepare = function ( $item, $param = null, $context = 'com_content.article'){
    return modMultiHelper::preparePlugin($item, $param, $context);
};

$param->items_link;
$param->items_image;
$param->content_tag3;

if($tag = $param->modules_tag3){
    $tgs = explode('/', $tag);
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item  = $tgs[3] ?? FALSE;
}

$link_show = $param->link_show;
$link = $param->link;
$modules;
$modules_tag = $param->modules_tag;

$count_items = 0;
foreach ($modules as $items){
    if(is_array($items))
    $count_items += count($items);
}

if($module_tag2 = $param->module_tag2)
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$count_items id$id $param->style\"  >";
else
    $param->moduleclass_sfx = $param->moduleclass_sfx." count$count_items ";


//if($param->id == 112)
//toPrint($param->style, '$param->style', 0, 'message', true);
//toPrint(null, '', 0, '', false);
//if($param->id == 112)
//toPrint($showtitle, '$showtitle', 0, 'message', true);
//if($param->id == 112)
//toPrint($link_show, '$link_show', 0, 'message', true);

if($showtitle):
    $titlea = "";
    if($link_show == 'ha')
        $titlea = "<$header_tag class='$header_class '><a href='$link' title='".strip_tags($title)."' class='id$id multiheadera'>$title</a></$header_tag>";
    elseif($link_show == 'ah')
        $titlea = "<a  href='$link' title='".strip_tags($title)."' class='id$id multiheadera'><$header_tag class='$header_class'>$title</$header_tag></a>";
    elseif($link_show == 'h')
        $titlea = "<$header_tag class='$header_class id$id multiheadera' title='".strip_tags($title)."' >$title</$header_tag>";
    elseif($link_show == 'a')
        $titlea = "<a href='$link'  title='".strip_tags($title)."' class='id$id multiheadera $header_class'>$title</a>";
    else
        $titlea =  "<$header_tag class='$header_class'>$title</$header_tag>";

    if(in_array($param->style, ['System-none','none','no','0',0,'']))
        echo $titlea;
    else
        $$mod->title = "<a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\">$title</a>";
endif;

foreach ($modules as $type => $items):
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
    $module->text = $module->content =  $prepare($module->content ?? '');

    $i++;

    if(isset($tag_container) && $tag_container)
        echo "<$tag_container class=\"item i$i $type moduletable$module->moduleclass_sfx  id$module->id $module->type  \">";

    if($tag_item)
        echo "<$tag_item class=\" item_tag3 $module->moduleclass_sfx\">";

if(empty($param->get('style_tag3')) || $param->style_tag3 == '0'):
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

        return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])?' imagelink '.$ext : ' url ';
    };
    $class = $isImage($module->link);

$header_tag3 = $param->header_tag3 ?? '';
$items_link = $param->items_link;

if($params->get('header_tag3') == 'default'){
    $header_tag3 = '';
}
if($params->get('header_tag3') == 'default' && ($module->showtitle??FALSE)){
    $header_tag3 = $module->header_tag ?: 'div';
}
if($params->get('header_tag3') == 'item'){
    $header_tag3 = $module->header_tag ?? '';
}
if($params->get('header_tag3') == '0'){
    $module_title = $header_tag3 = '';
}

if($header_tag3 && $module->title){
    $module_title = $module->title;//'';// $module->title;

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
    echo  $module->content ?? '';

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
	
endforeach; // foreach $modules as $type => $items

if($module_tag2)
    echo "</$module_tag2>";

?>
