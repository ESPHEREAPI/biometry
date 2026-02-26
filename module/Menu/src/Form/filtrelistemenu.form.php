<?php
 return array(
 	array(
 		'name' => 'type',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'type',
 			'value' => "2",
 		),
 		'options' => array(
 			'value_options' => array(
 				"1"    => $this->translate ('Frontoffice', 'application'),
 				"2"   => $this->translate ('Backoffice', 'application'),
 			),
 		),
 	),
 	array(
 		'name' => 'statut',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'statut',
 		),
 		'options' => array(
 			'empty_option'   => $this->translate ('Tous les status', 'application'),
 			'value_options' => array(
 				"1"    => $this->translate ('Actif', 'application'),
 				"-1"   => $this->translate ('Inactif', 'application'),
 				"2"    => $this->translate ('Supprime', 'application'),
 			),
 		),
 	),
 );