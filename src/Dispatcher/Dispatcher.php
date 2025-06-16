<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_articles
 *
 * @copyright   (C) 2024 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Joomla\Module\Multi\Site\Dispatcher;

// \Joomla\Module\Multi\Site\Dispatcher\Dispatcher
use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\Multi\Site\Helper\JModHelp as JModuleHelper;
use Joomla\CMS\Factory as JFactory;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die();

// phpcs:enable PSR1.Files.SideEffects

/**
 * Dispatcher class for mod_articles
 *
 * @since 5.2.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    public function __construct(\stdClass $module, $app, $input)
    { // CMSApplicationInterface Input
        defined('MULTIMOD_PATH') || define('MULTIMOD_PATH', realpath(__DIR__ . '/../../'));
        defined('DS') || define('DS', DIRECTORY_SEPARATOR);
        // echo "<pre> xxx ";
        // echo print_r(get_class($app),true);
        // echo "</pre>";

        // if(empty(class_exists('\Reg')))
        // @include_once MULTIMOD_PATH . "/libraries/reg.php";

        // if(!function_exists('toPrint') && file_exists(MULTIMOD_PATH . '/libraries/functions.php'))
        // require_once MULTIMOD_PATH . '/libraries/functions.php';

        // if(!function_exists('toPrint') && file_exists(JPATH_ROOT . '/functions.php'))
        // require_once JPATH_ROOT . '/functions.php';

        parent::__construct($module, $app, $input);
    }

    /**
     * Load the language.
     *
     * @return void
     *
     * @since 4.0.0
     */
    protected function loadLanguage(string $module = '')
    {
        $language = $this->app->getLanguage();

        if (empty($module)) {
            $language->load('joomla', JPATH_ADMINISTRATOR);
            $language->load('com_modules', JPATH_ADMINISTRATOR);
            $language->load($this->module->module, MULTIMOD_PATH);
            parent::loadLanguage();
            return;
        }

        $module = $this->module->module;

        // $lineOne = $this->getApplication()->getLanguage()->_('MOD_FOOTER_LINE1');
        // $data['list'] = $this->getHelperFactory()->getHelper('LanguagesHelper')->getLanguages($data['params']);

        $coreLanguageDirectory = JPATH_BASE;
        $extensionLanguageDirectory = JPATH_BASE . '/modules/' . $module;

        $langPaths = $language->getPaths();

        // Only load the module's language file if it hasn't been already
        if (! $langPaths || (! isset($langPaths[$coreLanguageDirectory]) && ! isset($langPaths[$extensionLanguageDirectory]))) {
            // 1.5 or Core then 1.6 3PD
            $language->load($module, $coreLanguageDirectory) || $language->load($module, $extensionLanguageDirectory);
        }
    }

    /**
     * Returns the layout data.
     * Логика модуля - Получение данных.
     *
     * @return array|false
     *
     * @since 5.2.0
     */
    protected function getLayoutData()
    {

        // echo "<pre>";
        // echo print_r(get_class($this),true);
        // echo "</pre>";
        $data = parent::getLayoutData();

        if (empty(class_exists('\Reg')))
            @include_once MULTIMOD_PATH . '/libraries/reg.php';

        $data['params'] = new \Reg($this->module->params);

        // if( ! $params instanceof Reg)
        // $params = (new Reg())->merge($params);

        $param = &$data['params'];

        $mod = "module" . $this->module->id;
        $par = "params" . $this->module->id;
        ${$mod} = &$this->module;
        ${$par} = &$param;

        $data['mod'] = "module" . $this->module->id;
        $data['par'] = "params" . $this->module->id;
        $data[$mod] = &$this->module;
        $data[$par] = &$param;
		
//return $data;

        $helper = $this->getHelperFactory()->getHelper('MultiHelper');

        /**
         * Проверка условий показов
         */
        if ($this->module->position && $this->module->id && empty($this->module->ajax) && ! $helper::requireWork($param)) {
            $param->layout = 'empty';
            $param->style = 'System-none';
            return FALSE;
            return $data;
            return $helper::ModeuleDelete($module) ?: FALSE;
        }
        /**
         * Отключение внешнего Заголовка при пустом макете для того чтобы включился внутренний
         */
        if (in_array($param->style, [
            'System-none',
            'none',
            'no',
            '0',
            0,
            ''
        ], true))
            $this->module->showtitle = FALSE;

        if ($param->disable_module_empty_count && ! $param->description_show && ! $param->image_show && ! $param->position_show && ! $param->modules_show && ! $param->menu_show && ! $param->article_show && ! $param->images_show && ! $param->link_show && ! $param->html_show && ! $this->module->showtitle) {
            $param->layout = 'empty';
            $param->style = 'System-none';
            return FALSE;
            return $data;
        }
        // $module->published = '';
        // $module->position = '';
        // $module->module = '';
        // $module->style = 'System-none';

        // $module->ajax = false;

        // $this->
        // echo "<pre>55555 ";
        // echo print_r(get_class($helper),true);
        // echo "</pre>";

        $helper::srcAdd();

        // $attrib['contentOnly'] = true; //ContentHelperRoute

        /**
         * Завершение пустого модуля
         */
        // $module = & ${$mod};
        // $params = & ${$par};
        // $param = & ${$par};

        $data['modules'] = $helper::moduleLayoutData($param, $this->module, $this->app);

//return $data;
        $module = &$data['module'];

        $count_items = array_map(fn ($mods) => is_array($mods) ? count($mods) : 0, $data['modules']);
        $count_items = array_sum($count_items);

        // if(empty($param->module_tag2))
        // $param->moduleclass_sfx = "multimodule $param->moduleclass_sfx count$count_items id$module->id"; //multimodule$param->moduleclass_sfx2 count$count_items id$id $param->style

        // $module->params = $param->toString();

        if ($count_items == 0 && $param->disable_module_empty_count) {
            $param->layout = 'empty';
            $param->style = 'System-none';
            return FALSE;
            return $data;
        }

        // if($data['modules'] === null){
        // echo "<pre>mID: {$this->module->id} {$this->module->title}";
        // //echo print_r(get_class($helper),true);
        // echo "</pre>";
        // }

        // return $data;

        // $data['module'];
        // $data['app'];
        // $data['input'];
        // $data['params'];
        // $data['template'];

        // $this->module,
        // $this->app,
        // $this->input,
        // new Registry($this->module->params),
        // this->app->getTemplate(),

        // $param->style;
        // $param->layout;

        $fileAsset = MULTIMOD_PATH . "/modules/mod_multi/tmpl/{$param->layout}.asset.json";
        // if(file_exists($fileAsset)){
        // $wa = JFactory::getContainer()->get(WebAssetRegistry::class);
        // // $wa = JFactory::getApplication()->getDocument()->getWebAssetManager()->getRegistry();
        // $wa->addRegistryFile($fileAsset);
        // // JFactory::getApplication()->getDocument()->getWebAssetManager()->useScript('jquery-noconflict');
        // }

        $cacheParams = new \stdClass();
        $cacheParams->cachemode = 'id'; // id, safeuri, id-кеш зависит от своей формулы, safeuri-кеш зависит от URL параметров
        $cacheParams->class = $helper::class;
        // $cacheparams->class = 'Joomla\Module\Multi\Site\Helper\MultiHelper';
        $cacheParams->method = 'moduleLayoutData';
        $cacheParams->methodparams = [
            $param,
            $this->module,
            $this->app
        ];
        // $cacheParams->modeparams = ['id' => 'int', 'Itemid' => 'int']; // Если cachemode==safeuri, то это массив attribute`ов и типов
        $cacheParams->modeparams = md5(serialize([
            $this->module->id,
            $this->module->module,
            $param->layout
        ])); // Если cachemode==id, то это MD5

        $data['list'] = JModuleHelper::moduleCache($this->module, $param, $cacheParams);

        return $data;
    }
}
