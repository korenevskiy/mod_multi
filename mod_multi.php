<?php defined('_JEXEC') or die('Restricted access');
/** ----------------------------------------------------------------------
# mod_multi - Modules Conatinier
# ------------------------------------------------------------------------
# author    Sergei Borisovich Korenevskiy
# Copyright (C) 2010 www.explorer-office.ru. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: https://explorer-office.ru/download/joomla/category/view/1
# Technical Support:  Forum - https://fb.com/groups/multimodule
# Technical Support:  Forum - https://vk.com/multimodule
# ------------------------------------------------------------------------
*/

use \Joomla\CMS\Factory as JFactory;
use \Joomla\CMS\HTML\HTMLHelper as JHtml;
use \Joomla\CMS\Helper\ModuleHelper as JModuleHelper;

defined('MULTIMOD_PATH') || define('MULTIMOD_PATH', __DIR__);
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

if(!function_exists('toPrint') && file_exists(JPATH_ROOT . '/modules/mod_multi_form/functions.php'))
	require_once  __DIR__ . '/../mod_multi_form/functions.php';

if(!function_exists('toPrint') && file_exists(JPATH_ROOT . '/functions.php'))
	require_once  JPATH_ROOT . '/functions.php';

if(empty(class_exists('\Reg'))){
	include_once MULTIMOD_PATH . "/libraries/reg.php";
}
require_once MULTIMOD_PATH . '/helper.php';

if( ! $params instanceof Reg)
	$params = (new Reg())->merge($params);


$param = &$params; // $params->toObject();
$module->params = &$params;
$param->id = $module->id;


$module->ajax = $module->ajax ?? false;

/** Проверка условий показов */
// <editor-fold defaultstate="collapsed" desc="Проверка условий показов">
if($module->position && $module->id):

if(empty($module->ajax) && !ModMultiHelper::requireWork($param)){
//toPrint($module,'$module',0, 'message',true);
	$module->ajax = false;
    $pos	= $module->position;
    $id		= $module->id;
    $name	= $module->module;
	$mod_count_pos = 0;
    $mod_count_pos = &JModHelp::ModeuleDelete($module);
//echo "<h1>Приет дорогой друг</h1>" . $module->position . '<br>'; return;

    static $jj;
    if(is_null($jj)){
        $jj = [];
    }

    if(empty($mod_count_pos))
        JFactory::getDocument()->addStyleDeclaration("/* Module:$id */\n.container-$pos{\ndisplay:none;}\n");
    if(empty($mod_count_pos))
        JFactory::getDocument()->setBuffer(FALSE,['type' => 'modules','name' => $pos]);
    if(empty($mod_count_pos))
        JFactory::getDocument()->setBuffer(FALSE,['type' => 'module','name' => $pos]);
    if(empty($mod_count_pos)){
//toPrint(JFactory::getDocument()::$_buffer ,' ',0, 'message',true);
//		$buf = &JFactory::getDocument()::$_buffer['modules'][$pos];
//		unset($buf);
		unset(JFactory::getDocument()::$_buffer['modules'][$pos]);
		unset(JFactory::getDocument()::$_buffer['module'][$pos]);
	}

    if(empty($mod_count_pos) && empty($jj[$pos])){
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

    }
    if(($mod_count_pos)){
        unset($jj[$pos]);
    }

    unset($module);
    return FALSE;
//    $script = "/*m$id*/document.body.classList.add('has-$module->position');";
}

endif;

$modules = array();
// <editor-fold defaultstate="collapsed" desc="field HTML">

