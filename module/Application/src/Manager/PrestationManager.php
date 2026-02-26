<?php

namespace Application\Manager;


class PrestationManager extends CommonManager
{    
    function getListePrestationTabParams(array $tabParams)
    {
    	// Construction des variables
        isset($tabParams['visite']) ? $visite = $tabParams['visite'] : $visite = null;
        isset($tabParams['naturePrestation']) ? $naturePrestation = $tabParams['naturePrestation'] : $naturePrestation = null;
        isset($tabParams['prestataire']) ? $prestataire = $tabParams['prestataire'] : $prestataire = null;
        isset($tabParams['dateMin']) ? $dateMin = $tabParams['dateMin'] : $dateMin = null;
        // isset($tabParams['dateMax']) ? $dateMax = $tabParams['dateMax'] : $dateMax = null;
        isset($tabParams['dateMax']) && !empty($tabParams['dateMax']) ? $dateMax = $tabParams['dateMax']." 23:59:59" : $dateMax = null;
        isset($tabParams['dateEncaisseMin']) ? $dateEncaisseMin = $tabParams['dateEncaisseMin'] : $dateEncaisseMin = null;
        isset($tabParams['dateEncaisseMax']) && !empty($tabParams['dateEncaisseMax']) ? $dateEncaisseMax = $tabParams['dateEncaisseMax']." 23:59:59" : $dateEncaisseMax = null;
        
        
        isset($tabParams['supprime']) ? $supprime = $tabParams['supprime'] : $supprime = null;
		 isset($tabParams['souscripteur']) ? $souscripteur = $tabParams['souscripteur'] : $souscripteur = null;
        
        isset($tabParams['nomAdherent']) ? $nomAdherent = $tabParams['nomAdherent'] : $nomAdherent = null;
        isset($tabParams['nomAyantDroit']) ? $nomAyantDroit = $tabParams['nomAyantDroit'] : $nomAyantDroit = null;
    	
    	
        isset($tabParams['nroPage']) ? $nroPage = $tabParams['nroPage'] : $nroPage = 1;
        isset($tabParams['nbreMax']) ? $nbreMax = $tabParams['nbreMax'] : $nbreMax = null;
        isset($tabParams['pagination']) ? $pagination = $tabParams['pagination'] : $pagination = false;
        isset($tabParams['onlyCount']) ? $onlyCount = $tabParams['onlyCount'] : $onlyCount = false;    
        
        isset($tabParams['orderBy']) ? $orderBy = $tabParams['orderBy'] : $orderBy = array("date" => "DESC"); 
        
    	$dqlNbreElt = "";
		
    	$dql = "FROM Entity\Prestation presta
                 JOIN presta.visite visit
                 JOIN visit.codeAdherent adherent
    			 WHERE 1 = 1";
    	
    	
    	
//     	$dql2 = "FROM Entity\Prestation presta
//                     JOIN presta.visite visit
//                     WHERE EXISTS 
//                         (SELECT lignePresta FROM Entity\LignePrestation lignePresta WHERE lignePresta.prestation = presta.id AND lignePresta.prestataire = '".$prestataire."')";
    	
    	
    	
    	if(!empty($visite))
    	    $dql .= " AND presta.visite = '$visite'";
    	
	    if(!empty($naturePrestation))
	        $dql .= " AND presta.naturePrestation = '$naturePrestation'";
	    
        if(!empty($prestataire))
        {
            $dql .= " AND (presta.prestataire = '$prestataire'
                           OR EXISTS (
                                        SELECT lignePresta FROM Entity\LignePrestation lignePresta WHERE lignePresta.prestation = presta.id AND lignePresta.prestataire = '".$prestataire."'
                                   )
                          )";
        }
        
        if(!empty($dateEncaisseMin) || !empty($dateEncaisseMax))
        {
            $dql .= " AND EXISTS (SELECT lignePrestaOther FROM Entity\LignePrestation lignePrestaOther WHERE lignePrestaOther.prestation = presta.id";
            
            if(!empty($dateEncaisseMin))
                $dql .= " AND lignePrestaOther.dateEncaisse >= '$dateEncaisseMin'";
            
                if(!empty($dateEncaisseMax))
                    $dql .= " AND lignePrestaOther.dateEncaisse <= '$dateEncaisseMax'";
            
            $dql .=   ")";
        }
        
