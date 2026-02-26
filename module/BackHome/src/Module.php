<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace BackHome;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

use Entity\Utilisateur;
use Entity\Employe;

use Application\Manager\CommonManager;
use Application\Manager\HomeManager;
use Application\Manager\MenuManager;
use Application\Manager\InternauteManager;
use Application\Manager\RegionManager;

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
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\MenuManager' => function ($sm) {
	 				$result = new MenuManager;
	 				$result->setServiceManager($sm);
	 				return $result;
 				},
 				'Application\Manager\HomeManager' => function ($sm) {
 					$result = new HomeManager;
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\InternauteManager' => function ($sm) {
 					$result = new InternauteManager;
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\RegionManager' => function ($sm) {
 					$result = new RegionManager;
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
			),
 		);
 	}
}
