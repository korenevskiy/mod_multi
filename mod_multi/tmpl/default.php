<?php defined('_JEXEC') or die;
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
//return;
//JHtml::addIncludePath(JPATH_SITE . '/components/com_finder/helpers/html');
//
//JHtml::_('jquery.framework'); 
//JHtml::_('formbehavior.chosen', 'select');
//JHtml::_('bootstrap.tooltip');
//JHtml::_('behavior.keepalive'); 

// Load the smart search component language file.
//$lang = JFactory::getLanguage();
//$lang->load('com_finder', JPATH_SITE);



//toPrint($params,'$params');
//return;
//new \Joomla\CMS\Object\CMSObject();
//$param = $params->toObject();
$param = (new Joomla\Registry\Registry($params))->toObject();//*** 
$param->id = $module->id;



//if($module->id== 142)
//    toPrint ($module,'$module',0, TRUE, TRUE);
$base = JUri::base();

$id      = $params->get('id');
$positon = $params->get('position');

//$style=$params->get('style');
$mod_show = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$showtitle = $params->get('showtitle');
//$title = htmlspecialchars($params->get('title'));
$title = ($params->get('title'));


$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));





//echo $id;
//if($id==148)
//toPrint(JDocument::getInstance(),'JDocument');
  
$prepare = function ( $item, $param = null, $context = 'com_content.article'){
    return modMultiHelper::preparePlugin($item, $param, $context);
};  

$params->get('items_link');
$params->get('items_image');

$params->get('content_tag3');
 

if($tag = $params->get('modules_tag3')){
    $tgs = explode('/', $tag);  
    $tag_title = $tgs[0] ?? FALSE;
    $tag_block = $tgs[1] ?? FALSE;
    $tag_container = $tgs[2] ?? FALSE;
    $tag_item  = $tgs[3] ?? FALSE;
}

$link_show = $params->get('link_show'); 
$link = $params->get('link');

//echo "<pre> ** $link_show ".print_r(( $module),true). " $showtitle++</pre>"; 
//echo "<pre> ** $link_show ".print_r(( $params),true). " $showtitle++</pre>"; 
//if($id == 135){
//    $mail = JFactory::getMailer();
//    $mail->addAddress('koreshs@mail.ru');
//    $type = $mail->Mailer;
//    $mail->setBody('Вау! :'.$type);
//    $send =& $mail->Send();
//    if ($send !== true)  
//        toPrint($send->message,"ERROR sending ($type) email: ");
//    else  
//        toPrint($send->message,"Mail sent ($type)  OK : "); 
//}

//$stylesheetModule = $params->get('stylesheetModule');
//$stylesheetTemplates = $params->get('stylesheetTemplates');
//$stylesheetText = $params->get('stylesheetText');
//$scryptModule = $params->get('scryptModule');
//$scryptTemplates = $params->get('scryptTemplates');
//$scriptText = $params->get('scriptText');
//
//if($stylesheetModule)JHtml::stylesheet(JUri::base().'modules/mod_multi/css/'.$stylesheetModule);
//if($stylesheetTemplates)JHtml::stylesheet(JUri::base().'templates/'.$stylesheetTemplates);
//if($stylesheetText)JFactory::getDocument()->addStyleDeclaration($stylesheetText);
//if($scryptModule)JHtml::script(JUri::base().'modules/mod_multi/css/'.$scryptModule);
//if($scryptTemplates)JHtml::script(JUri::base().'templates/'.$scryptTemplates);
//if($scriptText)JFactory::getDocument()->addScriptDeclaration($scriptText);
//JFactory::getDocument()->addStyleSheet() or JFactory::getDocument()->addScript() 
//JHtml::_('stylesheet', 'com_finder/finder.css', null, true, false); //add file in folder MEDIA/com_finder/finder.css
//$files = JHtml::_('stylesheet', 'templates/' . $this->template . '/css/general.css', null, false, true);
//       echo "<pre>$id - ".print_r ($modules, true)."</pre>";
        //else
//            return;


//ECHO <<<view
//view;   
$modules;
$modules_tag = $params->get('modules_tag');  

//if($modules_tag=='default'){
//    switch ($type_module){
//        case 'positions':
//            $modules_tag = "ul"; break;
//        case 'modules':
//            $modules_tag = "ul"; break;
//        case 'article':
//            $modules_tag = "div"; break;
//        default :
//            $modules_tag = "empty";
//    }
//}
$count_items = 0;
foreach ($modules as $items){
    if(is_array($items))
    $count_items += count($items);
}




if($module_tag2 = $params->get('module_tag2'))
    echo "<$module_tag2 class=\"multimodule".$params->get('moduleclass_sfx2')." count$count_items id$id $param->style\"  >";
else
    $param->moduleclass_sfx = $params->set('moduleclass_sfx',$params->get('moduleclass_sfx')." count$count_items "); 
 
