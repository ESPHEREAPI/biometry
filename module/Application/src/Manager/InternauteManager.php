<?php

namespace Application\Manager;

class InternauteManager extends CommonManager
{ 
	
    public function getListeInternaute($statut=null, $genre=null, $supprime=null, $newsletter=null, $nroPage=1, $nbreMax=null, $pagination=false,
									   $orderBy=array("dateCreation" => "DESC"))
    {
		$dqlNbreElt = "";
		
    	$dql = "FROM Entity\Internaute internaute
    			JOIN internaute.utilisateur u
    			WHERE 1 = 1";
    	
    	if(!empty($statut))
    		$dql .= " AND u.statut = '".$statut."'";
    	if(!empty($genre))
    		$dql .= " AND u.genre = '".$genre."'";
    	if(!empty($supprime))
    		$dql .= " AND u.supprime = '".$supprime."'";
    	if(!empty($newsletter))
    		$dql .= " AND u.newsletter = '".$newsletter."'";
    	
    	
    	if(is_array($orderBy) && count($orderBy) > 0)
    	{
    		$requeteOrderBy = "";
    	
    		$tabColonesUtilisateur = array('id', 'genre', 'nom', 'prenom', 'statut',
    									   'supprime', 'newsletter', 'dateCreation');
    	
    		foreach ($orderBy as $key => $value)
    		{
    			$colOrderBy = "internaute";
    			if(in_array($key, $tabColonesUtilisateur))
    			{
    				$colOrderBy = "u";
    			}
    			 
    			 
    			if($requeteOrderBy == "")
    				$requeteOrderBy .= " ORDER BY ".$colOrderBy.".".$key." ".$value;
    			else
    				$requeteOrderBy .= ", ".$colOrderBy.".".$key." ".$value;
    		}
    	
    		$dql .= $requeteOrderBy;
    	}
    	
    	// $dql .= " ORDER BY u.nom ASC, u.prenom";
    	
    	
    	
    	
    	if($pagination)
    		$dqlNbreElt = "SELECT COUNT(internaute.id) ".$dql;
    	 
    	$dql = "SELECT internaute ".$dql;
    	
    	return $this->getPaginator($dql, $nroPage, $nbreMax, $dqlNbreElt, $pagination);
    }
    
    function getInternauteParEmail($email)
    {
    	$dql = "SELECT internaute FROM Entity\Internaute internaute
    					JOIN internaute.utilisateur u
    					WHERE 1 = 1";
    	 

    	$dql .= " AND u.email = '".$email."'";
    	
    	$varRetour = $this->getPaginator($dql, 1, 1, "", false);

    	if(is_array($varRetour) && count($varRetour) > 0)
    		$varRetour = $varRetour[0];
    	else
    		$varRetour = null;
    
    	return $varRetour;
    }
    
    function getInternauteParLogin($login)
    {
    	$dql = "SELECT internaute FROM Entity\Internaute internaute
    					JOIN internaute.utilisateur u
    					WHERE 1 = 1";
    	 

    	$dql .= " AND u.login = '".$login."'";
    	
    	$varRetour = $this->getPaginator($dql, 1, 1, "", false);

    	if(is_array($varRetour) && count($varRetour) > 0)
    		$varRetour = $varRetour[0];
    	else
    		$varRetour = null;
    
    	return $varRetour;
    }
    
    function getInternauteParTelephone($telephone)
    {
        if(empty($telephone))
        {
            return null;
        }
        
        
        $dql = "SELECT internaute FROM Entity\Internaute internaute
    					JOIN internaute.utilisateur u
    					WHERE 1 = 1";
        
        
        $dql .= " AND REPLACE(u.telephone, ' ', '') = '".$telephone."'";
        
        
        // var_dump($dql); exit;
        
        $varRetour = $this->getPaginator($dql, 1, 1, "", false);
        
        if(is_array($varRetour) && count($varRetour) > 0)
            $varRetour = $varRetour[0];
        else
            $varRetour = null;
            
        return $varRetour;
    }
    
	function getInternauteParIdReseauxSociaux($oauthProvider, $oauthUid)
    {
    	$dql = "SELECT internaute FROM Entity\Internaute internaute
    					JOIN internaute.utilisateur u
    					WHERE 1 = 1";
    
    
    	$dql .= " AND u.oauthProvider = '".$oauthProvider."'";
    	$dql .= " AND u.oauthUid = '".$oauthUid."'";
    	 
    	$varRetour = $this->getPaginator($dql, 1, 1, "", false);
    
    	if(is_array($varRetour) && count($varRetour) > 0)
    		$varRetour = $varRetour[0];
    	else
    		$varRetour = null;
    
    	return $varRetour;
    }
}
