<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Album;


use Album\Controller\AuthController;
use Album\Service\AuthManager;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class Module implements ConfigProviderInterface
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
                Model\AlbumTable::class => function($container) {
                    $tableGateway = $container->get(Model\AlbumTableGateway::class);
                    return new Model\AlbumTable($tableGateway);
                },
                Model\AlbumTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                },
                Model\UserTable::class => function($container) {
                    $tableGateway = $container->get(Model\UserTableGateway::class);
                    return new Model\UserTable($tableGateway);
                },
                Model\UserTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },

            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AlbumController::class => function($container) {
                    return new Controller\AlbumController(
                        $container->get(Model\AlbumTable::class));
                },
                Controller\AuthController::class => function($container) {
                    return new Controller\AuthController($container->get(Model\UserTable::class) ,$container->get(AuthManager::class)
                    , $container->get(AuthenticationService::class));
                },
            ],
        ];
    }


    public function onBootstrap(MvcEvent $event)
    {

        // Get event manager.
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method.
        $sharedEventManager->attach(AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);

        $sessionManager = $event->getApplication()->getServiceManager()->get('Zend\Session\SessionManager');

        $this->forgetInvalidSession($sessionManager);
    }

    protected function forgetInvalidSession($sessionManager)
    {
        try {
            $sessionManager->start();
            return;
        } catch (\Exception $e) {
        }
        /**
         * Session validation failed: toast it and carry on.
         */
        // @codeCoverageIgnoreStart
        session_unset();
        // @codeCoverageIgnoreEnd
    }

    public function onDispatch(MvcEvent $event)
    {
        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Convert dash-style action name to camel-case.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        // Get the instance of AuthManager service.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);

        // Execute the access filter on every controller except AuthController
        // (to avoid infinite redirect).
        if ($controllerName != AuthController::class &&
            !$authManager->filterAccess($controllerName, $actionName)) {

            // Remember the URL of the page the user tried to access. We will
            // redirect the user to that URL after successful login.

            $uri = $event->getApplication()->getRequest()->getUri();

            // Make the URL relative (remove scheme, user info, host name and port)
            // to avoid redirecting to other domain by a malicious user.
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();

            // Redirect the user to the "Login" page.

            return $controller->redirect()->toRoute('auth', [],
                ['query' => ['redirectUrl' => $redirectUrl]]);
        }
    }
}
