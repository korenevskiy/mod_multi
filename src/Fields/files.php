<?php 
// namespace \Joomla\Module\Multi\Site\Fields;
defined('_JEXEC') or die();
/**
 * ------------------------------------------------------------------------
 * mod_multi - Modules Conatinier
 * ------------------------------------------------------------------------
 * author Sergei Borisovich Korenevskiy
 * Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
 *
 * @package mod_multi
 * @license GPL GNU General Public License version 2 or later;
 *          Websites: //explorer-office.ru/download/joomla/category/view/1
 *          Technical Support: Forum - //fb.com/groups/multimodule
 *          Technical Support: Forum - //vk.com/multimodule
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\HTML\HTMLHelper as JHtml;

// class JFormFieldFiles extends JFormField {
// class FilelistField extends ListField {
// Joomla\CMS\Form\Field\FilesField
// Joomla\Component\Modules\Administrator\Field\FilesField
// JFormFieldFiles
class JFormFieldFiles extends \Joomla\CMS\Form\Field\ListField
{

    public $type = 'files';

    /**
     * Name of the layout being used to render the field
     *
     * @var string
     * @since 4.0.0
     */
    // joomla.form.field.list
    // joomla.form.field.list-fancy-select
    protected $layout = 'joomla.form.field.list-fancy-select';

    /**
     * Method to get the field options.
     *
     * @return object[] The field option objects.
     *        
     * @since 3.7.0
     */
    protected function getOptions()
    {
        $query = " SELECT template FROM #__template_styles WHERE home=1 AND client_id=0; ";
        $template_name = \JFactory::getDBO()->setQuery($query)->loadResult();

        $exts = '/' . $this->getAttribute('extensions') . '/' . $this->getAttribute('extension') . '/' . $this->getAttribute('ext') . '/' . $this->getAttribute('types');

        $exts = str_replace([
            ',',
            '|',
            ';',
            ':',
            '.',
            ' '
        ], '/', $exts);
        $exts = array_filter(explode('/', $exts));

        $paths = array();

        foreach ($exts as $i => $ext) {
            $paths[] = (object) array(
                'path' => "/templates/$template_name/$ext/",
                'type' => " → tmpl/$ext"
            );
            $paths[] = (object) array(
                'path' => "/templates/$ext/",
                'type' => " → tmpls/$ext"
            );
            $paths[] = (object) array(
                'path' => "/modules/mod_multi/$ext/",
                'type' => " → mod/$ext"
            );
        }

        $paths[] = (object) array(
            'path' => "/templates/$template_name/media/",
            'type' => " → tmpl/media"
        );
        $paths[] = (object) array(
            'path' => "/templates/media/",
            'type' => " → tmpls/media"
        );
        $paths[] = (object) array(
            'path' => "/templates/",
            'type' => ' → tmpls'
        );
        $paths[] = (object) array(
            'path' => "/modules/mod_multi/media/",
            'type' => " → mod/media"
        );

        if ($directory = $this->getAttribute('directory', ''))
            $paths[] = (object) array(
                'path' => '/' . $directory,
                'type' => " → $directory"
            );

        if ($dirname = $this->getAttribute('dirname', '') && empty($directory))
            $paths[] = (object) array(
                'path' => '/' . $dirname,
                'type' => " → $dirname"
            );

        $paths[] = (object) array(
            'path' => "/modules/mod_multi/media/",
            'type' => " → mod/media"
        );

        $files = parent::getOptions();
        foreach ($paths as $path) {

            if (! $this->folder_exist(JPATH_ROOT . $path->path))
                continue;

            $fs = scandir(JPATH_ROOT . ($path->path));

            foreach ($fs as $k => $f) {

                if (substr($f, 0, 1) == '.')
                    continue;

                $ext = pathinfo($path->path . $f, PATHINFO_EXTENSION);

                if (! in_array($ext, $exts))
                    continue;

                $files[] = (object) array(
                    'value' => $path->path . $f,
                    'text' => $f . $path->type,
                    'path' => $path->path . $f,
                    'name' => ($f),
                    'Title' => ($f . $path->type),
                    'type' => $path->type,
                    'ext' => $ext
                );
            }
        }

        // toPrint($files,'$files:'.$this->name,0,'message');

        if ($this->default) {
            $this->default = str_replace([
                ',',
                '|',
                ';',
                ':',
                ' '
            ], '/', $this->default);
            $this->default = explode('/', $this->default);
        }

        return array_filter($files);
        // JHTML::_('select.genericlist', $files, $this->name,'class="inputbox chzn-custom-value" id = "category_ordering" multiple="multiple" ','path','Title', $value );
    }

    /**
     * Method to get the field input markup for a generic list.
     * Use the multiple attribute to enable multiselect.
     *
     * @return string The field input markup.
     *        
     * @since 3.7.0
     */
    protected function getInput()
    {

        // toPrint($this->value,'$this->value:'.$this->name ,0,'message');
        return parent::getInput();
    }

    function folder_exist($folder)
    {
        /* Get canonicalized absolute pathname */
        $path = realpath($folder);

        /* If it exist, check if it's a directory */
        return ($path !== false and is_dir($path)) ? $path : false;
    }
}