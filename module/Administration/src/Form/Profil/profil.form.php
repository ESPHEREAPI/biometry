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
     	"name" => "code",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "code",
 	    	"class" => "required",
 	    	"placeholder" => $this->translate ("Saisissez le code", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Code", "application"),
 	    ),
    ),
    array(
         'name' => 'typeProfil',
         'type' => 'Zend\Form\Element\Select',
         'attributes' => array(
             'type' => 'select',
             'id' => 'typeProfil',
             "class" => "required",
         ),
         'options' => array(
             "label" => $this->translate ("Type", "application"),
             'empty_option'   => $this->translate ('Selectionnez le type', 'application'),
             'value_options' => array(
                 "admin"   => $this->translate ("Admin", 'application'),
                 "prestataire"   => $this->translate ("Prestataire", 'application'),
             ),
         ),
    ),

    array(
         'name' => 'typeSousProfil',
         'type' => 'Zend\Form\Element\Select',
         'attributes' => array(
             'type' => 'select',
             'id' => 'typeSousProfil',
             "class" => "required",
         ),
         'options' => array(
             "label" => $this->translate ("Sous type", "application"),
             'empty_option'   => $this->translate ('Selectionnez le sous type', 'application'),
             'value_options' => array(
                 "admin"   => $this->translate ("Admin", 'application'),
                 "centre_hospitalier"   => $this->translate ("Centre hospitalier", 'application'),
                 
                 "laboratoire"   => $this->translate ("Laboratoire", 'application'),
                 "pharmacie"   => $this->translate ("Pharmacie", 'application'),
                 "service_sante"   => $this->translate ("Service sante", 'application'),
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