<?php

namespace Joomla\Module\Multi\Site\Helper;

/*
 * ------------------------------------------------------------------------
 * # mod_multi - Modules Conatinier
 * # ------------------------------------------------------------------------
 * # author Sergei Borisovich Korenevskiy
 * # Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
 * # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * # Websites: //explorer-office.ru/download/joomla/category/view/1
 * # Technical Support: Forum - //fb.com/groups/multimodule
 * # Technical Support: Forum - //vk.com/multimodule
 * -------------------------------------------------------------------------
 */
use JLoader;
use ContentHelperRoute;
use Joomla\CMS;
use Joomla\CMS\Date\Date as JDate;
use Joomla\CMS\HTML;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route as JRoute;
use Joomla\CMS\Uri\Uri as JUri;
use Joomla\Registry\Registry as JRegistry;
use Joomla\CMS\Factory as JFactory;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Filesystem\Folder as JFolder;
// use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\Module\Multi\Site\Helper\JModHelp as JModuleHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die();
// phpcs:enable PSR1.Files.SideEffects

jimport('joomla.application.module.helper');

// MultiHelper::getAjax
class MultiHelper implements DatabaseAwareInterface // abstract class ModMultiHelper
{
    use DatabaseAwareTrait;

    public static $params;

    public function __construct() {
        defined('MULTIMOD_PATH') || define('MULTIMOD_PATH', realpath(__DIR__ . '/../../'));
		
        if (empty(class_exists('\Reg')))
            @include_once MULTIMOD_PATH . "/libraries/reg.php";

        if (! function_exists('toPrint') && file_exists(MULTIMOD_PATH . '/libraries/functions.php'))
            require_once MULTIMOD_PATH . '/libraries/functions.php';

        if (! function_exists('toPrint') && file_exists(JPATH_ROOT . '/functions.php'))
            require_once JPATH_ROOT . '/functions.php';
	
        JLoader::register('Joomla\Module\Multi\Site\Helper\JModHelp', MULTIMOD_PATH . '/libraries/ModuleHelper.php');

        // require_once MULTIMOD_PATH . '/libraries/ModuleHelper.php';
    }

