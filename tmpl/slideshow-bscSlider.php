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

echo "<$tag_block class=\"items id$module_id count$count $type carousel  \">";

foreach ($modules as $type => $items){
    $order =  substr($type, 0, 2);
    $type = substr($type, 2);
    if(is_string($items)){
        echo $items;
        continue;
    }

foreach ($items as $id => $module){

    $i++;

    echo "<img src='$module->image'  class=\"item item_image i$i order$order $type moduletable$module->moduleclass_sfx  id$module->id $module->module     $module->moduleclass_sfx\" title='$module->title' alt='$module->title'/>";

}
}
echo "</$tag_block>";

if($module_tag2)
    echo "</$module_tag2>";

/* Оригинал скрипта
 * https://www.jqueryscript.net/slideshow/Fancy-Slideshow-Plugin-jQuery-BSC-Slider.html
 */

    JHtml::_('jquery.framework');
    JHtml::_('jquery.ui');

    $path_jft = JUri::base() . 'modules/mod_multi/media/jqfancytransitions/';
$script = "
    JHtml::stylesheet('

    JHtml::script($script);

static $accordion;

if(TRUE && empty($accordion)){

$accordion = <<< script

jQuery( function() {

    console.log("jQuery('.carousel.id$module_id').bscSlider()");

        jQuery(".carousel.id$module_id").bscSlider({
            autoplay    : true,
            /*height      : 300,*/
/* https://www.jqueryscript.net/slideshow/Fancy-Slideshow-Plugin-jQuery-BSC-Slider.html */
        }); return;

    var scriptElem = document.createElement('script');
        scriptElem.setAttribute('src',src);
        scriptElem.setAttribute('type','text/javascript');
        document.getElementsByTagName('head')[0].appendChild(scriptElem);

    scriptElem.onload =     function() {
        jQuery(".carousel.id$module_id").bscSlider({
            autoplay    : true,
            /*height      : 300,*/

        });
        };
return;

            console.log('000 x'+src);
    jQuery('<script/>').attr('type', 'text/javascript').attr('src', '
        function(){
            console.log('111');
            jQuery(".carousel.id$module_id").slick({
                dots: false,
                infinite: true,
                centerMode: true,
                slidesToShow: 1,
                    arrows: false,
                    variableWidth: true,
                    speed: 1200,
                    autoplaySpeed: 6000,
                    adaptiveHeight: true,
                slidesToScroll: 1,
                autoplay: true
            });
            console.log('222');
        }
    );return;

    jQuery(".carousel.id149").slick({
        dots: false,
        infinite: true,
        centerMode: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        adaptiveHeight: true,
    });
    console.log("carousel 2 id"+$module_id);

});

script;

JFactory::getDocument()->addScriptDeclaration($accordion);
}

return;

?>

<!--
<script xsrc="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="
<script type="text/javascript" src="
<link rel="stylesheet" type="text/css" href="
<link rel="stylesheet" type="text/css" href="
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>;
-->
<!--
<script type="text/javascript" src="
<script type="text/javascript" src="
<script type="text/javascript" src="
<link rel="stylesheet" type="text/css" href="
<link rel="stylesheet" type="text/css" href="
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

