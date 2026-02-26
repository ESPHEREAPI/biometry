<?php
 namespace Regionalisation\Form\Ville;
 
 use Zend\Session\Container;
 use Custom\Form\AbstractForm;
 
 class VilleForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	$elements = include  __DIR__ . '/ville.form.php';
    	$this->addElements($elements);
     }
 }