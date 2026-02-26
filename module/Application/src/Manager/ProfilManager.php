<?php

namespace Application\Manager;


class ProfilManager extends CommonManager
{ 
	
	function getListeProfilLangue($codeLangue=null, $statut=null, $supprime=null, $nroPage=1, $nbreMax=null, $pagination=false)
    {
		$dqlNbreElt = "";
		
    	$dql = "FROM Entity\ProfilLangue prfl
    			JOIN prfl.profil prf
    			JOIN prfl.langue lang
    			WHERE 1 = 1";
    	
    	if(!empty($codeLangue))
    		$dql .= " AND lang.code = '".$codeLangue."'";
    	if(!empty($statut))
    		$dql .= " AND prf.statut = '".$statut."'";
    	if(!empty($supprime))
    		$dql .= " AND prf.supprime = '".$supprime."'";
    	
    	$dql .= " ORDER BY prfl.nom ASC";
    	
    	if($pagination)
    		$dqlNbreElt = "SELECT COUNT(prfl.id) ".$dql;
    	 
    	$dql = "SELECT prfl ".$dql;
    	
    	return $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    }
}
