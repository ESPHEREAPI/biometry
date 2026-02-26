<?php

namespace Application\Filter\Common;

use Zend\InputFilter\InputFilter;


class DigitImputFilterLcl extends InputFilter {
	
	public $inputFilter;
	
	function __construct ($name, $required=true, $minVal=null, $maxVal=null, $inclusive=true, $othersValidators=array())
	{
		// parent::__construct();
		
		$validators = array();
		if($required)
		{
			$validators[] = array('name' => 'NotEmpty');
		}
		$validators[] = array('name' => 'Digits');
		
		if($minVal !== null || $maxVal !== null)
		{
			$options = array('inclusive' => $inclusive);
			if($minVal !== null)
				$options['min'] = $minVal;
			else
				$options['min'] = PHP_INT_MIN;

			if($maxVal !== null)
				$options['max'] = $maxVal;
			else
				$options['max'] = PHP_INT_MAX;

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