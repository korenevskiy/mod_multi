<?php
/**
 * @package     JoomLike.Site
 * @subpackage  mod_multimodule
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

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

$id         = $params->get('id');
$positon    = $params->get('position');

$style      = $params->get('style');
$mod_show   = count($modules);

$module_tag = $params->get('module_tag', 'div');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$showtitle = $params->get('showtitle');
//$title = htmlspecialchars($params->get('title'));
$title = ($params->get('title'));


$header_tag = $params->get('header_tag', 'h3');
$header_class = htmlspecialchars($params->get('header_class', 'module-header'));

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

$modules;
$modules_tag = $params->get('modules_tag'); 
$modules_showtitle = $params->get('modules_showtitle'); 
$type_module = $params->get('type_module'); 


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
 
$style=$params->get('style');
$module_tag2 = $params->get('module_tag2','div')?:'div';
if($module_tag2)
    echo "<$module_tag2  id='myModal".$module->id."'  class=\"multimodule$moduleclass_sfx id$id  $style ".$params->get('moduleclass_sfx2')."   $moduleClass\"  >";

// split html elements from $modules
$html_before = '';
$html_after = []; 
foreach ($modules as $id=>$item){
    if(is_string($item)) 
        $html_before .= $item;
    else 
        break;
}
$keys = array_keys($modules);
$keys = array_reverse($keys);
foreach ($keys as $key){
    if(is_string($modules[$key]))
        $html_after[$key] = $item;
    else 
        break;
}
ksort($html_after);


echo $html_before;

echo <<<VIEW
    <style type="text/css">
                .multimodule .modalLabel{cursor:pointer;}
                .multimodule .modalInput{display: none;}
                .multimodule .modalInput + $module_tag2.modalWindow{
                    position: fixed;
                    top: -90%;
                    right:0;
                    left: 0;
                    margin: auto;
                    -display: none;
                    transition: 0.5s;
                    z-index: 333;
                }
                .multimodule .modalInput:checked + $module_tag2.modalWindow{                    
                    top: 0;
                    display: block;                    
                }
                .multimodule .modalInput + $module_tag2.modalWindow > div{
                    position: absolute;
                    margin: 0 auto;
                    -width:400px;
                    -display: block;
                    transition: 0.1s linear;
                    z-index: 334;
                    background-color: white;
                    border-radius: 5px;
                    border:1px solid;
                    right: 0;
                    left: 0;
                }
                .multimodule .modalInput:checked + $module_tag2.modalWindow > div{
                    position: absolute;
                    display: flex;
                    top: 10%;
                    height: 80%;
                    margin: 30px auto 0;
                    -display: block;
                    transition: 0.1s linear;
                    box-shadow: 0 3px 7px rgba(0,0,0,0.7);
                }
                .multimodule .modalX{
                    line-height: inherit;
                    font-size: 1.6em;
                    width: 1.2em !important;
                    height: 1.2em !important;
                    display: flex; 
                    justify-content: center;
                    align-items: center;    
                    position: absolute;
                    top: 10px;
                    right: 11px;
                    padding:1px 4px;
                    border-radius:3px;
                    border:1px solid rgba(0,0,0,0.8);
                    cursor:pointer;
                    transition: 0.4s  0.1s;
                    opacity: 0.4;
                    z-index: 335;
                }
                .multimodule .modalX:hover{ 
                    -border:1px solid rgba(0,0,0,0.7);
                    opacity: 1;
                    transform: rotate(180deg)  scale(1.2);
                    
                }
                .multimodule .modal-header{
                }
                .multimodule .modal-content{
                    position: initial;
                    margin:20px;
                    width: auto;
                    width: initial;
                    overflow: auto;
                }
                .chrono_credits{
                    display: none;
                }
                form.chronoform>:last-child .gcore-input{
                    margin: auto;
                    float: none;                    
                }
                form.chronoform>:last-child .gcore-input input{
                    font: bolder 26px 'Poiret One', 'Helvetica', arial, sans-serif; 
                    height: auto;
                    transition: 0.1s linear;
                }
                form.chronoform>:last-child .gcore-input:hover input{
                    font-size: 28px; 
                    transition: 0.2s linear;
                }
            </style>
<div>
       <label class="modalLabel modalLabel$module->id" for="modal$module->id">$title</label>
       <input class="modalInput" type="checkbox" '.$moduleBackShow.' id="modal$module->id">
VIEW;
//echo "<pre>$link $header_tag** ".print_r(( $title),true). "++</pre>"; 

//echo "$link_show *".$params->get('title_alt')."-".$module->title."+".$module->title." ++$module->showtitle $params->showtitle!!";
    $content_tag3 = $params->get('content_tag3','div')?:'div';

    
    if($params->get('content_tag3')== 'default' && $module->module_tag && $module->style){
        echo "<div class='$module->moduleclass_sfx modalWindow".$module->id." modalWindow modal -modal-sm   moduleModal$module->moduleclass_sfx'   tabindex='-1'>"
           . "< $module->module_tagclass='modal-dialog'><div class='modal-content'>";
        $content_tag3 = '';
    }
    elseif($params->get('content_tag3')== 'item' && $module->module_tag){
        echo "<div  class='$module->moduleclass_sfx modalWindow".$module->id." modalWindow modal -modal-sm   moduleModal$module->moduleclass_sfx'   tabindex='-1'>"
           . "<$module->module_tag class='modal-dialog'><div class='modal-content'>";
        $content_tag3 = '';
    }  
    elseif($params->get('content_tag3','div') != 'none' && $content_tag3)
        echo "<div class=\" item_content  $module->moduleclass_sfx modalWindow".$module->id." modalWindow modal -modal-sm   moduleModal$module->moduleclass_sfx\"  tabindex='-1'>"
           . "<$content_tag3 class='modal-dialog'><div class='modal-content'>";

 

                    
                        echo "<div class=\"modal-header\">";
                        echo "<label class=\"modalX icon-delete\" for=\"modal$module->id\"></label>";
                    if ($link_show || $self_module_tag && $showtitle)
                    {    
                        if ($link_show)  echo "<a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\">";
                        echo "<$header_tag class=\"$header_class modal-title \">".strip_tags($title)."</$header_tag>";
                        if ($link_show)  echo "</a>";
                    }
                        echo "</div>"; 
                    
  

//echo "<lala>$title</lala>";
//if($image_show  && strlen($image)  && $image && !in_array($image, ['-','/','0','',' ',FALSE]) )
//    echo "<img class=\"multiimage \" src=\"$image\" alt=\"$title\" />$image";
//if($description_show && $description)    
//    echo "<div class=\"multidescription\">$description</div>";
//echo "<lala>$modules_tag</lala>";

// toPrint(strlen($image),'$image');

 
//echo "123<lala>$modules_tag $style </lala>";


// toPrint($modules,'$modules');

// Show container tag
 
echo "<div class='modal-body multimodules modules count$mod_show $emptystyle $style '>";    

//echo $modules_tag;
//foreach ($modules as $id=>$module)
//	echo $id."-";
//$modules=array();
// Show modules   
foreach ($modules as $id=>$item){
    if(is_string($item)) {
//        echo $item; 
        continue;
    }
foreach ($item as  $i=>$module){
    
    
    if($modules_tag == 'ul')            echo "<li class=\"moduletable$module->moduleclass_sfx id$module->id $module->module\">";
    else if ($modules_tag == 'table')    echo "<tr class=\" id$module->id $module->module \">";
    else if ($modules_tag=='div div')   echo "<div class=\" id$module->id $module->module moduletable$module->moduleclass_sfx\">";
	else if ($modules_tag=='dl')        echo "";
	else if ($module->module_tag=='')   echo "";//echo "<div class=\" id$module->id $module->module\">";
    else                                echo "<$module->module_tag class=\"moduletable$module->moduleclass_sfx id$module->id $module->module\">";
 
//$module->style;  ??
//$module->moduleclass_sfx; ??
//$module->module_tag; ??
//$module->header_tag;  ??
//$module->header_class; ??
// Show title modules    
    if($modules_showtitle){
        if($modules_tag == 'dl')            echo "<dt class=\"$module->header_class\">";
        else if($modules_tag == 'table')    echo "<td class=\"$module->header_class\">";
        else                                echo "<$module->header_tag  class=\"$module->header_class\">";
      echo $module->title;
        if($modules_tag == 'dl')            echo "</dt>";
        else if($modules_tag == 'table')    echo "</td>";
        else                                echo "</$module->header_tag>";
    }

// Show content modules    
    if($modules_tag == 'dl')                echo "<dd class=\"moduletable$module->moduleclass_sfx\">";
    if($modules_tag == 'table')             echo "<td class=\"moduletable$module->moduleclass_sfx\">";
//    if($params->get('mymenu_img_show')=='out'
//            && isset($module->menu_image)) echo  "<img alt=\"$module->title\" class=\"menuimg id$item->id \" src=\"$module->menu_image\"/>";
                                    echo  $module->content;
    if($modules_tag == 'dl')                echo '</dd>';
    if($modules_tag == 'table')             echo '</td>';
    
    if($modules_tag == 'ul')            echo "</li>";
    else if($modules_tag == 'table')    echo "</tr>";
    else if ($modules_tag=='div div')   echo "</div>";
	else if ($modules_tag=='dl')        echo "";
	else if ($module->module_tag=='')   echo "";//echo "</div>";
    else 								echo "</$module->module_tag>";
    //echo "$content";
}}

echo "</div>";
 




echo implode('', $html_after);
    
     
    
    
    if($params->get('content_tag3','div') != 'none' && $content_tag3)
        echo "</$content_tag3>";
 
    //echo "$content";
    if($params->get('content_tag3')== 'default' && $module->module_tag && $module->style){
        echo "</$module->module_tag>";
    }
    if($params->get('content_tag3')== 'item' && $module->module_tag){
        echo "</$module->module_tag>";
    }



 
echo "</div>";

 if($module_tag2)
 echo "</$module_tag2>";
?>

