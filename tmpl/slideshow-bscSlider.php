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
//JHtml::addIncludePath(JPATH_SITE . '/components/com_finder/helpers/html');
//
//JHtml::_('jquery.framework');
//JHtml::_('formbehavior.chosen', 'select');
//JHtml::_('bootstrap.tooltip');

//JHtml::_('behavior.keepalive');
//JHtml::_('bootstrap.tooltip');

// Load the smart search component language file.
//$lang = JFactory::getLanguage();
//$lang->load('com_finder', JPATH_SITE);


$param = (new Joomla\Registry\Registry($params))->toObject();//*** 


$module_id      = $params->get('id');
$positon = $params->get('position');

$style=$params->get('style');
$mod_show = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$showtitle = $params->get('showtitle');
//$title = htmlspecialchars($params->get('title'));
$title = ($params->get('title'));


$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));

 

$params->get('items_link');
$params->get('items_image'); 
 

$link_show = $params->get('link_show'); 
$link = $params->get('link');

//echo "<pre> ** $link_show ".print_r(( $module),true). " $showtitle++</pre>"; 
//echo "<pre> ** $link_show ".print_r(( $params),true). " $showtitle++</pre>"; 



//$stylesheetModule = $params->get('stylesheetModule');
//$stylesheetTemplates = $params->get('stylesheetTemplates');
//$stylesheetText = $params->get('stylesheetText');
//$scryptModule = $params->get('scryptModule');
//$scryptTemplates = $params->get('scryptTemplates');
//$scriptText = $params->get('scriptText');
//
//if($stylesheetModule)JHtml::stylesheet(JUri::base().'modules/mod_multi/css/'.$stylesheetModule);
//if($stylesheetTemplates)JHtml::stylesheet(JUri::base().'templates/'.$stylesheetTemplates);
//if($stylesheetText)JFactory::getDocument()->addStyleDeclaration($stylesheetText);
//if($scryptModule)JHtml::script(JUri::base().'modules/mod_multi/css/'.$scryptModule);
//if($scryptTemplates)JHtml::script(JUri::base().'templates/'.$scryptTemplates);
//if($scriptText)JFactory::getDocument()->addScriptDeclaration($scriptText);
//JFactory::getDocument()->addStyleSheet() or JFactory::getDocument()->addScript() 
//JHtml::_('stylesheet', 'com_finder/finder.css', null, true, false); //add file in folder MEDIA/com_finder/finder.css
//$files = JHtml::_('stylesheet', 'templates/' . $this->template . '/css/general.css', null, false, true);

//ECHO <<<view
//view;   
$modules;
$modules_tag = $params->get('modules_tag');  


//if($modules_tag=='default'){
//    switch ($type_module){
//        case 'positions':
//            $modules_tag = "ul"; break;
//        case 'modules':
//            $modules_tag = "ul"; break;
//        case 'article':
//            $modules_tag = "div"; break;
//        default :
//            $modules_tag = "empty";
//    }
//}

 

if($module_tag2 = $params->get('module_tag2'))
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$mod_show id$module_id $style\"  >";

 

//toPrint($showtitle."|".$link_show.'|'.$style,'');
//if($$mod->id == 111)
//toPrint($$mod,'');
//
//
//echo "123";
//echo "<pre>$link $header_tag** ".print_r(( $title),true). "++</pre>"; 

//echo "$link_show *".$params->get('title_alt')."-".$module->title."+".$module->title." ++$module->showtitle $params->showtitle!!";


if($showtitle):
    $titlea = "";
    if($link_show == 'ha')
        $titlea = "<$header_tag class=\"$header_class\"><a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\">$title</a></$header_tag>";
    elseif($link_show || $link_show == 'ah')// && in_array($style, ['System-none','none','no',''])  && $module_tag2
        $titlea = "<a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\"><$header_tag class=\"$header_class\">$title</$header_tag></a>";
    elseif(empty($link_show))//$module_tag2 &&
        $titlea =  "<$header_tag class=\"$header_class\">$title</$header_tag>";

    if(in_array($style, ['System-none','none','no','0',0,'']))
        echo $titlea;
    else
        $$mod->title = $titlea;
endif;


//echo "<lala>$title</lala>";
 //echo $style;
  
//echo "<lala>$modules_tag</lala>";
//
//$order=$params->get('order', 'ideh');
//echo $order.'123';

//echo ($params->get('layout','dev').' '.$order.' '.strlen($html));

 
 
//echo "123<lala>$modules_tag $style </lala>";

//$keys = array_keys($modules);

//toPrint($keys, '$keys',0);
//toPrint($modules,'5article',0);

    //if($tag = $params->get($type.'_tag'))
if($tag = $params->get('modules_tag3')){
    $tgs = explode('/', $tag);  
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item  = $tgs[3] ?? FALSE;
//        toPrint(0,"$tag $tag_title, $tag_block, $tag_container, $tag_item");
}
if(empty($tag_block)) 
    $tag_block = "div";
    
