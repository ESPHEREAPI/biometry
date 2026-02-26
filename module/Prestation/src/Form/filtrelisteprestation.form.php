<?php
 return array(
    
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
             'empty_option'   => $this->translate ("Tous les prestataires", 'application'),
             'is_method'      => true,
             'find_method'    => array(
                 'name'   => 'findBy',
                 'params' => array(
                     'criteria' => array(
                         'statut' => '1',
                         'supprime' => "-1",
                         // 'categorie' => 'CENTRE_HOSPITALIER',
                     ),
                 ),
             ),
         ),
    ),   
//  	array(
//  		'name' => 'naturePrestation',
//  		'type' => 'Zend\Form\Element\Select',
//  		'attributes' => array(
//  			'type' => 'select',
//  			'id' => 'naturePrestation',
//  		),
//  		'options' => array(
//  		    'empty_option'   => $this->translate ('Tous les types', 'application'),
//  			'value_options' => array(
//  				"ordonnance"    => $this->translate ('Ordonnance', 'application'),
//  				"examen"   => $this->translate ('Examen', 'application'),
//  			),
//  		),
//  	),
     array(
         'name' => 'dateMin',
         'attributes' => array(
             'type' => 'text',
             'id' => 'dateMin',
             'class' => 'datetime',
             'placeholder' => $this->translate ('Date enregistrement min', 'application'),
         ),
         "options" => array(
             "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
             ),
         ),
     ),
     array(
         'name' => 'dateMax',
         'attributes' => array(
             'type' => 'text',
             'id' => 'dateMax',
             'class' => 'datetime',
             'placeholder' => $this->translate ('Date enregistrement max', 'application'),
         ),
         "options" => array(
             "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
             ),
         ),
     ),
     
     
     
     
     array(
         'name' => 'dateEncaisseMin',
         'attributes' => array(
             'type' => 'text',
             'id' => 'dateEncaisseMin',
             'class' => 'datetime',
             'placeholder' => $this->translate ('Date encaisse min', 'application'),
         ),
         "options" => array(
             "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
             ),
         ),
     ),
     array(
         'name' => 'dateEncaisseMax',
         'attributes' => array(
             'type' => 'text',
             'id' => 'dateEncaisseMax',
             'class' => 'datetime',
             'placeholder' => $this->translate ('Date encaisse max', 'application'),
         ),
         "options" => array(
             "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
             ),
         ),
     ),
     
     
      array(
         'name' => 'souscripteur',
         'attributes' => array(
             'type' => 'text',
             'id' => 'souscripteur',
             'placeholder' => $this->translate ('Souscripteur', 'application'),
         ),
         "options" => array(
             "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
             ),
         ),
     ),
     
     array(
         'name' => 'nomAdherent',
         'attributes' => array(
             'type' => 'text',
             'id' => 'nomAdherent',
             'placeholder' => $this->translate ('Nom adherent', 'application'),
         ),
         "options" => array(
             "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
             ),
         ),
     ),
     array(
         'name' => 'nomAyantDroit',
         'attributes' => array(
             'type' => 'text',
             'id' => 'nomAyantDroit',
             'placeholder' => $this->translate ('Nom ayant droit', 'application'),
         ),
         "options" => array(
             "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
             ),
         ),
     ),
//      array(
//          'name' => 'idVisite',
//          'attributes' => array(
//              'type' => 'text',
//              'id' => 'idVisite',
//              'placeholder' => $this->translate ('Code visite', 'application'),
//          ),
//          "options" => array(
//              "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
//              ),
//          ),
//      ),
 );