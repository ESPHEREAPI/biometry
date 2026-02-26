<?php
 return array(
 	array(
 		'name' => 'statut',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'statut',
 		),
 		'options' => array(
 			'empty_option'   => $this->translate ('Selectionnez le statut', 'application'),
 			'value_options' => array(
 				"1"    => $this->translate ('Actif', 'application'),
 				"-1"   => $this->translate ('Inactif', 'application'),
 				"2"    => $this->translate ('Supprime', 'application'),
 			),
 		),
 	),
 );