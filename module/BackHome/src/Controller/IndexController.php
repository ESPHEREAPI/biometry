<?php

namespace BackHome\Controller;

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
	}
	
	public function getRegionManager ()
	{
		$sm = $this->appliContainer;
		return  $sm->get('Application\Manager\RegionManager');
	}
    
    public function indexAction ()
    {
        $this->initialiserPermission();
        
    	$this->nomPage = $this->getTranslator("Dashboard");
    	
    	
    	$this->initDashboardLayoutView();
    	
        return new ViewModel(array(
        ));
    }
    
    public function parametreAction ()
    {
        $this->initialiserPermission();

    	$this->nomPage = $this->getTranslator("Parametres");
    	
    	$this->initBackView();
    	
        return new ViewModel(array(
        		
        ));
    }
    
    public function activerCommunAction ()
    {
        $this->initialiserPermission();
        
    	$commonManager = $this->getCommonManager();
    	$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();
    	
    	$statut = $this->params()->fromRoute('statut', null);
    	if ($statut != -1 && $statut != 1) {
    		return $this->redirect()->toUrl($redirectUrl);
    	}
    	
    	$nomEntite = $this->params()->fromRoute('nomEntite', null);
    	if (!$nomEntite) {
    		return $this->redirect()->toUrl($redirectUrl);
    	}
    	
    	$idElts = $this->params()->fromRoute('idElts', null);
    	if (!$idElts) {
    		return $this->redirect()->toUrl($redirectUrl);
    	}
    	
    	$tabIdElt = explode(";", $idElts);
    	if(is_array($tabIdElt))
    	{
    		$tabElt = array();
    		foreach ($tabIdElt as $unIdElt)
    		{
    			$tabElt[$unIdElt] = $commonManager->em->find($this->nameSpaceModele.'\\'.$nomEntite, $unIdElt);
    			$tabElt[$unIdElt]->setStatut($statut);
    		}
    		$commonManager->em->flush();
    	}
    	
    	return $this->redirect()->toUrl($redirectUrl);
    }
    
    public function supprimerCommunAction ()
    {
        $this->initialiserPermission();
        
    	$request = $this->getRequest();
    	
    	$commonManager = $this->getCommonManager();
    	
    	
    	if($request->isPost())
    	{
    		$redirectUrl = $request->getPost()->redirectUrl;
    	}
    	else
    	{
    		$redirectUrl = $this->getRequest()->getHeader('Referer')->getUri();
    	}
    	 
    	$statut = $this->params()->fromRoute('statut', null);

    	if ($statut != -1 && $statut != 1) {
    		return $this->redirect()->toUrl($redirectUrl);
    	}
    	 
    	$nomEntite = $this->params()->fromRoute('nomEntite', null);
    	if (!$nomEntite) {
    		return $this->redirect()->toUrl($redirectUrl);
    	}
    	 
    	$idElts = $this->params()->fromRoute('idElts', null);
    	if (!$idElts) {
    		return $this->redirect()->toUrl($redirectUrl);
    	}
    	
    	if ($request->isPost()) 
    	{
    		$del = $request->getPost('del', 'No');
    	
    		if ($del == $this->getTranslator("Yes")) 
    		{
    			$tabIdElt = explode(";", $idElts);
    			if(is_array($tabIdElt))
    			{
    				$tabElt = array();
    				foreach ($tabIdElt as $unIdElt)
    				{
    					$tabElt[$unIdElt] = $commonManager->em->find($this->nameSpaceModele.'\\'.$nomEntite, $unIdElt);
    					$tabElt[$unIdElt]->setSupprime($statut);
    				}
    				$commonManager->em->flush();
    			}
    		}
    	
    		// Redirection
    		return $this->redirect()->toUrl($redirectUrl);
    	}
    	else
    	{
    		$this->nomPage = $this->getTranslator("Confirmez la suppression");
    		 
    		$this->initBackView();
    	}
    	
    	return array(
    		'idElts'  => $idElts,
    		'statut' => $statut,
    		'nomEntite' => $nomEntite,
    		'redirectUrl' => $redirectUrl,
    	);
    }
    
    public function supprimerFichierAction ()
    {
        $this->initialiserPermission();

    	$error = "";
    	$info = "";
    	$varRetour = "";
    	
    	$numActuel = $this->params()->fromRoute('numActuel', null);
    	if(!$numActuel) $numActuel = 1;
    	
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	 
    	 
    	if(is_array($postValues) && isset($postValues['cheminFichier']))
    	{
    		if(unlink(urldecode($postValues['cheminFichier'])))
    		{
    			$varRetour = $this->getTranslator("Fichier supprime avec succces");
    		}
    		else
    		{
    			$error = $this->getTranslator("Probleme lors de la suppression du fichier");
    		}
    	}
    	else
    	{
    		$error = $this->getTranslator("Le nom du fichier n'a pas ete transmis");
    	}
    	 
    	return new JsonModel(array(
    			'error' => $error,
    			'info' => $info,
    			'varRetour' => $varRetour,
    			'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    	));
    }
    
    public function chargerRegionsPaysAction()
    {
        $this->initialiserPermission();

    	$sessionEmploye = new Container('employe');
    	$regionManager = $this->getRegionManager();
    	$error = "";
    	$info = "";
    	$varRetour = "";
    	$nomRegion = "";
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	
    	
    	$pays = $this->params()->fromRoute('pays', null);
    	if (!$pays) {
    		$error = $this->getTranslator("Veuillez selectionner le pays");
    	}
    	
    	$region = $this->params()->fromRoute('region', null);
    
    	if(is_array($postValues) && empty($error))
    	{
    		$tab = $regionManager->getListeRegionLangue($pays, 1, -1, $sessionEmploye->offsetGet("id_langue")); // $codeLangue="fr_FR", $statut=null, $supprime=null, $nroPage=1, $nbreMax=null, $pagination=false
    
    		$varRetour .= '<option value="">'.$this->getTranslator("Selectionnez la region").'</option>';
    		if(is_array($tab))
    		{
    			foreach ($tab as $element)
    			{
    				$element->afficheChaine();
    				$selected = "";
    				if($element->getRegion()->getId() == $region)
    				{
    					$selected = "selected";
    					$nomRegion = $element->getNom();
    				}
    				
    				$varRetour .= '<option value="'.$element->getRegion()->getId().'" '.$selected.'>'.$element->getNom().'</option>';
    			}
    		}
    	}
    	else
    	{
    		if(empty($error))
    			$error = $this->getTranslator("Toutes les valeurs du filtre n'ont pas ete transmises");
    	}
    
    	return new JsonModel(array(
    			'error' => $error,
    			'info' => $info,
    			'varRetour' => $varRetour,
    			'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    			'nomRegion' => $nomRegion,
    	));
    }    
    
    public function reorganiserListeAction()
    {
        $this->initialiserPermission();

    	$error = "";
    	$info = "";
    	$varRetour = "";
    	
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	 
    	 
    	if(is_array($postValues) && isset($postValues['table']) && isset($postValues['listIds']))
    	{
    		if(!empty($postValues['listIds']))
    		{
    			$tabIds = explode(",", $postValues['listIds']);
    			if(is_array($tabIds))
    			{
    				$tabElt = array();
    				$compteur = 0;
    				
    				// Debut du mode transactionnel
    				$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    				try {
	    				foreach ($tabIds as $unId)
	    				{
	    					$compteur++;
	    					if(!empty($unId) && is_numeric($unId))
	    					{
	    						$tabElt[$unId] = $this->getEntityManager()->find('Entity\\'.$postValues['table'], $unId);
	    							
	    						if($tabElt[$unId])
	    						{
	    							if($postValues['table'] == "Formulaire")
	    								$tabElt[$unId]->setNumEtape($compteur);
	    							elseif($postValues['table'] == "Question")
	    								$tabElt[$unId]->setPosition($compteur);
	    							elseif($postValues['table'] == "QuestionPossibleReponse")
	    								$tabElt[$unId]->setPosition($compteur);;
	    						}	
	    					}
	    				}
	    				
	    				$this->getEntityManager()->flush();
    					$this->getEntityManager()->getConnection()->commit();
    						
    				} catch (\Exception $e) {
    					$this->getEntityManager()->getConnection()->rollback();
    					$this->getEntityManager()->close();
    					
    					$error = $e->getMessage();
    				}
    			}
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
    
    public function deplacerElementAction()
    {
        $this->initialiserPermission();
        
    	$error = "";
    	$info = "";
    	$varRetour = "";
    	$utilitaire = new Utilitaire();
    	 
    
    	$postValues = array_merge_recursive(
    			$this->getRequest()->getPost()->toArray(),
    			$this->getRequest()->getFiles()->toArray()
    			);
    	
    	if(is_array($postValues) && isset($postValues['nomClasseTable']) && isset($postValues['nomCamelCaseColonnePosition']) &&
    	   isset($postValues['idElt']) && isset($postValues['nouvellePosition']))
    	{	
			$nouvellePosition = $postValues['nouvellePosition'] + 1;

    		$element = $this->getEntityManager()->find('Entity\\'.$postValues['nomClasseTable'], $postValues['idElt']);
    		if(!$element)
    		{
    			$error = $this->getTranslator("Element introuvable");
    		}
    		else
    		{
    		    if($postValues['nomClasseTable'] != "Langue" && $utilitaire->endsWith($postValues['nomClasseTable'], "Langue"))
	    		{
	    			$longueurNomTable = strlen($postValues['nomClasseTable']);
	    			$nomClasseTable = substr($postValues['nomClasseTable'], 0, $longueurNomTable-6);
	    			$methodeGetTable = "get".$nomClasseTable;
	    			$element = $element->$methodeGetTable();
	    		}
	    		else
	    		{
	    			$nomClasseTable = $postValues['nomClasseTable'];
	    		}
	    		
	    		if($element->getStatut() == "-1" || $element->getSupprime() == "1")
	    		{
	    			$error = $this->getTranslator("Element desactive ou supprime");
	    		}
	    		else
	    		{
		    		$methodeGetPosition = "get".ucfirst($postValues['nomCamelCaseColonnePosition']);
		    		$methodeSetPosition = "set".ucfirst($postValues['nomCamelCaseColonnePosition']);
		    		$anciennePosition = $element->$methodeGetPosition();
		    		
		    		if($anciennePosition != $nouvellePosition)
		    		{
		    			$criteresFindBy = array();
		    			if(isset($postValues['otherCulumnsUniqWithPosition']))
		    			{
		    				$tabOtherCulumnsUniqWithPosition = json_decode($postValues['otherCulumnsUniqWithPosition'], true);
		    				if(is_array($tabOtherCulumnsUniqWithPosition))
		    				{
		    					$criteresFindBy = $tabOtherCulumnsUniqWithPosition;
		    				}	
		    			}

		    			// Debut du mode transactionnel
	    				$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
	    				try {
	    					
	    					$lastElt = $this->getEntityManager()->getRepository('Entity\\'.$nomClasseTable)->findBy($criteresFindBy, array($postValues['nomCamelCaseColonnePosition'] => "DESC"), "1");
	    					if($lastElt)
	    					{
	    						$queryBuilder = $this->getEntityManager()->createQueryBuilder();
	    						
	    						$supplementWhereClause = "";
	    						foreach ($criteresFindBy as $key => $valeur)
	    						{
	    							$supplementWhereClause .= " AND elt.".$key." = '".$valeur."'";
	    						}
	    						
	    						$lastElt = $lastElt[0];
			    				$lastEltPosition = $lastElt->$methodeGetPosition();
			    				
			    				
			    				if($nouvellePosition > $lastEltPosition)
			    				{
			    					$element->$methodeSetPosition($lastEltPosition+1);
			    				}
			    				elseif($nouvellePosition == $lastEltPosition)
			    				{
			    					$query = $queryBuilder->update('Entity\\'.$nomClasseTable, 'elt')
			    					->set('elt.'.$postValues['nomCamelCaseColonnePosition'], 'elt.'.$postValues['nomCamelCaseColonnePosition'].'-1')
			    					->where('elt.'.$postValues['nomCamelCaseColonnePosition'].' > ?1 AND elt.'.$postValues['nomCamelCaseColonnePosition'].' <= ?2 '.$supplementWhereClause)
			    					->setParameter(1, $anciennePosition)
			    					->setParameter(2, $nouvellePosition)
			    					->getQuery();
			    					
			    					$query->execute();
			    					
			    					$element->$methodeSetPosition($nouvellePosition);
			    				}
			    				elseif($nouvellePosition > $anciennePosition)
			    				{
			    					// UPDATE table, set position = position - 1 WHERE position > $anciennePosition AND position <= nouvellePosition
			    					
			    					$query = $queryBuilder->update('Entity\\'.$nomClasseTable, 'elt')
			    					->set('elt.'.$postValues['nomCamelCaseColonnePosition'], 'elt.'.$postValues['nomCamelCaseColonnePosition'].'-1')
			    					->where('elt.'.$postValues['nomCamelCaseColonnePosition'].' > ?1 AND elt.'.$postValues['nomCamelCaseColonnePosition'].' <= ?2 '.$supplementWhereClause)
			    					->setParameter(1, $anciennePosition)
			    					->setParameter(2, $nouvellePosition)
			    					->getQuery();
			    					
			    					$query->execute();
			    					
			    					$element->$methodeSetPosition($nouvellePosition);
			    				}
			    				else // nouvellePosition <= anbciennePosition
			    				{
			    					// UPDATE table, set position = position + 1 WHERE position < $anciennePosition AND position >= nouvellePosition
			    					
			    					$query = $queryBuilder->update('Entity\\'.$nomClasseTable, 'elt')
			    					->set('elt.'.$postValues['nomCamelCaseColonnePosition'], 'elt.'.$postValues['nomCamelCaseColonnePosition'].'+1')
			    					->where('elt.'.$postValues['nomCamelCaseColonnePosition'].' < ?1 AND elt.'.$postValues['nomCamelCaseColonnePosition'].' >= ?2 '.$supplementWhereClause)
			    					->setParameter(1, $anciennePosition)
			    					->setParameter(2, $nouvellePosition)
			    					->getQuery();
			    					 
			    					$query->execute();
			    					 
			    					$element->$methodeSetPosition($nouvellePosition);
			    				}
		    					 
		    					$this->getEntityManager()->flush();
		    					$this->getEntityManager()->getConnection()->commit();
	    					}
	    					else
	    					{
	    						$this->getEntityManager()->getConnection()->rollback();
	    						$this->getEntityManager()->close();
	    					}
	    
	    				} catch (\Exception $e) {
	    					$this->getEntityManager()->getConnection()->rollback();
	    					$this->getEntityManager()->close();
	    						
	    					$error = $e->getMessage();
	    				}
		    		}
	    		}	
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
	
	public function activerCommunAjaxAction ()
    {
        $this->initialiserPermission();
        
    	$commonManager = $this->getCommonManager();
    	$error = "";
		
		$postValues = array_merge_recursive(
    			$this->getRequest()->getPost()->toArray(),
    			$this->getRequest()->getFiles()->toArray()
    			);
		
    	if(!isset($postValues['statut']) || ($postValues['statut'] != -1 && $postValues['statut'] != 1)) {
    		$error = $this->getTranslator("Statut non transmis ou incorrect");
    	}
    	elseif(!isset($postValues['nomCamelCaseTable']) || empty($postValues['nomCamelCaseTable'])) {
    		$error = $this->getTranslator("Nom entite non transmis");
    	}
    	elseif(!isset($postValues['idElts']) || empty($postValues['idElts'])) {
    		$error = $this->getTranslator("Elements non transmis");
    	}
    	
		if(empty($error))
		{
			$tabIdElt = explode(";", $postValues['idElts']);
			if(is_array($tabIdElt))
			{
				$tabElt = array();
				foreach ($tabIdElt as $unIdElt)
				{
					$tabElt[$unIdElt] = $commonManager->em->find($this->nameSpaceModele.'\\'.$postValues['nomCamelCaseTable'], $unIdElt);
					$tabElt[$unIdElt]->setStatut($postValues['statut']);
				}
				$commonManager->em->flush();
			}
		}
    	
    	return new JsonModel(array(
    		'error' => $error,
    	));
    }
	
	
	public function supprimerCommunAjaxAction ()
    {
        $this->initialiserPermission();
        
    	$commonManager = $this->getCommonManager();
    	$error = "";
		$info = "";
		$varRetour = "";
		
		$postValues = array_merge_recursive(
    			$this->getRequest()->getPost()->toArray(),
    			$this->getRequest()->getFiles()->toArray()
    			);
				
		
    	if(!isset($postValues['supprime']) || ($postValues['supprime'] != "-1" && $postValues['supprime'] != "1")) {
    		$error = $this->getTranslator("Statut de suppression non transmis ou incorrect");
    	}
    	elseif(!isset($postValues['nomCamelCaseTable']) || empty($postValues['nomCamelCaseTable'])) {
    		$error = $this->getTranslator("Nom entite non transmis");
    	}
    	elseif(!isset($postValues['idElts']) || empty($postValues['idElts'])) {
    		$error = $this->getTranslator("Elements non transmis");
    	}
    	
		if(empty($error))
		{
			$tabIdElt = explode(";", $postValues['idElts']);
			if(is_array($tabIdElt))
			{
				// Debut du mode transactionnel
				$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
				try {
					
					$tabElt = array();
					foreach ($tabIdElt as $unIdElt)
					{
						$tabElt[$unIdElt] = $commonManager->em->find($this->nameSpaceModele.'\\'.$postValues['nomCamelCaseTable'], $unIdElt);
						$tabElt[$unIdElt]->setSupprime($postValues['supprime']);
						
						
						// var_dump($postValues['supprime']); exit;
						
						if(isset($postValues['nomCamelCaseColonnePosition']) && !empty($postValues['nomCamelCaseColonnePosition']) &&
						   isset($postValues['otherCulumnsUniqWithPosition']) && !empty($postValues['otherCulumnsUniqWithPosition'])) // Pour mettre à jour les positions des autres éléments
						{
						    // var_dump("ioioioio"); exit;
						    
						    
							$methodeSetPosition = "set".ucfirst($postValues['nomCamelCaseColonnePosition']);
							$methodeGetPosition = "get".ucfirst($postValues['nomCamelCaseColonnePosition']);
							
							$criteresFindBy = array();
							if(isset($postValues['otherCulumnsUniqWithPosition']))
							{
								$tabOtherCulumnsUniqWithPosition = json_decode($postValues['otherCulumnsUniqWithPosition'], true);
								if(is_array($tabOtherCulumnsUniqWithPosition))
								{
									$criteresFindBy = $tabOtherCulumnsUniqWithPosition;
								}								
							}
							
							
							if($postValues['supprime'] == "1") // Diminuer de 1 la position de tous les éléments après celui supprimé
							{
								$queryBuilder = $this->getEntityManager()->createQueryBuilder();
								
								$supplementWhereClause = "";
								foreach ($criteresFindBy as $key => $valeur)
								{
									$supplementWhereClause .= " AND elt.".$key." = '".$valeur."'";
								}
								
								$query = $queryBuilder->update('Entity\\'.$postValues['nomCamelCaseTable'], 'elt')
													  ->set('elt.'.$postValues['nomCamelCaseColonnePosition'], 'elt.'.$postValues['nomCamelCaseColonnePosition'].'-1')
													  ->where('elt.'.$postValues['nomCamelCaseColonnePosition'].' > ?1 '.$supplementWhereClause)
													  ->setParameter(1, $tabElt[$unIdElt]->$methodeGetPosition())
													  ->getQuery();
										 
								$query->execute();

								$tabElt[$unIdElt]->$methodeSetPosition(null);
							}
							else // Mettre l'élément déssuprimé à la position finale
							{
								$lastElt = $this->getEntityManager()->getRepository('Entity\\'.$postValues['nomCamelCaseTable'])->findBy($criteresFindBy, array($postValues['nomCamelCaseColonnePosition'] => "DESC"), "1");
								if($lastElt)
								{
									$lastElt = $lastElt[0];
									$tabElt[$unIdElt]->$methodeSetPosition($lastElt->$methodeGetPosition()+1);
								}
								else
								{
									$tabElt[$unIdElt]->$methodeSetPosition(1);
								}
							}
						}
						
						$this->getEntityManager()->flush();
					}
					// echo "eeeeeee"; exit;
					
					// $this->getEntityManager()->flush();
					$this->getEntityManager()->getConnection()->commit();
					
					
					$varRetour = $this->getTranslator("Operation  effectuee avec succces");
						
				} catch (\Exception $e) {
					$this->getEntityManager()->getConnection()->rollback();
					$this->getEntityManager()->close();
					
					$error = $e->getMessage();
				}
			}
		}
    	
    	return new JsonModel(array(
    		'error' 	=> $error,
			'info' 		=> $info,
			'varRetour' => $varRetour,
    	));
    }
    
    public function accesRefuseAction ()
    {
        $this->initialiserPermission();

        $sessionRedirectionPage = new Container("redirection_page");
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        
        $nomPage = $sessionRedirectionPage->offsetGet("nom_page");
        $descriptionPage = $sessionRedirectionPage->offsetGet("description_page");
        $urlRedirection = $sessionRedirectionPage->offsetGet("url_redirection");
        
        if(empty($nomPage))
        {
            $nomPage = $this->getTranslator("Acces refuse");
        }
        
        if(empty($descriptionPage))
        {
            $descriptionPage = $this->getTranslator("Vous n'avez pas acces a cette fonctionnalite");
        }
        
        if(empty($urlRedirection))
        {
            $urlRedirection = $basePath."/".$this->appliConfig->get('lienBackoffice');
        }
        
        
        $sessionRedirectionPage->offsetUnset("nom_page");
        $sessionRedirectionPage->offsetUnset("description_page");
        $sessionRedirectionPage->offsetUnset("url_redirection");
        
        
        $this->initEmptyLayoutView($nomPage);
        
        return new ViewModel(array(
            'nomPage' => $nomPage,
            'descriptionPage' => $descriptionPage,
            'urlRedirection' => $urlRedirection,
        ));
    }
    
    public function downloadAction()
    {
        $this->initialiserPermission();
        
        $nomCompletFichier = $this->params()->fromRoute('nomCompletFichier', null);
        if (!$nomCompletFichier)
        {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $nomCompletFichier = str_replace("_*-*_", "/", $nomCompletFichier);
        
        $file = __DIR__.'/../../../../public/'.$nomCompletFichier;
        
        // var_dump($file); exit;
        
        if(!file_exists($file))
        {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        
        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($file, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($file));
        $headers = new \Zend\Http\Headers();
        $headers->addHeaders(array(
            'Content-Disposition' => 'attachment; filename="' . basename($file) .'"',
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => filesize($file),
            'Expires' => '@0', // @0, because zf2 parses date as string to \DateTime() object
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public'
        ));
        $response->setHeaders($headers);
        return $response;
    }
    
    public function sauvegardeBdAction()
    {
        set_time_limit(300000);
        
        $commonManager = $this->getCommonManager();
        $utilitaire = new Utilitaire();
        
        $fileName = $commonManager->sauvegardeBD();
        sleep(10); // Pour attendre pendant que la base de donnees est sauvegardee
        if(file_exists($fileName))
        {
            $tabRecepteurs = array("gerard.tibui@zenitheinsurance.com" => 'TIBUI Gerard', 'mbele.alexis@zenitheinsurance.com' => 'MBELE Alexis');
            $sujet = "Sauvegarde base de donnees de la plateforme biometrique";
            $message = "Ci-joint la sauvegarde de la base de donnees de la plateforme biometrique";
            $tabPiecesJointes = array(basename($fileName, ".sql") => $fileName);
            
            
            // Envoi du mail au client
            $utilitaire->sendMailSMTP($tabRecepteurs, $sujet,
                $message, $tabPiecesJointes);
        }
    }
}
