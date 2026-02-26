<?php

namespace BackAuth\Controller;

use Interop\Container\ContainerInterface;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Application\Manager\MenuManager;
use Custom\Mvc\Controller\BackOfficeCommonController;
use Application\Manager\BackAuthManager;

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
    
    /**
     * @var \Application\Manager\BackAuthManager
     */
    protected $backAuthManager;
    
    protected $appliConfig;
    
    public function __construct(ContainerInterface $appliContainer, MenuManager $menuManager, BackAuthManager $backAuthManager)
    {
        $appliConfig =  new \Application\Core\AppliConfig();
        $this->appliConfig = $appliConfig;
        
		$this->appliContainer = $appliContainer;
		
        $this->menuManager = $menuManager;
        $this->backAuthManager = $backAuthManager;
        
        // $this->initialiserPermission(0);
    }
    
    public function indexAction ()
    {
    	$appliConfig =  new \Application\Core\AppliConfig();
		
    	$this->initBackAuthView();
		
        return new ViewModel(array(
        	'lienBackoffice' => $appliConfig->get('lienBackoffice'),	
        ));
    }

    public function connexionAction ()
    {
    	$appliConfig =  new \Application\Core\AppliConfig();
    	
    	$postValues = $this->getRequest()->getPost();
    	$postValues = json_encode($postValues); // Transformation de l'objet json en chaîne de caracteres
    	$postValues = json_decode($postValues, true); // Transformation de la chaîne de carateres json en tableau
    	
    	$error = "";
    	
    	if(!is_array($postValues) || !isset($postValues['login']) || !isset($postValues['motPasse']))
    	{
    		$error = $this->getTranslator("Please enter the login and the password");
    	}
    	elseif(!$appliConfig->get("mode_demo") && 
    		  (!isset($postValues['g-recaptcha-response']) || empty($postValues['g-recaptcha-response'])))
    	{
    		$error = $this->getTranslator("Veuillez Selectionner si vous n'etes pas un robot");
    	}
    	else
    	{
    		$recapcha =  new \Application\Core\Recapcha();

    		if(!$appliConfig->get("mode_demo") && 
    		   !$recapcha->isValid($postValues['g-recaptcha-response'], $this->request->getServer('REMOTE_ADDR')))
    		{
    			$error = $this->getTranslator("Le capcha semble ne pas etre valide");
    		}
    		elseif($this->getRequest()->getUriString() != $this->getBaseUrlMsbt()."/".$appliConfig->get("lienBackoffice")."/connexion")
    		{
    			$error = $this->getTranslator("Impossible de se connecter ainsi");
    		}
    		else
    		{
    			$userConnect = $this->backAuthManager->connexion($postValues['login'], $postValues['motPasse']);
    			
    			if(!$userConnect)
    			{
    				$error = $this->getTranslator("Login or password incorrect");
    			}
    			else
    			{
    				$appliConfig =  new \Application\Core\AppliConfig();
    				$infosSession = $appliConfig->get("infos_session");
    				
    				$utilisateur = $userConnect->getUtilisateur();
    				 
    				$filialeAgence = $userConnect->getFilialeAgence();
    				$profil = $userConnect->getProfil();
    				$prestataire = $userConnect->getPrestataire();
    				
    				
    				$userConnect->afficheChaine();
    				$utilisateur->afficheChaine();
    				$prestataire->afficheChaine();
    				
    				
    				$sessionAgence = new Container('agence');
    				 
    				$sessionEmploye = new Container('employe');
    				$sessionEmploye->offsetSet("id", $userConnect->getId());
    				$sessionEmploye->offsetSet("nom", $utilisateur->getNom());
    				$sessionEmploye->offsetSet("prenom", $utilisateur->getPrenom());
    				$sessionEmploye->offsetSet("id_langue", $utilisateur->getLangueDefaut()->getId());
    				$sessionEmploye->offsetSet("code_langue", $utilisateur->getLangueDefaut()->getCode());
    				$sessionEmploye->offsetSet("code_iso_langue", $utilisateur->getLangueDefaut()->getCodeIso());
    				$sessionEmploye->offsetSet("code_profil", $profil->getCode());
    				$sessionEmploye->offsetSet("id_profil", $profil->getId());
    				$sessionEmploye->offsetSet("type_profil", $profil->getTypeProfil());
    				$sessionEmploye->offsetSet("type_sous_profil", $profil->getTypeSousProfil());
    				$sessionEmploye->offsetSet("id_utilisateur", $utilisateur->getId());
    				$sessionEmploye->offsetSet("id_prestataire", $prestataire->getId());
    				$sessionEmploye->offsetSet("nom_prestataire", $prestataire->getNom());
    				$sessionEmploye->offsetSet("adresse_prestataire", $prestataire->getAdresse());
    				$sessionEmploye->offsetSet("email_prestataire", $prestataire->getEmail());
    				$sessionEmploye->offsetSet("telephone_prestataire", $prestataire->getTelephone());
    				$sessionEmploye->offsetSet("registre_prestataire", $prestataire->getRegistre());
    				$sessionEmploye->offsetSet("logo_prestataire", $prestataire->getLogo());
    				
    				 
    				if($prestataire)
    				{
    					// $agence = $filialeAgence->getAgence();
    					// $pays = $filialeAgence->getVille()->getRegion()->getPays();
    					$sessionAgence->offsetSet("id", $prestataire->getId());
    					$sessionAgence->offsetSet("nom", $prestataire->getNom());
    					$sessionAgence->offsetSet("code", $prestataire->getId());
    			
    			
    					// $sessionEmploye->offsetSet("id_pays", $pays->getId());
    				}
    				 
    				$profilLangue = $this->getEntityManager()->getRepository('Entity\ProfilLangue')->findOneBy(array('profil' => $profil->getId(),
    						'langue' => $utilisateur->getLangueDefaut()->getId()));
    				if($profilLangue)
    					$sessionEmploye->offsetSet("nom_profil", $profilLangue->getNom());
    					
    					
    				$tabIdMenu = array();
    				if($profil->getCode() != "SUP_ADMIN") // Pour les profils non super admin
    				{
    				    $tabPermission = $this->getEntityManager()->getRepository('Entity\Permission')->findBy(array('profil' => $profil->getId()));
    				    foreach ($tabPermission as $unePermission)
    				    {
    				        $tabIdMenu[] = $unePermission->getMenu()->getId();
    				    }
    				}
    				else // Pour le profil super admin
    				{
    				    $tabMenu = $this->getEntityManager()->getRepository('Entity\Menu')->findBy(array());
    				    foreach ($tabMenu as $unMenu)
    				    {
    				        $tabIdMenu[] = $unMenu->getId();
    				    }
    				}
    				
    				$sessionEmploye->offsetSet("liste_menu", json_encode($tabIdMenu));
    				
    				$sessionEmploye->setExpirationSeconds($infosSession['remember_me_login_backoffice']);
    			}	
    		}
    	}
    	 
    	return new JsonModel(array(
    		'error' => $error,
    	));
    }
    
    public function deconnexionAction ()
    {
    	$lienBackOffice = $this->appliConfig->get('lienBackoffice');
    	unset($_SESSION['employe'], $_SESSION['agence']);
    	
    	$basePath = $this->appliContainer->get('Request')->getBasePath();
    	return $this->redirect()->toUrl($basePath.'/'.$lienBackOffice); // On redirrige l'utilisateur a la page de connexion
    }
}
