<?php

namespace Menu\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Custom\Mvc\Controller\BackOfficeCommonController;

use Application\Core\Utilitaire;
use Application\Manager\MenuManager;

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
    
    
	public function getFiltreListeMenuForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Menu\Form\FiltreListeMenuForm');
	}
	
	public function getMenuForm ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Menu\Form\MenuForm');
	}
	
	public function getMenuInputFilter ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Application\Filter\MenuInputFilter');
	}
	
    public function indexAction ()
    {
    	$sessionAgence = new Container('agence');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$cheminPagination = $basePath."/".$this->appliConfig->get('lienBackoffice')."/menu/pagination";
    	$this->nomPage = $this->getTranslator("Liste des menus");
    	
    	$boutons = array('btn_ajouter' => array('url' => $this->appliConfig->get('lienBackoffice').'/menu/ajouter'),
    					 'btn_activer' => true,
		    			 'btn_desactiver' => true,
		    			 'btn_supprimer' => true,
    	);
    	
    	$formFiltre = $this->getFiltreListeMenuForm();
    	$this->initBackViewList($boutons, 'Menu', $formFiltre, $cheminPagination);
    	
    	
    	
    	
    	// Reconstruction des chemins vers l'ancetre
    	$tabAll = $this->getEntityManager()->getRepository('Entity\Menu')->findBy(array());
    	foreach ($tabAll as $unElt)
    	{
    		$unElt->setCheminPere($this->construireCheminPere($unElt, "Position"));
    	}
    	$this->getEntityManager()->flush();
    	
    	
    	
    	
        return new ViewModel(array(
        	'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
        ));
    }
    
    public function ajouterAction ()
    {
    	$sessionAgence = new Container('agence');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$this->nomPage = $this->getTranslator("Ajouter une menu");
    	$formPosted = false;
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/menu";
    	$utilitaire = new Utilitaire();
    	$sessionEmploye = new Container('employe');
    	$menuManager = $this->getMenuManager();
    	
    	$form = $this->getMenuForm();
    	
    	$request = $this->getRequest();
    	if ($request->isPost())
    	{
    		$formPosted = true;
    		$menuInputFilter = $this->getMenuInputFilter();
    		$menuInputFilter->valeurPere = $request->getPost()->pere;
    		
    		$form->setInputFilter($menuInputFilter->getInputFilter());
    		$form->setData($request->getPost());
    		
    		
    		
    		
    		
    		$tab = $menuManager->getListeMenuLangue(null, null, -1, null,
    					null, $request->getPost()->type, null, null,
    					null, 1, null, false,
    					array("cheminPere" => "ASC", "position" => "ASC", "nom" => "ASC"),
    					$sessionEmploye->offsetGet("id_langue")
    			);

    		$optionsMenuPere = array("" => $this->getTranslator("Selectionnez le menu parent"));
    		if(is_array($tab))
    		{
    			foreach ($tab as $element)
    			{
    				$optionsMenuPere[$element->getMenu()->getId()] = $this->construireComplementNomAncetre($element->getMenu())." ".$element->getNom();
    			}
    		}
    			
    		$form->get("pere")->setValueOptions($optionsMenuPere);
    		
    		$form->setValidationGroup('type', 'pere', 'nom', 'descCourte', 'url', 'nomControlleur', 'nomModule', 
    								  'nomAction', 'classImage', 'position', 'apparaitNav', 'apparaitNavBar', 'numeroOrdre');
    		
    		if($form->isValid())
    		{
    			$donneesFormulaire = $form->getData();
    			
    			$menu = $this->appliContainer->get('Entity\Menu');
    			$menu->exchangeArray($donneesFormulaire);

    			if(!empty($donneesFormulaire['pere']))
    				$menu->setPere($this->getEntityManager()->find('Entity\Menu', $donneesFormulaire['pere']));
    			else
    				$menu->setPere(null);
    			

    			// Debut du mode transactionnel
    			$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    			try {
    			
    				// Enregistrement dans la table menu
    				
    				$menu->nettoyageChaine();
    				$this->getEntityManager()->persist($menu);
    				$this->getEntityManager()->flush();
    				
    				$menu->setCheminPere($this->construireCheminPere($menu, "Position"));
    				$this->getEntityManager()->flush();
    				
    				
    				// Enregistrement dans la table menu_langue
    				$menuLangue = $this->appliContainer->get('Entity\MenuLangue');
    						
    				$menuLangue->exchangeArray($donneesFormulaire);
    				$menuLangue->setMenu($menu);
    				$menuLangue->setLangue($this->getEntityManager()->find('Entity\Langue', $sessionEmploye->offsetGet("id_langue")));
    						
    				$menuLangue->nettoyageChaine();
    				$this->getEntityManager()->persist($menuLangue);
    				
    				$this->getEntityManager()->flush();
    				$this->getEntityManager()->getConnection()->commit();
    					
    					
    				// Redirection dans la la liste des menus
    				return $this->redirect()->toRoute("menu");
    					
    			} catch (\Exception $e) {
    				$this->getEntityManager()->getConnection()->rollback();
    				$this->getEntityManager()->close();
    				throw $e;
    			}
    		}
    	}
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/menu/ajouter");
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	
    	$this->initBackViewSimpleForm('Menu', $form, $formPosted, $listEltsUrl, array("langue"));
    	
    	return new ViewModel(array(
    		'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    	));
    }
    
    public function modifierAction ()
    {
    	$sessionAgence = new Container('agence');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/menu";
    	$sessionEmploye = new Container('employe');
    	$utilitaire = new Utilitaire();
    	$menuManager = $this->getMenuManager();
    	
    	$id = $this->params()->fromRoute('id', null);
    	if (!$id) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	$menuLangue = $this->getEntityManager()->find('Entity\MenuLangue', $id);
    	if (!$menuLangue) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	$menu = $menuLangue->getMenu();

    	$formPosted = false;

    	$request = $this->getRequest();
    	
    	$form = $this->getMenuForm();
    	$form->setData($menuLangue->getArrayCopy());
    	
    	
    	
    	$tab = $menuManager->getListeMenuLangue(null, null, -1, null,
    			null, $menu->getType(), null, null,
    			null, 1, null, false,
    			array("cheminPere" => "ASC", "position" => "ASC", "nom" => "ASC"),
    			$menuLangue->getLangue()->getId()
    	);
    	
    	$optionsMenuPere = array("" => $this->getTranslator("Selectionnez le menu parent"));
    	if(is_array($tab))
    	{
    		foreach ($tab as $element)
    		{
    			$element->afficheChaine();
    			$optionsMenuPere[$element->getMenu()->getId()] = $this->construireComplementNomAncetre($element->getMenu())." ".$element->getNom();
    		}
    	}
    	 
    	$form->get("pere")->setValueOptions($optionsMenuPere);
    	
    	
    	
    	$form->get('nomControlleur')->setValue($menu->getNomControlleur());
    	$form->get('nomModule')->setValue($menu->getNomModule());
    	$form->get('nomAction')->setValue($menu->getNomAction());
    	$form->get('classImage')->setValue($menu->getClassImage());
    	$form->get('position')->setValue($menu->getPosition());
    	$form->get('apparaitNav')->setValue($menu->getApparaitNav());
    	$form->get('apparaitNavBar')->setValue($menu->getApparaitNavBar());
    	$form->get('numeroOrdre')->setValue($menu->getNumeroOrdre());
    	
    	
    	$form->get('nom')->setValue($utilitaire->afficherChaineBD($menuLangue->getNom()));
    	$form->get('descCourte')->setValue($utilitaire->afficherChaineBD($menuLangue->getDescCourte()));
    	
    	$form->get('type')->setValue($menu->getType());
    	$form->get('langue')->setValue($menuLangue->getLangue()->getId());
    	if($menu->getPere())
    		$form->get('pere')->setValue($menu->getPere()->getId());
    	
    	if($request->isPost())
    	{
    		$menuLangue = $this->getEntityManager()->getRepository('Entity\MenuLangue')->findOneBy(array('langue' => $request->getPost()->langue,
    				'menu' => $menu->getId(),
    		));
    		
    		$form->get('langue')->setValue($request->getPost()->langue);
    		$form->get('type')->setValue($request->getPost()->type);
    		if($request->getPost()->pere)
    			$form->get('pere')->setValue($request->getPost()->pere);
    		
    		
    		if(!$menuLangue)
    		{
    			$menuLangue = $this->appliContainer->get('Entity\MenuLangue');
    			$menuLangue->setMenu($menu);
    		}
    			
    		$postData = $request->getPost();
    		$formPosted = true;
    		$menuInputFilter = $this->getMenuInputFilter();
    		$menuInputFilter->valeurPere = $request->getPost()->pere;
    		
    		$form->setInputFilter($menuInputFilter->getInputFilter());
    		$form->setData($postData);
    		
    		if ($form->isValid())
    		{
    			$donneesFormulaire = $form->getData();
    			
    			$menu->exchangeArray($donneesFormulaire);
    			$menuLangue->exchangeArray($donneesFormulaire);
    				
    			if(!empty($request->getPost()->pere))
    				$menu->setPere($this->getEntityManager()->find('Entity\Menu', $request->getPost()->pere));
    			else
    				$menu->setPere(null);
    			
    			$menuLangue->setLangue($this->getEntityManager()->find('Entity\Langue', $request->getPost()->langue));
    			$menuLangue->setMenu($menu);
    			
    			// Debut du mode transactionnel
    			$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    			try {
    					
    				if(!$menuLangue->getId())
    					$this->getEntityManager()->persist($menuLangue);
    					
    					
    				$menu->nettoyageChaine();
    				$menuLangue->nettoyageChaine();
    				$this->getEntityManager()->flush();
    				
    				
    				
    				
    				
    				// Reconstruction des chemins vers l'ancetre
    				$tabAll = $this->getEntityManager()->getRepository('Entity\Menu')->findBy(array());
    				foreach ($tabAll as $unElt)
    				{
    					$unElt->setCheminPere($this->construireCheminPere($unElt, "Position"));
    				}
    				$this->getEntityManager()->flush();
    				
    				
    				
    				
    				$this->getEntityManager()->getConnection()->commit();
    					
    				// Redirection dans la la liste des menus
    				return $this->redirect()->toRoute("menu");
    			} catch (\Exception $e) {
    				$this->getEntityManager()->getConnection()->rollback();
    				$this->getEntityManager()->close();
    				throw $e;
    			}
    		}
    	}
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/menu/modifier/".$id);
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	
    	
    	$this->nomPage = $this->getTranslator("Modifier le menu")." « ".$menuLangue->getNom()." »";
    	
    	$this->initBackViewSimpleForm('Menu', $form, $formPosted, $listEltsUrl);
    	 
    	return new ViewModel(array(
    		'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    		'idMenuLangue' => $id,
    		'idMenu' => $menu->getId(),
    	));
    }
    
    public function paginationAction()
    {
    	$sessionAgence = new Container('agence');
    	$sessionEmploye = new Container('employe');
    	$menuManager = $this->getMenuManager();
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    
    	$numActuel = $this->params()->fromRoute('numActuel', null);
    	if(!$numActuel) $numActuel = 1;
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	
    	if(is_array($postValues) && isset($postValues['statut']) && isset($postValues['type']))
    	{
    		$supprime = -1;
    		if($postValues['statut'] == 2)
    		{
    			$supprime = 1;
    			$postValues['statut'] = null;
    		}
    		
//     		$retourPagination = $menuManager->getListeMenuLangue($postValues['type'], $sessionEmploye->offsetGet("code_langue"), $postValues['statut'], $supprime, $numActuel,
//     				$menuManager::NBRE_LIGNE_TABLEAU, true); // $idLangue=null, $statut=null, $supprime=null, $nroPage=null, $nbreMax=null, $pagination=false
    		
    				
    		$retourPagination = $menuManager->getListeMenuLangue($postValues['statut'], $sessionEmploye->offsetGet("code_langue"), -1, null, 
										null, $postValues['type'], null, null, 
										$supprime, $numActuel, $menuManager::NBRE_LIGNE_TABLEAU, true, 
										array("cheminPere" => "ASC", "position" => "ASC", "nom" => "ASC")
    							);
    		
    		$tab = $retourPagination['tab'];
    		$totalResult = $retourPagination['totalResult'];

    		if(is_array($tab) && count($tab) > 0 && $totalResult > 0)
    		{
    			$nroPage = $numActuel;
    			$nbrePages = $menuManager::NBRE_PAGE_PAGINATION;
    			$nbreResults = $totalResult;
    			$nbreMaxResultsParPage = $menuManager::NBRE_LIGNE_TABLEAU;
    			$cheminControlleur = $basePath.'/';
    			$parametres = "";
    		
    			include_once __DIR__.'/../../../Application/view/partial/pagination.phtml';
    			 
    			 
    			if(isset($headPagination) && is_string($headPagination)) $varRetour .= $headPagination;
    			include_once __DIR__.'/../../view/menu/index/pagination.phtml';
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
    
    public function chargerMenuAjaxAction()
    {
    	$sessionAgence = new Container('agence');
    	$sessionEmploye = new Container('employe');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$menuManager = $this->getMenuManager();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	
    	if(is_array($postValues) && isset($postValues['type']))
    	{
    		$idLangueHere = $sessionEmploye->offsetGet("id_langue");
    		if(isset($postValues['langue']) && is_numeric($postValues['langue']))
    			$idLangueHere = $postValues['langue'];
    		
    		
    		$tab = $menuManager->getListeMenuLangue(null, null, -1, null,
    				null, $postValues['type'], null, null,
    				null, 1, null, false,
    				array("cheminPere" => "ASC", "position" => "ASC", "nom" => "ASC"),
    				$idLangueHere
    		);
    		
    		$varRetour .= '<option value="">'.$this->getTranslator("Selectionnez le menu parent").'</option>';
    		
    		foreach($tab as $element)
    		{
    			$element->afficheChaine();
    			$varRetour .= '<option value="'.$element->getMenu()->getId().'">';
    			$varRetour .= 		$this->construireComplementNomAncetre($element->getMenu())." ".$element->getNom();
    			$varRetour .= '</option>';
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
    
    public function chargerUnMenuAjaxAction()
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
    		$error = $this->getTranslator("Aucun menu trouve");
    	}
    	$menuLangue = $this->getEntityManager()->find('Entity\MenuLangue', $id);
    	if (!$menuLangue) {
    		$error = $this->getTranslator("Aucun menu trouve");
    	}
    	$menu = $menuLangue->getMenu();
    	 
    	 
    	if($error == "" && is_array($postValues) && isset($postValues['langue']))
    	{
    		$newMenuLangue = $this->getEntityManager()->getRepository('Entity\MenuLangue')->findOneBy(array('langue' => $postValues['langue'],
    				'menu' => $menu->getId(),
    		));
    
    		if($newMenuLangue)
    		{
    			$newMenuLangue->afficheChaine();
    			$varRetour = $newMenuLangue->getArrayCopy();
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
