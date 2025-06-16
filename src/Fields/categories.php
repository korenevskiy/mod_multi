<?php  // namespace \Joomla\Module\Multi\Site\Fields;
defined('_JEXEC') or die;
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

class JFormFieldCategories extends JFormField {

  public $type = 'categories';

  protected function getInput(){
        require_once (JPATH_SITE.'/modules/mod_jshopping_product_calendar/helper.php');
        $tmp = new stdClass();
        $tmp->category_id = "";
        $tmp->name = JText::_('JALL');
        $categories_1  = array($tmp);
        $categories_select =array_merge($categories_1 , buildTreeCategory(0));
        $ctrl  =  $this->name ;

        $ctrl .= '[]';

        $value        = empty($this->value) ? '' : $this->value;

        return JHTML::_('select.genericlist', $categories_select,$ctrl,'class="inputbox" id = "category_ordering" multiple="multiple"','category_id','name', $value );
  }
}
?>