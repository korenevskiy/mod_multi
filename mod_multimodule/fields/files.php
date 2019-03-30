<?php
defined('_JEXEC') or die;
 
class JFormFieldFiles extends JFormField {

  public $type = 'Files';
  
  protected function getInput(){ 
        //require_once (JPATH_SITE.'/modules/mod_jshopping_product_calendar/helper.php'); 
      
        //require_once (JPATH_SITE.'/modules/mod_jshopping_product_calendar/helper.php'); 
       // return '';
        
        $query = " SELECT template FROM #__template_styles WHERE home=1 AND client_id=0; ";
        $template_name = JFactory::getDBO()->setQuery($query)->loadResult();
        
    //    $message = $template_name.'->TEMPLATE<br>';
//        
    //    require_once JPATH_ROOT.'/templates/functions.php';
        
     //   $message = toPrint(array_keys((array)$this) ,'','',0,FALSE);
//        $message .= join(',',array_keys((array)$this))."<br>"; //type element fieldname value
//        $message.=$this->type.'->type<br>';
//        $message.=$this->element.'->element<br>';
//        $message.=$this->fieldname.'->fieldname<br>';
//        $message.=$this->value.'->value<br>'; 
//        $message.=$this->default.'->default<br>'; 
//        $message.=$this->name.'->name<br>'; 
    //    JFactory::getApplication()->enqueueMessage($message); 

        
        
        
        $exts = '/'.$this->getAttribute('extensions').'/'.$this->getAttribute('extension').'/'.$this->getAttribute('ext').'/'.$this->getAttribute('types');
        
        $exts = str_replace([',','|',';',':','.',' '], '/', $exts);
        $exts = explode('/', $exts);
//        $exts = explode('/|;:,. ', $exts);
        
         
//        toPrint($exts,'types');   return '';
   
   
        $paths = array();
        
        foreach ($exts as $i => $ext){
            if(empty($ext)) {unset ($exts[$i]);continue;}
            $paths[] = (object) array('path'=> "/templates/$template_name/$ext/", 'type'=>" → tmpl/$ext");
            $paths[] = (object) array('path'=> "/templates/$ext/", 'type'=>" → tmpls/$ext");
            $paths[] = (object) array('path'=> "/modules/mod_multimodule/$ext/", 'type'=>" → mod/$ext"); 
        }
        
        $paths[] = (object) array('path'=> "/templates/$template_name/media/", 'type'=>" → tmpl/media");
        $paths[] = (object) array('path'=> "/templates/media/", 'type'=>" → tmpls/media");
        $paths[] = (object) array('path'=> "/templates/", 'type'=>' → tmpls');
        $paths[] = (object) array('path'=> "/modules/mod_multimodule/media/", 'type'=>" → mod/media"); 
        
//        foreach ($paths as $p)
//            $message.= $p->path.'->'.$p->type.'<br>';
//        $message.= JPATH_ROOT.'->JPATH_ROOT <br>';
            
//        JFactory::getApplication()->enqueueMessage($message); 
        
//        if($this->default=='fonts')         
        
        // →
    //    
        $files = array();
        foreach ($paths as $path) {
                
            if(!$this->folder_exist(JPATH_ROOT.$path->path))                continue;
            
            $fs = scandir(JPATH_ROOT.($path->path));
            
//        toPrint($fs,'$fs',0);   
            foreach ($fs as $k=> $f){
//                toPrint(substr($f,0,1),''.$f);
                if(substr($f,0,1)=='.'){
                    unset($fs[$k]);  
                    continue;
                }
                $fs[$k] = (object) array('path'=> $path->path.$f, 'name'=>($f), 'Title'=>($f.$path->type), 'type'=>$path->type , 'ext'=>pathinfo($path->path.$f, PATHINFO_EXTENSION));
            }
            $files = array_merge($files,$fs);
        } 
         
    
//        toPrint($files,'$files',0);   
 //       return ''; 
        
//        $files  = array_diff ($files,array('..', '.'));
        foreach($files as $f=>$file){
//            $ex = pathinfo($file, PATHINFO_EXTENSION);
            //$ex = pathinfo($file);
//            toPrint($file->ext);
            if(empty( in_array($file->ext, $exts))){
                unset($files[$f]);                
                continue;
            }
//        toPrint($file,'$file',0);   
//            $files[$f]->Title = ucfirst($file->name); 
                
        } 

//        toPrint($exts,'$exts',0);   
//        toPrint($files,'$files');   
     
//        $db = JFactory::getDbo();
//        $db->setQuery(" SHOW COLUMNS FROM `#__jshopping_products` WHERE Type='datetime'; ");   
//        //$fields = $db->loadAssocList('Field','Field');
//        $fields = $db->loadObjectList('Field');
        
        if($this->default){
            $this->default = str_replace([',','|',';',':',' '], '/', $this->default);
            $this->default = explode('/', $this->default);
        }
        
        
        //fieldname:protected] => scryptFiles
        //  [name] => scryptFiles
//        toPrint($this->name,'name',0);
//        toPrint($this->fieldname,'fieldname',0);
         if($this->fieldname!='stylesheetfiles')
//        toPrint($this->value,'$this->value:'.$this->fieldname,0);
//        toPrint($this->default,'$this->default:'.$this->fieldname,0);
        
        if($this->value)
            $value = $this->value;
        elseif ($this->default) 
            $value = $this->default; 
        else 
            $value = '';
//        elseif($files){
//            $first = reset($files);        
//            $value = [$first->Title]; 
//          } 
         
//         if($this->fieldname!='stylesheetfiles')
//        toPrint($value,'$value:'.$this->fieldname,0);
//        toPrint($this->name,'$this->name:',0);
//        toPrint($this->fieldname,'$this->fieldname:',0);
  
        return JHTML::_('select.genericlist', $files, $this->name,'class="inputbox   chzn-custom-value" id = "category_ordering"  multiple="multiple" ','path','Title', $value );
        
    }
    
    function folder_exist($folder)
    {
        // Get canonicalized absolute pathname
        $path = realpath($folder);

        // If it exist, check if it's a directory
        return ($path !== false AND is_dir($path)) ? $path : false;
    }
}
