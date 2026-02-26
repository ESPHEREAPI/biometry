<?php
 namespace Administration\Form\Employe;
 
 use Zend\Session\Container;
 use Custom\Form\AbstractForm;
 
 class FiltreListeEmployeForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	$elements = include  __DIR__ . '/filtrelisteemploye.form.php';
    	$this->addElements($elements);
     }
 }