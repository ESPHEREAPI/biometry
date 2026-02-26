<?php

namespace Administration\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Custom\Mvc\Controller\BackOfficeCommonController;

use Application\Manager\MenuManager;
use Entity\Permission;

class PermissionController extends BackOfficeCommonController
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
    
	public function getFiltreListePermissionForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Administration\Form\Permission\FiltreListePermissionForm');
	}
	
    public function indexAction ()
    {
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$this->nomPage = $this->getTranslator("Liste des permissions");
    	$this->descriptionPage = $this->getTranslator("Gestion des permissions des utilisateurs");
    	
    	$this->initBackView();
    	
    	$sessionEmploye = new Container('employe');
    	$profilManager = $this->getProfilManager();
    	
    	$retourPagination = $profilManager->getListeProfilLangue($sessionEmploye->offsetGet("code_langue"), 1, -1, 1,
    											$profilManager::NBRE_LIGNE_TABLEAU, true); // $codeLangue="fr_FR", $statut=null, $supprime=null, $nroPage=null, $nbreMax=null, $pagination=false
    	
    	$tab = $retourPagination['tab'];
    	
        return new ViewModel(array(
        	'tab' => $tab,
        	'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
        ));
    }
    
	public function validerAction()
    {
    	$sessionEmploye = new Container('employe');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    	
    	$idProfil = $this->params()->fromRoute('id', null);
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	if(is_array($postValues) && is_numeric($idProfil))
    	{
    		$idNotDeleted = "";
    		
    		foreach($postValues as $key => $value)
    		{
    			$tabKey = explode("tableau_", $key);

    			if(isset($tabKey[1]) && is_numeric($tabKey[1]))
    			{
    				$idMenu = $tabKey[1];
    				
    				$permission = $this->getEntityManager()->getRepository('Entity\Permission')->findOneBy(array('menu' => $idMenu,
    						'profil' => $idProfil,
    				));
    				
    				if(!$permission)
    				{
    					$permission = new Permission();
    					$permission->setMenu($this->getEntityManager()->find('Entity\Menu', $idMenu));
    					$permission->setProfil($this->getEntityManager()->find('Entity\Profil', $idProfil));
    					
    					$this->getEntityManager()->persist($permission);
    					$this->getEntityManager()->flush();
    				}
    				
    				if($idNotDeleted != "")
    					$idNotDeleted .= ",";
    				
    				$idNotDeleted .= $permission->getId();
    			}
    		}
    		
    		$dql = 'DELETE FROM Entity\Permission perm WHERE perm.profil = '.$idProfil;
    		
    		if($idNotDeleted != "")
    		{
    			$dql .= ' AND perm.id NOT IN ('.$idNotDeleted.')';
    		}
    		
    		$query = $this->getEntityManager()->createQuery($dql);
    		
    		$numDeleted = $query->execute();
    		
    		$this->getEntityManager()->flush();
    	}
    	else
    	{
    		$error = $this->getTranslator("Toutes les valeurs du filtre n'ont pas ete transmises");
    	}
    	
    	return new JsonModel(array(
    			'error' => $error,
    			'info' => $info,
    			'varRetour' => $varRetour,
    	));
    }
    
    public function profilAction()
    {
    	$sessionEmploye = new Container('employe');
    	$permissionManager = $this->getPermissionManager();
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    
    	$idProfil = $this->params()->fromRoute('id', null);

    	if(is_numeric($idProfil))
    	{
    		$tab = $this->construireMenus("SUP_ADMIN");
    		
    		if(is_array($tab) && count($tab) > 0)
    		{
    			$permissionController = $this;
    			
    			include_once __DIR__.'/../../view/administration/permission/profil.phtml';
    		}
    		else
    		{
    			$info = $this->getTranslator("Aucun element trouve");
    		}
    	}
    	else
    	{
    		$error = $this->getTranslator("Tous les parametres de l'url n'ont pas ete transmis");
    	}
    	
    	return new JsonModel(array(
    			'error' => $error,
    			'info' => $info,
    			'varRetour' => $varRetour,
    	));
    }
}
