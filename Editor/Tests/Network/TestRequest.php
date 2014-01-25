<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestRequest extends UnitTestCase {
    
    function testResponseEmpty() {
        $response = WebResponse::newFromData("");
        $this->assertEqual("",$response->getBody());
        $this->assertEqual(null,$response->getHttpVersion());
        $this->assertEqual(null,$response->getStatusCode());
        

        $response = WebResponse::newFromData(" ");
        $this->assertEqual("",$response->getBody());
        $this->assertEqual(null,$response->getHttpVersion());
        $this->assertEqual(null,$response->getStatusCode());
    }
    
    function testResponseNull() {
        $response = WebResponse::newFromData(null);
        $this->assertEqual("",$response->getBody());
        $this->assertEqual(null,$response->getHttpVersion());
        $this->assertEqual(null,$response->getStatusCode());
    }
    
    function testResponse200() {
        $data = "HTTP/1.1 200 OK\r\n".
            "Content-Type: text/html; charset=UTF-8\r\n".
            "Pragma: cache\r\n".
            "X-Powered-By: PHP/5.4.4-14+deb7u4\r\n".
            "Set-Cookie: PHPSESSID=67prn9qe5q354olm2pv22v5hj6; path=/\r\n".
            "Server: Apache/2.2.22 (Debian)\r\n".
            "Expires: Thu, 30 Jan 2014 08:20:21 GMT\r\n".
            "Transfer-Encoding: Identity\r\n".
            "Cache-Control: public\r\n".
            "Date: Thu, 23 Jan 2014 08:20:21 GMT\r\n".
            "Connection: close\r\n".
            "X-UA-Compatible: IE=edge\r\n".
            "Vary: Accept-EncodingLast-Modified: Sat, 04 Jan 2014 16:08:21 GMT".
            "\r\n\r\n".
            "<!DOCTYPE html>";
        
        $response = WebResponse::newFromData($data);
        $this->assertEqual('<!DOCTYPE html>',$response->getBody());
        $this->assertEqual(1.1,$response->getHttpVersion());
        $this->assertEqual(200,$response->getStatusCode());
        $this->assertEqual("PHPSESSID=67prn9qe5q354olm2pv22v5hj6; path=/",$response->getHeader('Set-Cookie'));
        $this->assertEqual("public",$response->getHeader('Cache-Control'));
    }
}