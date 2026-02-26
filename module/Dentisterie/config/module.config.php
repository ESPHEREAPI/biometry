<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Dentisterie;

use Zend\Session\Container;
$appliConfig =  new \Application\Core\AppliConfig();

return array(
    'router' => array(
        'routes' => array(
            'dentisterie' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/'.$appliConfig->get('lienBackoffice').'/:naturePrestation',
                    'constraints' => array(
                        'naturePrestation'     => 'dentisterie',
                    ),
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
				'child_routes' => array(
				    'recherche-visite-pour-mettre-prix' => array(
				        'type'    => 'Literal',
				        'options' => array(
				            'route'    => '/recherche-visite-pour-mettre-prix',
				            'defaults' => array(
				                'action'	=> 'rechercheVisitePourMettrePrix',
				            ),
				        ),
				    ),
				    'details' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/details/:id',
				            'constraints' => array(
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'controller'    => Controller\LignePrestationController::class,
				                'action' => 'details',
				            ),
				        ),
				        'may_terminate' => true,
				        'child_routes' => array(
				            'mettre-prix' => array(
				                'type' => 'Segment',
				                'options' => array(
				                    'route' => '/mettre-prix',
				                    'defaults' => array(
				                        'action' => 'mettrePrix',
				                    ),
				                ),
				            ),
				            'valider-rejeter' => array(
				                'type' => 'Segment',
				                'options' => array(
				                    'route' => '/valider-rejeter',
				                    'defaults' => array(
				                        'action' => 'validerRejeter',
				                    ),
				                ),
				            ),
				            'encaisser' => array(
				                'type' => 'Segment',
				                'options' => array(
				                    'route' => '/encaisser/:idLignePrestation',
				                    'constraints' => array(
				                        'idLignePrestation'     => '[1-9][0-9]*',
				                    ),
				                    'defaults' => array(
				                        'action' => 'encaisser',
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
				        ),
				        'verb' => 'get, post',
				    ),
				    'enregistrer' => array(
				        'type'    => 'Segment',
				        'options' => array(
				            'route'    => '/enregistrer/:nomAction[/:id]',
				            'constraints' => array(
				                'nomAction'     => 'ajout|modif',
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action'	=> 'enregistrer',
				            ),
				        ),
				    ),
				    'ajouter' => array(
						'type'    => 'Literal',
						'options' => array(
							'route'    => '/ajouter',
							'defaults' => array(
								'action'	=> 'ajouter',
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
				    
				    
				    
				    
				    
				    'imprimer-detaille-pdf' => array(
				        'type'    => 'Literal',
				        'options' => array(
				            'route'    => '/imprimer-detaille-pdf',
				            'defaults' => array(
				                'action'	=> 'imprimerDetaillePdf',
				            ),
				        ),
				    ),
				    'imprimer-detaille-csv' => array(
				        'type'    => 'Literal',
				        'options' => array(
				            'route'    => '/imprimer-detaille-csv',
				            'defaults' => array(
				                'action'	=> 'imprimerDetailleCsv',
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
				    'valider-prestation' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/valider-prestation/:id',
				            'constraints' => array(
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'validerPrestation',
				            ),
				        ),
				    ),
				    'rejeter-prestation' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/rejeter-prestation/:id',
				            'constraints' => array(
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'rejeterPrestation',
				            ),
				        ),
				    ),
				    'encaisser-prestation' => array(
				        'type' => 'Segment',
				        'options' => array(
				            'route' => '/encaisser-prestation/:id',
				            'constraints' => array(
				                'id'     => '[1-9][0-9]*',
				            ),
				            'defaults' => array(
				                'action' => 'encaisserPrestation',
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
				),
				'verb' => 'get, post',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Dentisterie\Controller\Index' => 'Dentisterie\Factory\IndexControllerFactory',
            'Dentisterie\Controller\LignePrestation' => 'Dentisterie\Factory\LignePrestationControllerFactory',
		),
		'factories' => array(
			Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
			Controller\LignePrestationController::class => Controller\Factory\LignePrestationControllerFactory::class,
		),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array('ViewJsonStrategy',), //--- pour de l'Ajax
    ),
);
