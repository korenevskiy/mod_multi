<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_articles
 *
 * @copyright   (C) 2024 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die();

use Joomla\CMS\Extension\Service\Provider\HelperFactory;
use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * The articles module service provider.
 *
 * @since 5.2.0
 */
return new class() implements ServiceProviderInterface {

    /**
     * Registers the service provider with a DI container.
     *
     * @param Container $container
     *            The DI container.
     *            
     * @return void
     *
     * @since 5.2.0
     */
    public function register(Container $container)
    {
        $container->registerServiceProvider(new ModuleDispatcherFactory('\\Joomla\\Module\\Multi'));
        $container->registerServiceProvider(new HelperFactory('\\Joomla\\Module\\Multi\\Site\\Helper')); // Joomla\Module\Multi\Site\Helper; MultiHelper
        $container->registerServiceProvider(new Module());

        // $reflector = new \ReflectionClass(new HelperFactory('\\Joomla\\Module\\Multi\\Site\\Helper\\'));
        // echo "<pre>";
        // echo print_r($reflector->getFileName(),true);
        // echo "</pre>";
        //
        // echo "<pre>";
        // echo print_r(get_class($this),true);
        // echo "</pre>";
        // echo "<pre>";
        // echo print_r((new HelperFactory('\\Joomla\\Module\\Multi\\Site\\Helper\\')),true);
        // echo "</pre>";
        // echo "<pre>";
        // echo print_r(get_class($container),true);
        // //echo print_r((new HelperFactory('\\Joomla\\Module\\Multi\\Site\\Helper\\'))->getHelper('MultiHelper'),true);
        // echo "</pre>";
    }
};
