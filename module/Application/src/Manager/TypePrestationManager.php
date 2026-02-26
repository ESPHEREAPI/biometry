<?php

namespace Application\Manager;


class TypePrestationManager extends CommonManager
{
    public function getListeTypePrestation($affiche=null, $categorie=null,$categorie2=null,$categorie3=null,$nroPage=1, $nbreMax=null, $pagination=false)
    {
		$dqlNbreElt = "";
		
    	$dql = "FROM Entity\TypePrestation tpres
    			WHERE 1 = 1";
    	
    	if(!empty($affiche))
    		$dql .= " AND tpres.affiche = '".$affiche."'";
    	if(!empty($categorie) and empty($categorie2) and empty($categorie3))
    		$dql .= " AND tpres.categorie = '".$categorie."'";
		elseif(!empty($categorie) and !empty($categorie2) and empty($categorie3)) {
			$dql .= " AND (tpres.categorie = '".$categorie."'";
    		$dql .= " OR tpres.categorie = '".$categorie2."')";
			 }
		elseif(!empty($categorie) and !empty($categorie2) and !empty($categorie3)) {
			$dql .= " AND (tpres.categorie = '".$categorie."'";
    		$dql .= " OR tpres.categorie = '".$categorie2."'";
			$dql .= " OR tpres.categorie = '".$categorie3."')";
			 }
    	
    	
    	$dql .= " ORDER BY tpres.categorie,tpres.nom ASC";
    	 
    	//$dql = "SELECT tpres ".$dql;
    	
    	if($pagination)
    		$dqlNbreElt = "SELECT COUNT(tpres.id) ".$dql;
    	 
    	$dql = "SELECT tpres ".$dql;
    	
    	return $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    }
}
