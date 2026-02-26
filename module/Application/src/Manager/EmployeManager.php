<?php

namespace Application\Manager;

use Application\Core\Utilitaire;

class EmployeManager extends CommonManager
{
    public function getListeEmploye($statut=null, $profil=null, $genre=null, $supprime=null, $nroPage=1, $nbreMax=null, $pagination=false, $categorie=null, $prestataire=null)
    {
		$dqlNbreElt = "";
		
    	$dql = "FROM Entity\Employe emp
    			JOIN emp.utilisateur u
                JOIN emp.prestataire presta
    			WHERE 1 = 1";
    	
    	if(!empty($statut))
    		$dql .= " AND u.statut = '".$statut."'";
    	if(!empty($genre))
    		$dql .= " AND u.genre = '".$genre."'";
    	if(!empty($supprime))
    		$dql .= " AND u.supprime = '".$supprime."'";
    	if(!empty($profil))
    		$dql .= " AND emp.profil = '".$profil."'";
    	
		if(!empty($categorie))
		    $dql .= " AND presta.categorie = '".$categorie."'";
		
	    if(!empty($prestataire))
	        $dql .= " AND presta.id = '".$prestataire."'";
    	
    	$dql .= " ORDER BY u.nom ASC, u.prenom";
    	
    	
    	
    	
    	if($pagination)
    		$dqlNbreElt = "SELECT COUNT(emp.id) ".$dql;
    	 
    	$dql = "SELECT emp ".$dql;
    	
    	return $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    }
	
	public function getEmployeByLoginOuEmail($loginOuEmail)
    {
    	$utilitaire = new Utilitaire();
    	$loginOuEmail = $utilitaire->nettoyageChaine($loginOuEmail);
    	
    	if (filter_var($loginOuEmail, FILTER_VALIDATE_EMAIL))
    	{
    	    $coloneRecherche = "email";
    	}
    	else
    	{
    	    $coloneRecherche = "login";
    	}
    	
    	$loginOuEmail = $utilitaire->nettoyageChaine($loginOuEmail);
    	
    	// Protection contre la faille CRLF
    	$loginOuEmail = str_replace(array("\n", "\r", PHP_EOL), '', $loginOuEmail);
    	
    	// Protecttion contre les robots
    	sleep(1); // Une pause de 1 sec
    	
		$employe = null;
		
    	$dql = "SELECT emp FROM Entity\Employe emp
    			JOIN emp.utilisateur u
    			WHERE u.".$coloneRecherche." = :loginOuEmail
    			AND u.statut = :statut
    			AND u.supprime = :supprime
    			AND emp.connexionAppli = :connexionAppli";
    	
    	$query = $this->em->createQuery($dql);
    	
    	$query->setParameter("loginOuEmail", $loginOuEmail);
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
