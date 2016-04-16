<?php
  //NAMESPACE
  namespace SB\Classes\Basic;

  //USES
  use SB\Classes\Basic\SB_Error;

  //CLASSES
  class XMLParseErrorException
  extends Exception {
    public function __construct($filename) {
      set_error_handler(array($this,"error_handler_xml"));

      $this->message .= "XML Parse Error" . PHP_EOL . PHP_EOL;

      if (is_string($filename)) {
        if (is_file($filename)) {
          $this->message .= "XML File: " . $filename . PHP_EOL;
        } else {
          $this->message .= "XML String: " . $filename . PHP_EOL;
        }
      }

      $dom = new DOMDocument();
      $dom->load($filename);

      //restore normal error handler
      restore_error_handler();

      parent::__construct();
    }


    public function error_handler_xml($errno, $errstr, $errfile, $errline) {
      $error    = libxml_get_last_error();
      $errmsg   = "";
      $errfile  = "";
      $errline  = "";
      $errcol   = "";
      $errinfo  = "";

      if ($error) {
        $errno          = $error->level;
        $errmsg         = $error->message;
        $errfile        = $error->file;
        $errline        = $error->line;
        $errcol         = $error->column;
        $errcallerinfo  = get_caller_info();
      }

      //get original message
      $pos = strpos($errstr,"]:") ;
      if ($pos) {
        $errstr = substr($errstr,$pos+ 2);
      }
      $errinfo = $errstr;

      //log error
      if (isset($_SESSION["project"]) && isset($_SESSION["project"]["error_logging"]) && $_SESSION["project"]["error_logging"])       {error_logger($errno, $errmsg, $errinfo, $errfile, $errline, $errcallerinfo);}
      if (isset($_SESSION["project"]) && isset($_SESSION["project"]["error_filing"])  && $_SESSION["project"]["error_filing"])        {error_filer($errno, $errmsg, $errinfo, $errfile, $errline, $errcallerinfo);}
      if (isset($_SESSION["project"]) && isset($_SESSION["project"]["error_mailing"]) && $_SESSION["project"]["error_mailing"])       {error_mailer($errno, $errmsg, $errinfo, $errfile, $errline, $errcallerinfo);}
      if (isset($_SESSION["project"]) && isset($_SESSION["project"]["error_displaying"]) && $_SESSION["project"]["error_displaying"]) {error_displayer($errno, $errmsg, $errinfo, $errfile, $errline, $errcallerinfo);}
    }
  }
