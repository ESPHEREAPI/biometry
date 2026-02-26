<?php

namespace Application\Filter\Common;

use Zend\InputFilter\InputFilter;


class ImageImputFilterLcl extends InputFilter {
	
	public $inputFilter;
	
	function __construct ($name, $required=true, $othersValidators=array())
	{
		// parent::__construct();
		
		$validators = array();
		if($required)
		{
			$validators[] = array('name' => 'NotEmpty');
		}
		
		$validators[] = array(
			'name' => '\Zend\Validator\File\IsImage',
		);
		
		if(is_array($othersValidators) && count($othersValidators) > 0)
			$validators[] = $othersValidators;
		
		// Adding a single input
		$this->add(array(
			'name' => $name,
			'required' => $required,
			'validators' => $validators,
		));
	}
}