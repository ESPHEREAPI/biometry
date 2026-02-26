<?php

namespace Application\Filter\Common;

use Zend\InputFilter\InputFilter;


class TextImputFilterLcl extends InputFilter {
	
	public $inputFilter;
	
	function __construct ($name, $required=true, $minLength=null, $maxLength=null, $othersValidators=array())
	{
		// parent::__construct();
		
		$validators = array();
		if($required)
		{
			$validators[] = array('name' => 'NotEmpty');
		}
		if($minLength !== null || $maxLength !== null)
		{
			$options = array('encoding' => 'UTF-8');
			if($minLength !== null)
				$options['min'] = $minLength;
			if($maxLength !== null)
				$options['max'] = $maxLength;
			
			$validators[] = array(
					'name' => 'StringLength',
					'options' => $options,
				);
		}
		
		if(is_array($othersValidators) && count($othersValidators) > 0)
			$validators[] = $othersValidators;
		
		// Adding a single input
		$this->add(array(
			'name' => $name,
			'required' => $required,
			'filters' => array(
				array ('name'=>'StringTrim'),
			),
			'validators' => $validators,
		));
	}
}