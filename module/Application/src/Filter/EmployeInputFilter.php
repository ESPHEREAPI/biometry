<?php

namespace Application\Filter;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\DigitImputFilterLcl;
use Application\Filter\Common\TextImputFilterLcl;
use Application\Filter\Common\CommonInputFilter;
use Application\Filter\Common\EmailImputFilterLcl;
use Application\Filter\Common\DateImputFilterLcl;
use Application\Filter\Common\EnumImputFilterLcl;

class EmployeInputFilter extends CommonInputFilter
{
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
			
			$loginUniqueValidator = array(
                        'name' => 'DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->getEntityManager()->getRepository('Entity\Utilisateur'),
                            'fields' => 'login',
                        	'messages' => array(
		                        'objectFound' => $this->translate('Login deja utilise', 'application'),
		                    )
                        )
            );
			
			$this->inputFilter->merge(new TextImputFilterLcl("nom", true, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("prenom", false, 2, 255));
			$this->inputFilter->merge(new EnumImputFilterLcl("genre", true, array("M", "F")));
			$this->inputFilter->merge(new DateImputFilterLcl("dateNaissance", false));
			$this->inputFilter->merge(new TextImputFilterLcl("lieuNaissance", false, 2, 255));
			$this->inputFilter->merge(new EmailImputFilterLcl("email", true));
			$this->inputFilter->merge(new TextImputFilterLcl("login", true, 5, 150, $loginUniqueValidator));
			$this->inputFilter->merge(new TextImputFilterLcl("motPasse", false, 5, 100));
			$this->inputFilter->merge(new TextImputFilterLcl("confirmMotPasse", false, 5, 100));
			$this->inputFilter->merge(new TextImputFilterLcl("telephone", false, 2, 255));
			$this->inputFilter->merge(new DigitImputFilterLcl("langueDefaut", true, 1));
			$this->inputFilter->merge(new TextImputFilterLcl("prestataire", true, 2, 100));
			$this->inputFilter->merge(new EnumImputFilterLcl("connexionAppli", true, array("-1", "1")));
		}
		
		return $this->inputFilter;
	}
}