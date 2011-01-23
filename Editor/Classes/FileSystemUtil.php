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

	/**
	 * Recursively finds an array of files inside a given directory
	 * @param string $dir The path to the directory
	 * @return array Array of files inside the dir
	 * @todo Filenames or paths?
	 */
	function listFilesRecurse($dir) {
		$out=array();
		if (is_dir($dir)) {
			FileSystemUtil::listFilesRecurseSpider($dir,$out);
		}
		return $out;
	}

	function listFilesRecurseSpider($dir,&$data) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if ($file{0}=='.') {
					// ignore
				}
				else if (is_file($dir.$file)) {
					array_push($data,$dir.$file);
				} else if (is_dir($dir.$file)) {
					FileSystemUtil::listFilesRecurseSpider($dir.$file.'/',$data);
				}
			}
			closedir($dh);
		}
	}
	
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
	
	

	
	/**
	 * Makes a filename into a nice looking title (stripping extensions etc.)
	 * @param string $filename The filename to convert
	 * @return string A nice looking title
	 */
	function filenameToTitle($filename) {
		$pos = strpos($filename,'.');
		if ($pos === false) {
			$title = $filename;
		}
		else {
			$title = substr($filename,0,$pos);
		}
		return ucfirst($title);
	}
	
	/**
	 * Finds a free path for a file, using a provided file as input
	 * @param string $path The path to be used
	 * @return string A free path
	 */
	function findFreeFilePath($path) {
		$path_parts = pathinfo($path);
		$dir = $path_parts['dirname'];
		$file = $path_parts['basename'];
		if (array_key_exists('extension',$path_parts)) {
			$ext = $path_parts['extension'];
		} else {
			$ext = '';
		}
	
		$output = $path;
		$head = substr($file,0,strlen($file)-strlen($ext)-1);
		$count = 1;
		while (file_exists($path)) {
			$path = $dir.'/'.$head.$count.'.'.$ext;
			$count++;
		}
		return $path;
	}
	
	/**
	 * Finds a paths base filename
	 * @param string $path The path to analyze
	 * @return string The base filename
	 */
	function findFilePathName($path) {
		$path_parts = pathinfo($path);
		return $path_parts['basename'];
	}
}