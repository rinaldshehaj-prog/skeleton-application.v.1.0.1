<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog;

use Blog\Model\ImageTable;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class Module
{
    const VERSION = '3.1.4dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\PostTable::class => function ($container) {
                    $tableGateway = $container->get(Model\PostTableGateway::class);
                    $imageGateway = $container->get(Model\ImageTableGateway::class);
                    $translaterGateway = $container->get(Model\TranslaterTableGateway::class);

                    return new Model\PostTable($tableGateway, $imageGateway, $translaterGateway);
                },
                Model\PostTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Post());
                    return new TableGateway('Post', $dbAdapter, null, $resultSetPrototype);
                },
                Model\AdminTable::class => function ($container) {
                    $tableGateway = $container->get(Model\AdminTableGateway::class);
                    return new Model\AdminTable($tableGateway);
                },
                Model\AdminTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Admin());
                    return new TableGateway('Admin', $dbAdapter, null, $resultSetPrototype);
                },

                Model\UserTable::class => function ($container) {
                    $tableGateway = $container->get(Model\UserTableGateway::class);
                    return new Model\UserTable($tableGateway);
                },
                Model\UserTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('User', $dbAdapter, null, $resultSetPrototype);
                },

                Model\TranslaterTable::class => function ($container) {
                    $tableGateway = $container->get(Model\TranslaterTableGateway::class);
                    return new Model\TranslaterTable($tableGateway);
                },
                Model\TranslaterTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Translater());
                    return new TableGateway('Translater', $dbAdapter, null, $resultSetPrototype);
                },

                Model\ImageTable::class => function ($container) {
                    $tableGateway = $container->get(Model\ImageTableGateway::class);
                    return new Model\ImageTable($tableGateway);
                },
                Model\ImageTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Image());
                    return new TableGateway('Images', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\BlogController::class => function ($container) {
                    return new Controller\BlogController(
                        $container->get(Model\PostTable::class),
                        $container->get(Model\TranslaterTable::class),
                        $container->get(Model\ImageTable::class),
                        $container->get(Service\ImageManager::class));
                },
                Controller\AdminController::class => function ($container) {
                    $translaterTable = $container->get(Model\TranslaterTable::class);
                    $imageTable = $container->get(Model\ImageTable::class);
                    $postTable = $container->get(Model\PostTable::class);
                    $imageManager = $container->get(Service\ImageManager::class);

                    return new Controller\AdminController($translaterTable, $imageTable, $postTable, $imageManager);
                },
            ],
        ];
    }
}
