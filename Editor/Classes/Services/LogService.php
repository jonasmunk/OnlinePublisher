<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Database.php');

class LogService {

	function getEntries($query=array()) {
		$sql = "select UNIX_TIMESTAMP(`time`) as `time`,`category`,`event`,`entity`,`message`,`user_id`,`ip`,`session`,user.username from log left join `user` on object_id=log.user_id";
		if ($query['category']) {
			$sql.=" where `category`=".Database::text($query['category']);
		}
		$sql.= " order by time desc,log.id desc";
		return Database::selectAll($sql);
	}
}