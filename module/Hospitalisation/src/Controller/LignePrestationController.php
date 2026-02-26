<?php

namespace Hospitalisation\Controller;

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
use Hospitalisation\Form\FiltreListeLignePrestationForm;
use Hospitalisation\Form\LignePrestationForm;
use Application\Manager\LignePrestationManager;
use Hospitalisation\Form\LignePrestationInputFilter;
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
     * @var \Application\Manager\MenuManager
     */
    protected $menuManager;
    
    /**
     * @var \Application\Manager\EmployeManager
     */
    protected $employeManager;
    
    /**
     * @var \Hospitalisation\Form\FiltreListeLignePrestationForm
     */
    protected $filtreListeLignePrestationForm;
    
    /**
     * @var \Hospitalisation\Form\LignePrestationForm
     */
    protected $lignePrestationForm;
    
    /**
     * @var \Hospitalisation\Form\LignePrestationInputFilter
     */
    protected $lignePrestationInputFilter;
    
    protected $naturePrestation;
    
    protected $appliConfig;
    
    public function __construct(ContainerInterface $appliContainer, LignePrestation $lignePrestation, LignePrestationManager $lignePrestationManager,
                                MenuManager $menuManager, EmployeManager $employeManager, FiltreListeLignePrestationForm $filtreListeLignePrestationForm,
                                LignePrestationForm $lignePrestationForm, LignePrestationInputFilter $lignePrestationInputFilter)
    {
        $appliConfig =  new \Application\Core\AppliConfig();
        $this->appliConfig = $appliConfig;
        
        $this->appliContainer = $appliContainer;

        $this->lignePrestation = $lignePrestation;
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
        
			 $this->numeroOrdre=1;
		
        
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
        
        
        
				$this->nomPage = $this->getTranslator("Liste des prestations en hospitalisation");
        
        
        
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
                include_once __DIR__.'/../../view/hospitalisation/ligne-prestation/pagination.phtml';
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
                    
                    
                        $form->add(array(
                            "name" => "nbreLignePretation_".$valueIndex,
                            "attributes" => array(
                                "type" => "number",
                            ),
                        ));
                 

                    
                    
                    $lignePrestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("valeurLignePretation_".$valueIndex, false, 1));
                    
                        $lignePrestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("nbreLignePretation_".$valueIndex, false, 1));
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
                                $lignePrestationAudit->setDate(new \DateTime(date("Y-m-d H:i:s")));
                                
                                
                                if(!empty($donneesFormulaire["valeurLignePretation_".$valueIndex]))
                                {
                                    $afficherMettrePrixLignePrestationButton = $this->lignePrestationManager->vefifierSiLignePrestationMettrePrix($lignePrestation);
                                    if($afficherMettrePrixLignePrestationButton)
                                    {
                                        
                                       if( !empty($donneesFormulaire["nbreLignePretation_".$valueIndex]))
                                        {
                                            $lignePrestation->setValeur($donneesFormulaire["valeurLignePretation_".$valueIndex]);
                                            $lignePrestation->setEtat("attente_validation");
                                            $lignePrestation->setNbre($donneesFormulaire["nbreLignePretation_".$valueIndex]);
                                            $lignePrestation->setPrestataire($prestataireMettrePrix);
                                            
                                            $lignePrestationAudit->setEtatLignePrestation("attente_validation");
                                            $this->getEntityManager()->persist($lignePrestationAudit);
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
                                    

                                    $lignePrestation->setNbre(null);
                                  
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
                        foreach ($tabEmployeServiceSante as $unEmployeServiceSante)
                        {
                            $telephone = $unEmployeServiceSante->getUtilisateur()->getTelephone();
                            if(!empty($telephone))
                                $tabNumTelephone[] = $telephone;
                        }
                        
                        
                        $message = $this->getTranslator("Prestataire :")." ".$prestataireMettrePrix->getNom()."\n";
                        
						
							
							$message .= $this->getTranslator("Demande de validation des prestations en hospitalisation pour la visite")." ".$consultation->getVisite()->getCodeCourt();	
	
                        
                        if(!empty($tabNumTelephone))
                            $utilitaire->sendSmsHttp($tabNumTelephone, $message);
						
						//ENVOI DE MAIL
	                $tabRecepteur=array('mbele.alexis@zenitheinsurance.com' => 'Mbele Alexis',
					                    'gerard.tibui@zenitheinsurance.com.com'=>'Tibui Gerard',
										'angwibi.charles@zenitheinsurance.com'=>'Aghangu Charles',
										'futela.christina@zenitheinsurance.com'=>'Futela Christina',
										'tem.severine@zenitheinsurance.com'=>'Tem Severine',
										'fotue.roger@zenitheinsurance.com'=>'Fotue Roger');
										
		            $sujet.="Validation hospitalisation";
					
		            $contenuMail=$message;
		            $utilitaire->sendMailSMTP($tabRecepteur, $sujet, $contenuMail);
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
                                                $nouveauEtatBD = "enregistre";
                                                
                                                $lignePrestation->setObservations(null);
                                            }
                                            
                                            $employe = $this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id"));
                                            $dateValideRejete = new \DateTime(date("Y-m-d H:i:s"));

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
                                                
                                               if(!empty($donneesFormulaire["nbreModifLignePretation_".$valueIndex]))
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
                                                $lignePrestation->setTaux(null);
                                                //$lignePrestation->setValeurModif(null);
                                                $lignePrestation->setNbreModif(null);
                                                //$lignePrestation->setPrestataire(null);
                                                
                                                $lignePrestation->setValeur(null);
                                                
                                                    $lignePrestation->setNbre(null);
                                              
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
                        
                        
                        
						
						    $message .= $this->getTranslator("Des prestations ont ete valides ou rejetes pour la visite")." ".$consultation->getVisite()->getCodeCourt();
                       
						
                        
                        if(!empty($telephone))
                            $utilitaire->sendSmsHttp($telephone, $message);
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
                $lignePrestation->setDateEncaisse(new \DateTime(date("Y-m-d H:i:s")));
                
                
                
                $lignePrestationAudit = new LignePrestationAudit();
                $lignePrestationAudit->setLignePrestation($lignePrestation);
                $lignePrestationAudit->setEmploye($this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id")));
                $lignePrestationAudit->setDate(new \DateTime(date("Y-m-d H:i:s")));
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
                    
                    if(!empty($tabNumTelephone))
                        $utilitaire->sendSmsHttp($tabNumTelephone, $message);
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
                                 
							
								 $error = $this->getTranslator("Vous avez deja enregistre des prestations en hospitalisation pour cette visite");     
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
