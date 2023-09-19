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
use \Joomla\CMS\Version as JVersion;
use \Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Uri\Uri as JUri;
use Joomla\CMS\Factory as JFactory;

JHtml::_('jquery.framework', true, TRUE, true);

if(JVersion::MAJOR_VERSION == 3){
	JHtml::_('jquery.ui');
}
else{

//$wa = new \Joomla\CMS\WebAsset\WebAssetManager;
$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();
//$wa->registerAndUseScript('Instascan', 'https://rawgit.com/schmich/instascan-builds/master/instascan.min.js', [], ['defer' => true]);

$wa->registerScript('jquery','modules/mod_multi/media/jquery/jquery-3.7.0.min.js',
		['version'=>'3.6.0']);

$wa->registerScript('jquery-migrate-old','modules/mod_multi/media/jquery/jquery-migrate-1.4.1.min.js',
		['version'=>'1.4.1','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],['jquery']);

$wa->registerScript('jquery-migrate','modules/mod_multi/media/jquery/jquery.migrate-3.4.1.min.js',
		['version'=>'3.4.0','dependencies' => ['jquery']],['defer' => false, 'nomodule' => false],['jquery','jquery-migrate-old']);

$wa->registerScript('jquery-ui','modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.js',
		['version'=>'1.13.2'],['defer' => false, 'nomodule' => false],['jquery','jquery-migrate']);

$wa->registerStyle('jquery-ui','modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.css',
		['version'=>'1.13.2'],['defer' => false, 'nomodule' => false],['jquery','jquery-migrate']);

//$wa->registerScript('jquery-ui', 'modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.js');
//$wa->registerStyle('jquery-ui', 'modules/mod_multi/media/jquery/jquery-ui-1.13.2/jquery-ui.min.css');
//JFactory::getApplication()->getDocument()->getWebAssetManager()->useStyle('jquery-ui')->useScript('jquery-ui');

//$wa->addInlineScript('document.addEventListener("DOMContentLoaded", function(){form_QR_'.$id.'.t = "'.JFactory::getApplication()->getFormToken().'"});');
}

$param = new \Reg($params);//*** ->toObject()

$base= JUri::base();

$positon = $params->get('position');

$mod_show = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = in_array($param->style, ['',0,NULL,'none','System-none','Cassiopeia-no'])? '': $params->get('moduleclass_sfx');//***

$showtitle = $params->get('showtitle');

$title = ($params->get('title'));

$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));

$isImage = function($url){
    $ext = strtolower(substr($url, strrpos($url, '.') + 1)) ;

    return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])? (' imagelink '.$ext):' url ';
};

$link_show = $params->get('link_show');
$link = $params->get('link');

$modules;
$modules_tag = $params->get('modules_tag');

if($module_tag2 = $params->get('module_tag2'))
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$mod_show id$param->id $param->style\"  >";

if($showtitle):
    $titlea = "";
    if($link_show == 'ha')
        $titlea = "<$header_tag class=\"$header_class\"><a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$param->id multiheadera\">$title</a></$header_tag>";
    elseif($link_show || $link_show == 'ah')
        $titlea = "<a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$param->id multiheadera\"><$header_tag class=\"$header_class\">$title</$header_tag></a>";
    elseif(empty($link_show))
        $titlea =  "<$header_tag class=\"$header_class\">$title</$header_tag>";

    if(in_array($param->style, ['System-none','none','no','0',0,'']))
        echo $titlea;
    else
        $$mod->title = $titlea;
endif;

if($tag = $params->get('modules_tag3')){
    $tgs = explode('/', $tag);
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item  = $tgs[3] ?? FALSE;

}

//$keys = array_keys($modules);

$elements = [];

