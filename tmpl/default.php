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


//$wa = new \Joomla\CMS\WebAsset\WebAssetManager;
//$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();
//$wa->registerAndUseScript('Instascan', 'https://rawgit.com/schmich/instascan-builds/master/instascan.min.js', [], ['defer' => true]);
//$wa->registerAndUseStyle('slideshowck','modules/mod_multi/media/slideshowCK/administrator/themes/default/css/camera.css');

//$wa->registerScript('jquery','modules/mod_multi/media/jquery/jquery-3.7.0.min.js',
//		['version'=>'3.6.0','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],[]);
//$wa->registerScript('jquery-migrate-old','modules/mod_multi/media/jquery/jquery-migrate-1.4.1.min.js',
//		['version'=>'1.4.1','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],['jquery']);
//$wa->registerScript('jquery-migrate','modules/mod_multi/media/jquery/jquery.migrate-3.4.1.min.js',
//		['version'=>'3.4.0','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],['jquery']);
//$wa->registerScript('jquery-ui','modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.js',
//		['version'=>'1.13.2','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],['jquery']);
//$wa->registerStyle('jquery-ui','modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.css',
//		['version'=>'1.13.2','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],['jquery']);

//JFactory::getApplication()->getDocument()->getWebAssetManager()->useStyle('jquery-ui')->useScript('jquery-ui');
//$wa->registerScript('jquery-ui', 'modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.js');
//$wa->registerStyle('jquery-ui', 'modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.css');
//$wa->addInlineScript('document.addEventListener("DOMContentLoaded", function(){form_QR_'.$id.'.t = "'.JFactory::getApplication()->getFormToken().'"});');

$param = new \Reg($params);//*** ->toObject()
$param->id = $module->id;
$param->ajax = $module->ajax ?? false;
$ajax = $param->ajax ? 'ajax' : '';


$base = JUri::base();

$id      = $param->id;
$positon = $param->position;

$mod_show = count($modules);

$module_tag 		= $param->module_tag ?? 'div';
$moduleclass_sfx 	= $param->moduleclass_sfx;

//$showtitle = $params->get('showtitle');
$showtitle = $module->showtitle;

$title = $param->title ?? '';

$header_tag = $param->header_tag ?? 'h3';
$header_class = htmlspecialchars($param->header_class ?? 'module-header');

$prepare = function ( $item, $param = null, $context = 'com_content.article'){
    return ModMultiHelper::preparePlugin($item, $param, $context);
};

$param->items_link;
$param->items_image;
$param->items_tag;

$tag_item = '';
$tag_block = $param->items_tag ?? '';

if($tag = $param->item_tag3){
    $tgs = explode('/', $tag);
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE; 
    $tag_item  = $tgs[3] ?? FALSE;
}

$link_show = $param->link_show;
$link = $param->link;
$modules;
$item_tag = $param->item_tag;

/* Подготовка для объединения несколько массивов подрят в один список.*/
$ii = 0;
$ElementTypes = [];
foreach ($modules as $items){
	$ElementTypes[] = is_array($items) ? count($items) : 0;
}

/* Получение количества всех элементов из всех списков */
$count_items = array_sum($ElementTypes); 


if($module_tag2 = $param->module_tag2)
    echo "<$module_tag2 class='multimodule$param->moduleclass_sfx2 count$count_items id$id $param->style $ajax'>";
else
    $param->moduleclass_sfx = $param->moduleclass_sfx." count$count_items $ajax";


//if($param->id == 112)
//	toPrint($param->style, '$param->style', 0, 'message', true);
//toPrint(null, '', 0, '', false);
//if($param->id == 112)
//toPrint($showtitle, '$showtitle', 0, 'message', true);
//if($param->id == 116)
//	toPrint($modules, '$modules', 0, 'message', true);


