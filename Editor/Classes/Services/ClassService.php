<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ClassService {

  static function getClassInfo() {
    $infos = array();
    $files = ClassService::_getFiles();
    foreach ($files as $path) {
      preg_match('/([A-Za-z0-9]+)\.php/i', $path,$matches);
      $name = $matches[1];
      //require_once($path);

      $info = new ClassInfo();
      $info->setPath($path);
      $info->setName($name);
      $relations = array();

      if (class_exists($name)) {
        $parent = get_parent_class($name);
        $info->setParent($parent);
        $instance = @new $name;
        $vars = get_object_vars($instance);
        $hierarchy = ClassService::_getHierarchy($name);
        $info->setHierarchy($hierarchy);
        $properties = array();
        foreach ($vars as $key => $value) {
          $property = new ClassPropertyInfo();
          $property->setName($key);
          $property->setValue($value);
          $property->setOrigin(ClassService::_getOrigin($key,$hierarchy));
          if (isset(Entity::$schema[$name])) {
            $schema = Entity::$schema[$name];
            if (is_array($schema['properties'])) {
              if (isset($schema['properties'][$key])) {
                $propInfo = $schema['properties'][$key];
                $property->setType($propInfo['type']);

                if (isset($propInfo['relation']) && is_array($propInfo['relation'])) {
                  $relation = new ClassRelationInfo();
                  $relation->setFromClass($name);
                  $relation->setFromProperty($key);
                  $relation->setToClass($propInfo['relation']['class']);
                  $relation->setToProperty($propInfo['relation']['property']);
                  $relations[] = $relation;
                }
                if (isset($propInfo['relations']) && is_array($propInfo['relations'])) {
                  foreach ($propInfo['relations'] as $relationInfo) {
                    $relation = new ClassRelationInfo();
                    $relation->setFromClass($name);
                    $relation->setFromProperty($key);
                    $relation->setToClass($relationInfo['class']);
                    $relation->setToProperty($relationInfo['property']);
                    $relations[] = $relation;
                  }
                }

              }
            }
          }
          $properties[] = $property;
        }
        $info->setProperties($properties);
      } else {
        $info->setHierarchy(array($name));
        Log::debug('Class '.$name.' does not exist');
      }
      $info->setRelations($relations);

      $infos[] = $info;
    }
    return $infos;
  }

  static function _getOrigin($property,$hierarchy) {
    foreach ($hierarchy as $class) {
      if (property_exists($class,$property)) {
        return $class;
      }
    }
    return null;
  }

  static function _getHierarchy($name) {
    $hier = array($name);
    $parent = $name;
    while ($parent) {
      $parent = get_parent_class($parent);
      if ($parent) {
        $hier[] = $parent;
      }
    }
    return array_reverse($hier);
  }

  static function _getFiles() {
    global $basePath;
    $dir = $basePath.'Editor/Classes/';
    $files = FileSystemService::find(array(
      'dir' => $dir,
      'extension' => 'php'
    ));
    return $files;
  }

    static function getByInterface($interface) {
    global $HUMANISE_EDITOR_CLASSES;
        if (is_array($HUMANISE_EDITOR_CLASSES['interfaces'][$interface])) {
            return $HUMANISE_EDITOR_CLASSES['interfaces'][$interface];
        }
        return [];
    }

    static function getBySuper($name) {
    global $HUMANISE_EDITOR_CLASSES;
        if (is_array($HUMANISE_EDITOR_CLASSES['parents'][$name])) {
            return $HUMANISE_EDITOR_CLASSES['parents'][$name];
        }
        return [];
    }

    static function load($name) {
    return class_exists($name, true);
    }

  static function getClasses() {
    global $basePath;
    $dir = $basePath.'Editor/Classes/';
    $files = ClassService::_getFiles();
    foreach ($files as $path) {
      preg_match('/([A-Za-z0-9]+)\.php/i', $path,$matches);
      $name = $matches[1];
      require_once($path);
      $valid = false;
      $parent = null;
      $props = null;
      if (class_exists($name)) {
        $valid = true;
        $parent = get_parent_class($name);
        $class = new ReflectionClass($name);
        if (!$class->isAbstract()) {
          $instance = @new $name;
          $props = get_object_vars($instance);
        }
      }
      $classes[] = array(
        'path' => $path,
        'relativePath' => substr($path,strlen($dir)),
        'name' => $name,
        'valid' => $valid,
        'parent' => $parent,
        'properties' => $props
      );
    }
    return $classes;
  }

  static function rebuildClasses() {
    global $basePath;
    $cache = [ 'all' => [], 'interfaces' => [], 'parents' => [] ];

    $list = ClassService::getClasses();
    foreach ($list as $item) {
      $className = $item['name'];
      $cache['all'][$className] = $item['relativePath'];
      $interfaces = class_implements($className);
      foreach ($interfaces as $interface) {
        if (!isset($cache['interfaces'][$interface])) {
          $cache['interfaces'][$interface] = [];
        }
        $cache['interfaces'][$interface][] = $className;
      }
      $parent = get_parent_class($className);
      while ($parent) {
        if (!isset($cache['parents'][$parent])) {
          $cache['parents'][$parent] = [];
        }
        $cache['parents'][$parent][] = $className;

        $parent = get_parent_class($parent);
      }
    }

    $text = var_export($cache,true);

    $text = "<?php\n" .
    "if (!isset(\$GLOBALS['basePath'])) {\n" .
    "   header('HTTP/1.1 403 Forbidden');\n" .
    " exit;\n" .
    "}\n\n" .
    "\$HUMANISE_EDITOR_CLASSES = ".$text."\n?>";

    $success = FileSystemService::writeStringToFile($text,$basePath.'Editor/Info/Classes.php');
    return $success;
  }
}