    public static function moduleLayoutData($param, $module, $app)
    {
        $params = &$param;
        $module->params = &$params;
        $param->id = $module->id;
	
        // if($module->id == 311){
        // echo "<pre> ModID:";
        // echo print_r($module->id,true);
        // echo "$param->item_style </pre>";
        // }

        $module->ajax = $module->ajax ?? false;

        // $app = JFactory::getApplication();
        $modules = array();

        // <editor-fold defaultstate="collapsed" desc="field HTML">
        if ($param->get('html_show')) {

            $copyright_text = '';

            if ($param->copyright_show) {
                $p = $app->getParams();

                $copyright_text = $param->copyright_text;
                $copyright_text = str_replace([
                    '%sitename',
                    '%SITENAME',
                    '%site_name',
                    '%SITE_NAME'
                ], JFactory::getConfig()->get('sitename'), $copyright_text);

                $copyright_text = str_replace([
                    '%page_title',
                    '%pagetitle',
                    '%PAGE_TITLE',
                    '%PAGE_TITLE'
                ], $p->get('page_title', ''), $copyright_text);
                $copyright_text = str_replace([
                    '%page_heading',
                    '%pageheading',
                    '%PAGE_HEADING',
                    '%PAGEHEADING'
                ], $p->get('page_heading', ''), $copyright_text);

                $copyright_text = str_replace([
                    '%title',
                    '%TITLE'
                ], JFactory::getDocument()->title, $copyright_text);
                $copyright_text = str_replace([
                    '%d',
                    '%D'
                ], JDate::getInstance()->day, $copyright_text);
                $copyright_text = str_replace([
                    '%y',
                    '%Y'
                ], JDate::getInstance()->year, $copyright_text);
                $copyright_text = str_replace([
                    '%m',
                    '%M'
                ], JDate::getInstance()->month, $copyright_text);
                $copyright_text = "<div class='copyright'>$copyright_text</div>";
            }

            $fontsFiles = $param->fontsFiles ?? [];

            $fontsGoogle = $param->fontsGoogle ?? [];

            /**
             * Показ иконок в Head
             */
            if ($param->favicon_show && JFactory::getDocument()->getType() == 'html') {

                JFactory::getDocument()->addCustomTag("<meta name='msapplication-starturl' content='./'>");
                $param->favicon_title;
                $param->favicon_tooltip;

                $favicon_files_ico = $param->favicon_files_ico ?? [];

                foreach ((array) $favicon_files_ico as $key => $value) {
                    // JDocument::getInstance()->_links[] = trim($value, "");//JUri::base().
                    JFactory::getDocument()->addFavicon('' . $value); // ??????
                                                                      // JFactory::getDocument()->addCustomTag("<link rel='icon' type='image/vnd.microsoft.icon' href='$value'>");
                }
                $favicon_files = $param->favicon_files;
                $icons_any_size = [
                    16,
                    32,
                    48,
                    64,
                    96
                ];
                $icons_ios_size = [
                    57,
                    60,
                    72,
                    76,
                    114,
                    120,
                    144,
                    152,
                    180
                ];
                $icons_win_size = [
                    70,
                    150,
                    310
                ];
                $icons_andr_size = [
                    192
                ];
                $icons_oper_size = [
                    228
                ];
                $favicon_color = $param->favicon_color;

                $uri_base = JUri::base();

                if ($favicon = $param->favicon_files_100) {

                    JFactory::getDocument()->addFavicon($favicon, $type = FALSE, $relation = 'apple-touch-icon');
                    // JFactory::getDocument()->addCustomTag("<link rel='icon' sizes='any' type='image/png' href='$favicon'>");
                    JFactory::getDocument()->addHeadLink($favicon, 'icon', 'rel', [
                        'sizes' => 'any',
                        'type' => 'image/png'
                    ]);
                    JFactory::getDocument()->addCustomTag("<meta name='msapplication-TileImage' content='$favicon'>");
                    JFactory::getDocument()->addCustomTag("<meta name='yandex-tableau-widget' content='logo=$uri_base$favicon, color=$favicon_color' />");
                }
                if (($favicon = $param->favicon_files_0) != - 1) {
                    JFactory::getDocument()->addHeadLink($favicon, 'mask-icon', 'rel', [
                        'color' => $favicon_color
                    ]);
                    JFactory::getDocument()->addHeadLink($favicon, 'icon', 'rel', [
                        'sizes' => 'any',
                        'type' => 'image/svg+xml'
                    ]);
                }

                foreach ($icons_any_size as $size) {
                    if ($favicon = $param->{'favicon_files_' . $size})

                        JFactory::getDocument()->addHeadLink($favicon, 'icon', 'rel', [
                            'sizes' => "{$size}x{$size}",
                            'type' => 'image/png'
                        ]);
                }
                foreach ($icons_ios_size as $size) {
                    if ($favicon = $param->{'favicon_files_' . $size})

                        JFactory::getDocument()->addHeadLink($favicon, 'apple-touch-icon', 'rel', [
                            'sizes' => "{$size}x{$size}"
                        ]);
                }
                foreach ($icons_win_size as $size) {
                    if ($favicon = $param->{'favicon_files_' . $size })
                        JFactory::getDocument()->addCustomTag("<meta name='msapplication-square{$size}x{$size}logo' content='$favicon'>");
                }
                if ($favicon = $param->favicon_files_310150) {
                    JFactory::getDocument()->addCustomTag("<meta name='msapplication-square310x150logo' content='$favicon'>");
                }
                if ($favicon_color) {
                    JFactory::getDocument()->addCustomTag("<meta name='theme-color' content='$favicon_color'>");
                    JFactory::getDocument()->addCustomTag("<meta name='msapplication-TileColor' content='$favicon_color'>");
                    JFactory::getDocument()->addCustomTag("<meta name='apple-mobile-web-app-status-bar-style' content='$favicon_color'/>");
                }
            }

            $html = $param->html_code ?? '';

            if ($fontsFiles)
                static::fontsFiles($fontsFiles, $module->id);
            if ($fontsGoogle)
                static::fontsGoogle($fontsGoogle, $module->id);

            if ($param->libsFiles){
				
				/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
				$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();
				
				foreach ((array)$param->libsFiles as $libs) {
					
					foreach (str_getcsv($libs) as $lib){
						[$type, $lib] = explode(':', $lib);

						if($type == 'js'){
							if(str_ends_with($lib, '.js'))
								$wa->registerScript(basename($lib, '.js'), $lib);
							else
								$wa->useScript($lib);
						}
						
						if($type == 'css'){
							if(str_ends_with($lib, '.css'))
								$wa->registerStyle(basename($lib, '.css'), $lib);
							else
								$wa->useStyle($lib);
						}
					}
                }
		}
			
			
            if ($param->stylesheetFiles)
                foreach ((array)$param->stylesheetFiles as $key => $value) {
                    JHtml::stylesheet(trim($value, " /\\"), [], [
                        'moduleid' => $module->id
                    ]);
                }
            if ($param->stylesheetText ?? '')
                JFactory::getDocument()->addStyleDeclaration("/* Module:$module->id */\n$param->stylesheetText");
            if ($param->stylesheetTag ?? '')
                $html .= "<style type='text/css' module='$module->id'>$param->stylesheetTag</style>";

            if ($param->scryptFiles)
                foreach ((array)$param->scryptFiles as $key => $value) {
                    JHtml::script(trim($value, " /\\"), [], [
                        'module' => $module->id
                    ]);
                }

            if ($param->scriptText ?? '')
                JFactory::getDocument()->addScriptDeclaration("/* Module:$module->id */\n$param->scriptText");
            if ($param->scriptTag ?? '')
                $html .= "<script type='text/javascript' module='$module->id'>$param->scriptTag</script>";

            if ($param->copyright_show == 'before')
                $html = $copyright_text . $html;
            if ($param->copyright_show == 'after')
                $html .= $copyright_text;

            if ($param->html_order == 0)
                $modules[0] = $html;
            else
                $modules[sprintf("%02d", $param->html_order) . 'html'] = $html;
        }
        // </editor-fold>

        $current_id = $module->id;
        $current_position = $module->position;
        $param->title_alt = $param->set('title_alt', trim($param->title_alt));

        /**
         * Определение Альтернативного Заголовка
         */
        if ($param->get('title_alt_show')) {
            $module->title = $param->title_alt ?: $module->title;
            $menuitem = NULL;

            if ($param->title_alt_show == 'cur_comp') {
                $pathes = JFactory::getApplication()->getPathway()->getPathway();
                $pathway = end($pathes);

                if ($pathway) {
                    $menuitem = new stdClass();
                    $menuitem->title = $pathway->name;
                    $menuitem->link = $pathway->link;
                } else {
                    $menuitem = JFactory::getApplication()->getMenu()->getActive();
                    $menuitem->title = $menuitem->getParams()->get('page_title', $menuitem->title);
                }
            }

            if (in_array($param->title_alt_show ?? '', [
                'text',
                'cur_menu',
                'cur_tab',
                'cur_page'
            ])) {
                switch ($param->title_alt_header ?? '') {
                    case 'cur_menu':
                        $menuitem = JFactory::getApplication()->getMenu()->getActive();
                        break;
                    case 'main_menu':
                        $menuitem = JFactory::getApplication()->getMenu()->getDefault();
                        break;
                    default:
                        $menuitem = JFactory::getApplication()->getMenu()->getItem($param->title_alt_header);
                        break;
                }
            }

            switch ($param->title_alt_show ?? '') {
                case 'text':
                    $module->title = $param->title_alt ?: $module->title;
                    break; // toPrint('TextBreak');
                case 'cur_menu':
                    $module->title = $menuitem->title;
                    break; // toPrint('CurMenuBreak');
                case 'cur_tab':
                    $module->title = $menuitem->getParams()->get('page_title', $menuitem->title);
                    break; // toPrint($menuitem,'CurTabBreak');
                case 'cur_page':
                    $module->title = $menuitem->getParams()->get('page_heading', $menuitem->title);
                    break; // toPrint('CurPageBreak'); ;
                default:
                    $module->title = $menuitem->title;
                    break;
            }
            $param->title = $module->title;
        }

        /**
         * Определение ссылки для Заголовка
         */
        if ($param->link_show && $param->link_menu != '_') {
            jimport('joomla.methods');
            $link = '#';
            $link_type_menu = $param->link_menu;
            $menuitem = NULL;
            switch ($link_type_menu) {
                case 'text':
                    $menuitem = (object) [
                        'link' => $param->link ?: '#',
                        'route' => ($param->link ?: '#'),
                        'id' => ''
                    ];
                    break;
                case 'home_menu':
                    $menuitem = (object) [
                        'link' => '',
                        'route' => '',
                        'id' => ''
                    ];
                    break;
                case 'cur_menu':
                    $menuitem = JFactory::getApplication()->getMenu()->getActive();
                    break;
                case 'main_menu':
                    $menuitem = JFactory::getApplication()->getMenu()->getDefault();
                    break;
                default:
                    $menuitem = JFactory::getApplication()->getMenu()->getItem($param->link_menu);
            }

            if (JFactory::getConfig()->get('sef', TRUE)) {
                $link = JURI::base() . $menuitem->route;
            } else {
                $link = JURI::base() . $menuitem->link . (($menuitem->id) ? '&Itemid=' . $menuitem->id : '');
            }

            $link = JRoute::_($link);
            $param->link = $param->set('link', $link);
        }

        $module->moduleclass_sfx = $param->moduleclass_sfx = "$param->moduleclass_sfx multimodule id$current_id pos$current_position ";
        $module->header_class = $param->header_class = ($param->header_class ?: 'module-header') . ' multiheader ';

        $param->id = $module->id;
        $param->position = $module->position;
        $param->module = $module->module;

        $param->showtitle = $module->showtitle;
        $param->title = $module->title;

        $param->menuid = $module->menuid ?? false;
        $param->name = $module->name ?? 'multi';

        $lang = JFactory::getLanguage();
        $lang->load('joomla', JPATH_ADMINISTRATOR);
        $lang->load('com_modules', JPATH_ADMINISTRATOR);

        $module->empty_list = TRUE;

        $mod = "module" . $module->id;
        $par = "params" . $module->id;
        ${$mod} = &$module;
        ${$par} = &$param;

        /**
         * Article
         */
        if ($param->article_show && ($param->article_id || $param->article_ids)) {

            $param->article_ids = array_filter(explode(' ', str_replace(',', ' ', $param->article_ids)), fn ($num) => is_numeric($num) && $num >= 0);

            $articles = static::getArticles([
                $param->article_id,
                ...$param->article_ids
            ], [], ($param->item_tag != 'none' ? $param->article_show : ''), $module->id);
            $modules[sprintf("%02d", $param->article_order) . 'article'] = [];

            foreach ($param->article_ids as $id) {
                if (isset($articles[$id]))
                    $modules[sprintf("%02d", $param->article_order) . 'article'][] = &$articles[$id];
            }

            if ($modules[sprintf("%02d", $param->article_order) . 'article'] ?? false)
                $module->empty_list = FALSE;
        }

        /**
         * Articles
         */
        if ($param->articles_show) {

            $param->articles_id = $param->articles_id || ! in_array('', (array) $param->articles_id, true) ? array_filter((array) $param->articles_id) : [];
            $param->articles_tags = $param->articles_id || ! in_array('', (array) $param->articles_tags, true) ? array_filter((array) $param->articles_tags) : [];

            if ($param->articles_id || $param->articles_tags) {
                $modules[sprintf("%02d", $param->articles_order) . 'articles'] = static::getArticles([], $param->articles_id, ($param->item_tag != 'none' ? $param->articles_show : ''), $module->id, $param->articles_tags, $param->articles_sort ?? 'ordering ASC');
            }

            if ($modules[sprintf("%02d", $param->articles_order) . 'articles'] ?? false)
                $module->empty_list = FALSE;
        }
        /**
         * В разработке!!!!!!! categories
         */
        if ($param->categories_show && $param->categories_id) {

            $param->categories_id = in_array('', $param->categories_id, true) ? [] : array_filter($param->categories_id);

            if ($param->categories_id)
                $modules[sprintf("%02d", $param->categories_order) . 'categories'] = static::getArticles([], $param->categories_id, $param->categories_show);

            if ($modules[sprintf("%02d", $param->categories_order) . 'categories'] ?? false)
                $module->empty_list = FALSE;
        }
        /**
         * Пункты меню
         */
        if ($param->menu_show && $param->menu) {
            $link_css = 'menu-anchor_css';
            $link_title = 'menu-anchor_title';

            $modules[sprintf("%02d", $param->menu_order) . 'menu'] = $menus = &static::getMenuItems($param->menu);

            // echo "<pre>".print_r($param->menu_img_show,true)."</pre>";
            // return;

            foreach ($menus as $id => &$item) {
                $menus[$id]->params = json_decode($item->params);
                if (empty($menus[$id]->params->menu_show)) {
                    unset($menus[$id]);
                    unset($modules[sprintf("%02d", $param->menu_order) . 'menu'][$id]);
                    unset($item);
                    continue;
                }

                $link_class = '';
                $link_title = '';
                if ($item->params->$link_css ?? false)
                    $item->link_class = $link_class = $item->params->$link_css;

                if ($item->params->$link_title ?? false)
                    $item->link_title = $link_title = $item->params->$link_title;

                // $menus[$id]->header_class = $link_class;
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

                // echo "<pre>$id : ".print_r($item->params->$link_css,true)."</pre>";
                // echo "<pre>$id : ".print_r($menus[$id]->link_class,true)."</pre><br>";
                // $menus[$id]->link =
                // $menus[$id]->module = 'menu';
                // $menus[$id]->moduleclass_sfx = '';
                // $menus[$id]->header_tag = '';

                if ($param->menu_img_show)
                    $img = "<img alt='$item->link_title' class='menuimg id$item->id ' src='$item->menu_image'/>";

                if ($param->menu_img_show == 'in')
                    $menus[$id]->content = "<a href='$item->link' title='$link_title' class='menuitem a id$item->id $link_class level_$item->menu_image '>$img <span>$item->title</span></a>";
                elseif ($param->menu_img_show == 'out')
                    $menus[$id]->content = "$img<a href='$item->link' title='$link_title' class='menuitem b id$item->id $link_class level_$item->menu_image'><span>$item->title</span></a>";
                else
                    $menus[$id]->content = '';
                // $menus[$id]->content ="$img<a href='$item->link' title='$item->link_title' class='menuitem id$item->id $link_class '>$item->title</a>";

                if (! isset($items[$id]->items))
                    $menus[$id]->items = [];

                // if(($menus[$id]->parent_id > 1 || $menus[$id]->level > 1) && !isset($menus[$menus[$id]->parent_id])){
                // echo "<pre>$id --- parent: ".$menus[$id]->parent_id."</pre>";
                // }
                if (($menus[$id]->parent_id > 1 || $menus[$id]->level > 1)) {
                    if (isset($menus[$menus[$id]->parent_id]))
                        $menus[$menus[$id]->parent_id]->items[$id] = &$menus[$id];
                    // if(isset($menus[$menus[$id]->parent_id]) && in_array($param->menu_img_show, ['in','out']))
                    // $menus[$menus[$id]->parent_id]->content .= $menus[$id]->content;

                    unset($menus[$id]);
                    unset($modules[sprintf("%02d", $param->menu_order) . 'menu'][$id]);

                    continue;
                }
            }
            if ($modules[sprintf("%02d", $param->menu_order) . 'menu'])
                $module->empty_list = FALSE;
        }

        // toPrint($param->position_show,'$param->position_show',0,'pre',true);
        /**
         * Modules positions
         */
        if ($param->position_show) {
            if ($param->position_show == 'position_module')
                $positions = static::split($param->position_module, [
                    ' ',
                    ',',
                    ';',
                    '\n',
                    '\r',
                    '\t'
                ]);

            else
                $positions = (array) $param->position_modules;

            if ($positions) :

                if ($param->position_ordering == 'position_ordering') {
                    $ord = implode(',', $positions);
                    $param->position_ordering = " FIND_IN_SET(position, '$ord'), ";
                }

                $modules[sprintf("%02d", $param->position_order) . 'position'] = static::getModulesFromPosition($positions, $param->position_ordering, ${$mod}->id, ${$mod}->position, $param->item_style);

                if ($modules[sprintf("%02d", $param->position_order) . 'position'])
                    $module->empty_list = FALSE;
			endif;

        }
        /**
         * Modules ID
         */
        if ($param->modules_show) {

            if ($param->modules_show == 'id')
                $modulesID = static::split($param->modules_ids, [
                    ' ',
                    ',',
                    ';',
                    '\n',
                    '\r',
                    '\t'
                ]);
            else
                $modulesID = (array) $param->modules_sel;

            if ($param->modules_ordering == 'modules_ordering') {
                $ord = implode(',', $modulesID);
                $param->modules_ordering = " FIND_IN_SET(id, '$ord'), ";
            }

            if ($modulesID) :

                $modules[sprintf("%02d", $param->modules_order) . 'modules'] = static::getModulesFromSelected($modulesID, $param->modules_ordering, ${$mod}->id, ${$mod}->position, $param->item_style);

                if ($modules[sprintf("%02d", $param->modules_order) . 'modules'])
                    $module->empty_list = FALSE;
			endif;

        }
        /**
         * Description
         */
        if ($param->description_show ?? '') {
            $description_tag = $param->description_tag ?? 'div';
            if ($description_tag)
                $modules[sprintf("%02d", $param->description_order) . 'desc'] = "<$description_tag class='multidescription'>$param->description</$description_tag>";
            else
                $modules[sprintf("%02d", $param->description_order) . 'desc'] = $param->description;
        }
        /**
         * Image
         */
        if ($param->image_show && $param->image) {
            $address = JUri::base();
            $modules[sprintf("%02d", $param->image_order) . 'img'] = $image = "<img class='multiimage' src='$address/$param->image' alt='$param->title' />";
        }
        /**
         * Images
         */
        if ($rnd = $param->images_show) {

            foreach ((array) $param->images_folder as $i => $fold) {
                if ($fold == - 1) {
                    unset($param->images_folder[$i]);
                    continue;
                }
                $param->ArrayItem('images_folder', $i, '/images/' . $fold);
                // $param->images_folder[$i] = '/images/'.$fold;
            }

            foreach (explode("\n", trim($param->images_folder2)) as $img) {
                if ($img && is_dir(JPATH_SITE . '/images/' . $img))
                    $param->ArrayItem('images_folder', '', '/images/' . $img);
                // $param->images_folder[] = $img;
            }

            $modules[sprintf("%02d", $param->images_order) . 'images'] = $items = static::getImages($param->images_folder, $rnd, $param->images_count, $param->images_links, $param->images_titles, $param->images_texts);

            if ($items)
                $module->empty_list = FALSE;
        }
        /**
         * Tags
         */
        if ($param->tags_show ?? false) {

            $modules[sprintf("%02d", $param->tags_order) . 'tags'] = $items = static::getTags($param->tags_show, $param->tags_catids ?? [], $param->tags_parents ?? [], $param->tags_maximum, $param->tags_sort, $param->tags_count, $param->tags_category_title, $param->Itemid ?? 0);
        }
        /**
         */
        if ($param->query_show && trim($param->query_select)) {
            $modules[sprintf("%02d", $param->query_order) . 'query'] = $items = static::getSelects(trim($param->query_select));

            if ($items)
                $module->empty_list = FALSE;
        }

        $count_items = array_map(fn ($mods) => is_array($mods) ? count($mods) : 0, $modules);
        $count_items = array_sum($count_items);

        // if(empty($param->module_tag2))
        // $param->moduleclass_sfx = "multimodule $param->moduleclass_sfx count$count_items id$module->id"; //multimodule$param->moduleclass_sfx2 count$count_items id$id $param->style

        // if($current_id == 308){
        // // toPrint('<style type="text/css"> #page_wrap,.wcm_chat, #wcm_chat, #page_wrap{display:none!important;} </style>');
        // }

        $modules = array_filter($modules);

        ksort($modules, SORT_NATURAL);
        reset($modules);
        // echo "<pre>55555 ";
        // echo print_r($count_items,true);
        // echo "</pre>";
        return $modules;

        /**
         * Завершение пустого модуля
         */
        // $module = & ${$mod};
        // $params = & ${$par};
        // $param = & ${$par};

        // JHtml::script($img);// register($img, $function);
        // $wa = new \Joomla\CMS\WebAsset\WebAssetManager;

        // JFactory::getApplication()->getDocument()->getWebAssetManager()->usePreset('jquery.ui');
        // JFactory::getApplication()->getDocument()->getWebAssetManager()->usePreset('jquery.ui');
        // $wa->useAsset($img, $link_title);
        // $wa->useStyle('jquery.ui')->useScript('jquery.ui');

        require JModuleHelper::getLayoutPath('mod_multi', $param->get('layout', 'default'));

        $module = &${$mod};
        $params = &${$par};
        $param = &${$par};
    }

