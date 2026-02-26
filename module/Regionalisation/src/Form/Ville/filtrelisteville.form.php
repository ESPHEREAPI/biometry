<?php

use Application\Core\Utilitaire;

$this->idLangueHehehhehe = $sessionEmploye->offsetGet("id_langue");

 return array(
 		
	array(
 		'type' => 'DoctrineModule\Form\Element\ObjectSelect',
 		'name' => 'pays',
 		'attributes' => array(
 			'id' => 'pays',
 			"class" => "required",
 		),
 		'options' => array(
 			'object_manager' => $this->em,
 			'target_class'   => 'Entity\Pays',
 			'label_generator' => function($targetEntity) {
 				
 				$paysLangue = $this->getEntityManager()->getRepository('Entity\PaysLangue')->findOneBy(
 						array('pays' => $targetEntity->getId(), 'langue' => $this->idLangueHehehhehe));
    				
 				$titrePaysLangue = "";
    			if($paysLangue)
    			{
    				$utilitaire = new Utilitaire();
    				
    				$titrePaysLangue = $utilitaire->afficherChaineBD($paysLangue->getNom());
    			}
 				
 				return $titrePaysLangue;
 			},
 			'empty_option'   => $this->translate ("Tous les pays", 'application'),
 			'is_method'      => true,
 			'find_method'    => array(
 				'name'   => 'findBy',
 				'params' => array(
 					'criteria' => array(
 						'statut' => '1',
 						'supprime' => "-1",
 					),
 				),
 			),
 		),
 	),
 	array(
 		'name' => 'region',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'region',
 		),
 		'options' => array(
 			'empty_option'   => $this->translate ('Toutes les regions', 'application'),
 		),
 	),	
 	array(
 		'name' => 'zone',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'zone',
 		),
 		'options' => array(
 			'empty_option'   => $this->translate ('Toutes les zones', 'application'),
 			'value_options' => array(
 				"A"    => $this->translate ('Zone A', 'application'),
 				"B"   => $this->translate ('Zone B', 'application'),
 				"C"    => $this->translate ('Zone C', 'application'),
 			),
 		),
 	),
 	array(
 		'name' => 'statut',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'statut',
 		),
 		'options' => array(
 			'empty_option'   => $this->translate ('Tous les status', 'application'),
 			'value_options' => array(
 				"1"    => $this->translate ('Actif', 'application'),
 				"-1"   => $this->translate ('Inactif', 'application'),
 				"2"    => $this->translate ('Supprime', 'application'),
 			),
 		),
 	),
 );