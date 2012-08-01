<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class GuiUtils {
	
	static $languages = array(
			'AB' => array('Abkhazian'),
			'AA' => array('Afar'),
			'AF' => array('Afrikaans'),
			'SQ' => array('Albanian'),
			'AM' => array('Amharic'),
			'AR' => array('Arabic'),
			'HY' => array('Armenian'),
			'AS' => array('Assamese'),
			'AY' => array('Aymara'),
			'AZ' => array('Azerbaijani'),
			'BA' => array('Bashkir'),
			'EU' => array('Basque'),
			'BN' => array('Bengali/Bangla'),
			'DZ' => array('Bhutani'),
			'BH' => array('Bihari'),
			'BI' => array('Bislama'),
			'BR' => array('Breton'),
			'BG' => array('Bulgarian'),
			'MY' => array('Burmese'),
			'BE' => array('Byelorussian'),
			'KM' => array('Cambodian'),
			'CA' => array('Catalan'),
			'ZH' => array('Chinese'),
			'CO' => array('Corsican'),
			'HR' => array('Croatian'),
			'CS' => array('Czech'),
			'DA' => array('Danish','da'=>'Dansk'),
			'NL' => array('Dutch'),
			'EN' => array('English','da'=>'Engelsk'),
			'EO' => array('Esperanto'),
			'ET' => array('Estonian'),
			'FO' => array('Faeroese'),
			'FJ' => array('Fiji'),
			'FI' => array('Finnish'),
			'FR' => array('French'),
			'FY' => array('Frisian'),
			'GD' => array('Gaelic/Scots Gaelic'),
			'GL' => array('Galician'),
			'KA' => array('Georgian'),
			'DE' => array('German','da'=>'Tysk'),
			'EL' => array('Greek'),
			'KL' => array('Greenlandic'),
			'GN' => array('Guarani'),
			'GU' => array('Gujarati'),
			'HA' => array('Hausa'),
			'IW' => array('Hebrew'),
			'HI' => array('Hindi'),
			'HU' => array('Hungarian'),
			'IS' => array('Icelandic'),
			'IN' => array('Indonesian'),
			'IA' => array('Interlingua'),
			'IE' => array('Interlingue'),
			'IK' => array('Inupiak'),
			'GA' => array('Irish'),
			'IT' => array('Italian'),
			'JA' => array('Japanese'),
			'JW' => array('Javanese'),
			'KN' => array('Kannada'),
			'KS' => array('Kashmiri'),
			'KK' => array('Kazakh'),
			'RW' => array('Kinyarwanda'),
			'KY' => array('Kirghiz'),
			'RN' => array('Kirundi'),
			'KO' => array('Korean'),
			'KU' => array('Kurdish'),
			'LO' => array('Laothian'),
			'LA' => array('Latin'),
			'LV' => array('Latvian/Lettish'),
			'LN' => array('Lingala'),
			'LT' => array('Lithuanian'),
			'MK' => array('Macedonian'),
			'MG' => array('Malagasy'),
			'MS' => array('Malay'),
			'ML' => array('Malayalam'),
			'MT' => array('Maltese'),
			'MI' => array('Maori'),
			'MR' => array('Marathi'),
			'MO' => array('Moldavian'),
			'MN' => array('Mongolian'),
			'NA' => array('Nauru'),
			'NE' => array('Nepali'),
			'NO' => array('Norwegian'),
			'OC' => array('Occitan'),
			'OR' => array('Oriya'),
			'OM' => array('Oromo/Afan'),
			'PS' => array('Pashto/Pushto'),
			'FA' => array('Persian'),
			'PL' => array('Polish'),
			'PT' => array('Portuguese'),
			'PA' => array('Punjabi'),
			'QU' => array('Quechua'),
			'RM' => array('Rhaeto-Romance'),
			'RO' => array('Romanian'),
			'RU' => array('Russian'),
			'SM' => array('Samoan'),
			'SG' => array('Sangro'),
			'SA' => array('Sanskrit'),
			'SR' => array('Serbian'),
			'SH' => array('Serbo-Croatian'),
			'ST' => array('Sesotho'),
			'TN' => array('Setswana'),
			'SN' => array('Shona'),
			'SD' => array('Sindhi'),
			'SI' => array('Singhalese'),
			'SS' => array('Siswati'),
			'SK' => array('Slovak'),
			'SL' => array('Slovenian'),
			'SO' => array('Somali'),
			'ES' => array('Spanish'),
			'SU' => array('Sudanese'),
			'SW' => array('Swahili'),
			'SV' => array('Swedish'),
			'TL' => array('Tagalog'),
			'TG' => array('Tajik'),
			'TA' => array('Tamil'),
			'TT' => array('Tatar'),
			'TE' => array('Tegulu'),
			'TH' => array('Thai'),
			'BO' => array('Tibetan'),
			'TI' => array('Tigrinya'),
			'TO' => array('Tonga'),
			'TS' => array('Tsonga'),
			'TR' => array('Turkish'),
			'TK' => array('Turkmen'),
			'TW' => array('Twi'),
			'UK' => array('Ukrainian'),
			'UR' => array('Urdu'),
			'UZ' => array('Uzbek'),
			'VI' => array('Vietnamese'),
			'VO' => array('Volapuk'),
			'CY' => array('Welsh'),
			'WO' => array('Wolof'),
			'XH' => array('Xhosa'),
			'JI' => array('Yiddish'),
			'YO' => array('Yoruba'),
			'ZU' => array('Zulu')
		);
	
	/**
	 * Builds select-options for a particular type of object
	 * @param string $type The type of object
	 * @param int $maxSize The maximum number of chars in the title
	 * @return string Option-tags for use with XmlWebGui
	 */
	function buildObjectOptions($type,$maxSize=100) {
		$output='';
		$sql="select id,title from object where type=".Database::text($type)." order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$output.='<option title="'.StringUtils::escapeXML(StringUtils::shortenString($row['title'],$maxSize)).'" value="'.$row['id'].'"/>';
		}
		Database::free($result);
		return $output;
	}

	/**
	 * Builds select-options for a particular type of object
	 * @param string $type The type of object
	 */
	function buildObjectItems($type) {
		$output='';
		if (is_array($type)) {
			foreach ($type as $object) {
				$output.='<item title="'.StringUtils::escapeXML($object->getTitle()).'" value="'.$object->getId().'"/>';
			}
		} else {
			$sql="select id,title from object where type=".Database::text($type)." order by title";
			$result = Database::select($sql);
			while ($row = Database::next($result)) {
				$title = $row['title'];
				$title = str_replace("'","",$title);
				$output.='<item title="'.StringUtils::escapeJavaScriptXML($title).'" value="'.$row['id'].'"/>';
			}
			Database::free($result);
		}
		return $output;
	}
	
	function getTranslated($value) {
		if (is_array($value)) {
			$lang = InternalSession::getLanguage();
			if (isset($value[$lang])) {
				return $value[$lang];
			}
			return $value[0];
		}
		return $value;		
	}
	
	function buildTranslatedItems($items) {
		$output = '';
		
		foreach ($items as $key => $texts) {
			$lang = InternalSession::getLanguage();
			$title = isset($texts[$lang]) ? $texts[$lang] : $texts['en'];
			$output.='<item text="'.StringUtils::escapeXML($title).'" value="'.$key.'"/>';
		}
		
		return $output;
	}
	
	function buildEntity($object) {
		return '<entity icon="'.$object->getIcon().'" title="'.StringUtils::escapeXML($object->getTitle()).'" value="'.$object->getId().'"/>';
	}
	
	function buildImageEntity($image) {
		return '<entity image="../../../services/images/?id='.$image->getId().'&amp;width=32&amp;height=32&amp;format=jpg&amp;timestamp='.$image->getUpdated().'" title="'.StringUtils::escapeXML($image->getTitle()).'" value="'.$image->getId().'"/>';
	}
	
	/**
	 * Builds select-options for a particular type of object
	 * @param string $type The type of object
	 * @param int $maxSize The maximum number of chars in the title
	 * @return string Option-tags for use with XmlWebGui
	 */
	function buildPageOptions($template=null) {
		$output='';
		$sql = "select page.id,page.title from page,template where page.template_id=template.id".($template!==null ? " and template.unique='authentication'" : "")." order by page.title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$output.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.$row['id'].'"/>';
		}
		Database::free($result);
		return $output;
	}
	
	/**
	 * Builds select-options for a particular type of object
	 * @param string $type The type of object
	 * @param int $maxSize The maximum number of chars in the title
	 * @return string Option-tags for use with XmlWebGui
	 */
	function buildPageItems($template=null) {
		$output='';
		$sql = "select page.id,page.title from page,template where page.template_id=template.id".($template!==null ? " and template.unique='authentication'" : "")." order by page.title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$output.='<item title="'.StringUtils::escapeJavaScriptXML($row['title']).'" value="'.$row['id'].'"/>';
		}
		Database::free($result);
		return $output;
	}
	
	function getLanguageIcon($lang) {
		$languageIcons = array(
			'EN' => 'flag/gb',
			'DA' => 'flag/dk',
			'DE' => 'flag/de'
		);
		return @$languageIcons[$lang];
	}
	
	function getLanguageName($lang) {
		return @GuiUtils::getTranslated(GuiUtils::$languages[$lang]);
	}
	
	function getLanguages() {
		$out = array();
		foreach (GuiUtils::$languages as $key => $value) {
			$out[$key] = GuiUtils::getTranslated($value);
		}
		return $out;
	}
	
	/**
	 * Formats a number of bytes to abreviated human readable string
	 * @param int $input The bytes to format
	 * @return string Human readable bytes
	 */
	function bytesToString($input) {
		if ($input<1024) {
			return $input.' b';
		}
		else if ($input<(1024*1024)) {
			return round(($input/1024),1).' Kb';
		}
		else if ($input<(1024*1024*1024)) {
			return round(($input/1024/1024),1).' Mb';
		}
		else {
			return $input;
		}
	}
	
	/**
	 * Formats a number of bytes to full human readable string
	 * @param int $input The bytes to format
	 * @return string Human readable bytes
	 */
	function bytesToLongString($input) {
		if ($input<1024) {
			return $input.' bytes';
		}
		else if ($input<(1024*1024)) {
			return round(($input/1024),1).' kilobytes';
		}
		else if ($input<(1024*1024*1024)) {
			return round(($input/1024/1024),1).' Megabytes';
		}
		else {
			return $input;
		}
	}
}
?>