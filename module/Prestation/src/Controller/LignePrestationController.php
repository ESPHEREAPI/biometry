<?php

namespace Prestation\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Custom\Mvc\Controller\BackOfficeCommonController;

use Application\Core\Utilitaire;
use Application\Filter\Common\DigitImputFilterLcl;
use Application\Manager\EmployeManager;
use Application\Manager\MenuManager;
use Entity\LignePrestationAudit;
use Entity\Prestation;
use Entity\LignePrestation;
use Entity\Medicament;
use Prestation\Form\FiltreListeLignePrestationForm;
use Prestation\Form\LignePrestationForm;
use Application\Manager\LignePrestationManager;
use Prestation\Form\LignePrestationInputFilter;
use Application\Filter\Common\TextImputFilterLcl;
use Application\Filter\Common\EnumImputFilterLcl;

class LignePrestationController extends BackOfficeCommonController
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $appliContainer;
    
    /**
     * @var \Entity\LignePrestation
     */
    protected $lignePrestation;
    
    /**
     * @var \Application\Manager\LignePrestationManager
     */
    protected $lignePrestationManager;
	
	/**
     * @var \Entity\Medicament
     */
    protected $medicament;
    
    /**
     * @var \Application\Manager\MenuManager
     */
    protected $menuManager;
    
    /**
     * @var \Application\Manager\EmployeManager
     */
    protected $employeManager;
    
    /**
     * @var \Prestation\Form\FiltreListeLignePrestationForm
     */
    protected $filtreListeLignePrestationForm;
    
    /**
     * @var \Prestation\Form\LignePrestationForm
     */
    protected $lignePrestationForm;
    
    /**
     * @var \Prestation\Form\LignePrestationInputFilter
     */
    protected $lignePrestationInputFilter;
    
    protected $naturePrestation;
    
    protected $appliConfig;
    
    public function __construct(ContainerInterface $appliContainer, LignePrestation $lignePrestation,Medicament $medicament, LignePrestationManager $lignePrestationManager,
                                MenuManager $menuManager, EmployeManager $employeManager, FiltreListeLignePrestationForm $filtreListeLignePrestationForm,
                                LignePrestationForm $lignePrestationForm, LignePrestationInputFilter $lignePrestationInputFilter)
    {
        $appliConfig =  new \Application\Core\AppliConfig();
        $this->appliConfig = $appliConfig;
        
        $this->appliContainer = $appliContainer;

        $this->lignePrestation = $lignePrestation;
		 $this->medicament=$medicament;
        $this->lignePrestationManager = $lignePrestationManager;
        $this->menuManager = $menuManager;
        $this->employeManager = $employeManager;
        $this->filtreListeLignePrestationForm = $filtreListeLignePrestationForm;
        $this->lignePrestationForm = $lignePrestationForm;
        $this->lignePrestationInputFilter = $lignePrestationInputFilter;
        
        $this->initialiserPermission();
    }
    
    public function initialiserControlleur()
    {
        $this->naturePrestation = $this->params()->fromRoute('naturePrestation', null);
        if($this->naturePrestation == "ordonnance")
        {
            $this->numeroOrdre = 1;
        }
        elseif($this->naturePrestation == "examen")
        {
            $this->numeroOrdre = 2;
        }
		elseif($this->naturePrestation=="lunetterie")
		{
			 $this->numeroOrdre=3;
		}
        
        // $this->peuxAjouter = $this->prestationManager->verifierSiPeuxAjouterPrestation($this->tabListeMenu, $this->numeroOrdre);
        
        
        
        $appliConfig =  new \Application\Core\AppliConfig();
        $basePath = $appliConfig->get("basePath");
        $varRetour = "";
        
        $id = $this->params()->fromRoute('id', null);
        if($id)
        {
            $prestation = $this->getEntityManager()->find('Entity\Prestation', $id);
            if($prestation && $prestation->getNaturePrestation() != $this->naturePrestation)
            {
                if(!$this->testerSiRequetteAjax())
                {
                    header("Location: ".$basePath."/".$appliConfig->get("lienBackoffice")."/acces-refuse"); // On redirrige l'utilisateur à la page de connexion
                    exit;
                }
                else
                {
                    $varRetour = $this->getTranslator("L'url ce cette action n'est pas correcte");
                }
            }
        }
        
        return $varRetour;
    }
    
    public function detailsAction()
    {
        $this->initialiserControlleur();
        
        $appliConfig =  new \Application\Core\AppliConfig();
        $sessionAgence = new Container('agence');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation;
        $sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        $utilitaire = new Utilitaire();
        $lignePrestationManager = $this->lignePrestationManager;
        $msgSuccess = "";
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            return $this->redirect()->toUrl($listEltsUrl);
        }
        $prestation = $this->getEntityManager()->find('Entity\Prestation', $id);
        if(!$prestation)
        {
            return $this->redirect()->toUrl($listEltsUrl);
        }
        $consultation = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $prestation->getVisite()->getId()));
        if(!$consultation)
        {
            return $this->redirect()->toUrl($listEltsUrl);
        }
        
        $visite = $consultation->getVisite();
        
        $cheminPagination = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation."/details/".$id."/pagination";
        
        
        if($this->naturePrestation == "ordonnance")
        {
            $this->nomPage = $this->getTranslator("Liste des medicaments");
        }
        elseif($this->naturePrestation == "examen")
        {
			if($typeSousProfil=="laboratoire")
			{
            $this->nomPage = $this->getTranslator("Liste des examens");
			}
			else
			{
				$this->nomPage = $this->getTranslator("Liste des examens/actes");
			}
        }
		elseif($this->naturePrestation == "lunetterie")
        {
				$this->nomPage = $this->getTranslator("Liste des prestations en lunetterie");
        }
        
        
        $boutons = array('btn_ajouter' => false,
                         'btn_activer' => false,
                         'btn_desactiver' => false,
                         'btn_supprimer' => false,
                         'autres_boutons' => array(
                            'btn_imprimer_recu' => array(
                                'nom' => $this->getTranslator("Imprimer bon"),
                                'icone' => 'fa-print',
                                'attributes' => array(
                                    'id' => 'imprimerRecuPrestation_'.$id,
                                    'class' => 'imprimerRecuPrestation',
                                    'rel' => $id,
                                    'url' => '##',
                                )
                            ),
                         )
                        );
        
        $formFiltre = $this->filtreListeLignePrestationForm;  
        
        $typeProfil = $sessionEmploye->offsetGet("type_profil");
        
        
        
        $this->initBackViewList($boutons, 'LignePrestation', $formFiltre, $cheminPagination);
        
        
        return new ViewModel(array(
            'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
            'typeProfil' => $typeProfil,
            'naturePrestation' => $this->naturePrestation,
            'prestation' => $prestation,
            'codeProfil' => $sessionEmploye->offsetGet('code_profil'),
            'infosMalade' => $this->construireInfosMalade($visite),
        ));
        
    }
    
    public function paginationAction()
    {
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        $lignePrestationManager = $this->lignePrestationManager;
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $codeProfil = $sessionEmploye->offsetGet("code_profil");
        $error = "";
        $info = "";
        $varRetour = "";
        
        $numActuel = $this->params()->fromRoute('numActuel', null);
        if(!$numActuel) $numActuel = 1;
        
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner l'identifiant de la prestation sur la route");
        }
        
        if(empty($error))
        {
            $prestation = $this->getEntityManager()->find('Entity\Prestation', $id);
            if(!$prestation)
            {
                $error = $this->getTranslator("Impossible de trouver la prestation");
            }
        }
        
        if(empty($error))
        {
            $consultation = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $prestation->getVisite()->getId()));
            if(!$consultation)
            {
                $error = $this->getTranslator("Impossible de trouver la consultation");
            }
        }
        
        
        $typeProfil = $sessionEmploye->offsetGet("type_profil");
        
        if(empty($error))
        {
            $visite = $consultation->getVisite();
            
            $postValues = $this->getRequest()->getPost();
            $postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
            $postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
            
            
            $supprime = -1;
            if($codeProfil == "SUP_ADMIN") // Le super administrateur voit tout
            {
                $supprime = null;
            }
            
            
            $tabParams = array("naturePrestation" => $this->naturePrestation,
                               "prestation" => $prestation->getId(),
                               "supprime" => $supprime,
                               "orderBy" => array("prestataire" => "ASC", "date" => "DESC"),
                            );
            
            
            
            $peuxValiderLignePrestation = $this->lignePrestationManager->verifierSiPeuxValiderRejeterLignePrestation($this->tabListeMenu, $this->numeroOrdre);
            $peuxRejeterLignePrestation = $this->lignePrestationManager->verifierSiPeuxValiderRejeterLignePrestation($this->tabListeMenu, $this->numeroOrdre);
            $peuxMettrePrixLignePrestation = $this->lignePrestationManager->verifierSiPeuxMettrePrixLignePrestation($this->tabListeMenu, $this->numeroOrdre);
            $peuxEncaisserLignePrestation = $this->lignePrestationManager->verifierSiPeuxEncaisserLignePrestation($this->tabListeMenu, $this->numeroOrdre);
            
            
            $peuxDevaliderLignePrestation = $this->lignePrestationManager->verifierSiPeuxValiderRejeterLignePrestation($this->tabListeMenu, $this->numeroOrdre);
            $peuxDerejeterLignePrestation = $this->lignePrestationManager->verifierSiPeuxValiderRejeterLignePrestation($this->tabListeMenu, $this->numeroOrdre);
            
            
            $tab = $this->lignePrestationManager->getListeLignePrestationTabParams($tabParams);
            if(is_array($tab) && count($tab) > 0)
            {
                
//                 $tabLignePrestation = array();
//                 foreach ($tab as $unObjetLignePrestation)
//                 {
//                     $afficherValiderLignePrestationButton = $this->lignePrestationManager->vefifierSiLignePrestationValidable($unObjetLignePrestation) && $peuxValiderLignePrestation;
//                     $afficherRejeterLignePrestationButton = $this->lignePrestationManager->vefifierSiLignePrestationRejetable($unObjetLignePrestation) && $peuxRejeterLignePrestation;
//                     $afficherEncaisserLignePrestationButton = $this->lignePrestationManager->vefifierSiLignePrestationEncaissable($unObjetLignePrestation, $sessionEmploye->offsetGet("id_prestataire")) && $peuxEncaisserLignePrestation;
//                     $afficherMettrePrixLignePrestationButton = $this->lignePrestationManager->vefifierSiLignePrestationMettrePrix($unObjetLignePrestation) && $peuxMettrePrixLignePrestation;
                    
                    
//                     $unObjetLignePrestation->afficheChaine();
                    
//                     $tabLignePrestation[] = array("id" => $unObjetLignePrestation->getId(),
//                         "nom" => $unObjetLignePrestation->getNom(),
//                         "prestation" => $unObjetLignePrestation->getPrestation()->getId(),
//                         "valeur" => $unObjetLignePrestation->getValeur(),
//                         "nbre" => $unObjetLignePrestation->getNbre(),
//                         "afficherValiderLignePrestationButton" => $afficherValiderLignePrestationButton,
//                         "afficherRejeterLignePrestationButton" => $afficherRejeterLignePrestationButton,
//                         "afficherEncaisserLignePrestationButton" => $afficherEncaisserLignePrestationButton,
//                         "afficherMettrePrixLignePrestationButton" => $afficherMettrePrixLignePrestationButton,
//                     );
//                 }
                
//                 $jsonTabLignePrestation = json_encode($tabLignePrestation);
                
                
                
                
                if(isset($headPagination) && is_string($headPagination)) $varRetour .= $headPagination;
                include_once __DIR__.'/../../view/prestation/ligne-prestation/pagination.phtml';
                if(isset($varRetourControls) && is_string($varRetourControls)) $varRetour .= $varRetourControls;
            }
            else
            {
                $info = $this->getTranslator("Aucun element trouve");
            }
        }
        
        
        return new JsonModel(array(
            'error' => $error,
            'info' => $info,
            'varRetour' => $varRetour,
            'numActuel' => $numActuel,
            'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
            'naturePrestation' => $this->naturePrestation,
            'typeProfil' => $typeProfil,
            'infosMalade' => $this->construireInfosMalade($visite),
        ));
    }
    
    
   public function mettrePrixAction ()
   {
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $formPosted = false;
        $listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation;
        $utilitaire = new Utilitaire();
        $sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        $lignePrestationManager = $this->lignePrestationManager;
        $prestataireMettrePrix = null;
        
        $error = "";
        $info = "";
        $varRetour = "";
        $tabError = array();
        
        $form = $this->lignePrestationForm;
        
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner l'identifiant de la prestation sur la route");
        }
        
        if(empty($error))
        {
            $prestation = $this->getEntityManager()->find('Entity\Prestation', $id);
            if(!$prestation)
            {
                $error = $this->getTranslator("Impossible de trouver la prestation");
            }
        }
        
        if(empty($error))
        {
            $consultation = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $prestation->getVisite()->getId()));
            if(!$consultation)
            {
                $error = $this->getTranslator("Impossible de trouver la consultation");
            }
        }
        
        if(empty($error))
        {
            $prestataireMettrePrix = $this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire"));
            if(!$prestataireMettrePrix)
            {
                $error = $this->getTranslator("Impossible de trouver le prestataire connecte");
            }
        }
        
        
        
        if(empty($error))
        {
            $lignePrestationInputFilter = $this->lignePrestationInputFilter;
            
            $form->setInputFilter($lignePrestationInputFilter->getInputFilter());
            $form->setData($postValues);
            
            if(!empty($postValues["listeLignePrestationSent"]))
            {
                $tabLignePrestation = @explode(";", $postValues["listeLignePrestationSent"]);

                foreach ($tabLignePrestation as $key => $valueIndex)
                {
                    $form->add(array(
                        "name" => "valeurLignePretation_".$valueIndex,
                        "attributes" => array(
                            "type" => "number",
                        ),
                    ));
                    
                    if($this->naturePrestation == "examen" || $this->naturePrestation == "lunetterie")
                    {
                        $form->add(array(
                            "name" => "nbreLignePretation_".$valueIndex,
                            "attributes" => array(
                                "type" => "number",
                            ),
                        ));
                    }

                    
                    
                    $lignePrestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("valeurLignePretation_".$valueIndex, false, 1));
                    
                    if($this->naturePrestation == "examen" || $this->naturePrestation == "lunetterie")
                    {
                        $lignePrestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("nbreLignePretation_".$valueIndex, false, 1));
                    }
                }
            }
            
            if($form->isValid())
            {
                $donneesFormulaire = $form->getData();
                
                // Debut du mode transactionnel
                $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                try {
                    
                    if(!empty($postValues["listeLignePrestationSent"]))
                    {
                        $tabLignePrestation = @explode(";", $postValues["listeLignePrestationSent"]);
                        foreach ($tabLignePrestation as $key => $valueIndex)
                        {
                            $lignePrestation = $this->getEntityManager()->find('Entity\LignePrestation', $valueIndex);
                            if(!$lignePrestation)
                            {
                                $info = $this->getTranslator("Impossible de trouver la ligne prestation n°")." ".$valueIndex;
                            }
                            else
                            {
                                $employe = $this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id"));
                                
                                $lignePrestationAudit = new LignePrestationAudit();
                                $lignePrestationAudit->setLignePrestation($lignePrestation);
                                $lignePrestationAudit->setEmploye($employe);
                                $lignePrestationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                                
                                
                                if(!empty($donneesFormulaire["valeurLignePretation_".$valueIndex]))
                                {
                                    $afficherMettrePrixLignePrestationButton = $this->lignePrestationManager->vefifierSiLignePrestationMettrePrix($lignePrestation);
                                    if($afficherMettrePrixLignePrestationButton)
                                    {
                                        if($this->naturePrestation == "ordonnance")
                                        {
                                            $lignePrestation->setValeur($donneesFormulaire["valeurLignePretation_".$valueIndex]);
                                            $lignePrestation->setEtat("attente_validation");
                                            $lignePrestation->setPrestataire($prestataireMettrePrix);
                                            
                                            
                                            $lignePrestationAudit->setEtatLignePrestation("attente_validation");
                                            $this->getEntityManager()->persist($lignePrestationAudit);
                                        }
                                        elseif(($this->naturePrestation == "examen" ||$this->naturePrestation == "lunetterie") && !empty($donneesFormulaire["nbreLignePretation_".$valueIndex]))
                                        {
                                            $examen=$this->getEntityManager()->getRepository('Entity\Medicament')->findOneBy(array('nom' => $lignePrestation->getNom() , 
									                                                                                       'prestataire' => $prestataireMettrePrix,
																														   'statut'=> '1',
																														   'supprime'=>'-1'));
																														   
                                            $lignePrestation->setValeur($donneesFormulaire["valeurLignePretation_".$valueIndex]);
                                            $lignePrestation->setEtat("attente_validation");
                                            $lignePrestation->setNbre($donneesFormulaire["nbreLignePretation_".$valueIndex]);
                                            $lignePrestation->setPrestataire($prestataireMettrePrix);
                                            
                                            $lignePrestationAudit->setEtatLignePrestation("attente_validation");
                                            $this->getEntityManager()->persist($lignePrestationAudit);
											
									   if(!$examen)
										{
										  $examenNew = new Medicament();
										  $examenNew->setCode($valueIndex);
										  $examenNew->setNom($lignePrestation->getNom());
										   $examenNew->setOrigine($lignePrestation->getTypePrestation()->getId());
										  $examenNew->setPrix($donneesFormulaire["valeurLignePretation_".$valueIndex]);
										  $examenNew->setQuantite($donneesFormulaire["nbreLignePretation_".$valueIndex]);
										  $examenNew->setCategorie("2");
										  $examenNew->setPrestataire($prestataireMettrePrix);
										  $examenNew->setStatut("1");
										  $examenNew->setSupprime("-1");
										  $this->getEntityManager()->persist($examenNew);
										}
										elseif ($examen)
										{
										$examen->setPrix($donneesFormulaire["valeurLignePretation_".$valueIndex]);
										$examen->setQuantite($donneesFormulaire["nbreLignePretation_".$valueIndex]);
										}
			
										$this->getEntityManager()->flush();	
                                        }
                                    }
                                }
                                else
                                {
                                    $lignePrestation->setValeur(null);
                                    $lignePrestation->setEtat("enregistre");
                                    $lignePrestation->setPrestataire(null);
                                    
                                    $lignePrestation->setValeurModif(null);
                                    $lignePrestation->setNbreModif(null);
                                    
                                     if($this->naturePrestation == "examen" || $this->naturePrestation == "lunetterie")
                                    {
                                        $lignePrestation->setNbre(null);
                                    }
                                    
                                    $lignePrestationAudit->setEtatLignePrestation("enregistre");
                                    $this->getEntityManager()->persist($lignePrestationAudit);
                                }
                            }
                        }
                    }
                    
                    $this->getEntityManager()->flush();
                    
                    $this->getEntityManager()->getConnection()->commit();
                    
                    if(!empty($postValues["listeLignePrestationSent"]))
                    {
                        // Envoi du sms
                        $tabEmployeServiceSante = $this->employeManager->getListeEmploye("1", null, null, "-1", 1, null, false, 'SERVICE_SANTE');
                        $tabNumTelephone = array();
						$tabEmail= array();
						
                        foreach ($tabEmployeServiceSante as $unEmployeServiceSante)
                        {
                            $telephone = $unEmployeServiceSante->getUtilisateur()->getTelephone();
							$email = $unEmployeServiceSante->getUtilisateur()->getEmail();
                            if(!empty($telephone))
                                $tabNumTelephone[] = $telephone;
							
							if(!empty($email))
                                $tabEmail[] = $email;
                        }
                        
						
                        
                        $message = $this->getTranslator("Prestataire :")." ".$prestataireMettrePrix->getNom()."\n";
                        
                        if($this->naturePrestation == "ordonnance")
                        {
                            $message .= $this->getTranslator("Demande de validation d'une ordonnance pour la visite")." ".$consultation->getVisite()->getCodeCourt();
                        }
                        elseif($this->naturePrestation == "examen")
                        {
							if($typeSousProfil=="laboratoire")
			                {					
                            $message .= $this->getTranslator("Demande de validation des examens pour la visite")." ".$consultation->getVisite()->getCodeCourt();
							}
							else
							{
							$message .= $this->getTranslator("Demande de validation des examens/actes pour la visite")." ".$consultation->getVisite()->getCodeCourt();	
							}
                        }
						
						elseif($this->naturePrestation == "lunetterie")
                        {
							
							$message .= $this->getTranslator("Demande de validation des prestations en lunetterie pour la visite")." ".$consultation->getVisite()->getCodeCourt();	
							
                        }
                        
                        if(!empty($tabNumTelephone))
						{
                            $utilitaire->sendSmsHttp($tabNumTelephone, $message);
						}
						try {
					$utilitaire = new Utilitaire();
	                //$tabRecepteur=$tabEmail
					$tabRecepteur = array("gerard.tibui@zenitheinsurance.com" => 'TIBUI Gerard', 'mbele.alexis@zenitheinsurance.com' => 'MBELE Alexis');
					
		            $sujet="Validation ";
					$sujet.=$this->naturePrestation;
		            $contenuMail=$message;
		            //$utilitaire->sendMailSMTP($tabRecepteur, $sujet, $contenuMail);
                    } 
					catch (\Exception $e) 
					{
    	                
    	            }
                    }
                } catch (\Exception $e) {
                    $this->getEntityManager()->getConnection()->rollback();
                    $this->getEntityManager()->close();
                    $error = $e->getMessage();
                }
            }
            else
            {
                $error = "1234";
                $tabError = $form->getMessages();
            }
        }
        
        return new JsonModel(array(
            'error' => $error,
            'tabError' => $tabError,
            'info' => $info,
            'varRetour' => $varRetour,
            'naturePrestation' => $this->naturePrestation,
            'tabLignePrestation' => array(),
        ));
    }
    
	public function encaisserToutAction()
    {
		
		
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $formPosted = false;
        $listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation;
        $utilitaire = new Utilitaire();
        $sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        $lignePrestationManager = $this->lignePrestationManager;
        $prestataireEncaisse = null;
        
        $error = "";
        $info = "";
        $varRetour = "";
        $tabError = array();
        
        $form = $this->lignePrestationForm;
        
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        
        $id = $this->params()->fromRoute('id', null);
		 
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner l'identifiant de la prestation sur la route");
        }
        
        if(empty($error))
        {
            $prestation = $this->getEntityManager()->find('Entity\Prestation', $id);
            if(!$prestation)
            {
                $error = $this->getTranslator("Impossible de trouver la prestation");
            }
        }
        
        if(empty($error))
        {
            $consultation = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $prestation->getVisite()->getId()));
            if(!$consultation)
            {
                $error = $this->getTranslator("Impossible de trouver la consultation");
            }
			
        }
        
        
        if(empty($error))
        {    

          $tabParams = array("naturePrestation" => $this->naturePrestation,
                               "prestation" => $id,
                               "supprime" =>"-1",
							   "etat" => "valide",
                            );
	      $tab = $this->lignePrestationManager->getListeLignePrestationTabParams($tabParams);
		   
		   foreach($tab as $element )
		    {
		   
          // Debut du mode transactionnel
                $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                try {
					
                                
                              
                                            
                                                                          
                                             $nouveauEtatBD = "encaisse";    
                                            $employe = $this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id"));
                                            $dateEncaisse = new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours')));
											


                                            $lignePrestationAudit = new LignePrestationAudit();
                                            $lignePrestationAudit->setLignePrestation($element);
                                            $lignePrestationAudit->setEmploye($employe);
                                            $lignePrestationAudit->setDate($dateEncaisse);
                                            $lignePrestationAudit->setEtatLignePrestation($nouveauEtatBD);
                                            $this->getEntityManager()->persist($lignePrestationAudit);
                                            
                                            
                                            $element->setDateEncaisse($dateEncaisse);
											$element->setEtat($nouveauEtatBD);
										 
                                            
                                            
                                
                                
                            
                        
                    
                    
                    
                    $this->getEntityManager()->flush();
                    $this->getEntityManager()->getConnection()->commit();
                    
                    
                    
                } catch (\Exception $e) {
                    $this->getEntityManager()->getConnection()->rollback();
                    $this->getEntityManager()->close();
                    $error = $e->getMessage();
                }
		    }
            
        }
        
        return new JsonModel(array(
            'error' => $error,
            'tabError' => $tabError,
            'info' => $info,
            'varRetour' => $varRetour,
            'naturePrestation' => $this->naturePrestation,
            'tabLignePrestation' => array(),
        ));
    }
    
    public function validerRejeterAction ()
    {
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $formPosted = false;
        $listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation;
        $utilitaire = new Utilitaire();
        $sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        $lignePrestationManager = $this->lignePrestationManager;
        $prestataireMettrePrix = null;
        
        $error = "";
        $info = "";
        $varRetour = "";
        $tabError = array();
        
        $form = $this->lignePrestationForm;
        
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        // var_dump($postValues); exit;
        
        
        $id = $this->params()->fromRoute('id', null);
		 
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner l'identifiant de la prestation sur la route");
        }
        
        if(empty($error))
        {
            $prestation = $this->getEntityManager()->find('Entity\Prestation', $id);
            if(!$prestation)
            {
                $error = $this->getTranslator("Impossible de trouver la prestation");
            }
        }
        
        if(empty($error))
        {
            $consultation = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $prestation->getVisite()->getId()));
            if(!$consultation)
            {
                $error = $this->getTranslator("Impossible de trouver la consultation");
            }
        }
        
        if(empty($error))
        {
            $prestataireMettrePrix = $this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire"));
            if(!$prestataireMettrePrix)
            {
                $error = $this->getTranslator("Impossible de trouver le prestataire connecte");
            }
        }
        
        
        if(empty($error))
        {
            $lignePrestationInputFilter = $this->lignePrestationInputFilter;
            
            $form->setInputFilter($lignePrestationInputFilter->getInputFilter());
            $form->setData($postValues);
            
            
            if(!empty($postValues["listeLignePrestationSent"]))
            {
                $tabLignePrestation = @explode(";", $postValues["listeLignePrestationSent"]);
                
                foreach ($tabLignePrestation as $key => $valueIndex)
                {
                    $form->add(array(
                        "name" => "tauxLignePretation_".$valueIndex,
                        "attributes" => array(
                            "type" => "text",
                        ),
                    ));
                    
                    $form->add(array(
                        "name" => "valeurModifLignePretation_".$valueIndex,
                        "attributes" => array(
                            "type" => "number",
                        ),
                    ));
                    
                    $form->add(array(
                        "name" => "nbreModifLignePretation_".$valueIndex,
                        "attributes" => array(
                            "type" => "number",
                        ),
                    ));
                    
//                     if($this->naturePrestation == "examen")
//                     {
//                         $form->add(array(
//                             "name" => "nbreModifLignePretation_".$valueIndex,
//                             "attributes" => array(
//                                 "type" => "number",
//                             ),
//                         ));
//                     }
                    
                    
                    
                    $form->add(array(
                        "name" => "observationsLignePretation_".$valueIndex,
                        "attributes" => array(
                            "type" => "textarea",
                        ),
                    ));
                    
                    
                    $form->add(array(
                        'name' => "actionLignePretation_".$valueIndex,
                        'type' => 'Zend\Form\Element\Select',
                        'attributes' => array(
                            'type' => 'select',
                        ),
                        'options' => array(
                            'value_options' => array(
                                "valide"    => $this->getTranslator('Valide'),
                                "rejete"   => $this->getTranslator ('Rejete'),
                                "devalide"    => $this->getTranslator('Valide'),
                                "derejete"   => $this->getTranslator ('Rejete'),
                            ),
                        ),
                    ));
                    
                    
                    $lignePrestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("tauxLignePretation_".$valueIndex, false, 1, 100));
                    $lignePrestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("valeurModifLignePretation_".$valueIndex, false, 1));
                    $lignePrestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("nbreModifLignePretation_".$valueIndex, false, 1));
                    
                    $lignePrestationInputFilter->getInputFilter()->merge(new TextImputFilterLcl("observationsLignePretation_".$valueIndex, false, 5, 255));
                    $lignePrestationInputFilter->getInputFilter()->merge(new EnumImputFilterLcl("actionLignePretation_".$valueIndex, false, array("valide", "rejete", "devalide", "derejete")));
                }
            }
            
           
            
            if($form->isValid())
            {
                $donneesFormulaire = $form->getData();
                
                // Debut du mode transactionnel
                $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                try {

                    if(!empty($postValues["listeLignePrestationSent"]))
                    {
                        $tabLignePrestation = @explode(";", $postValues["listeLignePrestationSent"]);
                        
                        foreach ($tabLignePrestation as $key => $valueIndex)
                        {
                            $lignePrestation = $this->getEntityManager()->find('Entity\LignePrestation', $valueIndex);
                            if(!$lignePrestation)
                            {
                                $info = $this->getTranslator("Impossible de trouver la ligne prestation n°")." ".$valueIndex;
                            }
                            else
                            {
                                $nouveauEtat = $donneesFormulaire["actionLignePretation_".$valueIndex];
                                if(!empty($nouveauEtat))
                                {
                                    if(!empty($donneesFormulaire["valeurModifLignePretation_".$valueIndex]) || ($nouveauEtat == "devalide" || $nouveauEtat == "derejete"))
                                    {
                                        $afficher = false;
                                        
                                        if($nouveauEtat == "valide")
                                        {
                                            $afficher = $this->lignePrestationManager->vefifierSiLignePrestationValidable($lignePrestation);
                                        }
                                        elseif ($nouveauEtat == "rejete")
                                        {
                                            $afficher = $this->lignePrestationManager->vefifierSiLignePrestationRejetable($lignePrestation);
                                        }
                                        if($nouveauEtat == "devalide")
                                        {
                                            $afficher = $this->lignePrestationManager->vefifierSiLignePrestationDevalidable($lignePrestation);
                                        }
                                        elseif ($nouveauEtat == "derejete")
                                        {
                                            $afficher = $this->lignePrestationManager->vefifierSiLignePrestationDerejetable($lignePrestation);
                                        }
                                        
                                        if($afficher)
                                        {
                                            $nouveauEtatBD = $nouveauEtat;
                                            if($nouveauEtat == "devalide" || $nouveauEtat == "derejete")
                                            {
                                                 $nouveauEtatBD = "attente_validation";
                                                
                                                $lignePrestation->setObservations(null);
                                            }
                                            
                                            $employe = $this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id"));
                                            $dateValideRejete = new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours')));


                                            $lignePrestationAudit = new LignePrestationAudit();
                                            $lignePrestationAudit->setLignePrestation($lignePrestation);
                                            $lignePrestationAudit->setEmploye($employe);
                                            $lignePrestationAudit->setDate($dateValideRejete);
                                            $lignePrestationAudit->setEtatLignePrestation($nouveauEtatBD);
                                            $this->getEntityManager()->persist($lignePrestationAudit);
                                            
                                            
                                            $lignePrestation->setEmployeValideRejete($employe);
                                            $lignePrestation->setDateValideRejete($dateValideRejete);
                                            
                                            
                                            if($nouveauEtat == "valide" || $nouveauEtat == "rejete")
                                            {
                                                if($this->naturePrestation == "ordonnance")
                                                {
                                                    $lignePrestation->setTaux($donneesFormulaire["tauxLignePretation_".$valueIndex]);
                                                    $lignePrestation->setValeurModif($donneesFormulaire["valeurModifLignePretation_".$valueIndex]);
                                                    $lignePrestation->setEtat($nouveauEtatBD);
                                                    $lignePrestation->setObservations($donneesFormulaire["observationsLignePretation_".$valueIndex]);
                                                    $lignePrestation->setNbreModif($donneesFormulaire["nbreModifLignePretation_".$valueIndex]);
                                                }
                                                elseif(($this->naturePrestation == "examen" || $this->naturePrestation=="lunetterie") && !empty($donneesFormulaire["nbreModifLignePretation_".$valueIndex]))
                                                {
                                                    $lignePrestation->setTaux($donneesFormulaire["tauxLignePretation_".$valueIndex]);
                                                    $lignePrestation->setValeurModif($donneesFormulaire["valeurModifLignePretation_".$valueIndex]);
                                                    $lignePrestation->setEtat($nouveauEtatBD);
                                                    $lignePrestation->setNbreModif($donneesFormulaire["nbreModifLignePretation_".$valueIndex]);
                                                    $lignePrestation->setObservations($donneesFormulaire["observationsLignePretation_".$valueIndex]);
                                                }
                                            }
                                            elseif($nouveauEtat == "devalide" || $nouveauEtat == "derejete")
                                            {
                                                $lignePrestation->setEtat($nouveauEtatBD);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    
                    $this->getEntityManager()->flush();
                    $this->getEntityManager()->getConnection()->commit();
                    
                    
                    // Envoi du sms
                    if(!empty($postValues["listeLignePrestationSent"]))
                    {
                        $telephone = $consultation->getVisite()->getTelephone();
                        
                        $message  = $this->getTranslator("Prestataire :")." ".$prestataireMettrePrix->getNom()."\n";
                        
                        if($this->naturePrestation == "ordonnance")
                        {
                            $message .= $this->getTranslator("Des medicaments ont ete valides ou rejetes pour la visite")." ".$consultation->getVisite()->getCodeCourt();
                        }
                        elseif($this->naturePrestation == "examen" )
                        {
							if($typeSousProfil=="laboratoire")
							{
                            $message .= $this->getTranslator("Des examens ont ete valides ou rejetes pour la visite")." ".$consultation->getVisite()->getCodeCourt();
							}
							else
							{
						    $message .= $this->getTranslator("Des examens/actes ont ete valides ou rejetes pour la visite")." ".$consultation->getVisite()->getCodeCourt();
							}
                        }
						
						elseif($this->naturePrestation == "lunetterie" )
                        {
						    $message .= $this->getTranslator("Des prestations en lunetterie ont ete validees ou rejete pour la visite")." ".$consultation->getVisite()->getCodeCourt();
                        }
						
                        
                        //if(!empty($telephone))
                            //$utilitaire->sendSmsHttp($telephone, $message);
                    }
                } catch (\Exception $e) {
                    $this->getEntityManager()->getConnection()->rollback();
                    $this->getEntityManager()->close();
                    $error = $e->getMessage();
                }
            }
            else
            {
                $error = "1234";
                $tabError = $form->getMessages();
            }
        }
        
        return new JsonModel(array(
            'error' => $error,
            'tabError' => $tabError,
            'info' => $info,
            'varRetour' => $varRetour,
            'naturePrestation' => $this->naturePrestation,
            'tabLignePrestation' => array(),
        ));
    }
    
    public function encaisserAction()
    {
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $info = "";
        $varRetour = "";
        $utilitaire = new Utilitaire();
        $lignePrestation = null;
        $prestataireMettrePrix = null;
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $idLignePrestation = $this->params()->fromRoute('idLignePrestation', null);
        if (!$idLignePrestation) {
            $error = $this->getTranslator("Veuillez renseigner la ligne prestation dans l'url");
        }
        
        if(empty($error))
        {
            $lignePrestation = $this->getEntityManager()->find('Entity\LignePrestation', $idLignePrestation);
            if (!$lignePrestation) {
                $error = $this->getTranslator("Aucune ligne prestation trouve");
            }
        }
        
        if(empty($error))
        {
            $prestataireMettrePrix = $this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire"));
            if(!$prestataireMettrePrix)
            {
                $error = $this->getTranslator("Impossible de trouver le prestataire connecte");
            }
        }
        
        if(empty($error))
        {
            if(!$this->lignePrestationManager->vefifierSiLignePrestationEncaissable($lignePrestation, $sessionEmploye->offsetGet("id_prestataire")))
            {
                $error = $this->getTranslator("Vous ne pouvez plus encaisser cette ligne prestation");
            }
            
            if(empty($error))
            {
                $tabParamsHere = array('etat' => "encaisse",
                                       'prestataire' => $sessionEmploye->offsetGet("id_prestataire"),
                                       'prestation' => $lignePrestation->getPrestation()->getId(),
                                      );
                
                $tabLignePrestation = $this->getEntityManager()->getRepository('Entity\LignePrestation')->findBy($tabParamsHere);
                
                $nouveauEtat = "encaisse";
                
                $lignePrestation->setEtat($nouveauEtat);
                $lignePrestation->setDateEncaisse(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                
                
                
                $lignePrestationAudit = new LignePrestationAudit();
                $lignePrestationAudit->setLignePrestation($lignePrestation);
                $lignePrestationAudit->setEmploye($this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id")));
                $lignePrestationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                $lignePrestationAudit->setEtatLignePrestation($nouveauEtat);
                
                
                
                $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                try {
                    $this->getEntityManager()->persist($lignePrestationAudit);
                    $this->getEntityManager()->flush();
                    
                    $this->getEntityManager()->getConnection()->commit();
                    
                } catch (\Exception $e) {
                    $this->getEntityManager()->getConnection()->rollback();
                    $this->getEntityManager()->close();
                    $error = $e->getMessage();
                }
                
                
                if(!is_array($tabLignePrestation) || count($tabLignePrestation) <= 0)
                {
                    $tabNumTelephone = array($lignePrestation->getPrestation()->getVisite()->getTelephone());
                    
                    $message = $this->getTranslator("Votre prestation a ete prise en compte, merci de faire confiance a ZENITHE Insurance");
                    
                    //if(!empty($tabNumTelephone))
                        //$utilitaire->sendSmsHttp($tabNumTelephone, $message);
                }
                
                $varRetour = $this->getTranslator("Operation effectuee avec succes");
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
            'naturePrestation' => $this->naturePrestation,
        ));
    }

    public function rechercherVisiteAction()
    {
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $info = "";
        $varRetour = "";
        $prestation = null;
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner le code de la visite");
        }
        
        if(empty($error))
        {
            $visite = $this->getEntityManager()->find('Entity\Visite', $id);
            
            if(!$visite)
            {
                $error = $this->getTranslator("Impossible de trouver la visite, verifiez que vous avez bien saisi le code de la visite");
            }
            else
            {
                if($visite->getPrestataire()->getId() != $sessionEmploye->offsetGet("id_prestataire"))
                {
                    $error = $this->getTranslator("Cette visite a ete enregistree par un autre centre hospitalier");
                }
                else
                {
                    $prestation = $this->getEntityManager()->getRepository('Entity\Prestation')->findOneBy(array('visite' => $id, 'naturePrestation' => $this->naturePrestation));
                    if($prestation)
                    {
                        if($this->naturePrestation == "ordonnance")
                        {
                            $error = $this->getTranslator("Vous avez deja enregistre une ordonnance pour cette visite");
                        }
                        elseif($this->naturePrestation == "examen")
                        {
							if($typeSousProfil=="laboratoire")
							{
                            $error = $this->getTranslator("Vous avez deja enregistre des examens pour cette visite");
							}
							else
							{
								 $error = $this->getTranslator("Vous avez deja enregistre des examens/actes pour cette visite");
							}
                        }
						elseif($this->naturePrestation == "lunetterie")
                        {
							
								 $error = $this->getTranslator("Vous avez deja enregistre des prestations en lunetterie pour cette visite");
                        }
                        else
                        {
                            $error = $this->getTranslator("La nature de la prestation pour cette visite n'a pas ete bien renseignee");
                        }
                    }
                    else
                    {
                        $adherent = $visite->getCodeAdherent();
                        $adherent->afficheChaine();
                        $ayantDroit = $visite->getCodeAyantDroit();
                        
                        $nomAyantDroit = "";
                        if($ayantDroit)
                        {
                            $ayantDroit->afficheChaine();
                            $nomAyantDroit = $ayantDroit->getNom();
                        }
                        
                        
                        $varRetour = array("adherent" => $adherent->getAssurePrincipal(),
                                           "ayantDroit" => $nomAyantDroit,
                                           "souscripteur" => $adherent->getSouscripteur()
                        );
                    }
                }
            }
        }
        
        return new JsonModel(array(
            'error' => $error,
            'info' => $info,
            'varRetour' => $varRetour,
            'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
            'naturePrestation' => $this->naturePrestation,
        ));
    }
}
