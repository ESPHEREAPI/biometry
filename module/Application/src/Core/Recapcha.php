<?php

namespace Application\Core;

class Recapcha
{
	public function script()
	{
		return '<script src="https://www.google.com/recaptcha/api.js"></script>';
	}
	
	public function html()
	{
		$appliConfig =  new \Application\Core\AppliConfig();
		$infosRecapcha = $appliConfig->get("infos_recapcha");
		
		return '<div class="g-recaptcha" data-sitekey="'.$infosRecapcha["api_sitekey"].'"></div>';
	}
	
	public function isValid($codeRecapcha, $adresseIpClient)
	{
		$utilitaire = new Utilitaire();
		$appliConfig =  new \Application\Core\AppliConfig();
		$infosRecapcha = $appliConfig->get("infos_recapcha");
		
		if(empty($codeRecapcha))
		{
			return false;
		}
		
		$requete_params = ["secret" => $infosRecapcha["api_secretkey"],
						   "response" => $codeRecapcha,
						   "remoteip" => $adresseIpClient];
		
		$requete_url = $infosRecapcha["url_verify"]."?".http_build_query($requete_params); 

		
		if(function_exists("curl_version"))
		{
			$curl = curl_init($requete_url);
			
			curl_setopt($curl, CURLOPT_HEADER, false); // On n'envoi pas le header
			
			// On se verifie pas le certificat ssl si on est en mode demo
			if($appliConfig->get("mode_demo"))
			{
				// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné.
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			}
			 
			// Modifie l'option CURLOPT_RETURNTRANSFER afin que le resultat soit contenu dans une variable
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			
			// Modifie l'option CURLOPT_TIMEOUT 2 secondes
			curl_setopt($curl, CURLOPT_TIMEOUT, 2);
			
			$reponse = curl_exec($curl);
		}
		else
		{
			$reponse = file_get_contents($requete_url);
		}
		
		if(empty($reponse) || is_null($reponse))
		{
			return false;
		}
		
		$reponse_json = json_decode($reponse);
		
		if(empty($reponse_json) || is_null($reponse_json))
		{
			return false;
		}
		
		return $reponse_json->success;
	}
}
