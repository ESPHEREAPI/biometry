<?php

namespace Application\Manager;


class RegionManager extends CommonManager
{ 
	
	function getListeRegionLangue($pays = null, $statut=null, $supprime=null, 
								  $idLangue=null, $orderBy=array("nom" => "ASC"), $nroPage=1, $nbreMax=null, $pagination=false)
	{
		$dqlNbreElt = "";
		$paramsRequete = array();
		
    	$dql = "FROM Entity\RegionLangue regi_l
    			JOIN regi_l.region regi
    			JOIN regi.pays pa
    			JOIN regi_l.langue lang
    			WHERE 1 = 1";
    	
    	if(!empty($pays))
    	{
    		$dql .= " AND pa.id = :idPays";
    		$paramsRequete["idPays"] = "".$pays;
    	}
    	if(!empty($idLangue))
    	{
    		$dql .= " AND lang.id = :idLangue";
    		$paramsRequete["idLangue"] = "".$idLangue;
    	}
    	if(!empty($statut))
    	{
    		$dql .= " AND regi.statut = :statutRegion";
    		$paramsRequete["statutRegion"] = "".$statut;
    	}
    	if(!empty($supprime))
    	{
    		$dql .= " AND regi.supprime = :supprimeRegion";
    		$paramsRequete["supprimeRegion"] = "".$supprime;
    	}
    	
		if(is_array($orderBy) && count($orderBy) > 0)
    	{
    		$requeteOrderBy = "";
    		$tabColonesRegion = array('id', 'pays', 'code', 'statut', 'supprime');
    		
    		foreach ($orderBy as $key => $value)
    		{
    			$colOrderBy = "regi_l";
    			if(in_array($key, $tabColonesRegion))
    			{
    				$colOrderBy = "regi";
    			}
    			if($requeteOrderBy == "")
    				$requeteOrderBy .= " ORDER BY ".$colOrderBy.".".$key." ".$value;
    			else
    				$requeteOrderBy .= ", ".$colOrderBy.".".$key." ".$value;
    		}
    		
    		$dql .= $requeteOrderBy;
    	}
    	
    	if($pagination)
    		$dqlNbreElt = "SELECT COUNT(regi_l.id) ".$dql;
    	 
    	$dql = "SELECT regi_l ".$dql;
    	
    	return $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination, $paramsRequete);
	}
	
	function getRegionLangueParCode($idRegion, $codeLangue)
    {
		$dqlNbreElt = "";
		
    	$dql = "SELECT reglang FROM Entity\RegionLangue reglang
    			JOIN regi_l.region regi
    			JOIN reglang.langue lang
    			WHERE regi.id = '".$idRegion."'
    			AND lang.code = '".$codeLangue."'";
    	
    	
    	$varRetour = $this->getPaginator($dql, 1, 1, "", false);
    	
    	if(is_array($varRetour) && count($varRetour) > 0)
    		$varRetour = $varRetour[0];
    	else
    		$varRetour = null;
    	
    	return $varRetour;
    }
}
