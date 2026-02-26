<?php

namespace Application\Manager;


class PrestataireManager extends CommonManager
{
    function getListePrestataire($nom=null, $statut=null, $supprime=null, $nroPage=1, $nbreMax=null, $pagination=false, $categorie=null, $tabCategorie=array(), $ville=null)
    {
		$dqlNbreElt = "";
		
    	$dql = "FROM Entity\Prestataire presta
                JOIN presta.categorie categ
    			WHERE 1 = 1";
    	  
    	
    	if(!empty($nom))
    	    $dql .= " AND presta.nom LIKE '$nom'";
    	if(!empty($statut))
    		$dql .= " AND presta.statut = '".$statut."'";
    	if(!empty($supprime))
    		$dql .= " AND presta.supprime = '".$supprime."'";
    	
    	if(!empty($categorie))
    	    $dql .= " AND presta.categorie = '".$categorie."'";
    	
	    if(!empty($ville))
	        $dql .= " AND presta.ville = '".$ville."'";
    	
	    if(is_array($tabCategorie) && count($tabCategorie) > 0)
	    {
	        $dql .= " AND (";
	        
	        $dqlCategories = "";
	        foreach ($tabCategorie as $uneCategorie)
	        {
	            if(!empty($dqlCategories))
	            {
	                $dqlCategories .= " OR";
	            }
	            $dqlCategories .= " presta.categorie = '".$uneCategorie."'";
	        }
	        
	        $dql .= $dqlCategories;
	        
	        
	        $dql .= ")";
	    }
    	
    	$dql .= " ORDER BY presta.nom ASC";
    	
    	if($pagination)
    		$dqlNbreElt = "SELECT COUNT(presta.id) ".$dql;
    	 
    	$dql = "SELECT presta ".$dql;
    	
    	
    	// var_dump($dql); exit;
    	
    	
    	return $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    }
    
    function viderDonneesPrestataire(array $tabPrestataire, array $tabPrestataireExlus=array())
    {
        
    }
    
    function viderDonneesUnPrestataire(\Entity\Prestataire $prestataire)
    {
        $profil = $this->em->getRepository('Entity\Profil')->findOneBy(array('code' => $prestataire->getCategorie()->getId()));
        $varRetour = array("succes" => true, "error" => "");
        
        if(!$profil)
        {
            $varRetour["succes"] = false;
            $varRetour["error"] = "Impossible de trouver le profil";
        }
        else
        {
            $codeProfil = $profil->getCode();
            $typeSousProfil = $profil->getTypeSousProfil();
            
            if($typeSousProfil == "centre_hospitalier" || $typeSousProfil == "service_sante")
            {
                $varRetour = $this->viderDonneesUnPrestataireEnregistreur($prestataire);
            }
            else
            {
                $varRetour = $this->viderDonneesUnPrestataireMettrePrix($prestataire);
            }
        }
        
        return $varRetour;
    }
    
    function viderDonneesUneVisite(\Entity\Visite $visite)
    {
        $profil = $this->em->getRepository('Entity\Profil')->findOneBy(array('code' => $visite->getPrestataire()->getCategorie()->getId()));
        $varRetour = array("succes" => true, "error" => "");
        
        if(!$profil)
        {
            $varRetour["succes"] = false;
            $varRetour["error"] = "Impossible de trouver le profil";
        }
        else
        {
            $codeProfil = $profil->getCode();
            $typeSousProfil = $profil->getTypeSousProfil();
            
            if($typeSousProfil == "centre_hospitalier" || $typeSousProfil == "service_sante")
            {
                $varRetour = $this->viderDonneesUneVisiteUnPrestataireEnregistreur($visite);
            }
            else
            {
                $varRetour = $this->viderDonneesUneVisiteUnPrestataireMettrePrix($visite);
            }
        }
        
        return $varRetour;
    }
    
