<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Menu;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

// Modeles
use Entity\Menu;
use Entity\MenuLangue;

// Formulaires
use Menu\Form\FiltreListeMenuForm;
use Menu\Form\MenuForm;

//Managers
use Application\Manager\MenuManager;

// Filtres
use Application\Filter\MenuInputFilter;

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
 				
 				// Filtres
 				'Application\Filter\MenuInputFilter' => function ($sm) {
 					$result = new MenuInputFilter;
 					$result->setTranslator($sm->get('translator'), "application");
 					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
 					return $result;
 				},
 				
 				// Models
	 			'Entity\Menu' => function ($sm) {
					$result = new Menu(
						$sm->get('Zend\Db\Adapter\Adapter')
					);
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					return $result;
				},
				'Entity\MenuLangue' => function ($sm) {
					$result = new MenuLangue(
						$sm->get('Zend\Db\Adapter\Adapter')
					);
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					return $result;
				},
				

				// Forms
				'Menu\Form\FiltreListeMenuForm' => function ($sm) {
					$result = new FiltreListeMenuForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
				'Menu\Form\MenuForm' => function ($sm) {
					$result = new MenuForm;
					$result->setTranslator($sm->get('translator'), "application");
					$result->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
					$result->initialize();
					return $result;
				},
			),
 		);
 	}
}
