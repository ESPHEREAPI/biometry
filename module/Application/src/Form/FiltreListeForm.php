<?php
 namespace Application\Form;
 
 use Custom\Form\AbstractForm;
 
 class FiltreListeForm extends AbstractForm
 {
     public function initialize()
     {
        $this->setAttribute('method', 'POST');
    	$this->setAttribute('enctype', "multipart/form-data");
    	
     }
 }