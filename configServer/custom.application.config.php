<?php
return array(
	
	// En developpement ou en production ??? 
    'mode_demo' => "1",
	
	// Parametres recapcha	
	'infos_recapcha' => array(
        'api_sitekey' => "6Ld3JCMTAAAAAOs5GKqLEuFCQ-14wgg71kkTzAMB", // A eventuellement modifier en production
		'api_secretkey' => "6Ld3JCMTAAAAAOEEbqKABq0aNlmNe4jY38fk3g9e", // A eventuellement modifier en production
		'url_api' => "https://www.google.com/recaptcha/api.js",
        'url_verify' => "https://www.google.com/recaptcha/api/siteverify",
    ),
	
	// Parametres generaux
	'nom_appli' => 'ZENITHE Insurance',
	'adresse' => '1540 Rue Koumassi, Bali, Douala',
    'telephone' => '(237) 233 43 41 32',
    'telephone_complet' => '+237233434132',
    'telephone_service_client' => '(237) 694 30 82 32',
    'telephone_service_client_complet' => '+237694308232',
	'version' => '1.0',
    'lienBackoffice' => 'admin',
	'basePath' => 'http://127.0.0.1/biometry_zf3/public',
    'news_letter_email_from' => 'info@zenitheinsurance.com',
    'modeDeveloppement' => true,
    'email_contact' => 'info@zenitheinsurance.com',
	'email_send_mail' => 'notification@zenitheinsurance.com',
	'email_recruit' => 'recruit@zenitheinsurance.com',
	'liste_code_iso_langue' => 'fr|en',
    'longuer_code_visite' => 6,

	// Parametres d'envoi des mails
	'nom_domaine' => 'secure.emailsrvr.com',
    'nom_smtp' => 'secure.emailsrvr.com',
    'port_smtp' => '587',
		
	// Parametres des paiements
	'taux_conversion_euro' => "655.957",
	'taux_tva' => '1.1925',

	// Parametres slide accueil
    'slide_accueil' => array(
        'nbre_article' => 5,
        'nbre_produit' => 5,
        'nbre_autre' => 5,
    ),

	// Parametres caroussel accueil
    'caroussel_accueil' => array(
        'nbre' => 8,
    ),

	// Parametres avances
	'liste_categories_exclues' => '7,8,9,10',
	'id_article_compagnie_fr' => 1,
	'id_article_compagnie_en' => 8,
	'id_categorie_nos_engagements_fr' => 9,
	'id_categorie_nos_engagements_en' => 10,
	'caractere_separation_parent' => '/',
		
	// Parametres e-insurance
	// 'email_admin_cotation' => array('che.vanessa@zenitheinsurance.com', 'tankam.narcisse@zenitheinsurance.com', 'sikod.nabila@zenitheinsurance.com', 'bongam.larissa@zenitheinsurance.com', 'mbimbe.steve@zenitheinsurance.com'),
	'email_admin_cotation' => array("moussipi.achille@gmail.com"),
    'email_admin_cotation_differe' => array("mousbit@yahoo.fr"),
	'email_admin_sinistre' => array('moussipi.achille@zenitheinsurance.com', "moussipi.achille@gmail.com"),
	'email_admin_souscription' => array('moussipi.achille@zenitheinsurance.com', "moussipi.achille@gmail.com"),
	'telephone_admin_sinistre' => array('+237675593975'),
	'telephone_admin_souscription' => array('+237675593975'),
		
	// Parametres smartsupp
	'smartsupp_key' => 'f2dcb35e02c2597251389f22dd650cc6d534396a',
		
	// Parametres disqus
	'code_disqus' => 'zenithe-insurance',
	
	// Parametres paiement	
	'infos_paiement' => array(
		'infos_paypal' => array( // PayPal
	        'version' => "124.0",
	        'user' => "vendeur_mousbit_api1.yahoo.fr",
	        'pwd' => "WJ4TVUJCCZP3RL3X",
			'signature' => "AFcWxV21C7fd0v3bYYYRCpSSRl31A06XlBg8I1i5u9F9reus.lfK9bKN",
			'code_devise' => "EUR",
			'url_serveur_paypal' => "https://api-3t.sandbox.paypal.com/nvp",
			'url_serveur_paypal_user' => "https://www.sandbox.paypal.com"
	    ),
		'infos_carte_bancaire' => array( // Carte bancaire UBA
	        'merchantId' => "CMCAM10327",
	        'serviceKey' => "9ace4fb7-b0dc-4dc5-9d83-22f2e3ee49cb",
	        'countryCurrencyCode' => "950",
			'url_post' => "https://ucollect.ubagroup.com/cipg-payportal/regptran",
			'url_json' => "https://ucollect.ubagroup.com/cipg-payportal/regjtran",
			'url_xml' => "https://ucollect.ubagroup.com/cipg-payportal/regxtran",
			'url_xml_post' => "https://ucollect.ubagroup.com/cipg-payportal/regxptran",
			'url_pay' => "https://ucollect.ubagroup.com/cipg-payportal/paytran",
			'url_base_bank_msbt' => "https://ucollect.ubagroup.com/cipg-payportal",
				
			'url_transaction_status' => 'https://ucollect.ubagroup.com/cipgpayportal/confirmation/verify',
	    ),
		'infos_orange_money' => array( // Orange Money
			
			// Developpement
			'url' => 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment',
			'url_transaction_status' => 'https://api.orange.com/orange-money-webpay/dev/v1/transactionstatus',
			'User-Agent' => 'ZenitheInsuranceProduction',
			'Authorization' => 'Bearer lFTmpsu8L8tdZGha0OjJT28VmzSq',
			'Host' => 'api.orange.com',
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
				
	        'merchant_key' => 'ff6d579b',
	        'currency' => 'OUV',
			'reference' => 'Zenithe Insurance',
			'return_url' => 'e-paiement/orange-money/return',
			// 'cancel_url' => 'e-paiement/orange-money/cancel',
			'notif_url' => 'e-paiement/orange-money/notif',
				
			'url_pay' => 'https://webpayment-sb.orange-money.com/payment/pay_token',
			'url_base_orange_msbt' => "https://webpayment-sb.orange-money.com",
				
				
			// Production
// 			'url' => 'https://api.orange.com/orange-money-webpay/cm/v1/webpayment',
// 			'url_transaction_status' => 'https://api.orange.com/orange-money-webpay/cm/v1/transactionstatus',
// 			'User-Agent' => 'ZenitheInsuranceProduction',
// 			'Authorization' => 'Bearer fNGRrMUqmT0fAVqWUfSl5BwxnhhG',
// 			'Host' => 'api.orange.com',
// 			'Content-Type' => 'application/json',
// 			'Accept' => 'application/json',
				
// 	        'merchant_key' => '20a27fe1',
// 	        'currency' => 'XAF',
// 			'reference' => 'Zenithe Insurance',
// 			'return_url' => 'e-paiement/orange-money/return',
// 			// 'cancel_url' => 'e-paiement/orange-money/cancel',
// 			'notif_url' => 'e-paiement/orange-money/notif',
				
// 			'url_pay' => 'https://webpayment.orange-money.com/payment/pay_token',
// 			'url_base_orange_msbt' => "https://webpayment.orange-money.com",
	    ),
		'infos_mtn_mobile_money' => array( // MTN Mobile Money
			'url' => 'https://developer.mtn.cm/OnlineMomoWeb/faces/transaction/transactionRequest.xhtml',
			'email' => 'moussipi.achille@zenitheinsurance.com',
			'password' => 'Huy69gTY74JhyB',
			'url_base_mtn_msbt' => "https://developer.mtn.cm",
			'notif_url' => 'e-paiement/mtn-mobile-money/notif',
			'host' => 'developer.mtn.cm',
	    )
	),
		
	// Parametres session	
	'infos_session' => array(
        'remember_me_einsurance' => "28800", // 10 minutes
        'remember_me_login' => "28800", // 10 minutes
		'remember_me_login_backoffice' => "28800", // 6 minutes
    ),

	// Parametres formulaire e-insurance	
	'inofs_form_einsurance' => array(
        
    ),
		
	// Parametres pour les requetes sql
	'requete_bd' => array(
        'tab_operateurs' => array("<", "<=", ">", ">=", "<>", "NULL", "NOT NULL", "IN", "NOT IN", "LIKE", "NOT LIKE"),
    ),

	// Parametres des reseaux sociaux
	'reseaux_sociaux' => array(
		'facebook' => array(
			"nom" => "Facebook",
			"icon" => "fa fa-facebook",
			"lien" => "https://www.facebook.com/zenitheinsurance",
			"class" => "social-icons-facebook",
		),
		'twitter' => array(
			"nom" => "Twitter",
			"icon" => "fa fa-twitter",
			"lien" => "https://twitter.com/zenitheinsuranc",
			"class" => "social-icons-twitter",
		),
		'linkedin' => array(
			"nom" => "Linkedin",
			"icon" => "fa fa-linkedin",
			"lien" => "https://www.linkedin.com/in/zenithe-insurance-s-a-06435b138/",
			"class" => "social-icons-linkedin",
		),
	),
		
	// Liste des agences (a deplacer dans la base de donnees)
	'tab_agences' =>  array(
 		"DIRECTION_GENERALE"    => "Direction Generale Zenithe Insurance (Alimentation Koumassi, Bali, Douala)",
 		"AG_BAFOUSSAM"    => "Agent General Pyramide Bafoussam (Carrefour le Maire, Bafoussam)",
 		"PRESINSURANCE_BUEA"    => "Presinsurance Buea (Molyko, Face Sosoliso Wash)",
 		"BD_LIMBE"    => "Bureau Direct Limbe (Pharmacie de Limbe)",
 		"BR_BAMENDA"    => "Bureau Regional Bamenda (VATICAN Building)",
 		"BD_DOUALA_NORD"    => "Bureau Direct Douala Nord (Face Parcours VITA, Makepe)",
 		"AG_LES_MEILLEURS"    => "Agent General Les Meilleurs (Immeuble DEKAGE, Akwa)",
 		"AG_NOUTONG"    => "Agent General Noutong (Face TEXACO Bali)",
 		"AG_MADONE"    => "Agent General Madone (Face Pharmacie Mondiale, Bessengue)",
 		"EUROPEAN_INSURANCE_KRIBI"    => "European Insurance Kribi, Kribi (Apres la BICEC Kribi)",
 		"AG_IU_CO"    => "Agent General IU & Co (Immeuble PMUC, Kumba)",
 		"BR_GAROUA"    => "Bureau Regional Garoua (Pharmacie de Garoua)",
 		"AG_PROGRES_YAOUNDE"    => "Agent General Progres Yaounde (Ngousso, Fabrique des parpaings)",
 		"AG_PROGRES_EMERGENCE_MAROUA"    => "Agent General Emergence (Hotel Tcherno, Maroua)",
 		"BD_BERTOUA"    => "Bureau Direct Bertoua (Face Legion de Gendarmerie, Bertoua)",
 		"BR_YAOUNDE"    => "Bureau Regional Yaounde (Face PNUD, Nouvelle Route Bastos)",
 		"EUROPEAN_INSURANCE_EBOLOWA"    => "European Insurance Ebolowa, Kribi (Cercle Municipal, Ebolowa)",	
 		"AG_ANDAL_NGAOUNDERE"    => "Agent General Andal Ngaoundere (Carrefour Ministre)",
 		"AG_PROGRES_DOUALA"    => "Agent General Progres Douala (Face Bureau des Transports, Akwa)",
 	),

	// Parametres pour l'autentification
	"oauth" => array(
		'twitter' => array(
			"consumer_key" => "pGx1uZ1UvH44qw6aukj9zNt2v",
			"consumer_secret" => "ElrPPGYV9AVTg0tcIWDA5IquInImX2sPA1tf08Dfo38EJJaaG8",
		),
		'yahoo' => array(
			"application_id" => "mfziUY6m",
			"consumer_key" => "dj0yJmk9NXRma1l6eTBuaWlQJmQ9WVdrOWJXWjZhVlZaTm0wbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD05ZQ--",
			"consumer_secret" => "fb0446e448d41eb30bd3d94a1318ade2efc8d62a",
		)
	),
    
    'webservice_server' => array(
        'ip_adress' => "35.204.126.17"
    ),
    
    "sms" => array(
        "url" => "https://app.lmtgroup.com/bulksms/api/v1/push",
        "api_key"=> "xzPMOPyTXby4Lej",
        "login" => "zenithassurance",
        "password" => "ZA2017T21",
        "senderName" => "ZENITHE",
    ),
    
//     "sms" => array(
//         "url" => "http://lmtgoldsms.dyndns.org:8282/Managesms-war/ServiceSMS?WSDL",
//         "login" => "ZENITH",
//         "password" => "Z3N!th@55",
//         "senderName" => "ZENITHE",
//     ),
);
?>