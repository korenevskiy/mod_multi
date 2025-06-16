
<?php defined('_JEXEC') or die;

/**
 * Multi Form - Easy Ajax Form Module with modal window, with field Editor and create article with form data
 * 
 * @package    Joomla
 * @copyright  Copyright (C) Open Source Matters. All rights reserved.
 * @extension  Multi Extension
 * @subpackage Modules
 * @author		Korenevskiy Sergei.B
 * @license    GNU/GPL, see LICENSE.php
 * @link       http://exoffice/download/joomla
 * mod_multi_form 
 */


//namespace Joomla\Module\MultiForm\Site\Helper;

/**
 * Description of reg
 */
class Reg extends \Joomla\Registry\Registry{
//	public function __construct($data = null, string $extenstionSession = '', string $separator = '.'){
//		
//// Добавить сохранение в сессию в случае указанного $extenstionSession
//		
//		parent::__construct($data, $separator);
//	}
	
    /**
     * Sets the value of a user state variable.
     *
     * @param   string  $key    The path of the state.
     * @param   mixed   $value  The value of the variable.
     *
     * @return  mixed  The previous state, if one existed. Null otherwise.
     *
     * @since   3.2
     */
//    public function setUserState($key, $value)
//    {
//        $session  = $this->getSession();
//        $registry = $session->get('registry');
//
//        if ($registry !== null) {
//            return $registry->set($key, $value);
//        }
//
//        return null;
//    }
    /**
     * Gets a user state.
     *
     * @param   string  $key      The path of the state.
     * @param   mixed   $default  Optional default value, returned if the internal value is null.
     *
     * @return  mixed  The user state or null.
     *
     * @since   3.2
     */
//    public function getUserState($key, $default = null)
//    {
//        $registry = $this->getSession()->get('registry');
//
//        if ($registry !== null) {
//            return $registry->get($key, $default);
//        }
//
//        return $default;
//    }


	function __get($nameProperty) {
		return $this->get($nameProperty, '');
		
//		function get($path, $default = null)
		$path = $nameProperty;
		$default = '' ?? null;
		
        // Return default value if path is empty
        if (empty($path)) {
            return $default;
        }
		
		
//		isset($this->data->$path) && is_array($this->data->$path)

        if ($this->separator === null || $this->separator === '' || !\strpos($path, $this->separator)) {
            if (isset($this->data->$path) && $this->data->$path !== null && $this->data->$path !== '')
				return $this->data->$path;
			else
				return $default;
        }

        // Explode the registry path into an array
        $nodes = \explode($this->separator, \trim($path));

        // Initialize the current node to be the registry root.
        $node  = $this->data;
        $found = false;

        // Traverse the registry to find the correct node for the result.
        foreach ($nodes as $n) {
            if (\is_array($node) && isset($node[$n])) {
                $node  = $node[$n];
                $found = true;

                continue;
            }

            if (!isset($node->$n)) {
                return $default;
            }

            $node  = $node->$n;
            $found = true;
        }

        if (!$found || $node === null || $node === '') {
            return $default;
        }

        return $node;
	}
	function __set($nameProperty, $value = null) {
		$this->set($nameProperty, $value);
	}
	
	function __isset($nameProperty) {
		return $this->exists($nameProperty);
	}
	
	function __unset(string $nameProperty) {
		$this->remove($nameProperty);
	}
	
	function ArrayItem($nameProperty, $index = null, $value = null){
		
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
    public function bindData($parent, $data, $recursive = true, $allowNull = true){
		parent::bindData($parent, $data, $recursive, $allowNull);
		
	}
    /**
     * Constructor
     *
     * @param  mixed   $data       The data to bind to the new Registry object.
     * @param  string  $separator  The path separator, and empty string will flatten the registry.
     * @param  bool	   $recursive  True to support recursive bindData.
     *
     * @since   1.0.0
     */
    public function __construct($data = null, string $separator = '.', $recursive = false)
    {
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
}
