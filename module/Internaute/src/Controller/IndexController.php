<?php

namespace Internaute\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

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
    
    
	public function getFiltreListeInternauteForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Internaute\Form\FiltreListeInternauteForm');
	}
	
	public function getInternauteForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Internaute\Form\InternauteForm');
	}
	
	public function getInternauteInputFilter ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Application\Filter\InternauteInputFilter');
	}
	
    public function indexAction ()
    {
    	$sessionAgence = new Container('agence');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$cheminPagination = $basePath."/".$this->appliConfig->get('lienBackoffice')."/internaute/pagination";
    	$this->nomPage = $this->getTranslator("Liste des internautes");
    	
    	$boutons = array('btn_activer' => true,
		    			 'btn_desactiver' => true,
		    			 'btn_supprimer' => true,
    	);
    	
    	$formFiltre = $this->getFiltreListeInternauteForm();
    	$this->initBackViewList($boutons, 'Utilisateur', $formFiltre, $cheminPagination);
    	
        return new ViewModel(array(
        	'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
        ));
    }
    
    public function paginationAction()
    {
    	$sessionAgence = new Container('agence');
    	$sessionEmploye = new Container('employe');
    	$internauteManager = $this->getInternauteManager();
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    
    	$numActuel = $this->params()->fromRoute('numActuel', null);
    	if(!$numActuel) $numActuel = 1;
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	
    	if(is_array($postValues) && isset($postValues['statut']) && isset($postValues['genre'])
           && isset($postValues['nomColoneTriPagination']) && isset($postValues['typeTriColonePagination'])
    	   && isset($postValues['nbreMaxLigneTableau']))
    	{
    		$supprime = -1;
    		if($postValues['statut'] == 2)
    		{
    			$supprime = 1;
    			$postValues['statut'] = null;
    		}
    		
    		$retourPagination = $internauteManager->getListeInternaute($postValues['statut'], $postValues['genre'], null, null, 
    														$numActuel, $postValues['nbreMaxLigneTableau'], true,
        													array($postValues['nomColoneTriPagination'] => $postValues['typeTriColonePagination'])); // $idLangue=null, $statut=null, $supprime=null, $nroPage=null, $nbreMax=null, $pagination=false
    		
    		$tab = $retourPagination['tab'];
    		$totalResult = $retourPagination['totalResult'];

    		if(is_array($tab) && count($tab) > 0 && $totalResult > 0)
    		{
    			$nroPage = $numActuel;
    			$nbrePages = $internauteManager::NBRE_PAGE_PAGINATION;
    			$nbreResults = $totalResult;
    			$nbreMaxResultsParPage = $postValues['nbreMaxLigneTableau'];
    			$cheminControlleur = $basePath.'/';
    			$parametres = "";
    		
    			include_once __DIR__.'/../../../Application/view/partial/pagination.phtml';
    			 
    			 
    			if(isset($headPagination) && is_string($headPagination)) $varRetour .= $headPagination;
    			include_once __DIR__.'/../../view/internaute/index/pagination.phtml';
    			if(isset($varRetourControls) && is_string($varRetourControls)) $varRetour .= $varRetourControls;
    		}
    		else
    		{
    			$info = $this->getTranslator("Aucun element trouve");
    		}
    	}
    	else
    	{
    		$error = $this->getTranslator("Toutes les valeurs du filtre n'ont pas ete transmises");
    	}
    	
    	return new JsonModel(array(
    			'error' => $error,
    			'info' => $info,
    			'varRetour' => $varRetour,
    			'numActuel' => $numActuel,
    			'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    	));
    }
}
