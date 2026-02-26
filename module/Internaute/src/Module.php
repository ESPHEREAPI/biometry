<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Internaute;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

// Modeles
use Entity\Internaute;

// Formulaires
use Internaute\Form\FiltreListeInternauteForm;
use Internaute\Form\InternauteForm;

//Managers
use Application\Manager\MenuManager;
use Application\Manager\InternauteManager;

// Filtres
use Application\Filter\InternauteInputFilter;

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
 				'Application\Manager\MenuManager' => function ($sm) {
 					$result = new MenuManager;
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				'Application\Manager\InternauteManager' => function ($sm) {
 					$result = new InternauteManager;
 					$result->setServiceManager($sm);
 					return $result;
 				},
 				
 				// Filtres
 				'Application\Filter\InternauteInputFilter' => function ($sm) {
 					$result = new InternauteInputFilter;
 					$result->setTranslator($sm->get('translator'), "application");
 					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
 					return $result;
 				},
 				
 				// Models
	 			'Entity\Internaute' => function ($sm) {
					$result = new Internaute(
						$sm->get('Zend\Db\Adapter\Adapter')
					);
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					return $result;
				},
				

				// Forms
				'Internaute\Form\FiltreListeInternauteForm' => function ($sm) {
					$result = new FiltreListeInternauteForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Internaute\Form\InternauteForm' => function ($sm) {
					$result = new InternauteForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
			),
 		);
 	}
}
