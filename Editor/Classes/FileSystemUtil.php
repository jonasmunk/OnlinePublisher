<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');

class FileSystemUtil {
	
	static $types = array(
		'html' => 'text/html',
		'htm' => 'text/html',
		'txt' => 'text/plain',
		'pdf' => "application/pdf",
		'mov' => "video/quicktime",
		'xml' => "text/xml",
		'zip' => "application/zip",
		'jpg' => "image/jpeg",
		'jpeg' => "image/jpeg",
		'png' => "image/png",
		'gif' => "image/gif",
		'doc' => "application/msword",
		'ppt' => "application/vnd.ms-powerpoint",
		'xsl' => "text/xml"
	);
	function mimeTypeToExtension($mime) {
		foreach (self::$types as $ext => $mimeType) {
			if ($mime==$mimeType) {
				return $ext;
			}
		}
		return null;
	}
	
	function extensionToMimeType($extension) {
		foreach (self::$types as $ext => $mimeType) {
			if ($extension==$ext) {
				return $mimeType;
			}
		}
		return null;
	}
	
	function fileNameToMimeType($filename) {
		$ext = FileSystemService::getFileExtension($filename);
		switch ($ext) {
			case 'html' : return "text/html";
			case 'htm' : return "text/html";
			case 'pdf' : return "application/pdf";
			case 'txt' : return "text/plain";
			case 'mov' : return "video/quicktime";
			case 'xml' : return "text/xml";
			case 'zip' : return "application/zip";
			case 'jpg' : return "image/jpeg";
			case 'jpeg' : return "image/jpeg";
			case 'png' : return "image/png";
			case 'gif' : return "image/gif";
			case 'doc' : return "application/msword";
			case 'ppt' : return "application/vnd.ms-powerpoint";
			case 'xsl' : return "text/xml";
			default: return "";
		} 
	}
}