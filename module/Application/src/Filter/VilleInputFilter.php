<?php

namespace Application\Filter;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\CommonInputFilter;
use Application\Filter\Common\TextImputFilterLcl;
use Application\Filter\Common\DigitImputFilterLcl;

class VilleInputFilter extends CommonInputFilter
{
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
			$this->inputFilter->merge(new TextImputFilterLcl("nom", true, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("code", true, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("codeZone", false, 1, 255));
			
			$this->inputFilter->merge(new DigitImputFilterLcl("pays", true, 1));
			$this->inputFilter->merge(new DigitImputFilterLcl("region", true, 1));
			
			$this->inputFilter->merge(new DigitImputFilterLcl("langue", true, 1));
		}
		
		return $this->inputFilter;
	}
}