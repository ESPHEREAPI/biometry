<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Prestation;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

// Modeles
use Entity\LignePrestation;
use Entity\Prestation;
use Entity\TypePrestation;
use Entity\Medicament;
use Entity\Examen;

// Formulaires
use Prestation\Form\FiltreListeLignePrestationForm;
use Prestation\Form\FiltreListePrestationForm;
use Prestation\Form\PrestationForm;
use Prestation\Form\ValiderPrestationForm;

//Managers
use Application\Manager\PrestationManager;
use Application\Manager\TypePrestationManager;

// Filtres
use Prestation\Form\PrestationInputFilter;
use Prestation\Form\ValiderPrestationInputFilter;
use Application\Manager\EmployeManager;
use Application\Manager\LignePrestationManager;
use Prestation\Form\LignePrestationForm;
use Prestation\Form\LignePrestationInputFilter;
use Application\Manager\PrestataireManager;
use Prestation\Form\RechercherVisiteForm;
use Prestation\Form\RechercherVisiteInputFilter;
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
 				'Prestation\Form\PrestationInputFilter' => function ($sm) {
 					$result = new PrestationInputFilter;
 					$result->setTranslator($sm->get('translator'), "application");
 					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
 					return $result;
 				},
 				'Prestation\Form\ValiderPrestationInputFilter' => function ($sm) {
     				$result = new ValiderPrestationInputFilter;
     				$result->setTranslator($sm->get('translator'), "application");
     				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
     				return $result;
 				},
 				'Prestation\Form\LignePrestationInputFilter' => function ($sm) {
     				$result = new LignePrestationInputFilter;
     				$result->setTranslator($sm->get('translator'), "application");
     				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
     				return $result;
 				},
 				'Prestation\Form\RechercherVisiteInputFilter' => function ($sm) {
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
				'Entity\Medicament' => function ($sm) {
					$result = new Medicament(
						$sm->get('Zend\Db\Adapter\Adapter')
					);
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					return $result;
				},
				'Entity\Examen' => function ($sm) {
					$result = new Examen(
						$sm->get('Zend\Db\Adapter\Adapter')
					);
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					return $result;
				},

				// Forms
				'Prestation\Form\FiltreListePrestationForm' => function ($sm) {
					$result = new FiltreListePrestationForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Prestation\Form\PrestationForm' => function ($sm) {
					$result = new PrestationForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Prestation\Form\ValiderPrestationForm' => function ($sm) {
    				$result = new ValiderPrestationForm;
    				$result->setTranslator($sm->get('translator'), "application");
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				$result->initialize();
    				return $result;
				},
				'Prestation\Form\FiltreListeLignePrestationForm' => function ($sm) {
    				$result = new FiltreListeLignePrestationForm;
    				$result->setTranslator($sm->get('translator'), "application");
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				$result->initialize();
    				return $result;
				},
				'Prestation\Form\LignePrestationForm' => function ($sm) {
    				$result = new LignePrestationForm;
    				$result->setTranslator($sm->get('translator'), "application");
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				$result->initialize();
    				return $result;
				},
				'Prestation\Form\RechercherVisiteForm' => function ($sm) {
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
