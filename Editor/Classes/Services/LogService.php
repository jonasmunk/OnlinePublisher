<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class LogService {

	static function getEntries($query=array()) {
		$page = 0;
		$size = 10;
		if (isset($query['page'])) {
			$page = $query['page'];
		}
		if (isset($query['size'])) {
			$size = $query['size'];
		}
		
		$sql = "select UNIX_TIMESTAMP(`time`) as `time`,`category`,`event`,`entity`,`message`,`user_id`,`ip`,`session`,user.username from log left join `user` on object_id=log.user_id";
		$where = '';
		if (isset($query['category']) && $query['category'] != 'all') {
			$where.=" `category`=".Database::text($query['category']);
		}
		if (isset($query['event']) && $query['event'] != 'all') {
			if ($where) {
				$where.=' and ';
			}
			$where.=" `event`=".Database::text($query['event']);
		}
		if ($where!='') {
			$sql.=' where '.$where;
		}
		$sql.= " order by time desc,log.id desc";
		$sql.= " limit ".($page * $size).",".$size;
		
		$countSql = "select count(id) as num from log".($where!='' ? ' where '.$where : '');
		
		$result = new SearchResult();
		$result->setList(Database::selectAll($sql));
		$row = Database::selectFirst($countSql);
		$result->setTotal(intval($row['num']));
		
		return $result;
	}
	
	static function getPageNotFoundOverview() {
		$sql = "select count(id) as `count`,UNIX_TIMESTAMP(min(time)) as first,UNIX_TIMESTAMP(max(time)) as last,message from log where event='pagenotfound' group by message order by max(time) desc";
		$result = new SearchResult();
		$result->setList(Database::selectAll($sql));
		return $result;
	}
	
	static function getUsedCategories() {
		return Database::selectArray("select distinct category from log order by category");
	}

	static function getUsedEvents() {
		return Database::selectArray("select distinct event from log order by event");
	}
}