<?php

namespace Application\Manager;


class LignePrestationManager extends CommonManager
{    
    function getListeLignePrestationTabParams(array $tabParams, array $tabParamsDifferent=array())
    {
    	// Construction des variables
        // isset($tabParams['visite']) ? $visite = $tabParams['visite'] : $visite = null;
        isset($tabParams['prestation']) ? $prestation = $tabParams['prestation'] : $prestation = null;
        isset($tabParams['prestataire']) ? $prestataire = $tabParams['prestataire'] : $prestataire = null;
        isset($tabParams['naturePrestation']) ? $naturePrestation = $tabParams['naturePrestation'] : $naturePrestation = null;
        isset($tabParams['typeExamen']) ? $typeExamen = $tabParams['typeExamen'] : $typeExamen = null;
        isset($tabParams['prestataireEnregistreur']) ? $prestataireEnregistreur = $tabParams['prestataireEnregistreur'] : $prestataireEnregistreur = null;
        isset($tabParams['dateMin']) ? $dateMin = $tabParams['dateMin'] : $dateMin = null;
        isset($tabParams['dateMax'])  && !empty($tabParams['dateMax']) ? $dateMax = $tabParams['dateMax']." 23:59:59" : $dateMax = null;
        
        
        isset($tabParams['dateEncaisseMin']) ? $dateEncaisseMin = $tabParams['dateEncaisseMin'] : $dateEncaisseMin = null;
        isset($tabParams['dateEncaisseMax']) && !empty($tabParams['dateEncaisseMax']) ? $dateEncaisseMax = $tabParams['dateEncaisseMax']." 23:59:59" : $dateEncaisseMax = null;
        
        
        isset($tabParams['etat']) ? $etat = $tabParams['etat'] : $etat = null;
        isset($tabParams['supprime']) ? $supprime = $tabParams['supprime'] : $supprime = null;
		
		 isset($tabParams['souscripteur']) ? $souscripteur = $tabParams['souscripteur'] : $souscripteur = null;
        
        isset($tabParams['nomAdherent']) ? $nomAdherent = $tabParams['nomAdherent'] : $nomAdherent = null;
        isset($tabParams['nomAyantDroit']) ? $nomAyantDroit = $tabParams['nomAyantDroit'] : $nomAyantDroit = null;
        
        isset($tabParamsDifferent['etat']) ? $etatDifferent = $tabParamsDifferent['etat'] : $etatDifferent = null;
        
    	
    	
        isset($tabParams['nroPage']) ? $nroPage = $tabParams['nroPage'] : $nroPage = 1;
        isset($tabParams['nbreMax']) ? $nbreMax = $tabParams['nbreMax'] : $nbreMax = null;
        isset($tabParams['pagination']) ? $pagination = $tabParams['pagination'] : $pagination = false;
        isset($tabParams['onlyCount']) ? $onlyCount = $tabParams['onlyCount'] : $onlyCount = false;    
        
        isset($tabParams['orderBy']) ? $orderBy = $tabParams['orderBy'] : $orderBy = array("date" => "DESC"); 
        
    	$dqlNbreElt = "";
		
    	$dql = "FROM Entity\LignePrestation lignePresta
                 JOIN lignePresta.prestation prestation
                 JOIN prestation.visite visit
                 JOIN visit.codeAdherent adherent
    			 WHERE 1 = 1";
    	
    	
    	if(!empty($prestation))
	        $dql .= " AND prestation.id = '$prestation'";
	        
        if(!empty($prestataire))
            $dql .= " AND lignePresta.prestataire = '$prestataire'";
        
        if(!empty($naturePrestation))
            $dql .= " AND prestation.naturePrestation = '$naturePrestation'";
	    
	    if(!empty($prestataireEnregistreur))
            $dql .= " AND visit.prestataire = '$prestataireEnregistreur'";
        
        if(!empty($supprime))
            $dql .= " AND lignePresta.supprime = '$supprime'";
        
        if(!empty($etat))
            $dql .= " AND lignePresta.etat = '$etat'";
            
        if(!empty($typeExamen))
            $dql .= " AND lignePresta.typeExamen = '$typeExamen'";
        
        if(!empty($dateMin))
            $dql .= " AND lignePresta.date >= '".$dateMin."'";
        if(!empty($dateMax))
            $dql .= " AND lignePresta.date <= '".$dateMax."'";
        
            
        if(!empty($dateEncaisseMin))
            $dql .= " AND lignePresta.dateEncaisse >= '".$dateEncaisseMin."'";
        if(!empty($dateEncaisseMax))
            $dql .= " AND lignePresta.dateEncaisse <= '".$dateEncaisseMax."'";
       
            
            
        if(!empty($etatDifferent))
            $dql .= " AND lignePresta.etat <> '".$etatDifferent."'";
         
         if(!empty($souscripteur))
        {
            $nomDql = "";
            $explodeQuestion = explode(" ", $souscripteur);
            foreach($explodeQuestion as $uneQuestion)
            {
                $subQuestion1 = trim($uneQuestion);
                $subQuestion2 = htmlentities($subQuestion1, ENT_COMPAT, "UTF-8");
                $subQuestion2 = str_replace("'", "&#39;", $subQuestion2);
                $subQuestion3 = mb_convert_encoding($subQuestion1, "UTF-8");
                $subQuestion3 = str_replace("'", " ", $subQuestion3);
                
                
                $subQuestion4 = str_replace("'", " ", $subQuestion1);
                $subQuestion4 = str_replace('"', " ", $subQuestion4);
                
                $nomDql .= " AND (adherent.souscripteur LIKE '%$subQuestion2%' OR
			    			      adherent.souscripteur LIKE '%$subQuestion3%' OR
			    			      adherent.souscripteur LIKE '%$subQuestion4%')";
            }
            
            $dql .= $nomDql;
        }  
            
