<?php

namespace Application\Service;

use Zend\Stdlib\RequestInterface as Request;
use Application\Core\Utilitaire;
use Custom\Service\CommonService;

class InternauteService extends CommonService implements InternauteServiceInterface
{
	public function inscription(Request $request)
	{
		$clientData = array_merge_recursive(
   						$request->getPost()->toArray(),
   						$request->getFiles()->toArray()
   				);
		
		$utilitaire = new Utilitaire();
		$error = "";
		$tableError = array();
		$internaute = null;
		$success = false;
		$form = $this->appliContainer->get('FrontHome\Form\InternauteForm');
		$internauteInputFilter = $this->appliContainer->get('Application\Filter\InternauteInputFilter');
		$internauteManager = $this->appliContainer->get('Application\Manager\InternauteManager');

		$form->setInputFilter($internauteInputFilter->getInputFilter());
		$form->setData($clientData);
					
		$form->setValidationGroup("langueDefaut", "nom", "prenom", "login", "email", "motPasse", "confirmMotPasse",
								  "telephone", "telephoneIso2", "telephoneDialCode");
		
		
		
		$unInternaute = $internauteManager->getInternauteParEmail($clientData['email']);
		// var_dump($unInternaute); exit;
		if($unInternaute)
		{
			$unUtilisateur = $unInternaute->getUtilisateur();
			$error = $this->getTranslator("Un utilisateur existe deja avec l'adresse mail")." ".$clientData['email'].", ";
			$error .= $this->getTranslator("s'il s'agit de votre adresse mail, veuillez :");
				
			if($unUtilisateur->getStatut() == "1")
			{
				$error .= '<ul>';
					
				$error .= '<li><a href="#" data-toggle="modal" data-target="#ctneurConnexionModale" id="cliquerConnAjax">'.$this->getTranslator("Cliquer ici pour vous connecter ou").'</a></li>';
				$error .= '<li><a href="#" data-toggle="modal" data-target="#ctneurMotPasseOublieModale" id="cliquerMotPassAjax">'.$this->getTranslator("Cliquer ici si vous avez oublie votre mot de passe").'</a></li>';
					
				$error .= '</ul>';
			}
			else
			{
				$error .= '<ul>';
					
				$error .= '<li><a id="activationCompteInscr" href="#" idInternaute="'.$unInternaute->getId().'">'.$this->getTranslator("Cliquer ici pour recevoir l'email d'activation de votre compte").'</a></li>';
					
				$error .= '</ul>';
			}
		}
		
		
		
		
		
		if(empty($error))
		{
			if ($form->isValid())
			{
				$utilisateur = $this->appliContainer->get('Entity\Utilisateur');
				$internaute = $this->appliContainer->get('Entity\Internaute');
				
				$continue = true;
				if($clientData['motPasse'] != $clientData['confirmMotPasse'])
				{
					$continue = false;
					$form->get('confirmMotPasse')->setMessages(array($this->getTranslator('Les mots de passe doivent etre identiques')));
				}
				 
				if($continue)
				{
					$donneesFormulaire = $form->getData();
					
					$dateCreation = new \DateTime(date("Y-m-d H:i:s"));
					$langueDefaut = $this->getEntityManager()->find('Entity\Langue', $donneesFormulaire['langueDefaut']);
					
					$utilisateur->exchangeArray($donneesFormulaire);
					$utilisateur->setLangueDefaut($langueDefaut);
					$utilisateur->setStatut("-1");
					$utilisateur->setType("internaute");
					$utilisateur->setDateCreation($dateCreation);
					$utilisateur->setMotPasse($utilitaire->crypterMotPass(trim($donneesFormulaire['motPasse'])));
	
					// Debut du mode transactionnel
					$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
					try {
						
						$utilisateur->nettoyageChaine(array("motPasse"));
						$this->getEntityManager()->persist($utilisateur);
						$this->getEntityManager()->flush();
						
						
						$internaute->SetUtilisateur($utilisateur);
						$internaute->setTokenActivation(uniqid());
						$internaute->setDateTokenActivation($dateCreation);
						$internaute->setLienActiverCompte(@$clientData['lienActiverCompte']);
						
						
						$internaute->nettoyageChaine();
						$this->getEntityManager()->persist($internaute);
						$this->getEntityManager()->flush();
						
						$this->getEntityManager()->getConnection()->commit();
						
						$success = true;
							
					} catch (\Exception $e) {
						$this->getEntityManager()->getConnection()->rollback();
						$this->getEntityManager()->close();
						
						$internaute = null;
						$error = $e->getMessage();
					}
				}
			}
			else
			{
				$tableError = $form->getMessages();
				$error = "1234";
			}
		}
		
		
		
		return array ('form' => $form,
					  'internaute' => $internaute, 
					  'error' => $error, 
					  'success' => $success,
					  'tableError' => $tableError
					 );
	}
	
	
	public function inscriptConnResauxSociaux(array $clientData)
	{
		$utilitaire = new Utilitaire();
		$error = "";
		$tableError = array();
		$internaute = null;
		$success = false;
		$form = $this->appliContainer->get('FrontHome\Form\InternauteForm');
		$internauteInputFilter = $this->appliContainer->get('Application\Filter\InternauteInputFilter');
		$internauteManager = $this->appliContainer->get('Application\Manager\InternauteManager');

		$form->setInputFilter($internauteInputFilter->getInputFilter());
		$form->setData($clientData);
					
		$form->setValidationGroup("langueDefaut", "nom", "prenom", "login", "email", "motPasse", "confirmMotPasse",
								  "telephone", "telephoneIso2", "telephoneDialCode", "oauthProvider", "oauthUid");
		
		$internaute = $internauteManager->getInternauteParEmail($clientData['email']);
		if(!$internaute)
		{
			$internaute = $internauteManager->getInternauteParIdReseauxSociaux($clientData['oauthProvider'], $clientData['oauthUid']);
		}
		
		if($internaute)
		{
			$internaute->getUtilisateur()->setStatut("1");	
			
			$oauthProvider = $internaute->getUtilisateur()->getOauthProvider();
			$oauthUid = $internaute->getUtilisateur()->getOauthUid();
			
			if(empty($oauthProvider) || empty($oauthUid))
			{
				$internaute->getUtilisateur()->setOauthProvider($clientData['oauthProvider']);
				$internaute->getUtilisateur()->setOauthUid($clientData['oauthUid']);
			}
			
			$this->getEntityManager()->flush();
			
			$success = true;
		}
		else
		{
			if ($form->isValid())
			{
				$utilisateur = $this->appliContainer->get('Entity\Utilisateur');
				$internaute = $this->appliContainer->get('Entity\Internaute');
				
				$donneesFormulaire = $form->getData();
					
				$dateCreation = new \DateTime(date("Y-m-d H:i:s"));
				$langueDefaut = $this->getEntityManager()->find('Entity\Langue', $donneesFormulaire['langueDefaut']);
				
				$utilisateur->exchangeArray($donneesFormulaire);
				$utilisateur->setLangueDefaut($langueDefaut);
				$utilisateur->setStatut("1");
				$utilisateur->setType("internaute");
				$utilisateur->setDateCreation($dateCreation);
				$utilisateur->setMotPasse($utilitaire->crypterMotPass(trim($donneesFormulaire['motPasse'])));

				// Debut du mode transactionnel
				$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
				try {
					
					$utilisateur->nettoyageChaine(array("motPasse"));
					$this->getEntityManager()->persist($utilisateur);
					$this->getEntityManager()->flush();
					
					
					$internaute->setUtilisateur($utilisateur);
					$internaute->setTokenActivation(uniqid());
					$internaute->setDateTokenActivation($dateCreation);
					
					
					$internaute->nettoyageChaine();
					$this->getEntityManager()->persist($internaute);
					$this->getEntityManager()->flush();
					
					$this->getEntityManager()->getConnection()->commit();
					
					$success = true;
						
				} catch (\Exception $e) {
					$this->getEntityManager()->getConnection()->rollback();
					$this->getEntityManager()->close();
					
					$internaute = null;
					$error = $e->getMessage();
				}
			}
			else
			{
				$tableError = $form->getMessages();
				$error = "1234";
			}
		}
		
		
		
		return array ('form' => $form,
					  'internaute' => $internaute, 
					  'error' => $error, 
					  'success' => $success,
					  'tableError' => $tableError
					 );
	}
	
