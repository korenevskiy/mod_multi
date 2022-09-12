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
 
JFormHelper::loadFieldClass('list'); 

class JFormFieldMultiFieldMod extends JFormFieldList  {//JFormField

  public $type = 'MultiFieldMod';
  public $class_new = '';
  public $options = [];
  
  	/**
	 * Render a layout of this field
	 *
	 * @param   string  $layoutId  Layout identifier
	 * @param   array   $data      Optional data for the layout
	 *
	 * @return  string
	 *
	 * @since   3.5
	 */
//	public function render($layoutId, $data = array())
//	{
//		$data = array_merge($this->getLayoutData(), $data);
//
//		return $this->getRenderer($layoutId)->render($data);
//	}

	/**
	 * Method to get a control group with label and input.
	 *
	 * @param   array  $options  Options to be passed into the rendering of the field
	 *
	 * @return  string  A string containing the html for the control group
	 *
	 * @since   3.2
	 */
//	public function renderField($options = array())
//	{
//            
//	}
        
  /**
   * Инициализация элемента
   * @param SimpleXMLElement $element
   * @param mixed $value
   * @param string $group
   * @return bool 
   */ 
    public function setup($element, $value, $group = NULL){ 
        $return = parent::setup($element, $value);
        //Атрибуты для копирования
        $attribs = ['parent','class','multiple','default','key_field','value_field','required','translate_default','query','filter','exclude','stripext',
            'hide_default','hide_none','directory','','header_tag','header_class','style','maxlength','hiddenLabel','Label','','','','','','','','','','','','','','',''];
        foreach ($attribs as $attr){
            if($this->getAttribute($attr))
                $this->$attr = $this->getAttribute($attr);
        }
        
        
        
        //$options[] = JHtml::_('select.option', '', JText::alt('JOPTION_USE_DEFAULT', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
        
        
        
        //$this->options = [];//$this->getOptions(); // опции из XML
        //toPrint($this->options,'$this->options',0);
//        toPrint($element,'$$element',0);
//        toPrint($this,'$this',0);
//-----------------------//-----------------------//-----------------------//-----------------------//-----------------------        
        //-----------------------//-----------------------//-----------------------
// -ПРОВЕРИТЬ ВСЕ ТИПЫ СПИСКОВ 
// -ВЫДЕЛИТЬ ОПЦИИ ИЗ XML.
// -ПРОВЕРИТЬ НАСЛЕДУЕМЫЙ КЛАСС (grouplist или list) и выполнять разные действия
// -Вызывать из getOptions() getGroup, так как класс этот наследуется от List
// -
        
        //Атрибуты каждой опции
        $option_attribs = ['value','text','disable','class','selected','checked','onclick','onchange','group']; 
         
        $classname_new = 'JFormField'. ucfirst($this->getAttribute('parent')) ; 
        
        JLoader::load($classname_new);
        JLoader::import($classname_new);
        
        if(class_exists($classname_new))
            $this->class_new = new $classname_new; 
        else 
            $this->class_new = new JFormFieldList;
        
        
        $this->class_new->setup($element, $value, $group);
        
        $fields_list = ['contenttype','radio','list','chromestyle','type_module','sql','moduletag','menu','category','category','filelist',
            'assignment','files','modulelayout','','','','','','','','','','',''];// типы полей для которых 
        $fields_group = [ 'menuitem'];// типы полей для которых 

// toPrint($options,'$options '.$classname_new.' '. (class_exists($classname_new)?'Yes':'No'),0,TRUE,TRUE);
// $this->getOptions();
//        return $return;
 
        if(in_array($this->parent, $fields_list)){ 
        }
        if(in_array($this->parent, $fields_group)){ 
        }
        
        if($this->class_new instanceof JFormFieldList){       
// toPrint($this->getOptions(),'getOptions',0,TRUE,TRUE);     
        }
        if($this->class_new instanceof JFormFieldGroupedList){    
// toPrint($this->getGroups(),'getGroups',0,TRUE,TRUE);     
        }
        
        return $return;
    }
/**
 * Опции элемента
 * @return array Опции
 */
    protected function getOptions() {
        $options = parent::getOptions(); // Переделать, чтобы опции брались из XML
        
        foreach ($options as $opt){ 
            $this->class_new->addOption($opt->text, $opt);
        }
// toPrint($options,'$options',0,TRUE,TRUE);
 
        return $this->class_new->getGroups();
 
        return parent::getOptions();
    }
 /**
 * Опции элемента
 * @return array Опции
 */
//    protected function getGroups() { 
//        $options = parent::getOptions();
//        $groups = []; $lang = '';
// toPrint($options,'$options',0,TRUE,TRUE);
//        foreach ($options as $opt){ 
//            if(empty($opt->group))
//                $groups[JText::_('JDEFAULT')][] = JHtml::_('select.option',
//                $opt->value, $opt->text . $lang,
//                'value',
//                'text'
//                    //,in_array($link->type, $this->class_new->disable)
//                );
//            else 
//                $groups[JText::_($opt->group)][] = JHtml::_('select.option',
//                $opt->value, $opt->text . $lang,
//                'value',
//                'text'
//                    //,in_array($link->type, $this->class_new->disable)
//                );
//        }
//            
//        $groups = array_merge($this->class_new::getGroups(), $groups);
//        return $groups;
//    }

