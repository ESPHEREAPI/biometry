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
             "label" => $this->translate ("Montant paye par Zenithe", "application"),
         	 'label_attributes' => array(
	            'class'  => 'control-label',
	        ),
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