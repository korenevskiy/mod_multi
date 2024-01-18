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

class JFormFieldFields extends Joomla\CMS\Form\Field\ListField {

  /**
   *
   * @var string
   */
  public $type = 'Fields';
  
  
    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     *
     * @since   4.4.0
     */
    protected function getOptions(){
		
		$db = JFactory::getDbo();
//        $db->setQuery(" SHOW COLUMNS FROM `#__jshopping_products` WHERE Type='datetime'; ");
        $db->setQuery("
SELECT id `value`, id, CONCAT(`title`,' \t /',`id`,'(',`type`,')',IF(`state`, '??', '??') ) `text`, 0  `disable`, '' `class`, 0 `selected`, 0 `checked`, '' `onclick`, '' `onchange`
FROM  `#__fields`
WHERE `context`='com_content.article' AND `only_use_in_subform` = 0 AND `type` = 'text'; ");
		
        $fields = $db->loadObjectList('id');
		
        ksort($fields);
		
		return array_merge(parent::getOptions(), $fields);
	}
}