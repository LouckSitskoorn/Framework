<?
  //NAMESPACE
  namespace SB\Classes\Template;

  //INCLUDES functions
  include_once __DIR__ . "/../Functions/uses.php";
  include_once __DIR__ . "/../Functions/array_functions.php";
  include_once __DIR__ . "/../Functions/string_functions.php";

  //USES
  use SB\Template\SB_TemplateObject;
  use SB\Functions as sb;

  //INTERFACES
  interface iSB_TagObject {
  }

  //CLASSES
  class SB_TagObject
  extends SB_TemplateObject
  implements iSB_TagObject {
    //published properties
    public $Class               = "";
    public $Dir                 = null;
    public $Lang                = null;
    public $Rel                 = null;
    public $Style               = "";
    public $Title               = null;

    //public properties
    public $Tagname             = "";

    public $Classes             = array();
    public $Styles              = array();

    public $PropID;
    public $ClassID;
    public $ReferenceID;

    public $CSS                 = array();
    public $BeforeHTML          = array();
    public $InitialHTML         = array();
    public $HTML                = array();
    public $FinalHTML           = array();
    public $AfterHTML           = array();

    public $InitCSS             = true;
    public $InitBeforeHTML      = true;
    public $InitInitialHTML     = true;
    public $InitHTML            = true;
    public $InitFinalHTML       = true;
    public $InitAfterHTML       = true;
    public $InitClasses         = true;
    public $InitStyles          = true;

    //InitedXXXXX property geeft aan of XXXXX ge-init is
    public $InitedCSS           = false;
    public $InitedHTML          = true;
    public $InitedClasses       = true;
    public $InitedStyles        = true;

    public $OutputAllHTML       = true;
    public $OutputHTML          = true;
    public $OutputCSS           = true;
    public $OutputClasses       = true;
    public $OutputStyles        = true;
    public $OutputReplaceQuotes = false;

    //implementations
    public function __construct() {
      //inherited
      parent::__construct();

      //tagname
      $this->Tagname = "";
    }


    public function init($initObject=NULL) {
      //inherited
      parent::init($initObject);

      //init classes & styles
      if ($this->InitClasses && !$this->PreParse)     {$this->initClasses();}
      if ($this->InitStyles && !$this->PreParse)      {$this->initStyles();}

      //init CSS
      if ($this->InitCSS && !$this->PreParse)         {$this->initCSS();}

      //init html
      if ($this->InitBeforeHTML && !$this->PreParse)  {$this->initBeforeHTML();}
      if ($this->InitInitialHTML && !$this->PreParse) {$this->initInitialHTML();}
      if ($this->InitHTML && !$this->PreParse)        {$this->initHTML();}
      if ($this->InitFinalHTML && !$this->PreParse)   {$this->initFinalHTML();}
      if ($this->InitAfterHTML && !$this->PreParse)   {$this->initAfterHTML();}
    }


    public function initAttributes() {
      //inherited
      parent::initAttributes();

      //init attributes
      $this->addAttribute("dir", $this->Dir);
      $this->addAttribute("lang", $this->Lang);
      $this->addAttribute("rel", $this->Rel);
      $this->addAttribute("title", $this->Title);
    }


    public function initCSS() {
      $this->InitedCSS = true;
    }


    public function initClasses() {
      //init classes
      if (!sb\is_empty($this->Class)) {
        $this->addClass($this->Class);
      }

      $this->InitedClasses = true;
    }


    public function initStyles() {
      //init styles
      if (!sb\is_empty($this->Style)) {
        $styles = explode(";", $this->Style);
        foreach ($styles as $style) {
          $styleparts  = explode(":", $style);

          if (count($styleparts) == 2) {
            $this->addStyle($styleparts[0], $styleparts[1]);
          }
        }
      }

      $this->InitedStyles = true;
    }


    public function initJavascriptFiles() {
      //inherited
      parent::initJavascriptFiles();

      //javascript files
      if (!$this->JavascriptCompiled
      ||   $this->Debug) {
        //original files
        $this->addJavascriptFile("/framework/classes/sb/js/__sb_tagobject.js");
      } else {
        //compiled files
        $this->addJavascriptFile("/framework/classes/sb/closure/__sb_tagobject-compiled.js");
      }

      $this->InitedJavascriptFiles  = true;
    }


    public function initBeforeHTML() {
    }


    public function initInitialHTML() {
    }


    public function initHTML() {
      $this->InitedHTML = true;
    }


    public function initFinalHTML() {
    }


    public function initAfterHTML() {
    }


    public function setBeforeHTML($html="") {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($html=="") {
        $this->BeforeHTML  = array();
      } else {
        if (is_array($html)) {
          $this->BeforeHTML  = $html;
        } else {
          $this->BeforeHTML  = array($html);
        }
      }
    }


    public function setInitialHTML($html="") {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($html=="") {
        $this->InitialHTML  = array();
      } else {
        if (is_array($html)) {
          $this->InitialHTML  = $html;
        } else {
          $this->InitialHTML  = array($html);
        }
      }
    }


    public function setHTML($html="") {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($html=="") {
        $this->HTML  = array();
      } else {
        if (is_array($html)) {
          $this->HTML  = $html;
        } else {
          $this->HTML  = array($html);
        }
      }
    }


    public function setFinalHTML($html="") {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($html=="") {
        $this->FinalHTML  = array();
      } else {
        if (is_array($html)) {
          $this->FinalHTML  = $html;
        } else {
          $this->FinalHTML  = array($html);
        }
      }
    }


    public function setAfterHTML($html="") {
      //wordt niet gebruikt?
      //set routines altijd NA de init() gebruiken!
      if ($html=="") {
        $this->AfterHTML  = array();
      } else {
        if (is_array($html)) {
          $this->AfterHTML  = $html;
        } else {
          $this->AfterHTML  = array($html);
        }
      }
    }


    public function clearClasses() {
      $this->Classes   = array();
    }


    public function addClass($class) {
      if (!sb\is_empty($class)) {
       if (sb\compare_in_array($class, $this->Classes)) {
         $this->removeClass($class);
       }

       $this->Classes[] = $class;
      }
    }


    public function removeClass($class) {
      foreach($this->Classes as $key=>$value) {
        if (sb\comparetext($class, $value)) {
          if ($this->Classes[$key]) {
            unset($this->Classes[$key]);
          }
        }
      }
    }


    public function clearStyles() {
      $this->Styles   = array();
    }


    public function removeStyle($style) {
      foreach($this->Styles as $key=>$value) {
        if (sb\comparetext($style, $key)) {
          unset($this->Styles[$key]);
        }
      }
    }


    public function addStyle($style, $value, $important=false) {
      $style = strtolower($style);

      if (!sb\is_empty($style)
      &&  !sb\is_empty($value)) {
        if (array_key_exists($style, $this->Styles)) {
          $this->removeStyle($style);
        }

        if ($important) {
          $valueimportant = " !important";
        } else {
          $valueimportant = "";
        }

        $this->Styles[$style] = $value . $valueimportant;
      }
    }


    public function getHTMLAttribute($attributename,$value) {
      if (!sb\is_empty($attributename)
      &&  !sb\is_empty($value)
      &&  !is_null($value)) {
        return " $attributename=\"$value\"";
      } else {
        return "";
      }
    }


    public function getHTMLStyle($style,$value) {
      if (!sb\is_empty($style)
      &&  !sb\is_empty($value)) {
        return " $style:$value;";
      } else {
        return "";
      }
    }


    public function getHTMLStartTag($closetag=true) {
      //init variables
      $classes            = "";
      $datareplaceglobals = [];
      $datareplaceor      = "";
      $datareplaceregex   = "";
      $returnhtml         = "";
      $styles             = "";

      //datareplaceglobals
      $rootobject           = $this->getRootObject("iSB_Template");
      if (is_object($rootobject)
      && property_exists($rootobject, "DataReplaceGlobal")) {
        $datareplaceglobals   = explode(",", $rootobject->DataReplaceGlobal);
        $datareplaceor        = "";
        foreach ($datareplaceglobals as $datareplaceglobal) {
          $datareplaceregex .= $datareplaceor . '{' . $datareplaceglobal . '\:';
          $datareplaceor    = '|';
        };
      }

      if (!sb\is_empty($this->Tagname)) {
        $returnhtml  = "<$this->Tagname";

        //ID/Name
        if (!sb\is_empty($this->ID)) {
          $returnhtml .= $this->getHTMLAttribute("id", $this->getJSID());
        }

        //Attributes
        if ($this->OutputAttributes) {
          //alle attributes in html tag opnemen
          foreach($this->Attributes as $attributename=>$attributevalue) {
            $returnhtml .= $this->getHTMLAttribute($attributename, $attributevalue);
          }
        } else {
          //alleen 'echte' html attributes en data-xxxx attributes in html tag opnemen
          foreach($this->Attributes as $attributename=>$attributevalue) {
            if (in_array($attributename, $this->AttributesHTML)
            ||  startswith($attributename, "data-")) {
              $returnhtml .= $this->getHTMLAttribute($attributename, $attributevalue);
            } else if (!sb\is_empty($datareplaceregex)
                   &&   preg_match_all("/" . $datareplaceregex . "/i", $attributevalue)) {
              $returnhtml .= $this->getHTMLAttribute("data-" . $attributename, $attributevalue);
            }
          }
        }

        //Classes
        if ($this->OutputClasses) {
          foreach($this->Classes as $class) {
            $classes .= $class . " ";
          }
          $returnhtml .= $this->getHTMLAttribute("class", $classes);
        }

        //Styles
        if ($this->OutputStyles) {
          foreach($this->Styles as $style=>$value) {
            $styles .= $this->getHTMLStyle($style, $value);
          }

          $returnhtml .= $this->getHTMLAttribute("style", $styles);
        }

        //uitzondering <br />
        /*
        if (sb\comparetext($this->Tagname, "br")
        ||  sb\comparetext($this->Tagname, "hr")) {
          $returnhtml .= "/>" . PHP_EOL;
        } elseif (sb\comparetext($this->Tagname, "textarea")) {
          //geen line break bij TEXTAREA's
          $returnhtml .= ">";
        } else {
          $returnhtml .= ">" . PHP_EOL;
        }
        */

        if ($closetag) {
          $returnhtml .= ">" . PHP_EOL;
        }
      }

      return $returnhtml;
    }


    public function getHTMLEndTag() {
      $returnhtml = '';

      if (!sb\comparetext($this->Tagname, "br")
      &&  !sb\comparetext($this->Tagname, "hr")) {
        if (!sb\is_empty($this->Tagname)) {
          $returnhtml = "</$this->Tagname>\n";
        }
      }

      return $returnhtml;
    }


    public function getAllCSS($css=array()) {
      //retourneert ALLE CSS van huidige object en al haar children

      if ($this->OutputAll
      &&  $this->OutputCSS) {
        if ($this->IncludeObjects) {
          foreach($this->Objects as $childobject) {
            if ($childobject && method_exists($childobject, "getAllCSS")) {
              $css = $childobject->getAllCSS($css);
            }
          }
        }

        foreach($this->CSS as $cssline) {
          //if ((sb\compare_in_array($cssline, $css)==false)) {
            //replace [jsid]
            $cssline  =  $this->replaceJSIDInString($cssline);

            $css[] = $cssline;
          //}
        }
      }

      return $css;
    }


    public function getAllHTML($html=array()) {
      //retourneert ALLE html van huidige object en al haar children

      //get all html
      if ($this->OutputAll
      &&  sb\compareboolean($this->OutputAllString,"true")
      &&  $this->OutputHTML) {
        //BEFORE
        foreach($this->BeforeHTML as $htmlline) {
          $lines = explode("\n", trim($htmlline));
          foreach($lines as $line) {
            //if (!sb\is_empty($line) {
              //replace [jsid]
              $line  =  $this->replaceJSIDInString($line);

              //add line to html array
              $html[] = $this->getIndent(1) . $line . PHP_EOL;
            //}
          }
        }

        //START TAG
        $tag = $this->getHTMLStartTag();
        if ($tag) {
          //replace [jsid]
          $tag  =  $this->replaceJSIDInString($tag);

          $html[] = $this->getIndent() . trim($tag) . PHP_EOL;
        }

        //INITIAL
        foreach($this->InitialHTML as $htmlline) {
          $lines = explode("\n", trim($htmlline));
          foreach($lines as $line) {
            //if (!sb\is_empty($line)) {
              //replace [jsid]
              $line  =  $this->replaceJSIDInString($line);

              //add line to html array
              $html[] = $this->getIndent(1) . trim($line) . PHP_EOL;
            //}
          }
        }

        if ($this->IncludeObjects) {
          foreach($this->Objects as $childobject) {
            if ($childobject && method_exists($childobject, "getAllHTML")) {
              $html = $childobject->getAllHTML($html);
            }
          }
        }

        //HTML
        foreach($this->HTML as $htmlline) {
          $lines = explode("\n", $htmlline);
          foreach($lines as $line) {
            if (!sb\is_empty($line)) {
              //replace [jsid]
              $line  =  $this->replaceJSIDInString($line);

              //add line to html array
              $html[] = $this->getIndent(1) . trim($line) . "\n";
            }
          }
        }

        //FINAL
        foreach($this->FinalHTML as $htmlline) {
          $lines = explode("\n", trim($htmlline));
          foreach($lines as $line) {
            //if (!sb\is_empty($line)) {
              //replace [jsid]
              $line  =  $this->replaceJSIDInString($line);

              //add line to html array
              $html[] = $this->getIndent(1) . trim($line) . "\n";
            //}
          }
        }

        //END TAG
        $tag = $this->getHTMLEndTag();
        if ($tag) {
          if (sb\comparetext($this->Tagname, "textarea")) {
            //geen indentation bij TEXTAREA's
            $html[] = $tag;
          } else {
            $html[] = $this->getIndent() . trim($tag) . "\n";
          }
        }

        //AFTER
        foreach($this->AfterHTML as $htmlline) {
          $lines = explode("\n", trim($htmlline));
          foreach($lines as $line) {
            //if (!sb\is_empty($line)) {
              //replace [jsid]
              $line  =  $this->replaceJSIDInString($line);

              //add line to html array
              $html[] = $this->getIndent(1) . trim($line) . "\n";
            //}
          }
        }
      }

      return $html;
    }


    public function addCSS($css, $insertbegin=false) {
      if (!sb\is_empty($css)) {
        if ($insertbegin) {
          array_unshift($this->CSS, $css);
        } else {
          $this->CSS[] = $css;
        }
      }
    }


    public function insertCSS($css) {
      $this->addCSS($css, true);
    }


    public function addBeforeHTML($html, $insertbegin=false) {
      if (!sb\is_empty($html)) {
        if ($insertbegin) {
          array_unshift($this->BeforeHTML, $html);
        } else {
          $this->BeforeHTML[] = $html;
        }
      }
    }


    public function addInitialHTML($html, $insertbegin=false) {
      if (!sb\is_empty($html)) {
        if ($insertbegin) {
          array_unshift($this->InitialHTML, $html);
        } else {
          $this->InitialHTML[] = $html;
        }
      }
    }


    public function addHTML($html, $insertbegin=false) {
      if (!sb\is_empty($html)) {
        if ($insertbegin) {
          array_unshift($this->HTML, $html);
        } else {
          $this->HTML[] = $html;
        }
      }
    }


    public function addFinalHTML($html, $insertbegin=false) {
      if (!sb\is_empty($html)) {
        if ($insertbegin) {
          array_unshift($this->FinalHTML, $html);
        } else {
          $this->FinalHTML[] = $html;
        }
      }
    }


    public function addAfterHTML($html, $insertbegin=false) {
      if (!sb\is_empty($html)) {
        if ($insertbegin) {
          array_unshift($this->AfterHTML, $html);
        } else {
          $this->AfterHTML[] = $html;
        }
      }
    }


    public function insertBeginHTML($html) {
      $this->addBeginHTML($html, true);
    }


    public function insertInitialHTML($html) {
      $this->addInitialHTML($html, true);
    }


    public function insertHTML($html) {
      $this->addHTML($html, true);
    }


    public function insertFinalHTML($html) {
      $this->addFinalHTML($html, true);
    }


    public function insertAfterHTML($html) {
      $this->addAfterHTML($html, true);
    }


    public function clearBeginHTML() {
      $this->BeginHTML = array();
    }


    public function clearInitialHTML() {
      $this->InitialHTML = array();
    }


    public function clearHTML() {
      $this->HTML = array();
    }


    public function clearFinalHTML() {
      $this->FinalHTML = array();
    }


    public function clearAfterHTML() {
      $this->AfterHTML = array();
    }


    public function replaceBeginHTML($html) {
      $this->clearBeginHTML();
      $this->addBeginHTML($html);
    }


    public function replaceInitialHTML($html) {
      $this->clearInitialHTML();
      $this->addInitialHTML($html);
    }


    public function replaceHTML($html) {
      $this->clearHTML();
      $this->addHTML($html);
    }


    public function replaceFinalHTML($html) {
      $this->clearFinalHTML();
      $this->addFinalHTML($html);
    }


    public function replaceAfterHTML($html) {
      $this->clearAfterHTML();
      $this->addAfterHTML($html);
    }


    public function outputCSS() {
      $returnstring = "";

      if ($this->OutputAll
      &&  $this->OutputCSS) {
        $csslines = array_filter($this->getAllCSS(array()));

        if (count($csslines) > 0) {
          $returnstring .= PHP_EOL;

          if ($this->OutputComments) {
            $returnstring .= "      <!-- BEGIN CSS -->" . PHP_EOL;
          }
          $returnstring .= "      <style type='text/css'>" . PHP_EOL;

          foreach($csslines as $cssline) {
            $returnstring .= "      {$cssline}" . PHP_EOL;
          }

          $returnstring .= "      </style>" . PHP_EOL;

          if ($this->OutputComments) {
            $returnstring .= "      <!-- END CSS -->" . PHP_EOL;
          }
        }
      }

      return $returnstring;
    }


    public function outputHTML() {
      //html
      $returnstring = "";

      if ($this->OutputAll
      &&  $this->OutputHTML
      &&  sb\compareboolean($this->OutputAllString,"true")) {
        $htmllines = $this->getAllHTML();

        if (count($htmllines) > 0) {
          $returnstring .= PHP_EOL;

          if ($this->OutputComments) {
            $returnstring .= "      <!-- BEGIN HTML -->" . PHP_EOL;
          }

          foreach($htmllines as $htmlline) {
            $returnstring .= "      " . $htmlline;
          }

          if ($this->OutputComments) {
            $returnstring .= "      <!-- END HTML -->" . PHP_EOL;
          }
        }
      }

      return $returnstring;
    }


    public function outputAllHTML() {
      $output  =  "";

      $output .= $this->outputCSS();
      $output .= $this->outputHTML();

      return $output;
    }


    public function outputAll() {
      //OVERRIDE outputAll
      $output = "";

      //output files
      if ($this->OutputAllFiles) {
        $output .= parent::outputAllFiles();
      }

      //output html
      if ($this->OutputAllHTML) {
        $output .= $this->outputAllHTML();
      }

      //output javascript
      if ($this->OutputAllJavascript) {
        $output .= $this->outputAllJavascript();
      }

      return $output;
    }
  }
?>