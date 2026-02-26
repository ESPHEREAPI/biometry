<?php

namespace Dentisterie\Controller\Factory;

use Dentisterie\Controller\LignePrestationController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LignePrestationControllerFactory implements FactoryInterface
{
     public function __invoke(ContainerInterface $container, $requestedName, array $options = null) 
     {
          $lignePrestation = $container->get('Entity\LignePrestation');

          $lignePrestationManager = $container->get('Application\Manager\LignePrestationManager');
          $menuManager = $container->get('Application\Manager\MenuManager');
          $employeManager = $container->get('Application\Manager\EmployeManager');


          $lignePrestationForm = $container->get('Dentisterie\Form\LignePrestationForm');
          $filtreListeLignePrestationForm = $container->get('Dentisterie\Form\FiltreListeLignePrestationForm');

          $lignePrestationInputFilter = $container->get('Dentisterie\Form\LignePrestationInputFilter');

          // Create an instance of the controller and pass the dependency 
          // to controller's constructor.
          return new LignePrestationController($container, $lignePrestation, $lignePrestationManager, $menuManager, $employeManager, $filtreListeLignePrestationForm,
                                              $lignePrestationForm, $lignePrestationInputFilter);
     }
}