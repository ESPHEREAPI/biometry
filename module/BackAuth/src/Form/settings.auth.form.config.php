<?php
 return array(
     array(
     	'name' => 'amount',
 	    'attributes' => array(
         	'type' => 'text',
 	        'id' => 'amount',
         ),
 	    'options' => array(
 	    	'label' => $this->translate ('Amount', 'application'),
 	    	'class' => 'min-input'	
 	    ),
     ),
    
 	array(
     	'name' => 'submit',
 	    'attributes' => array(
         	'type' => 'submit',
 	        'value' => $this->translate ('Valider', 'application'),
 	        'id' => 'submit',
 	    	'class' => "input-button",
         ),
     ),
 );