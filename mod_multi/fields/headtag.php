<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */ 

\defined('JPATH_PLATFORM') or die;
 
use Joomla\CMS\HTML\HTMLHelper as JHtml;

 



/**
 * Module Tag field.
 *
 * @since  3.0 
 */
//Joomla\CMS\Form\Field\ModtagField
//JFormFieldModtag
class JFormFieldHeadtag extends Joomla\CMS\Form\Field\HeadertagField //ModuletagField JFormFieldModuletag
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.0
	 */
//	protected $type = 'ModuleTag'; 
    
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.0
	 */
	protected function getOptions()
	{
		$fieldname = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname);
//        toPrint(array_keys((array)$this),'this',0,'pre', true);
//        $attr = ['labelclass','group','fieldname','name','class','type','element', 'form','formControl','id','input',];
//        foreach ($attr as $at)
//        toPrint(array_keys((array)$this->$at),$at,0,'pre', true);
        
//        toPrint( $this->labelclass,'labelclass',0,'pre', true);
//        toPrint( $this->class,'class',0,'pre', true);
//        toPrint($this->formControl,'formControl',0,'pre', true);
//        toPrint($this->id,'id',0,'pre', true);
//        toPrint($this->type,'type',0,'pre', true);
//        toPrint($this->name,'name',0,'pre', true);
//        toPrint($this->fieldname,'fieldname',0,'pre', true);
//        toPrint($this->group,'group',0,'pre', true);
//        toPrint($this->{'*input'},'input',0,'pre', true);
//        toPrint($this->element->option,'element-option',0,'pre', true)
//        toPrint(array_keys($this->name),'name',0,'pre', true);
        
        
		$options = parent::getOptions();
        
//        $options = [];
        
		foreach ($this->element->xpath('option') as $option)
		{ 
			$value = (string) $option['value'];
			$text  = trim((string) $option) != '' ? trim((string) $option) : $value;
//            $options[] = ['value'=>$value,'text'=>JText::alt($text, $fieldname),'disable'=>false];
            
			$disabled = (string) $option['disabled'];
			$disabled = in_array($disabled, ['true','disabled','1']);
			$disabled = $disabled || ($this->readonly && $value != $this->value);

			$checked = (string) $option['checked'];
			$checked = in_array($checked, ['true','checked','selected','1']);

			$selected = (string) $option['selected'];
			$selected = in_array($selected, ['true','selected','checked','1']);
            
			$options[] = array(
					'value'    => $value,
					'text'     => JText::alt($text, $fieldname),
					'disable'  => $disabled,
					'class'    => (string) $option['class'],
					'selected' => ($checked || $selected),
					'checked'  => ($checked || $selected),
                );
//            $options[] = [$value,$value];
//            toPrint( $text,'$text',0,'pre', true);
        }
//        toPrint( $options,'$options',0,'pre', true);
//        toPrint($this->form,'form',0,'pre', true);
        
//		$newtags    = array('figure', 'fieldset','li');
//
//		// Create one new option object for each tag
//		foreach ($newtags as $tag)
//		{
//			$options[] = JHtml::_('select.option', $tag, "&lt;$tag>");
//		}
        
		reset($options);

		return $options;
	}
    protected function getInput() {
        
        $hide_mod = "<style type='text/css'>#jform_params_header_tag-lbl,#jform_params_header_tag,#jform_params_header_tag_chzn{display:none;}</style>";
        
        return parent::getInput().$hide_mod;
    }
}
