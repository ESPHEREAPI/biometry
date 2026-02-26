<?php

namespace Application\Manager;
 
use Zend\Log\Logger;
use Zend\Log\Writer\Db AS DbLogWriter;

use Zend\ServiceManager\ServiceManager;

class CommonManager
{ 
// 	/**
// 	 * @var Doctrine\ORM\EntityManager
// 	 */
	public $em;
	public $serviceManager;
	
	const NBRE_LIGNE_TABLEAU = 10;
	const NBRE_PAGE_PAGINATION = 5;
	
	public function getServiceManager()
	{
		return $this->serviceManager;
	}
	
	/**
	 * Set service manager instance
	 *
	 * @param ServiceManager $locator
	 * @return CommonManager
	 */
	public function setServiceManager(ServiceManager $serviceManager)
	{
		$this->serviceManager = $serviceManager;
		if (null === $this->em) {
			$this->em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		}
		return $this;
	}
	
	public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}
	
	
	/**
	 * CommonController::getPaginator()
	 *
	 * @param mixed $dql
	 * @param mixed $nroPage
	 * @param mixed $nbreMax
	 * @return void
	 */
	public function getPaginator($dql, $nroPage=1, $nbreMax=null, $dqlNbreElt="", $pagination=false, $tabParametres=array())
	{
		$premierElt = $nbreMax*($nroPage-1);
		$tabRetour = null;
		
		$query = $this->em->createQuery($dql);
		
		// Pour la protection contre l'injection SQL
		if(is_array($tabParametres) && count($tabParametres) > 0)
		{
			foreach($tabParametres as $key => $valeur)
			{
				$query->setParameter($key, $valeur);
			}
		}
		 
		if($premierElt)
			$query->setFirstResult($premierElt);
		if($nbreMax)
			$query->setMaxResults($nbreMax);
		 
		$tabRetour = $query->execute();
		
		 
		$totalResult = 0;
		if($pagination && $dqlNbreElt != "")
		{
			$queryTotalResult = $this->em->createQuery($dqlNbreElt);
			
			// Pour la protection contre l'injection SQL
			if(is_array($tabParametres) && count($tabParametres) > 0)
			{
				foreach($tabParametres as $key => $valeur)
				{
					$queryTotalResult->setParameter($key, $valeur);
				}
			}
			
			$totalResult = $queryTotalResult->getSingleScalarResult();
		}
		 
		if($pagination)
		{
			return array('tab' => $tabRetour,
						 'totalResult' => $totalResult);
		}
		else
		{
			return $tabRetour;
		}
	}
	
	/**
	 * CommonController::logToDB()
	 *
	 * @param int $priority Peut accepter les valeurs : information = 0; avertissement = 1; erreur = 2
	 * @param string $title
	 * @param string $message
	 * @param \Entity\Utilisateur $utilisateur
	 * @return array
	 */
	public function logToDB ($priority, $title=null, $message, \Entity\Utilisateur $utilisateur=null)
	{
		$error = "";
		
		$idUtilisateur = null;
		if($utilisateur)
			$idUtilisateur = $utilisateur->getid();
	
		try {
			$db = $this->getEntityManager()->getConnection();   //Faire un try catch
		} catch (\Exception $e) {
			//failed to connect
			$error = "Impossible de se connecter a la base de donnees";
		}
		
		if(empty($error))
		{
			$params = $db->getParams();
	
			$dbConfig =  array(
					'driver'         => 'Pdo',
					'dsn'            => 'mysql:dbname='.$params['dbname'].';host='.$params['host'],
					'driver_options' => array(
							\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
					),
					'username' => $params['user'],
					'password' => $params['password'],
			);
		
			$db = new \Zend\Db\Adapter\Adapter($dbConfig);
		
		
			$extraColumns = array('title' => 'titre',
								  'user_id' => 'utilisateur_id',
								 );
			
			$mapping = array(
					'timestamp' => 'date_enregistrement',
					'priority'  => 'priorite',
					'message'   => 'message',
					'extra' => $extraColumns
			);
		
			$extraValues = array(
				'title' => $title,
				'user_id' => $idUtilisateur
			);
		
			
			try {
				$writer = new DbLogWriter($db, 'log_table', $mapping);
				$logger = new Logger();
				$logger->addWriter($writer);
			
				$logger->log($priority, $message, $extraValues);
			} catch (\Exception $e) {
				//failed to connect
				$error = "Impossible d'ecrire dans la table des logs";
			}	
		}
		
		return array('error' => $error);
	}
	
// 	/**
// 	 * @return Ambigous <boolean, String>
// 	 */
	public function sauvegardeBD($params=null)
	{
		$appliConfig =  new \Application\Core\AppliConfig();
		
		// Recuperation de la connexion
		try {
			
			if(empty($params))
			{
				$db = $this->getEntityManager()->getConnection();  // Faire un try catch
				$params = $db->getParams();
			}
			
			
			if($appliConfig->get("mode_demo")) // On est en mode demo, l'environnement est Windows
			{
				$cmd = 'mysqldump';
			}
			else // On est en mode production, l'environnement est Linux
			{
				$cmd = '/usr/bin/mysqldump';
			}
	
			
			$cmd .= ' --user='.$params['user'];
			$cmd .= ' --password='.$params['password'];
			$cmd .= ' --host='.$params['host'];
			$cmd .= ' --port='.$params['port'];
			$cmd .= ' --skip-add-drop-table --complete-insert --create-options';
			
			
			if(strtolower(PHP_OS) == "linux") 
			{
			    $fileName = __DIR__.'/../../../../public/docs/database/backup_'.date("Y-m-d_H-i-s").'.sql';
			}
			else // On est en mode production, l'environnement est Linux
			{
			    $fileName = __DIR__.'\..\..\..\..\public\docs\database\backup_'.date("Y-m-d_H-i-s").'.sql';
			}
			
			$cmd .= ' '.$params['dbname'].' > '.$fileName;
			
	
			system($cmd);
			
			
			$varRetour = $fileName;
	
		} catch (\Exception $e) {
			// failed to connect
			$varRetour = false;
		}
	
		return $varRetour;
	}
}
