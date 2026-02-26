<?php

namespace Application\Manager;


class LignePrestationAuditManager extends CommonManager
{    
    function getListeLignePrestationAuditTabParams(array $tabParams, array $tabParamsDifferent=array())
    {
    	// Construction des variables
        // isset($tabParams['visite']) ? $visite = $tabParams['visite'] : $visite = null;
        isset($tabParams['lignePrestation']) ? $lignePrestation = $tabParams['lignePrestation'] : $lignePrestation = null;
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
        
        
        
        isset($tabParamsDifferent['etat']) ? $etatDifferent = $tabParamsDifferent['etat'] : $etatDifferent = null;
        
    	
    	
        isset($tabParams['nroPage']) ? $nroPage = $tabParams['nroPage'] : $nroPage = 1;
        isset($tabParams['nbreMax']) ? $nbreMax = $tabParams['nbreMax'] : $nbreMax = null;
        isset($tabParams['pagination']) ? $pagination = $tabParams['pagination'] : $pagination = false;
        isset($tabParams['onlyCount']) ? $onlyCount = $tabParams['onlyCount'] : $onlyCount = false;    
        
        isset($tabParams['orderBy']) ? $orderBy = $tabParams['orderBy'] : $orderBy = array("date" => "DESC"); 
        
    	$dqlNbreElt = "";
		
    	$dql = "FROM Entity\LignePrestationAudit lignePrestaAudit
                 JOIN lignePrestaAudit.lignePrestation lignePresta
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
            $dql .= " AND lignePrestaAudit.etatLignePrestation = '$etat'";
            
        if(!empty($typeExamen))
            $dql .= " AND lignePresta.typeExamen = '$typeExamen'";
        
        if(!empty($dateMin))
            $dql .= " AND lignePrestaAudit.date >= '".$dateMin."'";
        if(!empty($dateMax))
            $dql .= " AND lignePrestaAudit.date <= '".$dateMax."'";
        
            
        if(!empty($dateEncaisseMin))
            $dql .= " AND lignePresta.dateEncaisse >= '".$dateEncaisseMin."'";
        if(!empty($dateEncaisseMax))
            $dql .= " AND lignePresta.dateEncaisse <= '".$dateEncaisseMax."'";
       
            
            
        if(!empty($etatDifferent))
            $dql .= " AND lignePresta.etat <> '".$etatDifferent."'";
         
            
            
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
	                $requeteOrderBy .= " ORDER BY lignePrestaAudit.".$key." ".$value;
                else
                    $requeteOrderBy .= ", lignePrestaAudit.".$key." ".$value;
	        }
	        
	        $dql .= $requeteOrderBy;
	    }
    	
    	
    	if($onlyCount)
    	{
    		$dql = "SELECT COUNT(lignePrestaAudit.id) ".$dql;
    		$varRetour = $this->getEntityManager()->createQuery($dql)->getSingleScalarResult();
    	}
    	else
    	{
    		if($pagination)
    			$dqlNbreElt = "SELECT COUNT(lignePrestaAudit.id) ".$dql;
    	
    		$dql = "SELECT lignePrestaAudit ".$dql;
    	
    		$varRetour = $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    	}
    	 
    	return $varRetour;
    }
}
