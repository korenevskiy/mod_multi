<?php
/*------------------------------------------------------------------------
# mod_newscalendar - News Calendar
# ------------------------------------------------------------------------
# author    Jesús Vargas Garita
# Copyright (C) 2010 www.joomlahill.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomlahill.com
# Technical Support:  Forum - http://www.joomlahill.com/forum
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');

//return FALSE;

if(!defined('MULTIPATH'))
    define('MULTIPATH', __DIR__); 
if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);
 
// Include the syndicate functions only once
//require_once __DIR__  . '/helper.php';
require_once MULTIPATH . '/helper.php';
//if($params->get('layout')=='_:demodesign')
//toPrint(modMultiModuleHelper::reqruireWork($params),'reqruireWork_!!!');


//$menu_assigment = (array)$params->get('menu_assigment',[]);
//$menu_id = JFactory::getApplication()->getMenu()->getActive()->id; 
//toPrint($menu_assigment,' $menu_assigment '.$module->title.' ('.$menu_id.') ------------------------------ ');
//$menu_id = JFactory::getApplication()->getMenu()->id;
//if(in_array($menu_id, $menu_assigment))
//$menu = JFactory::getApplication()->getMenu();
//$active = $menu->getActive()->title;
//toPrint($menu->getActive()->title." - ".$menu->getActive()->id.' - '.$menu->getActive()->parent_id.' - '.$menu->getActive()->alias,'ActiveMenu');




//Проверка условий показов
// <editor-fold defaultstate="collapsed" desc="Проверка условий показов">
//static $dispay_mods;
//if(is_null($dispay_mods))
//    $dispay_mods = [];
if(!modMultiModuleHelper::reqruireWork($params)){
    $pos = $module->position;
    $mod_list_pos = JModHelp::ModeuleDelete($module); 
//    toPrint($mod_list_pos,'$mod_list_pos:'.$module->id);
    
    if(empty($mod_list_pos))
        JFactory::getDocument()->addStyleDeclaration("/* Module:$module->id */\n.container-$pos{\ndisplay:none;}\n"); 
    if(empty($mod_list_pos))
        JFactory::getDocument()->setBuffer(FALSE,'modules',$module->position); 
    
    $module = NULL;
    unset($module); 
    return FALSE;
}else{
//    $dispay_mods[$module->id] = TRUE;
}// </editor-fold>


$modules = array();
// <editor-fold defaultstate="collapsed" desc="field HTML">

if ($params->get('html_show')) {
    
    $copyright_text = '';
    $copyright_show = $params->get('copyright_show');
    
    if($copyright_show){
        $copyright_text = $params->get('copyright_text');
        $copyright_text = str_replace(['%title','%sitename','%TITLE','%SITENAME'], JFactory::getConfig()->get('sitename'), $copyright_text);
        $copyright_text = str_replace(['%d','%D'], JDate::getInstance()->day, $copyright_text);
        $copyright_text = str_replace(['%y','%Y'], JDate::getInstance()->year, $copyright_text);
        $copyright_text = str_replace(['%m','%M'], JDate::getInstance()->month, $copyright_text);
        $copyright_text = "<div class='copyright'>$copyright_text</div>";
    }
    
    $fontsFiles = $params->get('fontsFiles');

    $fontsGoogle = $params->get('fontsGoogle');


    $faviconsFiles = $params->get('faviconsFiles');
    $stylesheetFiles = $params->get('stylesheetFiles');

    $stylesheetText = $params->get('stylesheetText');
    $stylesheetTag = $params->get('stylesheetTag');
    $scryptFiles = $params->get('scryptFiles');

    $scriptText = $params->get('scriptText');
    $scriptTag = $params->get('scriptTag');
    $html = $params->get('html_code');

    
    if ($faviconsFiles)
        foreach ($faviconsFiles as $key => $value) {
            JDocument::getInstance()->_links[] = trim($value, "");//JUri::base().
        }
    

//if($module->id==135)
//    toPrint($faviconsFiles,'$faviconsFiles');
//if($module->id==135)
//    toPrint(JDocument::getInstance(),'JDocument');

    if ($fontsFiles)
        modMultiModuleHelper::fontsFiles($fontsFiles);
    if ($fontsGoogle)
        modMultiModuleHelper::fontsGoogle($fontsGoogle);

    if ($stylesheetFiles)
        foreach ($stylesheetFiles as $key => $value) {
            JHtml::stylesheet(trim($value, " /\\")); //JUri::base().
        }
    if ($stylesheetText)
        JFactory::getDocument()->addStyleDeclaration("/* Module:$module->id */\n".$stylesheetText);
    if ($stylesheetTag)
        $html .= "<style type=\"text/css\" module=\"$module->id\">$stylesheetTag</style>";

    if ($scryptFiles)
        foreach ($scryptFiles as $key => $value) {
            JHtml::script(trim($value, " /\\"),[],['module'=>$module->id]);

            //JHtml::script(JUri::base().trim($value, " /\\")); 
        }
    if ($scriptText)
        JFactory::getDocument()->addScriptDeclaration("/* Module:$module->id */\n".$scriptText);
    if ($stylesheetTag)
        $html .= "<script type=\"text/javascript\" module=\"$module->id\">$scriptTag</script>";
    //throw new Exception('УРРААА');
        //if($html_code) $html .= $html_code;

    
    if($params->get('copyright_show')=='before')
        $html = $copyright_text.$html;
    if($params->get('copyright_show')=='after')
        $html .= $copyright_text;
    
    $html_order = $params->get('html_order');
 
    if ($html_order == 0){
        echo $html;}
    else {
        $modules[sprintf("%02d", $html_order) . 'html'] = $html;}
}// </editor-fold>


