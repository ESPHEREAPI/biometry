<?php

namespace Application\Filter\Common;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class CommonInputFilter
{
	protected $inputFilter;
	protected $em;
	private $_translator = null;
	private $_textDomain = 'default';
	private $_translator_enabled = false;
	
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		$this->inputFilter = $inputFilter;

		return $this;
	}
	
	public function getInputFilter()
	{
		return $this->inputFilter;
	}
	
	public function addInputFilter(InputFilterInterface $inputFilter)
	{
		if (!$this->inputFilter) {
			// Create a new input filter
			$this->inputFilter = new InputFilter();
			
			$this->inputFilter->merge($inputFilter);
		}
	}
	
	public function getEntityManager()
	{
		return $this->em;
	}
	
	public function setEntityManager($em)
	{
		$this->em = $em;
	}
	
	public function translate($k)
	{
		if ($this->_translator && $this->_translator_enabled) {
			return $this->_translator->translate($k, $this->_textDomain);
		}
		return $k;
	}
	
	public function setTranslator(\Zend\I18n\Translator\TranslatorInterface $translator = null, $textDomain = null)
	{
		if ($translator) {
			$this->_translator = $translator;
			$this->_translator_enabled = true;
		}
		if ($textDomain) {
			$this->_textDomain = $textDomain;
		}
	}
	
	public function getTranslator()
	{
		return $this->_translator;
	}
	
	public function hasTranslator()
	{
		return $this->_translator !== null;
	}
	
	public function setTranslatorEnabled($enabled = true)
	{
		$this->_translator_enabled = $enabled;
	}
	
	public function isTranslatorEnabled()
	{
		return $this->_translator_enabled;
	}
	
	public function setTranslatorTextDomain($textDomain = 'application')
	{
		$this->_textDomain = $textDomain;
	}
	
	public function getTranslatorTextDomain()
	{
		return $this->_textDomain;
	}
}