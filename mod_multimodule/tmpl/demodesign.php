<?php
/**
 * @package     JoomLike.Site
 * @subpackage  mod_multimodule
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


    $debug_off = isset($_GET['deb']);
//    if($debug_off)return;
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
$modules_showtitle = $params->get('modules_showtitle'); 
$type_module = $params->get('type_module'); 
$description = $params->get('description'); 


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

list($width, $height, $type, $attr) = getimagesize($image);//.demo.id112{height:1610px; }
 
echo <<<view
<div style="position:fixed; top:0;left:0;right:0; bottom:0; z-index:998; overflow-y: scroll;">
    <$module_tag id="id$id" class="multimodule$moduleclass_sfx x id$id" style=" background: url(/$image) center top;
"  >
view;
JFactory::getDocument()->addStyleDeclaration("
.id$id{
    height:$height"."px;
}
body>div{
    overflow-y: hidden;
    height: 0;
    z-index: 999;
    position: absolute;
    top:0;
    left:000px;
    right:000px;
    margin: 0 auto;
}
.demo_description{
    left: 0;
    right: 0;
    border: 10px solid #61c4ca;
    border-radius: 20px;
    padding: 20px;
    position: fixed;
    top: 60px;
    margin: auto;   
    display: block;
    width: 500px;
    z-index: 10000;
    box-shadow: 0 0 30px black;
    background-color: rgba(87, 117, 110, 0.46);
    color: rgb(97, 196, 202);
    text-shadow: 0 0 20px black;
    font-size: 24px;
    line-height: 30px;
}
");
// 
echo "<a href=\"#id$id\" style='position: fixed; bottom:0; left: 0; display: block; width: auto; z-index:1000;'>".$_SERVER['HTTP_HOST']."</a>";
if($description)echo "<div class='demo_description'  style=''>$description</div>";
echo "</$module_tag></div>";
?>
<style>
.A{
}
</style>