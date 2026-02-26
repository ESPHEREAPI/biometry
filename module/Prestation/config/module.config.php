<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Prestation;

use Zend\Session\Container;
$appliConfig =  new \Application\Core\AppliConfig();

return array(
    'router' => array(
        'routes' => array(
            'prestation' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/'.$appliConfig->get('lienBackoffice').'/:naturePrestation',
                    'constraints' => array(
                        'naturePrestation'     => 'ordonnance|examen|lunetterie',
                    ),
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
				'child_routes' => array(
					'ajouter-medicament-ajax' => array(
				        'type'    => 'Literal',
				        'options' => array(
				            'route'    => '/ajouter-medicament-ajax',
				            'defaults' => array(
				                'action'	=> 'ajouterMedicamentAjax',
				            ),
				        ),
				    ),
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
							'encaisser-tout' => array(
				                'type' => 'Segment',
				                'options' => array(
				                    'route' => '/encaisser-tout',
				                    'defaults' => array(
				                        'action' => 'encaisserTout',
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
				),
				'verb' => 'get, post',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Prestation\Controller\Index' => 'Prestation\Factory\IndexControllerFactory',
            'Prestation\Controller\LignePrestation' => 'Prestation\Factory\LignePrestationControllerFactory',
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
