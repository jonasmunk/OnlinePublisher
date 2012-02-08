<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class GuiUtils {
	
	static $languages = array(
			'AB' => 'Abkhazian',
			'AA' => 'Afar',
			'AF' => 'Afrikaans',
			'SQ' => 'Albanian',
			'AM' => 'Amharic',
			'AR' => 'Arabic',
			'HY' => 'Armenian',
			'AS' => 'Assamese',
			'AY' => 'Aymara',
			'AZ' => 'Azerbaijani',
			'BA' => 'Bashkir',
			'EU' => 'Basque',
			'BN' => 'Bengali/Bangla',
			'DZ' => 'Bhutani',
			'BH' => 'Bihari',
			'BI' => 'Bislama',
			'BR' => 'Breton',
			'BG' => 'Bulgarian',
			'MY' => 'Burmese',
			'BE' => 'Byelorussian',
			'KM' => 'Cambodian',
			'CA' => 'Catalan',
			'ZH' => 'Chinese',
			'CO' => 'Corsican',
			'HR' => 'Croatian',
			'CS' => 'Czech',
			'DA' => 'Dansk',
			'NL' => 'Dutch',
			'EN' => 'Engelsk',
			'EO' => 'Esperanto',
			'ET' => 'Estonian',
			'FO' => 'Faeroese',
			'FJ' => 'Fiji',
			'FI' => 'Finnish',
			'FR' => 'French',
			'FY' => 'Frisian',
			'GD' => 'Gaelic/Scots Gaelic',
			'GL' => 'Galician',
			'KA' => 'Georgian',
			'DE' => 'Tysk',
			'EL' => 'Greek',
			'KL' => 'Greenlandic',
			'GN' => 'Guarani',
			'GU' => 'Gujarati',
			'HA' => 'Hausa',
			'IW' => 'Hebrew',
			'HI' => 'Hindi',
			'HU' => 'Hungarian',
			'IS' => 'Icelandic',
			'IN' => 'Indonesian',
			'IA' => 'Interlingua',
			'IE' => 'Interlingue',
			'IK' => 'Inupiak',
			'GA' => 'Irish',
			'IT' => 'Italian',
			'JA' => 'Japanese',
			'JW' => 'Javanese',
			'KN' => 'Kannada',
			'KS' => 'Kashmiri',
			'KK' => 'Kazakh',
			'RW' => 'Kinyarwanda',
			'KY' => 'Kirghiz',
			'RN' => 'Kirundi',
			'KO' => 'Korean',
			'KU' => 'Kurdish',
			'LO' => 'Laothian',
			'LA' => 'Latin',
			'LV' => 'Latvian/Lettish',
			'LN' => 'Lingala',
			'LT' => 'Lithuanian',
			'MK' => 'Macedonian',
			'MG' => 'Malagasy',
			'MS' => 'Malay',
			'ML' => 'Malayalam',
			'MT' => 'Maltese',
			'MI' => 'Maori',
			'MR' => 'Marathi',
			'MO' => 'Moldavian',
			'MN' => 'Mongolian',
			'NA' => 'Nauru',
			'NE' => 'Nepali',
			'NO' => 'Norwegian',
			'OC' => 'Occitan',
			'OR' => 'Oriya',
			'OM' => 'Oromo/Afan',
			'PS' => 'Pashto/Pushto',
			'FA' => 'Persian',
			'PL' => 'Polish',
			'PT' => 'Portuguese',
			'PA' => 'Punjabi',
			'QU' => 'Quechua',
			'RM' => 'Rhaeto-Romance',
			'RO' => 'Romanian',
			'RU' => 'Russian',
			'SM' => 'Samoan',
			'SG' => 'Sangro',
			'SA' => 'Sanskrit',
			'SR' => 'Serbian',
			'SH' => 'Serbo-Croatian',
			'ST' => 'Sesotho',
			'TN' => 'Setswana',
			'SN' => 'Shona',
			'SD' => 'Sindhi',
			'SI' => 'Singhalese',
			'SS' => 'Siswati',
			'SK' => 'Slovak',
			'SL' => 'Slovenian',
			'SO' => 'Somali',
			'ES' => 'Spanish',
			'SU' => 'Sudanese',
			'SW' => 'Swahili',
			'SV' => 'Swedish',
			'TL' => 'Tagalog',
			'TG' => 'Tajik',
			'TA' => 'Tamil',
			'TT' => 'Tatar',
			'TE' => 'Tegulu',
			'TH' => 'Thai',
			'BO' => 'Tibetan',
			'TI' => 'Tigrinya',
			'TO' => 'Tonga',
			'TS' => 'Tsonga',
			'TR' => 'Turkish',
			'TK' => 'Turkmen',
			'TW' => 'Twi',
			'UK' => 'Ukrainian',
			'UR' => 'Urdu',
			'UZ' => 'Uzbek',
			'VI' => 'Vietnamese',
			'VO' => 'Volapuk',
			'CY' => 'Welsh',
			'WO' => 'Wolof',
			'XH' => 'Xhosa',
			'JI' => 'Yiddish',
			'YO' => 'Yoruba',
			'ZU' => 'Zulu'
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
	
	/**
	 * Finds the appropriate XmlWebGui icon of a link
	 * @param string $type The type of link (url, page, file, etc.)
	 * @param string $template A unique template name if $type is 'page'
	 * @param string $filename A filename if $type is 'file'
	 * @return string The appropriate icon, or 'Basic/Close' if not found
	 */
	function getLinkIcon($type,$template,$filename) {
		global $templates;
		$icon="Basic/Close";
		switch($type) {
			case "":
				$icon="Part/Text";
				break;
			case "url":
				$icon="Basic/Internet";
				break;
			case "page":
				if ($template!='') {
					$icon=$templates[$template]['icon'];
				}
				break;
			case "pageref":
				if ($template!='') {
					$icon=$templates[$template]['icon'];
				}
				break;
			case "file":
				if ($filename!='') {
					$icon=GuiUtils::getFileIcon($filename);
				}
				break;
			case "email":
				$icon="Element/EmailAddress";
				break;
		}
		return $icon;
	}

	/**
	 * Get the XmlWebGui icon of a file
	 * @param string $filename The filename of the file
	 * @return string The icon of the file, Generic icon if not known
	 */
	function getFileIcon($filename) {
		$ext = FileSystemService::getFileExtension($filename);
		switch ($ext) {
			case 'sql' : return "File/sql";
			case 'html' : return "File/html";
			case 'htm' : return "File/html";
			case 'pdf' : return "File/pdf";
			case 'txt' : return "File/txt";
			case 'rtf' : return "File/rtf";
			case 'mov' : return "File/mov";
			case 'xml' : return "File/xml";
			case 'zip' : return "File/zip";
			case 'jpg' : return "File/jpeg";
			case 'jpeg' : return "File/jpeg";
			case 'png' : return "File/png";
			case 'gif' : return "File/gif";
			case 'doc' : return "File/doc";
			case 'ppt' : return "File/ppt";
			case 'xls' : return "File/xls";
			case 'xsl' : return "File/xsl";
			default: return "File/Generic";
		} 
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
		return @GuiUtils::$languages[$lang];
	}
	
	function getLanguages() {
		return GuiUtils::$languages;
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