    public static function srcAdd()
    {
        $wa = JFactory::getApplication()->getDocument()->getWebAssetManager();
        $wa->registerScript('jquery.ui', 'modules/mod_multi/media/jquery/jquery-ui-1.14.1/jquery-ui.min.js');
        $wa->registerStyle('jquery.ui', 'modules/mod_multi/media/jquery/jquery-ui-1.14.1/jquery-ui.min.css');
    }

    public static function getAjax()
    {

        jimport('joomla.application.module.helper'); /* подключаем хелпер для модуля */

        $input = [
            'module' => 'CMD',
            'id' => 'INT',
            'format' => 'CMD',
            'deb' => 'STRING'
        ];

        $input = JFactory::getApplication()->input->getArray($input);

        $moduleid = (int) $input['id'];
        $moduleDeb = (string) $input['deb'];
        /* https://explorer-office.ru/index.php?option=com_ajax&format=raw&module=multi&id=299 */

        $query = "SELECT * FROM #__modules WHERE id=$moduleid;";
        $module = JFactory::getDbo()->setQuery($query)->loadObject();
        if (empty($module))
            return '';

        $module->ajax = true;

        JFactory::getLanguage()->load($module->module);

        $params = new \Reg($module->params);

		

		
		
//		$module->contentRendered = true;
		
		
		$file = JPATH_SITE . "/modules/$module->module/$module->module.php";
//		$file = JPATH_ROOT . '/modules/mod_multi/mod_multi.php';

		$attrib = [];
//		$attrib['contentOnly'] = true;

		if (file_exists($file)) {

			$content = '';
			ob_start();
			require $file;
			return ob_get_clean() . $content;
		} else {
			// $module->content = JModuleHelper::renderRawModule($module, $params, $attrib);
			// $module->content = JModHelp::renderModule($module, $attrib);
			// if($parentId == 311)
			// $module->content = '!:'. $module->id.':'. $chromestyle.':'.$params->style.' -- '.JModHelp::renderModule($module, $attrib);
			// else
			return JModHelp::renderModule($module, $attrib);
		}

		
//        $content = '';
//        ob_start();
//        require $file;
//        return ob_get_clean() . $content;
    }

