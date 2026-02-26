<?php

namespace Prestation\Form;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\CommonInputFilter;

class LignePrestationInputFilter extends CommonInputFilter
{
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
		}
		
		return $this->inputFilter;
	}
}