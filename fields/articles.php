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


use Joomla\CMS\Version as JVersion; 


if((new JVersion)->isCompatible('4')){
    require_once (JPATH_SITE.'/administrator/components/com_content/src/Field/Modal/ArticleField.php'); 
    
    class JFormFieldArticle extends Joomla\Component\Content\Administrator\Field\Modal\ArticleField // extends JFormField
    { //JFormFieldModal_Article
    }

}else{
    require_once (JPATH_SITE.'/administrator/components/com_content/models/fields/modal/article.php'); 
    class JFormFieldArticle extends JFormFieldModal_Article // extends JFormField
    { //JFormFieldModal_Article
    }
} 

 
?>
