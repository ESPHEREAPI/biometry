<?php
 namespace Administration\Form\Permission;
 
 use Zend\Session\Container;
 use Custom\Form\AbstractForm;
 
 class FiltreListePermissionForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	$elements = include  __DIR__ . '/filtrelistepermission.form.php';
    	$this->addElements($elements);
     }
 }