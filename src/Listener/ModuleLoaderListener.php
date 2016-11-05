<?php
/**
 * Basic Invoices
 *
 * @link      http://github.com/basicinvoices
 * @copyright Copyright (c) 2016 Juan Pedro Gonzalez Gutierrez
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace BasicInvoices\ModuleManager\Listener;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Sql;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Stdlib\ArrayUtils;

class ModuleLoaderListener
{
    /**
     * @param  ModuleEvent $e
     * @return void
     */
    public function __invoke(ModuleEvent $e)
    {
        // The modules have been loaded
        $target = $e->getTarget();
        
        if (!$target instanceof ModuleManagerInterface) {
            return;
        }
        
        $configListener = $e->getConfigListener();
        $config         = $configListener->getMergedConfig(false);
        
        $modules   = $target->getModules();
        $adapter   = new Adapter($config['db']);
        $sql       = new Sql($adapter);
        $select    = $sql->select('modules');
        $select->columns(['name']);
        $select->where(['active' => true]);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();
        
        if (($result instanceof ResultInterface) && ($result->isQueryResult()) && ($result->getAffectedRows())) {
            while ($current = $result->current()) {
                if (!in_array($current['name'], $modules)) {
                    $target->loadModule($current['name']);
                    $modules[] = $current['name'];
        
                    $module = $target->getModule($current['name']);
        
                    if (($module instanceof ConfigProviderInterface) || (is_callable([$module, 'getConfig']))) {
                        // Merge the config
                        $moduleConfig = $module->getConfig();
                        $config = ArrayUtils::merge($config, $moduleConfig);
                    }
                }
        
                $result->next();
            }
        
            $target->setModules($modules);
            $configListener->setMergedConfig($config);
        }
    }
}
