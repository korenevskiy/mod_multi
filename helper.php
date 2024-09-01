<?php defined('_JEXEC') or die;
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

use \Joomla\CMS;
use \Joomla\CMS\HTML;
use \Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Router\Route as JRoute;
//use Joomla\Registry\Registry as JRegistry;
use Joomla\CMS\Factory as JFactory;

jimport('joomla.application.module.helper');

//ModMultiHelper::getAjax
abstract class ModMultiHelper
{
    public static $params;

    public static function getAjax(){
        jimport('joomla.application.module.helper');/* подключаем хелпер для модуля */

        $input = ['module'=>'CMD','id'=>'INT','format'=>'CMD','deb'=>'STRING',];

        $input = JFactory::getApplication()->input->getArray($input);

        $moduleid = (int)$input['id'];
        $moduleDeb = (string)$input['deb'];
		/* https://explorer-office.ru/index.php?option=com_ajax&format=raw&module=multi&id=299        */

        $query = "SELECT * FROM #__modules WHERE id=$moduleid;";
        $module = JFactory::getDbo()->setQuery($query)->loadObject();
        if(empty($module))
            return '';

        $module->ajax = true;

        JFactory::getLanguage()->load($module->module);
		


        $params = new \Reg($module->params);

        $content = '';
        ob_start();
        require JPATH_ROOT.'/modules/mod_multi/mod_multi.php';
        $content = ob_get_clean().$content;

        return $content;
    }

/**
 * ОБработка плагинами элементов модуля
 * @param type $item
 * @param type $params
 * @param type $context
 * @return type
 */
    public static function preparePlugin( &$item, $params = null, $context = 'com_content.article') {

        if(empty(static::$params))
             return $item;
        $type_prepare = static::$params->get('prepare_content',FALSE);

        if(empty($type_prepare))
            return $item;

        $plg = JPluginHelper::importPlugin('content');

        if(is_string($item)){
            $item = JHtml::_('content.prepare', $item);

            return $item;
        }

        $item = JEventDispatcher::getInstance()->trigger($type_prepare, array($context,  $item,  $params, 0));
        return $item;
    }

    /**
     *
     * @param int $idItemMenu
     * @return string
     */
    public static function getMenuLink($idItemMenu)
    {
        if(empty($idItemMenu))
            return '';
        $query= "SELECT * FROM #__menu WHERE id = $idItemMenu; ";
        $item = JFactory::getDBO()->setQuery($query)->loadObject();
        if(!isset($item) || !isset($item->link)) 
			return '';
        return $item->link;
    }
    /**
     * Get link menuItem from alias
     * @param string $aliasMenu Alias menu
     * @return string link item menu
     */
    public static function getMenuLink_fromAlias($aliasMenu )
    {
        if(empty($aliasMenu))
            return '';
        $query= "SELECT * FROM #__menu WHERE alias = '$aliasMenu'; ";
        $item = JFactory::getDBO()->setQuery($query)->loadObject();
        if(empty($item) || empty($item->link))
            return '';
        return $item->link;
    }

    public static function getMenuLink_fromId($idItemMenu)
    {
        if(empty($aliasMenu))
            return '';
        $query= "SELECT * FROM #__menu WHERE id = $idItemMenu; ";
        $item = JFactory::getDBO()->setQuery($query)->loadObject();
        if(!isset($item) || !isset($item->link)) return '';
        return $item->link;
    }

    /**
     * Get list items from menu
     * @param string $typeMenu Type Menu
     * @return array list items from menu
     */
    public static function &getMenuItems($typeMenu )
    {
        $query= "SELECT * FROM #__menu WHERE menutype = '$typeMenu' AND published=1 ORDER BY lft,rgt,id; "; //AND level=1 
        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');
//toPrint($query,'$query',0,'message',true);
        foreach ($items as $i => $item){
            $items[$i]->content = "$item->title";
            $items[$i]->type = "menu";
        }
        return $items;
    }

