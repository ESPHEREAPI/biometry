<?php

namespace Dentisterie\Controller\Factory;

use Dentisterie\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
     public function __invoke(ContainerInterface $container, $requestedName, array $options = null) 
     {
          $prestation = $container->get('Entity\Prestation');

          $prestationManager = $container->get('Application\Manager\PrestationManager');
          $menuManager = $container->get('Application\Manager\MenuManager');
          $employeManager = $container->get('Application\Manager\EmployeManager');
          $lignePrestationManager = $container->get('Application\Manager\LignePrestationManager');
          $prestataireManager = $container->get('Application\Manager\PrestataireManager');
		 $typePrestationManager = $container->get('Application\Manager\TypePrestationManager');

          $lignePrestationAuditManager = $container->get('Application\Manager\LignePrestationAuditManager');

          // Les formulaires
          $prestationForm = $container->get('Dentisterie\Form\PrestationForm');
          $filtreListePrestationForm = $container->get('Dentisterie\Form\FiltreListePrestationForm');
          $validerPrestationForm = $container->get('Dentisterie\Form\ValiderPrestationForm');
          $rechercherVisiteForm = $container->get('Dentisterie\Form\RechercherVisiteForm');
          $rechercherVisiteInputFilter = $container->get('Dentisterie\Form\RechercherVisiteInputFilter');
         
         // Les filtres
         $prestationInputFilter = $container->get('Dentisterie\Form\PrestationInputFilter');
         $validerPrestationInputFilter = $container->get('Dentisterie\Form\ValiderPrestationInputFilter');

          // Create an instance of the controller and pass the dependency 
          // to controller's constructor.
          return new IndexController($container, $prestation, $prestationManager, $prestationForm, $filtreListePrestationForm, $prestationInputFilter,
                                    $validerPrestationForm, $validerPrestationInputFilter, $menuManager, $employeManager, $lignePrestationManager,
                                    $prestataireManager, $rechercherVisiteForm, $rechercherVisiteInputFilter, $lignePrestationAuditManager,$typePrestationManager);
     }
}