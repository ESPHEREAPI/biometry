<?php
 namespace Dentisterie\Form;
 
 use Zend\Session\Container;
 use Custom\Form\AbstractForm;
 
 class PrestationForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	$elements = include  __DIR__ . '/prestation.form.php';
    	$this->addElements($elements);
     }
 }