    /**
     * ОБработка плагинами элементов модуля
     *
     * @param type $item
     * @param type $params
     * @param type $context
     * @return type
     */
    public static function preparePlugin(&$item, $params = null, $context = 'com_content.article')
    {
        if (empty(static::$params))
            return $item;
        $type_prepare = static::$params->get('prepare_content', FALSE);

        if (empty($type_prepare))
            return $item;

        $plg = JPluginHelper::importPlugin('content');

        if (is_string($item)) {
            $item = JHtml::_('content.prepare', $item);

            return $item;
        }

        // $item = JEventDispatcher::getInstance()->trigger($type_prepare, array($context, $item, $params, 0));

        $item = JFactory::getApplication()->triggerEvent($type_prepare, [
            $context,
            $item,
            $params,
            0
        ]);

        return $item;
    }

    /**
     *
     * @param int $idItemMenu
     * @return string
     */
    public static function getMenuLink($idItemMenu)
    {
        if (empty($idItemMenu))
            return '';
        $query = "SELECT * FROM #__menu WHERE id = $idItemMenu; ";
        $item = JFactory::getDBO()->setQuery($query)->loadObject();
        if (! isset($item) || ! isset($item->link))
            return '';
        return $item->link;
    }

    /**
     * Get link menuItem from alias
     *
     * @param string $aliasMenu
     *            Alias menu
     * @return string link item menu
     */
    public static function getMenuLink_fromAlias($aliasMenu)
    {
        if (empty($aliasMenu))
            return '';
        $query = "SELECT * FROM #__menu WHERE alias = '$aliasMenu'; ";
        $item = JFactory::getDBO()->setQuery($query)->loadObject();
        if (empty($item) || empty($item->link))
            return '';
        return $item->link;
    }

    public static function getMenuLink_fromId($idItemMenu)
    {
        if (empty($aliasMenu))
            return '';
        $query = "SELECT * FROM #__menu WHERE id = $idItemMenu; ";
        $item = JFactory::getDBO()->setQuery($query)->loadObject();
        if (! isset($item) || ! isset($item->link))
            return '';
        return $item->link;
    }

    /**
     * Get list items from menu
     *
     * @param string $typeMenu
     *            Type Menu
     * @return array list items from menu
     */
    public static function &getMenuItems($typeMenu)
    {
        $query = "SELECT * FROM #__menu WHERE menutype = '$typeMenu' AND published=1 ORDER BY lft,rgt,id; "; // AND level=1
        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');
        // toPrint($query,'$query',0,'message',true);
        foreach ($items as $i => $item) {
            $items[$i]->content = "$item->title";
            $items[$i]->type = "menu";
        }
        return $items;
    }

    /**
     * Проверяет наличие слова в массиве
     *
     * @param string $needle
     * @param string|array $haystack
     * @param array $separators
     *            [";","\n","\r","\t"]
     * @param string $trim_mask
     *            " \t\n\r\0\x0B"
     * @return bool
     */
    public static function inArray($needle, $haystack, $separators = [
        ";",
        "\t",
        "\n",
        "\r"
    ], $trim_mask = " \t\n\r\0\x0B")
    {
        if (empty($needle) || empty($haystack))
            return false;
        if (empty($separators))
            $separators = [
                '|'
            ];

        $sep = reset($separators);

        $needle = str_replace($separators, '', trim($needle, $trim_mask));

        if (is_string($haystack)) {
            $haystack = str_replace($separators, $sep, $haystack);
            $haystack = explode($sep, $haystack);
        }
        foreach ($haystack as $k => $str)
            $haystack[$k] = trim($str, $trim_mask);

        return in_array($needle, $haystack);
    }

    public static function fontsFiles($files, $moduleid = 0)
    {
        // <link rel="preconnect" href="https://fonts.googleapis.com">
        // <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        // <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;1,300;1,400&family=Roboto+Condensed&display=swap" rel="stylesheet">
        /* Module ID: 114 */
        // @font-face {
        // font-family: 'Jost-VariableFont_wght' ;
        // src: local("Jost-VariableFont_wght"),
        // url("/templates/fonts/Jost-VariableFont_wght.ttf") format('truetype');
        // }
        // .Jost-VariableFont_wght{font-family: 'Jost-VariableFont_wght';}
        /* Module ID: 114 */
        // @font-face { font-family: 'Jost-VariableFont_wght' ;
        // src: local("Jost-VariableFont_wght"),
        // url("/templates/fonts/Jost-VariableFont_wght.ttf") format('truetype');}
        // .Jost-VariableFont_wght{font-family: 'Jost-VariableFont_wght';}
        $files;

        static $sortFontsFormats = [
            'eot' => 'embedded-opentype',
            'woff' => 'woff',
            'woff2' => 'woff2',
            'ttf' => 'truetype',
            'otf' => '',
            'svg' => 'svg'
        ];

        $fonts = [];

        foreach ($files as $file) {
            $fileInfo = pathinfo($file);
            $fileInfo['path'] = $file;
            $fonts[$fileInfo['filename']][$fileInfo['extension']] = $fileInfo;
        }

        $uriRoot = trim(CMS\Uri\Uri::root(), '/');

        foreach ($fonts as $filename => $font) {
            if (empty($font))
                continue;

            $types = array_intersect_key($sortFontsFormats, $font);

            if (empty($types))
                continue;

            $style = " /* Module ID: $moduleid */ \n";
            $style .= "@font-face {\n font-family: '$filename' ;\n";
            $style .= "src: ";
            $style .= "local($filename),";
            $fnt = str_replace(' ', '', $filename);
            if (strpos($filename, ' '))
                $style .= $fnt = "local(\"$fnt\"),";

            $count = count($types);

            foreach ($types as $ext => $format) {
                if (empty($font[$ext]['path']) || empty($format))
                    continue;

                $style .= "\nurl(\"{$uriRoot}{$font[$ext]['path']}\")  format('$format')";
                $style .= (-- $count) ? ',' : '';
            }
            $style .= ";\n}\n";
            $style .= ".$fnt{font-family: '$filename';}\n";

            JFactory::getDocument()->addStyleDeclaration($style);
        }
    }

