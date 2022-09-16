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

// no direct access


use \Joomla\CMS;
use \Joomla\CMS\HTML;
use \Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Router\Route as JRoute;
use Joomla\Registry\Registry as JRegistry;
use Joomla\CMS\Factory as JFactory;

        jimport('joomla.application.module.helper'); //подключаем хелпер для модуля
//require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
//require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
//require_once JPATH_SITE.'/components/com_content/helpers/route.php';
//jimport('joomla.application.component.model');
//JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

abstract class modMultiHelper //modProdCalendarHelper
{ 
    public static $params;
  
    
    public static function getAjax(){
        jimport('joomla.application.module.helper'); //подключаем хелпер для модуля
        
        $input = ['module'=>'CMD','id'=>'INT','format'=>'CMD','deb'=>'STRING',];
        //format=json|debug|raw   /method=...   /
        $input = JFactory::getApplication()->input->getArray($input);
        
        $moduleid = (int)$input['id'];
        $moduleDeb = (string)$input['deb'];
//https://explorer-office.ru/index.php?option=com_ajax&format=raw&module=multi&id=299        
        
        $query = "SELECT * FROM #__modules WHERE id=$moduleid;";
        $module = JFactory::getDbo()->setQuery($query)->loadObject();
        if(empty($module))
            return '';
        
        $module->ajax = true;
        
        JFactory::getLanguage()->load($module->module);
        
        $params = new JRegistry($module->params);//$module->params = 
        
        
			
        $content = '';
        ob_start();
        require JPATH_ROOT.'/modules/mod_multi/mod_multi.php';
        $content = ob_get_clean().$content; 
        
//        if(empty($module->published) || empty($html))
//            return '';
        
        
//        $html .= "<pre>";
//        $html .= print_r($input,true);
//        $html .= "</pre>";
//$moduleid = JFactory::getApplication()->input->getInt('id');// module id     
        
//        $content .=   '<pre>'.toPrint($module,'$module',0,false,true).'</pre>';
//        $content .=   '<pre>'.toPrint($input,'$input',0,false,true).'</pre>';
//        $content .=   '<pre>'.toPrint($query,'$query',0,false,true).'</pre>';
        return $content;
    }
    
/**
 * ОБработка плагинами элементов модуля
 * @param type $item
 * @param type $params
 * @param type $context
 * @return type
 */
    public static function preparePlugin( &$item, $params = null, $context = 'com_content.article') { //'com_content.article', 'text'
        
        if(empty(static::$params))
             return $item;
        $type_prepare = static::$params->get('prepare_content',FALSE);
        
        if(empty($type_prepare))
            return $item; 
        
//$debag = (is_string($item) && substr($item, 0, 4)=='<lbl');
//$debag = (is_string($item) && substr($item, 1, 16)=='div class=\'image');
//toPrint(substr($item, 1, 16),'$debag');        
//toPrint($debag,'$debag');        
        $plg = JPluginHelper::importPlugin('content');
//        if(is_string($item) && substr($item, 0, 4)=='<lbl')
//            toPrint($plg,'$plg');
//            toPrint($item,'$item');
//        if(is_object($item)){
//            JEventDispatcher::getInstance()->trigger($type_prepare, array($context, & $item,  $params, 0));
//            //return;
//            return $item;
//        }
//        return $item;
     
        if(is_string($item)){//  is_string($item) $context='text' $type_prepare == 'onContentPrepare' && 
            $item = JHtml::_('content.prepare', $item);   
//if($debag)  
//    toPrint($item,'modMultiHelper');
            return $item;
        }
//    if($type_prepare == 'onContentPrepare')
//        return JHtml::_('content.prepare', $item,$param,$context); 
//    $item = (object)['text'=>&$item,'X'=>$params->get('id')]; 
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
        if(empty($aliasMenu))
            return '';
        $query= "SELECT * FROM #__menu WHERE id = $idItemMenu; ";
        $item = JFactory::getDBO()->setQuery($query)->loadObject();
        if(!isset($item) || !isset($item->link)) return '';
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
    public static function getMenuItems($typeMenu )
    {   
        $query= "SELECT * FROM #__menu WHERE menutype = '$typeMenu' AND published=1 AND level=1 ORDER BY lft,rgt,id; ";
        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');
        
        foreach ($items as $i => $item){
            $items[$i]->content = "$item->title"; 
            $items[$i]->type = "menu"; 
                    
//                    (object)['image'=>'/images/'.$folder.'/'.$file,'link'=>$links[$i]??'','title'=>$titles[$i]??'','text'=>$texts[$i]?"<p>$texts[$i]</p>":'',
//                'content'=>$texts[$i]??'','moduleclass_sfx'=>'img_file','header_class'=>'img_title','text'=>$texts[$i]??'','text'=>$texts[$i]??'',
//                'id'=>$i];
        }
        return $items;
    }

    /**
     * Проверяет наличие слова в массиве
     * @param string $word
     * @param string|array $full_string
     * @param array $separators
     * @param string $trim_mask
     * @return bool
     */
    public static function inArray($needle, $haystack, $separators=[';'],$trim_mask = ' ')
    {
        if(empty($word) || empty($haystack))
            return false;
        if(empty($separators))
            $separators = ['|'];
        $sep = reset($separators);
        
        $needle = str_replace(['\n','\r','\t'], '', $needle);        
        $needle = str_replace($separators, $sep, $needle);
        
        if(is_string($haystack)){ 
            $haystack = str_replace([','], ';', $haystack); 
            $haystack = explode(';', $haystack);
        }
        $needle= trim(trim($needle),$trim_mask); 
        foreach ($haystack as $k=>$str) 
            $haystack[$k] = trim(trim($str),$trim_mask);
        
//        $x =  in_array($word,$strings); 
        
//        echo '<pre style"color: green;">'.print_r($word,true).'</pre>';
//        echo '<pre style"color: green;">'.print_r($strings,true).'</pre>';
        //if(!$x)        throw new Exception($str.' '.$full_string.' '.$trim_mask);
        return in_array($needle,$haystack);
    }
    
    public static function fontsFiles($files, $moduleid = 0)
    {
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
            $style .= " @font-face { font-family: '$filename' ;\n";
            $style .= "src: ";
            $style .= "local(\"$filename\"),";
            $fnt = str_replace(' ', '', $filename);
            if(strpos($filename, ' '))
                $style .= $fnt = "local(\"$fnt\"),";
            $count = count($font);
//            toPrint($font,'$font');
            
//                echo '***'.$count.'***';
                
            foreach ($sortFonts as $ext => $format ){
                if(empty($font[$ext]))
                    continue;
//                echo "+__".$count;
                $style .= "\nurl(\"{$font[$ext]['path']}\")  format('$format')";
                //if($count--) $style .= ',';
                $style .= (--$count)?',':'';
//                echo "_".$count;
            }
            $style .= ";}\n";
            $style .= ".$fnt{font-family: '$filename';}\n";
            
            JFactory::getDocument()->addStyleDeclaration($style);
//            toPrint($style,'$fonts');
        }
         
    }
    public static function fontsGoogle($fonts, $moduleid = 0)
    { //  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine"> 
//        <link href='http://fonts.googleapis.com/css?family=ПРОБЛЕМНЫЙ+ШРИФТ&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
//        <link href='http://fonts.googleapis.com/css?family=ПРОБЛЕМНЫЙ+ШРИФТ&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>        
/////////////https://developers.google.com/fonts/docs/getting_started
        $fonts = (array)$fonts;
        $fonts_all = [];
        foreach ($fonts as $font){
//            $font = str_replace(['|','/n'], '|', $font);//[',','|',';',':','/',' '];
            $fornts = explode('/n', $font);
            $fonts_all = array_merge($fonts_all, $fornts);
        }
        
//    toPrint($fonts_all,'$fonts_all',0,true,true);
        foreach ($fonts_all as &$font){
            $font = trim($font);
            if($font)
                JHtml::stylesheet($font,['moduleid'=>$moduleid]);//JUri::base().
//                JHtml::stylesheet("//fonts.googleapis.com/css?family=".trim($font, " /\\"));//JUri::base().
        } 
         
//        $href = '//www.vanhost.ru/my_ip';
//        $relation = 'stylesheet';
//        $relType = 'rel';
//        $attribs = [];
////        	public function addHeadLink($href, $relation, $relType = 'rel', $attribs = array())        
//        JDocumentHtml::getInstance()->addHeadLink($href,$relation,$relType,$attribs);
//        return;
        
//        JDocumentHtml->addFavicon($href, $type = 'image/vnd.microsoft.icon', $relation = 'shortcut icon'){
//		$href = str_replace('\\', '/', $href);
//		$this->addHeadLink($href, $relation, 'rel', array('type' => $type)); 
//		return $this;
//	}
//        JDocumentHtml->addCustomTag($html){
//		$this->_custom[] = trim($html);
//		return $this;
//	}

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
//        $param->work_type_require;// and, or, 0, all
        
        if(empty ($param->work_type_require) || $param->work_type_require == 'all')
            return TRUE;
        
        
//        if($param->description && $param->description_show)
//            return TRUE;

        
        
        //throw new Exception($web_site_is);
        //echo '<pre style"color: green;">'.print_r($_SERVER,true).'</pre>';
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
            
//            toPrint($res,'$resAND');
            
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
            
//            toPrint($res,'$resOR');
            
            return $res; 
            return FALSE;
        
        endif;
    }  
//Array
//(
//    [HTTP_AUTHORIZATION] => 
//    [HTTP_HOST] => test.cirkbilet.ru
//    [HTTP_X_FORWARDED_FOR] => 109.61.152.69
//    [HTTP_CONNECTION] => close
//    [HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
//    [HTTP_UPGRADE_INSECURE_REQUESTS] => 1
//    [HTTP_USER_AGENT] => Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36
//    [HTTP_DNT] => 1
//    [HTTP_ACCEPT_ENCODING] => gzip, deflate, sdch
//    [HTTP_ACCEPT_LANGUAGE] => ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4
//    [HTTP_COOKIE] => d6f00ee3af688931aa6f8d05470ca775=13952cdc87ff24aa82aad0b2810d7254
//    [PATH] => /usr/local/bin:/usr/bin:/bin
//    [SERVER_SIGNATURE] => 
//    [SERVER_SOFTWARE] => Apache/2.2.22
//    [SERVER_NAME] => test.cirkbilet.ru
//    [SERVER_ADDR] => 127.0.0.1
//    [SERVER_PORT] => 80
//    [REMOTE_ADDR] => 109.61.152.69
//    [DOCUMENT_ROOT] => /home/c/cirkbilet/bolshoybilet.ru/public_html
//    [SERVER_ADMIN] => support@timeweb.ru
//    [SCRIPT_FILENAME] => /home/c/cirkbilet/bolshoybilet.ru/public_html/index.php
//    [REMOTE_PORT] => 35406
//    [GATEWAY_INTERFACE] => CGI/1.1
//    [SERVER_PROTOCOL] => HTTP/1.0
//    [REQUEST_METHOD] => GET
//    [QUERY_STRING] => abd=123
//    [REQUEST_URI] => /?abd=123
//    [SCRIPT_NAME] => /index.php
//    [PHP_SELF] => /index.php
//    [REQUEST_TIME_FLOAT] => 1462959896.579
//    [REQUEST_TIME] => 1462959896
//    [argv] => Array
//        (
//            [0] => abd=123
//        )
//
//    [argc] => 1
//)
    
