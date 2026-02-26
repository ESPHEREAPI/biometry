<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Consultation;

$appliConfig =  new \Application\Core\AppliConfig();

return array(
    'router' => array(
        'routes' => array(
            'consultation' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/'.$appliConfig->get('lienBackoffice').'/consultation',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
				'child_routes' => array(
				    'enregistrer' => array(
				        'type'    => 'Literal',
				        'options' => array(
				            'route'    => '/enregistrer',
				            'defaults' => array(
				                'action'	=> 'enregistrer',
				            ),
				        ),
				    ),
				    'ajouter' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/ajouter[/:idVisite]',
							'constraints' => array(
								// 'idVisiteCrypte'     => '[1-9][0-9]*',
							),
							'defaults' => array(
								'action' => 'ajouter',
							),
						),
					),
					'modifier' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/modifier/:id',
							'constraints' => array(
								'id'     => '[1-9][0-9]*',
							),
							'defaults' => array(
								'action' => 'modifier',
							),
						),
					),
					'pagination' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/pagination[/:numActuel]',
							'constraints' => array(
								'numActuel' => '[1-9][0-9]*',
							),
							'defaults' => array(
								'action' => 'pagination',
							),
						),
					),
				    'imprimer-pdf' => array(
				        'type'    => 'Literal',
				        'options' => array(
				            'route'    => '/imprimer-pdf',
				            'defaults' => array(
				                'action'	=> 'imprimerPdf',
				            ),
				        ),
				    ),
				    'imprimer-csv' => array(
				        'type'    => 'Literal',
				        'options' => array(
				            'route'    => '/imprimer-csv',
				            'defaults' => array(
				                'action'	=> 'imprimerCsv',
				            ),
				        ),
				    ),
				    'valider-consultation' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/valider-consultation/:id',
				            'constraints' => array(
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'validerConsultation',
				            ),
				        ),
				    ),
				    'rejeter-consultation' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/rejeter-consultation/:id',
				            'constraints' => array(
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'rejeterConsultation',
				            ),
				        ),
				    ),
				    
				    
				    
				    
				    
				    'devalider-consultation' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/devalider-consultation/:id',
				            'constraints' => array(
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'devaliderConsultation',
				            ),
				        ),
				    ),
				    'derejeter-consultation' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/derejeter-consultation/:id',
				            'constraints' => array(
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'derejeterConsultation',
				            ),
				        ),
				    ),
				    
				    'recuperer-montant' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/recuperer-montant/[:id]',
				            'constraints' => array(
				                // 'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'recupererMontant',
				            ),
				        ),
				    ),
				    
				    
				    
				    
				    
				    
				    
				    
				    
				    
				    'encaisser-consultation' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/encaisser-consultation/:id',
				            'constraints' => array(
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'encaisserConsultation',
				            ),
				        ),
				    ),
				    'rechercher-visite' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/rechercher-visite/:id',
				            'constraints' => array(
				                // 'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'rechercherVisite',
				            ),
				        ),
				    ),
				    'imprimer-recu' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/imprimer-recu/:id',
				            'constraints' => array(
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'imprimerRecu',
				            ),
				        ),
				    ),
				),
				'verb' => 'get, post',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
			Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array('ViewJsonStrategy',), //--- pour de l'Ajax
    ),
);
