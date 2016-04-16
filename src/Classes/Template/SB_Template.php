<?
  //NAMESPACE
  namespace SB\Classes\Template;

  //INCLUDES functions
  include_once __DIR__ . "/../Functions/file_functions.php";
  include_once __DIR__ . "/../Functions/string_functions.php";

  //USES
  use SB\Template\SB_TextFile;
  use SB\Functions as sb;

  //INTERFACES
  interface iSB_Template {
  }

  //CLASSES
  class SB_Template
  extends SB_TextFile
  implements iSB_Template{
    //published properties
    public $Evaluate;
    public $ReplaceScriptOperators;

    //implementation
    public function __construct() {
      parent::__construct();

      //tagname
      $this->TemplateTagname        = "sb:template";
      $this->Tagname                = "";

      //default values
      $this->Evaluate               = false;
      $this->ReplaceScriptOperators = true;
    }


    public function readFile($filename) {
      //inherited
      $text = parent::readFile($filename);

      //if (isset($this)) {
        if (!sb\is_empty($text)) {
          //replace conditionals?
          if ($this->ReplaceScriptOperators) {
            $text = $this->replace_scriptoperators($text);
          }

          //evaluate template ?
          if ($this->Evaluate) {
            $text = $this->evaluateTemplate($text);
          }

          //set output property
          $this->Text = $text;

          return $text;
        } else {
          return false;
        }
      //} else {
      //  return false;
      //}
    }


    public function replace_scriptoperators($text="") {
      if (isset($this)) {
        //init variables
        if (!sb\is_empty($text)) {
          $text = $text;
        } elseif (!sb\is_empty($this->Text)) {
          $text = $this->Text;
        } elseif (!sb\is_empty($this->TextOriginal)) {
          $text = $this->TextOriginal;
        }

        if (!sb\is_empty($text)) {
          $matches = array();

          //vervang > en < tussen ...script tags door veilige conditionals
          preg_match_all('#<[^script]*?script[^>]*?>(.*?)</.*?script>#s', $text, $matches);

          foreach ($matches[1] as $key=>$match) {
            $between = $match;

            $replacedbetween = $between;
            $replacedbetween = str_ireplace('<<', '{conditional:SHIFTLEFT}', $replacedbetween);
            $replacedbetween = str_ireplace('>>', '{conditional:SHIFTRIGHT}', $replacedbetween);
            $replacedbetween = str_ireplace('>=', '{conditional:GREATERTHANOREQUALTO}', $replacedbetween);
            $replacedbetween = str_ireplace('<=', '{conditional:LESSTHANOREQUALTO}', $replacedbetween);
            $replacedbetween = str_ireplace('>', '{conditional:GREATERTHAN}', $replacedbetween);
            $replacedbetween = str_ireplace('<', '{conditional:LESSTHAN}', $replacedbetween);

            $replacedmatch = str_ireplace($between, $replacedbetween, $match);

            $text = str_replace( $match, $replacedmatch, $text);
          }
        }
      }

      return $text;
    }


    public function remove_comments($text) {
      if (isset($this)) {
        //verwijder alles wat niet tot de template behoort
        $return = array();
        $numberofmatches = preg_match_all ('#<!-- BEGIN TEMPLATE -->([\S\s]*?)<!-- END TEMPLATE -->#', $text, $return);

        if ($numberofmatches>0) {
          $text = "";
          foreach($return[0] as $tempcode) {
            $temp = str_ireplace ("<!-- BEGIN TEMPLATE -->", "", $tempcode);
            $temp = str_ireplace ("<!-- END TEMPLATE -->", "", $temp);

            $text .= $temp;
          }
        }
      }

      //inherited
      $text = parent::remove_comments($text);

      return $text;
    }


    public function evaluateTemplate($text="") {
      //init variables
      if (!sb\is_empty($text)) {
        $text = $text;
      } elseif (!sb\is_empty($this->Text)) {
        $text = $this->Text;
      } elseif (!sb\is_empty($this->TextOriginal)) {
        $text = $this->TextOriginal;
      }

      if (!sb\is_empty($text)) {
        //evaluate text
        $text = evalphpstr($text);

        //set output property
        $this->Text        = $text;
      }

      return $text;
    }


    public function clearTemplate() {
      $this->Text = "";
    }


    public function output() {
      echo $this->Text;
    }

  }

?>