        // echo($dql); exit;
            
        
        if(!empty($supprime))
            $dql .= " AND presta.supprime = '$supprime'";
        
        if(!empty($dateMin))
            $dql .= " AND presta.date >= '".$dateMin."'";
        if(!empty($dateMax))
            $dql .= " AND presta.date <= '".$dateMax."'";
		
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
                
               
            }
            $nomDql .= " AND EXISTS (SELECT ayantDroit FROM Entity\AyantDroit ayantDroit WHERE ayantDroit.codeAyantDroit = visit.codeAyantDroit
                AND (ayantDroit.nom LIKE '%$subQuestion2%' OR
                ayantDroit.nom LIKE '%$subQuestion3%' OR
                ayantDroit.nom LIKE '%$subQuestion4%')
                                       )";
            $dql .= $nomDql;
        }
            
            
	    if(is_array($orderBy) && count($orderBy) > 0)
	    {
	        $requeteOrderBy = "";
	        foreach ($orderBy as $key => $value)
	        {
	            if($requeteOrderBy == "")
	                $requeteOrderBy .= " ORDER BY presta.".$key." ".$value;
                else
                    $requeteOrderBy .= ", presta.".$key." ".$value;
	        }
	        
	        $dql .= $requeteOrderBy;
	    }
    	
	    
	    // echo($dql); exit;
    	
    	if($onlyCount)
    	{
    		$dql = "SELECT COUNT(presta.id) ".$dql;
    		$varRetour = $this->getEntityManager()->createQuery($dql)->getSingleScalarResult();
    	}
    	else
    	{
    		if($pagination)
    			$dqlNbreElt = "SELECT COUNT(presta.id) ".$dql;
    	
    		$dql = "SELECT presta ".$dql;
    	
    		$varRetour = $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    	}
    	 
    	return $varRetour;
    }
    
    public function verifierSiPeuxAjouterPrestation(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Prestation",
                                    "nomControlleur" => "Index",
                                    "nomAction" => "ajouter",
                                    "numeroOrdre" => $numeroOrdre
                                ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
	
	public function verifierSiPeuxAjouterHospitalisation(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Hospitalisation",
                                    "nomControlleur" => "Index",
                                    "nomAction" => "ajouter",
                                    "numeroOrdre" => $numeroOrdre
                                ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
	
	public function verifierSiPeuxAjouterDentisterie(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Dentisterie",
                                    "nomControlleur" => "Index",
                                    "nomAction" => "ajouter",
                                    "numeroOrdre" => $numeroOrdre
                                ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxModifierPrestation(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Prestation",
            "nomControlleur" => "Index",
            "nomAction" => "modifier",
            "numeroOrdre" => $numeroOrdre
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
	
	 public function verifierSiPeuxModifierDentisterie(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Dentisterie",
            "nomControlleur" => "Index",
            "nomAction" => "modifier",
            "numeroOrdre" => $numeroOrdre
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxImprimerPrestation(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Prestation",
            "nomControlleur" => "Index",
            "nomAction" => "imprimerRecu",
            "numeroOrdre" => $numeroOrdre
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
	
	
	public function verifierSiPeuxImprimerDentisterie(array $tabIdMenu, $numeroOrdre=1)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Dentisterie",
            "nomControlleur" => "Index",
            "nomAction" => "imprimerRecu",
            "numeroOrdre" => $numeroOrdre
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
	
	public function verifierSiPeuxModifierHospitalisation(array $tabIdMenu, $numeroOrdre)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Hospitalisation",
            "nomControlleur" => "Index",
            "nomAction" => "modifier",
            "numeroOrdre" => $numeroOrdre
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxImprimerHospitalisation(array $tabIdMenu, $numeroOrdre=1)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Hospitalisation",
            "nomControlleur" => "Index",
            "nomAction" => "imprimerRecu",
            "numeroOrdre" => $numeroOrdre
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiPrestationImprimable(\Entity\Prestation $prestation, $prestataireId=null)
    {
        $varRetour = false;

        $params = array('prestation' => $prestation->getId(), "etat" => "encaisse");
        
        if($prestataireId)
        {
            $params['prestataire'] = $prestataireId;
        }
        
        
        $tabLignePrestation = $this->getEntityManager()->getRepository('Entity\LignePrestation')->findBy($params);
        if(is_array($tabLignePrestation) && count($tabLignePrestation) > 0)
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
	
	
	    function vefifierSiHospitalisationImprimable(\Entity\Prestation $prestation, $prestataireId=null)
    {
        $varRetour = false;

        $params = array('prestation' => $prestation->getId(), "etat" => "valide");
        
        if($prestataireId)
        {
            $params['prestataire'] = $prestataireId;
        }
        
        
        $tabLignePrestation = $this->getEntityManager()->getRepository('Entity\LignePrestation')->findBy($params);
        if(is_array($tabLignePrestation) && count($tabLignePrestation) > 0)
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiPrestationModifiable(\Entity\Prestation $prestation)
    {
        $varRetour = true;
        
        $params = array("prestation" => $prestation->getId());
        $paramsDifferent = array("etat" => "enregistre");        
        
        $tabLignePrestation = $this->getEntityManager()->getRepository('Entity\LignePrestation')->findBy($params);
        foreach ($tabLignePrestation as $uneLignePrestation)
        {
            if($uneLignePrestation->getEtat() != "enregistre")
            {
                $varRetour = false;
                break;
            }
        }
        
        return $varRetour;
    }
	
	function vefifierSiHospitalisationModifiable(\Entity\Prestation $prestation)
    {
        $varRetour = false;
        
        $params = array("prestation" => $prestation->getId());
        $paramsDifferent = array("etat" => "enregistre");        
        
        $tabLignePrestation = $this->getEntityManager()->getRepository('Entity\LignePrestation')->findBy($params);
        foreach ($tabLignePrestation as $uneLignePrestation)
        {
            if(($uneLignePrestation->getEtat() == "enregistre") || ($uneLignePrestation->getEtat() == "valide"))
            {
                $varRetour = true;
                break;
            }
        }
        
        return $varRetour;
    }
    
	
    function vefifierSiPrestationSupprimable(\Entity\Prestation $prestation)
    {
        $varRetour = false;
//         if($prestation->getEtatPrestation() == "attente_validation")
//         {
//             $varRetour = true;
//         }
        
        return $varRetour;
    }
    
    function getNbreLignePrestationAttenteValidation(\Entity\Prestation $prestation)
    {
        $dqlNbreElt = "";
        
        $dql = "SELECT COUNT(lignePresta.id) FROM Entity\LignePrestation lignePresta
                WHERE lignePresta.prestation = '".$prestation->getId()."'
                AND lignePresta.etat = 'attente_validation'";
        
        return $this->getEntityManager()->createQuery($dql)->getSingleScalarResult();
    }
	
	function getNbreLignePrestationEnregistre(\Entity\Prestation $prestation)
    {
        $dqlNbreElt = "";
        
        $dql = "SELECT COUNT(lignePresta.id) FROM Entity\LignePrestation lignePresta
                WHERE lignePresta.prestation = '".$prestation->getId()."'
                AND lignePresta.etat = 'enregistre'";
        
        return $this->getEntityManager()->createQuery($dql)->getSingleScalarResult();
    }
	
	 function getNbreLignePrestationValide(\Entity\Prestation $prestation)
    {
        $dqlNbreElt = "";
        
        $dql = "SELECT COUNT(lignePresta.id) FROM Entity\LignePrestation lignePresta
                WHERE lignePresta.prestation = '".$prestation->getId()."'
                AND lignePresta.etat = 'valide'";
        
        return $this->getEntityManager()->createQuery($dql)->getSingleScalarResult();
    }
}
