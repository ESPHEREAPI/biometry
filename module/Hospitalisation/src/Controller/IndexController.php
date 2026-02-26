<?php

namespace Hospitalisation\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Custom\Mvc\Controller\BackOfficeCommonController;

use Application\Core\Utilitaire;
use Application\Filter\Common\TextImputFilterLcl;
use Application\Manager\LignePrestationAuditManager;
use Application\Manager\PrestataireManager;
use Application\Manager\PrestationManager;
use Application\Manager\TypePrestationManager;
use Application\Manager\EmployeManager;
use Application\Manager\MenuManager;
use Entity\LignePrestationAudit;
use Entity\Prestation;
use Entity\TypePrestation;
use Hospitalisation\Form\PrestationForm;
use Hospitalisation\Form\FiltreListePrestationForm;
use Hospitalisation\Form\PrestationInputFilter;
use Hospitalisation\Form\RechercherVisiteForm;
use Hospitalisation\Form\ValiderPrestationForm;
use Hospitalisation\Form\ValiderPrestationInputFilter;
use Application\Filter\Common\DigitImputFilterLcl;
use Entity\LignePrestation;
use Application\Manager\LignePrestationManager;
use Application\Filter\Common\EnumImputFilterLcl;
use Hospitalisation\Form\RechercherVisiteInputFilter;

