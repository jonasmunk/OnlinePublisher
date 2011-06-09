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
	
	function clearCompletePageCache() {
		$sql = "delete from page_cache";
		Database::delete($sql);		
	}
	
	function sendCachedPage($id,$path) {
		return false;
		$sql = "select page_cache.html,UNIX_TIMESTAMP(page.published) from page_cache,page,frame where page.secure=0 and page.dynamic=0 and page.id=page_cache.page_id and page.frame_id=frame.id and frame.dynamic=0";
		if (strlen($path)>0) {
			$sql.=" and page.path=".Database::text($path);
		} else {
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
	
	function createPageCache($id,$html) {
		return;
		$html = Database::text($html);
		if (strlen($html)>49900) {
			return; // Be sure not to cache incomplete html
		}
		$sql = "delete from page_cache where page_id=".Database::int($id);
		Database::delete($sql);
		$sql = "insert into page_cache (page_id,html,stamp) values (".Database::int($id).",".$html.",now())";
		Database::insert($sql);		
	}
}