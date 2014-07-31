<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class HierarchyService {
	    	
	static function createHierarchy($hierarchy) {
		if (!$hierarchy) {
			Log::debug('No hierarchy');
			return;
		}
        $data='<hierarchy xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"/>';

		$sql = "insert into hierarchy (name,language,data,changed,published) values (".
		Database::text($hierarchy->getName()).",".
		Database::text($hierarchy->getLanguage()).",".
		Database::text($data).",".
		"now(),now()".
		")";
		$hierarchy->setId(Database::insert($sql));
    }
    
    static function getHierarchyItemForPage($page) {
        $sql="select * from hierarchy_item where target_type='page' and target_id=".Database::int($page->getId());
        return Database::selectFirst($sql);
    }

    static function updateHierarchy($hierarchy) {
		if (!$hierarchy) {
			Log::debug('No hierarchy');
			return;
		}
        $sql="update hierarchy set ".
        "name=".Database::text($hierarchy->getName()).
        ",language=".Database::text($hierarchy->getLanguage()).
        " where id=".Database::int($hierarchy->getId());
        return Database::update($sql);
    }

    static function canDeleteHierarchy($id) {
        $sql="select count(id) as num from hierarchy_item where hierarchy_id=".Database::int($id);
        if ($row = Database::selectFirst($sql)) {
            if ($row['num']==0) {
                return true;
            }
        }
        return false;
    }

	static function markHierarchyChanged($id) {
		$sql="update hierarchy set changed=now() where id=".Database::int($id);
		Database::update($sql);
	}

	static function createItem($options) {
		if (Strings::isBlank(@$options['title'])) {
			Log::debug('No title');
			return false;
		}
		if (!in_array(@$options['targetType'],array('page','pageref','file','email','url'))) {
			Log::debug('Invalid targetType');
			return false;
		}
		if (!isset($options['hidden'])) {
			Log::debug('hidden not set');
			return false;
		}
		if (!isset($options['targetValue'])) {
			Log::debug('targetValue not set');
			return false;
		}
		if (!isset($options['parent'])) {
			Log::debug('parent not set');
			return false;
		}
		if (!isset($options['hierarchyId'])) {
			Log::debug('hierarchyId not set');
			return false;
		}
		$sql="select id from hierarchy where id=".Database::int($options['hierarchyId']);
		if (!$row = Database::selectFirst($sql)) {
			Log::debug('hierarchy not found');
			return false;
		}
		if ($options['parent']>0) {
			$sql="select id from hierarchy_item where id=".Database::int($options['parent'])." and hierarchy_id=".Database::int($options['hierarchyId']);
			if (!$row = Database::selectFirst($sql)) {
				Log::debug('parent not found');
				return false;
			}
		}
		// find index
		if (isset($options['index'])) {
			$sql = "select id, `index` from hierarchy_item where `index` >= @int(index) and parent = @int(parent) and hierarchy_id = @int(hierarchy) order by `index`";
			$result = Database::select($sql,['index'=>$options['index'],'parent'=>$options['parent'],'hierarchy'=>$options['hierarchyId']]);
			while ($row = Database::next($result)) {
				Database::update("update hierarchy_item set `index`=@int(index) where `id`=@int(id)",[
					'id' => $row['id'],
					'index' => intval($row['index'])+1
				]);
			}
			Database::free($result);
			$index = $options['index'];
		} else {
			$sql="select max(`index`) as `index` from hierarchy_item where parent=".Database::int($options['parent'])." and hierarchy_id=".Database::int($options['hierarchyId']);
			if ($row = Database::selectFirst($sql)) {
				$index=$row['index']+1;
			} else {
				$index=1;
			}
		}

		$target_id = null;
		$target_value = null;
		if ($options['targetType']=='page' || $options['targetType']=='pageref' || $options['targetType']=='file') {
			$target_id = $options['targetValue'];
		} else {
			$target_value = $options['targetValue'];
		}
		
		$sql="insert into hierarchy_item (title,hidden,type,hierarchy_id,parent,`index`,target_type,target_id,target_value) values (".
		Database::text($options['title']).
		",".Database::boolean($options['hidden']).
		",'item'".
		",".Database::int(@$options['hierarchyId']).
		",".Database::int($options['parent']).
		",".Database::int($index).
		",".Database::text($options['targetType']).
		",".Database::int($target_id).
		",".Database::text($target_value).
		")";
		$id = Database::insert($sql);
		HierarchyService::markHierarchyChanged($options['hierarchyId']);
		return $id;
	}
    
    static function deleteItem($id) {

        // Load info about item
        $sql="select * from hierarchy_item where id=".Database::int($id);
        $row = Database::selectFirst($sql);
		if (!$row) {
			Log::debug('Cannot find item');
			return null;
		}
		// Check that no children exists
        $sql="select * from hierarchy_item where parent=".Database::int($id);
		if (Database::selectFirst($sql)) {
			Log::debug('Will not delete item with parents');
			return null;
		}
        $parent = $row['parent'];
        $hierarchyId = $row['hierarchy_id'];

        // Delete item
        $sql="delete from hierarchy_item where id=".Database::int($id);
        Database::delete($sql);

        // Fix positions
        $sql="select id from hierarchy_item where parent=".Database::int($parent)." and hierarchy_id=".Database::int($hierarchyId)." order by `index`";
        $result = Database::select($sql);

        $index=1;
        while ($row = Database::next($result)) {
        	$sql="update hierarchy_item set `index`=".Database::int($index)." where id=".Database::int($row['id']);
        	Database::update($sql);
        	$index++;
        }
        Database::free($result);

        // Mark hierarchy as changed
        $sql="update hierarchy set changed=now() where id=".Database::int($hierarchyId);
        Database::update($sql);
	
		EventService::fireEvent('update','hierarchy',null,$hierarchyId);
        return $hierarchyId;
    }
	
}