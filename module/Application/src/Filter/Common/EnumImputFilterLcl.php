<?php

namespace Application\Filter\Common;

use Zend\InputFilter\InputFilter;


class EnumImputFilterLcl extends InputFilter {
	
	public $inputFilter;
	
	function __construct ($name, $required=true, $values=array(), $othersValidators=array())
	{
		$validators = array();
		if($required)
		{
			$validators[] = array('name' => 'NotEmpty');
		}
		$validators[] =array('name' => 'InArray',
							'options' => array('haystack' => $values),
		);
	
	
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