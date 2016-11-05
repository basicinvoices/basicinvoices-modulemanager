<?php
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