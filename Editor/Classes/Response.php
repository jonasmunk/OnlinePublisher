<?
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
class Response {
	
	function sendObject($obj) {
		header('Content-Type: text/plain; charset=utf-8');
		echo StringUtils::toJSON($obj);
	}
	
	function sendUnicodeObject($obj) {
		foreach ($obj as $key => $value) {
			if (is_string($value)) {
				if (is_array($obj)) {
					$obj[$key] = StringUtils::toUnicode($value);
				} else {
					$obj->$key = StringUtils::toUnicode($value);
				}
			}
		}
		Response::sendObject($obj);
	}
		
	function redirect($url) {
		session_write_close();
		header('Location: '.$url);
		exit();
	}
	
	function redirectMoved($url) {
		session_write_close();
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$url);
		exit();
	}
	
	function contentDisposition($filename) {
		header("Content-Disposition: attachment; filename=\"$filename\"");
	}
	
	function internalServerError() {
		header('HTTP/1.1 500 Internal Server Error');
	}
	
	function badRequest() {
		header('HTTP/1.1 400 Bad Request');
	}
	
	function notFound($text=null) {
		Response::sendStatus(404,'Not Found',$text);
	}
	
	function forbidden($text=null) {
		Response::sendStatus(403,'Forbidden',$text);
	}
	
	function sendStatus($number,$key,$text=null) {
		header('HTTP/1.1 '.$number.' '.$key);
		if ($text) {
			echo '<html><head><title>'.$text.'</title></head><body><h1>'.$text.'</h1></body></html>';
		}
	}
}
?>