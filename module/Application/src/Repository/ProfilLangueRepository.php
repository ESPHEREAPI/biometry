<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class ProfilLangueRepository extends EntityRepository
{
	public function getListeProfil(array $criteria)
	{
		$dql = "SELECT prl FROM Entity\ProfilLangue prl
				JOIN prl.profil pr
				WHERE 1 = 1";
		
		if(isset($criteria['supprime']))
			$dql .= " AND pr.supprime = '".$criteria['supprime']."'";
		
		if(isset($criteria['statut']))
			$dql .= " AND pr.statut = '".$criteria['statut']."'";
		
		if(isset($criteria['id_langue']))
			$dql .= " AND prl.langue = '".$criteria['id_langue']."'";

		
		$query = $this->_em->createQuery($dql);
		$tab = $query->execute();
		
		return $tab;
	}
}