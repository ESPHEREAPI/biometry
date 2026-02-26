<?php

namespace Application\Core;

use Zend\Session\Container;
use Zend\I18n\Translator\TranslatorAwareInterface;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class Utilitaire implements TranslatorAwareInterface
{
	private $_translator = null;
	private $_textDomain = 'application';
	private $_translator_enabled = false;
	private $_locale = null;
	
	
	const SEPARATEUR_EMAIL_PARAMS = "AQ_-47!!YR_;BgrSWh";
	const SEPARATEUR_EMAIL_SUB_ELT = "AQ_-47YR_;BgrS!!Wh";
	const NBRE_LIGNE_TABLEAU = 10;
	const NBRE_PAGE_PAGINATION = 5;
	const SALT_CLEE_CRYPTAGE = "KSY12RTEG";
	
	public function translate($k, $textDomain="application")
	{
		if ($this->_translator && $this->_translator_enabled) {
			return $this->_translator->translate($k, $textDomain);
		}
		return $k;
	}
	
	public function setTranslator(\Zend\I18n\Translator\TranslatorInterface $translator = null, $textDomain = null, $locale=null)
	{
		if ($translator) {
			$this->_translator = $translator;
			$this->_translator_enabled = true;
		}
		if ($textDomain) {
			$this->_textDomain = $textDomain;
		}
		if ($locale) {
			$this->_locale = $locale;
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
	
	public function get_next_previous_key(array $array, $current_key, $offset = 1)
	{
	    // create key map
	    $keys = array_keys($array);
	    
	    // find current key
	    $current_key_index = array_search($current_key, $keys);
	    
	    // return desired offset, if in array, or false if not
	    if(isset($keys[$current_key_index + $offset]))
	    {
	        return $keys[$current_key_index + $offset]; 
	    }
	    
	    return false;
	}
	
	public function random_string($type = 'alnum', $len = 8)
	{
	    switch($type)
	    {
	        case 'basic'	: return mt_rand();
	        break;
	        case 'alnum'	:
	        case 'alnum_uppercase'	:
	        case 'alnum_lowercase'	:
	        case 'numeric'	:
	        case 'nozero'	:
	        case 'alpha'	:
	            
	            switch ($type)
	            {
	                case 'alpha'	:	$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	                break;
	                case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	                break;
	                
	                case 'alnum_uppercase'	:	$pool = '23456789ABCDEFGHJKLMNPRSTUVWXYZ';
	                break;
	                
	                case 'alnum_lowercase'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyz';
	                break;
	                
	                
	                case 'numeric'	:	$pool = '0123456789';
	                break;
	                case 'nozero'	:	$pool = '123456789';
	                break;
	            }
	            
	            $str = '';
	            for ($i=0; $i < $len; $i++)
	            {
	                $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
	            }
	            return $str;
	            break;
	        case 'unique'	:
	        case 'md5'		:
	            
	            return md5(uniqid(mt_rand()));
	            break;
	        case 'encrypt'	:
	        case 'sha1'	:
	            
	            $CI =& get_instance();
	            $CI->load->helper('security');
	            
	            return do_hash(uniqid(mt_rand(), TRUE), 'sha1');
	            break;
	    }
	}
	
	public function crypterMotPass($motPass)
	{
		return sha1(md5("RS_".$motPass."-er"));
	}
	
	function existenceFichier($cheminFichier, $typeFichier="IMG") // retourne l'extension du fichier
	{
		$extension = "";
	
		if($typeFichier == "IMG")
		{
			$extensionsImg = array('.jpg', '.jpeg', '.png', '.bmp', '.gif', '.JPG', '.JPEG', '.PNG', '.BMP', '.GIF');
			foreach($extensionsImg as $uneExtension)
			{
				if(file_exists($cheminFichier.$uneExtension))
				{
					$extension = $uneExtension;
					break;
				}
			}
		}
	
		return $extension;
	}
	
	public function _findexts($filename)
	{
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		return $ext;
	}	
	
	public function getDatesFromRange($startDate, $endDate)
	{
		$return = array($startDate);
		$start = $startDate;
		$i = 1;
		if (strtotime($startDate) < strtotime($endDate)) {
			while (strtotime($start) < strtotime($endDate)) {
				$start = date('Y-m-d', strtotime($startDate . '+' . $i . ' days'));
				$return[] = $start;
				$i++;
			}
		}
	
		return $return;
	}
	
	/**
	 * Utilitaire::getWeekday()
	 *
	 * @param mixed $date au format yyyy-mm-dd
	 * @return
	 */
	public function getWeekday($date)
	{
		return date('w', strtotime($date));
	}
	
	public function dateTestToday($date)
	{
		$todayDate = new \DateTime(date("Y-m-d H:i:s"));
		$return = false;
		if($date == $todayDate->format("Y-m-d"))
			$return = true;

		return $return;
	}
	
	public function dateTestYesterday($date)
	{
		$yesterdayDate = date("Y-m-d", strtotime('yesterday'));
		$return = false;
		
		if($date == $yesterdayDate)
			$return = true;
	
		return $return;
	}
	
	public function dateTestTomorrow($date)
	{
		$tomorrowDate = $sundayLastWeek = date("Y-m-d", strtotime('tomorrow'));
		$return = false;
	
		if($date == $tomorrowDate)
			$return = true;
	
		return $return;
	}
	
	public function dateTestCurrentWeek($date)
	{
		$sundayLastWeek = date("Y-m-d", strtotime('sunday last week'));
		$mondayNextWeek = date("Y-m-d", strtotime('monday next week'));
		$return = false;
	
		if($date > $sundayLastWeek && $date < $mondayNextWeek)
			$return = true;
	
		return $return;
	}
	
	public function dateTestCurrentMonth($date)
	{
		$todayDate = new \DateTime(date("Y-m-d H:i:s"));
		$return = false;
	
		if(substr($date, 0, 7) == $todayDate->format("Y-m"))
			$return = true;
	
		return $return;
	}
	
	public function dateTestCurrentYear($date)
	{
		$todayDate = new \DateTime(date("Y-m-d H:i:s"));
		$return = false;
	
		if(substr($date, 0, 4) == $todayDate->format("Y"))
			$return = true;
	
			return $return;
	}	
	
	public function construireDateLocale($date, $afficheHeure=true)
	{
		$sessionAgence = new Container('agence');
		$sessionEmploye = new Container('employe');
		
		
		$dateRetour = "";
		$objetDateMessage = new \DateTime(date("Y-m-d H:i:s", strtotime($date)));
		
		setlocale(LC_ALL, trim($sessionEmploye->offsetGet("code_iso_langue")), trim($sessionEmploye->offsetGet("code_langue")));
		
		if($objetDateMessage)
		{
			if($this->dateTestToday($objetDateMessage->format("Y-m-d"))) // Si c'est aujourd'hui
			{
				$dateRetour =  $this->translate("Auj");
				if($afficheHeure)
					$dateRetour .= " ".strftime("%H:%M", $objetDateMessage->getTimestamp());
			}
			elseif($this->dateTestYesterday($objetDateMessage->format("Y-m-d"))) // Si c'est hier
			{
				$dateRetour =  $this->translate("Hier");
				if($afficheHeure)
					$dateRetour .= " ".strftime("%H:%M", $objetDateMessage->getTimestamp()); // Si c'est dans la semaine
			}
			elseif($this->dateTestCurrentWeek($objetDateMessage->format("Y-m-d")))
			{
				if($afficheHeure)
					$dateRetour =  strftime("%a %H:%M", $objetDateMessage->getTimestamp());
				else
					$dateRetour =  strftime("%a", $objetDateMessage->getTimestamp());
			}
			elseif($this->dateTestCurrentYear($objetDateMessage->format("Y-m-d")))
			{
				setlocale(LC_ALL, "en", "en_US");
				if($afficheHeure)
					$dateRetour =  strftime("%d %b %H:%M", $objetDateMessage->getTimestamp());
				else
					$dateRetour =  strftime("%d %b", $objetDateMessage->getTimestamp());
			}
			else
			{
				setlocale(LC_ALL, "en", "en_US");
				if($afficheHeure)
					$dateRetour =  strftime("%d %b %Y %H:%M", $objetDateMessage->getTimestamp());
				else
					$dateRetour =  strftime("%d %b %Y", $objetDateMessage->getTimestamp());
			}	
		}
		
		return $dateRetour;
	}
	
	/**
	 * Translates a camel case string into a string with
	 * underscores (e.g. firstName -> first_name)
	 *
	 * @param string $str String in camel case format
	 * @return string $str Translated into underscore format
	 */
	public function from_camel_case($str) 
	{
		$str[0] = strtolower($str[0]);
		// $func = create_function('$c', 'return "_" . strtolower($c[1]);');
		// return preg_replace_callback('/([A-Z])/', $func, $str);

		return preg_replace_callback('/([A-Z])/',
			function ($matches) {
			// return ucfirst(strtoupper($matches[1]));
			return "_" . strtolower($matches[1]);
        }, $str);
	}
	
	/**
	 * Translates a string with underscores
	 * into camel case (e.g. first_name -> firstName)
	 *
	 * @param string $str String in underscore format
	 * @param bool $capitalise_first_char If true, capitalise the first char in $str
	 * @return string $str translated into camel caps
	 */
	public function to_camel_case($str, $capitalise_first_char = false) 
	{
		if($capitalise_first_char) {
			$str[0] = strtoupper($str[0]);
		}
		/* $func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/_([a-z])/', $func, $str); */



		return preg_replace_callback('/_([a-z])/',
			function ($matches) {
        	return ucfirst(strtoupper($matches[1]));
        }, $str);
	}

	
	public function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
	
	public function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
	
		return $length === 0 ||
		(substr($haystack, -$length) === $needle);
	}
	
	
	/**
	 * Utilitaire::genererMotPass()
	 *
	 * @return
	 */
	public function genererMotPass()
	{
		$mdp = substr(uniqid(), 7, 6);
		return $mdp;
	}
	
// 	public function tronque($str, $nb = 100)
// 	{
// 		// Si le nombre de caracteres presents dans la chaine est superieur au nombre
// 		// maximum, alors on decoupe la chaine au nombre de caracteres
// 		if (strlen($str) > $nb)
// 		{
// 			$str = substr($str, 0, $nb);
// 			$position_espace = strrpos($str, " "); //on recupere l'emplacement du dernier espace dans la chaine, pour ne pas decouper un mot.
// 			$texte = substr($str, 0, $position_espace);  //on redecoupe a la fin du dernier mot
// 			$str = $str." ..."; //puis on rajoute des ...
// 		}
// 		return $str; //on retourne la variable modifiee
// 	}
	
	/**
	 * Utilitaire::tronque()
	 *
	 * @param mixed $str
	 * @param integer $nb
	 * @return
	 */
	function tronque($str, $nb=100) {
		if (mb_strlen($str, 'UTF-8') > $nb)
		{
			$str = mb_substr($str, 0, $nb, 'UTF-8');
			$pos = mb_strrpos($str, ' ', false, 'UTF-8');
			if($pos === false) 
			{
				return mb_substr($str, 0, $nb, 'UTF-8').' ...';
			}
			return mb_substr($str, 0, $pos, 'UTF-8').' ...';
		}
		else
		{
			return $str;
		}
	}
	
	public function textToURL($chaine)
	{
		$caracteres = array(
				'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'a' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
				'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'e' => 'e', 'e' => 'e', 'e' => 'e', 'ë' => 'e', '€' => 'e',
				'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
				'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
				'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
				'Œ' => 'oe', 'œ' => 'oe',
				'$' => 's');
	
		$chaine = strtr($chaine, $caracteres);
		$chaine = preg_replace('#[^A-Za-z0-9]+#', '-', $chaine);
		$chaine = trim($chaine, '-');
		$chaine = strtolower($chaine);
	
		return $chaine;
	}
	
	function remove_accents($str, $charset='utf-8')
	{
		$url = $str;
	    $url = preg_replace('#&Ccedil;#', 'C', $url);
	    $url = preg_replace('#&ccedil;#', 'c', $url);
	    $url = preg_replace('#&egrave;|&eacute;|&ecirc;|&euml;#', 'e', $url);
	    $url = preg_replace('#&Egrave;|&Eacute;|&Ecirc;|&Euml;#', 'E', $url);
	    $url = preg_replace('#&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;#', 'a', $url);
	    //$url = preg_replace('#@|&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;#', 'A', $url);
	    $url = preg_replace('#&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;#', 'A', $url);
	    $url = preg_replace('#&igrave;|&iacute;|&icirc;|&iuml;#', 'i', $url);
	    $url = preg_replace('#&Igrave;|&Iacute;|&Icirc;|&Iuml;#', 'I', $url);
	    $url = preg_replace('#&otilde;|&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;#', 'o', $url);
	    $url = preg_replace('#&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;#', 'O', $url);
	    $url = preg_replace('#&ugrave;|&uacute;|&ucirc;|&uuml;#', 'u', $url);
	    $url = preg_replace('#&Ugrave;|&Uacute;|&Ucirc;|&Uuml;#', 'U', $url);
	    $url = preg_replace('#&yacute;|&yuml;#', 'y', $url);
	    $url = preg_replace('#&Yacute;#', 'Y', $url);
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    $caracteres = array(
	    		'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'a' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
	    		'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'e' => 'e', 'e' => 'e', 'e' => 'e', 'ë' => 'e', '€' => 'e',
	    		'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
	    		'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
	    		'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
	    		'Œ' => 'oe', 'œ' => 'oe',
	    		'$' => 's');
	    
	    
	    
	    
	    
	    $url = strtr($url, $caracteres);
	    $url = preg_replace('#[^A-Za-z0-9]+#', ' ', $url);
	    // $url = trim($url, '-');
	    // $url = strtolower($url);
	
	    return ($url);
	}
	
// 	function remove_accents($string)
// 	{
// 		if(!preg_match('/[\x80-\xff]/', $string))
// 			return $string;
	
// 		$chars = array(
// 				// Decompositions for Latin-1 Supplement
// 				chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
// 				chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
// 				chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
// 				chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
// 				chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
// 				chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
// 				chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
// 				chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
// 				chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
// 				chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
// 				chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
// 				chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
// 				chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
// 				chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
// 				chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
// 				chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
// 				chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
// 				chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
// 				chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
// 				chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
// 				chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
// 				chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
// 				chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
// 				chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
// 				chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
// 				chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
// 				chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
// 				chr(195).chr(191) => 'y',
// 				// Decompositions for Latin Extended-A
// 				chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
// 				chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
// 				chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
// 				chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
// 				chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
// 				chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
// 				chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
// 				chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
// 				chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
// 				chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
// 				chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
// 				chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
// 				chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
// 				chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
// 				chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
// 				chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
// 				chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
// 				chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
// 				chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
// 				chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
// 				chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
// 				chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
// 				chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
// 				chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
// 				chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
// 				chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
// 				chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
// 				chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
// 				chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
// 				chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
// 				chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
// 				chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
// 				chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
// 				chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
// 				chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
// 				chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
// 				chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
// 				chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
// 				chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
// 				chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
// 				chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
// 				chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
// 				chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
// 				chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
// 				chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
// 				chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
// 				chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
// 				chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
// 				chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
// 				chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
// 				chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
// 				chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
// 				chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
// 				chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
// 				chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
// 				chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
// 				chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
// 				chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
// 				chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
// 				chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
// 				chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
// 				chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
// 				chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
// 				chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
// 		);
	
// 		$string = strtr($string, $chars);
	
// 		return $string;
// 	}

	public function add_heures($heure1,$heure2)
	{
		$secondes1 = $this->heure_to_secondes($heure1);
		$secondes2 = $this->heure_to_secondes($heure2);
		$somme=$secondes1+$secondes2;
		//transfo en h:i:s
		$s=$somme % 60; //reste de la division en minutes => secondes
		$m1=($somme-$s) / 60; //minutes totales
		$m=$m1 % 60;//reste de la division en heures => minutes
		$h=($m1-$m) / 60; //heures
		$resultat=$h.":".$m.":".$s;
		return $resultat;
	}
	
	public function heure_to_secondes($heure)
	{
		$array_heure = explode(":",$heure);
		$secondes = 3600*$array_heure[0];
		if(isset($array_heure[1])) $secondes += 60*$array_heure[1];
		if(isset($array_heure[2])) $secondes += $array_heure[2];
		return $secondes;
	}
	
	public function getMimeType ($file)
	{
		// MIME types array
		$mimeTypes = array(
				"323"       => "text/h323",
				"acx"       => "application/internet-property-stream",
				"ai"        => "application/postscript",
				"aif"       => "audio/x-aiff",
				"aifc"      => "audio/x-aiff",
				"aiff"      => "audio/x-aiff",
				"asf"       => "video/x-ms-asf",
				"asr"       => "video/x-ms-asf",
				"asx"       => "video/x-ms-asf",
				"au"        => "audio/basic",
				"avi"       => "video/x-msvideo",
				"axs"       => "application/olescript",
				"bas"       => "text/plain",
				"bcpio"     => "application/x-bcpio",
				"bin"       => "application/octet-stream",
				"bmp"       => "image/bmp",
				"c"         => "text/plain",
				"cat"       => "application/vnd.ms-pkiseccat",
				"cdf"       => "application/x-cdf",
				"cer"       => "application/x-x509-ca-cert",
				"class"     => "application/octet-stream",
				"clp"       => "application/x-msclip",
				"cmx"       => "image/x-cmx",
				"cod"       => "image/cis-cod",
				"cpio"      => "application/x-cpio",
				"crd"       => "application/x-mscardfile",
				"crl"       => "application/pkix-crl",
				"crt"       => "application/x-x509-ca-cert",
				"csh"       => "application/x-csh",
				"css"       => "text/css",
				"dcr"       => "application/x-director",
				"der"       => "application/x-x509-ca-cert",
				"dir"       => "application/x-director",
				"dll"       => "application/x-msdownload",
				"dms"       => "application/octet-stream",
				"doc"       => "application/msword",
				"dot"       => "application/msword",
				"dvi"       => "application/x-dvi",
				"dxr"       => "application/x-director",
				"eps"       => "application/postscript",
				"etx"       => "text/x-setext",
				"evy"       => "application/envoy",
				"exe"       => "application/octet-stream",
				"fif"       => "application/fractals",
				"flr"       => "x-world/x-vrml",
				"gif"       => "image/gif",
				"gtar"      => "application/x-gtar",
				"gz"        => "application/x-gzip",
				"h"         => "text/plain",
				"hdf"       => "application/x-hdf",
				"hlp"       => "application/winhlp",
				"hqx"       => "application/mac-binhex40",
				"hta"       => "application/hta",
				"htc"       => "text/x-component",
				"htm"       => "text/html",
				"html"      => "text/html",
				"htt"       => "text/webviewhtml",
				"ico"       => "image/x-icon",
				"ief"       => "image/ief",
				"iii"       => "application/x-iphone",
				"ins"       => "application/x-internet-signup",
				"isp"       => "application/x-internet-signup",
				"jfif"      => "image/pipeg",
				"jpe"       => "image/jpeg",
				"jpeg"      => "image/jpeg",
				"jpg"       => "image/jpeg",
				"js"        => "application/x-javascript",
				"latex"     => "application/x-latex",
				"lha"       => "application/octet-stream",
				"lsf"       => "video/x-la-asf",
				"lsx"       => "video/x-la-asf",
				"lzh"       => "application/octet-stream",
				"m13"       => "application/x-msmediaview",
				"m14"       => "application/x-msmediaview",
				"m3u"       => "audio/x-mpegurl",
				"man"       => "application/x-troff-man",
				"mdb"       => "application/x-msaccess",
				"me"        => "application/x-troff-me",
				"mht"       => "message/rfc822",
				"mhtml"     => "message/rfc822",
				"mid"       => "audio/mid",
				"mny"       => "application/x-msmoney",
				"mov"       => "video/quicktime",
				"movie"     => "video/x-sgi-movie",
				"mp2"       => "video/mpeg",
				"mp3"       => "audio/mpeg",
				"mpa"       => "video/mpeg",
				"mpe"       => "video/mpeg",
				"mpeg"      => "video/mpeg",
				"mpg"       => "video/mpeg",
				"mpp"       => "application/vnd.ms-project",
				"mpv2"      => "video/mpeg",
				"ms"        => "application/x-troff-ms",
				"mvb"       => "application/x-msmediaview",
				"nws"       => "message/rfc822",
				"oda"       => "application/oda",
				"p10"       => "application/pkcs10",
				"p12"       => "application/x-pkcs12",
				"p7b"       => "application/x-pkcs7-certificates",
				"p7c"       => "application/x-pkcs7-mime",
				"p7m"       => "application/x-pkcs7-mime",
				"p7r"       => "application/x-pkcs7-certreqresp",
				"p7s"       => "application/x-pkcs7-signature",
				"pbm"       => "image/x-portable-bitmap",
				"pdf"       => "application/pdf",
				"pfx"       => "application/x-pkcs12",
				"pgm"       => "image/x-portable-graymap",
				"pko"       => "application/ynd.ms-pkipko",
				"pma"       => "application/x-perfmon",
				"pmc"       => "application/x-perfmon",
				"pml"       => "application/x-perfmon",
				"pmr"       => "application/x-perfmon",
				"pmw"       => "application/x-perfmon",
				"pnm"       => "image/x-portable-anymap",
				"pot"       => "application/vnd.ms-powerpoint",
				"ppm"       => "image/x-portable-pixmap",
				"pps"       => "application/vnd.ms-powerpoint",
				"ppt"       => "application/vnd.ms-powerpoint",
				"prf"       => "application/pics-rules",
				"ps"        => "application/postscript",
				"pub"       => "application/x-mspublisher",
				"qt"        => "video/quicktime",
				"ra"        => "audio/x-pn-realaudio",
				"ram"       => "audio/x-pn-realaudio",
				"ras"       => "image/x-cmu-raster",
				"rgb"       => "image/x-rgb",
				"rmi"       => "audio/mid",
				"roff"      => "application/x-troff",
				"rtf"       => "application/rtf",
				"rtx"       => "text/richtext",
				"scd"       => "application/x-msschedule",
				"sct"       => "text/scriptlet",
				"setpay"    => "application/set-payment-initiation",
				"setreg"    => "application/set-registration-initiation",
				"sh"        => "application/x-sh",
				"shar"      => "application/x-shar",
				"sit"       => "application/x-stuffit",
				"snd"       => "audio/basic",
				"spc"       => "application/x-pkcs7-certificates",
				"spl"       => "application/futuresplash",
				"sql"       => "text/x-sql",
				"src"       => "application/x-wais-source",
				"sst"       => "application/vnd.ms-pkicertstore",
				"stl"       => "application/vnd.ms-pkistl",
				"stm"       => "text/html",
				"svg"       => "image/svg+xml",
				"sv4cpio"   => "application/x-sv4cpio",
				"sv4crc"    => "application/x-sv4crc",
				"t"         => "application/x-troff",
				"tar"       => "application/x-tar",
				"tcl"       => "application/x-tcl",
				"tex"       => "application/x-tex",
				"texi"      => "application/x-texinfo",
				"texinfo"   => "application/x-texinfo",
				"tgz"       => "application/x-compressed",
				"tif"       => "image/tiff",
				"tiff"      => "image/tiff",
				"tr"        => "application/x-troff",
				"trm"       => "application/x-msterminal",
				"tsv"       => "text/tab-separated-values",
				"txt"       => "text/plain",
				"uls"       => "text/iuls",
				"ustar"     => "application/x-ustar",
				"vcf"       => "text/x-vcard",
				"vrml"      => "x-world/x-vrml",
				"wav"       => "audio/x-wav",
				"wcm"       => "application/vnd.ms-works",
				"wdb"       => "application/vnd.ms-works",
				"wks"       => "application/vnd.ms-works",
				"wmf"       => "application/x-msmetafile",
				"wps"       => "application/vnd.ms-works",
				"wri"       => "application/x-mswrite",
				"wrl"       => "x-world/x-vrml",
				"wrz"       => "x-world/x-vrml",
				"xaf"       => "x-world/x-vrml",
				"xbm"       => "image/x-xbitmap",
				"xla"       => "application/vnd.ms-excel",
				"xlc"       => "application/vnd.ms-excel",
				"xlm"       => "application/vnd.ms-excel",
				"xls"       => "application/vnd.ms-excel",
				"xlsx"      => "vnd.ms-excel",
				"xlt"       => "application/vnd.ms-excel",
				"xlw"       => "application/vnd.ms-excel",
				"xof"       => "x-world/x-vrml",
				"xpm"       => "image/x-xpixmap",
				"xwd"       => "image/x-xwindowdump",
				"z"         => "application/x-compress",
				"zip"       => "application/zip"
		);
	
		$info = pathinfo($file);
		$extension = $info['extension'];
		// $extension = end(explode('.', $file));
	
		$returnValue = "";
		if(isset($mimeTypes[$extension])) $returnValue = $mimeTypes[$extension];
	
		return $returnValue;
	}
	
	
	public function is_connected()
	{
		try {
			$connected = @fsockopen('www.google.com', 80, $num, $error, 5);
			//website, port  (try 80 or 443)
			if ($connected){
				$is_conn = true; //action when connected
				fclose($connected);
			}else{
				$is_conn = false; //action in connection failure
			}
	
		} catch (\Exception $e) {
			$is_conn = false; //action in connection failure
		}
	
		return $is_conn;
	}
	
	/**
	 * Utilitaire::sendMail()
	 *
	 * @param mixed $tabRecepteur ( Exemple: array('m.jean@gmail.com' => 'MBARGA Jean', 'a.patrick@gmail.com' => 'AKONO Patrick') )
	 * @param mixed $sujet
	 * @param mixed $contenuMail
	 * @param mixed $tabPiecesJointes ( Exemple: array("Bulletin" => __DIR__.'/../../../../../public/bulletin.pdf', "Carte" => __DIR__.'/../../../../../public/cis.pdf') )
	 * @return bool
	 */
	public function sendMail($tabRecepteur, $sujet, $contenuMail,
			$tabPiecesJointes=array())
	{
	
		$complementNom = uniqid().'_'.time();
	
		$fichier = fopen(__DIR__.'/../../../../public/mails/mails_'.$complementNom.'.txt', 'w');
	
		$contenuFichier = "";
	
		foreach($tabRecepteur as $emaiRecepteur => $nomRecepteur)
		{
			if (filter_var($emaiRecepteur, FILTER_VALIDATE_EMAIL))
			{
				if($contenuFichier != "") $contenuFichier .= self::SEPARATEUR_EMAIL_PARAMS;
				$contenuFichier .= $emaiRecepteur.self::SEPARATEUR_EMAIL_PARAMS.$nomRecepteur;
			}
		}
	
		if($contenuFichier != "")
		{
			$contenuFichier .= PHP_EOL;
			$contenuFichier .= $sujet.PHP_EOL.$contenuMail;
			$contenuFichier .= PHP_EOL;
	
			if(is_array($tabPiecesJointes) && count($tabPiecesJointes) > 0)
			{
				$contenuFichier .= PHP_EOL;
				$contenuPiecesJointes = "";
				foreach($tabPiecesJointes as $nom => $chemin)
				{
					if($contenuPiecesJointes != "") $contenuPiecesJointes .= self::SEPARATEUR_EMAIL_PARAMS;
					$contenuPiecesJointes .= $nom.self::SEPARATEUR_EMAIL_PARAMS.$chemin;
				}
	
				$contenuFichier .= $contenuPiecesJointes;
			}
	
			if($contenuFichier != "")
			{
				fwrite($fichier, $contenuFichier);
			}
		}
		fclose($fichier);
	}
	
	/**
	 * Utilitaire::sendMailSMTP()
	 *
	 * @param mixed $tabRecepteur ( Exemple: array('m.jean@gmail.com' => 'MBARGA Jean', 'a.patrick@gmail.com' => 'AKONO Patrick') )
	 * @param mixed $sujet
	 * @param mixed $contenuMail
	 * @param mixed $tabPiecesJointes ( Exemple: array("Nom piece jointe 1" => __DIR__.'/../../../../../public/pieceJointe1.pdf', "Nom piece jointe 2" => __DIR__.'/../../../../../public/pieceJointe2.pdf') )
	 * @return bool
	 */
	public function sendMailSMTP($tabRecepteur, $sujet, $contenuMail,
			$tabPiecesJointes=array(), $emailExpediteur=null, $nomExpediteur="", $motPassExpediteur="",
			$autreParams=array())
	{
		$connected = $this->is_connected();
		if(!$connected) return false;
		
		
		
		$codeIsoLangue = "";
		$sessionInternaute = new Container('internaute');
		$sessionEmploye = new Container('employe');
		if($sessionInternaute->offsetExists("code_iso_langue"))
		{
			$codeIsoLangue = $sessionInternaute->offsetGet("code_iso_langue");
		}
		elseif($sessionEmploye->offsetExists("code_iso_langue"))
		{
			$codeIsoLangue = $sessionEmploye->offsetGet("code_iso_langue");
		}
		else
		{
			$codeIsoLangue = "fr";
		}
		
		(date("Y") <= "2016") ? $copyright = "2016" : $copyright = "2016 - ".date("Y");
		
		
		
		$appliConfig =  new \Application\Core\AppliConfig();
		
		if(empty($nomExpediteur))
			$nomExpediteur = $appliConfig->get("nom_appli");
		if(empty($emailExpediteur) || empty($motPassExpediteur))
		{
			$emailExpediteur = $appliConfig->get("email_send_mail");
			$motPassExpediteur = $appliConfig->get("email_send_password");
		}
	
		$bodyParts = array();
	
		
		$msgHtmlMsbt = "<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta charset='utf-8'> <!-- utf-8 works for most cases -->
	<meta name='viewport' content='width=device-width'> <!-- Forcing initial-scale shouldnt be necessary -->
	<meta http-equiv='X-UA-Compatible' content='IE=edge'> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name='x-apple-disable-message-reformatting'>  <!-- Disable auto-scale in iOS 10 Mail entirely -->
	<title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

	<!-- Web Font / @font-face : BEGIN -->
	<!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->
	
	<!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
	<!--[if mso]>
		<style>
			* {
				font-family: sans-serif !important;
			}
		</style>
	<![endif]-->
	


	<!-- Web Font / @font-face : END -->
	
	<!-- CSS Reset -->
    <style>

		/* What it does: Remove spaces around the email design added by some email clients. */
		/* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
	        margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }
        
        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        
        /* What is does: Centers email on Android 4.4 */
        div[style*='margin: 16px 0'] {
            margin:0 !important;
        }
        
        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
                
        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto; 
        }
        
        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }
        
        /* What it does: A work-around for iOS meddling in triggered links. */
        .mobile-link--footer a,
        a[x-apple-data-detectors] {
            color:inherit !important;
            text-decoration: underline !important;
        }

        /* What it does: Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }
      
        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size youd like to fix */
        /* Thanks to Eric Lepetit @ericlepetitsf) for help troubleshooting */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) { /* iPhone 6 and 6+ */
            .email-container {
                min-width: 375px !important;
            }
        }
    
    </style>
    
    <!-- Progressive Enhancements -->
    <style>
        
        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }

        /* Media Queries */
        @media screen and (max-width: 800px) {

            .email-container {
                width: 100% !important;
                margin: auto !important;
            }

            /* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */
            .fluid {
                max-width: 100% !important;
                height: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }

            /* What it does: Forces table cells into full-width rows. */
            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }
            /* And center justify these ones. */
            .stack-column-center {
                text-align: center !important;
            }
        
            /* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */
            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }
            table.center-on-narrow {
                display: inline-block !important;
            }
                
        }

    </style>
	
	
	<style>
		
		.lienSite {
			color : #0088CC;
			
		}
		
		a.lienSite {
			color : #0088CC;
			text-decoration: none;
		}
	</style>