if($showtitle):
    $titlea = "";
    if($link_show == 'ha')
        $titlea = "<$header_tag class='$header_class '><a href='$link' title='".strip_tags($title)."' class='id$id multiheadera'>$title</a></$header_tag>";
    elseif($link_show == 'ah')
        $titlea = "<a  href='$link' title='".strip_tags($title)."' class='id$id multiheadera'><$header_tag class='$header_class'>$title</$header_tag></a>";
    elseif($link_show == 'h')
        $titlea = "<$header_tag class='$header_class id$id multiheadera' title='".strip_tags($title)."' >$title</$header_tag>";
    elseif($link_show == 'a')
        $titlea = "<a href='$link'  title='".strip_tags($title)."' class='id$id multiheadera $header_class'>$title</a>";
    else
        $titlea =  "<$header_tag class='$header_class'>$title</$header_tag>";


    if(in_array($param->style, ['System-none','none','no','0',0,''])) //System-html5
        echo $titlea;
    elseif(in_array($link_show, ['ha','ah','a'])){
        $$mod->title = "<a href='$link' title='".strip_tags($title)."' class='id$id multiheadera'>$title</a>";
		$$mod->header_class .= "id$id multiheadera";
	}
    elseif(in_array($link_show, ['h'])){
        $$mod->title = $title;
		$$mod->header_class .= "id$id multiheadera";
	}
	else
        $$mod->title = $titlea;
endif;


//toPrint(null,'',0, 'pre',true);

foreach ($modules as $type => $items):
    if(is_string($items)){
        echo $items;
        unset($modules[$type]);
		$ii ++;
        continue;
    }
    $order = substr($type, 0, 2);
    $type = substr($type, 2);

    $count = count($items);
    $i = -1;
	
	
	
/* Проверка наличие тега для списка и проверка предыдущего списка что он несписок, чтобы подряд идущие списки групировались внутри тега */
if($param->items_tag && ($ii == 0 || $ElementTypes[$ii-1] == 0))
    echo "<$param->items_tag class='items count$count order$order $type  '>";


//    if($param->items_tag)
//        echo "<$param->items_tag class='items_tag i$i $type moduletable$module->moduleclass_sfx $module->type'>";

$ii ++;



foreach ($items as $id => & $module){
    $module->text = $module->content = $prepare($module->content ?? '');

    $i++;

	
	
	if($param->item_tag)
		echo "<$param->item_tag class='item $type moduletable$module->moduleclass_sfx  id$module->id $module->type '>";
	

if(empty($param->style_tag3) || $param->style_tag3 == '0'):
    echo $module->content;
else:


//echo "<pre>".print_r($module,true)."</pre>";
	
    $items_tag = $param->items_tag;

    if($module->module_tag == 'default'){
        $module->module_tag = 'div';
    }

    if($param->items_tag == 'default' && $module->module_tag && $module->style){
        echo "<$module->module_tag class='$module->moduleclass_sfx ' >";
        $items_tag = '';
    }
    elseif($param->items_tag == 'item' && $module->module_tag){
        echo "<$module->module_tag class='$module->moduleclass_sfx ' >";
        $items_tag = '';
    }

    $link = $link_ = "";

    $module_title = $module->title;

    $isImage = function($url){
        $ext = strtolower(substr($url, strrpos($url, '.') + 1)) ;

        return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])?' imagelink '.$ext : ' url ';
    };
    $class = $isImage($module->link);
	
	$link_class = $module->link_class ?? '';

	$title_tag = $param->title_tag ?? 'h6';
	
//if($type == 'articles')
//toPrint($title_tag,'$title_tag', 0, 'pre',true);

	if($param->title_tag == 'default'){
    	$title_tag = '';
	}
	if($param->title_tag == 'default' && ($module->showtitle ?? FALSE)){
    	$title_tag = $module->header_tag ?? 'div';
	}
	if($param->title_tag == 'item'){
    	$title_tag = $module->header_tag ?? '';
	}
	if($param->title_tag == '0'){
    	$module_title = $title_tag = '';
	}

	
