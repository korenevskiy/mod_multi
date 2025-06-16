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


use Joomla\Module\Multi\Site\Helper\MultiHelper as ModMultiHelper;
use Joomla\CMS\Factory as JFactory;

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JFactory::getApplication()->getDocument()->getWebAssetManager()->useStyle('jquery.ui')->useScript('jquery.ui');



$param = $params;//*** new \Reg($params)->toObject()

$id				= $param->id;
$positon        = $params->get('position');

$param->item_tag	= $param->item_tag ?: 'div';
$param->items_tag	= $param->items_tag ?: 'div';

$count_items = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$showtitle = $params->get('showtitle');

$title = $param->title;//($params->get('title'));

$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));

$link_show = $params->get('link_show');
$link = $params->get('link');

$modules;
$modules_tag = $params->get('modules_tag');

$prepare = function ( $item, $param = null, $context = 'com_content.article'){
    return ModMultiHelper::preparePlugin($item, $param, $context);
};

if($module_tag2 = $param->module_tag2)
    echo "<$module_tag2 class='multimodule $param->moduleclass_sfx2 count$count_items id$id $param->style  owl-theme'  >";
else
    $param->moduleclass_sfx = "multimodule $param->moduleclass_sfx count$count_items id$id owl-theme"; //${"module" . $param->id}->moduleclass_sfx = 

//echo "<pre>$showtitle $link_show/$header_tag $param->styless</pre>";
if($showtitle):
    $titlea = "";
    if($link_show == 'ha')
        $titlea = "<$header_tag class='$header_class '><a href='$link' title='".strip_tags($param->title)."' class='id$id multiheadera'>$param->title</a></$header_tag>";
    elseif($link_show == 'ah')
        $titlea = "<a  href='$link' title='".strip_tags($param->title)."' class='id$id multiheadera'><$header_tag class='$header_class'>$param->title</$header_tag></a>";
    elseif($link_show == 'h')
        $titlea = "<$header_tag class='$header_class id$id multiheadera' title='".strip_tags($param->title)."' >$param->title</$header_tag>";
    elseif($link_show == 'a')
        $titlea = "<a href='$link'  title='".strip_tags($param->title)."' class='id$id multiheadera $header_class'>$param->title</a>";
    else
        $titlea =  "<$header_tag class='$header_class'>$param->title</$header_tag>";

	
	


    if(in_array($param->style, ['System-none','none','no','0',0,''],true)) //System-html5
        echo $titlea;
    elseif(in_array($link_show, ['ha','ah','a'])){
        ${$mod}->title = "<a href='$link' title='".strip_tags($param->title??'')."' class='id$id multiheadera'>".($param->title??'')."</a>";
		${$mod}->header_class .= "id$id multiheadera";
	}
    elseif(in_array($link_show, ['h'])){
        ${$mod}->title = $param->title;
		${$mod}->header_class .= "id$id multiheadera";
	}
	else
        ${$mod}->title = $titlea;
		
endif;

if($tag = $params->get('modules_tag3')){
    $tgs = explode('/', $tag);
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item  = $tgs[3] ?? FALSE;

}

$keys = array_keys($modules);

//$elements = [];
$ii = 0;
$countModulesTypes = [];
foreach ($modules as $items){
	$countModulesTypes[] = is_array($items) ? count($items) : 0;
}

$count_items = array_sum($countModulesTypes); 

$countLast = 0;

$isConatinString = in_array(0, $countModulesTypes);

//echo "< $isConatinString >";
//echo "<pre>$module->id $module->title ". print_r($param,true).'</pre>';

foreach ($modules as $type => $items):
	
if(is_string($items)){
	echo $prepare($items) ;
	unset($modules[$type]);
	$countLast = 0;
	$ii ++;
	continue;
}
	
$order =  substr($type, 0, 2);
$type = substr($type, 2);

$keys = array_keys($items);
$count = count($items);



if($ii == 0 || $countModulesTypes[$ii-1] == 0)
//if($ii == 0 || $countModulesTypes[$ii-1] == 0)
//if($isConatinString)
	echo "\n<$param->items_tag class='slider items owl-carousel  count$count_items $moduleclass_sfx '  itemscope itemtype='http://schema.org/ImageGallery'>";

