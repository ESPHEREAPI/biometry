<?php

namespace Application\Filter;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\CommonInputFilter;
use Application\Filter\Common\DigitImputFilterLcl;
use Application\Filter\Common\FileSizeImputFilterLcl;
use Application\Filter\Common\TextImputFilterLcl;
use Application\Filter\Common\EmailImputFilterLcl;

class PrestataireInputFilter extends CommonInputFilter
{
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
				
			$this->inputFilter->merge(new TextImputFilterLcl("id", true, 2, 100));
			
			
			$this->inputFilter->merge(new TextImputFilterLcl("categorie", true, 2, 100));
			$this->inputFilter->merge(new DigitImputFilterLcl("ville", false, 1));
			$this->inputFilter->merge(new TextImputFilterLcl("nom", true, 2, 100));
			$this->inputFilter->merge(new TextImputFilterLcl("telephone", true, 2, 100));
			$this->inputFilter->merge(new EmailImputFilterLcl("email", false));
			$this->inputFilter->merge(new TextImputFilterLcl("adresse", false, 2, 100));
			$this->inputFilter->merge(new TextImputFilterLcl("registre", false, 2, 255));
			$this->inputFilter->merge(new FileSizeImputFilterLcl("logo", false, null, 20971520, array("png", "jpg", "jpeg")));
		}
		
		return $this->inputFilter;
	}
}