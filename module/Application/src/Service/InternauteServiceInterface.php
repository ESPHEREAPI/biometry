<?php

namespace Application\Service;

use Zend\Stdlib\RequestInterface as Request;

interface InternauteServiceInterface
{
	public function inscription(Request $request);
	
	public function modifierParametre(Request $request, \Entity\Internaute $internaute);
	
	public function modifierMotPasse(Request $request, \Entity\Internaute $internaute);
	public function inscriptConnResauxSociaux(array $clientData);
}