</head>
<body width='100%' bgcolor='#e4e4e4' style='margin: 0; mso-line-height-rule: exactly;'>
    <center style='width: 100%; background: #e4e4e4;'>";


		
if(!isset($autreParams['afficheHeader']) || $autreParams['afficheHeader'] !== false)
{
	$msgHtmlMsbt .= "				
        <!-- Email Header : BEGIN -->
        <table bgcolor='#ffffff' role='presentation' cellspacing='0' cellpadding='0' border='0' align='center' width='800' style='margin: auto;' class='email-container'>
			<tr>
				<td style='padding: 0 0;'>
					<table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'>
                        <tbody>
							<tr>
								<!-- Column : BEGIN -->
								<td class=''>
									<table role='presentation' cellspacing='0' cellpadding='0' border='0' style='margin: 0 !important;'>
										<tbody>
											<tr>
												<td style='padding: 10px; padding-bottom: 0; text-align: left;'>
													<a href='".$appliConfig->get('basePath')."'>
														<img alt='Logo' width='80' height='40' src='".$appliConfig->get('basePath')."/img/logo.png' border='0' class='fluid' style='background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;'>
													</a>
												</td>
											</tr>
											<tr>
												<td style='font-weight: bold; font-family: sans-serif; font-size: 10px; line-height: 20px; color: #555555; padding: 0 10px 10px; text-align: left;' class='center-on-narrow'>
													".$this->translate("Quand nous assurons nous assumons", "application")."
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<!-- Column : END -->
								<!-- Column : BEGIN -->
								<td class='' style='padding-right: 10px;' align='right;'>
									<table role='presentation' cellspacing='0' cellpadding='0' border='0' style='margin: 0 !important;' width='100%'>
										<tbody>
											<tr>
												<td style='font-weight: bold; font-family: sans-serif; font-size: 10px; line-height: 20px; color: #555555; text-align: right;' class='center-on-narrow'>
													".$this->translate($appliConfig->get('adresse'), 'application')."
												</td>
											</tr>
											<tr>
												<td>
													<div style='text-align: right; font-size: 13px;'>
														<span style='display: inline-block;'>
															<a href='".$appliConfig->get('basePath')."/".$codeIsoLangue."/e-insurance' class='lienSite'>".$this->translate("Obtenez un devis")."</a>
														</span>
														<span style='display: inline-block; padding: 0 2px; font-weight: bold; color: #0088CC;' class='lienSite'>
															|
														</span>
														<span style='display: inline-block;'>
															<a href='".$appliConfig->get('basePath')."/".$codeIsoLangue."/contact' class='lienSite'>".$this->translate("Nous Contacter")."</a>
														</span>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<!-- Column : END -->
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<hr />
				</td>
			</tr>
        </table>
        <!-- Email Header : END -->";
}
		
		
		
		
 $msgHtmlMsbt .= "       
        <!-- Email Body : BEGIN -->
		
		<table role='presentation' cellspacing='0' cellpadding='0' border='0' align='center' width='800' style='margin: auto;' class='email-container'>

            <!-- 1 Column Text : BEGIN -->
            <tr>
                <td bgcolor='#ffffff' style='padding: 40px; padding-top: 0; text-align: justify; font-family: sans-serif; font-size: 15px; color: #555555;'>";

 
