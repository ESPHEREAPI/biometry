<?php

namespace Application\Manager;


class VilleManager extends CommonManager
{ 
	
	function getListeVilleLangue($pays = null, $region=null, $zone = null,  $statut=null, $supprime=null, 
								 $idLangue=null, $orderBy=array("nom" => "ASC"), $nroPage=1, $nbreMax=null, $pagination=false)
	{
		$dqlNbreElt = "";
		$paramsRequete = array();
		
    	$dql = "FROM Entity\VilleLangue vil_l
    			JOIN vil_l.ville vil
    			JOIN vil.region regi
    			JOIN regi.pays pa
    			JOIN vil_l.langue lang
    			WHERE 1 = 1";
    	
    	if(!empty($pays))
    	{
    		$dql .= " AND pa.id = :idPays";
    		$paramsRequete["idPays"] = "".$pays;
    	}
    	if(!empty($region))
    	{
    		$dql .= " AND regi.id = :idRegion";
    		$paramsRequete["idRegion"] = "".$region;
    	}
    	if(!empty($zone))
    	{
    		$dql .= " AND vil.codeZone = :codeZone";
    		$paramsRequete["codeZone"] = "".$zone;
    	}
    	if(!empty($idLangue))
    	{
    		$dql .= " AND lang.id = :idLangue";
    		$paramsRequete["idLangue"] = "".$idLangue;
    	}
    	if(!empty($statut))
    	{
    		$dql .= " AND vil.statut = :statutVille";
    		$paramsRequete["statutVille"] = "".$statut;
    	}
    	if(!empty($supprime))
    	{
    		$dql .= " AND vil.supprime = :supprimeVille";
    		$paramsRequete["supprimeVille"] = "".$supprime;
    	}
    	
		if(is_array($orderBy) && count($orderBy) > 0)
    	{
    		$requeteOrderBy = "";
    		$tabColonesVille = array('id', 'region', 'code', 'codeZone', 'statut', 'supprime');
    		
    		foreach ($orderBy as $key => $value)
    		{
    			$colOrderBy = "vil_l";
    			if(in_array($key, $tabColonesVille))
    			{
    				$colOrderBy = "vil";
    			}
    			if($requeteOrderBy == "")
    				$requeteOrderBy .= " ORDER BY ".$colOrderBy.".".$key." ".$value;
    			else
    				$requeteOrderBy .= ", ".$colOrderBy.".".$key." ".$value;
    		}
    		
    		$dql .= $requeteOrderBy;
    	}
    	
    	if($pagination)
    		$dqlNbreElt = "SELECT COUNT(vil_l.id) ".$dql;
    	 
    	$dql = "SELECT vil_l ".$dql;
    	
    	return $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination, $paramsRequete);
	}
	
	function getVilleLangueParCode($idVille, $codeLangue)
    {
		$dqlNbreElt = "";
		
    	$dql = "SELECT villang FROM Entity\VilleLangue villang
    			JOIN villang.ville vil
    			JOIN villang.langue lang
    			WHERE vil.id = '".$idVille."'
    			AND lang.code = '".$codeLangue."'";
    	
    	
    	$varRetour = $this->getPaginator($dql, 1, 1, "", false);
    	
    	if(is_array($varRetour) && count($varRetour) > 0)
    		$varRetour = $varRetour[0];
    	else
    		$varRetour = null;
    	
    	return $varRetour;
    }
    
    function getVilleLanguePourZone($codeLangue = null)
    {
    	$dqlNbreElt = "";
    
    	$dql = "SELECT villang FROM Entity\VilleLangue villang
    			JOIN villang.ville vil
    			JOIN villang.langue lang
    			WHERE vil.codeZone IS NOT NULL
    			AND vil.statut = '1'
    			AND vil.supprime = '-1'";
    	
    	if(!empty($codeLangue))
    		$dql .= " AND lang.code = '".$codeLangue."'";
    	 
    	 
    	return $this->getPaginator($dql, 1, null, "", false);
    }
}
