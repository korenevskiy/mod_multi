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

use Joomla\Module\Multi\Site\Helper\MultiHelper as ModMultiHelper;

JHtml::_('jquery.framework');
JFactory::getApplication()->getDocument()->getWebAssetManager()->useStyle('jquery.ui')->useScript('jquery.ui');

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

$prepare = function ( $item, $param = null, $context = 'com_content.article'){
    return ModMultiHelper::preparePlugin($item, $param, $context);
};

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

echo "<div class=\"slider items slick  count$count $moduleclass_sfx \"  itemscope itemtype=\"http://schema.org/ImageGallery\">";

foreach ($elements as $i => & $module){
    $module->text =  $module->content = $prepare($module->content ?? '');

    if($tag_container)
        echo "<$tag_container class=\"item i$i $type sfx$module->moduleclass_sfx id$module->id $module->module  \">";

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
    if($params->get('items_image') == 'ai')
        echo $prepare("<a class=\"$class image\"  target=\"_blank\" href=\"$module->link\"  title=\"$module->title\"
            data-toggle=\"lightbox\"   data-gallery=\"gallery_$mod_id\" data-type=\"image\" onclick=\"return true;\">
            <img class=\" item_image $module->moduleclass_sfx\" width=\"300\" src=\"$module->image\"></a>");
    if($params->get('items_image') == 'ii')
        echo $prepare("<a class=\"$class image\"  target=\"_blank\" href=\"$module->image\"  title=\"$module->title\"
            data-toggle=\"lightbox\"   data-gallery=\"gallery_$mod_id\" data-type=\"image\" onclick=\"return true;\">
            <img class=\" item_image $module->moduleclass_sfx\" width=\"300\" src=\"$module->image\"></a>");
    if($params->get('items_image') == 'di')
        echo $prepare("<div class='$class image'><img width=\"300\" class=\"   item_image  $module->moduleclass_sfx\"  data-action=\"zoom\"  src=\"$module->image\"></div>");
    if($params->get('items_image') == 'i')
        echo $prepare("<img class=\"$class image item_image $module->moduleclass_sfx\" src=\"$module->image\">");
endif;

$title_tag = $param->title_tag ?: 'div';
$items_link = $params->get('items_link');
if($title_tag == 'default'){
    $title_tag = 'div';
}
if($title_tag == 'item'){
    if($module->header_tag && $module->showtitle)
        $title_tag = $module->header_tag;
    else
        $items_link = FALSE;
}
if($items_link && $module->title):
    if($items_link == 'ah')
        echo "<a class=\"$class\"  target=\"_blank\" href=\"$module->link\"  title=\"$module->title\" ><$title_tag class='title'>";
    elseif($items_link == 'ha')
        echo  "<$title_tag class='title'><a class=\"$class\"  target=\"_blank\" href=\"$module->link\"  title=\"$module->title\" >";
    elseif($title_tag)
        echo  "<$title_tag class='title'>";
    if($module->title)
        echo "$module->title";
    if($items_link == 'ah')
        echo  "</$title_tag></a>";
    elseif($items_link == 'ha')
        echo "</a></$title_tag>";
    elseif($title_tag)
        echo "</$title_tag>";
endif;

$item_tag = $params->get('item_tag');
if($item_tag == 'default')
    $item_tag = $module->module_tag;
if(($module->content ?? '') && $item_tag != 'none' && $item_tag) {
    echo ("<$item_tag class='info'>$module->content</$item_tag>");
}
if(($module->content ?? '') && empty($item_tag))
    echo ($module->content);

    if(isset($module->urls)	&& $module->urls->urla){
        $class = $isImage($module->urls->urla);
        $class .= ($module->urls->targeta===2)?"lightbox":($module->urls->targeta===3)?"modal":'';
        $target = ($module->urls->targeta==0)?"_parent":(($module->urls->targeta==1)?"_blank":(($module->urls->targeta==2)?"_self":''));
        echo $prepare("<a class=\"linka urls $class\"  target=\"$target\" href=\"{$module->urls->urla}\"  title=\"{$module->urls->urlatext}\" >{$module->urls->urlatext}</a>");
    }
    if(isset($module->urls)	&& $module->urls->urlb){
        $class = $isImage($module->urls->urlb);
        $class .= ($module->urls->targetb===2)?"lightbox":($module->urls->targetb===3)?"modal":'';
        $target = ($module->urls->targetb===0)?"_parent":($module->urls->targetb===1)?"_blank":($module->urls->targetb===2)?"_self":'';
        echo $prepare("<a class=\"linkb urls $class\"  target=\"$target\" href=\"{$module->urls->urlb}\"  title=\"{$module->urls->urlbtext}\" >{$module->urls->urlbtext}</a>");
    }
    if(isset($module->urls)	&& $module->urls->urlc){
        $class = $isImage($module->urls->urlc);
        $class .= ($module->urls->targetc===2)?"lightbox":($module->urls->targetc===3)?"modal":'';
        $target = ($module->urls->targetc===0)?"_parent":($module->urls->targetc===1)?"_blank":($module->urls->targetc===2)?"_self":'';
        echo $prepare("<a class=\"linkc urls $class\"  target=\"$target\" href=\"{$module->urls->urlc}\"  title=\"{$module->urls->urlctext}\" >{$module->urls->urlctext}</a>");
    }

    if($module->fields){
        echo $module->fields;
    }

    if($tag_container)
        echo "</$tag_container>";
}

echo "</div>";

if($module_tag2)
    echo "</$module_tag2>";

    $mod_path = "modules/mod_multi/media/";

    JHtml::script($mod_path."slick-1.9.0/slick.min.js");
    JHtml::stylesheet($mod_path."slick-1.9.0/slick.css");
    JHtml::stylesheet($mod_path."slick-1.9.0/styles.css");

$id      = $params->get('id');
$json_slick      = $params->get('json_layout','') ?: $params->get('json_slick','');

 $script = <<< script

jQuery( ()=>{

    console.log('jQuery:',jQuery.fn.jquery,' -slick -MultiModule:',{$id});
    jQuery(".slider.items.slick.id{$id}").slick({
        {$json_slick}
    });
        return;

});

script;

JFactory::getDocument()->addScriptDeclaration($script);

return;
?>