if(!isset($autreParams['afficheSujet']) || $autreParams['afficheSujet'] === true)
{
 	$msgHtmlMsbt .= "
 					<h1 class='lienSite' style='margin-top: 20px;'>".$sujet."</h1>";
}
 
 $msgHtmlMsbt .= "					
					<div>".$contenuMail."</div>
                </td>
            </tr>
		</table>
		<!-- Email Body : END -->";
 
 
 

if(!isset($autreParams['afficheFooter']) || $autreParams['afficheFooter'] !== false)
{
 	$msgHtmlMsbt .= "
 	
        <!-- Email Footer : BEGIN -->
        <table role='presentation' cellspacing='0' cellpadding='0' border='0' align='center' width='800' style='margin: auto;' class='email-container'>
            <tr>
                <td style='padding: 40px 10px;width: 100%; font-size: 12px; font-family: sans-serif; text-align: center; color: #333;'>
                    © Copyright ".$copyright." ".$appliConfig->get('nom_appli')."
					<br />
					<span class='mobile-link--footer'>".$this->translate($appliConfig->get('adresse'))."</span>
					<br />
					<span class='mobile-link--footer'>".$appliConfig->get('telephone')."</span>
                </td>
            </tr>
        </table>
        <!-- Email Footer : END -->";
}




