<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Album;


use Album\Controller\AlbumController;
use Album\Controller\AuthController;
use Album\Controller\Factory\AuthControllerFactory;
use Album\Factory\AlbumControllerFactory;
use Album\Factory\AuthAdapterFactory;
use Album\Factory\AuthenticationServiceFactory;
use Album\Factory\AuthManagerFactory;
use Album\Service\AuthAdapter;
use Album\Service\AuthManager;
use Blog\Controller\AdminController;
use Blog\Controller\BlogController;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Sql\Literal;
use Zend\Router\Http\Segment;

    return [
        'router' => [
            'routes' => [
                'album' => [
                    'type'    => Segment::class,
                    'options' => [
                        'route' => '/album[/:action[/:id]]',
                        'constraints' => [
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'     => '[0-9]+',
                        ],
                        'defaults' => [
                            'controller' => Controller\AlbumController::class,
                            'action'     => 'index',
                        ],
                    ],
                ],

                'auth'=>[
                    'type'    => Segment::class,
                    'options' => [
                        'route'=> '/auth[/:action]',
                        'constraints' => [
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                        ],
                        'defaults' => [
                            'controller' => Controller\AuthController::class,
                            'action'     => 'login',
                        ],
                    ],

                ]
            ],
        ],

        'controllers' => [
            'factories'=>[
                AlbumController::class=>AlbumControllerFactory::class,
                AuthController::class=>AuthControllerFactory::class
            ]
        ],

        'service_manager' => [
            'factories' => [
                AuthManager::class => AuthManagerFactory::class,
                AuthenticationService::class => AuthenticationServiceFactory::class,
                AuthAdapter::class=>AuthAdapterFactory::class
            ],
        ],

        'access_filter'=>[
            'options'=>[
                'mode'=>'restrictive'
            ],
            'controllers'=>[
                Controller\AlbumController::class=>[
                    ['actions' => ['index', 'register'], 'allow' => '*'],
                    ['actions'=>['add', 'edit', 'delete'], 'allow'=>'@'],
                ],
                BlogController::class=>[
                    ['actions'=>['index'], 'allow'=>'*'],
                ],
                AdminController::class=>[
                    ['actions' => ['index', 'addImage', 'addContent'], 'allow'=>'@']
                ]
            ],
        ],

        'view_manager' => [
            'template_path_stack' => [
                __DIR__ . '/../view',
            ],
        ],
    ];