<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administration;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

// Modeles
use Entity\Utilisateur;
use Entity\Employe;
use Entity\Profil;
use Entity\Prestataire;

// Formulaires
use Administration\Form\Employe\FiltreListeEmployeForm;
use Administration\Form\Employe\EmployeForm;
use Administration\Form\Profil\FiltreListeProfilForm;
use Administration\Form\Profil\ProfilForm;
use Administration\Form\Permission\FiltreListePermissionForm;


// Prestataire
use Administration\Form\Prestataire\FiltreListePrestataireForm;
use Administration\Form\Prestataire\PrestataireForm;



//Managers
use Application\Manager\EmployeManager;
use Application\Manager\PrestataireManager;
use Application\Manager\ProfilManager;
use Application\Manager\PermissionManager;
use Application\Manager\MenuManager;

// Filtres
use Application\Filter\EmployeInputFilter;
use Application\Filter\PrestataireInputFilter;
use Application\Filter\ProfilInputFilter;
use Application\Manager\VilleManager;



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
 			    'Application\Manager\VilleManager' => function ($sm) {
     			    $result = new VilleManager;
     			    $result->setServiceManager($sm);
     			    return $result;
 			    },
 			    'Application\Manager\MenuManager' => function ($sm) {
 					$result = new MenuManager;
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\EmployeManager' => function ($sm) {
 					$result = new EmployeManager;
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\ProfilManager' => function ($sm) {
 					$result = new ProfilManager;
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\PermissionManager' => function ($sm) {
 					$result = new PermissionManager;
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\PrestataireManager' => function ($sm) {
     				$result = new PrestataireManager;
     				$result->setServiceManager($sm);
     				return $result;
 				},
 				
 				// Filtres
 				'Application\Filter\EmployeInputFilter' => function ($sm) {
 					$result = new EmployeInputFilter;
 					$result->setTranslator($sm->get('translator'), "application");
 					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
 					return $result;
 				},
 				'Application\Filter\ProfilInputFilter' => function ($sm) {
 					$result = new ProfilInputFilter;
 					$result->setTranslator($sm->get('translator'), "application");
 					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
 					return $result;
 				},
 				'Application\Filter\PrestataireInputFilter' => function ($sm) {
     				$result = new PrestataireInputFilter;
     				$result->setTranslator($sm->get('translator'), "application");
     				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
     				return $result;
 				},
 				
 				// Models
				'Entity\Utilisateur' => function ($sm) {
					$result = new Utilisateur(
						$sm->get('Zend\Db\Adapter\Adapter')
					);
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					return $result;
				},
	 			'Entity\Employe' => function ($sm) {
					$result = new Employe(
						$sm->get('Zend\Db\Adapter\Adapter')
					);
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					return $result;
				},
				'Entity\Profil' => function ($sm) {
					$result = new Profil(
							$sm->get('Zend\Db\Adapter\Adapter')
					);
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					return $result;
				},
				'Entity\Prestataire' => function ($sm) {
    				$result = new Prestataire(
    				    $sm->get('Zend\Db\Adapter\Adapter')
    				    );
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				return $result;
				},

				// Forms
				'Administration\Form\Employe\FiltreListeEmployeForm' => function ($sm) {
					$result = new FiltreListeEmployeForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Administration\Form\Employe\EmployeForm' => function ($sm) {
					$result = new EmployeForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Administration\Form\Profil\FiltreListeProfilForm' => function ($sm) {
					$result = new FiltreListeProfilForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Administration\Form\Profil\ProfilForm' => function ($sm) {
					$result = new ProfilForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Administration\Form\Permission\FiltreListePermissionForm' => function ($sm) {
					$result = new FiltreListePermissionForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				
				
				
				
				// Prestataire
				'Administration\Form\Prestataire\FiltreListePrestataireForm' => function ($sm) {
    				$result = new FiltreListePrestataireForm;
    				$result->setTranslator($sm->get('translator'), "application");
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				$result->initialize();
    				return $result;
				},
				'Administration\Form\Prestataire\PrestataireForm' => function ($sm) {
				    $result = new PrestataireForm;
    				$result->setTranslator($sm->get('translator'), "application");
    				$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
    				$result->initialize();
    				return $result;
				},
			),
 		);
 	}
}
