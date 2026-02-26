<?php
 namespace BackAuth\Form;
 
 use Custom\Form\AbstractForm;
 use Zend\Session\Container;
 
 class AuthForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
     	$elements = include  __DIR__ . '/settings.auth.form.config.php';
     	$this->addElements($elements);                     
     }
 }