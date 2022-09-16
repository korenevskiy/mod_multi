<?php defined('_JEXEC') or die; // name = json_layout
/**------------------------------------------------------------------------
 * mod_multi - Modules Conatinier 
 * ------------------------------------------------------------------------
 * author    Sergei Borisovich Korenevskiy
 * Copyright (C) 2010 //explorer-office.ru. All Rights Reserved.
 * @package  mod_multi
 * @license  GPL   GNU General Public License version 2 or later;  
 * Websites: //explorer-office.ru/download/joomla/category/view/1
 * Technical Support:  Forum - //fb.com/groups/multimodule
 * Technical Support:  Forum - //vk.com/multimodule
 */ 

use Joomla\CMS\Form\Field\ModulelayoutField as JFormFieldModulelayout; 
use Joomla\CMS\Form\FormField as JFormField; 
use Joomla\CMS\Language\Text as JText; 
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\Registry\Registry as JRegistry;
//use Joomla\Registry\Factory as JFactory; 
use Joomla\CMS\Factory as JFactory;
use Joomla\Filesystem\Folder as JFolder;
use Joomla\Filesystem\File as JFile;
use Joomla\Filesystem\Path as JPath;
use Joomla\CMS\Document\Document as JDocument;
use Joomla\CMS\Application\SiteApplication as JAplication;
use Joomla\CMS\Form\Field\FilelistField as JFormFieldFileList; 
use \Joomla\CMS\Version as JVersion;
//JVersion::MAJOR_VERSION == 3

use Joomla\CMS\Editor\Editor as JEditor; 

JFormHelper::loadFieldClass('filelist');

//use Joomla\CMS\Form\Field\ListField as JFormFieldList;          
//use Joomla\CMS\Form\Field\FilelistField as JFormFieldFileList;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.path');

JFormHelper::loadFieldClass('list');
 
//Joomla\CMS\Form\Field\ModtagField
//JFormFieldModtag 
class JFormFieldLayoutjson extends JFormFieldFileList {

 
    public $hiddenLabel = true; 
	
    protected $directory = __DIR__ .'/../tmpl/';

    protected $fileFilter = '*.php';
    
    protected $labelclass = 'field-spacer';
    
    protected $class = 'field-spacer'; 
    
	/**
	 * Method to get a control group with label and input.
	 *
	 * @param   array  $options  Options to be passed into the rendering of the field
	 *
	 * @return  string  A string containing the html for the control group
	 *
	 * @since   3.7.3
	 */
	public function renderField($options = array())
	{
		$options['class'] = empty($options['class']) ? 'field-spacer' : $options['class'] . ' field-spacer';
        
        $showon = [];
        foreach ($this->getFileList() as $layout => $ini){
            $showon[] = "layout:_:$layout";//layout:full[OR]layout:intro[OR]layout:content
        }
//        $this->showon = implode('[OR]', $showon);
        
		return parent::renderField($options);
	}
    
    protected function getLabel(){ 
        $html = parent::getLabel();
        return $html;
    }
//	protected function getOptions() {
//	}
  
    protected function getFileList() {
        
        static $files_ini;
        
        
        if($files_ini)
            return $files_ini;
        
        $files_ini = [];
        
        $this->fileFilter = '^[^_]*\.php$';
        $this->filter = '^[^_]*\.php$';
        
        $path_mod = realpath(__DIR__.'/../');//JPATH_SITE.'/modules/mod_multi/';
        $this->directory = realpath(__DIR__.'/../tmpl/') ;
        
        $files = parent::getOptions();
         
         
        $lang = JFactory::getApplication()->getLanguage()->getTag();//ru-RU
        $lng = substr($lang, 0, 2) ;//ru
        
        
         
        
        foreach ($files as $file){
            if(substr($file->value, -3) != 'php')
                continue; 
            $file_name = substr($file->value, 0, -4);
            if(file_exists("$path_mod/language/$lang/$lang.mod_multi.$file_name.ini")){
                $files_ini[$file_name] =  file_get_contents("$path_mod/language/$lang/$lang.mod_multi.$file_name.ini");
                continue;
            }
            if(file_exists("$path_mod/language/$lng/$lng.mod_multi.$file_name.ini")){
                $files_ini[$file_name] =  file_get_contents("$path_mod/language/$lng/$lng.mod_multi.$file_name.ini");
                continue;
            } 
            if(file_exists("$path_mod/tmpl/$file_name.ini")){
                $files_ini[$file_name] =  file_get_contents("$path_mod/tmpl/$file_name.ini");
                continue;
            }
//			$options[] = HTMLHelper::_('select.option', '', Text::alt('JOPTION_USE_DEFAULT', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
                
        }
        
        
//        toPrint($lang,'$lang',5);
//        toPrint($lng,'$lng',5); 
        
        return $files_ini;
    }


    protected function getInput(){
		$html = '<br><label>JSON Layout Configuration</label>';
        $html .= $this->getEditor();
        $html .= '<br><label>JSON Layout Description</label>';
        $html .= $this->getHTML();
        return $html;        
    }

