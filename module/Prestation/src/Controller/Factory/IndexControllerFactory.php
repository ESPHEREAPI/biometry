<?php

namespace Prestation\Controller\Factory;

use Prestation\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
     public function __invoke(ContainerInterface $container, $requestedName, array $options = null) 
     {
          $prestation = $container->get('Entity\Prestation');
		  $consultation = $container->get('Entity\Consultation');
		  $medicament = $container->get('Entity\Medicament');

          $prestationManager = $container->get('Application\Manager\PrestationManager');
          $menuManager = $container->get('Application\Manager\MenuManager');
          $employeManager = $container->get('Application\Manager\EmployeManager');
          $lignePrestationManager = $container->get('Application\Manager\LignePrestationManager');
          $prestataireManager = $container->get('Application\Manager\PrestataireManager');
		 $typePrestationManager = $container->get('Application\Manager\TypePrestationManager');

          $lignePrestationAuditManager = $container->get('Application\Manager\LignePrestationAuditManager');

          // Les formulaires
          $prestationForm = $container->get('Prestation\Form\PrestationForm');
          $filtreListePrestationForm = $container->get('Prestation\Form\FiltreListePrestationForm');
          $validerPrestationForm = $container->get('Prestation\Form\ValiderPrestationForm');
          $rechercherVisiteForm = $container->get('Prestation\Form\RechercherVisiteForm');
          $rechercherVisiteInputFilter = $container->get('Prestation\Form\RechercherVisiteInputFilter');
         
         // Les filtres
         $prestationInputFilter = $container->get('Prestation\Form\PrestationInputFilter');
         $validerPrestationInputFilter = $container->get('Prestation\Form\ValiderPrestationInputFilter');

          // Create an instance of the controller and pass the dependency 
          // to controller's constructor.
          return new IndexController($container, $prestation,$consultation,$medicament, $prestationManager, $prestationForm, $filtreListePrestationForm, $prestationInputFilter,
                                    $validerPrestationForm, $validerPrestationInputFilter, $menuManager, $employeManager, $lignePrestationManager,
                                    $prestataireManager, $rechercherVisiteForm, $rechercherVisiteInputFilter, $lignePrestationAuditManager,$typePrestationManager);
     }
}