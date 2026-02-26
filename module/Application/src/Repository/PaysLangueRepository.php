<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Core\Utilitaire;

class PaysLangueRepository extends EntityRepository
{
	public function getListePays(array $criteria)
	{
		$utilitaire = new Utilitaire();
		
		$dql = "SELECT py_l FROM Entity\PaysLangue py_l
				JOIN py_l.pays py
				JOIN py_l.langue lang
				WHERE 1 = 1";
		
		if(isset($criteria['supprime']))
			$dql .= " AND py.supprime = '".$criteria['supprime']."'";
		
		if(isset($criteria['statut']))
			$dql .= " AND py.statut = '".$criteria['statut']."'";
		
		if(isset($criteria['code_langue']))
			$dql .= " AND lang.code = '".$criteria['code_langue']."'";

		
		$dql .= " ORDER BY py_l.nom ASC";
		
		$query = $this->_em->createQuery($dql);
		$tab = $query->execute();
		
		foreach ($tab as $element)
		{
			$element->afficheChaine();
		}

		return $tab;
	}
}