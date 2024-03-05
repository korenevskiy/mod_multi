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
*** ------------------------ Карусель с вращающимся фотками/карточками  -------------------------------------------- ***
 * https://www.jqueryscript.net/slider/jQuery-Waterwheel-Carousel-Plugin.html
*/


$param = new \Reg($params);//*** ->toObject()



$id      = $params->get('id');
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
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$mod_show id$id $style\"  >";

 

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
 
if($tag = $params->get('modules_tag3')){
    $tgs = explode('/', $tag);  
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item  = $tgs[3] ?? FALSE;
//        toPrint(0,"$tag $tag_title, $tag_block, $tag_container, $tag_item");
}
//        toPrint($params->get('header_tag3',''),'header_tag3');
 
//echo "123<lala>$modules_tag $style </lala>";

//toPrint($modules, '$modules');
//$keys = array_keys($modules);

//toPrint($keys, '$keys',0);
//toPrint($modules,'5article');
//toPrint($keys, '$keys',0);

        
$elements = [];

foreach ($modules as $type => $items){
    $order =  substr($type, 0, 2); // $type[0];
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

//$keys = array_keys($elements);

//toPrint($keys, '$keys',0);
//toPrint($elements,'$elements'); multislideshowid238

$count = count($elements);
//    if(isset($tag_block) && $tag_block) 

echo "<div  id='multislideshowid$param->id'   class='-gallery items count$count $moduleclass_sfx  id$param->id Feature-Carousel'   
	style=' position: relative;
    width: 100%;
	max-width: 100%;
    height: 700px;
	'>";
foreach ($elements as $i => $module):
    
//    if(!isset($tag_container) or empty($tag_container))
//        $tag_container = "div";
    
    
//    toPrint(array_keys((array)$module));
//    if($tag_container)
//        echo "<$tag_container class=\"item i$i $type sfx$module->moduleclass_sfx id$module->id $module->module  \">";
                                                    

   
//$module->style;  ??
//$module->moduleclass_sfx; ??
//$module->module_tag; ??
//$module->header_tag;  ??
//$module->header_class; ??
//$module->title;   ??
//$module->id;  ??
//$module->module
//$module->content  ?? ''
//$module->menu_image
//
//
//
//
// Show title modules         
//    if($tag_t = $params->get('header_tag3','')){
//        echo "<$tag_t class=\"$module->header_class\">"; 
//        echo $module->title; 
//        echo "</$tag_t>";
//    }

    
    //dl dt dd
    //details summary x
    //figure  figcaption x
    //fieldset legend x
//toPrint($module->content,'$module->content');
// 
// Show content modules  
    
    $base = JUri::base();
    
    if(empty($module->link))
        $module->link = $module->image;
    
    if(empty($module->title))
        $module->title = $module->image; 
    //echo "<div  id='bu$i'>";
    echo "<div class='carousel-feature'><a class=' '   rel='group' href='/' title='$module->title'  >";
    echo "<img class='$module->moduleclass_sfx thumb' src='$base/$module->image' alt='$module->title' title='$module->title'  oncontextmenu='return false;'  style=' max-width: 220px; max-height: 320px'>";
//if($module->title) echo  "<span class='title'>$module->title</span>";
//if($module->content) echo  "<span class='info'>$module->content</span>"; $module->link
	
 echo "<div class='carousel-caption'><p>$module->title</p></div>";
       
    echo "</a></div>";
    //echo '</div>'; 
    //echo "$content";
     
//    if($tag_container)
//        echo "</$tag_container>";
endforeach;
 echo "</div>";
 
// echo '<a href="#" id="prev">Prev</a>';
// echo '<a href="#" id="next">Next</a>';

 
 
//    if(isset($tag_block) && $tag_block) 
 
 
if($module_tag2)
    echo "</$module_tag2>";
 
//JHtml::addIncludePath(JPATH_SITE . '/components/com_finder/helpers/html');
//
JHtml::_('jquery.framework'); // load jquery
JFactory::getApplication()->getDocument()->getWebAssetManager()->useStyle('jquery.ui')->useScript('jquery.ui'); // load jquery ui from Joomla
//JHtml::_('formbehavior.chosen', 'select');
//JHtml::_('bootstrap.tooltip');

//JHtml::_('behavior.keepalive');
//JHtml::_('bootstrap.tooltip');

// Load the smart search component language file.
//$lang = JFactory::getLanguage();
//$lang->load('com_finder', JPATH_SITE);
    //JHtml::stylesheet('//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
    
     



// <editor-fold defaultstate="collapsed" desc="Scrypt Carousel for count 5">
static $script;
if(empty($script)):
endif;
  
$mod_path =  JUri::base();

    JHtml::script($mod_path .		"modules/mod_multi/media/carousel-Feature-Carousel/js/jquery.featureCarousel.min.js"); 
    JHtml::stylesheet($mod_path .	"modules/mod_multi/media/carousel-Feature-Carousel/css/feature-carousel.css");  
//    JHtml::stylesheet($mod_path . "main.css");
    
    
//    JHtml::script($mod_path . "fancybox_3/jquery.fancybox.min.js"); 
//    JHtml::stylesheet($mod_path . "fancybox_3/jquery.fancybox.min.css"); 
     
//    JHtml::script($mod_path . "fancybox_2/jquery.fancybox.pack.js"); 
//    JHtml::stylesheet($mod_path . "fancybox_2/jquery.fancybox.css"); 
        
    //https://www.jqueryscript.net/slider/Creating-3D-Perspective-Carousel-with-jQuery-CSS3-CSSSlider.html 
    
$style_layout = in_array(JFactory::getConfig()->get('error_reporting'), [0,NULL,'','none','default'])? '': '- '.basename (__FILE__,'.php');

$json_layout = $param->json_layout ?? '';

$script = <<< script

/* MultiModule ModuleID:$param->id $style_layout */
jQuery(function($){
//   return;
	var carousel = $("#multislideshowid$param->id").featureCarousel({
		$json_layout
          // include options like this:
          // (use quotes only for string values, and no trailing comma after last option)
          // option: value,
          // option: value
    });
   return;
		 $("#but_prev").click(function () {
          carousel.prev();
        });
        $("#but_pause").click(function () {
          carousel.pause();
        });
        $("#but_start").click(function () {
          carousel.start();
        });
        $("#but_next").click(function () {
          carousel.next();
        });
}); 

script;
JFactory::getDocument()->addScriptDeclaration($script);
//https://www.jqueryscript.net/slider/jQuery-Waterwheel-Carousel-Plugin.html

//<script type="text/javascript"></script>
//<style type="text/css"></style>

// </editor-fold>
?> 
