<?php  // namespace \Joomla\Module\Multi\Site\Fields;
defined('_JEXEC') or die;
/**------------------------------------------------------------------------
 * field_cost - Fields for accounting and calculating the cost of goods
 * ------------------------------------------------------------------------
 * author    Sergei Borisovich Korenevskiy
 * Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
 * @package  mod_multi_form
 * @license  GPL   GNU General Public License version 2 or later;
 * Websites: //explorer-office.ru/download/joomla/category/view/1
 * Technical Support:  Forum - //fb.com/groups/multimodule
 * Technical Support:  Forum - //vk.com/multimodule
 */

use Joomla\CMS\Plugin\PluginHelper as JPluginHelper;
use Joomla\Registry\Registry as JRegistry;
use Joomla\CMS\Form\Field\ListField as JFormFieldList;
use \Joomla\CMS\Form\FormField as JFormField;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Factory as JFactory;

use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\CMS\Layout\LayoutHelper as JLayoutHelper;
use Joomla\CMS\Layout\FileLayout as JLayoutFile;
use \Joomla\CMS\Version as JVersion;
use Joomla\CMS\Form\Form as JForm;
use Joomla\CMS\Language\Language as JLanguage;

if(!function_exists('toPrint') && file_exists(JPATH_ROOT . '/functions.php')){
	require_once  JPATH_ROOT . '/functions.php';
// toPrint(null,'' ,0,'');
}

class JFormFieldTranslate extends JFormField  {
	
	public $fieldname = '';

	public function __construct($form = null) {
		parent::__construct($form);

		$this->file = '';

		$this->path = dirname(__DIR__.'/');

		$option1 = basename(dirname(dirname(dirname(__DIR__))));
		$option2 = basename(dirname(dirname(__DIR__)));
		$option3 = basename(dirname(__DIR__));

		if($option2 == 'components')
			$this->file = $option3;
		if($option2 == 'modules')
			$this->file = $option3;
		if($option1 == 'plugins')
			$this->file = 'plg_'.$option2.'_'.$option3;

	}

	public function setup(\SimpleXMLElement $element, $value, $group = null) {

		if(JFactory::getApplication()->getConfig()->get('debug'))
			static::languageMinificationRaw();
		
		$this->element = $element;

		if($element['path']){
			$this->path = (string)$element['path'];

			$option1 = basename(dirname(dirname(dirname($this->path))));
			$option2 = basename(dirname(dirname($this->path)));
			$option3 = basename(dirname($this->path));

			if($option2 == 'components')
				$this->file = $option3;
			if($option2 == 'modules')
				$this->file = $option3;
			if($option1 == 'plugins')
				$this->file = 'plg_'.$option2.'_'.$option3;
		}

		if($element['file'])
			$this->file =  (string)$element['file'];

		if($element['option'])
			$this->file =  (string)$element['option'];

		if(str_ends_with($this->file, '.ini'))
			$this->file = substr($this->file, 0, -4);

		$this->path = rtrim($this->path,'/');

		if(str_ends_with($this->path, '/language'))
			$this->path = substr($this->path, 0, -9);

		$lang = JFactory::getApplication()->getLanguage();

		$lang->load($this->file, $this->path);
		$lang->load($this->file, $this->path.'/language');
		$lang->load($this->file, JPATH_SITE);
		$lang->load($this->file, JPATH_ADMINISTRATOR);

		$pathsOption = $lang->getPaths($this->file);

		return true;
	}

	public $path = '';

	public $file = '';

	public function getInput() {
		return '';
	}
	public function getLabel() {
		
		$id = JFactory::getApplication()->getInput()->getInt('id');
		if($id)
			return "<a href='/index.php?option=com_ajax&module=multi&method=get&format=raw&id=$id'>Link Test Ajax</a><hr>";
		else
			return '';
	}
	public function getTitle() {
		return "";
	}
	public function getId($fieldId, $fieldName) {
		return '';
	}	
	public static function languageMinificationRaw(){
		
		
		$lang = JFactory::getApplication()->getLanguage();
		
		$dir = getcwd();
		
		$dir = __DIR__;
		
		while(!file_exists($dir . '/language')){
			$dir = dirname($dir);
		}

		$dir .= '/language';
		
		$files = Joomla\Filesystem\Folder::files($dir, "raw.ini", true, true);
		
		foreach ($files as $file){
			$text = file_get_contents($file);
			$text = str_replace("\n[", "[{<!>}][", $text);
			$text = str_replace("]\n", "][{<!>}]", $text);
			$text = str_replace("\";\n", "\";[{<!>}]", $text);
			$text = str_replace("\"\n", "\"[{<!>}]", $text);
			$text = str_replace("\n", '', $text);
			$text = str_replace('[{<!>}]', PHP_EOL, $text);
			$file = str_replace(".raw.ini", '.ini', $file);
			$f = file_put_contents($file, $text);
		}
	}
}