    private function viderDonneesUnPrestataireMettrePrix(\Entity\Prestataire $prestataire)
    {
        $varRetour = array("succes" => true, "error" => "");
        
        // Le dql des LignePrestationAudit
        $dqlLignePrestationAudit  = "SELECT lignePrestAudit FROM Entity\LignePrestationAudit lignePrestAudit
                                     JOIN lignePrestAudit.lignePrestation lignePrest
                                     WHERE lignePrest.prestataire='".$prestataire->getId()."'";
        
        // Le dql des LignePrestation
        $dqlLignePrestation  = "SELECT lignePrest FROM Entity\LignePrestation lignePrest
                                WHERE lignePrest.prestataire='".$prestataire->getId()."'";
        
        
        // Debut du mode transactionnel
        $this->em->getConnection()->beginTransaction(); // On suspend l'auto-commit
        try {
            
            $tabLignePrestationAudit = $this->getPaginator($dqlLignePrestationAudit);
            $tabLignePrestation = $this->getPaginator($dqlLignePrestation);
            
            
            
            var_dump($tabLignePrestationAudit, $tabLignePrestation); exit;
            
            
            $this->supprimerDonneesBd($tabLignePrestationAudit);
            
            $tabLignePrestationTravaille = array();
            foreach ($tabLignePrestation as $uneLignePrestation)
            {
                $tabLignePrestationTravaille[$uneLignePrestation->getId()] = $uneLignePrestation;
                
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setPrestataire(null);
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setEtat("enregistre");
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setTaux(null);
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setValeurModif(null);
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setNbreModif(null);
                
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setValeur(null);
                if($tabLignePrestationTravaille[$uneLignePrestation->getId()]->getPrestation()->getNaturePrestation() == "examen")
                {
                    $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setNbre(null);
                }
            }
            
            $this->em->flush();
            $this->em->getConnection()->commit();
            
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            $this->em->close();
            
            $varRetour["succes"] = false;
            $varRetour["error"] = $e->getMessage();
        }
        
        return $varRetour;
    }
    
    private function viderDonneesUnPrestataireEnregistreur(\Entity\Prestataire $prestataire)
    {
        $varRetour = array("succes" => true, "error" => "");
        
        // Le dql des LignePrestationAudit
        $dqlLignePrestationAudit  = "SELECT lignePrestAudit FROM Entity\LignePrestationAudit lignePrestAudit
                                         JOIN lignePrestAudit.lignePrestation lignePrest
                                         JOIN lignePrest.prestation prest
                                         JOIN prest.visite visit
                                         WHERE visit.prestataire='".$prestataire->getId()."'";
        
        // Le dql des LignePrestation
        $dqlLignePrestation  = "SELECT lignePrest FROM Entity\LignePrestation lignePrest
                                    JOIN lignePrest.prestation prest
                                    JOIN prest.visite visit
                                    WHERE visit.prestataire='".$prestataire->getId()."'";
        
        
        // Le dql des Prestation
        $dqlPrestation  = "SELECT prest FROM Entity\Prestation prest
                               JOIN prest.visite visit
                               WHERE visit.prestataire='".$prestataire->getId()."'";
        
        
        
        
        // Le dql des ConsultationAudit
        $dqlConsultationAudit  = "SELECT consultAudit FROM Entity\ConsultationAudit consultAudit
                                      JOIN consultAudit.consultation consult
                                      JOIN consult.visite visit
                                      WHERE visit.prestataire='".$prestataire->getId()."'";
        
        
        // Le dql des Consultation
        $dqlConsultation  = "SELECT consult FROM Entity\Consultation consult
                                 JOIN consult.visite visit
                                 WHERE visit.prestataire='".$prestataire->getId()."'";
        
        
        // Le dql des Visite
        $dqlVisite  = "SELECT visit FROM Entity\Visite visit
                           WHERE visit.prestataire='".$prestataire->getId()."'";
        
        
        // Debut du mode transactionnel
        $this->em->getConnection()->beginTransaction(); // On suspend l'auto-commit
        try {
            
            $tabLignePrestationAudit = $this->getPaginator($dqlLignePrestationAudit);
            $tabLignePrestation = $this->getPaginator($dqlLignePrestation);
            $tabPrestation = $this->getPaginator($dqlPrestation);
            $tabConsultationAudit = $this->getPaginator($dqlConsultationAudit);
            $tabConsultation = $this->getPaginator($dqlConsultation);
            $tabVisite = $this->getPaginator($dqlVisite);
            
            $this->supprimerDonneesBd($tabLignePrestationAudit);
            $this->supprimerDonneesBd($tabLignePrestation);
            $this->supprimerDonneesBd($tabPrestation);
            $this->supprimerDonneesBd($tabConsultationAudit);
            $this->supprimerDonneesBd($tabConsultation);
            $this->supprimerDonneesBd($tabVisite);
            
            $this->em->flush();
            $this->em->getConnection()->commit();
            
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            $this->em->close();
            
            $varRetour["succes"] = false;
            $varRetour["error"] = $e->getMessage();
        }
        
        return $varRetour;
    }
    
    
    
    
    
    
    
    
    
    
    private function viderDonneesUneVisiteUnPrestataireMettrePrix(\Entity\Visite $visite)
    {
        $varRetour = array("succes" => true, "error" => "");
        
        // Le dql des LignePrestationAudit
        $dqlLignePrestationAudit  = "SELECT lignePrestAudit FROM Entity\LignePrestationAudit lignePrestAudit
                                     JOIN lignePrestAudit.lignePrestation lignePrest
                                     JOIN lignePrest.prestation presta
                                     WHERE presta.visite = '".$visite->getId()."'";
        
        // Le dql des LignePrestation
        $dqlLignePrestation  = "SELECT lignePrest FROM Entity\LignePrestation lignePrest
                                JOIN lignePrest.prestation presta
                                WHERE presta.visite = '".$visite->getId()."'";
        
        
        // Debut du mode transactionnel
        $this->em->getConnection()->beginTransaction(); // On suspend l'auto-commit
        try {
            
            $tabLignePrestationAudit = $this->getPaginator($dqlLignePrestationAudit);
            $tabLignePrestation = $this->getPaginator($dqlLignePrestation);
            
            
            
            var_dump($tabLignePrestationAudit, $tabLignePrestation); exit;
            
            
            $this->supprimerDonneesBd($tabLignePrestationAudit);
            
            $tabLignePrestationTravaille = array();
            foreach ($tabLignePrestation as $uneLignePrestation)
            {
                $tabLignePrestationTravaille[$uneLignePrestation->getId()] = $uneLignePrestation;
                
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setPrestataire(null);
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setEtat("enregistre");
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setTaux(null);
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setValeurModif(null);
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setNbreModif(null);
                
                $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setValeur(null);
                if($tabLignePrestationTravaille[$uneLignePrestation->getId()]->getPrestation()->getNaturePrestation() == "examen")
                {
                    $tabLignePrestationTravaille[$uneLignePrestation->getId()]->setNbre(null);
                }
            }
            
            $this->em->flush();
            $this->em->getConnection()->commit();
            
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            $this->em->close();
            
            $varRetour["succes"] = false;
            $varRetour["error"] = $e->getMessage();
        }
        
        return $varRetour;
    }
    
