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
//JHtml::_('jquery.framework');
//JHtml::_('formbehavior.chosen', 'select');
//JHtml::_('bootstrap.tooltip');

//JHtml::_('behavior.keepalive');
//JHtml::_('bootstrap.tooltip');

// Load the smart search component language file.
//$lang = JFactory::getLanguage();
//$lang->load('com_finder', JPATH_SITE);

$param = (new Joomla\Registry\Registry($params))->toObject();//*** 

$id      = $params->get('id');
$positon = $params->get('position');


$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$showtitle = $params->get('showtitle');
$title = htmlspecialchars($params->get('title'));
$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'page-header'));

$image_show = $params->get('image_show');
$image = $params->get('image'); 

$description_show = $params->get('description_show');
$description = $params->get('description'); 

$link_show = $params->get('link_show'); 
$link = $params->get('link');

  
$prepare = function ( $item, $param = null, $context = 'com_content.article'){
    return modMultiHelper::preparePlugin($item, $param, $context);
};  
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
$type_module = $params->get('type_module'); 

if($modules_tag=='default'){
    switch ($type_module){
        case 'positions':
            $modules_tag = "ul"; break;
        case 'modules':
            $modules_tag = "ul"; break;
        case 'article':
            $modules_tag = "div"; break;
        default :
            $modules_tag = "empty";
    }
}

$self_module_tag=$params->get('self_module_tag', '0');
if($self_module_tag)echo "<$module_tag class=\"multimodule$moduleclass_sfx id$id\"  >";
//
//
if ($link_show)	
    echo "<a href=\"$link\" title=\"$title\" class=\"id$id multiheadera\"> <$header_tag class=\"$header_class\">$title</$header_tag></a>";
else if ($self_module_tag && $showtitle)	
    echo "<$header_tag class=\"$header_class\">$title</$header_tag>";

if($image_show       && $image)          echo "<img class=\"multiimage\" src=\"$image\" alt=\"$title\" />";
if($description_show && $description)    echo "<div class=\"multidescription\">$description</div>";

$mod_show = count($modules);
// Show container tag
//if ($modules_tag!='div div' && $mod_show)
//    echo "<div class=\"multimodules modules\">";
//else if ($modules_tag!='empty' && $mod_show)
//    echo "<$modules_tag class=\"multimodules modules\">";


echo "---";

//toPrint($modules,'$modules',0);


//echo $modules_tag;
//$modules=array();
// Show modules   

$items = $modules;
$modules = [];
$headers = [];
$contents = [];
foreach($items as $id=>$type_item){
    if(is_string($type_item)){
        echo $type_item;
        continue;
    }
    foreach ($type_item as $i => $module){
	$headers[$id.$i]=$module->title;
	$contents[$id.$i]=$module->content;
	$modules[$id.$i]=$module;
    }
}
echo "<table class=\"multimodules modules\">";

if($params->get('header_tag3')){

    $header_tag3 = $params->get('header_tag3');
    $items_link = $params->get('items_link');

    if($params->get('header_tag3') == 'default'){
        $header_tag3 = '';
    }
    if($params->get('header_tag3') == 'default' && $module->showtitle){
        $header_tag3 = $module->header_tag ?: 'div';
    }
    if($params->get('header_tag3') == 'item'){ 
        $header_tag3 = $module->header_tag;
    } 
    echo "<tr class=\" headers  \">";
    foreach($headers as $id=>$header){
	$module = $modules[$id];
	echo "<th class=\"$module->header_class id$module->id $module->module $module->moduleclass_sfx\">";
                
                
        if($params->get('items_link')=='ha'){
            $module_title = "<$header_tag3 class=\" item_title $module->header_class\"><a href='$module->link' class='$class' title='$module->title' >$module->title</a></$header_tag3>";
        }
        if($params->get('items_link')=='ah'){
            $module_title = "<a href='$module->link' class='$class item_title' title='$module->title' ><$header_tag3 class=\"  $module->header_class\">$module->title</$header_tag3></a>";
        }
        if($params->get('items_link')=='0'){
            $module_title = "<$header_tag3 class=\"$class item_title $module->header_class\" title='$module->title' >$module->title</$header_tag3>";
        }
        echo $prepare($module_title);                      
                
                
//        echo $header;        
        
	echo "</th>";
    }
    echo "</tr>";
}

echo "<tr class=\" contents  \">";
	foreach($contents as $id=>$content){
		$module = $modules[$id];
		if($module->module_tag)
                    echo "<td><$module_tag  class=\"$module->header_class id$module->id $module->module $module->moduleclass_sfx\"> "; 
		else 
                    echo "<td class=\"$module->header_class id$module->id $module->module $module->moduleclass_sfx\">";
                if($modules[$id]->image)
                    echo $prepare("<img src=\"{$modules[$id]->image}\" width=\"300\" /> "); 
                echo $prepare($content);
		if($module->module_tag) 
                    echo "</$module_tag> "; 
		echo "</div></td>";
	}
echo "</tr>";


// Show container tag
//if ($modules_tag!='div div' && $mod_show)
//    echo "</div>";
//else if ($modules_tag!='empty' && $mod_show) 
//	echo "</$modules_tag>";
echo "</table>";

if($self_module_tag)echo "</$module_tag>";
?>