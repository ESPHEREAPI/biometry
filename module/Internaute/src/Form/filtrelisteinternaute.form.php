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
 			'empty_option'   => $this->translate ('Tous les status', 'application'),
 			'value_options' => array(
 				"1"    => $this->translate ('Actif', 'application'),
 				"-1"   => $this->translate ('Inactif', 'application'),
 				"2"    => $this->translate ('Supprime', 'application'),
 			),
 		),
 	),
 	 array(
 		'name' => 'genre',
 		'type' => 'Zend\Form\Element\Select',
 		'attributes' => array(
 			'type' => 'select',
 			'id' => 'genre',
 		),
 		'options' => array(
 			'empty_option'   => $this->translate ('Selectionnez le genre', 'application'),
 			'value_options' => array(
 				"F"   => $this->translate ('Feminin', 'application'),
 				"M"    => $this->translate ('Maxculin', 'application'),
 			),
 		),
 	),
 );