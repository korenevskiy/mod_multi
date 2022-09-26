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

class JFormFieldFiles extends JFormField {

  public $type = 'Files';

  protected function getInput(){

        $query = " SELECT template FROM #__template_styles WHERE home=1 AND client_id=0; ";
        $template_name = JFactory::getDBO()->setQuery($query)->loadResult();

        $exts = '/'.$this->getAttribute('extensions').'/'.$this->getAttribute('extension').'/'.$this->getAttribute('ext').'/'.$this->getAttribute('types');

        $exts = str_replace([',','|',';',':','.',' '], '/', $exts);
        $exts = explode('/', $exts);

        $paths = array();

        foreach ($exts as $i => $ext){
            if(empty($ext)) {unset ($exts[$i]);continue;}
            $paths[] = (object) array('path'=> "/templates/$template_name/$ext/", 'type'=>" → tmpl/$ext");
            $paths[] = (object) array('path'=> "/templates/$ext/", 'type'=>" → tmpls/$ext");
            $paths[] = (object) array('path'=> "/modules/mod_multi/$ext/", 'type'=>" → mod/$ext");
        }

        $paths[] = (object) array('path'=> "/templates/$template_name/media/", 'type'=>" → tmpl/media");
        $paths[] = (object) array('path'=> "/templates/media/", 'type'=>" → tmpls/media");
        $paths[] = (object) array('path'=> "/templates/", 'type'=>' → tmpls');
        $paths[] = (object) array('path'=> "/modules/mod_multi/media/", 'type'=>" → mod/media");

        $files = array();
        foreach ($paths as $path) {

            if(!$this->folder_exist(JPATH_ROOT.$path->path))                continue;

            $fs = scandir(JPATH_ROOT.($path->path));

            foreach ($fs as $k=> $f){

                if(substr($f,0,1)=='.'){
                    unset($fs[$k]);
                    continue;
                }
                $fs[$k] = (object) array('path'=> $path->path.$f, 'name'=>($f), 'Title'=>($f.$path->type), 'type'=>$path->type , 'ext'=>pathinfo($path->path.$f, PATHINFO_EXTENSION));
            }
            $files = array_merge($files,$fs);
        }

        foreach($files as $f=>$file){

            if(empty( in_array($file->ext, $exts))){
                unset($files[$f]);
                continue;
            }

        }

//        $db->setQuery(" SHOW COLUMNS FROM `#__jshopping_products` WHERE Type='datetime'; ");

        if($this->default){
            $this->default = str_replace([',','|',';',':',' '], '/', $this->default);
            $this->default = explode('/', $this->default);
        }

         if($this->fieldname!='stylesheetfiles')

        if($this->value)
            $value = $this->value;
        elseif ($this->default)
            $value = $this->default;
        else
            $value = '';

        return JHTML::_('select.genericlist', $files, $this->name,'class="inputbox   chzn-custom-value" id = "category_ordering"  multiple="multiple" ','path','Title', $value );

    }

    function folder_exist($folder)
    {

        $path = realpath($folder);

        return ($path !== false AND is_dir($path)) ? $path : false;
    }
}

