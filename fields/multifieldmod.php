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

class JFormFieldMultiFieldMod extends JFormFieldList  {

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

	/**
	 * Method to get a control group with label and input.
	 *
	 * @param   array  $options  Options to be passed into the rendering of the field
	 *
	 * @return  string  A string containing the html for the control group
	 *
	 * @since   3.2
	 */

  /**
   * Инициализация элемента
   * @param SimpleXMLElement $element
   * @param mixed $value
   * @param string $group
   * @return bool
   */
    public function setup($element, $value, $group = NULL){
        $return = parent::setup($element, $value);

        $attribs = ['parent','class','multiple','default','key_field','value_field','required','translate_default','query','filter','exclude','stripext',
            'hide_default','hide_none','directory','','header_tag','header_class','style','maxlength','hiddenLabel','Label','','','','','','','','','','','','','','',''];
        foreach ($attribs as $attr){
            if($this->getAttribute($attr))
                $this->$attr = $this->getAttribute($attr);
        }

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
            'assignment','files','modulelayout','','','','','','','','','','',''];
        $fields_group = [ 'menuitem'];

        if(in_array($this->parent, $fields_list)){
        }
        if(in_array($this->parent, $fields_group)){
        }

        if($this->class_new instanceof JFormFieldList){

        }
        if($this->class_new instanceof JFormFieldGroupedList){

        }

        return $return;
    }
/**
 * Опции элемента
 * @return array Опции
 */
    protected function getOptions() {
        $options = parent::getOptions();

        foreach ($options as $opt){
            $this->class_new->addOption($opt->text, $opt);
        }

        return $this->class_new->getGroups();

        return parent::getOptions();
    }
 /**
 * Опции элемента
 * @return array Опции
 */

    protected function getInput(){

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

    }

}

//    /**
//     *
//     * @param \SimpleXMLElement $element
//     * @param mixed $value
//     * @param string $group
//     * @return boolean
//     */

