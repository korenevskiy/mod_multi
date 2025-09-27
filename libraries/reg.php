<?php \defined('JPATH_PLATFORM') or die;

/** ----------------------------------------------------------------------
 * Joomla modified Platform Registry class.
 * ------------------------------------------------------------------------
 * @package    Joomla
 * @copyright  Copyright (C) Open Source Matters. All rights reserved.
 * @extension  Multi Extension
 * @subpackage CMS
 * @author		Korenevskiy Sergei Borisovich
 * @license		GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://exoffice/download/joomla
 */


if(! class_exists('Reg')){
/**
 * Joomla modified Platform Registry class.
 */
class Reg extends \Joomla\Registry\Registry{
//	public function __construct($data = null, string $extenstionSession = '', string $separator = '.'){
//		
//// Добавить сохранение в сессию в случае указанного $extenstionSession
//		
//		parent::__construct($data, $separator);
//	}
	
	/**
	 * Constructor
	 *
	 * @param  mixed   $data       The data to bind to the new Registry object.
	 * @param  string  $separator  The path separator, and empty string will flatten the registry.
	 * @param  bool	   $recursive  True to support recursive bindData.
	 * @since   1.0.0
	 */
	public function __construct($data = null, string $separator = '.', $recursive = false) {
		$this->separator = $separator;

		// Instantiate the internal data object.
		$this->data = new \stdClass();

		// Optionally load supplied data.
		if ($data instanceof self) {
			$this->merge($data, $recursive);
		} elseif (\is_array($data) || \is_object($data)) {
			$this->bindData($this->data, $data, $recursive);
		} elseif (!empty($data) && \is_string($data)) {
			$this->loadString($data);
		}
	}
	
	/**
	 * Get a registry value.
	 *
	 * @param   string  $name     Registry path (e.g. joomla.content.showauthor)
	 *
	 * @return  mixed  Value of entry or null
	 *
	 * @since   1.0
	 */
	public function __get($nameProperty) {
		return $this->get($nameProperty, '');
	}
	
	/**
	 * Set a registry value.
	 *
	 * @param   string  $nameProperty	Registry Path (e.g. joomla.content.showauthor)
	 * @param   mixed   $value			Value of entry 
	 *
	 * @since   1.0
	 */
	public function __set($nameProperty, $value = null) : void {
		$this->set($nameProperty, $value);
	}
	
	/**
	 * Check if a registry path exists.
	 *
	 * @param   string  $name  Registry path (e.g. joomla.content.showauthor)
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function __isset($nameProperty) {
		return $this->exists($nameProperty);
	}
	
	public function __unset(string $nameProperty) : void {
		$this->remove($nameProperty);
	}
	
	public function ArrayItem($nameProperty, $index = null, $value = null){
		
		if(!isset($this->data->$nameProperty))
			$this->data->$nameProperty = [];
		
		
		if($index === null && $value === null)
			return $this->data->$nameProperty ?? [];
		
		$old = $this->data->$nameProperty[$index] ?? null;
		
		if($value === null)
			return $old;
		
		if($index === '' || $index === null)
			$this->data->$nameProperty[] = $value;
		else
			$this->data->$nameProperty[$index] = $value;
		
		return $old;
	}

	/**
	 * Method to recursively bind data to a parent object.
	 *
	 * @param  object   $parent     The parent object on which to attach the data values.
	 * @param  mixed    $data       An array or object of data to bind to the parent object.
	 * @param  boolean  $recursive  True to support recursive bindData.
	 * @param  boolean  $allowNull  True to allow null values.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function bindData($parent, $data, $recursive = true, $allowNull = true) {
		parent::bindData($parent, $data, $recursive, $allowNull);
		
	}
	/** 
	 * Delete a registry value 
	 * @param   string  $name  Registry Path (e.g. joomla.content.showauthor) 
	 * @return void
	 */
	public function __invoke($data): mixed
	{
		if($data instanceof \Joomla\Registry)
		{
			return $this->merge($data);
		}

		if(is_array($data))
		{
			return $this->loadArray($data);
		}

		if(is_a($data))
		{
			return $this->loadObject($data);
		}
	}

}
}
