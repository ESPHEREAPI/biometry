<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administration;

$appliConfig =  new \Application\Core\AppliConfig();

return array(
    'router' => array(
        'routes' => array(
            'administration' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/'.$appliConfig->get('lienBackoffice').'/administration',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
				'child_routes' => array(
					'employe' => array(
						'type'    => 'Literal',
						'options' => array(
							'route'    => '/employe',
							'defaults' => array(
								'controller'    => Controller\EmployeController::class,
								'action'	=> 'index',
							),
						),
						'may_terminate' => true,
						'child_routes' => array(
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
						),
						'verb' => 'get, post',
					),
					'profil' => array(
						'type'    => 'Literal',
						'options' => array(
							'route'    => '/profil',
							'defaults' => array(
								'controller'    => Controller\ProfilController::class,
								'action'	=> 'index',
							),
						),
						'may_terminate' => true,
						'child_routes' => array(
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
						),
						'verb' => 'get, post',
					),
					'permission' => array(
						'type'    => 'Literal',
						'options' => array(
							'route'    => '/permission',
							'defaults' => array(
								'controller'    => Controller\PermissionController::class,
								'action'	=> 'index',
							),
						),
						'may_terminate' => true,
						'child_routes' => array(
							'profil' => array(
								'type' => 'Segment',
								'options' => array(
									'route' => '/profil/:id',
									'constraints' => array(
										'id'     => '[1-9][0-9]*',
									),
									'defaults' => array(
										'action' => 'profil',
									),
								),
								'may_terminate' => true,
								'child_routes' => array(
									'valider' => array(
										'type'    => 'Literal',
										'options' => array(
											'route'    => '/valider',
											'defaults' => array(
												'action'	=> 'valider',
											),
										),
									),
								),
								'verb' => 'get, post',
							),
						),
						'verb' => 'get, post',	
					),
				    
				    
				    // Début prestataire
				    'prestataire' => array(
				        'type'    => 'Literal',
				        'options' => array(
				            'route'    => '/prestataire',
				            'defaults' => array(
				                'controller'    => Controller\PrestataireController::class,
				                'action'	=> 'index',
				            ),
				        ),
				        'may_terminate' => true,
				        'child_routes' => array(
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
				                        // 'id'     => '[1-9][0-9]*',
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
				        ),
				        'verb' => 'get, post',
				    ),
				    // Fin prestataire
				),
				'verb' => 'get, post',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
			Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
			Controller\EmployeController::class => Controller\Factory\EmployeControllerFactory::class,
			Controller\ProfilController::class => Controller\Factory\ProfilControllerFactory::class,
			Controller\PermissionController::class => Controller\Factory\PermissionControllerFactory::class,
			Controller\PrestataireController::class => Controller\Factory\PrestataireControllerFactory::class,
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array('ViewJsonStrategy',), //--- pour de l'Ajax
    ),
);
