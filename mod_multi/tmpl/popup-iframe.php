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

defined('_JEXEC') or die;


//JHtml::script(JUri::base().'media/jui/js/jquery-migrate.js', false, true); 
//JHtml::script(JUri::base().'media/jui/js/jquery-noconflict.js', false, true); 
//echo "<script type=\"text/javascript\">$.noConflict(); </script>";// 
//$doc = JFactory::getDocument();
//$doc->AddScriptDeclaration("$.noConflict();    };");
JHtml::_('jquery.framework'); 
JHtml::_('jquery.ui', array('core', 'sortable'));
//JHtml::_('jquery.modal'); 
//JHTML::_('behavior.modal', 'a.modal', array('handler' => 'ajax'));
//JHTML::_('behavior.modal');

//JHtml::script(JUri::base().'media/jui/js/jquery.modal.js', false, true); //JHtml::script('com_search/search.js', false, true);

  
//JHtml::_('jquery.framework'); 
//JHtml::_('jquery.ui', array('core', 'sortable'));
//JHTML::_('behavior.modal', 'a.modal', array('handler' => 'ajax'));
 

//
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


$param = (new Joomla\Registry\Registry($params))->toObject();//*** 


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
//if($stylesheetModule)JHtml::stylesheet(JUri::base().'modules/mod_multi/css/'.$stylesheetModule);
//if($stylesheetTemplates)JHtml::stylesheet(JUri::base().'templates/'.$stylesheetTemplates);
//if($stylesheetText)JFactory::getDocument()->addStyleDeclaration($stylesheetText);
//if($scryptModule)JHtml::script(JUri::base().'modules/mod_multi/css/'.$scryptModule);
//if($scryptTemplates)JHtml::script(JUri::base().'templates/'.$scryptTemplates);
//if($scriptText)JFactory::getDocument()->addScriptDeclaration($scriptText);
//JFactory::getDocument()->addStyleSheet() or JFactory::getDocument()->addScript() 
//JHtml::_('stylesheet', 'com_finder/finder.css', null, true, false); //add file in folder MEDIA/com_finder/finder.css
//$files = JHtml::_('stylesheet', 'templates/' . $this->template . '/css/general.css', null, false, true);

$modules;
$modules_tag = $params->get('modules_tag'); 
$modules_showtitle = $params->get('modules_showtitle'); 
$type_module = $params->get(' '); 


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

$self_module_tag=$params->get('self_module_tag', '0');
$emptystyle = (!$style && !$self_module_tag)?$moduleclass_sfx." ".$style:"";
if($self_module_tag)
    echo "<$module_tag  id='myModal".$module->id."'  class=\"multimodule$moduleclass_sfx id$id $emptystyle   $moduleClass\"  >";
//
//
 $img="";
if($image_show       && $image)           $img = "<img class=\"multiimage\" src=\"$image\" alt=\"$title\" title=\"$title\"  />";

ECHO <<<VIEW



<style type="text/css">
         
h2.item-title.omii{   
    background: url(/templates/beez_20/images/news-sep.gif) no-repeat;
    padding: 10px 0 20px;
    font-size: 1.3em;
    font-family: times new roman;
}
h2.item-title.omii a{
             text-align: center;
        text-align-last: center;
        text-decoration: none; color: #000000;
        border: 10px solid transparent;
            display: block;
    border-radius: 15px;
    border: 10px solid transparent;
border-bottom:0;
    box-sizing: content-box;
    text-shadow: 0px 0px 1px white;
    width: auto;
        height: 150px;
    transition: 0.3s;
   }
h2.item-title.omii a:hover {
    background-color: transparent;
    text-decoration: none;
        box-shadow: 0 0 10px #a00;
    transition: 0.3s; 
    width: auto;
    transition: 0.3s;
}
h2.item-title.omii a img{
    width: 60%;
    transition: 0.3s;
}
h2.item-title.omii a:hover img{
    width: 70%;
    transition: 0.3s;
}
            </style>
   
<!--
     $module->id $module->id  xdata-modal
       $moduleBackShow   $module->id 
 https://www.ya.ru/  rel="ajax:modal"  rel="modal:open"   $link   
     
           data-modal
        data-modal-class-name="im limage" 
        data-modal-className='li limage' 
        data-modal-title="$title" 
        data-modal-iframe='true' 
        data-modal-width='95%' 
        data-modal-height='95%'
        data-modal-done="1" 
                        zoompage-fontsize="15"  class="modal_link modal  " 
         
        rel="modal:open"
                            
   --> 
         <h2 class="item-title omii">
<a href="$link" target="__blank"
   
>
        $img
 </a>     </h2>   
        
VIEW;
 if($self_module_tag)
 echo "</$module_tag>";
//<br><xiframe src="omii_3d/omii.html"></xiframe>
//echo "<pre>$link $header_tag** ".print_r(( $title),true). "++</pre>"; 
return;

