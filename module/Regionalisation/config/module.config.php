<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Regionalisation;

$appliConfig =  new \Application\Core\AppliConfig();

return array(
    'router' => array(
        'routes' => array(
        	'regionalisation' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/'.$appliConfig->get('lienBackoffice').'/regionalisation',
					'defaults' => array(
						'controller'    => Controller\IndexController::class,
						'action'	=> 'index',
					),
				),
        		'may_terminate' => true,
        		'child_routes' => array(	
        			'ville' => array(
		                'type'    => 'Literal',
		                'options' => array(
		                    'route'    => '/ville',
		                    'defaults' => array(
		                        'controller'    => Controller\VilleController::class,
		                        'action'        => 'index',
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
							'chargervillesajax' => array(
								'type'    => 'Segment',
								'options' => array(
									'route'    => '/chargervillesajax/:id',
									'constraints' => array(
										'id'     => '[1-9][0-9]*',
									),
									'defaults' => array(
										'action'	=> 'chargerVillesAjax',
									),
								),
							),	
						),
						'verb' => 'get, post',
		            ),
        		),
        		'verb' => 'get, post',
			),
        ),
    ),
    'controllers' => array(
		'factories' => array(
			Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
			Controller\VilleController::class => Controller\Factory\VilleControllerFactory::class,
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array('ViewJsonStrategy',), //--- pour de l'Ajax
    ),
);
