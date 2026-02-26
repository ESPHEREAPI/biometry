<?php
 return array(
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
     	"name" => "prenom",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "prenom",
 	    	"placeholder" => $this->translate ("Saisissez le prenom", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Prenom", "application"),
 	    ),
    ),
 	array(
     	"name" => "genre",
     	"type" => "Zend\Form\Element\Select",
     	"attributes" => array(
     		"type" => "select",
     		"id" => "genre",
     		"class" => "required",
     	),
     	"options" => array(
     		"label" => $this->translate ("Genre", "application"),
     		"empty_option" => $this->translate ("Selectionnez le genre"),
     		"value_options" => array(
     			"F"    => $this->translate ("Feminin", "application"),
     			"M"   => $this->translate ("Maxculin", "application"),
     		),
     	),
    ),
 	array(
     	"name" => "dateNaissance",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "dateNaissance",
 	    	"placeholder" => $this->translate ("Saisissez la date de naissance", "application"),
 	    	"class" => "date",
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Date de naissance", "application"),
 	    ),
    ),
 	array(
     	"name" => "lieuNaissance",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "lieuNaissance",
 	    	"placeholder" => $this->translate ("Saisissez le lieu de naissance", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Lieu de naissance", "application"),
 	    ),
    ),	
 	array(
     	"name" => "email",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "email",
 	    	"placeholder" => $this->translate ("Saisissez l'adresse mail de l'utiisateur", "application"),
 	        "class" => "required",
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Email", "application"),
 	    ),
     ),
 	 array(
     	"name" => "login",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "login",
 	    	"placeholder" => $this->translate ("Saisissez le login de l'utiisateur", "application"),
 	    	"class" => "required",
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Login", "application"),
 	    ),
    ),	
 	array(
     	"name" => "motPasse",
 	    "attributes" => array(
         	"type" => "password",
 	        "id" => "motPasse",
 	    	"placeholder" => $this->translate ("Saisissez le mot de passe de l'utiisateur", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Mot de passe", "application"),
 	    ),
     ),	
 	 array(
     	"name" => "confirmMotPasse",
 	    "attributes" => array(
         	"type" => "password",
 	        "id" => "confirmMotPasse",
 	    	"placeholder" => $this->translate ("Saisissez a nouveau le mot de passe de l'utiisateur", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Confirmer mot passe", "application"),
 	    ),
     ),	
 	 array(
     	"name" => "telephone",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "telephone",
 	    	"placeholder" => $this->translate ("Saisissez le numero de telephone de l'utiisateur", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Telephone", "application"),
 	    ),
     ),
     
     
     array(
         'type' => 'DoctrineModule\Form\Element\ObjectSelect',
         'name' => 'prestataire',
         'attributes' => array(
             'id' => 'prestataire',
             "class" => "required",
         ),
         'options' => array(
             "label" => $this->translate ("Prestataire", "application"),
             'object_manager' => $this->em,
             'target_class'   => 'Entity\Prestataire',
             // s'property'       => 'nom',
             'label_generator' => function (\ Entity\Prestataire $prestataire) {
                
                $prestataire->afficheChaine();
                return $prestataire->getNom();
             },
             'empty_option'   => $this->translate ("Selectionnez le prestataire", 'application'),
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
 		'name' => 'langueDefaut',
 		'attributes' => array(
 			'id' => 'langueDefaut',
 			"class" => "required",
 		),
 		'options' => array(
 			"label" => $this->translate ("Langue", "application"),
 			'object_manager' => $this->em,
 			'target_class'   => 'Entity\Langue',
 			'property'       => 'nom',
 			'empty_option'   => $this->translate ("Selectionnez la langue de l'utilisateur", 'application'),
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
     	"name" => "connexionAppli",
     	"type" => "Zend\Form\Element\Select",
     	"attributes" => array(
     		"type" => "select",
     		"id" => "connexionAppli",
     		"class" => "required",
     	),
     	"options" => array(
     		"label" => $this->translate ("Connexion au backoffice", "application"),
     		"empty_option" => $this->translate ("Selectionnez si l'employe se connecte"),
     		"value_options" => array(
     			"1"    => $this->translate ("Oui", "application"),
     			"-1"   => $this->translate ("Non", "application"),
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