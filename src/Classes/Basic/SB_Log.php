<?php
  //NAMESPACE
  namespace SB\Classes\Basic;

  //USES
  use SB\Traits\Basic\tSB_ObjectHelper;

  use Illuminate\Support\Facades\Log;
  use Monolog\Logger;
  use Monolog\Handler\StreamHandler;
  use Monolog\Handler\FirePHPHandler;
  use Monolog\Handler\ChromePHPHandler;
  use Monolog\Handler\BrowserConsoleHandler;
  use SB\Functions as sb;

  //INCLUDES
  include_once __DIR__ . "/../../Functions/string_functions.php";
  include_once __DIR__ . "/../../Functions/array_functions.php";

  //CLASSES
  class SB_Log {
    //uses
    use tSB_ObjectHelper;

    //published properties
    public $Filename    = __DIR__ . "../temp/logs/test.log";
    public $Level       = Logger::DEBUG;
    public $Name        = "Log";

    //protected properties
    protected $Handler  = "FIREPHP"; //"CONSOLE";

    //private properties
    private $Logger     = null;

    //implementation
    public function __construct($handler="CONSOLE", $name="Log", $level=Logger::DEBUG) {
      $this->Name       = $name;
      $this->Level      = $level;
      $this->Handler    = $handler;

      $this->Logger     = new Logger($this->Name);

      $this->setHandler($this->Handler);
    }


    public function setHandler($handler) {
      $this->Handler  = $handler;

      switch (strtoupper($this->Handler)) {
        case "FIREBUG"    :
        case "FIREPHP"    :
          $this->Logger->pushHandler(new FirePHPHandler($this->Level));
          break;

        case "CHROME"     :
        case "CHROMELOG"  :
        case "CHROMEPHP"  :
          $this->Logger->pushHandler(new ChromePHPHandler($this->Level));
          break;

        case "CONSOLE"    :
        case "BROWSER"    :
          $this->Logger->pushHandler(new BrowserConsoleHandler($this->Level));
          break;

        case "FILE"       :
        case "STREAM"     :
          $this->Logger->pushHandler(new StreamHandler($this->Filename, $this->Level));
          break;

        default           :
          $this->Logger->pushHandler(new FirePHPHandler($this->Level));
          break;
      }
    }


    public function getHandler() {
      return $this->Handler;
    }


    public function log($var, $level=Logger::INFO) {
      $text =  "";
      $arr  = [];

      if (is_array($var)) {
        $text = "";
        $arr  = $var;
      } else if (is_object($var)) {
        $text = "";
        $arr =  json_decode(json_encode($var), true); //sb\object_to_array($var);
      } else {
        $text = (string) $var;
        $arr  = [];
      }

      if ($this->Logger) {
        switch ($level) {
          case Logger::DEBUG  :
          case Logger::INFO   :
            $this->Logger->addInfo($text, $arr);
            break;

          case Logger::ERROR  :
            $this->Logger->addError($text, $arr);
            break;

          case Logger::WARNING:
            $this->Logger->addWarning($text, $arr);
            break;

          case Logger::NOTICE :
            $this->Logger->addNotice($text, $arr);
            break;

          case Logger::ALERT  :
            $this->Logger->addAlert($text, $arr);
            break;

          case Logger::CRITICAL  :
            $this->Logger->addCritical($text, $arr);
            break;
        }
      }

    }
  }