        if(!empty($nomAdherent))
        {
            $nomDql = "";
            $explodeQuestion = explode(" ", $nomAdherent);
            foreach($explodeQuestion as $uneQuestion)
            {
                $subQuestion1 = trim($uneQuestion);
                $subQuestion2 = htmlentities($subQuestion1, ENT_COMPAT, "UTF-8");
                $subQuestion2 = str_replace("'", "&#39;", $subQuestion2);
                $subQuestion3 = mb_convert_encoding($subQuestion1, "UTF-8");
                $subQuestion3 = str_replace("'", " ", $subQuestion3);
                
                
                $subQuestion4 = str_replace("'", " ", $subQuestion1);
                $subQuestion4 = str_replace('"', " ", $subQuestion4);
                
                $nomDql .= " AND (adherent.assurePrincipal LIKE '%$subQuestion2%' OR
	    			          adherent.assurePrincipal LIKE '%$subQuestion3%' OR
	    			          adherent.assurePrincipal LIKE '%$subQuestion4%')";
            }
            
            $dql .= $nomDql;
        }
        
        if(!empty($nomAyantDroit))
        {
            $nomDql = "";
            $explodeQuestion = explode(" ", $nomAyantDroit);
            foreach($explodeQuestion as $uneQuestion)
            {
                $subQuestion1 = trim($uneQuestion);
                $subQuestion2 = htmlentities($subQuestion1, ENT_COMPAT, "UTF-8");
                $subQuestion2 = str_replace("'", "&#39;", $subQuestion2);
                $subQuestion3 = mb_convert_encoding($subQuestion1, "UTF-8");
                $subQuestion3 = str_replace("'", " ", $subQuestion3);
                
                
                $subQuestion4 = str_replace("'", " ", $subQuestion1);
                $subQuestion4 = str_replace('"', " ", $subQuestion4);
                
                $nomDql .= " AND EXISTS (SELECT ayantDroit FROM Entity\AyantDroit ayantDroit WHERE ayantDroit.codeAyantDroit = visit.codeAyantDroit
                                                                                                      AND (ayantDroit.nom LIKE '%$subQuestion2%' OR
                                                                    			    			           ayantDroit.nom LIKE '%$subQuestion3%' OR
                                                                    			    			           ayantDroit.nom LIKE '%$subQuestion4%')
                                   )";
            }
            
            $dql .= $nomDql;
        }
        
            
	    if(is_array($orderBy) && count($orderBy) > 0)
	    {
	        $requeteOrderBy = "";
	        foreach ($orderBy as $key => $value)
	        {
	            if($requeteOrderBy == "")
	                $requeteOrderBy .= " ORDER BY lignePresta.".$key." ".$value;
                else
                    $requeteOrderBy .= ", lignePresta.".$key." ".$value;
	        }
	        
	        $dql .= $requeteOrderBy;
	    }
    	
    	
    	if($onlyCount)
    	{
    		$dql = "SELECT COUNT(lignePresta.id) ".$dql;
    		$varRetour = $this->getEntityManager()->createQuery($dql)->getSingleScalarResult();
    	}
    	else
    	{
    		if($pagination)
    			$dqlNbreElt = "SELECT COUNT(lignePresta.id) ".$dql;
    	
    		$dql = "SELECT lignePresta ".$dql;
    	
    		$varRetour = $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    	}
    	 
    	return $varRetour;
    }
    
    
    
    
    
    
    
    
    
    
    
    
