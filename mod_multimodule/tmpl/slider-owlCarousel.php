<?php
/**
 * @package     JoomLike.Site
 * @subpackage  mod_multimodule
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
//JHtml::addIncludePath(JPATH_SITE . '/components/com_finder/helpers/html');
//
JHtml::_('jquery.framework'); // load jquery
JHtml::_('jquery.ui'); // load jquery ui from Joomla
JHtml::_('jquery.ui', array('core', 'sortable'));
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



$id = $mod_id   = $params->get('id');
$positon        = $params->get('position');

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
//if($stylesheetModule)JHtml::stylesheet(JUri::base().'modules/mod_multimodule/css/'.$stylesheetModule);
//if($stylesheetTemplates)JHtml::stylesheet(JUri::base().'templates/'.$stylesheetTemplates);
//if($stylesheetText)JFactory::getDocument()->addStyleDeclaration($stylesheetText);
//if($scryptModule)JHtml::script(JUri::base().'modules/mod_multimodule/css/'.$scryptModule);
//if($scryptTemplates)JHtml::script(JUri::base().'templates/'.$scryptTemplates);
//if($scriptText)JFactory::getDocument()->addScriptDeclaration($scriptText);
//JFactory::getDocument()->addStyleSheet() or JFactory::getDocument()->addScript() 
//JHtml::_('stylesheet', 'com_finder/finder.css', null, true, false); //add file in folder MEDIA/com_finder/finder.css
//$files = JHtml::_('stylesheet', 'templates/' . $this->template . '/css/general.css', null, false, true);

//ECHO <<<view
//view;   
$modules;
$modules_tag = $params->get('modules_tag');  

 //flybox

//$hel::$prep;
 
//if($mod_id==242){
//    $image1 = "<lbl style='width:50px;display:block;'>
//            <img width=\"300\"  src=\"/images/site.jpg\" />
//        </lbl>"; 
//    $image1 = $prepare($image1);
//    toPrint($image1,'$image1');
////    echo $image1;
////    $image1 = FALSE;
//}
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

$prepare = function &( $item, $param = null, $context = 'com_content.article'){
    return modMultiModuleHelper::preparePlugin($item, $param, $context);
};  
 

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
        list($tag_title, $tag_block, $tag_container, $tag_item) = explode('/', $tag);
    
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

foreach ($modules as $type => $items){
    if(is_string($items)){
        echo $prepare($items) ;
        unset($modules[$type]);
        continue;
    }
    $order =  substr($type, 0, 2); // $type[0];
    $type = substr($type, 2);
    $count = count($items);
    foreach ($items as $id => $module){
        $module->moduleclass_sfx = $module->moduleclass_sfx??'';
        $module->moduleclass_sfx .= "  countype$count order$order $type  ";
        $elements[] = $module;
    }
}  

$keys = array_keys($elements);

//toPrint($keys, '$keys',0);
//toPrint($elements,'$elements');

$count = count($elements);
//    if(isset($tag_block) && $tag_block) 
echo "<div class=\"slider items owl-carousel  count$count $moduleclass_sfx \"  itemscope itemtype=\"http://schema.org/ImageGallery\">";// id$id pos$positon 

foreach ($elements as $i => & $module){
    $module->text =  $module->content = & $prepare($module->content);
//     modMultiModuleHelper::preparePlugin ($module);
    
//    if(!isset($tag_container) or empty($tag_container))
//        $tag_container = "div";
    
    
//    toPrint(array_keys((array)$module));
    if($tag_container)
        echo "<$tag_container class=\"item i$i $type sfx$module->moduleclass_sfx id$module->id $module->module  \">";
                                                    

   
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
        $module->title = pathinfo($module->image, PATHINFO_FILENAME); //= $module->image;
    
$isImage = function($url){
    $ext = strtolower(substr($url, strrpos($url, '.') + 1)) ;
    //$ext = (($p = strrpos('.', $url)) !== false) ? substr($url,$p+1) : '';
    //$ext = pathinfo($url, PATHINFO_EXTENSION);
    return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])?' imagelink '.$ext:' url ';
};
$class = $isImage($module->link);

if($params->get('items_image') && $module->image):
    if($params->get('items_image') == 'ai')
        echo $prepare("<a class=\"$class image\"  target=\"_blank\" href=\"$module->link\"  title=\"$module->title\"
            data-toggle=\"lightbox\"   data-gallery=\"gallery_$mod_id\" data-type=\"image\" onclick=\"return true;\">
            <img class=\" $module->moduleclass_sfx\" width=\"300\"  src=\"$module->image\"></a>");   
    if($params->get('items_image') == 'di') 
        echo $prepare("<div class='image'><img width=\"300\" class=\"     $module->moduleclass_sfx\"  data-action=\"zoom\"  src=\"$module->image\"></div>");       
    if($params->get('items_image') == 'i') 
        echo $prepare("<img class=\"image $module->moduleclass_sfx\" width=\"300\"  src=\"$module->image\">");
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
//if($$mod->id == 244)
//toPrint($module->fields,'$module->fields:'.$module->id);
        
//            toPrint($module->urls,'$module->urls');
     
    //echo "$content";
     
    if($tag_container)
        echo "</$tag_container>";
}
 
//    if(isset($tag_block) && $tag_block) 
echo "</div>"; 
 
if($module_tag2)
    echo "</$module_tag2>";


// <editor-fold defaultstate="collapsed" desc="Scrypt Carousel for count 5">
//if (empty($script)) {


    $mod_path = "modules/mod_multimodule/media/";  

    JHtml::script($mod_path . "OwlCarousel/owl.carousel.js");
    JHtml::stylesheet($mod_path . "OwlCarousel/owl.carousel.css");
    JHtml::stylesheet($mod_path . "OwlCarousel/owl.theme.default.css");
    JHtml::stylesheet($mod_path . "OwlCarousel/style.css");

    
    
//    JHtml::stylesheet("//cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css");
//    JHtml::script( "//cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js");
 //jQuery('.slider.items.owl-carousel.id244 .imagelink ').ekkoLightbox();
//jQuery('.slider.items.owl-carousel.id{$id} .imagelink ').ekkoLightbox();
//jQuery('.slider.items.owl-carousel.id{$id} [data-toggle="lightbox"]').ekkoLightbox();
    
    
    
    JHtml::stylesheet("//raw.githubusercontent.com/round/enhance.js/master/css/zoom.css");
    JHtml::script( "//cdn.jsdelivr.net/velocity/1.2.2/velocity.min.js");
    JHtml::script( "//raw.githubusercontent.com/round/enhance.js/master/js/enhance.js");
    

$id      = $params->get('id');
$json_owlCarousel      = $params->get('json_owlCarousel');


//toPrint($json_owlCarousel,'$json_owlCarousel');

//https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html
 $script = <<< script
 
         
         

//    jQuery('.slider.items.owl-carousel').on('click', '[data-toggle="lightbox"]', function(event) {
//        //event.preventDefault();
//        jQuery(this).ekkoLightbox();
//    });          

jQuery( function() {
//        console.clear();
//        return;
        console.log('jQuery:',jQuery.fn.jquery,' -OwlSlider -MultiModule:',{$id}); 
        
        jQuery(".slider.items.owl-carousel.id{$id}").owlCarousel({ 
            {$json_owlCarousel}
        });  


            
           return;   
//        var gallery = new PhotoSwipe(".slider.items.owl-carousel.id{$id} a.image");gallery.init();
 setInterval(function(){}, 1000);        
//        console.log("carousel");
}); 


script;

JFactory::getDocument()->addScriptDeclaration($script);
//<style type="text/css"></style>
//}
// </editor-fold>

return;
?> 


  