    public static function fontsGoogle($fonts, $moduleid = 0)
    {

        /* <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine"> */
        /* <link href='http://fonts.googleapis.com/css?family=ПРОБЛЕМНЫЙ+ШРИФТ&subset=latin,cyrillic' rel='stylesheet' type='text/css'> */
        /* <link href='http://fonts.googleapis.com/css?family=ПРОБЛЕМНЫЙ+ШРИФТ&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'> */
        /* https://developers.google.com/fonts/docs/getting_started */
        $fonts = (array) $fonts;
        $fonts_all = [];
        foreach ($fonts as $font) {
            /* $font = str_replace(['|','/n'], '|', $font);//[',','|',';',':','/',' ']; */
            $fornts = explode('/n', $font);
            $fonts_all = array_merge($fonts_all, $fornts);
        }

        foreach ($fonts_all as &$font) {
            $font = trim($font);
            if ($font)
                JHtml::stylesheet($font, [
                    'moduleid' => $moduleid
                ]);
            /* JHtml::stylesheet("//fonts.googleapis.com/css?family=".trim($font, " /\\"));//JUri::base(). */
        }
        /* JDocumentHtml::getInstance()->addHeadLink($href,$relation,$relType,$attribs); */

        /* JDocumentHtml->addFavicon($href, $type = 'image/vnd.microsoft.icon', $relation = 'shortcut icon'){ */

        /* JDocumentHtml->addCustomTag($html) */
    }

    public static function replace($old, $new, $str)
    {
        $new_str = str_replace($old, $new, $str);
        if ($new_str == $str)
            return $new_str;
        else
            return self::replace($old, $new, $new_str);
    }

    /**
     * Checking the terms of impressions.
     * / Проверка условий показов
     *
     * @param array $param
     *            Parameters / Параметры
     * @return bool результат проверки показов
     */
    public static function requireWork(&$param)
    {
        if (empty($param->get('work_type_require')) || $param->work_type_require == 'all')
            return TRUE;

        if ($param->work_type_require == 'and') :

            # 5 Require for Main Page
            if ($param->mainpage_is) :
                $mainpage_home = JFactory::getApplication()->getMenu()->getActive()->home;
                if ($param->mainpage_is == 'only' && ! $mainpage_home) {
                    return FALSE;
                } else if ($param->mainpage_is == 'without' && $mainpage_home) {
                    return FALSE;
                }
            endif;

                # 6 Require for Mobile device
            if ($param->mobile_is) :
                $is_mobile = static::is_mobile_device();
                if ($param->mobile_is == 'only' && ! $is_mobile) {
                    return FALSE;
                } else if ($param->mobile_is == 'without' && $is_mobile) {
                    return FALSE;
                }
            endif;

                # 7 Require for Languages
            if ($param->langs_is ?? false) :
                $tag = JFactory::getLanguage()->getTag();
                if ($param->langs_is == 'only' && ! in_array($tag, $param->langs)) {
                    return FALSE;
                } else if ($param->langs_is == 'without' && in_array($tag, $param->langs)) {
                    return FALSE;
                }
            endif;

            $res = TRUE;

            if (file_exists(MULTIMOD_PATH . '/src/Fields/use.php'))
                $res = require MULTIMOD_PATH . '/src/Fields/use.php';

            return $res;
            return TRUE;

        else :

            # 5 Require for Main Page
            if ($param->mainpage_is) :
                $mainpage_home = JFactory::getApplication()->getMenu()->getActive()->home;
                if ($param->mainpage_is == 'only' && $mainpage_home) {
                    return TRUE;
                } elseif ($param->mainpage_is == 'without' && ! $mainpage_home) {
                    return TRUE;
                }
            endif;

                # 6 Require for Mobile device
            if ($param->mobile_is) :
                $is_mobile = static::is_mobile_device();
                if ($param->mobile_is == 'only' && $is_mobile) {
                    return TRUE;
                } else if ($param->mobile_is == 'without' && ! $is_mobile) {
                    return TRUE;
                }
            endif;

                # 7 Require for Languages
            if ($param->langs_is) :
                $tag = JFactory::getLanguage()->getTag();
                if ($param->langs_is == 'only' && in_array($tag, $param->langs)) {
                    return TRUE;
                } else if ($param->langs_is == 'without' && ! in_array($tag, $param->langs)) {
                    return TRUE;
                }
            endif;

            $res = FALSE;

            if (file_exists(MULTIMOD_PATH . '/src/Fields/use.php'))
                $res = require MULTIMOD_PATH . '/src/Fields/use.php';

            return $res;
            return FALSE;

        endif;
    }

    /**
     * Get Images from image directory
     *
     * @param array|string $folder
     * @param bool $rnd
     * @param int $count
     * @param array|string|null $links
     *            Если NULL то тогда $links будет взят самими картинками
     * @param array|string $titles
     * @param array|string $texts
     * @return array list
     */
    public static function getImages($folders = '', $rnd = FALSE, $count = 12, $links = [], $titles = [], $texts = [])
    {
        jimport('joomla.filesystem.folder');

        $folders = (array) $folders;

        if ($folders == $links)
            $links = NULL;
        if (is_string($links))
            $links = (array) static::split($links, PHP_EOL);
        if (is_string($titles))
            $titles = (array) static::split($titles, PHP_EOL);
        if (is_string($texts))
            $texts = (array) static::split($texts, PHP_EOL);

        $items = [];
        foreach ($folders as $folder) {

            $files = (is_dir(JPATH_SITE . $folder)) ? JFolder::files(JPATH_SITE . $folder, '\.jpg|\.jpeg|\.JPG|\.JPEG|\.png|\.PNG|\.apng|\.APNG|\.gif|\.GIF|\.WEBP|\.webp|\.HEIF|\.heif|\.HEIC|\.heic|\.AVIF|\.avif$') : [];
            foreach ($files as $i => $file) {
                $items[$file] = new \Reg([
                    'image' => $folder . '/' . $file,
                    'link' => $links[$i] ?? '',
                    'title' => $titles[$i] ?? pathinfo($file, PATHINFO_FILENAME),
                    'text' => $texts[$i] ?? '',
                    'content' => $texts[$i] ?? '',
                    'moduleclass_sfx' => 'img_file',
                    'header_class' => 'img_title',
                    'id' => $i,
                    'type' => 'images',
                    'module_tag' => 'div',
                    'module' => '',
                    'style' => ''
                    // 'src' => JPATH_SITE.$folder.'/'.$file
                ]);
            }
        }

        if ($rnd == 'rnd')
            $items = self::array_shuffle_assoc($items);
        if ($count)
            $items = array_slice($items, 0, $count, TRUE);

        if (is_null($links)) {
            foreach ($items as &$item) {
                $item->link = $item->image;
            }
        }

        return $items;
    }

