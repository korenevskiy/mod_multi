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
//JHtml::_('jquery.framework');
//JHtml::_('formbehavior.chosen', 'select');
//JHtml::_('bootstrap.tooltip');

//JHtml::_('behavior.keepalive');
//JHtml::_('bootstrap.tooltip');

// Load the smart search component language file.
//$lang = JFactory::getLanguage();
//$lang->load('com_finder', JPATH_SITE);

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

 

$prepare = function &( $item, $param = null, $context = 'com_content.article'){
    return modMultiModuleHelper::preparePlugin($item, $param, $context);
};  
 

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

//toPrint($kays, '$kays',0);
//toPrint($modules,'5article',0);  
//echo "<lala>$modules_tag</lala>";//
//$order=$params->get('order', 'ideh');
//echo $order.'123';
//echo ($params->get('layout','dev').' '.$order.' '.strlen($html)); 
//echo "123<lala>$modules_tag $style </lala>";    
//echo $modules_tag;
//foreach ($modules as $id=>$module)
//	echo $id."-";
//$modules=array();
// Show modules   
 
//ECHO <<<view
//view;   
//if($tag = $params->get($type.'_tag'))
if($tag = $params->get('modules_tag3')){
    list($tag_title, $tag_block, $tag_container, $tag_item) = explode('/', $tag);
        //toPrint(0,"$tag $tag_title, $tag_block, $tag_container, $tag_item");
        //toPrint($params->get('header_tag3',''),'header_tag3');
}

$modules;
$modules_tag = $params->get('modules_tag');  


if($module_tag2 = $params->get('module_tag2'))
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$mod_show id$id $style\"  >";
 
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



$elements = [];
$order = 0; 
$after_text = '';
$count = count($modules);

foreach ($modules as $type => &$items){
    $order++;
    if(is_string($items)){          // вывод html пользовательских полей 
        //echo $items;
        //unset($modules[$type]);
        if(1 == $order){
            echo $items;
            continue;
        }
        if($count == $order){
            $after_text .= $items;
            continue;
        }
        
        $elements[$type.sprintf("%03d", $order)] = (object)[
            'content'=>$items,
            'title'=>$title,
            'type'=>'description',
            'id'=>$i,
            'header_tag'=>'',
            'header_class'=>'',
            'module_tag'=>$params->get('description_tag'),
            'moduleclass_sfx'=>'',
            'module'=>'description',
            'showtitle'=>FALSE,
            'style'=>'',
            'position'=>'',
            'link'=>'',
            'image'=>'',
        ]; 
        continue;
    }
//    $order =  substr($type, 0, 2); // $type[0];
//    $type_ = substr($type, 2);
//    $count = count($items);
    foreach ($items as $i => &$module){ 
        $elements[$type.sprintf("%03d", $i)] = $module;
    }
    //$elements = array_merge($elements,$items);
}

$count = count($elements);
    
if(isset($tag_block) && $tag_block) 
    echo "<$tag_block  id='exTabs$id'  class=\"items count$count     \">";

$current = ''; 
$json_tabbootstrap = $params->get('json_tabbootstrap');
echo "<ul class='nav nav-tabs $json_tabbootstrap       panel-tabs' role='tablist'>";
foreach ($elements as $id => $module){
    $current = empty($current)?"active":"noactive";    
    echo " <li class='nav-item'><a  class='$current nav-link' href='#tabmod$id$module->id' data-toggle='tab' role='tab'><strong>$module->title</strong></a></li>";
}
echo "</ul>";
    

$current = '';
$i = 0;

echo "<div class='tab-content'>";
foreach ($elements as $id => $module){
    $module->text = $module->content = & $prepare($module->content);
 
    $i++; 
    $current = empty($current)?"in show active ":"noactive";
    echo "<div id='tabmod$id$module->id' role='tabpanel' class=\"item  tab-pane fade $current  i$i type_$module->type sfx$module->moduleclass_sfx id$module->id $module->module  \">";
//    if(!isset($tag_container) or empty($tag_container))
//        $tag_container = "div"; 
//    toPrint(array_keys((array)$module));
    
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
 
// Show title modules       
    $header_tag3 = $params->get('header_tag3','');
    if($header_tag3 == 'default' ){
        $header_tag3 = $module->showtitle? ($module->header_tag?:'span') : ''; 
    }
    if($header_tag3 == 'item'){
        $header_tag3 = $module->header_tag ?? 'div';
    }
    if($header_tag3){
        echo "<$header_tag3 class=\"$module->header_class\">"; 
        echo $module->title;
        echo "</$header_tag3>";
    }

    
 
 
// Show content modules  
    $content_tag3 = $params->get('content_tag3','');
    if($content_tag3 == 'default' ){
        $content_tag3 = $module->style? ($module->module_tag?:'div') : ''; 
    }
    if($content_tag3 == 'item'){
        $content_tag3 = $module->module_tag ?? '';
    }
    
    if($content_tag3) 
        echo "<$content_tag3 class=\" $module->moduleclass_sfx\">";
    echo $prepare($module->content) ;
    if($content_tag3) 
        echo "</$content_tag3>";
     
    //echo "$content"; 
    echo "</div>"; 
}
echo "</div>";   

    if(isset($tag_block) && $tag_block) 
        echo "</$tag_block>"; 

echo $after_text;

if($module_tag2 = $params->get('module_tag2'))
    echo "</$module_tag2>";
 
 
?>