$msgHtmlMsbt .="    </center>
</body>
</html>";
		
		
 		// $text = new MimePart(strip_tags($this->afficherChaineBD($contenuMail)));
 		// $text->type = "text/plain";
		
		$html = new MimePart($msgHtmlMsbt);
		$html->type = "text/html";
		
		
		// $bodyParts[] = $text;
		$bodyParts[] = $html;
	
	
		if(is_array($tabPiecesJointes) && count($tabPiecesJointes) > 0)
		{
			foreach($tabPiecesJointes as $nom => $chemin)
			{
				$mimeType = $this->getMimeType($chemin);
				if($mimeType != "" && file_exists($chemin))
				{
					$info = pathinfo($chemin);
					$extension = $info['extension'];
	
					$fileContents = fopen($chemin, 'r');
					$attachment = new MimePart($fileContents);
	
					$attachment->type = $mimeType;
	
					$attachment->filename = $nom.".".$extension;
					$attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
					$attachment->encoding    = Mime::ENCODING_BASE64;
	
					$bodyParts[] = $attachment;
				}
			}
		}
	
	
	
		$body = new MimeMessage();
		$body->setParts($bodyParts);
	
	
		$message = new Message();
		$message->setBody($body);
		$message->setFrom($emailExpediteur, $nomExpediteur);
		// $message->addTo('mousbit@yahoo.fr', "Parent d'eleve");
		foreach($tabRecepteur as $emaiRecepteur => $nomRecepteur)
		{
			if (filter_var($emaiRecepteur, FILTER_VALIDATE_EMAIL)) $message->addTo($emaiRecepteur, $nomRecepteur);
		}
	
	
	
		$message->setSubject($sujet);
		$message->setEncoding("UTF-8");
	
	
		// Setup SMTP transport using PLAIN authentication over TLS
		$transport = new SmtpTransport();
		$options   = new SmtpOptions(array(
				'name'              => $appliConfig->get("nom_domaine"),
				'host'              => $appliConfig->get("nom_smtp"),
				'port'              => $appliConfig->get("port_smtp"), // Notice port change for TLS is 587
				'connection_class'  => 'plain',
				'connection_config' => array(
						'username' => $emailExpediteur,
						'password' => $motPassExpediteur,
						'ssl'      => 'tls',
				),
		));
	
		$transport->setOptions($options);
	
		try {
			$transport->send($message);
	
		} catch (\Exception $e) {
			return false;
		}
	
		return true;
	}
	
	/**
	 * Utilitaire::nettoyageChaine()
	 *
	 * @param mixed $chaineCaractere
	 * @return
	 */
	public function nettoyageChaine($chaineCaractere)
	{
		$chaineCaractere = htmlentities($chaineCaractere, ENT_COMPAT, "UTF-8");
		$chaineCaractere = trim($chaineCaractere);
		$chaineCaractere = str_replace("'", "&#39;", $chaineCaractere);
		
		return $chaineCaractere;
	}
	
	/**
	 * Utilitaire::fileSizeConvert()
	 *
	 * @param mixed $bytes
	 * @return
	 */
	public function fileSizeConvert($bytes)
	{
		$bytes = floatval($bytes);
		$arBytes = array(
				0 => array(
						"UNIT" => "TB",
						"VALUE" => pow(1024, 4)
				),
				1 => array(
						"UNIT" => "GB",
						"VALUE" => pow(1024, 3)
				),
				2 => array(
						"UNIT" => "MB",
						"VALUE" => pow(1024, 2)
				),
				3 => array(
						"UNIT" => "KB",
						"VALUE" => 1024
				),
				4 => array(
						"UNIT" => "B",
						"VALUE" => 1
				),
		);
	
		foreach($arBytes as $arItem)
		{
			if($bytes >= $arItem["VALUE"])
			{
				$result = $bytes / $arItem["VALUE"];
				$result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
				break;
			}
		}
		return $result;
	}
	
	/* creates a compressed zip file */
	function createZip($files = array(), $destination = '', $overwrite = false)
	{
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		
		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new \ZipArchive();
			if($zip->open($destination, $overwrite ? \ZipArchive::OVERWRITE : \ZipArchive::CREATE) !== true) {
				return false;
			}
			//add the files
			foreach($valid_files as $file) {
				$zip->addFile($file, basename($file));
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
	
			//close the zip -- done!
			$zip->close();
	
			//check to make sure the file exists
			return file_exists($destination);
		}
		else
		{
			return false;
		}
	}
	
	public function afficherChaineBD($chaine)
	{
		$chaine = html_entity_decode($chaine, ENT_COMPAT, "UTF-8");
		$chaine = str_replace("&#39;", "'", $chaine);
		return $chaine;
	}
	
	
	public function supprimerRepertoire($dir) 
	{
		if(is_dir($dir))
		{
			$it = new \RecursiveDirectoryIterator($dir);
			$it = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
			foreach($it as $file) {
				if ('.' === $file->getBasename() || '..' ===  $file->getBasename()) continue;
				if ($file->isDir()) rmdir($file->getPathname());
				else unlink($file->getPathname());
			}
			rmdir($dir);
		}
	}
	
	public function construireUrlPaypal()
	{
		$appliConfig =  new \Application\Core\AppliConfig();
		
		/// Pour les paiements
		$infosPaiement = $appliConfig->get("infos_paiement");
		
    	$infosPaypal = $infosPaiement["infos_paypal"];

		$api_paypal = $infosPaypal["url_serveur_paypal"].'?'; // Site de l'API PayPal. On ajoute déjà le ? afin de concaténer directement les paramètres.
		$parametres_paypal = array(
									'VERSION' => $infosPaypal["version"],
									'USER' => $infosPaypal["user"],
									'PWD' => $infosPaypal["pwd"],
									'SIGNATURE' => $infosPaypal["signature"]
								  ); // Ajoute tous les paramètres
		
		
		$api_paypal .= http_build_query($parametres_paypal);
	
		return 	$api_paypal; // Renvoie la chaîne contenant tous nos paramètres.
	}
	
	public function recupererParamPaypal($resultat_paypal)
	{
		$liste_parametres = explode("&",$resultat_paypal); // Crée un tableau de paramètres
		foreach($liste_parametres as $param_paypal) // Pour chaque paramètre
		{
			list($nom, $valeur) = explode("=", $param_paypal); // Sépare le nom et la valeur
			$liste_param_paypal[$nom]=urldecode($valeur); // Crée l'array final
		}
		return $liste_param_paypal; // Retourne l'array
	}
	
	public function paiementPaypal($idSouscription, $montant, $nomUrlProduit, $baseUrl, $codeIsoLangue, $descriptionProduit, $cheminImage=null)
	{
		$blockCipher = new \Zend\Crypt\BlockCipher(new \Zend\Crypt\Symmetric\Mcrypt(array('algo' => 'aes')));
		$blockCipher->setKey($nomUrlProduit.self::SALT_CLEE_CRYPTAGE);
		$idCrypte = $blockCipher->encrypt($idSouscription);
		
		
		$varRetour = array('error' => "", 'tableError' => "", 'token' => "");
		$appliConfig =  new \Application\Core\AppliConfig();
		
		/// Pour les paiements
		$infosPaiement = $appliConfig->get("infos_paiement");
		
    	$infosPaypal = $infosPaiement["infos_paypal"];
    	
    	
    	$requete_url = $this->construireUrlPaypal();
//     	$requete_url = $requete_url."&METHOD=SetExpressCheckout".
//     			"&CANCELURL=".urlencode($baseUrl."/".$codeIsoLangue."/e-insurance/erreur-paiement").
//     			"&RETURNURL=".urlencode($baseUrl."/".$codeIsoLangue."/e-insurance/".$nomUrlProduit."/souscrire/resultat?codeProduitMsbt=".urlencode($idCrypte)).
//     			"&AMT=".$this->convertirEuro($montant).
//     			"&CURRENCYCODE=".$infosPaypal["code_devise"].
//     			"&DESC=".urlencode($descriptionProduit).
//     			"&LOCALECODE=".$codeIsoLangue;
    	
    	
    	$requete_params = array("METHOD" => "SetExpressCheckout",
    							"AMT" => $this->convertirEuro($montant),
    							"CURRENCYCODE" => $infosPaypal["code_devise"],
				    			"DESC" => $descriptionProduit,
				    			"LOCALECODE" => $codeIsoLangue,
    							"CANCELURL" => $baseUrl."/".$codeIsoLangue."/e-insurance/erreur-paiement",
				    			"RETURNURL" => $baseUrl."/".$codeIsoLangue."/e-insurance/".$nomUrlProduit."/souscrire/resultat?codeProduitMsbt=".$idCrypte,);
    	
    	
    	if(!empty($cheminImage))
    	{
    		$requete_params["HDRIMG"] = urlencode($cheminImage);
    	}
    	
    	
    	// echo $requete_url."?".http_build_query($requete_params); exit;
    	
    	
    	// Initialise notre session cURL. On lui donne la requête à exécuter
    	$ch = curl_init($requete_url."&".http_build_query($requete_params));
    	// $ch = curl_init($requete_url);
    	
    	
    	// On ne verifie pas le certificat ssl si on est en mode demo
    	if($appliConfig->get("mode_demo"))
    	{
    		// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné.
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    	}
    	
    	// Modifie l'option CURLOPT_RETURNTRANSFER afin que le resultat soit contenu dans une variable
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	
    	// On lance l'exécution de la requête URL.
    	if($resultat_paypal = curl_exec($ch)) // Si elle s'est exécutée correctement
    	{
    		$liste_param_paypal = $this->recupererParamPaypal($resultat_paypal);
    		
    		// Si la requête a été traitée avec succès
			if ($liste_param_paypal['ACK'] == 'Succes')
			{
				// Redirige le visiteur sur le site de PayPal
				// header("Location: https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']);
				
				
				$varRetour['token'] = $liste_param_paypal['TOKEN'];
				$varRetour['modePaiement'] = 'paiement-paypal';
			}
			else // En cas d'échec, affiche la première erreur trouvée.
			{
				$varRetour['error'] = "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";
			}
    	}
    	else // S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
    	{
    		$varRetour['error'] =  curl_error($ch);
    	}
    	
    	// On ferme notre session cURL.
    	curl_close($ch);
    	
    	
    	return $varRetour;
	}
	
	public function construireParamsCarteBancaire()
	{
		$appliConfig =  new \Application\Core\AppliConfig();
	
		/// Pour les paiements
		$infosPaiement = $appliConfig->get("infos_paiement");
		$infosCarteBancaire = $infosPaiement["infos_carte_bancaire"];
	
		$parametres_carte_bancaire = array(
			'merchantId' => $infosCarteBancaire["merchantId"],
			'serviceKey' => $infosCarteBancaire["serviceKey"],
			'countryCurrencyCode' => $infosCarteBancaire["countryCurrencyCode"]
		); // Ajoute tous les parametres
	
		return 	$parametres_carte_bancaire; // Renvoie la chaîne contenant tous nos paramètres.
	}
	
	public function getCarteBancaireTransactionStatus($souscription)
	{
		$varRetour = array('error' => "", 'tableError' => "", 'token' => "");
		$appliConfig =  new \Application\Core\AppliConfig();
	
		/// Pour les paiements
		$infosPaiement = $appliConfig->get("infos_paiement");
		$infosCarteBancaire = $infosPaiement["infos_carte_bancaire"];
			
		$paramsCarteBancaire = $this->construireParamsCarteBancaire();
			
		$requete_params = array("cipgid" => $infosCarteBancaire["merchantId"],
								"mytxnref" => $souscription->getCode(),
								"cipgtxnref" => $souscription->getTransactionFournisseur(),
		);
			
		$requete_params = array_merge($paramsCarteBancaire, $requete_params);
			
		// Initialise notre session cURL. On lui donne la requête à exécuter
		// $ch = curl_init($infosCarteBancaire['url_transaction_status']);
		$ch = curl_init();
			
			
		// On se verifie pas le certificat ssl si on est en mode demo
		if($appliConfig->get("mode_demo"))
		{
			// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné.
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
	
		//set option of URL to post to
		curl_setopt($ch, CURLOPT_URL, $infosCarteBancaire['url_transaction_status']); // Demander ce parametre a UBA
		//set option of request method -----HTTP POST Request
		curl_setopt($ch, CURLOPT_POST, true);
		//The HTTP authentication methods to use
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		//This line sets the parameters to post to the URL
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requete_params);
		//This line makes sure that the response is gotten back to the
		// $response object(see below) and not echoed
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
		// On lance l'exécution de la requête URL.
		if($response = curl_exec($ch)) // Si elle s'est exécutée correctement
		{
			if(empty($response) || $response == "null")
			{
				$varRetour['error'] = "Transaction non initialisee";
			}
			else
			{
				// object
				$returnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				//Close the stream
				// curl_close($ch);
	
				//Check if there are no errors ie httpresponse == 200 -OK
				if ($returnCode == 200) {
					//If there are no errors, the transaction ID is returned
					$varRetour = $response;
						
				}
				else
				{
					$varRetour['error'] = "Erreur de communication avec le serveur : <b>".$returnCode."</b>";
				}
			}
		}
		else // S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
		{
			$varRetour['error'] =  curl_error($ch);
		}
			
		// On ferme notre session cURL.
		curl_close($ch);
			
		return $varRetour;
	}
	
	
	public function paiementCarteBancaire($souscription, $nomUrlProduit, $baseUrl, $codeIsoLangue, $descriptionProduit, $cheminImage=null)
	{
		$varRetour = array('error' => "", 'tableError' => "", 'token' => "");
		$appliConfig =  new \Application\Core\AppliConfig();
		
		if($souscription->getTransactionFournisseur() && $souscription->getStatutPaiement() != "6") // Retester pour les paiements echoues
		{
			$varRetour['transactionid'] = $souscription->getTransactionFournisseur();
			$varRetour['modePaiement'] = 'paiement-carte-bancaire';
		}
		else
		{
			if($codeIsoLangue == "fr")
			{
				switch ($souscription->getCotation()->getProduit()->getId()) {
					case 4 :
						$nomProduit = "Automobile";
						break;
							
					case 21 :
						$nomProduit = "Caution";
						break;
				
					case 7 :
						$nomProduit = "Responsabilite Civile Chef de Famille";
						break;
							
					case 23 :
						$nomProduit = "Responsabilite Civile Professionnelle";
						break;
							
					case 22 :
						$nomProduit = "Responsabilite Scolaire";
						break;
							
					case 5 :
						$nomProduit = "Zenithe Visio";
						break;
							
					case 6 :
						$nomProduit = "Individuelle Accident";
						break;
							
					case 24 :
						$nomProduit = "Multirisque Habitation";
						break;
				
					default :
						$nomProduit = "Nom du produit";
						break;
				}	
			}
			else
			{
			    switch ($souscription->getCotation()->getProduit()->getId()) {
					case 4 :
						$nomProduit = "Automobile";
						break;
							
					case 21 :
						$nomProduit = "Bond";
						break;
				
					case 7 :
						$nomProduit = "Household Liability";
						break;
							
					case 23 :
						$nomProduit = "Professional Liability";
						break;
							
					case 22 :
						$nomProduit = "School Liability";
						break;
							
					case 5 :
						$nomProduit = "Zenithe Visio";
						break;
							
					case 6 :
						$nomProduit = "Personal Accident";
						break;
							
					case 24 :
						$nomProduit = "Home Multirisk";
						break;
				
					default :
						$nomProduit = "Product name";
						break;
				}	
			}
			
			
			
			$utilisateur = $souscription->getInternaute()->getUtilisateur();
	
			/// Pour les paiements
			$infosPaiement = $appliConfig->get("infos_paiement");
			$infosCarteBancaire = $infosPaiement["infos_carte_bancaire"];
			 
			$paramsCarteBancaire = $this->construireParamsCarteBancaire();
			 
			$requete_params = array("referenceNumber" => $souscription->getCode(),
									"date" => date("d/m/Y H:i:s"),
									"total" => $souscription->getPrimeTtc(),
									// "total" => "10",
									"noOfItems" => "1",
									"description" => $nomProduit,
									"customerLastname" => $this->remove_accents($utilisateur->getNom()),
									"customerFirstName" => $this->remove_accents($utilisateur->getPrenom()),
									"customerEmail" => $utilisateur->getEmail(),
									"customerPhoneNumber" => "+".$utilisateur->getTelephoneDialCode().str_replace(" ", "", $utilisateur->getTelephone()),
									// "approveurl" => $baseUrl."/".$codeIsoLangue."/e-insurance/client/souscription/details/".$souscription->getId(), // URL en cas de success
									// "cancelurl" => $baseUrl."/".$codeIsoLangue."/e-insurance/".$nomUrlProduit."/souscrire", // URL en cas d'annulation par l'internaute
									// "declineurl" => $baseUrl."/".$codeIsoLangue."/e-insurance/".$nomUrlProduit."/souscrire", // URL en cas d'echec du paiement
									);
			 
			$requete_params = array_merge($paramsCarteBancaire, $requete_params);

			
			if(!empty($cheminImage))
			{
				// $requete_params["HDRIMG"] = urlencode($cheminImage);
			}
			 
			// Initialise notre session cURL. On lui donne la requête à exécuter
			// $ch = curl_init($infosCarteBancaire['url_post']);
			$ch = curl_init();
			 
			 
			// On se verifie pas le certificat ssl si on est en mode demo
			if($appliConfig->get("mode_demo"))
			{
				// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné.
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			}
	
			//set option of URL to post to
			curl_setopt($ch, CURLOPT_URL, $infosCarteBancaire['url_post']); // Demander ce parametre a UBA
			//set option of request method -----HTTP POST Request
			curl_setopt($ch, CURLOPT_POST, true);
			//The HTTP authentication methods to use
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			//This line sets the parameters to post to the URL
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requete_params);
			//This line makes sure that the response is gotten back to the
			// $response object(see below) and not echoed
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			 
			// On lance l'exécution de la requête URL.
			if($response = curl_exec($ch)) // Si elle s'est exécutée correctement
			{
				if(empty($response) || $response == "null")
				{
					$varRetour['error'] = "Transaction non initialisee";
				}
				else
				{
					// object
					$returnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					//Close the stream
					// curl_close($ch);
		
					//Check if there are no errors ie httpresponse == 200 -OK
					if ($returnCode == 200) {
						//If there are no errors, the transaction ID is returned
						$transactionid = $response;
						
						$varRetour['transactionid'] = $transactionid;
						$varRetour['modePaiement'] = 'paiement-carte-bancaire';
						
					}
					else
					{
						$varRetour['error'] = "Erreur de communication avec le serveur : <b>".$returnCode."</b>";
					}
				}
			}
			else // S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
			{
				$varRetour['error'] =  curl_error($ch);
			}
			 
			// On ferme notre session cURL.
			curl_close($ch);	
		}
				 
		return $varRetour;
	}
	
	public function construireParamsOrangeMoney($souscription, $nomUrlProduit, $baseUrl, $codeIsoLangue)
	{
		$appliConfig =  new \Application\Core\AppliConfig();
	
		/// Pour les paiements
		$infosPaiement = $appliConfig->get("infos_paiement");
		$infosOrangeMoney = $infosPaiement["infos_orange_money"];
	
		$parametres_orange_money = array(
				'merchant_key' => $infosOrangeMoney["merchant_key"],
				'currency' => $infosOrangeMoney["currency"],
				"reference" => $infosOrangeMoney["reference"],
				"return_url" => $baseUrl."/".$infosOrangeMoney["return_url"]."?codeMsbt=".$souscription->getCode(),
				"cancel_url" => $baseUrl."/".$codeIsoLangue."/e-insurance/".$nomUrlProduit."/souscrire",
				"notif_url"  => $baseUrl."/".$infosOrangeMoney["notif_url"]."?codeMsbt=".$souscription->getCode(),
		); // Ajoute tous les parametres
	
		return 	$parametres_orange_money; // Renvoie la chaîne contenant tous nos paramètres.
	}
	
	public function construireHeaderOrangeMoney($requete_params)
	{
		$appliConfig =  new \Application\Core\AppliConfig();
	
		/// Pour les paiements
		$infosPaiement = $appliConfig->get("infos_paiement");
		$infosOrangeMoney = $infosPaiement["infos_orange_money"];
	
		$headers = array(
					"User-Agent: ".$infosOrangeMoney['User-Agent'],
					"Authorization: ".$infosOrangeMoney['Authorization'],
					"Host: ".$infosOrangeMoney['Host'],
					"Content-Type: ".$infosOrangeMoney['Content-Type'],
					"Accept: ".$infosOrangeMoney['Accept'],
					"Content-Length: ".strlen($requete_params),
			);
	
		return 	$headers; // Renvoie la chaîne contenant tous nos paramètres.
	}
	
	/**
	 * Utilitaire::getOrangeMoneyTransactionStatus()
	 *
	 * @param integer $orderId : Code de la commande sur la plateforme locale
	 * @param double $amount
	 * @param string $payToken
	 * @return array
	 */
	public function getOrangeMoneyTransactionStatus($orderId, $amount, $payToken)
	{
		$varRetour = array('error' => "", 'tableError' => "", 'status' => "", 'txnid' => "");
		$appliConfig =  new \Application\Core\AppliConfig();

		/// Pour les paiements
		$infosPaiement = $appliConfig->get("infos_paiement");
		$infosOrangeMoney = $infosPaiement["infos_orange_money"];

		$requete_params = array("order_id"   => $orderId,
								"amount"     => $amount,
								"pay_token"  => $payToken
		);

		$requete_params = json_encode($requete_params);
		$headers = $this->construireHeaderOrangeMoney($requete_params);
			

		// Initialise notre session cURL.
		$ch = curl_init();


		// On se verifie pas le certificat ssl si on est en mode demo
		if($appliConfig->get("mode_demo"))
		{
			// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné.
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}

		//set option of URL to post to
		curl_setopt($ch, CURLOPT_URL, $infosOrangeMoney['url_transaction_status']);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requete_params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// On lance l'exécution de la requête URL.
		if($response = curl_exec($ch)) // Si elle s'est exécutée correctement
		{
			if(empty($response) || $response == "null")
			{
				$varRetour['error'] = "Transaction non initialisee";
			}
			else
			{
				// object
				$returnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				//Close the stream
				// curl_close($ch);
					
				// var_dump($response, $returnCode); exit;

				//Check if there are no errors ie httpresponse == 200 -OK
				if ($returnCode == 201) {
					//If there are no errors, the transaction ID is returned

					$data = json_decode($response, true);

					$varRetour['status'] = $data['status'];
					$varRetour['txnid'] = $data['txnid'];
				}
				else
				{
					$varRetour['error'] = "Erreur de communication avec le serveur : <b>".$returnCode."</b>";
				}
			}
		}
		else // S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
		{
			$varRetour['error'] =  curl_error($ch);
		}

		// On ferme notre session cURL.
		curl_close($ch);	
		return $varRetour;
	}
	
	
	
	public function construireParamsMtnMobileMoney()
	{
		$appliConfig =  new \Application\Core\AppliConfig();
	
		/// Pour les paiements
		$infosPaiement = $appliConfig->get("infos_paiement");
		$infosMtnMobileMoney = $infosPaiement["infos_mtn_mobile_money"];
	
		$parametres_mtn_mobile_money = array(
				'_email' => $infosMtnMobileMoney["email"],
				'_clP' => $infosMtnMobileMoney["password"],
				'idbouton' => "2",
				'typebouton' => "PAIE",
		); // Ajoute tous les parametres
	
		return 	$parametres_mtn_mobile_money; // Renvoie la chaîne contenant tous nos paramètres.
	}
	
	
	public function paiementMtnMobileMoney($souscription, $donneesToutesEtapes)
	{
		$varRetour = array('error' => "", 'tableError' => "", 'token' => "");
		$appliConfig =  new \Application\Core\AppliConfig();
	
	
		$utilisateur = $souscription->getInternaute()->getUtilisateur();
	
		/// Pour les paiements
		$infosPaiement = $appliConfig->get("infos_paiement");
		$infosMtnMobileMoney = $infosPaiement["infos_mtn_mobile_money"];
			
		$paramsMtnMobileMoney = $this->construireParamsMtnMobileMoney();
			
		$requete_params = array("_tel" => $donneesToutesEtapes['form_telMobileMoney'],
								"_amount" => $souscription->getPrimeTtc(),
								);
			
		$requete_params = array_merge($paramsMtnMobileMoney, $requete_params);
	
	
		if(!empty($cheminImage))
		{
			// $requete_params["HDRIMG"] = urlencode($cheminImage);
		}
			
		// Initialise notre session cURL. On lui donne la requête à exécuter
		// $ch = curl_init($infosCarteBancaire['url_post']);
		$ch = curl_init();
			
			
		// On se verifie pas le certificat ssl si on est en mode demo
		if($appliConfig->get("mode_demo"))
		{
			// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné.
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
	
		//set option of URL to post to
		curl_setopt($ch, CURLOPT_URL, $infosMtnMobileMoney['url']); // Demander ce parametre a MTN
		//set option of request method -----HTTP POST Request
		curl_setopt($ch, CURLOPT_POST, true);
		//The HTTP authentication methods to use
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		//This line sets the parameters to post to the URL
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requete_params);
		//This line makes sure that the response is gotten back to the
		// $response object(see below) and not echoed
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
		// On lance l'exécution de la requête URL.
		if($response = curl_exec($ch)) // Si elle s'est exécutée correctement
		{
			if(empty($response) || $response == "null")
			{
				$varRetour['error'] = "Transaction non initialisee";
			}
			else
			{
				// object
				$returnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
				//Check if there are no errors ie httpresponse == 200 -OK
				if ($returnCode == 200) {
					
					$reponseArray = json_decode($response, true);

					if(empty($reponseArray['StatusCode']))
					{
						$varRetour['error'] = "Le serveur met trop de temps a repondre";
					}
					elseif($reponseArray['StatusCode'] == "100")
					{
						$varRetour['error'] = "La transaction a echouee, verifiez que le numero <b>".$donneesToutesEtapes['form_telMobileMoney']."</b> que vous avez saisi est un numero MTN Cameroun valide et que votre compte MTN Mobile Money est active et que vous avez suffisament de credit";
					}
					elseif($reponseArray['StatusCode'] == "01")
					{
						// Dans ce cas, la transaction s'est deroulee avec success
						$varRetour['transactionid'] = $reponseArray['TransactionID'];
						$varRetour['modePaiement'] = 'paiement-mtn-mobile-money';
					}
					else
					{
					    $varRetour['error'] = "Erreur de communication avec le serveur : <b>Probleme General ".$reponseArray['StatusCode']."</b>";
					}	
				}
				else
				{
					$varRetour['error'] = "Erreur de communication avec le serveur, veuillez consulter votre telephone et tapez *126# pour continuer la transaction <b>".$returnCode."</b>";
				}
			}
		}
		else // S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
		{
			$varRetour['error'] =  curl_error($ch);
		}
			
		// On ferme notre session cURL.
		curl_close($ch);
	
			
		return $varRetour;
	}
	
	
	
	public function paiementOrangeMoney($souscription, $nomUrlProduit, $baseUrl, $codeIsoLangue, $descriptionProduit, $cheminImage=null)
	{
		$varRetour = array('error' => "", 'tableError' => "", 'token' => "");
		$appliConfig =  new \Application\Core\AppliConfig();
		
		
		if($souscription->getTransactionFournisseur())
		{
			$varRetour['transactionid'] = $souscription->getTransactionFournisseur();
			$varRetour['modePaiement'] = 'paiement-orange-money';
		}
		else
		{
			$utilisateur = $souscription->getInternaute()->getUtilisateur();
	
			/// Pour les paiements
			$infosPaiement = $appliConfig->get("infos_paiement");
			$infosOrangeMoney = $infosPaiement["infos_orange_money"];
			 
			$paramsOrangeMoney = $this->construireParamsOrangeMoney($souscription, $nomUrlProduit, $baseUrl, $codeIsoLangue);
			 
			$requete_params = array("order_id" => $souscription->getCode(),
									"amount" => $souscription->getPrimeTtc(),
									"lang" => $codeIsoLangue
									);
			 
			$requete_params = array_merge($paramsOrangeMoney, $requete_params);
			$requete_params = json_encode($requete_params);
			
			$headers = $this->construireHeaderOrangeMoney($requete_params);
			
			 
			// Initialise notre session cURL.
			$ch = curl_init();
			 
			 
			// On se verifie pas le certificat ssl si on est en mode demo
			if($appliConfig->get("mode_demo"))
			{
				// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné.
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			}
	
			//set option of URL to post to
			curl_setopt($ch, CURLOPT_URL, $infosOrangeMoney['url']);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requete_params);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			 
			// On lance l'exécution de la requête URL.
			if($response = curl_exec($ch)) // Si elle s'est exécutée correctement
			{
				if(empty($response) || $response == "null")
				{
					$varRetour['error'] = "Transaction non initialisee";
				}
				else
				{
					// object
					$returnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					//Close the stream
					// curl_close($ch);
					
					// var_dump($response, $returnCode); exit;
		
					//Check if there are no errors ie httpresponse == 200 -OK
					if ($returnCode == 201) {
						//If there are no errors, the transaction ID is returned
						
						$data = json_decode($response, true);
						
						$varRetour['transactionid'] = $data['pay_token'];
						$varRetour['codeSouscription'] = $souscription->getCode();
						$varRetour['modePaiement'] = 'paiement-orange-money';
						
					}
					else
					{
						$varRetour['error'] = "Erreur de communication avec le serveur : <b>".$returnCode."</b>";
					}
				}
			}
			else // S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
			{
				$varRetour['error'] =  curl_error($ch);
			}
			 
			// On ferme notre session cURL.
			curl_close($ch);	
		}
				 
		return $varRetour;
	}
	
	public function paiementLivraison($souscription, $nomUrlProduit, $baseUrl, $codeIsoLangue, $descriptionProduit, $cheminImage=null)
	{
		$varRetour = array('error' => "", 
						  'tableError' => "",
						  'token' => "",
						  'souscription_id' => $souscription->getId(),
						  'modePaiement' => 'paiement-livraison',
						  'transactionid' => null
					 );
			
		return $varRetour;
	}
	
	public function convertirEuro($montant)
	{
		$appliConfig =  new \Application\Core\AppliConfig();
		
		return round($montant/$appliConfig->get("taux_conversion_euro"), 2);
	}
	
	public function construireLigneFormulaire($label, $input, $classConteneur="")
	{
		$varRrtour = '<div class="form-group '.$classConteneur.'">';	
		$varRrtour .= '	<div class="col-md-3">';
		$varRrtour .= 		$label;
		$varRrtour .= '	</div>';
		$varRrtour .= '	<div class="col-md-9">';
		$varRrtour .= 		$input;
		$varRrtour .= '	</div>';
		$varRrtour .= '	<div class="clearBoth"></div>';
		$varRrtour .= '</div>';
		
		
		return $varRrtour;
	}
	
	
	public function construireLigneMultiCheckFormulaire($label, $valueOptions, $values, $nameElement, $classConteneur="")
	{
		$varRrtour = '<div class="form-group '.$classConteneur.'">';
		$varRrtour .= '	<div class="col-md-3">';
		$varRrtour .= 		$label;
		$varRrtour .= '	</div>';
		$varRrtour .= '	<div class="col-md-9">';
		
		foreach ($valueOptions as $option => $element)
		{
			$checked = "";
			$selected = "";
			$disabled = "";
			if($values && in_array($element['value'], $values))
				$checked = 'checked="checked"';
			if(isset($element['selected']))
				$checked = 'checked="checked"';
			if(isset($element['disabled']))
				$disabled = 'disabled="disabled"';
			
			$varRrtour .= '		<div class="col-md-6">';
			$varRrtour .= '			<div class="ctneurICheck">';
			$varRrtour .= '				<label>';
			$varRrtour .= '					<input type="checkbox" name="'.$nameElement.'[]" value="'.$element['value'].'" '.$checked.' '.$selected.' '.$disabled.'> '.$element['label'];
			$varRrtour .= '				</label>';
			$varRrtour .= '			</div>';
			$varRrtour .= '		</div>';
		}

		$varRrtour .= '	</div>';
		$varRrtour .= '	<div class="clearBoth"></div>';
		$varRrtour .= '</div>';
	
	
		return $varRrtour;
	}
	
	function sendSmsHttp($telephone, $message, $senderName="", $login = "", $password = "")
	{
	    $varRetour = array("status" => "", "error" => "");
	    
	    if(is_array($telephone))
	    {
	        foreach ($telephone as $unTelephone)
	        {
	            $varRetour = $this->sendSmsNexah($unTelephone, $message, $senderName, $login, $password);
	            //$varRetour = $this->sendSmsMTarget($unTelephone, $message, $senderName, $login, $password);
	            //$varRetour = $this->sendUnSmsHttp($unTelephone, $message, $senderName, $login, $password);
	        }
	    }
	    else
	    {
	        $varRetour = $this->sendSmsNexah($telephone, $message, $senderName, $login, $password);
	        //$varRetour = $this->sendSmsMTarget($telephone, $message, $senderName, $login, $password);
	        //$varRetour = $this->sendUnSmsHttp($telephone, $message, $senderName, $login, $password);
	    }
	    
	    return $varRetour;
	}
	
	function sendSmsNexah($telephone, $message, $senderId="", $login = "", $password = "")
	{
	    $varRetour = array("status" => "", "error" => "");
	    
	    $appliConfig =  new \Application\Core\AppliConfig();
	    $paramSms = $appliConfig->get("sms_nexah");
	    
	    
	    if(is_array($telephone))
	    {
	        $telephone = implode(",", $telephone);
	    }
	    
	    $telephone = str_replace("+", "", $telephone);
	    
	    if(strlen($telephone) != 9 && strlen($telephone) != 12)
	    {
	        $varRetour['error'] =  "Longueur du numero de telephone incorrecte";
	        $varRetour['status'] =  0;
	        
	        return $varRetour;
	    }
	    
	    if(strlen($telephone) == 9)
	    {
	        $telephone = "237".$telephone;
	    }
		if(strlen($telephone) == 12)
	    {
	        $telephone = "".$telephone;
	    }
	    
	    if(empty($senderId))
	    {
	        $senderId = $paramSms["senderid"];
	    }
	    
	    $parameters = [
			"user" => $paramSms["username"],
	        "password" => $paramSms["password"],
	        "senderid" => $senderId,
	        "mobiles" => $telephone,
	        "sms" => $message
	    ];
	    
	    $postFields = http_build_query($parameters);
	    
	    $curl = curl_init();
	    
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => $paramSms["url"]."?".$postFields,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "utf-8",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 30,
	        CURLOPT_SSL_VERIFYPEER => 0,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
	    ));
	    
	    $response = curl_exec($curl);
	    $error = curl_error($curl);
	    curl_close($curl);
	    
	    // On lance l'exécution de la requête URL.
	    if(!$error) // Si elle s'est exécutée correctement
	    {
	        $result = json_decode($response,true); 
			if(isset($result['sms'][0]))
			{
				$result=$result['sms'][0];
				$varRetour['status'] =  $result['status']=="success" ? 1 : 0;
				$varRetour['error'] =  $result['errordescription'];
			}
			else
			{
				$varRetour['status'] =  $result['responsecode'];
				$varRetour['error'] =  $result['success'];
			}
	    }
	    else
	    {
	        $varRetour['error'] =  $error;
	    }
	    
	    return $varRetour;
	}
	
	function sendSmsMTarget($telephone, $message, $senderName="", $login = "", $password = "")
	{
	    $varRetour = array("status" => "", "error" => "");
	    
	    $appliConfig =  new \Application\Core\AppliConfig();
	    $paramSms = $appliConfig->get("sms_mtarget");
	    
	    
	    if(is_array($telephone))
	    {
	        $telephone = implode(",", $telephone);
	    }
	    
	    $telephone = str_replace("+", "", $telephone);
	    
	    if(strlen($telephone) != 9 && strlen($telephone) != 12)
	    {
	        $varRetour['error'] =  "Longueur du numero de telephone incorrecte";
	        $varRetour['status'] =  0;
	        
	        return $varRetour;
	    }
	    
	    if(strlen($telephone) == 9)
	    {
	        $telephone = "00237".$telephone;
	    }
		if(strlen($telephone) == 12)
	    {
	        $telephone = "00".$telephone;
	    }
	    
	    if(empty($senderName))
	    {
	        $senderName = $paramSms["senderName"];
	    }
	    
	    $parameters = [
			"username" => $paramSms["username"],
	        "serviceid" => $paramSms["serviceId"],
	        "password" => $paramSms["password"],
	        "sender" => $senderName,
	        "msisdn" => $telephone,
	        "msg" => $message,
	        "allowunicode" => "true"
	    ];
	    
	    $postFields = http_build_query($parameters);
	    
	    $curl = curl_init();
	    
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => $paramSms["url"]."?".$postFields,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "utf-8",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 30,
	        CURLOPT_SSL_VERIFYPEER => 0,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
	    ));
	    
	    $response = curl_exec($curl);
	    $error = curl_error($curl);
	    curl_close($curl);
	    
	    // On lance l'exécution de la requête URL.
	    if(!$error) // Si elle s'est exécutée correctement
	    {
	        $result = json_decode($response); 
			if($result->results[0]->code == 0)$varRetour['status'] = 1;
	        else
	        {
	            $varRetour['error'] =  $result->results[0]->reason;
	            $varRetour['status'] =  $result->results[0]->code;
	        }
	    }
	    else
	    {
	        $varRetour['error'] =  $error;
	        $varRetour['status'] =  0;
	    }
	    
	    return $varRetour;
	}
	
	function sendUnSmsHttp($telephone, $message, $senderName="", $login = "", $password = "")
	{
	    $varRetour = array("status" => "", "error" => "");
	    
	    $appliConfig =  new \Application\Core\AppliConfig();
	    $paramSms = $appliConfig->get("sms");
	    
	    
	    if(is_array($telephone))
	    {
	        $telephone = implode(",", $telephone);
	    }
	    
	    $telephone = str_replace("+", "", $telephone);
	    
	    if(strlen($telephone) != 9 && strlen($telephone) != 12)
	    {
	        $varRetour['error'] =  "Longueur du numero de telephone incorrecte";
	        $varRetour['status'] =  0;
	        
	        return $varRetour;
	    }
	    
	    if(strlen($telephone) == 9)
	    {
	        $telephone = "237".$telephone;
	    }
	    
	    if(empty($senderName))
	    {
	        $senderName = $paramSms["sender_id"];
	    }
	    
	    
	    // if(strlen($message) <= 160)
	    // {
	    //     $flag = "short_sms";
	    // }
	    // else
	    // {
	    //     $flag = "long_sms";
	    // }
	    
	    
	    
	    $parameters = ["login" => $paramSms["login"],
	        "password" => $paramSms["password"],
	        "sender_id" => $senderName,
	        "destinataire" => $telephone,
	        "message" => $message,
	        // 'encodage' => 'Utf-8'
	        // "flag" => $flag,
	    ];
	    
	    
	    
	    
	    
	    $postFields = http_build_query($parameters);
	    
	    $curl = curl_init();
	    
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => $paramSms["url"]."?".$postFields,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "utf-8",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 30,
	        CURLOPT_SSL_VERIFYPEER => 0,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
	    ));
	    
	    
	    $response = curl_exec($curl);
	    $error = curl_error($curl);
	    curl_close($curl);
	    
	    // On lance l'exécution de la requête URL.
	    if(!$error) // Si elle s'est exécutée correctement
	    {
	        if(is_numeric($response))
	        {
	            $varRetour['status'] =  1;
	        }
	        else
	        {
	            $varRetour['error'] =  $response;
	            $varRetour['status'] =  0;
	            
	        }
	    }
	    else
	    {
	        $varRetour['error'] =  $error;
	        $varRetour['status'] =  0;
	    }
	    
	    return $varRetour;
	}
	
	
	function sendUnSmsHttp_lmt($telephone, $message, $senderName="", $login = "", $password = "")
	{
	    $varRetour = array("status" => "", "error" => "");
	    
	    $appliConfig =  new \Application\Core\AppliConfig();
	    $paramSms = $appliConfig->get("sms");
	    
	    
	    if(is_array($telephone))
	    {
	        $telephone = implode(",", $telephone);
	    }
	    
	    $telephone = str_replace("+", "", $telephone);
	    
	    if(strlen($telephone) != 9 && strlen($telephone) != 12)
	    {
	        $varRetour['error'] =  "Longueur du numero de telephone incorrecte";
	        $varRetour['status'] =  0;
	        
	        return $varRetour;
	    }
	    
	    if(strlen($telephone) == 9)
	    {
	        $telephone = "237".$telephone;
	    }
	    
	    if(empty($senderName))
	    {
	        $senderName = $paramSms["senderName"];
	    }
	    
	    $password = $paramSms["password"];
	    
	    
	    if(strlen($message) <= 160)
	    {
	        $flag = "short_sms";
	    }
	    else
	    {
	        $flag = "long_sms";
	    }
	    
	    
	    
	    $parameters = array("api_key" => $paramSms["api_key"],
	        "password" => $password,
	        "sender" => $senderName,
	        "phone" => $telephone,
	        "message" => $message,
	        "flag" => $flag,
	    );
	    
	    
	    
	    
	    
	    $postFields = http_build_query($parameters);
	    
	    $curl = curl_init();
	    
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => $paramSms["url"],
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "utf-8",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 30,
	        CURLOPT_SSL_VERIFYPEER => 0,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => "POST",
	        CURLOPT_POSTFIELDS => $postFields,
	        CURLOPT_HTTPHEADER => array(
	            "Content-Type: application/x-www-form-urlencoded;charset=utf-8"
	        )
	    ));
	    
	    
	    $response = curl_exec($curl);
	    $error = curl_error($curl);
	    curl_close($curl);
	    
	    // On lance l'exécution de la requête URL.
	    if(!$error) // Si elle s'est exécutée correctement
	    {
	        $json = json_decode($response, true);
	        
	        if($json['status'] == 'success')
	        {
	            $varRetour['status'] =  1;
	        }
	        else
	        {
	            $varRetour['error'] =  $json['message'];
	            $varRetour['status'] =  0;
	        }
	    }
	    else
	    {
	        $varRetour['error'] =  $error;
	        $varRetour['status'] =  0;
	    }
	    
	    return $varRetour;
	}
	
