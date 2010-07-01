<?
class RemoteFile {
    
    var $url;
    var $data;
	var $info;
    
    function RemoteFile($url) {
        $this->url = $url;
        $data = null;
    }
    
    function _get($returnTransfer=false,$path=null) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
		if ($path!==null) {
			$file = fopen($path, "wb");
		    curl_setopt($ch, CURLOPT_FILE, $file);
            curl_exec($ch);
			$this->info = curl_getinfo($ch);
        	curl_close($ch);
			fclose($file);
        } else if ($returnTransfer) {
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returnTransfer);
            $this->data = curl_exec($ch);
			$this->info = curl_getinfo($ch);
        	curl_close($ch);
        } else {
        	curl_close($ch);
		}
	}
	
	function getInfo() {
		return $this->info;
	}
	
	function isSuccess() {
		if (!$this->info) {
			return false;
		}
		return $this->info['http_code']===200;
	}
	
	function getFilename() {
		$parsed = @parse_url($this->url);
		if ($parsed===false) {
			return '';
		}
		$path = $parsed['path'];
		$splitted = split('/',$path);
		return $splitted[count($splitted)-1];
	}
	
	function getContentType() {
		$type = $this->info['content_type'];
		$splitted = split(';',$type);
		return $splitted[0];
	}

	function getData() {
	    $this->_get(true);
	    return $this->data;
	}

    function writeToFile($path) {
	    $this->_get(false,$path);
		return true;
    }

	function writeToTempFile() {
		global $basePath;
	    $path = $basePath.'local/cache/temp/'.mktime();
		$this->writeToFile($path);
		return $path;
	}
}

?>