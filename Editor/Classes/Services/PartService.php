<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

class PartService {
    
    static function load($type,$id) {
        if (!$id) {
            return null;
        }
        $class = ucfirst($type).'Part';
        if (!ClassService::load($class)) {
            return null;
        }
        return ModelService::load($class,$id);
    }

    static function remove($part) {
        
        $sql = "delete from part where id=".Database::int($part->getId());
        Database::delete($sql);

        $sql = "delete from part_".$part->getType()." where part_id=".Database::int($part->getId());
        Database::delete($sql);

        $sql = "delete from link where part_id=".Database::int($part->getId());
        Database::delete($sql);

        $sql = "delete from part_link where part_id=".Database::int($part->getId());
        Database::delete($sql);
        
        // Delete relations
        $schema = PartService::getSchema($part->getType());
        if (isset($schema['relations']) && is_array($schema['relations'])) {
            foreach ($schema['relations'] as $field => $info) {
                $sql = "delete from ".$info['table']." where ".$info['fromColumn']."=".Database::int($part->getId());
                Database::delete($sql);
            }
        }
    }
    
    static private function getSchema($type) {
        $class = PartService::getClassName($type);
        if (array_key_exists($class,Entity::$schema)) {
            return Entity::$schema[$class];
        }
        return null;
    }
    
    static function save($part) {
        $controller = PartService::getController($part->getType());
        if ($part->isPersistent()) {
            if ($controller) {
                $controller->beforeSave($part);
            }
            PartService::update($part);
        } else {
            $schema = PartService::getSchema($part->getType());
            
            $sql = "insert into part (type,dynamic,created,updated) values (".
            Database::text($part->getType()).",".
            Database::boolean($part->isDynamic()).",".
            "now(),now()".
            ")";
            $part->setId(Database::insert($sql));
            
            $columns = SchemaService::buildSqlColumns($schema);
            $values = SchemaService::buildSqlValues($part,$schema);

            $sql = "insert into part_".$part->getType()." (part_id";
            if (strlen($columns)>0) {
                $sql.=",".$columns;
            }
            $sql.=") values (".$part->getId();
            if (strlen($values) > 0) {
                $sql.=",".$values;
            }
            $sql.=")";
            Database::insert($sql);
            
            if (isset($schema['relations']) && is_array($schema['relations'])) {
                foreach ($schema['relations'] as $field => $info) {
                    $getter = 'get'.ucfirst($field);
                    $ids = $part->$getter();
                    if ($ids!==null) {
                        foreach ($ids as $id) {
                            $sql = "insert into ".$info['table']." (".$info['fromColumn'].",".$info['toColumn'].") values (".Database::int($part->getId()).",".Database::int($id).")";
                            Database::insert($sql);
                        }
                    }
                }
            }
            if ($controller) {
                $changed = $controller->beforeSave($part);
                if ($changed) {
                    PartService::update($part);
                }
            }
        }
    }
    
    static function update($part) {
        $sql = "update part set updated=now(),dynamic=".Database::boolean($part->isDynamic())." where id=".Database::int($part->getId());
        Database::update($sql);
        
        
        $schema = PartService::getSchema($part->getType());
        $setters = SchemaService::buildSqlSetters($part,$schema);
        
        if (Strings::isNotBlank($setters)) {
            $sql = "update part_".$part->getType()." set ".$setters." where part_id=".Database::int($part->getId());
            Database::update($sql);
        }
        
        // Update relations
        if (isset($schema['relations']) && is_array($schema['relations'])) {
            foreach ($schema['relations'] as $field => $info) {
                $sql = "delete from ".$info['table']." where ".$info['fromColumn']."=".$part->getId();
                Database::delete($sql);
                $getter = 'get'.ucfirst($field);
                $ids = $part->$getter();
                if ($ids!==null) {
                    foreach ($ids as $id) {
                        $sql = "insert into ".$info['table']." (".$info['fromColumn'].",".$info['toColumn'].") values (".Database::int($part->getId()).",".Database::int($id).")";
                        Database::insert($sql);
                    }
                }
            }
        }
    }

    static function getClassName($type) {
        if (Strings::isBlank($type)) {
            return null;
        }
        return ucfirst($type).'Part';
    }
    
    /**
     * Creates a new part based on the type
     */
    static function newInstance($type) {
        $class = PartService::getClassName($type);
        if (!ClassService::load($class)) {
            return null;
        }
        return new $class;
    }
    