    protected function getInput(){ 
//        return  parent::getInput();
        //
        if($this->class_new)
            return $this->class_new->getInput();
        else 
            return JHTML::_('select.genericlist', $this->getOptions(), $this->name,'class="inputbox   chzn-custom-value" id = "category_ordering"  multiple="multiple" ','value','Title', $value );
        
        if($this->class_new instanceof JFormFieldList){       
            $type = 'list';
        }
        else if($this->class_new instanceof JFormFieldGroupedList){
            $type = 'group';
        }
        else{
            $type = 'field';
        }
        
                              
        if($type == 'group')
            return $this->class_new->getInput ();
        if($type == 'list')
            return $this->class_new->getInput ();
        
       
        
//        toPrint($this,'MultiModule');
        
    }
    
 
}


//class JFormFieldMultiModGroup extends JFormFieldGroupedList{
//    public $type = 'MultiModGroup';
//    public $class_new = '';
//    public $options = []; 
//    /**
//     * 
//     * @param \SimpleXMLElement $element
//     * @param mixed $value
//     * @param string $group
//     * @return boolean
//     */
//    public function setup(\SimpleXMLElement $element, $value, $group = null, $fieldtype = null): boolean {
//        $return = parent::setup($element, $value, $group);
//        
//        //Атрибуты для копирования
//        $attribs = ['parent','class','multiple','default','key_field','value_field','required','translate_default','query','filter','exclude','stripext',
//            'hide_default','hide_none','directory','','header_tag','header_class','style','maxlength','hiddenLabel','Label','','','','','','','','','','','','','','',''];
//        foreach ($attribs as $attr){
//            if($this->getAttribute($attr))
//                $this->$attr = $this->getAttribute($attr);
//        }
//        
//        
//        //Атрибуты каждой опции
//        $option_attribs = ['value','text','disable','class','selected','checked','onclick','onchange','group']; 
//         
//        $classname_new = 'JFormField'. ucfirst($this->getAttribute('parent')) ; 
//        
//        
//        if(class_exists($classname_new))
//            $this->class_new = new $classname_new; 
//        else 
//            $this->class_new = new JFormFieldGroupedList;
//        $this->class_new->setup($element, $value, $group);
//        
//        $fields_list = ['contenttype','radio','list','chromestyle','type_module','sql','moduletag','menu','category','category','filelist',
//            'assignment','files','modulelayout','','','','','','','','','','',''];// типы полей для которых 
//        $fields_group = [ 'menuitem'];// типы полей для которых 
//
// toPrint($options,'$options '.$classname_new.' '. (class_exists($classname_new)?'Yes':'No'),0,TRUE,TRUE);
// 
////        return $return;
// 
//        if(in_array($this->parent, $fields_list)){ 
//        }
//        if(in_array($this->parent, $fields_group)){ 
//        }
//        
//        if($this->class_new instanceof JFormFieldList){       
// toPrint($this->getOptions(),'getOptions',0,TRUE,TRUE);     
//        }
//        if($this->class_new instanceof JFormFieldGroupedList){    
// toPrint($this->getGroups(),'getGroups',0,TRUE,TRUE);     
//        }
//        
//        return $return;
//    }
//}
 