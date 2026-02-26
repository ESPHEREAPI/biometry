<?php

namespace Application\Filter;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\CommonInputFilter;
use Application\Filter\Common\TextImputFilterLcl;
use Application\Filter\Common\DigitImputFilterLcl;
use Application\Filter\Common\EnumImputFilterLcl;

class MenuInputFilter extends CommonInputFilter
{
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
			$this->inputFilter->merge(new DigitImputFilterLcl("langue", true, 1));
			$this->inputFilter->merge(new EnumImputFilterLcl("type", true, array(1, 2)));
			$this->inputFilter->merge(new TextImputFilterLcl("nom", true, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("descCourte", false, 2, 1024));
			$this->inputFilter->merge(new TextImputFilterLcl("url", true, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("nomControlleur", false, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("nomModule", false, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("nomAction", false, 2, 255));
			
			$this->inputFilter->merge(new DigitImputFilterLcl("numeroOrdre", true, 1));
			
			$this->inputFilter->merge(new TextImputFilterLcl("classImage", false, 2, 255));
			
			
			if(empty($valeurPere))
				$this->inputFilter->merge(new TextImputFilterLcl("pere", false, 1, 10));
			else
				$this->inputFilter->merge(new DigitImputFilterLcl("pere", true, 1));
			
			
			$this->inputFilter->merge(new DigitImputFilterLcl("position", true, 1));
			$this->inputFilter->merge(new EnumImputFilterLcl("apparaitNav", true, array(-1, 1)));
			$this->inputFilter->merge(new EnumImputFilterLcl("apparaitNavBar", true, array(-1, 1)));
		}
		
		return $this->inputFilter;
	}
}