<?php
 namespace Dentisterie\Form;
 
 use Zend\Session\Container;
 use Custom\Form\AbstractForm;
 use Application\Manager\PrestataireManager;
     
 class RechercherVisiteForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	$elements = include  __DIR__ . '/rechercherviste.form.php';
    	$this->addElements($elements);
    	
    	
    	$prestataireManager = new PrestataireManager();
    	$prestataireManager->em = $this->em;
    	$tab = $prestataireManager->getListePrestataire(null, "1", "-1", 1, null, false, null, array("CENTRE_HOSPITALIER", "CENTRE_HOSPITALIER_SIMPLE", "SERVICE_SANTE","CENTRE_HOSPITALIER_OPTIQUE","CENTRE_HOSPITALIER_DENTISTE"));

    	$valueOptions = array();
    	foreach ($tab as $element)
    	{
    	    $element->afficheChaine();
    		$valueOptions[$element->getId()] = $element->getNom();
    	}

    	$this->add(array(
	     	"name" => "prestataireRechercheVisite",
	     	"type" => "Zend\Form\Element\Select",
	     	"attributes" => array(
	     		"type" => "select",
	     		"id" => "prestataireRechercheVisite",
	     		"class" => "form-control required",
	     	),
	     	"options" => array(
	     		"label" => $this->translate ("Centre hospitalier", "application"),
	     	    'label_attributes' => array(
	     	        'class'  => 'control-label',
	     	    ),
	     		"empty_option" => $this->translate ("Selectionnez", "application"),
	     		"value_options" => $valueOptions,
	     	),
	    ));
    	
    	$this->add(array(
            "name" => "visite",
            "attributes" => array(
                 "type" => "text",
                 "id" => "visite",
                 "class" => "form-control required",
                 "placeholder" => $this->translate ("Saisissez le code de la visite", "application"),
             ),
            "options" => array(
                 "label" => $this->translate ("Code de la visite", "application"),
                 'label_attributes' => array(
                        'class'  => 'control-label',
                  ),
            ),
    	));
    	
     }
 }