if ($param->get('html_show')) {

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

    $fontsFiles = $param->fontsFiles ?? [];

    $fontsGoogle = $param->fontsGoogle ?? [];

/* Показ иконок в Head */
// <editor-fold defaultstate="collapsed" desc="Показ иконок в Head">
    if ($param->favicon_show && JFactory::getDocument()->getType() == 'html'){

        JFactory::getDocument()->addCustomTag("<meta name='msapplication-starturl' content='./'>");
        $param->favicon_title;
        $param->favicon_tooltip;

        $favicon_files_ico = $param->favicon_files_ico ?? [];

        foreach ((array)$favicon_files_ico as $key => $value) {
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

        if ($favicon = $param->favicon_files_100){

            JFactory::getDocument()->addFavicon($favicon, $type = FALSE, $relation = 'apple-touch-icon');
            //JFactory::getDocument()->addCustomTag("<link rel='icon' sizes='any' type='image/png' href='$favicon'>");
            JFactory::getDocument()->addHeadLink($favicon, 'icon', 'rel', ['sizes'=>'any','type'=>'image/png']);
            JFactory::getDocument()->addCustomTag("<meta name='msapplication-TileImage' content='$favicon'>");
            JFactory::getDocument()->addCustomTag("<meta name='yandex-tableau-widget' content='logo=$uri_base$favicon, color=$favicon_color' />");

        }
        if (($favicon = $param->favicon_files_0) != -1){
            JFactory::getDocument()->addHeadLink($favicon, 'mask-icon', 'rel', ['color'=>$favicon_color]);
            JFactory::getDocument()->addHeadLink($favicon, 'icon', 'rel', ['sizes'=>'any','type'=>'image/svg+xml']);
        }

        foreach ($icons_any_size as $size){
            if($favicon = $param->{'favicon_files_'.$size})

            JFactory::getDocument()->addHeadLink($favicon, 'icon', 'rel', ['sizes'=>"{$size}x{$size}",'type'=>'image/png']);
        }
        foreach ($icons_ios_size as $size){
            if($favicon = $param->{'favicon_files_'.$size})

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

    if ($fontsFiles)
        ModMultiHelper::fontsFiles($fontsFiles, $module->id);
    if ($fontsGoogle)
        ModMultiHelper::fontsGoogle($fontsGoogle, $module->id);

    if ($param->stylesheetFiles ?? [])
        foreach ($param->stylesheetFiles as $key => $value) {
            JHtml::stylesheet(trim($value, " /\\"),[],['moduleid'=>$module->id]);
        }
    if ($param->stylesheetText ?? '')
        JFactory::getDocument()->addStyleDeclaration("/* Module:$module->id */\n$param->stylesheetText");
    if ($param->stylesheetTag ?? '')
        $html .= "<style type='text/css' module='$module->id'>$param->stylesheetTag</style>";

    if ($param->scryptFiles ?? [])
        foreach ($param->scryptFiles as $key => $value) {
            JHtml::script(trim($value, " /\\"),[],['module'=>$module->id]);

        }
    if ($param->scriptText ?? '')
        JFactory::getDocument()->addScriptDeclaration("/* Module:$module->id */\n$param->scriptText");
    if ($param->scriptTag ?? '')
        $html .= "<script type='text/javascript' module='$module->id'>$param->scriptTag</script>";

    if($param->copyright_show=='before')
        $html = $copyright_text.$html;
    if($param->copyright_show=='after')
        $html .= $copyright_text;

    if ($param->html_order == 0){
        echo $html;}
    else {
        $modules[sprintf("%02d", $param->html_order) . 'html'] = $html;}
}// </editor-fold>

$current_id          = $module->id;
$current_position    = $module->position;
$param->title_alt    = $param->set('title_alt',trim($param->title_alt));

/* Определение Альтернативного Заголовка */
if($param->get('title_alt_show')){
    $module->title = $param->title_alt ?: $module->title;
    $menuitem = NULL;

    if($param->title_alt_show == 'cur_comp'){
		$pathes = JFactory::getApplication()->getPathway()->getPathway();
        $pathway =  end($pathes);

        if($pathway){
            $menuitem = new stdClass;
            $menuitem->title = $pathway->name;
            $menuitem->link = $pathway->link;

        }else{
            $menuitem = JFactory::getApplication()->getMenu()->getActive();

            $menuitem->title = $menuitem->getParams()->get('page_title',$menuitem->title);

        }
    }

    if(in_array($param->title_alt_show ?? '', ['text','cur_menu','cur_tab','cur_page'])){
        switch($param->title_alt_header ?? ''){
            case 'cur_menu': $menuitem = JFactory::getApplication()->getMenu()->getActive(); break;
            case 'main_menu': $menuitem = JFactory::getApplication()->getMenu()->getDefault(); break;
            default: $menuitem = JFactory::getApplication()->getMenu()->getItem($param->title_alt_header); break;
        }
    }
    switch ($param->title_alt_show ?? ''){
        case 'text':  $module->title = $param->title_alt?:$module->title; break;     //toPrint('TextBreak'); 
        case 'cur_menu': $module->title = $menuitem->title; break;                          //toPrint('CurMenuBreak');
        case 'cur_tab':  $module->title = $menuitem->getParams()->get('page_title',$menuitem->title); break; //toPrint($menuitem,'CurTabBreak');
        case 'cur_page':$module->title = $menuitem->getParams()->get('page_heading',$menuitem->title); break; //toPrint('CurPageBreak'); ; 
        default: $module->title = $menuitem->title; break;
    }
    $param->title = $module->title;

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
        default: $menuitem = JFactory::getApplication()->getMenu()->getItem($param->link_menu);
    }

    if(JFactory::getConfig()->get('sef',TRUE)){
        $link = JURI::base().$menuitem->route;
    }
    else {
        $link = JURI::base().$menuitem->link. (($menuitem->id)?'&Itemid='.$menuitem->id:'');
    }

    $link = JRoute::_( $link);
    $param->link = $param->set('link',$link);

}

$module->moduleclass_sfx = $param->moduleclass_sfx = "$param->moduleclass_sfx multimodule id$current_id pos$current_position ";
$module->header_class = $param->header_class = ($param->header_class?:'module-header').' multiheader ';

$param->id          = $module->id;
$param->position    = $module->position;
$param->module      = $module->module;

$param->showtitle = $module->showtitle;
$param->title     = $module->title;

$param->menuid    = $module->menuid??false;
$param->name      = $module->name??'multi';

/* Отключение внешнего Заголовка при пустом макете для того чтобы включился внутренний*/
if (in_array($param->style, ['System-none','none','no','0',0,''], true))
    $module->showtitle = FALSE;

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

$lang = JFactory::getLanguage();
$lang->load('joomla', JPATH_ADMINISTRATOR);
$lang->load('com_modules', JPATH_ADMINISTRATOR);

$module->empty_list = TRUE;

$mod="module".$module->id;
$par="params".$module->id;
${$mod} = & $module;
${$par} = & $param;

/* Article */
if($param->article_show && ($param->article_id || $param->article_ids)){
	
	$param->article_ids = array_filter(explode(' ', str_replace(',', ' ', $param->article_ids)),
			fn($num)=> is_numeric($num) && $num >= 0);
	
    $articles = ModMultiHelper::getArticles([$param->article_id, ...$param->article_ids], [], ($param->item_tag!='none'?$param->article_show:'') , $module->id);
    $modules[sprintf("%02d", $param->article_order).'article'] = [];
	
	foreach ($param->article_ids as $id){
		if(isset($articles[$id]))
			$modules[sprintf("%02d", $param->article_order).'article'][] = &$articles[$id];
	}
	
	if($modules[sprintf("%02d", $param->article_order).'article'] ?? false)
        $module->empty_list = FALSE;
}

/* Articles */
if($param->articles_show){
	
	$param->articles_id = $param->articles_id || !in_array('', (array)$param->articles_id, true) ? array_filter((array)$param->articles_id) : [];
	$param->articles_tags = $param->articles_id || !in_array('', (array)$param->articles_tags, true) ? array_filter((array)$param->articles_tags) : [];
	
	if($param->articles_id || $param->articles_tags){
		$modules[sprintf("%02d", $param->articles_order).'articles'] = ModMultiHelper::getArticles([], $param->articles_id, ($param->item_tag!='none'?$param->articles_show:'') , $module->id, $param->articles_tags, $param->articles_sort??'ordering ASC');
	}
	
	if($modules[sprintf("%02d", $param->articles_order).'articles'] ?? false)
		$module->empty_list = FALSE;
}
/* В разработке!!!!!!! categories */
if($param->categories_show && $param->categories_id){
	
	$param->categories_id = in_array('', $param->categories_id,true) ? [] : array_filter($param->categories_id);
	
    if($param->categories_id)
		$modules[sprintf("%02d", $param->categories_order).'categories'] = ModMultiHelper::getArticles([], $param->categories_id, $param->categories_show);
    
	if($modules[sprintf("%02d", $param->categories_order).'categories'] ?? false)
        $module->empty_list = FALSE;
}
/* Пункты меню */
if($param->menu_show && $param->menu){
    $link_css = 'menu-anchor_css';
    $link_title = 'menu-anchor_title';
	
    $modules[sprintf("%02d", $param->menu_order).'menu'] = $menus = &ModMultiHelper::getMenuItems($param->menu);
	
//echo "<pre>".print_r($param->menu_img_show,true)."</pre>";
//return;

    foreach ($menus as $id=> &$item){
        $menus[$id]->params = json_decode ($item->params);
        if(empty($menus[$id]->params->menu_show)){
            unset($menus[$id]);
            unset($modules[sprintf("%02d", $param->menu_order).'menu'][$id]);
            unset($item);
            continue;
        }
		
		$link_class = '';
		$link_title = '';
		if($item->params->$link_css ?? false)
			$item->link_class = $link_class = $item->params->$link_css;
		
		if($item->params->$link_title ?? false)
			$item->link_title = $link_title = $item->params->$link_title;
		
//        $menus[$id]->header_class = $link_class;
        $menus[$id]->menu_image = $item->params->menu_image;
		
		$menus[$id]->moduleclass_sfx = $link_class;

        $menus[$id]->link = JRoute::_($item->link);

        $module->header_tag = 'none';

        $menus[$id]->showtitle = TRUE;
        $img = '';
        $item->image = $item->menu_image;
		
		$menus[$id]->module = 'menu';
		$menus[$id]->module_tag = '';
		$menus[$id]->header_class = '';
		
//echo "<pre>$id : ".print_r($item->params->$link_css,true)."</pre>";
//echo "<pre>$id : ".print_r($menus[$id]->link_class,true)."</pre><br>";
//		$menus[$id]->link = 
//		$menus[$id]->module = 'menu';
//		$menus[$id]->moduleclass_sfx = '';
//		$menus[$id]->header_tag = '';
		
        if($param->menu_img_show)
            $img = "<img alt='$item->link_title' class='menuimg id$item->id ' src='$item->menu_image'/>";
		
        if($param->menu_img_show =='in')
            $menus[$id]->content ="<a href='$item->link' title='$link_title' class='menuitem a id$item->id $link_class level_$item->menu_image '>$img <span>$item->title</span></a>";
        elseif($param->menu_img_show =='out')
            $menus[$id]->content ="$img<a href='$item->link' title='$link_title' class='menuitem b id$item->id $link_class level_$item->menu_image'><span>$item->title</span></a>";
        else
            $menus[$id]->content = '';
//			$menus[$id]->content ="$img<a href='$item->link' title='$item->link_title' class='menuitem id$item->id $link_class '>$item->title</a>";
		
		if(!isset($items[$id]->items))
			$menus[$id]->items = [];
		
		
//		if(($menus[$id]->parent_id > 1 || $menus[$id]->level > 1) && !isset($menus[$menus[$id]->parent_id])){
//			echo "<pre>$id --- parent: ".$menus[$id]->parent_id."</pre>";
//		}
		if(($menus[$id]->parent_id > 1 || $menus[$id]->level > 1)){
			if(isset($menus[$menus[$id]->parent_id]))
				$menus[$menus[$id]->parent_id]->items[$id] = &$menus[$id];
//			if(isset($menus[$menus[$id]->parent_id]) && in_array($param->menu_img_show, ['in','out']))
//				$menus[$menus[$id]->parent_id]->content .= $menus[$id]->content;
			
			unset($menus[$id]);
			unset($modules[sprintf("%02d", $param->menu_order).'menu'][$id] );
			
			continue;
		}
		
		
    }
    if($modules[sprintf("%02d", $param->menu_order).'menu'])
        $module->empty_list = FALSE;
}

//toPrint($param->position_show,'$param->position_show',0,'pre',true);
/* Modules positions */
if($param->position_show){
    if($param->position_show == 'position_module')
        $positions = ModMultiHelper::split($param->position_module, [' ',',',';','\n','\r','\t']);

    else
        $positions = (array)$param->position_modules;

    if($positions):

    if($param->position_ordering == 'position_ordering'){
        $ord = implode(',', $positions);
        $param->position_ordering =  " FIND_IN_SET(position, '$ord'), ";
    }

    $modules[sprintf("%02d", $param->position_order).'position'] = ModMultiHelper::getModulesFromPosition($positions,$param->position_ordering, ${$mod}->id,${$mod}->position,$param->style_tag3);

    if($modules[sprintf("%02d", $param->position_order).'position'])
        $module->empty_list = FALSE;
    endif;
}
/* Modules ID */
if($param->modules_show){

    if($param->modules_show == 'id')
        $modulesID = ModMultiHelper::split($param->modules_ids, [' ',',',';','\n','\r','\t']);

    else
        $modulesID = (array)$param->modules_sel;
    if($modulesID):
    if($param->modules_ordering == 'modules_ordering'){
        $ord = implode(',', $modulesID);
        $param->modules_ordering =  " FIND_IN_SET(id, '$ord'), ";
    }

    $modules[sprintf("%02d", $param->modules_order).'modules'] = ModMultiHelper::getModulesFromSelected($modulesID,$param->modules_ordering, ${$mod}->id,${$mod}->position,$param->style_tag3);

    if($modules[sprintf("%02d", $param->modules_order).'modules'])
        $module->empty_list = FALSE;
    endif;
}
/* Description */
if($param->description_show ?? ''){
    $description_tag = $param->description_tag??'div';
    if($description_tag)
        $modules[sprintf("%02d", $param->description_order).'desc'] = "<$description_tag class='multidescription'>$param->description</$description_tag>";
    else
        $modules[sprintf("%02d", $param->description_order).'desc'] =  $param->description;
}
/* Image */
if( $param->image_show   && $param->image){
    $address = JUri::base();
    $modules[sprintf("%02d", $param->image_order).'img'] = $image   = "<img class='multiimage' src='$address/$param->image' alt='$param->title' />";
}
/* Images */
if($rnd = $param->images_show){

    foreach ((array)$param->images_folder as $i => $fold){
        if($fold==-1){
            unset($param->images_folder[$i]);
            continue;
        }
		$param->ArrayItem('images_folder', $i, '/images/'.$fold);
//		$param->images_folder[$i] = '/images/'.$fold;
    }
    
    foreach(explode("\n",trim($param->images_folder2)) as $img){
    	if($img && is_dir(JPATH_SITE.'/images/'. $img))
			$param->ArrayItem('images_folder','', '/images/'.$img);
//      $param->images_folder[] = $img;
    }
    
    $modules[sprintf("%02d", $param->images_order).'images'] = $items = ModMultiHelper::getImages($param->images_folder,$rnd,$param->images_count, $param->images_links,$param->images_titles,$param->images_texts);

    if($modules[sprintf("%02d", $param->images_order).'images'])
        $module->empty_list = FALSE;
}
/* Tags */
if($param->tags_show ?? false){

    $modules[sprintf("%02d", $param->tags_order).'tags'] = $items
		= ModMultiHelper::getTags($param->tags_show, $param->tags_catids??[], $param->tags_parents??[], $param->tags_maximum, $param->tags_sort, $param->tags_count, $param->tags_category_title, $param->Itemid ?? 0);

}
/*  */
if($param->query_show && trim($param->query_select)){
	$modules[sprintf("%02d", $param->query_order).'query'] = $items = ModMultiHelper::getSelects(trim($param->query_select));

    if($modules[sprintf("%02d", $param->query_order).'query'])
        $module->empty_list = FALSE;
}

//if($current_id == 308){
////    toPrint('<style type="text/css"> #page_wrap,.wcm_chat, #wcm_chat, #page_wrap{display:none!important;} </style>');
//}

$modules = array_filter($modules);

ksort($modules, SORT_NATURAL);
reset($modules);

/* Завершение пустого модуля */
// <editor-fold defaultstate="collapsed" desc="Завершение пустого модуля">
if($param->get('disable_module_empty_count')):// && empty($modules)
    $mod_keys = array_keys($modules);
    $exit = $module->empty_list;

    if($exit){

		$pos = $module->position;
		$mod_count_pos = JModHelp::ModeuleDelete($module);

		if(empty($mod_count_pos))
			JFactory::getDocument()->addStyleDeclaration("/* Module:$module->id */\n.container-$pos{\ndisplay:none;}\n");
		if(empty($mod_count_pos))
			JFactory::getDocument()->setBuffer(FALSE,['type' => 'modules','name' => $module->position]);
		if(empty($mod_count_pos))
			JFactory::getDocument()->setBuffer(FALSE,['type' => 'module','name' => $module->position]);
		if(empty($mod_count_pos)){
//			toPrint(JFactory::getDocument()::$_buffer ,' ',0, 'message',true);
			unset(JFactory::getDocument()::$_buffer['modules'][$pos]);
			unset(JFactory::getDocument()::$_buffer['module'][$pos]);
		}

		$module = NULL;
		unset($module);
		return FALSE;
    }
endif;
// </editor-fold>

$module	= & ${$mod};
$params	= & ${$par};
$param	= & ${$par};


//JHtml::script($img);// register($img, $function);
//$wa = new \Joomla\CMS\WebAsset\WebAssetManager;
$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerScript('jquery.ui', 'modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.js');
$wa->registerStyle('jquery.ui', 'modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.css');

//JFactory::getApplication()->getDocument()->getWebAssetManager()->usePreset('jquery.ui');
//JFactory::getApplication()->getDocument()->getWebAssetManager()->usePreset('jquery.ui');
//$wa->useAsset($img, $link_title);
//$wa->useStyle('jquery.ui')->useScript('jquery.ui');


require JModuleHelper::getLayoutPath('mod_multi',$param->get('layout', 'default'));

$module	= & ${$mod};
$params	= & ${$par};
$param	= & ${$par};

