# basicinvoices-modulemanager

This module basically allows you to load modules from a database table.

## Installation

Add this project in your `composer.json`:

    "require": {
        "basicinvoices/basicinvoices-modulemanager": "dev-master"
    }

Tell composer to download the module by running:

    composer update
    
### Post installation

Enable the module in your application by editting `module.config.php`. As an example:

    return [
        'Zend\Db',
        'BasicInvoices\ModuleManager',
        'Zend\Validator',
        'Application',
    ];

Create the database table.

    CREATE TABLE IF NOT EXISTS `modules` (
     `name` varchar(100) NOT NULL,
     `description` varchar(255) DEFAULT NULL,
     `active` tinyint(1) NOT NULL DEFAULT '0',
     UNIQUE KEY `UX_MODULE_NAME` (`name`),
     KEY `IX_ACTIVE_MODULE` (`active`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

The description column is not really needed but comes in handy if building a controller to load or unload the modules.

The module will only load the `active` modules.

Enable the database configuration by editting `global.php`

     return [
         'db' => [
             'driver'         => 'Pdo',
             'dsn'            => 'mysql:dbname=basic_invoices;host=localhost',
             'driver_options' => [
                 PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
             ],
         ],
     ];

...and the database credentials in your `local.php`

    return [
         'db' => [
             'username' => 'YOURUSERNAME',
             'password' => 'YOURPASSWORD',
         ],
     ];
    
...and you are ready to go!