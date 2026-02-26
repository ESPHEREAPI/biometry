<?php

namespace BackAuth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

use Entity\Utilisateur;
use Entity\Employe;

use BackAuth\Form\AuthForm;

use Application\Manager\CommonManager;
use Application\Manager\BackAuthManager;

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
 				'Application\Manager\CommonManager' => function ($sm) {
 					$result = new CommonManager;
 					// $result->setTranslator($sm->get('translator'), "application");
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\BackAuthManager' => function ($sm) {
 					$result = new BackAuthManager;
 					$result->setServiceManager($sm);
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

				// Forms
				'BackAuth\Form\AuthForm' => function ($sm) {
					$result = new AuthForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				
			),
 		);
 	}
}
