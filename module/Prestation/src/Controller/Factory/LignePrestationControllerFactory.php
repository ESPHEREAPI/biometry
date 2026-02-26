<?php

namespace Prestation\Controller\Factory;

use Prestation\Controller\LignePrestationController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LignePrestationControllerFactory implements FactoryInterface
{
     public function __invoke(ContainerInterface $container, $requestedName, array $options = null) 
     {
          $lignePrestation = $container->get('Entity\LignePrestation');
		  $medicament = $container->get('Entity\Medicament');

          $lignePrestationManager = $container->get('Application\Manager\LignePrestationManager');
          $menuManager = $container->get('Application\Manager\MenuManager');
          $employeManager = $container->get('Application\Manager\EmployeManager');


          $lignePrestationForm = $container->get('Prestation\Form\LignePrestationForm');
          $filtreListeLignePrestationForm = $container->get('Prestation\Form\FiltreListeLignePrestationForm');

          $lignePrestationInputFilter = $container->get('Prestation\Form\LignePrestationInputFilter');

          // Create an instance of the controller and pass the dependency 
          // to controller's constructor.
          return new LignePrestationController($container, $lignePrestation,$medicament, $lignePrestationManager, $menuManager, $employeManager, $filtreListeLignePrestationForm,
                                              $lignePrestationForm, $lignePrestationInputFilter);
     }
}