if($showtitle):
    $titlea = "";
    if($link_show == 'ha')
        $titlea = "<$header_tag class=\"$header_class \"><a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\">$title</a></$header_tag>";
    elseif($link_show || $link_show == 'ah')// && in_array($param->style, ['System-none','none','no',''])  && $module_tag2
        $titlea = "<a  href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\"><$header_tag class=\"$header_class\">$title</$header_tag></a>";
    elseif(empty($link_show))//$module_tag2 &&
        $titlea =  "<$header_tag class=\"$header_class\">$title</$header_tag>";

    if(in_array($param->style, ['System-none','none','no','0',0,'']))
        echo $titlea;
//    else
//        $$mod->title = $titlea;
    else
        $$mod->title = "<a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\">$title</a>";
endif;



//if($module->id == 112)
//toPrint($params->get('header_tag'),'$params->get(header_tag)',0,'message',true);
//
//
//echo "<lala>$title</lala>";
 //echo $param->style; 
//echo "<lala>$modules_tag</lala>";
//
//$order=$params->get('order', 'ideh');
//echo $order.'123'; 
//echo ($params->get('layout','dev').' '.$order.' '.strlen($html)); 
//echo "123<lala>$modules_tag $param->style </lala>";
//if($module->id==144)
//toPrint($params->get('style_tag3'),'style_tag3');
//if($module->position=='footer')
//toPrint($kays, '$kays',0);
//toPrint($module_tag2,'$module_tag2',0, $param->id == 194);
//toPrint($modules,'5article',0);
//if($params->get('id')==149)  toPrint($modules,'$modules'); 
//echo $modules_tag;
//foreach ($modules as $id=>$module)
//	echo $id."-";
//$modules=array();


// Show modules   
foreach ($modules as $type => $items){
    if(is_string($items)){          // вывод html пользовательских полей 
        echo $items;
        unset($modules[$type]);
        continue;
    }
    $order =  substr($type, 0, 2); // $type[0];
    $type = substr($type, 2);
    


    $count = count($items);
    $i = -1;
    
    if(isset($tag_block) && $tag_block) 
        echo "<$tag_block class=\"items count$count order$order $type  \">";
    
foreach ($items as $id => $module){
    $module->text = $module->content =  $prepare($module->content);
    
//$module->style;  ??
//$module->moduleclass_sfx; ??
//$module->module_tag; ??
//$module->header_tag;  ??
//$module->header_class; ??
//$module->title;   ??
//$module->id;  ??
//$module->module
//$module->content
//$module->menu_image

    
//if($param->id== 143)
//    toPrint ($module,'$module',0, TRUE, TRUE);
    
// Show title modules         
    $i++;
//echo "$param->style_tag3"    ;
    if(isset($tag_container) && $tag_container)
        echo "<$tag_container class=\"item i$i $type moduletable$module->moduleclass_sfx  id$module->id $module->type  \">";
    
    if($tag_item) 
        echo "<$tag_item class=\" item_tag3 $module->moduleclass_sfx\">";
    
    if(empty($param->style_tag3) || $param->style_tag3 == '0'):
        echo $module->content; 
    else:
     

        
//toPrint($module->module_tag,'$module->module_tag-'.$module->id,0,$module->id!=174); 
    
    $content_tag3 = $params->get('content_tag3');
    
    if($module->module_tag == 'default'){
        $module->module_tag = 'div';
    }
        
    
    if($param->content_tag3 == 'default' && $module->module_tag && $module->style){
        echo "<$module->module_tag class='$module->moduleclass_sfx ' >";
        $content_tag3 = '';
    }
    elseif($param->content_tag3== 'item' && $module->module_tag){
        echo "<$module->module_tag class='$module->moduleclass_sfx ' >";
        $content_tag3 = '';
    }  
    
 
//if($params->get('id')==144)  
//    toPrint($params->get('header_tag3'),'$header_tag3-'.$module->id);   
//if($params->get('id')==144)  
//    toPrint($module->module_tag,'$header_tag3-'.$module->id);   
    
//        toPrint($module,'$module');
//        toPrint($module,'$module');

    $link = $link_ = "";
    
    $module_title = $module->title;
    
    
    $isImage = function($url){
        $ext = strtolower(substr($url, strrpos($url, '.') + 1)) ;
        //$ext = (($p = strrpos('.', $url)) !== false) ? substr($url,$p+1) : '';
        //$ext = pathinfo($url, PATHINFO_EXTENSION);
        return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])?' imagelink '.$ext:' url ';
    };
    $class = $isImage($module->link);






//if($params->get('id')==144)  
//    toPrint($header_tag3,'$header_tag3-'.$module->id);           
$header_tag3 = $params->get('header_tag3');
$items_link = $params->get('items_link');

