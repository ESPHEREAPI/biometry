<?php

namespace Regionalisation\Controller\Factory;

use Regionalisation\Controller\VilleController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class VilleControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) 
    {
        $menuManager = $container->get('Application\Manager\MenuManager');

        // Create an instance of the controller and pass the dependency 
        // to controller's constructor.
        return new VilleController($container, $menuManager);
    }
}