<?php
  //NAMESPACE
  namespace SB\Services;

  //USES
  use View;
  use Session;
  use Symfony\Component\Yaml\Parser;
  use SB\Interfaces\YAMLParserInterface;
  use SB\Classes\Basic\SB_Object;
  use SB\Functions as sb;


  //INCLUDE
  include __DIR__ . "/../Functions/json_functions.php";

  // CLASSES
  class YAMLParserService
  extends SB_Object
  implements YAMLParserInterface {
    //published properties
    public $Parser              = null;

    public $Filename          = "";
    public $Filecontents      = "";
    public $SessionIdentifier = "";
    public $YAML              = [];

    //implementation
    public function __construct($propertyarray=[]) {
      //inherited
      parent::__construct($propertyarray);

      $this->init();
    }


    /**
     * Init YAMLparser object
     *
     * @param  boolean $initObject
     */
    public function init($initObject=null) {
      //inherited
      parent::init($initObject);

      $this->Parser = new Parser();
    }


    /**
     * get an item from a yaml file
     *
     * @param  string $yamlstring
     * @return mixed
     */
    public function get($yamlstring="", $deep=false) {
      //init variables
      $returnresult     = null;
      $yaml             = [];
      $yamlcontents     = "";
      $yamlresult       = [];
      $yamlresult2      = [];
      $yamlstringparts  = [];

      //filenames from session?
      if (sb\is_empty($this->Filename)) {
        if ($this->SessionIdenitfier
        &&  Session::has($this->SessionIdenitfier)) {
          $this->filename(Session::get($this->SessionIdenitfier));
          $this->load();
        }
      }

      //load YAML?
      if (sb\is_empty($this->YAML)) {
        $this->load($this->Filename);
      }

      //get value
      if (!sb\is_empty($this->YAML)) {
        //specific value or all values?
        if (!sb\is_empty($yamlstring)) {
          //split yamlstring
          $yamlstringparts  = explode(".", $yamlstring);

          //iterate yamlstringparts
          $yamlresult = $this->YAML;
          foreach ($yamlstringparts as $yamlstringpart) {
            if (isset($yamlresult[$yamlstringpart])) {
              $yamlresult = $yamlresult[$yamlstringpart];
            } else {
              if ($deep) {
                if (is_array($yamlresult)) {
                  foreach ($yamlresult as $yamlitem) {
                    if (isset($yamlitem[$yamlstringpart])) {
                      $yamlresult2[] = $yamlitem[$yamlstringpart];
                    }
                  }
                  $yamlresult = $yamlresult2;
                } else {
                  $yamlresult = null;
                  break;
                }
              } else {
                $yamlresult = null;
              }
            }
          }

          $returnresult = $this->yamlvalue($yamlresult);
        } else {
          //all values
          $returnresult = $this->yamlvalues($this->YAML);
        }
      }

      return $returnresult;
    }


    /**
     * get all items from a yaml file
     *
     * @return mixed
     */
    public function getAll() {
      return $this->get(null);
    }


      /**
     * get items from a yaml file as string
     *
     * @param  string $yamlstring
     * @return string
     */
    public function getString($yamlstring="", $separator=",", $deep=false) {
      $yamlresult = $this->get($yamlstring, $deep);

      if (is_array($yamlresult)) {
        $yamlresult = implode($separator, $yamlresult);
      } else if (is_bool($yamlresult)) {
        $yamlresult = sb\booltostr($yamlresult);
      } else if (sb\is_number($yamlresult)) {
        $yamlresult = (string)$yamlresult;
      } else {
        $yamlresult = (string)$yamlresult;
      }

      return $yamlresult;
    }


    /**
     * get item from a yaml file as array
     *
     * @param  string $yamlstring
     * @param  array/string $filenames
     * @return array
     */
    public function getArray($yamlstring) {
      $returnvalue = $this->get($yamlstring);

      if (!is_array($returnvalue)) {
        $returnvalue = [$returnvalue];
      } else {
        $returnvalue = $returnvalue;
      }

      return $returnvalue;
    }


    /**
     * get item from a yaml file as json
     *
     * @param  string $yamlstring
     * @param  array/string $filenames
     * @return json object
     */
    public function getJSON($yamlstring, $filenames="") {
      $returnvalue = $this->get($yamlstring, $filenames);

      if (is_string($returnvalue)
      && sb\is_json($returnvalue)) {
        $returnvalue = json_decode($returnvalue);
      } else {
        $returnvalue = null;
      }

      return $returnvalue;
    }


    /**
     * get evaluated yaml variable
     *
     * @param  mixed $yamlvar
     * @return mixed
     */
    public function yamlvalue($yamlvar="") {
      //init variables
      $yamleval   = "";
      $yamlresult = [];

      if (!is_array($yamlvar)) {
        $yamlvararray = [$yamlvar];
      } else {
        $yamlvararray = $yamlvar;
      }

      if (is_array($yamlvararray)) {
        foreach ($yamlvararray as $yamlkey=>$yamlvalue) {
          if (is_string($yamlvalue)) {
            $yamlvalue = trim($yamlvalue);

            if (sb\startswith($yamlvalue, "eval(")) {
              $yamleval = sb\leftfrom(sb\rightfrom($yamlvalue, 5), 2);
              $yamlresult[$yamlkey] = eval($yamleval);
            } else {
              $yamlresult[$yamlkey] = $yamlvalue;
            }
          } else {
            $yamlresult[$yamlkey] = $yamlvalue;
          }
        }
      }

      if (count($yamlresult) == 0) {
        $yamlresult = null;
      } else if (count($yamlresult) == 1
             && array_keys($yamlresult) === range(0, count($yamlresult) - 1)) {
        $yamlresult = array_values($yamlresult)[0];
      }

      return $yamlresult;
    }


    /**
     * get all items from a yaml file
     *
     * @return mixed
     */
    function yamlvalues(&$yamlarray) {
      //init variables
      $returnvalue  = [];

      //iterate yaml array
      foreach ($yamlarray as $key => $value) {
        if (is_array($value)) {
          $returnvalue[$key]  = $this->yamlvalues($value);
        } else {
          $returnvalue[$key]  = $this->yamlvalue($value);
        }
      }

      return $returnvalue;
    }


    /**
     * get/set filename
     *
     * @param  string/array $filenames
     * @return string
    */
    public function filename($filenames="") {
      //get / set ?
      if (!sb\is_empty($filenames)) {
        //set filename property
        $this->Filename = $filenames;
      }

      return $this->Filename;
    }


    /**
     * load YAML file
     *
     * @return YAML array
     */
    public function load($filenames="") {
      View::addExtension('blade.yaml','blade');

      //init variables
      $filecontents = "";
      $yaml         = [];

      //filenames empty?
      if (sb\is_empty($filenames)) {
        $filenames  = $this->Filename;
      }

//if (file_exists("C:/Temp/yaml" .sb\filename_noextension(basename($filenames[0])) . ".txt")) {
//  $filecontents = file_get_contents("C:/Temp/yaml" .sb\filename_noextension(basename($filenames[0])) . ".txt");
//  $this->YAML = unserialize($filecontents);
//} else {
      //get yaml from filename(s)
      if (!sb\is_empty($filenames)) {
        //set filename property
        $this->Filename = $filenames;

        //iterate filenames
        if (is_string($filenames)) {
          $filenames  = explode(";", $this->Filename);
        }

        foreach ($filenames as $filename) {
          if (file_exists($filename)) {
            //COMPILE blade file
            //TODO: werkt niet ivm basename($filename) wordt altijd in de eerste directory gezocht
            //$strippedfilename = basename($filename, ".blade.yaml");
            //View::addLocation( dirname($filename));
            //$viewcontents = View::make($strippedfilename);

            $filecontents = trim(file_get_contents($filename));
            //$viewcontents = View::make(
            //    ['template' => $filecontents]
            //);
            //$viewcontents = view(['template' => $filecontents]);
            $viewcontents = $this->bladeCompile($filecontents);

            //SAVE?
            //file_put_contents("C:/Temp/". basename($filename) . rand(10000,20000) .  "yaml.txt", $viewcontents);

            //PARSE!
            if (!sb\is_empty((string)$viewcontents)) {
              $yaml = array_replace_recursive($yaml, $this->Parser->parse($viewcontents));
            }
          }
        }

        $this->YAML = $yaml;
      } else {
        return $this->Filename;
      }
//}
    }


    public function bladeCompile($value, array $args = array())  {
      $generated = \Blade::compileString($value);
      $evaluated = $this->evalstring($generated, $args);

      //return $this->evalstring($generated, $args);
      return \Blade::compileString($evaluated);
    }


    public function evalstring($string, array $args = array()) {
      ob_start() and extract($args, EXTR_SKIP);

      try {
        eval('?>'.$string);
      }

      catch (\Exception $e) {
        ob_get_clean(); throw $e;
      }

      $content = ob_get_clean();

      //TODO: dit moet zodat headers (logs) hierna nog goed gaan, maar waaarom??
      //en hangt dit ook nog af van output_buffer true of false in php.ini / htaccess / user.ini !??
      //ob_start();

      if (sb\contains($content, "<?")
      ||  sb\contains($content, "@switch")
      ||  sb\contains($content, "@includeroot")) {
        $content = $this->bladeCompile($content);
      }

      return $content;
    }

  }