	public function modifierParametre(Request $request, \Entity\Internaute $internaute)
	{
		$clientData = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
		);
		
		$utilitaire = new Utilitaire();
		$utilisateur = $internaute->getUtilisateur();
		$error = "";
		$tableError = array();
		$success = false;
		$form = $this->appliContainer->get('FrontHome\Form\InternauteForm');
		$internauteInputFilter = $this->appliContainer->get('Application\Filter\InternauteInputFilter');

		
		if($utilisateur->getLogin() == $clientData['login'])
			$internauteInputFilter->controlerUniciteLogin = false;
		
		if($utilisateur->getEmail() == $clientData['email'])
			$internauteInputFilter->controlerUniciteEmail = false;
		
		
		$form->setInputFilter($internauteInputFilter->getInputFilter());
		$form->setData($clientData);
			
		$form->setValidationGroup("langueDefaut", "nom", "prenom", "login", "email", "telephone", "telephoneIso2", "telephoneDialCode", "csrf");
	
		if ($form->isValid())
		{
			$donneesFormulaire = $form->getData();
	
			$langueDefaut = $this->getEntityManager()->find('Entity\Langue', $donneesFormulaire['langueDefaut']);

			$utilisateur->exchangeArray($donneesFormulaire);
			$utilisateur->setLangueDefaut($langueDefaut);

			// Debut du mode transactionnel
			$this->getEntityManager()->getConnection()->beginTransaction(); // On suspend l'auto-commit
			try {
					
				$utilisateur->nettoyageChaine(array("motPasse"));
				$this->getEntityManager()->persist($utilisateur);
				$this->getEntityManager()->flush();
					
				$this->getEntityManager()->getConnection()->commit();
					
				$success = true;

			} catch (\Exception $e) {
				$this->getEntityManager()->getConnection()->rollback();
				$this->getEntityManager()->close();
					
				$error = $e->getMessage();
			}
		}
		else
		{
			$tableError = $form->getMessages();
			$error = "1234";
		}
	