//echo ($params->get('layout','dev').' '.$order.' '.strlen($html));

// if($params->get('layout')=='_:demodesign')
//toPrint('YES_!!!');
//
////$params->get('layout', 'default')
//toPrint($params->get('demodesign'),'demodesign');
//toPrint($params->get('layout'),'layout_!!!');
//if($params->get('layout')=='_:demodesign') 
//toPrint($params->get('web_site'),'web_site_!!!');
//if($params->get('layout')=='_:demodesign')
//toPrint('ДЕМО!!!');




    
$current_id          = $module->id;
$current_position    = $module->position;
$params->set('title_alt',trim($params->get('title_alt'))); 

if($params->get('title_alt'))
    $module->title = $params->get('title_alt', $module->title);





//echo "<lala>$module->title</lala>";
//echo "<pre> ** ".print_r(( $params),true). "++</pre>"; 
//$type_module = $params->get('type_module');//positions,modules,menu,article,0

$moduleclass_sfx = $params->get('moduleclass_sfx');
$params->set('moduleclass_sfx',"$moduleclass_sfx multimodule id$current_id pos$current_position "); 
//$params->set('header_class',$header_class.' multiheader ');
$header_class = $params->get('header_class', 'module-header');
$params->set('header_class',$header_class.' multiheader ');

 

$params->set('id',      $module->id); 
$params->set('position',$module->position); 
$params->set('module',  $module->module); 
//$params->set('items_link',  $module->items_link); 
//$params->set('items_image',  $module->items_image); 

$params->set('showtitle',  $module->showtitle); 
$params->set('title',  $module->title); 

$params->set('menuid',  $module->menuid); 
$params->set('name',  $module->name); 
//$params->set('style',  $module->style); 
//showtitle
//id
//title
//position
//menuid
//style

/* Определение ссылки для Заголовка */
if($params->get('link_show') && $params->get('link_menu')!='_'){
    $link = modMultiModuleHelper::getMenuLink($params->get('link_menu'));
    $params->set('link',$link);
}
/* Отключение внешнего Заголовка при пустом макете для того чтобы включился внутренний*/
if (in_array($params->get('style'), ['System-none','none','no','0',0,'']))
    $module->showtitle = FALSE;  


//toPrint(0,$current_id);
//toPrint($params,'$params',0); 
//toPrint($module,'$module',0); 
//$params->get('showtitle'); 

if($params->get('disable_module_empty_count') && 
        !$params->get('description_show','') && 
        !$params->get('image_show') && 
        !$params->get('position_show') && 
        !$params->get('modules_show') && 
        !$params->get('menu_show') && 
        !$params->get('article_show') && 
        !$params->get('images_show') && 
        !$params->get('link_show') && 
        !$params->get('html_show') && 
        !$module->showtitle
        )
    return;

// new Joomla\CMS\Object\CMSObject; 
//toPrint(0,$current_id);
    

$lang = JFactory::getLanguage();
$lang->load('joomla', JPATH_ADMINISTRATOR);
$lang->load('com_modules', JPATH_ADMINISTRATOR);


$modules_ordering = $params->get('modules_ordering',1);


//    toPrint($type_module,'$type_module');
$mod="module".$module->id;
$par="params".$module->id;
$$mod=$module;
$$par=$params; 
if(($article_show = $params->get('article_show')) && ($article_id = $params->get('article_id'))){//$type_module=='article' 
    //$article_show = full,intro,content,0
    $article_order = $params->get('article_order'); 
    $modules[sprintf("%02d", $article_order).'article'] = modMultiModuleHelper::getArticles($article_id,[],$article_show);
}

if(($categories_show = $params->get('categories_show')) && ($categories_id = $params->get('categories_id'))){//$type_module=='article' 
    //$article_show = full,intro,content,0
    $categories_order = $params->get('categories_order'); 
    $modules[sprintf("%02d", $categories_order).'category'] = modMultiModuleHelper::getArticles([],$categories_id,$categories_show);
}

