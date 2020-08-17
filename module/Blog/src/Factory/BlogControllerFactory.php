<?php


namespace Blog\Factory;


use Blog\Controller\BlogController;
use Blog\Model\ImageTable;
use Blog\Model\PostTable;
use Blog\Model\TranslaterTable;
use Blog\Service\ImageManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class BlogControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $postTable = $container->get(PostTable::class);
        $content = $container->get(TranslaterTable::class);
        $image = $container->get(ImageTable::class);
        $imageManager = $container->get(ImageManager::class);
        return new BlogController($postTable, $content, $image, $imageManager);
    }
}