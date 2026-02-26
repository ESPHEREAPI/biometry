<?php

namespace Application\Manager;


use Application\Core\Utilitaire;

class BackAuthManager extends CommonManager
{ 
	
    public function connexion($login, $motPasse)
    {
    	$utilitaire = new Utilitaire();
    	$login = $utilitaire->nettoyageChaine($login);
    	
    	if (filter_var($login, FILTER_VALIDATE_EMAIL))
    	{
    	    $coloneRecherche = "email";
    	}
    	else
    	{
    	    $coloneRecherche = "login";
    	}
    	
    	$login = $utilitaire->nettoyageChaine($login);
    	
    	// Protection contre la faille CRLF
    	$login = str_replace(array("\n", "\r", PHP_EOL), '', $login);
    	
    	// Protecttion contre les robots
    	sleep(1); // Une pause de 1 sec
    	
		$employe = null;
		
    	$dql = "SELECT emp FROM Entity\Employe emp
    			JOIN emp.utilisateur u
    			WHERE u.".$coloneRecherche." = :login
    			AND u.motPasse = :motPasse
    			AND u.statut = :statut
    			AND u.supprime = :supprime
    			AND emp.connexionAppli = :connexionAppli";
    	
    	$query = $this->em->createQuery($dql);
    	
    	$query->setParameter("login", $login);
    	$query->setParameter("motPasse", $utilitaire->crypterMotPass($motPasse));
    	$query->setParameter("statut", '1');
    	$query->setParameter("supprime", '-1');
    	$query->setParameter("connexionAppli", '1');
    	
		$query->setMaxResults(1);
    	
    	
    	$tab = $query->execute();
    	
    	if(is_array($tab) && count($tab) > 0)
    	{
    		$employe = $tab[0];
    	}
    		
    	
    	return $employe;
    }	
}
