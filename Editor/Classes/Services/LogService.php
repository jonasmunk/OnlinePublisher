<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Database.php');

class LogService {

	function getEntries() {
		$sql = "select UNIX_TIMESTAMP(`time`) as `time`,`category`,`event`,`entity`,`message`,`user_id`,`ip`,`session`,user.username from log left join `user` on object_id=log.user_id order by time desc";
		return Database::selectAll($sql);
	}
}