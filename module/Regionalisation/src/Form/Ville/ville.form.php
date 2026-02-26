<?php

$this->idLangueHehehhehe = $sessionEmploye->offsetGet("id_langue");

 return array(
 	array(
 		'type' => 'DoctrineModule\Form\Element\ObjectSelect',
 		'name' => 'langue',
 		'attributes' => array(
 			'id' => 'langue',
 			"class" => "required",
 		),
 		'options' => array(
 			"label" => $this->translate ("Langue", "application"),
 			'object_manager' => $this->em,
 			'target_class'   => 'Entity\Langue',
 			'property'       => 'nom',
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
 		'type' => 'DoctrineModule\Form\Element\ObjectSelect',
 		'name' => 'pays',
 		'attributes' => array(
 			'id' => 'pays',
 			"class" => "required",
 		),
 		'options' => array(
 			"label" => $this->translate ("Pays", "application"),
 			'object_manager' => $this->em,
 			'target_class'   => 'Entity\Pays',
 			'label_generator' => function($targetEntity) {
 				
 				$paysLangue = $this->getEntityManager()->getRepository('Entity\PaysLangue')->findOneBy(
 						array('pays' => $targetEntity->getId(), 'langue' => $this->idLangueHehehhehe));
    				
 				$nomPaysLangue = "";
    			if($paysLangue)
    			{
    				$paysLangue->afficheChaine();
    				$nomPaysLangue = $paysLangue->getNom();
    			}
 				
 				return $nomPaysLangue;
 			},
 			'empty_option'   => $this->translate ("Selectionnez le pays", 'application'),
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
 			"label" => $this->translate ("Region", "application"),
 			'empty_option'   => $this->translate ('Selectionnez la region', 'application'),
 		),
 	),
 	array(
     	"name" => "nom",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "nom",
 	    	"class" => "required",
 	    	"placeholder" => $this->translate ("Saisissez le titre", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Nom", "application"),
 	    ),
    ),
 	array(
     	"name" => "code",
     	"attributes" => array(
     		"type" => "text",
     		"id" => "code",
     		"placeholder" => $this->translate ("Saisissez le code", "application"),
     	),
     	"options" => array(
     		"label" => $this->translate ("Code", "application"),
     		"label_attributes" => array(
     			"title" => $this->translate("Si aucun code n'est saisi, le code est genere a partir du titre"),
     		),
     	),
    ),
 	array(
     	"name" => "codeZone",
     	"attributes" => array(
     		"type" => "text",
     		"id" => "codeZone",
     		"placeholder" => $this->translate ("Saisissez le code de la zone", "application"),
     	),
     	"options" => array(
     		"label" => $this->translate ("Code zone", "application"),
     	),
    ),
 	array(
     	"name" => "submit",
 	    "attributes" => array(
         	"type" => "submit",
 	        "value" => $this->translate ("Valider", "application"),
 	        "id" => "submit",
 	    	"class" => "btn btn-primary",
         ),
    ),
 	array(
     	"name" => "cancel",
 	    "attributes" => array(
         	"type" => "button",
 	        "value" => $this->translate ("Annuler", "application"),
 	        "id" => "cancel",
 	    	"class" => "btn btn-default",
         ),
    ),
 );