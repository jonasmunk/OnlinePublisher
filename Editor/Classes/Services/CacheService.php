<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */

class CacheService {

	function clearPageCache($id) {
		$sql = "delete from page_cache where page_id=".Database::int($id);
		Database::delete($sql);		
	}
	
	function getNumberOfCachedPages() {
		$sql = "select count(page_id) as num from page_cache";
		$row = Database::selectFirst($sql);
		return intval($row['num']);
	}
	
	function clearCompletePageCache() {
		$sql = "delete from page_cache";
		Database::delete($sql);		
	}
	
	function sendCachedPage($id,$path) {
		if (Request::getBoolean('viewsource')) {
			return false;
		}
		$sql = "select page_cache.html,UNIX_TIMESTAMP(page.published) from page_cache,page,frame where page.secure=0 and page.dynamic=0 and page.id=page_cache.page_id and page.frame_id=frame.id and frame.dynamic=0";
		if (strlen($path)>0) {
			$sql.=" and page_cache.path=".Database::text($path);
			$sql.=" and page.path=".Database::text($path);
		} else {
			$sql.=" and (page_cache.path is null or page_cache.path='')";
			$sql.=" and page.id=".Database::int($id);
		}
		if ($row = Database::selectFirst($sql)) {
			header("Last-Modified: " . gmdate("D, d M Y H:i:s",$row['published']) . " GMT");
			header("Cache-Control: public");
			header("Expires: " . gmdate("D, d M Y H:i:s",time()+604800) . " GMT");
			header("Content-Type: text/html; charset=UTF-8");
			echo $row['html'];
			return true;
		}
		return false;
	}
	
	function createPageCache($id,$path,$html) {
		$html = Database::text($html);
		if (strlen($html)>49900) {
			return; // Be sure not to cache incomplete html
		}
		$sql = "delete from page_cache where page_id=".Database::int($id);
		if (strlen($path)>0) {
			$sql.=" and page_cache.path=".Database::text($path);
		} else {
			$sql.=" and (page_cache.path is null or page_cache.path='')";
		}
		Database::delete($sql);
		$sql = "insert into page_cache (page_id,path,html,stamp) values (".Database::int($id).",".Database::text($path).",".$html.",now())";
		Database::insert($sql);		
	}
	
	////// Images //////
	
    function clearCompleteImageCache() {
        global $basePath;
        $dir = $basePath.'local/cache/images/';
        $files = FileSystemService::listFiles($dir);
        foreach ($files as $file) {
            @unlink($dir.$file);
        }
    }

    function getImageCacheInfo() {
        global $basePath;
        $dir = $basePath.'local/cache/images/';
        $files = FileSystemService::listFiles($dir);
		$info = array('count'=>count($files),'size'=>0);
        foreach ($files as $file) {
            $info['size']+=filesize($dir.$file);
        }
		return $info;
    }
	
	////// Temp //////
	
    function clearCompleteTempCache() {
        global $basePath;
        $dir = $basePath.'local/cache/temp/';
        $files = FileSystemService::listFiles($dir);
        foreach ($files as $file) {
            @unlink($dir.$file);
        }
    }

    function getTempCacheInfo() {
        global $basePath;
        $dir = $basePath.'local/cache/temp/';
        $files = FileSystemService::listFiles($dir);
		$info = array('count'=>count($files),'size'=>0);
        foreach ($files as $file) {
            $info['size']+=filesize($dir.$file);
        }
		return $info;
    }
	
	////// URLs //////
	
    function clearCompleteUrlCache() {
        global $basePath;
        $dir = $basePath.'local/cache/urls/';
        $files = FileSystemService::listFiles($dir);
        foreach ($files as $file) {
            @unlink($dir.$file);
        }
    }

    function getUrlCacheInfo() {
        global $basePath;
        $dir = $basePath.'local/cache/urls/';
        $files = FileSystemService::listFiles($dir);
		$info = array('count'=>count($files),'size'=>0);
        foreach ($files as $file) {
            $info['size']+=filesize($dir.$file);
        }
		return $info;
    }
}