    /**
     * Get Articles from array IDs or one ID.
     *
     * @param array|int $articles_id
     * @param array|int $categorys_id
     * @param string $article_mode
     * @param int $mod_id
     * @param array $tags
     * @return array list
     */
    public static function getArticles($articles_id = [], $categorys_id = [], $article_mode = 'full', $mod_id = 0, $tags = [], $sorting = '')
    { // full,intro,content
        $where = '';

        // $type = is_array($articles_id) ? 'articles' : 'article';

        $articles_id = array_filter((array) $articles_id);

        if ($articles_id)
            $where .= "AND av.id IN (" . join(",", $articles_id) . ")";

        $categorys_id = array_filter((array) $categorys_id);

        if ($categorys_id)
            $where .= "AND av.catid IN (" . join(",", $categorys_id) . ")";

        $query = "SELECT id, asset_id, title, alias, introtext,	`fulltext`, access, language,images, ordering, state, attribs, catid, urls " . "FROM #__content av" . "WHERE   av.state = 1 " . "$where ; ";

        $ContentText = "''";

        switch ($article_mode) {
            case 'full':
                $ContentText = " CONCAT(a.introtext, ' ', a.fulltext) ";
                break;
            case 'intro':
                $ContentText = " a.introtext ";
                break;
            case 'fulltext':
                $ContentText = " a.fulltext ";
                break;
        }

        $tags = implode(',', array_filter((array) $tags));
        $tags_join_tags = '';

        if ($tags) {
            $tags_join_tags = " INNER JOIN joom_contentitem_tag_map m ON m.content_item_id = a.id AND m.tag_id IN ($tags) ";
        }

        if ($sorting && $sorting != 'rand()') {
            $sorting = " av.$sorting";
        }
        if (empty($sorting)) {
            $sorting = " true";
        }

        $query = "
SELECT av.*, GROUP_CONCAT(av.f_content SEPARATOR ' ') as f_content
FROM (
SELECT
    IF((af.f_type IN ('yesno','checkboxes','url') AND (af.f_val IN (NULL,'', '0'))        OR af.f_type IN ('imagelist') AND af.f_val=-1),'',
    CONCAT('<li class=\"field  ',af.f_type,' ',af.f_render_class,' req_',af.f_required,' \">',
    IF(af.f_showlabel, CONCAT('<span class=\"field-label ',af.f_type,' ',af.f_label_render_class,'\" >',af.f_label,'</span>'),''),
/*    --render_label, */
    CASE af.f_type
        WHEN 'yesno' THEN CONCAT('<span class=\field-value  val_',af.f_val,'\" >',af.f_val,'</span>')
        WHEN 'url' THEN  CONCAT('<a class=\"field-value   \" href=\"',af.f_val,'\" >',af.f_label,'</a>')
        WHEN 'imagelist'  THEN CONCAT('<img class=\"field-value   \" src=\"',af.f_val,'\" src=\"',af.f_label,'\"/>')
        WHEN 'checkboxes'  THEN  CONCAT('<span class=\"field-value  val_',af.f_val,' \" >',af.f_val,'</span>')
        WHEN 'integer '  THEN  CONCAT('<span class=\"field-value  val_',af.f_val,' \" >',af.f_val,'</span>')
      /*  --'text','textarea','integer', '' */
        ELSE CONCAT('<span class=\"field-value  \" >',af.f_val,'</span>')
    END,'</li>')) f_content,
    af.*
FROM (
SELECT
    f.params f_params, /*-- */
/*--    f.fieldparams f_field_params, */
/*--    LOCATE('\"label_render_class\":\"', f.params, 1) POS_label_render_class , */
    IF(LOCATE('\"display\":', f.params, 1), TRIM('}' FROM TRIM(',' FROM TRIM('\"' FROM SUBSTRING(f.params, LOCATE('\"display\":', f.params, 1)+10,2)))),'')  f_display,
    f.type f_type,

    IF(LOCATE('\"render_class\":\"', f.params, 1), SUBSTRING_INDEX(SUBSTRING(f.params, LOCATE('\"render_class\":\"', f.params, 1)+16),'\"',1),'')  f_render_class,

    a.id , a.catid, a.featured,a.state,a.ordering, a.version,a.title, a.checked_out, a.checked_out_time,  a.images,a.attribs,a.urls, $ContentText content, a.alias,a.access, a.language,a.hits,a.created, a.modified,
    f.id f_id, f.group_id f_group_id,  f.state f_state, f.required f_required,
    f.name f_name,  f.title f_title, f.label f_label,
    IF(LOCATE('\"showlabel\":', f.params, 1), TRIM('}' FROM TRIM(',' FROM TRIM('\"' FROM SUBSTRING(f.params, LOCATE('\"showlabel\":', f.params, 1)+12,2)))),'')  f_showlabel,
    IF(LOCATE('\"label_render_class\":\"', f.params, 1), SUBSTRING_INDEX(SUBSTRING(f.params, LOCATE('\"label_render_class\":\"', f.params, 1)+22),'\"',1),'')  f_label_render_class,
    f.ordering f_ordering,
/*--    f.context f_context, */
/*--    f.default_value f_default_value,     v.value f_value,*/
    IF(v.value!='',v.value,f.default_value) f_val, IF(v.value,0,1) f_def
FROM #__content a
$tags_join_tags
LEFT JOIN #__fields_categories fc ON fc.category_id  IN (a.catid, 0)
LEFT JOIN #__fields f ON fc.field_id = f.id AND f.state AND f.context = \"com_content.article\"
LEFT JOIN #__fields_values v ON v.item_id = a.id  AND f.id = v.field_id
/*--WHERE a.id = 1 AND f.state */
/*-- WHERE a.catid = 8 AND f.state */
/*-- GROUP BY a.id, display */
ORDER BY a.ordering, f.ordering
) af ) av
WHERE   state = 1
$where
GROUP BY av.id
ORDER BY $sorting
; ";

        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');

        foreach ($items as $id => &$art) {

            if ($art->f_content)
                $items[$id]->fields = "<ul class='fields'>$art->f_content</ul>";

            $params = new \Reg($items[$id]->f_params);
            $items[$id]->params = $params;

            $items[$id]->module = 'article';
            $items[$id]->module_tag = 'div';
            $items[$id]->moduleclass_sfx = "article $art->id";
            $items[$id]->header_class = "title ";
            jimport('joomla.registry.registry');
            $items[$id]->images = new \Reg($items[$id]->images);
            $items[$id]->image = $items[$id]->images->image_intro ?: $items[$id]->images->image_fulltext;
            $items[$id]->attribs = new \Reg($items[$id]->attribs);
            $items[$id]->urls = new \Reg($items[$id]->urls);
            $items[$id]->type = 'article'; // $type;
            JHtml::addIncludePath(JPATH_ROOT . '/components/com_content/src/Helper');
            /* require_once JPATH_BASE . '/components/com_content/helpers/route.php'; */
            require_once JPATH_BASE . '/components/com_content/src/Helper/RouteHelper.php';

            /* We know that user has the privilege to view the article */
            $items[$id]->link = JRoute::_(ContentHelperRoute::getArticleRoute($id, $art->catid));
            // $items[$id]->link = ContentHelperRoute::getArticleRoute($id, $art->catid);
            // toPrint($items);
            // $items[$id]->dt = HTML\Helpers\Date::relative($query);
        }

        return $items;
    }

    /**
     * Get Items with Query selects from DB
     *
     * @param string $query
     * @return array list
     */
    public static function getSelects($query = '')
    { // full,intro,content
        $items = JFactory::getDBO()->setQuery((string) $query)->loadObjectList();

        foreach ($items as $key => &$item) {
            if ($item->type) :
                if ($item->type == 'article') {
                    jimport('joomla.registry.registry');
                    $items[$key]->module_tag = 'div';
                    $items[$key]->moduleclass_sfx = "article id$item->id";

                    if (empty($items[$key]->image) && $items[$key]->images) {
                        $images = new \Reg($items[$key]->images);
                        $items[$key]->images = $images->toObject();
                        $items[$key]->image = $items[$key]->images->image_intro ?? $items[$key]->images->image_fulltext;
                    }

                    if (empty($items[$key]->attribs)) {
                        $attribs = new \Reg($items[$key]->attribs);
                        $items[$key]->attribs = $attribs->toObject();
                    }

                    if (empty($items[$key]->urls) && $items[$key]->urls) {
                        $urls = new \Reg($items[$key]->urls);
                        $items[$key]->urls = $urls->toObject();
                    }

                    if (empty($items[$key]->link) && $items[$key]->id && $items[$key]->catid) {
                        // require_once JPATH_BASE . '/components/com_content/helpers/route.php';
                        require_once JPATH_BASE . '/components/com_content/src/Helper/RouteHelper.php';
                        $items[$key]->link = JRoute::_(ContentHelperRoute::getArticleRoute($key, $item->catid));
                    }
                }
                if ($item->type == 'category') {
                    jimport('joomla.registry.registry');
                    $items[$key]->module_tag = 'div';
                    $items[$key]->moduleclass_sfx = "article id$item->id";

                    $params = new \Reg($items[$key]->params);
                    $items[$key]->params = $params;

                    if (empty($items[$key]->content) && $items[$key]->description)
                        $items[$key]->content = $items[$key]->description;

                    if (empty($items[$key]->image) && $items[$key]->params)
                        $items[$key]->image = $items[$key]->params->image;
                    if (empty($items[$key]->link))
                        $items[$key]->link = JRoute::_(ContentHelperRoute::getCategoryRoute($item->id, ($item->language ?? 0))); // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<---------
                }

            endif;

        }
        return $items;
    }

