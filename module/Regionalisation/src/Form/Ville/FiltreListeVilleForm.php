<?php
 namespace Regionalisation\Form\Ville;
 
 use Zend\Session\Container;
 use Custom\Form\AbstractForm;
 
 class FiltreListeVilleForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	$elements = include  __DIR__ . '/filtrelisteville.form.php';
    	$this->addElements($elements);
     }
 }