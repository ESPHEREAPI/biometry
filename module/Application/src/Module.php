<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Cache\StorageFactory;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Validator\AbstractValidator;

class Module
{
    const VERSION = '3.0.3-dev';

    public function onBootstrap(MvcEvent $e)
    {
    	$eventManager = $e->getApplication()->getEventManager();
    	$sm = $e->getApplication()->getServiceManager();
    	//--- pour les sessions
        $config = $sm->get('Config');
        

        // var_dump($config); exit;

    	// $cacheStorage = StorageFactory::factory($config ['filesystem']);
    	// $saveHandler = new \Zend\Session\SaveHandler\Cache($cacheStorage);
    	$sessionConfig = new \Zend\Session\Config\SessionConfig();
    	$sessionConfig -> setOptions($config ['session']);
		// $sessionManager = new \Zend\Session\SessionManager($sessionConfig, NULL, $saveHandler);
		$sessionManager = new \Zend\Session\SessionManager($sessionConfig, NULL);


    	$sessionManager->start();
    	\Zend\Session\Container::SetDefaultManager($sessionManager);
    	//---
    	$moduleRouteListener = new ModuleRouteListener();
    	$moduleRouteListener->attach($eventManager);
    	
    	$translator = $e->getApplication()->getServiceManager()->get('translator');
    	$translator->addTranslationFile(
    			'phpArray',
    			'./vendor/zendframework/zend-i18n-resources/languages/'.strtolower(substr($translator->getLocale(), 0, 2)).'/Zend_Validate.php'
    	
    	);
    	AbstractValidator::setDefaultTranslator($translator);
    	
    	
    	
    	
    	
    	// $appliConfig =  new \Application\Core\AppliConfig;
    	
    	
    	// var_dump($appliConfig->get("caroussel_accueil"));
    	
    	
    	
    	/*
    	$em = $e->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');
    	$tabLangueActives = $em->getRepository('Entity\Langue')->findBy(array('statut' => 1, 'supprime' => -1));
    	
    	if(is_array($tabLangueActives) && count($tabLangueActives) > 0)
    	{
    		$varListeCodeIsoLangue = "";
    		foreach ($tabLangueActives as $uneLangueActives)
    		{
    			if($varListeCodeIsoLangue != "")
    				$varListeCodeIsoLangue .= "|";
    			
    			$varListeCodeIsoLangue .= $uneLangueActives->getCodeIso();
    		}
    		
    		/// Create the object-oriented wrapper using the configuration data
    		$config = new \Zend\Config\Config(include __DIR__."/../../config/custom.application.config.php", true);
    		$config->liste_code_iso_langue = $varListeCodeIsoLangue;
    		
    		$writer = new \Zend\Config\Writer\PhpArray();
    		$writer->toFile(__DIR__."/../../config/custom.application.config.php", $config);
    	}
    	*/
    }
    
    public function translate($k)
    {
    	if ($this->_translator && $this->_translator_enabled)
    	{
    		return $this->_translator->translate($k, "application");
    	}
    	return $k;
    }


    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
