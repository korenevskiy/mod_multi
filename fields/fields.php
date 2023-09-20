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

class JFormFieldFields extends JFormField {

    /**
     *
     * @var string
     */
  public $type = 'Fields';

  protected function getInput(){
        require_once (JPATH_SITE.'/modules/mod_jshopping_product_calendar/helper.php');

        $db = JFactory::getDbo();
        $db->setQuery(" SHOW COLUMNS FROM `#__jshopping_products` WHERE Type='datetime'; ");

        $fields = $db->loadObjectList('Field');

        ksort($fields);

        $i = 0;
        foreach ($fields as $k=> $f){
            $fields[$k]->Title =ucfirst($f->Field);
            if($i==1)
                $value=$f->Field;
            $i++;
        }

        $value        = empty($this->value) ? $value : $this->value;

        return JHTML::_('select.genericlist', $fields, $this->name,'class="inputbox" id = "category_ordering"  ','Field','Title', $value );
  }
}