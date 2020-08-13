<?php


namespace Album\Controller\Factory;


use Album\Controller\AuthController;
use Album\Model\UserTable;
use Album\Service\AuthManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authManager = $container->get(AuthManager::class);
        $userTable = $container->get(UserTable::class);

        return new AuthController($userTable, $authManager);
    }
}