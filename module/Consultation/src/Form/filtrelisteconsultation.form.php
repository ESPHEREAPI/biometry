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
     
	  array(
         'type' => 'DoctrineModule\Form\Element\ObjectSelect',
         'name' => 'typeConsultation',
         'attributes' => array(
             'id' => 'typeConsultation',
         ),
         'options' => array(
             "label" => $this->translate ("Type", "application"),
             'object_manager' => $this->em,
             'target_class'   => 'Entity\TypePrestation',
             'property'       => 'nom',
         
             'empty_option'   => $this->translate ("Tous les Types", 'application'),
             'is_method'      => true,
             'find_method'    => array(
                 'name'   => 'findBy',
                 'params' => array(
                     'criteria' => array(
                         'affiche' => '1',
                         'categorie' => "consultations",
						 
                     ),
                 ),
             ),
         ),
     ),
	 
    array(
         'name' => 'etatConsultation',
         'type' => 'Zend\Form\Element\Select',
         'attributes' => array(
             'type' => 'select',
             'id' => 'etatConsultation',
             'value' => "1",
         ),
         'options' => array(
             'empty_option'   => $this->translate ('Tous les etats', 'application'),
             'value_options' => array(
                 "attente_validation"    => $this->translate ('Attente de validation', 'application'),
                 "rejete"   => $this->translate ('Rejete', 'application'),
                 "valide"   => $this->translate ('Valide', 'application'),
                 "encaisse"   => $this->translate ('Encaisse', 'application'),
             ),
         ),
    ),
     array(
         'name' => 'dateMin',
         'attributes' => array(
             'type' => 'text',
             'id' => 'dateMin',
             'class' => 'datetime',
             'placeholder' => $this->translate ('Date consultation min', 'application'),
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
             'placeholder' => $this->translate ('Date consultation max', 'application'),
         ),
         "options" => array(
             "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
             ),
         ),
     ),
//      array(
//          'name' => 'montantMin',
//          'attributes' => array(
//              'type' => 'number',
//              'min' => 0,
//              'id' => 'montantMin',
//              // 'class' => 'date',
//              'placeholder' => $this->translate ('Montant min', 'application'),
//          ),
//          "options" => array(
//              "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
//              ),
//          ),
//      ),
//      array(
//          'name' => 'montantMax',
//          'attributes' => array(
//              'type' => 'number',
//              'min' => 1,
//              'id' => 'montantMax',
//              // 'class' => 'date',
//              'placeholder' => $this->translate ('Montant max', 'application'),
//          ),
//          "options" => array(
//              "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;"></i>'
//              ),
//          ),
//      ),
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