//if($type == 'articles')
//	toPrint($type,'$type', 0, 'pre',true);
//if($type == 'articles')
//	toPrint($title_tag,'$title_tag', 0, 'pre',true);
//if($type == 'articles')
//	toPrint( $module->title,'$module->title', 0, 'pre',true);
//toPrint($title_tag,'$title_tag', 0, 'pre',true);
	
	if($title_tag && $module->title){
		$module_title = $module->title;//'';// $module->title;

		if($param->items_link == 'ha'){
			$module_title = "<$title_tag class=' $param->item_class_sfx$param->id item_title $module->header_class'><a href='$module->link' class='$class $link_class' _title='$module->title' >$module->title</a></$title_tag>";
		}
		if($param->items_link == 'ah'){
			$module_title = "<a href='$module->link' class=' $param->item_class_sfx$param->id $class $link_class item_title' _title='$module->title' ><$title_tag class='  $module->header_class'>$module->title</$title_tag></a>";
		}
		if($param->items_link == 'h'){
			$module_title = "<$title_tag class=' $param->item_class_sfx$param->id item_title $class $module->header_class'>$module->title</$title_tag>";
		}
		if($param->items_link == 'a'){
			$module_title = "<a href='$module->link' class=' $param->item_class_sfx$param->id $class $link_class item_title $module->header_class' _title='$module->title' >$module->title</a>";
		}
		if($param->items_link == '0'){
			$module_title = '';
//			$module_title = "<$title_tag class=' $param->item_class_sfx$param->id $class $link_class item_title $module->header_class' _title='$module->title' >$module->title</$title_tag>";
		}
		echo $prepare($module_title);
	}

    if($param->articles_fields && $module->fields??FALSE){
        echo $module->fields;
    }

    $isImage = function($url){
        $ext = pathinfo($url, PATHINFO_EXTENSION);//strtolower(substr($url, strrpos($url, '.') + 1)) ;

        return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])?' imagelink '.$ext:' url ';
    };
    $class = $isImage($module->link);

    if($param->items_image && $module->image):
    if($param->items_image == 'ai')
        echo $prepare("<a class='$class image'  target='_blank' href='$module->link'  _title='$module->title'
            data-toggle='lightbox'   data-gallery='gallery_$id' data-type='image' onclick='return true;'>
            <img class=' item_image $module->moduleclass_sfx '   src='$base$module->image'></a>");
    if($param->items_image == 'ii')
        echo $prepare("<a class='$class image'  target='_blank' href='$base$module->image'  _title='$module->title'
            data-toggle='lightbox'   data-gallery='gallery_$id' data-type='image' onclick='return true;'>
            <img class=' item_image $module->moduleclass_sfx '  src='$base$module->image'></a>");
    if($param->items_image == 'di') {
        echo $prepare("<div class='$class image'><img  class='  item_image  $module->moduleclass_sfx '  data-action='zoom' src='$base$module->image'></div>");
    }
    if($param->items_image == 'i')
        echo $prepare("<img class='$class image item_image $module->moduleclass_sfx'    src='$base$module->image'>");
    endif;

//    if($param->items_tag != 'none'){
//    if($items_tag)
//        echo "<$items_tag class=' item_content  $module->moduleclass_sfx'>";
	
	
	echo $module->content ?? '';
	

	if(isset($module->items) && is_array($module->items) && $module->items){
		$item = $module;
		require \Joomla\CMS\Helper\ModuleHelper::getLayoutPath('mod_multi', '_items');
	}

//    if($items_tag)
//        echo "</$items_tag>";
//    }
	
	if($param->articles_more && $type == 'articles'){
		echo "<a href='$module->link' class='link_more item_more' aria-label='$module->title'>$param->articles_more</a>";
	}
	if($param->article_more && $type  == 'article'){
		echo "<a href='$module->link' class='link_more item_more' aria-label='$module->title'>$param->article_more</a>";
	}

    if($param->items_tag == 'default' && $module->module_tag && $module->style){
        echo "</$module->module_tag>";
    }
    if($param->items_tag == 'item' && $module->module_tag){
        echo "</$module->module_tag>";
    }

    endif;

	
	if($param->item_tag)
		echo "</$param->item_tag>";

}

	
//    if($param->items_tag)
//        echo "</$param->items_tag>";

/* Проверка наличие тега для списка и проверка будущего списка что он несписок, чтобы подряд идущие списки групировались внутри тега */
if(isset($param->items_tag) && $param->items_tag && empty($ElementTypes[$ii]))
	echo "</$param->items_tag>";


endforeach; // foreach $modules as $type => $items

if($module_tag2)
    echo "</$module_tag2>";

?>
