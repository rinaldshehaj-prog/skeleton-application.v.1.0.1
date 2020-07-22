<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */


use Zend\Session\Storage\SessionArrayStorage;

return [
    'db'							 => [
        'driver'						 => 'pdo',
        'dsn'   						 => 'mysql:dbname=zend;host=localhost;charset=utf8',
        'username'						 => 'rinald',
        'password'						 => 'Rini123$',
    ],
    'session_config' => [
        // Cookie expires in 1 hour
        'cookie_lifetime' => 60*60*1,
        // Stored on server for 30 days
        'gc_maxlifetime' => 60*60*24*30,
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
    ),
];
