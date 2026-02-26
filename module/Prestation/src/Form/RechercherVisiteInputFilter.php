<?php

namespace Prestation\Form;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\CommonInputFilter;
use Application\Filter\Common\TextImputFilterLcl;

class RechercherVisiteInputFilter extends CommonInputFilter
{
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
			$this->inputFilter->merge(new TextImputFilterLcl("prestataireRechercheVisite", true, 2, 100));
			$this->inputFilter->merge(new TextImputFilterLcl("visite", true, 6, 6));
			
		}
		
		return $this->inputFilter;
	}
}