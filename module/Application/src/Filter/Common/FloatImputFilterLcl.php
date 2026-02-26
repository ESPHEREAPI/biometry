<?php

namespace Application\Filter\Common;

use Zend\InputFilter\InputFilter;


class FloatImputFilterLcl extends InputFilter {
	
	public $inputFilter;
	
	function __construct ($name, $required=true, $minVal=null, $maxVal=null, $inclusive=true, $othersValidators=array())
	{
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
	
		$validators = array();
		if($required)
		{
			$validators[] = array('name' => 'NotEmpty');
		}
		$validators[] = array('name' => 'Float',
							  'options' => array(
					                'locale' => 'en_US'
					         ));
	
		if($minVal !== null || $maxVal !== null)
		{
			$options = array('inclusive' => $inclusive);
			if($minVal !== null)
				$options['min'] = $minVal;
			else
				$options['min'] = PHP_FLOAT_MIN;

			if($maxVal !== null)
				$options['max'] = $maxVal;
			else
				$options['max'] = PHP_FLOAT_MAX;
	
			$validators[] = array(
				'name' => 'Between',
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