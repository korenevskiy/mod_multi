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
use Joomla\CMS\Factory as JFactory;
use Joomla\Module\Multi\Site\Helper\MultiHelper as ModMultiHelper;

defined('_JEXEC') or die();

$param = $params; // *** new \Reg($params)->toObject()

\Joomla\CMS\HTML\HtmlHelper::_('bootstrap.tab', '#modTab' . $param->id, []);

$id = $params->get('id');
$positon = $params->get('position');

// echo "<pre> xxx333 ";
// //echo print_r(get_class($params),true);
// echo print_r(gettype($modules),true);
// echo "</pre>";
// return;

$style = $params->get('style');
$mod_show = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$showtitle = $params->get('showtitle');

$title = ($params->get('title'));

$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));

$prepare = function ($item, $param = null, $context = 'com_content.article') {
    return ModMultiHelper::preparePlugin($item, $param, $context);
};

$link_show = $params->get('link_show');
$link = $params->get('link');

if ($tag = $params->get('modules_tag3')) {
    $tgs = explode('/', $tag);
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item = $tgs[3] ?? FALSE;
}

$modules;
$modules_tag = $params->get('modules_tag');

if ($module_tag2 = $params->get('module_tag2'))
    echo "<$module_tag2 class=\"multimodule" . $params->get('moduleclass_sfx2') . " count$mod_show id$id $style\"  >";

if ($showtitle) :
    $titlea = "";
    if ($link_show == 'ha')
        $titlea = "<$header_tag class=\"$header_class\"><a href=\"$link\" title=\"" . strip_tags($title) . "\" class=\"id$id multiheadera\">$title</a></$header_tag>";
    elseif ($link_show || $link_show == 'ah')
        $titlea = "<a href=\"$link\" title=\"" . strip_tags($title) . "\" class=\"id$id multiheadera\"><$header_tag class=\"$header_class\">$title</$header_tag></a>";
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

$elements = [];
$order = 0;
$after_text = '';
$count = count($modules);

foreach ($modules as $type => &$items) {
    $order ++;
    if (is_string($items)) {

        if (1 == $order) {
            echo $items;
            continue;
        }
        if ($count == $order) {
            $after_text .= $items;
            continue;
        }

        $elements[$type . sprintf("%03d", $order)] = (object) [
            'content' => $items,
            'title' => $title,
            'type' => 'description',
            'id' => $i,
            'header_tag' => '',
            'header_class' => '',
            'module_tag' => $params->get('description_tag'),
            'moduleclass_sfx' => '',
            'module' => 'description',
            'showtitle' => FALSE,
            'style' => '',
            'position' => '',
            'link' => '',
            'image' => ''
        ];
        continue;
    }

    foreach ($items as $i => &$module) {
        $elements[$type . sprintf("%03d", $i)] = $module;
    }
}

$count = count($elements);

if (isset($tag_block) && $tag_block)
    echo "<$tag_block  id='exTabs$id'  class=\"items count$count     \">";

$current = '';
$json_tabbootstrap = $params->get('json_tabbootstrap');

// $param->json_layout;

$json = new stdClass();

$json_layout = explode(PHP_EOL, $param->json_layout);

foreach ($json_layout as &$js_lay) {
    $pos = strpos($js_lay, '#');
    if ($pos !== false)
        $js_lay = substr($js_lay, 0, $pos);
    $pos = strpos($js_lay, '//');
    if ($pos !== false)
        $js_lay = substr($js_lay, 0, $pos);

    foreach (explode(',', $js_lay) as $jslay) {
        $jsl = explode(':', $jslay);
        if (count($jsl) == 2) {
            $prop = trim($jsl[0]);
            $prop = trim($prop, '\'"');
            $val = trim($jsl[1]);
            $val = trim($val, '\'"');

            $json->{$prop} = $val;
        }
    }
}

$json_layout = $json;

$tabTag = $json_layout->tabTag ?? 'a';
$tabClass = $json_layout->tabClass ?? 'nav-link';
$listTabTag = $json_layout->listTabTag ?? 'nav';
$listTabClass = $json_layout->listTabClass ?? 'nav nav-justified ';

reset($elements);
$first_key = key($elements);

echo "<$listTabTag  class='$listTabClass panel-tabs' id='modTab$param->id' role='tablist' >";

foreach ($elements as $id => $module) {
    $image_html = '';
    $current = $first_key == $id ? "active" : "noactive";
    $selected = $first_key == $id ? "true" : "false";
    echo $listTabTag == 'ul' ? "<li class='nav-item'>" : '';
    echo "<$tabTag class='$tabClass $current' href='#nav-$param->id-$id'	 data-bs-target='#nav-$param->id-$id'	aria-current='page'
			id='nav-$param->id-$id-tab' data-bs-toggle='tab' data-toggle='tab' type='button' role='tab' 
			aria-controls='nav-$param->id-$id' aria-selected='$selected'>$module->title  $image_html</$tabTag>";
    echo $listTabTag == 'ul' ? '</li>' : '';
}
echo "</$listTabTag>";

$i = 0;

echo "<div class='tab-content' id='nav-tabContent-$param->id'>";
foreach ($elements as $id => $module) {
    $module->text = $module->content = $prepare($module->content ?? '');

    $i ++;
    $current = $first_key == $id ? "in show active " : "noactive";
    echo "<div id='nav-$param->id-$id' class='tab-pane fade $current' aria-labelledby='nav-$param->id-$id-tab item i$i type_$module->type sfx$module->moduleclass_sfx id$id $module->module  ' role='tabpanel'>";

    $title_tag = $param->title_tag;
    if ($title_tag == 'default') {
        $title_tag = isset($module->showtitle) && $module->showtitle ? ($module->header_tag ?: 'span') : '';
    }
    if ($title_tag == 'item') {
        $title_tag = $module->header_tag ?? 'div';
    }
    if ($title_tag) {
        echo "<$title_tag class=\"$module->header_class\">";
        echo $module->title;
        echo "</$title_tag>";
    }

    $item_tag = $params->get('item_tag', '');
    if ($item_tag == 'default') {
        $item_tag = $module->style ? ($module->module_tag ?: 'div') : '';
    }
    if ($item_tag == 'item') {
        $item_tag = $module->module_tag ?? '';
    }

    if ($item_tag)
        echo "<$item_tag class=\" $module->moduleclass_sfx\">";
    echo $prepare($module->content ?? '');
    if ($item_tag)
        echo "</$item_tag>";

    echo "</div>";
}
echo "</div>";

if (isset($tag_block) && $tag_block)
    echo "</$tag_block>";

echo $after_text;

if ($module_tag2 = $params->get('module_tag2'))
    echo "</$module_tag2>";

?>