    /**
     * Список категорий по ID - //В разработке все ещё
     *
     * @param int $catid
     * @return array list
     */
    public static function getCategories($catid = NULL)
    { // full,intro,content
        if ($catid)
            $catid = " AND id = $catid";

        $items = [];
        $query = "
SELECT id, parent_id, lft, rgt, level, path, title, alias, description, published, params, description, language
FROM #__categories
WHERE access AND published $catid
ORDER BY lft LIMIT 300; ";

        // if($modules_ordering)
        // $query .= "ORDER BY ordering ";
        // $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');
        /* https://cdnjs.cloudflare.com/ajax/libs/three.js/r68/three.min.js */

        return $items;
    }

    public static function getTags($show = 'list', $catids = [], $parents = [], $maximum = 50, $order = 'title ASC', $count = true, $category_title = false, $Itemid = 0)
    {
        $db = JFactory::getDbo();
        $nowDate = JFactory::getDate()->toSql();
        $nullDate = $db->getNullDate();

        $user = JFactory::getUser();
        $groups = $user->getAuthorisedGroups();
        $levels = $user->getAuthorisedViewLevels();

        $groups = implode(',', $groups);
        $groupsIn = '0,' . $groups;

        if ($catids)
            $catids = ' AND `cat`.`id` IN (' . implode(',', $catids) . ') ';
        else
            $catids = '';
        if ($parents)
            $parents = ' AND `t`.`parent_id` IN (' . implode(',', $parents) . ') ';
        else
            $parents = '';

        $query = "
SELECT MAX(`tag_id`) AS `tag_id`,COUNT(*) AS `count`,MAX(`t`.`parent_id`) AS `parent_id`,MAX(`t`.`title`) AS `title`,MAX(`t`.`alias`) AS `alias`,MAX(`t`.`access`) AS `access`,MAX(`t`.`params`) AS `params`,'' AS `parent`,MAX(`t`.`images`) AS `images`,`cat`.`title` AS `cat_title`,`cat`.`id` AS `cat_id`, `t`.`description` `content`, 'tags' `type`
FROM `#__contentitem_tag_map` AS `m`
INNER JOIN `#__tags` AS `t` ON `tag_id` = `t`.`id`
INNER JOIN `#__ucm_content` AS `c` ON `m`.`core_content_id` = `c`.`core_content_id`
INNER JOIN `#__categories` AS `cat` ON `c`.`core_catid` = `cat`.`id`
WHERE `t`.`access` IN ($groups) AND `t`.`published` = 1  $parents $catids AND
	`cat`.`access` IN ($groups) AND `cat`.`published` = 1 AND `m`.`type_alias` = `c`.`core_type_alias` AND `c`.`core_state` = 1
	AND `c`.`core_access` IN ($groupsIn)
	AND (`c`.`core_publish_up` IS NULL OR `c`.`core_publish_up` = '$nullDate' OR `c`.`core_publish_up` <= '$nowDate')
	AND (`c`.`core_publish_down` IS NULL OR `c`.`core_publish_down` = '$nullDate' OR `c`.`core_publish_down` >= '$nowDate')
GROUP BY `tag_id`,`t`.`title`,`t`.`access`,`t`.`alias`,`cat`.`id`
ORDER BY $order LIMIT $maximum
		";

        if ($order != 'rand()')
            $query = "
SELECT `a`.`tag_id`,`a`.`parent_id`,`a`.`count`,`a`.`title`,`a`.`access`,`a`.`alias`,`a`.`cat_id`,`a`.`cat_title`,`a`.`params`,`a`.`parent`,`a`.`images`, `a`.`content`, `a`.`type`
FROM ($query) AS `a`
ORDER BY $order  LIMIT $maximum ;
			";

        try {

            $db->setQuery($query);
            $list = $db->loadObjectList();
        } catch (\RuntimeException $e) {
            $list = array();
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        $Itemid = $Itemid ? "&Itemid=$Itemid" : '';

        foreach ($list as &$tag) {

            $tag->items = [];
            $cat_id = $tag->cat_id ? "&id=$tag->cat_id" : '';

            if ($count)
                $tag->content .= "<span class='tag-count badge bg-info'>$tag->count</span>";

            if ($category_title)
                $tag->content .= "<span class='tag-category badge bg-info'>$tag->cat_title</span>";

            $tag->images = json_decode($tag->images, false);
            $tag->image = htmlspecialchars($tag->images->image_intro, ENT_COMPAT, 'UTF-8');
            $tag->title = htmlspecialchars($tag->title, ENT_COMPAT, 'UTF-8');
            $tag->text = $tag->content;
            $tag->header_class = 'tag-title';
            $tag->moduleclass_sfx = 'tag';
            $tag->link = JRoute::_("index.php?option=com_content&view=category&layout=blog$cat_id$Itemid&filter_tag=$tag->tag_id");
            $tag->module_tag = $tag->tag_id;
            $tag->id = $tag->tag_id;
            $tag->style = '';
            $tag->header_tag = 'span';
        }

        if ($show == 'tree') {

            $tag_ids = array_column($list, 'tag_id');
            $cat_ids = array_column($list, 'cat_id');
            $parents = [];

            foreach ($list as &$tag) {
                /* Ключи родителей */
                $parent_keys = array_keys($tag_ids, $tag->parent_id);
                if ($parent_keys) {

                    $c_ids = array_intersect_key($cat_ids, array_flip($parent_keys));
                    if (in_array($tag->cat_id, $c_ids)) {
                        $cat_id = $tag->cat_id;
                    } else {
                        $cat_id = reset($c_ids);
                    }
                    $cat_key = array_search($cat_id, $c_ids);
                    $tag->parent = &$list[$cat_key];
                    $list[$cat_key]->items[] = &$tag;
                } else {
                    $parents[] = $tag;
                }
            }
            $list = $parents;
        }

        return $list;
    }

    /**
     * Create Tree elements with properties: children, parent.
     * Use properties: id, parent_id
     *
     * @param array $list
     * @return array
     */
    public static function Tree($list)
    {
        $parents = [];
        $ids = array_column($list, 'id');
        $parents_ids = array_column($list, 'parent_id');

        foreach ($list as $i => &$tag) {
            $tag->items = [];
        }

        foreach ($list as $i => &$tag) {
            $parent_i = array_search($tag->parent_id, $parents_ids);
            if ($parent_i !== false) {
                $tag->parent = &$list[$parent_i];
                $list[$parent_i]->items[] = &$tag;
            } else {
                $tag->parent = '';
                $parents[] = $tag;
            }
        }

        return $parents;
    }

    public static function getModulesFromPosition($positions, $modules_ordering, $current_id = 0, $current_position = '', $chromestyle = '', $item_tag = '')
    {
        foreach ($positions as $key => $pos)
            $positions[$key] = trim($pos);
        $where = join("','", $positions);

        $tag = JFactory::getLanguage()->getTag();

        $query = "SELECT id, asset_id, title, published, module, access, showtitle, params, language, ordering, position, content " . "FROM #__modules " . "WHERE client_id=0 AND published = 1 /*AND module!='mod_multi'*/ " . "AND position IN ('$where') " . "AND language IN ('$tag','*') ";
        if ($current_id)
            $query .= "AND id!='$current_id' ";
        if ($current_position)
            $query .= "AND !(position='$current_position' AND `module`='mod_multi') ";
        $query .= "ORDER BY $modules_ordering ordering ";

        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');
        // echo ' .!.'.$module->moduleclass_sfx .' ';
        // echo ' .!.'.$chromestyle .' ';
        // echo ' .!.'.$module->style .' ';
        // echo ' .!.'.$module->params->get('class_sfx','') .' !!! ';

        // if($current_id == 311){
        // echo "<pre> ! ";
        // //print_r($item_tag);
        // //echo "\n !";
        // print_r($chromestyle);
        // //////print_r($params->toObject());
        // echo '</pre>';
        // }

        if (in_array($item_tag, [
            '',
            '0',
            0,
            NULL
        ], true)) {
            // $chromestyle = 'System-none';
            return static::getModules($items, $chromestyle, $current_id);
        }
        if ($chromestyle != 'System-none') {

            return static::getModules($items, $chromestyle, $current_id);
        }
        if ($chromestyle == 'System-none') {

            return static::getModules($items, $chromestyle, $current_id);
        }
    }

    public static function getModulesFromSelected($modulesID, $modules_ordering, $current_id = 0, $current_position = '', $chromestyle = '', $item_tag = '')
    {
        $modulesID = trim(join(",", $modulesID), ',');
        $lang = JFactory::getLanguage()->getTag();

        if (empty($modulesID))
            return [];

        $b = \JFactory::getDbo();

        $query = "SELECT id, asset_id, title, published, module, access, showtitle, params, language, ordering, position, content " . "FROM #__modules " . "WHERE client_id=0 AND published = 1 /*AND module!='mod_multi'*/ " . "AND id IN ($modulesID) AND language IN (" . $b->q($lang) . ',' . $b->q('*') . ") ";

        if ($current_id)
            $query .= "AND id!='$current_id' ";
        if ($current_position)
            $query .= "AND !(position='$current_position' AND `module`='mod_multi') ";
        $query .= "ORDER BY $modules_ordering ordering ";

        $query .= " ; ";

        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');

        if (in_array($item_tag, [
            '',
            '0',
            0,
            NULL
        ], true) && $chromestyle) {
            // $chromestyle = 'System-none';

            return static::getModules($items, $chromestyle, $current_id);
        }
        if ($chromestyle != 'System-none' || empty($chromestyle)) {

            return static::getModules($items, $chromestyle, $current_id);
        }
        if ($chromestyle == 'System-none') {

            return static::getModules($items, $chromestyle, $current_id);
        }
    }

    public static function getModules($multi_items, $chromestyle = '', $parentId = 0)
    {
        $app = JFactory::getApplication();
        // if($parentId == 311){
        // echo "<pre> ! ";
        // //print_r($item_tag);
        // //echo "\n !";
        // print_r($chromestyle);
        // //////print_r($params->toObject());
        // echo '</pre>';
        // }
        foreach ($multi_items as $multi_module_id => &$module) {

		JFactory::getLanguage()->load($module->module);

		$params = $module->params = new \Reg($module->params);

		$module->module_tag = $params->module_tag;
		$module->header_tag = $params->header_tag;
		$module->parentId = $parentId;
		$module->style = $params->style;
		$module->moduleclass_sfx = $params->moduleclass_sfx;
		$module->header_class = $params->header_class;
		$module->link = $params->link;
		$module->type = 'modules';
		$module->image = $params->image ?: $params->backgroundimage;
		$module->style = $params->style;

		if ($chromestyle && $chromestyle != '0')
			$params->style = $chromestyle;

		$file = JPATH_SITE . "/modules/$module->module/$module->module.php";

		$attrib = [];
//		$attrib['contentOnly'] = true;

		if (file_exists($file)) {
			// if($parentId == 311)
			// $content = '!!:'.$chromestyle.':'. $params->style;
			// else
			$content = '';

			ob_start();

			require $file;

			$module->content = ob_get_clean() . $content;
		} else {

//			 $module->content = JModuleHelper::renderRawModule($module, $params, $attrib);
//			 $module->content = JModHelp::renderModule($module, $attrib);
//			 if($parentId == 311)
//			 $module->content = '!:'. $module->id.':'. $chromestyle.':'.$params->style.' -- '.JModHelp::renderModule($module, $attrib);
//			 else
			$module->content = JModHelp::renderModule($module, $attrib);
		}

		$module->contentRendered = true;

		if (empty($module->published) || $module->published < 0 || empty($module->content))
			unset($multi_items[$multi_module_id]);
	}
	return $multi_items;
	}

	public static function getModulesLegacy($multi_items, $chromestyle = '', $parentId = 0)
	{
        $app = JFactory::getApplication();

        foreach ($multi_items as $multi_module_id => &$module) {

			$module->params = new \Reg($module->params);

			if ($chromestyle && $chromestyle != '0')
				$module->params->style = $chromestyle;

			$module->image = $params->image ?: $params->backgroundimage;

			$module->content = JModuleHelper::renderModule($module);
			$module->type = 'modules';

			if (empty($module->published))
			unset($multi_items[$multi_module_id]);
		}
		return $multi_items;
	}

    /**
     * Split string
     *
     * @param type $string
     *            String spliting
     * @param array|string $separators
     *            Char(chars) separator
     * @return array Array items
     */
    public static function split($string = '', $separators = [
        '|'
    ])
    { // array|string $separators
        if (empty($string))
            return [];

        if (empty($separators))
            $separators = [
                '|',
                PHP_EOL
            ];

        if (is_string($separators))
            $separators = (array) $separators;

        $sep = reset($separators);

        $string = str_replace([
            '\n',
            '\r',
            '\t'
        ], '', $string);

        $string = str_replace($separators, $sep, $string);

        return array_filter(array_map(fn ($item) => trim($item), explode($sep, $string)));
    }

    /**
     * Check mobile device user
     *
     * @return boolean
     */
    public static function is_mobile_device()
    {
        static $isMobile;
        if ($isMobile !== NULL)
            return $isMobile;

        $mobile_agent_array = array(
            'ipad',
            'iphone',
            'android',
            'pocket',
            'palm',
            'windows ce',
            'windowsce',
            'cellphone',
            'opera mobi',
            'ipod',
            'small',
            'sharp',
            'sonyericsson',
            'symbian',
            'opera mini',
            'nokia',
            'htc_',
            'samsung',
            'motorola',
            'smartphone',
            'blackberry',
            'playstation portable',
            'tablet browser'
        );
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        foreach ($mobile_agent_array as $value) {
            if (strpos($agent, $value) !== false) {
                $isMobile = TRUE;
                return TRUE;
            }
        }
        $isMobile = FALSE;
        return FALSE;
    }

    private static function array_shuffle_assoc(array &$list)
    {
        if (! is_array($list))
            return $list;

        $keys = array_keys($list);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }
        return $random;
    }