    /**
     * Проверяет наличие слова в массиве
     * @param string $needle
     * @param string|array $haystack
     * @param array $separators [";","\n","\r","\t"]
     * @param string $trim_mask " \t\n\r\0\x0B"
     * @return bool
     */
    public static function inArray($needle, $haystack, $separators=[";","\t","\n","\r"],$trim_mask = " \t\n\r\0\x0B")
    {
        if(empty($needle) || empty($haystack))
            return false;
        if(empty($separators))
            $separators = ['|'];
		
        $sep = reset($separators);

        $needle = str_replace($separators, '', trim($needle, $trim_mask));

        if(is_string($haystack)){
            $haystack = str_replace($separators, $sep, $haystack);
            $haystack = explode($sep, $haystack);
        }
        foreach ($haystack as $k=>$str)
            $haystack[$k] = trim($str, $trim_mask);

        return in_array($needle, $haystack);
    }

    public static function fontsFiles($files, $moduleid = 0)
    {
//<link rel="preconnect" href="https://fonts.googleapis.com">
//<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
//<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;1,300;1,400&family=Roboto+Condensed&display=swap" rel="stylesheet">
/* Module ID: 114 */ 
// @font-face { 
//		font-family: 'Jost-VariableFont_wght' ;
//		src: local("Jost-VariableFont_wght"),
//		url("/templates/fonts/Jost-VariableFont_wght.ttf")  format('truetype');
//		}
//.Jost-VariableFont_wght{font-family: 'Jost-VariableFont_wght';}
 /* Module ID: 114 */ 
// @font-face { font-family: 'Jost-VariableFont_wght' ;
//src: local("Jost-VariableFont_wght"),
//url("/templates/fonts/Jost-VariableFont_wght.ttf")  format('truetype');}
//.Jost-VariableFont_wght{font-family: 'Jost-VariableFont_wght';}


        $files;

        $sortFonts = ['eot' => 'embedded-opentype','woff' => 'woff','ttf' => 'truetype','otf' => '','svg' => 'svg'];

        $fonts = [];

        foreach ($files as $file){
            $fileInfo = pathinfo($file);
            $fileInfo['path']= $file;
            $fonts[$fileInfo['filename']][$fileInfo['extension']] = $fileInfo;
        }

        foreach ($fonts as $filename => $font){
            $style = " /* Module ID: $moduleid */ \n";
            $style .= "@font-face {\n font-family: '$filename' ;\n";
            $style .= "src: ";
            $style .= "local($filename),";
            $fnt = str_replace(' ', '', $filename);
            if(strpos($filename, ' '))
                $style .= $fnt = "local(\"$fnt\"),";
            $count = count($font);

            foreach ($sortFonts as $ext => $format ){
                if(empty($font[$ext]))
                    continue;

                $style .= "\nurl(\"{$font[$ext]['path']}\")  format('$format')";

                $style .= (--$count)?',':'';

            }
            $style .= ";\n}\n";
            $style .= ".$fnt{font-family: '$filename';}\n";

            JFactory::getDocument()->addStyleDeclaration($style);

        }
    }
	
    public static function fontsGoogle($fonts, $moduleid = 0){
		
/* <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine">  */
/* <link href='http://fonts.googleapis.com/css?family=ПРОБЛЕМНЫЙ+ШРИФТ&subset=latin,cyrillic' rel='stylesheet' type='text/css'> */
/*   <link href='http://fonts.googleapis.com/css?family=ПРОБЛЕМНЫЙ+ШРИФТ&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>  */
/* https://developers.google.com/fonts/docs/getting_started */
        $fonts = (array)$fonts;
        $fonts_all = [];
        foreach ($fonts as $font){
/* $font = str_replace(['|','/n'], '|', $font);//[',','|',';',':','/',' ']; */
            $fornts = explode('/n', $font);
            $fonts_all = array_merge($fonts_all, $fornts);
        }

        foreach ($fonts_all as &$font){
            $font = trim($font);
            if($font)
                JHtml::stylesheet($font,['moduleid'=>$moduleid]);
/* JHtml::stylesheet("//fonts.googleapis.com/css?family=".trim($font, " /\\"));//JUri::base(). */
        }
/* JDocumentHtml::getInstance()->addHeadLink($href,$relation,$relType,$attribs); */

/* JDocumentHtml->addFavicon($href, $type = 'image/vnd.microsoft.icon', $relation = 'shortcut icon'){ */

/*  JDocumentHtml->addCustomTag($html) */

    }

