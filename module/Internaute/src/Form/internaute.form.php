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
 			'empty_option'   => $this->translate ("Selectionnez la langue", "application"),
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
     	"name" => "image",
 	    "attributes" => array(
         	"type" => "file",
 	        "id" => "image",
 	    	"class" => "required",
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Image", "application")." (1200px*400px)",
 	    	"label_attributes" => array(
 	    		"title" => $this->translate("Taille preferable")." 1200px*400px",
 	    	),
 	    ),
    ),
 	array(
     	"name" => "categorie",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "categorie",
 	    	"placeholder" => $this->translate ("Saisissez la categorie", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Categorie", "application"),
 	    ),
    ),	
 	array(
     	"name" => "titre",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "titre",
 	    	"placeholder" => $this->translate ("Saisissez le titre", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Titre", "application"),
 	    ),
    ),
 	array(
     	"name" => "description",
     	"type" => "Zend\Form\Element\Textarea",
     	"attributes" => array(
     		"id" => "description",
     		"placeholder" => $this->translate ("Saisissez la description", "application"),
     	),
     	"options" => array(
     		"label" => $this->translate ("Description", "application"),
     	),
    ),
 	array(
     	"name" => "url",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "url",
 	    	"placeholder" => $this->translate ("Saisissez l'url", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Url", "application"),
 	    ),
    ),	
 	array(
     	"name" => "position",
     	"attributes" => array(
     		"type" => "number",
     		"min" => 1,
     		"id" => "position",
     		"class" => "required",
     		"placeholder" => $this->translate ("Position", "application"),
     	),
     	"options" => array(
     		"label" => $this->translate ("Position", "application"),
     		"label_attributes" => array(
     			"title" => $this->translate("Saisissez 0 si vous ne voulez pas que l'article apparaisse dans le internaute"),
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