if($params->get('header_tag3') == 'default'){
    $header_tag3 = '';
}
if($params->get('header_tag3') == 'default' && ($module->showtitle??FALSE)){
    $header_tag3 = $module->header_tag ?: 'div';
}
if($params->get('header_tag3') == 'item'){ 
    $header_tag3 = $module->header_tag ?? '';
}

//toPrint($header_tag3,'$header_tag3',0);
//toPrint($module,'$module',0);
if($header_tag3 && $module->title){  
    $module_title = $module->title;
    
    if($params->get('items_link')=='ha'){
        $module_title = "<$header_tag3 class=\" item_title $module->header_class\"><a href='$module->link' class='$class' title='$module->title' >$module->title</a></$header_tag3>";
    }
    if($params->get('items_link')=='ah'){
        $module_title = "<a href='$module->link' class='$class item_title' title='$module->title' ><$header_tag3 class=\"  $module->header_class\">$module->title</$header_tag3></a>";
    }
    if($params->get('items_link')=='a'){
        $module_title = "<a href='$module->link' class='$class item_title $module->header_class' title='$module->title' >$module->title</a>";
    }
    if($params->get('items_link')=='0'){
        $module_title = "<$header_tag3 class=\"$class item_title $module->header_class\" title='$module->title' >$module->title</$header_tag3>";
    }
    echo $prepare($module_title);          
}
    

    if($module->fields??FALSE){  // Custom Fields from articles 
        echo $module->fields;
    }
    //dl dt dd
    //details summary x
    //figure  figcaption x
    //fieldset legend x
//toPrint($module->content,'$module->content');
//
// Show content modules  

//if($params->get('id')==144) 
//    toPrint($params,'$params');
//    toPrint($params->get('header_tag3','span'),'header_tag3');


    $isImage = function($url){
        $ext = strtolower(substr($url, strrpos($url, '.') + 1)) ;
        //$ext = (($p = strrpos('.', $url)) !== false) ? substr($url,$p+1) : '';
        //$ext = pathinfo($url, PATHINFO_EXTENSION);
        return in_array($ext, ['png','apng','svg','bmp','jpg','jpeg','gif','webp','ico'])?' imagelink '.$ext:' url ';
    };
    $class = $isImage($module->link);  

//    if($module->id==107){
//                    echo "<pre>image ".print_r($module->image, TRUE)."</pre>";
//                    echo "<pre>items_image ".print_r($params->get('items_image'), TRUE)."</pre>";
//                $multi_items[$multi_module_id]->image = $module->params->get('backgroundimage',FALSE);
// //$multi_items[$multi_module_id]->image = 'XX';
//                }
    
    if($params->get('items_image') && $module->image):
    if($params->get('items_image') == 'ai')
        echo $prepare("<a class='$class image'  target='_blank' href='$module->link'  title='$module->title'
            data-toggle='lightbox'   data-gallery='gallery_$mod_id' data-type='image' onclick='return true;'>
            <img class=' item_image $module->moduleclass_sfx '   src='$base$module->image'></a>");   
    if($params->get('items_image') == 'ii')
        echo $prepare("<a class='$class image'  target='_blank' href='$base$module->image'  title='$module->title'
            data-toggle='lightbox'   data-gallery='gallery_$mod_id' data-type='image' onclick='return true;'>
            <img class=' item_image $module->moduleclass_sfx '   src='$base$module->image'></a>");   
    if($params->get('items_image') == 'di') {
        echo $prepare("<div class='$class image'><img  class='   item_image  $module->moduleclass_sfx '  data-action='zoom' src='$base$module->image'></div>");
    }
    if($params->get('items_image') == 'i') 
        echo $prepare("<img class='$class image item_image $module->moduleclass_sfx'    src='$base$module->image'>");
    endif;


    
    if($param->content_tag3 != 'none'){
    if($content_tag3)
        echo "<$content_tag3 class=\" item_content  $module->moduleclass_sfx\">";
    echo  $module->content;
	
	if(isset($module->items) && is_array($module->items) && $module->items){
		$item = $module;
		require \Joomla\CMS\Helper\ModuleHelper::getLayoutPath('mod_multi', '_items');
	}

    if($content_tag3)
        echo "</$content_tag3>";
    }
    
    //echo "$content";
    if($param->content_tag3== 'default' && $module->module_tag && $module->style){
        echo "</$module->module_tag>";
    }
    if($param->content_tag3== 'item' && $module->module_tag){
        echo "</$module->module_tag>";
    }
    
    endif;
    
    if($tag_item)
        echo "</$tag_item>";
    
    if($tag_container)
        echo "</$tag_container>";
}


    if(isset($tag_block) && $tag_block) 
        echo "</$tag_block>"; 
}






if($module_tag2)
    echo "</$module_tag2>";
 
 
?>