    public static function replace($old, $new, $str)
    {
        $new_str = str_replace($old,$new,$str);
        if($new_str==$str)
            return $new_str;
        else
            return self::replace($old, $new, $new_str);
    }

    /**
     * Checking the terms of impressions. / Проверка условий показов
     * @param array $param Parameters / Параметры
     * @return bool результат проверки показов
     */
    public static function requireWork(&$param)
    {

        if(empty ($param->get('work_type_require')) || $param->work_type_require == 'all')
            return TRUE;

        if($param->work_type_require == 'and'):

            #5 Require for Main Page
            if($param->mainpage_is):
                $mainpage_home = JFactory::getApplication()->getMenu()->getActive()->home;
                if($param->mainpage_is == 'only' && !$mainpage_home){
                    return FALSE;
                }
                else if($param->mainpage_is == 'without' && $mainpage_home){
                    return FALSE;
                }
            endif;

            #6 Require for Mobile device
            if($param->mobile_is):
                $is_mobile = static::is_mobile_device();
                if($param->mobile_is == 'only' && !$is_mobile){
                    return FALSE;
                }
                else if($param->mobile_is == 'without' && $is_mobile){
                    return FALSE;
                }
            endif;

            #7 Require for Languages
            if($param->langs_is ?? false):
                $tag = JFactory::getLanguage()->getTag();
                if($param->langs_is == 'only' && !in_array($tag,$param->langs)){
                    return FALSE;
                }
                else if($param->langs_is == 'without' && in_array($tag,$param->langs)){
                    return FALSE;
                }
            endif;

            $res = TRUE;

            if(file_exists(__DIR__.'/fields/use.php'))
                $res = require __DIR__.'/fields/use.php';
			
            return $res;
            return TRUE;

        else:

            #5 Require for Main Page
            if($param->mainpage_is):
                $mainpage_home = JFactory::getApplication()->getMenu()->getActive()->home;
                if($param->mainpage_is == 'only' && $mainpage_home){
                    return TRUE;
                }
                elseif($param->mainpage_is == 'without' && !$mainpage_home){
                    return TRUE;
                }
            endif;

            #6 Require for Mobile device
            if($param->mobile_is):
                $is_mobile = static::is_mobile_device();
                if($param->mobile_is == 'only' && $is_mobile){
                    return TRUE;
                }
                else if($param->mobile_is == 'without' && !$is_mobile){
                    return TRUE;
                }
            endif;

            #7 Require for Languages
            if($param->langs_is):
                $tag = JFactory::getLanguage()->getTag();
                if($param->langs_is == 'only' && in_array($tag,$param->langs)){
                    return TRUE;
                }
                else if($param->langs_is == 'without' && !in_array($tag,$param->langs)){
                    return TRUE;
                }
            endif;

            $res = FALSE;

            if(file_exists(__DIR__.'/fields/use.php'))
                $res = require __DIR__.'/fields/use.php';

            return $res;
            return FALSE;

        endif;
    }