$ii ++;
$i = -1;

foreach ($items as $id => & $module){
	
	
    $module->moduleclass_sfx = $module->moduleclass_sfx??'';
    $module->moduleclass_sfx .= "  countype$count order$order $type  ";
    $i++;
	
    $module->text =  $module->content = $prepare($module->content);
	
	$href = $param->item_tag == 'a' ? " href='$module->link'" : '';
	
	echo "\n<$param->item_tag class='item i$i $type sfx$module->moduleclass_sfx id$module->id  $module->type'$href>";

    if(empty($module->link))
        $module->link = $module->image;

    if(empty($module->title))
        $module->title = pathinfo($module->image, PATHINFO_FILENAME);

$isImage = function($url){
		$ext = strtolower(substr($url, strrpos($url, '.') + 1)) ;
		return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])?' imagelink '.$ext:' url ';
	};
	
$class = $isImage($module->link);

if($params->get('items_image') && $module->image):
    if($params->get('items_image') == 'ai')
        echo $prepare("<a class='$class image'  target='_blank' href='$module->link'  title='$module->title'
            data-toggle='lightbox'   data-gallery='gallery_$id' data-type='image' onclick='return true;'>
            <img class=' item_image $module->moduleclass_sfx' width='300'  src='$module->image'></a>");
    if($params->get('items_image') == 'ii')
        echo $prepare("<a class='$class image'  target='_blank' href='$module->image'  title='$module->title'
            data-toggle='lightbox'   data-gallery='gallery_$id' data-type='image' onclick='return true;'>
            <img class=' item_image $module->moduleclass_sfx' width='300'  src='$module->image'></a>");
    if($params->get('items_image') == 'di')
        echo $prepare("<div class='$class image'><img width='300' class='   item_image  $module->moduleclass_sfx'  data-action='zoom'  src='$module->image'></div>");
    if($params->get('items_image') == 'i')
        echo $prepare("<img class='$class image item_image $module->moduleclass_sfx' width='300'  src='$module->image'>");
endif;

$title_tag	= $param->title_tag ?: 'div';//header_tag3
$items_link	= $param->items_link;
if($title_tag == 'default'){
    $title_tag = 'div';
}
if($title_tag == 'item'){
    if($module->header_tag && $module->showtitle)
        $title_tag = $module->header_tag;
    else
        $items_link = FALSE;
}
//toPrint($items_link,'$items_link',0,'message',true);
if($items_link && $module->title):
    if($items_link == 'ah')
        echo "<a class='$class' href='$module->link' _title='$module->title'><$title_tag class='title'>$module->title</$title_tag></a>";
    elseif($items_link == 'ha')
        echo "<$title_tag class='title'><a class='$class' href='$module->link' title='$module->title'>$module->title</a></$title_tag>";
    elseif($items_link == 'a')
        echo "<a class='$class title' href='$module->link' _title='$module->title'>$module->title</a>";
    elseif($title_tag)
        echo "<$title_tag class='title $class'>$module->title</$title_tag>";
    else
        echo "$module->title";
endif;

$item_tag = $params->get('item_tag');
if($item_tag == 'default')
    $item_tag = $module->module_tag;
if(($module->content ?? '') && $item_tag != 'none' && $item_tag) {
    echo ("<$item_tag class='info'>$module->content</$item_tag>");
}
if($module->content && empty($item_tag))
	echo ($module->content ?? '');

    if(isset($module->urls)	&& $module->urls->urla){
        $class = $isImage($module->urls->urla);
        $class .= $module->urls->targeta===2 ? 'lightbox' : ($module->urls->targeta===3 ? 'modal' : '') ;
        $target = $module->urls->targeta===0 ? '_parent' : ($module->urls->targeta==1 ? '_blank' : ($module->urls->targeta==2 ? '_self' : ''));
        echo $prepare("<a class='linka urls $class'  target='$target' href='{$module->urls->urla}'  _title='{$module->urls->urlatext}' >{$module->urls->urlatext}</a>");
    }
    if(isset($module->urls)	&& $module->urls && $module->urls->urlb){
        $class = $isImage($module->urls->urlb);
        $class .= $module->urls->targetb===2 ? 'lightbox' : ($module->urls->targetb===3 ? 'modal' : '');
        $target = $module->urls->targetb===0 ? '_parent' : ($module->urls->targetb===1 ? '_blank' : ($module->urls->targetb===2 ? '_self' : ''));
        echo $prepare("<a class='linkb urls $class'  target='$target' href='{$module->urls->urlb}'  _title='{$module->urls->urlbtext}' >{$module->urls->urlbtext}</a>");
    }
    if(isset($module->urls)	&& $module->urls && $module->urls->urlc){
        $class = $isImage($module->urls->urlc);
        $class .= $module->urls->targetc===2 ? 'lightbox' : ($module->urls->targetc===3 ? 'modal' : '');
        $target = $module->urls->targetc===0 ? '_parent' : ($module->urls->targetc===1 ? '_blank' : ($module->urls->targetc===2 ? '_self' : ''));
        echo $prepare("<a class='linkc urls $class'  target='$target' href='{$module->urls->urlc}'  _title='{$module->urls->urlctext}' >{$module->urls->urlctext}</a>");
    }

    if(isset($module->fields) && $module->fields){
        echo $module->fields;
    }
	
	
	if($param->get('articles_more') && $type == 'articles'){
		echo "<a href='$module->link' class='link_more item_more' aria-label='$module->title'>$param->articles_more</a>";
	}
	if($param->get('article_more') && $type  == 'article'){
		echo "<a href='$module->link' class='link_more item_more' aria-label='$module->title'>$param->article_more</a>";
	}

    echo "</$param->item_tag>";
}


//if(isset($param->items_tag) && $param->items_tag && empty($countModulesTypes[$ii]))
//if(empty($countModulesTypes[$ii]))
//if($isConatinString)
if(empty($countModulesTypes[$ii]))
	echo "\n</$param->items_tag>";

$countLast = $count;

endforeach;



if($module_tag2)
    echo "</$module_tag2>";

$mod_path = "modules/mod_multi/media/";
	
$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();

//    JHtml::script($mod_path . 'OwlCarousel/owl.carousel.js');
//    JHtml::stylesheet($mod_path . 'OwlCarousel/owl.carousel.css');
//    JHtml::stylesheet($mod_path . 'OwlCarousel/owl.theme.default.css');
//    JHtml::stylesheet($mod_path . 'OwlCarousel/style.css');
	

$wa->registerAndUseStyle('owl.carousel.css',$mod_path . 'OwlCarousel/owl.carousel.css');
$wa->registerAndUseStyle('owl.theme.default.css',$mod_path . 'OwlCarousel/owl.theme.default.css');
$wa->registerAndUseStyle('owl.style.css',$mod_path . 'OwlCarousel/style.css');
$wa->registerAndUseScript('owl.carousel.js', $mod_path . 'OwlCarousel/owl.carousel.js');

$wa->useScript('jquery-noconflict');


//    JHtml::stylesheet($mod_path . 'OwlCarousel/enhance.js_zoom.css');
//    JHtml::script( $mod_path . 'OwlCarousel/velocity.min.js');
//    JHtml::script( $mod_path . 'OwlCarousel/enhance.js');


//$wa->registerAndUseStyle('enhance.zoom.css', $mod_path . 'OwlCarousel/enhance.js_zoom.css', [], ['defer' => true],['owl.carousel.css']);
$wa->registerAndUseScript('velocity.js', $mod_path . 'OwlCarousel/velocity.min.js', [], ['defer' => true],['owl.carousel.js']);
//$wa->registerAndUseScript('enhance.js',$mod_path . 'OwlCarousel/enhance.js',[], ['defer' => true],['owl.carousel.js']);

	

$id						= $params->get('id');
$json_owlCarousel		= $params->get('json_layout','') ?: $params->get('json_owlCarousel','');
//return; 

/* https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html */
 $script = <<< script
/* MultiModule:$id OwlSlider */
jQuery( function() {
        console.log('jQuery:',jQuery.fn.jquery,' -OwlSlider -MultiModule:',{$id});
        jQuery(".slider.items.owl-carousel.id{$id}").owlCarousel({
            {$json_owlCarousel}
        });
});

script;

JFactory::getDocument()->addScriptDeclaration($script);

return;
?>

