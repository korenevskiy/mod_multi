<?php
/**------------------------------------------------------------------------
 * mod_multi - Modules Conatinier 
 * ------------------------------------------------------------------------
 * author    Sergei Borisovich Korenevskiy
 * Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
 * @package  mod_multi
 * @license  GPL   GNU General Public License version 2 or later;  
 * Websites: //explorer-office.ru/download/joomla/category/view/1
 * Technical Support:  Forum - //fb.com/groups/multimodule
 * Technical Support:  Forum - //vk.com/multimodule
 */ 
defined('_JEXEC') or die;

                          
require_once (JPATH_SITE.'/administrator/components/com_content/models/fields/modulesmodule.php');

class JFormFieldModules extends JFormFieldModulesModule // extends JFormField
{ 
//  public $type = 'article';
  
//  protected function getInput(){
//        
//        require_once (JPATH_SITE.'/modules/mod_multi/helper.php'); 
//        $tmp = new stdClass();  
//        $tmp->category_id = "";
//        $tmp->name = JText::_('JALL');
//        $categories_1  = array($tmp);
//        $categories_select =array_merge($categories_1 , buildTreeCategory(0)); 
//        $ctrl  =  $this->name ;   
//        //$ctrl  = $this->control_name .'['. $this->name .']';   
//        //$ctrl  = 'jform[params][catids]'; 
//        $ctrl .= '[]'; 
//        
//        $value        = empty($this->value) ? '' : $this->value;    
//
//        return JHTML::_('select.genericlist', $categories_select,$ctrl,'class="inputbox" id = "category_ordering" multiple="multiple"','category_id','name', $value );
//  }
}
?>