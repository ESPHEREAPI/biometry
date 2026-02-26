<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

use Zend\Session\Container;

$appliConfig =  new \Application\Core\AppliConfig();
$infosSession = $appliConfig->get("infos_session");

if(isset($_SERVER['REQUEST_URI']) && substr_count($_SERVER['REQUEST_URI'], $appliConfig->get("lienBackoffice")) > 0)
{
	$sessionEmploye = new Container('employe');
	$sessionAgence = new Container('agence');
	
	if(!$sessionEmploye->offsetExists('code_langue'))
	{
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			if($lang == 'fr')
			{
				$lang = 'fr_FR';
			}
			elseif($lang == 'en')
			{
				$lang = 'en_US';
			}
			else
			{
				$lang = 'fr_FR'; // Langue par defaut
			}
		}
		else
		{
			$lang = 'fr_FR'; // Langue par defaut
		}
		
		$sessionEmploye->offsetSet('code_langue', $lang);
		$sessionEmploye->offsetSet('code_iso_langue', substr($lang, 0, 2));
	}
	else
	{
		$lang = $sessionEmploye->offsetGet('code_langue');
	}
		
	$sessionAgence->offsetSet('lienBackoffice', $appliConfig->get("lienBackoffice"));
	
	
	$sessionAgence->setExpirationSeconds($infosSession['remember_me_login_backoffice']);
	$sessionEmploye->setExpirationSeconds($infosSession['remember_me_login_backoffice']);
}
else
{
	$sessionInternaute = new Container('internaute');
	if(!$sessionInternaute->offsetExists('code_langue'))
	{
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			if($lang == 'fr')
			{
				$lang = 'fr_FR';
			}
			elseif($lang == 'en')
			{
				$lang = 'en_US';
			}
			else
			{
				$lang = 'fr_FR'; // Langue par defaut
			}
		}
		else
		{
			$lang = 'fr_FR'; // Langue par defaut
		}
		
		$sessionInternaute->offsetSet('code_langue', $lang);
		$sessionInternaute->offsetSet('code_iso_langue', substr($lang, 0, 2));
	}
	else
	{
		$lang = $sessionInternaute->offsetGet('code_langue');
	}

	// Reinitialisation de la session
	if($sessionInternaute->offsetExists("remember_me_seconds"))
	{
		$sessionInternaute->setExpirationSeconds($sessionInternaute->offsetGet("remember_me_seconds"));
	}
}

// echo $lang;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/hometest',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
	],
	'doctrine' => [
        'driver' => [
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'my_annotation_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    'module/Entity',
                    ///'another/path',
                ],
            ],
            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => [
                'drivers' => [
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    'Entity' => 'my_annotation_driver',
                ],
            ],
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'translate' => \Zend\I18n\View\Helper\Translate::class
        ]
    ],

    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\I18n\TranslatorFactory',
        ),
    ),
    'translator' => array(
		'locale' => $lang,
        'translation_file_patterns' => array(
        	array(
        		'type' => 'gettext',
        		'base_dir' =>  __DIR__ . '/../../Application/language',
        		'pattern' => '%s.mo',
        		'text_domain' => 'application',
        	),
        	array(
        		'type' => 'phpArray',
        		'base_dir' => __DIR__ . '/../../Application/language/val',
        		'pattern' => 'Zend_Validate_%s.php',
        		'text_domain' => 'application',
        	),
        ),
    ),
    'session' => array(
    	// 'remember_me_seconds' => $infosSession['login'],	
    ),
    // 'filesystem' => array(
    //     'adapter' => array(
	// 		'name' => 'filesystem',
	// 		'options' => new \Zend\Cache\Storage\Adapter\FilesystemOptions(
	// 			array(
	// 				'cache_dir' => __DIR__ . '/../../../data/cache/'
	// 			)
	// 		),
	// 	),
	// 	'plugins' => array(
	// 		'exception_handler' => array('throw_exceptions' => false),
	// 	),
    // ),
];
