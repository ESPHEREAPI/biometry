<?php

namespace Administration\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Custom\Mvc\Controller\BackOfficeCommonController;

use Application\Core\Utilitaire;
use Application\Manager\MenuManager;

class PrestataireController extends BackOfficeCommonController
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
    
	public function getFiltreListePrestataireForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Administration\Form\Prestataire\FiltreListePrestataireForm');
	}
	
	public function getPrestataireForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Administration\Form\Prestataire\PrestataireForm');
	}
	
	public function getPrestataireInputFilter ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Application\Filter\PrestataireInputFilter');
	}
	
    public function indexAction ()
    {
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$cheminPagination = $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/prestataire/pagination";
    	$this->nomPage = $this->getTranslator("Liste des prestataires");
    	
    	$boutons = array('btn_ajouter' => array('url' => $this->appliConfig->get('lienBackoffice').'/administration/prestataire/ajouter'),
    					 'btn_activer' => true,
		    			 'btn_desactiver' => true,
		    			 'btn_supprimer' => true,
    	);
    	
    	$formFiltre = $this->getFiltreListePrestataireForm();
    	
    	$this->initBackViewList($boutons, 'Prestataire', $formFiltre, $cheminPagination);
    	
        return new ViewModel(array(
        	
        ));
    }
    
    public function ajouterAction ()
    {
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$this->nomPage = $this->getTranslator("Ajouter un prestataire");
    	$formPosted = false;
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/prestataire";
    	$utilitaire = new Utilitaire();
    	
    	$form = $this->getprestataireForm();
    	
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    	    $postData = array_merge_recursive(
    	        $this->getRequest()->getPost()->toArray(),
    	        $this->getRequest()->getFiles()->toArray()
    	        );;
    	    
    		$formPosted = true;
    		$prestataireInputFilter = $this->getPrestataireInputFilter();
    		$form->setInputFilter($prestataireInputFilter->getInputFilter());
    		$form->setData($postData);
    		
    		
    		if(!isset($postData['logo']['name']) || empty($postData['logo']['name']))
    		{
    		    $form->getInputFilter()->remove('logo');
    		}
    		
    		if ($form->isValid())
    		{
    			$donneesFormulaire = $form->getData();
    			$prestataire = $this->appliContainer->get('Entity\Prestataire');
    			$prestataire->exchangeArray($donneesFormulaire);
    			$prestataire->setLogo(null);
    			
    			
    			$prestataire->setCategorie($this->getEntityManager()->find('Entity\CategoriePrestataire', $donneesFormulaire['categorie']));
    			if(!empty($donneesFormulaire['ville']))
    			{
    			    $prestataire->setVille($this->getEntityManager()->find('Entity\Ville', $donneesFormulaire['ville']));
    			}
    			
    			$prestataire->setStatut(1);
    			
    			// Debut du mode transactionnel
    			$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    			try {
    			
    				// Enregistrement dans la table prestataire
    				$prestataire->nettoyageChaine();
    				$this->getEntityManager()->persist($prestataire);
    				$this->getEntityManager()->flush();
    			
    					
    				$this->getEntityManager()->flush();
    				$this->getEntityManager()->getConnection()->commit();
    				
    				
    				// Pour enregistrer le logo
    				if(isset($postData['logo']['name']) && $postData['logo']['name'] != "")
    				{
    				    $uploadDirectory    = __DIR__.'/../../../../public/img/back/prestataire/logo/'; //specify upload directory ends with / (slash)
    				    
    				    // Enregistrement de l'avis d'appel d'offre
    				    $fileName          = $postData['logo']['name'];
    				    $fileExt           = strtolower(substr($fileName, strrpos($fileName, '.'))); //get file extention
    				    $newFileName       = "LOGO_".$prestataire->getId().$fileExt; //new file name
    				    
    				    if(move_uploaded_file($postData['logo']['tmp_name'], $uploadDirectory.$newFileName ))
    				    {
    				        $prestataire->setLogo($newFileName);
    				        $this->getEntityManager()->flush();
    				    }
    				}
    				
    				
    				
    			
	    			// Redirection dans la la liste des prestataires
	    			return $this->redirect()->toRoute("administration/prestataire");
    					
    			} catch (\Exception $e) {
    				$this->getEntityManager()->getConnection()->rollback();
    				$this->getEntityManager()->close();
    				throw $e;
    			}
    		}
    	}
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/prestataire/ajouter");
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	
    	$this->initBackViewSimpleForm('Prestataire', $form, $formPosted, $listEltsUrl, array('statut', 'submitClose'));
    	
    	return new ViewModel(array(
    	));
    }
    
    public function modifierAction ()
    {
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/prestataire";
    	$sessionEmploye = new Container('employe');
    	$msgSuccess = "";
    	
    	
    	$id = $this->params()->fromRoute('id', null);
    	if (!$id) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	$prestataire = $this->getEntityManager()->find('Entity\Prestataire', $id);
    	if (!$prestataire) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	
    	
    	$prestataire->afficheChaine();
    	
    	$oldLogo = $prestataire->getLogo();
    	

    	$this->nomPage = $this->getTranslator("Modifier un prestataire");
    	$formPosted = false;

    	$request = $this->getRequest();
    	
    	$form = $this->getPrestataireForm();
    	$form->setData($prestataire->getArrayCopy());
		
    	$continue = true;
    	
    	if ($request->isPost())
    	{
    	    $postData = array_merge_recursive(
    	        $this->getRequest()->getPost()->toArray(),
    	        $this->getRequest()->getFiles()->toArray()
    	        );;
    		$formPosted = true;
    		$prestataireInputFilter = $this->getPrestataireInputFilter();
    		
    		$form->setInputFilter($prestataireInputFilter->getInputFilter());
    		$form->setData($postData);
    		
    		
    		if(!isset($postData['logo']['name']) || empty($postData['logo']['name']))
    		{
    		    $form->getInputFilter()->remove('logo');
    		}
    		
    		if ($form->isValid())
    		{
    			$donneesFormulaire = $form->getData();
    			
    			
    			if($continue)
    			{
    				// Debut du mode transactionnel
    				$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    				try {
    				    $prestataire->exchangeArray($donneesFormulaire);
    				    $prestataire->setLogo($oldLogo);
    					$prestataire->setCategorie($this->getEntityManager()->find('Entity\CategoriePrestataire', $donneesFormulaire['categorie']));
    					
    					if(!empty($donneesFormulaire['ville']))
    					{
    					    $prestataire->setVille($this->getEntityManager()->find('Entity\Ville', $donneesFormulaire['ville']));
    					}
    					else
    					{
    					    $prestataire->setVille(null);
    					}
    					
    					
    					
    					$prestataire->nettoyageChaine();
    					$this->getEntityManager()->flush();
    					$this->getEntityManager()->getConnection()->commit();
    						
    					
    					
    					// Pour enregistrer le logo
    					if(isset($postData['logo']['name']) && $postData['logo']['name'] != "")
    					{
    					    $uploadDirectory    = __DIR__.'/../../../../public/img/back/prestataire/logo/'; //specify upload directory ends with / (slash)
    					    
    					    // Enregistrement de l'avis d'appel d'offre
    					    $fileName          = $postData['logo']['name'];
    					    $fileExt           = strtolower(substr($fileName, strrpos($fileName, '.'))); //get file extention
    					    $newFileName       = "LOGO_".$prestataire->getId().$fileExt; //new file name
    					    
    					    if(move_uploaded_file($postData['logo']['tmp_name'], $uploadDirectory.$newFileName ))
    					    {
    					        $prestataire->setLogo($newFileName);
    					        $this->getEntityManager()->flush();
    					    }
    					}
    					
    					
    					
    					// Redirection dans la la liste des employes
    					// return $this->redirect()->toRoute("administration/prestataire");
    					if(isset($request->getPost()->submitClose))
    					{
    					    // Redirection dans la la liste des questions
    					    return $this->redirect()->toUrl($listEltsUrl);
    					}
    					else
    					{
    					    $msgSuccess = $this->getTranslator("Operation effectuee avec success");
    					}
    						
    				} catch (\Exception $e) {
    					$this->getEntityManager()->getConnection()->rollback();
    					$this->getEntityManager()->close();
    					throw $e;
    				}	
    			}
    		}
    	}
    	
    	
    	
    	
    	
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/administration/prestataire/modifier/".$id);
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	 
    	$this->initBackViewSimpleForm('Prestataire', $form, $formPosted, $listEltsUrl, array(), $msgSuccess);
    	 
    	return new ViewModel(array(
    	));
    }
    
    public function paginationAction()
    {
    	$sessionEmploye = new Container('employe');
    	$prestataireManager = $this->getPrestataireManager();
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    
    	$numActuel = $this->params()->fromRoute('numActuel', null);
    	if(!$numActuel) $numActuel = 1;
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	if(is_array($postValues) && isset($postValues['statut']) && isset($postValues['categorie']) && isset($postValues['ville']))
    	{
    		$supprime = -1;
    		if($postValues['statut'] == 2)
    		{
    			$supprime = 1;
    			$postValues['statut'] = null;
    		}
    		
			$retourPagination = $prestataireManager->getListePrestataire("", $postValues['statut'], $supprime, $numActuel,
    																	 $prestataireManager::NBRE_LIGNE_TABLEAU, true,
			                                                             $postValues['categorie'], array(), $postValues['ville']); // $codeLangue="fr_FR", $statut=null, $supprime=null, $nroPage=null, $nbreMax=null, $pagination=false

    		$tab = $retourPagination['tab'];
    		$totalResult = $retourPagination['totalResult'];
    		
    
    		if(is_array($tab) && count($tab) > 0 && $totalResult > 0)
    		{
    			$nroPage = $numActuel;
    			$nbrePages = $prestataireManager::NBRE_PAGE_PAGINATION;
    			$nbreResults = $totalResult;
    			$nbreMaxResultsParPage = $prestataireManager::NBRE_LIGNE_TABLEAU;
    			$cheminControlleur = $basePath.'/';
    			$parametres = "";
    			
    			include_once __DIR__.'/../../../Application/view/partial/pagination.phtml';
    			
    			
    			if(isset($headPagination) && is_string($headPagination)) $varRetour .= $headPagination;
    			include_once __DIR__.'/../../view/administration/prestataire/pagination.phtml';
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
