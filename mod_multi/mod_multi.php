<?php defined('_JEXEC') or die('Restricted access');
/*------------------------------------------------------------------------
# mod_multi - Modules Conatinier 
# ------------------------------------------------------------------------
# author    Sergei Borisovich Korenevskiy
# Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: //explorer-office.ru/download/joomla/category/view/1
# Technical Support:  Forum - //fb.com/groups/multimodule
# Technical Support:  Forum - //vk.com/multimodule
-------------------------------------------------------------------------*/

// no direct access

use Joomla\CMS\Factory as JFactory;

//return FALSE;



defined('MULTIMOD_PATH') || define('MULTIMOD_PATH', __DIR__);
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

//$debug_info = JFactory::getConfig()->get('error_reporting');
 

// Include the syndicate functions only once
//require_once __DIR__  . '/helper.php';
require_once MULTIMOD_PATH . '/helper.php';
//if($params->get('layout')=='_:demodesign')
//toPrint(modMultiHelper::requireWork($params),'requireWork_!!!');


//$menu_assigment = (array)$params->get('menu_assigment',[]);
//$menu_id = JFactory::getApplication()->getMenu()->getActive()->id;
//toPrint($menu_assigment,' $menu_assigment '.$module->title.' ('.$menu_id.') ------------------------------ ');
//$menu_id = JFactory::getApplication()->getMenu()->id;
//if(in_array($menu_id, $menu_assigment))
//$menu = JFactory::getApplication()->getMenu();
//$active = $menu->getActive()->title;
//toPrint($menu->getActive()->title." - ".$menu->getActive()->id.' - '.$menu->getActive()->parent_id.' - '.$menu->getActive()->alias,'ActiveMenu');


$params->set('header_tag', $params->get('head_tag'));
$params->set('module_tag', $params->get('mod_tag'));

$param = $params->toObject();


//Проверка условий показов
// <editor-fold defaultstate="collapsed" desc="Проверка условий показов">
//static $dispay_mods;
//if(is_null($dispay_mods))
//    $dispay_mods = [];
if($module->position && $module->id):
//        $script = "document.querySelector('grid-child.container-component')[0].style.cssText = 'z-index: 1;';";
//        $script = "document.addEventListener('DOMContentLoaded', function(){ $script });"; 
//        JFactory::getDocument()->addScriptDeclaration($script);//echo "$pos:$id - $name +| = $script ! ";
    
if(empty($module->ajax) && !modMultiHelper::requireWork($param)){
    
    $pos = $module->position;
    $id = $module->id;
    $name = $module->module;
    $mod_list_pos = JModHelp::ModeuleDelete($module); 
//    toPrint($mod_list_pos,'$mod_list_pos:'.$module->id);
    
    static $jj;
    if(is_null($jj)){
        $jj = [];
    }
    
    
    if(empty($mod_list_pos))
        JFactory::getDocument()->addStyleDeclaration("/* Module:$id */\n.container-$pos{\ndisplay:none;}\n"); 
    if(empty($mod_list_pos))
        JFactory::getDocument()->setBuffer(FALSE,'modules',$pos); 
    if(empty($mod_list_pos))
        unset(JFactory::getDocument()->_buffer['modules'][$pos]); 
    
//if('cassiopeia' == JFactory::getApplication()->getTemplate()){
    if(empty($mod_list_pos) && empty($jj[$pos])){
        $jj[$pos] = '';
        $jj[$pos] .= "/*m$id*/var pos_count = {};var pos_del = '$pos';";
        $jj[$pos] .= "if(document.querySelector('.grid-child.container-$pos')){";
        $jj[$pos] .= "pos_count[pos_del] = document.querySelector('.grid-child.container-$pos').childElementCount??0;";
        $jj[$pos] .= "if(pos_count[pos_del]==0) {document.body.classList.remove('has-$pos');}";
        $jj[$pos] .= "if(pos_count[pos_del]==0) {document.querySelector('.grid-child.container-$pos').remove();}";
        $jj[$pos] .= "}";
        $jj[$pos] = "document.addEventListener('DOMContentLoaded', function(){ $jj[$pos] });";
        JFactory::getDocument()->addScriptDeclaration($jj[$pos]);
            
//        $script = "/*m$id*/document.body.classList.remove('has-$module->position');"; 
//        $script .= "var container$id = document.querySelector('.grid-child.container-$module->position');";
//        $script .= "if(container$id){"; 
//        $script .= "container$id.classList.add('fade','collapse','d-none');"; 
//        $script .= "container$id.classList.add('collapse');"; 
//        $script .= "};";
//        $script = "document.addEventListener('DOMContentLoaded', function(){ $script });";
//        JFactory::getDocument()->addScriptDeclaration($script);//echo "$module->position:$id - $name +| = $script ! ";
    } 
    if(($mod_list_pos)){
        unset($jj[$pos]);
    }
//    $module = NULL;
    unset($module); 
    return FALSE;
//    $script = "/*m$id*/document.body.classList.add('has-$module->position');";
//    $script .= "var container$id = document.querySelector('.grid-child.container-$module->position');";
//    $script .= "if(container$id){";
//    $script .= "container$id.classList.remove('fade','collapse','d-none');";
//    $script .= "container$id.classList.remove('collapse');";
//    $script .= "};";
//    $script = "document.addEventListener('DOMContentLoaded', function(){ $script });";
//    JFactory::getDocument()->addScriptDeclaration($script);
//    $dispay_mods[$module->id] = TRUE;
//}
}
endif;// </editor-fold>



