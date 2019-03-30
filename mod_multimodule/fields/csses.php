<?php
defined('_JEXEC') or die;
 
class JFormFieldCsses extends JFormField {

  public $type = 'Csses';
 
    function folder_exist($folder)
        {
        // Get canonicalized absolute pathname
        $path = realpath($folder);

        // If it exist, check if it's a directory
        return ($path !== false AND is_dir($path)) ? $path : false;
    }
  
  protected function getInput(){ 
       
        //require_once (JPATH_SITE.'/modules/mod_jshopping_product_calendar/helper.php'); 
       // return '';
        
        $query = " SELECT template FROM #__template_styles WHERE home=1 AND client_id=0; ";
        $template_name = JFactory::getDBO()->setQuery($query)->loadResult();
        
        
      
        $paths = array();
        $paths[] = (object) array('path'=> "/templates/$template_name/css/", 'type'=>' → tmpl/css');
        $paths[] = (object) array('path'=> "/templates/css/", 'type'=>' → tmpls/css');
        $paths[] = (object) array('path'=> "/templates/", 'type'=>' → tmpls');
        $paths[] = (object) array('path'=> "/modules/mod_multimodule/css/", 'type'=>' → mod/css'); 
        
        // →
        
        $files = array();
        foreach ($paths as $path) {
            if(!$this->folder_exist(JPATH_ROOT.$path->path))                continue;
            $fs = scandir(JPATH_ROOT.($path->path));
            foreach ($fs as $k=> $f)
                $fs[$k] = (object) array('path'=> $path->path.$f, 'name'=>($f.$path->type));
            $files = array_merge($files,$fs);
        } 
         
        
        
//        $files  = array_diff ($files,array('..', '.'));
        foreach($files as $f=>$file)  {
            if(strpos(strtolower($file->name),'.css' ) === false)
                    unset($files[$f]);
        }

      
//        $db = JFactory::getDbo();
//        $db->setQuery(" SHOW COLUMNS FROM `#__jshopping_products` WHERE Type='datetime'; ");   
//        //$fields = $db->loadAssocList('Field','Field');
//        $fields = $db->loadObjectList('Field');
        
//        ksort($fields);
        $i = 0;
        foreach ($files as $k=> $f){
//            $fn = basename($f);
            //$fn = $f;
//            $f = (object)array('path'=>$f,'name'=>$fn );
//            $files[$k]= $f;
            $files[$k]->Title =ucfirst($f->name);
            if($i==0)
                $value=$f->Title;
            $i++;
        }
        
        $value        = empty($this->value) ? $value : $this->value;    
  
        return JHTML::_('select.genericlist', $files, $this->name,'class="inputbox   chzn-custom-value" id = "category_ordering"  multiple="multiple" ','path','Title', $value );
  }
}
