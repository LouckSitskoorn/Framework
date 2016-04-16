<?php
  //NAMESPACE
  namespace SB\Traits\Basic;

  //INCLUDES  functions
  require_once __DIR__ . "/../../Functions/string_functions.php";
  require_once __DIR__ . "/../../Functions/xml_functions.php";

  //uses
  use SB\Functions as sb;

  //TRAITS
  trait tSB_XMLObject {
    //published properties
    public $CachingXML                      = false;
    public $CachingXMLPrefix                = "";
    public $CachingXMLSuffix                = "_xml";

    public $XMLMap                          = "";

    public $XMLID                           = "";
    public $XMLFormID                       = "";
    public $XMLFieldname                    = "";
    public $XMLQueryID                      = "";
    public $XMLSearchID                     = "";
    public $XMLSelectionID                  = "";

    public $XML                             = "";
    public $XMLColumns                      = "";
    public $XMLRequest                      = "";
    public $XMLResult                       = "";
    public $XMLSearch                       = "";
    public $XMLSelection                    = "";
    public $XMLSubmit                       = "";


    //implementation
    public function initAttributes_XML($object=null) {
      if (isset($this)
      && !isset($object)) {
        $object=$this;
      }

      if (isset($object)
      &&  is_instance_of($object, "SB_Object")) {
        $object->addAttribute("cachingxml", booltostr($object->CachingXML, "false"));
        $object->addAttribute("cachingxmlprefix", $object->CachingXMLPrefix);
        $object->addAttribute("cachingxmlsuffix", $object->CachingXMLSuffix);

        $object->addAttribute("xmlid", $object->getJSIDs($object->XMLID));
        $object->addAttribute("xmlfieldname", $object->XMLFieldname);
        $object->addAttribute("xmlformid", $object->getJSIDs($object->XMLFormID));
        $object->addAttribute("xmlqueryid", $object->getJSIDs($object->XMLQueryID));
        $object->addAttribute("xmlsearchid", $object->getJSIDs($object->XMLSearchID));
        $object->addAttribute("xmlselectionid", $object->getJSIDs($object->XMLSelectionID));

        $object->addAttribute("xmlmap", $object->XMLMap);
      }
    }


    public function initJavascriptFiles_XML($object=null) {
      if (isset($this)
      && !isset($object)) {
        $object=$this;
      }

      if (isset($object)) {
        //component files
        if (!$object->JavascriptCompiled
        ||   $object->Debug) {
          $object->addJavascriptFile("/framework/traits/js/__sb_trait_xmlobject.js");
        } else {
          //closure compiled
          $object->addJavascriptFile("/framework/traits/js/closure/__sb_trait_xmlobject-compiled.js");
        }
      }
    }


    public function initCSSFiles_XML($object=null) {
      if (isset($this)
      && !isset($object)) {
        $object=$this;
      }

      if (isset($object)) {
      }
    }
  }

?>