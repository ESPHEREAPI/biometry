<?php

namespace BackHome\Controller\Factory;

use BackHome\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) 
    {
        $menuManager = $container->get('Application\Manager\MenuManager');

        // Create an instance of the controller and pass the dependency 
        // to controller's constructor.
        return new IndexController($container, $menuManager);
    }
}