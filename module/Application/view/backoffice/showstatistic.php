<?php
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
	
if (isset($_GET['typeprofil']) and isset($_GET['prestataireId']) and isset($_GET['annee']))
 { 
  $typeProfil=$_GET['typeprofil'];
$prestataireId=$_GET['prestataireId'];
$Annee=$_GET['annee'];
           
        //$typeProfil = $sessionEmploye->offsetGet("type_profil");
		//$prestataireId="";	
		
if($typeProfil != "prestataire")
  {
	// Requête pour tester la connexion 1440
	
	$sql1="SELECT count(c.id) as nb,YEAR(v.date) as yr,MONTH(v.date) as mh FROM dbx45ty_consultation as c ".
			  "JOIN dbx45ty_visite as v ON c.visite_id = v.id ".
			  "WHERE YEAR(v.date)='".$Annee."'".
			   "and nature_consultation='payante' ".
			  "group by DATE_FORMAT(c.date, '%Y%m')";
			  
			  
			  
    $sql2="SELECT count(p.id)as nb,YEAR(p.date) as yr,MONTH(p.date) as mh FROM dbx45ty_prestation as p ".      
              "WHERE YEAR(p.date)='".$Annee."'".
              "and p.nature_prestation ='ordonnance' ".
			   "group by DATE_FORMAT(p.date, '%Y%m')";
			   
			   		  
    $sql3="SELECT count(p.id)as nb,YEAR(p.date) as yr,MONTH(p.date) as mh FROM dbx45ty_prestation as p ".              
               "WHERE YEAR(p.date)='".$Annee."'".
              "and p.nature_prestation ='examen' ".
			   "group by DATE_FORMAT(p.date, '%Y%m')";

  
$STH1 = $pdo->prepare($sql1);
$STH1->execute();
$rows1 = $STH1->fetchAll();
$STH1->setFetchMode(PDO::FETCH_ASSOC);

$STH2 = $pdo->prepare($sql2);
$STH2->execute();
$rows2 = $STH2->fetchAll();
$STH2->setFetchMode(PDO::FETCH_ASSOC);

$STH3 = $pdo->prepare($sql3);
$STH3->execute();
$rows3 = $STH3->fetchAll();
$STH3->setFetchMode(PDO::FETCH_ASSOC);


			$msg = array('nombrecon' => array_column($rows1, 'nb'),
			             'nombreord' => array_column($rows2, 'nb'),
						 'nombreexa' => array_column($rows3, 'nb'));
			$message[] = $msg;
	

		  
  }
   echo json_encode($message);
  
  }
?>