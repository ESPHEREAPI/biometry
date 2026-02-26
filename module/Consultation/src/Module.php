<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Consultation;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

// Modeles
use Entity\Consultation;

// Formulaires
use Consultation\Form\FiltreListeConsultationForm;
use Consultation\Form\ConsultationForm;
use Consultation\Form\ValiderConsultationForm;

//Managers
use Application\Manager\ConsultationManager;

// Filtres
use Consultation\Form\ConsultationInputFilter;
use Consultation\Form\ValiderConsultationInputFilter;
use Application\Manager\EmployeManager;

class Module implements ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function translate($k)
    {
    	if ($this->_translator && $this->_translator_enabled) {
    		return $this->_translator->translate($k, "application");
    	}
    	return $k;
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
 	
 	public function getServiceConfig()
 	{
 		return array(
 			'factories' => array(
 				
 				// Managers
 				'Application\Manager\ConsultationManager' => function ($sm) {
 					$result = new ConsultationManager;
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\ConsultationManager' => function ($sm) {
     				$result = new ConsultationManager;
     				$result->setServiceManager($sm);
     				return $result;
 				},
 				'Application\Manager\EmployeManager' => function ($sm) {
     				$result = new EmployeManager;
     				$result->setServiceManager($sm);
     				return $result;
 				},
 				
 				// Filtres
 				'Consultation\Form\ConsultationInputFilter' => function ($sm) {
 					$result = new ConsultationInputFilter;
 					$result->setTranslator($sm->get('translator'), "application");
 					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
 					return $result;
 				},
 				'Consultation\Form\ValiderConsultationInputFilter' => function ($sm) {
     				$result = new ValiderConsultationInputFilter;
     				$result->setTranslator($sm->get('translator'), "application");
     				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
     				return $result;
 				},
 				
 				// Models
	 			'Entity\Consultation' => function ($sm) {
					$result = new Consultation(
						$sm->get('Zend\Db\Adapter\Adapter')
					);
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					return $result;
				},
				

				// Forms
				'Consultation\Form\FiltreListeConsultationForm' => function ($sm) {
					$result = new FiltreListeConsultationForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Consultation\Form\ConsultationForm' => function ($sm) {
					$result = new ConsultationForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Consultation\Form\ValiderConsultationForm' => function ($sm) {
    				$result = new ValiderConsultationForm;
    				$result->setTranslator($sm->get('translator'), "application");
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				$result->initialize();
    				return $result;
				},
			),
 		);
 	}
}
