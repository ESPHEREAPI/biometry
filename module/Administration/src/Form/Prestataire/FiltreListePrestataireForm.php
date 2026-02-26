<?php
 namespace Administration\Form\Prestataire;
 
 use Zend\Session\Container;
 use Custom\Form\AbstractForm;
 
 class FiltreListePrestataireForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	$elements = include  __DIR__ . '/filtrelisteprestataire.form.php';
    	$this->addElements($elements);
     }
 }