if($params->get('menu_show') && ($menu = $params->get('menu'))){//$type_module=='menu'
    $link_css = 'menu-anchor_css';
    $link_title = 'menu-anchor_title';
    $menu_img_show = $params->get('menu_img_show','0');
    $menu_order = $params->get('menu_order','0');
    $modules[sprintf("%02d", $menu_order).'menu'] = $menus = &modMultiModuleHelper::getMenuItems($menu);
    foreach ($menus as $id=> &$item){
        $menus[$id]->params = json_decode ($item->params);
        $css = $item->params->$link_css;
        $title = ($item->params->$link_title)?$item->params->$link_title:$item->title;
        $menus[$id]->menu_image = $item->params->menu_image;
        
        $menus[$id]->moduleclass_sfx = $link_css;
        $img = '';
        $item->image = $item->menu_image;
        if($menu_img_show)
            $img = "<img alt=\"$title\" class=\"menuimg id$item->id \" src=\"$item->menu_image\"/>";        
        if($menu_img_show =='in')
            $menus[$id]->content ="<a href=\"$item->link\" title=\"$title\ class=\"menuitem id$item->id $css \">$img <span>$item->title</span></a>";
        if($menu_img_show =='out')
            $menus[$id]->content ="$img<a href=\"$item->link\" title=\"$title\ class=\"menuitem id$item->id $css \"><span>$item->title</span></a>";
    }
}

if($position_mode = $params->get('position_show')){//$type_module=='positions'
    $positions = array(); 
    $position_module = $params->get('position_module');
    $position_modules = $params->get('position_modules');
    $position_order = $params->get('position_order','0'); 
    
    if($position_mode == 'position_module')
        $positions = explode(",", str_replace(';',',',$position_module));
    else
        $positions = array_merge($positions,(array)$position_modules) ;
    
    foreach ($positions as $key => $pos)
        if(!$pos)
            unset($positions[$key]);
    if(count($positions))
        $modules[sprintf("%02d", $position_order).'position'] = modMultiModuleHelper::getModulesFromPosition($positions,$modules_ordering, $$mod->id,$$mod->position,$params->get('style_tag3'));
}

if(($modulesID = $params->get('modules')) && $params->get('modules_show')){//$type_module=='modules'
    $modules_order = $params->get('modules_order','0'); 
    $modules[sprintf("%02d", $modules_order).'modules'] = modMultiModuleHelper::getModulesFromSelected($modulesID,$modules_ordering, $$mod->id,$$mod->position,$params->get('style_tag3'));
}

if($params->get('description_show')){
    $description_order = $params->get('description_order',0);
    $description_tag = $params->get('description_tag','div');    
    if($description_tag)
        $modules[sprintf("%02d", $description_order).'desc'] = "<$description_tag class=\"multidescription\">".$params->get('description')."</$description_tag>";
    else
        $modules[sprintf("%02d", $description_order).'desc'] =  $params->get('description');
}

if($params->get('image_show') && $params->get('image')){
    $image_order = $params->get('image_order');
    $title = $params->get('title');
    $modules[sprintf("%02d", $image_order).'img'] = $image   = "<img class=\"multiimage\" src=\"".$params->get('image')."\" alt=\"$title\" />";
}

if(($rnd = $params->get('images_show'))){
    
    $images_order = $params->get('images_order','');
    $images_folder = $params->get('images_folder','');
    $images_count = $params->get('images_count','');
    $images_links = $params->get('images_links','');
    $images_titles = $params->get('images_titles','');
    $images_texts = $params->get('images_texts','');
//    toPrint($images_folder,'$images_folder');
    //return;
    $modules[sprintf("%02d", $images_order).'images'] = $items = modMultiModuleHelper::getImages($images_folder,$rnd,$images_count, $images_links,$images_titles,$images_texts);
//    toPrint($modules[$images_order.'images'],'$modules:'.$current_id,3);
    
}

$modules = array_filter($modules);

ksort($modules);
reset($modules);

//$items;
//Завершение пустого модуля
// <editor-fold defaultstate="collapsed" desc="Завершение пустого модуля">
if($params->get('disable_module_empty_count') && empty($modules)){
    $pos = $module->position;
    $mod_list_pos = JModHelp::ModeuleDelete($module); 
//    toPrint($mod_list_pos,'$mod_list_pos:'.$module->id);
    
    if(empty($mod_list_pos))
        JFactory::getDocument()->addStyleDeclaration("/* Module:$module->id */\n.container-$pos{\ndisplay:none;}\n"); 
    if(empty($mod_list_pos))
        JFactory::getDocument()->setBuffer(FALSE,'modules',$module->position); 
    
    $module = NULL;
    unset($module); 
    return FALSE;
}
// </editor-fold>



$module=$$mod;
$params=$$par;

modMultiModuleHelper::$params = $params;

//echo "<pre> ** $type_module - pos $position_modules - ids ".join(",",$modulesID)."++</pre>"; 
//echo "<pre> ** ".print_r($modules,true)."++</pre>"; //146




//echo ($params->get('layout','dev'));
//return;
 
//if($params->get('layout')=='_:demodesign')
//echo "333";
//print ($params->get('layout'));
require JModuleHelper::getLayoutPath('mod_multimodule',$params->get('layout', 'default')); 



$module=$$mod;
$params=$$par; 