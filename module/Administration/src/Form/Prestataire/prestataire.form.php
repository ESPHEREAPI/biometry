<?php

use Zend\Session\Container;
$sessionEmploye = new Container('employe');
$this->idLangueHehehhehe = $sessionEmploye->offsetGet("id_langue");

 return array(
     
     
    array(
         "name" => "id",
         "attributes" => array(
             "type" => "text",
             "id" => "prestataire_id",
             "class" => "required",
             "placeholder" => $this->translate ("Saisissez l'identifiant", "application"),
         ),
         "options" => array(
             "label" => $this->translate ("Identifiant", "application"),
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
         'type' => 'DoctrineModule\Form\Element\ObjectSelect',
         'name' => 'categorie',
         'attributes' => array(
             'id' => 'categoriePrestataire',
             "class" => "required",
         ),
         'options' => array(
             "label" => $this->translate ("Categorie", "application"),
             'object_manager' => $this->em,
             'target_class'   => 'Entity\CategoriePrestataire',
             // 'property'       => 'nom',
             'label_generator' => function (\Entity\CategoriePrestataire $categoriePrestataire) {
                    $categoriePrestataire->afficheChaine();
             
                    return $categoriePrestataire->getNom();
             },
             'empty_option'   => $this->translate ("Selectionnez la categorie", 'application'),
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
            "label" => $this->translate ("Ville", "application"),
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
            'empty_option'   => $this->translate ("Veuillez selectionner la ville", 'application'),
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
     
 	array(
     	"name" => "telephone",
 	    "attributes" => array(
         	"type" => "text",
 	        "id" => "telephone",
 	    	"class" => "required",
 	    	"placeholder" => $this->translate ("Saisissez le numero de telephone", "application"),
         ),
 	    "options" => array(
 	    	"label" => $this->translate ("Telephone", "application"),
 	    ),
    ),
     
    array(
         "name" => "email",
         "attributes" => array(
             "type" => "text",
             "id" => "email",
             "placeholder" => $this->translate ("Saisissez l'adresse mail", "application"),
         ),
         "options" => array(
             "label" => $this->translate ("Adresse mail", "application"),
         ),
    ),
     
    array(
         "name" => "adresse",
         "attributes" => array(
             "type" => "text",
             "id" => "adresse",
             "placeholder" => $this->translate ("Saisissez l'adresse", "application"),
         ),
         "options" => array(
             "label" => $this->translate ("Adresse", "application"),
         ),
    ),
    
    array(
        "name" => "registre",
        "attributes" => array(
            "type" => "text",
            "id" => "registre",
            "placeholder" => $this->translate ("Saisissez le registre de commerce", "application"),
        ),
        "options" => array(
            "label" => $this->translate ("Registre de commerce", "application"),
        ),
    ),
    
    array(
        "name" => "logo",
        "attributes" => array(
            "type" => "file",
            "id" => "logo",
        ),
        "options" => array(
            "label" => $this->translate ("Logo", "application"),
            "label_attributes" => array(
                "class" => "msbt-tooltip",
                "data-placement" => "right",
                "title" => $this->translate ("Veuillez choisir le logo de l'entreprise", "application"),
            ),
        ),
    ),
     
    array(
         "name" => "submitClose",
         "attributes" => array(
             "type" => "submit",
             "value" => $this->translate ("Enregistrer et quitter", "application"),
             "id" => "submitCloseEtape",
             "class" => "btn btn-primary",
         ),
    ),
     
 	array(
     	"name" => "submit",
 	    "attributes" => array(
         	"type" => "submit",
 	        "value" => $this->translate ("Enregistrer", "application"),
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