<?php
  //NAMESPACE
  namespace SB\Functions;

  //INCLUDES functions
  include_once __DIR__ . "/array_functions.php";


  //FUNCTIONS
  function replace_variables($string, $replacetags, $variablearray, $emptyvalue="NULL", $timing=false) {
    global $_SESSION;

    //timing
    $timerstart = timer_start();

    //init variables
    if (is_string($replacetags)) {
      $replacetags = array_fill(0, 1, $replacetags);
    }

    $searcharray            = array();
    $replacearray           = array();
    $replacetagsarray       = array();
    $replacetaguniquearray  = array();
    $params                 = array();

    //find replacetags
    if (is_array($variablearray)) {
      foreach ($replacetags as $replacetag) {
        $replacetagarray = array_unique(explode("[".$replacetag.":", $string));

        $count = 0;
        foreach($replacetagarray as $key=>$replacestr) {
          if ($count > 0
          ||  count($replacetagarray) == 1) {
            $requeststring = get_string_restant_rechtehaken($replacestr);

            if (comparetext($replacetag, "encrypt")) {
              $varname = trim(get_string_between($requeststring, ":", "]"));

              $search = "[$replacetag:" .$varname."]";
              $value = cryptConvertCheck($varname);

              $searcharray[$search]  = $search;
              $replacearray[$search] = $value;

            } else {
              $varname = strtolower(trim(leftpart($requeststring, "[")));
              $argname = strtolower(trim(get_string_between($requeststring, "[", "]")));
              $propertyname = trim(rightpart($requeststring, "."));

              if (array_key_exists_case($varname, $variablearray)) {
                //if ( (in_array(strtolower($varname), array_map('strtolower', array_keys($variablearray)))) ) {
                //if (isset($variablearray[$varname])) {
                $variablefound = true;

                //$variable = $variablearray[$varnameoriginal];
                $variable = array_key_get_case($varname, $variablearray);
              } else {
                $variablefound = false;
              }

              if ($variablefound) {
                if (!$argname) {
                  //[session:variabele]
                  $search = "[$replacetag:" .$varname."]";
                  $value = $variable;

                  if (is_null($value)) {
                    $value = $emptyvalue;
                  }

                  //Convert to ISO ?!?! ivm 'categorieÃ«n' in filter sql
                  //$value=convert_to_iso($value);

                  //$string = str_ireplace($search, $value, $string);
                  $searcharray[$search]  = $search;
                  $replacearray[$search] = $value;
                } else {
                  if (!$propertyname) {
                    //[session:variabele[argument]]
                    $search = "[$replacetag:" .$varname . "[" . $argname . "]" . "]";
                    $unserializedvariable = unserialize($variable[$argname]);
                    $value = $variable[$argname];

                    //COnvert to ISO ?!?! ivm 'categorieÃ«n' in filter sql
                    //$value=convert_to_iso($value);

                    //$string = str_ireplace($search, $value, $string);
                    $searcharray[$search]  = $search;
                    $replacearray[$search] = $value;
                  } else {
                    //[session:variabele[argument].property]
                    $search = "[$replacetag:" .$varname . "[" . $argname . "].". $propertyname . "]";
                    $unserializedvariable = unserialize($variable[$argname]);
                    $value = $unserializedvariable->$propertyname;

                    //COnvert to ISO ?!?! ivm 'categorieÃ«n' in filter sql
                    //$value=convert_to_iso($value);

                    //$string = str_ireplace($search, $value, $string);
                    $searcharray[$search]  = $search;
                    $replacearray[$search] = $value;
                  }
                }
              } else {
                $search = "[$replacetag:" .$varname."]";
                $value  = "";

                //$string = str_ireplace($search, "", $string);
                $searcharray[$search]  = $search;
                $replacearray[$search] = $value;
              }
            }
          }
          $count++;
        }
      }
    }

    $searcharray[]  = '"NULL"';
    $replacearray[] = 'NULL';

    //$string = str_ireplace('"NULL"', 'NULL', $string);
    $string = str_ireplace($searcharray, $replacearray, $string);

    //timing
    if ($timing) {
      fb_timer_end($timerstart, 0, "replace_functions.php : replace_variables");
    }

    return $string;
  }


  function replace_variables_result($result, $fields="", $replacetags="", $variablearray=array()) {
    //fields parameter is string or array?
    if (!is_array($fields)) {
      $fieldarray = explode(",", $fields);
    } else {
      $fieldarray  = $fields;
    }

    //replace in result?
    if (is_array($result)) {
      //result aflopen
      foreach($result as $key=>$record) {
        if ($fields == ""
        ||  $fields == "*") {
          //geen fields of * meegegeven: dan ALLE fields gebruiken
          foreach ($record as $fieldkey=>$fieldvalue) {
            $result[$key][$fieldkey] = replace_variables($record[$fieldkey], $replacetags, $variablearray, false);
          }
        } else {
          //wel fields meegegeven: dan alleen die fields gebruiken
          //$result[$key] = array_change_key_case($result[$key], CASE_LOWER);
          foreach ($fieldarray as $fieldkey) {
            $fieldkey = (trim(str_ireplace('"', '', $fieldkey)));

            $text     = replace_variables($result[$key][$fieldkey], $replacetags, $variablearray, false);

            $result[$key][$fieldkey] = $text;
          }
        }
      }
    }

    return $result;
  }


  function replace_variables_record($record, $fields="", $replacetags="", $variablearray=array()) {
    //fields parameter is string or array?
    if (!is_array($fields)) {
      $fieldarray = explode(",", $fields);
    } else {
      $fieldarray  = $fields;
    }

    //replace in result?
    if (is_array($record)) {
      if ($fields == ""
      ||  $fields == "*") {
        //geen fields of * meegegeven: dan ALLE fields gebruiken
        foreach ($record as $fieldkey=>$fieldvalue) {
          $record[$fieldkey] = replace_variables($record[$fieldkey], $replacetags, $variablearray, false);
        }
      } else {
        //wel fields meegegeven: dan alleen die fields gebruiken
        //$result[$key] = array_change_key_case($result[$key], CASE_LOWER);
        foreach ($fieldarray as $fieldkey) {
          $fieldkey = (trim(str_ireplace('"', '', $fieldkey)));
          $record[$fieldkey] = replace_variables($record[$fieldkey], $replacetags, $variablearray, false);
        }
      }
    }

    return $record;
  }


  //functions
  function replace_text($string, $sessionarray, $requestarray, $paramarray, $xmlsubmit, $xmlresult, $xmlsearch, $xmlselection, $xmlcolumns, $timing=false) {
    $timerstart = timer_start();
  }


  function replace_xmlsubmit($string, $xmlsubmit=false, $stripslashes=false, $timing=false) {
    global $_SESSION;

    //timing
    $timerstart = timer_start();

    //xmlsubmit is SimpleXML object?
    if ($xmlsubmit
    &&  is_string($xmlsubmit)) {
      $xmlsubmit  =  new SimpleXMLElement($xmlsubmit);
    }

    //init variables
    if ($stripslashes) {
      $string=stripslashes($string);
    }

    $replacetag           = "xmlsubmit";

    $replacearraystrs1    = explode("[" . $replacetag . ":", $string);
    $replacearray1        = array_splice($replacearraystrs1, 1);
    $replacebrackets1     = count($replacearray1) ? array_fill(0, count($replacearray1), "[]") : [];

    $replacearraystrs2    = explode("{" . $replacetag . ":", $string);
    $replacearray2        = array_splice($replacearraystrs2, 1);
    $replacebrackets2     = count($replacearray2) ? array_fill(0, count($replacearray2), "{}") : [];

    $replacearray         = array_merge($replacearray1, $replacearray2);
    $replacebrackets      = array_merge($replacebrackets1, $replacebrackets2);

    $encryptedfieldnames  = (isset($_SESSION["encryptedfieldnames"])) ? unserialize($_SESSION["encryptedfieldnames"]) : false;

    //vervang alle [xmlsubmit:...] in $string door waardes uit $xmlrequest
    foreach($replacearray as $replacekey=>$replacestring) {
      //if ($count>0) {
        $xpathstring      = get_string_restant_brackets($replacestring);

        if ($replacebrackets[$replacekey] == "[]") {
          $search           = "[" . $replacetag . ":" . $xpathstring . "]";
        } elseif ($replacebrackets[$replacekey] == "{}") {
          $search           = "{" . $replacetag . ":" . $xpathstring . "}";
        }

        if ($xmlsubmit) {
          $result = $xmlsubmit->xpath($xpathstring);

          if (!empty($result)) {
            while(list( , $nodevalue) = each($result)) {
              //decrypt nodevalue?
              if (isset($result[0])
              &&  count($result[0]->attributes()) > 0) {
                foreach($result[0]->attributes() as $attributename=>$attributevalue) {
                  if ($attributename == "fieldname") {
                    if (is_array($encryptedfieldnames)
                    &&  in_array($attributevalue, $encryptedfieldnames)) {
                      $nodevalue = decryptConvert((string)$nodevalue);
                    }
                  }
                }
              }

              if (isnotempty($nodevalue) && $nodevalue != "{null}") {
                $string = str_ireplace($search, $nodevalue, $string);
                $string = str_ireplace("{null}", " NULL ", $string);
              } else {
                $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
                $string = str_ireplace($search, " NULL ", $string);
              }
            }
          } else {
            $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
            $string = str_ireplace($search, " NULL ", $string);
          }
        } else {
            $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
            $string = str_ireplace($search, " NULL ", $string);
        }
      //} //end if
      //$count++;
    } //next replacestring

    if ($timing) {
      fb_timer_end($timerstart, 0, "replace_functions.php : replace_xmlsubmit");
    }

    return $string;
  }


  function replace_xmlrequest($string, $xmlrequest=false, $timing=false) {
    //timing
    $timerstart = timer_start();

    //xmlrequest is SimpleXML object?
    if ($xmlrequest
    &&  is_string($xmlrequest)) {
      $xmlrequest  =  new SimpleXMLElement($xmlrequest);
    }

    //init variables
    $replacetag   = "xmlrequest";
    $replacearray = explode("[" . $replacetag . ":", $string);

    //vervang alle [xmlrequest:...] in $string door waardes uit $xmlrequest
    $count = 0;
    foreach($replacearray as $replacestring) {

      if ($count>0) {
        $xmlrequeststring = get_string_restant_rechtehaken($replacestring);
        $xpathstring      = $xmlrequeststring;
        $search = "[$replacetag:$xpathstring]";

        if ($xmlrequest) {
          $result = $xmlrequest->xpath($xpathstring);

          if (!empty($result)) {
            while(list( , $nodevalue) = each($result)) {
              if (isnotempty($nodevalue) && $nodevalue != "{null}") {
                $string = str_ireplace($search, $nodevalue, $string);
              } else {
                $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
                $string = str_ireplace($search, " NULL ", $string);
              }
            }
          } else {
            $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
            $string = str_ireplace($search, " NULL ", $string);
          }
        } else {
            $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
            $string = str_ireplace($search, " NULL ", $string);
        }
      } //end if
      $count++;
    } //next replacestring

    if ($timing) {
      fb_timer_end($timerstart, 0, "replace_functions.php : replace_xmlrequest");
    }

    return $string;
  }


  function replace_xmlselection($string, $xmlselection=false, $timing=false) {
    //timing
    $timerstart = timer_start();

    //xmlselection is SimpleXML object?
    if ($xmlselection
    &&  is_string($xmlselection)) {
      $xmlselection  =  new SimpleXMLElement($xmlselection);
    }

    //init variables
    $replacetag   = "xmlselection";
    $replacearray = explode("[" . $replacetag . ":", $string);

    //vervang alle [xmlselection:...] in $string door waardes uit $xmlselection
    $count = 0;
    foreach($replacearray as $replacestring) {

      if ($count>0) {
        $xmlselectionstring = get_string_restant_rechtehaken($replacestring);
        $xpathstring        = $xmlselectionstring;
        $search             = "[$replacetag:$xpathstring]";

        if ($xmlselection) {
          $result = $xmlselection->xpath($xpathstring);

          if (!empty($result)) {
            while(list( , $nodevalue) = each($result)) {
              //decrypt nodevalue?
              //TODO: Uiteindelijk voor ALLE ID's dus dan kan het cryptConvert() worden ipv cryptConvertCheck()
              //if (!is_empty((string)$nodevalue)
              //&&  !comparetext((string)$nodevalue, "-1")
              //&&  !ctype_alnum(str_replace("_", "", $nodevalue))) {
                foreach($result[0]->attributes() as $attributename=>$attributevalue) {
                  if ($attributename == "fieldname") {
                    if (comparetext(right($attributevalue,2), "ID") /*&& is_numeric((string)$nodevalue)*/ ) {
                      //if (comparetext(right($attributevalue,2), "ID") && is_numeric((string)$nodevalue)) {
                      //||  comparetext(right($attributevalue,3), "IDs")) {
                      $nodevalue = cryptConvertCheck((string)$nodevalue);
                    }
                  }
                }
              //}

              if (isnotempty($nodevalue) && $nodevalue != "{null}") {
                $string = str_ireplace($search, $nodevalue, $string);
              } else {
                $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
                $string = str_ireplace($search, " NULL ", $string);
              }
            }
          } else {
            $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
            $string = str_ireplace($search, " NULL ", $string);
          }
        } else {
            $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
            $string = str_ireplace($search, " NULL ", $string);
        }
      } //end if
      $count++;
    } //next replacestring

    if ($timing) {
      fb_timer_end($timerstart, 0, "replace_functions.php : replace_xmlselection");
    }

    return $string;
  }


  function replace_xmlresult($string, $xmlresult=false, $timing=false) {
    $timerstart = timer_start();

    //xmlresult is SimpleXML object?
    if ($xmlresult
    &&  is_string($xmlresult)) {
      $xmlresult  =  new SimpleXMLElement($xmlresult);
    }

    //init variables
    $replacetag = "xmlresult";
    $replacearray = explode("[" . $replacetag . ":", $string);

    //vervang alle [xmlresult:...] in $string door waardes uit $xmlrequest
    $count = 0;
    foreach($replacearray as $replacestring) {

      if ($count>0) {
        $xmlrequeststring = get_string_restant_rechtehaken($replacestring);
        $xpathstring      = $xmlrequeststring;
        $search = "[$replacetag:$xpathstring]";

        if ($xmlresult) {
          $result = $xmlresult->xpath($xpathstring);

         if (!empty($result)) {
            while(list( , $nodevalue) = each($result)) {
              if (isnotempty($nodevalue) && $nodevalue != "{null}") {
                $string = str_ireplace($search, $nodevalue, $string);
             } else {
                $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
              $string = str_ireplace($search, " NULL ", $string);
             }
            }
          } else {
            $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
           $string = str_ireplace($search, " NULL ", $string);
          }
        } else {
            $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
           $string = str_ireplace($search, " NULL ", $string);
        }
      } //end if
      $count++;
    } //next replacestring

    if ($timing) {
      fb_timer_end($timerstart, 0, "replace_functions.php : replace_xmlresult");
    }

    return $string;
  }


  function replace_xmlcolumns($string, $xmlcolumns=false, $timing=false) {
    $timerstart = timer_start();

    //xmlcolumns is SimpleXML object?
    if ($xmlcolumns
    &&  is_string($xmlcolumns)) {
      $xmlcolumns  =  new SimpleXMLElement($xmlcolumns);
    }

    //init variables
    $replacetag = "xmlcolumns";
    $replacearray = explode("[" . $replacetag . ":", $string);

    //vervang alle [xmlcolumns:...] in $string door waardes uit $xmlrequest
    $count = 0;
    foreach($replacearray as $replacestring) {

      if ($count>0) {
        $xmlrequeststring = get_string_restant_rechtehaken($replacestring);
        $xpathstring      = $xmlrequeststring;
        $search = "[$replacetag:$xpathstring]";

        if ($xmlcolumns) {
          $result = $xmlcolumns->xpath($xpathstring);

         if (!empty($result)) {
            while(list( , $nodevalue) = each($result)) {
              if (isnotempty($nodevalue) && $nodevalue != "{null}") {
                $string = str_ireplace($search, $nodevalue, $string);
             } else {
                $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
              $string = str_ireplace($search, " NULL ", $string);
             }
            }
          } else {
            $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
           $string = str_ireplace($search, " NULL ", $string);
          }
        } else {
            $string = str_ireplace("\"" . $search . "\"", " NULL ", $string);
           $string = str_ireplace($search, " NULL ", $string);
        }
      } //end if
      $count++;
    } //next replacestring

    if ($timing) {
      fb_timer_end($timerstart, 0, "replace_functions.php : replace_xmlcolumns");
    }

    return $string;
  }


  function replace_hookpair($string, $variable, $replacetag, $timing=false) {
    $value = "";

    $string=stripslashes($string);

    if (contains($string, "[" . $replacetag . ":", false)) {
      $string =trimstringleft($string, "[".$replacetag.":");
      $string =trimstringright($string, "]");

      $varname        = trim(leftpart($string, "["));
      $argname        = strtolower(trim(get_string_between($string, "[", "]")));
      $propertyname   = trim(rightpart($string, "]."));

      if ($varname
      &&  $argname
      &&  $propertyname) {

        //replace object    (session:opties[LABEL_SM_MELDER.Waarde])
        $unserializedobject= (array_key_exists($varname, $variable) && array_key_exists($argname, $variable[$varname])) ? is_serialized($variable[$varname][$argname]) ? unserialize($variable[$varname][$argname]) : $variable[$varname][$argname] : FALSE;

        if ($unserializedobject
        &&  property_exists($unserializedobject, $propertyname)) {
          $value              = $unserializedobject->$propertyname;
        } else {
          $value = "[conditional:LEFTHOOK]" . $replacetag . ":" . $string . "[conditional:RIGHTHOOK]";
        }
      } else if ($varname
             &&  $argname) {
        //replace array     (session:account[gebruikerid])
        if (isset($variable[$varname])
        &&  is_string($variable[$varname])) {
          $variable[$varname] = trim_explode(",", $variable[$varname]);

          //TODO: array moet nu base 1 zijn ivm repeateroffset, moet mooier!
          array_unshift($variable[$varname], "");
          unset($variable[$varname][0]);
        }

        if (isset($variable[$varname])
        &&  isset($variable[$varname][$argname])) {
          $value                = (is_bool($variable[$varname][$argname])) ? booltostr2($variable[$varname][$argname]) : $variable[$varname][$argname];
        }
      } else if ($varname) {
        //replace value     (session:test)
        $value                = (is_bool($variable[$varname])) ? booltostr2($variable[$varname]) : $variable[$varname];
      //} else if ($argname) {
      //  $value                = $variable[$argname];
      }
    }

    return $value;
  }


  function remove_bracepair($string, $variable, $timing=false) {
    return replace_bracepair($string, $variable, "", $timing);
  }


  function replace_bracepair($string, $variablearray, $replacetag, $timing=false) {
    //init variables
    $braceopen    = "[";
    $braceclose   = "]";
    $propertyname = "";
    $value        = "";
    $varisobject  = false;
    $varisarray   = false;

    //prepare string
    $string=stripslashes($string);

    //prepare braces
    if (contains($string, "{" . $replacetag . ":", false)) {
      $braceopen  = "{";
      $braceclose = "}";
    } elseif (contains($string, "[" . $replacetag . ":", false)) {
      $braceopen  = "[";
      $braceclose = "]";
    }

    if (contains($string, $braceopen . $replacetag . ":", false)) {
      $string =trimstringleft($string, $braceopen . $replacetag.":");
      $string =trimstringright($string, $braceclose);

      $varname        = trim(leftpart($string, "["));
      $argname        = strtolower(trim(get_string_between($string, "[", "]")));

      if (contains($string, ".")) {
        $varisobject    = true;
        $propertyname   = removebrackets(trim(rightpart($string, "].")));
      } else if (contains($string, "][")) {
        $varisarray     = true;
        $propertyname   = removebrackets(trim(rightpart($string, "][")));
      }

      if ($varname
      &&  $argname !== ""
      &&  $argname !== false
      &&  $propertyname) {
        //replace object/array    (bijv: session:opties[LABEL_SM_MELDER.Waarde])
        $unserializedobject= array_key_exists($varname, $variablearray) ? is_serialized($variablearray[$varname][$argname]) ? unserialize($variablearray[$varname][$argname]) : $variablearray[$varname][$argname] : FALSE;

        if ($varisobject) {
          if ($unserializedobject
          &&  property_exists($unserializedobject, $propertyname)) {
            $value              = (is_bool($unserializedobject->$propertyname)) ? booltostr2($unserializedobject->$propertyname) : $unserializedobject->$propertyname;
          } else {
            $value              = "[conditional:LEFTBRACE]" . $replacetag . ":" . $string . "[conditional:RIGHTBRACE]";
          }
        } else if ($varisarray) {
          if ($unserializedobject
          &&  array_key_exists($propertyname, $unserializedobject)) {
            $value              = (is_bool($unserializedobject[$propertyname])) ? booltostr2($unserializedobject[$propertyname]) : $unserializedobject[$propertyname];
          } else {
            $value              = "[conditional:LEFTBRACE]" . $replacetag . ":" . $string . "[conditional:RIGHTBRACE]";
          }
        }

      } else if ($varname
             &&  $argname !== ""
             &&  $argname !== false) {
        //replace array     (bijv: session:account[gebruikerid])
        if (isset($variablearray[$varname])
        &&  is_string($variablearray[$varname])) {
          $variablearray[$varname] = trim_explode(",", $variablearray[$varname]);

          //TODO: array moet nu base 1 zijn ivm repeateroffset, moet mooier!
          array_unshift($variablearray[$varname], "");
          unset($variablearray[$varname][0]);
        }

        if (isset($variablearray[$varname])
        &&  isset($variablearray[$varname][$argname])) {
          $value                = (is_bool($variablearray[$varname][$argname])) ? booltostr2($variablearray[$varname][$argname]) : $variablearray[$varname][$argname];
        }

      } else if ($varname) {
        //replace value     (bijv: session:test)
        if (isset($variablearray[$varname])) {
          $value                = (is_bool($variablearray[$varname])) ? booltostr2($variablearray[$varname]) : $variablearray[$varname];
        } else {
          $value                = "FALSE";
        }
      }
    }

    return $value;
  }


  function replace_conditionals($string="", $replacetag="conditional", $timing=false) {
    //timing
    $timerstart=timer_start();

    if (!is_empty($string)) {
      $string = str_ireplace("[". $replacetag . ":GREATERTHAN]",  ">",  $string);
      $string = str_ireplace("[". $replacetag . ":LESSTHAN]"   ,  "<",  $string);
      $string = str_ireplace("[". $replacetag . ":SMALLERTHAN]"   ,  "<",  $string);
      $string = str_ireplace("[". $replacetag . ":GREATERTHANOREQUALTO]",  ">=",  $string);
      $string = str_ireplace("[". $replacetag . ":LESSTHANOREQUALTO]",  "<=",  $string);
      $string = str_ireplace("[". $replacetag . ":SMALLERTHANOREQUALTO]",  "<=",  $string);
      $string = str_ireplace("[". $replacetag . ":EQUALS]",  "=",  $string);
      $string = str_ireplace("[". $replacetag . ":NOTEQUALTO]",  "!=",  $string);
      $string = str_ireplace("[". $replacetag . ":SHIFTLEFT]",  "<<",  $string);
      $string = str_ireplace("[". $replacetag . ":SHIFTRIGHT]",  ">>",  $string);

      $string = str_ireplace("[". $replacetag . ":LEFTHOOK]",  "[",  $string);
      $string = str_ireplace("[". $replacetag . ":RIGHTHOOK]",  "]",  $string);

      $string = str_ireplace("[". $replacetag . ":LEFTBRACE]",  "{",  $string);
      $string = str_ireplace("[". $replacetag . ":RIGHTBRACE]",  "}",  $string);

      $string = str_ireplace("{". $replacetag . ":GREATERTHAN}",  ">",  $string);
      $string = str_ireplace("{". $replacetag . ":LESSTHAN}"   ,  "<",  $string);
      $string = str_ireplace("{". $replacetag . ":SMALLERTHAN}"   ,  "<",  $string);
      $string = str_ireplace("{". $replacetag . ":GREATERTHANOREQUALTO}",  ">=",  $string);
      $string = str_ireplace("{". $replacetag . ":LESSTHANOREQUALTO}",  "<=",  $string);
      $string = str_ireplace("{". $replacetag . ":SMALLERTHANOREQUALTO}",  "<=",  $string);
      $string = str_ireplace("{". $replacetag . ":EQUALS}",  "=",  $string);
      $string = str_ireplace("{". $replacetag . ":NOTEQUALTO}",  "!=",  $string);
      $string = str_ireplace("{". $replacetag . ":SHIFTLEFT}",  "<<",  $string);
      $string = str_ireplace("{". $replacetag . ":SHIFTRIGHT}",  ">>",  $string);

      $string = str_ireplace("{". $replacetag . ":LEFTHOOK}",  "{",  $string);
      $string = str_ireplace("{". $replacetag . ":RIGHTHOOK}",  "}",  $string);

      $string = str_ireplace("{". $replacetag . ":LEFTBRACE}",  "{",  $string);
      $string = str_ireplace("{". $replacetag . ":RIGHTBRACE}",  "}",  $string);
    }

    //timing
    if ($timing) {
      fb_timer_end($timerstart, 0, "__sb_xmltemplatereader.php : replace conditionals");
    }

    return $string;
  }


  function replace_conditionals_preg($string="", $replacetag="conditional", $timing=false) {
    //timing
    $timerstart=timer_start();

    if (!is_empty($string)) {
      $searcharray = array("GREATERTHAN", "LESSTHAN", "SMALLERTHAN", "GREATERTHANOREQUALTO", "LESSTHANOREQUALTO", "SMALLERTHANOREQUEALTO", "EQUALS", "NOTEQUALTO", "SHIFTLEFT", "SHIFTRIGHT", "LEFTHOOK", "RIGHTHOOK", "LEFTBRACE", "RIGHTBRACE");
      $replacearray= array(">","<","<",">=","<=","<=","=","!=","<<",">>","[","]","{","}");

      $string = replace_between_brackets($string, $searcharray, $replacearray, $replacetag.":");

      /*
      $string = preg_replace("/[\[|\{]" .$replacetag . ":GREATERTHAN" . "[\]|\}]/i",  ">",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":LESSTHAN" . "[\]|\}]/i"   ,  "<",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":SMALLERTHAN" . "[\]|\}]/i"   ,  "<",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":GREATERTHANOREQUALTO" . "[\]|\}]/i",  ">=",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":LESSTHANOREQUALTO" . "[\]|\}]/i",  "<=",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":SMALLERTHANOREQUALTO" . "[\]|\}]/i",  "<=",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":EQUALS" . "[\]|\}]/i",  "=",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":NOTEQUALTO" . "[\]|\}]/i",  "!=",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":SHIFTLEFT" . "[\]|\}]/i",  "<<",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":SHIFTRIGHT" . "[\]|\}]/i",  ">>",  $string);

      $string = preg_replace("/[\[|\{]" .$replacetag . ":LEFTHOOK" . "[\]|\}]/i",  "[",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":RIGHTHOOK" . "[\]|\}]/i",  "]",  $string);

      $string = preg_replace("/[\[|\{]" .$replacetag . ":LEFTBRACE" . "[\]|\}]/i",  "{",  $string);
      $string = preg_replace("/[\[|\{]" .$replacetag . ":RIGHTBRACE" . "[\]|\}]/i",  "}",  $string);
      */
    }

    //timing
    if ($timing) {
      fb_timer_end($timerstart, 0, "__sb_xmltemplatereader.php : replace conditionals preg");
    }

    return $string;
  }


  function replace_datetimes($string, $replacetag="datetime", $timing=false) {
    //timing
    $timerstart = timer_start();

    //replace [datetime:....]
    $string = str_ireplace ("[". $replacetag . ":Date]",  date( "Y/m/d", time()), $string);
    $string = str_ireplace ("[". $replacetag . ":Time]", date("G:i"), $string);
    $string = str_ireplace ("[". $replacetag . ":DateTime]", date("Y/m/d G:i:s", time() ), $string);
    $string = str_ireplace ("[". $replacetag . ":DateString]",  date("Ymd", time()), $string);
    $string = str_ireplace ("[". $replacetag . ":DateTimeString]",  date("YmdGis", time()), $string);

    $string = str_ireplace ("[". $replacetag . ":FormDate]",  date( "d-m-Y", time()), $string);
    $string = str_ireplace ("[". $replacetag . ":FormDateTime]", date( "d-m-Y G:i:s", time() ), $string);
    $string = str_ireplace ("[". $replacetag . ":FormTime]",  date( "G:i", time()), $string);

    $string = str_ireplace ("[". $replacetag . ":Year]", date("Y", time()), $string);
    $string = str_ireplace ("[". $replacetag . ":Month]", maandnaam(date("m", time())), $string);
    $string = str_ireplace ("[". $replacetag . ":Day]", date("d", time()), $string);

    $string = str_ireplace ("[". $replacetag . ":MonthName]", maandnaam(date("m", time())), $string);
    $string = str_ireplace ("[". $replacetag . ":DayName]", dagnaam(date("d", time())), $string);

    $string = str_ireplace ("[". $replacetag . ":DateLong]",  date("j", time()) . " " . maandnaam(date("m", time())) . " " . date("Y", time()) , $string);
    $string = str_ireplace ("[". $replacetag . ":DateTimeLong]",  date("j", time()) . " " . maandnaam(date("m", time())) . " " . date("Y", time()) . "  " . date("G:i", time()), $string);


    $string = str_ireplace ("{". $replacetag . ":Date}",  date( "Y/m/d", time()), $string);
    $string = str_ireplace ("{". $replacetag . ":Time}", date("G:i"), $string);
    $string = str_ireplace ("{". $replacetag . ":DateTime}", date("Y/m/d G:i:s", time() ), $string);
    $string = str_ireplace ("{". $replacetag . ":DateString}",  date("Ymd", time()), $string);
    $string = str_ireplace ("{". $replacetag . ":DateTimeString}",  date("YmdGis", time()), $string);

    $string = str_ireplace ("{". $replacetag . ":FormDate}",  date( "d-m-Y", time()), $string);
    $string = str_ireplace ("{". $replacetag . ":FormDateTime}", date( "d-m-Y G:i:s", time() ), $string);
    $string = str_ireplace ("{". $replacetag . ":FormTime}",  date( "G:i", time()), $string);

    $string = str_ireplace ("{". $replacetag . ":Year}", date("Y", time()), $string);
    $string = str_ireplace ("{". $replacetag . ":Month}", maandnaam(date("m", time())), $string);
    $string = str_ireplace ("{". $replacetag . ":Day}", date("d", time()), $string);

    $string = str_ireplace ("{". $replacetag . ":MonthName}", maandnaam(date("m", time())), $string);
    $string = str_ireplace ("{". $replacetag . ":DayName}", dagnaam(date("d", time())), $string);

    $string = str_ireplace ("{". $replacetag . ":DateLong}",  date("j", time()) . " " . maandnaam(date("m", time())) . " " . date("Y", time()) , $string);
    $string = str_ireplace ("{". $replacetag . ":DateTimeLong}",  date("j", time()) . " " . maandnaam(date("m", time())) . " " . date("Y", time()) . "  " . date("G:i", time()), $string);

    //timing
    if ($timing) {
      fb_timer_end($timerstart, 0, "__sb_xmltemplatereader.php : replace datetimes");
    }

    return $string;
  }


  function replace_datetimes_preg($string, $replacetag="datetime", $timing=false) {
    //timing
    $timerstart = timer_start();

    //replace [datetime:....]

    $searcharray  = array(
        "DATE"
      , "TIME"
      , "DATESTRING"
      , "DATETIMESTRING"
      , "DATETIME"
      , "TIMESTRING"
      , "FORMDATETIME"
      , "FORMDATE"
      , "FORMTIME"
      , "YEAR"
      , "MONTH"
      , "DAY"
      , "MONTHNAME"
      , "DAYNAME"
      , "DATELONG"
      , "DATETIMELONG"
    );
    $replacearray = array(
        date("Y/m/d", time())
      , date("G:i")
      , date("Ymd", time())
      , date("YmdHis", time())
      , date("Y/m/d G:i:s", time() )
      , date("His", time())
      , date("d-m-Y G:i:s", time() )
      , date("d-m-Y", time())
      , date("G:i", time())
      , date("Y", time())
      , maandnaam(date("m", time()))
      , date("d", time())
      , maandnaam(date("m", time()))
      , dagnaam(date("d", time()))
      , date("j", time()) . " " . maandnaam(date("m", time())) . " " . date("Y", time())
      , date("j", time()) . " " . maandnaam(date("m", time())) . " " . date("Y", time()) . "  " . date("G:i", time())
    );

    $string = replace_between_brackets($string, $searcharray, $replacearray, $replacetag.":");

    /*
    $string = preg_replace("/[\[|\{]" .$replacetag . ":DATE" . "[\]|\}]/i", date( "Y/m/d", time()), $string);
    $string = preg_replace("/[\[|\{]" .$replacetag . ":Time" . "[\]|\}]/i", date("G:i"), $string);
    $string = preg_replace("/[\[|\{]" .$replacetag . ":DateTime" . "[\]|\}]/i", date("Y/m/d G:i:s", time() ), $string);
    $string = preg_replace("/[\[|\{]" .$replacetag . ":DateString" . "[\]|\}]/i",  date("Ymd", time()), $string);
    $string = preg_replace("/[\[|\{]" .$replacetag . ":DateTimeString" . "[\]|\}]/i",  date("YmdGis", time()), $string);

    $string = preg_replace("/[\[|\{]" .$replacetag . ":FormDate" . "[\]|\}]/i",  date( "d-m-Y", time()), $string);
    $string = preg_replace("/[\[|\{]" .$replacetag . ":FormDateTime" . "[\]|\}]/i", date( "d-m-Y G:i:s", time() ), $string);
    $string = preg_replace("/[\[|\{]" .$replacetag . ":FormTime" . "[\]|\}]/i",  date( "G:i", time()), $string);

    $string = preg_replace("/[\[|\{]" .$replacetag . ":Year" . "[\]|\}]/i", date("Y", time()), $string);
    $string = preg_replace("/[\[|\{]" .$replacetag . ":Month" . "[\]|\}]/i", maandnaam(date("m", time())), $string);
    $string = preg_replace("/[\[|\{]" .$replacetag . ":Day" . "[\]|\}]/i", date("d", time()), $string);

    $string = preg_replace("/[\[|\{]" .$replacetag . ":MonthName" . "[\]|\}]/i", maandnaam(date("m", time())), $string);
    $string = preg_replace("/[\[|\{]" .$replacetag . ":DayName" . "[\]|\}]/i", dagnaam(date("d", time())), $string);

    $string = preg_replace("/[\[|\{]" .$replacetag . ":DateLong" . "[\]|\}]/i",  date("j", time()) . " " . maandnaam(date("m", time())) . " " . date("Y", time()) , $string);
    $string = preg_replace("/[\[|\{]" .$replacetag . ":DateTimeLong" . "[\]|\}]/i",  date("j", time()) . " " . maandnaam(date("m", time())) . " " . date("Y", time()) . "  " . date("G:i", time()), $string);
    */

    //timing
    if ($timing) {
      fb_timer_end($timerstart, 0, "__sb_xmltemplatereader.php : replace datetimes preg");
    }

    return $string;
  }



  function replace_value($string, $valuearray, $replacetag="value", $replacehooks=true, $timing=false) {
    if (contains($string, "[" . $replacetag . ":", false)) {
      $varname1 =trimstringleft(addslashes($string), "[".$replacetag.":");
      $varname2 =trimstringright($string, "]");

      if (is_array($valuearray)
      &&  isset($valuearray[$varname2])) {
        $string = str_replace("[" . $replacetag . ":" . $varname2 ."]", $valuearray[$varname2], $string);
      }
    }

    if (contains($string, "{" . $replacetag . ":", false)) {
      $varname3 =trimstringleft($string, "{".$replacetag.":");
      $varname4 =trimstringright($string, "}");

      if (is_array($valuearray)
      &&  isset($valuearray[$varname4])) {
        $string = str_replace("{" . $replacetag . ":" . $varname4 ."}", $valuearray[$varname4], $string);
      }
    }

    return $string;
  }




  function replace_queryrecord($string, $variablearray, $variablequeryname, $replacetag="jitqueryrecord", $replacehooks=true, $timing=false) {
    //init variables
    $queryname     = "";
    $fieldname     = "";
    $value         = "";

    $string=stripslashes($string);

    if (contains($string, "[" . $replacetag . ":", false)
    ||  contains($string, "{" . $replacetag . ":", false)) {
      if (contains($string, "[" . $replacetag . ":", false)) {
        $string = trimstringleft($string, "[".$replacetag.":");
        $string = trimstringright($string, "]");
      } else if (contains($string, "{" . $replacetag . ":", false)) {
        $string = trimstringleft($string, "{".$replacetag.":");
        $string = trimstringright($string, "}");
      }

      $queryname      = trim(leftpart($string, "."));
      $fieldname      = trim(rightpart($string, "."));

      //replace
      if ($queryname
      &&  $fieldname) {
        //replace object    [label:LABEL_TITEL.Waarde]
        if ($queryname == $variablequeryname) {
          if (array_key_exists($fieldname, $variablearray)) {
            $value = $variablearray[$fieldname];
          } else {
            //if ($replacehooks) {
            //  $value = "[conditional:LEFTHOOK]" . $replacetag . ":" . $string . "[conditional:RIGHTHOOK]";
            //} else {
            //  fbb("QueryRecord not found:" . $replacetag . ":" . $string);
            //$value = "FALSE";
            //}
          }
        }
      }

      if (contains($value, ".")) {
        if (rightpart($value, ".") == "0"
        ||  rightpart($value, ".") == "00") {
          $value = leftpart($value, ".");
        }
      }
    }

    return $value;
  }



  function replace_queryjson($string, $variablearray, $variablequeryname, $replacetag="jitqueryjson", $replacehooks=true, $timing=false) {
    //init variables
    $queryname     = "";
    $value         = "";

    $string=stripslashes($string);

    if (contains($string, "[" . $replacetag . ":", false)) {
      $string = trimstringleft($string, "[".$replacetag.":");
      $string = trimstringright($string, "]");

      $queryname      = trim($string);

      //replace
      if ($queryname) {
        //replace object    [label:LABEL_TITEL.Waarde]

        if ($queryname == $variablequeryname) {
          $value = json_encode(json_rows_array($variablearray));
        }
      }
    }

    return $value;
  }


  function replace_specialchars($string, $htmlspecialchars=true, $linebreaks=true) {
    if ($htmlspecialchars) {
      $string =  htmlspecialchars($string);
    }

    if ($linebreaks) {
      $string = str_replace("\r\n", "<br />", $string);
      $string = str_replace("\n\r", "<br />", $string);
      $string = str_replace("\n", "<br />", $string);
      $string = str_replace("\r", "<br />", $string);
    }

    return $string;
  }


  function remove_htmlcomments($string, $timing=false, $timinglimit=0.1) {
    //timing
    $timerstart = timer_start();

    //strpos of the opening tag
    $start_position = 0;

    //number of tags "deep" we are
    $current_count = 0;
    //go through the string and remove the tags:
    for ( $p = 0; $p < strlen($string); $p++ ) {
        //find opening tags
        if ( $string[$p] == "<" && $string[$p+1] == "!" && $string[$p+2] == "-" && $string[$p+3] == "-" ) {
            $current_count++;
            if ( $current_count == 1 ) {
                $start_position = $p;
            }
        }
        //find closing tags if applicable.
        if ( $current_count > 0 && $string[$p] == "-" && $string[$p+1] == "-" && $string[$p+2] == ">") {
            $current_count--;
            if ( $current_count == 0 ) {
                $p = $p + 3;
                $string = substr($string, 0, $start_position) . substr($string, $p);
                $start_position = 0;
                $p = 0;
            }
        }
    }

    //timing
    if ($timing) {
      fb_timer_end($timerstart, 0, "_replace_functions.php : remove html comments");
    }

    //handle any uneven tags at the end.
    return substr($string, 0, (strpos($string, "<!--") == 0 ? strlen($string) : strpos($string, "<!--"))) . "\n";

    //timing
    if ($timing) {
      fb_timer_end($timerstart, 0, "_replace_functions.php : remove html comments");
    }

    return $string;
  }


  function remove_javascriptcomments($string, $timing=false, $timinglimit=0.1) {
    //timing
    $timerstart = timer_start();

    //strpos of the opening tag
    $start_position = 0;

    //number of tags "deep" we are
    $current_count = 0;
    //go through the string and remove the tags:
    for ( $p = 0; $p < strlen($string); $p++ ) {
        //find opening tags
        if ( ($string[$p] == "/" && isset($string[$p+1]) && $string[$p+1] == "*")
                                 && isset($string[$p+2]) && $string[$p+2] != "[" ) {
            $current_count++;
            if ( $current_count == 1 ) {
                $start_position = $p;
            }
        }

        //find closing tags if applicable.
        if ( $current_count > 0 && $string[$p] == "*" && $string[$p+1] == "/") {
            $current_count--;
            if ( $current_count == 0 ) {
                $p = $p + 2;
                $string = substr($string, 0, $start_position) . substr($string, $p);
                $start_position = 0;
                $p = 0;
            }
        }
    }

    //timing
    if ($timing) {
      fb_timer_end($timerstart, $timinglimit, "_replace_functions.php : remove javascript comments");
    }

    //handle any uneven tags at the end.
    return substr($string, 0, (strpos($string, "/*") == 0 ? strlen($string) : strpos($string, "/*"))) . "\n";
  }


  function replace_test($string, $variable, $replacetag="optie", $timing=false) {
    return "TEST";
  }


  function replace_fontawesome($string) {
    $icon   = leftpart($matches[1], "(");
    $style  = leftpart(rightpart($matches[1], "("), ")");

    return ("<i class=\"fa fa-" . $icon . "\" style=\"". $style . "\" />");
  }
?>