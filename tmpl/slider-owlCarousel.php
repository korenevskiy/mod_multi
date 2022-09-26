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
JHtml::_('jquery.ui');
JHtml::_('jquery.ui', array('core', 'sortable'));

$param = (new Joomla\Registry\Registry($params))->toObject();//***

$id = $mod_id   = $params->get('id');
$positon        = $params->get('position');

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
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$mod_show id$id $param->style\"  >";

if($showtitle):
    $titlea = "";
    if($link_show == 'ha')
        $titlea = "<$header_tag class=\"$header_class\"><a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\">$title</a></$header_tag>";
    elseif($link_show || $link_show == 'ah')
        $titlea = "<a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\"><$header_tag class=\"$header_class\">$title</$header_tag></a>";
    elseif(empty($link_show))
        $titlea =  "<$header_tag class=\"$header_class\">$title</$header_tag>";

    if(in_array($param->style, ['System-none','none','no','0',0,'']))
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

echo "<div class=\"slider items owl-carousel  count$count $moduleclass_sfx \"  itemscope itemtype=\"http://schema.org/ImageGallery\">";

foreach ($elements as $i => & $module){
    $module->text =  $module->content = & $prepare($module->content);

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
            <img class=\" item_image $module->moduleclass_sfx\" width=\"300\"  src=\"$module->image\"></a>");
    if($params->get('items_image') == 'ii')
        echo $prepare("<a class=\"$class image\"  target=\"_blank\" href=\"$module->image\"  title=\"$module->title\"
            data-toggle=\"lightbox\"   data-gallery=\"gallery_$mod_id\" data-type=\"image\" onclick=\"return true;\">
            <img class=\" item_image $module->moduleclass_sfx\" width=\"300\"  src=\"$module->image\"></a>");
    if($params->get('items_image') == 'di')
        echo $prepare("<div class='$class image'><img width=\"300\" class=\"   item_image  $module->moduleclass_sfx\"  data-action=\"zoom\"  src=\"$module->image\"></div>");
    if($params->get('items_image') == 'i')
        echo $prepare("<img class=\"$class image item_image $module->moduleclass_sfx\" width=\"300\"  src=\"$module->image\">");
endif;

$header_tag3 = $params->get('header_tag3');
$items_link = $params->get('items_link');
if($header_tag3 == 'default'){
    $header_tag3 = 'div';
}
if($header_tag3 == 'item'){
    if($module->header_tag && $module->showtitle)
        $header_tag3 = $module->header_tag;
    else
        $items_link = FALSE;
}
if($items_link && $module->title):
    if($items_link == 'ah')
        echo "<a class=\"$class\"  target=\"_blank\" href=\"$module->link\"  title=\"$module->title\" ><$header_tag3 class='title'>";
    elseif($items_link == 'ha')
        echo  "<$header_tag3 class='title'><a class=\"$class\"  target=\"_blank\" href=\"$module->link\"  title=\"$module->title\" >";
    elseif($header_tag3)
        echo  "<$header_tag3 class='title'>";
    if($module->title)
        echo "$module->title";
    if($items_link == 'ah')
        echo  "</$header_tag3></a>";
    elseif($items_link == 'ha')
        echo "</a></$header_tag3>";
    elseif($header_tag3)
        echo "</$header_tag3>";
endif;

$content_tag3 = $params->get('content_tag3');
if($content_tag3 == 'default')
    $content_tag3 = $module->module_tag;
if($module->content && $content_tag3 != 'none' && $content_tag3) {
    echo ("<$content_tag3 class='info'>$module->content</$content_tag3>");
}
if($module->content && empty($content_tag3))
    echo ($module->content);

    if($module->urls->urla){
        $class = $isImage($module->urls->urla);
        $class .= ($module->urls->targeta===2)?"lightbox":($module->urls->targeta===3)?"modal":'';
        $target = ($module->urls->targeta==0)?"_parent":(($module->urls->targeta==1)?"_blank":(($module->urls->targeta==2)?"_self":''));
        echo $prepare("<a class=\"linka urls $class\"  target=\"$target\" href=\"{$module->urls->urla}\"  title=\"{$module->urls->urlatext}\" >{$module->urls->urlatext}</a>");
    }
    if($module->urls->urlb){
        $class = $isImage($module->urls->urlb);
        $class .= ($module->urls->targetb===2)?"lightbox":($module->urls->targetb===3)?"modal":'';
        $target = ($module->urls->targetb===0)?"_parent":($module->urls->targetb===1)?"_blank":($module->urls->targetb===2)?"_self":'';
        echo $prepare("<a class=\"linkb urls $class\"  target=\"$target\" href=\"{$module->urls->urlb}\"  title=\"{$module->urls->urlbtext}\" >{$module->urls->urlbtext}</a>");
    }
    if($module->urls->urlc){
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

    JHtml::script($mod_path . "OwlCarousel/owl.carousel.js");
    JHtml::stylesheet($mod_path . "OwlCarousel/owl.carousel.css");
    JHtml::stylesheet($mod_path . "OwlCarousel/owl.theme.default.css");
    JHtml::stylesheet($mod_path . "OwlCarousel/style.css");

    JHtml::stylesheet("
    JHtml::script( "
    JHtml::script( "

$id      = $params->get('id');
$json_owlCarousel      = $params->get('json_layout','') ?: $params->get('json_owlCarousel','');

 $script = <<< script

jQuery( function() {

        console.log('jQuery:',jQuery.fn.jquery,' -OwlSlider -MultiModule:',{$id});

        jQuery(".slider.items.owl-carousel.id{$id}").owlCarousel({
            {$json_owlCarousel}
        });

           return;

 setInterval(function(){}, 1000);

});

script;

JFactory::getDocument()->addScriptDeclaration($script);

return;
?>

