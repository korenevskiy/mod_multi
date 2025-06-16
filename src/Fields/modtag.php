<?php // namespace \Joomla\Module\Multi\Site\Fields;

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

class JFormFieldModtag extends Joomla\CMS\Form\Field\ModuletagField //ModuletagField JFormFieldModuletag
{
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.0
	 */
	protected function getOptions()
	{
		
		if(JVersion::MAJOR_VERSION < 5)
			$this->default = 'div';
		else
			$this->default = '';
		
		$fieldname = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname);

		$options = parent::getOptions();

		foreach ($this->element->xpath('option') as $option)
		{
			$value = (string) $option['value'];
			$text  = trim((string) $option) != '' ? trim((string) $option) : $value;

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

        }

		reset($options);

		return $options;
	}
    protected function getInput() {

		if(JVersion::MAJOR_VERSION < 5)
			$hide_mod = "<style type='text/css'>#jform_params_module_tag-lbl,#jform_params_module_tag,#jform_params_module_tag_chzn{display:none;}</style>";
		else
			$hide_mod = "<style type='text/css'>#jform_params_mod_tag-lbl,#jform_params_mod_tag,#jform_params_mod_tag_chzn{display:none;}</style>";

        

        return parent::getInput().$hide_mod;
    }
}

