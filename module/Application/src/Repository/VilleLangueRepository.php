<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class VilleLangueRepository extends EntityRepository
{
	public function getListeVille(array $criteria)
	{
		$dql = "SELECT vl FROM Entity\VilleLangue vl
				JOIN vl.ville vil
				JOIN vl.langue lang
				JOIN vil.region regi
				JOIN regi.pays pay
				WHERE 1 = 1";
		
		if(isset($criteria['supprime']))
			$dql .= " AND vil.supprime = '".$criteria['supprime']."'";
		
		if(isset($criteria['statut']))
			$dql .= " AND vil.statut = '".$criteria['statut']."'";
		
		if(isset($criteria['id_region']))
			$dql .= " AND regi.id = '".$criteria['id_region']."'";
		
		if(isset($criteria['id_pays']))
			$dql .= " AND pay.id = '".$criteria['id_pays']."'";
		
		if(isset($criteria['id_langue']))
			$dql .= " AND vl.langue = '".$criteria['id_langue']."'";
		
		if(isset($criteria['code_langue']))
			$dql .= " AND lang.code = '".$criteria['code_langue']."'";
		
		
		
		// $dql .= " AND pay. = '2'";
		$dql .= " AND vl.nom <> ''";
		
		$dql .= " ORDER BY vl.nom ASC";
		
		
		$query = $this->_em->createQuery($dql);
		$tab = $query->execute();
		
		foreach ($tab as $element)
		{
			$element->afficheChaine();
		}
		
		return $tab;
	}
}