		return array ('form' => $form,
					  'error' => $error,
					  'success' => $success,
					  'tableError' => $tableError
					 );
	}
	
	public function modifierMotPasse(Request $request, \Entity\Internaute $internaute)
	{
		$clientData = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
		);
	
		$utilitaire = new Utilitaire();
		$utilisateur = $internaute->getUtilisateur();
		$error = "";
		$tableError = array();
		$success = false;
		$continue = true;
		$form = $this->appliContainer->get('FrontHome\Form\InternauteForm');
		$internauteInputFilter = $this->appliContainer->get('Application\Filter\InternauteInputFilter');
		
		$oldMotPasse = $utilisateur->getMotPasse();
	

		$form->setInputFilter($internauteInputFilter->getInputFilter());
		$form->setData($clientData);
			
		$form->setValidationGroup("ancienMotPasse", "nouveauMotPasse", "confirmMotPasse", "csrf");
	
		if ($form->isValid())
		{
			
			if($oldMotPasse != $utilitaire->crypterMotPass($clientData['ancienMotPasse']))
			{
				$continue = false;
				$form->get('ancienMotPasse')->setMessages(array($this->getTranslator("L'ancien mot de passe n'est pas correct")));
			}
			
			if($clientData['nouveauMotPasse'] != $clientData['confirmMotPasse'])
			{
				$continue = false;
				$form->get('confirmMotPasse')->setMessages(array($this->getTranslator('Les mots de passe doivent etre identiques')));
			}
			
			if($continue)
			{
				$nouveauMotPasse = $utilitaire->crypterMotPass($clientData['nouveauMotPasse']);
			
				$utilisateur->setMotPasse($nouveauMotPasse);
				
				// Debut du mode transactionnel
				try {
					$this->getEntityManager()->flush();
						
					$success = true;
		
				} catch (\Exception $e) {
					$error = $e->getMessage();
				}
			}
			else
			{
				$tableError = $form->getMessages();
				$error = "1234";
			}
		}
		else
		{
			$tableError = $form->getMessages();
			$error = "1234";
		}
	
		return array ('form' => $form,
				'error' => $error,
				'success' => $success,
				'tableError' => $tableError
		);
	}
}