class IndexController extends BackOfficeCommonController
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $appliContainer;
    
    /**
     * @var \Entity\Prestation
     */
    protected $prestation;
    
    /**
     * @var \Application\Manager\PrestationManager
     */
    protected $prestationManager;
	
	/**
     * @var \Application\Manager\TypePrestationManager
     */
    protected $typePrestationManager;
    
    /**
     * @var \Hospitalisation\Form\PrestationForm
     */
    protected $prestationForm;
    
    /**
     * @var \Hospitalisation\Form\FiltreListePrestationForm
     */
    protected $filtreListePrestationForm;
    
    /**
     * @var \Hospitalisation\Form\PrestationInputFilter
     */
    protected $prestationInputFilter;
    
    /**
     * @var \Hospitalisation\Form\ValiderPrestationForm
     */
    protected $validerPrestationForm;
    
    /**
     * @var \Hospitalisation\Form\ValiderPrestationInputFilter
     */
    protected $validerPrestationInputFilter;
    
    /**
     * @var \Application\Manager\MenuManager
     */
    protected $menuManager;
    
    /**
     * @var \Application\Manager\EmployeManager
     */
    protected $employeManager;
    
    /**
     * @var \Application\Manager\LignePrestationManager
     */
    protected $lignePrestationManager;
    
    /**
     * @var \Application\Manager\PrestataireManager
     */
    protected $prestataireManager;
    
    /**
     * @var \Hospitalisation\Form\RechercherVisiteForm
     */
    protected $rechercherVisiteForm;
    
    /**
     * @var \Hospitalisation\Form\RechercherVisiteInputFilter
     */
    protected $rechercherVisiteInputFilter;
    
    
    /**
     * @var \Application\Manager\LignePrestationAuditManager
     */
    protected $lignePrestationAuditManager;
    
    
    protected $naturePrestation;
    
    protected $appliConfig;
    
    public function __construct(ContainerInterface $appliContainer, Prestation $prestation, PrestationManager $prestationManager, PrestationForm $prestationForm,
                                FiltreListePrestationForm $filtreListePrestationForm, PrestationInputFilter $prestationInputFilter,
                                ValiderPrestationForm $validerPrestationForm, ValiderPrestationInputFilter $validerPrestationInputFilter,
                                MenuManager $menuManager, EmployeManager $employeManager, LignePrestationManager $lignePrestationManager,
                                PrestataireManager $prestataireManager, RechercherVisiteForm $rechercherVisiteForm,
                                RechercherVisiteInputFilter $rechercherVisiteInputFilter, LignePrestationAuditManager $lignePrestationAuditManager,
							   TypePrestationManager $typePrestationManager)
    {
        $appliConfig =  new \Application\Core\AppliConfig();
        $this->appliConfig = $appliConfig;
        
        $this->appliContainer = $appliContainer;
        
        $this->prestation = $prestation;
        $this->prestationManager = $prestationManager;
        $this->prestationForm = $prestationForm;
        $this->filtreListePrestationForm = $filtreListePrestationForm;
        $this->prestationInputFilter = $prestationInputFilter;
        $this->validerPrestationForm = $validerPrestationForm;
        $this->validerPrestationInputFilter = $validerPrestationInputFilter;
        $this->menuManager = $menuManager;
        $this->employeManager = $employeManager;
        $this->lignePrestationManager = $lignePrestationManager;
        $this->prestataireManager = $prestataireManager;
        $this->rechercherVisiteForm = $rechercherVisiteForm;
        $this->rechercherVisiteInputFilter = $rechercherVisiteInputFilter;
        $this->lignePrestationAuditManager = $lignePrestationAuditManager;
		$this->typePrestationManager=$typePrestationManager;
        
        
        $this->initialiserPermission();
    }
    
    public function initialiserControlleur()
    {
        $this->naturePrestation = $this->params()->fromRoute('naturePrestation', null); 
            $this->numeroOrdre = 1;
        
        $this->peuxAjouter = $this->prestationManager->verifierSiPeuxAjouterHospitalisation($this->tabListeMenu, $this->numeroOrdre);
        
        
        
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
	
    public function indexAction ()
    {
        $this->initialiserControlleur();
        
    	$sessionAgence = new Container('agence');
    	$sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
    	
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$cheminPagination = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation."/pagination";
    		 
    	    $this->nomPage = $this->getTranslator("Liste de prestations en hospitalisation");	
    	
    	$boutons = array('btn_ajouter' => array('url' => $this->appliConfig->get('lienBackoffice').'/'.$this->naturePrestation.'/ajouter'),
    					 'btn_activer' => false,
    	                 'btn_desactiver' => false,
		    			 'btn_supprimer' => false,
    	                 'btn_imprimer' => array(
    	                                       'url' => $this->appliConfig->get('lienBackoffice').'/'.$this->naturePrestation.'/imprimer',
    	                                       'nom' => $this->getTranslator("Export simple")),
                	    'btn_imprimer_detaille' => array(
                                    	       'url' => $this->appliConfig->get('lienBackoffice').'/'.$this->naturePrestation.'/imprimer-detaille',
                                    	       'nom' => $this->getTranslator("Export Detaille")),
    	                 'autres_boutons' => array(
    	                     'btn_mettre_prix' => array('nom' => $this->getTranslator("Recherche visite"),
    	                                                'icone' => 'fa-search',
                            	                        'attributes' => array(
                            	                             'id' => 'saisirVisite',
                            	                             'url' => '##',
                            	                            'data-toggle' => 'modal',
                            	                            'data-target' => '#modal-rechercher-visite',
                            	                        )
    	                     ),
    	                 )
    	);
    	
    	$formFiltre = $this->filtreListePrestationForm;
    	
    	// Construction du formulaire
    	$validerPrestationForm = $this->validerPrestationForm;
    	$validerPrestationForm->setAttribute('action', "");
    	$validerPrestationForm->setAttribute('class', "form-horizontal");
    	$validerPrestationForm->setAttribute('role', "form");
    	$validerPrestationForm->setAttribute('id', "modal-form-valider-prestation");
    	$validerPrestationForm->prepare();
    	
    	$simpleFormViewModel = new ViewModel();
    	$simpleFormViewModel->setTemplate('backoffice/simple_form');
    	$simpleFormViewModel->setVariable('form', $validerPrestationForm);
    	$simpleFormViewModel->setVariable('formPosted', false);
    	$simpleFormViewModel->setVariable('urlCancel', "#");
    	$simpleFormViewModel->setVariable('notUsedElt', array());
    	$simpleFormViewModel->setVariable('msgSuccess', "");
    	$simpleFormViewModel->setVariable('msgError', "");
    	$simpleFormViewModel->setVariable('msgWarning', "");
    	$simpleFormViewModel->setVariable('onlyFormElement', true);
    	$simpleFormViewModel->setVariable('customForm', true);
    	
    	$viewRender = $this->appliContainer->get('ViewRenderer');
    	$htmlValiderPrestationForm = $viewRender->render($simpleFormViewModel);
    	
    	
    	
    	
    	
    	// Construction du formulaire
    	$rechercherVisiteForm = $this->rechercherVisiteForm;
    	$rechercherVisiteForm->setAttribute('action', "");
    	$rechercherVisiteForm->setAttribute('class', "form-horizontal");
    	$rechercherVisiteForm->setAttribute('role', "form");
    	$rechercherVisiteForm->setAttribute('id', "formRechercheVisite");
    	$rechercherVisiteForm->prepare();
    	
    	$simpleFormViewModel = new ViewModel();
    	$simpleFormViewModel->setTemplate('backoffice/simple_form');
    	$simpleFormViewModel->setVariable('form', $rechercherVisiteForm);
    	$simpleFormViewModel->setVariable('formPosted', false);
    	$simpleFormViewModel->setVariable('urlCancel', "#");
    	$simpleFormViewModel->setVariable('notUsedElt', array());
    	$simpleFormViewModel->setVariable('msgSuccess', "");
    	$simpleFormViewModel->setVariable('msgError', "");
    	$simpleFormViewModel->setVariable('msgWarning', "");
    	$simpleFormViewModel->setVariable('onlyFormElement', true);
    	$simpleFormViewModel->setVariable('customForm', true);
    	
    	$viewRender = $this->appliContainer->get('ViewRenderer');
    	$htmlRechercherVisiteForm = $viewRender->render($simpleFormViewModel);
    	
    	
    	
    	// var_dump($htmlRechercherVisiteForm); exit;
    	
    	
    	
    	
    	
    	$typeProfil = $sessionEmploye->offsetGet("type_profil");
    	
    	
    	
    	$tabPrestataire = $this->prestataireManager->getListePrestataire(null, "1", "-1", 1, null, false, null, array("CENTRE_HOSPITALIER", "CENTRE_HOSPITALIER_SIMPLE","CENTRE_HOSPITALIER_OPTIQUE","CENTRE_HOSPITALIER_DENTISTE"));
    	
    	// var_dump($tabPrestataire); exit;
    	
    	
    	$this->initBackViewList($boutons, 'Prestation', $formFiltre, $cheminPagination);  	
    	
        return new ViewModel(array(
        	'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
            'htmlValiderPrestationForm' => $htmlValiderPrestationForm,
            'typeProfil' => $typeProfil,
            'naturePrestation' => $this->naturePrestation,
            'tabPrestataire' => $tabPrestataire,
            'htmlRechercherVisiteForm' => $htmlRechercherVisiteForm,
        ));
    }
    
    public function ajouterAction ()
    {
        $this->initialiserControlleur();
		
       
    	$sessionAgence = new Container('agence');
		$sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	 
			$this->nomPage = $this->getTranslator("Ajouter les prestations en hospitalisation");
		
		
		
    	
    	$formPosted = false;
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation;
    	$utilitaire = new Utilitaire();
    	$sessionEmploye = new Container('employe');
    	$prestationManager = $this->prestationManager;
    	
    	$form = $this->prestationForm;
    	
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation."/ajouter");
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    	
    	$this->initBackViewSimpleForm('Prestation', $form, $formPosted, $listEltsUrl, array("submitClose"));
		
		 $listeTypePrestation=$this->typePrestationManager->getListeTypePrestation("1","hospitalisation");	
		
		 
		
		//var_dump($listeTypePrestation);
			//exit();
    	
    	return new ViewModel(array(
    		'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    	    'naturePrestation' => $this->naturePrestation,
    	    'originformAjoutPrestation' => 'ajout',
    	    'idPrestation' => null,
    	    'prestation' => null,
			'listeTypePrestation'=>$listeTypePrestation,
			
    	));
    }
    
    public function enregistrerAction ()
    {
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $formPosted = false;
        $listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation;
        $utilitaire = new Utilitaire();
        $sessionEmploye = new Container('employe');
        $prestationManager = $this->prestationManager;
        $typeProfil = $sessionEmploye->offsetGet("type_profil");
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        
        // $error = "";
        $info = "";
        $varRetour = "";
        $tabError = array();
        
        $form = $this->prestationForm;
        
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
        $id = $this->params()->fromRoute('id', null);
        if($id)
        {
            $prestation = $this->getEntityManager()->find('Entity\Prestation', $id);
        }
        else
        {
            $prestation = null;
        }
        
        
        
        $action = $this->params()->fromRoute('nomAction', null);
        if($action != 'ajout' && $action != 'modif')
        {
            $error = $this->getTranslator("Veuillez specifier si l'action est l'ajout ou la modification");
        }
        
        if($action == 'ajout' && isset($postValues['visite']) && isset($postValues['visteTouvee']))
        {
            $postValues['visite'] = $postValues['visteTouvee'];
        }
        

        $prestationInputFilter = $this->prestationInputFilter;
        
        $form->setInputFilter($prestationInputFilter->getInputFilter());
        $form->getInputFilter()->remove('visite');
		//mbele
        //if($typeProfil == "prestataire")
       // {
            $form->getInputFilter()->remove('prestataire');
        //}
        $form->setData($postValues);
        
        if(!empty($postValues["listeLignePrestationSent"]))
        {
            $tabLignePrestation = @explode(";", $postValues["listeLignePrestationSent"]);
            foreach ($tabLignePrestation as $key => $valueIndex)
            {
                
                
                  
					$tabTpres=array();
					
			        $listeTp=$this->typePrestationManager->getListeTypePrestation("1","hospitalisation");
					
					foreach($listeTp as $value) 
					{ 
					$tabTpres[$value->getId()]= $value->getNom();
					}
					
					$form->add(array(
 		                    'name' =>  "typeExamenTemplate_".$valueIndex,
 		                    'type' => 'Zend\Form\Element\Select',
 		                    'attributes' => array(
 			                'type' => 'select',
 			                'id' =>  "typeExamenTemplate_".$valueIndex,
 			                "class" => "required",
 		                        "styleLigne" => " display: none;",),
 		                    'options' => array(
 			                "label" => $this->getTranslator("Type", "application"),
 			                'empty_option'   => $this->getTranslator('Selectionnez', 'application'),
 		                                      ),
 	                                  ));
					
			$form->get("typeExamenTemplate_".$valueIndex)->setValueOptions($tabTpres);
							
              
				$form->add(array(
                    "name" => "plafondTemplate_".$valueIndex,
                    "attributes" => array(
                        "type" => "text",
                    ),
                ));
				
				$form->add(array(
                    "name" => "observationsTemplate_".$valueIndex,
                    "attributes" => array(
                        "type" => "text",
                    ),
                ));
				
                
               
                	
               
					$tabTp=array();
					
			        $listeTp=$this->typePrestationManager->getListeTypePrestation("1","hospitalisation");
		          
					
					foreach($listeTp as $value) 
					{ 
					$tabTp[]= $value->getId();
					}
                    $prestationInputFilter->getInputFilter()->merge(new EnumImputFilterLcl("typeExamenTemplate_".$valueIndex, true, $tabTp));
				
				 $prestationInputFilter->getInputFilter()->merge(new TextImputFilterLcl("plafondTemplate_".$valueIndex, false, 1, 255));
				
				$prestationInputFilter->getInputFilter()->merge(new TextImputFilterLcl("observationsTemplate_".$valueIndex, false, 2, 255));
				
              
				
            }
        }
        
        // var_dump($postValues); exit;
        
        if(empty($error))
        {
            if($form->isValid())
            {
                $donneesFormulaire = $form->getData();
                
                if($action == 'ajout')
                {
                    $visite = $this->getEntityManager()->find('Entity\Visite', $postValues['visite']);
                }
                else
                {
                    $visite = $prestation->getVisite();
                }
                
                
                if($action == 'ajout')
                {
                    $consultation = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $postValues['visite']));
                }
                else
                {
                    $consultation = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $visite->getId()));
                }
                
                if($action == 'ajout')
                {
                    $oldPrestation = $this->getEntityManager()->getRepository('Entity\Prestation')->findOneBy(array('visite' => $postValues['visite'], 'naturePrestation' => $this->naturePrestation));
                }
                else
                {
                    $oldPrestation = $prestation;
                }
                
                if($action == 'ajout' && !$visite)
                {
                    $form->get("visite")->setMessages(array($this->getTranslator("Verifiez que le code de la visite est correct")));
                    $error = "1234";
                    $tabError = $form->getMessages();
                }
                elseif($action == 'ajout' && !$consultation)
                {
                    $error = $this->getTranslator("Veuillez enregistrer une consultation pour cette visite avant de continuer");
                }
                elseif($action == 'ajout' && $oldPrestation)
                {
                   
								
							$error = $this->getTranslator("Vous avez deja enregistre des prestations en hospitalisation pour cette visite");		
                  
                }
                elseif($action == 'modif' && !$oldPrestation)
                {
                 		
						$error = $this->getTranslator("Impossible de trouver ces prestations en hospitalisation");				  
                }
                else
                {
                    if($action == 'ajout')
                    {
                        $prestation = $this->prestation;
                        $prestation->exchangeArray($donneesFormulaire);
                        $prestation->setVisite($visite);
                        $prestation->setDate(new \DateTime(date("Y-m-d H:i:s")));
                        $prestation->setNaturePrestation($this->naturePrestation);
                        
                        $prestation->setPrestataire($this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")));
                    } 
                    
                    // Debut du mode transactionnel
                    $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                    try {
                        
                        // Enregistrement dans la table prestation
                        $prestation->nettoyageChaine();
                        
                        
                        if($action == 'ajout')
                        {
                            $this->getEntityManager()->persist($prestation);
                        }   
                        
                        $consultation->setNatureAffection($donneesFormulaire['natureAffection']);
                        
                        
                        $this->getEntityManager()->flush();
                        
                       if(!empty($postValues["listeLignePrestationSent"]))
                         {
                            $tabLignePrestation = @explode(";", $postValues["listeLignePrestationSent"]);
                            foreach ($tabLignePrestation as $key => $valueIndex)
                            {
                                if(isset($donneesFormulaire["plafondTemplate_".$valueIndex]) && !empty($donneesFormulaire["typeExamenTemplate_".$valueIndex]) && isset($donneesFormulaire["observationsTemplate_".$valueIndex]))
								  
                                {
                                    
                                    if($action == 'modif' && is_numeric($valueIndex))
                                    {
                                        $nouveauElement = false;
                                        $lignePrestation = $this->getEntityManager()->find('Entity\LignePrestation', $valueIndex);
                                    }
                                    else
                                    {
                                        $nouveauElement = true;
                                        $lignePrestation = new LignePrestation();
                                    }
                                    
                                    
                                    
                                    if($nouveauElement)
                                    {
                                        $lignePrestation->setPrestation($prestation);
                                    } 
                                    
                                   
                                   
									if(!empty($donneesFormulaire["plafondTemplate_".$valueIndex]))
									{
									 $lignePrestation->setPosologie($donneesFormulaire["plafondTemplate_".$valueIndex]);
									}
									else
									{
										$lignePrestation->setPosologie(null);
									}
                                     
                                     $lignePrestation->setTypeExamen($this->getEntityManager()->find('Entity\TypePrestation', $donneesFormulaire["typeExamenTemplate_".$valueIndex]));
								
									if(!empty($donneesFormulaire["observationsTemplate_".$valueIndex]))
									{
									$lignePrestation->setObservations($donneesFormulaire["observationsTemplate_".$valueIndex]);
									}
									else
									{
										$lignePrestation->setObservations(null);
									}
									//$lignePrestation->setPrestataire($this->getEntityManager()->find('Entity\Prestataire',$sessionEmploye->offsetGet("id_prestataire")));
									
									$lignePrestation->setEtat("valide");
									
                                 
                                    
                                    if($nouveauElement)
                                    {
                                        $lignePrestation->setDate(new \DateTime(date("Y-m-d H:i:s")));
                                        $this->getEntityManager()->persist($lignePrestation);
                                        
                                        
                                        $lignePrestationAudit = new LignePrestationAudit();
                                        $lignePrestationAudit->setLignePrestation($lignePrestation);
                                        $lignePrestationAudit->setEmploye($this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id")));
                                        $lignePrestationAudit->setEtatLignePrestation("enregistre");
                                        $lignePrestationAudit->setDate(new \DateTime(date("Y-m-d H:i:s")));
                                        
                                        $this->getEntityManager()->persist($lignePrestationAudit);
                                        $this->getEntityManager()->flush();
                                    }
                                    
                                    $this->getEntityManager()->flush();
									
                                }
                                else
                                {
                                    $error = "1234";
                                    $tabError = $form->getMessages();
                                }
                            }
						   
						   // Envoi du sms
                        $telephone = $consultation->getVisite()->getTelephone();
									
									
                        $message = $this->getTranslator("Prestataire :")." ".$this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire"))->getNom()."\n";
									
                       
                        
                        		
						    $message .= $this->getTranslator("Hospitalisation validee pour la visite")." ".$consultation->getVisite()->getCodeCourt();
                       
						
                        
                        if(!empty($telephone))
                            $utilitaire->sendSmsHttp($telephone, $message);
						   
                        }  
					elseif(empty($postValues["listeLignePrestationSent"]) && $action=="ajout")
					{
						$tabTp=array();
					
			        $listeTp=$this->typePrestationManager->getListeTypePrestation("1","hospitalisation");
						   foreach($listeTp as $value)
						   { 
							   
							   //$nouveauElement = true;
                                        $lignePrestation = new LignePrestation();
               
                                        $lignePrestation->setPrestation($prestation);
                                    
                                        $lignePrestation->setValeurModif(0000);
						                $lignePrestation->setNom($value->getNom());
                                                 
                                        $lignePrestation->setTypeExamen($this->getEntityManager()->find('Entity\TypePrestation', $value->getId()));
							            
							             //$lignePrestation->setObservations("OK");
							   
							          $lignePrestation->setPrestataire($this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")));
                                                                                                          
                                        $lignePrestation->setDate(new \DateTime(date("Y-m-d H:i:s")));
							           
							            $lignePrestation->setEtat("enregistre");
							   
                                        $this->getEntityManager()->persist($lignePrestation);
                                        
                                        $this->getEntityManager()->flush();
					       }
						
						
						// Envoi du sms
                        $tabEmployeServiceSante = $this->employeManager->getListeEmploye("1", null, null, "-1", 1, null, false, 'SERVICE_SANTE');
                        $tabNumTelephone = array();
                        foreach ($tabEmployeServiceSante as $unEmployeServiceSante)
                        {
                            $telephone = $unEmployeServiceSante->getUtilisateur()->getTelephone();
                            if(!empty($telephone))
                                $tabNumTelephone[] = $telephone;
                        }
                        
                        
                        $message = $this->getTranslator("Prestataire :")." ".$this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire"))->getNom()."\n";
                        
						
							
							$message .= $this->getTranslator("Un patient est en hospitalisation pour la visite")." ".$consultation->getVisite()->getCodeCourt();	
	
                        
                        if(!empty($tabNumTelephone))
                            $utilitaire->sendSmsHttp($tabNumTelephone, $message);
                            
                                 
					}
                        
                        $this->getEntityManager()->getConnection()->commit();
						
						
                        
                    } catch (\Exception $e) {
                        $this->getEntityManager()->getConnection()->rollback();
                        $this->getEntityManager()->close();
                        $error = $e->getMessage();
                    }
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
    
    public function modifierAction ()
    {
        $this->initialiserControlleur();
        
        $appliConfig =  new \Application\Core\AppliConfig();
    	$sessionAgence = new Container('agence');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation;
    	$sessionEmploye = new Container('employe');
    	$utilitaire = new Utilitaire();
    	$typeProfil = $sessionEmploye->offsetGet("type_profil");
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
    	
    	
			$this->nomPage = $this->getTranslator("Modifier les prestations en hospitalisation");	
			 
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
    	
//     	if($typeProfil == "prestataire" && $prestation->getVisite()->getPrestataire()->getId() != $sessionEmploye->offsetGet("id_prestataire"))
//     	{
//     	    return $this->redirect()->toUrl($basePath."/".$appliConfig->get("lienBackoffice")."/acces-refuse");
//     	}
    	
    	
    	if(!$this->prestationManager->vefifierSiHospitalisationModifiable($prestation))
    	{
    	    return $this->redirect()->toUrl($basePath."/".$appliConfig->get("lienBackoffice")."/acces-refuse");
    	}
    	
    	
    	$visite = $consultation->getVisite();
    	
    	
    	$tabObjetLignePrestation = $this->getEntityManager()->getRepository('Entity\LignePrestation')->findBy(array('prestation' => $prestation->getId(), 'supprime' => '-1'));    	
    	$tabLignePrestation = array();
    	foreach ($tabObjetLignePrestation as $unObjetLignePrestation)
    	{
	        $unObjetLignePrestation->afficheChaine();
	        
	        $idTypeExamen = "";
	        $typeExamen = $unObjetLignePrestation->getTypeExamen();
	        if($typeExamen)
	        {
	            $idTypeExamen = $typeExamen->getId();
	        }
			
	        
	        $tabLignePrestation[] = array("id" => $unObjetLignePrestation->getId(),
	            "plafond" => $unObjetLignePrestation->getPosologie(),
	            "prestation" => $unObjetLignePrestation->getPrestation()->getId(),
	            "typeExamen" => $idTypeExamen,
				"observations" => $unObjetLignePrestation->getObservations(),
	            "modifiable" => $this->lignePrestationManager->vefifierSiLigneHospitalisationModifiable($unObjetLignePrestation),
	        );
    	}
    	
    	
		// var_dump($tabLignePrestation); exit;
    	
    	
    	
    	$consultation->afficheChaine();
    	$prestation->afficheChaine();
    	

    	$formPosted = false;

    	$request = $this->getRequest();
    	
    	$form = $this->prestationForm;
    	if($typeProfil == "admin")
    	{
    	    $form->get("prestataire")->setValue($prestation->getVisite()->getPrestataire()->getId());
    	}
    	
    	
    	$form->setData($prestation->getArrayCopy());
    	$form->get("natureAffection")->setValue($consultation->getNatureAffection());
    	
    	
//     	if(!$this->prestationManager->vefifierSiPrestationModifiable($prestation))
//     	{
//     	    return $this->redirect()->toUrl($basePath."/".$appliConfig->get("lienBackoffice")."/acces-refuse");
//     	}
    	
    	
    	if($request->isPost())
    	{
    		$postData = $request->getPost();
    		$formPosted = true;
    		$prestationInputFilter = $this->prestationInputFilter;
    		
    		$form->setInputFilter($prestationInputFilter->getInputFilter());
    		$form->setData($postData);
    		
    		
    		$form->setValidationGroup("typePrestation", "montant");
    		if ($form->isValid())
    		{
    			$donneesFormulaire = $form->getData();
    			
    			$prestation->exchangeArray($donneesFormulaire);
    			
    			// Debut du mode transactionnel
    			$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
    			try {
    					
    				$prestation->nettoyageChaine();
    				$this->getEntityManager()->flush(); 				
    				
    				
    				$this->getEntityManager()->getConnection()->commit();
    				
    				// Redirection dans la la liste des employes
    				if(isset($request->getPost()->submitClose))
    				{
    				    // Redirection dans la la liste des prestations
    				    return $this->redirect()->toRoute("prestation");
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
    	// On prepare l'affichage du formulaire
    	$form->setAttribute('action', $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation."/modifier/".$id);
    	$form->setAttribute('class', "form-horizontal");
    	$form->setAttribute('role', "form");
    	$form->setAttribute('id', "form_ajout");
    	$form->prepare();
    		
			
				$this->nomPage = $this->getTranslator("Modifier les prestations en hospitalisation");	
    	
    	
    	
    	$this->initBackViewSimpleForm('Prestation', $form, $formPosted, $listEltsUrl, array(), $msgSuccess);
		
		
		$listeTypePrestation=$this->typePrestationManager->getListeTypePrestation("1","hospitalisation");	
		
    	 
    	return new ViewModel(array(
    		'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    		'idPrestation' => $prestation->getId(),
    	    'naturePrestation' => $this->naturePrestation,
    	    'originformAjoutPrestation' => 'modif',
    	    'tabLignePrestation' => $tabLignePrestation,
    	    'prestation' => $prestation,
    	    'idPrestation' => $prestation->getId(),
    	    'infosMalade' => $this->construireInfosMalade($visite),
			'listeTypePrestation'=>$listeTypePrestation,
    	));
    }
    
    public function paginationAction()
    {
        $error = $this->initialiserControlleur();
        
    	$sessionAgence = new Container('agence');
    	$sessionEmploye = new Container('employe');
    	$prestationManager = $this->prestationManager;
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
    	
    	
    	if(is_array($postValues) && isset($postValues['prestataire'])
    	   && isset($postValues['dateMin']) && isset($postValues['dateMax'])
    	   && isset($postValues['nomAdherent']) && isset($postValues['nomAyantDroit'])
    	   // && isset($postValues['idVisite'])
    	   && isset($postValues['nomColoneTriPagination']) && isset($postValues['typeTriColonePagination'])
    	   && isset($postValues['nbreMaxLigneTableau']))
    	{
    		$supprime = -1;
    		if($codeProfil == "SUP_ADMIN") // Le super administrateur voit tout
    		{
    		    $supprime = null;
    		}
    		
    		$typeProfil = $sessionEmploye->offsetGet("type_profil");
			$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
    		$prestataireId = trim($postValues['prestataire']);
    		if($typeProfil == "prestataire")
    		{
    		    $prestataireId = $sessionEmploye->offsetGet("id_prestataire");
    		}
    		
    		$tabParams = array("prestataire" => $prestataireId,
    		                   "naturePrestation" => $this->naturePrestation,
                    		   "dateMin" => trim($postValues['dateMin']),
                    		   "dateMax" => trim($postValues['dateMax']),
    		    
                    		   "nomAdherent" => trim($postValues['nomAdherent']),
                    		   "nomAyantDroit" => trim($postValues['nomAyantDroit']),
                    		   // "visite" => trim($postValues['idVisite']),
    		    
    		                   "supprime" => $supprime,
    		                   "pagination" => true,
    		                   "nroPage" => $numActuel,
    		                   "nbreMax"  => $postValues['nbreMaxLigneTableau'],
    		                   "orderBy" => array($postValues['nomColoneTriPagination'] => $postValues['typeTriColonePagination']),
    		                  );
    		
    		
    		$retourPagination = $this->prestationManager->getListePrestationTabParams($tabParams);
    		
    		$tab = $retourPagination['tab'];
    		$totalResult = $retourPagination['totalResult'];

    		if(is_array($tab) && count($tab) > 0 && $totalResult > 0)
    		{
    			$nroPage = $numActuel;
    			$nbrePages = $prestationManager::NBRE_PAGE_PAGINATION;
    			$nbreResults = $totalResult;
    			$nbreMaxResultsParPage = $postValues['nbreMaxLigneTableau'];
    			$cheminControlleur = $basePath.'/';
    			$parametres = "";
    		
    			include_once __DIR__.'/../../../Application/view/partial/pagination.phtml';
    			 
    			 
    			if(isset($headPagination) && is_string($headPagination)) $varRetour .= $headPagination;
    			include_once __DIR__.'/../../view/hospitalisation/index/pagination.phtml';
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
    	        'naturePrestation' => $this->naturePrestation,
    	));
    }

    public function rechercherVisiteAction()
    {
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $sessionEmploye = new Container('employe');
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $info = "";
        $varRetour = "";
        $prestation = null;
        
        $typeProfil = $sessionEmploye->offsetGet("type_profil");
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );
        
        
//         if($typeProfil == "prestataire")
//         {
//             $postValues['prestataire'] = $sessionEmploye->offsetGet("id_prestataire");
//         }
        
        $id = $this->params()->fromRoute('id', null);
        if (!$id) {
            $error = $this->getTranslator("Veuillez renseigner le code de la visite");
        }
        
        $idVisiteTrouvee = null;
        
        if(empty($error))
        {
            // $visite = $this->getEntityManager()->find('Entity\Visite', $id);
            $anneeUtilisee = date("Y");
            $mois = date("m");
            $idPrestataire = $postValues['prestataire'];
            
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
            elseif($typeProfil == "prestataire" && $visite->getPrestataire()->getId() != $postValues['prestataire'])
            {
                $error = $this->getTranslator("Cette visite a ete enregistree par un autre centre hospitalier");
            }
            else
            {
                $id = $this->construireCodeCompletVisite($idPrestataire, $anneeUtilisee, $id);
                $idVisiteTrouvee = $id;
                
                $consultation = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $id));
                if(!$consultation)
                {
                    
			
						$error = $this->getTranslator("Veuillez enregistrer la consultation pour cette visite avant d'enregistrer les prestations en hospitalisation");	
                }
                elseif($consultation->getEtatConsultation() != "valide" && $consultation->getEtatConsultation() != "encaisse")
                {
                    
						
							$error = $this->getTranslator("Cette consultation n'a pas encore ete validee, vous ne pouvez pas enregistrer des prestations en hospitalisation pour celle-ci");
							
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
                        $consultation->afficheChaine();
                        
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
                                           "souscripteur" => $adherent->getSouscripteur(),
                                           "natureAffection" => $consultation->getNatureAffection(),
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
            'idVisiteTrouvee' => $idVisiteTrouvee,
        ));
    }
    
    public function rechercheVisitePourMettrePrixAction()
    {
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $error = "";
        $tabError = array();
        $info = "";
        $varRetour = "";
        $prestation = null;
        $prestationId = "";
        
        $postValues = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray()
            );

        $form = $this->rechercherVisiteForm;
        
        $form->setInputFilter($this->rechercherVisiteInputFilter->getInputFilter());
        $form->setData($postValues);
        
        
        if($form->isValid())
        {
            $donneesFormulaire = $form->getData();
            
            // $visite = $this->getEntityManager()->find('Entity\Visite', $postValues['visite']);
            $anneeUtilisee = date("Y");
            $mois = date("m");
            $idPrestataire = $donneesFormulaire['prestataireRechercheVisite'];
            
            $visite = $this->getEntityManager()->find('Entity\Visite', $this->construireCodeCompletVisite($idPrestataire, $anneeUtilisee, $donneesFormulaire['visite']));
            if(!$visite)
            {
                if($mois == "01")
                {
                    $anneeUtilisee -= 1;
                    $visite = $this->getEntityManager()->find('Entity\Visite', $this->construireCodeCompletVisite($idPrestataire, $anneeUtilisee, $donneesFormulaire['visite']));
                }
            }
            
            if(!$visite)
            {
                $error = $this->getTranslator("Impossible de trouver la visite, verifiez que vous avez bien saisi le code de la visite");
            }
            else
            {
                $prestation = $this->getEntityManager()->getRepository('Entity\Prestation')->findOneBy(array('visite' => $visite->getId(), 'naturePrestation' => $this->naturePrestation));
                if(!$prestation)
                {
                    
							 $error = $this->getTranslator("Aucune prestation en hospitalisation n'a ete enregistre pour cette visite");
						
                }
                else
                {
                    $prestationId = $prestation->getId();
                }
            }
        }
        else
        {
            $error = "1234";
            $tabError = $form->getMessages();
        }
        
        return new JsonModel(array(
            'error' => $error,
            'tabError' => $tabError,
            'info' => $info,
            'varRetour' => $varRetour,
            'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
            'naturePrestation' => $this->naturePrestation,
            "prestationId" => $prestationId,
        ));
    }
    
    public function imprimerRecuAction()
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
            $error = $this->getTranslator("Veuillez renseigner la prestation dans l'url");
        }
        
        if(empty($error))
        {
            $prestation = $this->getEntityManager()->find('Entity\Prestation', $id);
            if (!$prestation) {
                $error = $this->getTranslator("Aucun prestation trouve");
            }
        }
        
        if(empty($error))
        {
			
            if(!$this->prestationManager->vefifierSiHospitalisationImprimable($prestation, $sessionEmploye->offsetGet("id_prestataire")))
            {
                $error = $this->getTranslator("Vous ne pouvez pas imprimer cette hospitalisation");
            }
			//else
			//{
			//$error = $this->getTranslator("Vous n'etes pas habilete a imprimer cette hospitalisation");	
			//}
				
			
			
				
            
            if(empty($error))
            {
                //$prestationAudit = $this->getEntityManager()->getRepository('Entity\LignePrestationAudit')->findOneBy(array('prestation' => $id, "etatPrestation" => "encaisse"));
                
                
                $tabParams = array("prestation" => $id,
                                    "etat" => 'valide',
                                    "nbreMax" => "1",
                                    "orderBy" => array("date" => "DESC")
                                   );
                
                
                $tab = $this->lignePrestationAuditManager->getListeLignePrestationAuditTabParams($tabParams);
                (is_array($tab) && count($tab) > 0) ? $lignePrestationAudit = $tab[0] : $lignePrestationAudit = null;
                
                
                include_once __DIR__.'/../../view/hospitalisation/index/imprimer-recu.phtml';
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
    
    public function imprimerPdfAction()
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
        
        $postValues = $this->getRequest()->getPost();
        $postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
        $postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
        
        
        if(is_array($postValues) && isset($postValues['prestataire'])
            && isset($postValues['dateMin']) && isset($postValues['dateMax'])
            
            && isset($postValues['nomAdherent']) && isset($postValues['nomAyantDroit'])
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
            
//             $tabParams = array("prestataire" => $prestataireId,
//                 "dateMin" => trim($postValues['dateMin']),
//                 "dateMax" => trim($postValues['dateMax']),
//                 "supprime" => $supprime,
//                 "pagination" => false,
//                 "etat" => "encaisse",
//                 "naturePrestation" => $this->naturePrestation,
//                 "orderBy" => array("prestation" => "DESC"),
//             );

            $tabParams = array("prestataire" => $prestataireId,
                "naturePrestation" => $this->naturePrestation,
                "dateMin" => trim($postValues['dateMin']),
                "dateMax" => trim($postValues['dateMax']),
                
                "nomAdherent" => trim($postValues['nomAdherent']),
                "nomAyantDroit" => trim($postValues['nomAyantDroit']),
                // "visite" => trim($postValues['idVisite']),
                
                "supprime" => $supprime,
                "pagination" => false,
                "orderBy" => array("date" => "DESC"),
            );
            
            
            $tab = $this->prestationManager->getListePrestationTabParams($tabParams);
            if(is_array($tab) && count($tab) > 0)
            {
                include_once __DIR__.'/../../view/hospitalisation/index/imprimer-pdf.phtml';
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
    
    
    public function imprimerDetaillePdfAction()
    {
        $error = $this->initialiserControlleur();
        
        $sessionAgence = new Container('agence');
        $sessionEmploye = new Container('employe');
        $lignePrestationManager = $this->lignePrestationManager;
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $codeProfil = $sessionEmploye->offsetGet("code_profil");
        $error = "";
        $info = "";
        $varRetour = "";
        
        $postValues = $this->getRequest()->getPost();
        $postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
        $postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
        
        
        if(is_array($postValues) && isset($postValues['prestataire'])
            && isset($postValues['dateMin']) && isset($postValues['dateMax'])
           
            && isset($postValues['nomAdherent']) && isset($postValues['nomAyantDroit'])
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
            
            //             $tabParams = array("prestataire" => $prestataireId,
            //                 "dateMin" => trim($postValues['dateMin']),
            //                 "dateMax" => trim($postValues['dateMax']),
            //                 "supprime" => $supprime,
            //                 "pagination" => false,
            //                 "etat" => "encaisse",
            //                 "naturePrestation" => $this->naturePrestation,
            //                 "orderBy" => array("prestation" => "DESC"),
            //             );
            
            $tabParams = array("prestataire" => $prestataireId,
                "naturePrestation" => $this->naturePrestation,
                "dateMin" => trim($postValues['dateMin']),
                "dateMax" => trim($postValues['dateMax']),
           
                "nomAdherent" => trim($postValues['nomAdherent']),
                "nomAyantDroit" => trim($postValues['nomAyantDroit']),
                // "visite" => trim($postValues['idVisite']),
                "etat"=>"valide",
                "supprime" => $supprime,
                "pagination" => false,
                "orderBy" => array("date" => "DESC"),
            );
            
            
            // $tab = $this->prestationManager->getListePrestationTabParams($tabParams);
            $tab = $this->lignePrestationManager->getListeLignePrestationTabParams($tabParams);
            if(is_array($tab) && count($tab) > 0)
            {
                include_once __DIR__.'/../../view/hospitalisation/index/imprimer-detaille-pdf.phtml';
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
        $error = $this->initialiserControlleur();
        
        $appliConfig =  new \Application\Core\AppliConfig();
        $sessionAgence = new Container('agence');
        $sessionEmploye = new Container('employe');
        $lignePrestationManager = $this->lignePrestationManager;
        $basePath = $this->appliContainer->get('Request')->getBasePath();
        $codeProfil = $sessionEmploye->offsetGet("code_profil");
        $error = "";
        $info = "";
        $varRetour = "";
        
        $postValues = $this->getRequest()->getPost();
        $postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
        $postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
        
        
        if(is_array($_GET) && isset($_GET['prestataire']) && isset($_GET['typeLignePrestation']) && isset($_GET['etatLignePrestation'])
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
                "typeLignePrestation" => trim($_GET['typeLignePrestation']),
                "etatLignePrestation" => trim($_GET['etatLignePrestation']),
                "dateMin" => trim($_GET['dateMin']),
                "dateMax" => trim($_GET['dateMax']),
                
                "nomAdherent" => trim($_GET['nomAdherent']),
                "nomAyantDroit" => trim($_GET['nomAyantDroit']),
                // "visite" => trim($_GET['idVisite']),
                
                "supprime" => $supprime,
                "pagination" => false,
                "orderBy" => array($_GET['nomColoneTriPagination'] => $_GET['typeTriColonePagination']),
            );
            
            
            $tab = $this->lignePrestationManager->getListeLignePrestationTabParams($tabParams);
            if(is_array($tab) && count($tab) > 0)
            {
                $properties = array("creator" => "MSBT",
                    "lastModifiedBy" => "System");
                
                
                $titresColones = array();
                if($typeProfil == "admin")
                {
                    $titresColones[] = array("titre" => $this->getTranslator("Prestataire"), "largeur" => 20);
                }
                
                $titresColonesAutres = array(array("titre" => $this->getTranslator("Type consultation"), "largeur" => 20),
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
                    
                    switch ($element->getEtatLignePrestation()) {
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
                    if($element->getEtatLignePrestation() == "valide" || $element->getEtatLignePrestation() == "encaisse")
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
                    
                    $datasHereAutres = array($element->getTypeLignePrestation()->getNom(), $natureAffection, $element->getDate()->format("d/m/Y H:i"),
                        number_format($element->getMontant(), 0, ",", " "), $montantModif,
                        $montantAssure, $titleImgEtat);
                    
                    $datasHere = array_merge($datasHere, $datasHereAutres);
                    
                    $datas[] = $datasHere;
                }
                
                $msbtExcel = new \Application\Core\MsbtExcel($properties, $titresColones, $datas, $this->getTranslator("Liste des lignePrestations"));
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
}
