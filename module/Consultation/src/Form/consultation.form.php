<?php
 return array(
    
    array(
         "name" => "visite",
         "attributes" => array(
             "type" => "text",
             "id" => "visite",
             "class" => "required",
             "placeholder" => $this->translate ("Saisissez le code de la visite", "application"),
         ),
         "options" => array(
             "label" => $this->translate ("Code de la visite", "application"),
             "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;" title="'.$this->translate ("Retrouver la visite", "application").'"></i>')
         ),
    ),
   array(
         'type' => 'Zend\Form\Element\Radio',
         'name' => 'natureConsultation',
         'attributes' => array(
             'id' => 'natureConsultation',
             "class"  => "form-control required",
             "styleLigne" => " display: none;",
         ),
         'options' => array(
             'label' => $this->translate("Payante", "application")." ?",
             'value_options' => array(
                 array(
                     'value' => 'payante',
                     // 'selected' => true,
                     'label' => $this->translate("Payante", "application"),
                     'attributes' => array(
                         'classConteneur' => 'col-md-3',
                     ),
                 ),
                 array(
                     'value' => 'gratuite',
                     'label' => $this->translate("Gratuite", "application"),
                     'attributes' => array(
                         'classConteneur' => 'col-md-3',
                     ),
                 ),
             ),
         )
     ),
    
	 
	 array(
         'type' => 'DoctrineModule\Form\Element\ObjectSelect',
         'name' => 'typeConsultation',
         'attributes' => array(
             'id' => 'typeConsultation',
             "class" => "required",
			 "styleLigne" => " display: none;",
         ),
         'options' => array(
             "label" => $this->translate ("Type", "application"),
             'object_manager' => $this->em,
             'target_class'   => 'Entity\TypePrestation',
             // s'property'       => 'nom',
             'label_generator' => function (\ Entity\TypePrestation $typePrestation) {
                
                $typePrestation->afficheChaine();
                return $typePrestation->getNom();
             },
             'empty_option'   => $this->translate ("Selectionnez le type", 'application'),
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
         "name" => "montant",
         "attributes" => array(
             "type" => "number",
             "id" => "montant",
             "class" => "required",
             "placeholder" => $this->translate ("Veuillez saisir le montant", "application"),
             "styleLigne" => " display: none;",
         ),
         "options" => array(
             "label" => $this->translate ("Montant", "application"),
         ),
    ),
    array(
         "name" => "visteTouvee",
         "attributes" => array(
             "type" => "hidden",
             "id" => "visteTouvee",
         ),
    ),
    array(
         'type' => 'Zend\Form\Element\Csrf',
         'name' => 'csrf',
         'options' => array(
             'csrf_options' => array(
                 'timeout' => 5000
             )
         )
    ),
    array(
         "name" => "submitClose",
         "attributes" => array(
             "type" => "submit",
             "value" => $this->translate ("Enregistrer et quitter", "application"),
             "id" => "submitClose",
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
             "style" => "display: none;"
         ),
    ),
    array(
         "name" => "cancel",
         "attributes" => array(
             "type" => "button",
             "value" => $this->translate ("Retour", "application"),
             "id" => "cancel",
             "class" => "btn btn-default",
         ),
    ),
 );