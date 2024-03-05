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


if($_GET['deb'] == 'deb' || $_GET['debug'])
	return;


defined('_JEXEC') or die;

$param = new \Reg($params);//*** ->toObject()

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

list($width, $height, $type, $attr) = getimagesize($image);

echo <<<view
<div style="position:fixed; top:0;left:0;right:0; bottom:0; z-index:998; overflow-y: scroll; height: 100%;">
    <$module_tag id="id$id" class="multimodule$moduleclass_sfx x id$id" style=" background: url(/$image) center top  no-repeat;
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
    background-color: rgba(0, 0, 0, 0.5);
    color: rgb(97, 196, 202);
    text-shadow: 0 0 20px black;
    font-size: 24px;
    line-height: 30px;
}
");

echo "<a href=\"#id$id\" style='position: fixed; bottom:0; left: 0; display: block; width: auto; z-index:1000;'>".$_SERVER['HTTP_HOST']."</a>";
if($description)echo "<div class='demo_description'  style=''>$description</div>";
echo "</$module_tag></div>";
?>
<style>
.A{
}
</style>
