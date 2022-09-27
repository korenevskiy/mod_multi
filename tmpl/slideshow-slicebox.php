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
/*
*** ------------------------ Просто Слайдшоу показывающий 1 элемент во всю ширину -------------------------------------------- ***
*/

$param = (new Joomla\Registry\Registry($params))->toObject();//***

$module_id      = $params->get('id');
$positon = $params->get('position');

$style=$params->get('style');
$mod_show = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$showtitle = $params->get('showtitle');

$title = ($params->get('title'));

$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));

$params->get('items_link');
$params->get('items_image');

$link_show = $params->get('link_show');
$link = $params->get('link');

$modules;
$modules_tag = $params->get('modules_tag');

if($module_tag2 = $params->get('module_tag2'))
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$mod_show id$module_id $style\"  >";

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
if(empty($tag_block))
    $tag_block = "div";

$count = 0;
foreach ($modules as $type => $items){
    if(!is_string($items))
        $count += count($items);
}
$i = 0;

echo "<ul $tag_block class=\"items sb-slider id$module_id count$count $type carousel  \">";

foreach ($modules as $type => $items){
    if(is_string($items)){
        echo $items;
        unset($modules[$type]);
        continue;
    }
    $order =  substr($type, 0, 2);
    $type = substr($type, 2);

foreach ($items as $id => $module){

        echo "<li class=\"item i$i order$order $type moduletable$module->moduleclass_sfx  id$module->id $module->module  \">";

    $i++;

    $link = $link_ = "";

    if($params->get('items_link')){
        $link = "<a href='$module->link' title='$module->title' >";
        $link_ = '</a>';
    }

    if($tag_t = $params->get('header_tag3','')){
        echo "<$tag_t class=\" item_title $module->header_class\">";
        echo $link.     $module->title  .$link_;
        echo "</$tag_t>";
    }

    if($params->get('items_image') && $module->image)
        echo "<img src='/$module->image' class=\" item_image i$i  $module->moduleclass_sfx\" title='$module->title' alt='$module->title'/>";

        echo "</li>";
}

}
echo "</ul>";

if($module_tag2)
    echo "</$module_tag2>";

    JHtml::_('jquery.framework');
    JHtml::_('jquery.ui');

    JHtml::script(JUri::base() . 'modules/mod_multi/media/modernizr-custom.js');
    JHtml::script(JUri::base() . 'modules/mod_multi/media/jquery.easing.1.3.min.js');

    JHtml::script(JUri::base() .'modules/mod_multi/media/Slicebox/js/jquery.slicebox.js');

    JHtml::stylesheet(JUri::base() . 'modules/mod_multi/media/Slicebox/css/slicebox.css');

static $accordion;

$id      = $params->get('id');
$json_slicebox      = $params->get('json_layout','') ?: $params->get('json_slicebox','');

if(TRUE && empty($accordion)){

$accordion = <<< script

jQuery( function() {

    console.log("jQuery('.carousel.id$module_id').slicebox();");

    jQuery(".carousel.id$module_id").slicebox({
            {$json_slicebox}
        });
});

script;

JFactory::getDocument()->addScriptDeclaration($accordion);
}

return;

?>

<!--
<script xsrc="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css"/>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>;
-->
<!--
<script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css"/>
<link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css"/>
-->

<script type="text/javascript">

    jQuery( function() {

//        jQuery('#slides').liquidCarousel({

        console.log("carousel 123");
        return;

    });
</script>

<style type="text/css">
.carousel.id<?= $module_id ?> {
    position: relative;
    overflow: hidden;
    -width: 100%;
}

.carousel.id<?= $module_id ?> > .item {

    -width: 100px;
}
</style>