    protected function getHTML(){ 
        
         
//$script = <<< script
////jQuery( function() {
//
////    console.log(""); 
////    document.querySelector('#jform_params_layoutd').addEventListener('change', function (ev) {
////        console.log('Changed', ev.target.value)
////    });
//        
//    
////    jQuery("#jform_params_layoutd").slicebox({
////            {$json_slicebox}
////            
////    });
//            
////});  
//script;



//JFactory::getDocument()->addScriptDeclaration($script);
//        JFactory::getApplication()->getDocument()->addScriptDeclaration($script);
//        JDocument::getInstance()->addScriptDeclaration($script);

//data-showon="[{"field":"jform[params][layout]","values":["_:slider-owlCarousel"],"sign":"=","op":""}]"
//data-showon="[{"field":"jform[params][layout]","values":["_:slider-owlCarousel."],"sign":"=","op":""}]"
//&quot; &#x27;   &#10;
//showon="layout:_:slideshow-slicebox"

        
//        $style = " .layoutINI{display:none;
//    /*border: 1px solid #ccc;*/
//    /*height: calc(100vh - 400px);*/
//    height: calc(100vh - 800px);
//    min-height: 200px;
//    max-height: 500px;
//    max-width: 100%;
//    vertical-align: middle;
//    min-width: 450px;
//    overflow: scroll;}
//    .CodeMirror{
//    height: calc(100vh - 800px);
//    min-height: 200px;
//    }
//    ";

//	data-showon="[{
//		"field":"jform[params][layout]",
//		"values":["_:carousel-EasySlides"],
//		"sign":"=","op":""
//	},{
//		"field":"jform[params][layout]",
//		"values":["_:carousel-waterwheelCarousel"],
//		"sign":"=","op":"OR"
//	},{
//		"field":"jform[params][layout]",
//		"values":["_:slider-owlCarousel"],
//		"sign":"=","op":"OR"
//	},{
//	"field":"jform[params][layout]","values":["_:slider-slick"],"sign":"=","op":"OR"},{"field":"jform[params][layout]","values":["_:slideshow-3D-CSS3-Transforms"],"sign":"=","op":"OR"},{"field":"jform[params][layout]","values":["_:slideshow-CK"],"sign":"=","op":"OR"},{"field":"jform[params][layout]","values":["_:slideshow-blink"],"sign":"=","op":"OR"},{"field":"jform[params][layout]","values":["_:slideshow-jqFancyTransitions"],"sign":"=","op":"OR"},{"field":"jform[params][layout]","values":["_:slideshow-slicebox"],"sign":"=","op":"OR"}]"
//[{"field":"jform[params][layout]","values":["_:carousel-EasySlides"],"sign":"=","op":""}]
		$html = [''];
        $options = $this->getFileList();
//toPrint($options,'$options');
//_:slideshow-jqFancyTransitions        
        foreach ($options as $name => $opt){ 
			$showon = '[{"field":"jform[params][layout]","values":["_:'.$name.'"],"sign":"=","op":"","valid":0,"X":"1"}]';
//$html[] = "<pre>$name \t$showon</pre>";
            $html[] = "<pre style=' ' class='layoutINI $name '  data-showon='$showon' >$opt</pre>"; //data-file='$file'
        }
//        $html[] = "<script type='text/javascript'>$script</script>";
//        $html[] = "<style  type='text/css'>$style</style>";
        
       
		return implode($html);
        return '';
    }
    
    function folder_exist($folder)
    {
        // Get canonicalized absolute pathname
        $path = realpath($folder);

        // If it exist, check if it's a directory
        return ($path !== false AND is_dir($path)) ? $path : false;
    }
    
    function getEditor(){
        // Only create the editor if it is not already created.
        JFormHelper::loadFieldClass('editor');
		
        $this->editor = JEditor::getInstance('codemirror'); 
        
		$params = array(
//			'autofocus' => $this->autofocus,
//			'readonly'  => $this->readonly || $this->disabled,
//			'syntax'    => (string) $this->element['syntax'],
		);
 
		return $this->editor->display(
			$this->name,
			htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8'),
			$this->width,
			$this->height?:300,
			$this->columns,
			$this->rows?:20,
			$this->buttons ? (is_array($this->buttons) ? array_merge($this->buttons, $this->hide) : $this->hide) : false,
			$this->id,
			$this->asset,
			$this->form->getValue($this->authorField),
			$params
		);
    }
}
$style = "
.layoutINI{
/*   display:none; */ 
    border: 1px solid #ccc; /* */
    /*height: calc(100vh - 400px);  */
    height: calc(100vh - 800px);
    min-height: 200px;
    max-height: 500px;
    max-width: 100%;
    vertical-align: middle;
    min-width: 450px;
    overflow: scroll;
	
	white-space: pre;
}
.CodeMirror{
    height: calc(100vh - 800px);
    min-height: 200px;
}
";
JFactory::getApplication()->getDocument()->addStyleDeclaration($style);
return;
?>
<style type='text/css'>
.layoutINI{
    display:none;
    /*border: 1px solid #ccc;*/
    /*height: calc(100vh - 400px);*/
    height: calc(100vh - 800px);
    min-height: 200px;
    max-height: 500px;
    max-width: 100%;
    vertical-align: middle;
    min-width: 450px;
    overflow: scroll;
}
.CodeMirror{
    height: calc(100vh - 800px);
    min-height: 200px;
}
</style>