<?php

namespace Application\Core;


class AppliConfig {
	
	private $settings;
	
	
	/**
	 * Le constrcuteur avec sa logique est privé pour émpêcher l'instanciation en dehors de la classe
	 **/
	public function __construct()
	{
		$this->settings = require(__DIR__."/../../../../config/custom.application.config.php");
	}
	
	/**
	 *  Permet d'obtenir la valeur de la configuration
	 *  @param $key string clef à récupérer
	 *  @return mixed
	 **/
	public function get($key)
	{
		if (!isset($this->settings[$key])) {
			return null;
		}
		
		return $this->settings[$key];
	}
}