//     public function verifierSiPeuxAjouterLignePrestation(array $tabIdMenu)
//     {
//         $varRetour = false;
        
//         $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Prestation",
//             "nomControlleur" => "LignePrestation",
//             "nomAction" => "ajouter",
//             "numeroOrdre" => $numeroOrdre
//         ));
        
        
//         if($menu && in_array($menu->getId(), $tabIdMenu))
//         {
//             $varRetour = true;
//         }
        
//         return $varRetour;
//     }
    
    public function verifierSiPeuxMettrePrixLignePrestation(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Prestation",
                    "nomControlleur" => "LignePrestation",
                    "nomAction" => "mettrePrix",
                    "numeroOrdre" => $numeroOrdre
                ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxValiderRejeterLignePrestation(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Prestation",
            "nomControlleur" => "LignePrestation",
            "nomAction" => "validerRejeter",
            "numeroOrdre" => $numeroOrdre
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxEncaisserLignePrestation(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Prestation",
            "nomControlleur" => "LignePrestation",
            "nomAction" => "encaisser",
            "numeroOrdre" => $numeroOrdre
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }    
    
	public function verifierSiPeuxMettrePrixLigneDentisterie(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Dentisterie",
                    "nomControlleur" => "LignePrestation",
                    "nomAction" => "mettrePrix",
                    "numeroOrdre" => $numeroOrdre
                ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxValiderRejeterLigneDentisterie(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Dentisterie",
            "nomControlleur" => "LignePrestation",
            "nomAction" => "validerRejeter",
            "numeroOrdre" => $numeroOrdre
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxEncaisserLigneDentisterie(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Dentisterie",
            "nomControlleur" => "LignePrestation",
            "nomAction" => "encaisser",
            "numeroOrdre" => $numeroOrdre
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }  
    function vefifierSiLignePrestationValidable(\Entity\LignePrestation $lignePrestation)
    {
        $varRetour = false;
        // if($lignePrestation->getEtat() == "attente_validation" || $lignePrestation->getEtat() == "valide" || $lignePrestation->getEtat() == "rejete")
        if($lignePrestation->getEtat() == "attente_validation")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiLignePrestationDevalidable(\Entity\LignePrestation $lignePrestation)
    {
        $varRetour = false;
        // if($lignePrestation->getEtat() == "attente_validation" || $lignePrestation->getEtat() == "valide" || $lignePrestation->getEtat() == "rejete")
        if($lignePrestation->getEtat() == "valide")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiLignePrestationRejetable(\Entity\LignePrestation $lignePrestation)
    {
        $varRetour = false;
        // if($lignePrestation->getEtat() == "attente_validation" || $lignePrestation->getEtat() == "valide" || $lignePrestation->getEtat() == "rejete")
        if($lignePrestation->getEtat() == "attente_validation")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiLignePrestationDerejetable(\Entity\LignePrestation $lignePrestation)
    {
        $varRetour = false;
        // if($lignePrestation->getEtat() == "attente_validation" || $lignePrestation->getEtat() == "valide" || $lignePrestation->getEtat() == "rejete")
        if($lignePrestation->getEtat() == "rejete")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiLignePrestationEncaissable(\Entity\LignePrestation $lignePrestation, $prestataireId=null)
    {
        $varRetour = false;
        if($lignePrestation->getEtat() == "valide")
        {
            $varRetour = true;
        }
        
        if($prestataireId)
        {
            if(!$lignePrestation->getPrestataire() || ($prestataireId != $lignePrestation->getPrestataire()->getId()))
            {
                $varRetour = false;
            }
        }
        
        return $varRetour;
    }
    
    function vefifierSiLignePrestationMettrePrix(\Entity\LignePrestation $lignePrestation)
    {
        $varRetour = false;
        if($lignePrestation->getEtat() == "enregistre")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiLignePrestationModifiable(\Entity\LignePrestation $lignePrestation)
    {
        $varRetour = false;
        if($lignePrestation->getEtat() == "enregistre" || $lignePrestation->getEtat() == "attente_validation")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
	
	function vefifierSiLigneHospitalisationModifiable(\Entity\LignePrestation $lignePrestation)
    {
        $varRetour = false;
        if($lignePrestation->getEtat() == "enregistre" || $lignePrestation->getEtat() == "valide")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiLignePrestationSupprimable(\Entity\LignePrestation $lignePrestation)
    {
        $varRetour = false;
//         if($lignePrestation->getEtat() == "attente_validation")
//         {
//             $varRetour = true;
//         }
        
        return $varRetour;
    }
}