    /** Gets the controller for a type */
    static function getController($type) {
        global $basePath;
        if (!$type) {
            Log::debug('Unable to get controller for no type');
            return null;
        }
        $class = ucfirst($type).'PartController';
        $path = $basePath.'Editor/Classes/Parts/'.$class.'.php';
        if (!file_exists($path)) {
            Log::debug('Unable to find controller for: '.$type);
            return null;
        }
        require_once $path;
        return new $class;
    }
    
    static function getPageIdsForPart($partId) {
        $sql = "select page_id as id from document_section where part_id=@int(partId)";
        return Database::selectIntArray($sql,['partId'=>$partId]);
    }

    /** Builds the context for a page */
    static function buildPartContext($pageId) {
        $context = new PartContext();
    
        $sql = "select link.*,page.path from link left join page on page.id=link.target_id and link.target_type='page' where page_id=".$pageId." and source_type='text'";

        $result = Database::select($sql);
        while ($row = Database::next($result)) {
            $context -> addBuildLink(
                Strings::escapeSimpleXML($row['source_text']),
                $row['target_type'],
                $row['target_id'],
                $row['target_value'],
                $row['target'],
                $row['alternative'],
                $row['path']
            );
        }
        Database::free($result);

        return $context;
    }
    

    /** Get a list of all available parts */
    static function getAvailableParts() {
        global $basePath;
        $arr = FileSystemService::listDirs($basePath."Editor/Parts/");
        for ($i=0;$i<count($arr);$i++) {
            if (substr($arr[$i],0,3)=='CVS') {
                unset($arr[$i]);
            }
        }
        return $arr;
    }

    /** A map of all available parts */
    static function getParts() {
        return array(
            'header' => array ( 'name' => array('da'=>'Overskrift','en'=>'Header') ),
            'text' => array ( 'name' => array('da'=>'Tekst','en'=>'Text') ),
            'listing' => array ( 'name' => array('da'=>'Punktopstilling','en'=>'Bullet list') ),
            'image' => array ( 'name' => array('da'=>'Billede','en'=>'Image') ),
            'imagegallery' => array ( 'name' => array('da'=>'Billedgalleri','en'=>'Image gallery') ),
            'horizontalrule' => array ( 'name' => array('da'=>'Adskiller','en'=>'Divider') ),
            'table' => array ( 'name' => array('da'=>'Tabel','en'=>'Table') ),
            'richtext' => array ( 'name' => array('da'=>'Rig tekst','en'=>'Rich text') ),
            'file' => array ( 'name' => array('da'=>'Fil','en'=>'File') ),
            'person' => array ( 'name' => array('da'=>'Person','en'=>'Person') ),
            'news' => array ( 'name' => array('da'=>'Nyheder','en'=>'News') ),
            'formula' => array ( 'name' => array('da'=>'Formular','en'=>'Formula') ),
            'list' => array ( 'name' => array('da'=>'Liste','en'=>'List') ),
            'mailinglist' => array ( 'name' => array('da'=>'Postliste','en'=>'Mailing list') ),
            'html' => array ( 'name' => array('da'=>'HTML','en'=>'HTML') ),
            'poster' => array ( 'name' => array('da'=>'Plakat','en'=>'Poster') ),
            'map' => array ( 'name' => array('da'=>'Kort','en'=>'Map') ),
            'movie' => array ( 'name' => array('da'=>'Film','en'=>'Movie') ),
            'menu' => array ( 'name' => array('da'=>'Menu','en'=>'Menu') )
        );
    }
    
    /** The part menu structure */
    static function getPartMenu() {
        $parts = PartService::getParts();
        $menu = array(
            'header' => $parts['header'],
            'text' => $parts['text'],
            'listing' => $parts['listing'],
            'image' => $parts['image'],
            'horizontalrule' => $parts['horizontalrule'],
            'table' => $parts['table'],
            'x' => 'divider',
            'richtext' => $parts['richtext'],
            'file' => $parts['file'],
            'imagegallery' => $parts['imagegallery'],
            'y' => 'divider',
            'advanced' => array('name'=>array('da'=>'Avanceret','en'=>'Advanced'),'children'=>array(
                'person' => $parts['person'],
                'news' => $parts['news'],
                'formula' => $parts['formula'],
                'list' => $parts['list'],
                'mailinglist' => $parts['mailinglist'],
                'html' => $parts['html'],
                'poster' => $parts['poster'],
                'map' => $parts['map'],
                'movie' => $parts['movie'],
                'menu' => $parts['menu']
            ))
        );
        return $menu;
    }
    
