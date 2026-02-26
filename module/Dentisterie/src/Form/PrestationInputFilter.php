<?php

namespace Dentisterie\Form;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\CommonInputFilter;
use Application\Filter\Common\TextImputFilterLcl;

class PrestationInputFilter extends CommonInputFilter
{
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
			$this->inputFilter->merge(new TextImputFilterLcl("natureAffection", true, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("visite", true, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("prestataire", true, 2));
		}
		
		return $this->inputFilter;
	}
}