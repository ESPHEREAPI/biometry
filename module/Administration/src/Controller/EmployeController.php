<?php

namespace Administration\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Custom\Mvc\Controller\BackOfficeCommonController;

use Application\Core\Utilitaire;
use Application\Manager\MenuManager;

class EmployeController extends BackOfficeCommonController
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
    
	public function getFiltreListeEmployeForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Administration\Form\Employe\FiltreListeEmployeForm');
	}
	
	public function getEmployeForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Administration\Form\Employe\EmployeForm');
	}
	
	public function getEmployeInputFilter ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Application\Filter\EmployeInputFilter');
	}
	
    public function indexAction ()
    {
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$cheminPagination = $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/employe/pagination";
    	$this->nomPage = $this->getTranslator("Liste des employes");
    	
    	$boutons = array('btn_ajouter' => array('url' => $this->appliConfig->get('lienBackoffice').'/administration/employe/ajouter'),
    					 'btn_activer' => true,
		    			 'btn_desactiver' => true,
		    			 'btn_supprimer' => true,
    	);
    	
    	$formFiltre = $this->getFiltreListeEmployeForm();
    	$this->initBackViewList($boutons, 'Utilisateur', $formFiltre, $cheminPagination);
    	
        return new ViewModel(array(
        	
        ));
    }
    
    public function ajouterAction ()
    {
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$this->nomPage = $this->getTranslator("Ajouter un employe");
    	$formPosted = false;
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/employe";
    	$utilitaire = new Utilitaire();
    	
    	$form = $this->getEmployeForm();
    	
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    		$formPosted = true;
    		$employeInputFilter = $this->getEmployeInputFilter();
    		$form->setInputFilter($employeInputFilter->getInputFilter());
    		$form->setData($request->getPost());
    		
    		$form->setValidationGroup('nom', 'prenom', 'genre', 'email', 'login', 'motPasse', 'confirmMotPasse', 'prestataire', 'langueDefaut');
    		if ($form->isValid())
    		{
    			$donneesFormulaire = $form->getData();
    			if($donneesFormulaire['motPasse'] != $donneesFormulaire['confirmMotPasse'])
    			{
    				$form->get('confirmMotPasse')->setMessages(array($this->getTranslator('Les mots de passe doivent etre identiques')));
    			}
    			else 
    			{
    				$utilisateur = $this->appliContainer->get('Entity\Utilisateur');
    				$utilisateur->exchangeArray($donneesFormulaire);
    				$utilisateur->setType("employe");
    				$utilisateur->setLangueDefaut($this->getEntityManager()->find('Entity\Langue', $donneesFormulaire['langueDefaut']));
    				$utilisateur->setDateCreation(new \DateTime(date("Y-m-d H:i:s")));
    				
    				if($donneesFormulaire['motPasse'] != "")
    					$utilisateur->setMotPasse($utilitaire->crypterMotPass($donneesFormulaire['motPasse']));
    				
    				// Debut du mode transactionnel
    				$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    				try {
    				    $prestataire = $this->getEntityManager()->find('Entity\Prestataire', $donneesFormulaire['prestataire']);
    				    $profil = $this->getEntityManager()->getRepository('Entity\Profil')->findOneBy(array('code' => $prestataire->getCategorie()->getId(),));
    				    
    					// Enregistrement dans la table utilisateur
    					$utilisateur->nettoyageChaine();
    					$this->getEntityManager()->persist($utilisateur);
    					$this->getEntityManager()->flush();
    					 
    					// Enregistrement dans la table employe
    					$employe = $this->appliContainer->get('Entity\Employe');
    					$employe->setUtilisateur($utilisateur);
    					
    					
    					$employe->setPrestataire($prestataire);
    					$employe->setProfil($profil);
    					
    					$employe->nettoyageChaine();
    					$this->getEntityManager()->persist($employe);
    					$this->getEntityManager()->flush();
    					$this->getEntityManager()->getConnection()->commit();
    					
    					
    					// Redirection dans la la liste des employes
    					return $this->redirect()->toRoute("administration/employe");
    					
    				} catch (\Exception $e) {
    					$this->getEntityManager()->getConnection()->rollback();
    					$this->getEntityManager()->close();
    					throw $e;
    				}
    			}
    		}
    	}
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/employe/ajouter");
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	
    	$this->initBackViewSimpleForm('Employe', $form, $formPosted, $listEltsUrl, array('statut', 
    			'dateNaissance', 'lieuNaissance', 'telephone', 'connexionAppli'));
    	
    	return new ViewModel(array(
    	));
    }
    
    public function modifierAction ()
    {
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/employe";
    	$sessionEmploye = new Container('employe');
    	$utilitaire = new Utilitaire();
    	
    	$id = $this->params()->fromRoute('id', null);
    	if (!$id) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	$employe = $this->getEntityManager()->find('Entity\Employe', $id);
    	if (!$employe) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	
    	$utilisateur = $employe->getUtilisateur();
    	
    	$employe->afficheChaine();
    	$utilisateur->afficheChaine();

    	$this->nomPage = $this->getTranslator("Modifier un employe");
    	$formPosted = false;

    	$request = $this->getRequest();
    	
    	$form = $this->getEmployeForm();
    	$form->setData($employe->getArrayCopy());
    	$form->setData($utilisateur->getArrayCopy());
    	
    	$form->get('motPasse')->setValue("");
    	if($utilisateur->getDateNaissance())
    		$form->get('dateNaissance')->setValue($utilisateur->getDateNaissance()->format("Y-m-d"));

    	$form->get('motPasse')->setAttribute("class", str_replace("required", "", $form->get('motPasse')->getAttribute("class")));
    	$form->get('confirmMotPasse')->setAttribute("class", str_replace("required", "", $form->get('motPasse')->getAttribute("class")));
    	
    	
    	
    	$oldLogin = $utilisateur->getLogin();
    	$continue = true;
    	
    	if ($request->isPost())
    	{
    		$postData = $request->getPost();
    		$formPosted = true;
    		$employeInputFilter = $this->getEmployeInputFilter();
    		
    		$form->setInputFilter($employeInputFilter->getInputFilter());
    		$form->setData($postData);
    		
    		$form->setValidationGroup('nom', 'prenom', 'genre', 'email', 'motPasse', 'confirmMotPasse'
    				, 'prestataire', 'langueDefaut', 'telephone', 'dateNaissance', 'lieuNaissance', 'connexionAppli');
    		if ($form->isValid())
    		{
    			$donneesFormulaire = $form->getData();
    			
    			// Enregistrement dans la table utilisateur
    			if(empty($donneesFormulaire['dateNaissance']))
    				$dateNaissance = null;
    			else
    				$dateNaissance = new \DateTime(date($donneesFormulaire['dateNaissance']));
    			
    			if($oldLogin != $postData->login)
    			{
    				$autreUtilisateur = $this->getEntityManager()->getRepository('Entity\Utilisateur')->findOneBy(array('login' => $postData->login,));
    				if($autreUtilisateur)
    				{
    					$continue = false;
    					$form->get('login')->setMessages(array($this->getTranslator('Element deja utilise')));
    				}
    			}
    			elseif($postData->motPasse != "" && $postData->motPasse != $postData->confirmMotPasse)
    			{
    				$continue = false;
    				$form->get('confirmMotPasse')->setMessages(array($this->getTranslator('Les mots de passe doivent etre identiques')));
    			}
    			
    			$motPasse = $utilisateur->getMotPasse();
    			if($postData->motPasse != "")
    				$motPasse = $utilitaire->crypterMotPass($donneesFormulaire['motPasse']);
    			
    			
    			// Debut du mode transactionnel
    			$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    			try {
    				
    				if($continue)
    				{
    				    $prestataire = $this->getEntityManager()->find('Entity\Prestataire', $donneesFormulaire['prestataire']);
    				    $profil = $this->getEntityManager()->getRepository('Entity\Profil')->findOneBy(array('code' => $prestataire->getCategorie()->getId(),));
    				    
    				    
    					$utilisateur->exchangeArray($donneesFormulaire);
    					
    					$utilisateur->setLangueDefaut($this->getEntityManager()->find('Entity\Langue', $donneesFormulaire['langueDefaut']));
    					$utilisateur->setDateNaissance($dateNaissance);
    					$utilisateur->setLogin($postData->login);
    					$utilisateur->setMotPasse($motPasse);
    					
    					$employe->exchangeArray($donneesFormulaire);
    					
    					$employe->setPrestataire($prestataire);
    					$employe->setProfil($profil);
    					
    					
    					$utilisateur->nettoyageChaine();
    					$employe->nettoyageChaine();
    					$this->getEntityManager()->flush();
    					$this->getEntityManager()->getConnection()->commit();
    					
    					// Redirection dans la la liste des employes
    					return $this->redirect()->toRoute("administration/employe");
    				}
    			} catch (\Exception $e) {
    				$this->getEntityManager()->getConnection()->rollback();
    				$this->getEntityManager()->close();
    				throw $e;
    			}
    		}
    	}
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/employe/modifier/".$id);
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	 
    	$this->initBackViewSimpleForm('Employe', $form, $formPosted, $listEltsUrl);
    	 
    	return new ViewModel(array(
    	));
    }
    
    public function paginationAction()
    {
    	$sessionEmploye = new Container('employe');
    	$employeManager = $this->getEmployeManager();
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    
    	$numActuel = $this->params()->fromRoute('numActuel', null);
    	if(!$numActuel) $numActuel = 1;
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	if(is_array($postValues)  && isset($postValues['statut']) && isset($postValues['genre']) && isset($postValues['profil']) && isset($postValues['prestataire']))
    	{
    		$supprime = -1;
    		if($postValues['statut'] == 2)
    		{
    			$supprime = 1;
    			$postValues['statut'] = null;
    		}
    		
    		$retourPagination = $employeManager->getListeEmploye($postValues['statut'], $postValues['profil'], $postValues['genre'], $supprime, $numActuel,
    		                                                     $employeManager::NBRE_LIGNE_TABLEAU, true, null, $postValues['prestataire']); // $statut=null, $supprime=null, $nroPage=null, $nbreMax=null, $pagination=false
    		$tab = $retourPagination['tab'];
    		$totalResult = $retourPagination['totalResult'];

    		if(is_array($tab) && count($tab) > 0 && $totalResult > 0)
    		{
    			$nroPage = $numActuel;
    			$nbrePages = $employeManager::NBRE_PAGE_PAGINATION;
    			$nbreResults = $totalResult;
    			$nbreMaxResultsParPage = $employeManager::NBRE_LIGNE_TABLEAU;
    			$cheminControlleur = $basePath.'/';
    			$parametres = "";
    		
    			include_once __DIR__.'/../../../Application/view/partial/pagination.phtml';
    			 
    			 
    			if(isset($headPagination) && is_string($headPagination)) $varRetour .= $headPagination;
    			include_once __DIR__.'/../../view/administration/employe/pagination.phtml';
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