// 	// Fonction qui envoi les SMS
// 	function sendSmsHttp_old($telephone, $message, $senderName="", $login = "", $password = "")
// 	{
// 	    $appliConfig =  new \Application\Core\AppliConfig();
// 	    $paramSms = $appliConfig->get("sms");
	    
// 	    if(is_string($telephone))
// 	    {
// 	        $telephone = array($telephone);
// 	    }
	    
// 	    if(empty($senderName))
// 	    {
// 	        $senderName = $paramSms["senderName"];
// 	    }
	    
// 	    $login = $paramSms["login"];
// 	    $password = $paramSms["password"];
	    
	    
	    
// 	    $params = array();
// 	    $params['userLogoin']     = $login;
// 	    $params['userpassword']   = $password;
// 	    $params['numeroCourt']    = $senderName;
// 	    $params['destinataires']  = $telephone;
// 	    $params['messages']       = $message;
	    
	    
	    
// 	    try {
	        
// 	        $client = new \SoapClient($paramSms["url"]);
// 	        // var_dump($client); exit;
	        
// 	        $result = $client->envoyerSMS($params);
// 	        $varRetour = $result->return;
// 	        // echo "success";
// 	        //Affihcage du statut du résultat
// 	        // var_dump($result, $varRetour); exit;
// 	    } catch (\SoapFault $fault) {
// 	        // trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
// // 	        echo "error";
// // 	        var_dump($fault); exit;
// // 	        exit;
	        
