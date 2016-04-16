<?php
  //NAMESPACE
  namespace SB\Functions;

  //USES
  use SB\Classes\Basic\SB_Error;

  //INCLUDES functions
  require_once __DIR__ . "/debug_functions.php";

  //FUNCTIONS
  function boolOrEval($var, $default=null, $objectid="", $propertyname="", $info="") {
    //init variables
    $returnvalue    = false;

    if (is_string($var)) {
      $var = htmlspecialchars_decode($var);

      //STRING
      if (stripos($var, "[") === false
      &&  stripos($var, "{") === false
      &&  stripos($var, "eval_") === false
      &&  stripos($var, "condition:") === false) {
        if (!comparetext($var, "")) {
          try {
            $errorphp  = SB_Error::error_php_syntax("return (" . $var . ");");

            if (!$errorphp) {
              $returnvalue = eval("return (" . $var . ");");
            } else {
              fbb("boolOrEval : " . $objectid . "->". $propertyname . " = " . $var . " (" . get_caller_info() . ")");

              SB_Error::error_logger(E_USER_ERROR, "String could not be evaluated", PHP_EOL . "Object : $objectid" . PHP_EOL . "Property : $propertyname" . PHP_EOL . "Value: $var" . PHP_EOL . "Caller: " . get_caller_info(), basename(__FILE__), __LINE__);

              $returnvalue = "!!ERROR!!";
            }
          } catch (Exception $e) {
            SB_Error::error_logger(E_USER_ERROR, "String could not be evaluated", PHP_EOL . "Error : $e->getMessage()" . PHP_EOL . "Object : $objectid" . PHP_EOL . "Property : $propertyname" . PHP_EOL . "Value: $var" , basename(__FILE__), __LINE__);
          }
        } else {
          $returnvalue = (!is_null($default)) ? $default : $var;
        }
      } else {
        $returnvalue = (!is_null($default)) ? $default : $var;
      }

    } else if (is_bool($var)) {
      //BOOL
      $returnvalue  = $var;

    } else if (is_number($var)) {
      //NUMBER
      $returnvalue  = ($var != 0);

    } else if (is_null($var)) {
      //NULL
      $returnvalue  = (!is_null($default)) ? $default : false;
    }

    return $returnvalue == true;
  }


  function boolOrEvalString($var, $default=null) {
    $returnvalue = null;

    $evalvalue = boolOrEval($var, $default);

    if ($evalvalue === TRUE) {
      $returnvalue = "true";
    } elseif ($evalvalue === FALSE) {
      $returnvalue = "false";
    } else {
      $returnvalue = $var;
    }

    return $returnvalue;
  }


  function valueOrEval($var, $default=null, $objectid="", $propertyname="") {
    //init variables
    $returnvalue    = false;

    $var = htmlspecialchars_decode($var);

    if (is_string($var)) {
      //STRING
      if (stripos($var, "[") === false
      &&  stripos($var, "eval_") === false) {
        if (!comparetext($var, "")) {
          try {
            $errorphp  = SB_Error::error_php_syntax("return (" . $var . ");");

            if (!$errorphp) {
              $returnvalue = eval("return (" . $var . ");");
            } else {
              fbb($objectid . ".". $propertyname . " = " . $var);

              SB_Error::error_logger(E_USER_ERROR, "String could not be evaluated", "Object : $objectid\nProperty : $propertyname\nValue: $var" , basename(__FILE__), __LINE__);
            }
          } catch (Exception $e) {
            SB_Error::error_logger(E_USER_ERROR, "String could not be evaluated", "Error : $e->getMessage()\nObject : $objectid\nProperty : $propertyname\nValue: $var" , basename(__FILE__), __LINE__);
          }
        } else {
          $returnvalue = (!is_null($default)) ? $default : $var;
        }
      } else {
        $returnvalue = $var;
      }

    } else if (is_bool($var)) {
      //BOOL
      $returnvalue  = booltostr($var);

    } else if (is_number($var)) {
      //NUMBER
      $returnvalue  = floatval($var);

    } else if (is_null($var)) {
      //NULL
      $returnvalue  = "";
    }

    return $returnvalue == true;
  }


  function evalbool($var, $default=null, $objectid="", $propertyname="") {
    return boolOrEval($var, $default, $objectid, $propertyname);
  }

  function evalboolstr($var, $default=null) {
    return boolOrEvalString($var, $default);
  }

  function evalstr($str, $emptystr="") {
    if (stripos($str, "evaljs_") !== false) {
      return $str;
    } else {
      if (is_null($str)) {
        return "null";
      } else {
        if (isempty($str)) {
          return "'" . $emptystr . "'";
        } else {
          return "'" . $str . "'";
        }
      }
    }
  }

  function evalphpstr($str) {
   ob_start();
   ob_flush();

   eval('?>' . $str . '<?');

   $s = ob_get_contents();
   ob_end_clean();

   return $s;
  }

  function getEvalJSValue($value) {
    $returnvalue = false;

    if (stripos($value, "evaljs_") !== false) {
      $returnvalue = "eval('$value');";
    } else {
      if (is_string($value)) {
        $returnvalue = "'$value'";
      } elseif (is_null($value)) {
        $returnvalue = "''";
      } else {
        $returnvalue = $value;
      }
    }

    return $returnvalue;
  }

?>