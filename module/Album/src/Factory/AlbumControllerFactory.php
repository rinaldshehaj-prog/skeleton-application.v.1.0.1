<?php


namespace Album\Factory;


use Album\Controller\AlbumController;
use Album\Model\AlbumTable;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AlbumControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $albumTable = $container->get(AlbumTable::class);
        return new AlbumController($albumTable);
    }
}