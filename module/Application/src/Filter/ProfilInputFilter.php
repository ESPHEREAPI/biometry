<?php

namespace Application\Filter;

use Zend\InputFilter\InputFilter;

use Application\Filter\Common\CommonInputFilter;
use Application\Filter\Common\EnumImputFilterLcl;
use Application\Filter\Common\TextImputFilterLcl;

class ProfilInputFilter extends CommonInputFilter
{
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
			$codeUniqueValidator = array(
					'name' => 'DoctrineModule\Validator\NoObjectExists',
					'options' => array(
							'object_repository' => $this->getEntityManager()->getRepository('Entity\Profil'),
							'fields' => 'code',
							'messages' => array(
									'objectFound' => $this->translate('Code deja utilise', 'application'),
							)
					)
			);
				
			$this->inputFilter->merge(new TextImputFilterLcl("nom", true, 2, 255));
			$this->inputFilter->merge(new TextImputFilterLcl("code", true, 3, 150, $codeUniqueValidator));
			$this->inputFilter->merge(new EnumImputFilterLcl("typeProfil", true, array("admin", "prestataire")));
			$this->inputFilter->merge(new EnumImputFilterLcl("typeSousProfil", true, array("admin", "centre_hospitalier", "laboratoire", "pharmacie", "service_sante")));
		}
		
		return $this->inputFilter;
	}
}