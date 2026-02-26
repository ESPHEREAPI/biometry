<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Internaute;

$appliConfig =  new \Application\Core\AppliConfig();

return array(
    'router' => array(
        'routes' => array(
            'internaute' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/'.$appliConfig->get('lienBackoffice').'/internaute',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
				'child_routes' => array(
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
