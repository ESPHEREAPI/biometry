<?php

namespace Application\Filter\Common;

use Zend\InputFilter\InputFilter;


class FileSizeImputFilterLcl extends InputFilter {
	
	public $inputFilter;
	
	function __construct ($name, $required=true, $minSize=null, $maxSize=null, $extensions=array(), $othersValidators=array())
	{
		// parent::__construct();
		
		$validators = array();
		if($required)
		{
			$validators[] = array('name' => 'NotEmpty');
		}
		if($minSize !== null || $maxSize !== null)
		{
			$options = array();
			if($minSize !== null)
				$options['min'] = $minSize;
			if($maxSize !== null)
				$options['max'] = $maxSize;
			
			$validators[] = array(
					'name' => '\Zend\Validator\File\Size',
					'options' => $options,
				);
		}
		
		if(is_array($extensions) && count($extensions) > 0)
		{
            $validators[] = array(
                'name' => '\Zend\Validator\File\Extension',
                'options' => array(
                    'extension' => $extensions,
                ),
            );
		}
		
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