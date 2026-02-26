<?php

namespace Application\Manager;


class AdherentManager extends CommonManager
{    
	function getListeAdherentTabParams(array $tabParams)
    {
		$dateServeur= date("Y-m-d");
    	// Construction des variables
        isset($tabParams['assurePrincipal']) ? $assurePrincipal = $tabParams['assurePrincipal'] : $assurePrincipal = "";
		
		isset($tabParams['statut']) ? $statut = $tabParams['statut'] : $statut = null;
    	
        isset($tabParams['nroPage']) ? $nroPage = $tabParams['nroPage'] : $nroPage = 1;
        isset($tabParams['nbreMax']) ? $nbreMax = $tabParams['nbreMax'] : $nbreMax = null;
        isset($tabParams['pagination']) ? $pagination = $tabParams['pagination'] : $pagination = false;
        isset($tabParams['onlyCount']) ? $onlyCount = $tabParams['onlyCount'] : $onlyCount = false;    
        
        isset($tabParams['orderBy']) ? $orderBy = $tabParams['orderBy'] : $orderBy = array("assurePrincipal" => "ASC"); 
        
    	$dqlNbreElt = "";
		
    	$dql = "FROM Entity\Adherent adh
    			 WHERE 1 = 1";
    	
    	if(!empty($assurePrincipal))
			$dql .= " AND adh.assurePrincipal LIKE '$assurePrincipal'";
			
		if(!empty($statut))
    	    $dql .= " AND adh.statut = '$statut'";
		
		
           $dql .= " AND adh.echeancePolice >= '$dateServeur'";
           
		
			
    		
	    if(is_array($orderBy) && count($orderBy) > 0)
	    {
	        $requeteOrderBy = "";
	        foreach ($orderBy as $key => $value)
	        {
	            if($requeteOrderBy == "")
	                $requeteOrderBy .= " ORDER BY adh.".$key." ".$value;
                else
                    $requeteOrderBy .= ", adh.".$key." ".$value;
	        }
	        
	        $dql .= $requeteOrderBy;
	    }
    	
    	
    	if($onlyCount)
    	{
    		$dql = "SELECT COUNT(adh.codeAdherent) ".$dql;
    		$varRetour = $this->getEntityManager()->createQuery($dql)->getSingleScalarResult();
    	}
    	else
    	{
    		if($pagination)
    			$dqlNbreElt = "SELECT COUNT(adh.codeAdherent) ".$dql;
    	
    		$dql = "SELECT adh ".$dql;
    	
    		$varRetour = $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    	}
    	 
    	return $varRetour;
    }
}
