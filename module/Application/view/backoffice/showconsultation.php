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
	
	$sql="SELECT count(c.id) as nb FROM dbx45ty_consultation as c ".
			  "JOIN dbx45ty_visite as v ON c.visite_id = v.id ".
			  "WHERE c.date BETWEEN timestamp(DATE_SUB(NOW(), INTERVAL 120 MINUTE)) AND timestamp(NOW()) ".
			  "and c.etat_consultation='valide' ".
			  "and v.prestataire_id='".$prestataireId."'";
  //$query = $pdo->query($sql);

foreach($pdo->query($sql) as $data)
		{
			$msg = array('nombreValide' => $data['nb'],
			              'sujet' => "Consultations",
						 'corps' => "Demande(s) de consultation traitée!");
			$message[] = $msg;
		}
		  
     echo json_encode($message);
  }
  else
  {
	$message = array();
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
	
	$sql="SELECT count(c.id) as nb FROM dbx45ty_consultation as c ".
			  "JOIN dbx45ty_visite as v ON c.visite_id = v.id ".
			  "WHERE c.date BETWEEN timestamp(DATE_SUB(NOW(), INTERVAL 2880 MINUTE)) AND timestamp(NOW()) ".
			  "and c.etat_consultation='attente_validation'";
  //$query = $pdo->query($sql);

foreach($pdo->query($sql) as $data)
		{
			$msg = array('nombreValide' => $data['nb'],
			             'sujet' => "Consultations",
						 'corps' => "Consultation(s) en Attente de Validation!");
			$message[] = $msg;
		}
		  
     echo json_encode($message);  
  }
  
  
  }
?>