    /**
     * Get Images from image directory
     * @param array||string $folder
     * @param bool $rnd
     * @param int $count
     * @param array|string|null $links Если NULL то тогда $links будет взят самими картинками
     * @param array|string $titles
     * @param array|string $texts
     * @return array list
     */
    public static function getImages($folders='', $rnd = FALSE,$count=12,$links=[],$titles=[],$texts=[]){
        jimport( 'joomla.filesystem.folder' );

        $folders = (array)$folders;

        if($folders==$links)
            $links = NULL;
        if(is_string($links))
            $links = (array)static::split ($links, PHP_EOL);
        if(is_string($titles))
            $titles = (array)static::split ($titles, PHP_EOL);
        if(is_string($texts))
            $texts = (array)static::split ($texts, PHP_EOL);

        $items = [];
        foreach ($folders as $folder){
            $files = JFolder::files(JPATH_SITE.$folder, '\.jpg|\.jpeg|\.JPG|\.JPEG|\.png|\.PNG|\.apng|\.APNG|\.gif|\.GIF|\.WEBP|\.webp|\.HEIF|\.heif|\.HEIC|\.heic|\.AVIF|\.avif$');
            foreach ($files as $i => $file){
                $items[$file] = new \Reg([
                    'image'		=>	$folder . '/' . $file,
                    'link'		=>	$links[$i]	??	'',
                    'title'		=>	$titles[$i]	??	pathinfo($file, PATHINFO_FILENAME),
                    'text'		=>	$texts[$i] ?? '',
                    'content'	=>	$texts[$i] ?? '',
                    'moduleclass_sfx' => 'img_file',
                    'header_class' => 'img_title',
                    'id'		=>	$i,
                    'type'		=>	'images',
                    'module_tag'=>	'div',
					'module'	=>	'',
					'style'		=>	'',
//					'src'		=>	JPATH_SITE.$folder.'/'.$file
                ]);
            }
        }

        if($rnd == 'rnd')
            $items = self::array_shuffle_assoc($items);
        if($count)
            $items = array_slice ($items, 0, $count, TRUE);

        if(is_null($links)){
            foreach ($items as &$item){
                $item->link = $item->image;
            }
        }

        return $items;
    }
    /**
     * Get Articles from array IDs or one ID.
     * @param array|int $articles_id
     * @param array|int $categorys_id
     * @param string $article_mode
     * @param int $mod_id
     * @param array $tags
     * @return array list
     */
    public static function getArticles($articles_id = [], $categorys_id = [], $article_mode = 'full', $mod_id = 0, $tags = [], $sorting = ''){//full,intro,content
        $where = '';
		
//		$type = is_array($articles_id) ? 'articles' : 'article';

        $articles_id = array_filter((array)$articles_id);
		
        if($articles_id)
            $where .= "AND av.id IN (".join(",",$articles_id).")";
		
        $categorys_id = array_filter((array)$categorys_id);
		
        if($categorys_id)
            $where .= "AND av.catid IN (".join(",",$categorys_id).")";

        $query = "SELECT id, asset_id, title, alias, introtext,	`fulltext`, access, language,images, ordering, state, attribs, catid, urls "
                . "FROM #__content av"
                . "WHERE   av.state = 1 "
                . "$where ; ";
		
		$ContentText = "''";
		
		switch ($article_mode){
			case 'full': $ContentText = " CONCAT(a.introtext, ' ', a.fulltext) "; break;
			case 'intro': $ContentText = " a.introtext "; break;
			case 'fulltext': $ContentText = " a.fulltext "; break;
		}
		
		$tags = implode(',', array_filter((array)$tags));
		$tags_join_tags = '';
		
		if($tags ){
			$tags_join_tags = " INNER JOIN joom_contentitem_tag_map m ON m.content_item_id = a.id AND m.tag_id IN ($tags) ";
		}
		
		if($sorting && $sorting != 'rand()'){
			$sorting = " av.$sorting";
		}
		if(empty($sorting)){
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
		
        foreach ($items as $id => &$art){

			
			
            if($art->f_content)
                $items[$id]->fields = "<ul class='fields'>$art->f_content</ul>";

            $params = new \Reg($items[$id]->f_params);
            $items[$id]->params = $params;

            $items[$id]->module = 'article';
            $items[$id]->module_tag = 'div';
            $items[$id]->moduleclass_sfx = "article $art->id";
            $items[$id]->header_class = "title ";
            jimport( 'joomla.registry.registry' );
            $items[$id]->images = new \Reg($items[$id]->images);
            $items[$id]->image = $items[$id]->images->image_intro ?: $items[$id]->images->image_fulltext;
            $items[$id]->attribs = new \Reg($items[$id]->attribs);
            $items[$id]->urls = new \Reg($items[$id]->urls);
            $items[$id]->type = 'article';//$type;
            JHtml::addIncludePath(JPATH_ROOT . '/components/com_content/src/Helper');
/* require_once JPATH_BASE . '/components/com_content/helpers/route.php'; */
            require_once JPATH_BASE . '/components/com_content/src/Helper/RouteHelper.php';

				/* We know that user has the privilege to view the article */
			$items[$id]->link = JRoute::_(ContentHelperRoute::getArticleRoute($id, $art->catid)); 
//            $items[$id]->link = ContentHelperRoute::getArticleRoute($id, $art->catid);
//toPrint($items);
//			$items[$id]->dt = HTML\Helpers\Date::relative($query);

        }
		
        return $items;
    }
    /**
     * Get Articles from array IDs or one ID.
     * @param string $query
     * @return array list
     */
    public static function getSelects($query = ''){//full,intro,content

        $items = JFactory::getDBO()->setQuery((string)$query)->loadObjectList();

        foreach ($items as $key => &$item){
            if($item->type):
                if($item->type=='article'){
                    jimport( 'joomla.registry.registry' );
                    $items[$key]->module_tag = 'div';
                    $items[$key]->moduleclass_sfx = "article id$item->id";

                    if(empty($items[$key]->image) && $items[$key]->images){
                        $images = new \Reg($items[$key]->images);
                        $items[$key]->images = $images->toObject();
                        $items[$key]->image = $items[$key]->images->image_intro ?? $items[$key]->images->image_fulltext;
                    }

                    if(empty($items[$key]->attribs)){
                        $attribs = new \Reg($items[$key]->attribs);
                        $items[$key]->attribs = $attribs->toObject();
                    }

                    if(empty($items[$key]->urls) && $items[$key]->urls){
                        $urls = new \Reg($items[$key]->urls);
                        $items[$key]->urls = $urls->toObject();
                    }

                    if(empty($items[$key]->link) && $items[$key]->id && $items[$key]->catid){
                        require_once JPATH_BASE . '/components/com_content/helpers/route.php';
                        $items[$key]->link = JRoute::_(ContentHelperRoute::getArticleRoute($key, $item->catid));
                    }
                }
                if($item->type=='category'){
                    jimport( 'joomla.registry.registry' );
                    $items[$key]->module_tag = 'div';
                    $items[$key]->moduleclass_sfx = "article id$item->id";

                    $params = new \Reg($items[$key]->params);
                    $items[$key]->params = $params->toObject();

                    if(empty($items[$key]->content) && $items[$key]->description)
                        $items[$key]->content = $items[$key]->description;

                    if(empty($items[$key]->image) && $items[$key]->params)
                        $items[$key]->image = $items[$key]->params->image;
                    if(empty($items[$key]->link))
                        $items[$key]->link = JRoute::_(ContentHelperRoute::getCategoryRoute($item->id, ($item->language??0))); //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<---------
                }

            endif;

        }
        return $items;
    }

    /**
     * Список категорий по  ID - //В разработке все ещё
     * @param int $catid
     * @return array list
     */
    public static function getCategories($catid = NULL){//full,intro,content

		if($catid)
			$catid = " AND id = $catid";

         $items = [];
         $query = "
SELECT id, parent_id, lft, rgt, level, path, title, alias, description, published, params, description, language
FROM #__categories
WHERE access AND published $catid
ORDER BY lft LIMIT 300; ";

//        if($modules_ordering)
//            $query .= "ORDER BY ordering ";
//        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');
/* https://cdnjs.cloudflare.com/ajax/libs/three.js/r68/three.min.js */

        return $items;
    }

    public static function getTags($show = 'list', $catids = [], $parents = [], $maximum = 50, $order = 'title ASC', $count=true, $category_title = false, $Itemid = 0) {

		$db         = JFactory::getDbo();
		$nowDate    = JFactory::getDate()->toSql();
		$nullDate   = $db->getNullDate();

		$user       = JFactory::getUser();
		$groups     = $user->getAuthorisedGroups();
		$levels		= $user->getAuthorisedViewLevels();

		$groups		= implode(',', $groups);
		$groupsIn	= '0,'.$groups;

		if($catids)
			$catids = ' AND `cat`.`id` IN ('. implode(',', $catids) . ') ';
		else
			$catids = '';
		if($parents)
			$parents = ' AND `t`.`parent_id` IN ('. implode(',', $parents) . ') ';
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

		if($order != 'rand()')
			$query = "
SELECT `a`.`tag_id`,`a`.`parent_id`,`a`.`count`,`a`.`title`,`a`.`access`,`a`.`alias`,`a`.`cat_id`,`a`.`cat_title`,`a`.`params`,`a`.`parent`,`a`.`images`, `a`.`content`, `a`.`type`
FROM ($query) AS `a`
ORDER BY $order  LIMIT $maximum ;
			";

		try
		{

			$db->setQuery($query);
			$list = $db->loadObjectList();

		}
		catch (\RuntimeException $e)
		{
			$list = array();
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		$Itemid = $Itemid ? "&Itemid=$Itemid" : '' ;
		
		

		foreach ($list as &$tag)
		{

			$tag->items = [];
			$cat_id = $tag->cat_id ? "&id=$tag->cat_id" : '';

			if($count)
				$tag->content .= "<span class='tag-count badge bg-info'>$tag->count</span>";

			if($category_title)
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

		if($show == 'tree'){

			$tag_ids = array_column($list, 'tag_id');
			$cat_ids = array_column($list, 'cat_id');
			$parents = [];

			foreach ($list as &$tag)
			{
				/* Ключи родителей */
				$parent_keys = array_keys($tag_ids, $tag->parent_id);
				if($parent_keys){

					$c_ids = array_intersect_key($cat_ids, array_flip($parent_keys));
					if(in_array($tag->cat_id, $c_ids)){
						$cat_id = $tag->cat_id;
					}else{
						$cat_id = reset($c_ids);
					}
					$cat_key = array_search($cat_id, $c_ids);
					$tag->parent = &$list[$cat_key];
					$list[$cat_key]->items[] = &$tag;
				}else{
					$parents[] = $tag;
				}
			}
			$list = $parents;
		}

		return $list;
	}

	/**
	 * Create Tree elements with properties: children, parent. Use properties: id, parent_id
	 * @param array $list
	 * @return array
	 */
	public static function Tree($list) {
		$parents = [];
		$ids = array_column($list, 'id');
		$parents_ids = array_column($list, 'parent_id');

		foreach ($list as $i => &$tag){
			$tag->items = [];
		}

		foreach ($list as $i => &$tag)
		{
			$parent_i = array_search($tag->parent_id, $parents_ids);
			if($parent_i !== false){
				$tag->parent = &$list[$parent_i];
				$list[$parent_i]->items[] = &$tag;
			}
			else {
				$tag->parent = '';
				$parents[] = $tag;
			}

		}

		return $parents;
	}

    public static function getModulesFromPosition($positions,$modules_ordering,$current_id = 0,$current_position = '',$chromestyle='',$item_tag=''){

        foreach ($positions as $key => $pos)
            $positions[$key] = trim($pos);
        $where = join("','",$positions);

        $tag = JFactory::getLanguage()->getTag();

        $query = "SELECT id, asset_id, title, published, module, access, showtitle, params, language, ordering, position, content "
                . "FROM #__modules "
                . "WHERE client_id=0 AND published = 1 /*AND module!='mod_multi'*/ "
                . "AND position IN ('$where') "
                . "AND language IN ('$tag','*') ";
        if($current_id)
            $query .= "AND id!='$current_id' ";
        if($current_position)
            $query .= "AND !(position='$current_position' AND `module`='mod_multi') ";
        $query .= "ORDER BY $modules_ordering ordering ";

        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');

        if(in_array($item_tag, ['','0',0,NULL],true)){
            $chromestyle = 'System-none';
            return self::getModules($items,$chromestyle,$current_id);
        }
        if($chromestyle != 'System-none'){

            return self::getModulesLegacy($items,$chromestyle,$current_id);
        }
        if($chromestyle == 'System-none'){

            return self::getModules($items, $chromestyle,$current_id);
        }
    }
    public static function getModulesFromSelected($modulesID,$modules_ordering,$current_id = 0,$current_position = '',$chromestyle='',$item_tag=''){

        $modulesID = trim(join(",",$modulesID),',');
        $lang = JFactory::getLanguage()->getTag();

        if(empty($modulesID))
            return [];

		$b = \JFactory::getDbo();

        $query = "SELECT id, asset_id, title, published, module, access, showtitle, params, language, ordering, position, content "
                . "FROM #__modules "
                . "WHERE client_id=0 AND published = 1 /*AND module!='mod_multi'*/ "
                . "AND id IN ($modulesID) AND language IN (" . $b->q($lang) . ',' . $b->q('*') . ") ";

        if($current_id)
            $query .= "AND id!='$current_id' ";
        if($current_position)
            $query .= "AND !(position='$current_position' AND `module`='mod_multi') ";
        $query .= "ORDER BY $modules_ordering ordering ";

        $query .= " ; ";

        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');

        if(in_array($item_tag, ['','0',0,NULL],true) && $chromestyle){
            $chromestyle = 'System-none';

            return self::getModules($items,$chromestyle,$current_id);
        }
        if($chromestyle != 'System-none' || empty($chromestyle)){

            return self::getModulesLegacy($items,$chromestyle,$current_id);
        }
        if($chromestyle == 'System-none'){

            return self::getModules($items,$chromestyle,$current_id);
        }
    }

    public static function getModules($multi_items, $chromestyle = '', $parentId=0){

		$app = JFactory::getApplication();

        foreach ($multi_items as $multi_module_id => $module){

            $file = JPATH_SITE."/modules/$module->module/$module->module.php";
            $content = "";

            if(file_exists($file)){

                $params = $module->params = new \Reg($module->params);

				$module->style = $params->style ?: '';

                if($chromestyle)
                    $module->params->style = $chromestyle;

				$module->parentId = $parentId;
				$module->style = $module->params->get('style','');
				$module->moduleclass_sfx = $module->params->get('moduleclass_sfx','');
				$module->module_tag = $module->params->get('module_tag','');
				$module->header_tag = $module->params->get('header_tag','');
				$module->header_class = $module->params->get('header_class','');

                $image = $module->params->get('backgroundimage','');
                $image = $module->params->get('image', $image);

                $multi_items[$multi_module_id]->image = $image;

                $module->params->set('image', $image);

                $link = $module->params->get('link', '');
                $multi_items[$multi_module_id]->link = $link;
                $module->params->set('link', $link);

                $multi_items[$multi_module_id]->published = $module->published;
                $multi_items[$multi_module_id]->params= $module->params;
                $multi_items[$multi_module_id]->parentId= $module->parentId;
                $multi_items[$multi_module_id]->style= $module->style;
                $multi_items[$multi_module_id]->moduleclass_sfx= $module->moduleclass_sfx;
                $multi_items[$multi_module_id]->module_tag= $module->module_tag;
                $multi_items[$multi_module_id]->header_tag= $module->header_tag;
                $multi_items[$multi_module_id]->header_class= $module->header_class;
                $multi_items[$multi_module_id]->type= 'modules';

                JFactory::getLanguage()->load($module->module);

                $content = '';
                ob_start();

                require $file;

                $multi_items[$multi_module_id]->content = ob_get_clean().$content;

                if(empty($module->published) || empty($multi_items[$multi_module_id]->content))
                    unset($multi_items[$multi_module_id]);

            }
        }
        return $multi_items;
    }

    public static function getModulesLegacy($multi_items, $chromestyle = '', $parentId=0){

		$app = JFactory::getApplication();
			
        foreach ($multi_items as $multi_module_id => &$module){

            $module->params =  new \Reg($module->params);
            if($chromestyle && $chromestyle != '0'){
                $module->params->set('style',$chromestyle);
            }
            $module->image = $module->params->get('backgroundimage',FALSE);
            $module->image = $module->params->get('image',$module->image);

            $module->content = JModuleHelper::renderModule($module);
            $module->type = 'modules';

            if(empty($module->published))
                unset($multi_items[$multi_module_id]);

        }
        return $multi_items;
    }

    /**
     * Split string
     * @param type $string String spliting
     * @param array|string $separators Char(chars) separator
     * @return array Array items
     */
    public static function split($string = '', $separators = ['|']){ // array|string $separators
        if(empty($string))
            return [];
		
        if(empty($separators))
            $separators = ['|',PHP_EOL];
		
		if(is_string($separators))
			$separators	= (array)$separators;
		
        $sep = reset($separators);

        $string = str_replace(['\n','\r','\t'], '', $string);

        $string = str_replace($separators, $sep, $string);
		
		return array_filter(array_map(fn($item)=>trim($item), explode($sep, $string)));
    }

    /**
     * Check mobile device user
     * @return boolean
     */
    public static function is_mobile_device() {

        static $isMobile;
        if($isMobile!==NULL)
            return $isMobile;

        $mobile_agent_array = array('ipad', 'iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
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

    private static function array_shuffle_assoc(array &$list) {
        if (!is_array($list)) return $list;

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
	 * @param   string   $html       The content of the introtext to be truncated
	 * @param   integer  $maxLength  The maximum number of charactes to render
	 *
	 * @return  string  The truncated string
	 *
	 * @since   1.6
	 */
	public static function truncate($html, $maxLength = 0)
	{
		$baseLength = \strlen($html);

		/* First get the plain text string. This is the rendered text we want to end up with. */
		$ptString = JHtml::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = false);

		for ($maxLength; $maxLength < $baseLength;)
		{
			/* Now get the string if we allow html. */
			$htmlString = JHtml::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = true);

			/* Now get the plain text from the html string. */
			$htmlStringToPtString = JHtml::_('string.truncate', $htmlString, $maxLength, $noSplit = true, $allowHtml = false);

			/* If the new plain text string matches the original plain text string we are done. */
			if ($ptString === $htmlStringToPtString)
			{
				return $htmlString;
			}

			/* Get the number of html tag characters in the first $maxlength characters */
			$diffLength = \strlen($ptString) - \strlen($htmlStringToPtString);

			/* Set new $maxlength that adjusts for the html tags */
			$maxLength += $diffLength;

			if ($baseLength <= $maxLength || $diffLength <= 0)
			{
				return $htmlString;
			}
		}

		return $html;
	}
}

abstract class JModHelp extends JModuleHelper{
    static function &ModeuleDelete($module) : int {
        $modules = &static::load();
		
		$count = 0;
//toPrint(null,'',0, 'pre',true);
//toPrint(count($modules),'$module 1', 0, 'message',true);

        foreach ($modules as $i => &$mod){
            if($mod->id == $module->id){
                unset ($modules[$i]); // Вызывает ошибку свойства Position объекта модуля, 
                unset ($mod);
		
            }elseif($module->position == $mod->position ){
				$count++;
			}
//			else{
//				$modules[$i]->position = $mod->position == null ? '' : $mod->position;
//			}
        }
		
		array_multisort($modules);
//toPrint(count($modules),'$module 2', 0, 'message',true);

        $modules = &static::getModules($module->position);

        $module->published = '';
        $module->position = '';
        $module->module = '';
        $module->style = 'System-none';
        return $count;
    }
}

if(empty(class_exists('Reg'))){
class Reg extends \Joomla\Registry\Registry{
	function __get($nameProperty) {
		return $this->get($nameProperty, '');
	}
	function __set($nameProperty, $value = null) {
		$this->set($nameProperty, $value);
	}
	
	function __isset($nameProperty) {
		return $this->exists($nameProperty);
	}
	
	public function __unset($nameProperty)
	{
		$this->remove($nameProperty);
	}
	
	function ArrayItem($nameProperty, $index = null, $value = null){
		
		if(!isset($this->data->$nameProperty))
			$this->data->$nameProperty = [];
		
		
		if($index === null && $value === null)
			return $this->data->$nameProperty ?? [];
		
		$old = $this->data->$nameProperty[$index] ?? null;
		
		if($value === null)
			return $old;
		
		if($index === '' || $index === null)
			$this->data->$nameProperty[] = $value;
		else
			$this->data->$nameProperty[$index] = $value;
		
		return $old;
	}
}
}