    /** Gets all available controllers */
    static function getAllControllers() {
        $controllers = array();
        $parts = PartService::getParts();
        foreach ($parts as $key => $value) {
            $controllers[] = PartService::getController($key);
        }
        return $controllers;
    }

    /**
     * Used to sort arrays of tools
     */
    static function compareParts($partA, $partB) {
        $a = $partA['priority'];
        $b = $partB['priority'];
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }


    /** Get part info based on its unique ID */
    static function getPartInfo($unique) {
        global $basePath;
        $file = $basePath."Editor/Parts/".$unique."/info.xml";
        if (file_exists($file)) {
            $info = array();
            $doc = new DOMDocument();
            if ($doc->load($file)) {
                $info['name'] = DOMUtils::getPathText($doc->documentElement,"/part/name");
                $info['description'] = DOMUtils::getPathText($doc->documentElement,"/part/description");
                $info['priority'] = DOMUtils::getPathText($doc->documentElement,"/part/priority");
            }
            else {
                error_log('getPartInfo: '.$doc->getErrorString());
            }
            return $info;
        }
        else {
            return false;
        }
    }
    
    /** Get the possible link text for a certain part */
    static function getLinkText($partId) {
        $text = '';
        $sql = "select text,document_section.part_id from part_text,document_section where document_section.part_id=part_text.part_id and document_section.part_id=".Database::int($partId)."
union select text,document_section.part_id from part_header,document_section where document_section.part_id=part_header.part_id and document_section.part_id=".Database::int($partId)."
union select text,document_section.part_id from part_listing,document_section where document_section.part_id=part_listing.part_id and document_section.part_id=".Database::int($partId)."
union select html as text,document_section.part_id from part_table,document_section where document_section.part_id=part_table.part_id and document_section.part_id=".Database::int($partId);
        $result = Database::select($sql);
        while ($row = Database::next($result)) {
            $text.=' '.$row['text'];
        }
        Database::free($result);
        return $text;
    }
    
    /** Get the first link for a part */
    static function getSingleLink($part,$sourceType=null) {
        $sql = "select part_link.*,page.path from part_link left join page on page.id=part_link.target_value and part_link.target_type='page' where part_id=".Database::int($part->getId());
        if (!is_null($sourceType)) {
            $sql.=" and source_type=".Database::text($sourceType);
        }
        if ($row = Database::selectFirst($sql)) {
            return $row;
        } else {
            return false;
        }
    }
    
    /** Remove all existing links for a part */
    static function removeLinks($part) {
        $sql = "delete from part_link where part_id=".Database::int($part->getId());
        Database::delete($sql);
    }
    
    /** Remove all existing links for a part */
    static function removeLink($link) {
        $sql = "delete from part_link where id=@int(id)";
        Database::delete($sql,['id'=>$link->getId()]);
    }
    
    /** Gets all links for a part */
    static function getLinks($part) {
        $links = array();
        $sql = "select * from part_link where part_id=".Database::int($part->getId());
        $result = Database::select($sql);
        while ($row = Database::next($result)) {
            $link = new PartLink();
            $link->setId(intval($row['id']));
            $link->setPartId(intval($row['part_id']));
            $link->setSourceType($row['source_type']);
            $link->setSourceText($row['source_text']);
            $link->setTargetType($row['target_type']);
            $link->setTargetValue($row['target_value']);
            $links[] = $link;
        }
        Database::free($result);
        return $links;
    }
    
    /** Saves a link */
    static function saveLink($link) { /* PartLink */
        Log::debug($link);
        if ($link->id) {
            $sql="update part_link set ".
            "part_id=".Database::int($link->partId).
            ",source_type=".Database::text($link->sourceType).
            ",source_text=".Database::text($link->sourceText).
            ",target_type=".Database::text($link->targetType).
            ",target_value=".Database::text($link->targetValue).
            " where id=".Database::int($link->id);
            Database::update($sql);
        } else {
            $sql="insert into part_link (part_id,source_type,source_text,target_type,target_value
                ) values (".
                Database::int($link->partId).",".
                Database::text($link->sourceType).",".
                Database::text($link->sourceText).",".
                Database::text($link->targetType).",".
                Database::text($link->targetValue).
            ")";
            $link->id = Database::insert($sql);
        }
    }
}