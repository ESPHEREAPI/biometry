<?php

namespace Application\Filter\Common;

use Zend\InputFilter\InputFilter;


class RegexImputFilterLcl extends InputFilter {
	
	public $inputFilter;
	
	function __construct ($name, $required=true, $pattern, $othersValidators=array())
	{
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
	
		$validators = array();
		if($required)
		{
			$validators[] = array('name' => 'NotEmpty');
		}
		
		$validators[] = array('name' => 'Regex',
							  'options' => array(
							       'pattern' => $pattern,
							  ));
		
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