<?php

namespace Joomla\Module\Multi\Site\Helper;

/**
 * Joomla! Content Management System
 *
 * @copyright (C) 2005 Open Source Matters, Inc. <https://www.joomla.org>
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
use Joomla\CMS\Cache\CacheControllerFactoryInterface;
use Joomla\CMS\Cache\Controller\CallbackController;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Event\Module;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Profiler\Profiler;
use Joomla\Database\ParameterType;
use Joomla\Filesystem\Path;
use Joomla\Registry\Registry;
use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
// use Joomla\CMS\Helper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die();

// phpcs:enable PSR1.Files.SideEffects
abstract class JModHelp extends JModuleHelper
{

    static function &ModeuleDelete($module): int
    {
        $modules = &static::load();

        $count = 0;
        // toPrint(null,'',0, 'pre',true);
        // toPrint(count($modules),'$module 1', 0, 'message',true);

        foreach ($modules as $i => &$mod) {
            if ($mod->id == $module->id) {
                unset($modules[$i]); // Вызывает ошибку свойства Position объекта модуля,
                unset($mod);
            } elseif ($module->position == $mod->position) {
                $count ++;
            }
            // else{
            // $modules[$i]->position = $mod->position == null ? '' : $mod->position;
            // }
        }

        array_multisort($modules);
        // toPrint(count($modules),'$module 2', 0, 'message',true);

        $modules = &static::getModules($module->position);

        // $module->published = '';
        // $module->position = '';
        // $module->module = '';
        // $module->style = 'System-none';

        // $module->ajax = false;

        // echo "<h1>Приет дорогой друг</h1>" . $module->position . '<br>'; return;

        static $jj;
        if (is_null($jj))
            $jj = [];

        if (empty($count)) {
            JFactory::getDocument()->addStyleDeclaration("/* Module:$module->id */\n.container-$module->position{\ndisplay:none;}\n");
            JFactory::getDocument()->setBuffer(FALSE, [
                'type' => 'module',
                'name' => $module->position
            ]);
            JFactory::getDocument()->setBuffer(FALSE, [
                'type' => 'modules',
                'name' => $module->position
            ]);
            // toPrint(JFactory::getDocument()::$_buffer ,' ',0, 'message',true);
            // $buf = &JFactory::getDocument()::$_buffer['modules'][$module->position];
            // unset($buf);
            unset(JFactory::getDocument()::$_buffer['modules'][$module->position]);
            unset(JFactory::getDocument()::$_buffer['module'][$module->position]);
        }

        if (empty($count) && empty($jj[$module->position])) {
            $jj[$module->position] = '';
            $jj[$module->position] .= "/*m$module->id*/var pos_count = {};var pos_del = '$module->position';";
            $jj[$module->position] .= "if(document.querySelector('.grid-child.container-$module->position')){";
            $jj[$module->position] .= "pos_count[pos_del] = document.querySelector('.grid-child.container-$module->position').childElementCount??0;";
            $jj[$module->position] .= "if(pos_count[pos_del]==0) {document.body.classList.remove('has-$module->position');}";
            $jj[$module->position] .= "if(pos_count[pos_del]==0) {document.querySelector('.grid-child.container-$module->position').remove();}";
            $jj[$module->position] .= "}";
            $jj[$module->position] = "document.addEventListener('DOMContentLoaded', function(){ {$jj[$module->position]} });";
            JFactory::getDocument()->addScriptDeclaration($jj[$module->position]);
            // $script = "/*m$module->id*/document.body.classList.remove('has-$module->position');";
            // $script = "/*m$id*/document.body.classList.add('has-$module->position');";
        }
        if (($count)) {
            unset($jj[$module->position]);
        }

        unset($module);
        return $count;
    }
}

