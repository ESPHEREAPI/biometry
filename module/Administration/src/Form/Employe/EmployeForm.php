<?php
 namespace Administration\Form\Employe;
 
 use Zend\Session\Container;
 use Custom\Form\AbstractForm;
 use Application\Manager\ProfilManager;
 
 class EmployeForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	$elements = include  __DIR__ . '/employe.form.php';
    	$this->addElements($elements);
    	
    	
//     	$profilManager = new ProfilManager();
//     	$profilManager->em = $this->em;
//     	$tab = $profilManager->getListeProfilLangue($sessionEmploye->offsetGet("code_langue"), 1, -1);
    	
//     	$valueOptions = array();
//     	foreach ($tab as $element)
//     	{
//     		$valueOptions[$element->getProfil()->getId()] = $element->getNom();
//     	}
    	
//     	$this->add(array(
// 	     	"name" => "profil",
// 	     	"type" => "Zend\Form\Element\Select",
// 	     	"attributes" => array(
// 	     		"type" => "select",
// 	     		"id" => "profil",
// 	     		"class" => "required",
// 	     	),
// 	     	"options" => array(
// 	     		"label" => $this->translate ("Profil", "application"),
// 	     		"empty_option" => $this->translate ("Selectionnez le profil"),
// 	     		"value_options" => $valueOptions,
// 	     	),
// 	    ));
     }
 }