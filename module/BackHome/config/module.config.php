<?php
/**
 * Application blanche   (http://zf2.biz)
 * utiise Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace BackHome;

$appliConfig =  new \Application\Core\AppliConfig();

return array(
    'router' => array(
        'routes' => array(
            'backdownload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/download/:nomCompletFichier',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'download',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    
                ),
                'verb' => 'get, post',
            ),
            'backhome' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/'.$appliConfig->get('lienBackoffice').'/accueil',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
				'child_routes' => array(
					
				),
				'verb' => 'get, post',
            ),
        	'backenvoyernewsletter' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/'.$appliConfig->get('lienBackoffice').'/envoyernewsletter',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'envoyerNewsletter',
                    ),
                ),
                'may_terminate' => true,
				'child_routes' => array(
					
				),
				'verb' => 'get, post',
            ),
        	'parametre' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/'.$appliConfig->get('lienBackoffice').'/parametre',
        			'defaults' => array(
        				'controller'    => Controller\IndexController::class,
        				'action'	=> 'parametre',
        			),
        		),
        	),
        	'activer_commun' => array(
        		'type'    => 'Segment',
        		'options' => array(
        			'route'    => '/'.$appliConfig->get('lienBackoffice').'/activer_commun/:statut/:nomEntite/:idElts',
        			'constraints' => array(
        				// 'statut'     => '[\-1-1]',
        			),
        			'defaults' => array(
        				'controller'    => Controller\IndexController::class,
        				'action'	=> 'activerCommun',
        			),
        		),
        	),
			'activer_commun_ajax' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/'.$appliConfig->get('lienBackoffice').'/activer_commun_ajax',
        			'defaults' => array(
        				'controller'    => Controller\IndexController::class,
        				'action'	=> 'activerCommunAjax',
        			),
        		),
        	),
			'supprimer_commun_ajax' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/'.$appliConfig->get('lienBackoffice').'/supprimer_commun_ajax',
        			'defaults' => array(
        				'controller'    => Controller\IndexController::class,
        				'action'	=> 'supprimerCommunAjax',
        			),
        		),
        	),
        	'charger_regions_pays' => array(
        		'type'    => 'Segment',
        		'options' => array(
        			'route'    => '/'.$appliConfig->get('lienBackoffice').'/charger_regions_pays/:pays[/:region]',
        			'constraints' => array(
        				'pays'     => '[1-9][0-9]*',
        				'region'     => '[1-9][0-9]*',
        			),
        			'defaults' => array(
        				'controller'    => Controller\IndexController::class,
        				'action'	=> 'chargerRegionsPays',
        			),
        		),
        	),
        	'supprimer_commun' => array(
        		'type'    => 'Segment',
        		'options' => array(
        			'route'    => '/'.$appliConfig->get('lienBackoffice').'/supprimer_commun/:statut/:nomEntite/:idElts',
        			'constraints' => array(
        				// 'statut'     => '[0-1]',
        			),
        			'defaults' => array(
        				'controller'    => Controller\IndexController::class,
        				'action'	=> 'supprimerCommun',
        			),
        		),
        	),
        	'supprimer_fichier' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/'.$appliConfig->get('lienBackoffice').'/supprimer_fichier',
        			'defaults' => array(
        				'controller'    => Controller\IndexController::class,
        				'action'		=> 'supprimerFichier',
        			),
        		),
        	),
        	'reorganiser_liste' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/'.$appliConfig->get('lienBackoffice').'/reorganiser_liste',
        			'defaults' => array(
        				'controller'    => Controller\IndexController::class,
        				'action'		=> 'reorganiserListe',
        			),
        		),
        	),
        	'deplacer-element-common' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/'.$appliConfig->get('lienBackoffice').'/deplacer-element-common',
        			'defaults' => array(
        				'controller'    => Controller\IndexController::class,
        				'action'		=> 'deplacerElement',
        			),
        		),
        	),
            'acces-refuse' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/'.$appliConfig->get('lienBackoffice').'/acces-refuse',
                    'defaults' => array(
                        'controller'    => Controller\IndexController::class,
                        'action'		=> 'accesRefuse',
                    ),
                ),
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
		
	'console' => array(
		'router' => array(
			'routes' => array(
				'sauvegarde-bd' => array(
					'options' => array(
						'route'    => 'sauvegarde-bd',
						'defaults' => array(
							'controller' => \BackHome\Controller\IndexController::class,
							'action'     => 'sauvegardeBd'
						)
					)
				),	
			)
		)
	),
);
