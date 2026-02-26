<?php

namespace Consultation\Form;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\CommonInputFilter;
use Application\Filter\Common\TextImputFilterLcl;
use Application\Filter\Common\EnumImputFilterLcl;
use Application\Filter\Common\DigitImputFilterLcl;

class ConsultationInputFilter extends CommonInputFilter
{
	
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
			$this->inputFilter->merge(new TextImputFilterLcl("visite", true, 2, 255));
			$this->inputFilter->merge(new EnumImputFilterLcl("natureConsultation", true, array("payante", "gratuite")));
			//$this->inputFilter->merge(new EnumImputFilterLcl("typeConsultation", true, array("CS0", "CS1", "CS2")));
			$this->inputFilter->merge(new DigitImputFilterLcl("montant", true, 100));
		}
		
		return $this->inputFilter;
	}
}