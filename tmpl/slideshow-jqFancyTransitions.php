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
defined('_JEXEC') or die();

JHtml::_('jquery.framework', true, TRUE, true);
JFactory::getApplication()->getDocument()
    ->getWebAssetManager()
    ->useStyle('jquery.ui')
    ->useScript('jquery.ui');

$param = $params; // *** new \Reg($params)->toObject()

$positon = $params->get('position');

$style = $params->get('style'); // ***
$mod_show = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = in_array($style, [
    '',
    0,
    NULL,
    'none',
    'System-none',
    'Cassiopeia-no'
], true) ? '' : $params->get('moduleclass_sfx'); // ***

$showtitle = $params->get('showtitle');

$title = ($params->get('title'));

$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));

$link_show = $params->get('link_show');
$link = $params->get('link');

$modules;
$modules_tag = $params->get('modules_tag');

if ($module_tag2 = $params->get('module_tag2'))
    echo "<$module_tag2 class=\"multimodule" . $params->get('moduleclass_sfx2') . " count$mod_show id$param->id $style\"  >";

if ($showtitle) :
    $titlea = "";
    if ($link_show == 'ha')
        $titlea = "<$header_tag class=\"$header_class\"><a href=\"$link\" title=\"" . strip_tags($title) . "\" class=\"id$param->id multiheadera\">$title</a></$header_tag>";
    elseif ($link_show || $link_show == 'ah')
        $titlea = "<a href=\"$link\" title=\"" . strip_tags($title) . "\" class=\"id$param->id multiheadera\"><$header_tag class=\"$header_class\">$title</$header_tag></a>";
    elseif (empty($link_show))
        $titlea = "<$header_tag class=\"$header_class\">$title</$header_tag>";

    if (in_array($style, [
        'System-none',
        'none',
        'no',
        '0',
        0,
        ''
    ], true))
        echo $titlea;
    else
        ${$mod}->title = $titlea;
endif;

if ($tag = $params->get('modules_tag3')) {
    $tgs = explode('/', $tag);
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item = $tgs[3] ?? FALSE;
}

$keys = array_keys($modules);

$elements = [];

foreach ($modules as $type => $items) {
    $order = substr($type, 0, 2);
    $type = substr($type, 2);
    if (is_string($items)) {
        echo $items;
        continue;
    }
    $count = count($items);
    foreach ($items as $id => $module) {
        $module->moduleclass_sfx = $module->moduleclass_sfx ?? '';
        $module->moduleclass_sfx .= "  countype$count order$order $type  ";
        $elements[] = $module;
    }
}

$keys = array_keys($elements);

$count = count($elements);

echo "<div id='multislideshowid$param->id' class=\"slider items   count$count $moduleclass_sfx id$param->id \">";

foreach ($elements as $i => $module) {

    if (empty($module->link))
        $module->link = $module->image;

    if (empty($module->title))
        $module->title = $module->image;

    $isImage = function ($url) {
        $ext = strtolower(substr($url, strrpos($url, '.') + 1));

        return in_array($ext, [
            'png',
            'apng',
            'svg',
            'bmp',
            'jpg',
            'jpeg',
            'gif',
            'webp',
            'ico'
        ]) ? (' imagelink ' . $ext) : ' url ';
    };
    $class = $isImage($module->link);
    $img_path = trim($module->image, '/');

    echo "<img src=\"$module->image\" alt=\" $module->title\" class=\"image $module->moduleclass_sfx\"   >";

    if ($params->get('items_image') && $params->get('items_image') != 'i')
        echo "<a class=\"$class image\"  target=\"_blank\" href=\"$module->link\"  title=\"$module->title\" ></a>";
}

echo "</div>";

if ($module_tag2)
    echo "</$module_tag2>";

JHtml::script('modules/mod_multi/media/jqfancytransitions/slideshow.js');

$json_jqFancyTransitions = $params->get('json_layout', '') ?: $params->get('json_jqFancyTransitions', '');

$style_layout = in_array(JFactory::getConfig()->get('error_reporting'), [
    0,
    NULL,
    '',
    'none',
    'default'
], true) ? '' : basename(__FILE__, '.php');
// https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html #multislideshowid$param->id .slider.items.id$param->id
$script = <<< script

/* MultiModule ModuleID:$param->id - $style_layout */
jQuery( function() {
        jQuery("#multislideshowid$param->id").jqFancyTransitions({
            {$json_jqFancyTransitions}
        });
});

script;

JFactory::getDocument()->addScriptDeclaration($script);

return;
?>

