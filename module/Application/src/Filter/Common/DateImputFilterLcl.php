<?php

namespace Application\Filter\Common;

use Zend\InputFilter\InputFilter;


class DateImputFilterLcl extends InputFilter {
	
	public $inputFilter;
	
	function __construct ($name, $required=true, $minVal=null, $maxVal=null, $othersValidators=array(), $format='Y-m-d')
	{
		// exit;
		$validators = array();
		if($required)
		{
			$validators[] = array('name' => 'NotEmpty');
		}
		$validators[] = array('name' => 'Date',
							  'options' => array('format' => $format,
							  ),
		);
	
	
		if(is_array($othersValidators) && count($othersValidators) > 0)
			$validators[] = $othersValidators;
		
		
		if(!empty($minVal))
		{
			$validators[] = array(
					            'name'    => 'GreaterThan',
					            'options' =>  array(
					                'min'       => $minVal,
					                'inclusive' => true
					            )
					        );
		}
		
		if(!empty($maxVal))
		{
			$validators[] = array(
					            'name'    => 'LessThan',
					            'options' =>  array(
					                'max'       => $maxVal,
					                'inclusive' => true
					            )
					        );
		}
		
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