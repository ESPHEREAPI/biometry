<?php

namespace Application\Manager;


class AyantDroitManager extends CommonManager
{    
	function getListeAyantDroitTabParams(array $tabParams)
    {
    	// Construction des variables
        isset($tabParams['codeAdherent']) ? $codeAdherent = $tabParams['codeAdherent'] : $codeAdherent = "";
    	
    	
        isset($tabParams['nroPage']) ? $nroPage = $tabParams['nroPage'] : $nroPage = 1;
        isset($tabParams['nbreMax']) ? $nbreMax = $tabParams['nbreMax'] : $nbreMax = null;
        isset($tabParams['pagination']) ? $pagination = $tabParams['pagination'] : $pagination = false;
        isset($tabParams['onlyCount']) ? $onlyCount = $tabParams['onlyCount'] : $onlyCount = false;    
        
        isset($tabParams['orderBy']) ? $orderBy = $tabParams['orderBy'] : $orderBy = array("nom" => "ASC"); 
        
    	$dqlNbreElt = "";
		
    	$dql = "FROM Entity\AyantDroit ad
    			 WHERE 1 = 1";
    	
    	if(!empty($codeAdherent))
    	    $dql .= " AND ad.codeAdherent = '$codeAdherent'";
    	
    		
	    if(is_array($orderBy) && count($orderBy) > 0)
	    {
	        $requeteOrderBy = "";
	        foreach ($orderBy as $key => $value)
	        {
	            if($requeteOrderBy == "")
	                $requeteOrderBy .= " ORDER BY ad.".$key." ".$value;
                else
                    $requeteOrderBy .= ", ad.".$key." ".$value;
	        }
	        
	        $dql .= $requeteOrderBy;
	    }
    	
    	
    	if($onlyCount)
    	{
    		$dql = "SELECT COUNT(ad.codeAyantDroit) ".$dql;
    		$varRetour = $this->getEntityManager()->createQuery($dql)->getSingleScalarResult();
    	}
    	else
    	{
    		if($pagination)
    			$dqlNbreElt = "SELECT COUNT(ad.codeAyantDroit) ".$dql;
    	
    		$dql = "SELECT ad ".$dql;
    	
    		$varRetour = $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    	}
    	 
    	return $varRetour;
    }
}
