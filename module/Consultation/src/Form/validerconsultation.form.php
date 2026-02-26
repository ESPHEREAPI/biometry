<?php
 return array(
     array(
         "name" => "montantModif",
         "attributes" => array(
             "type" => "number",
             "id" => "montantModif",
             "class" => "form-control required",
             "placeholder" => $this->translate ("Veuillez saisir le montant paye par Zenithe", "application"),
         ),
         "options" => array(
             "label" => $this->translate ("Montant valide", "application"),
         	 'label_attributes' => array(
	            'class'  => 'control-label',
	        ),
            "concatUnite" => array("unite" => $this->translate ("FCFA", "application")),
         ),
     ),
     array(
         "name" => "taux",
         "attributes" => array(
             "type" => "text",
             "id" => "taux",
             "class" => "form-control required",
             "placeholder" => $this->translate ("Veuillez saisir le taux de couverture", "application"),
         ),
         "options" => array(
             "label" => $this->translate ("Taux de couverture", "application"),
             'label_attributes' => array(
                 'class'  => 'control-label',
             ),
             "concatUnite" => array("unite" => $this->translate ("%", "application")),
         ),
     ),
     
     array(
         "name" => "observations",
         "attributes" => array(
             "type" => "textarea",
             "id" => "observations",
             "class" => "form-control",
             "placeholder" => $this->translate ("Veuillez saisir l'observation", "application"),
         ),
         "options" => array(
             "label" => $this->translate ("Observation", "application"),
         	 'label_attributes' => array(
	            'class'  => 'control-label',
	        ),
         ),
     ),
 );