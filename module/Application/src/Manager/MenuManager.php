<?php

namespace Application\Manager;


class MenuManager extends CommonManager
{ 
	
	function getListeMenuLangue($statut=null, $codeLangue=null, $idPere=-1, $codeProfilEmploye=null, 
								$apparaitNavBar=null, $type=null, $url=null, $apparaitNav=null, 
								$supprime=null, $nroPage=1, $nbreMax=null, $pagination=false, 
								$orderBy=array("position" => "ASC"), $idLangue=null
							   )
    {
		$dqlNbreElt = "";
		
    	$dql = "FROM Entity\MenuLangue mnlang
    			JOIN mnlang.menu mn
    			JOIN mnlang.langue lang
    			WHERE 1 = 1";
    	
    	if(!empty($statut))
    		$dql .= " AND mn.statut = '".$statut."'";
    	
    	if(!empty($idLangue))
    		$dql .= " AND lang.id = '".$idLangue."'";
    	
    	if(!empty($codeLangue))
    		$dql .= " AND lang.code = '".$codeLangue."'";
    	if(empty($idPere))
    		$dql .= " AND mn.pere IS NULL";
    	elseif($idPere != -1)
    		$dql .= " AND mn.pere = '".$idPere."'";
    	
    	if($type == 2 && !empty($codeProfilEmploye) && $codeProfilEmploye != "SUP_ADMIN")
		{
			$dql .= " AND EXISTS (SELECT perm FROM Entity\Permission perm
    							JOIN perm.profil pr
    							WHERE perm.menu = mn.id
    							AND pr.code = '".$codeProfilEmploye."')";
		}
		
		if(!empty($apparaitNavBar))
			$dql .= " AND mn.apparaitNavBar = '".$apparaitNavBar."'";
		
    	if(!empty($type))
    		$dql .= " AND mn.type = '".$type."'";
    		
    	if(!empty($url))
    		$dql .= " AND mnlang.url = '".$url."'";
    	
    	if(!empty($apparaitNav))
    		$dql .= " AND mn.apparaitNav = '".$apparaitNav."'";
    		
    	if(!empty($supprime))
    		$dql .= " AND mn.supprime = '".$supprime."'";
    		
		if(is_array($orderBy) && count($orderBy) > 0)
    	{
    		$requeteOrderBy = "";
    		
    		$tabColonesMenu = array('id', '	pere', 'nomControlleur', 'nomModule', 'nomAction', 
    								'classImage', 'type', 'position', 'apparaitNav',
    								'apparaitNavBar', 'statut', 'supprime', 'cheminPere');
    		
    		foreach ($orderBy as $key => $value)
    		{
    			$colOrderBy = "mnlang";
    			if(in_array($key, $tabColonesMenu))
    			{
    				$colOrderBy = "mn";
    			}
    			
    			
    			if($requeteOrderBy == "")
    				$requeteOrderBy .= " ORDER BY ".$colOrderBy.".".$key." ".$value;
    			else
    				$requeteOrderBy .= ", ".$colOrderBy.".".$key." ".$value;
    		}
    		
    		$dql .= $requeteOrderBy;
    	}
    	
    	if($pagination)
    		$dqlNbreElt = "SELECT COUNT(mnlang.id) ".$dql;
    	 
    	$dql = "SELECT mnlang ".$dql;
    	
    	
    	
    	// var_dump($dql); exit;
    	
    	return $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    }
}