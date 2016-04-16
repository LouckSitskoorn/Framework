<?
  //NAMESPACE
  namespace SB\Classes\Template;

  //INCLUDES  functions
  include_once __DIR__. "/../Functions/debug_functions.php";
  include_once __DIR__. "/../Functions/file_functions.php";
  include_once __DIR__. "/../Functions/log_functions.php";
  include_once __DIR__. "/../Functions/replace_functions.php";
  include_once __DIR__. "/../Functions/string_functions.php";

  //USES
  use SB\Template\SB_TagObject;
  use SB\Functions as sb;

  //INTERFACES
  interface iSB_TextFile {
  }

  //CLASSES
  class SB_TextFile
  extends SB_TagObject
  implements iSB_TextFile {
    //published properties
    public $RootPath;
    public $CustomPath;

    public $Filename;
    public $PropertyDirectives;
    public $ReplaceHooks;
    public $ReplaceHooksConditionals;
    public $ReplaceHooksCookies;
    public $ReplaceHooksDatetimes;
    public $ReplaceHooksDecrypt;
    public $ReplaceHooksEncrypt;
    public $ReplaceHooksFontAwesome;
    public $ReplaceHooksIncludes;
    public $ReplaceHooksIncludesMerge;
    public $ReplaceHooksIncludesPHP;
    public $ReplaceHooksNull;
    public $ReplaceHooksParams;
    public $ReplaceHooksProject;
    public $ReplaceHooksRequest;
    public $ReplaceHooksRequestXML;
    public $ReplaceHooksSession;
    public $ReplaceHooksValues;
    public $ReplaceHooksXMLColumns;
    public $ReplaceHooksXMLResult;
    public $ReplaceHooksXMLSearch;
    public $ReplaceHooksXMLSelection;
    public $ReplaceHooksXMLSubmit;
    public $RemoveComments;
    public $StripSlashes;

    public $IncludedFiles               = [];
    public $IncludedFileContents        = [];
    public $IncludedFileContentSections = [];

    //public properties
    public $Text;
    public $TextOriginal;


    //implementation
    public function __construct() {
      parent::__construct();

      //tagname
      $this->TemplateTagname          = "sb:textfile";
      $this->Tagname                  = "";

      //default values
      $this->PropertyDirectives       = true;
      $this->ReplaceHooks             = false;
      $this->ReplaceHooksConditionals = true;
      $this->ReplaceHooksCookies      = true;
      $this->ReplaceHooksDatetimes    = true;
      $this->ReplaceHooksDecrypt      = true;
      $this->ReplaceHooksEncrypt      = true;
      $this->ReplaceHooksFontAwesome  = true;
      $this->ReplaceHooksIncludes     = true;
      $this->ReplaceHooksIncludesMerge= true;
      $this->ReplaceHooksIncludesPHP  = true;
      $this->ReplaceHooksNull         = true;
      $this->ReplaceHooksLabel        = true;
      $this->ReplaceHooksOption       = true;
      $this->ReplaceHooksParams       = true;
      $this->ReplaceHooksProject      = true;
      $this->ReplaceHooksRequest      = true;
      $this->ReplaceHooksRequestXML   = true;
      $this->ReplaceHooksSession      = true;
      $this->ReplaceHooksValues       = true;
      $this->ReplaceHooksXMLColumns   = true;
      $this->ReplaceHooksXMLResult    = true;
      $this->ReplaceHooksXMLRequest   = true;
      $this->ReplaceHooksXMLSearch    = true;
      $this->ReplaceHooksXMLSelection = true;
      $this->ReplaceHooksXMLSubmit    = true;
      $this->RemoveComments           = false;
      $this->StripSlashes             = true;
    }


    public function readString($text) {
      //if (isset($this)) {
        //set original text property
        $this->TextOriginal   = $text;

        if (!sb\is_empty($text)) {
          //replace hooks?
          if ($this->ReplaceHooks) {
            $text = $this->replace_hooks($text);
          }

          //set text propertu
          $this->Text         = $text;
        }
      //}

      return $text;
    }


    public function readFile($filename) {
      //timing
      $timerstart=sb\timer_start();

      //init variables
      $returnvalue = null;

      //if (isset($this)) {
        //file exists?
        if (file_exists($filename)
        &&  !is_dir($filename)) {
          //$this->Filename = $filename;

          //timing
          $timerstart2=sb\timer_start();

          //read text from file
          $text = sb\file_get_contents_utf8($filename);

          if ($this->Timing) {
            sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : readFile - filegetcontents_utf8");
          }

          //set original text property
          $this->TextOriginal = $text;

          if (!sb\is_empty($text)) {
            //property directives?
            if ($this->PropertyDirectives) {
              $this->setProperties($text);
            }

            //remove comments?
            if ($this->RemoveComments) {
              $text = $this->remove_comments($text);
            }

            //replace hooks?
            if ($this->ReplaceHooks) {
              $text = $this->replace_hooks($text);
            }

            //remove comments?
            if ($this->RemoveComments) {
              $text = $this->remove_comments($text);
            }

            //set output property
            $this->Text         = $text;

            $returnvalue = $text;
          } else {
            $returnvalue =  false;
          }

        } else {
          $returnvalue =  false;
        }
      //} else {
      //  $returnvalue =  false;
      //}

      //timing
      if ($this->Timing) {
        sb\fb_timer_end($timerstart, $this->TimingLimit, "SB_TextFile : readFile");
      }

      return $returnvalue;
    }

    /*
    public function replace_hooks($text="") {
      //timing
      $timerstart=sb\timer_start();

      //replace common
      //if (isset($this->ReplaceHooksCommon)
      //  &&  $this->ReplaceHooksCommon) {
      //  $text   = $this->replace_hooks_common($text);
      //}

      //replace params
      if (isset($this->ReplaceHooksParams)
        &&  $this->ReplaceHooksParams) {
        $text   = $this->replace_hooks_param($text, "param");
      }

      //replace includes
      if (isset($this->ReplaceHooksIncludes)
        &&  $this->ReplaceHooksIncludes) {
        $text   = $this->replace_hooks_include($text, "include");
      }

      //inherited
      return parent::replace_hooks($text);
    }
    */

    public function replace_hooks($text="") {
      //timing
      $timerstart=sb\timer_start();

      //replace common
      if (isset($this->ReplaceHooksCommon)
      &&  $this->ReplaceHooksCommon) {
        $text   = $this->replace_hooks_common($text);
      }

      //replace params
      if (isset($this->ReplaceHooksParams)
      &&  $this->ReplaceHooksParams) {
        $text   = $this->replace_hooks_param($text, "param");
      }

      //replace request
      if (isset($this->ReplaceHooksRequest)
      &&  $this->ReplaceHooksRequest) {
        $text   = $this->replace_hooks_request($text, "request");
      }

      //replace session
      if (isset($this->ReplaceHooksSession)
      &&  $this->ReplaceHooksSession) {
        $text   = $this->replace_hooks_session($text, "session");
      }

      //replace includes
      if (isset($this->ReplaceHooksIncludes)
      &&  $this->ReplaceHooksIncludes) {
        $text   = $this->replace_hooks_include($text, "include");
      }

      //replace includemerges
      if (isset($this->ReplaceHooksIncludesMerge)
      &&  $this->ReplaceHooksIncludes) {
        $text   = $this->replace_hooks_includemerge($text, "includemerge");
      }

      //replace phpincludes
      if (isset($this->ReplaceHooksIncludesPHP)
      &&  $this->ReplaceHooksIncludesPHP) {
        $text   = $this->replace_hooks_includephp($text, "includephp");
      }

      //replace conditionals
      if (isset($this->ReplaceHooksConditionals)
      &&  $this->ReplaceHooksConditionals) {
        $text   = $this->replace_hooks_conditional($text, "conditional");
      }

      //replace datetimes
      if (isset($this->ReplaceHooksDatetimes)
      &&  $this->ReplaceHooksDatetimes) {
        $text   = $this->replace_hooks_datetime($text, "datetime");
      }

      //replace values
      if (isset($this->ReplaceHooksValues)
      &&  $this->ReplaceHooksValues) {
        $text   = $this->replace_hooks_value($text, "value");
      }

      //replace params
      if (isset($this->ReplaceHooksParams)
      &&  $this->ReplaceHooksParams) {
        $text   = $this->replace_hooks_param($text, "param");
      }

      //replace request
      if (isset($this->ReplaceHooksRequest)
      &&  $this->ReplaceHooksRequest) {
        $text   = $this->replace_hooks_request($text, "request");
      }

      //replace requestxml
      if (isset($this->ReplaceHooksRequestXML)
      &&  $this->ReplaceHooksRequestXML) {
        $text   = $this->replace_hooks_requestxml($text, "request\:xml");
      }

      //replace xmlsubmit
      if (isset($this->ReplaceHooksXMLSubmit)
      &&  $this->ReplaceHooksXMLSubmit) {
        $text   = $this->replace_hooks_xmlsubmit($text, "xmlsubmit");
      }

      //replace xmlsearch
      if (isset($this->ReplaceHooksXMLSearch)
      &&  $this->ReplaceHooksXMLSearch) {
        $text   = $this->replace_hooks_xmlsearch($text, "xmlsearch");
      }

      //replace xmlselection
      if (isset($this->ReplaceHooksXMLSelection)
      &&  $this->ReplaceHooksXMLSelection) {
        $text   = $this->replace_hooks_xmlselection($text, "xmlselection");
      }

      //replace xmlresult
      if (isset($this->ReplaceHooksXMLResult)
      &&  $this->ReplaceHooksXMLResult) {
        $text   = $this->replace_hooks_xmlresult($text, "xmlresult");
      }

      //replace cookies
      if (isset($this->ReplaceHooksCookies)
      &&  $this->ReplaceHooksCookies) {
        $text   = $this->replace_hooks_cookies($text, "cookie");
      }

      //replace session
      if (isset($this->ReplaceHooksSession)
      &&  $this->ReplaceHooksSession) {
        $text   = $this->replace_hooks_session($text, "session");
      }

      //replace project
      if (isset($this->ReplaceHooksProject)
      &&  $this->ReplaceHooksProject) {
        $text   = $this->replace_hooks_project($text, "project");
      }

      //replace encrypt
      if (isset($this->ReplaceHooksEncrypt)
      &&  $this->ReplaceHooksEncrypt) {
        $text   = $this->replace_hooks_encrypt($text, "encrypt");
      }

      //replace decrypt
      if (isset($this->ReplaceHooksDecrypt)
      &&  $this->ReplaceHooksDecrypt) {
        $text   = $this->replace_hooks_decrypt($text, "decrypt");
      }

      //replace NULL
      if (isset($this->ReplaceHooksNull)
      &&  $this->ReplaceHooksNull) {
        $text   = $this->replace_hooks_null($text, "null");
      }

      //replace font awesome
      if (isset($this->ReplaceHooksFontAwesome)
      &&  $this->ReplaceHooksFontAwesome) {
        $text   = $this->replace_hooks_fontawesome($text, "fa");
      }

      //replace includes
      if (isset($this->ReplaceHooksIncludes)
      &&  $this->ReplaceHooksIncludes) {
        $text   = $this->replace_hooks_include($text, "include");
      }

      //replace merge includes
      if (isset($this->ReplaceHooksIncludesMerge)
      &&  $this->ReplaceHooksIncludesMerge) {
        $text   = $this->replace_hooks_includemerge($text, "includemerge");
      }

      //timing
      if ($this->Timing) {
        sb\fb_timer_end($timerstart, $this->TimingLimit, "SB_TextFile : Replace hooks TOTAL ({$this->ID})");
      }

      return $text;
    }


    public function replace_hooks_common($text) {
      if (!sb\is_empty($text)) {
        //vervang common hooks
        $timerstart2=sb\timer_start();

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace null (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_include($text, $replacetag="include") {
      if (!sb\is_empty($text)) {
        //vervang INCLUDE
        $timerstart2=sb\timer_start();

        while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
                $filenames    = sb\multi_explode(",;", $matches[1]);
                $filecontents = "";

                foreach($filenames as $filename) {
                  $filepart     = sb\leftpart($filename, ":");
                  $sectionpart  = sb\rightpart($filename, ":");

                  //paths bepalen
                  $rootpath       = (!sb\is_empty($this->RootPath)) ? sb\striplastslash($this->RootPath) . "/" : __DIR__ . "/../../../";
                  $rootfilefull   = $rootpath . sb\stripfirstslash($filepart);

                  $custompath     = (!sb\is_empty($this->CustomPath)) ? sb\striplastslash($this->CustomPath) . "/" : "";
                  $customfilefull = (!realpath($this->CustomPath) ? $rootpath : "") . $custompath . sb\stripfirstslash($filepart);

                  //als custom file bestaat dan gaat die voor
                  if (file_exists($customfilefull)) {
                    $filecontents .= $this->includeFile($customfilefull, $sectionpart);
                  } else if (file_exists($rootfilefull)) {
                    $filecontents .= $this->includeFile($rootfilefull, $sectionpart);
                  }
                }

                return $filecontents;
              }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace includes (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_includemerge($text, $replacetag="includemerge") {
      if (!sb\is_empty($text)) {
        //vervang INCLUDEMERGE
        $timerstart2=sb\timer_start();

        while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
                $filenames    = sb\multi_explode(",;", $matches[1]);
                $filecontents = "";

                foreach($filenames as $filename) {
                  $filepart     = sb\leftpart($matches[1], ":");
                  $sectionpart  = sb\rightpart($matches[1], ":");

                  //paths bepalen
                  $rootpath       = (!sb\is_empty($this->RootPath)) ? sb\striplastslash($this->RootPath) . "/" : __DIR__ . "/../../../";
                  $rootfilefull   = $rootpath . sb\stripfirstslash($filepart);

                  $custompath     = (!sb\is_empty($this->CustomPath)) ? sb\striplastslash($this->CustomPath) . "/" : "";
                  $customfilefull = (!realpath($this->CustomPath) ? $rootpath : "") . $custompath . sb\stripfirstslash($filepart);

                  $filecontents .= $this->includeFile($rootfilefull, $sectionpart);
                  $filecontents .= $this->includeFile($customfilefull, $sectionpart);
                }

                return $filecontents;
              }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace merge includes (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_includephp($text, $replacetag="includephp") {
      if (!sb\is_empty($text)) {
        //vervang PHPINCLUDE
        $timerstart2=sb\timer_start();

        while(preg_match_all("/[\{\[]" . $replacetag . "\:([^\[\]]*?)[\}\]]/", $text, $matches)) {
          $text = preg_replace_callback(
              "/[\{\[]" . $replacetag . "\:([^\[\]]*?)[\}\]]/"
            , function($matches) use ($replacetag) {
              $filenames = sb\multi_explode(",;", $matches[1]);

              foreach($filenames as $filename) {
                file_include_exists(__DIR__ . "/../../../" . $filename);
              }
            }
            , $text
          );

          //TODO: phpinclude wordt vooral voor init_opties gebruikt waardoor $_SESSION veranderd
          //      daarom moet this->Session opnieuw gezet worden, mooiere manier verzinnen
          $this->Session = $_SESSION;
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace php includes (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_conditional($text, $replacetag="conditional") {
      if (!sb\is_empty($text)) {
        //vervang CONDITIONALS
        $timerstart2=sb\timer_start();

        while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
              "/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
                return replace_conditionals_preg($matches[0], $replacetag);
              }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace conditionals (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_datetime($text, $replacetag="datetime") {
      //timing
      $timerstart2=sb\timer_start();

      if (!sb\is_empty($text)) {
        //vervang DATETIMES

        while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
              return replace_datetimes_preg($matches[0], $replacetag);
            }
            , $text
          );
        }

        //timing
        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace datetimes (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_value($text, $replacetag="value") {
      if (!sb\is_empty($text)) {
        //vervang VALUES
        $timerstart2=sb\timer_start();

        //get values array
        $valuesarray  = array_change_key_case($this->getValuesArray(), CASE_LOWER);

        while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i"
            , function($matches) use ($replacetag, $valuesarray) {
                return array_get($valuesarray, strtolower($matches[1]), "string", "[conditional:LEFTHOOK]value:" . $matches[1] . "[conditional:RIGHTHOOK]");
              }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace values (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_param($text, $replacetag="param") {
      if (!sb\is_empty($text)) {
        //vervang PARAM
        $timerstart2=sb\timer_start();

        //$paramsarray = array_change_key_case($this->getParamsArray(), CASE_LOWER);
        $paramsarray = $this->getParamsArray();
        //foreach ($paramsarray as $paramskey=>$paramsitem) {
        //  $paramsarray[$paramskey] = strtolower($paramsitem);
        //}

        while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i"
            , function($matches) use ($replacetag, $paramsarray) {
                return array_get($paramsarray, $matches[1], "string", "[conditional:LEFTHOOK]param:" . $matches[1] . "[conditional:RIGHTHOOK]");
              }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace params (" . $replacetag . ")");
        }
      }

      return $text;
    }


    /*TODO: Werkend maken voor guid arrays zoals {guid:WachtwoordlinkID} */
    /*
    public function replace_hooks_guid($text, $replacetag="guid") {
      if (!sb\is_empty($text)) {
        //vervang PARAM
        $timerstart2=sb\timer_start();

        //$paramsarray = array_change_key_case($this->getParamsArray(), CASE_LOWER);
        $paramsarray = $this->getParamsArray();
        //foreach ($paramsarray as $paramskey=>$paramsitem) {
        //  $paramsarray[$paramskey] = strtolower($paramsitem);
        //}

        while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i"
            , function($matches) use ($replacetag, $paramsarray) {
                return array_get($paramsarray, $matches[1], "string", "[conditional:LEFTHOOK]param:" . $matches[1] . "[conditional:RIGHTHOOK]");
              }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace params (" . $replacetag . ")");
        }
      }

      return $text;
    }
    */

    public function replace_hooks_request($text, $replacetag="request") {
      if (!sb\is_empty($text)) {
        //vervang REQUEST
        //TODO: vervangt nu geen [request:x....] moet [request:xml...] worden!!
        $timerstart2=sb\timer_start();

        //while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]x[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
        //  $text = preg_replace_callback(
        //    "/[\[|\{]" . $replacetag . "\:([^\[\]x[\]\{\}]*)[\]|\}]/i"
        while(preg_match_all("/[\[|\{]" . $replacetag . ":((?:(?!\[" . $replacetag . ":])[^\s\"\'\-\=\{\}\<\>\,\/\;\\\~\*\&\^\!])*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . ":((?:(?!\[" . $replacetag . ":])[^\s\"\'\-\=\{\}\<\>\,\/\;\\\~\*\&\^\!])*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
                //replace bracepair
                $replacevalue = replace_bracepair($matches[0], $this->Request, $replacetag, $this->Timing);

                //decrypt?
                if (is_encrypted($replacevalue)) {
                  //decrypt met sessionid
                  $decryptedvalue = decryptConvert($replacevalue);

                  //decrypt zonder sessionid als eerste decrypt rotzooi oplevert
                  if (!is_alphanumeric($decryptedvalue)) {
                    $decryptedvalue = decryptConvert($replacevalue, "crid", false, false);
                  }

                  $replacevalue = $decryptedvalue;
                }

                //replace specialchars?
                $replacevalue = replace_specialchars($replacevalue);

                return $replacevalue;
              }
            , $text
          );
        }

        //timing
        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace request (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_requestxml($text, $replacetag="request\:xml") {
      if (!sb\is_empty($text)) {
        //vervang REQUEST:XML
        $timerstart2=sb\timer_start();

        while(preg_match_all("/\[" . $replacetag . "([^\[\]]*?)\]/", $text, $matches)) {
          $text = preg_replace_callback(
            "/\[" . $replacetag . "([^\[\]]*?)\]/"
            , function($matches) use ($replacetag) {
                return array_get($this->Request, strtolower("xml" . $matches[1]), "string", "");
              }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace request xml (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_xmlsubmit($text, $replacetag="xmlsubmit") {
      if (!sb\is_empty($text)) {
        //vervang XMLSUBMIT
        $timerstart2=sb\timer_start();

        //if (!sb\is_empty($this->XMLSubmit)) {
        while(preg_match_all("/(?|\[" . $replacetag."\:(.*\])\]|\{" . $replacetag."\:(.*\])\}|[\[\{]" . $replacetag."\:(.*)[\}\]])/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/(?|\[" . $replacetag."\:(.*\])\]|\{" . $replacetag."\:(.*\])\}|[\[\{]" . $replacetag."\:(.*)[\}\]])/i"
            , function($matches) use ($replacetag) {
                return replace_xmlsubmit("[" . $replacetag . ":" . $matches[1] . "]", $this->XMLSubmit, $this->StripSlashes);
              }
            , $text);
        }
        //}

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace xmlsubmit (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_xmlsearch($text, $replacetag="xmlsearch") {
      if (!sb\is_empty($text)) {
        //vervang XMLSEARCH
        $timerstart2=sb\timer_start();

        //if (!sb\is_empty($this->XMLSearch)) {
        while(preg_match_all("/(?|\[" . $replacetag."\:(.*\])\]|\{" . $replacetag."\:(.*\])\}|[\[\{]" . $replacetag."\:(.*)[\}\]])/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/(?|\[" . $replacetag."\:(.*\])\]|\{" . $replacetag."\:(.*\])\}|[\[\{]" . $replacetag."\:(.*)[\}\]])/i"
            , function($matches) use ($replacetag) {
                return replace_xmlsearch("[" . $replacetag . ":" . $matches[1] . "]", $this->XMLSearch, $this->StripSlashes);
              }
            , $text);
        }
        //}

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace xmlsearch (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_xmlselection($text, $replacetag="xmlselection") {
      if (!sb\is_empty($text)) {
        //vervang XMLSELECTION
        $timerstart2=sb\timer_start();

        //if (!sb\is_empty($this->XMLSelection)) {
        while(preg_match_all("/(?|\[" . $replacetag."\:(.*\])\]|\{" . $replacetag."\:(.*\])\}|[\[\{]" . $replacetag."\:(.*)[\}\]])/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/(?|\[" . $replacetag."\:(.*\])\]|\{" . $replacetag."\:(.*\])\}|[\[\{]" . $replacetag."\:(.*)[\}\]])/i"
            , function($matches) use ($replacetag) {
                return replace_xmlselection("[" . $replacetag . ":" . $matches[1] . "]", $this->XMLSelection, $this->StripSlashes);
              }
            , $text);
        }
        //}

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace xmlselection (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_xmlresult($text, $replacetag="xmlresult") {
      if (!sb\is_empty($text)) {
        //vervang XMLRESULT
        $timerstart2=sb\timer_start();

        //if (!sb\is_empty($this->XMLResult)) {
        while(preg_match_all("/(?|\[" . $replacetag."\:(.*\])\]|\{" . $replacetag."\:(.*\])\}|[\[\{]" . $replacetag."\:(.*)[\}\]])/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/(?|\[" . $replacetag."\:(.*\])\]|\{" . $replacetag."\:(.*\])\}|[\[\{]" . $replacetag."\:(.*)[\}\]])/i"
            , function($matches) use ($replacetag) {
                return replace_xmlresult("[" . $replacetag . ":" . $matches[1] . "]", $this->XMLResult, $this->StripSlashes);
              }
            , $text);
        }
        //}

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace xmlresult (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_cookies($text, $replacetag="cookie") {
      if (!sb\is_empty($text)) {
        //vervang SESSION
        $timerstart2=sb\timer_start();
        while(preg_match_all("/[\[|\{]" . $replacetag . ":((?:(?!\[" . $replacetag . ":])[^\s\"\'\-\=\{\}\<\>\,\/\;\\\~\*\&\^\!])*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . ":((?:(?!\[" . $replacetag . ":])[^\s\"\'\-\=\{\}\<\>\,\/\;\\\~\*\&\^\!])*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
                return replace_specialchars(replace_bracepair($matches[0], $this->Cookie, $replacetag, $this->Timing));
              }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace cookies (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_session($text, $replacetag="session") {
      if (!sb\is_empty($text)) {
        //vervang SESSION
        $timerstart2=sb\timer_start();

        while(preg_match_all("/[\[|\{]" . $replacetag . ":((?:(?!\[" . $replacetag . ":])[^\s\"\'\-\=\{\}\<\>\,\/\;\\\~\*\&\^\!])*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . ":((?:(?!\[" . $replacetag . ":])[^\s\"\'\-\=\{\}\<\>\,\/\;\\\~\*\&\^\!])*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
                return replace_specialchars(replace_bracepair($matches[0], $this->Session, $replacetag, $this->Timing), false, true);
              }
            , $text
          );
        }

        if (true||$this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace session (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_project($text, $replacetag="project") {
      if (!sb\is_empty($text)) {
        //vervang prject variables
        $timerstart2=sb\timer_start();

        while(preg_match_all("/[\[|\{]" . $replacetag . ":((?:(?!\[" . $replacetag . ":])[^\s\"\'\-\=\{\}\<\>\,\/\;\\\~\*\&\^\!])*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . ":((?:(?!\[" . $replacetag . ":])[^\s\"\'\-\=\{\}\<\>\,\/\;\\\~\*\&\^\!])*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
              return replace_specialchars(replace_bracepair($matches[0], $this->Session["project"], $replacetag, $this->Timing));
            }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace project (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_encrypt($text, $replacetag="encrypt") {
      if (!sb\is_empty($text)) {
        //vervang ENCRYPT
        $timerstart2=sb\timer_start();

        while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
              return replace_specialchars(encryptConvert($matches[1]));
            }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace encrypt (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_decrypt($text, $replacetag="decrypt") {
      if (!sb\is_empty($text)) {
        //vervang DECRYPT
        $timerstart2=sb\timer_start();

        while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
                return replace_specialchars(decryptConvert($matches[1]));
              }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace decrypt (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_null($text, $replacetag="null") {
      if (!sb\is_empty($text)) {
        //vervang "NULL" door NULL
        $timerstart2=sb\timer_start();

        $text = preg_replace("/(\"\s*" . $replacetag . "\s*\")/i", "NULL", $text);

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace null (" . $replacetag . ")");
        }
      }

      return $text;
    }


    public function replace_hooks_fontawesome($text, $replacetag="fa") {
      if (!sb\is_empty($text)) {
        //vervang "fa:blabla" door <i class='fa-blabla' />
        $timerstart2=sb\timer_start();

        while(preg_match_all("/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i", $text, $matches)) {
          $text = preg_replace_callback(
            "/[\[|\{]" . $replacetag . "\:([^\[\]\{\}]*)[\]|\}]/i"
            , function($matches) use ($replacetag) {
                $icon   = sb\leftpart($matches[1], "(");
                $style  = sb\leftpart(sb\rightpart($matches[1], "("), ")");

                return "<i class=\"fa fa-" . $icon . "\" style=\"". $style . "\" ></i>";
              }
            , $text
          );
        }

        if ($this->Timing) {
          sb\fb_timer_end($timerstart2, $this->TimingLimit, "SB_TextFile : Replace hooks - replace font awesome (" . $replacetag . ")");
        }
      }

      return $text;
    }


    /*
    public function remove_comments($text) {
      //timing
      $timerstart=sb\timer_start();

      if (isset($this)) {
        //smarty style comments weghalen
        $text = preg_replace('/{\*.*\*}/Uism', '', $text);

        // JAVASCRIPT comments weghalen
        // (moet met enkele aanhalingstekens, anders geeft ie een fout aan!)
        //$text = preg_replace('/\/\*[^\[]*[^\]]\*\//imsU', '', $text);
        //$text=remove_javascriptcomments($text, $this->Timing, $this->TimingLimit);
        //$text = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\)\/\/.*))/', '', $text);
        $text = preg_replace('/\/\*(.*?)\*\//ims', '', $text);

        // HTML comments weghalen
        //$text = preg_replace('/<!--.*-->/Uism', '', $text);
        //$text=remove_htmlcomments($text, $this->Timing, $this->TimingLimit);
        $text = preg_replace("/<!--(.*?)-->/", "", $text);

        //$output = preg_replace('/\<!--(.|\t\n\r)*?--\>/u', '', $output );
        //$output = preg_replace('#\<!--([^>]*)--\>#', '', $output );

        //timing
        if ($this->Timing) {
          sb\fb_timer_end($timerstart, $this->TimingLimit, "SB_TextFile : remove comments ({$this->ID}) " . get_caller_method());
        }
      }

      return $text;
    }
    */

    public function remove_comments($output) {
      //timing
      $timerstart=sb\timer_start();

      if (isset($this)) {
        // smarty style comments weghalen
        $output = preg_replace('/{\*.*\*}/Uis', '', $output );

        // javascript style comments weghalen
        // (moet met enkele aanhalingstekens, anders geeft ie een fout aan!)
        $output = preg_replace('/\/\*.*\*\//Uis', '', $output );

        //verwijder alles wat niet tot de template behoort
        /*
        $return = array();
        $numberofmatches = preg_match_all ('#<!-- BEGIN TEMPLATE -->([\S\s]*?)<!-- END TEMPLATE -->#', $output, $return);
        if ($numberofmatches>0) {
          $output = '';
          foreach($return[0] as $tempcode) {
            $temp = str_ireplace('<!-- BEGIN TEMPLATE -->', "", $tempcode);
            $temp = str_ireplace('<!-- END TEMPLATE -->', "", $temp);
            $output .= $temp;
          }
        } else {
          //$output = $input;
        }
        */

        // html style comments weghalen
        //$output = preg_replace('/<!--.*-->/Uis', '', $output );
        $output = removecomments_html($output);

        //$output = preg_replace('/\<!--(.|\t\n\r)*?--\>/u', '', $output );
        //$output = preg_replace('#\<!--([^>]*)--\>#', '', $output );

      }

      return $output;
    }


    public function setProperties($text) {
      //timing
      $timerstart = sb\timer_start();

      //array met properties aanmaken
      $propertyresult = array();

      //array met sections vullen
      preg_match_all("/<!--\[Property (.*?)=(.*?)\]-->/i", $text, $propertyresult);

      if (count($propertyresult) > 0) {
        foreach ($propertyresult[0] as $propertyindex=>$propertystring) {
          $propertystring = betweenpart($propertystring, "<!--[", "]-->");

          $propertykeyvaluepair  =  sb\rightpart($propertystring, " ");
          $propertykey           =  sb\leftpart($propertykeyvaluepair, "=");
          $propertyvalue         =  sb\rightpart($propertykeyvaluepair, "=");

          if (property_exists($this, $propertykey)) {
            if (is_string($this->$propertykey)) {
              //STRING
              $this->$propertykey  =  $propertyvalue;

            } else if (is_bool($this->$propertykey)) {
              //BOOL
              $this->$propertykey  =  boolOrEval($propertyvalue);

            } else if (is_number($this->$propertykey)) {
              //NUMBER
              $this->$propertykey  =  $propertyvalue;

            } else if (is_null($this->$propertykey)) {
              //NULL
              $this->$propertykey  =  $propertyvalue;
            }
          }
        }
      }

      //timing
      if ($this->Timing) {
        sb\fb_timer_end($timerstart, $this->TimingLimit, "SB_TextFile : Set Properties");
      }
    }


    public function includeFile($fullfilename, $sectionname= "") {
      if (!array_key_exists($fullfilename, $this->IncludedFileContents)) {
        $filecontents = sb\file_get_contents_exists($fullfilename);

        $this->IncludedFiles[]                      = $fullfilename;
        $this->IncludedFileContents[$fullfilename]  = $filecontents;

        if ($filecontents) {
          //array met sections vullen
          //$sections = preg_split("/\<\!--\s*[\[\{]section:/", $filecontents);
          $sections = preg_split("/<!--\s*<<\s*/", $filecontents);

          foreach($sections as $section) {
            $sectionparts = preg_split("/\s*>>\s*--\>/", $section);

            if (count($sectionparts) > 1) {
              if ($sectionparts[0] && $sectionparts[1]) {
                $this->IncludeFileContentSections[$fullfilename][strtolower($sectionparts[0])] = $sectionparts[1];
              }
            }
          }
        } else {
          $returnvalue = false;
        }
      } else {
        $filecontents = $this->IncludedFileContents[$fullfilename];
      }

      if ($sectionname) {
        $returnvalue = $this->IncludeFileContentSections[$fullfilename][strtolower($sectionname)];
      } else {
        $returnvalue = $filecontents;
      }


      return $returnvalue;
    }

    /*
    public function includeFile($filename) {
      //OVERRIDE includeFile

      //init variables
      $rootpath       = sb\striplastslash($this->RootPath);
      $custompath     = sb\striplastslash($this->CustomPath);

      //paths bepalen
      $rootpath       = (!sb\is_empty($rootpath)) ? sb\striplastslash($this->RootPath) . "/" : __DIR__ . "/../../../";
      $rootfilefull   = $rootpath . sb\stripfirstslash($filename);

      $custompath     = (!sb\is_empty($custompath)) ? sb\striplastslash($this->CustomPath) . "/" : "";
      $customfilefull = (!realpath($custompath) ? $rootpath : "") . $custompath . sb\stripfirstslash($filename);

      //aan include files toevoegen
      $this->IncludedFiles[] = $rootfilefull;
      $this->IncludedFiles[] = $customfilefull;

      //if ($this->Developer) {
      //  fbb("ROOT INCLUDE : " . $rootfilefull . " : " . file_exists($rootfilefull));
      //  fbb("CUSTOM INCLUDE : " . $customfilefull . " : " . file_exists($customfilefull));
      //}

      //als custom file bestaat dan custom file gebruiken
      if (file_exists($customfilefull)) {
        return sb\file_get_contents_exists($customfilefull);
      } else if (file_exists($rootfilefull)) {
        return sb\file_get_contents_exists($rootfilefull);
      } else {
        return "";
      }
    }
    */
  }


?>