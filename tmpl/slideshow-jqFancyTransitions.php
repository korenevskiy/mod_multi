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
//JHtml::addIncludePath(JPATH_SITE . '/components/com_finder/helpers/html');
//
//JHtml::_('bootstrap.framework');
JHtml::_('jquery.framework', true, TRUE, true); // load jquery
JHtml::_('jquery.ui'); // load jquery ui from Joomla
//JHtml::_('formbehavior.chosen', 'select');
//JHtml::_('bootstrap.tooltip');

//JHtml::_('behavior.keepalive');
//JHtml::_('bootstrap.tooltip');

// Load the smart search component language file.
//$lang = JFactory::getLanguage();
//$lang->load('com_finder', JPATH_SITE);
    //JHtml::stylesheet('//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
    
    
//    JHtml::script('modules/mod_jshopping_manufacturers/media/jquery.mobile.custom.min.js');
//    JHtml::script('modules/mod_jshopping_manufacturers/media/jquery.liquidcarousel.js');
//    JHtml::script('modules/mod_jshopping_manufacturers/media/fbisCarousel.js');
//    JHtml::stylesheet('modules/mod_jshopping_manufacturers/media/fbisCarousel.css');
     
    
//JHtml::stylesheet('//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css');
//JHtml::stylesheet('//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css');
////JHtml::script('//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js');
//JHtml::script('//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.js');



$param = (new Joomla\Registry\Registry($params))->toObject();//***  


 
$positon = $params->get('position');


$style=$params->get('style');//***
$mod_show = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = in_array($style, ['',0,NULL,'none','System-none','Cassiopeia-no'])? '': $params->get('moduleclass_sfx');//***

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
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$mod_show id$param->id $style\"  >";

 

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
        $titlea = "<$header_tag class=\"$header_class\"><a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$param->id multiheadera\">$title</a></$header_tag>";
    elseif($link_show || $link_show == 'ah')// && in_array($style, ['System-none','none','no',''])  && $module_tag2
        $titlea = "<a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$param->id multiheadera\"><$header_tag class=\"$header_class\">$title</$header_tag></a>";
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
$keys = array_keys($modules);

//toPrint($keys, '$keys',0);
//toPrint($modules,'5article');
//toPrint($keys, '$keys',0);

        
$elements = [];

//if($param->id == 133)echo "<moduleX >$param->id</moduleX>";
//toPrint($modules,'$modules',0);


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

//toPrint($elements,'$elements',0);
$keys = array_keys($elements);

//toPrint($keys, '$keys',0);
//toPrint($elements,'$elements');

$count = count($elements);
//    if(isset($tag_block) && $tag_block) 
echo "<div id='multislideshowid$param->id' class=\"slider items   count$count $moduleclass_sfx id$param->id \">";// id$param->id pos$positon jqFancyTransitions

foreach ($elements as $i => $module){
 
   
//$module->style;  ??
//$module->moduleclass_sfx; ??
//$module->module_tag; ??
//$module->header_tag;  ??
//$module->header_class; ??
//$module->title;   ??
//$module->id;  ??
//$module->module
//$module->content
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
    

    if(empty($module->link))
        $module->link = $module->image;
    
    if(empty($module->title))
        $module->title = $module->image; 
    
    
$isImage = function($url){
    $ext = strtolower(substr($url, strrpos($url, '.') + 1)) ;
    //$ext = (($p = strrpos('.', $url)) !== false) ? substr($url,$p+1) : '';
    //$ext = pathinfo($url, PATHINFO_EXTENSION);
    return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])? (' imagelink '.$ext):' url ';
};
$class = $isImage($module->link); 
$img_path = trim($module->image,'/'); // JURI::root()  JUri::base().

//toPrint($module->image,'$module->image '.$param->id,0);
//if($param->id == 133)echo "<moduleX >$param->id</moduleX>";

echo "<img src=\"$module->image\" alt=\" $module->title\" class=\"image $module->moduleclass_sfx\"   >";

if($params->get('items_image') && $params->get('items_image') != 'i') 
    echo "<a class=\"$class image\"  target=\"_blank\" href=\"$module->link\"  title=\"$module->title\" ></a>";   

 
  
      
}
 
//    if(isset($tag_block) && $tag_block) 
echo "</div>"; 
 
if($module_tag2)
    echo "</$module_tag2>";


// <editor-fold defaultstate="collapsed" desc="Scrypt Carousel for count 5">
//if (empty($script)) {


  
//    JHtml::stylesheet($mod_path . "slide4.css");
     
 
    

JHtml::script('modules/mod_multi/media/jqfancytransitions/slideshow.js'); 
//JHtml::script('modules/mod_multi/media/jqfancytransitions/jqFancyTransitions.1.8.js'); 
   
 
$json_jqFancyTransitions = $params->get('json_layout','') ?: $params->get('json_jqFancyTransitions','');


//toPrint($json_owlCarousel,'$json_owlCarousel');
$style_layout = in_array(JFactory::getConfig()->get('error_reporting'), [0,NULL,'','none','default'])? '':basename (__FILE__,'.php');
//https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html  #multislideshowid$param->id .slider.items.id$param->id
 $script = <<< script

/* MultiModule ModuleID:$param->id - $style_layout */
jQuery( function() { 
        jQuery("#multislideshowid$param->id").jqFancyTransitions({ 
            {$json_jqFancyTransitions}
        });   
}); 
//setInterval(function(){ }, 100000);  
            
script;

JFactory::getDocument()->addScriptDeclaration($script);
//echo "<script type='text/javascript'>
//jQuery.noConflict(); // var multislideshow = 
////(function($) { 
//            $script 
//  //  })(jQuery);
//</script>";


//<style type="text/css"></style>
//}
// </editor-fold>

return;
?> 

 