foreach ($modules as $type => $items){
    $order =  substr($type, 0, 2);
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

$count = count($elements);

echo "<div id='multislideshowid$param->id' class='$moduleclass_sfx slideshowCK camera_wrap $param->skin  cameraCont  cameraContents    slider items   count$count  id$param->id '>";

foreach ($elements as $i => $module):

    if(empty($module->link))
        $module->link = $module->image;

    if(empty($module->title))
        $module->title = $module->image;

$class = $isImage($module->link);
$img_path = trim($module->image,'/');

echo "<div data-thumb='$base$module->image'  data-src='$base$module->image' data-alt='$module->title' class='image $module->moduleclass_sfx cameraContent cameraSlide'   >";

switch ($param->header_tag3){
    case 'item':// тэг модуля
        $module->header_tag = $module->header_tag ? $module->header_tag : 'div';
    case 'default':
        $module->header_tag = ($module->showtitle ?? false) ? ($module->header_tag??'div') : 0;
    case 'none':
        $module->header_tag = 0;
    default :
        $module->header_tag = $param->header_tag3;
}
switch ($param->content_tag3){
    case 'item':
        $module->module_tag = $module->module_tag ? $module->module_tag : 'div';
    case 'default':
        $module->module_tag = in_array($module->style, ['none','no','0',0,'','System-none','Cassiopeia-no','Protostar-no']) ? 'div': ($module->module_tag??'div');
    case 'none':
        $module->module_tag = 0; $module->content = '';
    default :
        $module->module_tag = $param->content_tag3;
}

//toPrint($module->link, '', 0, 'message', true);
//	$img_parts = explode("images/", $module->link);
//	$img = end($img_parts);
//toPrint($img, '', 0, 'message', true);

$mod_title = ($param->items_image && $param->items_image != 'i') ?
        "<a class=\"$class image camera_link\"  target=\"_blank\" href=\"$module->link\"  title=\"$module->title\" ></a>" : $module->title;
echo $mod_title;
//if($module->header_tag && $module->title || $module->module_tag && $module->content){
//    echo "<div class='_camera_caption fadeIn  '>";
//    if($module->header_tag && $module->title) echo "<$module->header_tag class='camera_caption_title '>$mod_title</$module->header_tag>";
//    if($module->module_tag && $module->content) echo "<div class='camera_caption_desc '>$module->content</div>";
//    echo "</div>";
//}
echo " </div>";

endforeach;

echo "</div>";

if($module_tag2)
    echo "</$module_tag2>";

    $mod_path = "modules/mod_multi/media/";

$css = "#camera_wrap_" . $param->id . " .camera_pag_ul li img, #camera_wrap_" . $param->id . " .camera_thumbs_cont ul li > img {height:75px;}";

JFactory::getDocument()->addStyleDeclaration($css);

//JHtml::script('modules/mod_multi/media/slideshowCK/camera.min.js');
//JHtml::stylesheet('modules/mod_multi/media/slideshowCK/administrator/themes/default/css/camera.css');


$wa->registerAndUseScript('slideshowck','modules/mod_multi/media/slideshowCK/camera.min.js',
		['version'=>'auto'],['defer' => false, 'nomodule' => false],['jquery','jquery-migrate','jquery-ui']); // 
$wa->registerAndUseStyle('slideshowck','modules/mod_multi/media/slideshowCK/administrator/themes/default/css/camera.css',
		['version'=>'auto'],['defer' => false, 'nomodule' => false]);

$param->json_layout = $param->json_layout ?? $param->json_slideshowCK ?? '';

$style_layout = in_array(JFactory::getConfig()->get('error_reporting'), [0,NULL,'','none','default'])? '':basename (__FILE__,'.php');
//https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html  #multislideshowid$param->id .slider.items.id$param->id
 $script = <<< script
/* MultiModule ModuleID:$param->id - $style_layout */
jQuery(document).ready( function() {
        new Slideshowck('#multislideshowid$param->id', {
//            \nimagePath: '$base/modules/mod_multi/media/slideshowCK/images/',
            \n$param->json_layout
        });
});

//             imagePath: '$base/images/slideshow/',
script;

  
$wa->addInlineScript($script,
		['version'=>'auto'],['defer' => false, 'nomodule' => false],['jquery','jquery-ui','jquery-migrate']);
//JFactory::getDocument()->addScriptDeclaration($script);

return;
?>