$modules = array();
// <editor-fold defaultstate="collapsed" desc="field HTML">

if ($params->get('html_show')) {
    
    $copyright_text = '';
    $copyright_show = $param->copyright_show;
    
    if($copyright_show){
        $copyright_text = $param->copyright_text;
        $copyright_text = str_replace(['%title','%sitename','%TITLE','%SITENAME'], JFactory::getConfig()->get('sitename'), $copyright_text);
        $copyright_text = str_replace(['%d','%D'], JDate::getInstance()->day, $copyright_text);
        $copyright_text = str_replace(['%y','%Y'], JDate::getInstance()->year, $copyright_text);
        $copyright_text = str_replace(['%m','%M'], JDate::getInstance()->month, $copyright_text);
        $copyright_text = "<div class='copyright'>$copyright_text</div>";
    }
    
    $fontsFiles = $param->fontsFiles ?? '';

    $fontsGoogle = $param->fontsGoogle ?? '';
    


//Показ иконок в Head
// <editor-fold defaultstate="collapsed" desc="Показ иконок в Head">
    if ($param->favicon_show && JFactory::getDocument()->getType() == 'html'){
        //\libraries\joomla\document\html.php class JDocumentHtml
        JFactory::getDocument()->addCustomTag("<meta name='msapplication-starturl' content='./'>");
        $param->favicon_title; 
        $param->favicon_tooltip; 
//        JFactory::getDocument()->addCustomTag("<meta name = 'apple-mobile-web-app-title' content = 'AppTitle'>");
//        JFactory::getDocument()->addCustomTag("<meta name='msapplication-tooltip' content='Title'>"); 
//        JFactory::getDocument()->addCustomTag("<meta name='application-name' content='Title'/> ");
        
        $favicon_files_ico = $param->favicon_files_ico ?:[];
        
        foreach ($favicon_files_ico as $key => $value) {
            //JDocument::getInstance()->_links[] = trim($value, "");//JUri::base().
            JFactory::getDocument()->addFavicon(''.$value);  // ??????
            //JFactory::getDocument()->addCustomTag("<link rel='icon'  type='image/vnd.microsoft.icon' href='$value'>");
        }
        $favicon_files = $param->favicon_files;
        $icons_any_size = [16,32,48,64,96];
        $icons_ios_size = [57,60,72,76,114,120,144,152,180];
        $icons_win_size = [70,150,310]; 
        $icons_andr_size = [192];
        $icons_oper_size = [228];
        $favicon_color = $param->favicon_color; 
        
        
        $uri_base = JUri::base(); 
        
        
        if ($favicon = $param->favicon_files_100){// PNG any size
            //JFactory::getDocument()->addCustomTag("<link href='$favicon' rel='apple-touch-icon'>");
            JFactory::getDocument()->addFavicon($favicon, $type = FALSE, $relation = 'apple-touch-icon');
            //JFactory::getDocument()->addCustomTag("<link rel='icon' sizes='any' type='image/png' href='$favicon'>");
            JFactory::getDocument()->addHeadLink($favicon, 'icon', 'rel', ['sizes'=>'any','type'=>'image/png']);
            JFactory::getDocument()->addCustomTag("<meta name='msapplication-TileImage' content='$favicon'>");
            JFactory::getDocument()->addCustomTag("<meta name='yandex-tableau-widget' content='logo=$uri_base$favicon, color=$favicon_color' />");
            
//            JFactory::getDocument()->addHeadLink($href, $relation, $relType = 'rel', $attribs = array());
//            JFactory::getDocument()->addFavicon($href, $type = 'image/vnd.microsoft.icon', $relation = 'shortcut icon');
        }
        if (($favicon = $param->favicon_files_0) != -1){// SVG
//            JFactory::getDocument()->addCustomTag("<link rel='mask-icon' color='$favicon_color' href='$favicon'>");
//            JFactory::getDocument()->addCustomTag("<link rel='icon' sizes='any' type='image/svg+xml' href='$favicon'>");
            
            JFactory::getDocument()->addHeadLink($favicon, 'mask-icon', 'rel', ['color'=>$favicon_color]);
            JFactory::getDocument()->addHeadLink($favicon, 'icon', 'rel', ['sizes'=>'any','type'=>'image/svg+xml']);
        }
        
        foreach ($icons_any_size as $size){
            if($favicon = $para->{'favicon_files_'.$size})
//            JFactory::getDocument()->addCustomTag("<link rel='icon' sizes='{$size}x{$size}' type='image/png' href='$favicon'>");
            JFactory::getDocument()->addHeadLink($favicon, 'icon', 'rel', ['sizes'=>"{$size}x{$size}",'type'=>'image/png']);
        }
        foreach ($icons_ios_size as $size){
            if($favicon = $param->{'favicon_files_'.$size})
            //JFactory::getDocument()->addCustomTag("<link rel='apple-touch-icon' sizes='{$size}x{$size}' href='$favicon'>");
            JFactory::getDocument()->addHeadLink($favicon, 'apple-touch-icon', 'rel', ['sizes'=>"{$size}x{$size}"]);
        }
        foreach ($icons_win_size as $size){
        if($favicon = $param->{'favicon_files_'.$size })
            JFactory::getDocument()->addCustomTag("<meta name='msapplication-square{$size}x{$size}logo' content='$favicon'>");
        }
        if ($favicon = $param->favicon_files_310150){
            JFactory::getDocument()->addCustomTag("<meta name='msapplication-square310x150logo' content='$favicon'>");
        }
        if ($favicon_color){ 
            JFactory::getDocument()->addCustomTag("<meta name='theme-color' content='$favicon_color'>");
            JFactory::getDocument()->addCustomTag("<meta name='msapplication-TileColor' content='$favicon_color'>");
            JFactory::getDocument()->addCustomTag("<meta name='apple-mobile-web-app-status-bar-style' content='$favicon_color'/>");
        } 
    }
// </editor-fold>      
            
    $html = $param->html_code ?? '';

//if($module->id==135)
//    toPrint($faviconsFiles,'$faviconsFiles');
//if($module->id==135)
//    toPrint(JDocument::getInstance(),'JDocument');

    if ($fontsFiles)
        modMultiHelper::fontsFiles($fontsFiles, $module->id);
    if ($fontsGoogle)
        modMultiHelper::fontsGoogle($fontsGoogle, $module->id);

    if ($param->stylesheetFiles ?? [])
        foreach ($param->stylesheetFiles as $key => $value) {
            JHtml::stylesheet(trim($value, " /\\"),[],['moduleid'=>$module->id]); //JUri::base().
        }
    if ($param->stylesheetText ?? '')
        JFactory::getDocument()->addStyleDeclaration("/* Module:$module->id */\n$param->stylesheetText");
    if ($param->stylesheetTag ?? '')
        $html .= "<style type='text/css' module='$module->id'>$param->stylesheetTag</style>";

    if ($param->scryptFiles ?? [])
        foreach ($param->scryptFiles as $key => $value) {
            JHtml::script(trim($value, " /\\"),[],['module'=>$module->id]);

            //JHtml::script(JUri::base().trim($value, " /\\")); 
        }
    if ($param->scriptText ?? '')
        JFactory::getDocument()->addScriptDeclaration("/* Module:$module->id */\n$param->scriptText");
    if ($param->scriptTag ?? '')
        $html .= "<script type='text/javascript' module='$module->id'>$param->scriptTag</script>";
    //throw new Exception('УРРААА');
        //if($html_code) $html .= $html_code;

    
    if($param->copyright_show=='before')
        $html = $copyright_text.$html;
    if($param->copyright_show=='after')
        $html .= $copyright_text;
            
 
    if ($param->html_order == 0){
        echo $html;}
    else {
        $modules[sprintf("%02d", $param->html_order) . 'html'] = $html;}
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
$param->title_alt    = $params->set('title_alt',trim($param->title_alt)); 


//echo toPrint($module,'$module ',0,$module->id==299);
//echo toPrint($params->toObject(),'$params ',0,$module->id==299);
//echo toPrint($modules,'$modules ',0,$module->id==299);
//return '';

/* Определение Альтернативного Заголовка */
if($params->get('title_alt_show')){
    $module->title = $param->title_alt ?: $module->title;
    $title_alt_show = $param->title_alt_show;
    $menuitem = NULL;
    if(in_array($title_alt_show, ['cur_menu','cur_tab','cur_page'])){
        switch($param->title_alt_header){
            case 'cur_menu': $menuitem = JFactory::getApplication()->getMenu()->getActive(); break;
            case 'main_menu': $menuitem = JFactory::getApplication()->getMenu()->getDefault(); break;// getItems('home', 1);
            default: $menuitem = JFactory::getApplication()->getMenu()->getItem($param->title_alt_header); break;
        }
    }
    switch ($title_alt_show){
        case 'text':  $module->title = $param->title_alt?:$module->title; break;     //toPrint('TextBreak'); 
        case 'cur_menu': $module->title = $menuitem->title; break;                          //toPrint('CurMenuBreak');
        case 'cur_tab':  $module->title = $menuitem->getParams()->get('page_title',$menuitem->title); break; //toPrint($menuitem,'CurTabBreak');
        case 'cur_page':$module->title = $menuitem->getParams()->get('page_heading',$menuitem->title); break; //toPrint('CurPageBreak'); ; 
        default: $module->title = $menuitem->title; break;
    } 
    $param->title = $params->set('title',  $module->title);
    
} 
/* Определение ссылки для Заголовка */
if($param->link_show && $param->link_menu!='_'){
    jimport( 'joomla.methods' );
    $link = '#';
    $link_type_menu = $param->link_menu;
    $menuitem = NULL;
    switch ($link_type_menu){ 
        case 'text': $menuitem = (object)['link'=>$param->link?:'#','route'=>($param->link?:'#'),'id'=>'']; break;
        case 'home_menu': $menuitem = (object)['link'=>'','route'=>'','id'=>'']; break;
        case 'cur_menu': $menuitem = JFactory::getApplication()->getMenu()->getActive(); break;
        case 'main_menu': $menuitem = JFactory::getApplication()->getMenu()->getDefault(); break;
        default: $menuitem = JFactory::getApplication()->getMenu()->getItem($param->link_menu);// modMultiHelper::getMenuLink_fromAlias($link_type_menu); break;
    } 
//    toPrint($menuitem,'$menuitem'.$current_id.' -'.$link_type_menu,0);
    if(JFactory::getConfig()->get('sef',TRUE)){
        $link = JURI::base().$menuitem->route;//$menuitem->route
    } 
    else {
        $link = JURI::base().$menuitem->link. (($menuitem->id)?'&Itemid='.$menuitem->id:'');//$menuitem->route
    }
//    toPrint($link,'$link'); 
    $link = JRoute::_( $link); 
    $param->link = $params->set('link',$link);
    
//    toPrint($link,'$link');
//    toPrint($menuitem,'$menuitem');
}


//require_once JPATH_ROOT .'/modules/mod_multi_form/functions.php';
//toPrint($query,'',0,'pre',true);
//toPrint($module,'ModuleX',0,'pre');
//return;

//echo "<lala>$module->title</lala>";
//echo "<pre> ** ".print_r(( $params),true). "++</pre>"; 
//$type_module = $params->get('type_module');//positions,modules,menu,article,0
        
$param->moduleclass_sfx = $params->set('moduleclass_sfx',"$param->moduleclass_sfx multimodule id$current_id pos$current_position"); 
$param->header_class    = $params->set('header_class',($param->header_class?:'module-header').' multiheader ');

 
$param->id          = $params->set('id',      $module->id); 
$param->position    = $params->set('position',$module->position); 
$param->module      = $params->set('module',  $module->module); 
//$param->items_link  = $params->set('items_link',  $module->items_link); 
//$param->items_image = $params->set('items_image',  $module->items_image); 

$param->showtitle = $params->set('showtitle',   $module->showtitle);
$param->title     = $params->set('title',       $module->title);

$param->menuid    = $params->set('menuid',      $module->menuid??false);
$param->name      = $params->set('name',        $module->name??'multi');
//$params->set('style',  $module->style); 
//showtitle
//id
//title
//position
//menuid
//style

/* Отключение внешнего Заголовка при пустом макете для того чтобы включился внутренний*/
if (in_array($param->style, ['System-none','none','no','0',0,'']))
    $module->showtitle = FALSE;  


//toPrint(0,$current_id);
//toPrint($params,'$params',0); 
//toPrint($module,'$module',0); 
//$params->get('showtitle'); 

if($param->disable_module_empty_count && 
        !($param->description_show?:'') && 
        !$param->image_show && 
        !$param->position_show && 
        !$param->modules_show && 
        !$param->menu_show && 
        !$param->article_show && 
        !$param->images_show && 
        !$param->link_show && 
        !$param->html_show && 
        !$module->showtitle
        )
    return;

// new Joomla\CMS\Object\CMSObject; 
//toPrint(0,$current_id);
    

$lang = JFactory::getLanguage();
$lang->load('joomla', JPATH_ADMINISTRATOR);
$lang->load('com_modules', JPATH_ADMINISTRATOR);



$module->empty_list = TRUE;
//    toPrint($type_module,'$type_module');
$mod="module".$module->id;
$par="params".$module->id;
$$mod=$module;
$$par=$params; 






// Article
if($param->article_show && $param->article_id){//$type_module=='article'
    //$param->article_show = full,intro,content,0
    $article_order = $param->article_order; 
    $modules[sprintf("%02d", $article_order).'article'] = modMultiHelper::getArticles([$param->article_id],[],$param->article_show, $module->id);
    if($modules[sprintf("%02d", $article_order).'article'])
        $module->empty_list = FALSE; 
}
//Articles
if($param->articles_show && $param->articles_id){//$type_module=='article' 
    //$param->articles_show = full,intro,content,0 
    $modules[sprintf("%02d", $param->articles_order).'articles'] = modMultiHelper::getArticles([],$param->articles_id,$param->articles_show, $module->id);
    if($modules[sprintf("%02d", $param->articles_order).'articles'])
        $module->empty_list = FALSE; 
//    toPrint(reset($modules[sprintf("%02d", $param->articles_order).'articles'])->link,'link0-'.$current_id.' '.sprintf("%02d", $param->articles_order));
}
// В разработке!!!!!!! categories
if($param->categories_show && $param->categories_id){//$type_module=='article' 
    //$article_show = full,intro,content,0 
    $modules[sprintf("%02d", $param->categories_order).'categories'] = modMultiHelper::getArticles([],$param->categories_id,$param->categories_show);
    if($modules[sprintf("%02d", $param->categories_order).'categories'])
        $module->empty_list = FALSE; 
}
//
if($param->menu_show && $param->menu){//$type_module=='menu'
    $link_css = 'menu-anchor_css';
    $link_title = 'menu-anchor_title'; 
    $modules[sprintf("%02d", $param->menu_order).'menu'] = $menus = &modMultiHelper::getMenuItems($param->menu); 
    
    foreach ($menus as $id=> &$item){
        $menus[$id]->params = json_decode ($item->params);
        if(empty($menus[$id]->params->menu_show)){
            unset($menus[$id]);
            unset($modules[sprintf("%02d", $param->menu_order).'menu'][$id]);
            unset($item);
            continue;
        }
        $css = $item->params->$link_css;
        $title = ($item->params->$link_title)?$item->params->$link_title:$item->title;
        $menus[$id]->menu_image = $item->params->menu_image;
        
        $menus[$id]->link = JRoute::_($item->link);
        
        //require_once JPATH_BASE . '/components/com_content/helpers/route.php';
        //$items[$key]->link = JRoute::_(ContentHelperRoute::getArticleRoute($key, $item->catid)); 
        $module->header_tag = 'none';
//        $module->module_tag = 'none';
        $menus[$id]->showtitle = TRUE;
        $menus[$id]->moduleclass_sfx = $link_css;
        $img = '';
        $item->image = $item->menu_image;
        if($param->menu_img_show)
            $img = "<img alt='$title' class='menuimg id$item->id ' src='$item->menu_image'/>";        
        elseif($param->menu_img_show =='in')
            $menus[$id]->content ="<a href='$item->link' title='$title' class='menuitem id$item->id $css '>$img <span>$item->title</span></a>";
        elseif($param->menu_img_show =='out')
            $menus[$id]->content ="$img<a href='$item->link' title='$title' class='menuitem id$item->id $css '><span>$item->title</span></a>";
        else
            $menus[$id]->content = '';
    }
    if($modules[sprintf("%02d", $param->menu_order).'menu'])
        $module->empty_list = FALSE;
    $modules[sprintf("%02d", $param->menu_order).'menu']->module = 'menu';
//toPrint($menus,'}',0);
}
// Modules positions
if($param->position_show){//$type_module=='positions'  
    
    if($param->position_show == 'position_module')
        $positions = modMultiHelper::split($param->position_module, [' ',',',';','\n','\r','\t']);
        //$positions = explode(",", str_replace(';',',',$param->position_module));
    else
        $positions = (array)$param->position_modules;

    if($positions):  
    
    if($param->position_ordering == 'position_ordering'){
        $ord = implode(',', $positions);
        $param->position_ordering =  " FIND_IN_SET(position, '$ord'), ";
    }
//return;
//toPrint($positions,'$positions',0);
    $modules[sprintf("%02d", $param->position_order).'position'] = modMultiHelper::getModulesFromPosition($positions,$param->position_ordering, $$mod->id,$$mod->position,$param->style_tag3);
    
    if($modules[sprintf("%02d", $param->position_order).'position'])
        $module->empty_list = FALSE;
    endif;
} 
// Modules ID
if($param->modules_show){//$type_module=='modules' 
        
    if($param->modules_show == 'id')
        $modulesID = modMultiHelper::split($param->modules_ids, [' ',',',';','\n','\r','\t']);
        //$modulesID = explode(",", str_replace(';',',',$param->modules_sel));
    else
        $modulesID = (array)$param->modules_sel;
    if($modulesID): 
    if($param->modules_ordering == 'modules_ordering'){
        $ord = implode(',', $modulesID);
        $param->modules_ordering =  " FIND_IN_SET(id, '$ord'), ";
    }  
    
    $modules[sprintf("%02d", $param->modules_order).'modules'] = modMultiHelper::getModulesFromSelected($modulesID,$param->modules_ordering, $$mod->id,$$mod->position,$param->style_tag3);
    
    if($modules[sprintf("%02d", $param->modules_order).'modules'])
        $module->empty_list = FALSE;
    endif;
}
// Description
if($param->description_show ?? ''){ 
    $description_tag = $param->description_tag??'div';
    if($description_tag)
        $modules[sprintf("%02d", $param->description_order).'desc'] = "<$description_tag class='multidescription'>$param->description</$description_tag>";
    else
        $modules[sprintf("%02d", $param->description_order).'desc'] =  $param->description;
}
// Image
if( $param->image_show   && $param->image){ 
    $address = JUri::base();
    $modules[sprintf("%02d", $param->image_order).'img'] = $image   = "<img class='multiimage' src='$address/$param->image' alt='$param->title' />";
}
// Images
if($rnd = $param->images_show){
    
    $param->images_folder2 = trim($param->images_folder2)?:'/';    
    
    foreach ((array)$param->images_folder as $i => $fold){
        if($fold==-1){
            unset($param->images_folder[$i]);
            continue;
        }
        $param->images_folder[$i] = '/images/'.$fold;
    }
    
    if( $param->images_folder2 != '/' && is_dir(JPATH_SITE.'/'. $param->images_folder2)){
        $param->images_folder[] = $param->images_folder2;
    } 
    $modules[sprintf("%02d", $param->images_order).'images'] = $items = modMultiHelper::getImages($param->images_folder,$rnd,$param->images_count, $param->images_links,$param->images_titles,$param->images_texts);
//    toPrint($modules[sprintf("%02d", $param->images_order).'images'],'$modules:'.$current_id,3);
    
//toPrint($modules[sprintf("%02d", $param->images_order).'images'],'$items ',0,true, true);
    if($modules[sprintf("%02d", $param->images_order).'images'])
        $module->empty_list = FALSE;
}

if($param->query_show && trim($param->query_select)){ 
    
    $modules[sprintf("%02d", $param->query_order).'query'] = $items = modMultiHelper::getSelects(trim($param->query_select)); 
    
    if($modules[sprintf("%02d", $param->query_order).'query'])
        $module->empty_list = FALSE;
}
//\Joomla\CMS\Factory::getLanguage()-
//\Joomla\CMS\Language\Multilanguage::getSiteHomePages();

if($current_id == 308){
//    toPrint('<style type="text/css"> #page_wrap,.wcm_chat, #wcm_chat, #page_wrap{display:none!important;} 
//   .alert-message{background-color:lightsteelblue;}</style>','',0,'div'); 
//    toPrint($_SERVER['HTTP_ACCEPT_LANGUAGE'],'$_SERVER HTTP_ACCEPT_LANGUAGE ',0);
//    toPrint(JFactory::getLanguage()->getTag(),'Tag');
//    toPrint(JFactory::getLanguage()->getUsed(),'getUsed');
//    toPrint(JFactory::getLanguage()->getName(),'getName');
//    toPrint(JFactory::getLanguage()->getLocale(),'getLocale');
}


$modules = array_filter($modules);

ksort($modules, SORT_NATURAL);//SORT_STRING
reset($modules);

//$items;
//Завершение пустого модуля
// <editor-fold defaultstate="collapsed" desc="Завершение пустого модуля">
if($params->get('disable_module_empty_count')):// && empty($modules)
    $mod_keys = array_keys($modules);
    $exit = $module->empty_list; 
    //['images','modules','position','menu','category','categories','article','articles','query']
    
    if($exit){
    
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
endif;
// </editor-fold>

//toPrint(count($modules),'$modules');

$module=$$mod;
$params=$$par;

modMultiHelper::$params = $params;

//echo "<pre> ** $type_module - pos $param->position_modules - ids ".join(",",$modulesID)."++</pre>";
//echo "<pre> ** ".print_r($modules,true)."++</pre>"; //146




//echo ($params->get('layout','dev'));
//return;
 
//if($params->get('layout')=='_:demodesign')
//echo "333";
//print ($params->get('layout'));




require JModuleHelper::getLayoutPath('mod_multi',$params->get('layout', 'default'));




$module=$$mod;
$params=$$par; 

