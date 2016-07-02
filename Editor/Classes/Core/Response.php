<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Response {

  static $OK = 200;
  static $FORBIDDEN = 403;
  static $NOT_FOUND = 404;
  static $UNAUTHORIZED = 401;
  static $UNAVAILABLE = 503;

	static function sendObject($obj) {
    if (!ConfigurationService::isUnicode()) {
      $obj = Strings::toUnicode($obj);
    }
    header('Content-Type: text/plain; charset=utf-8');
    $str = Strings::toJSON($obj);
    /* TODO May be overkill since JSON is often very small
    $supportsGzip = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;
    if ($supportsGzip) {
        $str = gzencode($str,9);
        header('Content-Encoding: gzip');
    }*/
    echo $str;
	}

  static function setExpiresInDays($days=0) {
    Response::setExpiresInHours($days * 24);
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
		header('HTTP/1.1 301 Moved Permanently',true,301);
		header('Location: '.$url);
		exit();
	}

	static function contentDisposition($filename) {
		header("Content-Disposition: attachment; filename=\"$filename\"");
	}

	static function internalServerError($text=null) {
		Response::sendStatus(500,$text);
	}

	static function badGateway($text=null) {
		Response::sendStatus(502,$text);
	}

	static function badRequest($text=null) {
		Response::sendStatus(400,$text);
	}

	static function notFound($text=null) {
		Response::sendStatus(404,$text);
	}

  static function unauthorized($text=null) {
		Response::sendStatus(Response::$UNAUTHORIZED,$text);
  }

	static function forbidden($text=null) {
		Response::sendStatus(Response::$FORBIDDEN,$text);
	}

	static function uploadSuccess() {
		header('Content-Type: text/plain');
		echo 'SUCCESS';
	}

	static function uploadFailure() {
		Response::badRequest();
		header('Content-Type: text/plain');
		echo 'FAILURE';
	}

	static function sendStatus($number,$text=null) {
    http_response_code($number);
		if ($text) {
			echo '<!DOCTYPE html><html><head><title>'.$text.'</title></head><body><h1>'.$text.'</h1></body><p>'.$number.'</p></html>';
		}
	}
}
?>