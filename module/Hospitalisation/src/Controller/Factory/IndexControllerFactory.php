<?php

namespace Hospitalisation\Controller\Factory;

use Hospitalisation\Controller\IndexController;
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
          $prestationForm = $container->get('Hospitalisation\Form\PrestationForm');
          $filtreListePrestationForm = $container->get('Hospitalisation\Form\FiltreListePrestationForm');
          $validerPrestationForm = $container->get('Hospitalisation\Form\ValiderPrestationForm');
          $rechercherVisiteForm = $container->get('Hospitalisation\Form\RechercherVisiteForm');
          $rechercherVisiteInputFilter = $container->get('Hospitalisation\Form\RechercherVisiteInputFilter');
         
         // Les filtres
         $prestationInputFilter = $container->get('Hospitalisation\Form\PrestationInputFilter');
         $validerPrestationInputFilter = $container->get('Hospitalisation\Form\ValiderPrestationInputFilter');

          // Create an instance of the controller and pass the dependency 
          // to controller's constructor.
          return new IndexController($container, $prestation, $prestationManager, $prestationForm, $filtreListePrestationForm, $prestationInputFilter,
                                    $validerPrestationForm, $validerPrestationInputFilter, $menuManager, $employeManager, $lignePrestationManager,
                                    $prestataireManager, $rechercherVisiteForm, $rechercherVisiteInputFilter, $lignePrestationAuditManager,$typePrestationManager);
     }
}