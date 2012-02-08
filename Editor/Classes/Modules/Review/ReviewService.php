<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Review
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class ReviewService {
	
	function search($query) {
		$sql = '';
		if ($query['unreviewed']) {
			$sql = "select page.id as page_id, page.title as page_title,'' as user_title, null as date, -1 as accepted 
					from page where page.id not in (select relation.from_object_id from relation,review 
					where relation.to_type='object' and relation.to_object_id=review.object_id)
					order by date desc,page_title";
		}
		if ($query['accepted'] || $query['rejected']) {
			if ($sql) {
				$sql.=' union ';
			}
			$sql.= "select page.id as page_id,page.title as page_title,user.title as user_title,UNIX_TIMESTAMP(review.date) as date,review.accepted
					from page,relation as page_review,relation as review_user,review,object as user 
					where page_review.from_type='page' and page_review.from_object_id=page.id
					and page_review.to_type='object' and page_review.to_object_id=review.object_id
					and review_user.from_type='object' and review_user.from_object_id=review.object_id
					and review_user.to_type='object' and review_user.to_object_id=user.id";
			if ($query['accepted'] && !$query['rejected']) {
				$sql.=' and review.accepted=1';
			}
			if (!$query['accepted'] && $query['rejected']) {
				$sql.=' and review.accepted=0';
			}
			if ($span=='day') {
				$sql.=' and review.date<'.Database::datetime(DateUtils::addDays(time(),-1));
			} else if ($span=='week') {
				$sql.=' and review.date<'.Database::datetime(DateUtils::addDays(time(),-7));
			}
			
		}	
		$list = array();
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$combo = new ReviewCombo();
			$combo->setPageId($row['page_id']);
			$combo->setPageTitle($row['page_title']);
			$combo->setAccepted($row['accepted']);
			$list[] = $combo;
		}
		Database::free($result);

		return $list;
	}
}
?>