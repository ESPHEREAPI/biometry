<?php

namespace Consultation\Controller\Factory;

use Consultation\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) 
    {
        $consultation = $container->get('Entity\Consultation');
		$medicament = $container->get('Entity\Medicament');
         
        $consultationManager = $container->get('Application\Manager\ConsultationManager');
        $menuManager = $container->get('Application\Manager\MenuManager');
        $employeManager = $container->get('Application\Manager\EmployeManager');

        // Les formulaires
        $consultationForm = $container->get('Consultation\Form\ConsultationForm');
        $filtreListeConsultationForm = $container->get('Consultation\Form\FiltreListeConsultationForm');
        $validerConsultationForm = $container->get('Consultation\Form\ValiderConsultationForm');

        // Les filtres
        $consultationInputFilter = $container->get('Consultation\Form\ConsultationInputFilter');
        $validerConsultationInputFilter = $container->get('Consultation\Form\ValiderConsultationInputFilter');

        // Create an instance of the controller and pass the dependency 
        // to controller's constructor.
        return new IndexController($container, $consultation, $medicament, $consultationManager, $consultationForm, $filtreListeConsultationForm, $consultationInputFilter,
                                   $validerConsultationForm, $validerConsultationInputFilter, $menuManager, $employeManager);
    }
}