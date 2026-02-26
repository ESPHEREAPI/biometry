<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Dentisterie;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

// Modeles
use Entity\LignePrestation;
use Entity\Prestation;
use Entity\TypePrestation;

// Formulaires
use Dentisterie\Form\FiltreListeLignePrestationForm;
use Dentisterie\Form\FiltreListePrestationForm;
use Dentisterie\Form\PrestationForm;
use Dentisterie\Form\ValiderPrestationForm;

//Managers
use Application\Manager\PrestationManager;
use Application\Manager\TypePrestationManager;

// Filtres
use Dentisterie\Form\PrestationInputFilter;
use Dentisterie\Form\ValiderPrestationInputFilter;
use Application\Manager\EmployeManager;
use Application\Manager\LignePrestationManager;
use Dentisterie\Form\LignePrestationForm;
use Dentisterie\Form\LignePrestationInputFilter;
use Application\Manager\PrestataireManager;
use Dentisterie\Form\RechercherVisiteForm;
use Dentisterie\Form\RechercherVisiteInputFilter;
use Application\Manager\LignePrestationAuditManager;

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
 				'Application\Manager\PrestationManager' => function ($sm) {
 					$result = new PrestationManager;
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\TypePrestationManager' => function ($sm) {
     				$result = new TypePrestationManager;
     				$result->setServiceManager($sm);
     				return $result;
 				},
 				'Application\Manager\EmployeManager' => function ($sm) {
     				$result = new EmployeManager;
     				$result->setServiceManager($sm);
     				return $result;
 				},
 				'Application\Manager\LignePrestationManager' => function ($sm) {
     				$result = new LignePrestationManager;
     				$result->setServiceManager($sm);
     				return $result;
 				},
 				'Application\Manager\PrestataireManager' => function ($sm) {
     				$result = new PrestataireManager;
     				$result->setServiceManager($sm);
     				return $result;
 				},
 				'Application\Manager\LignePrestationAuditManager' => function ($sm) {
     				$result = new LignePrestationAuditManager;
     				$result->setServiceManager($sm);
     				return $result;
 				},
 				
 				
 				
 				// Filtres
 				'Dentisterie\Form\PrestationInputFilter' => function ($sm) {
 					$result = new PrestationInputFilter;
 					$result->setTranslator($sm->get('translator'), "application");
 					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
 					return $result;
 				},
 				'Dentisterie\Form\ValiderPrestationInputFilter' => function ($sm) {
     				$result = new ValiderPrestationInputFilter;
     				$result->setTranslator($sm->get('translator'), "application");
     				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
     				return $result;
 				},
 				'Dentisterie\Form\LignePrestationInputFilter' => function ($sm) {
     				$result = new LignePrestationInputFilter;
     				$result->setTranslator($sm->get('translator'), "application");
     				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
     				return $result;
 				},
 				'Dentisterie\Form\RechercherVisiteInputFilter' => function ($sm) {
     				$result = new RechercherVisiteInputFilter;
     				$result->setTranslator($sm->get('translator'), "application");
     				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
     				return $result;
 				},
 				
 				// Models
	 			'Entity\Prestation' => function ($sm) {
					$result = new Prestation(
						$sm->get('Zend\Db\Adapter\Adapter')
					);
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					return $result;
				},
				'Entity\LignePrestation' => function ($sm) {
    				$result = new LignePrestation(
    				    $sm->get('Zend\Db\Adapter\Adapter')
    				    );
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				return $result;
				},
				
				'Entity\TypePrestation' => function ($sm) {
    				$result = new TypePrestation(
    				    $sm->get('Zend\Db\Adapter\Adapter')
    				    );
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				return $result;
				},

				// Forms
				'Dentisterie\Form\FiltreListePrestationForm' => function ($sm) {
					$result = new FiltreListePrestationForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Dentisterie\Form\PrestationForm' => function ($sm) {
					$result = new PrestationForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Dentisterie\Form\ValiderPrestationForm' => function ($sm) {
    				$result = new ValiderPrestationForm;
    				$result->setTranslator($sm->get('translator'), "application");
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				$result->initialize();
    				return $result;
				},
				'Dentisterie\Form\FiltreListeLignePrestationForm' => function ($sm) {
    				$result = new FiltreListeLignePrestationForm;
    				$result->setTranslator($sm->get('translator'), "application");
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				$result->initialize();
    				return $result;
				},
				'Dentisterie\Form\LignePrestationForm' => function ($sm) {
    				$result = new LignePrestationForm;
    				$result->setTranslator($sm->get('translator'), "application");
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				$result->initialize();
    				return $result;
				},
				'Dentisterie\Form\RechercherVisiteForm' => function ($sm) {
    				$result = new RechercherVisiteForm;
    				$result->setTranslator($sm->get('translator'), "application");
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				$result->initialize();
    				return $result;
				},
			),
 		);
 	}
}
