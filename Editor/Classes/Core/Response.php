<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Response {
		
	static function sendObject($obj) {
        if (!ConfigurationService::isUnicode()) {
    		$obj = Strings::toUnicode($obj);            
        }
        header('Content-Type: text/plain; charset=utf-8');
        echo Strings::toJSON($obj);
	}
    
    static function setExpiresInHours($hours=0) {
        $offset = 60 * 60 * $hours;
		
		$modified = SystemInfo::getDate();
	    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $modified).' GMT', true, 200);
       	header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
       	header("Cache-Control: max-age=$offset, must-revalidate");
        header("Pragma: hack");
    }
		
	static function redirect($url) {
		session_write_close();
		header('Location: '.$url);
		exit();
	}
	
	static function redirectMoved($url) {
		session_write_close();
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$url);
		exit();
	}
	
	static function contentDisposition($filename) {
		header("Content-Disposition: attachment; filename=\"$filename\"");
	}
	
	static function internalServerError($text=null) {
		Response::sendStatus(500,'Internal Server Error',$text);
	}
	
	static function badGateway($text=null) {
		Response::sendStatus(502,'Bad Gateway',$text);
	}
	
	static function badRequest($text=null) {
		Response::sendStatus(400,'Bad Request',$text);
	}
	
	static function notFound($text=null) {
		Response::sendStatus(404,'Not Found',$text);
	}
	
	static function forbidden($text=null) {
		Response::sendStatus(403,'Forbidden',$text);
	}
	
	static function sendStatus($number,$key,$text=null) {
		header('HTTP/1.1 '.$number.' '.$key);
		if ($text) {
			echo '<html><head><title>'.$text.'</title></head><body><h1>'.$text.'</h1></body><p>'.$number.' '.$key.'</p></html>';
		}
	}
}
?>