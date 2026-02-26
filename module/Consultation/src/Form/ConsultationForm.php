<?php
 namespace Consultation\Form;
 
 use Zend\Session\Container;
 use Custom\Form\AbstractForm;
 
 class ConsultationForm extends AbstractForm
 {
     public function initialize()
     {
     	$sessionEmploye = new Container('employe');
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	$elements = include  __DIR__ . '/consultation.form.php';
    	$this->addElements($elements);
     }
 }