<?php

namespace Application\Manager;
use Doctrine\ORM\Query\ResultSetMapping;


class ConsultationManager extends CommonManager
{    
	function getListeConsultationTabParams(array $tabParams)
    {
    	// Construction des variables
        isset($tabParams['visite']) ? $visite = $tabParams['visite'] : $visite = null;
        isset($tabParams['typeConsultation']) ? $typeConsultation = $tabParams['typeConsultation'] : $typeConsultation = null;
		
        isset($tabParams['natureConsultation']) ? $natureConsultation = $tabParams['natureConsultation'] : $natureConsultation = null;
       
        isset($tabParams['etatConsultation']) ? $etatConsultation = $tabParams['etatConsultation'] : $etatConsultation = null;
        isset($tabParams['prestataire']) ? $prestataire = $tabParams['prestataire'] : $prestataire = null;
        isset($tabParams['dateMin']) ? $dateMin = $tabParams['dateMin'] : $dateMin = null;
        // isset($tabParams['dateMax']) ? $dateMax = $tabParams['dateMax'] : $dateMax = null;
        isset($tabParams['dateMax']) && !empty($tabParams['dateMax']) ? $dateMax = $tabParams['dateMax']." 23:59:59" : $dateMax = null;
        isset($tabParams['montantMin']) ? $montantMin = $tabParams['montantMin'] : $montantMin = null;
        isset($tabParams['montantMax']) ? $montantMax = $tabParams['montantMax'] : $montantMax = null;
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
		
    	$dql = "FROM Entity\Consultation consult
                 JOIN consult.visite visit
                 JOIN visit.codeAdherent adherent
    			 WHERE 1 = 1";
    	
    	if(!empty($visite))
    	    $dql .= " AND consult.visite = '$visite'";
    	
	    if(!empty($typeConsultation))
	        $dql .= " AND consult.typeConsultation = '$typeConsultation'";
	    
        if(!empty($etatConsultation))
            $dql .= " AND consult.etatConsultation = '$etatConsultation'";
		
		 if(!empty($natureConsultation))
            $dql .= " AND consult.natureConsultation = '$natureConsultation'";
	    
        if(!empty($prestataire))
            $dql .= " AND visit.prestataire = '$prestataire'";
        
        if(!empty($supprime))
            $dql .= " AND consult.supprime = '$supprime'";
            
        
        if(!empty($dateMin))
            $dql .= " AND consult.date >= '".$dateMin."'";
        if(!empty($dateMax))
            $dql .= " AND consult.date <= '".$dateMax."'";
    	
        if(!empty($montantMin))
            $dql .= " AND consult.montant >= '".$montantMin."'";
        if(!empty($montantMax))
            $dql .= " AND consult.montant <= '".$montantMax."'";
        
        
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
                                                                        			    			           ayantDroit.nom LIKE '%$subQuestion4%'))";
            
            $dql .= $nomDql;
        }
            
            
            
            
	    if(is_array($orderBy) && count($orderBy) > 0)
	    {
	        $requeteOrderBy = "";
	        foreach ($orderBy as $key => $value)
	        {
	            if($requeteOrderBy == "")
	                $requeteOrderBy .= " ORDER BY consult.".$key." ".$value;
                else
                    $requeteOrderBy .= ", consult.".$key." ".$value;
	        }
	        
	        $dql .= $requeteOrderBy;
	    }
    	
    	
    	if($onlyCount)
    	{
    		$dql = "SELECT COUNT(consult.id) ".$dql;
    		$varRetour = $this->getEntityManager()->createQuery($dql)->getSingleScalarResult();
    	}
    	else
    	{
    		if($pagination)
    			$dqlNbreElt = "SELECT COUNT(consult.id) ".$dql;
    	
    		$dql = "SELECT consult ".$dql;
    	
    		$varRetour = $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    	}
    	 
    	return $varRetour;
    }
    
    public function verifierSiPeuxAjouterConsultation(array $tabIdMenu)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Consultation",
            "nomControlleur" => "Index",
            "nomAction" => "ajouter",
            "numeroOrdre" => 1
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxModifierConsultation(array $tabIdMenu)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Consultation",
            "nomControlleur" => "Index",
            "nomAction" => "modifier",
            "numeroOrdre" => 1
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxValiderConsultation(array $tabIdMenu)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Consultation",
            "nomControlleur" => "Index",
            "nomAction" => "validerConsultation",
            "numeroOrdre" => 1
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxDevaliderConsultation(array $tabIdMenu)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Consultation",
            "nomControlleur" => "Index",
            "nomAction" => "devaliderConsultation",
            "numeroOrdre" => 1
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxRejeterConsultation(array $tabIdMenu)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Consultation",
            "nomControlleur" => "Index",
            "nomAction" => "rejeterConsultation",
            "numeroOrdre" => 1
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxDerejeterConsultation(array $tabIdMenu)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Consultation",
            "nomControlleur" => "Index",
            "nomAction" => "derejeterConsultation",
            "numeroOrdre" => 1
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxEncaisserConsultation(array $tabIdMenu)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Consultation",
            "nomControlleur" => "Index",
            "nomAction" => "encaisserConsultation",
            "numeroOrdre" => 1
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    public function verifierSiPeuxImprimerConsultation(array $tabIdMenu)
    {
        $varRetour = false;
        
        $menu = $this->getEntityManager()->getRepository('Entity\Menu')->findOneBy(array('nomModule' => "Consultation",
            "nomControlleur" => "Index",
            "nomAction" => "imprimerRecu",
            "numeroOrdre" => 1
        ));
        
        
        if($menu && in_array($menu->getId(), $tabIdMenu))
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiConsultationValidable(\Entity\Consultation $consultation)
    {
        $varRetour = false;
        // if($consultation->getEtatConsultation() == "attente_validation" || $consultation->getEtatConsultation() == "valide" || $consultation->getEtatConsultation() == "rejete")
        if($consultation->getEtatConsultation() == "attente_validation")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiConsultationDevalidable(\Entity\Consultation $consultation)
    {
        $varRetour = false;
        // if($consultation->getEtatConsultation() == "attente_validation" || $consultation->getEtatConsultation() == "valide" || $consultation->getEtatConsultation() == "rejete")
        if($consultation->getEtatConsultation() == "valide")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiConsultationRejetable(\Entity\Consultation $consultation)
    {
        $varRetour = false;
        // if($consultation->getEtatConsultation() == "attente_validation" || $consultation->getEtatConsultation() == "valide" || $consultation->getEtatConsultation() == "rejete")
        if($consultation->getEtatConsultation() == "attente_validation")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    } 
    
    function vefifierSiConsultationDerejetable(\Entity\Consultation $consultation)
    {
        $varRetour = false;
        // if($consultation->getEtatConsultation() == "attente_validation" || $consultation->getEtatConsultation() == "valide" || $consultation->getEtatConsultation() == "rejete")
        if($consultation->getEtatConsultation() == "rejete")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    } 
    
    function vefifierSiConsultationEncaissable(\Entity\Consultation $consultation)
    {
        $varRetour = false;
        if($consultation->getEtatConsultation() == "valide")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiConsultationImprimable(\Entity\Consultation $consultation)
    {
        $varRetour = false;
        if($consultation->getEtatConsultation() == "encaisse")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiConsultationModifiable(\Entity\Consultation $consultation)
    {
        $varRetour = false;
        if($consultation->getEtatConsultation() == "attente_validation")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
    
    function vefifierSiConsultationSupprimable(\Entity\Consultation $consultation)
    {
        $varRetour = false;
        if($consultation->getEtatConsultation() == "attente_validation")
        {
            $varRetour = true;
        }
        
        return $varRetour;
    }
	
	function consultationPermiseAdherent($code_adherent,$type_consultation,$prestataire)
    {	
		$permise = false;
		
        $sql="SELECT count(c.id) as nb FROM dbx45ty_consultation as c ".
		     "JOIN dbx45ty_visite as v ON c.visite_id = v.id ".
			 "JOIN dbx45ty_adherent as ad ON v.code_adherent=ad.code_adherent ".
			 "WHERE DATEDIFF(now(),c.date) <=? ".
			 "and ad.code_adherent=? ".
			 "and v.code_ayant_droit is ? ".
			 "and c.nature_consultation=? ".
			 "and c.type_consultation=? ".
			 "and v.prestataire_id=? ";
		 


// build rsm here
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('\Entity\Consultation', 'c');
		//$rsm->addJoinedEntityResult('\Entity\Visite','v','c','visite_id');
		//$rsm->addJoinedEntityResult('\Entity\Adherent','ad','v','code_adherent');
	    $rsm->addFieldResult('c', 'nb', 'id');
		

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1,14);
		$query->setParameter(2,$code_adherent );
		$query->setParameter(3,null );
		$query->setParameter(4,'payante' );
		$query->setParameter(5,$type_consultation);
		$query->setParameter(6,$prestataire);
        $donnee = $query->getResult();
       
		if ($donnee[0]->getId()<=4)
		
		   $permise=true;
		
    	return $permise;
    }
	
	
	function consultationPermiseAyantDroit($code_ayant_droit,$type_consultation,$prestataire)
    {	
		$permise = false;
		
			 
		 $sql="SELECT count(c.id) as nb FROM dbx45ty_consultation as c ".
		     "JOIN dbx45ty_visite as v ON c.visite_id = v.id ".
			 "JOIN dbx45ty_ayant_droit as ay ON v.code_ayant_droit=ay.code_ayant_droit ".
			 "WHERE DATEDIFF(now(),c.date) <=? ".
			 "and v.code_ayant_droit=? ".
			 "and ay.code_ayant_droit is not null ".
			 "and c.nature_consultation=? ".
			 "and c.type_consultation=? ".
			 "and v.prestataire_id=? ";


// build rsm here
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('\Entity\Consultation', 'c');
		//$rsm->addJoinedEntityResult('\Entity\Visite','v','c','visite_id');
		//$rsm->addJoinedEntityResult('\Entity\Adherent','ad','v','code_adherent');
	    $rsm->addFieldResult('c', 'nb', 'id');
		

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1,14);
		$query->setParameter(2,$code_ayant_droit );
		$query->setParameter(3,'payante' );
		$query->setParameter(4,$type_consultation);
		$query->setParameter(5,$prestataire);
        $donnee = $query->getResult();
        
		if ($donnee[0]->getId()<=2)
		
		   $permise=true;
		
    	return $permise;
    }
}

