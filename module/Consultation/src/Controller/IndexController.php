<?php

namespace Consultation\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Custom\Mvc\Controller\BackOfficeCommonController;

use Application\Core\Utilitaire;
use Application\Manager\ConsultationManager;
use Application\Manager\EmployeManager;
use Application\Manager\MenuManager;
use Entity\Consultation;
use Entity\Medicament;
use Consultation\Form\ConsultationForm;
use Consultation\Form\FiltreListeConsultationForm;
use Consultation\Form\ConsultationInputFilter;
use Consultation\Form\ValiderConsultationForm;
use Consultation\Form\ValiderConsultationInputFilter;
use Entity\ConsultationAudit;

class IndexController extends BackOfficeCommonController
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $appliContainer;
    
    /**
     * @var \Entity\Consultation
     */
    protected $consultation;
	
	 /**
     * @var \Entity\Medicament
     */
    protected $medicament;
    
    /**
     * @var \Application\Manager\ConsultationManager
     */
    protected $consultationManager;
    
    /**
     * @var \Consultation\Form\ConsultationForm
     */
    protected $consultationForm;
    
    /**
     * @var \Consultation\Form\FiltreListeConsultationForm
     */
    protected $filtreListeConsultationForm;
    
    /**
     * @var \Consultation\Form\ConsultationInputFilter
     */
    protected $consultationInputFilter;
    
    /**
     * @var \Consultation\Form\ValiderConsultationForm
     */
    protected $validerConsultationForm;
    
    /**
     * @var \Consultation\Form\ValiderConsultationInputFilter
     */
    protected $validerConsultationInputFilter;
    
    /**
     * @var \Application\Manager\MenuManager
     */
    protected $menuManager;
    
    /**
     * @var \Application\Manager\EmployeManager
     */
    protected $employeManager;
    
    protected $appliConfig;
    
    public function __construct(ContainerInterface $appliContainer, Consultation $consultation,Medicament $medicament, ConsultationManager $consultationManager, ConsultationForm $consultationForm,
                                FiltreListeConsultationForm $filtreListeConsultationForm, ConsultationInputFilter $consultationInputFilter,
                                ValiderConsultationForm $validerConsultationForm, ValiderConsultationInputFilter $validerConsultationInputFilter,
        MenuManager $menuManager, EmployeManager $employeManager)
    {
        $appliConfig =  new \Application\Core\AppliConfig();
        $this->appliConfig = $appliConfig;
        
        $this->appliContainer = $appliContainer;
        $this->consultation=$consultation;
        $this->medicament = $medicament;
        $this->consultationManager = $consultationManager;
        $this->consultationForm = $consultationForm;
        $this->filtreListeConsultationForm = $filtreListeConsultationForm;
        $this->consultationInputFilter = $consultationInputFilter;
        $this->validerConsultationForm = $validerConsultationForm;
        $this->validerConsultationInputFilter = $validerConsultationInputFilter;
        $this->menuManager = $menuManager;
        $this->employeManager = $employeManager;
        
        
        // $this->initialiserPermission();
        // $this->peuxAjouter = $this->consultationManager->verifierSiPeuxAjouterConsultation($this->tabListeMenu);
    }
	
	public function initialiserControlleur()
	{
		$this->initialiserPermission();
        $this->peuxAjouter = $this->consultationManager->verifierSiPeuxAjouterConsultation($this->tabListeMenu);
	}
	
    public function indexAction ()
    {
		$this->initialiserControlleur();
		
    	$sessionEmploye = new Container('employe');
    	
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$cheminPagination = $basePath."/".$this->appliConfig->get('lienBackoffice')."/consultation/pagination";
    	$this->nomPage = $this->getTranslator("Liste des consultations");
    	
    	$boutons = array('btn_ajouter' => array('url' => $this->appliConfig->get('lienBackoffice').'/consultation/ajouter'),
    	                 'btn_imprimer' => array('url' => $this->appliConfig->get('lienBackoffice').'/consultation/imprimer'),
    					 'btn_activer' => false,
    	                 'btn_desactiver' => false,
		    			 'btn_supprimer' => false,
    	);
    	
    	$formFiltre = $this->filtreListeConsultationForm;
    	
    	
    	// Construction du formulaire
    	$validerConsultationForm = $this->validerConsultationForm;
    	$validerConsultationForm->setAttribute('action', "");
    	$validerConsultationForm->setAttribute('class', "form-horizontal");
    	$validerConsultationForm->setAttribute('role', "form");
    	$validerConsultationForm->setAttribute('id', "modal-form-valider-consultation");
    	$validerConsultationForm->prepare();
    	
    	$simpleFormViewModel = new ViewModel();
    	$simpleFormViewModel->setTemplate('backoffice/simple_form');
    	$simpleFormViewModel->setVariable('form', $validerConsultationForm);
    	$simpleFormViewModel->setVariable('formPosted', false);
    	$simpleFormViewModel->setVariable('urlCancel', "#");
    	$simpleFormViewModel->setVariable('notUsedElt', array());
    	$simpleFormViewModel->setVariable('msgSuccess', "");
    	$simpleFormViewModel->setVariable('msgError', "");
    	$simpleFormViewModel->setVariable('msgWarning', "");
    	$simpleFormViewModel->setVariable('onlyFormElement', true);
    	$simpleFormViewModel->setVariable('customForm', true);
    	
    	$viewRender = $this->appliContainer->get('ViewRenderer');
    	$htmlValiderConsultationForm = $viewRender->render($simpleFormViewModel);
    	
    	
    	
    	
    	$typeProfil = $sessionEmploye->offsetGet("type_profil");
    	
    	
    	
    	$this->initBackViewList($boutons, 'Consultation', $formFiltre, $cheminPagination);  	
    	
    	
        return new ViewModel(array(
        	'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
            'htmlValiderConsultationForm' => $htmlValiderConsultationForm,
            'typeProfil' => $typeProfil,
        ));
    }
    
    public function ajouterAction ()
    {
		$utilitaire = new Utilitaire();
		
		$idVisite = $this->params()->fromRoute('idVisite', null);
		
		$visite = null;
		$codeCourtVisite = "";
		
		// var_dump($idVisiteCrypte); exit;
		if($idVisite)
		{
			unset($_SESSION['employe'], $_SESSION['agence']);
			
			//$idVisite = $utilitaire->encrypt_decrypt($idVisiteCrypte, "d");
			
			
			// var_dump($idVisiteCrypte, $utilitaire->encrypt_decrypt("2019_HOPITAL_DEIDO_9FE6MG", "e"), $idVisite); exit;
			
			$visite = $this->getEntityManager()->find('Entity\Visite',$idVisite);
			
			
			if($visite)
			{
				$codeCourtVisite = $visite->getCodeCourt();
				$employeConnect = $visite->getEmploye();
				$this->connecterUtilisateur($employeConnect);
			}
		}
		
		$this->initialiserControlleur();
		
		
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$this->nomPage = $this->getTranslator("Ajouter une consultation");
    	$formPosted = false;
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/consultation";
    	$utilitaire = new Utilitaire();
    	$sessionEmploye = new Container('employe');
    	$consultationManager = $this->consultationManager;
    	
    	$typeProfil = $sessionEmploye->offsetGet("type_profil");
		
		
		
		//recuperation visite
		//$idVisite = $this->params()->fromRoute('idVisite', null);
    	//if (!$idVisite) {
    	//	return $this->redirect()->toUrl($listEltsUrl);
    	//}
    	
    	$form = $this->consultationForm;
    	
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/consultation/ajouter");
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	
    	if($typeProfil == "admin")
    	{
    	    $form->get("natureConsultation")->setValue('gratuite');
    	}
    	elseif($typeProfil == "prestataire")
    	{
    	    $form->get("natureConsultation")->setValue('payante');
    	}
    	
    	
    	$this->initBackViewSimpleForm('Consultation', $form, $formPosted, $listEltsUrl, array("submitClose"));
    	
    	return new ViewModel(array(
    		'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
			'visite' => $visite,
			'origin' => 'ajout',
			'codeCourtVisite' => $codeCourtVisite,
    	));
    }
	
	public function recupererMontantAction()
    {
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $sessionEmploye = new Container('employe');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $info = "";
        $varRetour = "";
        $medicament = null;
        
        $typeProfil = $sessionEmploye->offsetGet("type_profil");
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner le code de la consultation");
        }
        
        
        
        if(empty($error))
        {
                       
						
                          $consultation = $this->getEntityManager()->getRepository('Entity\Medicament')->findOneBy(array('nom' => $id, 
						                                                                                                 'prestataire' =>$this->getEntityManager()->find('Entity\Prestataire',$sessionEmploye->offsetGet("id_prestataire")),
																														 'categorie' => '3',
																														 'statut'=> '1',
																														   'supprime'=>'-1'));
						  //var_dump($medicament);exit;
                       
						if($consultation)
						{
						$varRetour = array("id" => $consultation->getId(),
                                           "nom" => $consultation->getNom(),
                                           "prix" => $consultation->getPrix(),
										   
                        );
						}
						
        }
           
        
        
        return new JsonModel(array(
            'error' => $error,
            'info' => $info,
            'varRetour' => $varRetour,
            'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
        ));
    }
    
    public function enregistrerAction ()
    {
		$this->initialiserControlleur();
		
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $this->nomPage = $this->getTranslator("Ajouter une consultation");
        $formPosted = false;
        $listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/consultation";
        $utilitaire = new Utilitaire();
        $sessionEmploye = new Container('employe');
        $consultationManager = $this->consultationManager;
        
        $typeProfil = $sessionEmploye->offsetGet("type_profil");
        
        $error = "";
        $info = "";
        $varRetour = "";
        $tabError = array();
        
        $form = $this->consultationForm;
        
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        if($typeProfil == "admin")
        {
            $postValues['natureConsultation'] = "gratuite";
        }
        
        
        if(isset($postValues['visite']) && isset($postValues['visteTouvee']))
        {
            $postValues['visite'] = $postValues['visteTouvee'];
        }
        
        
        $consultationInputFilter = $this->consultationInputFilter;
        
        $form->setInputFilter($consultationInputFilter->getInputFilter());
        $form->setData($postValues);
        
        
        if($postValues['natureConsultation'] == "gratuite")
        {
            $form->getInputFilter()->remove('montant');
        }
        
        
        if($form->isValid())
        {
            // var_dump("eeeee"); exit;
            $donneesFormulaire = $form->getData();
            
            $visite = $this->getEntityManager()->find('Entity\Visite', $donneesFormulaire['visite']);
            if(!$visite)
            {
                $form->get("visite")->setMessages(array($this->getTranslator("Verifiez que le code de la visite est correct")));
                $error = "1234";
                $tabError = $form->getMessages();
            }
            else
            {
                $consultationOld = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $donneesFormulaire['visite']));
                if($consultationOld)
                {
                    $error = $this->getTranslator("Cette visite a deja une consultation");
                }
                else
                {
					
					//
		         if($donneesFormulaire['natureConsultation'] =='payante')
		         {
				   $permise=false;
			       if($visite->getCodeAyantDroit())
			       {
			        $permise = $this->consultationManager->consultationPermiseAyantDroit($visite->getCodeAyantDroit(),$donneesFormulaire['typeConsultation'],$visite->getPrestataire()->getId());	
                   }	
			       else
				    $permise = $this->consultationManager->consultationPermiseAdherent($visite->getCodeAdherent(),$donneesFormulaire['typeConsultation'],$visite->getPrestataire()->getId());
			
                   if($permise==false)
                   {
               
                    $error = $this->getTranslator("Impossible d'ajouter! Une consultation est valable deux semaines.\n");
				    $utilitaire = new Utilitaire();
				    $pres=$visite->getPrestataire()->getId();
				    $typecon=$donneesFormulaire['typeConsultation'];
				
	                $tabRecepteur=array('mbele.alexis@zenitheinsurance.com' => 'Mbele Alexis');
		            $sujet="Tentative d'ajout de doublon";
		            $contenuMail=strval($visite->getCodeAdherent()->getAssurePrincipal())."&&&&&&".strval($typecon)."&&&&&&".strval($pres);
		            $utilitaire->sendMailSMTP($tabRecepteur, $sujet, $contenuMail);
				    goto consultationinterdite;
                   }
		         }
					
					//
                    if($donneesFormulaire['natureConsultation'] == "gratuite")
                    {
                        $etatConsultation = "encaisse";
                    }
                    else
                    {
                        $etatConsultation = "attente_validation";
                    }
                    
                    
                    $consultation = $this->consultation;
                    $consultation->exchangeArray($donneesFormulaire,null, false, array("typeConsultation"));
                    $consultation->setVisite($visite);
                    $consultation->setTypeConsultation($this->getEntityManager()->find('Entity\TypePrestation', $donneesFormulaire['typeConsultation']));
                    $consultation->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                    $consultation->setEtatConsultation($etatConsultation);
                    if($donneesFormulaire['natureConsultation'] == "gratuite")
                    {
                        $consultation->setMontantModif(0);
                    }
                    
                    if($donneesFormulaire['natureConsultation'] == "gratuite")
                    {
                        $consultation->setMontant(0);
                    }
                    
                    
                    // Debut du mode transactionnel
                    $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                    try {
                         $consultationPrix=$this->getEntityManager()->getRepository('Entity\Medicament')->findOneBy(array('origine' => $donneesFormulaire['typeConsultation'] , 
									                                                                                       'prestataire' => $sessionEmploye->offsetGet("id_prestataire"),
																														   'statut'=> '1',
																														   'supprime'=>'-1'));
                        // Enregistrement dans la table consultation
                        $consultation->nettoyageChaine();
                        $this->getEntityManager()->persist($consultation);
                        $this->getEntityManager()->flush();
                        
                        
                        $consultationAudit = new ConsultationAudit();
                        $consultationAudit->setConsultation($consultation);
                        $consultationAudit->setEmploye($this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id")));
                        $consultationAudit->setEtatConsultation($etatConsultation);
                        $consultationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                        
                        $this->getEntityManager()->persist($consultationAudit);
                        $this->getEntityManager()->flush();
						
						                if(!$consultationPrix && $donneesFormulaire['natureConsultation'] == "payante")
										{
										  $consultationPrixNew = new Medicament();
										  $consultationPrixNew->setCode("1");
										  $consultationPrixNew->setNom($donneesFormulaire['typeConsultation']);
										   $consultationPrixNew->setOrigine($donneesFormulaire['typeConsultation']);
										  $consultationPrixNew->setPrix($donneesFormulaire['montant']);
										  $consultationPrixNew->setCategorie("3");
										  $consultationPrixNew->setPrestataire($this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")));
										  $consultationPrixNew->setStatut("1");
										  $consultationPrixNew->setSupprime("-1");
										  $this->getEntityManager()->persist($consultationPrixNew);
										}
										elseif ($consultationPrix && $donneesFormulaire['natureConsultation'] == "payante")
										{
										$consultationPrix->setPrix($donneesFormulaire['montant']);
										
										}
			
										$this->getEntityManager()->flush();	
						
						
                        
                        $this->getEntityManager()->getConnection()->commit();
                        
                        
                        if($consultation->getNatureConsultation() == "payante")
                        {
                            // Envoi du sms
                            $tabEmployeServiceSante = $this->employeManager->getListeEmploye("1", null, null, "-1", 1, null, false, 'SERVICE_SANTE');
                            $tabNumTelephone = array();
							$tabEmail = array();
							
                            foreach ($tabEmployeServiceSante as $unEmployeServiceSante)
                            {
                             $telephone = $unEmployeServiceSante->getUtilisateur()->getTelephone();
							$email = $unEmployeServiceSante->getUtilisateur()->getEmail();
                            if(!empty($telephone))
                                $tabNumTelephone[] = $telephone;
							
							if(!empty($email))
                                $tabEmail[] = $email;
                            }
                            
                            $message  = $this->getTranslator("Centre hospitalier :")." ".$visite->getPrestataire()->getNom()."\n";
                            $message .= $this->getTranslator("Demande de consultation de code")." ".$visite->getCodeCourt()." ".$this->getTranslator("en attente de validation");
                            
                            if(!empty($tabNumTelephone))
							{
								$utilitaire->sendSmsHttp($tabNumTelephone, $message);
							}
							
							// Envoi du mail
    	            try {
					$utilitaire = new Utilitaire();
	                //$tabRecepteur=$tabEmail;
					 $tabRecepteur = array("gerard.tibui@zenitheinsurance.com" => 'TIBUI Gerard', 'mbele.alexis@zenitheinsurance.com' => 'MBELE Alexis');
		            $sujet="Validation Consultation";
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
            }
        }
        else
        {
            $error = "1234";
            $tabError = $form->getMessages();
        }
        
        consultationinterdite:
        
        return new JsonModel(array(
            'error' => $error,
			'tabError' => $tabError,
			'info' => $info,
			'varRetour' => $varRetour,
        ));
    }
    
    public function modifierAction ()
    {
		$this->initialiserControlleur();
		
        $appliConfig =  new \Application\Core\AppliConfig();
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/consultation";
    	$sessionEmploye = new Container('employe');
    	$utilitaire = new Utilitaire();
    	$consultationManager = $this->consultationManager;
    	$msgSuccess = "";
    	$visite = null;
    	
    	$id = $this->params()->fromRoute('id', null);
    	if (!$id) {
    		return $this->redirect()->toUrl($listEltsUrl);
    	}
    	$consultation = $this->getEntityManager()->find('Entity\Consultation', $id);
    	if(!$consultation)
    	{
    	    return $this->redirect()->toUrl($listEltsUrl);
    	}
    	
    	$visite = $consultation->getVisite();
    	
    	$consultation->afficheChaine();

    	$formPosted = false;

    	$request = $this->getRequest();
    	
    	$form = $this->consultationForm;
    	$form->setData($consultation->getArrayCopy());
    	
    	
    	
    	if($consultation->getVisite()->getPrestataire()->getId() != $sessionEmploye->offsetGet("id_prestataire"))
    	{
    	    return $this->redirect()->toUrl($basePath."/".$appliConfig->get("lienBackoffice")."/acces-refuse");
    	}
    	
    	
    	if(!$this->consultationManager->vefifierSiConsultationModifiable($consultation))
    	{
    	    return $this->redirect()->toUrl($basePath."/".$appliConfig->get("lienBackoffice")."/acces-refuse");
    	}
		
		$visite = $consultation->getVisite();
    	
    	
    	if($request->isPost())
    	{
    		$postData = $request->getPost();
    		$formPosted = true;
    		$consultationInputFilter = $this->consultationInputFilter;
    		
    		$form->setInputFilter($consultationInputFilter->getInputFilter());
    		$form->setData($postData);
    		
    		
    		$form->setValidationGroup("natureConsultation", "typeConsultation", "montant");
    		if ($form->isValid())
    		{
    			$donneesFormulaire = $form->getData();
    			
    			$consultation->exchangeArray($donneesFormulaire, null, false, array("typeConsultation"));
    			$consultation->setTypeConsultation($this->getEntityManager()->find('Entity\TypePrestation', $donneesFormulaire['typeConsultation']));
    			
    			
    			$etatConsultation = $consultation->getEtatConsultation();
    			if($donneesFormulaire['natureConsultation'] == "gratuite")
    			{
    			    $etatConsultation = "encaisse";
    			    
    			    $consultation->setMontant(0);
    			    $consultation->setMontantModif(0);
    			    $consultation->setEtatConsultation($etatConsultation);
    			}
    			
    			
    			$consultationAudit = new ConsultationAudit();
    			$consultationAudit->setConsultation($consultation);
    			$consultationAudit->setEmploye($this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id")));
    			$consultationAudit->setEtatConsultation($etatConsultation);
    			$consultationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
    			
    			
    			
    			
    			
    			// Debut du mode transactionnel
    			$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    			try {

    				$consultation->nettoyageChaine();
    				
    				$this->getEntityManager()->persist($consultationAudit);
    				$this->getEntityManager()->flush(); 				
    				
    				$this->getEntityManager()->getConnection()->commit();
    				
    				// Redirection dans la la liste des employes
    				if(isset($request->getPost()->submitClose))
    				{
    				    // Redirection dans la la liste des questions
    				    return $this->redirect()->toRoute("consultation");
    				}
    				else
    				{
    				    if($donneesFormulaire['natureConsultation'] == "gratuite")
    				    {
    				        // Redirection dans la la liste des questions
    				        return $this->redirect()->toRoute("consultation");
    				    }
    				    else
    				    {
    				        $msgSuccess = $this->getTranslator("Operation effectuee avec success");
    				    }
    				}
    				
    				
    			} catch (\Exception $e) {
    				$this->getEntityManager()->getConnection()->rollback();
    				$this->getEntityManager()->close();
    				throw $e;
    			}
    		}
    	}
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/consultation/modifier/".$id);
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	
    	
    	$this->nomPage = $this->getTranslator("Modifier une consultation");
    	
    	$this->initBackViewSimpleForm('Consultation', $form, $formPosted, $listEltsUrl, array("visite"), $msgSuccess);
    	 
    	return new ViewModel(array(
    		'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    		'idConsultation' => $consultation->getId(),
    	    'infosMalade' => $this->construireInfosMalade($visite),
			'origin' => 'modif',
			'codeCourtVisite' => $visite->getCodeCourt(),
    	));
    }
    
    public function paginationAction()
    {
		$this->initialiserControlleur();
		
    	$sessionEmploye = new Container('employe');
    	$consultationManager = $this->consultationManager;
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$codeProfil = $sessionEmploye->offsetGet("code_profil");
    	$error = "";
    	$info = "";
    	$varRetour = "";
    
    	$numActuel = $this->params()->fromRoute('numActuel', null);
    	if(!$numActuel) $numActuel = 1;
    
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	
    	if(is_array($postValues) && isset($postValues['prestataire']) && isset($postValues['typeConsultation']) && isset($postValues['etatConsultation'])
    	   && isset($postValues['dateMin']) && isset($postValues['dateMax'])
    	   && isset($postValues['nomAdherent']) && isset($postValues['nomAyantDroit']) && isset($postValues['souscripteur'])
    	   // && isset($postValues['idVisite'])
    	   && isset($postValues['nomColoneTriPagination']) && isset($postValues['typeTriColonePagination']))
    	{
    		$supprime = -1;
    		if($codeProfil == "SUP_ADMIN") // Le super administrateur voit tout
    		{
    		    $supprime = null;
    		}
    		
    		$typeProfil = $sessionEmploye->offsetGet("type_profil");
    		$prestataireId = trim($postValues['prestataire']);
    		if($typeProfil == "prestataire")
    		{
    		    $prestataireId = $sessionEmploye->offsetGet("id_prestataire");
    		}
    		
    		$tabParams = array("prestataire" => $prestataireId,
    		                   "typeConsultation" => trim($postValues['typeConsultation']),
                    		   "etatConsultation" => trim($postValues['etatConsultation']),
                    		   "dateMin" => trim($postValues['dateMin']),
                    		   "dateMax" => trim($postValues['dateMax']),
							   
							   "souscripteur" => trim($postValues['souscripteur']),
    		    
                		       "nomAdherent" => trim($postValues['nomAdherent']),
                		       "nomAyantDroit" => trim($postValues['nomAyantDroit']),
                		       "natureConsultation" => "payante",
    		           
    		                   "supprime" => $supprime,
    		                   "pagination" => true,
    		                   "nroPage" => $numActuel,
    		                   "nbreMax"  => $postValues['nbreMaxLigneTableau'],
    		                   "orderBy" => array($postValues['nomColoneTriPagination'] => $postValues['typeTriColonePagination']),
    		                  );
    		
    		
    		$retourPagination = $this->consultationManager->getListeConsultationTabParams($tabParams);
    		
    		$tab = $retourPagination['tab'];
    		$totalResult = $retourPagination['totalResult'];

    		if(is_array($tab) && count($tab) > 0 && $totalResult > 0)
    		{
    			$nroPage = $numActuel;
    			$nbrePages = $consultationManager::NBRE_PAGE_PAGINATION;
    			$nbreResults = $totalResult;
    			$nbreMaxResultsParPage = $postValues['nbreMaxLigneTableau'];
    			$cheminControlleur = $basePath.'/';
    			$parametres = "";
    		
    			include_once __DIR__.'/../../../Application/view/partial/pagination.phtml';
    			 
    			 
    			if(isset($headPagination) && is_string($headPagination)) $varRetour .= $headPagination;
    			include_once __DIR__.'/../../view/consultation/index/pagination.phtml';
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
    
    public function imprimerPdfAction()
    {
		$this->initialiserControlleur();
		
        $sessionEmploye = new Container('employe');
        $consultationManager = $this->consultationManager;
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $codeProfil = $sessionEmploye->offsetGet("code_profil");
        $error = "";
        $info = "";
        $varRetour = "";
        
        $postValues = $this->getRequest()->getPost();
        $postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
        $postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
        
        
        if(is_array($postValues) && isset($postValues['prestataire']) && isset($postValues['typeConsultation']) && isset($postValues['etatConsultation'])
            && isset($postValues['dateMin']) && isset($postValues['dateMax'])
            && isset($postValues['nomAdherent']) && isset($postValues['nomAyantDroit']) && isset($postValues['souscripteur'])
            // && isset($postValues['idVisite'])
            && isset($postValues['nomColoneTriPagination']) && isset($postValues['typeTriColonePagination']))
        {
            $supprime = -1;
            if($codeProfil == "SUP_ADMIN") // Le super administrateur voit tout
            {
                $supprime = null;
            }
            
            $typeProfil = $sessionEmploye->offsetGet("type_profil");
            $prestataireId = trim($postValues['prestataire']);
            if($typeProfil == "prestataire")
            {
                $prestataireId = $sessionEmploye->offsetGet("id_prestataire");
            }
            
            $tabParams = array("prestataire" => $prestataireId,
                               "typeConsultation" => trim($postValues['typeConsultation']),
                               "etatConsultation" => trim($postValues['etatConsultation']),
							    "natureConsultation" => "payante",
                               "dateMin" => trim($postValues['dateMin']),
                               "dateMax" => trim($postValues['dateMax']),
							   "souscripteur" => trim($postValues['souscripteur']),
                
                
                               "nomAdherent" => trim($postValues['nomAdherent']),
                               "nomAyantDroit" => trim($postValues['nomAyantDroit']),
                               // "visite" => trim($postValues['idVisite']),
                
                
                
                               "supprime" => $supprime,
                               "pagination" => false,
                               "orderBy" => array($postValues['nomColoneTriPagination'] => $postValues['typeTriColonePagination']),
            );
            
            
            $tab = $this->consultationManager->getListeConsultationTabParams($tabParams);
            if(is_array($tab) && count($tab) > 0)
            {
                include_once __DIR__.'/../../view/consultation/index/imprimer-pdf.phtml';
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
            'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
        ));
    }
    
    public function imprimerCsvAction()
    {
		$this->initialiserControlleur();
		
        $appliConfig =  new \Application\Core\AppliConfig();
        $sessionEmploye = new Container('employe');
        $consultationManager = $this->consultationManager;
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $codeProfil = $sessionEmploye->offsetGet("code_profil");
        $error = "";
        $info = "";
        $varRetour = "";
        
        $postValues = $this->getRequest()->getPost();
        $postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
        $postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
        
        
        if(is_array($_GET) && isset($_GET['prestataire']) && isset($_GET['typeConsultation']) && isset($_GET['etatConsultation'])
            && isset($_GET['dateMin']) && isset($_GET['dateMax'])
            && isset($_GET['nomAdherent']) && isset($_GET['nomAyantDroit'])
            // && isset($_GET['idVisite'])
            && isset($_GET['nomColoneTriPagination']) && isset($_GET['typeTriColonePagination'])
            && isset($_GET['nbreMaxLigneTableau']))
        {
            $supprime = -1;
            if($codeProfil == "SUP_ADMIN") // Le super administrateur voit tout
            {
                $supprime = null;
            }
            
            $typeProfil = $sessionEmploye->offsetGet("type_profil");
            $prestataireId = trim($_GET['prestataire']);
            if($typeProfil == "prestataire")
            {
                $prestataireId = $sessionEmploye->offsetGet("id_prestataire");
            }
            
            $tabParams = array("prestataire" => $prestataireId,
                "typeConsultation" => trim($_GET['typeConsultation']),
                "etatConsultation" => trim($_GET['etatConsultation']),
                "dateMin" => trim($_GET['dateMin']),
                "dateMax" => trim($_GET['dateMax']),
                
                "nomAdherent" => trim($_GET['nomAdherent']),
                "nomAyantDroit" => trim($_GET['nomAyantDroit']),
                // "visite" => trim($_GET['idVisite']),
                
                "supprime" => $supprime,
                "pagination" => false,
                "orderBy" => array($_GET['nomColoneTriPagination'] => $_GET['typeTriColonePagination']),
            );
            
            
            $tab = $this->consultationManager->getListeConsultationTabParams($tabParams);
            if(is_array($tab) && count($tab) > 0)
            {
                $properties = array("creator" => "MSBT",
                    "lastModifiedBy" => "System");
                
                
                $titresColones = array();
                if($typeProfil == "admin")
                {
                    $titresColones[] = array("titre" => $this->getTranslator("Prestataire"), "largeur" => 20);
                }
                
                $titresColonesAutres = array(array("titre" => $this->getTranslator("Type consulation"), "largeur" => 20),
                                        array("titre" => $this->getTranslator("Nature affection"), "largeur" => 25),
                                        array("titre" => $this->getTranslator("Date"), "largeur" => 20),
                                        array("titre" => $this->getTranslator("Montant declare"), "largeur" => 15),
                                        array("titre" => $this->getTranslator("Montant paye par Zenithe"), "largeur" => 15),
                                        array("titre" => $this->getTranslator("Part assure"), "largeur" => 15),
                                        array("titre" => $this->getTranslator("Etat"), "largeur" => 20)
                                       );
                
                $titresColones = array_merge($titresColones, $titresColonesAutres);
                
                
                $datas = array();
                foreach ($tab as $element)
                {
                    $visite = $element->getVisite();
                    $prestataire = $visite->getPrestataire();
                    $adherent = $visite->getCodeAdherent();
                    $ayantDroit = $visite->getCodeAyantDroit();
                    $nomSouscripteur = $adherent->getSouscripteur();


                    $element->afficheChaine();
                    $prestataire->afficheChaine();
                    $adherent->afficheChaine();


                    $nomAdherent = $adherent->getAssurePrincipal();
                    $nomAyantDroit = "";
                    if($ayantDroit)
                    {
                        $ayantDroit->afficheChaine();
                        $nomAyantDroit = $ayantDroit->getNom();
                    }

                    switch ($element->getEtatConsultation()) {
                        case "attente_validation":
                            $titleImgEtat = $this->getTranslator("Attente de validation");
                            $classCouleur = "text-orange";
                            ;
                            break;

                        case "rejete":
                            $titleImgEtat = $this->getTranslator("Rejete");
                            $classCouleur = "text-red";
                            ;
                            break;

                        case "valide":
                            $titleImgEtat = $this->getTranslator("Valide");
                            $classCouleur = "text-green";
                            ;
                            break;

                        case "encaisse":
                            $titleImgEtat = $this->getTranslator("Encaisse");
                            $classCouleur = "text-navy";
                            ;
                            break;

                        default:
                            $clasCssImgEtat = "";
                            $titleImgEtat = "";
                            break;
                    }

                    $montantAssure = "";
                    if($element->getEtatConsultation() == "valide" || $element->getEtatConsultation() == "encaisse")
                    {
                        $montantAssure = $element->getMontant() - $element->getMontantModif();
                        if($montantAssure != 0)
                        {
                            $montantAssure = number_format($montantAssure, 0, ",", " ");
                        }
                        else
                        {
                            $montantAssure = "";
                        }
                    }
                    
                    
                    $natureAffection = $element->getNatureAffection();
                    if(empty($natureAffection))
                    {
                        $natureAffection = "";
                    }
                    
                    
                    $montantModif = $element->getMontantModif();
                    if(!empty($montantModif))
                    {
                        $montantModif = number_format($element->getMontantModif(), 0, ",", " ");
                    }
                    else
                    {
                        $montantModif = "";
                    }
                    
                    
                    $datasHere = array();
                    if($typeProfil == "admin")
                    {
                        $datasHere[] = $prestataire->getNom();
                    }
                    
                    $datasHereAutres = array($element->getTypeConsultation()->getNom(), $natureAffection, $element->getDate()->format("d/m/Y H:i"),
                                             number_format($element->getMontant(), 0, ",", " "), $montantModif,
                                             $montantAssure, $titleImgEtat);
                    
                    $datasHere = array_merge($datasHere, $datasHereAutres);
                    
                    $datas[] = $datasHere;
                }
                
                $msbtExcel = new \Application\Core\MsbtExcel($properties, $titresColones, $datas, $this->getTranslator("Liste des consultations"));
                $msbtExcel->download();
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
            'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
        ));
    }
    
    public function validerConsultationAction()
    {
		$this->initialiserControlleur();
		
    	$sessionEmploye = new Container('employe');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$error = "";
    	$tabError = array();
    	$info = "";
    	$varRetour = "";
    	$utilitaire = new Utilitaire();
    	$consultation = null;
    
    	$postValues = array_merge_recursive(
    	    $this->getRequest()->getPost()->toArray(),
    	    $this->getRequest()->getFiles()->toArray()
    	    );
    
    	 
    	$id = $this->params()->fromRoute('id', null);
    	if (!$id) {
    		$error = $this->getTranslator("Veuillez renseigner la consultation dans l'url");
    	}
    	
    	if(empty($error))
    	{
    	    $consultation = $this->getEntityManager()->find('Entity\Consultation', $id);
    	    if (!$consultation) {
    	        $error = $this->getTranslator("Aucune consultation trouve");
    	    }
    	}
    	
    	
    	$nouveauEtat = "valide";
		 $dateValideRejete = new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours')));
    	 
    	if(empty($error))
    	{
    	    if(!$this->consultationManager->vefifierSiConsultationValidable($consultation))
    	    {
    	        $error = $this->getTranslator("Vous ne pouvez plus modifier cette consultation, car elle est deja validee ou rejetee");
    	    }
    	    elseif(trim($postValues['montantModif']) > $consultation->getMontant())
    	    {
    	        $error = $this->getTranslator("Le montant paye par Zenithe doit etre inferieur ou egal au montant du centre hospitalier");
    	    }
    	    
    	    if(empty($error))
    	    {
    	        $form = $this->validerConsultationForm;
    	        
    	        $form->setInputFilter($this->validerConsultationInputFilter->getInputFilter());
    	        $form->setData($postValues);
    	        
    	        if($form->isValid())
    	        {
    	            $employe = $this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id"));
    	            
    	            $donneesFormulaire = $form->getData();
    	            $consultation->exchangeArray($donneesFormulaire);
    	            $consultation->setEmployeValideRejete($employe);
					$consultation->setDateValideRejete($dateValideRejete);
    	            $consultation->setEtatConsultation($nouveauEtat);
    	            $consultation->nettoyageChaine();
    	            
    	            $consultationAudit = new ConsultationAudit();
    	            $consultationAudit->setConsultation($consultation);
    	            $consultationAudit->setEmploye($employe);
    	            $consultationAudit->setEtatConsultation($nouveauEtat);
    	            $consultationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
    	            
    	            
    	            // Debut du mode transactionnel
    	            $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    	            try {
    	                $this->getEntityManager()->persist($consultationAudit);
    	                $this->getEntityManager()->flush();
    	                
    	                $this->getEntityManager()->getConnection()->commit();
    	                
    	            } catch (\Exception $e) {
    	                $this->getEntityManager()->getConnection()->rollback();
    	                $this->getEntityManager()->close();
    	                $error = $e->getMessage();
    	            }
    	            
    	            
    	            $varRetour = $this->getTranslator("Operation effectuee avec succes");
    	            
    	            // Envoi du sms
    	            try {
    	                $visite = $consultation->getVisite();
    	                $telephone = $visite->getTelephone();
    	                
    	                $message  = $this->getTranslator("Centre hospitalier :")." ".$visite->getPrestataire()->getNom()."\n";
    	                $message .= $this->getTranslator("Votre demande de consultation pour la visite de code")." ".$visite->getCodeCourt()." ".$this->getTranslator("a ete validee");
    	                
    	                //if(!empty($telephone))
    	                  // $utilitaire->sendSmsHttp($telephone, $message);
    	            } catch (\Exception $e) {
    	                
    	            }
					
	                
    	            
    	        }
    	        else
    	        {
    	            $error = "1234";
    	            $tabError = $form->getMessages();
    	        }
    	    }
    	}
    	else
    	{
    		$error = $this->getTranslator("Toutes les valeurs du filtre n'ont pas ete transmises");
    	}
    
    	return new JsonModel(array(
    			'error' => $error,
    	        'tabError' => $tabError,
    			'info' => $info,
    			'varRetour' => $varRetour,
    			'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    	));
    }
    
    public function devaliderConsultationAction()
    {
		$this->initialiserControlleur();
		
        $sessionEmploye = new Container('employe');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $info = "";
        $varRetour = "";
        $utilitaire = new Utilitaire();
        $consultation = null;
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner la consultation dans l'url");
        }
        
        if(empty($error))
        {
            $consultation = $this->getEntityManager()->find('Entity\Consultation', $id);
            if (!$consultation) {
                $error = $this->getTranslator("Aucune consultation trouve");
            }
        }
        
        $nouveauEtat = "attente_validation";
        
        if(empty($error))
        {
            if(!$this->consultationManager->vefifierSiConsultationDevalidable($consultation))
            {
                $error = $this->getTranslator("Vous ne pouvez plus devalider cette consultation");
            }
            
            if(empty($error))
            {
                
                $employe = $this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id"));
                $consultation->setEtatConsultation($nouveauEtat);
                $consultation->setEmployeValideRejete($employe);
                $consultation->setMontantModif(null);
                
                $consultationAudit = new ConsultationAudit();
                $consultationAudit->setConsultation($consultation);
                $consultationAudit->setEmploye($employe);
                $consultationAudit->setEtatConsultation($nouveauEtat);
                $consultationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                
                
                // Debut du mode transactionnel
                $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                try {
                    $this->getEntityManager()->persist($consultationAudit);
                    $this->getEntityManager()->flush();
                    
                    $this->getEntityManager()->getConnection()->commit();
                    
                } catch (\Exception $e) {
                    $this->getEntityManager()->getConnection()->rollback();
                    $this->getEntityManager()->close();
                    $error = $e->getMessage();
                }
                
                
                
                // $consultation->setEtatConsultation("rejete");
                $this->getEntityManager()->flush();
                
                $varRetour = $this->getTranslator("Operation effectuee avec succes");
                
//                // Envoi du sms
//                try {
                    
//                     $visite = $consultation->getVisite();
//                     $telephone = $visite->getTelephone();
                    
//                     $message  = $this->getTranslator("Centre hospitalier :")." ".$visite->getPrestataire()->getNom()."\n";
//                     $message .= $this->getTranslator("Votre demande de consultation pour la visite de code")." ".$visite->getCodeCourt()." ".$this->getTranslator("a ete devalidee");
                    
//                     if(!empty($telephone))
//                        $utilitaire->sendSmsHttp($telephone, $message);
//                 } catch (\Exception $e) {
                    
//                 }
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
    
    public function rejeterConsultationAction()
    {
		$this->initialiserControlleur();
		
        $sessionEmploye = new Container('employe');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $info = "";
        $varRetour = "";
        $utilitaire = new Utilitaire();
        $consultation = null;
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner la consultation dans l'url");
        }
        
        if(empty($error))
        {
            $consultation = $this->getEntityManager()->find('Entity\Consultation', $id);
            if (!$consultation) {
                $error = $this->getTranslator("Aucune consultation trouve");
            }
        }
        
        $nouveauEtat = "rejete";
        
        if(empty($error))
        {
            if(!$this->consultationManager->vefifierSiConsultationRejetable($consultation))
            {
                $error = $this->getTranslator("Vous ne pouvez plus rejeter cette consultation");
            }
            
            if(empty($error))
            {
                
                $employe = $this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id"));
                $consultation->setEtatConsultation($nouveauEtat);
                $consultation->setEmployeValideRejete($employe);
                
                $consultationAudit = new ConsultationAudit();
                $consultationAudit->setConsultation($consultation);
                $consultationAudit->setEmploye($employe);
                $consultationAudit->setEtatConsultation($nouveauEtat);
                $consultationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                
                
                // Debut du mode transactionnel
                $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                try {
                    $this->getEntityManager()->persist($consultationAudit);
                    $this->getEntityManager()->flush();
                    
                    $this->getEntityManager()->getConnection()->commit();
                    
                } catch (\Exception $e) {
                    $this->getEntityManager()->getConnection()->rollback();
                    $this->getEntityManager()->close();
                    $error = $e->getMessage();
                }
                
                
                
                // $consultation->setEtatConsultation("rejete");
                $this->getEntityManager()->flush();
                
                $varRetour = $this->getTranslator("Operation effectuee avec succes");
                
                // Envoi du sms
                try {
                    $visite = $consultation->getVisite();
                    $telephone = $visite->getTelephone();
                    
                    $message  = $this->getTranslator("Centre hospitalier :")." ".$visite->getPrestataire()->getNom()."\n";
                    $message .= $this->getTranslator("Votre demande de consultation pour la visite de code")." ".$visite->getCodeCourt()." ".$this->getTranslator("a ete rejetee");
                    
                    
                    if(!empty($telephone))
                        $utilitaire->sendSmsHttp($telephone, $message);
                } catch (\Exception $e) {
                    
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
    
    public function derejeterConsultationAction()
    {
		$this->initialiserControlleur();
		
        $sessionEmploye = new Container('employe');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $info = "";
        $varRetour = "";
        $utilitaire = new Utilitaire();
        $consultation = null;
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner la consultation dans l'url");
        }
        
        if(empty($error))
        {
            $consultation = $this->getEntityManager()->find('Entity\Consultation', $id);
            if (!$consultation) {
                $error = $this->getTranslator("Aucune consultation trouve");
            }
        }
        
        $nouveauEtat = "attente_validation";
        
        if(empty($error))
        {
            if(!$this->consultationManager->vefifierSiConsultationDerejetable($consultation))
            {
                $error = $this->getTranslator("Vous ne pouvez plus derejeter cette consultation");
            }
            
            if(empty($error))
            {
                
                $employe = $this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id"));
                $consultation->setEtatConsultation($nouveauEtat);
                $consultation->setEmployeValideRejete($employe);
                $consultation->setMontantModif(null);
                
                $consultationAudit = new ConsultationAudit();
                $consultationAudit->setConsultation($consultation);
                $consultationAudit->setEmploye($employe);
                $consultationAudit->setEtatConsultation($nouveauEtat);
                $consultationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                
                
                // Debut du mode transactionnel
                $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                try {
                    $this->getEntityManager()->persist($consultationAudit);
                    $this->getEntityManager()->flush();
                    
                    $this->getEntityManager()->getConnection()->commit();
                    
                } catch (\Exception $e) {
                    $this->getEntityManager()->getConnection()->rollback();
                    $this->getEntityManager()->close();
                    $error = $e->getMessage();
                }
                
                
                
                // $consultation->setEtatConsultation("rejete");
                $this->getEntityManager()->flush();
                
                $varRetour = $this->getTranslator("Operation effectuee avec succes");
                
//                 // Envoi du sms
//                 try {
//                     $visite = $consultation->getVisite();
//                     $telephone = $visite->getTelephone();
                    
//                     $message  = $this->getTranslator("Centre hospitalier :")." ".$visite->getPrestataire()->getNom()."\n";
//                     $message .= $this->getTranslator("Votre demande de consultation pour la visite de code")." ".$visite->getCodeCourt()." ".$this->getTranslator("a ete derejetee");
                    
//                     if(!empty($telephone))
//                         $utilitaire->sendSmsHttp($telephone, $message);
//                 } catch (\Exception $e) {
                    
//                 }
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
    
    public function encaisserConsultationAction()
    {
		$this->initialiserControlleur();
		
        $sessionEmploye = new Container('employe');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $info = "";
        $varRetour = "";
        $utilitaire = new Utilitaire();
        $consultation = null;
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner la consultation dans l'url");
        }
        
        if(empty($error))
        {
            $consultation = $this->getEntityManager()->find('Entity\Consultation', $id);
            if (!$consultation) {
                $error = $this->getTranslator("Aucune consultation trouve");
            }
        }
        
        if(empty($error))
        {
            if(!$this->consultationManager->vefifierSiConsultationEncaissable($consultation))
            {
                $error = $this->getTranslator("Vous ne pouvez plus encaisser cette consultation");
            }
            
            
            $nouveauEtat = "encaisse";
            
            if(empty($error))
            {
                $consultation->setEtatConsultation($nouveauEtat);
                
                $consultationAudit = new ConsultationAudit();
                $consultationAudit->setConsultation($consultation);
                $consultationAudit->setEmploye($this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id")));
                $consultationAudit->setEtatConsultation($nouveauEtat);
                $consultationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                
                
                // Debut du mode transactionnel
                $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                try {
                    $this->getEntityManager()->persist($consultationAudit);
                    $this->getEntityManager()->flush();
                    
                    $this->getEntityManager()->getConnection()->commit();
                    
                } catch (\Exception $e) {
                    $this->getEntityManager()->getConnection()->rollback();
                    $this->getEntityManager()->close();
                    $error = $e->getMessage();
                }
                
                
                
                // $consultation->setEtatConsultation("encaisse");
                $this->getEntityManager()->flush();
                
                
                //$tabNumTelephone = array($consultation->getVisite()->getTelephone());

                //$message = $this->getTranslator("Votre consultation a ete prise en compte au centre hospitalier, merci de faire confiance a ZENITHE Insurance");
                
                //if(!empty($tabNumTelephone))
                    //$utilitaire->sendSmsHttp($tabNumTelephone, $message);
                
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
        ));
    }

    public function rechercherVisiteAction()
    {
		$this->initialiserControlleur();
		
        $sessionEmploye = new Container('employe');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $info = "";
        $varRetour = "";
        $consultation = null;
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner le code de la visite");
        }
        
        $idVisiteTrouvee = null;
        
        if(empty($error))
        {
            $anneeUtilisee = date("Y");
            $mois = date("m");
            $idPrestataire = $sessionEmploye->offsetGet("id_prestataire");
            
            $visite = $this->getEntityManager()->find('Entity\Visite', $this->construireCodeCompletVisite($idPrestataire, $anneeUtilisee, $id));
            if(!$visite)
            {
                if($mois == "01")
                {
                    $anneeUtilisee -= 1;
                    $visite = $this->getEntityManager()->find('Entity\Visite', $this->construireCodeCompletVisite($idPrestataire, $anneeUtilisee, $id));
                }
            }
            
            if(!$visite)
            {
                $error = $this->getTranslator("Impossible de trouver la visite, verifiez que vous avez bien saisi le code de la visite");
            }
            else
            {
                $id = $this->construireCodeCompletVisite($idPrestataire, $anneeUtilisee, $id);
                $idVisiteTrouvee = $id;
                
                if($visite->getPrestataire()->getId() != $sessionEmploye->offsetGet("id_prestataire"))
                {
                    $error = $this->getTranslator("Cette visite a ete enregistree par un autre centre hospitalier");
                }
                else
                {
                    $consultation = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $id));
					
                    
                    if($consultation)
                    {
                        $error = $this->getTranslator("Cette visite a deja une consultation");
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
            'idVisiteTrouvee' => $idVisiteTrouvee,
        ));
    }
    
    public function imprimerRecuAction()
    {
		$this->initialiserControlleur();
		
        $sessionEmploye = new Container('employe');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $info = "";
        $varRetour = "";
        $consultation = null;
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner la consultation dans l'url");
        }
        
        if(empty($error))
        {
            $consultation = $this->getEntityManager()->find('Entity\Consultation', $id);
            if (!$consultation) {
                $error = $this->getTranslator("Aucune consultation trouve");
            }
        }
        
        if(empty($error))
        {
            if(!$this->consultationManager->vefifierSiConsultationImprimable($consultation))
            {
                $error = $this->getTranslator("Vous ne pouvez pas imprimer cette consultation car elle doit d'abord etre encaissee");
            }
            
            if(empty($error))
            {
                $consultationAudit = $this->getEntityManager()->getRepository('Entity\ConsultationAudit')->findOneBy(array('consultation' => $id, "etatConsultation" => "encaisse"));
                
                include_once __DIR__.'/../../view/consultation/index/imprimer-recu.phtml';
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
