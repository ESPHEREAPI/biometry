<?php

namespace Consultation\Form;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\CommonInputFilter;
use Application\Filter\Common\TextImputFilterLcl;
use Application\Filter\Common\DigitImputFilterLcl;

class ValiderConsultationInputFilter extends CommonInputFilter
{
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
			$this->inputFilter->merge(new DigitImputFilterLcl("montantModif", true));
			$this->inputFilter->merge(new TextImputFilterLcl("observations", false, 2, 255));
			$this->inputFilter->merge(new DigitImputFilterLcl("taux", true, 1, 100));			
		}
		
		return $this->inputFilter;
	}
}