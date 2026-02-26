<?php

namespace Regionalisation\Controller;

use Interop\Container\ContainerInterface;

use Zend\View\Model\ViewModel;

use Application\Manager\MenuManager;
use Custom\Mvc\Controller\BackOfficeCommonController;

class IndexController extends BackOfficeCommonController
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
	protected $appliContainer;
	
    /**
     * @var \Application\Manager\MenuManager
     */
    protected $menuManager;
    
    protected $appliConfig;
    
    public function __construct(ContainerInterface $appliContainer, MenuManager $menuManager)
    {
        $appliConfig =  new \Application\Core\AppliConfig();
        $this->appliConfig = $appliConfig;
        
		$this->appliContainer = $appliContainer;
		$this->menuManager = $menuManager;
		
		$this->initialiserPermission();
    }
    
    public function indexAction ()
    {
    	$this->nomPage = $this->getTranslator("Regionalisation");

    	$this->initBackView();
    	 
    	return new ViewModel(array(
    	
    	));
    }
}
