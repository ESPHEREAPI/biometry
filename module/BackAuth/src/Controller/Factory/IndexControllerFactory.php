<?php

namespace BackAuth\Controller\Factory;

use BackAuth\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) 
    {
        $menuManager = $container->get('Application\Manager\MenuManager');
        $backAuthManager = $container->get('Application\Manager\BackAuthManager');

        // Create an instance of the controller and pass the dependency 
        // to controller's constructor.
        return new IndexController($container, $menuManager, $backAuthManager);
    }
}