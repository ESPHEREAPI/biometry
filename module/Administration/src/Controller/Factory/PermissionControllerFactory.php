<?php

namespace Administration\Controller\Factory;

use Administration\Controller\PermissionController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PermissionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) 
    {
        $menuManager = $container->get('Application\Manager\MenuManager');

        // Create an instance of the controller and pass the dependency 
        // to controller's constructor.
        return new PermissionController($container, $menuManager);
    }
}