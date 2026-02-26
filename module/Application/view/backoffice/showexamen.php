<?php
	
if (isset($_GET['typeprofil']) and isset($_GET['prestataireId']))
 { 
  $typeProfil=$_GET['typeprofil'];
$prestataireId=$_GET['prestataireId'];
           
        //$typeProfil = $sessionEmploye->offsetGet("type_profil");
		//$prestataireId="";	
		
if($typeProfil == "prestataire")
  {
	  $message = array();
	  $tableau = array();
	  //$prestataireId = $sessionEmploye->offsetGet("id_prestataire");
	// Création du DSN

	$dsn = 'mysql:host=localhost;dbname=biometry;port=3306;charset=utf8';

	// Création et test de la connexion

	try {
	 
	$pdo = new PDO($dsn, 'root' , 'DeepWater@2021');

	}
	catch (PDOException $exception) {
	 exit('Erreur de connexion à la base de données');
	 
	}

	// Requête pour tester la connexion 1440
			  
			  $sql="SELECT p.id, COUNT(l.id) FROM dbx45ty_prestation as p ".
                   "join dbx45ty_ligne_prestation as l ON p.id=l.prestation_id ".
                   "and l.date BETWEEN timestamp(DATE_SUB(NOW(), INTERVAL 120 MINUTE)) AND timestamp(NOW()) ".
				   "and p.nature_prestation ='examen'  ".
                   "and (l.etat = 'valide' or l.etat = 'rejete' )".
                   "and l.prestataire_id='".$prestataireId."' group by p.id";

  //$query = $pdo->query($sql);
  
  $STH = $pdo->prepare($sql);
$STH->execute();
$rows = $STH->fetchAll();
//all your results is in $rows array
$STH->setFetchMode(PDO::FETCH_ASSOC);
  
  $rowCount =count($rows);
			$msg = array('nombreValide' => $rowCount ,
			             'pres' => array_column($rows, 'id'),
			              'sujet' => "Examens",
						 'corps' => "Demande(s) d examen traitée!"
						 );
			$message[] = $msg;
		
		  
     echo json_encode($message);
  } 
  
  else
  {
	  $message = array();
	  $tableau = array();
	  //$prestataireId = $sessionEmploye->offsetGet("id_prestataire");
	// Création du DSN

	$dsn = 'mysql:host=localhost;dbname=biometry;port=3306;charset=utf8';

	// Création et test de la connexion

	try {
	 
	$pdo = new PDO($dsn, 'root' , 'DeepWater@2021');

	}
	catch (PDOException $exception) {
	 exit('Erreur de connexion à la base de données');
	 
	}

	// Requête pour tester la connexion 1440
			  
			  $sql="SELECT p.id, COUNT(l.id) FROM dbx45ty_prestation as p ".
                   "join dbx45ty_ligne_prestation as l ON p.id=l.prestation_id ".
                   "and l.date BETWEEN timestamp(DATE_SUB(NOW(), INTERVAL 2880 MINUTE)) AND timestamp(NOW()) ".
				   "and p.nature_prestation ='examen'  ".
                   "and l.etat ='attente_validation' group by p.id";
                   

  //$query = $pdo->query($sql);
  
  $STH = $pdo->prepare($sql);
$STH->execute();
$rows = $STH->fetchAll();
//all your results is in $rows array
$STH->setFetchMode(PDO::FETCH_ASSOC);
  
  $rowCount =count($rows);
			$msg = array('nombreValide' => $rowCount ,
			             'pres' => array_column($rows, 'id'),
			              'sujet' => "Examens",
						 'corps' => "Examen(s) en Attente de Validation!"
						 );
			$message[] = $msg;
		
		  
     echo json_encode($message);
  } 
 
  
  }
?>