//if($params->get('id')==149)  toPrint($modules,'$modules');
    
//echo $modules_tag;
//foreach ($modules as $id=>$module)
//	echo $id."-";
//$modules=array();
// Show modules   
$count = 0;
foreach ($modules as $type => $items){
    if(!is_string($items))
        $count += count($items);
}
$i = 0;

echo "<$tag_block class=\"items id$module_id count$count $type carousel  \">";

foreach ($modules as $type => $items){
    $order =  substr($type, 0, 2); // $type[0];
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

    JHtml::_('jquery.framework'); // load jquery
    JHtml::_('jquery.ui'); // load jquery ui from Joomla  
    
    $path_jft = JUri::base() . 'modules/mod_multi/media/jqfancytransitions/'; 
$script = "//www.jqueryscript.net/demo/Fancy-Slideshow-Plugin-jQuery-BSC-Slider/assets/js/jquery.bscslider.js"; 
    JHtml::stylesheet('//www.jqueryscript.net/demo/Fancy-Slideshow-Plugin-jQuery-BSC-Slider/assets/css/jquery.bscslider.css');
//    JHtml::stylesheet($path_jft.'slick-theme.css'); 
    JHtml::script($script);
    
//    toPrint($path_jft,'$path_jft');
    
static $accordion;
// toPrint($accordion,'$accordion');
     
if(TRUE && empty($accordion)){//
//return;
//    JHtml::script('modules/mod_jshopping_manufacturers/media/jquery.mobile.custom.min.js');
    //JHtml::script(Juri::base() . 'modules/mod_jshopping_categories/media/jquery.ui.accordion.min.js');
 //<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
//$module_id      = $params->get('id');
 //<script>   
$accordion = <<< script
 
        
jQuery( function() {

    console.log("jQuery('.carousel.id$module_id').bscSlider()");
    
        jQuery(".carousel.id$module_id").bscSlider({ 
            autoplay    : true, 
            /*height      : 300,*/
/* https://www.jqueryscript.net/slideshow/Fancy-Slideshow-Plugin-jQuery-BSC-Slider.html */
        }); return;
        
//    var src = '$path_jft'+'jqFancyTransitions.1.8.js';
//    var src = '$script'; 
    
        
    var scriptElem = document.createElement('script');
        scriptElem.setAttribute('src',src);
        scriptElem.setAttribute('type','text/javascript');
        document.getElementsByTagName('head')[0].appendChild(scriptElem);
//return;  
        
        
    scriptElem.onload =     function() {
        jQuery(".carousel.id$module_id").bscSlider({ 
            autoplay    : true, 
            /*height      : 300,*/

        });
        };
return;
            
            console.log('000 x'+src);
    jQuery('<script/>').attr('type', 'text/javascript').attr('src', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js').appendTo('head').ready(
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
        

//        nextArrow: none,
//        prevArrow: none,
//        appendArrows: false,
        
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
//setInterval(function(){ }, 100000);
//.id$module_id
  
script;
 
//        adaptiveHeight: true,

//    return;
        
//     	jQuery('.carousel').slick({
//            dots: false,
//            infinite: true,
//            centerMode: true,
//            slidesToShow: 1,
//            slidesToScroll: 1,
//            autoplay: true,
//            adaptiveHeight: true,
//	});
//        console.log("carousel 3");

//</script>
//toPrint($level,'$level');
JFactory::getDocument()->addScriptDeclaration($accordion);
}

return;
// toPrint($accordion,'$accordion');

?>

<!--
<script xsrc="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script> 
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css"/> 
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css"/>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>;
-->
<!--
<script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css"/>  
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css"/>
-->

<script type="text/javascript">
    
    
//jQuery('head').add('script').attr('type','text/javascript').attr('src','//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js');
    
    jQuery( function() { 
//        jQuery(".slider").slick({
//            dots: false,
//            infinite: true,
//            centerMode: true,
//            slidesToShow: 5,
//            slidesToScroll: 1,
//            autoplay: true
//        });
      
//        jQuery('.jcarousel').jcarousel();
//        jQuery('#slides').liquidCarousel({
//		height: 108,
//		hideNavigation: false,
//		onBeforeNext: function() {
//			console.log('%conBeforeNext', 'color: green; font-weight:bold;', ' has been called');
//		},
//		onBeforePrevious: function() {
//			console.log('%conBeforePrevious', 'color: green; font-weight:bold;', ' has been called');
//		}
//	});
        console.log("carousel 123");
        return;
//        jQuery('section.awSlider .carousel').carousel({
//            pause: "hover",
//            interval: 2000
//        });
//      jQuery("div.fbisCarousel").fbisCarousel({noToDisplay:5});
//     	jQuery('.slides').liquidCarousel({
//		height: 170,  
//	});
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
