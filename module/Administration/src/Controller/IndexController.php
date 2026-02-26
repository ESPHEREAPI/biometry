<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administration\Controller;

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
    	$this->nomPage = $this->getTranslator("Adminsitration");
    	
    	$this->initBackView();
    	
        return new ViewModel(array(
        		
        ));
    }
}
