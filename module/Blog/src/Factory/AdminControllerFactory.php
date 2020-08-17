<?php


namespace Blog\Factory;


use Blog\Controller\AdminController;
use Blog\Model\ImageTable;
use Blog\Model\PostTable;
use Blog\Model\TranslaterTable;
use Blog\Service\ImageManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdminControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $translater = $container->get(TranslaterTable::class);
        $image = $container->get(ImageTable::class);
        $post = $container->get(PostTable::class);
        $manager = $container->get(ImageManager::class);

        return new AdminController($translater, $image, $post, $manager);
    }
}