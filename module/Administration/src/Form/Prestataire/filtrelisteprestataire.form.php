<?php

use Zend\Session\Container;
$sessionEmploye = new Container('employe');
$this->idLangueHehehhehe = $sessionEmploye->offsetGet("id_langue");
 
 
 return array(
 	array(
 		'name' => 'statut',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'statut',
 		),
 		'options' => array(
 			'empty_option'   => $this->translate ('Selectionnez le statut', 'application'),
 			'value_options' => array(
 				"1"    => $this->translate ('Actif', 'application'),
 				"-1"   => $this->translate ('Inactif', 'application'),
 				"2"    => $this->translate ('Supprime', 'application'),
 			),
 		),
 	),
     array(
         'type' => 'DoctrineModule\Form\Element\ObjectSelect',
         'name' => 'categorie',
         'attributes' => array(
             'id' => 'categoriePrestataire',
             "class" => "required",
         ),
         'options' => array(
             'object_manager' => $this->em,
             'target_class'   => 'Entity\CategoriePrestataire',
             // 'property'       => 'nom',
             'label_generator' => function (\Entity\CategoriePrestataire $categoriePrestataire) {
             $categoriePrestataire->afficheChaine();
             
             return $categoriePrestataire->getNom();
             },
             'empty_option'   => $this->translate ("Toutes les categories", 'application'),
             'is_method'      => true,
             'find_method'    => array(
                 'name'   => 'findBy',
                 'params' => array(
                     'criteria' => array(
                         'statut' => '1',
                         // 'supprime' => "-1",
                     ),
                 ),
             ),
        ),
     ),
     
     array(
         'type' => 'DoctrineModule\Form\Element\ObjectSelect',
         'name' => 'ville',
         'attributes' => array(
             'id' => 'ville',
             "class" => "",
             // "classEltLiaison" => "ctneurLivraison",
         ),
         'options' => array(
             'object_manager' => $this->em,
             'target_class'   => 'Entity\Ville',
             'label_generator' => function($targetEntity) {
             
             $villeLangue = $this->em->getRepository('Entity\VilleLangue')->findOneBy(
                 array('ville' => $targetEntity->getId(), 'langue' => $this->idLangueHehehhehe));
             
             $nomVilleLangue = "";
             if($villeLangue)
             {
                 $villeLangue->afficheChaine();
                 $nomVilleLangue = $villeLangue->getNom();
             }
             
             return $nomVilleLangue;
             },
             'empty_option'   => $this->translate ("Toutes les villes", 'application'),
             'is_method'      => true,
             'find_method'    => array(
                 'name'   => 'getListeVille',
                 'params' => array(
                     'criteria' => array(
                         'statut' => '1',
                         'supprime' => "-1",
                         'code_langue' => $sessionEmploye->offsetGet("code_langue"),
                         'id_pays' => 47,
                     ),
                 ),
             ),
        ),
     ),
 );