    /**
     * Method to truncate introtext
     * Метод обрезания текста
     *
     * The goal is to get the proper length plain text string with as much of
     * the html intact as possible with all tags properly closed.
     *
     * @param string $html
     *            The content of the introtext to be truncated
     * @param integer $maxLength
     *            The maximum number of charactes to render
     *            
     * @return string The truncated string
     *        
     * @since 1.6
     */
    public static function truncate($html, $maxLength = 0)
    {
        $baseLength = \strlen($html);

        /* First get the plain text string. This is the rendered text we want to end up with. */
        $ptString = JHtml::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = false);

        for ($maxLength; $maxLength < $baseLength;) {
            /* Now get the string if we allow html. */
            $htmlString = JHtml::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = true);

            /* Now get the plain text from the html string. */
            $htmlStringToPtString = JHtml::_('string.truncate', $htmlString, $maxLength, $noSplit = true, $allowHtml = false);

            /* If the new plain text string matches the original plain text string we are done. */
            if ($ptString === $htmlStringToPtString) {
                return $htmlString;
            }

            /* Get the number of html tag characters in the first $maxlength characters */
            $diffLength = \strlen($ptString) - \strlen($htmlStringToPtString);

            /* Set new $maxlength that adjusts for the html tags */
            $maxLength += $diffLength;

            if ($baseLength <= $maxLength || $diffLength <= 0) {
                return $htmlString;
            }
        }

        return $html;
    }
}
