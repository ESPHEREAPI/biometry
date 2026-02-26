<?php
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
 		'name' => 'type',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'type',
 			"class" => "required",
 		),
 		'options' => array(
 			"label" => $this->translate ("Type", "application"),
 			'empty_option'   => $this->translate ('Selectionnez le type', 'application'),
 			'value_options' => array(
 				"1"   => $this->translate ('Frontoffice', 'application'),
 				"2"   => $this->translate ('Backoffice', 'application'),
 			),
 		),
 	),
 	array(
 		'name' => 'pere',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'pere',
 		),
 		'options' => array(
 			"label" => $this->translate ("Menu parent", "application"),
 			'empty_option'   => $this->translate ('Selectionnez le menu parent', 'application'),
 		),
 	),
 	array(
     	"name" => "nom",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "nom",
 	    	"class" => "required",
 	    	"placeholder" => $this->translate ("Saisissez le nom", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Nom", "application"),
 	    ),
    ),
 	array(
     	"name" => "descCourte",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "descCourte",
 	    	"placeholder" => $this->translate ("Saisissez la description courte", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Description courte", "application"),
 	    ),
    ),
 	array(
     	"name" => "url",
     	"attributes" => array(
     		"type" => "text",
     		"id" => "url",
     		"placeholder" => $this->translate ("Saisissez l'url", "application"),
     		"class" => "required",
     	),
     	"options" => array(
     		"label" => $this->translate ("Url", "application"),
     	),
    ),
 	array(
     	"name" => "nomControlleur",
     	"attributes" => array(
     		"type" => "text",
     		"id" => "nomControlleur",
     		"placeholder" => $this->translate ("Saisissez le nom du controlleur", "application"),
     	),
     	"options" => array(
     		"label" => $this->translate ("Nom controlleur", "application"),
     	),
    ),
 	array(
     	"name" => "nomModule",
     	"attributes" => array(
     		"type" => "text",
     		"id" => "nomModule",
     		"placeholder" => $this->translate ("Saisissez le nom du module", "application"),
     	),
     	"options" => array(
     		"label" => $this->translate ("Nom module", "application"),
     	),
    ),
 	array(
     	"name" => "nomAction",
     	"attributes" => array(
     		"type" => "text",
     		"id" => "nomAction",
     		"placeholder" => $this->translate ("Saisissez le nom de l'action", "application"),
     	),
     	"options" => array(
     		"label" => $this->translate ("Nom action", "application"),
     	),
    ),
    array(
         "name" => "numeroOrdre",
         "attributes" => array(
             "type" => "text",
             "id" => "numeroOrdre",
             "placeholder" => $this->translate ("Saisissez le numero d'ordre du menu", "application"),
         ),
         "options" => array(
             "label" => $this->translate ("Numero d'ordre du menu", "application"),
         ),
    ),
 	array(
     	"name" => "classImage",
     	"attributes" => array(
     		"type" => "text",
     		"id" => "classImage",
     		"placeholder" => $this->translate ("Saisissez la classe css de l'icone", "application"),
     	),
     	"options" => array(
     		"label" => $this->translate ("Class css icone", "application"),
     	),
    ),
 	array(
     	"name" => "position",
     	"attributes" => array(
     		"type" => "number",
     		"min" => 1,
     		"id" => "position",
     		// "value" => 0,
     		"placeholder" => $this->translate ("Saisissez la position du menu", "application"),
     		"class" => "required",
     	),
     	"options" => array(
     		"label" => $this->translate ("Position", "application"),
     	),
    ),
 	array(
 		'name' => 'apparaitNav',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'apparaitNav',
 			"class" => "required",
 		),
 		'options' => array(
 			"label" => $this->translate ("Apparait chemin navigation", "application"),
 			'empty_option'   => $this->translate ('Selectionnez', 'application'),
 			'value_options' => array(
 				"1"    => $this->translate ('Oui', 'application'),
 				"-1"   => $this->translate ('Non', 'application'),
 			),
 		),
 	),
 	array(
 		'name' => 'apparaitNavBar',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'apparaitNavBar',
 			"class" => "required",
 		),
 		'options' => array(
 			"label" => $this->translate ("Apparait barre de navigation", "application"),
 			'empty_option'   => $this->translate ('Selectionnez', 'application'),
 			'value_options' => array(
 				"1"    => $this->translate ('Oui', 'application'),
 				"-1"   => $this->translate ('Non', 'application'),
 			),
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