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

use Joomla\CMS\Form\FormHelper as JFormHelper;
use Joomla\CMS\HTML\HTMLHelper as JHtml;

JFormHelper::loadFieldClass('list');

class JFormFieldLanguages extends JFormFieldList  {/*JFormField*/

 	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'Languages';

	/**
	 * Method to get the field options for category
	 * Use the extension attribute in a form to specify the.specific extension for
	 * which categories should be displayed.
	 * Use the show_root attribute to specify whether to show the global category root in the list.
	 *
	 * @return  array    The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
        $opts = [];
        $options = [];

        $opts['*']= ['*', '- - '.JText::_('JALL_LANGUAGE'). ": ★ " , '✔ - -'];//JHtml ✔

        foreach (Joomla\CMS\Language\LanguageHelper::getKnownLanguages() as $opt){
            $opts[$opt['tag']]= [$opt['tag'], "$opt[nativeName]: $opt[tag]" , ' ◯'];//JHtml ✔
        }
        foreach (Joomla\CMS\Language\LanguageHelper::getContentLanguages() as $opt){

            $opts[$opt->lang_code]= [$opt->lang_code, "$opt->title: $opt->lang_code", $opt->published?'✔':'◯'];//JHtml
        }
        
        foreach($opts as $opt){
            $options[$opt[0]]= JHtml::_('select.option', $opt[0], "$opt[1]: $opt[2]");//JHtml ✔✔✔✓✔
        }

        $options = array_merge($options,parent::getOptions() );

        return $options;

    }
}
