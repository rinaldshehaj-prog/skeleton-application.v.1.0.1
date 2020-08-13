<?php


namespace Blog\Factory;


use Blog\Controller\BlogController;
use Blog\Model\PostTable;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class BlogControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $postTable = $container->get(PostTable::class);
        return new BlogController($postTable);
    }
}