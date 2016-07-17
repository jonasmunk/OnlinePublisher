<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class WebResponse {

  private $statusCode;
  private $headerRaw;
  private $headers = [];
  private $body;
  private $httpVersion;

  function WebResponse() {
  }

  static function newFromData($data) {
    $response = new WebResponse();
    if (strlen($data)>0) {
      if (preg_match("/^HTTP\\/([0-9\\.]+) ([0-9]+)/u", $data, $matches)===1) {
        $response->setHttpVersion(floatval($matches[1]));
        $response->setStatusCode(intval($matches[2]));
        $parts = explode("\r\n\r\nHTTP/", $data);
        $parts = (count($parts) > 1 ? 'HTTP/' : '').array_pop($parts);
        list($header, $body) = explode("\r\n\r\n", $parts, 2);
        $response->setBody($body);
        $response->setHeaderRaw($header);

        $headers = [];
        $lines = explode("\r\n",$header);
        foreach ($lines as $line) {
          if ($pos = strpos($line,':')) {
            $headers[] = ['name' => substr($line,0,$pos), 'value' => ltrim(substr($line,$pos+1))];
          }
        }
        $response->setHeaders($headers);
      }
    }
    return $response;
  }

  function setStatusCode($statusCode) {
    $this->statusCode = $statusCode;
  }

  function getStatusCode() {
    return $this->statusCode;
  }

  function setBody($body) {
    $this->body = $body;
  }

  function getBody() {
    return $this->body;
  }

  function setHeaderRaw($headerRaw) {
    $this->headerRaw = $headerRaw;
  }

  function getHeaderRaw() {
    return $this->headerRaw;
  }

  function setHeaders($headers) {
    $this->headers = $headers;
  }

  function getHeaders() {
    return $this->headers;
  }

  function getHeader($name) {
    foreach ($this->headers as $header) {
      if ($name == $header['name']) {
        return $header['value'];
      }
    }
    return null;
  }

  function setHttpVersion($httpVersion) {
    $this->httpVersion = $httpVersion;
  }

  function getHttpVersion() {
    return $this->httpVersion;
  }

  function isSuccess() {
    return $this->statusCode == 200;
  }
}