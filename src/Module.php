<?php
/**
 * Basic Invoices
 *
 * @link      http://github.com/basicinvoices
 * @copyright Copyright (c) 2016 Juan Pedro Gonzalez Gutierrez
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace BasicInvoices\ModuleManager;

use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;

class Module
{
    public function init(ModuleManagerInterface $manager)
    {
        $sharedManager = $manager->getEventManager()->getSharedManager();
        $sharedManager->attach(ModuleManager::class, ModuleEvent::EVENT_LOAD_MODULES_POST, new Listener\ModuleLoaderListener(), 9000);
    }
}