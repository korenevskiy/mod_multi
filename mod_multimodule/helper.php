<?php
/*------------------------------------------------------------------------
# mod_newscalendar - News Calendar
# ------------------------------------------------------------------------
# author    Jesús Vargas Garita
# Copyright (C) 2010 www.joomlahill.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomlahill.com
# Technical Support:  Forum - http://www.joomlahill.com/forum
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

//require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
//require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');

//require_once JPATH_SITE.'/components/com_content/helpers/route.php';

//jimport('joomla.application.component.model');

//JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

abstract class modMultiModuleHelper //modProdCalendarHelper
{ 
    public static $params;
    

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
            $item = JHTML::_('content.prepare', $item);  
//if($debag)  
//    toPrint($item,'modMultiModuleHelper');
            return $item;
        }
//    if($type_prepare == 'onContentPrepare')
//        return JHTML::_('content.prepare', $item,$param,$context); 
//    $item = (object)['text'=>&$item,'X'=>$params->get('id')]; 
        $item = JEventDispatcher::getInstance()->trigger($type_prepare, array($context,  $item,  $params, 0));
        return $item;
    }





        public static function getMenuLink($idItemMenu)
    {
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
                    
//                    (object)['image'=>DS.'images'.DS.$folder.DS.$file,'link'=>$links[$i]??'','title'=>$titles[$i]??'','text'=>$texts[$i]?"<p>$texts[$i]</p>":'',
//                'content'=>$texts[$i]??'','moduleclass_sfx'=>'img_file','header_class'=>'img_title','text'=>$texts[$i]??'','text'=>$texts[$i]??'',
//                'id'=>$i];
        }
        return $items;
    }

    public static function inArray($word, $full_string, $trim_mask=" ")
    {
        $word= trim(trim($word),$trim_mask); 
        $full_string = str_replace([','], ';', $full_string); 
        $strings = explode(';', $full_string);
        foreach ($strings as $k=>$str) 
            $strings[$k] = trim(trim($str),$trim_mask);
        
        $x =  in_array($word,$strings); 
        
//        echo '<pre style"color: green;">'.print_r($word,true).'</pre>';
//        echo '<pre style"color: green;">'.print_r($strings,true).'</pre>';
        //if(!$x)        throw new Exception($str.' '.$full_string.' '.$trim_mask);
        return in_array($word,$strings);
    }
    
    public static function fontsFiles($files)
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
            
            $style = " @font-face { font-family: '$filename' ;\n";
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
    public static function fontsGoogle($fonts)
    { //  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine"> 
//        <link href='http://fonts.googleapis.com/css?family=ПРОБЛЕМНЫЙ+ШРИФТ&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
//        <link href='http://fonts.googleapis.com/css?family=ПРОБЛЕМНЫЙ+ШРИФТ&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>        
/////////////https://developers.google.com/fonts/docs/getting_started
        $fonts =(array) $fonts;
//        if($fonts){
//            $fonts = str_replace([',','|',';',':',' '], '/', $fonts);
//            $fonts = explode('/', $fonts);
//        }
        foreach ($fonts as $font)
            JHtml::stylesheet("//fonts.googleapis.com/css?family=".trim($font, " /\\"));//JUri::base().
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
     * @param array $params Parameters / Параметры
     * @return bool результат проверки показов 
     */
    public static function reqruireWork(&$params)
    { 
        $work_type_require = $params->get('work_type_require', '');// and  or 
        
        if(empty ($work_type_require) || $work_type_require == 'all')
            return TRUE;
        
        
//        if($params->get('description') && $params->get('description_show'))
//            return TRUE;

        
        
        //throw new Exception($web_site_is);
        //echo '<pre style"color: green;">'.print_r($_SERVER,true).'</pre>';
        if($work_type_require == 'and'){
            
            
            #5 Require for Main Page
            $mainpage_is = $params->get('mainpage_is', '');//off, only , without
            if($mainpage_is):
                $mainpage_home = JFactory::getApplication()->getMenu()->getActive()->home; 
                if($mainpage_is == 'only' && !$mainpage_home){
                    return FALSE; 
                }
                else if($mainpage_is == 'without' && $mainpage_home){
                    return FALSE;
                }
            endif;
            
            #6 Require for Mobile device 
            $mobile_is = $params->get('mobile_is', '');//off, only , without
            if($mobile_is):
                $is_mobile = static::is_mobile_device(); 
                if($mobile_is == 'only' && !$is_mobile){
                    return FALSE; 
                }
                else if($mobile_is == 'without' && $is_mobile){
                    return FALSE;
                }
            endif;
            
            $res = TRUE;
            
            if(file_exists(__DIR__.DS.'element'.DS.'use.php'))
                $res = require __DIR__.DS.'element'.DS.'use.php';
            
//            toPrint($res,'$resAND');
            
            return $res; 
            return TRUE; 
        }
        else{
            
            
            #5 Require for Main Page
            $mainpage_is = $params->get('mainpage_is', '');//off, only , without
            if($mainpage_is):
                $mainpage_home = JFactory::getApplication()->getMenu()->getActive()->home; 
                if($mainpage_is == 'only' && $mainpage_home){
                    return TRUE; 
                }
                elseif($mainpage_is == 'without' && !$mainpage_home){
                    return TRUE;
                }
            endif;
            
            
            #6 Require for Mobile device 
            $mobile_is = $params->get('mobile_is', '');//off, only , without
            if($mobile_is):
                $is_mobile = static::is_mobile_device(); 
                if($mobile_is == 'only' && $is_mobile){
                    return TRUE; 
                }
                else if($mobile_is == 'without' && !$is_mobile){
                    return TRUE;
                }
            endif;
            
            
            
            $res = FALSE;
            
            
            if(file_exists(__DIR__.DS.'element'.DS.'use.php'))
                $res = require __DIR__.DS.'element'.DS.'use.php';
            
//            toPrint($res,'$resOR');
            
            return $res; 
            return FALSE;
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
    }
    
    /**
     * Get Images from image directory
     * @param string $folder
     * @param bool $rnd
     * @param int $count
     * @param array||string $links
     * @param array||string $titles
     * @param array||string $texts 
     * @return array list 
     */
    public static function getImages($folder='', $rnd = FALSE,$count=12,$links=[],$titles=[],$texts=[]){
        jimport( 'joomla.filesystem.folder' );
//        if(empty($folder))
//            $folder = DS .'/images';
//        elseif($folder[0] != DS)
//            $folder = DS.$folder;
        //JUri::base();
//        toPrint($folder,'$folder');
        if(is_string($links))
            $links = (array)static::split ($links);
        if(is_string($titles))
            $titles = (array)static::split ($titles);
        if(is_string($texts))
            $texts = (array)static::split ($texts);
        $files = JFolder::files(JPATH_BASE.DS.'images'.DS.$folder, '\.jpg|\.jpeg|\.JPG|\.JPEG|\.png|\.PNG|\.apng|\.APNG|\.gif|\.GIF|\.WEBP|\.webp$');
        if($rnd)
            shuffle($files);
        if($count)
            $files = array_slice ($files, 0, $count);
//        toPrint($folder,'$folder');
//        toPrint($files,'$files');
        $items = [];
        foreach ($files as $i => $file){
            $items[$file] = (object)['image'=>DS.'images'.DS.$folder.DS.$file,'link'=>$links[$i]??'','title'=>$titles[$i]??'','text'=>$texts[$i]?"<p>$texts[$i]</p>":'',
                'content'=>$texts[$i]??'','moduleclass_sfx'=>'img_file','header_class'=>'img_title','text'=>$texts[$i]??'','text'=>$texts[$i]??'',
                'id'=>$i,'type'=>'images'];
        }
        return $items;
    }
    /**
     * Get Articles from array IDs or one ID.
     * @param array|int $articles_id
     * @param string $article_mode
     * @return array list 
     */
    public static function getArticles($articles_id = [],$categorys_id = [],$article_mode='full'){//full,intro,content
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

    a.id , a.catid, a.featured,a.state,a.ordering, a.version,a.title, a.checked_out, a.checked_out_time,  a.images,a.attribs,a.urls, a.introtext,a.fulltext,a.alias,a.access,
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
        foreach ($items as $key => &$item){
            if($article_mode == 'full')
                $items[$key]->content = $item->introtext.$item->fulltext;
            if($article_mode == 'intro')
                $items[$key]->content = $item->introtext;
            if($article_mode == 'content')
                $items[$key]->content = $item->fulltext;
            if($item->f_content)
                $items[$key]->fields = "<ul class=\"fields\">$item->f_content</ul>";
            
            $params = new JRegistry($items[$key]->f_params);
            $items[$key]->params = $params->toObject();
            
            $items[$key]->module_tag = 'div';
            $items[$key]->moduleclass_sfx = "article id$item->id";
            jimport( 'joomla.registry.registry' );
            $images = new JRegistry($items[$key]->images);
            $items[$key]->images = $images->toObject();
            $items[$key]->image = $items[$key]->images->image_intro ?? $items[$key]->images->image_fulltext; 
            $attribs = new JRegistry($items[$key]->attribs);
            $items[$key]->attribs = $attribs->toObject();
            $urls = new JRegistry($items[$key]->urls);
            $items[$key]->urls = $urls->toObject();
            $items[$key]->type = 'article';
            //JParameter
            require_once JPATH_BASE . '/components/com_content/helpers/route.php';
            $items[$key]->link = JRoute::_(ContentHelperRoute::getArticleRoute($key, $item->catid)); 
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
    public static function getModulesFromPosition($positions,$modules_ordering,$current_id = 0,$current_position = '',$chromestyle=''){
                
        foreach ($positions as $key => $pos)
            $positions[$key] = trim($pos);
        $where = join("','",$positions);
        
//SELECT id, asset_id, title, published, module, access, showtitle, params, language, ordering, position, content FROM av68r_modules WHERE client_id=0 AND published = 1 AND module!='mod_multimodule' AND position IN (position-1,position-7) ORDER BY position, ordering
        $query = "SELECT id, asset_id, title, published, module, access, showtitle, params, language, ordering, position, content "
                . "FROM #__modules "
                . "WHERE client_id=0 AND published = 1 /*AND module!='mod_multimodule'*/ "
                . "AND position IN ('$where')";
        if($current_id)
            $query .= "AND id!='$current_id' ";
        if($current_position)
            $query .= "AND position!='$current_position' ";
        if($modules_ordering)
            $query .= "ORDER BY ordering ";
        else 
            $query .= "ORDER BY position, ordering ";
        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');
        //echo $query;
//        echo $query; 
//        echo $query.count($items).'+++';
        
//        toPrint($chromestyle,'$chromestyle');
        
        if($chromestyle != 'System-none'){
            return self::getModulesLegacy($items,$chromestyle); 
        }
        else{
            return self::getModules($items);            
        }
    }
    public static function getModulesFromSelected($modulesID,$modules_ordering,$current_id = 0,$current_position = '',$chromestyle=''){
        
        $where = join(",",$modulesID);
        
        $query = "SELECT id, asset_id, title, published, module, access, showtitle, params, language, ordering, position, content "
                . "FROM #__modules "
                . "WHERE client_id=0 AND published = 1 /*AND module!='mod_multimodule'*/ "
                . "AND id IN ($where) ";
        if($current_id)
            $query .= "AND id!='$current_id' ";
        if($current_position)
            $query .= " AND !(position='banner' AND module='mod_multimodule') "; //"AND position!='$current_position' "
        if($modules_ordering)
            $query .= "ORDER BY ordering ";
        $items = JFactory::getDBO()->setQuery($query)->loadObjectList('id');

	//	echo "<pre> ** ".print_r($query,true)."++</pre>";    
	//	echo "<pre> ** $type_module - pos $current_position - ids ".join(",",$modulesID)."++</pre>"; 
	//	echo "<pre> ** ".print_r($items[149],true)."++</pre>"; 
	
//            toPrint($chromestyle,'$chromestyle');
        if($chromestyle != 'System-none')
            return self::getModulesLegacy($items,$chromestyle);
        else
            return self::getModules($items);
    }
    public static function getModules($multi_items, $parentId=0){ 
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
                $module->params = json_decode ($module->params);
                //echo "<pre> **".print_r( $module,true). "++</pre>"; 
                //var_dump($module->params);
                //echo "<br>".$file."<br>". $module->params."<br> ";
                $params = new JObject();
                $params->setProperties($module->params);
				
                $module->params = $params;
				$module->parentId = $parentId;
				$module->style = $module->params->get('style','');
				$module->moduleclass_sfx = $module->params->get('moduleclass_sfx','');
				$module->module_tag = $module->params->get('module_tag','');
				$module->header_tag = $module->params->get('header_tag','');
				$module->header_class = $module->params->get('header_class',''); 
				
                $multi_items[$multi_module_id]->published = $module->published;
                $multi_items[$multi_module_id]->params= $module->params;
                $multi_items[$multi_module_id]->parentId= $module->parentId;
                $multi_items[$multi_module_id]->style= $module->style; 
                $multi_items[$multi_module_id]->moduleclass_sfx= $module->moduleclass_sfx;
                $multi_items[$multi_module_id]->module_tag= $module->module_tag;
                $multi_items[$multi_module_id]->header_tag= $module->header_tag; 
                $multi_items[$multi_module_id]->header_class= $module->header_class;
                $multi_items[$multi_module_id]->image= $params->get('backgroundimage',FALSE);
                $multi_items[$multi_module_id]->type= 'modules';
				
				//if ($multi_module_id == 144 || $multi_module_id == 145)echo "<pre> **".print_r( $module,true). "++</pre>"; 
				
				JFactory::getLanguage()->load($module->module);
			
                ob_start();
				//echo $file."<br>" ;//149,142,146			
                require $file;
                $multi_items[$multi_module_id]->content = ob_get_clean();
                
                if(empty($module->published))
                    unset($multi_items[$multi_module_id]);
				//$multi_items[$multi_module_id]->id = $multi_module_id;
                //$module->content = $content;
            }
        }
        return $multi_items;
    }
    
    public static function getModulesLegacy($multi_items, $parentId=0, $chromestyle = ''){ //'System-none'
            //toPrint($multi_items,'$multi_items');
            
        foreach ($multi_items as $multi_module_id => &$module){
            
            if($chromestyle){ 
		$module->params =  new \Joomla\Registry\Registry($module->params);  
                $module->params->set('style',$chromestyle);  
            } 
            $module->image = $module->params->get('backgroundimage',FALSE);
            
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
     * @param array $separator Char(chars) separator
     * @return array Array items
     */
    public static function split($string = '', array $separator = []){
        if(empty($string))
            return [];
        if(empty($separator))
            $separator = ['|'];
        $string = str_replace(['\n','\r','\t'], '', $string);
        
        $items = [];
        foreach ($separator as $s){
            $arr = explode($s, $string);
            $items = array_merge($items,$arr);
        }
        foreach ($items as &$item){
            $item = trim($item);
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
    
//    
//    public static function getCal(&$params)
//    {
//		
//		$input  = JFactory::getApplication()->input;
//		
//		$curmonth=$input->getInt('month',($params->get("defmonth")?$params->get("defmonth"):date('n')));
//		$curyear=$input->getInt('year',($params->get("defyear")?$params->get("defyear"):date('Y')));
//		 
//		$dayofmonths=array(31,(!($curyear%400)?29:(!($curyear%100)?28:(!($curyear%4)?29:28)) ), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
//		
//		$dayofmonth = $dayofmonths[$curmonth-1];
//		$day_count = 1;
//		$num = 0;
//
//		$weeks = array();
//		for($i = 0; $i < 7; $i++)
//		{
//			$a=floor((14-$curmonth)/12);
//			$y=$curyear-$a;
//			$m=$curmonth+12*$a-2;
//			$dayofweek=($day_count+$y+floor($y/4)-floor($y/100)+floor($y/400)+floor((31*$m)/12)) % 7;
//			$dayofweek = $dayofweek - 1 - $params->get("firstday");
//			if($dayofweek <= -1) $dayofweek =$dayofweek + 7;
//
//
//			if($dayofweek == $i)
//			{
//				$weeks[$num][$i] = $day_count.' 0';
//				$day_count++;
//			}
//			else
//			{
//				$weeks[$num][$i] = ($dayofmonths[$curmonth!=1?($curmonth-2):(11)]-($dayofweek-1-$i)).' 1';
//			}
//		}
//
//		while(true)
//		{
//			$num++;
//			for($i = 0; $i < 7; $i++)
//			{
//				if ($day_count > $dayofmonth) {
//					$weeks[$num][$i] = ($day_count-$dayofmonths[$curmonth-1]).' 1';
//				} elseif ($day_count <= $dayofmonth) {
//					$weeks[$num][$i] = $day_count.' 0';
//				}
//				$day_count++;
//	  
//				if($day_count > $dayofmonth && $i==6) break;
//			}
//			if($day_count > $dayofmonth && $i==6) break;
//		}
//		
//		if (!$params->get('ajaxed')) {
//			$ajaxed = 0;	
//		} else {
//			$ajaxed = 1;	
//		}
//		
//		$monthname = 'MOD_PRODCALENDAR_MONTHNAME_' . $params->get( "submonthname" ) . '_' . $curmonth;
//		$monthname = modJshoppingProductCalendarHelper::encode($monthname,$params->get('encode'),$ajaxed);
//		
//                
//		$cal = new JObject();
//		$cal->items = modJshoppingProductCalendarHelper::getList($params, $curmonth, $curyear );
//                //var_dump($cal->items);
//                //var_dump(count($cal->items));
//		$cal->weeks = $weeks;
//		$cal->curmonth = $curmonth;
//		$cal->curyear = $curyear;
//		$cal->monthname = $monthname;
//		$cal->dayofmonths = $dayofmonths;
//		$cal->ajaxed = $ajaxed;
//		
//		return $cal;
//    }
//	
//	public static function getList(&$params, $curmonth, $curyear)
//	{
//            require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
//            require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
//		$db = JFactory::getDbo();
//                $db->setQuery("SHOW COLUMNS FROM `#__jshopping_products` LIKE 'date_event';");                
//                $column = $db->loadObject();
////SHOW COLUMNS FROM `pac0x_jshopping_products` LIKE 'date_event';
////SHOW COLUMNS FROM `pac0x_jshopping_products` WHERE Field = 'date_event';
////$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
//
////		$app = JFactory::getApplication();
////		$appParams = $app->getParams();
////var_dump($appParams);
//                
//		//$model->setState('params', $appParams);
//
//		$limit = (int) $params->get('count', 0);
////		if ( $limit ) {
////			$model->setState('list.start', 0);
////			$model->setState('list.limit', $limit);
////		}
////
////		$model->setState('filter.published', 1);
//
////		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
////		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
////		$model->setState('filter.access', $access);
//
//                $CatsIds  = $params->get('catids', array());      
//                //$catids  = $params->get('catid', array());
//                //var_dump($catids); 
//		$state   = $params->get('state', 1);
//                $field = $params->get('fielddate', 'date_modify');
//		
//		if ( $CatsIds ) {//$catids
//                    
//                              
//                   if (is_array($CatsIds)) {    
//                        $cat_arr = array(); 
//                        foreach($CatsIds as $key=>$curr){ 
//                            if (intval($curr)) $cat_arr[$key] = intval($curr);
//                        }  
//                    } else {
//                        $cat_arr = array();
//                        if (intval($CatsIds)) $cat_arr[] = intval($CatsIds);
//                    }
//                    //var_dump($cat_arr);  
//                    
//                    $month = date('m');//getInt//getWord
//                    $year = date('Y');
//                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
////                    if($column == null){
//                        $queryDate = " AND prod.$field>='".$year."-".$month."-1"."'";
//                        $queryDate.= " AND prod.$field<='".$year."-".$month."-".$daysInMonth." 23:59:59'";
//                        $rows = modJshoppingProductCalendarHelper::getPlaceProducts($params->get('count_products', 35),$cat_arr,$queryDate, " ", " ",array("prod.$field"));
//                        foreach ($rows as $k=> $r){
//                            $rows[$k]->date_event = $r->$field;
//                        }
////                    }else{
////                        $queryDate = " AND prod.date_event>='".$year."-".$month."-1"."'";
////                        $queryDate.= " AND prod.date_event<='".$year."-".$month."-".$daysInMonth." 23:59:59'";
////                        $rows = modJshoppingProductCalendarHelper::getPlaceProducts($params->get('count_products', 35),$cat_arr,$queryDate, " ", " ",array('prod.date_event'));
////                    }
//                        //$product = JTable::getInstance('product', 'jshop');
//                        //$rows = $product->getLastProducts($params->get('count_products', 40), $cat_arr);
////////getDopProducts($count, $array_categories = null, $filters = array(),$productids,$order_query,$sort_by)
////
////
////
//                        //$category = JSFactory::getTable('category', 'jshop');
//                        //$category->load($category_id);
//                    //var_dump(count($rows));
////  echo "<br/>";
////echo "<pre>";
////var_dump($rows);
////echo "</pre>";
//                
////
////		
////			if ($params->get('show_child_category_articles', 0) && (int) $params->get('levels', 0) > 0) {
////				// Get an instance of the generic categories model
////$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
////				//$categories->setState('params', $appParams);
////				$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
////				//$categories->setState('filter.get_children', $levels);
////				//$categories->setState('filter.published', 1);
////				//$categories->setState('filter.access', $access);
////				//$additional_catids = array();
////	
////				foreach($CatsIds as $catid)//$catids
////				{
////                                            //category -> getSubCategories($parentId, $order = 'id', $ordering = 'asc', $publish = 0)
////                                            //category -> getAllCategories($publish = 1, $access = 1)
////                                            //category -> getTreeChild()
////                                        $category = JSFactory::getTable('category', 'jshop');
////                                        $category->load($catid);
////                                        $treeCats = $category -> getTreeChild();
////                                        $additional_catids =  array_merge ($additional_catids, $treeCats);
////                                        
////					//$categories->setState('filter.parentId', $catid);
////					//$recursive = true;
////					//$items = $categories->getItems($recursive);
////	
//////					if ($items)
//////					{
//////						foreach($items as $category)
//////						{
//////							$condition = (($category->level - $categories->getParent()->level) <= $levels);
//////							if ($condition) {
//////								$additional_catids[] = $category->id;
//////							}
//////	
//////						}
//////					}
////				}
////                                $CatsIds = array_unique($additional_catids);
////				//$catids = array_unique(array_merge($catids, $additional_catids));
////			}
//			
////			$model->setState('filter.category_id',$CatsIds );//$catids
//			
//		}
//
//                
//                
//		//$userId = JFactory::getUser()->get('id');
//
//                                
//
////		$order_map = array(
////			'm_dsc' => 'a.modified DESC, a.created',
////			'mc_dsc' => 'CASE WHEN (a.modified = '.$db->quote($db->getNullDate()).') THEN a.created ELSE a.modified END',
////			'c_dsc' => 'a.created',
////			'p_dsc' => 'a.publish_up',
////		);
//		//$ordering = JArrayHelper::getValue($order_map, $params->get('ordering'), 'a.publish_up');
//                
//                
//                
//		$dir = 'DESC';
//                                
//	
////		$nullDate	= $db->Quote($db->getNullDate());
//		
//		$startDateRange = $curyear . '-' . $curmonth . '-01 00:00:00';
//		$endDateRange   = $curyear . '-' . ($curmonth + 1) . '-01 00:00:00';
//		if ( $curmonth == 12 ) {
//			$endDateRange = ($curyear + 1) . '-01-01 00:00:00';
//		}
//		
//		if ( $state == 3 ) {
//			$published = array(1,2);	
//		} else {
//			$published = $state;
//		}		
//			
////     echo "<pre>";
////var_dump($rows);
////echo "</pre>";                           
////getAllProducts($filters, $order = null, $orderby = null, $limitstart = 0, $limit = 0)
//		//$items = $model->getItems();
//		
//		$calitems = array();
//                addLinkToProducts($rows);
//                
//		foreach ($rows as &$item) {
//			$item->slug = $item->id.':'.$item->alias;
//			$item->catslug = $item->category_id.':'.$item->category_alias;
////http://teatr-chehova.ru/index.php?option=com_jshopping&controller=product&task=view&category_id=1&product_id=180&Itemid=0	
/////                        $link = '/index.php?option=com_jshopping&controller=product&task=view&category_id='.$item->category_id.'&product_id='.$item->product_id.'&Itemid=0';
//				//$link = ContentHelperRoute::getArticleRoute($item->slug, $item->catslug);
////				if ($params->get('remmonth',0)) {
////					$link .= '&month='.$curmonth.'&year='.$curyear;	
////				}
/////                        $link=        SEFLink($link, 1); 
//		
////http://teatr-chehova.ru/component/jshopping/product/view/1/179?Itemid=0
//			$item->link = JRoute::_($item->product_link);
//			$item->day = JHtml::_('date',$item->date_event, 'j');
//			
//			$item->title = $item->name;
////echo "<pre>";
////var_dump($item->product_link);
////echo "</pre>";				
//			$calitems[$item->day][] = $item;
//		}
//
//                
//		return $calitems;
//	}
//	
//    public static function getAjax()
//    {		
//		$input  = JFactory::getApplication()->input;
//		
//                require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
//                require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
//                
//		//jimport('joomla.application.module.helper');
//		require_once JPATH_BASE.'/administrator/components/com_modules/models/module.php';
//		
//		
//		
//		$modModel = JModelLegacy::getInstance('Module', 'ModulesModel', array('ignore_request' => true));
//		
//		$mid = $input->getInt('mid');
//		
//		$mymodule = $modModel->getItem($mid);
//		
//		$myparams = new JRegistry;
//		$myparams->loadArray($mymodule->params);
//		$myparams->mid = $mid;
//      //  echo get_class  ($myparams);
////	echo " <pre>";
////        var_dump($myparams);
////        echo "</pre>";//<br/>
//                //if (!$myparams->get('ajaxed')) {
//		//	$ajaxed = 0;	
//		//} else {
//			$ajaxed = 1;
//                //}
//                
//                $curmonth = JRequest::getInt( "month", date('m') );//getInt//getWord
//                $curyear = JRequest::getInt( "year", date('Y') );
//                $dayofmonth = cal_days_in_month(CAL_GREGORIAN, $curmonth, $curyear);
//		$monthname = 'MOD_PRODCALENDAR_MONTHNAME_' . $myparams->get( "submonthname" ) . '_' . $curmonth;
//		$monthname = modJshoppingProductCalendarHelper::encode($monthname,$myparams->get('encode'),$ajaxed);
//                //$dayofmonth = $dayofmonth;//$dayofmonths[$curmonth-1];
//		$day_count = 1;
//		$num = 0;
//$dayofmonths=array(31,(!($curyear%400)?29:(!($curyear%100)?28:(!($curyear%4)?29:28)) ), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
//		$weeks = array();
//		for($i = 0; $i < 7; $i++)
//		{
//			$a=floor((14-$curmonth)/12);
//			$y=$curyear-$a;
//			$m=$curmonth+12*$a-2;
//			$dayofweek=($day_count+$y+floor($y/4)-floor($y/100)+floor($y/400)+floor((31*$m)/12)) % 7;
//			$dayofweek = $dayofweek - 1 - $myparams->get("firstday");
//			if($dayofweek <= -1) $dayofweek =$dayofweek + 7;
//
//
//			if($dayofweek == $i)
//			{
//				$weeks[$num][$i] = $day_count.' 0';
//				$day_count++;
//                                //echo "+";
//			}
//			else
//			{
//                            $xi = $curmonth==1? 11 : ($curmonth-2);
//				$weeks[$num][$i] = ($dayofmonths[$xi]-($dayofweek-1-$i)).' 1';
//                                //echo "-";
//			}
//		}
//                
//        	while(true)
//		{
//			$num++;
//			for($i = 0; $i < 7; $i++)
//			{
//				if ($day_count > $dayofmonth) {
//					$weeks[$num][$i] = ($day_count-$dayofmonth).' 1';
//				} elseif ($day_count <= $dayofmonth) {
//					$weeks[$num][$i] = $day_count.' 0';
//				}
//				$day_count++;
//	  
//				if($day_count > $dayofmonth && $i==6) break;
//			}
//			if($day_count > $dayofmonth && $i==6) break;
//		}
//
//        
//
//        
//
//        $rows = modJshoppingProductCalendarHelper::getListProductsAjax($myparams, $curmonth, $curyear );
//        
//        
//        	foreach ($rows as &$item) {
//			$item->slug = $item->id.':'.$item->alias;
//			$item->catslug = $item->category_id.':'.$item->category_alias;
//
//			$item->link = JRoute::_($item->product_link);
//			$item->day = JHtml::_('date',$item->date_event, 'j');
//                        $item->date_event=$item->date_event;
//			
//			$item->title = $item->name;
////echo "<pre>";
////var_dump($item->product_link);
////echo "</pre>";				
//			$items[$item->day][] = $item;
//		}
//        
//                
//
////echo " <pre>";
////var_dump($items);
////echo "</pre>";//<br/>    
////return;
//                
//	
//	 /*	 */
////        echo "<br/><pre>";
////        var_dump($module);
////        echo "</pre>";
//               jimport( 'joomla.application.module.helper' );
//                $module = JModuleHelper::getModule('mod_jshopping_product_calendar');
//		$registry = new JRegistry;
//		$registry->loadString($module->params);
//		$registry->merge($myparams);
//		$registry->set('mid', $mid);
//		$registry->set('ajaxed', 1);
//		
//		//$module->params = $registry->toString();
//                
////		echo "<br/><br/><pre>-";
////                var_dump($module);      
////                echo "</pre>";
//                
////               var_dump(  class_exists('JModuleHelper'));
////               var_dump(  method_exists('JModuleHelper','renderModule'));
////               var_dump(function_exists('JModuleHelper::renderModule'));
//                //require JModuleHelper::getLayoutPath('mod_jshopping_product_calendar');
////        echo $curyear." getYear<br/>";
////        echo $curmonth." getMonth<br/>";
////        echo $monthname." getMonthName<br/>";
//        //echo date("t", "$curyear-$curmonth-22")." getCountDayMonth<br/>"; //date("t", $time);
//        //echo date("t", "$curyear-$curmonth-22")." getCountDayMonth<br/>"; //date("t", $time);
////        echo $dayofmonth." getCountDayMonth<br/>"; //date("t", $time);
////        echo count($items)." getCountProd<br/>"; //date("t", $time);       
////echo " <pre>";
////var_dump($weeks);
////echo "</pre>";//<br/>
//                  
//               
//		$cal = new JObject();
//		$cal->items = $items;
//                $cal->params = $myparams;
//                //var_dump($cal->items);
//                //var_dump(count($cal->items));
//		$cal->weeks = $weeks;
//		$cal->curmonth = $curmonth;
//		$cal->curyear = $curyear;
//		$cal->monthname = $monthname;
//		$cal->dayofmonths = $dayofmonths;
//		$cal->ajaxed = $ajaxed;
//		
//                $params = $myparams;
//		//return $cal;
//               
////echo "<br/><br/><pre>-";
////var_dump(  JModuleHelper::getLayoutPath('mod_jshopping_product_calendar'));
////echo "</pre>";
//               require JModuleHelper::getLayoutPath('mod_jshopping_product_calendar'); 
//		//return JModuleHelper::renderModule($module);
//    }
//
//    public static function encode($text,$encode,$ajaxed)
//    {
//        //echo "<br/><br/><pre>-";
////var_dump(  $encode,iconv("UTF-8", $encode, JText::_($text),JText::_($text)));
////var_dump(   $text);
////echo "</pre>";
//		if ($encode!='UTF-8' && $ajaxed) { 
//			$text=iconv("UTF-8", $encode, JText::_($text));
//		}
//		else {
//			$text=JText::_($text);
//		}
//		return $text;
//    }
//    
//    
//    	public static function getListProductsAjax(&$params, $month, $year)
//	{
//    //($count, $array_categories = null, $filters = array(),$productids,$order_query,$sort_by)
//        
////        require_once(JPATH_BASE.DS.'includes'.DS.'defines.php');
////        require_once(JPATH_BASE.DS.'includes'.DS.'framework.php');
//        require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
//        require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
////        require_once (JPATH_SITE.'/libraries/joomla/database/table.php');
////        jimport( 'joomla.database.table' );        
////        jimport('joomla.application.module.helper');
//        
//
//        $jshopConfig = JSFactory::getConfig();
//        $db = JFactory::getDBO();
//        $lang = JSFactory::getLang();
//        
////echo "<br/><pre>";
//////var_dump("JDocumentRaw",class_exists ("JDocumentRaw"));
////echo "</pre>";
//
//
//
////echo "<br/><pre>";
////var_dump("Lang-",$lang->lang,$lang->get('name'));
////echo "</pre>";
//        //$product = JTable::getInstance('product', 'jshop');
//        $field = $params->get('fielddate', 'date_modify');
//        
//                $db->setQuery("SHOW COLUMNS FROM `#__jshopping_products` LIKE 'date_event';");                
//                $column = $db->loadObject();
//                
//        $adv_query = ""; $adv_from = ""; 
//        //$adv_result = $product->getBuildQueryListProductDefaultResult();
//  $adv_result = "prod.product_id, pr_cat.category_id, prod.`".$lang->get('name')."` as name, prod.`".$lang->get('short_description')."` as short_description, prod.product_ean, prod.image, prod.product_price, prod.currency_id, prod.product_tax_id as tax_id, prod.product_old_price, prod.product_weight, prod.average_rating, prod.reviews_count, prod.hits, prod.weight_volume_units, prod.basic_price_unit_id, prod.label_id, prod.product_manufacturer_id, prod.min_price, prod.product_quantity, prod.date_modify, prod.$field ";        
////else $adv_result = "prod.product_id, pr_cat.category_id, prod.`".$lang->get('name')."` as name, prod.`".$lang->get('short_description')."` as short_description, prod.product_ean, prod.image, prod.product_price, prod.currency_id, prod.product_tax_id as tax_id, prod.product_old_price, prod.product_weight, prod.average_rating, prod.reviews_count, prod.hits, prod.weight_volume_units, prod.basic_price_unit_id, prod.label_id, prod.product_manufacturer_id, prod.min_price, prod.product_quantity, prod.date_modify, prod.date_event";        
//
//        //string(488) "prod.product_id, pr_cat.category_id, prod.`name_ru-RU` as name, prod.`short_description_ru-RU` as short_description, prod.product_ean, prod.image, prod.product_price, prod.currency_id, prod.product_tax_id as tax_id, prod.product_old_price, prod.product_weight, prod.average_rating, prod.reviews_count, prod.hits, prod.weight_volume_units, prod.basic_price_unit_id, prod.label_id, prod.product_manufacturer_id, prod.min_price, prod.product_quantity, prod.different_prices, prod.date_modify, prod.date_event"
//
//        //$product->getBuildQueryListProductSimpleList("dop", $array_categories, $filters, $adv_query, $adv_from, $adv_result);
//        
//            $CatsIds  = $params->get('catids', array()); 
//            if($CatsIds){
//                    if (is_array($CatsIds)) {    
//                        $cat_arr = array(); 
//                        foreach($CatsIds as $key=>$curr){ 
//                            if (intval($curr)) $cat_arr[$key] = intval($curr);
//                        }  
//                    } else {
//                        $cat_arr = array();
//                        if (intval($CatsIds)) $cat_arr[] = intval($CatsIds);
//                    }
//                    
//                
//                    
//                if (is_array($cat_arr) && count($cat_arr)){
//                    $adv_query .= " AND pr_cat.category_id IN (".implode(",", $cat_arr).")";
//                }        
//            }
//            
//            $user = JFactory::getUser(); 
//            $jshopConfig = JSFactory::getConfig();
//            $groups = implode(',', $user->getAuthorisedViewLevels());
//            $adv_query .=' AND prod.access IN ('.$groups.') AND cat.access IN ('.$groups.')';
//            
//            if ($jshopConfig->hide_product_not_avaible_stock){
//                $adv_query .= " AND prod.product_quantity > 0";
//            }
//            if ($jshopConfig->show_delivery_time){
//                $adv_result .= ", prod.delivery_times_id";
//            }
//            if ($jshopConfig->admin_show_product_extra_field){
//                $adv_result .= getQueryListProductsExtraFields();
//            }
//            if ($jshopConfig->product_list_show_vendor){
//                $adv_result .= ", prod.vendor_id";
//            }
//            if ($jshopConfig->product_list_show_qty_stock){
//                $adv_result .= ", prod.unlimited";
//            }
//            
//            
//                
//            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
//            $adv_query.= " AND prod.$field>='".$year."-".$month."-1"."'";
//            $adv_query.= " AND prod.$field<='".$year."-".$month."-".$daysInMonth." 23:59:59'";
//    
//        $count = (int)$params->get('count_products', 35);
//      //  JPluginHelper::importPlugin('jshoppingproducts');
//       
//        
////        $dispatcher = JDispatcher::getInstance();
////        $dispatcher->trigger( 'onBeforeQueryGetProductList', array("dop_products", &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters) );
////        $dispatcher->trigger('onBuildQueryListProductFilterPrice', array($filters, &$adv_query, &$adv_from));                        
//        $query = "SELECT $adv_result FROM `#__jshopping_products` AS prod
//                  INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
//                  LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
//                  $adv_from
//                  WHERE prod.product_publish = '1' AND cat.category_publish='1' $productids ".$adv_query."
//                  GROUP BY prod.product_id $order_query $sort_by LIMIT ".$count;
//        
//          
//        
//        $db->setQuery($query);
//        $products = $db->loadObjectList();
//        
//        
//        modJshoppingProductCalendarHelper::addLinkToProducts($products);
////echo "<br/><pre>";
////var_dump($products);
////echo "</pre>";  
//        //$products = listProductUpdateData($products);
//        return $products; 
//								
//    }  
//    
//public static function addLinkToProducts(&$products, $default_category_id = 0, $useDefaultItemId = 0){
//    $jshopConfig = JSFactory::getConfig();
//    foreach($products as $key=>$value){
//        $category_id = (!$default_category_id)?($products[$key]->category_id):($default_category_id);
//        
//        if (!$category_id) $category_id = 0;
////echo "<br/><pre>";
////var_dump($useDefaultItemId);
////echo "</pre>";     
//$link = 'index.php?option=com_jshopping&controller=product&task=view&category_id='.$category_id.'&product_id='.$products[$key]->product_id;
//        $products[$key]->product_link = modJshoppingProductCalendarHelper::SEFLink($link, $useDefaultItemId);
//            
//        $products[$key]->buy_link = ''; 
//        if ($jshopConfig->show_buy_in_category && $products[$key]->_display_price){
//            if (!($jshopConfig->hide_buy_not_avaible_stock && ($products[$key]->product_quantity <= 0))){
//                $products[$key]->buy_link = SEFLink('index.php?option=com_jshopping&controller=cart&task=add&category_id='.$category_id.'&product_id='.$products[$key]->product_id, 1);
//            }
//        }
//    }
//}
//
//public static function SEFLink($link, $useDefaultItemId = 0, $redirect = 0, $ssl=null){
//	$app = JFactory::getApplication();
//    //JPluginHelper::importPlugin('jshoppingproducts');
////    $dispatcher =JDispatcher::getInstance();
////    $dispatcher->trigger('onLoadJshopSefLink', array(&$link, &$useDefaultItemId, &$redirect, &$ssl));
//    $defaultItemid = getDefaultItemid();
//    if ($useDefaultItemId==2){
//        $Itemid = getShopManufacturerPageItemid();
//        if (!$Itemid) $Itemid = $defaultItemid;
//    }elseif ($useDefaultItemId==1){
//        $Itemid = $defaultItemid;
//    }else{
//        $Itemid = JRequest::getInt('Itemid');
//        if (!$Itemid) $Itemid = $defaultItemid;
//    }
////    $dispatcher->trigger('onAfterLoadJshopSefLinkItemid', array(&$Itemid, &$link, &$useDefaultItemId, &$redirect, &$ssl));
//	if (!preg_match('/Itemid=/', $link)){        
//        if (!preg_match('/\?/', $link)) $sp = "?"; else $sp = "&";
//        $link .= $sp.'Itemid='.$Itemid;
//    }
//   	$link = JRoute::_($link, (($redirect) ? (false) : (true)), $ssl);
//	if ($app->isAdmin()){
//        $link = str_replace('/administrator', '', $link);
//    }
//return $link;
//}
//    
//    public static function  getPlaceProducts($count, $array_categories = null, $productids,$order_query,$sort_by, $newFields=array()){
//        
////        require_once(JPATH_BASE.DS.'includes'.DS.'defines.php');
////        require_once(JPATH_BASE.DS.'includes'.DS.'framework.php');
////        require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
////        require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
////        require_once (JPATH_SITE.'/libraries/joomla/database/table.php');
////        jimport( 'joomla.database.table' );        
////        jimport('joomla.application.module.helper');
//        
//
//        $jshopConfig = JSFactory::getConfig();
//        $db = JFactory::getDBO();
//        $product = JTable::getInstance('product', 'jshop');        
//        $adv_query = ""; $adv_from = ""; 
//        $adv_result = $product->getBuildQueryListProductDefaultResult($newFields);
//        $filter=array();
//        $product->getBuildQueryListProductSimpleList("dop", $array_categories, $filter, $adv_query, $adv_from, $adv_result);
//        JPluginHelper::importPlugin('jshoppingproducts');
////        $dispatcher = JDispatcher::getInstance();
////        $dispatcher->trigger( 'onBeforeQueryGetProductList', array("place_products", &$adv_result, &$adv_from, &$adv_query, &$order_query) );
//// 
//        $query = "SELECT $adv_result FROM `#__jshopping_products` AS prod
//                  INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
//                  LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
//                  $adv_from
//                  WHERE prod.product_publish = '1' AND cat.category_publish='1' $productids ".$adv_query."
//                  GROUP BY prod.product_id $order_query $sort_by LIMIT ".$count;
//        $db->setQuery($query);
//        $products = $db->loadObjectList();
////echo "<br/><pre>";
////var_dump($products);
////echo "</pre>";  
//        $products = listProductUpdateData($products);
//        return $products; 
//								
//    }  
}



abstract class JModHelp extends Joomla\CMS\Helper\ModuleHelper{
    static function ModeuleDelete($module){
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