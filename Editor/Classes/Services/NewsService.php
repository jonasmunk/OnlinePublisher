<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */

class NewsService {
	
	function synchronizeSource($id) {
		// TODO: Dont remove old items, only update existing
		$source = Newssource::load($id);
		if (!$source) return;
		NewsService::clearSource($id);
		$data = RemoteDataService::getRemoteData($source->getUrl(),30);
		if ($data->isHasData()) {
			$parser = new FeedParser();
			$feed = $parser->parseURL($data->getFile());
			if ($feed) {
				$items = $feed->getItems();
				foreach ($items as $item) {
					$srcItem = new Newssourceitem();
					$srcItem->setTitle($item->getTitle());
					$srcItem->setText($item->getDescription());
					$srcItem->setNewssourceId($source->getId());
					$srcItem->setGuid($item->getGuid());
					$srcItem->setDate($item->getPubDate());
					$srcItem->setUrl($item->getLink());
					$srcItem->save();
					$srcItem->publish();
				}
			}
		}
	}
	
	function clearSource($id) {
		$items = Query::after('newssourceitem')->withProperty('newssource_id',$id)->get();
		foreach ($items as $item) {
			$item->remove();
		}
	}
}