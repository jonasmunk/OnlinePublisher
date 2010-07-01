<?
class Response {
	
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
	
	function internalServerError() {
		header('HTTP/1.1 500 Internal Server Error');
	}
	
	function badRequest() {
		header('HTTP/1.1 400 Bad Request');
	}
	
	function notFound($text=null) {
		Response::sendStatus(404,'Not Found',$text);
	}
	
	function sendStatus($number,$key,$text=null) {
		header('HTTP/1.1 '.$number.' '.$key);
		if ($text) {
			echo '<html><head><title>'.$text.'</title></head><body><h1>'.$text.'</h1></body></html>';
		}
	}
}
?>