/*

<script src="/media/jui/js/jquery.modal.min.js" type="text/javascript" charset="utf-8"></script>
<xlink rel="stylesheet" href="/media/jui/css/jquery.modal.css" type="text/css" media="screen" />
<script type="text/javascript">
        jQuery(function(){
        //jQuery('.modal_link').modal();
        });
</script>
 
    .modalLabel{cursor:pointer;}
                .modalInput{display: none;}
                .modalInput + $moduleTag.modalWindow{
                    position: fixed;
                    top: -90%;
                    right:0;
                    left: 0;
                    -display: none;
                    transition: 0.5s;
                    z-index: 333;
                }
                .modalInput:checked + $moduleTag.modalWindow{                    
                    top: 0;
                    display: block;                    
                }
                .modalInput + $moduleTag.modalWindow > div{
                    position: absolute;
                    margin: 0 auto;
                    width:400px;
                    -display: block;
                    transition: 0.1s linear;
                    z-index: 334;
                    background-color: white;
                    border-radius: 5px;
                    border:1px solid;
                    right: 0;
                    left: 0;
                }
                .modalInput:checked + $moduleTag.modalWindow > div{
                    position: absolute;
                    top: 10%;
                    margin: 30px auto 0;
                    -display: block;
                    transition: 0.1s linear;
                    box-shadow: 0 3px 7px rgba(0,0,0,0.7);
                }
                .modalX{
                    position: absolute;
                    top: 10px;
                    right: 11px;
                    display: block;
                    width:auto;
                    padding:1px 4px;
                    border-radius:3px;
                    border:1px solid rgba(0,0,0,0.8);
                    cursor:pointer;
                    transition: 0.1s linear;
                    opacity: 0.4;
                }
                .modalX:hover{
                    transition: 0.1s linear;
                    -border:1px solid rgba(0,0,0,0.7);
                    opacity: 1;
                }
                .modal-content{
                    margin:20px;
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
*/


//rel="modal:open"
if($image): 
    echo "<a href='$urls->urla' rel=\"modal:open\"  class='modal_link im' data-modal-class-name='im' data-modal-title='$text' data-modal-width='90%' >$text<span class='icon-search large-icon'>&nbsp;</span></a>";
 else: 
    echo "<a href='$urls->urla' rel=\"modal:open\"  class='modal_link' data-modal-class-name='_no_title li' data-modal-className='li' data-modal-title='$text' data-modal-iframe='true' data-modal-width='95%' data-modal-height='95%'>".($urls->urlatext?$urls->urlatext:$imgs->image_intro_caption)."<span class='icon-search large-icon'>&nbsp;</span></a>";//   
 endif; 
 
 
//echo "$link_show *".$params->get('title_alt')."-".$module->title."+".$module->title." ++$module->showtitle $params->showtitle!!";
if ($modules_tag=='div div' && $mod_show)
    echo "<div class='modalWindow".$module->id." modalWindow modal modal-sm fade moduleModal$moduleClassSfx'  tabindex='-1'>"
                . "<div class='modal-dialog'><div class='modal-content'>";

                    
                        echo "<div class=\"modal-header\">";
                        echo "<label class=\"modalX\" for=\"modal$module->id\">X</label>";
                    if ($link_show || $self_module_tag && $showtitle)
                    {    
                        if ($link_show)  echo "<a href=\"$link\" title=\"".strip_tags($title)."\" class=\"id$id multiheadera\">";
                        echo "<$header_tag class=\"$header_class modal-title \">".strip_tags($title)."</$header_tag>";
                        if ($link_show)  echo "</a>";
                    }
                        echo "</div>"; 
                    
  

//echo "<lala>$title</lala>";
if($image_show       && $image)          echo "<img class=\"multiimage\" src=\"$image\" alt=\"$title\" />";
if($description_show && $description)    echo "<div class=\"multidescription\">$description</div>";
//echo "<lala>$modules_tag</lala>";


 
//echo "123<lala>$modules_tag $style </lala>";



// Show container tag
 
echo "<div class='modal-body multimodules modules count$mod_show $emptystyle $style '>";    

//echo $modules_tag;
//foreach ($modules as $id=>$module)
//	echo $id."-";
//$modules=array();
// Show modules   
$modules=[];
foreach ($modules as $id=>$module){
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
                                    echo $module->content;
    if($modules_tag == 'dl')                echo '</dd>';
    if($modules_tag == 'table')             echo '</td>';
    
    if($modules_tag == 'ul')            echo "</li>";
    else if($modules_tag == 'table')    echo "</tr>";
    else if ($modules_tag=='div div')   echo "</div>";
	else if ($modules_tag=='dl')        echo "";
	else if ($module->module_tag=='')   echo "";//echo "</div>";
    else
        echo "</$module->module_tag>";
    //echo "$content";
}

echo "</div>";
if ($modules_tag=='div div' && $mod_show)
    echo " </div></div></div>";
 
echo "</div>";


?>

