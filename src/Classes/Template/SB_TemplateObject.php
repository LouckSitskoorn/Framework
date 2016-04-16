<?
  //NAMESPACE
  namespace SB\Classes\Template;

  //INCLUDES functions
  include_once __DIR__ . "/../Functions/string_functions.php";

  //USES
  use SB\Basic\SB_Object;
  use SB\Basic\Traits\tSB_XMLObject;
  use SB\Functions as sb;

  //GLOBALS
  $cssminfile           = __DIR__ . "/../../libraries/cssmin/cssmin-v3.0.1.php";
  $cssminfound          = false;
  $closurecompilerpath  = __DIR__ . "/../../libraries/closure-compiler";
  $closurecompilerfound = false;
  $scssphpfile          = __DIR__ . "/../../libraries/scssphp/scss.inc.php";
  $scssphpfound         = false;

  //INCLUDES  framework sb
  /*
  include_once __DIR__ . "/__sb_object.php";
  include_once __DIR__ . "/__sb_javascriptobject.php";
  include_once __DIR__ . "/__sb_headobject.php";

  //INCLUDES  framework traits
  include_once __DIR__ . "/../../traits/__sb_trait_xmlobject.php";
  */

  //INCLUDES  libraries
  if (file_exists($cssminfile)) {
    include_once $cssminfile;
    $cssminfound = true;
  }

  if (is_dir($closurecompilerpath)) {
    $closurecompilerfound = true;
  }

  if (file_exists($scssphpfile)) {
    include_once $scssphpfile;
    $scssphpfound = true;
  }

  //INTERFACES
  interface iSB_TemplateObject {
  }

  //CLASSES
  class SB_TemplateObject
  extends SB_Object
  implements iSB_TemplateObject {
    //uses
    use tSB_XMLObject;

    //public properties
    public $TemplateTagname;

    public $Attributes                    = array();        #output attributes
    public $AttributesHTML                = array();        #html attributes
    public $AttributesTemplate            = array();        #input attributes vanuit de template
    public $AttributesObject              = array();        #object attributes

    public $CustomAttributes              = false;
    public $MappedProperties              = array();

    public $IncludeObjects                = true;

    public $HTMLText;

    public $PropsetID;
    public $PropID;

    public $ObjectsetID;
    public $ObjectID;
    public $RepeatID;

    //public $SessionID                     = "[jit:phpsessid]";
    public $Dummy;
    public $Session;
    public $Request;
    public $Cookie;
    public $Params;
    public $Values;

    public $JSID;
    public $ContainerJSID;
    public $HashJSID;

    public $Hashing                         = false;
    public $HashingAlgorithm                = "crc32";

    public $IDPrefix                        = "";
    public $IDSuffix                        = "";
    public $AddIDPrefix                     = true;
    public $AddIDSuffix                     = true;
    public $AddContainerSuffix              = true;

    public $PreParse                        = false;
    public $PreLoad                         = false;

    public $HeadObjects                     = array();

    public $JavascriptFiles                 = array();
    public $JavascriptFilesMinified         = array();
    public $JavascriptGlobals               = array();
    public $InitialJavascriptBefore         = array(array());
    public $InitialJavascript               = array(array());
    public $InitialJavascriptAfter          = array(array());
    public $InitialJavascriptChildsFirst    = false;
    public $InitialJavascriptRecursive      = true;
    public $JavascriptBefore                = array(array());
    public $Javascript                      = array(array());
    public $JavascriptAfter                 = array(array());
    public $JavascriptChildsFirst           = false;
    public $JavascriptRecursive             = true;
    public $FinalJavascriptBefore           = array(array());
    public $FinalJavascript                 = array(array());
    public $FinalJavascriptAfter            = array(array());
    public $FinalJavascriptChildsFirst      = false;
    public $FinalJavascriptRecursive        = true;
    public $JavascriptFunctions             = array();
    public $JavascriptObjects               = array();

    public $CSSFiles                        = array();

    public $Links                           = array();

    public $JavascriptCompiled              = true;

    //InitXXXXX property geeft aan of XXXXX ge-init moet worden
    public $InitAttributes                  = true;
    public $InitedAttributes                = false;

    public $InitJavascriptFiles             = true;
    public $InitEvalJavascriptFiles         = true;
    public $InitJavascriptGlobals           = true;
    public $InitJavascriptObjects           = true;
    public $InitJavascriptFunctions         = true;
    public $InitInitialJavascript           = true;
    public $InitJavascript                  = true;
    public $InitFinalJavascript             = true;

    public $InitCSSFiles                    = true;

    public $InitLinks                       = true;

    //InitedXXXXX property geeft aan of XXXXX ge-init is
    public $InitedJavascriptFiles           = false;
    public $InitedEvalJavascriptFiles       = false;
    public $InitedJavascriptGlobals         = false;
    public $InitedJavascriptObjects         = false;
    public $InitedJavascriptFunctions       = false;
    public $InitedInitialJavascript         = false;
    public $InitedJavascript                = false;
    public $InitedFinalJavascript           = false;

    public $InitedCSSFiles                  = false;

    public $InitedLinks                     = false;

    //combine en compile properties
    public $CombineJavascriptFiles            = false;
    public $CombineJavascriptFilesPathClient  = "/temp/js";
    public $CombineJavascriptFilesPathServer  = "/temp/js";
    public $CompileJavascriptFiles            = false;
    public $CompileJavascriptFilesPathClient  = "/temp/js/compiled";
    public $CompileJavascriptFilesPathServer  = "/temp/js/compiled";
    public $CompilationLevelJavascriptFiles   = "SIMPLE_OPTIMIZATIONS"; //"WHITESPACE_ONLY";
    public $CombineCSSFiles                   = false;
    public $CombineCSSFilesHeaders            = true;
    public $CombineCSSFilesPathClient         = "/temp/css";
    public $CombineCSSFilesPathServer         = "/temp/css";
    public $CombineCSSFilesPrefix             = "";
    public $CombineCSSFilesPrefixPath         = "";
    public $CompileCSSFiles                   = false;
    public $CompileCSSFilesPathClient         = "/temp/css/compiled";
    public $CompileCSSFilesPathServer         = "/temp/css/compiled";

    //OutputXXXXX property geeft aan of XXXXX ge-output moet worden
    public $OutputAll                       = true;
    public $OutputAllString                 = "true";
    public $OutputAllFiles                  = true;
    public $OutputAllJavascript             = true;
    public $OutputAttributes                = true;
    public $OutputComments                  = true;
    public $OutputCSSFiles                  = true;
    public $OutputEvalCSSFiles              = true;
    public $OutputCSSFilesAsScript          = false;
    public $OutputCSSFilesChildsFirst       = false;
    public $OutputJavascriptFiles           = true;
    public $OutputEvalJavascriptFiles       = true;
    public $OutputJavascriptFilesAsScript   = false;
    public $OutputJavascriptFilesChildsFirst= true;
    public $OutputLinks                     = true;
    public $OutputInitialJavascript         = true;
    public $OutputInitialJavascriptAsFile   = true;
    public $OutputJavascript                = true;
    public $OutputJavascriptAsFile          = true;
    public $OutputFinalJavascript           = true;
    public $OutputFinalJavascriptAsFile     = true;
    public $OutputJavascriptFunctions       = true;
    public $OutputJavascriptFunctionsAsFile = true;
    public $OutputJavascriptGlobals         = true;
    public $OutputJavascriptGlobalsAsFile   = true;
    public $OutputJavascriptObjects         = true;
    public $OutputJavascriptObjectsAsFile   = true;
    public $OutputJavascriptSessionID       = true;
    public $OutputLoadedObjects             = true;
    public $OutputInit                      = false;
    public $OutputRun                       = false;

    public $OutputJavascriptContainerHeader = "(function outputJavascript() {";
    public $OutputJavascriptContainerFooter = "})()";

    public $Objecting                       = false;
    public $ObjectingJavascript             = false;
    public $Profiling                       = false;
    public $TimingOutput                    = false;
    public $TimingScript                    = false;

    public $Remove                          = false;
    public $RemoveItems                     = false;

    public $OutputPathPrefix                = "";

    public $Ready                           = false;

    //implementation
    public function __construct() {
      //inherited
      parent::__construct();

      //set JSID
      $this->setHashJSID();

      //default values
      $this->IDSuffix                       = "";
      $this->AttributesHTML                 = ["action", "class", "dir", "lang", "method", "rel", "src", "style", "target", "title", "href"];
    }


    public function addAttributeTemplate($name, $value) {
      if (!sb\is_empty($name)
      &&  !is_null($value)) {
        if (compare_in_array($value, $this->AttributesTemplate)) {
          $this->removeTemplateAttribute($value);
        }
        $this->AttributesTemplate[$name] = $value;
      }
    }


    public function removeTemplateAttribute($name) {
      foreach($this->AttributesTemplate as $key => $value) {
        if (comparetext($key, $name)) {
          if ($this->AttributesTemplate[$key]) {
            unset($this->AttributesTemplate[$key]);
          }
        }
      }
    }


    public function addAttributeObject($name, $object) {
      if (!sb\is_empty($name)
      &&  !sb\is_empty($object)) {
        $this->AttributesObject[$name] = $object;
      }
    }


    public function removeTemplateObject($name) {
      foreach($this->AttributesObject as $key => $object) {
        if (comparetext($key, $name)) {
          if ($this->AttributesObject[$key]) {
            unset($this->AttributesObject[$key]);
          }
        }
      }
    }


    public function addAttribute($name, $value, $insertbegin=false) {
      if (!sb\is_empty($name)
      &&  !is_null($value)) {
        //if (compare_in_array($name, $this->Attributes, true)) {
        //  $this->removeAttribute($name);
        //}
        if ($insertbegin) {
          array_unshift_assoc($this->Attributes, $name, $value);
        } else {
          $this->Attributes[$name] = $value;
        }
      }
    }


    public function insertAttribute($name, $value) {
      $this->addAttribute($name, $value, true);
    }


    public function removeAttribute($name) {
      foreach($this->Attributes as $key => $value) {
        if (comparetext($key, $name)) {
          if ($this->Attributes[$key]) {
            unset($this->Attributes[$key]);
          }
        }
      }
    }

    public function clearAttributes() {
      $this->Attributes   = array();
    }

    public function hasAttribute($name) {
      $found = false;

      foreach($this->Attributes as $key => $value) {
        if (comparetext($key, $name)) {
          $found=true;
        }
      }
      return $found;
    }


    public function getAttribute($name) {
      foreach($this->Attributes as $key => $value) {
        if (comparetext($key, $name)) {
          return $this->$key;
        }
      }
    }


    public function addParam($name, $value, $insertbegin=false) {
      if (!sb\is_empty($name)
      &&  !is_null($value)) {
        //$name = strtolower($name);

        if (is_array($this->Params)
        ||  is_null($this->Params)) {
          if ($insertbegin) {
            array_unshift_assoc($this->Params, $name, $value);
          } else {
            $this->Params[$name] = $value;
          }
        } else if (is_string($this->Params)) {
          if ($insertbegin) {
            $this->Params = $name . "=" . $value . (!sb\is_empty($this->Params) ? ";" . $this->Params : "");
          } else {
            $this->Params = (!sb\is_empty($this->Params) ? $this->Params .";" : "") . $name . "=" . $value ;
          }
        }
      }
    }


    public function initPropsets() {
      if (!sb\is_empty($this->PropsetID)) {
        $rootobject = $this->getRootObject();
        if ($rootobject) {
          $propset = $rootobject->getObject($this->PropsetID);
          if ($propset) {
            $propset->init();

            foreach($propset->Props as $prop) {
              $propobjects = array();
              if ($prop->PropID) {
                $propobjects = $this->getObjectsByPropertyValue('PropID', $prop->PropID);
//                $propobject = $this->getObjectByPropID($prop->PropID);
              } else {
                $propobjects[] = $this;
              }
              foreach($propobjects as $propobject) {
                $propobject->setProperty($prop->Property, $prop->Value);
              }
            }
          }
        }
      }

      if ($this->PropID) {
        //overwrite met evt. gezette attributes
        foreach($this->Attributes as $attribute_name=>$attribute_value) {
          //TODO: dit gaat mis als deze values al gereplaced zijn, bijv. in Objectset!
//          $this->setProperty($attribute_name, $attribute_value);
        }
      }
      //verwijder attributes, behalve voor HTMLObjecten (gewone html tags)
      if (!($this instanceof SB_HTMLObject)) {
        $this->clearAttributes();
      }
      foreach($this->Objects as $childobject) {
        $childobject->initPropsets();
      }
    }


    public function getJSID($id=false) {
      $returnvalue = "";

      if ($this->JSID) {
        //jsid handmatig gezet
        $returnvalue = $this->JSID;
      } else {
        //jsid genereren
        if ($id) {
          $returnvalue = leftpart($id, "~");
        } else {
          if ($this->OriginalID) {
            $returnvalue = $this->OriginalID;
          } else {
            $returnvalue  =  $this->ID;
          }
        }

        if ($this->AddIDPrefix) {
          $returnvalue = $this->getIDPrefix() . $returnvalue;
        }

        if ($this->AddIDSuffix) {
          $returnvalue = $returnvalue . $this->getIDSuffix();
        }

        if ($this->AddContainerSuffix) {
          $containerid = $this->getContainerJSID();
          if ($containerid) {
            $returnvalue = $returnvalue . "_" . $containerid;
          }
        }

        //if ($this->RepeatID) {
        //  if (contains($returnvalue, "_" . $this->RepeatID . "_")) {
        //    $returnvalue = str_ireplace("_" . $this->RepeatID . "_", "_", $returnvalue);
        //    $returnvalue = $returnvalue . "_" . $this->RepeatID;
        //  }
        //}

        //if ($this->RepeatID !== null) {
        //  $returnvalue = $returnvalue . "_" . $this->RepeatID;
        //}
      }

      //hash?
      if ($this->Hashing
      &&  substr($id, 0, 2) != "id") {
        if (strpos($returnvalue, "_{repeater:repeatid}") !== false) {
          $returnvalue = "id" . hash($this->HashingAlgorithm, removesubstring($returnvalue, "_{repeater:repeatid}")) . "_{repeater:repeatid}";
        } else {
          $returnvalue = "id" . hash($this->HashingAlgorithm, $returnvalue);
        }
      }

      return $returnvalue;
    }


    public function getJSIDs($id) {
      $returnvalue  = "";
      $komma        = "";

      if ($id) {
        //$rootobject = $this->getRootObject("SB_XMLTemplateReader");
        $rootobject = $this->getRootObject();

        if ($rootobject) {
          $objectids = explode(",", $id);

          foreach ($objectids as $key=>$objectid) {
            $object = $rootobject->getObject(trim($objectid));

            if ($object) {
              if (method_exists($object, "getJSID")) {
                $returnvalue .= $komma . $object->getJSID();
                $komma       = ",";
              }
            } else {
              $returnvalue .= $komma . $objectid;
              $komma = ",";
            }
          }
        }
      }

      return $returnvalue;
    }


    public function getContainerJSID($object=false) {
      if (!$object) {
        $object = $this;
      }

      if (!sb\is_empty($object->ContainerJSID)) {
        //object heeft container
        $returnvalue = $object->ContainerJSID;
      } else {
        //object heeft GEEN container, dan parent onderzoeken
        if ($object->ParentObject) {
          if ($object instanceof SB_Object) {
            $returnvalue = $object->getContainerJSID($object->ParentObject);
          } else {
            $returnvalue = false;
          }
        } else {
          $returnvalue = false;
        }
      }

      return $returnvalue;
    }


    public function setHashJSID() {
      $this->HashJSID = hash("md5", $this->getJSID());
    }


    public function getHashJSID() {
      return hash("md5", $this->getJSID());
    }


    public function getValuesArray() {
      //values array aanmaken
      $valuesarray  = array();

      if (!sb\is_empty($this->Values)) {
        if (is_string($this->Values)
        &&  is_urlEncoded($this->Values)) {
          $this->Values = urldecode($this->Values);
        }

        if (is_string($this->Values)
        &&  is_serialized($this->Values)) {
          $this->Values = unserialize($this->Values);
        }

        if (is_string($this->Values)) {
          //string, dan associatieve array van maken
          if (stripos($this->Values, ",") !== false) {
            //komma-gescheiden
            $valuesarray = explode_assoc(",", $this->Values);
          } elseif (stripos($this->Values, ";") !== false) {
            //puntkomma-gescheiden
            $valuesarray = explode_assoc(";", $this->Values);
          } else {
            //spatie-gescheiden
            $valuesarray = explode_assoc(" ", $this->Values);
          }
        } elseif (is_array($this->Values)) {
          //array
          $valuesarray = $this->Values;
        }
      } else {
        $rootobject = $this->getRootObject();
        if ($rootobject) {
          $values = $rootobject->getObjectsByType('SB_Value');
          foreach ($values as $value) {
            $valuesarray[$value->ID] = $value->Value;
          }
        }
      }

      return $valuesarray;
    }


    public function getParamValue($paramname) {
      $paramsarray  = $this->getParamsArray();

      foreach ($paramsarray  as $paramkey=>$paramvalue) {
        if (comparetext($paramkey, $paramname)) {
          return $paramvalue;
        }
      }

      return false;
    }


    public function getParamsArray() {
      //values array aanmaken
      $paramsarray  = array();

      if (is_string($this->Params)) {
        //string, dan associatieve array van maken
        if (stripos($this->Params, ",") !== false) {
          //komma-gescheiden
          //$paramsarray = explode_assoc(",", $this->Params);
          $paramsarray = explode_assoc(";", $this->Params);
        } elseif (stripos($this->Params, ";") !== false) {
          //puntkomma-gescheiden
          $paramsarray = explode_assoc(";", $this->Params);
        } else {
          //spatie-gescheiden
          $paramsarray = explode_assoc(" ", $this->Params);
        }
      } elseif (is_array($this->Params)) {
        //array
        $paramsarray = $this->Params;
      }

      return $paramsarray;
    }


    public function getParamsString() {
      //values array aanmaken
      $paramsstring = "";

      if (is_string($this->Params)) {
        //string
        $paramsstring = $this->Params;
      } elseif (is_array($this->Params)) {
        //array
        $paramsstring = implode_assoc(";", $this->Params);;
      }

      return $paramsstring;
    }


    public function getIDPrefix() {
      $returnvalue = "";

      //if (!$this->ParentObject) {
        $returnvalue = $this->IDPrefix;
      //} else {
     //   if ($this->ParentObject instanceof SB_TemplateObject) {
      //    $returnvalue = $this->ParentObject->IDPrefix;
     //   } else {
      //    $returnvalue = $this->IDPrefix;
      //  }
      //}

      return $returnvalue;
    }


    public function getIDSuffix() {
      $returnvalue = $this->IDSuffix;

      return $returnvalue;
    }


    public function addObject($object) {
      //inherited
      parent::addObject($object);

      //vervang IDPrefix door die van parent
      if ($object instanceof SB_TemplateObject) {
        $object->IDPrefix  = $this->IDPrefix;

        //IDSuffix wordt per class ingesteld op type!
//        $object->IDSuffix  = $this->IDSuffix;
      }
    }


    public function addHeadObject($object, $insertbegin=false) {
      if (class_exists($object->Classname)) {
        $classname  = $object->Classname;
        $headobject = new $classname();

        foreach ($object as $key=>$value) {
          $headobject->$key = $value;
        }
      } else {
        $headobject = $object;
      }

      if ($insertbegin) {
        array_unshift($this->HeadObjects, $headobject);
      } else {
        $this->HeadObjects[] = $headobject;
      }
    }


    public function addHeadObject_JavascriptFile($filename, $insertbegin=false) {
      $headobject = new SB_HeadObject_JavascriptFile();
      $headobject->Filename = $filename;

      if ($insertbegin) {
        array_unshift($this->HeadObjects, $headobject);
      } else {
        $this->HeadObjects[] = $headobject;
      }
    }


    public function addHeadObject_CSSFile($filename, $insertbegin=false) {
      $headobject = new SB_HeadObject_CSSFile();
      $headobject->Filename = $filename;

      if ($insertbegin) {
        array_unshift($this->HeadObjects, $headobject);
      } else {
        $this->HeadObjects[] = $headobject;
      }
    }


    public function addJavascriptFile($javascriptfilename, $section="", $insertbegin=false, $inline=false) {
      if (!sb\is_empty($javascriptfilename)) {
        //oude manier in array javascriptfiles
        if (!in_array($javascriptfilename, $this->JavascriptFiles)) {
          if ($insertbegin) {
            array_unshift($this->JavascriptFiles, $javascriptfilename);
          } else {
            $this->JavascriptFiles[] = $javascriptfilename;
          }
        }

        //nieuwe manier in array headobjects
        /*
        if (!in_array_object($this->HeadObjects, "Filename", $javascriptfilename)) {
          $headobject = new SB_HeadObject_JavascriptFile();
          $headobject->Filename = $javascriptfilename;
          $headobject->Section  = $section;

          if ($insertbegin) {
            array_unshift($this->HeadObjects, $headobject);
          } else {
            $this->HeadObjects[]  = $headobject;
          }
        }
        */
      }
    }


    public function addInitialJavascript($javascript, $section="", $insertbegin=false) {
      if (!sb\is_empty($javascript)) {
        //ouwe manier in array initialjavascript
        if (!sb\is_empty($section)) {
          $javascriptarray = &$this->InitialJavascript[$section];
        } else {
          $javascriptarray = &$this->InitialJavascript;
        }

        if ($insertbegin) {
          array_unshift($javascriptarray, $javascript);
        } else {
          $javascriptarray[] = $javascript;
        }

        //nieuwe manier in array headobjects
        /*
        $headobject = new SB_HeadObject_Javascript();
        $headobject->Position = "DEFAULT";
        $headobject->Script   = $javascript;
        $headobject->Section  = $section;
        $headobject->Sequence = "INITIAL";

        if ($insertbegin) {
          array_unshift($this->HeadObjects, $headobject);
        } else {
          $this->HeadObjects[]  = $headobject;
        }
        */
      }
    }


    public function addInitialJavascriptBefore($javascript, $section="", $insertbegin=false) {
      if (!sb\is_empty($javascript)) {
        //ouwe manier in array initialjavascriptbefore
        if (!sb\is_empty($section)) {
          $javascriptarray = &$this->InitialJavascriptBefore[$section];
        } else {
          $javascriptarray = &$this->InitialJavascriptBefore;
        }

        if ($insertbegin) {
          array_unshift($javascriptarray, $javascript);
        } else {
          $javascriptarray[] = $javascript;
        }

        //nieuwe manier in array headobjects
        /*
        $headobject = new SB_HeadObject_Javascript();
        $headobject->Position = "BEFORE";
        $headobject->Script   = $javascript;
        $headobject->Section  = $section;
        $headobject->Sequence = "INITIAL";

        if ($insertbegin) {
          array_unshift($this->HeadObjects, $headobject);
        } else {
          $this->HeadObjects[]  = $headobject;
        }
        */
      }
    }


    public function addInitialJavascriptAfter($javascript, $section="", $insertbegin=false) {
      if (!sb\is_empty($javascript)) {
        //ouwe manier in array initialjavascriptafter
        if (!sb\is_empty($section)) {
          $javascriptarray = &$this->InitialJavascriptAfter[$section];
        } else {
          $javascriptarray = &$this->InitialJavascriptAfter;
        }

        if ($insertbegin) {
          array_unshift($javascriptarray, $javascript);
        } else {
          $javascriptarray[] = $javascript;
        }

        //nieuwe manier in array headobjects
        /*
        $headobject = new SB_HeadObject_Javascript();
        $headobject->Position = "AFTER";
        $headobject->Script   = $javascript;
        $headobject->Section  = $section;
        $headobject->Sequence = "INITIAL";

        if ($insertbegin) {
          array_unshift($this->HeadObjects, $headobject);
        } else {
          $this->HeadObjects[]  = $headobject;
        }
        */
      }
    }


    public function addJavascript($javascript, $section="", $insertbegin=false) {
      if (!sb\is_empty($javascript)) {
        //ouwe manier in array javascript
        if (!sb\is_empty($section)) {
          $javascriptarray = &$this->Javascript[$section];
        } else {
          $javascriptarray = &$this->Javascript;
        }

        if ($insertbegin) {
          array_unshift($javascriptarray, $javascript);
        } else {
          $javascriptarray[] = $javascript;
        }

        //nieuwe manier in array headobjects
        /*
        $headobject = new SB_HeadObject_Javascript();
        $headobject->Position = "DEFAULT";
        $headobject->Script   = $javascript;
        $headobject->Section  = $section;
        $headobject->Sequence = "DEFAULT";

        if ($insertbegin) {
          array_unshift($this->HeadObjects, $headobject);
        } else {
          $this->HeadObjects[]  = $headobject;
        }
        */
      }
    }


    public function addJavascriptBefore($javascript, $section="", $insertbegin=false) {
      if (!sb\is_empty($javascript)) {
        //ouwe manier in array javascriptbefore
        if (!sb\is_empty($section)) {
          $javascriptarray = &$this->JavascriptBefore[$section];
        } else {
          $javascriptarray = &$this->JavascriptBefore;
        }

        if ($insertbegin) {
          array_unshift($javascriptarray, $javascript);
        } else {
          $javascriptarray[] = $javascript;
        }

        //nieuwe manier in array headobjects
        /*
        $headobject = new SB_HeadObject_Javascript();
        $headobject->Position = "BEFORE";
        $headobject->Script   = $javascript;
        $headobject->Section  = $section;
        $headobject->Sequence = "DEFAULT";

        if ($insertbegin) {
          array_unshift($this->HeadObjects, $headobject);
        } else {
          $this->HeadObjects[]  = $headobject;
        }
        */
      }
    }


    public function addJavascriptAfter($javascript, $section="", $insertbegin=false) {
      if (!sb\is_empty($javascript)) {
        //ouwe manier in array javascriptafter
        if (!sb\is_empty($section)) {
          $javascriptarray = &$this->JavascriptAfter[$section];
        } else {
          $javascriptarray = &$this->JavascriptAfter;
        }

        if ($insertbegin) {
          array_unshift($javascriptarray, $javascript);
        } else {
          $javascriptarray[] = $javascript;
        }

        //nieuwe manier in array headobjects
        /*
        $headobject = new SB_HeadObject_Javascript();
        $headobject->Position = "AFTER";
        $headobject->Script   = $javascript;
        $headobject->Section  = $section;
        $headobject->Sequence = "DEFAULT";

        if ($insertbegin) {
          array_unshift($this->HeadObjects, $headobject);
        } else {
          $this->HeadObjects[]  = $headobject;
        }
        */
      }
    }


    public function addFinalJavascript($javascript, $section="", $insertbegin=false) {
      if (!sb\is_empty($javascript)) {
        //ouwe manier in array finaljavascript
        if (!sb\is_empty($section)) {
          $javascriptarray = &$this->FinalJavascript[$section];
        } else {
          $javascriptarray = &$this->FinalJavascript;
        }

        if ($insertbegin) {
          array_unshift($javascriptarray, $javascript);
        } else {
          $javascriptarray[] = $javascript;
        }

        //nieuwe manier in array headobjects
        /*
        $headobject = new SB_HeadObject_Javascript();
        $headobject->Position = "DEFAULT";
        $headobject->Script   = $javascript;
        $headobject->Section  = $section;
        $headobject->Sequence = "FINAL";

        if ($insertbegin) {
          array_unshift($this->HeadObjects, $headobject);
        } else {
          $this->HeadObjects[]  = $headobject;
        }
        */
      }
    }


    public function addFinalJavascriptBefore($javascript, $section="", $insertbegin=false) {
      if (!sb\is_empty($javascript)) {
        //ouwe manier in array finaljavascriptbefore
        if (!sb\is_empty($section)) {
          $javascriptarray = &$this->FinalJavascriptBefore[$section];
        } else {
          $javascriptarray = &$this->FinalJavascriptBefore;
        }

        if ($insertbegin) {
          array_unshift($javascriptarray, $javascript);
        } else {
          $javascriptarray[] = $javascript;
        }

        //nieuwe manier in array headobjects
        /*
        $headobject = new SB_HeadObject_Javascript();
        $headobject->Position = "BEFORE";
        $headobject->Script   = $javascript;
        $headobject->Section  = $section;
        $headobject->Sequence = "FINAL";

        if ($insertbegin) {
          array_unshift($this->HeadObjects, $headobject);
        } else {
          $this->HeadObjects[]  = $headobject;
        }
        */
      }
    }


    public function addFinalJavascriptAfter($javascript, $section="", $insertbegin=false) {
      if (!sb\is_empty($javascript)) {
        //ouwe manier in array finaljavascriptafter
        if (!sb\is_empty($section)) {
          $javascriptarray = &$this->FinalJavascriptAfter[$section];
        } else {
          $javascriptarray = &$this->FinalJavascriptAfter;
        }

        if ($insertbegin) {
          array_unshift($javascriptarray, $javascript);
        } else {
          $javascriptarray[] = $javascript;
        }

        //nieuwe manier in array headobjects
        /*
        $headobject = new SB_HeadObject_Javascript();
        $headobject->Position = "AFTER";
        $headobject->Script   = $javascript;
        $headobject->Section  = $section;
        $headobject->Sequence = "FINAL";

        if ($insertbegin) {
          array_unshift($this->HeadObjects, $headobject);
        } else {
          $this->HeadObjects[]  = $headobject;
        }
        */
      }
    }


    public function addJavascriptFunction($javascript, $insertbegin=false) {
      if (!sb\is_empty($javascript)) {
        if ($insertbegin) {
          array_unshift($this->JavascriptFunctions, $javascript);
        } else {
          $this->JavascriptFunctions[] = $javascript;
        }
      }
    }


    public function addJavascriptGlobal($key, $value, $type="variable", $datatype="string", $inserbegin=false) {
      if (is_string($type)) {
        $types = explode(",", $type);
      } else if (is_array($type)) {
        $types = $type;
      }

      if (!sb\is_empty($key)) {
        foreach ($types as $type) {
          //ouwe manier in array javascriptglobals
          $javascriptglob = new SB_JavascriptObject();

          $javascriptglob->Key      = $key;
          $javascriptglob->Value    = $value;
          $javascriptglob->Type     = $type;
          $javascriptglob->DataType = $datatype;

          $this->JavascriptGlobals[$key . "_" . $type] = $javascriptglob;

          //nieuwe manier in array headobjects
          /*
          $headobject = new SB_HeadObject_JavascriptGlobal();

          $headobject->Key      = $key;
          $headobject->Value    = $value;
          $headobject->Type     = $type;
          $headobject->DataType = $datatype;

          if ($insertbegin) {
            array_unshift($this->HeadObjects, $headobject);
          } else {
            $this->HeadObjects[]  = $headobject;
          }
          */
        }
      }
    }


    public function insertJavascriptGlobal($key, $value) {
      $this->addJavascriptGlobal($key, $value);
    }


    public function addJavascriptObject($key, $value, $quote=false) {
      $this->JavascriptObjects[$key]  = ($quote) ? quote($value, "'") : $value;
    }


    public function addCSSFile($cssfilename, $insertbegin=false, $inline=false) {
      if (!sb\is_empty($cssfilename)) {
        //ouwe manier in array cssfiles
        if (!in_array($cssfilename, $this->CSSFiles)) {
          if ($insertbegin) {
            array_unshift($this->CSSFiles, $cssfilename);
          } else {
            $this->CSSFiles[] = $cssfilename;
          }
        }

        //nieuwe manier in array headobjects
        /*
        if (!in_array_object($this->HeadObjects, "Filename", $cssfilename)) {
          $headobject = new SB_HeadObject_JavascriptFile();
          $headobject->Filename = $cssfilename;
          $headobject->Section  = $section;

          if ($insertbegin) {
            array_unshift($this->HeadObjects, $headobject);
          } else {
            $this->HeadObjects[]  = $headobject;
          }
        }
        */
      }
    }

    public function addLink($link, $insertbegin=false) {
      if (!sb\is_empty($link)) {
        if ($insertbegin) {
          array_unshift($this->Links, $link);
        } else {
          $this->Links[] = $link;
        }
      }
    }

    public function insertLink($link) {
      $this->addLink($link, true);
    }

    /*
    public function getAllInitialJavascript($javascript) {
      //retourneert ALLE initial javascript van huidige object en al haar children

      if ($this->OutputAll
      &&  $this->OutputInitialJavascript) {
        if ($this->IncludeObjects) {
          foreach($this->Objects as $childobject) {
            if ($childobject&&method_exists($childobject, "getAllInitialJavascript")) {
              $javascript = $childobject->getAllInitialJavascript($javascript);
            }
          }
        }

        if (count($this->InitialJavascript) > 0) {
          //$javascript[] = "<script type='text/javascript'>\n";
          foreach($this->InitialJavascript as $javascriptline) {
            //if ((compare_in_array($javascriptline, $javascript)==false)) {
              //replace [jsid]
              $javascriptline  =  $this->replaceJSIDInString($javascriptline);

              //add line to javascript array
              $javascript[] = $javascriptline;
            //}
          }
          //$javascript[] = "</script>\n";
        }
      }

      return $javascript;
    }
    */


    public function getAllInitialJavascript($javascript= array(), $recursive=true, $childsfirst=false) {
      //retourneert ALLE initialjavascript van huidige object en al haar children
      if ($this->OutputAll
      &&  $this->OutputInitialJavascript) {
        //BEFORE
        if (count($this->InitialJavascriptBefore) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
          foreach($this->InitialJavascriptBefore as $javascriptkey=>$javascriptvalue) {
            //multidimensional?
            if (is_array($javascriptvalue)) {
              //javascriptvalue is multidimensional array [functionname][javascript]
              if (!empty($javascriptvalue)) {
                foreach($this->InitialJavascriptBefore[$javascriptkey] as $javascriptline) {
                  //if ((compare_in_array($javascriptline, $javascript)==false)) {
                    if (!sb\is_empty($javascriptline)) {
                      //replace
                      $javascriptline  =  $this->replaceJSIDInString($javascriptline);
                      //$javascriptline   = $this->replace_hooks($javascriptline);

                      //add line to javascript array
                      $javascript[$javascriptkey][] = $javascriptline;
                    }
                  //}
                }
              }

            } else {
              //javascriptvalue is string
              if (!sb\is_empty($javascriptvalue)) {
                //replace
                $javascriptline  =  $this->replaceJSIDInString($javascriptvalue);
                //$javascriptline   = $this->replace_hooks($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              }
            }
          }
        }


        //children
        if ($recursive
        &&  $childsfirst) {
          if ($this->IncludeObjects) {
            //get initial javascript from children
            foreach($this->Objects as $childobject) {
              if (is_object($childobject) && method_exists($childobject, "getAllInitialJavascript")) {
                $javascript = $childobject->getAllInitialJavascript($javascript, $childobject->InitialJavascriptRecursive, $childobject->InitialJavascriptChildsFirst);
              }
            }
          }
        }

        //INITIALJAVASCRIPT lines
        if (count($this->InitialJavascript) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
          foreach($this->InitialJavascript as $javascriptkey=>$javascriptvalue) {
            //multidimensional?
            if (is_array($javascriptvalue)) {
              //javascriptvalue is multidimensional array [functionname][javascript]
              if (!empty($javascriptvalue)) {
                foreach($this->InitialJavascript[$javascriptkey] as $javascriptline) {
                  //if ((compare_in_array($javascriptline, $javascript)==false)) {
                    if (!sb\is_empty($javascriptline)) {
                      //replace
                      $javascriptline  =  $this->replaceJSIDInString($javascriptline);
                      //$javascriptline   = $this->replace_hooks($javascriptline);

                      //add line to javascript array
                      $javascript[$javascriptkey][] = $javascriptline;
                    }
                  //}
                }
              }

            } else {
              //javascriptvalue is string
              if (!sb\is_empty($javascriptvalue)) {
                //replace
                $javascriptline  =  $this->replaceJSIDInString($javascriptvalue);
                //$javascriptline   = $this->replace_hooks($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              }
            }
          }
        }

        //children
        if ($recursive
        && !$childsfirst) {
          if ($this->IncludeObjects) {
            //get initial javascript from children
            foreach($this->Objects as $childobject) {
              if (is_object($childobject) && method_exists($childobject, "getAllInitialJavascript")) {
                $javascript = $childobject->getAllInitialJavascript($javascript, $childobject->InitialJavascriptRecursive, $childobject->InitialJavascriptChildsFirst);
              }
            }
          }
        }


        //AFTER
        if (count($this->InitialJavascriptAfter) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
          foreach($this->InitialJavascriptAfter as $javascriptkey=>$javascriptvalue) {
            //multidimensional?
            if (is_array($javascriptvalue)) {
              //javascriptvalue is multidimensional array [functionname][javascript]
              if (!empty($javascriptvalue)) {
                foreach($this->InitialJavascriptAfter[$javascriptkey] as $javascriptline) {
                  //if ((compare_in_array($javascriptline, $javascript)==false)) {
                    if (!sb\is_empty($javascriptline)) {
                      //replace
                      $javascriptline  =  $this->replaceJSIDInString($javascriptline);
                      //$javascriptline   = $this->replace_hooks($javascriptline);

                      //add line to javascript array
                      $javascript[$javascriptkey][] = $javascriptline;
                    }
                  //}
                }
              }

            } else {
              //javascriptvalue is string
              if (!sb\is_empty($javascriptvalue)) {
                //replace
                $javascriptline  =  $this->replaceJSIDInString($javascriptvalue);
                //$javascriptline   = $this->replace_hooks($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              }
            }
          }
        }
      }

      return $javascript;
    }


    public function getAllJavascript($javascript=array(), $recursive=true, $childsfirst=false) {
      //retourneert ALLE javascript van huidige object en al haar children

      if ($this->OutputAll
      &&  $this->OutputJavascript) {
        //BEFORE
        if (count($this->JavascriptBefore) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
          foreach($this->JavascriptBefore as $javascriptkey=>$javascriptvalue) {
            //multidimensional?
            if (is_array($javascriptvalue)) {
              //javascriptvalue is multidimensional array [functionname][javascript]
              if (!empty($javascriptvalue)) {
                foreach($this->Javascript[$javascriptkey] as $javascriptline) {
                  //replace
                  $javascriptline   = $this->replaceJSIDInString($javascriptline);

                  //add line to javascript array
                  $javascript[$javascriptkey][] = $javascriptline;
                }
              }

            } else {
              //javascriptvalue is string
              if (!sb\is_empty($javascriptvalue)) {
                //replace
                $javascriptline  =  $this->replaceJSIDInString($javascriptvalue);
                //$javascriptline   = $this->replace_hooks($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              }
            }
          }
        }

        //children
        if ($recursive
        &&  $childsfirst) {
          if ($this->IncludeObjects) {
            foreach($this->Objects as $childobject) {
              if ($childobject && method_exists($childobject, "getAllJavascript")) {
                $javascript = $childobject->getAllJavascript($javascript, $childobject->JavascriptRecursive, $childobject->JavascriptChildsFirst);
              }
            }
          }
        }


        //JAVASCRIPT lines
        if (count($this->Javascript) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
          foreach($this->Javascript as $javascriptkey=>$javascriptvalue) {
            //multidimensional?
            if (is_array($javascriptvalue)) {
              //javascriptvalue is multidimensional array [functionname][javascript]
              if (!empty($javascriptvalue)) {
                foreach($this->Javascript[$javascriptkey] as $javascriptline) {
                  //if ((compare_in_array($javascriptline, $javascript)==false)) {
                    //if (!sb\is_empty($javascriptline)) {
                      //replace
                      $javascriptline   = $this->replaceJSIDInString($javascriptline);
                      //$javascriptline   = $this->replace_hooks($javascriptline);

                      //add line to javascript array
                      $javascript[$javascriptkey][] = $javascriptline;
                    //}
                  //}
                }
              }

            } else {
              //javascriptvalue is string
              if (!sb\is_empty($javascriptvalue)) {
                //replace
                $javascriptline  =  $this->replaceJSIDInString($javascriptvalue);
                //$javascriptline   = $this->replace_hooks($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              }
            }
          }
        }

        //children
        if ($recursive
        && !$childsfirst) {
          if ($this->IncludeObjects) {
            foreach($this->Objects as $childobject) {
              if ($childobject && method_exists($childobject, "getAllJavascript")) {
                $javascript = $childobject->getAllJavascript($javascript, $childobject->JavascriptRecursive, $childobject->JavascriptChildsFirst);
              }
            }
          }
        }


        //AFTER
        if (count($this->JavascriptAfter) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
          foreach($this->JavascriptAfter as $javascriptkey=>$javascriptvalue) {
            //multidimensional?
            if (is_array($javascriptvalue)) {
              //javascriptvalue is multidimensional array [functionname][javascript]
              if (!empty($javascriptvalue)) {
                foreach($this->Javascript[$javascriptkey] as $javascriptline) {
                  //replace
                  $javascriptline   = $this->replaceJSIDInString($javascriptline);

                  //add line to javascript array
                  $javascript[$javascriptkey][] = $javascriptline;
                }
              }

            } else {
              //javascriptvalue is string
              if (!sb\is_empty($javascriptvalue)) {
                //replace
                $javascriptline  =  $this->replaceJSIDInString($javascriptvalue);
                //$javascriptline   = $this->replace_hooks($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              }
            }
          }
        }

      }

      return $javascript;
    }


    public function getAllFinalJavascript($javascript=array(), $recursive=true, $childsfirst=false) {
      //retourneert ALLE finaljavascript van huidige object en al haar children

      if ($this->OutputAll
      &&  $this->OutputFinalJavascript) {
        //BEFORE
        if (count($this->FinalJavascriptBefore) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
          foreach($this->FinalJavascriptBefore as $javascriptkey=>$javascriptvalue) {
            //multidimensional?
            if (is_array($javascriptvalue)) {
              //javascriptvalue is multidimensional array [functionname][javascript]
              if (!empty($javascriptvalue)) {
                foreach($this->FinalJavascriptBefore[$javascriptkey] as $javascriptline) {
                  //if ((compare_in_array($javascriptline, $javascript)==false)) {
                    if (!sb\is_empty($javascriptline)) {
                      //replace
                      $javascriptline  =  $this->replaceJSIDInString($javascriptline);
                      //$javascriptline   = $this->replace_hooks($javascriptline);

                      //add line to javascript array
                      $javascript[$javascriptkey][] = $javascriptline;
                    }
                  //}
                }
              }

            } else {
              //javascriptvalue is string
              if (!sb\is_empty($javascriptvalue)) {
                //replace
                $javascriptline  =  $this->replaceJSIDInString($javascriptvalue);
                //$javascriptline   = $this->replace_hooks($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              }
            }
          }
        }

        //children
        if ($recursive
        &&  $childsfirst) {
          if ($this->IncludeObjects) {
            foreach($this->Objects as $childobject) {
              if ($childobject && method_exists($childobject, "getAllFinalJavascript")) {
                $javascript = $childobject->getAllFinalJavascript($javascript, $childobject->FinalJavascriptRecursive, $childobject->FinalJavascriptChildsFirst);
              }
            }
          }
        }

        if (count($this->FinalJavascript) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
          foreach($this->FinalJavascript as $javascriptkey=>$javascriptvalue) {
            //multidimensional?
            if (is_array($javascriptvalue)) {
              //javascriptvalue is multidimensional array [functionname][javascript]
              if (!empty($javascriptvalue)) {
                foreach($this->FinalJavascript[$javascriptkey] as $javascriptline) {
                  //if ((compare_in_array($javascriptline, $javascript)==false)) {
                    if (!sb\is_empty($javascriptline)) {
                      //replace
                      $javascriptline  =  $this->replaceJSIDInString($javascriptline);
                      //$javascriptline   = $this->replace_hooks($javascriptline);

                      //add line to javascript array
                      $javascript[$javascriptkey][] = $javascriptline;
                    }
                  //}
                }
              }

            } else {
              //javascriptvalue is string
              if (!sb\is_empty($javascriptvalue)) {
                //replace
                $javascriptline  =  $this->replaceJSIDInString($javascriptvalue);
                //$javascriptline   = $this->replace_hooks($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              }
            }
          }
        }

        //children
        if ($recursive
        && !$childsfirst) {
          if ($this->IncludeObjects) {
            foreach($this->Objects as $childobject) {
              if ($childobject && method_exists($childobject, "getAllFinalJavascript")) {
                $javascript = $childobject->getAllFinalJavascript($javascript, $childobject->FinalJavascriptRecursive, $childobject->FinalJavascriptChildsFirst);
              }
            }
          }
        }


        //AFTER
        if (count($this->FinalJavascriptAfter) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
          foreach($this->FinalJavascriptAfter as $javascriptkey=>$javascriptvalue) {
            //multidimensional?
            if (is_array($javascriptvalue)) {
              //javascriptvalue is multidimensional array
              if (!empty($javascriptvalue)) {
                foreach($this->FinalJavascriptAfter[$javascriptkey] as $javascriptline) {
                  //if ((compare_in_array($javascriptline, $javascript)==false)) {
                    if (!sb\is_empty($javascriptline)) {
                      //replace
                      $javascriptline  =  $this->replaceJSIDInString($javascriptline);
                      //$javascriptline   = $this->replace_hooks($javascriptline);

                      //add line to javascript array
                      $javascript[$javascriptkey][] = $javascriptline;
                    }
                  //}
                }
              }

            } else {
              //javascriptvalue is string
              if (!sb\is_empty($javascriptvalue)) {
                //replace
                $javascriptline  =  $this->replaceJSIDInString($javascriptvalue);
                //$javascriptline   = $this->replace_hooks($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              }
            }
          }
        }
      }

      return $javascript;
    }


    public function getAllJavascriptFunctions($javascript=array()) {
      //retourneert ALLE javascript-functions van huidige object en al haar children

      if ($this->OutputAll
      &&  $this->OutputJavascriptFunctions) {
        if ($this->IncludeObjects) {
          foreach($this->Objects as $childobject) {
            if ($childobject && method_exists($childobject, "getAllJavascriptFunctions")) {
              $javascript = $childobject->getAllJavascriptFunctions($javascript);
            }
          }
        }

        if (count($this->JavascriptFunctions) > 0) {
          $javascriptfunctions = '';
          foreach($this->JavascriptFunctions as $javascriptline) {
            //even alles aan elkaar plakken
            $javascriptfunctions .= $javascriptline;
          }

          if (trim($javascriptfunctions)!='') {
            //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
            foreach($this->JavascriptFunctions as $javascriptline) {
              if (!sb\is_empty($javascriptline)) {
                //if ((compare_in_array($javascriptline, $javascript)==false)) {
                  //replace [jsid]
                  $javascriptline  =  $this->replaceJSIDInString($javascriptline);

                  //add line to javascript array
                  $javascript[] = $javascriptline;
                //}
              }
            }
          }
        }
      }

      return $javascript;
    }


    /*
    public function getAllJavascriptObjects($javascript) {
      //retourneert ALLE javascript-objecten van huidige object en al haar children
      if ($this->OutputAll
      &&  $this->OutputJavascriptObjects) {
        if ($this->IncludeObjects) {
          foreach($this->Objects as $childobject) {
            if ($childobject && method_exists($childobject, "getAllJavascriptObjects")) {
              $javascript = $childobject->getAllJavascriptObjects($javascript);
            }
          }
        }

        if (count($this->JavascriptObjects) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;
          foreach($this->JavascriptObjects as $javascriptobjectkey=>$javascriptobject) {
            if (!sb\is_empty($javascriptobject)) {
              //if ((compare_in_array($javascriptline, $javascript)==false)) {
                $javascriptline = "window." . $this->JavascriptObjectPrefix . ((is_string($javascriptobjectkey) ? $javascriptobjectkey : ($this->getJSID())) . $this->JavascriptObjectSuffix . " = ");
                $komma = "";

                if (is_object($javascriptobject)) {
                  //object
                  $javascriptline .= "{";

                  foreach ($javascriptobject as $propertyname=>$propertyvalue) {
                    if (!sb\is_empty($propertyvalue)
                    &&  !is_object($propertyvalue)
                    &&  !is_array($propertyvalue)) {
                      $javascriptline .= $komma . $propertyname . ":" . "\"" . str_ireplace("\"", '`', javascriptlinebreaks($propertyvalue)) ."\"";
                      $komma = ", ";
                    }
                  }

                  $javascriptline .= "};";

                } elseif (is_string($javascriptobject)) {
                  //string
                  $javascriptline .= $javascriptobject . ";";
                }

                //replace [jsid]
                $javascriptline  =  $this->replaceJSIDInString($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              //}
            }
          }
        }
      }

      return $javascript;
    }
    */


    public function getAllJavascriptObjects($javascript=array(), $recursive=true, $childsfirst=false) {
      //retourneert ALLE javascript-objecten van huidige object en al haar children
      if ($this->OutputAll
      &&  $this->OutputJavascriptObjects) {
        //children
        if ($recursive
        &&  $childsfirst) {
          if ($this->IncludeObjects) {
            foreach($this->Objects as $childobject) {
              if ($childobject && method_exists($childobject, "getAllJavascriptObjects")) {
                $javascript = $childobject->getAllJavascriptObjects($javascript);
              }
            }
          }
        }

        //JavascriptObjects array
        if (count($this->JavascriptObjects) > 0) {
          //$javascript[] = "<script type='text/javascript'>" . PHP_EOL;

          foreach($this->JavascriptObjects as $javascriptobjectkey=>$javascriptobject) {
            if (!sb\is_empty($javascriptobject)) {
              //if ((compare_in_array($javascriptline, $javascript)==false)) {
                $javascriptline = "window." . ((is_string($javascriptobjectkey) ? $javascriptobjectkey : $javascriptobject->id) . "_object" . " = ");
                $komma = "";

                if (is_object($javascriptobject)) {
                  //object
                  $javascriptline .= "{";

                  foreach ($javascriptobject as $propertyname=>$propertyvalue) {
                    if ($propertyname
                    && !is_nothing($propertyvalue)
                    && !is_object($propertyvalue)
                    && !is_array($propertyvalue)) {
                      if (is_string($propertyvalue)) {
                        //if (is_numberic($propertyvalue)) {
                        //  $javascriptline .= $komma . '"'. $propertyname . '":' . $propertyvalue;
                        //  $komma = ", ";
                        //} elseif (is_boolexpression($propertyvalue)) {
                        if (is_boolexpression($propertyvalue)) {
                          $propertyvalue  = booltostr(boolOrEval($propertyvalue));
                          $javascriptline .= $komma . '"'. $propertyname . '":' . $propertyvalue;
                          $komma = ", ";
                        } else {
                          $javascriptline .= $komma . '"' . $propertyname . '":' . "\"" . str_ireplace("\"", '`', removelinebreaks($propertyvalue)) ."\"";
                          $komma = ", ";
                        }
                      } else if (is_number($propertyvalue)) {
                        $javascriptline .= $komma . '"'. $propertyname . '":' . $propertyvalue;
                        $komma = ", ";
                      } else if (is_null($propertyvalue)) {
                        $propertyvalue = "null";
                        $javascriptline .= $komma . '"'. $propertyname . '":' . $propertyvalue;
                        $komma = ", ";
                      } else if (is_bool($propertyvalue)) {
                        $propertyvalue = booltostr($propertyvalue);
                        $javascriptline .= $komma . '"'. $propertyname . '":' . $propertyvalue;
                        $komma = ", ";
                      }
                    }
/*
                    if ($propertyname
                    && !is_nothing($propertyvalue)
                    && !is_object($propertyvalue)
                    && !is_array($propertyvalue)) {
                      $javascriptline .= $komma . '"'. $propertyname . '":' . $propertyvalue;
                      //$javascriptline .= $komma . '"' . $propertyname . '":' . "\"" . str_ireplace("\"", '`', removelinebreaks($propertyvalue)) ."\"";
                      $komma = ", ";
                    }
*/
                  }

                  $javascriptline .= "};";

                } elseif (is_string($javascriptobject)) {
                  //string
                  $javascriptline .= $javascriptobject . ";";
                }

                //replace [jsid]
                $javascriptline  =  $this->replaceJSIDInString($javascriptline);

                //add line to javascript array
                $javascript[] = $javascriptline;
              //}
            }
          }
        }

        //Objecting Javascript
        if ($this->ObjectingJavascript) {
          $javascriptline = "window."
                          . $this->JavascriptObjectPrefix
                          . ($this->getJSID())
                          . $this->JavascriptObjectSuffix;

          $javascriptline .= " = ";

          if ($this->JavascriptObjectClass) {
            $javascriptline .= $this->JavascriptObjectClass . ".create(";
          }

          $javascriptline .= "{";
          $javascriptline .= '"id":"' . $this->getJSID() . '"';

          $komma = " ,";

          //attributes
          foreach ($this->Attributes as $attributekey=>$attributevalue) {
            $propertyname   = ($this->ObjectingJavascript) ? $this->getPropertyName($attributekey, $attributekey) : $attributekey;
            $propertyvalue  = $this->getPropertyValue($attributevalue, '"');

            if (is_string($propertyvalue)
            && !is_numeric($propertyvalue)
            && !is_boolic($propertyvalue)
            && !is_nothing($propertyvalue)) {
              $propertyvalue=quote(unquote($propertyvalue));
            }

            //if (is_null($propertyvalue)) {
            //  $propertyvalue = "null";
            //}

            if ($propertyname
            && !is_nothing($propertyvalue)
            && !is_object($propertyvalue)
            && !is_array($propertyvalue)) {
              $javascriptline .= $komma . '"'. $propertyname . '":' . $propertyvalue;
              $komma = " ,";
            }
          }

          //attributeobjects
          foreach ($this->AttributesObject as $attributekey=>$attributeobject) {
            $propertyname   = ($this->ObjectingJavascript) ? $this->getPropertyName($attributekey, $attributekey) : $attributekey;

            if ($propertyname
            &&  is_object($attributeobject)) {
              $javascriptline .= $komma . '"'. $propertyname . '":' . $attributeobject->getJSID() . "_object";
              $komma = " ,";
            } else if (is_objecticarray($attributeobject)) {
              $javascriptline .= $komma . '"'. $propertyname . '":' . stripquotes(json_encode($attributeobject));
              $komma = " ,";
            } else if (is_array($attributeobject)) {
              $javascriptline .= $komma . '"'. $propertyname . '":' . json_encode($attributeobject);
              $komma = " ,";
            }
          }

          $javascriptline .= "}";

          if ($this->JavascriptObjectClass) {
            $javascriptline .= ")";
          }

          $javascriptline .= ";";

          //replace [jsid]
          $javascriptline  =  $this->replaceJSIDInString($javascriptline);

          //add line to javascript array
          $javascript[] = $javascriptline;
        }

        //children
        if ($recursive
        && !$childsfirst) {
          if ($this->IncludeObjects) {
            foreach($this->Objects as $childobject) {
              if ($childobject && method_exists($childobject, "getAllJavascriptObjects")) {
                $javascript = $childobject->getAllJavascriptObjects($javascript);
              }
            }
          }
        }
      }

      return $javascript;
    }


    public function getAllJavascriptGlobals($javascriptglobalsarray=array(), $recursive=true, $childsfirst=false) {
      //retourneert ALLE javascript-globals van huidige object en al haar children

      //init variables
      $javascriptline         = "";
      $javascriptglobalvalue  = "";

      if ($this->OutputAll
      &&  $this->OutputJavascriptGlobals) {
        //children
        if ($recursive
        &&  $childsfirst) {
          if ($this->IncludeObjects) {
            foreach($this->Objects as $childobject) {
              if ($childobject && method_exists($childobject, "getAllJavascriptGlobals")) {
                $javascriptglobalsarray = $childobject->getAllJavascriptGlobals($javascriptglobalsarray);
              }
            }
          }
        }

        if (count($this->JavascriptGlobals) > 0) {
          foreach($this->JavascriptGlobals as $javascriptglobalkey=>$javascriptglobalobj) {
            //if (!sb\is_empty($javascriptglobal)) {
              if (!array_key_exists($javascriptglobalkey, $javascriptglobalsarray)) {
                //quotes around value?
                $javascriptglobalvalue = (is_string($javascriptglobalobj->Value)) ? addslashes_singlequotes($javascriptglobalobj->Value) : ((sb\is_empty($javascriptglobalobj->Value)) ? "" : $javascriptglobalobj->Value);

                //construct javascriptline
                switch ($javascriptglobalobj->DataType) {
                  case "string" :
                    $javascriptglobalvalue = "'" . $javascriptglobalvalue . "'";
                    break;
                }


                switch ($javascriptglobalobj->Type) {
                  case "variable"     :
                    $javascriptline = (is_string($javascriptglobalobj->Key)) ? ($javascriptglobalobj->Key . " = " . $javascriptglobalvalue . ";")  : "";
                    break;

                  case "object"       :
                    $javascriptline = "global['" . $javascriptglobalobj->Key . "'] = " . $javascriptglobalvalue . ";";
                    break;
                }

                //replace [jsid]
                $javascriptline  =  $this->replaceJSIDInString($javascriptline);

                //add line to javascript array
                $javascriptglobalsarray[$javascriptglobalkey] = $javascriptline;
              }
            //}
          }
        }

        //children
        if ($recursive
        && !$childsfirst) {
          if ($this->IncludeObjects) {
            foreach($this->Objects as $childobject) {
              if ($childobject && method_exists($childobject, "getAllJavascriptGlobals")) {
                $javascriptglobalsarray = $childobject->getAllJavascriptGlobals($javascriptglobalsarray);
              }
            }
          }
        }
      }

      return $javascriptglobalsarray;
    }


    public function getHeadObjectsByType($objecttype) {
      $returnobjects = [];

      foreach($this->HeadObjects as $childobject) {
        if ($childobject instanceof $objecttype) {
          $returnobjects[] = $childobject;
        }
      }

      return $returnobjects;
    }


    public function getAllJavascriptFiles($javascriptfiles=array(), $childsfirst=false) {
      //retourneert alle javascriptfiles van huidige object en al haar children
      if ($this->OutputAll
      &&  ($this->OutputJavascriptFiles)) { // || $this->OutputEvalJavascriptFiles) ) {
        if (!$childsfirst) {
          //get javascriptfiles
          foreach($this->JavascriptFiles as $javascriptfile) {
            if ((in_array($javascriptfile, $javascriptfiles)==false)) {
              $javascriptfiles[] = $javascriptfile;
            }
          }

          //get headobjects
          //foreach($this->getHeadObjectsByType("SB_HeadObject_JavascriptFile") as $headobject) {
          //  $javascriptfiles[] = $headobject->Filename;
          //}
        }

        if ($this->IncludeObjects) {
          //get initial javascript from children
          foreach($this->Objects as $childobject) {
            if ($childobject
            &&  method_exists($childobject, "getAllJavascriptFiles")) {
              $javascriptfiles = $childobject->getAllJavascriptFiles($javascriptfiles, $childsfirst);
            }
          }
        }

        if ($childsfirst) {
          //get javascriptfiles
          foreach($this->JavascriptFiles as $javascriptfile) {
            if ((in_array($javascriptfile, $javascriptfiles)==false)) {
              $javascriptfiles[] = $javascriptfile;
            }
          }

          //get headobjects
          //foreach($this->getHeadObjectsByType("SB_HeadObject_JavascriptFile") as $headobject) {
          //  $javascriptfiles[] = $headobject->Filename;
          //}
        }
      }

      return $javascriptfiles;
    }


    public function getAllJavascriptFileObjects($javascriptfileobjects=array(), $childsfirst=false, $position="ALL") {
      //retourneert alle javascriptfiles van huidige object (en al haar children) ALS OBJECT
      if ($this->OutputAll
      &&  ($this->OutputJavascriptFiles)) { // || $this->OutputEvalJavascriptFiles) ) {
        if (!$childsfirst) {
          //get javascriptfiles
          /*
          foreach($this->JavascriptFiles as $key=>$javascriptfile) {
            $headobject1[$key] = new SB_HeadObject_JavascriptFile();
            $headobject1[$key]->Combine  = $this->CombineJavascriptFiles;
            $headobject1[$key]->Compile  = $this->CompileJavascriptFiles;
            $headobject1[$key]->Filename = $javascriptfile;

            if (!in_array_object($this->HeadObjects, "Filename", $javascriptfile)) {
              $javascriptfileobjects[] = $headobject1[$key];
            }
          }
          */

          //get headobjects
          foreach($this->getHeadObjectsByType("SB_HeadObject_JavascriptFile") as $headobject2) {
            if (comparetext($headobject2->Position, $position)
            ||  comparetext($headobject2->Position, "ALL")
            ||  comparetext($position, "ALL")) {
              if (!in_array_object($javascriptfileobjects, "Filename", $headobject2->Filename)) {
                $javascriptfileobjects[] = $headobject2;
              }
            }
          }
        }

        if ($this->IncludeObjects) {
          //get initial javascript from children
          foreach($this->Objects as $childobject) {
            if ($childobject
            &&  method_exists($childobject, "getAllJavascriptFileObjects")) {
              $javascriptfileobjects = $childobject->getAllJavascriptFileObjects($javascriptfileobjects, $childsfirst, $position);
            }
          }
        }

        if ($childsfirst) {
          //get javascriptfiles
          /*
          foreach($this->JavascriptFiles as $key=>$javascriptfile) {
            $headobject1[$key] = new SB_HeadObject_JavascriptFile();
            $headobject1[$key]->Combine  = $this->CombineJavascriptFiles;
            $headobject1[$key]->Compile  = $this->CompileJavascriptFiles;
            $headobject1[$key]->Filename = $javascriptfile;

            if (!in_array_object($this->HeadObjects, "Filename", $javascriptfile)) {
              $javascriptfileobjects[] = $headobject1[$key];
            }
          }
          */

          //get headobjects
          foreach($this->getHeadObjectsByType("SB_HeadObject_JavascriptFile") as $headobject2) {
            if (comparetext($headobject2->Position, $position)
            ||  comparetext($headobject2->Position, "ALL")
            ||  comparetext($position, "ALL")) {
              if (!in_array_object($javascriptfileobjects, "Filename", $headobject2->Filename)) {
                $javascriptfileobjects[] = $headobject2;
              }
            }
          }
        }
      }

      return $javascriptfileobjects;
    }


    public function getAllCSSFileObjects($cssfileobjects=array(), $childsfirst=false, $position="ALL") {
      //retourneert alle cssfiles van huidige object (en al haar children) ALS OBJECT
      if ($this->OutputAll
      &&  ($this->OutputCSSFiles)) { // || $this->OutputEvalCSSFiles) ) {
        if (!$childsfirst) {
          //get headobjects
          foreach($this->getHeadObjectsByType("SB_HeadObject_CSSFile") as $headobject2) {
            if (comparetext($headobject2->Position, $position)
            ||  comparetext($headobject2->Position, "ALL")
            ||  comparetext($position, "ALL")) {
              if (!in_array_object($cssfileobjects, "Filename", $headobject2->Filename)) {
                $cssfileobjects[] = $headobject2;
              }
            }
          }
        }

        if ($this->IncludeObjects) {
          //get initial css from children
          foreach($this->Objects as $childobject) {
            if ($childobject
            &&  method_exists($childobject, "getAllCSSFileObjects")) {
              $cssfileobjects = $childobject->getAllCSSFileObjects($cssfileobjects, $childsfirst, $position);
            }
          }
        }

        if ($childsfirst) {
          //get headobjects
          foreach($this->getHeadObjectsByType("SB_HeadObject_CSSFile") as $headobject2) {
            if (comparetext($headobject2->Position, $position)
            ||  comparetext($headobject2->Position, "ALL")
            ||  comparetext($position, "ALL")) {
              if (!in_array_object($cssfileobjects, "Filename", $headobject2->Filename)) {
                $cssfileobjects[] = $headobject2;
              }
            }
          }
        }
      }

      return $cssfileobjects;
    }


    public function getAllEvalJavascriptFiles($javascriptfiles=array()) {
      //retourneert alle javascriptfiles van huidige object en al haar children

      if ($this->OutputAll
      &&  $this->OutputEvalJavascriptFiles) {
        if ($this->IncludeObjects) {
          foreach($this->Objects as $childobject) {
            if ($childobject
            &&  method_exists($childobject, "getAllEvalJavascriptFiles")) {
              $javascriptfiles = $childobject->getAllEvalJavascriptFiles($javascriptfiles);
            }
          }
        }

        foreach($this->JavascriptFiles as $javascriptfile) {
          //$strippedjsfile = leftpart($javascriptfile, "?");
          $strippedjsfile = $javascriptfile;

          if ((compare_in_array($strippedjsfile, $javascriptfiles) == false)) {
            $javascriptfiles[] = $strippedjsfile;
          }
        }
      }

      return $javascriptfiles;
    }


    public function getAllCSSFiles($cssfiles=array()) {
      //retourneert ALLE CSS van huidige object en al haar children
      //let op : hierin kunnen ook SCSS files zitten,
      //deze zullen eerst hernoemd moeten worden voordat deze ergens gebruikt worden !
      if ($this->OutputAll
      &&  ($this->OutputCSSFiles)) { // || $this->OutputEvalCSSFiles) ) {
        if ($this->IncludeObjects) {
          foreach($this->Objects as $childobject) {
            if ($childobject&&method_exists($childobject, "getAllCSSFiles")) {
              $cssfiles = $childobject->getAllCSSFiles($cssfiles);
            }
          }
        }

        foreach ($this->CSSFiles as $cssfile) {
          if ((compare_in_array($cssfile, $cssfiles)==false)) {
            $cssfiles[] = $cssfile;
          }
        }
      }

      return $cssfiles;
    }


    public function getAllEvalCSSFiles($cssfiles=array()) {
      //retourneert ALLE CSS van huidige object en al haar children

      if ($this->OutputAll
      &&  ($this->OutputEvalCSSFiles) ) {
        if ($this->IncludeObjects) {
          foreach($this->Objects as $childobject) {
            if ($childobject&&method_exists($childobject, "getAllCSSFiles")) {
              $cssfiles = $childobject->getAllEvalCSSFiles($cssfiles);
            }
          }
        }

        foreach ($this->CSSFiles as $cssfile) {
          if ((compare_in_array($cssfile, $cssfiles)==false)) {
            $cssfiles[] = $cssfile;
          }
        }
      }

      return $cssfiles;
    }


    public function getAllLinks($links=array()) {
      //retourneert ALLE Links van huidige object en al haar children

      if ($this->OutputAll
      &&  ($this->OutputLinks) ) {
        if ($this->IncludeObjects) {
          foreach($this->Objects as $childobject) {
            if ($childobject&&method_exists($childobject, "getAllLinks")) {
              $links = $childobject->getAllLinks($links);
            }
          }
        }

        foreach ($this->Links as $link) {
          if ((compare_in_array($link, $links)==false)) {
            $links[] = $link;
          }
        }
      }

      return $links;
    }


    public function outputAll() {
      $output = "";

      if ($this->OutputAll) {
        if ($this->OutputAllFiles) {
          $output .= $this->outputAllFiles();
        }

        if ($this->OutputAllJavascript) {
          $output .= $this->outputAllJavascript();
        }
      }

      return $output;
    }


    public function outputAllFiles() {
      $output = "";

      if ($this->OutputCSSFiles)                {
        $output .= $this->outputCSSFiles();
      } else {
        $output .= $this->outputEvalCSSFiles();
      }

      if ($this->OutputJavascriptFiles)         {
        $output .= $this->outputJavascriptFiles();
      } else {
        $output .= $this->outputEvalJavascriptFiles();
      }

      return $output;
    }


    public function outputAllJavascript() {
      $output = "";

      if ($this->OutputJavascriptFilesAsScript) {$output .= $this->outputJavascriptFilesAsScript();}
      if ($this->OutputJavascriptFunctions)     {$output .= $this->outputJavascriptFunctions(false, ($this->OutputJavascriptFunctionsAsFile ? "jit" : "all"));}
      if ($this->OutputJavascriptGlobals)       {$output .= $this->outputJavascriptGlobals(false, ($this->OutputJavascriptGlobalsAsFile ? "jit" : "all"));}
      if ($this->OutputJavascriptObjects)       {$output .= $this->outputJavascriptObjects(false, ($this->OutputJavascriptObjectsAsFile ? "jit" : "all"));}
      if ($this->OutputInitialJavascript)       {$output .= $this->outputInitialJavascript(false, ($this->OutputInitialJavascriptAsFile ? "jit" : "all"));}
      if ($this->OutputJavascript)              {$output .= $this->outputJavascript(false, ($this->OutputJavascriptAsFile ? "jit" : "all"));}
      if ($this->OutputFinalJavascript)         {$output .= $this->outputFinalJavascript(false, ($this->OutputFinalJavascriptAsFile ? "jit" : "all"));}

      return $output;
    }


    public function outputJavascriptFiles() {
      global $closurecompilerpath,$closurecompilerfound;

      //TODO: Eerst combinen en dan javascriptfiles[] vervangen door combined javascriptfile
      $returnstring = "";

      //init variables
      $clientjavascriptfiles             = array();
      $clientjavascriptfile              = "";
      $serverjavascriptfile              = "";
      $prefixedclientjavascriptfile      = "";
      $timestampserverjavascriptfile     = null;
      $clientcombinedjavascriptfile      = "";
      $servercombinedjavascriptfile      = "";
      $cachedcombinedjavascriptfile      = false;
      $javascriptfilecontents            = "";
      $combinedjavascriptfilename        = $this->ID . "_combined.js";
      $combinedjavascriptfilecontents    = "";
      $timestampcombinedjavascriptfile   = null;

      $clientcombinedjavascriptpath      = $this->CombineJavascriptFilesPathClient;
      $servercombinedjavascriptpath      = __DIR__ . "/../../../" . sb\stripfirstslash($this->CombineJavascriptFilesPathServer);

      $clientcompiledjavascriptfilespath = $this->CompileJavascriptFilesPathClient;
      $servercompiledjavascriptfilespath = __DIR__ . "/../../../" . sb\stripfirstslash($this->CompileJavascriptFilesPathServer);
      $servercompiledjavascriptfile      = "";
      $cachedcompiledjavascriptfile      = false;
      $timestampcompiledjavascriptfile   = null;
      $javaexecutable                    = (isset($_SESSION["java_executable"])) ? $_SESSION["java_executable"] : "java";
      $compilercommand                   = "";
      $compilationlevel                  = $this->CompilationLevelJavascriptFiles;

      //javascript files
      if ($this->OutputAll
      &&  $this->OutputJavascriptFiles) {
        //alle javascriptfiles ophalen
        $clientjavascriptfiles = array_filter($this->getAllJavascriptFiles(array(), $this->OutputJavascriptFilesChildsFirst));

        if (count($clientjavascriptfiles)>0) {
          //script opzetten
          $returnstring .= PHP_EOL;

          //comment
          if ($this->OutputComments) {
            $returnstring .= "      <!-- BEGIN Javascript Files -->" . PHP_EOL;
          }

          //use cached combined javascriptfile?
          if ($this->CombineJavascriptFiles) {
            $clientcombinedjavascriptfile = $clientcombinedjavascriptpath . "/" . $combinedjavascriptfilename;
            $servercombinedjavascriptfile = $servercombinedjavascriptpath . "/" . $combinedjavascriptfilename;

            if (file_exists($servercombinedjavascriptfile)) {
              $cachedcombinedjavascriptfile = true;

              //check of er nieuwere javascriptfiles zijn
              $timestampcombinedjavascriptfile = filemtime($servercombinedjavascriptfile);

              foreach($clientjavascriptfiles as $clientjavascriptfile) {
                $serverjavascriptfile = server_path($clientjavascriptfile);

                $timestampserverjavascriptfile = filemtime($serverjavascriptfile);
                if ($timestampserverjavascriptfile > $timestampcombinedjavascriptfile) {
                  //nieuwere javascriptfile gevonden, dus combined file opnieuw aanmaken
                  $cachedcombinedjavascriptfile = false;

                  //delete old combined file
                  unlink($servercombinedjavascriptfile);
                  break;
                }
              }
            }
          }

          //alle javascriptfiles aflopen
          foreach($clientjavascriptfiles as $clientjavascriptfile) {
            //construct server javascript filename
            $clientjavascriptfile = (stripos($clientjavascriptfile, "?") === false) ? $clientjavascriptfile : leftpart($clientjavascriptfile, "?");
            $serverjavascriptfile = (stripos($clientjavascriptfile, "?") === false) ? server_path($clientjavascriptfile) : server_path(leftpart($clientjavascriptfile, "?"));

            //javascript file exists?
            if (file_exists($serverjavascriptfile)) {
              //COMPILE javascript file?
              if ($this->CompileJavascriptFiles
              &&  !$cachedcombinedjavascriptfile
              &&  $closurecompilerfound) {
                set_time_limit(0);

                $servercompiledjavascriptfile      = $servercompiledjavascriptfilespath . "/" . str_replace(".js", ".min.js", basename($clientjavascriptfile));
                $clientcompiledjavascriptfile      = $clientcompiledjavascriptfilespath . "/" . str_replace(".js", ".min.js", basename($clientjavascriptfile));

                //compiled javascript file already exists?
                if (file_exists($servercompiledjavascriptfile)) {
                  $timestampserverjavascriptfile   = filemtime($serverjavascriptfile);
                  $timestampcompiledjavascriptfile = filemtime($servercompiledjavascriptfile);
                  if ($timestampserverjavascriptfile > $timestampcompiledjavascriptfile) {
                    $cachedcompiledjavascriptfile = false;
                  } else {
                    $cachedcompiledjavascriptfile = true;
                  }
                } else {
                  $cachedcompiledjavascriptfile = false;
                }

                //use cached compiled javascript file?
                if (!$cachedcompiledjavascriptfile) {
                  //(re)create new compiled javascript file
                  $matches  = array();
                  $fh       = fopen($serverjavascriptfile, "r");
                  $fdata    = fread($fh, 256);
                  fclose($fh);

                  //search for @compilation_level in javascript file
                  preg_match_all('/@compilation_level\s*(\w*)/i', $fdata, $matches);

                  //found @compilation_level? then use it
                  if (isset($matches[1][0])) {
                    $compilationlevel = $matches[1][0];
                  } else {
                    $compilationlevel = $this->CompilationLevelJavascriptFiles;
                  }

                  //TODO: eigenlijk buiten de foreach
                  mkpath(filename_path($servercompiledjavascriptfile));

                  //setup compiler command
                  $compilercommand = sprintf($javaexecutable . " -jar %s/compiler.jar --js %s --js_output_file %s --compilation_level %s", $closurecompilerpath, $serverjavascriptfile, $servercompiledjavascriptfile , $compilationlevel);

                  //compile javascript
                  exec($compilercommand, $return, $code);

                  //compile ok? then use compiled javascript file, otherwise use original javascript file
                  if ($code == 0) {
                    $serverjavascriptfile = $servercompiledjavascriptfile;
                    $clientjavascriptfile = $clientcompiledjavascriptfile;
                  } else {
                    fbb("Compile error : " . $code . " in " . $servercompiledjavascriptfile, $compilercommand);
                  }
                } else {
                  //use cached compiled javascriptfile
                  $serverjavascriptfile = $servercompiledjavascriptfile;
                  $clientjavascriptfile = $clientcompiledjavascriptfile;
                }
              }
            }

            if (!$this->CombineJavascriptFiles
            ||  startswith($clientjavascriptfile, "http")
            ||  startswith($clientjavascriptfile, "*")) {
              //SEPARATE javascriptfiles
              $clientjavascriptfile = add_timestamp(trimstringleft($clientjavascriptfile, "*"));

              $prefixedclientjavascriptfile = startswith($clientjavascriptfile, "/") ? $this->OutputPathPrefix . $clientjavascriptfile : $clientjavascriptfile;

              //timing
              if ($this->TimingScript) {
                $returnstring .= "    <script type=\"application/javascript\" >var timerstart_js = new Date();</script>" . PHP_EOL;
              }

              $returnstring .= "      <script type=\"application/javascript\" src=\"{$prefixedclientjavascriptfile}\"></script>" . PHP_EOL;

              //timing
              if ($this->TimingScript) {
                $returnstring .= "    <script type=\"application/javascript\" >var duration = ((new Date() - timerstart_js) / 1000); if (duration >= " . $this->TimingLimit . ") {console.log('" . basename($clientjavascriptfile) . " : ' + ((new Date() - timerstart_js) / 1000) + ' seconden');}</script>" . PHP_EOL;
              }

            } else {
              //COMBINED javascriptfile
              if (!$cachedcombinedjavascriptfile) {
                //timing
                if ($this->TimingScript) {
                  $javascriptfilecontents = "var timerstart_js = new Date();";
                } else {
                  $javascriptfilecontents = "";
                }

                //retrieve javascript file contents
                $javascriptfilecontents .= file_get_contents($serverjavascriptfile);

                //remove BOM codes, sommige files bevatten ï»¿, "BOM codes" uit eoa editor zo blijkt
                $javascriptfilecontents = preg_replace('/\x{EF}\x{BB}\x{BF}/','', $javascriptfilecontents);

                //timing
                if ($this->TimingScript) {
                  $javascriptfilecontents .= "var duration = ((new Date() - timerstart_js) / 1000); if (duration >= " . $this->TimingLimit . ") {console.log('" . basename($clientjavascriptfile) . " : ' + ((new Date() - timerstart_js) / 1000) + ' seconden');}";
                }

                //append file contents to combined file contents
                $combinedjavascriptfilecontents .= "/* " . $clientjavascriptfile . "*/ " . PHP_EOL . $javascriptfilecontents . PHP_EOL . PHP_EOL;
              }
            }
          }

          //COMBINE javascriptfiles?
          if ($this->CombineJavascriptFiles) {
            if (!$cachedcombinedjavascriptfile) {
              //(re)create combined javascript file
              $filehandle = fopen($servercombinedjavascriptfile, "a+");
              if (is_writable($servercombinedjavascriptfile)) {
                chmod($servercombinedjavascriptfile, 0755);
                fwrite($filehandle, $combinedjavascriptfilecontents);
                fclose($filehandle);
              }
            }

            $prefixedclientcombinedjavascriptfile = startswith($clientcombinedjavascriptfile, "/") ? $this->OutputPathPrefix . $clientcombinedjavascriptfile : $clientcombinedjavascriptfile;
            $returnstring .= "      <script type=\"application/javascript\" src=\"" . add_timestamp($prefixedclientcombinedjavascriptfile) . "\"></script>" . PHP_EOL;
          }

          //script loadedobjects
          if ($this->OutputLoadedObjects) {
            if (count($clientjavascriptfiles) > 0) {
              $returnstring .= PHP_EOL;

              //comment
              if ($this->OutputComments) {
                $returnstring .= "      <!-- Javascript LoadedObjects array -->" . PHP_EOL;
              }

              //script tag
              $returnstring .= "      <script type=\"application/javascript\">" . PHP_EOL;
              $returnstring .= "        if (typeof(LoadedObjects)=='undefined') {" . PHP_EOL;
              $returnstring .= "          LoadedObjects = [];" . PHP_EOL;
              $returnstring .= "        }" . PHP_EOL;

              //javascriptfiles
              foreach($clientjavascriptfiles as $clientjavascriptfile) {
                //if ($this->CompileJavascriptFiles
                //&&  !$cachedcombinedjavascriptfile) {
                //  $clientjavascriptfile      = str_replace(".js", ".min.js", basename($clientjavascriptfile));
                //}
                $serverjavascriptfile = server_path($clientjavascriptfile);
                if (file_exists($serverjavascriptfile)) {
                  $serverjavascripttimestamp = filemtime($serverjavascriptfile);
                } else {
                  $serverjavascripttimestamp = time();
                }

                $prefixedclientjavascriptfile = startswith($clientjavascriptfile, "/") ? $this->OutputPathPrefix . $clientjavascriptfile : $clientjavascriptfile;
                $returnstring .= "        LoadedObjects.push('" . $prefixedclientjavascriptfile . ":" . $serverjavascripttimestamp . "');" . PHP_EOL;
              }

              $returnstring .= "      </script>" . PHP_EOL;

              //comment
              if ($this->OutputComments) {
                $returnstring .= "      <!-- END Javascript Files -->" . PHP_EOL;
              }
            }
          }
        }
      }

      return $returnstring;
    }

/*
    public function outputHeadObjects($position="ALL") {
      outputHeadObjects_JavascriptFile($position);
    }
*/

    public function outputHeadObjects_JavascriptFile($position="ALL") {
      global $closurecompilerpath,$closurecompilerfound;

      $returnstring = "";

      //javascript files
      if ($this->OutputAll
      &&  $this->OutputJavascriptFiles) {
        //alle javascriptfiles ophalen
        $clientjavascriptfileobjects = $this->getAllJavascriptFileobjects(array(), $this->OutputJavascriptFilesChildsFirst, $position);
        if (count($clientjavascriptfileobjects)>0) {
          //script opzetten
          $returnstring .= PHP_EOL;

          //comment
          if ($this->OutputComments) {
            $returnstring .= "      <!-- BEGIN HeadObject Javascript Files -->" . PHP_EOL;
          }

          //alle headobjects aflopen
          foreach($clientjavascriptfileobjects as $clientjavascriptfileobject) {
            //construct server javascript filename
            $clientjavascriptfile = (stripos($clientjavascriptfileobject->Filename, "?") === false) ? $clientjavascriptfileobject->Filename : leftpart($clientjavascriptfileobject->Filename, "?");
            $serverjavascriptfile = (stripos($clientjavascriptfileobject->Filename, "?") === false) ? server_path($clientjavascriptfileobject->Filename) : server_path(leftpart($clientjavascriptfileobject->Filename, "?"));

            //SEPARATE javascriptfiles
            $clientjavascriptfile = add_timestamp($clientjavascriptfile, $clientjavascriptfileobject->Timestamp);

            $prefixedclientjavascriptfile = startswith($clientjavascriptfile, "/") ? $this->OutputPathPrefix . $clientjavascriptfile : $clientjavascriptfile;
            $returnstring .= "      <script type=\"application/javascript\" src=\"{$prefixedclientjavascriptfile}\"></script>" . PHP_EOL;
          }

          //script loadedobjects
          if ($this->OutputLoadedObjects) {
            if (count($clientjavascriptfileobjects) > 0) {
              $returnstring .= PHP_EOL;

              //comment
              if ($this->OutputComments) {
                $returnstring .= "      <!-- Javascript LoadedObjects array -->" . PHP_EOL;
              }

              //script tag
              $returnstring .= "      <script type=\"application/javascript\">" . PHP_EOL;
              $returnstring .= "        if (typeof(LoadedObjects)=='undefined') {" . PHP_EOL;
              $returnstring .= "          LoadedObjects = [];" . PHP_EOL;
              $returnstring .= "        }" . PHP_EOL;

              //javascriptfiles
              foreach($clientjavascriptfileobjects as $clientjavascriptfileobject) {
                //if ($this->CompileJavascriptFiles
                //&&  !$cachedcombinedjavascriptfile) {
                //  $clientjavascriptfile      = str_replace(".js", ".min.js", basename($clientjavascriptfile));
                //}
                $serverjavascriptfile = server_path($clientjavascriptfileobject->Filename);
                if (file_exists($serverjavascriptfile)) {
                  $serverjavascripttimestamp = filemtime($serverjavascriptfile);
                } else {
                  $serverjavascripttimestamp = time();
                }

                $prefixedclientjavascriptfile = startswith($clientjavascriptfileobject->Filename, "/") ? $this->OutputPathPrefix . $clientjavascriptfileobject->Filename : $clientjavascriptfileobject->Filename;
                $returnstring .= "        LoadedObjects.push('" . $prefixedclientjavascriptfile . ":" . $serverjavascripttimestamp . "');" . PHP_EOL;
              }

              $returnstring .= "      </script>" . PHP_EOL;

              //comment
              if ($this->OutputComments) {
                $returnstring .= "      <!-- END HeadObject Javascript  Files -->" . PHP_EOL;
              }
            }
          }
        }
      }

      return $returnstring;
    }


    public function outputHeadObjects_CSSFile($position="ALL") {
      global $closurecompilerpath,$closurecompilerfound;

      $returnstring = "";

      //javascript files
      if ($this->OutputAll
      &&  $this->OutputCSSFiles) {
        //alle javascriptfiles ophalen
        $clientcssfileobjects = $this->getAllCSSFileobjects(array(), $this->OutputCSSFilesChildsFirst, $position);
        if (count($clientcssfileobjects)>0) {
          //script opzetten
          $returnstring .= PHP_EOL;

          //comment
          if ($this->OutputComments) {
            $returnstring .= "      <!-- BEGIN HeadObject CSS Files -->" . PHP_EOL;
          }

          //alle headobjects aflopen
          foreach($clientcssfileobjects as $clientcssfileobject) {
            //construct server css filename
            $clientcssfile = (stripos($clientcssfileobject->Filename, "?") === false) ? $clientcssfileobject->Filename : leftpart($clientcssfileobject->Filename, "?");
            $servercssfile = (stripos($clientcssfileobject->Filename, "?") === false) ? server_path($clientcssfileobject->Filename) : server_path(leftpart($clientcssfileobject->Filename, "?"));

            //SCSS converteren naar CSS?
            if ($scssphpfound) {
            	if (contains($servercssfile,".scss")) {
	            	$scss = new scssc();
								$scss->setFormatter("scss_formatter");
								$scss->addImportPath(filename_path($servercssfile));

								$scssIn = removecomments(file_get_contents($servercssfile));
								$cssOut = $scss->compile($scssIn);
								$clientcssfile         = str_replace(".scss", "_scss.css", $clientcssfile);
	              $servercssfile         = str_replace(".scss", "_scss.css", $servercssfile);

	              unset($scss);

	              file_put_contents($servercssfile, $cssOut);
          		}
						}

            //SEPARATE cssfiles
            $clientcssfile = add_timestamp($clientcssfile, $clientcssfileobject->Timestamp);

            $prefixedclientcssfile = startswith($clientcssfile, "/") ? $this->OutputPathPrefix . $clientcssfile : $clientcssfile;
            $returnstring .= "      <link rel='stylesheet' type='text/css' href='{$prefixedclientcssfile}' />" . PHP_EOL;
          }

          //script loadedobjects
          if ($this->OutputLoadedObjects) {
            if (count($clientcssfileobjects) > 0) {
              $returnstring .= PHP_EOL;

              //comment
              if ($this->OutputComments) {
                $returnstring .= "      <!-- CSS LoadedObjects array -->" . PHP_EOL;
              }

              //script tag
              $returnstring .= "      <script type=\"application/javascript\">" . PHP_EOL;
              $returnstring .= "        if (typeof(LoadedObjects)=='undefined') {" . PHP_EOL;
              $returnstring .= "          LoadedObjects = [];" . PHP_EOL;
              $returnstring .= "        }" . PHP_EOL;

              //cssfiles
              foreach($clientcssfileobjects as $clientcssfileobject) {
                //if ($this->CompileCSSFiles
                //&&  !$cachedcombinedcssfile) {
                //  $clientcssfile      = str_replace(".js", ".min.js", basename($clientcssfile));
                //}
                $servercssfile = server_path($clientcssfileobject->Filename);
                if (file_exists($servercssfile)) {
                  $servercsstimestamp = filemtime($servercssfile);
                } else {
                  $servercsstimestamp = time();
                }

                $prefixedclientcssfile = startswith($clientcssfileobject->Filename, "/") ? $this->OutputPathPrefix . $clientcssfileobject->Filename : $clientcssfileobject->Filename;
                $returnstring .= "        LoadedObjects.push('" . $prefixedclientcssfile . ":" . $servercsstimestamp . "');" . PHP_EOL;
              }

              $returnstring .= "      </script>" . PHP_EOL;

              //comment
              if ($this->OutputComments) {
                $returnstring .= "      <!-- END HeadObject CSS  Files -->" . PHP_EOL;
              }
            }
          }
        }
      }

      return $returnstring;
    }


/*
    public function outputHeadObjects_JavascriptFile() {
      global $closurecompilerpath,$closurecompilerfound;

      //TODO: Eerst combinen en dan javascriptfiles[] vervangen door combined javascriptfile
      $returnstring = "";

      //init variables
      $clientjavascriptfiles             = array();
      $clientjavascriptfile              = "";
      $serverjavascriptfile              = "";
      $prefixedclientjavascriptfile      = "";
      $timestampserverjavascriptfile     = null;
      $clientcombinedjavascriptfile      = "";
      $servercombinedjavascriptfile      = "";
      $cachedcombinedjavascriptfile      = false;
      $javascriptfilecontents            = "";
      $combinedjavascriptfilename        = $this->ID . "__headobjects_combined.js";
      $combinedjavascriptfilecontents    = "";
      $timestampcombinedjavascriptfile   = null;

      $clientcombinedjavascriptpath      = $this->CombineJavascriptFilesPathClient;
      $servercombinedjavascriptpath      = __DIR__ . "/../../../" . sb\stripfirstslash($this->CombineJavascriptFilesPathServer);

      $clientcompiledjavascriptfilespath = $this->CompileJavascriptFilesPathClient;
      $servercompiledjavascriptfilespath = __DIR__ . "/../../../" . sb\stripfirstslash($this->CompileJavascriptFilesPathServer);
      $servercompiledjavascriptfile      = "";
      $cachedcompiledjavascriptfile      = false;
      $timestampcompiledjavascriptfile   = null;
      $javaexecutable                    = (isset($_SESSION["java_executable"])) ? $_SESSION["java_executable"] : "java";
      $compilercommand                   = "";
      $compilationlevel                  = $this->CompilationLevelJavascriptFiles;

      //javascript files
      if ($this->OutputAll
      &&  $this->OutputJavascriptFiles) {
        //alle javascriptfiles ophalen
        $clientjavascriptfileobjects = array_filter($this->getAllJavascriptFiles(array(), $this->OutputJavascriptFilesChildsFirst));

        if (count($clientjavascriptfileobjects)>0) {
          //script opzetten
          $returnstring .= PHP_EOL;

          //comment
          if ($this->OutputComments) {
            $returnstring .= "      <!-- BEGIN Javascript Files -->" . PHP_EOL;
          }

          //use cached combined javascriptfile?
          if ($this->CombineJavascriptFiles) {
            $clientcombinedjavascriptfile = $clientcombinedjavascriptpath . "/" . $combinedjavascriptfilename;
            $servercombinedjavascriptfile = $servercombinedjavascriptpath . "/" . $combinedjavascriptfilename;

            if (file_exists($servercombinedjavascriptfile)) {
              $cachedcombinedjavascriptfile = true;

              //check of er nieuwere javascriptfiles zijn
              $timestampcombinedjavascriptfile = filemtime($servercombinedjavascriptfile);

              foreach($clientjavascriptfiles as $clientjavascriptfile) {
                $serverjavascriptfile = server_path($clientjavascriptfile);

                $timestampserverjavascriptfile = filemtime($serverjavascriptfile);
                if ($timestampserverjavascriptfile > $timestampcombinedjavascriptfile) {
                  //nieuwere javascriptfile gevonden, dus combined file opnieuw aanmaken
                  $cachedcombinedjavascriptfile = false;

                  //delete old combined file
                  unlink($servercombinedjavascriptfile);
                  break;
                }
              }
            }
          }

          //alle javascriptfiles aflopen
          foreach($clientjavascriptfiles as $clientjavascriptfile) {
            //construct server javascript filename
            $clientjavascriptfile = (stripos($clientjavascriptfile, "?") === false) ? $clientjavascriptfile : leftpart($clientjavascriptfile, "?");
            $serverjavascriptfile = (stripos($clientjavascriptfile, "?") === false) ? server_path($clientjavascriptfile) : server_path(leftpart($clientjavascriptfile, "?"));

            //javascript file exists?
            if (file_exists($serverjavascriptfile)) {
              //COMPILE javascript file?
              if ($this->CompileJavascriptFiles
              &&  !$cachedcombinedjavascriptfile
              &&  $closurecompilerfound) {
                $servercompiledjavascriptfile      = $servercompiledjavascriptfilespath . "/" . str_replace(".js", ".min.js", basename($clientjavascriptfile));
                $clientcompiledjavascriptfile      = $clientcompiledjavascriptfilespath . "/" . str_replace(".js", ".min.js", basename($clientjavascriptfile));

                //compiled javascript file already exists?
                if (file_exists($servercompiledjavascriptfile)) {
                  $timestampserverjavascriptfile   = filemtime($serverjavascriptfile);
                  $timestampcompiledjavascriptfile = filemtime($servercompiledjavascriptfile);
                  if ($timestampserverjavascriptfile > $timestampcompiledjavascriptfile) {
                    $cachedcompiledjavascriptfile = false;
                  } else {
                    $cachedcompiledjavascriptfile = true;
                  }
                } else {
                  $cachedcompiledjavascriptfile = false;
                }

                //use cached compiled javascript file?
                if (!$cachedcompiledjavascriptfile) {
                  //(re)create new compiled javascript file
                  $matches  = array();
                  $fh       = fopen($serverjavascriptfile, "r");
                  $fdata    = fread($fh, 256);
                  fclose($fh);

                  //search for @compilation_level in javascript file
                  preg_match_all('/@compilation_level\s*(\w*)/i', $fdata, $matches);

                  //found @compilation_level? then use it
                  if (isset($matches[1][0])) {
                    $compilationlevel = $matches[1][0];
                  } else {
                    $compilationlevel = $this->CompilationLevelJavascriptFiles;
                  }

                  //TODO: eigenlijk buiten de foreach
                  mkpath(filename_path($servercompiledjavascriptfile));

                  //setup compiler command
                  $compilercommand = sprintf($javaexecutable . " -jar %s/compiler.jar --js %s --js_output_file %s --compilation_level %s", $closurecompilerpath, $serverjavascriptfile, $servercompiledjavascriptfile , $compilationlevel);

                  //compile javascript
                  exec($compilercommand, $return, $code);

                  //compile ok? then use compiled javascript file, otherwise use original javascript file
                  if ($code == 0) {
                    $serverjavascriptfile = $servercompiledjavascriptfile;
                    $clientjavascriptfile = $clientcompiledjavascriptfile;
                  } else {
                    fbb("Compile error : " . $code . " in " . $servercompiledjavascriptfile, $compilercommand);
                  }
                } else {
                  //use cached compiled javascriptfile
                  $serverjavascriptfile = $servercompiledjavascriptfile;
                  $clientjavascriptfile = $clientcompiledjavascriptfile;
                }
              }
            }

            if (!$this->CombineJavascriptFiles) {
              //SEPARATE javascriptfiles
              $clientjavascriptfile = add_timestamp($clientjavascriptfile);

              $prefixedclientjavascriptfile = startswith($clientjavascriptfile, "/") ? $this->OutputPathPrefix . $clientjavascriptfile : $clientjavascriptfile;
              $returnstring .= "      <script type=\"application/javascript\" src=\"{$prefixedclientjavascriptfile}\"></script>" . PHP_EOL;
            } else {
              //COMBINED javascriptfile
              if (!$cachedcombinedjavascriptfile) {

                //retrieve javascript file contents
                $javascriptfilecontents = file_get_contents($serverjavascriptfile);

                //remove BOM codes, sommige files bevatten ï»¿, "BOM codes" uit eoa editor zo blijkt
                $javascriptfilecontents = preg_replace('/\x{EF}\x{BB}\x{BF}/','', $javascriptfilecontents);

                //append file contents to combined file contents
                $combinedjavascriptfilecontents .= "/* " . $clientjavascriptfile . "* / " . PHP_EOL . $javascriptfilecontents . PHP_EOL . PHP_EOL;
              }
            }
          }

          //COMBINE javascriptfiles?
          if ($this->CombineJavascriptFiles) {
            if (!$cachedcombinedjavascriptfile) {
              //(re)create combined javascript file
              $filehandle = fopen($servercombinedjavascriptfile, "a+");
              if (is_writable($servercombinedjavascriptfile)) {
                chmod($servercombinedjavascriptfile, 0755);

                fwrite($filehandle, $combinedjavascriptfilecontents);
                fclose($filehandle);
              }
            }

            $prefixedclientcombinedjavascriptfile = startswith($clientcombinedjavascriptfile, "/") ? $this->OutputPathPrefix . $clientcombinedjavascriptfile : $clientcombinedjavascriptfile;
            $returnstring .= "      <script type=\"application/javascript\" src=\"" . add_timestamp($prefixedclientcombinedjavascriptfile) . "\"></script>" . PHP_EOL;
          }


          //script loadedobjects
          if ($this->OutputLoadedObjects) {
            if (count($clientjavascriptfiles) > 0) {
              $returnstring .= PHP_EOL;

              //comment
              if ($this->OutputComments) {
                $returnstring .= "      <!-- Javascript LoadedObjects array -->" . PHP_EOL;
              }

              //script tag
              $returnstring .= "      <script type=\"application/javascript\">" . PHP_EOL;
              $returnstring .= "        if (typeof(LoadedObjects)=='undefined') {" . PHP_EOL;
              $returnstring .= "          LoadedObjects = [];" . PHP_EOL;
              $returnstring .= "        }" . PHP_EOL;

              //javascriptfiles
              foreach($clientjavascriptfiles as $clientjavascriptfile) {
                //if ($this->CompileJavascriptFiles
                //&&  !$cachedcombinedjavascriptfile) {
                //  $clientjavascriptfile      = str_replace(".js", ".min.js", basename($clientjavascriptfile));
                //}
                $serverjavascriptfile = server_path($clientjavascriptfile);
                if (file_exists($serverjavascriptfile)) {
                  $serverjavascripttimestamp = filemtime($serverjavascriptfile);
                } else {
                  $serverjavascripttimestamp = time();
                }

                $prefixedclientjavascriptfile = startswith($clientjavascriptfile, "/") ? $this->OutputPathPrefix . $clientjavascriptfile : $clientjavascriptfile;
                $returnstring .= "        LoadedObjects.push('" . $prefixedclientjavascriptfile . ":" . $serverjavascripttimestamp . "');" . PHP_EOL;
              }

              $returnstring .= "      </script>" . PHP_EOL;

              //comment
              if ($this->OutputComments) {
                $returnstring .= "      <!-- END Javascript Files -->" . PHP_EOL;
              }
            }
          }
        }
      }
      return $returnstring;
    }
*/


    public function outputEvalJavascriptFiles() {
      $returnstring = "";

      //javascript files
      if ($this->OutputAll
      &&  $this->OutputEvalJavascriptFiles) {
        $javascriptfiles = array_filter($this->getAllEvalJavascriptFiles(array()));

        if (count($javascriptfiles)>0) {
          $returnstring .= PHP_EOL;

          //comment
          if ($this->OutputComments) {
            $returnstring .= "<!-- BEGIN Evaluate Javascript Files -->" . PHP_EOL;
          }

          //evealjavascript files
          if (count($javascriptfiles)>0) {
            $returnstring .= "<script type=\"application/javascript\">" . PHP_EOL;
            foreach($javascriptfiles as $javascriptfile) {
              $prefixedjavascriptfile = startswith($javascriptfile, "/") ? $this->OutputPathPrefix . $javascriptfile : $javascriptfile;
              $serverjavascriptfile = server_path($javascriptfile);

              if (file_exists($serverjavascriptfile)) {
                $serverjavascripttimestamp = filemtime($serverjavascriptfile);
              } else {
                $serverjavascripttimestamp = time();
              }

              $returnstring .= "  ajaxscriptexternal('{$prefixedjavascriptfile}', " . $serverjavascripttimestamp . ");" . PHP_EOL;
              //$returnstring .= "  ajaxloadjscss('{$javascriptfile}', 'js');" . PHP_EOL;
            }
            $returnstring .= "</script>" . PHP_EOL;
          }

          //comment
          if ($this->OutputComments) {
            $returnstring .= "<!-- END Evaluate Javascript Files -->" . PHP_EOL;
          }
        }
      }

      return $returnstring;
    }


    public function outputJavascriptFilesAsScript() {
      //init variables
      $returnstring = "";

      //output samenstellen
      if ($this->OutputAll
      &&  $this->OutputJavascriptFilesAsScript) {
        $javascriptfiles = array_filter($this->getAllJavascriptFiles(array()));

        if (count($javascriptfiles)>0) {
          $returnstring .= PHP_EOL;

          //commment
          if ($this->OutputComments) {
            $returnstring .= "<!-- BEGIN Javascript Files As Scripts -->" . PHP_EOL;
          }

          //javafiles as script
          $returnstring .= "<script id=\"OutputJavascriptFilesAsScript\" type=\"application/javascript\">" . PHP_EOL;
          foreach($javascriptfiles as $javascriptfile) {
            $returnstring .= "  loadExternalScript('$javascriptfile');" . PHP_EOL;
          }
          $returnstring .= "</script>" . PHP_EOL;

          //comment
          if ($this->OutputComments) {
            $returnstring .= "<!-- END Javascript Files As Scripts -->" . PHP_EOL;
          }
        }
      }

      return $returnstring;
    }


    public function outputInitialJavascript($outputforfile=false,$outputfilter="all") {
      //init variables
      $returnstring = "";
      $returnlines  = "";
      $returncount  = 0;

      //output samenstellen
      if ($this->OutputAll
      &&  $this->OutputInitialJavascript) {
        $javascriptlines = array_filter($this->getAllInitialJavascript(array(array()), $this->InitialJavascriptRecursive, $this->InitialJavascriptChildsFirst));

        if (count($javascriptlines)>0) {
          $returnstring .= PHP_EOL;

          //comment
          if (!$outputforfile
          &&  $this->OutputComments) {
            $returnstring .= "  <!-- BEGIN Initial Javascript  -->" . PHP_EOL;
          }

          //script tag
          if (!$outputforfile) {
            $returnstring .= "  <script id=\"OutputInitialJavascriptScript\"  type=\"text/javascript\">" . PHP_EOL;
          }

          //function
          if (!$outputforfile) {
            $returnstring .= "    " . $this->OutputJavascriptContainerHeader . PHP_EOL;
            $returnstring .= "          if (typeof outputInitialJavascriptFile_{$this->getHashJSID()} == 'function') {outputInitialJavascriptFile_{$this->getHashJSID()}(); }" . PHP_EOL . PHP_EOL;
          } else {
            $returnstring .= "    outputInitialJavascriptFile_{$this->getHashJSID()} = function outputInitialJavascriptFile_{$this->getHashJSID()}() {" . PHP_EOL;
          }

          //timing
          if ($this->Timing
          ||  $this->TimingOutput) {
            $returnstring .= "          var timerstart = timerStart();" . PHP_EOL;
          }

          //profiling
          if ($this->Profiling) {
            $returnstring .= "          if (console && console.profile) {console.profile('outputInitialJavascript');}" . PHP_EOL;
          }

          //javascript lines
          foreach(array_keys($javascriptlines) as $javascriptkey) {
            $returnlines = "";
            $returncount = 0;

            //array or string?
            if (is_array($javascriptlines[$javascriptkey])) {
              //javascriptkey is array
              if (!empty($javascriptlines[$javascriptkey])
              &&  !sb\is_empty($javascriptkey)) {
                $returnlines .= ltrim("
          /* {$javascriptkey} */
          if (typeof {$javascriptkey} == 'function') {" . PHP_EOL, PHP_EOL);

                if ($this->Timing
                ||  $this->TimingOutput) {
                  $returnlines .= "            var timerstart2 = timerStart();" . PHP_EOL . PHP_EOL;
                }

                foreach($javascriptlines[$javascriptkey] as $javascriptline) {
                  if (!sb\is_empty($javascriptline)) {
                    if ($outputfilter == "all") {
                      $returnlines .= "  " . ltrim(rtrim($javascriptline, " "), PHP_EOL);
                      $returncount++;
                    } else if ($outputfilter == "jit"
                           && (stripos($javascriptline, "[jit") !== false || stripos($javascriptline, "{jit") !== false) ) {
                      $returnlines .= "  " . ltrim(rtrim($javascriptline, " "), PHP_EOL);
                      $returncount++;
                    } else if ($outputfilter == "nojit"
                           && (stripos($javascriptline, "[jit") === false && stripos($javascriptline, "{jit") === false) ) {
                      $returnlines .= "  " . ltrim(rtrim($javascriptline, " "), PHP_EOL);
                      $returncount++;
                    }
                  }
                }

                if ($this->Timing
                ||  $this->TimingOutput) {
                  $returnlines .= PHP_EOL . "            cl_timer_end(timerstart2, " . $this->TimingLimit . ", 'outputInitialJavascript " . ($outputforfile ? "(file) " : "") . $javascriptkey . "');" . PHP_EOL;
                }

                $returnlines .= ltrim("
          }" . PHP_EOL . PHP_EOL, PHP_EOL);

                if ($returncount > 0) {
                  $returnstring .= $returnlines;
                }
              }
            } else {
              //javascriptkey is string
              if (!sb\is_empty($javascriptlines[$javascriptkey])) {

                if ($outputfilter == "all") {
                  $returnstring .= "  {$javascriptlines[$javascriptkey]}" . PHP_EOL;
                  $returncount++;
                } else if ($outputfilter == "jit"
                       && (stripos($javascriptlines[$javascriptkey], "[jit") !== false || stripos($javascriptlines[$javascriptkey], "{jit") !== false) ) {
                  $returnstring .= "  {$javascriptlines[$javascriptkey]}" . PHP_EOL;
                  $returncount++;
                } else if ($outputfilter == "nojit"
                       && (stripos($javascriptlines[$javascriptkey], "[jit") === false && stripos($javascriptlines[$javascriptkey], "{jit") === false) ) {
                  $returnstring .= "  {$javascriptlines[$javascriptkey]}" . PHP_EOL;
                  $returncount++;
                }
              }
            }
          }

          //profiling end
          if ($this->Profiling) {
            $returnstring .= "          if (console && console.profile) {console.profileEnd();}" . PHP_EOL;
          }

          //timing end
          if ($this->Timing
          ||  $this->TimingOutput) {
            $returnstring .= "          cl_timer_end(timerstart, " . $this->TimingLimit . ", 'outputInitialJavascript TOTAL " . ($outputforfile ? "(file) " : "") . $this->OriginalID . "');" . PHP_EOL;
          }

          //function end
          if (!$outputforfile) {
            $returnstring .= "    " . $this->OutputJavascriptContainerFooter . ";" . PHP_EOL;
          } else {
            $returnstring .= "    };" . PHP_EOL;
          }

          //script tag end
          if (!$outputforfile) {
            $returnstring .= "  </script>" . PHP_EOL;
          }

          //comment end
          if (!$outputforfile
          &&  $this->OutputComments) {
            $returnstring .= "  <!-- END Initial Javascript -->" . PHP_EOL;
          }
        }
      }

      //if ($returncount == 0) {
      //  $returnstring = "";
      //}

      return $returnstring;
    }


    public function outputJavascript($outputforfile=false, $outputfilter="all") {
      //init variables
      $returnstring = "";
      $returnlines  = "";
      $returncount  = 0;

      //output samenstellen
      if ($this->OutputAll
      &&  $this->OutputJavascript) {
        $javascriptlines = array_filter($this->getAllJavascript(array(array()), $this->JavascriptRecursive, $this->JavascriptChildsFirst));

        if (count($javascriptlines)>0) {
          $returnstring .= PHP_EOL;

          //comment
          if (!$outputforfile
          &&  $this->OutputComments) {
            $returnstring .= "  <!-- BEGIN Javascript  -->" . PHP_EOL;
          }

          //script tag
          if (!$outputforfile) {
            $returnstring .= "  <script id=\"outputJavascriptScript\" type=\"text/javascript\">" . PHP_EOL;
          }

          //function
          if (!$outputforfile) {
            $returnstring .= "    " . $this->OutputJavascriptContainerHeader . PHP_EOL;
            $returnstring .= "          if (typeof outputJavascriptFile_{$this->getHashJSID()} == 'function') {typeof outputJavascriptFile_{$this->getHashJSID()}();}" . PHP_EOL . PHP_EOL;
          } else {
            $returnstring .= "    outputJavascriptFile_{$this->getHashJSID()} = function outputJavascriptFile_{$this->getHashJSID()}() {" . PHP_EOL;
          }

          //timing
          if ($this->Timing
          ||  $this->TimingOutput) {
            $returnstring .= "          var timerstart = timerStart();" . PHP_EOL;
          }

          //profiling
          if ($this->Profiling) {
            $returnstring .= "          if (console && console.profile) {console.profile('outputJavascript');}" . PHP_EOL;
          }

          //javascript lines
          foreach(array_keys($javascriptlines) as $javascriptkey) {
            $returnlines = "";
            $returncount = 0;

            //array or string?
            if (is_array($javascriptlines[$javascriptkey])) {
              //javascriptkey is array
              if (!empty($javascriptlines[$javascriptkey])
              &&  !sb\is_empty($javascriptkey)) {
                $returnlines .= ltrim("
          /* {$javascriptkey} */
          if (typeof {$javascriptkey} == 'function') {" . PHP_EOL, PHP_EOL);

                if ($this->Timing
                ||  $this->TimingOutput) {
                  $returnlines .= "            var timerstart2 = timerStart();" . PHP_EOL . PHP_EOL;
                }

                foreach($javascriptlines[$javascriptkey] as $javascriptline) {
                  if (!sb\is_empty($javascriptline)) {
                    if ($outputfilter == "all") {
                      $returnlines .= "  " . ltrim(rtrim($javascriptline, " "), PHP_EOL);
                      $returncount++;
                    } else if ($outputfilter == "jit"
                           && (stripos($javascriptline, "[jit") !== false || stripos($javascriptline, "{jit") !== false) ) {
                      $returnlines .= "  " . ltrim(rtrim($javascriptline, " "), PHP_EOL);
                      $returncount++;
                    } else if ($outputfilter == "nojit"
                           && (stripos($javascriptline, "[jit") === false && stripos($javascriptline, "{jit") === false) ) {
                      $returnlines .= "  " . ltrim(rtrim($javascriptline, " "), PHP_EOL);
                      $returncount++;
                    }
                  }
                }

                if ($this->Timing
                ||  $this->TimingOutput) {
                  $returnlines .= PHP_EOL . "            cl_timer_end(timerstart2, " . $this->TimingLimit . ", 'outputJavascript " . ($outputforfile ? "(file) " : "") . $javascriptkey . "');" . PHP_EOL;
                }

                $returnlines .= ltrim("
          }" . PHP_EOL . PHP_EOL, PHP_EOL);

                if ($returncount > 0) {
                  $returnstring .= $returnlines;
                }
              }
            } else {
              //javascriptkey is string
              if (!sb\is_empty($javascriptlines[$javascriptkey])) {
                if ($outputfilter == "all") {
                  $returnstring .= "  {$javascriptlines[$javascriptkey]}" . PHP_EOL;
                  $returncount++;
                } else if ($outputfilter == "jit"
                       && (stripos($javascriptlines[$javascriptkey], "[jit") !== false || stripos($javascriptlines[$javascriptkey], "{jit") !== false) ) {
                  $returnstring .= "  {$javascriptlines[$javascriptkey]}" . PHP_EOL;
                  $returncount++;
                } else if ($outputfilter == "nojit"
                       && (stripos($javascriptlines[$javascriptkey], "[jit") === false && stripos($javascriptlines[$javascriptkey], "{jit") === false) ) {
                  $returnstring .= "  {$javascriptlines[$javascriptkey]}" . PHP_EOL;
                  $returncount++;
                }
              }
            }
          }

          //profiling end
          if ($this->Profiling) {
            $returnstring .= "          if (console && console.profile) {console.profileEnd();}" . PHP_EOL;
          }

          //timing end
          if ($this->Timing
          ||  $this->TimingOutput) {
            $returnstring .= "          cl_timer_end(timerstart, " . $this->TimingLimit . ", 'outputJavascript TOTAL " . ($outputforfile ? "(file) " : "") . $this->OriginalID . "');" . PHP_EOL;
          }

          //function end
          if (!$outputforfile) {
            $returnstring .= "    " . $this->OutputJavascriptContainerFooter . ";" . PHP_EOL;
          } else {
            $returnstring .= "    };" . PHP_EOL;
          }

          // script tag end
          if (!$outputforfile) {
            $returnstring .= "  </script>" . PHP_EOL;
          }

          //comment end
          if (!$outputforfile
          &&  $this->OutputComments) {
            $returnstring .= "  <!-- END Javascript -->" . PHP_EOL;
          }
        }
      }

      //if ($returncount == 0) {
      //  $returnstring = "";
      //}

      return $returnstring;
    }


    public function outputFinalJavascript($outputforfile=false,$outputfilter="all") {
      //init variables
      $returnstring = "";
      $returnlines  = "";
      $returncount  = 0;

      //output samenstellen
      if ($this->OutputAll
      &&  $this->OutputFinalJavascript) {
        $javascriptlines = array_filter($this->getAllFinalJavascript(array(array()), $this->FinalJavascriptRecursive, $this->FinalJavascriptChildsFirst));

        if (count($javascriptlines)>0) {
          $returnstring .= PHP_EOL;

          //comment
          if (!$outputforfile
          &&  $this->OutputComments) {
            $returnstring .= "  <!-- BEGIN Final Javascript  -->" . PHP_EOL;
          }

          //script tag
          if (!$outputforfile) {
            $returnstring .= "  <script id=\"OutputFinalJavascriptScript\"  type=\"application/javascript\">" . PHP_EOL;
          }

          //function
          if (!$outputforfile) {
            $returnstring .= "    " . $this->OutputJavascriptContainerHeader . PHP_EOL;
            $returnstring .= "          if (typeof outputFinalJavascriptFile_{$this->getHashJSID()} == 'function') {outputFinalJavascriptFile_{$this->getHashJSID()}();}" . PHP_EOL . PHP_EOL;
          } else {
            $returnstring .= "    outputFinalJavascriptFile_{$this->getHashJSID()} = function outputFinalJavascriptFile_{$this->getHashJSID()}() {" . PHP_EOL;
          }

          //timing
          if ($this->Timing
          ||  $this->TimingOutput) {
            $returnstring .= "          var timerstart = timerStart();" . PHP_EOL;
          }

          //profiling
          if ($this->Profiling) {
            $returnstring .= "          if (console && console.profile) {console.profile('outputFinalJavascript');}" . PHP_EOL;
          }

          //javascript lines
          foreach(array_keys($javascriptlines) as $javascriptkey) {
            $returnlines = "";
            $returncount = 0;

            //array or string?
            if (is_array($javascriptlines[$javascriptkey])) {
              //javascriptkey is array
              if (!empty($javascriptlines[$javascriptkey])
              &&  !sb\is_empty($javascriptkey)) {
                $returnlines .= ltrim("
          /* {$javascriptkey} */
          if (typeof {$javascriptkey} == 'function') {" . PHP_EOL, PHP_EOL);

                if ($this->Timing
                ||  $this->TimingOutput) {
                  $returnlines .= "            var timerstart2 = timerStart();" . PHP_EOL . PHP_EOL;
                }

                foreach($javascriptlines[$javascriptkey] as $javascriptline) {
                  if (!sb\is_empty($javascriptline)) {
                    if ($outputfilter == "all") {
                      $returnlines .= "  " . ltrim(rtrim($javascriptline, " "), PHP_EOL);
                      $returncount++;
                    } else if ($outputfilter == "jit"
                           && (stripos($javascriptline, "[jit") !== false || stripos($javascriptline, "{jit") !== false) ) {
                      $returnlines .= "  " . ltrim(rtrim($javascriptline, " "), PHP_EOL);
                      $returncount++;
                    } else if ($outputfilter == "nojit"
                           && (stripos($javascriptline, "[jit") === false && stripos($javascriptline, "{jit") === false) ) {
                      $returnlines .= "  " . ltrim(rtrim($javascriptline, " "), PHP_EOL);
                      $returncount++;
                    }
                  }
                }

                if ($this->Timing
                ||  $this->TimingOutput) {
                  $returnlines .= PHP_EOL . "            cl_timer_end(timerstart2, " . $this->TimingLimit . ", 'outputFinalJavascript " . ($outputforfile ? "(file) " : "") . $javascriptkey . "');" . PHP_EOL;
                }

                $returnlines .= ltrim("
          }" . PHP_EOL . PHP_EOL, PHP_EOL);

                if ($returncount > 0) {
                  $returnstring .= $returnlines;
                }
              }
            } else {
              //javascriptkey is string
              if (!sb\is_empty($javascriptlines[$javascriptkey])) {
                if ($outputfilter == "all") {
                  $returnstring .= "  {$javascriptlines[$javascriptkey]}" . PHP_EOL;
                  $returncount++;
                } else if ($outputfilter == "jit"
                       && (stripos($javascriptlines[$javascriptkey], "[jit") !== false || stripos($javascriptlines[$javascriptkey], "{jit") !== false) ) {
                  $returnstring .= "  {$javascriptlines[$javascriptkey]}" . PHP_EOL;
                  $returncount++;
                } else if ($outputfilter == "nojit"
                       && (stripos($javascriptlines[$javascriptkey], "[jit") === false && stripos($javascriptlines[$javascriptkey], "{jit") === false) ) {
                  $returnstring .= "  {$javascriptlines[$javascriptkey]}" . PHP_EOL;
                  $returncount++;
                }
              }
            }
          }

          //end profiling
          if ($this->Profiling) {
            $returnstring .= "          if (console && console.profile) {console.profileEnd();}" . PHP_EOL;
          }

          //timing end
          if ($this->Timing
          ||  $this->TimingOutput) {
            $returnstring .= "          cl_timer_end(timerstart, " . $this->TimingLimit . ", 'outputFinalJavascript TOTAL " . ($outputforfile ? "(file) " : "") . $this->OriginalID . "');" . PHP_EOL;
          }

          //function end
          if (!$outputforfile) {
            $returnstring .= "    " . $this->OutputJavascriptContainerFooter . ";" . PHP_EOL;
          } else {
            $returnstring .= "    };" . PHP_EOL;
            }

          //script tag end
          if (!$outputforfile) {
            $returnstring .= "  </script>" . PHP_EOL;
          }

          //comment end
          if (!$outputforfile
          &&  $this->OutputComments) {
            $returnstring .= "  <!-- END Final Javascript -->" . PHP_EOL;
          }
        }
      }

      //if ($returncount == 0) {
      //  $returnstring = "";
      //}

      return $returnstring;
    }


    public function outputJavascriptFunctions($outputforfile=false,$outputfilter="all") {
      //init variables
      $returnstring = "";
      $returnlines  = "";
      $returncount  = 0;

      //output samenstellen
      if ($this->OutputAll
      &&  $this->OutputJavascriptFunctions) {
        $javascriptfunctions = array_filter($this->getAllJavascriptFunctions(array()));

        if (count($javascriptfunctions)>0) {
          $returnstring .= PHP_EOL;

          //comment
          if (!$outputforfile
          &&  $this->OutputComments) {
            $returnstring .= "<!-- BEGIN Javascript Functions -->" . PHP_EOL;
          }

          //script tag
          if (!$outputforfile) {
            $returnstring .= "<script id=\"OutputJavascriptFunctionsScript\"  type=\"application/javascript\">" . PHP_EOL;
          }

          //javascript functions
          foreach($javascriptfunctions as $javascriptline) {
            if ($outputfilter == "all") {
              $returnstring .= ltrim(rtrim($javascriptline, " "), PHP_EOL);
              $returncount++;
            } else if ($outputfilter == "jit"
                   && (stripos($javascriptline, "[jit") !== false || stripos($javascriptline, "{jit") !== false) ) {
              $returnlines .= "  " . ltrim(rtrim($javascriptline, " "), PHP_EOL);
              $returncount++;
            } else if ($outputfilter == "nojit"
                   && (stripos($javascriptline, "[jit") === false && stripos($javascriptline, "{jit") === false) ) {
              $returnstring .= ltrim(rtrim($javascriptline, " "), PHP_EOL);
              $returncount++;
            }
          }

          //script tag end
          if (!$outputforfile) {
            $returnstring .= "</script>" . PHP_EOL;
          }

          //comment end
          if (!$outputforfile
          &&  $this->OutputComments) {
            $returnstring .= "<!-- END Javascript Functions -->" . PHP_EOL;
          }

        }
      }

      if ($returncount == 0) {
        $returnstring = "";
      }

      return $returnstring;
    }


    public function outputJavascriptObjects($outputforfile=false,$outputfilter="all") {
      //init variables
      $returnstring = "";
      $returnlines  = "";
      $returncount  = 0;

      //output samenstellen
      if ($this->OutputAll
      &&  $this->OutputJavascriptObjects) {
        $javascriptobjects = array_filter($this->getAllJavascriptObjects(array()));

        if (count($javascriptobjects)>0) {
          $returnstring .= PHP_EOL;

          //comment
          if (!$outputforfile
          &&  $this->OutputComments) {
            $returnstring .= "  <!-- BEGIN Javascript Objects -->" . PHP_EOL;
          }

          //script tag
          if (!$outputforfile) {
            $returnstring .= "  <script id=\"outputJavascriptObjectsScript\" type=\"application/javascript\">" . PHP_EOL;
            $returnstring .= "    " . $this->OutputJavascriptContainerHeader . PHP_EOL;
            $returnstring .= "          if (typeof outputJavascriptObjectsFile_{$this->getHashJSID()} == 'function') {outputJavascriptObjectsFile_{$this->getHashJSID()}(); }" . PHP_EOL;
          } else {
            $returnstring .= "    outputJavascriptObjectsFile_{$this->getHashJSID()} = function outputJavascriptObjectsFile_{$this->getHashJSID()}(){" . PHP_EOL;
          }

          //timing
          if ($this->Timing
          ||  $this->TimingOutput) {
            $returnstring .= "          var timerstart = timerStart();" . PHP_EOL;
          }

          //javascript functions
          foreach($javascriptobjects as $javascriptline) {
              if ($outputfilter == "all") {
                $returnlines .= "          {$javascriptline}" . PHP_EOL;
                $returncount++;
              } else if ($outputfilter == "jit"
                     //&& (stripos($javascriptline, "[jit") !== false || stripos($javascriptline, "{jit") !== false) ) {
                     && (stripos($javascriptline, "[jit") !== false || stripos($javascriptline, "{jit") !== false) ) {
                $returnlines .= "          {$javascriptline}" . PHP_EOL;
                $returncount++;
              } else if ($outputfilter == "nojit"
                     && (stripos($javascriptline, "[jit") === false && stripos($javascriptline, "{jit") === false) ) {
                $returnlines .= "          {$javascriptline}" . PHP_EOL;
                $returncount++;
              }
          }

          if ($returncount > 0) {
            $returnstring .= $returnlines;
          }

          //timing end
          if ($this->Timing
          ||  $this->TimingOutput) {
            $returnstring .= "          cl_timer_end(timerstart, " . $this->TimingLimit . ", 'outputJavascriptObjects TOTAL " . ($outputforfile ? "(file) " : "") . $this->OriginalID . "');" . PHP_EOL;
          }

          //function end
          if (!$outputforfile) {
            $returnstring .= "    " . $this->OutputJavascriptContainerFooter . ";" . PHP_EOL;
          } else {
            $returnstring .= "    };void(0);" . PHP_EOL;
          }

          //script tag end
          if (!$outputforfile) {
            $returnstring .= "  </script>" . PHP_EOL;
          }

          //comment end
          if (!$outputforfile
          &&  $this->OutputComments) {
            $returnstring .= "  <!-- END Javascript Objects -->" . PHP_EOL;
          }

        }
      }

      //if ($returncount == 0
      //&&  $outputfilter = "all") {
      //  $returnstring = "";
      //}

      return $returnstring;
    }


    /*
    public function outputJavascriptObjectsAsFile($excludejits=true) {
      //javascript
      $returnstring = "";

      if ($this->OutputAll
      &&  $this->OutputJavascriptObjectsAsFile) {
        $javascriptobjects = array_filter($this->getAllJavascriptObjects(array()));

        if (count($javascriptobjects)>0) {
          $returnstring .= PHP_EOL;

          $returnstring .= "  (function outputJavascriptObjectsAsFile() {" . PHP_EOL;

          //javascript functions
          foreach($javascriptobjects as $javascriptline) {
            if (!$excludejits
            ||  stripos($javascriptline, "[jit:") === false) {
              $returnstring .= "        {$javascriptline}";
            }
          }

          $returnstring .= "  })();" . PHP_EOL;
        }
      }

      return $returnstring;
    }
    */


    public function outputJavascriptGlobals($outputforfile=false,$outputfilter="all") {
      //init variables
      $returnstring = "";
      $returnlines  = "";
      $returncount  = 0;

      //output samenstellen
      if ($this->OutputAll
      &&  $this->OutputJavascriptGlobals) {
        $returnstring .= PHP_EOL;

        //comment
        if (!$outputforfile
        &&  $this->OutputComments) {
          $returnstring .= "  <!-- BEGIN Javascript Globals -->" . PHP_EOL;
        }

        //script tag
        if (!$outputforfile) {
          $returnstring .= "  <script id=\"outputJavascriptGlobalsScript\" type=\"application/javascript\">" . PHP_EOL;
        }

        //global variable
        $returnstring   .= "    if (typeof window['global'] == 'undefined') {" . PHP_EOL;
        $returnstring   .= "      window['global'] = {};" . PHP_EOL;
        $returnstring   .= "    }; " . PHP_EOL . PHP_EOL;

        //function
        if (!$outputforfile) {
            $returnstring .= "    " . $this->OutputJavascriptContainerHeader . PHP_EOL;
          $returnstring .= "          if (typeof outputJavascriptGlobalsFile_{$this->getHashJSID()} == 'function') {outputJavascriptGlobalsFile_{$this->getHashJSID()}(); }" . PHP_EOL;
        } else {
          $returnstring .= "    outputJavascriptGlobalsFile_{$this->getHashJSID()} = function outputJavascriptGlobalsFile_{$this->getHashJSID()}() {" . PHP_EOL;
        }

        //timing
        if ($this->Timing
        ||  $this->TimingOutput) {
          $returnstring .= "          var timerstart = timerStart();" . PHP_EOL;
        }

        //get javascriptglobals
        $javascriptglobals = array_filter($this->getAllJavascriptGlobals(array()));

        if (count($javascriptglobals) > 0) {
          $returnstring .= PHP_EOL;

          //javascript functions
          foreach($javascriptglobals as $javascriptline) {
              if ($outputfilter == "all") {
                $returnlines .= "          {$javascriptline}" . PHP_EOL;
                $returncount++;
              } else if ($outputfilter == "jit"
                     && (stripos($javascriptline, "[jit") !== false || stripos($javascriptline, "{jit") !== false) ) {
                $returnlines .= "          {$javascriptline}" . PHP_EOL;
                $returncount++;
              } else if ($outputfilter == "nojit"
                     && (stripos($javascriptline, "[jit") === false && stripos($javascriptline, "{jit") === false) ) {
                $returnlines .= "          {$javascriptline}" . PHP_EOL;
                $returncount++;
              }
          }

          if ($returncount > 0) {
            $returnstring .= $returnlines;
          }
        }

        //timing end
        if ($this->Timing
        ||  $this->TimingOutput) {
          $returnstring .= "          cl_timer_end(timerstart, " . $this->TimingLimit . ", 'outputJavascriptGlobals TOTAL " . ($outputforfile ? "(file) " : "") . $this->OriginalID . "');" . PHP_EOL;
        }

        //function end
        if (!$outputforfile) {
            $returnstring .= "    " . $this->OutputJavascriptContainerFooter . ";" . PHP_EOL;
        } else {
          $returnstring .= "    };" . PHP_EOL;
        }

        //script tag end
        if (!$outputforfile) {
          $returnstring .= "  </script>" . PHP_EOL;
        }

        //comment end
        if (!$outputforfile
        &&  $this->OutputComments) {
          $returnstring .= "  <!-- END Javascript Globals -->" . PHP_EOL;
        }
      }

      //if ($returncount == 0) {
      //  $returnstring = "";
      //}

      return $returnstring;
    }


    public function outputCSSFiles() {
      global $cssminfile, $cssminfound, $scssphpfound;

      $returnstring = '';

      //init variables
      $clientcssfiles                 = array();
      $clientcssfile                  = "";
      $clientcssfileprefixed          = "";
      $servercssfile                  = "";
      $servercssfileoriginal          = "";
      $servercssfiletimestamp         = null;
      $clientcombinedcssfile          = "";
      $clientcombinedcssfileprefixed  = "";
      $servercombinedcssfile          = "";
      $cachedcombinedcssfile          = false;
      $combinedcssfiletimestamp       = null;
      $combinedcssfilename            = $this->ID . (($this->CombineCSSFilesPrefix) ? "_" . $this->CombineCSSFilesPrefix : "") . "_combined.css";
      $combinedcssfilecontents        = "";
      $combinedcssfiletimestamp       = null;
      $clientcombinedcsspath          = $this->CombineCSSFilesPathClient;
      $servercombinedcsspath          = __DIR__ . "/../../../" . sb\stripfirstslash($this->CombineCSSFilesPathServer);
      $cssfilecontents                = "";
      $combinedcssfilecontents        = "";
      $matches                        = array();

      $clientcompiledcssfilespath     = $this->CompileCSSFilesPathClient;
      $servercompiledcssfilespath     = __DIR__ . "/../../../" . sb\stripfirstslash($this->CompileCSSFilesPathServer);
      $servercompiledcssfile          = "";
      $cachedcompiledcssfile          = false;
      $servercompiledcssfiletimestamp       = null;

      $rootpath                       = str_ireplace("\\", "/", realpath(__DIR__ ."/../../../"));
      $prefixpath                     = $this->CombineCSSFilesPrefixPath;

      //CSS files
      if ($this->OutputAll
      &&  $this->OutputCSSFiles) {
        $clientcssfiles = array_filter($this->getAllCSSFiles(array()));

        if (count($clientcssfiles)>0) {
          $returnstring .= PHP_EOL;

          //comment
          if ($this->OutputComments) {
            $returnstring .= "      <!-- BEGIN CSS Files -->" . PHP_EOL;
          }

          //use cached combined cssfile?
          if ($this->CombineCSSFiles) {
          	$clientcombinedcssfile = filename_concat($clientcombinedcsspath, $combinedcssfilename);
            $servercombinedcssfile = filename_concat($servercombinedcsspath, $combinedcssfilename);

            if (file_exists($servercombinedcssfile)) {
              $cachedcombinedcssfile = true;
              //check of er nieuwere cssfiles zijn
              $combinedcssfiletimestamp = filemtime($servercombinedcssfile);

              foreach($clientcssfiles as $clientcssfile) {
                $servercssfile = server_path($clientcssfile);

                $servercssfiletimestamp = filemtime($servercssfile);
                if ($servercssfiletimestamp > $combinedcssfiletimestamp) {
                  $cachedcombinedcssfile = false;

                  unlink($servercombinedcssfile);
                  break;
                }
              }
            }
          }

          //alle cssfiles aflopen
          foreach($clientcssfiles as $clientcssfile) {
            $servercssfile          = server_path($clientcssfile);
            $servercssoriginalfile  = $servercssfile;

            //css file exists?
            if (file_exists($servercssfile)
            && !filesize($servercssfile) == 0) {
              //SCSS converteren naar CSS?
              if ($scssphpfound) {
                if (contains($servercssfile,".scss")) {
                 $scss = new scssc();
                  $scss->setFormatter("scss_formatter");
                  $scss->addImportPath(filename_path($servercssfile));

									$scssIn = removecomments(file_get_contents($servercssfile));
									$cssOut = $scss->compile($scssIn);

									unset($scss);

									$clientcssfile         = str_replace(".scss", "_scss.css", $clientcssfile);
	                $servercssfile         = str_replace(".scss", "_scss.css", $servercssfile);
									$servercssoriginalfile = str_replace(".scss", "_scss.css", $servercssfile);

	                file_put_contents($servercssfile, $cssOut);
            		}
							}

            	//COMPILE css file?
              if ($this->CompileCSSFiles
              &&  !$cachedcombinedcssfile
              &&  $cssminfound) {
                $hash                       = hash("crc32", $clientcssfile);
                $servercompiledcssfile      = $servercompiledcssfilespath . "/" . str_replace(array(".css",".php"), "_" . $hash . ".min.css", basename($clientcssfile));
                $clientcompiledcssfile      = $clientcompiledcssfilespath . "/" . str_replace(array(".css",".php"), "_" . $hash . ".min.css", basename($clientcssfile));

                //compiled css file already exists?
                if (file_exists($servercompiledcssfile)
                && !filesize($servercompiledcssfile) == 0) {
                  $servercssfiletimestamp         = filemtime($servercssfile);
                  $servercompiledcssfiletimestamp = filemtime($servercompiledcssfile);
                  if ($servercssfiletimestamp > $servercompiledcssfiletimestamp) {
                    $cachedcompiledcssfile = false;
                  } else {
                    $cachedcompiledcssfile = true;
                  }
                } else {
                  $cachedcompiledcssfile = false;
                }

                //use cached compiled css file?
                if (!$cachedcompiledcssfile) {
                  //(re)create new compiled css file
                  if (comparetext(filename_extension($servercssfile),"css")) {
                    $cssfilecontents = file_get_contents($servercssfile);
                  } elseif (comparetext(filename_extension($servercssfile),"php")) {
                    $cssfilecontents = php_to_string($servercssfile, true);
                  }

                  //remove BOM codes, sommige files bevatten ï»¿, "BOM codes" zo blijkt
                  $cssfilecontents = preg_replace('/\x{EF}\x{BB}\x{BF}/','', $cssfilecontents);

                  //remove headers
                  $cssfilecontents = preg_replace("/header\s*\(.*\);/i", "", $cssfilecontents);

                  //replace urls in css
                  $cssfilecontents = replace_css_url_absolute($cssfilecontents, $servercssfile, $rootpath, $prefixpath);

                  //compile css
                  //$compiledcssfilecontents = CssMin::minify($cssfilecontents);
                  $compiledcssfilecontents = compress_css_url($cssfilecontents);

                  //TODO: eigenlijk buiten de foreach
                  mkpath(filename_path($servercompiledcssfile));

                  //(re)create compiled css file
                  file_put_contents($servercompiledcssfile,$compiledcssfilecontents);

                  //compile ok? then use compiled css file, otherwise use original css file
                  $servercssoriginalfile  = $servercssfile;
                  $servercssfile          = $servercompiledcssfile;
                  $clientcssfile          = $clientcompiledcssfile;
                } else {
                  //use cached compiled cssfile
                  $servercssoriginalfile  = $servercssfile;
                  $servercssfile          = $servercompiledcssfile;
                  $clientcssfile          = $clientcompiledcssfile;
                }
              }
            }

            //COMBINE cssfiles
            if (!$this->CombineCSSFiles) {
              //SEPARATE cssfiles
              $clientcssfile          = add_timestamp($clientcssfile);
              $clientcssfileprefixed  = startswith($clientcssfile, "/") ? $this->OutputPathPrefix . $clientcssfile : $clientcssfile;

              $returnstring .= "      <link rel='stylesheet' type='text/css' href='{$clientcssfileprefixed}' />" . PHP_EOL;
            } else {
              //COMBINED cssfiles
              if (!$cachedcombinedcssfile) {
                //retrieve css file contents
                if (comparetext(filename_extension($servercssoriginalfile), "css")) {
                  $cssfilecontents = file_get_contents($servercssoriginalfile);

                  $cssimports = explode("@import ", $cssfilecontents);
                  if (is_array($cssimports)
                  &&  count($cssimports) > 1) {

                    foreach ($cssimports as $cssimportoriginalfile) {
                      if (!sb\is_empty($cssimportoriginalfile)) {
                        $cssimportfile = $cssimportoriginalfile;

                        $cssimportfile = leftpart(leftpart(trim($cssimportfile), ";"), " ");
                        $cssimportfile = trimstringleft(trim($cssimportfile), "url(");
                        $cssimportfile = trimstringright(trim($cssimportfile), ")");
                        $cssimportfile = removequotes($cssimportfile);

                        $servercssimportfile          = server_path($cssimportfile, filename_path($servercssoriginalfile));

                        if (file_exists($servercssimportfile)
                        && !filesize($servercssimportfile) == 0
                        && !is_dir($servercssimportfile)) {
                          $servercssimportfilecontents  = file_get_contents($servercssimportfile) . PHP_EOL;

                          //compile css?
                          if ($this->CompileCSSFiles
                          && $cssminfound) {
                            //$servercssimportfilecontents = CssMin::minify($servercssimportfilecontents);
                            $servercssimportfilecontents = compress_css_url($servercssimportfilecontents);
                          }

                          $cssfilecontents = str_ireplace("@import " . $cssimportoriginalfile, $servercssimportfilecontents, $cssfilecontents);
                        }
                      }
                    }

                  }
                } elseif (comparetext(filename_extension($servercssfile),"php")) {
                  $cssfilecontents = php_to_string($servercssfile, true);
                }

                //remove BOM codes, sommige files bevatten ï»¿, "BOM codes" zo blijkt
                $cssfilecontents = preg_replace('/\x{EF}\x{BB}\x{BF}/','', $cssfilecontents);

                //remove headers
                $cssfilecontents = preg_replace("/header\s*\(.*\);/i", "", $cssfilecontents);

                //replace urls in css, OOK als al door compile gedaan is
                $cssfilecontents = replace_css_url_absolute($cssfilecontents, $servercssoriginalfile, $rootpath, $prefixpath);

                //compile css?
                if ($this->CompileCSSFiles
                && $cssminfound) {
                  //$cssfilecontents = CssMin::minify($cssfilecontents);
                  $cssfilecontents = compress_css_url($cssfilecontents);
                }

                //append css file contents to combined css file contents
                if ($this->CombineCSSFilesHeaders) {
                  $combinedcssfilecontents .= "/* " . $clientcssfile . "*/ " . PHP_EOL;
                }

                $combinedcssfilecontents .= $cssfilecontents;

                if ($this->CombineCSSFilesHeaders) {
                  $combinedcssfilecontents .= PHP_EOL . PHP_EOL;
                }
              }
            }
          }

          //COMBINE cssfiles?
          if ($this->CombineCSSFiles) {
            if (!$cachedcombinedcssfile) {
              //(re)create combined cssfile
              $filehandle = fopen($servercombinedcssfile, "a+");
              if (is_writable($servercombinedcssfile)) {
                chmod($servercombinedcssfile, 0755);

                fwrite($filehandle, $combinedcssfilecontents);
                fclose($filehandle);
              }
            }
            $clientcombinedcssfileprefixed = startswith($clientcombinedcssfile, "/") ? $this->OutputPathPrefix . $clientcombinedcssfile : $clientcombinedcssfile;

            $returnstring .= "      <link rel='stylesheet' type='text/css' href='" . add_timestamp($clientcombinedcssfileprefixed) . "' />" . PHP_EOL;
          }

          //script loadedobjects
          if ($this->OutputLoadedObjects) {
            if (count($clientcssfiles) > 0) {
              $returnstring .= PHP_EOL;

              //comment
              if ($this->OutputComments) {
                $returnstring .= "      <!-- CSS LoadedObjects array -->" . PHP_EOL;
              }

              //script tag
              $returnstring .= "      <script type=\"application/javascript\">" . PHP_EOL;
              $returnstring .= "        if (typeof(LoadedObjects)=='undefined') {" . PHP_EOL;
              $returnstring .= "          LoadedObjects = [];" . PHP_EOL;
              $returnstring .= "        }" . PHP_EOL;

              foreach($clientcssfiles as $clientcssfile) {
                $servercssfile          = server_path($clientcssfile);
                if (file_exists($servercssfile)) {
                  $servercsstimestamp = filemtime($servercssfile);
                } else {
                  $servercsstimestamp = time();
                }

                $clientcssfileprefixed  = startswith($clientcssfile, "/") ? $this->OutputPathPrefix . $clientcssfile : $clientcssfile;

                $returnstring .= "        LoadedObjects.push('" . $clientcssfileprefixed . ":" . $servercsstimestamp . "');" . PHP_EOL;

                if (comparetext(filename_extension($servercssfile), "css")) {
                  $cssfilecontents = file_get_contents($servercssfile);

                  $cssimports = explode("@import ", $cssfilecontents);
                  if (is_array($cssimports)
                  &&  count($cssimports) > 1) {
                    foreach ($cssimports as $cssimportoriginalfile) {
                      if (!sb\is_empty($cssimportoriginalfile)) {
                        $cssimportfile = $cssimportoriginalfile;

                        $cssimportfile = leftpart(leftpart(trim($cssimportfile), ";"), " ");
                        $cssimportfile = trimstringleft(trim($cssimportfile), "url(");
                        $cssimportfile = trimstringright(trim($cssimportfile), ")");
                        $cssimportfile = removequotes($cssimportfile);

                        $clientcssimportfile          = server_path($cssimportfile, filename_path($clientcssfile));

                        $returnstring .= "        LoadedObjects.push('" . $clientcssimportfile . "');" . PHP_EOL;
                      }
                    }
                  }
                }

              }
              $returnstring .= "      </script>" . PHP_EOL;

              //comment
              if ($this->OutputComments) {
                $returnstring .= "      <!-- END CSS Files -->" . PHP_EOL;
              }
            }
          }
        }
      }

      return $returnstring;
    }


    public function outputEvalCSSFiles() {
      $returnstring = "";

      //css files
      if ($this->OutputAll
      &&  $this->OutputEvalCSSFiles) {
        $cssfiles = array_filter($this->getAllEvalCSSFiles(array()));

        if (count($cssfiles)>0) {
          $returnstring .= PHP_EOL;

          //comment
          if ($this->OutputComments) {
            $returnstring .= "<!-- BEGIN Evaluate CSS Files -->" . PHP_EOL;
          }

          if (count($cssfiles)>0) {
            $returnstring .= "<script type=\"application/javascript\">" . PHP_EOL;
            foreach($cssfiles as $cssfile) {
              $cssfileprefixed  = startswith($cssfile, "/") ? $this->OutputPathPrefix . $cssfile : $cssfile;
              $servercssfile = server_path($cssfile);

              if (file_exists($servercssfile)) {
                $serverjavascripttimestamp = filemtime($servercssfile);
              } else {
                $serverjavascripttimestamp = time();
              }

              $returnstring .= "  ajaxscriptexternal('$cssfileprefixed', $serverjavascripttimestamp);" . PHP_EOL;
            }
            $returnstring .= "</script>" . PHP_EOL;
          }

          if ($this->OutputComments) {
            $returnstring .= "<!-- END Evaluate CSS Files -->" . PHP_EOL;
          }
        }
      }

      return $returnstring;
    }




    public function outputLinks() {
      $returnstring = "";

      if ($this->OutputAll
      &&  $this->OutputLinks) {
        $links = array_filter($this->getAllLinks(array()));

        if (count($links) > 0) {
          $returnstring .= PHP_EOL;

          if ($this->OutputComments) {
            $returnstring .= "      <!-- BEGIN Links -->" . PHP_EOL;
          }

          foreach($links as $link) {
            $returnstring .= "      $link" . PHP_EOL;
          }

          if ($this->OutputComments) {
            $returnstring .= "      <!-- END Links -->" . PHP_EOL;
          }
        }
      }

      return $returnstring;
    }


    public function init($initObject=NULL) {
      //inherited
      parent::init($initObject);

      //init attributes
      if ($this->InitAttributes && !$this->PreParse)            {$this->initAttributes();}

      //init javascript files
      if ($this->InitJavascriptFiles && !$this->PreParse)       {$this->initJavascriptFiles();}

      //init CSS files
      if ($this->InitCSSFiles && !$this->PreParse)              {$this->initCSSFiles();}

      //init javascript
      if ($this->InitJavascriptFunctions && !$this->PreParse)   {$this->initJavascriptFunctions();}
      if ($this->InitJavascriptObjects && !$this->PreParse)     {$this->initJavascriptObjects();}
      if ($this->InitJavascriptGlobals && !$this->PreParse)     {$this->initJavascriptGlobals();}
      if ($this->InitInitialJavascript && !$this->PreParse)     {$this->initInitialJavascript();}
      if ($this->InitJavascript && !$this->PreParse)            {$this->initJavascript();}
      if ($this->InitFinalJavascript && !$this->PreParse)       {$this->initFinalJavascript();}

      //init Links
      if ($this->InitLinks && !$this->PreParse)                 {$this->initLinks();}
    }


    public function initProperties() {
      //inherited
      parent::initProperties();

      //preload?
      if ($this->PreLoad) {
        $this->OutputHTML                 = false;

        $this->OutputJavascriptFunctions  = false;
        $this->OutputJavascriptObjects    = false;
        $this->OutputJavascriptGlobals    = false;
        $this->OutputInitialJavascript    = false;
        $this->OutputJavascript           = false;
        $this->OutputFinalJavascript      = false;

        $this->OutputCSS                  = false;
      }
    }


    public function initAttributes() {
      //init attributes

      //trait calls
      if (method_exists("tSB_XMLObject", "initAttributes_XML"))            {$this->initAttributes_XML($this);}

      //attribute originalid
      $this->addAttribute("originalid", coalesce($this->OriginalID, $this->ID));

      //attributes
      if (!($this instanceof SB_HTMLObject)
      &&  !($this instanceof SB_TextObject)) {
        $this->addAttribute("debug", booltostr($this->Debug, "true"));
        $this->addAttribute("demo", booltostr($this->Demo, "false"));
        $this->addAttribute("hashing", booltostr($this->Hashing, "false"));
        $this->addAttribute("logging", booltostr($this->Logging, "false"));
        $this->addAttribute("objecting", booltostr($this->Objecting, "false"));
        $this->addAttribute("profiling", booltostr($this->Profiling, "false"));
        $this->addAttribute("test", booltostr($this->Test, "true"));
        $this->addAttribute("timing", booltostr($this->Timing, "false"));
        $this->addAttribute("timinglimit", $this->TimingLimit);
        $this->addAttribute("timingoutput", booltostr($this->TimingOutput, "false"));

        $this->addAttribute("versionid", $this->VersionID);
        //$this->addAttribute("VersionMajor", $this->VersionMajor);
        //$this->addAttribute("VersionMinor", $this->VersionMinor);

        $this->addAttribute("xmlcolumns", $this->XMLColumns);
        $this->addAttribute("xmlrequest", $this->XMLRequest);
        $this->addAttribute("xmlresult", $this->XMLResult);
        $this->addAttribute("xmlsearch", $this->XMLSearch);
        $this->addAttribute("xmlselection", $this->XMLSelection);
        $this->addAttribute("xmlsubmit", $this->XMLSubmit);

        $this->addAttribute("dummy", $this->Dummy);
        $this->addAttribute("ready", booltostr($this->Ready, "false"));

        //$this->addAttributeObject("params", $this->getParamsArray());
      }

      $this->InitedAttributes = true;
    }


    public function initJavascriptFiles() {
      //javascript files
      if (!$this->JavascriptCompiled
      ||   $this->Debug) {
        //original files
        $this->addJavascriptFile("/framework/libraries/jsclasses/Classes.js");
        $this->addJavascriptFile("/framework/libraries/jsclasses/Traits.js");

        $this->addJavascriptFile("/framework/classes/sb/js/__sb_globals.js");
        $this->addJavascriptFile("/framework/classes/sb/js/__sb_object.js");
        $this->addJavascriptFile("/framework/classes/sb/js/__sb_timer.js");

        //trait calls
        if (method_exists("tSB_XMLObject", "initJavascriptFiles_XML"))            {$this->initJavascriptFiles_XML($this);}

        $this->addJavascriptFile("/framework/classes/sb/js/__sb_templateobject.js");
      } else {
        //compiled files
        $this->addJavascriptFile("/framework/libraries/jsclasses/Classes.min.js");
        $this->addJavascriptFile("/framework/libraries/jsclasses/Traits.min.js");

        $this->addJavascriptFile("/framework/classes/sb/closure/__sb_globals-compiled.js");
        $this->addJavascriptFile("/framework/classes/sb/closure/__sb_object-compiled.js");
        $this->addJavascriptFile("/framework/classes/sb/closure/__sb_timer-compiled.js");

        //trait calls
        if (method_exists("tSB_XMLObject", "initJavascriptFiles_XML"))            {$this->initJavascriptFiles_XML($this);}

        $this->addJavascriptFile("/framework/classes/sb/closure/__sb_templateobject-compiled.js");
      }

      $this->InitedJavascriptFiles  = true;
    }


    public function initInitialJavascript() {
      //trait calls
      if (method_exists("tSB_XMLObject", "initInitialJavascript_XML"))            {$this->initInitialJavascript_XML($this);}

      $this->InitedInitialJavascript  = true;
    }


    public function initJavascript() {
      //trait calls
      if (method_exists("tSB_XMLObject", "initJavascript_XML"))                   {$this->initJavascript_XML($this);}

      $this->InitedJavascript = true;
    }

    //TODO: splitsen in de initJavascript
    public function initJavascriptBefore() {
      $this->InitedJavascriptBefore = true;
    }


    public function initJavascriptAfter() {
      $this->InitedJavascriptAfter = true;
    }


    public function initFinalJavascript() {
      //trait calls
      if (method_exists("tSB_XMLObject", "initFinalJavascript_XML"))              {$this->initFinalJavascript_XML($this);}

      $this->InitedFinalJavascript = true;
    }


    public function initJavascriptFunctions() {
      $this->InitedJavascriptFunctions = true;
    }


    public function initJavascriptGlobals() {
      //trait calls
      if (method_exists("tSB_XMLObject", "initJavascriptGlobals_XML"))            {$this->initJavascriptGlobals_XML($this);}

      $this->InitedJavascriptGlobals = true;
    }


    public function initJavascriptObjects() {
      //trait calls
      if (method_exists("tSB_XMLObject", "initJavascriptObjects_XML"))            {$this->initJavascriptObjects_XML($this);}

      $this->InitedJavascriptObjects = true;
    }


    public function initCSSFiles() {
      //trait calls
      if (method_exists("tSB_XMLObject", "initCSSFiles_XML"))                     {$this->initCSSFiles_XML($this);}

      $this->InitedCSSFiles = true;
    }


    public function initLinks() {
      $this->InitedLinks = true;
    }


    public function finalize() {
      //$this->replaceJSID(false);
    }


    public function setJavascriptFiles($javascriptfiles='') {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($javascriptfiles=='') {
        $this->JavascriptFiles  = array();
      } else {
        if (is_array($javascriptfiles)) {
          $this->JavascriptFiles  = $javascriptfiles;
        } else {
          $this->JavascriptFiles  = multi_explode(",;", $javascriptfiles);
        }
      }
    }


    /*
    public function setEvalJavascriptFiles($javascriptfiles='') {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($javascriptfiles=='') {
        $this->EvalJavascriptFiles  = array();
      } else {
        if (is_array($javascriptfiles)) {
          $this->EvalJavascriptFiles  = $javascriptfiles;
        } else {
          $this->EvalJavascriptFiles  = array($javascriptfiles);
        }
      }
    }
    */


    public function setInitialJavascript($initialjavascript='') {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($initialjavascript=='') {
        $this->InitialJavascript  = array();
      } else {
        if (is_array($initialjavascript)) {
          $this->InitialJavascript  = $initialjavascript;
        } else {
          $this->InitialJavascript  = multi_explode(",;", $initialjavascript);
        }
      }
    }

    public function setJavascript($javascript='') {
      //wordt niet gebruikt?
      if ($javascript=='') {
//        $this->Javascript  = array();
      } else {
        if (is_array($javascript)) {
          $this->Javascript  = $javascript;
        } else {
          $this->Javascript  = array($javascript);
        }
      }
    }

    public function setJavascriptFunctions($javascriptfunctions='') {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($javascriptfunctions=='') {
        $this->JavascriptFunctions  = array();
      } else {
        if (is_array($javascriptfunctions)) {
          $this->JavascriptFunctions  = $javascriptfunctions;
        } else {
          $this->JavascriptFunctions  = array($javascriptfunctions);
        }
      }
    }

    public function setFinalJavascript($finaljavascript='') {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($finaljavascript=='') {
        $this->FinalJavascript  = array();
      } else {
        if (is_array($finaljavascript)) {
          $this->FinalJavascript  = $finaljavascript;
        } else {
          $this->FinalJavascript  = multi_explode(",;", $finaljavascript);
        }
      }
    }

    public function setCSSFiles($cssfiles='') {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($cssfiles=='') {
        $this->CSSFiles  = array();
      } else {
        if (is_array($cssfiles)) {
          $this->CSSFiles  = $cssfiles;
        } else {
          $this->CSSFiles  = multi_explode(",;", $cssfiles);
        }
      }
    }

    public function setCSS($css='') {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($css=='') {
        $this->CSS  = array();
      } else {
        if (is_array($css)) {
          $this->CSS  = $css;
        } else {
          $this->CSS  = array($css);
        }
      }
    }

    public function setLinks($links='') {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($links=='') {
        $this->Links  = array();
      } else {
        if (is_array($links)) {
          $this->Links  = $links;
        } else {
          $this->Links  = array($links);
        }
      }
    }


    public function replaceJSID($recursive=true, $timing=false) {
      //timing
      $timerstart = timer_start();

      //init variables
      $search = array("[jsid]", "{jsid}");
      $replace= $this->getJSID();

      foreach ($this as $property_name => $property_value) {
        //property is string
        if (is_string($this->$property_name)) {
//            if (stripos($property_value, $search) !== false) {
            $this->$property_name = str_ireplace($search, $replace, $property_value);
//            $this->$property_name = preg_replace($search, $replace, $property_value);
//            }
        }

        //property is array
        if (is_array($this->$property_name)
        &&  $property_name != "Objects"
        //&&  $property_name != "CSSFiles"
        //&&  $property_name != "EvalJavascriptFiles"
        //&&  $property_name != "JavascriptFiles"
        ) {
          foreach($this->$property_name as $key=>$item) {
            if (is_string($item)) {
//                if (stripos($item, $search) !== false) {
//                $newvalue = preg_replace($search, $replace, $item);
              $this->{$property_name}[$key] = str_ireplace($search, $replace, $item);
                //                }
            }
          }
        }

        //property is object
        if ($this->$property_name instanceof SB_Object) {
          if ($property_name != "ParentObject"
          &&  $property_name != "RootObject") {
            //TODO: dit maakt het traag   (wordt gebruikt voor background.image enz
            if ($this->$property_name instanceof SB_TemplateObject) {
              $this->$property_name->replaceJSID($recursive);
            }
          }
        }
      }

      if ($recursive) {
        foreach($this->Objects as $childobject) {
          if ($childobject instanceof SB_TemplateObject) {
            $childobject->replaceJSID($recursive);
          }
        }
      }

      //timing
      if ($timing) {
        fb_timer_end($timerstart,0,"__sb_templateobject.php : replaceJSID");
      }
    }


    public function replaceIDInString($string, $replacesuffix="") {
      return $this->replaceBracketIDInString($string, "id", "getID", $replacesuffix);
    }


    public function replaceJSIDInString($string, $replacesuffix="") {
      return $this->replaceBracketIDInString($string, "jsid", "getJSID", $replacesuffix);
    }


    public function replaceIDJSIDInString($string, $replacesuffix="") {
      $returnstring = $string;

      $returnstring = $this->replaceBracketIDInString($returnstring, "id", "getID", $replacesuffix);
      $returnstring = $this->replaceBracketIDInString($returnstring, "jsid", "getJSID", $replacesuffix);

      return $returnstring;
    }

    public function replaceBracketIDInString($string, $replacetag="jsid", $replacefunction="getJSID", $replacesuffix="") {
      //timing
      $timerstart = timer_start();

      if (is_string($string)) {
        //replace [jsid]
        $string  =  str_ireplace("[$replacetag]", $this->$replacefunction() . $replacesuffix, $string);
        $string  =  str_ireplace("{".$replacetag."}", $this->$replacefunction() . $replacesuffix, $string);

        //replace [jsid:blablabla]

        if (stripos($string, "[" . $replacetag . ":") !== false
        ||  stripos($string, "{" . $replacetag . ":") !== false ) {
//          $replacearray = explode("[$replacetag:", $string);
          $replacearray = multi_explode(array("[" . $replacetag . ":", "{" . $replacetag . ":") , $string);
          $replacecount = 0;

          foreach ($replacearray as $replacestring) {
            if ($replacecount > 0) {
//              $idstring = get_string_restant_rechtehaken($replacestring);
              $idstring = get_string_restant_brackets($replacestring);

              $rootobject = $this->getRootObject();
              if ($rootobject) {
                $object    = $rootobject->getObject($idstring);
                if ($object) {
                  if ($object instanceof SB_TemplateObject) {
                    $replacevalue = $object->$replacefunction() . $replacesuffix;
                    $string = str_ireplace ("[". $replacetag .":" .$idstring . "]", trim($replacevalue), $string);
                    $string = str_ireplace ("{". $replacetag .":" .$idstring . "}", trim($replacevalue), $string);
                  }
                }

                $object    = $this->getObject($idstring);
                if ($object) {
                  if ($object instanceof SB_TemplateObject) {
                    $replacevalue = $object->$replacefunction() . $replacesuffix;
                    $string = str_ireplace ("[". $replacetag .":" .$idstring . "]", trim($replacevalue), $string);
                    $string = str_ireplace ("{". $replacetag .":" .$idstring . "}", trim($replacevalue), $string);
                  }
                }
              }
            }

            $replacecount++;
          }
        }
      }

      if ($this->Timing) {
        fb_timer_end($timerstart, $this->TimingLimit, "SB_TemplateObject : replace bracketid in string");
      }

      return $string;
    }


    public function getAllRun($run=array()) {
      //retourneert ALLE run output van huidige object en al haar children

      //get all run output
      if ($this->OutputAll
      &&  $this->OutputRun) {

        if ($this->IncludeObjects) {
          foreach($this->Objects as $childobject) {
            if ($childobject && method_exists($childobject, "getAllRun")) {
              $run = $childobject->getAllRun($run);
            }
          }
        }

        //RUN
        foreach($this->RunOutput as $runline) {
          $lines = explode("\n", $runline);
          foreach($lines as $line) {
            if (!sb\is_empty($line)) {
              //add line to run array
              $run[] = $line . PHP_EOL;
            }
          }
        }
      }

      return $run;
    }

    public function outputRun() {
      //init variables
      $returnstring = "";
      $runline      = "";
      $runlines     = array();

      //output runoutput
      if ($this->OutputAll
      &&  $this->OutputRun) {
        $runlines = $this->getAllRun();

        if (count($runlines)>0) {
          $returnstring .= PHP_EOL;

          if ($this->OutputComments) {
            $returnstring .= "<!-- BEGIN RUN OUTPUT -->" . PHP_EOL;
          }

          foreach($runlines as $runline) {
            $returnstring .= $runline;
          }

          if ($this->OutputComments) {
            $returnstring .= "<!-- END RUN OUTPUT -->" . PHP_EOL;
          }
        }
      }

      return $returnstring;
    }

  }
