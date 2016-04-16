<?
  namespace SB\Classes\Basic;

  //INCLUDES functions
  include_once __DIR__ . "/../../Functions/encryption_functions.php";

  //USES
  use SB\Functions as sb;

  /*
  //INCLUDES framework sb
  include_once __DIR__ . "/__sb_replaceproperty.php";
  include_once __DIR__ . "/__sb_array_sorter.php";

  //INCLUDES functions
  include_once __DIR__ . "/../../functions/_string_functions.php";
  include_once __DIR__ . "/../../functions/_eval_functions.php";
  include_once __DIR__ . "/../../functions/_date_functions.php";
  include_once __DIR__ . "/../../functions/_ini_functions.php";
  include_once __DIR__ . "/../../functions/_debug_functions.php";
  include_once __DIR__ . "/../../functions/_sort_functions.php";
  include_once __DIR__ . "/../../functions/_array_functions.php";
  include_once __DIR__ . "/../../functions/_encryption_functions.php";
  include_once __DIR__ . "/../../functions/_memory_functions.php";
  include_once __DIR__ . "/../../functions/_php_functions.php";
  include_once __DIR__ . "/../../functions/_css_functions.php";
  include_once __DIR__ . "/../../functions/_json_functions.php";
  include_once __DIR__ . "/../../functions/_object_functions.php";
  */

  //CLASSES
  class SB_Object {
    //published properties
    public $ID;

    public $OriginalID;
    public $HashID;
    public $ParentID;
    public $SortID;

    public $Debug;
    public $DebugString;
    public $Demo;
    public $DemoString;
    public $Logging;
    public $LoggingString;
    public $Test;
    public $TestString;
    public $Timing;
    public $TimingString;
    public $TimingLimit;
    public $TimingLimitString;

    public $Developer;
    public $DeveloperString;
    public $Master;
    public $MasterString;

    public $LoggingPath;
    public $LoggingFilename;
    public $LoggingTablename;

    public $AddRepeatID;

    //protected properties
    protected $GUID;

    //public properties
    public $Objects;
    public $ObjectIndex;
    public $ObjectLevel;
    public $RootObject;
    public $ParentObject;

    public $ContainerID;
    public $ContainerClassID;

    public $Init;
    public $InitChildsFirst;
    public $InitObjects;
    public $InitObjectsProperties;
    public $InitOutput;
    public $InitProperties;
    public $InitRecursive;

    public $Inited;
    public $InitedProperties;

    public $Run;
    public $RunChildsFirst;
    public $RunObjects;
    public $RunOutput;
    public $RunRecursive;

    public $Runned;

    public $Indent;
    public $IndentChar;
    public $IndentString;

    public $VersionID;
    public $VersionMajor;
    public $VersionMinor;

    //implementation
    public function __construct($propertyarray=[]) {
      //set GUID
      $this->setGUID();
      $this->setHashID();

      //set ID
      $this->ID                       = "object_" . $this->GUID;
      $this->VersionID                = "SB";
      $this->VersionMajor             = "6";
      $this->VersionMinor             = "1";

      //default values
      $this->Debug                    = true;
      $this->DebugString              = "";
      $this->Demo                     = false;
      $this->DemoString               = "";
      $this->Logging                  = false;
      $this->LoggingString            = "";
      $this->Test                     = false;
      $this->TestString               = "";
      $this->Timing                   = false;
      $this->TimingString             = "";
      $this->TimingLimit              = 0.1;
      $this->TimingLimitString        = "";

      $this->Developer                = false;
      $this->DeveloperString          = "";
      $this->Master                   = false;
      $this->MasterString              = "";

      $this->Objects                  = array();
      $this->ObjectLevel              = 0;

      $this->LoggingFilename          = "";
      //$this->LoggingOutput            = "";
      $this->LoggingPath              = __DIR__ . "/../../../temp/log/" . date("Ymd", time());
      $this->LoggingTablename         = "logs";

      $this->Indent                   = true;
      $this->IndentChar               = "  ";
      $this->IndentString             = "";

      $this->Init                     = true;
      $this->InitChildsFirst          = true;
      $this->InitObjects              = true;
      $this->InitObjectsProperties    = true;
      $this->InitOutput               = array();
      $this->InitProperties           = true;
      $this->InitRecursive            = true;
      $this->Inited                   = false;
      $this->InitedProperties         = false;

      $this->Run                      = true;
      $this->RunChildsFirst           = true;
      $this->RunObjects               = true;
      $this->RunRecursive             = true;
      $this->RunOutput                = array();
      $this->Runned                   = false;

      $this->AddRepeatID              = true;

      //set properties from given properties array
      if (is_array($propertyarray)
      && !empty($propertyarray)) {
        $this->setProperties($propertyarray);
      }
    }


    public function __destruct() {
      if ($this->Objects
      &&  is_array($this->Objects)
      && !is_empty($this->Objects)) {
        foreach ($this->Objects as $object) {
          //$object->__destruct();
          unset($object);
        }
      }
    }


    public function __clone() {
      //set guid
      $this->setGUID();

      //object properties clonen
      foreach($this as $name => $value) {
        if (is_object($value)) {
          if ($name != "ParentObject"
          &&  $name != "RootObject"
          &&  $name != "QueryObject"
          &&  $name != "ConnectionObject") {
            $this->$name = clone $this->$name;
          }
        }
      }

      //Objects klonen
      if ($this->Objects
      && is_array($this->Objects)
      && !is_empty($this->Objects)) {
        //foreach($this->Objects as $object) {
        //  $object = clone $object;
        //}
        foreach($this->Objects as $key=>$object) {
          $this->Objects[$key] = clone $this->Objects[$key];
        }
      }

    }

    /*
    public function initRepeatIDs(){
      $this->OriginalID = $this->ID;
      //$this->ID         = (stripos($this->ID, "[repeater:repeatid]") === FALSE) ? $this->ID . "_" . $this->RepeatObjectID : $this->ID;
      $this->ID         = $this->ID . "_" . $this->RepeatObjectID;

      //foreach($this->Objects as $childobject) {
      //  $childobject->RepeatObjectID = $this->RepeatObjectID;
      //  $childobject->initRepeatIDs();
      //}
    }
    */

    public function setGUID() {
      $this->GUID = sb\uuid();                  //random getal zodat elk object een uniek ID heeft
    }


    public function setHashID() {
      $this->HashID = hash("md5", $this->ID);
    }


    public function run() {
      $this->Runned = true;
    }


    public function init($initObject=null) {
      $initObject = (!is_null($initObject)) ? $initObject : $this->Init;

      if ($initObject) {
        //init properties
        if ($this->InitProperties
        && !$this->InitedProperties) {
          $this->initProperties();
        }

        //move to other parent?
        foreach ($this->Objects as $key=>$object) {
          if ($object->ParentID) {
            $rootobject  = $this->getRootObject();
            $parentobject = $rootobject->getObjectByID($object->ParentID);

            if ($parentobject) {
              $this->moveObject($object, $parentobject);
            }
          }
        }

        $this->Inited=true;
      }
    }


    public function initProperties() {
      $this->OriginalID       = $this->ID;

      $this->InitedProperties = true;
    }


    /*
    public function initPropertiesObjects($recursive=true, $init_childs_first=false) {
      foreach($this->Objects as $childobject) {
        if ($childobject instanceof SB_Object) {
          if (!$init_childs_first) {
            $childobject->initProperties();
          }

          if ($recursive) {
           $childobject->initPropertiesObjects(true, $init_childs_first);
          }

          if ($init_childs_first) {
            $childobject->initProperties();
          }
        }
      }
    }
    */

    public function initObjects($initObjects=null, $initRecursive=null, $initChildsFirst=null) {
      $initObjects      = (!is_null($initObjects))      ? $initObjects : $this->InitObjects;
      $initRecursive    = (!is_null($initRecursive))    ? $initRecursive : $this->InitRecursive;
      $initChildsFirst  = (!is_null($initChildsFirst))  ? $initChildsFirst : $this->InitChildsFirst;

      if ($initObjects) {
        foreach($this->Objects as $childkey=>$childobject) {
          if ($childobject instanceof SB_Object) {
            if (!$initChildsFirst) {
              $childobject->init();
            }

            if ($initRecursive) {
              $childobject->initObjects($initObjects, $initRecursive, $initChildsFirst);
            }

            if ($initChildsFirst) {
              $childobject->init();
            }
          }
        }
      }
    }


    public function initObjectsProperties($initObjectsProperties=null, $initRecursive=null, $initChildsFirst=null) {
      $initObjectsProperties  = (!is_null($initObjectsProperties))  ? $initObjectsProperties : $this->InitObjectsProperties;
      $initRecursive          = (!is_null($initRecursive))          ? $initRecursive : $this->InitRecursive;
      $initChildsFirst        = (!is_null($initChildsFirst))        ? $initChildsFirst : $this->InitChildsFirst;

      if ($initObjectsProperties) {
        foreach($this->Objects as $childkey=>$childobject) {
          if ($childobject instanceof SB_Object) {
            if (!$initChildsFirst) {
              $childobject->initProperties();
            }

            if ($initRecursive) {
              $childobject->initObjectsProperties($initObjectsProperties, $initRecursive, $initChildsFirst);
            }

            if ($initChildsFirst) {
              $childobject->initProperties();
            }
          }
        }
      }
    }


    public function runObjects($runObjects=null, $runRecursive=null, $runChildsFirst=null) {
      $runObjects      = (!is_null($runObjects))      ? $runObjects : $this->RunObjects;
      $runRecursive    = (!is_null($runRecursive))    ? $runRecursive : $this->RunRecursive;
      $runChildsFirst  = (!is_null($runChildsFirst))  ? $runChildsFirst : $this->RunChildsFirst;

      if ($runObjects) {
        foreach($this->Objects as $childobject) {
          if ($childobject instanceof SB_Object) {
            if (!$runChildsFirst) {
              $childobject->run();
            }

            if ($runRecursive) {
             $childobject->runObjects($runObjects, $runRecursive, $runChildsFirst);
            }

            if ($runChildsFirst) {
              $childobject->run();
            }
          }
        }
      }
    }


    public function initCounterIDs($counter, $recursive=true){
      //$object->OriginalID = $object->ID;
      $this->ID         = $this->ID . "_" . $counter;

      if ($recursive) {
        foreach($this->Objects as $childobject) {
          $childobject->initCounterIDs($counter, $recursive);
        }
      }
    }

    public function initCounterIDsByType($objecttype, $counter, $recursive=true){
      if ($this instanceof $objecttype) {
        $this->ID         = $this->ID . "_" . $counter;
      }

      if ($recursive) {
        foreach($this->Objects as $childobject) {
          $childobject->initCounterIDsByType($objecttype, $counter, $recursive);
        }
      }
    }

    public function createChildObjects($recursive=true) {
      foreach($this->Objects as $childobject) {
        if ($recursive) {
          if (method_exists($childobject, "createChildObjects")) {
            $childobject->createChildObjects(true);
          }
        }
      }
    }

    public function finalize() {
    }

    public function finalizeObjects($recursive=true, $finalize_childs_first=false) {
      foreach($this->Objects as $childobject) {
        if ($childobject instanceof SB_Object) {
          if (!$finalize_childs_first) {
            $childobject->finalize();
          }
          if ($recursive) {
            $childobject->finalizeObjects(true, $finalize_childs_first);
          }
          if ($finalize_childs_first) {
            $childobject->finalize();
          }
        }
      }
    }


    public function getRootObject($classname='', $root=false) {
      //indien $classname aanwezig dan pakt ie de EERST bovenliggende ancestor van dat type
      if ($this->RootObject
      &&  $classname=='') {
        return $this->RootObject;
      } else {
        $parentobject = $this->ParentObject;

        if ($parentobject) {
          if ($parentobject instanceof $classname) {
            if ($root) {
              return $parentobject->getRootObject($classname, $root);
            } else {
              return $parentobject;
            }
          } else {
            return $parentobject->getRootObject($classname, $root);
          }
        } else {
          if ($classname=='') {
            //eindpunt is de root
            return $this;
          } else {
            //classname niet gevonden dus false
            return false;
          }
        }
      }
    }


    public function getParentObject() {
      if (is_object($this->ParentObject)) {
        return $this->ParentObject;
      } else {
        return false;
      }
    }


    public function addObject($object) {
      $object->ParentObject = &$this;
      $object->ObjectIndex = count($this->Objects);
      $object->ObjectLevel = $object->ParentObject->ObjectLevel + 1;

      $this->Objects[$object->ObjectIndex] = $object;
    }


    public function clearObjects() {
      array_splice($this->Objects, 0);
//      $this->Objects = array();
    }


    public function insertObject($object, $position) {
      $object->ParentObject = &$this;
      $object->ObjectIndex = $position;

      //TODO: bij de rest van de objects de ObjectIndex veranderen !!
      for($i=$position; $i < count($this->Objects);$i++) {
        if ($this->Objects[$i]) {
          $this->Objects[$i]->ObjectIndex++;
        }
      }

      //insert object in Objects array
      array_insert_array($this->Objects, $position, array($object));
    }


    public function moveObject($object, $parentobject, $position=0) {
      //foreach($this->ParentObject->Objects as $key=>$object) {
      //  if ($object->ID == $this->ID) {
      //    $objectindex = $key;
      //  }
      //}
      $parentobject->insertObject($object, $position);

      $this->deleteObject($object);
      $object->ParentObject = $parentobject;
      //unset($this->ParentObject->Objects[$objectindex]);

      $parentobject->sortObjects();
    }


    public function deleteObject($object) {
      $object->ParentObject = null;

      foreach($this->Objects as $key=>$childobject) {
        if ($object->ID == $childobject->ID) {
          $objectindex = $key;
        }
      }

      //array_splice($this->Objects, $objectindex, 1);
      unset($this->Objects[$objectindex]);
      //to do : bij de rest van de objects de ObjectIndex veranderen !!
    }


    public function removeObject($object) {
      $object->ParentObject = null;

      array_splice($this->Objects, $objectindex, 1);
      //to do : bij de rest van de objects de ObjectIndex veranderen !!
    }


    public function replaceObject($sourceobject, $destobject, $recursive=true) {
      foreach($this->Objects as $childindex=>$childobject) {
        if ($recursive) {
          if (method_exists($childobject, "replaceObject")) {
            $childobject->replaceObject($sourceobject, $destobject, $recursive);
          } else {
            //ERROR : stdClass ?!??!?!?  waar komen die vandaan ?!?!
          }
        }

        if (comparetext($childobject->ID, $sourceobject->ID)) {
          $this->Objects[$childindex] = $destobject;
        }
      }
    }


    public function hasObjects() {
      if (count($this->Objects)>0) {
        return true;
      } else {
        return false;
      }
    }


    public function sortObjects($property = 'SortID', $reverse = false) {
      if ($reverse) {
        $sortorder = 'DESC';
      } else {
        $sortorder = 'ASC';
      }

      if ($this->hasObjects()) {
        $arraysorter = new ArraySorter($this->Objects, array($property => $sortorder));
      }
    }


    public function getID() {
      return $this->ID;
    }


    public function getContainerID($object=false) {
      if (!$object) {
        $object = $this;
      }

      if (isnotempty($object->ContainerID)) {
        //object heeft container
        $returnvalue = $object->ContainerID;
      } else {
        //object heeft GEEN container, dan parent onderzoeken
        if ($object->ParentObject) {
          if ($object instanceof SB_Object) {
            $returnvalue = $object->getContainerID($object->ParentObject);
          } else {
            $returnvalue = false;
          }
        } else {
          $returnvalue = false;
        }
      }

      return $returnvalue;
    }


    public function getObject($id, $recursive=true) {
      return $this->getObjectByID($id, $recursive);
    }


    public function getObjectByID($id, $recursive=true) {
      $returnobject = false;

      //DIT DOEN WE NIET
      /*
        $rootobject = $this->getRootObject();
        if ($rootobject) {
          $prefix = $rootobject->Prefix;

        }
      */

      foreach($this->Objects as $childobject) {
        $object = false;

        if ($recursive) {
          if (method_exists($childobject, "getObject")) {
            $object = $childobject->getObject($id, true);
          } else {
            //ERROR : stdClass ?!??!?!?  waar komen die vandaan ?!?!
          }

          if (is_object($object)) {
            $returnobject = $object;
          }
        }

        if (comparetext($childobject->ID, $id)) {
          $returnobject = $childobject;
        }
      }

      return $returnobject;
    }


    public function getObjectByGUID($guid, $recursive=true) {
      foreach($this->Objects as $childobject) {
        if ($recursive) {
          if (method_exists($childobject, "getObjectByGUID")) {
            $object = $childobject->getObjectByGUID($guid, true);
          }
          if ($object) {
            $returnobject = $object;
          }
        }
        if (comparetext($childobject->GUID, $guid)) {
          $returnobject = $childobject;
        }
      }
      return $returnobject;
    }


    public function getObjectsByType($objecttype, $recursive=true, $childsfirst=true, $objects =array()) {
      //retourneert ALLE objecten van bepaald type
      $returnobjects = $objects;

      $objecttypes = sb\trim_explode(",", $objecttype);
      foreach($objecttypes as $objecttype) {
        foreach($this->Objects as $childobject) {
          if (!$childsfirst) {
            if ($childobject instanceof $objecttype
            ||  $objecttype == "*") {
              $returnobjects[] = $childobject;
            }
          }

          if ($recursive) {
            if (method_exists($childobject,"getObjectsByType")) {
              $returnobjects = $childobject->getObjectsByType($objecttype, $recursive, $childsfirst, $returnobjects);
            } else {
              //echo $childobject->ID . " : " . get_class($childobject);
            }
          }

          if ($childsfirst) {
            if ($childobject instanceof $objecttype
            ||  $objecttype == "*") {
              $returnobjects[] = $childobject;
            }
          }
        }
      }

      return $returnobjects;
    }


    public function getObjectByClass($cssclass, $recursive=true) {
      //retourneert EERSTE object van bepaald type
      $returnobjects = $this->getObjectsByClass($cssclass, $recursive);

      if (count($returnobjects)) {
        return $returnobjects[0];
      } else {
        return false;
      }
    }


    public function getObjectsByClass($cssclass, $recursive=true, $childsfirst=true, $objects =array()) {
      //retourneert ALLE objecten van bepaald type
      $returnobjects = $objects;

      $cssclasses = sb\trim_explode(",", $cssclass);
      foreach($cssclasses as $cssclass) {
        foreach($this->Objects as $childobject) {
          if (property_exists($childobject, "Class")) {
            $class  = $childobject->Class;

            if (!$childsfirst) {
              if (contains($class, $cssclass)
              ||  $cssclass == "*") {
                $returnobjects[] = $childobject;
              }
            }

            if ($recursive) {
              if (method_exists($childobject,"getObjectsByClass")) {
                $returnobjects = $childobject->getObjectsByClass($cssclass, $recursive, $childsfirst, $returnobjects);
              } else {
                //echo $childobject->ID . " : " . get_class($childobject);
              }
            }

            if ($childsfirst) {
              if (contains($class, $cssclass)
              ||  $cssclass == "*") {
                $returnobjects[] = $childobject;
              }
            }
          }
        }
      }

      return $returnobjects;
    }


    public function getObjectByType($objecttype, $recursive=true) {
      //retourneert EERSTE object van bepaald type
      $returnobjects = $this->getObjectsByType($objecttype, $recursive);

      if (count($returnobjects)) {
        return $returnobjects[0];
      } else {
        return false;
      }
    }


    public function getObjectsByProperty($propertyname, $recursive=true, $objects=array()) {
      $returnobjects = $objects;

      $propertynames = sb\trim_explode(",", $propertyname);
      foreach($propertynames as $propertyname) {
        foreach($this->Objects as $childobject) {
          if ($recursive) {
            if (method_exists($childobject,"getObjectsByProperty")) {
              $returnobjects = $childobject->getObjectsByProperty($propertyname, true, $returnobjects);
            } else {
              //echo $childobject->ID . " : " . get_class($childobject);
            }
          }

          if ($childobject->hasProperty($propertyname)) {
            $returnobjects[] = $childobject;
          }
        }
      }

      return $returnobjects;
    }


    public function getObjectsByPropertyValue($propertyname, $value, $recursive=true, $objects=array()) {
      $returnobjects = $objects;

      $propertynames = sb\trim_explode(",", $propertyname);
      foreach($propertynames as $propertyname) {
        foreach($this->Objects as $childobject) {
          if ($recursive) {
            if (method_exists($childobject,"getObjectsByPropertyValue")) {
              $returnobjects = $childobject->getObjectsByPropertyValue($propertyname, $value, true, $returnobjects);
            } else {
              //echo $childobject->ID . " : " . get_class($childobject);
            }
          }

          if ($childobject->hasProperty($propertyname)) {
            if ($childobject->$propertyname == $value) {
              $returnobjects[] = $childobject;
            }
          }
        }
      }

      return $returnobjects;
    }


    public function getObjectsByTypeAndPropertyValue($objecttype, $propertyname, $value, $recursive=true, $childsfirst=true) {
      $returnobjects        = [];

      $objectsbytype  = $this->getObjectsByType($objecttype, $recursive, $childsfirst);
      foreach ($objectsbytype as $object) {
        if ($object->hasProperty($propertyname)) {
          if ($object->$propertyname == $value) {
            $returnobjects[] = $object;
          }
        }
      }

      return $returnobjects;
    }


    public function getObjectSize($includechildobjects=false) {
      $old = memory_get_usage();
      $dummy = rec_copy($this, $includechildobjects);
      $mem = memory_get_usage();

      $size = abs($mem - $old);

      return $size;
    }

    /*
    public function getObjectsCount($object, $recursive=true, $objectcount=0) {
      //TODO: Werkt dit nog?? $objectttype wordt netgens gedeclareerd
      $returnvalue = $objectcount;

      if ($object->hasObjects()) {
        foreach($object->Objects as $childobject) {
          if ($recursive) {
            if (method_exists($childobject,"getObjectsCount")) {
              $returnvalue += $childobject->getObjectsCount($objecttype, true, $objectcount);
            }
          }
          $returnvalue += count($childobject->Objects);
        }
      }

      return $returnvalue;
    }
    */

    public function getIndent($difference=0) {
      $returnindent = "";

      //indent
      if ($this->Indent) {
        if (is_empty($this->IndentString)) {
          //indentation by objectlevel (default)
          for ($i=0 ; $i < ($this->ObjectLevel + $difference); $i++) {
            $returnindent .= $this->IndentChar;
          }
        } else {
          //indentation by string
          $returnindent = $this->IndentString;
        }
      }

      return $returnindent;
    }

    public function hasObject($id, $recursive=true) {
      return $this->hasObjectOfID($id, $recursive);
    }

    public function hasObjectOfID($id, $recursive=true) {
      foreach($this->Objects as $childobject) {
        if ($recursive) {
          if (method_exists($childobject, "hasObject")) {
            $hasobject = $childobject->hasObject($id, true);
          } else {
            //ERROR : stdClass ?!??!?!?  waar komen die vandaan ?!?!
          }
          $returnvalue = $hasobject;
        }

        if (comparetext($childobject->ID, $id)) {
          $returnvalue = true;
        }
      }
      return $returnvalue;
    }

    public function hasObjectsOfType($objecttype, $recursive=true, $return=false) {
      $returnvalue = $return;

      if (! $returnvalue) {
        foreach($this->Objects as $childobject) {
          if ($recursive) {
            $returnvalue = $childobject->hasObjectsOfType($objecttype, true, $returnvalue);
          }
        }

        if ($this instanceof $objecttype) {
          $returnvalue = true;
        }
      }

      return $returnvalue;
    }

    public function hasObjectsWithProperty($propertyname, $recursive=true, $return=false) {
      $returnvalue = $return;

      if (! $returnvalue) {
        foreach($this->Objects as $childobject) {
          if ($recursive) {
            $returnvalue = $childobject->hasObjectsWithProperty($propertyname, true, $returnvalue);
          }
        }

        if ($this->hasProperty($propertyname)) {
          $returnvalue = true;
        }
      }

      return $returnvalue;
    }

    public function hasObjectsWithPropertyValue($propertyname, $value, $recursive=true, $return=false) {
      $returnvalue = $return;

      if (! $returnvalue) {
        foreach($this->Objects as $childobject) {
          if ($recursive) {
            $returnvalue = $childobject->hasObjectsWithPropertyValue($propertyname, $value, true, $returnvalue);
          }
        }

        if ($this->hasProperty($propertyname)) {
          if ($this->$propertyname == $value) {
            $returnvalue = true;
          }
        }
      }

      return $returnvalue;
    }

    public function hasProperty($propertyname) {
      //TODO: moet ook werken voor sub-properties background-color etc.
      $properties = get_object_vars($this);
      foreach($properties as $property=>$value) {
        if (strtolower(trim($property))==strtolower(trim($propertyname))){
          return true;
        }
      }
      return false;
    }


    public function copyProperties($object) {
      $properties = get_object_vars($this);
      $class = get_class($this);
      $newclass = get_class($object);

      $returnvalue = false;
      //if ($object instanceof $class) {
        foreach($properties as $property=>$value) {
          $object->$property = $value;
        }
        $returnvalue = true;
      //}

      return $returnvalue;
    }


    public function setProperty($key, $value) {
      if (contains($key, '-')) {
        $key = str_ireplace('-','.', $key);
      }

      $properties         = preg_split('/\./', $key);
      $changeprop         = $this;
      $textprop           = '';
      $numberofproperties = count($properties);
      $count              = 0;

      foreach($properties as $property) {
        $count++;
        if ($property) {
          $variables = get_class_vars(get_class($changeprop));
          if ($variables) {
            $found = false;

            $propertyarray = array();
            foreach ($variables as $variable_name => $variable_value) {
              $propertyarray[strtolower(trim($variable_name))] = trim($variable_name);
            }

            //$propertyname = (in_array(strtolower(trim($property)), $propertyarray)) ? $propertyarray[strtolower(trim($property))] : false;
            if (isset($propertyarray[strtolower(trim($property))])) {
              $propertyname = $propertyarray[strtolower(trim($property))];
            } else {
              $propertyname = false;
            }

            if ($propertyname) {
              $found = true;
              if ($count==$numberofproperties) {
                if (is_bool($changeprop->$propertyname)) {
                  if (is_bool($value)) {
                    $changeprop->$propertyname = $value;
                  } else if (is_string($value)) {
                    $changeprop->$propertyname = sb\boolOrEval(strtolower($value), TRUE);
                  }
                } elseif (is_object($changeprop->$propertyname)) {
                  if (property_exists($changeprop->$propertyname, 'Value')) {
                    $changeprop->$propertyname->Value = $value; // trim($value);
                  }
                } else {
                  $changeprop->$propertyname = $value; // trim($value);
                }
              } else {
                $changeprop = $changeprop->$propertyname;
              }
            }
          }
        }
      }
      return $propertyname;
    }
/*
      public function setProperties($propertyarray) {
      $object = $this;

      $objectproperties = get_class_vars(get_class($object));
      $objectpropertyarray = array();
      foreach ($objectproperties as $objectproperty_name => $objectproperty_value) {
        $objectpropertyarray[strtolower(trim($objectproperty_name))] = trim($objectproperty_name);
      }

      foreach($propertyarray as $key=>$value) {
        $propertyname = $objectpropertyarray[strtolower(trim($key))];
        if ($propertyname) {
          if (is_bool($object->$propertyname)) {
            $object->$propertyname = sb\boolOrEval($value);
          } else {
            if (is_object($object->$propertyname)) {
              if (property_exists($object->$propertyname, 'Value')) {
                $object->$propertyname->Value = $value; // trim($value);
              }
            } else {
              $object->$propertyname = $value;
            }
          }
        }
      }
    }
    */


    public function getProperty($propertyname) {
      $propertynames = array_keys(get_object_vars($this));
      foreach($propertynames as $propname) {
        if (strcasecmp($propertyname, $propname) == 0) {
          return $this->$propname;
        }
      }
      return NULL;
    }


    public function getPropertyName($propertyname, $default=NULL) {
      $propertynames = array_keys(get_object_vars($this));
      foreach($propertynames as $propname) {
        if (strcasecmp($propertyname, $propname) == 0) {
          return $propname;
        }
      }
      return $default;
    }


    public function getPropertyValue($propertyvalue, $quote=false) {
      //init variables
      $komma        = "";
      $arraystring  = "";
      $objectstring = "";
      $quote        = ($quote === true) ? "'" : $quote;

      if (is_string($propertyvalue)) {
        if (is_objectic($propertyvalue)
        ||  is_arrayic($propertyvalue)) {
          return str_replace('"', "'", $propertyvalue);
        } else if (is_numeric($propertyvalue)
               ||  is_boolic($propertyvalue)) {
          return $propertyvalue;
        } else if (is_nullic($propertyvalue)) {
          return "null";
        } else {
          return $quote . addslashes($propertyvalue) . $quote;
        }
      } else if (is_number($propertyvalue)) {
        return $propertyvalue;
      } else if (is_bool($propertyvalue)) {
        return booltostr($propertyvalue);
      } else if (is_null($propertyvalue)) {
        return "null";
      } else if (is_array($propertyvalue)) {
        $arraystring .= "[";
        foreach ($propertyvalue as $arraykey=>$arrayvalue) {
          $arraystring .= $komma . $arraykey . ":" . $this->getPropertyValue($arrayvalue, $quote);
          $komma = ",";
        }
        $arraystring .= "]";

        return $arraystring;
      } else if (is_object($propertyvalue)) {
        $objectstring .= "{";
        foreach ($propertyvalue as $propkey=>$propvalue) {
          $objectstring .= $komma . $propkey . ":" . $this->getPropertyValue($propvalue, $quote);
          $komma = ",";
        }
        $objectstring .= "}";

        return $objectstring;
      }
    }

/*
    public function setPropertyJSON($json, $object=false) {
      if (!$object) {
        $object = $this;
      }

      return json_setobject($object, $json);
    }
*/

    /*
class SB_TESTOBJECT
extends SB_Object {
  public $Test1;
  public $Test2;
  public $Test3;
  public $Test4 = [];
  public $Test5 = [];
  public $Test5_classname = "SB_TESTOBJECT";
}

$jsonstr='{
           "propA":123,
           "propB":"noot",

           "propC": {
             "subProp1":"wim",
             "subProp2":345
            },

					 "propD": [1,2,3,1001],
           "propE":[
               {
                 "test1":"AAA",
                 "test2":"BBB"
               },{
                 "test2":"XXX",
                 "test3":"YYY"
               },{
                 "test3":"YYY",
                 "test4": [1,2,3],
                 "test5": [
                   {
                     "test1":"AAAAAA",
                     "test2":"BBBBBB"
                   },{
                     "test2":"XXXXXX",
                     "test3":"YYYYYY"
                   },{
                     "test3":"YYYYYY",
                     "test4": [4,5,6]
                   }
                 ]
               }
             ]
          }
         ';

//$jsonstr='{"test":123, "aap":"noot", "subobj":{"objecttype":"SB_Object", "mies":"wim", "zus":345}, "arr":[1,2,3,1001], "objarr":[{"objecttype":"SB_TESTOBJECT", "aaa":"AAA","bbb":"BBB"},{"objecttype":"SB_TESTOBJECT", "aaa":"XXX", "yyy":"YYY"}]}';
//$json = json_decode($jsonstr);

$obj=new SB_Object();

$obj->ID = "Test";
$obj->PropA = "TESTTEST";
$obj->PropB = "AAPAAP";

$obj->PropC =new SB_Object();
$obj->PropC->SubProp1 = "aaaaaa";
$obj->PropC->SubProp2 = "bbbbbb";

$obj->PropD = [2,3];

$obj->PropE = [];
$obj->PropE_classname = "SB_TESTOBJECT";

$obj->setPropertyJSON($jsonstr);


pb($obj->PropA);
//print_r($obj->PropC);
pb($obj->PropC->SubProp1);
print_r($obj->PropD);
pb();
pb($obj->PropE[2]->Test3);
print_r($obj->PropE[2]->Test5[0]->Test1);
//pb($obj->PropE[0]["aaa"]);
//print_r($obj->PropE[0]);

//print_r($obj);

*/


    public function setProperties($propertyarray) {
      $object = $this;

      $objectproperties = get_class_vars(get_class($object));
      $objectpropertyarray = array();
      foreach ($objectproperties as $objectproperty_name => $objectproperty_value) {
        $objectpropertyarray[strtolower(trim($objectproperty_name))] = trim($objectproperty_name);
      }

      foreach($propertyarray as $key=>$value) {
        //ook geschikt maken voor separator '-' (zoals bij CSS)
        if (contains($key, '-')) {
          $key = str_ireplace('-','.', $key);
        }

        $properties = explode('.', $key);
        $count = 0;
        $propobject = $this;
        foreach($properties as $property) {
          if ($count==0) {
            $propertyname = $objectpropertyarray[strtolower(trim($property))];
          } else {
            $subobjectproperties = get_class_vars(get_class($propobject));
            foreach ($subobjectproperties as $objectproperty_name => $objectproperty_value) {
              if (strtolower(trim($objectproperty_name))==strtolower(trim($property))) {
                $propertyname = trim($objectproperty_name);
              }
            }
          }

          if ($propertyname) {
            if (is_object($propobject->$propertyname)) {
              $propobject = $propobject->$propertyname;
              if ($count==count($properties)-1) {  #alleen als er verder een properties achter komen!
                if (property_exists($propobject, 'Value')) {
                  $propobject->Value = $value; // trim($value);
                }
              }
            }
          }

          $count++;

        }

        if ($propertyname) {
          if (is_bool($propobject->$propertyname)) {
            $propobject->$propertyname = sb\boolOrEval($value, TRUE);
          } else {
            $propobject->$propertyname = $value;
          }
        }
      }
    }


    public function getReplaceProperties($propertyarray) {
      $object = $this;
      $propertyname = "";
      $replaceproperties = array();

      $objectclass = get_class($object);

      //HTMLObject
      if ($object instanceof SB_HTMLObject) {
        //voeg Attributes toe aan replaceproperty array
        foreach($object->Attributes as $attribute_name=>&$attribute_value) {
//        $haken = substr_count($attribute_value, "[");
          $haken = multi_substr_count($attribute_value, array("[", "{") );

          if ($haken>0) {
            $replaceproperty = new SB_ReplaceProperty();
            $replaceproperty->Count         = $haken;
            $replaceproperty->ObjectID      = $object->ID;
            $replaceproperty->ObjectPointer = &$object;
            $replaceproperty->Pointer       = &$attribute_value;
            $replaceproperty->Property      = $attribute_name;
            $replaceproperty->String        = $attribute_value;

            $replaceproperties[] = $replaceproperty;
          }
        }
      }

      //TODO: titel verzinnen
      $objectproperties = get_class_vars($objectclass);

      $objectpropertyarray = array();
      foreach ($objectproperties as $objectproperty_name => $objectproperty_value) {
        $objectpropertyarray[strtolower(trim($objectproperty_name))] = trim($objectproperty_name);
      }

      //TODO: titel verzinnen
      foreach($propertyarray as $key=>$value) {
        $propcount = 0;
        $propobject = $this;

        //properties met '.' of '-'
        //(uitzondering : attributes die beginnen met 'data-')
        if (!startswith($key, "data-")) {
          //ook geschikt maken voor separator '-' (zoals bij CSS)
          if (contains($key, '-')) {
            $key = str_ireplace('-','.', $key);
          }

          $properties = explode('.', $key);
          foreach($properties as $property) {
            //kijk naar MappedProperties voor eventuele andere propertynames dan in de template
            foreach($object->MappedProperties as $key=>$mappedproperty) {
              if (comparetext($property, $key)) {
                $property = $mappedproperty;
                break;
              }
            }

            if ($propcount==0) {
              if (isset($objectpropertyarray[strtolower(trim($property))])) {
                $propertyname = $objectpropertyarray[strtolower(trim($property))];
              } else {
                $propertyname = '';
              }
            } else {
              $subobjectproperties = get_class_vars(get_class($propobject));
              foreach ($subobjectproperties as $objectproperty_name => $objectproperty_value) {
                if (strtolower(trim($objectproperty_name))==strtolower(trim($property))) {
                  $propertyname = trim($objectproperty_name);
                }
              }
            }

            if ($propertyname) {
              if (is_object($propobject->$propertyname)) {
                $propobject = $propobject->$propertyname;
                if ($propcount==count($properties)-1) {  #alleen als er verder geen properties achter komen!
                  if (property_exists($propobject, 'Value')) {
                    $propobject->Value = $value; // trim($value);
  //                  $haken = substr_count($value, "[");
                    $haken = multi_substr_count($objectproperty_value, array("[", "{") );

                    if ($haken>0) {
                      $replaceproperty = new SB_ReplaceProperty();
                      $replaceproperty->ObjectID      = $propobject->ID;
                      $replaceproperty->ObjectPointer = &$object;
                      $replaceproperty->Pointer       = &$propobject->Value;
                      $replaceproperty->Property      = $propertyname;
                      $replaceproperty->String        = $value;
                      $replaceproperty->Count         = $propcount;

                      $replaceproperties[]  = $replaceproperty;
                    }
                  }
                }
              }
            }

            $propcount++;
          }
        }

        if ($propertyname) {
          //ZETTEN VAN PROPERTY
          if (is_bool($propobject->$propertyname)) {
            $propobject->$propertyname = sb\boolOrEval($value, TRUE, $propobject->ID, $propertyname);
          } else {
            $propobject->$propertyname = $value;
          }

//          $haken = substr_count($value, $replacestring);
          $haken = multi_substr_count($value, array("[", "{") );

          if ($haken>0) {
            $replaceproperty = new SB_ReplaceProperty();
            if ($object instanceof SB_TextObject) {
              //TEXTOBJECT
              $parentobject = $propobject->getParentObject();

              if (is_object($parentobject)) {
                $replaceproperty->ObjectID      = $parentobject->ID ;
                $replaceproperty->ObjectPointer = &$parentobject;
                $replaceproperty->Property      = "";
              } else {
                $replaceproperty->ObjectID      = $propobject->ID;
                $replaceproperty->ObjectPointer = &$object;
                $replaceproperty->Property      = "";
              }
            } else {
              //OVERIGE OBJECTEN
              $replaceproperty->ObjectID      = $propobject->ID;
              $replaceproperty->ObjectPointer = &$object;
              $replaceproperty->Property      = $propertyname;
            }

            $replaceproperty->Pointer       = &$propobject->$propertyname;
            $replaceproperty->String        = $value;
            $replaceproperty->Count         = $haken;

            $replaceproperties[] = $replaceproperty;
          }

        }
      }

      return $replaceproperties;
    }


    public function replaceproperties_commonByRecord($record, $replacetag  = "[%", $recursive=true) {
      if ($this instanceof SB_Object) {
        if ($this instanceof SB_HTMLObject) {
          foreach($this->Attributes as $attribute_name=>$attribute_value) {
            $search     = get_string_between_rechtehakenprocent($attribute_value);
            $searchfield= get_string_between($search, $replacetag, "]");
            $value      = $record[$searchfield];

            $this->Attributes[$attribute_name] = str_ireplace($search, $value, $attribute_value);
          }
        }

        foreach ($this as $property_name => $property_value) {
          if (is_string($this->$property_name)
          &&  $property_name != "HTMLText"
          &&  $property_name != "CleanText"
          &&  $property_name != "Output"
          &&  $property_name != "XML"
          &&  $property_name != "ValidXML") {

            if (stripos($property_value, $replacetag) !== false) {
              $search     = get_string_between_rechtehakenprocent($property_value);
              $searchfield= get_string_between($search, $replacetag, "]");
              $value      = $record[$searchfield];

              $this->$property_name = str_ireplace($search, $value, $property_value);
            }
          }

          if ($this->$property_name instanceof SB_Object) {
            if ($property_name != 'ParentObject') {
              $this->$property_name->replaceproperties_commonByRecord($record, $replacetag, $recursive);
            }
          }
        }
        if ($recursive) {
          foreach($this->Objects as $childobject) {
           $childobject->replaceproperties_commonByRecord($record, $replacetag, $recursive);
          }
        }
      }
    }


    public function replaceproperties_common($search, $replace, $recursive=true, $templates=false, $regex=false) {
      //timing
      $timerstart = timer_start();

      if ($this instanceof SB_Object) {
        //vervang eol in $replace
        if (is_string($replace)) {
          $replace  = str_ireplace("\r\n", htmlspecialchars("<br />"), $replace);
          $replace  = str_ireplace("\r", htmlspecialchars("<br />"), $replace);
          $replace  = str_ireplace("\n", htmlspecialchars("<br />"), $replace);
          $replace  = str_ireplace("\"", "", $replace);
        } else if (is_array($replace)) {
          foreach ($replace as $key=>$rep) {
            $rep    = str_ireplace("\r\n", htmlspecialchars("<br />"), $rep);
            $rep    = str_ireplace("\r", htmlspecialchars("<br />"), $rep);
            $rep    = str_ireplace("\n", htmlspecialchars("<br />"), $rep);
            $rep    = str_ireplace("\"", "", $rep);

            $replace[$key] = $rep;
          }
        }

        //vervang $search in attributes
        if(isset($this->Attributes)
        && $this->Attributes) {
          foreach($this->Attributes as $attributename=>$attributevalue) {
            if ($regex) {
              $this->Attributes[$attributename] = preg_replace($search, $replace, $attributevalue);
            } else {
              $this->Attributes[$attributename] = str_ireplace($search, $replace, $attributevalue);
            }
          }
        }

        //vervang $search in properties
        foreach ($this as $propertyname => $propertyvalue) {
          //property is string?
          if (is_string($this->$propertyname)) {
            if ($regex) {
              $this->$propertyname = preg_replace($search, $replace, $propertyvalue);
            } else {
              $this->$propertyname = str_ireplace($search, $replace, $propertyvalue);
            }
          }

          //property is array?
          if (is_array($this->$propertyname)
          //&& $property_name != "JavascriptFiles"
          //&& $property_name != "EvalJavascriptFiles"
          //&& $property_name != "CSSFiles"
          && $propertyname != "OriginalObjects"
          && $propertyname != "Objects") {
            foreach($this->$propertyname as $key=>$item) {
              $newvalue = $item;
              if (is_string($item)) {
                $newvalue = $item;
                if ($regex) {
                  $newvalue= preg_replace($search, $replace, $item);
                } else {
                  //if (stripos($item, $search) !== false) {
                  $newvalue = str_ireplace($search, $replace, $item);
                  //}
                }
                $this->{$propertyname}[$key] = $newvalue;
              }
            }
          }

          //property is object?
          if ($this->$propertyname instanceof SB_Object) {
            if ($propertyname != "ParentObject"
            &&  $propertyname != "RootObject") {
              //TODO: dit maakt het traag   (wordt gebruikt voor background.image enz
              $this->$propertyname->replaceproperties_common($search, $replace, $recursive, $templates, $regex, $this->Timing);
            }
          }
        }

        if ($recursive) {
          if ($this->Objects
          && is_array($this->Objects)
          && !is_empty($this->Objects)) {
            foreach($this->Objects as $childobject) {
              $childobject->replaceproperties_common($search, $replace, $recursive, $templates, $regex, $this->Timing);
            }
          }
        }
      }

      //timing
      if ($this->Timing) {
        fb_timer_end($timerstart, $this->TimingLimit,"SB_Object : replaceproperties_common");
      }
    }


    public function replaceconditions_common($search, $recursive=true, $regex=false) {
      //timing
      $timerstart = timer_start();

      //init variables
      $evaluatedvalue = "";


      if ($this instanceof SB_Object) {
        //vervang $search in attributes
        if(isset($this->Attributes)
        && $this->Attributes) {
          foreach($this->Attributes as $attributename=>$attributevalue) {
            if ($regex) {
              $matches = preg_match_all($search, $this->Attributes[$attributename]);
              if ($matches) {
                $this->Attributes[$attributename] = preg_replace($matches[0], eval($matches[1]), $attributevalue);
              }
            }
          }
        }

        //vervang $search in properties
        foreach ($this as $propertyname => $propertyvalue) {
          //property is string
          if (is_string($this->$propertyname)
          ||  is_null($this->$propertyname)) {
            if ($regex) {
              preg_match_all($search, $this->$propertyname, $matches);

              if (!is_empty($matches)
              &&  !is_empty($matches[0])
              &&  !is_empty($matches[1])) {
                $evaluation = 'if (' . $matches[1][0] . ') { $evaluatedvalue = "true"; } else { $evaluatedvalue = "false"; }';
                eval($evaluation);

                $this->$propertyname = str_replace($matches[0][0], $evaluatedvalue, $this->$propertyname);
              }
            }
          }

          //property is boolean
          if (is_bool($this->$propertyname)) {
            if ($regex) {
              preg_match_all($search, $this->$propertyname, $matches);
              if (!is_empty($matches)
              &&  !is_empty($matches[0])
              &&  !is_empty($matches[1])) {
                $evaluation = 'if (' . $matches[1][0] . ') { $evaluatedvalue = "true"; } else { $evaluatedvalue = "false"; }';
                eval($evaluation);
                $this->$propertyname = str_replace($matches[0][0], $evaluatedvalue, $this->$propertyname);
              }
            }
          }

          //property is array
          if (is_array($this->$propertyname)
          //&& $property_name != "JavascriptFiles"
          //&& $property_name != "EvalJavascriptFiles"
          //&& $property_name != "CSSFiles"
          && $propertyname != "Objects"
          && $propertyname != "TemplateAttributes") {
            foreach($this->$propertyname as $key=>$item) {
              $newvalue = $item;
              if (is_string($item)) {
                $newvalue = $item;
                if ($regex) {
                  preg_match_all($search, $newvalue, $matches);

                  if (!is_empty($matches)
                  &&  !is_empty($matches[0])
                  &&  !is_empty($matches[1])) {
                    $evaluation = 'if (' . $matches[1][0] . ') { $evaluatedvalue = "true"; } else { $evaluatedvalue = "false"; }';
                    eval($evaluation);

                    $newvalue = str_replace($matches[0][0], $evaluatedvalue, $newvalue);
                  }
                }
                $this->{$propertyname}[$key] = $newvalue;
              }
            }
          }

          //property is object
          //if ($this->$propertyname instanceof SB_Object) {
          //  if ($propertyname != "ParentObject"
          //  &&  $propertyname != "RootObject") {
          //    //TODO: dit maakt het traag   (wordt gebruikt voor background.image enz
          //    $this->$propertyname->replaceproperties_common($search, $replace, $recursive, $templates, $regex, $timing);
          //  }
          //}
        }


        if ($recursive) {
          if ($this->Objects
          && is_array($this->Objects)
          && !is_empty($this->Objects)) {
            foreach($this->Objects as $childobject) {
              $childobject->replaceconditions_common($search, $recursive, $regex);
            }
          }
        }
      }

      //timing
      if ($this->Timing) {
        fb_timer_end($timerstart, $this->TimingLimit,"SB_Object : replaceproperties_common");
      }
    }

    /*
    public function test() {
      echo "TESTESTEST";
    }
    */

    /*
    public function getPropertyArray($object) {
      $propertyarray  = array();
      $properties     = get_class_vars(get_class($object));

      foreach ($properties as $property_name => $property_value) {
        if (is_string($property_value)
        ||  is_
        $propertyarray[strtolower(trim($property_name))] = trim($property_name);
      }

      return $propertyarray;
    }
    */
  }

?>