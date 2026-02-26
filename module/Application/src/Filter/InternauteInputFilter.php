<?php

namespace Application\Filter;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\CommonInputFilter;
use Application\Filter\Common\TextImputFilterLcl;
use Application\Filter\Common\DigitImputFilterLcl;
use Application\Filter\Common\EmailImputFilterLcl;

class InternauteInputFilter extends CommonInputFilter
{
	public $controlerUniciteLogin = true;
	public $controlerUniciteEmail = true;
	
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
			
			if($this->controlerUniciteLogin)
			{
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
			}
			else
			{
				$loginUniqueValidator = null;
			}
			
			if($this->controlerUniciteEmail)
			{
				$emailUniqueValidator = array(
						'name' => 'DoctrineModule\Validator\NoObjectExists',
						'options' => array(
								'object_repository' => $this->getEntityManager()->getRepository('Entity\Utilisateur'),
								'fields' => 'email',
								'messages' => array(
									'objectFound' => $this->translate('Email deja utilise', 'application'),
								)
						)
				);
			}
			else
			{
				$emailUniqueValidator = null;
			}
			
			$this->inputFilter->merge(new DigitImputFilterLcl("langueDefaut", true, 1));
			$this->inputFilter->merge(new TextImputFilterLcl("nom", true, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("prenom", false, 2, 255));
			
			$this->inputFilter->merge(new TextImputFilterLcl("oauthProvider", false, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("oauthUid", false, 2, 255));
			
			
			$this->inputFilter->merge(new TextImputFilterLcl("telephone", true, 2, 20));
			// $this->inputFilter->merge(new DigitImputFilterLcl("telephoneDialCode", true, 2, 5));
			// $this->inputFilter->merge(new TextImputFilterLcl("telephoneIso2", true, 2, 5));
			$this->inputFilter->merge(new EmailImputFilterLcl("email", true, $emailUniqueValidator));
			$this->inputFilter->merge(new TextImputFilterLcl("login", true, 5, 150, $loginUniqueValidator));
			
			$this->inputFilter->merge(new TextImputFilterLcl("motPasse", true, 5, 100));
			$this->inputFilter->merge(new TextImputFilterLcl("ancienMotPasse", true, 5, 100));
			$this->inputFilter->merge(new TextImputFilterLcl("nouveauMotPasse", true, 5, 100));
			$this->inputFilter->merge(new TextImputFilterLcl("confirmMotPasse", true, 5, 100));
		}
		
		return $this->inputFilter;
	}
}