<?php

namespace Administration\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Custom\Mvc\Controller\BackOfficeCommonController;

use Application\Core\Utilitaire;

use Application\Manager\MenuManager;
use Entity\ProfilLangue;

class ProfilController extends BackOfficeCommonController
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
    
	public function getFiltreListeProfilForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Administration\Form\Profil\FiltreListeProfilForm');
	}
	
	public function getProfilForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Administration\Form\Profil\ProfilForm');
	}
	
	public function getProfilInputFilter ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Application\Filter\ProfilInputFilter');
	}
	
    public function indexAction ()
    {
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$cheminPagination = $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/profil/pagination";
    	$this->nomPage = $this->getTranslator("Liste des profils");
    	
    	$boutons = array('btn_ajouter' => array('url' => $this->appliConfig->get('lienBackoffice').'/administration/profil/ajouter'),
    					 'btn_activer' => true,
		    			 'btn_desactiver' => true,
		    			 'btn_supprimer' => true,
    	);
    	
    	$formFiltre = $this->getFiltreListeProfilForm();
    	
    	$this->initBackViewList($boutons, 'Profil', $formFiltre, $cheminPagination);
    	
        return new ViewModel(array(
        	
        ));
    }
    
    public function ajouterAction ()
    {
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$this->nomPage = $this->getTranslator("Ajouter un profil");
    	$formPosted = false;
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/profil";
    	$utilitaire = new Utilitaire();
    	
    	$form = $this->getprofilForm();
    	
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    		$formPosted = true;
    		$profilInputFilter = $this->getProfilInputFilter();
    		$form->setInputFilter($profilInputFilter->getInputFilter());
    		$form->setData($request->getPost());
    		
    		if ($form->isValid())
    		{
    			$donneesFormulaire = $form->getData();
    			$profil = $this->appliContainer->get('Entity\Profil');
    			$profil->exchangeArray($donneesFormulaire);
    			$profil->setStatut(1);
    			
    			// Debut du mode transactionnel
    			$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    			try {
    			
    				// Enregistrement dans la table profil
    				$profil->nettoyageChaine();
    				$this->getEntityManager()->persist($profil);
    				$this->getEntityManager()->flush();
    			
    				// Enregistrement dans la table profil_langue
    				$tabLangue = $this->getEntityManager()->getRepository('Entity\Langue')->findAll();
    				foreach ($tabLangue as $uneLangue)
    				{
    					$profilLangue = new ProfilLangue();
    					$profilLangue->setProfil($profil);
    					$profilLangue->setNom($donneesFormulaire['nom']);
    					$profilLangue->setLangue($uneLangue);
    					
    					$profilLangue->nettoyageChaine();
    					$this->getEntityManager()->persist($profilLangue);
    				}
    					
    				$this->getEntityManager()->flush();
    				$this->getEntityManager()->getConnection()->commit();
    			
	    			// Redirection dans la la liste des profils
	    			return $this->redirect()->toRoute("administration/profil");
    					
    			} catch (\Exception $e) {
    				$this->getEntityManager()->getConnection()->rollback();
    				$this->getEntityManager()->close();
    				throw $e;
    			}
    		}
    	}
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/profil/ajouter");
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	
    	$this->initBackViewSimpleForm('Profil', $form, $formPosted, $listEltsUrl, array('statut'));
    	
    	return new ViewModel(array(
    	));
    }
    
    public function modifierAction ()
    {
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/profil";
    	$sessionEmploye = new Container('employe');
    	
    	$id = $this->params()->fromRoute('id', null);
    	if (!$id) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	$profil = $this->getEntityManager()->find('Entity\Profil', $id);
    	if (!$profil) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	
    	$profilLangue = $this->getEntityManager()->getRepository('Entity\ProfilLangue')->findOneBy(array('langue' => $sessionEmploye->offsetGet("id_langue"),
    																														'profil' => $profil->getId(),
    																												 ));
    	if (!$profilLangue) {
    		$profilLangue = new ProfilLangue();
    	}
    	
    	
    	$profil->afficheChaine();
    	$profilLangue->afficheChaine();
    	

    	$this->nomPage = $this->getTranslator("Modifier un profil");
    	$formPosted = false;

    	$request = $this->getRequest();
    	
    	$form = $this->getProfilForm();
    	$form->setData($profil->getArrayCopy());
    	$form->setData(array('nom' => $profilLangue->getNom()));
		
    	$oldCode = $profilLangue->getProfil()->getCode();
    	$continue = true;
    	
    	if ($request->isPost())
    	{
    		$postData = $request->getPost();
    		$formPosted = true;
    		$profilInputFilter = $this->getProfilInputFilter();
    		
    		$form->setInputFilter($profilInputFilter->getInputFilter());
    		$form->setData($postData);
    		
    		$form->setValidationGroup('nom', 'typeProfil', 'typeSousProfil');
    		if ($form->isValid())
    		{
    			$donneesFormulaire = $form->getData();
    			
    			if($oldCode != $postData->code)
    			{
    				$autreProfil = $this->getEntityManager()->getRepository('Entity\Profil')->findOneBy(array('code' => $postData->code,));
    				if($autreProfil)
    				{
    					$continue = false;
    					$form->get('code')->setMessages(array($this->getTranslator('Code deja utilise')));
    				}
    			}
    			
    			if($continue)
    			{
    				// Debut du mode transactionnel
    				$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    				try {
    					$profil->exchangeArray($donneesFormulaire);
    					$profil->setCode($postData->code);
    						
    					// Enregistrement dans la table profil_langue
    					$profilLangue->setNom($postData->nom);
    					if(!$profilLangue->getId())
    					{
    						$profilLangue->setProfil($profil);
    						$profilLangue->setLangue($this->getEntityManager()->getRepository('Entity\Langue')->findOneBy(array('code' => $sessionEmploye->offsetGet("code_langue"))));
    						$this->getEntityManager()->persist($profilLangue);
    					}
    					
    					$profil->nettoyageChaine();
    					$profilLangue->nettoyageChaine();
    					$this->getEntityManager()->flush();
    					$this->getEntityManager()->getConnection()->commit();
    						
    					// Redirection dans la la liste des employes
    					return $this->redirect()->toRoute("administration/profil");
    						
    				} catch (\Exception $e) {
    					$this->getEntityManager()->getConnection()->rollback();
    					$this->getEntityManager()->close();
    					throw $e;
    				}	
    			}
    		}
    	}
    	
    	
    	
    	
    	
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/profil/modifier/".$id);
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	 
    	$this->initBackViewSimpleForm('Profil', $form, $formPosted, $listEltsUrl);
    	 
    	return new ViewModel(array(
    	));
    }
    
    public function paginationAction()
    {
    	$sessionEmploye = new Container('employe');
    	$profilManager = $this->getProfilManager();
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    
    	$numActuel = $this->params()->fromRoute('numActuel', null);
    	if(!$numActuel) $numActuel = 1;
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	if(is_array($postValues)  && isset($postValues['statut']))
    	{
    		$supprime = -1;
    		if($postValues['statut'] == 2)
    		{
    			$supprime = 1;
    			$postValues['statut'] = null;
    		}
    		
			$retourPagination = $profilManager->getListeProfilLangue($sessionEmploye->offsetGet("code_langue"), $postValues['statut'], $supprime, $numActuel,
    																	$profilManager::NBRE_LIGNE_TABLEAU, true); // $codeLangue="fr_FR", $statut=null, $supprime=null, $nroPage=null, $nbreMax=null, $pagination=false

    		$tab = $retourPagination['tab'];
    		$totalResult = $retourPagination['totalResult'];
    		
    
    		if(is_array($tab) && count($tab) > 0 && $totalResult > 0)
    		{
    			$nroPage = $numActuel;
    			$nbrePages = $profilManager::NBRE_PAGE_PAGINATION;
    			$nbreResults = $totalResult;
    			$nbreMaxResultsParPage = $profilManager::NBRE_LIGNE_TABLEAU;
    			$cheminControlleur = $basePath.'/';
    			$parametres = "";
    			
    			include_once __DIR__.'/../../../Application/view/partial/pagination.phtml';
    			
    			
    			if(isset($headPagination) && is_string($headPagination)) $varRetour .= $headPagination;
    			include_once __DIR__.'/../../view/administration/profil/pagination.phtml';
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
    	));
    }
}
