<?php

namespace Regionalisation\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Custom\Mvc\Controller\BackOfficeCommonController;

use Application\Core\Utilitaire;

use Application\Manager\MenuManager;
use Entity\VilleLangue;

class VilleController extends BackOfficeCommonController
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
    
    
	public function getFiltreListeVilleForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Regionalisation\Form\Ville\FiltreListeVilleForm');
	}
	
	public function getVilleForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Regionalisation\Form\Ville\VilleForm');
	}
	
	public function getVilleInputFilter ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Application\Filter\VilleInputFilter');
	}
	
    public function indexAction ()
    {
    	$sessionAgence = new Container('agence');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$cheminPagination = $basePath."/".$this->appliConfig->get('lienBackoffice')."/regionalisation/ville/pagination";
    	$this->nomPage = $this->getTranslator("Liste des villes");
    	
    	$boutons = array('btn_ajouter' => array('url' => $this->appliConfig->get('lienBackoffice').'/regionalisation/ville/ajouter'),
    					 'btn_activer' => true,
		    			 'btn_desactiver' => true,
		    			 'btn_supprimer' => true,
    	);
    	
    	$formFiltre = $this->getFiltreListeVilleForm();
    	$this->initBackViewList($boutons, 'Ville', $formFiltre, $cheminPagination);
    	
        return new ViewModel(array(
        	'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
        ));
    }
    
    public function ajouterAction ()
    {
    	$sessionAgence = new Container('agence');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$this->nomPage = $this->getTranslator("Ajouter une ville");
    	$formPosted = false;
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/regionalisation/ville";
    	$utilitaire = new Utilitaire();
    	$sessionEmploye = new Container('employe');
    	$idRegion = "";
    	
    	$form = $this->getVilleForm();
    	
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    		$idRegion = $request->getPost()->region;
    		
    		$formPosted = true;
    		$villeInputFilter = $this->getVilleInputFilter();
    		
    		$form->setInputFilter($villeInputFilter->getInputFilter());
    		$form->setData($request->getPost());
    		
    		$form->setValidationGroup('nom', 'code', 'codeZone', 'pays');
    		if($form->isValid())
    		{
    			if(empty($idRegion))
    			{
    				$form->get("region")->setMessages(array($this->getTranslator("Veuillez selectionner une region")));
    			}
    			else
    			{
	    			$donneesFormulaire = $form->getData();
	    			
	    			if(empty($donneesFormulaire['code']))
	    				$donneesFormulaire['code'] = $utilitaire->textToURL($donneesFormulaire['nom']);
	    			
	    			$ville = $this->appliContainer->get('Entity\Ville');	
	    			$ville->exchangeArray($donneesFormulaire);
	    			$ville->setRegion($this->getEntityManager()->find('Entity\Region', $request->getPost()->region));
	    			
	    			// Debut du mode transactionnel
	    			$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
	    			try {
	    			
	    				// Enregistrement dans la table ville
	    				$ville->nettoyageChaine();
	    				$this->getEntityManager()->persist($ville);
	    				$this->getEntityManager()->flush();
	    			
	    				// Enregistrement dans la table ville_langue
	    				$tabLangue = $this->getEntityManager()->getRepository('Entity\Langue')->findAll();
	    				foreach ($tabLangue as $uneLangue)
	    				{
	    					$villeLangue = new VilleLangue();
	    						
	    					$villeLangue->exchangeArray($donneesFormulaire);
	    						
	    					// $villeLangue->setCodeUrl($codeUrlVille);
	    					$villeLangue->setVille($ville);
	    					$villeLangue->setLangue($uneLangue);
	    						
	    					$villeLangue->nettoyageChaine();
	    					$this->getEntityManager()->persist($villeLangue);
	    				}
	    			
	    				$this->getEntityManager()->flush();
	    				$this->getEntityManager()->getConnection()->commit();
	    					
	    					
	    				// Redirection dans la la liste des villes
	    				return $this->redirect()->toRoute("regionalisation/ville");
	    					
	    			} catch (\Exception $e) {
	    				$this->getEntityManager()->getConnection()->rollback();
	    				$this->getEntityManager()->close();
	    				throw $e;
	    			}	
    			}
    		}
    		else
    		{
    			if(empty($idRegion))
    			{
    				$form->get("region")->setMessages(array($this->getTranslator("Veuillez selectionner une region")));
    			}
    		}
    	}
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/regionalisation/ville/ajouter");
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	
    	$this->initBackViewSimpleForm('Ville', $form, $formPosted, $listEltsUrl, array("langue"));
    	
    	return new ViewModel(array(
    		'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    		'idRegion' => $idRegion
    	));
    }
    
    public function modifierAction ()
    {
    	$sessionAgence = new Container('agence');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/regionalisation/ville";
    	$sessionEmploye = new Container('employe');
    	$utilitaire = new Utilitaire();
    	 
    	$id = $this->params()->fromRoute('id', null);
    	if (!$id) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	$villeLangue = $this->getEntityManager()->find('Entity\VilleLangue', $id);
    	if (!$villeLangue) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	$ville = $villeLangue->getVille();
    	$idRegion = $ville->getRegion()->getId();
    	
    	$ville->afficheChaine();
    	$villeLangue->afficheChaine();
    	
    	$formPosted = false;
    	
    	$request = $this->getRequest();
    	 
    	$form = $this->getVilleForm();
    	$form->setData($villeLangue->getArrayCopy());
    	$form->setData($ville->getArrayCopy());
    	$form->get('pays')->setValue($ville->getRegion()->getPays()->getId());
    	$form->get('region')->setValue($ville->getRegion()->getId());
    	  
    	if($request->isPost())
    	{
    		$idRegion = $request->getPost()->region;
    		$villeLangue = $this->getEntityManager()->getRepository('Entity\VilleLangue')->findOneBy(array('langue' => $request->getPost()->langue,
    			'ville' => $ville->getId(),
    		));
    	
    		$form->get('langue')->setValue($request->getPost()->langue);
    		$form->get('pays')->setValue($request->getPost()->pays);
    		$form->get('region')->setValue($request->getPost()->region);
    	
    	
    		if(!$villeLangue)
    		{
    			$villeLangue = new VilleLangue();
    			$villeLangue->setVille($ville);
    		}
    			 
    		$postData = $request->getPost();
    		$formPosted = true;
    		$villeInputFilter = $this->getVilleInputFilter();
    	
    		$form->setInputFilter($villeInputFilter->getInputFilter());
    		$form->setData($postData);
    		
    		$form->setValidationGroup('nom', 'code', 'codeZone', 'pays', 'langue');
    		if ($form->isValid())
    		{
    			if(empty($idRegion))
    			{
    				$form->get("region")->setMessages(array($this->getTranslator("Veuillez selectionner une region")));
    			}
    			else
    			{
	    			$donneesFormulaire = $form->getData();
	    			if(empty($donneesFormulaire['code']))
	    				$donneesFormulaire['code'] = $utilitaire->textToURL($request->getPost()->nom);
	    			
	    			$villeLangue->exchangeArray($donneesFormulaire);
	    			$ville->exchangeArray($donneesFormulaire);
	    			$ville->setRegion($this->getEntityManager()->find('Entity\Region', $request->getPost()->region));
	    								 
	    			$villeLangue->setLangue($this->getEntityManager()->find('Entity\Langue', $donneesFormulaire['langue']));
	    			
	    			// Debut du mode transactionnel
	    			$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
	    			try {
	    										
	    				if(!$villeLangue->getId())
	    				{
	    					$villeLangue->setVille($ville);
	    					$this->getEntityManager()->persist($villeLangue);
	    					
	    				}
						
	    				$ville->nettoyageChaine();
	    				$villeLangue->nettoyageChaine();
	    				$this->getEntityManager()->flush();
	    				$this->getEntityManager()->getConnection()->commit();
	    											
	    				// Redirection dans la la liste des villes
	    				return $this->redirect()->toRoute("regionalisation/ville");
	    			}
	    			catch (\Exception $e) {
	    					$this->getEntityManager()->getConnection()->rollback();
	    					$this->getEntityManager()->close();
	    					throw $e;
	    			}	
    			}
    		}
    		else
    		{
    			if(empty($idRegion))
    			{
    				$form->get("region")->setMessages(array($this->getTranslator("Veuillez selectionner une region")));
    			}
    		}
    	}
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/regionalisation/ville/modifier/".$id);
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    		 
    		 
    	$this->nomPage = $this->getTranslator("Modifier la ville")." « ".$villeLangue->getNom()." »";
    		 
    	$this->initBackViewSimpleForm('Ville', $form, $formPosted, $listEltsUrl);
    	
    	return new ViewModel(array(
    		'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    		'idVilleLangue' => $id,
    		'ville' => $ville,
    		'idRegion' => $idRegion
    	));
    }
    
    public function paginationAction()
    {
    	$sessionAgence = new Container('agence');
    	$sessionEmploye = new Container('employe');
    	$villeManager = $this->getVilleManager();
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    
    	$numActuel = $this->params()->fromRoute('numActuel', null);
    	if(!$numActuel) $numActuel = 1;
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	
    	if(is_array($postValues) && isset($postValues['statut']) && isset($postValues['pays']) 
    	   && isset($postValues['region']) && isset($postValues['zone'])
    	   && isset($postValues['nomColoneTriPagination']) && isset($postValues['typeTriColonePagination'])
    	   && isset($postValues['nbreMaxLigneTableau']))
    	{
    		$supprime = -1;
    		if($postValues['statut'] == 2)
    		{
    			$supprime = 1;
    			$postValues['statut'] = null;
    		}
    		
    		$retourPagination = $villeManager->getListeVilleLangue($postValues['pays'], $postValues['region'], $postValues['zone'], 
    						 	 $postValues['statut'], $supprime, $sessionEmploye->offsetGet("id_langue"), array($postValues['nomColoneTriPagination'] => $postValues['typeTriColonePagination']),
    							 $numActuel,
    							 $postValues['nbreMaxLigneTableau'], true); // $statut=null, $supprime=null, $nroPage=null, $nbreMax=null, $pagination=false
    		
    		$tab = $retourPagination['tab'];
    		$totalResult = $retourPagination['totalResult'];

    		if(is_array($tab) && count($tab) > 0 && $totalResult > 0)
    		{
    			$nroPage = $numActuel;
    			$nbrePages = $villeManager::NBRE_PAGE_PAGINATION;
    			$nbreResults = $totalResult;
    			$nbreMaxResultsParPage = $postValues['nbreMaxLigneTableau'];
    			$cheminControlleur = $basePath.'/';
    			$parametres = "";
    		
    			include_once __DIR__.'/../../../Application/view/partial/pagination.phtml';
    			 
    			 
    			if(isset($headPagination) && is_string($headPagination)) $varRetour .= $headPagination;
    			include_once __DIR__.'/../../view/regionalisation/ville/pagination.phtml';
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
    
    public function chargerVillesAjaxAction()
    {
    	$sessionAgence = new Container('agence');
    	$sessionEmploye = new Container('employe');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    	
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	 
    	$id = $this->params()->fromRoute('id', null);
    	if (!$id) {
    		$error = $this->getTranslator("Aucune ville trouve");
    	}
    	$villeLangue = $this->getEntityManager()->find('Entity\VilleLangue', $id);
    	if (!$villeLangue) {
    		$error = $this->getTranslator("Aucune ville trouve");
    	}
    	$ville = $villeLangue->getVille();
    	 
    	 
    	if($error == "" && is_array($postValues) && isset($postValues['langue']))
    	{
    		$newVilleLangue = $this->getEntityManager()->getRepository('Entity\VilleLangue')->findOneBy(array('langue' => $postValues['langue'],
    			'ville' => $ville->getId(),
    		));
    	
    		if($newVilleLangue)
    		{
    			$newVilleLangue->afficheChaine();
    			$varRetour = $newVilleLangue->getArrayCopy();
    		}
    		else
    		{
    			$error = $this->getTranslator("Aucune ville langue trouve");
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
			'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    	));
    }
}