    /**
     * Get Images from image directory
     * @param array||string $folder
     * @param bool $rnd
     * @param int $count
     * @param array||string||null $links Если NULL то тогда $links будет взят самими картинками
     * @param array||string $titles
     * @param array||string $texts 
     * @return array list 
     */
    public static function getImages($folders='', $rnd = FALSE,$count=12,$links=[],$titles=[],$texts=[]){
        jimport( 'joomla.filesystem.folder' );
//        if(empty($folder))
//            $folder = '//images';
//        elseif($folder[0] != '/')
//            $folder = '/'.$folder;
        //JUri::base();
//        toPrint($folder,'$folder');
        $folders = (array)$folders;
        
        if($folders==$links)
            $links = NULL;
        if(is_string($links))
            $links = (array)static::split ($links);
        if(is_string($titles))
            $titles = (array)static::split ($titles);
        if(is_string($texts))
            $texts = (array)static::split ($texts);
        
//toPrint($folders,'$folders',0);  
//
//info@orelmusizo.ru
//!nf0mus1zO
        
        
        $items = [];
        foreach ($folders as $folder){
            $files = JFolder::files(JPATH_SITE.$folder, '\.jpg|\.jpeg|\.JPG|\.JPEG|\.png|\.PNG|\.apng|\.APNG|\.gif|\.GIF|\.WEBP|\.webp|\.HEIF|\.heif|\.HEIC|\.heic|\.AVIF|\.avif$'); 
            foreach ($files as $i => $file){
                $items[$file] = (object)[
                    'image'=>$folder.'/'.$file,
                    'link'=>$links[$i]??'',
                    'title'=>$titles[$i]??$file,
                    'text'=>isset($texts[$i])?"<p>$texts[$i]</p>":'',
                    'content'=>$texts[$i]??'',
                    'moduleclass_sfx'=>'img_file',
                    'header_class'=>'img_title',
                    'text'=>$texts[$i]??'',
                    'text'=>$texts[$i]??'',
                    'id'=>$i,
                    'type'=>'images',
                    'module_tag'=>'div'
                ];
            }
        }
        
//            toPrint($files,'$files',0); 
        if($rnd == 'rnd')
            $items = self::array_shuffle_assoc($items);
        if($count)
            $items = array_slice ($items, 0, $count, TRUE);
        
        if(is_null($links)){
            foreach ($items as &$item){
                $item->link = $item->image;
            }
        }
        
//        toPrint($items,'Images',0);
        
        return $items;
    }
    /**
     * Get Articles from array IDs or one ID.
     * @param array|int $articles_id
     * @param string $article_mode
     * @return array list 
     */
    public static function getArticles($articles_id = [],$categorys_id = [],$article_mode='full', $mod_id = 0){//full,intro,content
        $where = '';
        
        if(!is_array($articles_id))
            $articles_id = array($articles_id);
        $articles_id = array_diff($articles_id, ['']);
        if($articles_id && $articles_id[0])
            $where .= "AND av.id IN (".join(",",$articles_id).")";
        
        
        if(!is_array($categorys_id))
            $categorys_id = array($categorys_id);
        $categorys_id = array_diff($categorys_id, ['']);
        if($categorys_id && $categorys_id[0])
            $where .= "AND av.catid IN (".join(",",$categorys_id).")";
        

//SELECT id, asset_id, title,introtext,	fulltext,  access, language, ordering, state,attribs FROM av68r_content WHERE  state = 1 AND id IN (56)
        $query = "SELECT id, asset_id, title, alias, introtext,	`fulltext`, access, language,images, ordering, state, attribs, catid, urls "
                . "FROM #__content av"
                . "WHERE   av.state = 1 "
                . "$where ; ";
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

    a.id , a.catid, a.featured,a.state,a.ordering, a.version,a.title, a.checked_out, a.checked_out_time,  a.images,a.attribs,a.urls, a.introtext,a.fulltext,a.alias,a.access, a.language,
    f.id f_id, f.group_id f_group_id,  f.state f_state, f.required f_required, 
    f.name f_name,  f.title f_title, f.label f_label, 
    IF(LOCATE('\"showlabel\":', f.params, 1), TRIM('}' FROM TRIM(',' FROM TRIM('\"' FROM SUBSTRING(f.params, LOCATE('\"showlabel\":', f.params, 1)+12,2)))),'')  f_showlabel, 
    IF(LOCATE('\"label_render_class\":\"', f.params, 1), SUBSTRING_INDEX(SUBSTRING(f.params, LOCATE('\"label_render_class\":\"', f.params, 1)+22),'\"',1),'')  f_label_render_class, 
    f.ordering f_ordering,
/*--    f.context f_context, */
/*--    f.default_value f_default_value,     v.value f_value,*/
    IF(v.value!='',v.value,f.default_value) f_val, IF(v.value,0,1) f_def
FROM #__content a 
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
ORDER BY av.ordering
; ";
//        toPrint($query,'$query');       
//        return ;;
        
        
//        if($modules_ordering)
//            $query .= "ORDER BY ordering ";
        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');
        foreach ($items as $id => &$art){
            if($article_mode == 'full')
                $items[$id]->content = $art->introtext.$art->fulltext;
            if($article_mode == 'intro')
                $items[$id]->content = $art->introtext;
            if($article_mode == 'content')
                $items[$id]->content = $art->fulltext;
            if($art->f_content)
                $items[$id]->fields = "<ul class=\"fields\">$art->f_content</ul>";
            
            $params = new JRegistry($items[$id]->f_params);
            $items[$id]->params = $params->toObject();
            
            $items[$id]->module_tag = 'div';
            $items[$id]->moduleclass_sfx = "article $art->id";
            jimport( 'joomla.registry.registry' );
            $images = new JRegistry($items[$id]->images);
            $items[$id]->images = $images->toObject();
            $items[$id]->image = $items[$id]->images->image_intro ?? $items[$id]->images->image_fulltext; 
            $attribs = new JRegistry($items[$id]->attribs);
            $items[$id]->attribs = $attribs->toObject();
            $urls = new JRegistry($items[$id]->urls);
            $items[$id]->urls = $urls->toObject();
            $items[$id]->type = 'article';
            JHtml::addIncludePath(JPATH_ROOT . '/components/com_content/helpers');  
//          require_once JPATH_BASE . '/components/com_content/helpers/route.php';
            require_once JPATH_BASE . '/components/com_content/src/Helper/RouteHelper.php';
			//  
            
            //$language !== '*' && Multilanguage::isEnabled();
            
				// We know that user has the privilege to view the article
				//$art->link = Route::_(RouteHelper::getArticleRoute($art->slug, $art->catid, $art->language));
            //Joomla\Component\Content\Site\Helper\RouteHelper::getArticleRoute($id);// J4 ContentHelper
            //$newUrl = JRoute::_(ContentHelperRoute::getArticleRoute($import->id.':'.$import->alias, $import->catid));
            //(new CMS\Helper\RouteHelper)->getRoute($id, $query); // J4 CMS Helper
//            $base = '';//JUri::base();
//            $path_uri = JUri::getInstance($index_path);
//            $path_uri = JUri::buildQuery(['option'=>'com_content','view'=>'article','id'=>'60','catid'=>'8','Itemid'=>'859','language'=>$art->language]);
//            $path_uri = JUri::buildQuery(['option'=>'com_content','view'=>'article','id'=>'1','catid'=>'8']);
            
            //$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
//            $items[$id]->lnk[00] = JRoute::link("site", $base.ContentHelperRoute::getArticleRoute($art->id.':'.$art->alias, $art->catid));
//            $index = ContentHelperRoute::getArticleRoute($id, $art->catid,$art->language);
//            $items[$id]->lnk[1] = (string)$index;
//            $items[$id]->lnk[2] = (string)JRoute::_($base.$index);
//            $items[$id]->lnk[3] = (string)JUri::getInstance($base.$index);
//            $items[$id]->lnk[400] = (string)JUri::getInstance(JRoute::_($base.$index));
//            
//            $index2 = ContentHelperRoute::getArticleRoute($id);
//            $items[$id]->lnk[20] = (string)$index2;
//            $items[$id]->lnk[21] = (string)JRoute::_($index2);
//            $items[$id]->lnk[22] = (string)JUri::getInstance($index2);
//            $items[$id]->lnk[23] = JUri::getInstance(JRoute::_($index2));
//            //JRoute::_(ContentHelperRoute::getArticleRoute($art->slug, $art->catid, $art->language));
//            //http://joomla4.ru/?view=article&id=7:current-my-event&catid=9 
//            foreach ($items[$id]->lnk as &$lnk){
//                $lnk = "<a href='$lnk' style='color:white'>$lnk</a>";
//            }
//              $art->displayCategoryLink  = Route::_(RouteHelper::getCategoryRoute($art->catid, $art->category_language));
//            	$art->introtext = JHtml::_('content.prepare', $art->introtext, '', 'mod_articles_category.content');
//				$art->introtext = self::_cleanIntrotext($art->introtext);
//toPrint($items[$id]->lnk,'lnk ',0,$categorys_id[0]==8 && $art->id == 60);

//            $items[$id]->Xlink = $path_uri2;//ContentHelperRoute::getArticleRoute($id, $art->catid);
        
//if(toPrint()):
//    echo "<pre style='background-color: gray;'>mod:$mod_id - <b>Art:<span style='font-size: 1.2rem;font-wheight: 800;'>$id</span></b> - <b>Cat:<span style='font-size: 1.2rem;font-wheight: 800;'>$art->catid</span></b> ________  <b style='font-size: 1.2rem;font-wheight: 800;'>$art->title</b>  ________ ";
//    echo print_r($items[$id]->lnk,true);
//    echo "</pre>";
//endif;

//			$items[$id]->link = \Joomla\Component\Content\Site\Helper\RouteHelper::getArticleRoute($id, $art->catid);
            $items[$id]->link = ContentHelperRoute::getArticleRoute($id, $art->catid); 
			
//                    toPrint($item);
		// Register FieldsHelper
//		JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
//		JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
            //$fields = FieldsHelper::getFields('com_content.article', $item, true);
            
//		JPluginHelper::importPlugin('system');
//                JPluginHelper::importPlugin('content');
		
            //JEventDispatcher::getInstance()->trigger('onContentAfterSave', array('com_content.article', &$item, true));//, $fieldsData
//            JEventDispatcher::getInstance()->trigger('onContentAfterSave', array('com_content.article', &$this->table, false, $fieldsData));
//            \JFactory::getApplication()->triggerEvent('onContentPrepare', array('com_content.article' , &$item, &$items[$id]->params));//, &$art->params
//            JEventDispatcher::getInstance()->trigger('onContentBeforeDisplay',array('com_content.article' , &$item, &$items[$id]->params));
        }
        return $items;
    }
    /**
     * Get Articles from array IDs or one ID.
     * @param string $query 
     * @return array list 
     */
    public static function getSelects($query = ''){//full,intro,content
         
        
//        if($modules_ordering)
//            $query .= "ORDER BY ordering ";
        $items = JFactory::getDBO()->setQuery((string)$query)->loadObjectList();
//        toPrint($query,'$query');
//        toPrint($items,'$items',0);
//        
//        return [];
        
        foreach ($items as $key => &$item){
            if($item->type):
                if($item->type=='article'){
                    jimport( 'joomla.registry.registry' );
                    $items[$key]->module_tag = 'div';
                    $items[$key]->moduleclass_sfx = "article id$item->id";
            
                    
                    if(empty($items[$key]->image) && $items[$key]->images){
                        $images = new JRegistry($items[$key]->images);
                        $items[$key]->images = $images->toObject();
                        $items[$key]->image = $items[$key]->images->image_intro ?? $items[$key]->images->image_fulltext; 
                    }
                    
                    
                    if(empty($items[$key]->attribs)){
                        $attribs = new JRegistry($items[$key]->attribs);
                        $items[$key]->attribs = $attribs->toObject();
                    }
                    
                    if(empty($items[$key]->urls) && $items[$key]->urls){
                        $urls = new JRegistry($items[$key]->urls);
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
                    
                    $params = new JRegistry($items[$key]->params);
                    $items[$key]->params = $params->toObject();
                    
                    if(empty($items[$key]->content) && $items[$key]->description)
                        $items[$key]->content = $items[$key]->description;
                    
                    if(empty($items[$key]->image) && $items[$key]->params)
                        $items[$key]->image = $items[$key]->params->image;
                    if(empty($items[$key]->link))
                        $items[$key]->link = JRoute::_(ContentHelperRoute::getCategoryRoute($item->id, ($item->language??0))); //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<---------
                }
                
                
                
            endif;


//                    toPrint($item);
		// Register FieldsHelper
//		JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
//		JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
            //$fields = FieldsHelper::getFields('com_content.article', $item, true);
            
//		JPluginHelper::importPlugin('system');
//                JPluginHelper::importPlugin('content');
		
            //JEventDispatcher::getInstance()->trigger('onContentAfterSave', array('com_content.article', &$item, true));//, $fieldsData
//            JEventDispatcher::getInstance()->trigger('onContentAfterSave', array('com_content.article', &$this->table, false, $fieldsData));
//            \JFactory::getApplication()->triggerEvent('onContentPrepare', array('com_content.article' , &$item, &$items[$key]->params));//, &$item->params
//            JEventDispatcher::getInstance()->trigger('onContentBeforeDisplay',array('com_content.article' , &$item, &$items[$key]->params));
        }
        return $items;
    }
    
    /**
     * Список категорий по  ID - //В разработке все ещё
     * @param int $catid 
     * @return array list 
     */
    public static function getCategories($catid = NULL){//full,intro,content
         
         $items = [];
         $query = "
SELECT id, parent_id, lft, rgt, level, path, title, alias, description, published, params, description, language
FROM #__categories 
WHERE access AND published
ORDER BY lft LIMIT 300; ";
        
//        if($modules_ordering)
//            $query .= "ORDER BY ordering ";
//        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');
//        
//        foreach ($items as $key => &$item){
//            if($item->type):
//                if($item->type=='article'){
//                    jimport( 'joomla.registry.registry' );
//                    $items[$key]->module_tag = 'div';
//                    $items[$key]->moduleclass_sfx = "article id$item->id";
//            
//                    $images = new JRegistry($items[$key]->images);
//                    $items[$key]->images = $images->toObject();
//                    $items[$key]->image = $items[$key]->images->image_intro ?? $items[$key]->images->image_fulltext; 
//                    
//                    $attribs = new JRegistry($items[$key]->attribs);
//                    $items[$key]->attribs = $attribs->toObject();
//                    
//                    $urls = new JRegistry($items[$key]->urls);
//                    $items[$key]->urls = $urls->toObject();
//                    $items[$key]->type = 'article';
//            
//                    require_once JPATH_BASE . '/components/com_content/helpers/route.php';
//                    $items[$key]->link = JRoute::_(ContentHelperRoute::getArticleRoute($key, $item->catid)); 
//                    
//                    //$items[$key]->link = JRoute::_(ContentHelperRoute::getCategoryRoute($catid, $language)); //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<---------
//                }
//                
//                https://cdnjs.cloudflare.com/ajax/libs/three.js/r68/three.min.js
//                
//            endif;


//                    toPrint($item);
		// Register FieldsHelper
//		JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
//		JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
            //$fields = FieldsHelper::getFields('com_content.article', $item, true);
            
//		JPluginHelper::importPlugin('system');
//                JPluginHelper::importPlugin('content');
		
            //JEventDispatcher::getInstance()->trigger('onContentAfterSave', array('com_content.article', &$item, true));//, $fieldsData
//            JEventDispatcher::getInstance()->trigger('onContentAfterSave', array('com_content.article', &$this->table, false, $fieldsData));
//            \JFactory::getApplication()->triggerEvent('onContentPrepare', array('com_content.article' , &$item, &$items[$key]->params));//, &$item->params
//            JEventDispatcher::getInstance()->trigger('onContentBeforeDisplay',array('com_content.article' , &$item, &$items[$key]->params));
//        }
        return $items;
    }
    
    public static function getTags($show = 'list', $catids = [], $parents = [], $maximum = 50, $order = 'title ASC', $count=true, $category_title = false, $Itemid = 0) {//
		
		
		$db         = JFactory::getDbo();
		$nowDate    = JFactory::getDate()->toSql();
		$nullDate   = $db->getNullDate();
		
		$user       = JFactory::getUser();
		$groups     = $user->getAuthorisedGroups();
		$levels		= $user->getAuthorisedViewLevels();
		
		$groups		= implode(',', $groups);
		$groupsIn	= '0,'.$groups;
		
//toPrint($levels,'$levels',0,true);
		
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
		
//toPrint($groups,'$groups',0,true);
//toPrint($query,'$query',0,true);
		
		try
		{
//toPrint($query,'$query',0,true);
//return [];
			$db->setQuery($query);
			$list = $db->loadObjectList();
//toPrint($list,'$list',0,true);

		}
		catch (\RuntimeException $e)
		{
			$list = array();
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}
		
		
		$Itemid = $Itemid ? "&Itemid=$Itemid" : '' ;
		
		foreach ($list as &$tag)
		{
//toPrint($tag,'$tag',0,true);
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
				// Ключи родителей
				$parent_keys = array_keys($tag_ids, $tag->parent_id);
				if($parent_keys){
					// Категории родителей
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
//		if(isset($list[$tag->parent_id])){
//			$list[$tag->parent_id]->items[] = $tag;//$tag->tag_id
//			$tag->parent = $list[$tag->parent_id];
//		}else{
//			$parents[] = $tag;//$tag->tag_id
//			$tag->parent = null;
//		}
		}
			
		return $parents;
	}
    
    public static function getModulesFromPosition($positions,$modules_ordering,$current_id = 0,$current_position = '',$chromestyle='',$content_tag3=''){
//return [];          
        foreach ($positions as $key => $pos)
            $positions[$key] = trim($pos);
        $where = join("','",$positions);
        
        $tag = JFactory::getLanguage()->getTag();
		
        
//SELECT id, asset_id, title, published, module, access, showtitle, params, language, ordering, position, content FROM av68r_modules WHERE client_id=0 AND published = 1 AND module!='mod_multi' AND position IN (position-1,position-7) ORDER BY position, ordering
        $query = "SELECT id, asset_id, title, published, module, access, showtitle, params, language, ordering, position, content "
                . "FROM #__modules "
                . "WHERE client_id=0 AND published = 1 /*AND module!='mod_multi'*/ "
                . "AND position IN ('$where') "
                . "AND language IN ('$tag','*') ";
        if($current_id)
            $query .= "AND id!='$current_id' ";
        if($current_position)
            $query .= "AND !(position='$current_position' AND `module`='mod_multi') "; //"AND position!='$current_position' " 
        $query .= "ORDER BY $modules_ordering ordering ";
//if($current_id == 311){        
//toPrint($query,'$query',0);
//toPrint($positions,'$positions',0);
//return [];
//}
//309  296   258   299   300  
//return;
//toPrint($positions,'$positions',0);
//return [];
        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');
//foreach($items as $m)
//    toPrint($m->ordering,'$m->ordering '.$m->id.' ---'.$m->title,0,$current_id == 296);
        //echo $query;
//        echo $query; 
//        echo $query.count($items).'+++';
//echo ''.implode(',', $positions);
//return [];
//toPrint($items,'$chromestyle',0);
//return;        
        if(in_array($content_tag3, ['','0',0,NULL])){
            $chromestyle = 'System-none'; 
            return self::getModules($items,$chromestyle,$current_id);
        }
        if($chromestyle != 'System-none'){
//toPrint('getModulesLegacy()');
            return self::getModulesLegacy($items,$chromestyle,$current_id); 
        }
        if($chromestyle == 'System-none'){
//toPrint('getModules()-'.count($items));
            return self::getModules($items, $chromestyle,$current_id);
        }
    }
    public static function getModulesFromSelected($modulesID,$modules_ordering,$current_id = 0,$current_position = '',$chromestyle='',$content_tag3=''){
        
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
            $query .= "AND !(position='$current_position' AND `module`='mod_multi') "; //"AND position!='$current_position' " 
        $query .= "ORDER BY $modules_ordering ordering ";
        
        $query .= " ; ";
        
//toPrint($query,'$query',0,$current_id == 191); //return [];
        
        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');

	//	echo "<pre> ** ".print_r($query,true)."++</pre>";    
	//	echo "<pre> ** $type_module - pos $current_position - ids ".join(",",$modulesID)."++</pre>"; 
	//	echo "<pre> ** ".print_r($items[149],true)."++</pre>"; 
	
//toPrint($chromestyle,'$chromestyle-'.$current_id,0, $current_id == 194);
//toPrint(array_keys($items),'$chromestyle-'.$current_id,0, $current_id == 191);
//toPrint($current_id,'$current_id',0);
//            toPrint($chromestyle,'$chromestyle');
        if(in_array($content_tag3, ['','0',0,NULL]) && $chromestyle){
            $chromestyle = 'System-none'; 
//            echo "One $content_tag3+$chromestyle";
            return self::getModules($items,$chromestyle,$current_id);
        }
        if($chromestyle != 'System-none' || empty($chromestyle)){
//            echo "Three $content_tag3+$chromestyle";
//toPrint('getModulesLegacy() - getModulesFromSelected');
            return self::getModulesLegacy($items,$chromestyle,$current_id);
        }
        if($chromestyle == 'System-none'){
//toPrint('getModules() - getModulesFromSelected');
//            echo "Two $content_tag3+$chromestyle";
            return self::getModules($items,$chromestyle,$current_id);
        }
    }
    
    public static function getModules($multi_items, $chromestyle = '', $parentId=0){ 
//        /echo "<pre> **".print_r(array_keys( $items),true). "++</pre>"; 

        foreach ($multi_items as $multi_module_id => $module){
            //$multi_items[$key]->module = $module->module;
            //
            //if(!isset($module->module))            {echo "<pre>$multi_module_id: ".print_r($module,true). "</pre>";continue;}
            
            $file = JPATH_SITE."/modules/$module->module/$module->module.php";
            $content = "";
        //    echo $file."<br>";//149,142,146
			//echo "<pre> ** ".print_r($multi_items,true)."++</pre>"; 
            //$b = $params->get('base');
                 
            if(file_exists($file)){
                //$multi_items[$key]->params = unserialize  ($module->params);
//                $module->params = json_decode ($module->params);
                //echo "<pre> **".print_r( $module,true). "++</pre>"; 
                //var_dump($module->params);
                //echo "<br>".$file."<br>". $module->params."<br> ";
//                $params = new JObject();
//                $params->setProperties($module->params);
//                $module->params = $params;
                
                $params = $module->params = new \Joomla\Registry\Registry($module->params);
                
		$module->style = $module->params->get('style','');
//                toPrint($module->params,'$module->params '.$module->style);
                //$reg->loadObject($module->params);
                if($chromestyle)
                    $module->params->set('style',$chromestyle);
             				
				$module->parentId = $parentId;
				$module->style = $module->params->get('style','');
				$module->moduleclass_sfx = $module->params->get('moduleclass_sfx','');
				$module->module_tag = $module->params->get('module_tag','');
				$module->header_tag = $module->params->get('header_tag','');
				$module->header_class = $module->params->get('header_class',''); 
 
                $image = $module->params->get('backgroundimage','');
                $image = $module->params->get('image', $image); 
                
//toPrint($module->id,'$ID',0);
//if($image)
//toPrint($image,'$image'.$module->id,0);
//continue; 
            //if($module->id==147)
                $multi_items[$multi_module_id]->image = $image;
//continue;
                $module->params->set('image', $image);

//                if($module->id==107){
//                    echo "<pre>".print_r($module, TRUE)."</pre>";
//                $multi_items[$multi_module_id]->image = $module->params->get('backgroundimage',FALSE);
// //$multi_items[$multi_module_id]->image = 'XX';
//                }
//toPrint($multi_items[$multi_module_id],'$image'.$module->id,0);
//toPrint($module,'$module',0);
//if($module->id==147)
//return [];  
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
                
                
				$app = JFactory::getApplication();
//toPrint($module,'$module',0); 
			
		//if ($multi_module_id == 144 || $multi_module_id == 145)echo "<pre> **".print_r( $module,true). "++</pre>"; 
	
                JFactory::getLanguage()->load($module->module);
			
                $content = '';
                ob_start();
//          toPrint($file);      
//return [];					//echo $file."<br>" ;//149,142,146			
                require $file;
                    
                $multi_items[$multi_module_id]->content = ob_get_clean().$content;
                
//toPrint(strlen($multi_items[$multi_module_id]->content)," ------ - - Lang:<b><u>$module->language</u></b> -Type:<b><u>$module->module</u></b> ID:<b><u>$multi_module_id</u></b> -Length:");
            
                if(empty($module->published) || empty($multi_items[$multi_module_id]->content))
                    unset($multi_items[$multi_module_id]);
				//$multi_items[$multi_module_id]->id = $multi_module_id;
                //$module->content = $content;
            }
        }
        return $multi_items;
    }
    
    public static function getModulesLegacy($multi_items, $chromestyle = '', $parentId=0){ //'System-none'
            //toPrint($multi_items,'$multi_items');
//toPrint($module,'$module',0); 
//return [];           
        foreach ($multi_items as $multi_module_id => &$module){
//toPrint($module,'$module',0);
//continue;
//return [];            
            $module->params =  new \Joomla\Registry\Registry($module->params);  
            if($chromestyle && $chromestyle != '0'){ 
                $module->params->set('style',$chromestyle);  
            }
            $module->image = $module->params->get('backgroundimage',FALSE);
            $module->image = $module->params->get('image',$module->image);
            
//        echo '<pre style"color: green;">'.print_r($module,true).'</pre>';
//toPrint($module,'$module',0); 
//continue;
//return  [];   
			$app = JFactory::getApplication();
            
            $module->content = \Joomla\CMS\Helper\ModuleHelper::renderModule($module);//JModuleHelper
            $module->type = 'modules';
            
            if(empty($module->published))
                unset($multi_items[$multi_module_id]);
             
        }
        return $multi_items;
    }
    
    
    /**
     * Split string 
     * @param type $string String spliting
     * @param array $separators Char(chars) separator
     * @return array Array items
     */
    public static function split($string = '', array $separators = ['|']){
        if(empty($string))
            return [];
        if(empty($separators))
            $separators = ['|'];
        $sep = reset($separators);
        
        $string = str_replace(['\n','\r','\t'], '', $string);
        
        $string = str_replace($separators, $sep, $string);
        
        $items = explode($sep, $string);
        
        foreach ($items as $k => &$item){
            $item = trim($item);
            if(empty($item))
                unset($items[$k]);
        }
//        $items = array_diff($items, ['']);
        return $items;
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

		// First get the plain text string. This is the rendered text we want to end up with.
		$ptString = JHtml::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = false);

		for ($maxLength; $maxLength < $baseLength;)
		{
			// Now get the string if we allow html.
			$htmlString = JHtml::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = true);

			// Now get the plain text from the html string.
			$htmlStringToPtString = JHtml::_('string.truncate', $htmlString, $maxLength, $noSplit = true, $allowHtml = false);

			// If the new plain text string matches the original plain text string we are done.
			if ($ptString === $htmlStringToPtString)
			{
				return $htmlString;
			}

			// Get the number of html tag characters in the first $maxlength characters
			$diffLength = \strlen($ptString) - \strlen($htmlStringToPtString);

			// Set new $maxlength that adjusts for the html tags
			$maxLength += $diffLength;

			if ($baseLength <= $maxLength || $diffLength <= 0)
			{
				return $htmlString;
			}
		}

		return $html;
	}
}



abstract class JModHelp extends Joomla\CMS\Helper\ModuleHelper{
    static function &ModeuleDelete($module){
        $modules = &static::load();
        foreach ($modules as $i => &$mod){
            if($mod->id == $module->id){
                unset ($modules[$i]); 
                unset ($mod);
            }
        }
        $modules = &static::getModules($module->position); 
        
        $module->published = FALSE; 
        $module->position = FALSE; 
        $module->module = FALSE; 
        $module->style = 'System-none';//System-none
        return $modules;
    }
}