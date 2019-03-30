<?php
defined('_JEXEC') or die;
 

class JFormFieldPosition extends JFormField // extends JFormField
{ 
  public $type = 'article';
  
  protected function getInput(){
              require_once (JPATH_SITE.'/administrator/components/com_content/models/fields/modal/helper.php'); 
//        require_once JPATH_ADMINISTRATOR . '/components/com_templates/helpers/templates.php';
//
//        JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_modules/helpers/html');
//        $clientId       = $this->item->client_id;
//        $state          = 1;
//        $selectedPosition = $this->item->position;
//        $positions = JHtml::_('modules.positions', $clientId, $state, $selectedPosition);
//
//        // Add custom position to options
//        $customGroupText = JText::_('COM_MODULES_CUSTOM_POSITION');
//
//        // Build field
//        $attr = array(
//                'id'          => 'multi_position',
//                'list.select' => $this->item->position,
//                'list.attr'   => 'class="chzn-custom-value" '
//                . 'data-custom_group_text="' . $customGroupText . '" '
//                . 'data-no_results_text="' . JText::_('COM_MODULES_ADD_CUSTOM_POSITION') . '" '
//                . 'data-placeholder="' . JText::_('COM_MODULES_TYPE_OR_SELECT_POSITION') . '" '
//        );
//
//        return JHtml::_('select.groupedlist', $positions, 'jform[multi_position]', $attr);
//      
      
        require_once (JPATH_SITE.'/modules/mod_multimodule/helper.php'); 
        $tmp = new stdClass();  
        $tmp->category_id = "";
        $tmp->name = JText::_('JALL');
        $categories_1  = array($tmp);
        $categories_select =array_merge($categories_1 , buildTreeCategory(0)); 
        $ctrl  =  $this->name ;   
        //$ctrl  = $this->control_name .'['. $this->name .']';   
        //$ctrl  = 'jform[params][catids]'; 
        $ctrl .= '[]';
        
        $value        = empty($this->value) ? '' : $this->value;    

        return JHTML::_('select.genericlist', $categories_select,$ctrl,'class="inputbox chzn-custom-value" id = "multi_position" multiple="multiple"','category_id','name', $value );
  }
}
?>