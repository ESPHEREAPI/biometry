<?php
 namespace Administration\Form\Profil;
 
 use Zend\Session\Container;
 use Custom\Form\AbstractForm;
 
 class ProfilForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	$elements = include  __DIR__ . '/profil.form.php';
    	$this->addElements($elements);
     }
 }