// 	        $varRetour = "Error : ".$fault->faultcode;
// 	    }
	    
// 	    return $varRetour;
	    
	    
	    
	    
	    
	    
// 	    // 		if(is_array($telephone))
// 	        // 		{
// 	        // 			$recipients = "";
// 	        // 			foreach ($telephone as $unTel)
// 	            // 			{
// 	            // 				$recipients .= "<gsm>".$unTel."</gsm>";
// 	            // 			}
// 	        // 		}
// 	    // 		else
// 	        // 		{
// 	        // 			$recipients = "<gsm>".$telephone."</gsm>";
// 	        // 		}
	    
	    
// 	    // 		$postUrl = 'http://api.startsms.org/api/sendsms/xml';
	    
// 	    // 				$xmlString = "<SMS>
// 	    // 	    				<authentification>
// 	    // 	    					<username>".$uid."</username>
// 	    // 	    					<password>".$pwd."</password>
// 	    // 	    				</authentification>
// 	    // 	    				<message>
// 	    // 	    					<sender>".$sender."</sender>
// 	    // 	    					<text>".$message."</text>
// 	    // 	    				</message>
// 	    // 	    				<recipients>
// 	    // 	    					".$recipients."
// 	    // 	    				</recipients>
// 	    //     				</SMS>";
	    
