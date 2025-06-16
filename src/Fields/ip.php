<?php // namespace \Joomla\Module\Multi\Site\Fields;

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

class JFormFieldIp extends JFormField {

//  public $type = 'ip_info';

//    function folder_exist($folder)
//        {
//        /* Get canonicalized absolute pathname  */
//        $path = realpath($folder);
//
//        /* If it exist, check if it's a directory */
//        return ($path !== false AND is_dir($path)) ? $path : false;
//    }

	protected function getInput(){
	  
		return '<pre class="badge bg-secondary" role="alert" >'.($_SERVER['REMOTE_ADDR']??'') . '</pre> <pre  class="badge text-bg-secondary" role="alert" >'.($_SERVER['HTTP_X_FORWARDED_FOR']??'') . '</pre>';

        $query = " SELECT template FROM #__template_styles WHERE home=1 AND client_id=0; ";
        $template_name = JFactory::getDBO()->setQuery($query)->loadResult();

        $paths = array();
        $paths[] = (object) array('path'=> "/templates/$template_name/css/", 'type'=>' → tmpl/css');
        $paths[] = (object) array('path'=> "/templates/css/", 'type'=>' → tmpls/css');
        $paths[] = (object) array('path'=> "/templates/", 'type'=>' → tmpls');
        $paths[] = (object) array('path'=> "/modules/mod_multi/css/", 'type'=>' → mod/css');

        $files = array();
        foreach ($paths as $path) {
            if(!$this->folder_exist(JPATH_ROOT.$path->path))                continue;
            $fs = scandir(JPATH_ROOT.($path->path));
            foreach ($fs as $k=> $f)
                $fs[$k] = (object) array('path'=> $path->path.$f, 'name'=>($f.$path->type));
            $files = array_merge($files,$fs);
        }

        foreach($files as $f=>$file)  {
            if(strpos(strtolower($file->name),'.css' ) === false)
                    unset($files[$f]);
        }

        $i = 0;
        foreach ($files as $k=> $f){
            $files[$k]->Title =ucfirst($f->name);
            if($i==0)
                $value=$f->Title;
            $i++;
        }

        $value        = empty($this->value) ? $value : $this->value;

        return JHTML::_('select.genericlist', $files, $this->name,'class="inputbox   chzn-custom-value" id = "category_ordering"  multiple="multiple" ','path','Title', $value );
  }
}