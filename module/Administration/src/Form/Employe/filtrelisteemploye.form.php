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
 	array(
 		'type' => 'DoctrineModule\Form\Element\ObjectSelect',
 		'name' => 'profil',
 		'attributes' => array(
 			'id' => 'profil',
 		),
 		'options' => array(
 			'object_manager' => $this->em,
 			'target_class'   => 'Entity\ProfilLangue',
 			'property'       => 'nom',
 			'empty_option'   => $this->translate ("Selectionnez le profil", 'application'),
 			'option_attributes' => array(
 				'value' => function ($targetEntity) {
 					return $targetEntity->getProfil()->getId();
 				},
 			),
 			'is_method'      => true,
 			'find_method'    => array(
 				'name'   => 'getListeProfil',
 				'params' => array(
 					'criteria' => array(
 						'statut' => '1',
 						'supprime' => "-1",
 						'id_langue' => $sessionEmploye->offsetGet("id_langue"),
 					),
 				),
 			),
 		),
 	),
 	array(
 	    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
 	    'name' => 'prestataire',
 	    'attributes' => array(
 	        'id' => 'prestataire',
 	    ),
 	    'options' => array(
 	        'object_manager' => $this->em,
 	        'target_class'   => 'Entity\Prestataire',
 	        'property'       => 'nom',
 	        'empty_option'   => $this->translate ("Selectionnez le prestataire", 'application'),
//  	        'option_attributes' => array(
//  	            'value' => function ($targetEntity) {
//  	              return $targetEntity->getProfil()->getId();
//  	            },
//  	        ),
            'is_method'      => true,
            'find_method'    => array(
                'name'   => 'findBy',
                'params' => array(
                    'criteria' => array(
                        'statut' => '1',
                        'supprime' => "-1",
                        // 'id_langue' => $sessionEmploye->offsetGet("id_langue"),
                    ),
                ),
            ),
        ),
    ),
 );