    private function viderDonneesUneVisiteUnPrestataireEnregistreur(\Entity\Visite $visite)
    {
        $varRetour = array("succes" => true, "error" => "");
        
        // Le dql des LignePrestationAudit
        $dqlLignePrestationAudit  = "SELECT lignePrestAudit FROM Entity\LignePrestationAudit lignePrestAudit
                                         JOIN lignePrestAudit.lignePrestation lignePrest
                                         JOIN lignePrest.prestation prest
                                         JOIN prest.visite visit
                                         WHERE visit.id='".$visite->getId()."'";
        
        // Le dql des LignePrestation
        $dqlLignePrestation  = "SELECT lignePrest FROM Entity\LignePrestation lignePrest
                                    JOIN lignePrest.prestation prest
                                    JOIN prest.visite visit
                                    WHERE visit.id='".$visite->getId()."'";
        
        
        // Le dql des Prestation
        $dqlPrestation  = "SELECT prest FROM Entity\Prestation prest
                               JOIN prest.visite visit
                               WHERE visit.id='".$visite->getId()."'";
        
        
        
        
        // Le dql des ConsultationAudit
        $dqlConsultationAudit  = "SELECT consultAudit FROM Entity\ConsultationAudit consultAudit
                                      JOIN consultAudit.consultation consult
                                      JOIN consult.visite visit
                                      WHERE visit.id='".$visite->getId()."'";
        
        
        // Le dql des Consultation
        $dqlConsultation  = "SELECT consult FROM Entity\Consultation consult
                                 JOIN consult.visite visit
                                 WHERE visit.id='".$visite->getId()."'";
        
        
        // Le dql des Visite
        $dqlVisite  = "SELECT visit FROM Entity\Visite visit
                           WHERE visit.id='".$visite->getId()."'";
        
        
        // Debut du mode transactionnel
        $this->em->getConnection()->beginTransaction(); // On suspend l'auto-commit
        try {
            
            $tabLignePrestationAudit = $this->getPaginator($dqlLignePrestationAudit);
            $tabLignePrestation = $this->getPaginator($dqlLignePrestation);
            $tabPrestation = $this->getPaginator($dqlPrestation);
            $tabConsultationAudit = $this->getPaginator($dqlConsultationAudit);
            $tabConsultation = $this->getPaginator($dqlConsultation);
            $tabVisite = $this->getPaginator($dqlVisite);
            
            $this->supprimerDonneesBd($tabLignePrestationAudit);
            $this->supprimerDonneesBd($tabLignePrestation);
            $this->supprimerDonneesBd($tabPrestation);
            $this->supprimerDonneesBd($tabConsultationAudit);
            $this->supprimerDonneesBd($tabConsultation);
            $this->supprimerDonneesBd($tabVisite);
            
            $this->em->flush();
            $this->em->getConnection()->commit();
            
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            $this->em->close();
            
            $varRetour["succes"] = false;
            $varRetour["error"] = $e->getMessage();
        }
        
        return $varRetour;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    private function supprimerDonneesBd(array $tab)
    {
        foreach ($tab as $element)
        {
            $this->em->remove($element);
        }
    }
}
