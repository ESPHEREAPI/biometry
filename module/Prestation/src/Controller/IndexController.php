<?php

namespace Prestation\Controller;

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
use Entity\Consultation;
use Entity\Medicament;
use Entity\ConsultationAudit;
use Entity\TypePrestation;
use Prestation\Form\PrestationForm;
use Prestation\Form\FiltreListePrestationForm;
use Prestation\Form\PrestationInputFilter;
use Prestation\Form\RechercherVisiteForm;
use Prestation\Form\ValiderPrestationForm;
use Prestation\Form\ValiderPrestationInputFilter;
use Application\Filter\Common\DigitImputFilterLcl;
use Entity\LignePrestation;
use Application\Manager\LignePrestationManager;
use Application\Filter\Common\EnumImputFilterLcl;
use Prestation\Form\RechercherVisiteInputFilter;

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
     * @var \Entity\Consultation
     */
    protected $consultation;
	
	/**
     * @var \Entity\Medicament
     */
    protected $medicament;
    
    /**
     * @var \Application\Manager\PrestationManager
     */
    protected $prestationManager;
	
	/**
     * @var \Application\Manager\TypePrestationManager
     */
    protected $typePrestationManager;
    
    /**
     * @var \Prestation\Form\PrestationForm
     */
    protected $prestationForm;
    
    /**
     * @var \Prestation\Form\FiltreListePrestationForm
     */
    protected $filtreListePrestationForm;
    
    /**
     * @var \Prestation\Form\PrestationInputFilter
     */
    protected $prestationInputFilter;
    
    /**
     * @var \Prestation\Form\ValiderPrestationForm
     */
    protected $validerPrestationForm;
    
    /**
     * @var \Prestation\Form\ValiderPrestationInputFilter
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
     * @var \Prestation\Form\RechercherVisiteForm
     */
    protected $rechercherVisiteForm;
    
    /**
     * @var \Prestation\Form\RechercherVisiteInputFilter
     */
    protected $rechercherVisiteInputFilter;
    
    
    /**
     * @var \Application\Manager\LignePrestationAuditManager
     */
    protected $lignePrestationAuditManager;
    
    protected $tabMedicament = [];

    protected $naturePrestation;
    
    protected $appliConfig;
    
    public function __construct(ContainerInterface $appliContainer, Prestation $prestation,Consultation $consultation,Medicament $medicament, PrestationManager $prestationManager, PrestationForm $prestationForm,
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
		 $this->consultation=$consultation;
		 $this->medicament=$medicament;
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
        
        //$this->initialiserPermission();
    }
    
    public function initialiserControlleur()
    {   
	    $sessionEmploye = new Container('employe');
        $this->naturePrestation = $this->params()->fromRoute('naturePrestation', null);
		 $this->prestationAjoutId = null;
		 $prestataire=$this->getEntityManager()->find('Entity\Prestataire',$sessionEmploye->offsetGet("id_prestataire"));
        if($this->naturePrestation == "ordonnance")
        {
            $this->numeroOrdre = 1;


            $this->tabMedicament = $this->getEntityManager()->getRepository('Entity\Medicament')->findBy(array('categorie'=>"1", 'statut' => "1", 'supprime' => '-1'));
        }
        elseif($this->naturePrestation == "examen")
        {
            $this->numeroOrdre = 2;
			 $this->tabMedicament = $this->getEntityManager()->getRepository('Entity\Medicament')->findBy(array('prestataire' => $prestataire,
			                                                                                                    'categorie'=>"2", 
																												'statut' => "1", 
																												'supprime' => '-1'));
        }
		elseif($this->naturePrestation == "lunetterie")
        {
            $this->numeroOrdre = 3;
        }
        
        $this->peuxAjouter = $this->prestationManager->verifierSiPeuxAjouterPrestation($this->tabListeMenu, $this->numeroOrdre);
        
        
        
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
        $this->initialiserPermission(); 
                $error = $this->initialiserControlleur();
        
    	$sessionAgence = new Container('agence');
    	$sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
    	
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$cheminPagination = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation."/pagination";
    	if($this->naturePrestation == "ordonnance")
    	{
    	    $this->nomPage = $this->getTranslator("Liste des ordonnances");
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
		elseif( $this->naturePrestation == "lunetterie")
    	{
    	    $this->nomPage = $this->getTranslator("Liste des prestations en lunetterie");
			
    	}
		
    	
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
    	                    /*  'btn_mettre_prix' => array('nom' => $this->getTranslator("Recherche visite"),
    	                                                'icone' => 'fa-search',
                            	                        'attributes' => array(
                            	                             'id' => 'saisirVisite',
                            	                             'url' => '##',
                            	                            'data-toggle' => 'modal',
                            	                            'data-target' => '#modal-rechercher-visite',
                            	                        )
    	                     ), */
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
	     $idVisite = $this->params()->fromRoute('idVisite', null);
		  $codeVisite="";
		 $prestat="";
		
		$utilitaire = new Utilitaire();
		$sessionEmploye = new Container('employe');
	
		$idVisite = $this->params()->fromRoute('idVisite', null);
		//var_dump($idVisite); exit;
		$visite = null;
		$codeCourtVisite = "";
		$listeTypePrestation=[];
		
		
		
		if($idVisite)
		{
			unset($_SESSION['employe'], $_SESSION['agence']);
			
			
			$visite = $this->getEntityManager()->find('Entity\Visite', $idVisite);
			
			if($visite)
			{
				$codeCourtVisite = $visite->getCodeCourt();
				$codeVisite = $visite->getId();
				$prestat= $visite->getPrestataire()->getId();
				$employeConnect = $visite->getEmploye();
				$this->connecterUtilisateur($employeConnect);
		 
	            $this->initialiserPermission(); 
                $error = $this->initialiserControlleur();

                
				
			}
      			////////////////
				////////////////
				$consultationOld = $this->getEntityManager()->getRepository('Entity\Consultation')->findOneBy(array('visite' => $visite->getId()));
				
                if($consultationOld)
                {
                    $error = $this->getTranslator("Cette visite a deja une consultation");
                }
                else
                {
					//var_dump($visite); exit;
                        $etatConsultation = "encaisse";
                   
                    
                    $consultation = $this->consultation;
                    //$consultation->exchangeArray($donneesFormulaire,null, false, array("typeConsultation"));
                    $consultation->setVisite($visite);
                    $consultation->setTypeConsultation($this->getEntityManager()->find('Entity\TypePrestation', "CS0"));
					 $consultation->setNatureConsultation("gratuite");
                    $consultation->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                    $consultation->setEtatConsultation($etatConsultation);
                   
                        $consultation->setMontantModif(0);
                   
                        $consultation->setMontant(0);
                 
                    
                    // Debut du mode transactionnel
                    $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                    try {
                        
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
                        
                        $this->getEntityManager()->getConnection()->commit();
                        
                        
                                                   
                    } catch (\Exception $e) {
                        $this->getEntityManager()->getConnection()->rollback();
                        $this->getEntityManager()->close();
                        $error = $e->getMessage();
						
                    }
                }
            
				////////////////
				////////////////
			
		}
		else
		{			 
	     $this->initialiserPermission(); 
         $error = $this->initialiserControlleur();
		}
		
        
	
       
    	$sessionAgence = new Container('agence');
		$sessionEmploye = new Container('employe');
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	if($this->naturePrestation == "ordonnance")
    	{
			$listeTypePrestation=[];
    	    $this->nomPage = $this->getTranslator("Ajouter une ordonnance");
    	}
    	 elseif($this->naturePrestation == "examen")
    	{
			if($typeSousProfil=="laboratoire")
		    {
    	    $this->nomPage = $this->getTranslator("Ajouter les examens");
			}
			else
			{
				$this->nomPage = $this->getTranslator("Ajouter les examens/actes");
			}
    	}
		elseif( $this->naturePrestation == "lunetterie")
		{
			$this->nomPage = $this->getTranslator("Ajouter les prestations en lunetterie");
		}
		
    	
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
		
		if($this->naturePrestation == "examen")
         {
		     if($typeSousProfil=="laboratoire")
			 {
		     	$listeTypePrestation=$this->typePrestationManager->getListeTypePrestation("1","examens");
		     }
		     else
			 {
		     	$listeTypePrestation=$this->typePrestationManager->getListeTypePrestation("1","examens","actes");
		     }
		 }
		 elseif($this->naturePrestation == "lunetterie")
		 {
		    $listeTypePrestation=$this->typePrestationManager->getListeTypePrestation("1","lunetteries");	
		 }
		 
		
		//var_dump($listeTypePrestation);
			//exit();
    	
    	return new ViewModel(array(
    		'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    	    'naturePrestation' => $this->naturePrestation,
    	    'originformAjoutPrestation' => 'ajout',
    	    'idPrestation' => null,
    	    'prestation' => null,
            'listeTypePrestation' => $listeTypePrestation,
            'tabMedicament' => $this->tabMedicament,
			'visite' => $visite,
			//'origin' => 'ajout',
			'codeCourtVisite' => $codeCourtVisite,
			'codeVisite' => $codeVisite,
			'prestat' => $prestat,
    	));
    }
    
    public function enregistrerAction ()
    {
        $this->initialiserPermission(); 
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
        if($typeProfil == "prestataire")
        {
            $form->getInputFilter()->remove('prestataire');
        }
        $form->setData($postValues);
		
		//var_dump($postValues);exit;
        
        if(!empty($postValues["listeLignePrestationSent"]))
        {
            $tabLignePrestation = @explode(";", $postValues["listeLignePrestationSent"]);
            foreach ($tabLignePrestation as $key => $valueIndex)
            {
                $form->add(array(
                    "name" => "nomTemplate_".$valueIndex,
                    "attributes" => array(
                        "type" => "text",
                    ),
                ));
                
                if($this->naturePrestation == "ordonnance")
                {
                     $form->add(array(
                        "name" => "montantTemplate_".$valueIndex,
                        "attributes" => array(
                            "type" => "number",
                        ),
                    ));
			
			    $form->add(array(
                        "name" => "nbreTemplate_".$valueIndex,
                        "attributes" => array(
                            "type" => "number",
                        ),
                    ));
                    
                    $form->add(array(
                        "name" => "posologieTemplate_".$valueIndex,
                        "attributes" => array(
                            "type" => "text",
                        ),
                    ));
                }
                elseif($this->naturePrestation == "examen")
                {
					$tabTpres=array();
					if($typeSousProfil=="laboratoire")
					{
			          $listeTp=$this->typePrestationManager->getListeTypePrestation("1","examens");
	 	            }
		            else
					{
			        $listeTp=$this->typePrestationManager->getListeTypePrestation("1","examens","actes");
		            }
					
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
                        "name" => "montantTemplate_".$valueIndex,
                        "attributes" => array(
                            "type" => "number",
                        ),
                    ));
			
			    $form->add(array(
                        "name" => "nbreTemplate_".$valueIndex,
                        "attributes" => array(
                            "type" => "number",
                        ),
                    ));
								
                }  
				elseif($this->naturePrestation == "lunetterie")
                {
					$tabTpres=array();
					
			        $listeTp=$this->typePrestationManager->getListeTypePrestation("1","lunetteries");
					
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
                        "name" => "montantTemplate_".$valueIndex,
                        "attributes" => array(
                            "type" => "number",
                        ),
                    ));
			
			    $form->add(array(
                        "name" => "nbreTemplate_".$valueIndex,
                        "attributes" => array(
                            "type" => "number",
                        ),
                    ));
							
                }
                
                if($this->naturePrestation == "ordonnance")
                {
                    $prestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("nomTemplate_".$valueIndex, true, 1));

                    $prestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("montantTemplate_".$valueIndex, true, 1));
					
					 $prestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("nbreTemplate_".$valueIndex, true, 1));
                    $prestationInputFilter->getInputFilter()->merge(new TextImputFilterLcl("posologieTemplate_".$valueIndex, true, 2, 255));
                
                    $form->get("nomTemplate_".$valueIndex)->setOptions(array('disable_inarray_validator' => true));
                }
                elseif($this->naturePrestation == "examen")
                {
                    $prestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("nomTemplate_".$valueIndex, true, 1));

					$tabTp=array();
					if($typeSousProfil=="laboratoire")
					{
			          $listeTp=$this->typePrestationManager->getListeTypePrestation("1","examens");
	 	            }
		            else{
			        $listeTp=$this->typePrestationManager->getListeTypePrestation("1","examens","actes");
		            }
					
					foreach($listeTp as $value) { 
					$tabTp[]= $value->getId();
					}
                    $prestationInputFilter->getInputFilter()->merge(new EnumImputFilterLcl("typeExamenTemplate_".$valueIndex, true, $tabTp));
					$prestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("montantTemplate_".$valueIndex, true, 1));
					
					 $prestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("nbreTemplate_".$valueIndex, true, 1));
                }
				elseif($this->naturePrestation == "lunetterie")
                {
					$tabTp=array();
					
			        $listeTp=$this->typePrestationManager->getListeTypePrestation("1","lunetteries");
		          
					
					foreach($listeTp as $value) { 
					$tabTp[]= $value->getId();
					}
                    $prestationInputFilter->getInputFilter()->merge(new EnumImputFilterLcl("typeExamenTemplate_".$valueIndex, true, $tabTp));
					$prestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("montantTemplate_".$valueIndex, true, 1));
					
					 $prestationInputFilter->getInputFilter()->merge(new DigitImputFilterLcl("nbreTemplate_".$valueIndex, true, 1));
                }
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
				
				//verifier que le contrat soit toujours valide
				
				$adherent = $this->getEntityManager()->find('Entity\Adherent', $consultation->getVisite()->getCodeAdherent());
				if(($adherent->getStatut() == "-1") || (!empty($adherent->getEcheancePolice()) && $adherent->getEcheancePolice()->format("Y-m-d") < date("Y-m-d")))
                {
                $status = 400;
                $error .= $this->getTranslator("Le contrat de l'assure a expire\n");
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
                    if($this->naturePrestation == "ordonnance")
                    {
                        $error = $this->getTranslator("Vous avez deja enregistre une ordonnance pour cette visite");
                    }
                     elseif($this->naturePrestation == "examen")
                    {
						if ($typeSousProfil=="laboratoire")
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
                elseif($action == 'modif' && !$oldPrestation)
                {
                    if($this->naturePrestation == "ordonnance")
                    {
                        $error = $this->getTranslator("Impossible de trouver cette ordonnance");
                    }
                     elseif($this->naturePrestation == "examen")
                    {
						if ($typeSousProfil=="laboratoire")
						{
                        $error = $this->getTranslator("Impossible de trouver ces examens");
						}
						else
						{
						$error = $this->getTranslator("Impossible de trouver ces examens/actes");	
						}
                    }
					elseif($this->naturePrestation == "lunetterie")
                    {
						
						$error = $this->getTranslator("Impossible de trouver ces prestations en lunetterie");	
						
                    }
                    else
                    {
                        $error = $this->getTranslator("Impossible de trouver cette prescription");
                    }
                }
                else
                {
                    if($action == 'ajout')
                    {
                        $prestation = $this->prestation;
                        $prestation->exchangeArray($donneesFormulaire);
                        $prestation->setVisite($visite);
                        $prestation->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                        $prestation->setNaturePrestation($this->naturePrestation);
                        
                        $prestation->setPrestataire($this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")));
                    } 
                    
                    // Debut du mode transactionnel
                    $this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
                    try {
                                              
                       if(!empty($postValues["listeLignePrestationSent"]))
                         {
							 // Enregistrement dans la table prestation
                        $prestation->nettoyageChaine();
                        if($action == 'ajout')
                        {
                        $this->getEntityManager()->persist($prestation);
                        }   
                        
						str_replace('"', " ", $donneesFormulaire['natureAffection']);
                        $consultation->setNatureAffection($donneesFormulaire['natureAffection']);                    
                        $this->getEntityManager()->flush();
						
                            $tabLignePrestation = @explode(";", $postValues["listeLignePrestationSent"]);
                            foreach ($tabLignePrestation as $key => $valueIndex)
                            {
                                if(($this->naturePrestation == "ordonnance" && !empty($donneesFormulaire["nomTemplate_".$valueIndex]) && !empty($donneesFormulaire["nbreTemplate_".$valueIndex]) && !empty($donneesFormulaire["montantTemplate_".$valueIndex]) && !empty($donneesFormulaire["posologieTemplate_".$valueIndex])) ||
                                   (($this->naturePrestation == "examen" || $this->naturePrestation == "lunetterie")  && !empty($donneesFormulaire["nomTemplate_".$valueIndex]) && !empty($donneesFormulaire["typeExamenTemplate_".$valueIndex]) && !empty($donneesFormulaire["nbreTemplate_".$valueIndex]) && !empty($donneesFormulaire["montantTemplate_".$valueIndex])))
                                {
                                    $medicament = null;
                                    if($this->naturePrestation == "ordonnance" || $this->naturePrestation == "examen")
                                    {
                                        $medicament = $this->getEntityManager()->find('Entity\Medicament', $donneesFormulaire["nomTemplate_".$valueIndex]);
                                        if(!$medicament)
                                        {
                                            continue;
                                        }
                                    }

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
                                    
                                    //$lignePrestation->setNom($donneesFormulaire["nomTemplate_".$valueIndex]);
                                    
                                    if($this->naturePrestation == "ordonnance")
                                    {
                                        $lignePrestation->setNom($medicament->getNom());
                                        $lignePrestation->setMedicament($medicament);

                                        $lignePrestation->setValeur($donneesFormulaire["montantTemplate_".$valueIndex]);
										
										$lignePrestation->setNbre($donneesFormulaire["nbreTemplate_".$valueIndex]);
										
                                        $lignePrestation->setPosologie($donneesFormulaire["posologieTemplate_".$valueIndex]);
                                    }
                                     elseif($this->naturePrestation == "examen")
                                    {
                                        $lignePrestation->setNom($medicament->getNom());
                                        $lignePrestation->setMedicament($medicament);
                                        
                                        $lignePrestation->setTypeExamen($this->getEntityManager()->find('Entity\TypePrestation', $donneesFormulaire["typeExamenTemplate_".$valueIndex]));
										
										 $lignePrestation->setValeur($donneesFormulaire["montantTemplate_".$valueIndex]);
										
										$lignePrestation->setNbre($donneesFormulaire["nbreTemplate_".$valueIndex]);
                                    }
									elseif($this->naturePrestation == "lunetterie")
                                    {
                                        $lignePrestation->setNom($donneesFormulaire["nomTemplate_".$valueIndex]);
                                        
                                        $lignePrestation->setTypeExamen($this->getEntityManager()->find('Entity\TypePrestation', $donneesFormulaire["typeExamenTemplate_".$valueIndex]));
										
										 $lignePrestation->setValeur($donneesFormulaire["montantTemplate_".$valueIndex]);
										
										$lignePrestation->setNbre($donneesFormulaire["nbreTemplate_".$valueIndex]);
                                    }
                                    
                                   if($nouveauElement && isset($postValues['valeurSubmit']) && $postValues['valeurSubmit']=="submit")
                                    {
										
                                        $lignePrestation->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
										$lignePrestation->setPrestataire($this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")));
                                        $this->getEntityManager()->persist($lignePrestation);
                                        
                                        
                                        $lignePrestationAudit = new LignePrestationAudit();
                                        $lignePrestationAudit->setLignePrestation($lignePrestation);
                                        $lignePrestationAudit->setEmploye($this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id")));
                                        $lignePrestationAudit->setEtatLignePrestation("enregistre");
                                        $lignePrestationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
           
                                        $this->getEntityManager()->persist($lignePrestationAudit);
                                        $this->getEntityManager()->flush();
                                    }
									elseif($nouveauElement && isset($postValues['valeurSubmit']) && $postValues['valeurSubmit']=="submitClose" )
									{
										 $examen=$this->getEntityManager()->getRepository('Entity\Medicament')->findOneBy(array('nom' =>$medicament->getNom(), 
									                                                                                       'prestataire' => $this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")),
																														   'statut'=> '1',
																														   'supprime'=>'-1'));
										
										$lignePrestation->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
										$lignePrestation->setEtat("attente_validation");
										$lignePrestation->setPrestataire($this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")));
                                        $this->getEntityManager()->persist($lignePrestation);
                                        
                                        
                                        $lignePrestationAudit = new LignePrestationAudit();
                                        $lignePrestationAudit->setLignePrestation($lignePrestation);
                                        $lignePrestationAudit->setEmploye($this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id")));
                                        $lignePrestationAudit->setEtatLignePrestation("attente_validation");
                                        $lignePrestationAudit->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime(' + 1 hours'))));
                                        $this->getEntityManager()->persist($lignePrestationAudit);
										
										
										if(!$examen && $this->naturePrestation == "examen")
										{
										  $examenNew = new Medicament();
										  $examenNew->setCode($valueIndex);
										  $examenNew->setNom($medicament->getNom());
										   $examenNew->setOrigine($donneesFormulaire["typeExamenTemplate_".$valueIndex]);
										  $examenNew->setPrix($donneesFormulaire["montantTemplate_".$valueIndex]);
										  $examenNew->setQuantite($donneesFormulaire["nbreTemplate_".$valueIndex]);
										  $examenNew->setCategorie("2");
										  $examenNew->setPrestataire($this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")));
										  $examenNew->setStatut("1");
										  $examenNew->setSupprime("-1");
										  $this->getEntityManager()->persist($examenNew);
										}
										elseif ($examen && $this->naturePrestation == "examen")
										{
									    $examen->setOrigine($donneesFormulaire["typeExamenTemplate_".$valueIndex]);
										$examen->setPrix($donneesFormulaire["montantTemplate_".$valueIndex]);
										$examen->setQuantite($donneesFormulaire["nbreTemplate_".$valueIndex]);
										}
										
                                        $this->getEntityManager()->flush();
									}
									elseif(!$nouveauElement && isset($postValues['valeurSubmit']) && $postValues['valeurSubmit']=="submitClose" )
									{
										 $examen=$this->getEntityManager()->getRepository('Entity\Medicament')->findOneBy(array('nom' =>$medicament->getNom(), 
									                                                                                       'prestataire' => $this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")),
																														   'statut'=> '1',
																														   'supprime'=>'-1'));
										//var_dump($examen);exit;																				                            
										$lignePrestation->setEtat("attente_validation");
										$lignePrestation->setPrestataire($this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")));
										
										if(!$examen && $this->naturePrestation == "examen")
										{
										  $examenNew = new Medicament();
										  $examenNew->setCode($valueIndex);
										  $examenNew->setNom($medicament->getNom());
										   $examenNew->setOrigine($donneesFormulaire["typeExamenTemplate_".$valueIndex]);
										  $examenNew->setPrix($donneesFormulaire["montantTemplate_".$valueIndex]);
										  $examenNew->setQuantite($donneesFormulaire["nbreTemplate_".$valueIndex]);
										  $examenNew->setCategorie("2");
										  $examenNew->setPrestataire($this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")));
										  $examenNew->setStatut("1");
										  $examenNew->setSupprime("-1");
										  $this->getEntityManager()->persist($examenNew);
										}
										elseif ($examen && $this->naturePrestation == "examen")
										{
									    $examen->setOrigine($donneesFormulaire["typeExamenTemplate_".$valueIndex]);
										$examen->setPrix($donneesFormulaire["montantTemplate_".$valueIndex]);
										$examen->setQuantite($donneesFormulaire["nbreTemplate_".$valueIndex]);
										}
										
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
							if(!empty($postValues["listeLignePrestationSent"]) && $postValues['valeurSubmit']=="submitClose")
                                    {
									    
										// Envoi du sms
                                       $tabEmployeServiceSante = $this->employeManager->getListeEmploye("1", null, null, "-1", 1, null, false, 'SERVICE_SANTE');
                                       $tabNumTelephone = array();
									   $tabEmail=array();
                                       foreach ($tabEmployeServiceSante as $unEmployeServiceSante)
                                       {
                                        $telephone = $unEmployeServiceSante->getUtilisateur()->getTelephone();
										$email = $unEmployeServiceSante->getUtilisateur()->getEmail();
                                        if(!empty($telephone))
                                         $tabNumTelephone[] = $telephone;
									 
									    if(!empty($email))
                                         $tabEmail[] = $email;
                                       }
									   //var_dump($tabEmployeServiceSante);exit();	
									   
									     $message = $this->getTranslator("Prestataire :")." ".$this->getEntityManager()->find('Entity\Employe', $sessionEmploye->offsetGet("id"))->getPrestataire()->getNom()."\n";
                        
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
                                         $utilitaire->sendSmsHttp($tabNumTelephone, $message);
						                 try{
					                          $utilitaire = new Utilitaire();
	                                          $tabRecepteur=$tabEmail;
											  
		                                       $sujet="Validation ";
					                           $sujet.=$this->naturePrestation;
		                                       $contenuMail=$message;
		                                      $utilitaire->sendMailSMTP($tabRecepteur, $sujet, $contenuMail);
                                             } 
					                     catch (\Exception $e) 
					                         {
    	                                       
    	                                     }
									}
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
        
		if($action == 'ajout')
          {
           $prestationAjout = $this->getEntityManager()->getRepository('Entity\Prestation')->findOneBy(array('visite' => $postValues['visite'], 'naturePrestation' => $this->naturePrestation));
           $this->prestationAjoutModifId=$prestationAjout->getId();		   
          }
		else
		{
			
			$this->prestationAjoutModifId=$prestation->getId();
		}
		  
		 
		 
		 //var_dump($this->prestationAjout);exit;
        return new JsonModel(array(
            'error' => $error,
			'tabError' => $tabError,
			'info' => $info,
			'varRetour' => $varRetour,
            'naturePrestation' => $this->naturePrestation,
            'tabLignePrestation' => array(),
			'prestationAjoutModifId'=> $this->prestationAjoutModifId ,
        ));
    }
    
    public function modifierAction ()
    {
        $this->initialiserPermission(); 
                $error = $this->initialiserControlleur();
        
        $appliConfig =  new \Application\Core\AppliConfig();
    	$sessionAgence = new Container('agence');
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	$listEltsUrl = $basePath."/".$this->appliConfig->get('lienBackoffice')."/".$this->naturePrestation;
    	$sessionEmploye = new Container('employe');
    	$utilitaire = new Utilitaire();
		$visite = null;
		$codeVisite = "";
		$prestat = "";
    	$typeProfil = $sessionEmploye->offsetGet("type_profil");
		$typeSousProfil = $sessionEmploye->offsetGet("type_sous_profil");
    	
    	if($this->naturePrestation == "ordonnance")
    	{
    	    $this->nomPage = $this->getTranslator("Modifier une ordonnance");
    	}
    	 elseif($this->naturePrestation == "examen")
    	{
			if($typeSousProfil=="laboratoire")
		    {
				$this->nomPage = $this->getTranslator("Modifier les examens");
			}
		    else
			{
			$this->nomPage = $this->getTranslator("Modifier les examens/actes");	
			}
			 
    	}
		elseif($this->naturePrestation == "lunetterie")
    	{
			$this->nomPage = $this->getTranslator("Modifier les prestations en lunetterie");	
			 
    	}
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
    	
    	
    	if(!$this->prestationManager->vefifierSiPrestationModifiable($prestation))
    	{
    	    return $this->redirect()->toUrl($basePath."/".$appliConfig->get("lienBackoffice")."/acces-refuse");
    	}
    	
    	
    	$visite = $consultation->getVisite();
		$codeVisite = $visite->getId();
    	
    	
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
	            "nom" => $unObjetLignePrestation->getNom(),
	            "prestation" => $unObjetLignePrestation->getPrestation()->getId(),
	            "nbre" => $unObjetLignePrestation->getNbre(),
				"montant" => $unObjetLignePrestation->getValeur(),
	            "typeExamen" => $idTypeExamen,
	            "posologie" => $unObjetLignePrestation->getPosologie(),
                "modifiable" => $this->lignePrestationManager->vefifierSiLignePrestationModifiable($unObjetLignePrestation),
                "medicament" => $unObjetLignePrestation->getMedicament() ? $unObjetLignePrestation->getMedicament()->getId() : null,
                "examen" => $unObjetLignePrestation->getExamen() ? $unObjetLignePrestation->getExamen()->getId() : null,
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
    	
    	
    	if($this->naturePrestation == "ordonnance")
    	{
			$listeTypePrestation=[];
    	    $this->nomPage = $this->getTranslator("Modifier une ordonnance");
    	}
    	 elseif($this->naturePrestation == "examen")
    	{
			if($typeSousProfil=="laboratoire")
		    {
    	    $this->nomPage = $this->getTranslator("Modifier les examens");
			}
			else
			{
				$this->nomPage = $this->getTranslator("Modifier les examens/actes");
			}
    	}
		elseif( $this->naturePrestation == "lunetterie")
    	{
			
				$this->nomPage = $this->getTranslator("Modifier les prestations en lunetterie");
    	}
    	
    	
    	
    	
    	$this->initBackViewSimpleForm('Prestation', $form, $formPosted, $listEltsUrl, array(), $msgSuccess);
		
		if($this->naturePrestation=="examen")
		{
		    if($typeSousProfil=="laboratoire")
		    {
		    	$listeTypePrestation=$this->typePrestationManager->getListeTypePrestation("1","examens");
		    }
		    else
		    {
		    	$listeTypePrestation=$this->typePrestationManager->getListeTypePrestation("1","examens","actes");
		    }
		}
		elseif($this->naturePrestation=="lunetterie")
		{
		$listeTypePrestation=$this->typePrestationManager->getListeTypePrestation("1","lunetteries");	
		}
    	 
    	return new ViewModel(array(
    		'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
    		'idPrestation' => $prestation->getId(),
    	    'naturePrestation' => $this->naturePrestation,
    	    'originformAjoutPrestation' => 'modif',
    	    'tabLignePrestation' => $tabLignePrestation,
    	    'prestation' => $prestation,
    	    'infosMalade' => $this->construireInfosMalade($visite),
            'listeTypePrestation'=> $listeTypePrestation,
            'tabMedicament' => $this->tabMedicament,
			//'visite' =>$visite,
			'visite' =>$visite,
			'codeVisite' =>$codeVisite,
			'prestat' =>$prestat,
    	));
    }
    
    public function paginationAction()
    {
        $this->initialiserPermission(); 
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
    	   && isset($postValues['dateEncaisseMin']) && isset($postValues['dateEncaisseMax'])
    	   && isset($postValues['nomAdherent']) && isset($postValues['nomAyantDroit']) && isset($postValues['souscripteur'])
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
                    		   "dateEncaisseMin" => trim($postValues['dateEncaisseMin']),
                    		   "dateEncaisseMax" => trim($postValues['dateEncaisseMax']),
							   
							   "souscripteur" => trim($postValues['souscripteur']),
    		    
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
    			include_once __DIR__.'/../../view/prestation/index/pagination.phtml';
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
	
	public function recupererMontantAction()
    {
        $this->initialiserPermission(); 
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
            $error = $this->getTranslator("Veuillez renseigner le code du medicament");
        }
        
        
        
        if(empty($error))
        {
                        if($this->naturePrestation == "ordonnance")
                        {
						
                          //$medicament = $this->getEntityManager()->getRepository('Entity\Medicament')->findOneBy(array('id' => $id));
						  //var_dump($medicament);exit;
                        }
                         elseif($this->naturePrestation == "examen")
                        {
							 $medicament = $this->getEntityManager()->getRepository('Entity\Medicament')->findOneBy(array('id' => $id));
                        }
						elseif( $this->naturePrestation == "lunetterie")
                        {
							
						  
                        }
                        else
                        {
                            
                        }
						
						$varRetour = array("id" => $medicament->getId(),
                                           "nom" => $medicament->getNom(),
                                           "prix" => $medicament->getPrix(),
										    "quantite" => $medicament->getQuantite(),
										   "type" => $medicament->getOrigine(),
                        );
						
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
        $this->initialiserPermission(); 
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
                    if($this->naturePrestation == "ordonnance")
                    {
                        $error = $this->getTranslator("Veuillez enregistrer la consultation pour cette visite avant d'enregistrer l'ordonnance");
                    }
                     elseif($this->naturePrestation == "examen")
                    {
						if($typeSousProfil=="laboratoire")
		                {
                        $error = $this->getTranslator("Veuillez enregistrer la consultation pour cette visite avant d'enregistrer les examens");
						}
						else
						{
						$error = $this->getTranslator("Veuillez enregistrer la consultation pour cette visite avant d'enregistrer les examens/actes");	
						}
                    }
					elseif($this->naturePrestation == "lunetterie")
                    {
			
						$error = $this->getTranslator("Veuillez enregistrer la consultation pour cette visite avant d'enregistrer les prestations en lunetterie");	
                    }
                    else
                    {
                        $error = $this->getTranslator("Veuillez enregistrer la consultation pour cette visite avant d'enregistrer les details de la prestation");
                    }
                }
                elseif($consultation->getEtatConsultation() != "valide" && $consultation->getEtatConsultation() != "encaisse")
                {
                    if($this->naturePrestation == "ordonnance")
                    {
                        $error = $this->getTranslator("Cette consultation n'a pas encore ete validee, vous ne pouvez pas enregistrer une ordonnance pour celle-ci");
                    }
                    elseif($this->naturePrestation == "examen")
                    {
						if($typeSousProfil=="laboratoire")
		                {
                        $error = $this->getTranslator("Cette consultation n'a pas encore ete validee, vous ne pouvez pas enregistrer des examens pour celle-ci");
						}
						else
						{
							$error = $this->getTranslator("Cette consultation n'a pas encore ete validee, vous ne pouvez pas enregistrer des examens/actes pour celle-ci");
						}
							
                    }
					elseif( $this->naturePrestation == "lunetterie")
                    {
						
							$error = $this->getTranslator("Cette consultation n'a pas encore ete validee, vous ne pouvez pas enregistrer des prestations en lunetterie pour celle-ci");
							
                    }
                    else
                    {
                        $error = $this->getTranslator("Cette consultation n'a pas encore ete validee, vous ne pouvez pas enregistrer des details de prescription pour celle-ci");
                    }
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
						elseif( $this->naturePrestation == "lunetterie")
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
        $this->initialiserPermission(); 
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
                    if($this->naturePrestation == "ordonnance")
                    {
                        $error = $this->getTranslator("Aucune ordonnance n'a ete enregistre pour cette visite");
                    }
                     elseif($this->naturePrestation == "examen")
                    {
						if($typeSousProfil=="laboratoire")
		                {
                        $error = $this->getTranslator("Aucun examen n'a ete enregistre pour cette visite");
						}
						else
						{
							 $error = $this->getTranslator("Aucun examen/acte n'a ete enregistre pour cette visite");
						}
                    }
					elseif($this->naturePrestation == "lunetterie")
                    {
							 $error = $this->getTranslator("Aucune prescription de lunette n'a ete enregistre pour cette visite");
						
                    }
                    else
                    {
                        $error = $this->getTranslator("Aucune prestation n'a ete enregistre pour cette visite");
                    }
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
        $this->initialiserPermission(); 
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
            if(!$this->prestationManager->vefifierSiPrestationImprimable($prestation, $sessionEmploye->offsetGet("id_prestataire")))
            {
                $error = $this->getTranslator("Vous ne pouvez pas imprimer cette prestation car elle doit d'abord etre encaissee par vous");
            }
            
            if(empty($error))
            {
                // $prestationAudit = $this->getEntityManager()->getRepository('Entity\LignePrestationAudit')->findOneBy(array('prestation' => $id, "etatPrestation" => "encaisse"));
                
                
                $tabParams = array("prestation" => $id,
                                    "etat" => 'encaisse',
                                    "nbreMax" => "1",
                                    "orderBy" => array("date" => "DESC")
                                   );
                
                
                $tab = $this->lignePrestationAuditManager->getListeLignePrestationAuditTabParams($tabParams);
                (is_array($tab) && count($tab) > 0) ? $lignePrestationAudit = $tab[0] : $lignePrestationAudit = null;
                
                
                include_once __DIR__.'/../../view/prestation/index/imprimer-recu.phtml';
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
       $this->initialiserPermission(); 
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
            && isset($postValues['dateEncaisseMin']) && isset($postValues['dateEncaisseMax'])
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
                "dateEncaisseMin" => trim($postValues['dateEncaisseMin']),
                "dateEncaisseMax" => trim($postValues['dateEncaisseMax']),
			    "souscripteur" => trim($postValues['souscripteur']),
                
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
                include_once __DIR__.'/../../view/prestation/index/imprimer-pdf.phtml';
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
        $this->initialiserPermission(); 
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
            && isset($postValues['dateEncaisseMin']) && isset($postValues['dateEncaisseMax'])
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
                "dateEncaisseMin" => trim($postValues['dateEncaisseMin']),
                "dateEncaisseMax" => trim($postValues['dateEncaisseMax']),
                "souscripteur" => trim($postValues['souscripteur']),
                "nomAdherent" => trim($postValues['nomAdherent']),
                "nomAyantDroit" => trim($postValues['nomAyantDroit']),
                // "visite" => trim($postValues['idVisite']),
                "etat"=>"encaisse",
                "supprime" => $supprime,
                "pagination" => false,
                "orderBy" => array("date" => "DESC"),
            );
            
            
            // $tab = $this->prestationManager->getListePrestationTabParams($tabParams);
            $tab = $this->lignePrestationManager->getListeLignePrestationTabParams($tabParams);
            if(is_array($tab) && count($tab) > 0)
            {
                include_once __DIR__.'/../../view/prestation/index/imprimer-detaille-pdf.phtml';
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
        $this->initialiserPermission(); 
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

    public function ajouterMedicamentAjaxAction()
    {
        $this->initialiserPermission(); 
                $error = $this->initialiserControlleur();
		  $sessionEmploye = new Container('employe');

        $info = "";
        $varRetour = "";
        $medicament = "";
		 $medicamentArray=[];

        $postValues = $this->getRequest()->getPost();
        $postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
        $postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau

        if(is_array($postValues) && isset($postValues['nomMedicament']))
        {   
            $ancien=null;
	        if($this->naturePrestation=="examen")
	        {	
			$ancien=$this->getEntityManager()->getRepository('Entity\Medicament')->findOneBy(array('nom' =>$postValues['nomMedicament'], 
									                                                                                       'prestataire' => $this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")),
																														   'statut'=> '1',
																														   'supprime'=>'-1'));
			}
           else if ($this->naturePrestation=="ordonnance")
		   {
			$ancien=$this->getEntityManager()->getRepository('Entity\Medicament')->findOneBy(array('nom' =>$postValues['nomMedicament'],
																									'statut'=> '1',
																								    'supprime'=>'-1')); 
		   }
			if(!$ancien)
            {				
            $medicament = $this->appliContainer->get('Entity\Medicament');
            $medicament->setCode(uniqid());
            $medicament->setNom(strtoupper($postValues['nomMedicament']));

            $medicament->nettoyageChaine();
			if($this->naturePrestation=="examen")
			{
			 $medicament->setPrestataire($this->getEntityManager()->find('Entity\Prestataire', $sessionEmploye->offsetGet("id_prestataire")));
			 $medicament->setCategorie('2');
			}

            $this->getEntityManager()->persist($medicament);
            $this->getEntityManager()->flush();
			

            $medicament->afficheChaine();

            $medicamentArray = [
                'id' => $medicament->getId(),
                'nom' => $medicament->getNom(),
            ];
			
			}
			else
			{
			  $error = $this->getTranslator("Ce nom existe deja, Veuillez le rechercher dans la liste deroulante");	
			}
        }
        else
        {
            $error = $this->getTranslator("Veuillez saisir le nom");
        }

        return new JsonModel(array(
            'error' => $error,
            'info' => $info,
            'varRetour' => $varRetour,
            'medicamentArray' => $medicamentArray,
            'lienBackoffice' => $this->appliConfig->get('lienBackoffice'),
        ));
    }
}