// 	    // 				$fields = "XML=".urlencode($xmlString);
	    
// 	    // 				$ch = curl_init();
// 	    // 				curl_setopt($ch, CURLOPT_URL, $postUrl);
// 	    // 				curl_setopt($ch, CURLOPT_POST, 1);
// 	    // 				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
// 	    // 				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    
	    
// 	    // 				$reponse = curl_exec($ch);
// 	    // 				curl_close($ch);
	    
// 	    // 				return $reponse;
// 	}
	
	function sendSmsHttplmt_new($telephone, $message, $senderName="", $login = "", $password = "")
	{
	    $varRetour = array("status" => "", "error" => "");
	    
	    $appliConfig =  new \Application\Core\AppliConfig();
	    $paramSms = $appliConfig->get("sms");
	    
	    
	    if(is_array($telephone))
	    {
	        $telephone = implode(",", $telephone);
	    }
	    
	    if(empty($senderName))
	    {
	        $senderName = $paramSms["senderName"];
	    }
	    
	    $password = $paramSms["password"];
	    
	    
	    if(strlen($message) <= 160)
	    {
	        $flag = "short_sms";
	    }
	    else
	    {
	        $flag = "long_sms";
	    }
	    
	    
	    
	    $parameters = array("api_key" => $paramSms["api_key"],
	        "password" => $password,
	        "sender" => $senderName,
	        "phone" => $telephone,
	        "message" => $message,
	        "flag" => $flag,
	    );
	    
	    
	    
	    
	    
	    $postFields = http_build_query($parameters);
	    
	    $curl = curl_init();
	    
	    curl_setopt_array($curl, array(
	        CURLOPT_URL => $paramSms["url"],
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "utf-8",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 30,
	        CURLOPT_SSL_VERIFYPEER => 0,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => "POST",
	        CURLOPT_POSTFIELDS => $postFields,
	        CURLOPT_HTTPHEADER => array(
	            "Content-Type: application/x-www-form-urlencoded;charset=utf-8"
	        )
	    ));
	    
	    
	    $response = curl_exec($curl);
	    $error = curl_error($curl);
	    curl_close($curl);
	    
	    // On lance l'exécution de la requête URL.
	    if(!$error) // Si elle s'est exécutée correctement
	    {
	        $json = json_decode($response, true);
	        
	        if($json['status'] == 'success')
	        {
	            $varRetour['status'] =  1;
	        }
	        else
	        {
	            $varRetour['error'] =  $json['message'];
	            $varRetour['status'] =  0;
	        }
	    }
	    else
	    {
	        $varRetour['error'] =  $json['message'];
	        $varRetour['status'] =  0;
	    }
	    
	    return $varRetour;
	}
	
	public function paiementCarteBancaireSimple($referenceNumberCommande, $montantCommande, $nomUser, $prenomUser, $emailUser, $telephone, $descProduit,
												$nomUrlProduit, $baseUrl, $codeIsoLangue, $descriptionProduit, $cheminImage=null)
	{
		$varRetour = array('error' => "", 'tableError' => "", 'token' => "");
		$appliConfig =  new \Application\Core\AppliConfig();
		
		
		if($souscription->getTransactionFournisseur())
		{
			$varRetour['transactionid'] = $souscription->getTransactionFournisseur();
			$varRetour['modePaiement'] = 'paiement-carte-bancaire';
		}
		else
		{
			$utilisateur = $souscription->getInternaute()->getUtilisateur();
	
			/// Pour les paiements
			$infosPaiement = $appliConfig->get("infos_paiement");
			$infosCarteBancaire = $infosPaiement["infos_carte_bancaire"];
			 
			$paramsCarteBancaire = $this->construireParamsCarteBancaire();
			 
			$requete_params = array("referenceNumber" => $souscription->getCode(),
									"date" => date("d/m/Y H:i:s"),
									"total" => $souscription->getPrimeTtc(),
									// "total" => "10",
									"noOfItems" => "1",
									"description" => $descriptionProduit,
									"customerLastname" => $utilisateur->getNom(),
									"customerFirstName" => $utilisateur->getPrenom(),
									"customerEmail" => $utilisateur->getEmail(),
									"customerPhoneNumber" => "+".$utilisateur->getTelephoneDialCode().$utilisateur->getTelephone(),
									// "approveurl" => $baseUrl."/".$codeIsoLangue."/e-insurance/client/souscription/details/".$souscription->getId(), // URL en cas de success
									// "cancelurl" => $baseUrl."/".$codeIsoLangue."/e-insurance/".$nomUrlProduit."/souscrire", // URL en cas d'annulation par l'internaute
									// "declineurl" => $baseUrl."/".$codeIsoLangue."/e-insurance/".$nomUrlProduit."/souscrire", // URL en cas d'echec du paiement
									);
			 
			$requete_params = array_merge($paramsCarteBancaire, $requete_params);
			
			
			// var_dump($requete_params, $infosCarteBancaire['url_post']); exit;
			
			if(!empty($cheminImage))
			{
				// $requete_params["HDRIMG"] = urlencode($cheminImage);
			}
			 
			// Initialise notre session cURL. On lui donne la requête à exécuter
			// $ch = curl_init($infosCarteBancaire['url_post']);
			$ch = curl_init();
			 
			 
			// On se verifie pas le certificat ssl si on est en mode demo
			if($appliConfig->get("mode_demo"))
			{
				// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné.
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			}
	
			//set option of URL to post to
			curl_setopt($ch, CURLOPT_URL, $infosCarteBancaire['url_post']); // Demander ce parametre a UBA
			//set option of request method -----HTTP POST Request
			curl_setopt($ch, CURLOPT_POST, true);
			//The HTTP authentication methods to use
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			//This line sets the parameters to post to the URL
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requete_params);
			//This line makes sure that the response is gotten back to the
			// $response object(see below) and not echoed
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			 
			// On lance l'exécution de la requête URL.
			if($response = curl_exec($ch)) // Si elle s'est exécutée correctement
			{
				if(empty($response) || $response == "null")
				{
					$varRetour['error'] = "Transaction non initialisee";
				}
				else
				{
					// object
					$returnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					//Close the stream
					// curl_close($ch);
		
					//Check if there are no errors ie httpresponse == 200 -OK
					if ($returnCode == 200) {
						//If there are no errors, the transaction ID is returned
						$transactionid = $response;
						
						$varRetour['transactionid'] = $transactionid;
						$varRetour['modePaiement'] = 'paiement-carte-bancaire';
						
					}
					else
					{
						$varRetour['error'] = "Erreur de communication avec le serveur : <b>".$returnCode."</b>";
					}
				}
			}
			else // S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
			{
				$varRetour['error'] =  curl_error($ch);
			}
			 
			// On ferme notre session cURL.
			curl_close($ch);	
		}
				 
		return $varRetour;
	}
	
	function roundUpToAny($nombre, $arrondiSupperieur=5)
	{
	    return (ceil($nombre)%$arrondiSupperieur === 0) ? ceil($nombre) : round(($nombre+$arrondiSupperieur/2)/$arrondiSupperieur)*$arrondiSupperieur;
	}
	
	function utf8ize($d) {
	    if (is_array($d)) {
	        foreach ($d as $k => $v) {
	            $d[$k] = $this->utf8ize($v);
	        }
	    } else if (is_string ($d)) {
	        return utf8_encode($d);
	    }
	    return $d;
	}

	public function construireChaineConnexionOracle($host, $port, $simpleDbname)
	{
		return 'oci:dbname=(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST='.$host.')(PORT='.$port.'))(CONNECT_DATA=(SERVICE_NAME='.$simpleDbname.')))';
	}
}
