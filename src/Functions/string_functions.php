<?
  //NAMESPACE
  namespace SB\Functions;

  //INCLUDES functions
  include_once __DIR__ . "/debug_functions.php";
  //include_once __DIR__ . "/_error_functions.php";

  //FUNCTIONS
  function echostr($str='') {
  	if (is_array($str)) {
  		foreach($str as $string) {
    		echostr($string);
  		}
  	} else {
  		echo "'".$str."'";
  	}
  }


  function sqlstring($sql='') {
    return nl2br("<br>$sql");
  }


  function printsql($sql='') {
  	if (is_array($sql)) {
  		foreach($sql as $sqlstring) {
    		printsql($sqlstring);
  		}
  	} else {
  		echo sqlstring($sql);
  	}
  }


  function printhtml($html='') {
  	if (is_array($html)) {
  		foreach($html as $htmlstring) {
    		printhtml($htmlstring);
  		}
  	} else {
	    echo str_replace("&lt;br /&gt;","<br/>",htmlentities(nl2br($html)));
  	}
  }


  function printhtmlbreak($html='') {
  	if (is_array($html)) {
  		foreach($html as $htmlstring) {
    		printhtmlbreak($htmlstring);
  		}
  	} else {
	  	printhtml($html);
	    printbreak();
  	}
  }


  function printjson($str='', $pre=true, $echo=true) {
  	if (is_array($str)) {
      $json = $str;
  	} else {
  		$json = json_decode($str);
  	}
    $jsonencode = json_encode($json, JSON_PRETTY_PRINT);

    if ($echo) {
      printpre($jsonencode, $pre);
    }

    return $jsonencode;
  }


  function printpre($str='', $pre=true) {
  	if (is_array($str)) {
  		foreach($str as $string) {
    		printpre($string, $pre);
  		}
  	} else {
	  	if ($pre) {echo "<pre>\n";}
	    echo $str;
	    if ($pre) {echo "\n</pre>\n";}
  	}
  }


  function printline($str='') {
  	if (is_array($str)) {
  		foreach($str as $string) {
    		printline($string);
  		}
  	} else {
  		echo $str."\n";
  	}
  }


  function pb(...$vars) {
    if ($vars) {
      foreach ($vars as $var) {
        printbreak($var);
      }
    } else {
      //als geen parameter is meegegeven, dan alleen een linebreak printen
      printbreak();
    }
  }


  function pb_flush($str='') {
    printbreak($str);

    ob_flush();
    flush();
  }


  function get_var_name($var) {
    foreach($GLOBALS as $var_name => $value) {
      if ($value === $var) {
        return $var_name;
      }
    }

    return false;
  }


  function pv($str='') {
    printvar($str);
  }


  function printvar($str='', $varname="") {
    if ($varname) {
      echo "$".$varname." = ";
    }
    if (is_array($str)||is_object($str)) {
    		//foreach($str as $key=>$value) {
      //echo "<br/>".str_repeat("-",$level)."$key:";
      //	printbreak($value, $level);
    		//}
    		printjson(json_encode($str))."<br/>" . PHP_EOL;
    } else {
      echo $str."<br/>" . PHP_EOL;
    }
  }


  function printeol($str='') {
  	if (is_array($str)||is_object($str)) {
  		foreach($str as $key=>$value) {
    		printeol($value);
  		}
  	} else {
    	echo $str. PHP_EOL;
  	}
  }


  function printbreak($var="", $key=false, $linebreak="<br />", $space="&nbsp;", $counter=0) {
    if ($key !== false) {
      echo str_repeat($space, $counter * 2) . $key . " = ";
    }

    if (is_array($var)
    ||  is_object($var)) {
      $counter++;

      echo "[".$linebreak;
      foreach($var as $arraykey=>$value) {
        printbreak($value, $arraykey, $linebreak, $space, $counter);
      }
      if ($key !== false) {
        echo str_repeat($space, $counter * 2);
      }

      echo "]" . $linebreak;

    } else {
      $counter--;

      echo $var;
      echo $linebreak . PHP_EOL;
    }

  }


  function echobr($str='') {
  	if (is_array($str)) {
  		foreach($str as $string) {
    		echobr($string);
  		}
  	} else {
  		echo $str."<br>" . PHP_EOL;
  	}
  }


  function breakprint($str='') {
  	if (is_array($str)) {
  		foreach($str as $string) {
    		breakprint($string);
  		}
  	} else {
  		echo "<br>".$str;
  	}
  }

  function breakprintbreak($str='') {
  	if (is_array($str)) {
  		foreach($str as $string) {
    		breakprintbreak($string);
  		}
  	} else {
  		echo "<br>".$str."<br>";
  	}
  }

  function printlinejs($str='') {
  	if (is_array($str)) {
  		foreach($str as $string) {
    		printlinejs($string);
  		}
  	} else {
  		echo $str."\n";
  	}
  }

  function displayhtml($text='') {
    $text = str_ireplace("  ","&nbsp;&nbsp;",$text);
    $text = str_ireplace(chr(13),"<BR>",$text);
//    $text = hyperlink($text);
    if (comparetext($text,'')) {
      $text = '&nbsp;';
    }
    return trim($text);
  }

/*  function displayhyperlink($text) {
    $text = str_ireplace("  ","&nbsp;&nbsp;",$text);
    $text = str_ireplace(chr(13),"<BR>",$text);
    $text = hyperlink($text);
    if (comparetext($text,'')) {
      $text = '&nbsp;';
    }
    return trim($text);
  }
*/
  function longeststring() {
    $returnvalue = 0;
    $args = func_get_args();
    foreach($args as $arg) {
    	if (strlen($arg)>$returnvalue) {
    		$returnvalue = $arg;
    	}
    }
    return $returnvalue;
	}

  function smalleststring() {
    $returnvalue = 99999;
    $args = func_get_args();
    foreach($args as $arg) {
    	if (strlen($arg)<$returnvalue) {
    		$returnvalue = $arg;
    	}
    }
    return $returnvalue;
	}

	function displaytext($text='') {
    $text = str_ireplace("&nbsp;", " ", $text);
    $text = str_ireplace("<BR>", chr(13) ,$text);
    if (comparetext($text,'&nbsp;')) {
      $text = ' ';
    }
    return trim($text);
  }

  /* Uitgeschakeld vanwege UTF-8 probleem
  function replacenonwestern($str) {
    //single character:
    $str = strtr($str,
      "Ãƒâ‚¬Ãƒï¿½Ãƒâ€šÃƒÆ’Ãƒâ€žÃƒâ€¦Ãƒâ€¡ÃƒË†Ãƒâ€°ÃƒÅ Ãƒâ€¹ÃƒÅ’Ãƒï¿½ÃƒÅ½Ãƒï¿½Ãƒâ€˜Ãƒâ€™Ãƒâ€œÃƒâ€�Ãƒâ€¢Ãƒâ€“ÃƒËœÃƒï¿½ÃƒÅ¸ÃƒÂ ÃƒÂ¡ÃƒÂ¢ÃƒÂ£ÃƒÂ¤ÃƒÂ¥ÃƒÂ§ÃƒÂ¨ÃƒÂ©ÃƒÂªÃƒÂ«ÃƒÂ¬ÃƒÂ­ÃƒÂ®ÃƒÂ¯ÃƒÂ±ÃƒÂ²ÃƒÂ³ÃƒÂ´ÃƒÂµÃƒÂ¶ÃƒÂ¸ÃƒÂ¹ÃƒÂºÃƒÂ»ÃƒÂ¼ÃƒÂ½ÃƒÂ¿",
      "AAAAAACEEEEIIIINOOOOOOYSaaaaaaceeeeiiiinoooooouuuuyy");

    //double character:
    $match   = array('ÃƒÂ¦', 'Ãƒâ€ ');
    $replace = array('ae', 'AE');
    $str = str_replace($replace, $replace, $str);

    return $str;
  }
  */

  function removehooks($str) {
    $str = str_ireplace("[", '', $str);
    $str = str_ireplace("]", '', $str);
    $str = str_ireplace("(", '', $str);
    $str = str_ireplace(")", '', $str);
    $str = str_ireplace("{", '', $str);
    $str = str_ireplace("}", '', $str);
    return $str;
  }

  function removeopeninghook($str) {

    $returnvalue = $str;
    $laststr = left(trim($str),1);
    if ($laststr == "("
    || $laststr == "<"
    || $laststr == "["
    || $laststr == "{") {
      $returnvalue = rightfrom(trim($str), 1);
    }
    return $returnvalue;

    //return ltrim($str, "({<[");
  }

  function removeclosinghook($str) {

    $returnvalue = $str;
    $laststr = right(trim($str),1);
    if ($laststr == ")"
    || $laststr == ">"
    || $laststr == "]"
    || $laststr == "}") {
      $returnvalue = leftfrom(trim($str), 1);
    }
    return $returnvalue;

    //return rtrim($str, ")}>]");
  }

  function removeouterhooks($str) {
    $returnvalue = removeopeninghook(removeclosinghook($str));


    return $returnvalue;
  }

//	function removereturns($str) {
//	  $str = str_ireplace('\n', ' ', stripslashes($str));
//	  $str = str_ireplace('\r', ' ', stripslashes($str));
//	  $str = str_ireplace(chr(10), ' ', stripslashes($str));
//	  return $str;
//	}

  function unquote($str) {
    $str  = trim($str);
    $left = left(ltrim($str),1);
    $right= right(rtrim($str),1);

    //remove left quote?
    if ($left == chr(34)
    ||  $left == chr(39)
    ||  $left == chr(96)
    ||  $left == chr(180)) {
      $str = rightfrom(ltrim($str),1);
    }

    //remove right quote?
    if ($right == chr(34)
    ||  $right == chr(39)
    ||  $right == chr(96)
    ||  $right == chr(180)) {
      $str = leftfrom(rtrim($str),1);
    }

    return $str;
  }


  function quote($str, $quote='"') {
    return addquotes($str, $quote);
  }


  function quoted($str, $quote='"') {
    return quote(unquote($str), $quote);
  }

  //function removecomments($string) {
  //  $string = preg_replace('!/\*.*?\*/!s', '', $string);
  //  $string = preg_replace('/<!--(.|\s)*?-->/', '', $string);
  //
  //  return $string;
  //}

  function removecomments($input) {
    $output = $input;

    // smarty style comments weghalen
    $output = preg_replace('/{\*.*\*}/Uis', '', $output );

    // javascript style comments weghalen
    // (moet met enkele aanhalingstekens, anders geeft ie een fout aan!)
    $output = preg_replace('/\/\*.*\*\//Uis', '', $output );

    //verwijder alles wat niet tot de template behoort
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

    // html style comments weghalen
    //$output = preg_replace('/<!--.*-->/Uis', '', $output );
    $output = removecomments_html($output);

    //$output = preg_replace('/\<!--(.|\t\n\r)*?--\>/u', '', $output );
    //$output = preg_replace('#\<!--([^>]*)--\>#', '', $output );

    return $output;
  }


  function removecomments_html($string) {
    //deze functie verwijdert ook geneste html comments

    //strpos of the opening tag
    $start_position = 0;

    //number of tags "deep" we are
    $current_count = 0;

    //go through the string and remove the tags:
    for ( $p = 0; $p < strlen($string); $p++ ) {
      //find opening tags
      if ( $string[$p] == "<" && $string[$p+1] == "!"
          && $string[$p+2] == "-" && $string[$p+3] == "-" )
      {
        $current_count++;
        if ( $current_count == 1 )
        {
          $start_position = $p;
        }
      }
      //find closing tags if applicable.
      if ( $current_count > 0 && $string[$p] == "-"
          && $string[$p+1] == "-" && $string[$p+2] == ">")
      {
        $current_count--;
        if ( $current_count == 0 )
        {
          $p = $p + 3;
          $string = substr($string, 0, $start_position) . substr($string, $p);
          $start_position = 0;
          $p = 0;
        }
      }
    }

    //handle any uneven tags at the end.
    return substr($string, 0, (strpos($string, "<!--") == 0 ? strlen($string) : strpos($string, "<!--"))) . "\n";
  }


  function removequotes($str) {
    $str = str_ireplace("\"", '', $str);
    $str = str_ireplace("'", '', $str);
    $str = str_ireplace("`", '', $str);
    return $str;
  }

  function removecommentlines($input) {
    $splittedinput = explode("\n", $input);
    $returninput   = "";

    foreach ($splittedinput as $key=>$lineinput) {
      if (!startswith(trim($lineinput), "//")) {
        $returninput .= ((($key > 0) ? "\n" : "") . $lineinput);
      }
    }

    return $returninput;
  }

  function is_quoted($str) {
    $returnvalue  = false;

    if (is_string($str)
    &   strlen($str) > 0) {
      if ($str[0] == '"'
      &&  $str[strlen($str)-1] == '"') {
        $returnvalue = true;
      }
    }

    return $returnvalue;
  }


  function addquotes($str, $quote='"') {
    return $quote . $str . $quote;
  }


  function addquotes_parts($str, $delimiter=",", $quote='"') {
    $strreturn= "";
    $strparts = explode($delimiter, $str);

    foreach ($strparts as $key=>$strpart) {
      $strpart = $quote . $strpart . $quote;

      if ($key == 0) {
        $strreturn .= $strpart;
      } else {
        $strreturn .= $delimiter . $strpart;
      }
    }

    return $strreturn;
  }


  function removemultiplespaces($str, $betweenquotes=true) {
    //betweenquotes nog niet geimplementeerd
    //preg_replace("/[[:blank:]]+/m", " ", $string);
    return preg_replace("/\s+/S", " ", $str);
  }

  function removedashes($str) {
    $str = str_ireplace("-", '', $str);
    $str = str_ireplace("_", '', $str);
    return $str;
  }

  function removenonalpha($str) {
    $pattern = "/[^0-9a-zA-Z]/";
    return preg_replace($pattern, "", $str);
  }

  function removenonnumbers($str) {
    $pattern = "/[^0-9]/";
    return preg_replace($pattern, "", $str);
  }

  function removenonletters($str) {
    $pattern = "/[^a-zA-Z]/";
    return preg_replace($pattern, "", $str);
  }

  function removecharacters($str, $characters) {
    $array = preg_split('//', $characters, -1, PREG_SPLIT_NO_EMPTY);
    foreach($array as $character) {
      $str = str_ireplace($character, '', $str);
    }
    return $str;
  }

  function removeletters($str) {
    return removecharacters($str, "abcdefghijklmnopqrstuvwxyz");
  }

  function removenumbers($str) {
    return removecharacters($str, "0123456789");
  }

  function removereturns($str) {
    $str = preg_replace("/(\r?\n)/", " ", $str);
    $str = str_ireplace("<br>", "", $str);
    $str = str_ireplace("<br />", "", $str);
    $str = str_ireplace("<br/>", "", $str);
    return $str;
  }

  function removelinebreaks($str) {
//    $str = preg_replace("/(\r?\n)/", " ", $str);
    $str = str_replace("\r", '', str_replace("\n", '', $str));
    //$str = str_ireplace("&lt;br /&gt;", "<br />");
    return $str;
  }

  function javascriptlinebreaks($str) {
    $str = str_replace("\r", "\\", str_replace("\n", "\\", $str));

    return $str;
  }

  function removesubstring($str, $substr) {
    $str = str_ireplace($substr,"",$str);
    return $str;
  }

  function trimstringleft($str, $trimstr) {
    if (comparetext(left($str, strlen($trimstr)), $trimstr)) {
      $str = right($str, strlen($str)-strlen($trimstr));
    }
    return $str;
  }

  function trimstringright($str, $trimstr) {
    if (comparetext(right($str, strlen($trimstr)), $trimstr)) {
      $str = left($str, strlen($str)-strlen($trimstr));
    }
    return $str;
  }


  function trimstring($str, $trimstr) {
    $str = trimstringleft(trimstringright($str, $trimstr), $trimstr);
/*
    if (comparetext(left($str, strlen($trimstr)), $trimstr)) {
      $str = right($str, strlen($str)-strlen($trimstr));
    }
    if (comparetext(right($str, strlen($trimstr)), $trimstr)) {
      $str = left($str, strlen($str)-strlen($trimstr));
    }
*/
    return $str;
  }


  function trimhttp($str) {
    $str = str_ireplace("http://", "", $str);
    $str = str_ireplace("https://", "", $str);

    return $str;
  }


  function nonbreakablespaces($str) {
    $str = str_ireplace(" ","&nbsp;",$str);
    return $str;
  }

  function nl2br_all($string) {
    $string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
    return $string;
  }


  function left($str, $howManyCharsFromLeft)	{
    $returnstring = "";

    try {
      $returnstring = substr($str, 0, $howManyCharsFromLeft);
    } catch (Exception $e) {
      //fbb($str);
      //fbb(get_caller_method());
    }

    return $returnstring;
  }


  function right($str, $howManyCharsFromRight)	{
    $strLen = strlen($str);
    return substr($str, $strLen - $howManyCharsFromRight, $strLen);
  }


  function jsonstring($str) {
    if (startswith($str,'{')&&endswith($str,'}')) {
      //JSON
      $str = json_decode($str);
    }
    return $str;
  }


  function contains($haystack, $needle, $casesensitive=false, $comparealpha=false) {
    if ($comparealpha) {
      $haystack = removenonalpha($haystack);
      $needle = removenonalpha($needle);
    }

    if ($casesensitive) {
      $pos = strpos($haystack, $needle);
    } else {
      $pos = stripos($haystack, $needle);
    }

    if ($pos!==false) {
      return true;
    } else {
      return false;
    }
  }


  function contains_one($haystack, $needles, $needledelimiter="", $casesensitive=false, $comparealpha=false) {
    if ($comparealpha) {
      $haystack = removenonalpha($haystack);
    }

    if (is_string($needles)) {
      if (!is_empty($needledelimiter)) {
        $needlearray  = explode($needledelimiter, $needles);
      } else {
        $needlearray  = array($needles);
      }
    } else if (is_array($needles)) {
      $needlearray = $needles;
    }

    foreach ($needles as $needle) {
      if ($comparealpha) {
        $needle = removenonalpha($needle);
      }

    	if ($casesensitive) {
      	$pos = strpos($haystack, $needle);
    	} else {
    		$pos = stripos($haystack, $needle);
    	}

    	if ($pos!==false) {
    		return true;
    	} else {
    	  return false;
    	}
    }

    return false;
  }


  function contains_all($haystack, $needles, $needledelimiter="", $casesensitive=false, $comparealpha=false) {
    if ($comparealpha) {
      $haystack = removenonalpha($haystack);
    }

    if (is_string($needles)) {
      if (!is_empty($needledelimiter)) {
        $needlearray  = explode($needledelimiter, $needles);
      } else {
        $needlearray  = array($needles);
      }
    } else if (is_array($needles)) {
      $needlearray = $needles;
    }

    foreach ($needles as $needle) {
      if ($comparealpha) {
        $needle = removenonalpha($needle);
      }

      if ($casesensitive) {
        $pos = strpos($haystack, $needle);
      } else {
        $pos = stripos($haystack, $needle);
      }

      if ($pos===false) {
        return false;
      }
    }

    return $pos!==false;
  }


  function startswith($str, $start, $trim=false) {
    $returnvalue = false;

    if (is_string($start)) {
      $start =array($start);
    }

    $tempstr = ($trim) ? trim($str) : $str;

    foreach ($start as $startstr) {
      if (comparetext(left($tempstr, strlen($startstr)), $startstr)) {
        return true;
      }
    }

    return $returnvalue;
  }


  function endswith($str, $end, $trim=false) {
    $returnvalue = false;

    if (is_string($end)) {
      $end =array($end);
    }

    $tempstr = ($trim) ? trim($str) : $str;

    foreach ($end as $endstr) {
      if (comparetext(right($tempstr, strlen($endstr)), $endstr)) {
        return true;
      }
    }

    return $returnvalue;
  }


  function mid($str, $start, $howManyCharsToRetrieve = 0) {
    $start--;
    if ($howManyCharsToRetrieve === 0)
      $howManyCharsToRetrieve = strlen($str) - $start;

    return substr($str, $start, $howManyCharsToRetrieve);
  }


  function leftfrom($str, $vanafchar)	{
    return substr($str, 0, strlen($str)-$vanafchar);
  }


  function rightfrom($str, $vanafchar)	{
    return substr($str, $vanafchar, strlen($str)-$vanafchar);
  }


  function trimleftright($str, $left, $right)	{
    return leftfrom(rightfrom(trim($str),$left),$right);
  }

  function multi_substr_count( $haystack, $needle ) {
    $count = 0;
    if (is_array($needle)) {
      foreach ($needle as $substring) {
        $count += substr_count( $haystack, $substring);
      }
    } else {
      $count = substr_count( $haystack, $needle);
    }
    return $count;
  }

  function spatiestring($aantal) {
/*
    $returnstring = '';
    for ($t = 1; $t<=$aantal ; $t++)	{
      $returnstring .= "&nbsp;";
    }
    return $returnstring;
*/
    return str_repeat("&nbsp;",$aantal);
  }


  function alignstring($string, $align) {
    $result = $string;
    if (comparetext($align,'LEFT')) {
      $result = "&nbsp;".$string;
    }
    if (comparetext($align,'RIGHT')) {
      $result = $string."&nbsp;";
    }
    return $result;
  }


  function compareboolean($boolean1, $boolean2	) {
    $conv1 = strtobool($boolean1);
    $conv2 = strtobool($boolean2);
    if ($conv1==$conv2) {
      return true;
    }
  }


  /**
   * Vergelijk string met andere strings
   * @return boolean
   * @param string $string
   * @param array  $array
  */
  function comparetext($string, $array) {
    if (is_string($array)) {
      $array = explode(",", $array);
    }

    if (is_array($array)) {
      foreach ($array as $key => $value) {
        if (strcasecmp(strval(trim($string)), strval(trim($value))) == 0) {
          return true;
        } else {
          return false;
        }
      }
    }

    return false;
  }


  /* Uitgeschakeld vanwege UTF-8 probleem
  function comparealpha($string1, $string2	) {
    $alphastring1 = removemultiplespaces(removenonalpha(replacenonwestern($string1)));
    $alphastring2 = removemultiplespaces(removenonalpha(replacenonwestern($string1)));

    return comparetext($alphastring1, $alphastring2);
  }
  */

  function strpos_case_insensitive($haystack, $needle, $offset=0) {
    $haystack = substr($haystack, $offset , strlen($haystack) );

    $temp = stristr($haystack, $needle);
    $pos = strlen($haystack) - strlen($temp);

    if ($pos == strlen($haystack) )
      $pos = FALSE;
    else
      $pos += $offset;

    return $pos;
  }

  function leftpart($string, $separator=" ", $defaultvalue="") {
    //$parts = explodei($separator, $string);
    //if ($parts[0]) {
    //  return $parts[0];
    //}
    if (is_empty($defaultvalue)) {
      $defaultvalue = $string;
    }

    if (contains($string, $separator)) {
      return separatedstring($string, 1, $separator);
    } else {
      return $defaultvalue;
    }
  }

  function rightpart($string, $separator=" ", $defaultvalue="") {
    //$parts = explodei($separator, $string);
    //if ($parts[1]) {
    //  return $parts[1];
    //}
    if (contains($string, $separator)) {
      return separatedstring($string, 2, $separator);
    } else {
      return $defaultvalue;
    }
  }


  function betweenpart($string, $leftseparator=" ", $rightseparator=" ", $defaultvalue="",$checkboth=false) {
    $returnstring = $defaultvalue;

    if (stripos($string, $leftseparator) !== false
    &&  stripos($string, $rightseparator) !== false) {
      $returnstring = leftpart(rightpart($string,$leftseparator),$rightseparator, $defaultvalue);
    } else if (stripos($string, $leftseparator) !== false
           && !$checkboth) {
      $returnstring = rightpart($string, $leftseparator, $defaultvalue);
    } else if (stripos($string, $rightseparator) !== false
           && !$checkboth) {
      $returnstring = leftpart($string, $rightseparator, $defaultvalue);
    }

    return $returnstring;
  }


  function leftparts($string, $separator=" ", $limit=0) {
    $returnvalue = "";
    $stringparts = explodei($separator, $string);
    foreach($stringparts as $index=>$stringpart) {
      if ($index < $limit || $limit==0) {
        $returnvalue = $returnvalue.$stringpart;
        //TO DO: laatste separator eraf halen
        $returnvalue = $returnvalue.$separator;
      }
    }
    return $returnvalue;

  }


  function rightparts($string, $separator=" ", $limit=0) {
    $returnvalue = "";
    $stringparts = explodei($separator, $string);
    $reversestringparts = array_reverse($stringparts);
    foreach($reversestringparts as $index=>$stringpart) {
      if ($index < $limit || $limit==0) {
        //TO DO: eerste separator eraf halen
        $returnvalue = $separator;
        $returnvalue = $separator.$stringpart.$returnvalue;
      }
    }
    return $returnvalue;
  }


  //FUNCTION trim_explode
  function trim_explode($separator, $str) {
    $explodearray = explode($separator, $str);
    foreach($explodearray as $key=>$explodeitem) {
      $explodearray[$key] = trim($explodeitem);
    }
    return $explodearray;
  }


  function explode_trim($separator, $str) {
    return trim_explode($separator, $str);
  }


  function multi_explode($separators, $str, $trim=false, $casesensitive=false) {
    if (is_array($separators)) {
      $pipe = "";
      $pattern = "/";
      foreach($separators as $separator) {
        $pattern .= $pipe . preg_quote($separator);
        $pipe = "|";
      }
      $pattern .= "/" . (!$casesensitive ? "i" : "");
    } else {
      if(!$separators) {
        $pattern = "[\W]";
      } else {
        $pattern = "/[".preg_quote($separators)."]/" . (!$casesensitive ? "i" : "");
      }
    }

    $explodearray = preg_split($pattern, $str, false, PREG_SPLIT_NO_EMPTY);

    if ($trim) {
      foreach($explodearray as $key=>$explodeitem) {
        $explodearray[$key] = trim($explodeitem);
      }
    }

    return $explodearray;

  }

  function explode_multi($separators, $str, $trim=false, $casesensitive=false) {
    return multi_explode($separators, $str, $trim, $casesensitive);
  }

  function separatoraantal($string, $separator)	{
    $hulpaantal = 0;
    $hulpfoundone = 0;

    do {
      $hulpfoundone = strpos_case_insensitive($string, $separator, ($hulpfoundone+1));
      if ($hulpfoundone > 0) {
        $hulpaantal = $hulpaantal + 1;
      }
    } while ($hulpfoundone>0);
    return $hulpaantal;
  }


  function separatedstring($string, $positie, $separator)	{
    $pieces = explode($separator, $string, 2);
    //$arraypositie = $positie-1;

    if (count($pieces)>=$positie) {
      $returnvalue = trim($pieces[$positie-1]);
      if (comparetext(right($returnvalue,1),'|')) {
        return trim(leftfrom($returnvalue,1));
      } else {
        return trim($pieces[$positie-1]);
      }
    } else {
      return '';
    }
  }


  function explodei($separator, $string, $limit = false ) {
    $len = strlen($separator);
    for ( $i = 0; ; $i++ ) {
      if ( ($pos = stripos( $string, $separator )) === false || ($limit !== false && $i > $limit - 2 ) ) {
         $result[$i] = $string;
         break;
      }
      $result[$i] = substr( $string, 0, $pos );
      $string = substr( $string, $pos + $len );
    }
    return $result;
  }


  function explode_brackets($str, $separator=",", $leftbracket="(", $rightbracket=")", $quote="'", $ignore_escaped_quotes=true ) {
    $buffer = '';
    $stack = array();
    $depth = 0;
    $betweenquotes = false;
    $len = strlen($str);
    $char = '';
    for ($i=0; $i<$len; $i++) {
      $previouschar = $char;
      $char = $str[$i];
      switch($char) {
        case $separator:
          if (!$betweenquotes) {
            if (!$depth) {
              if ($buffer !== '') {
                $stack[] = $buffer;
                $buffer = '';
              }
              continue 2;
            }
          }
          break;
        case $quote:
          if ($ignore_escaped_quotes) {
            if ($previouschar!="\\") {
              $betweenquotes = !$betweenquotes;
            }
          } else {
            $betweenquotes = !$betweenquotes;
          }
          break;
        case $leftbracket:
          if (!$betweenquotes) {
            $depth++;
          }
          break;
        case $rightbracket:
          if (!$betweenquotes) {
            if ($depth) {
              $depth--;
            } else {
              $stack[] = $buffer.$char;
              $buffer = '';
              continue 2;
            }
          }
          break;
        }
        $buffer .= $char;
    }
    if ($buffer !== '') {
      $stack[] = $buffer;
    }

    return $stack;
  }


	/*
  function str_ireplace($searchFor, $replaceWith, $string, $offset = 0) {
    if (is_string($searchFor)
    &&  is_string($replaceWith)
    &&  is_string($string)) {
      $lsearchFor = strtolower($searchFor);
      $lstring = strtolower($string);
      $newPos = strpos($lstring, $lsearchFor, $offset);
      if (strlen($newPos) == 0) {
        return($string);
      } else {
        $left = substr($string, 0, $newPos);
        $right = substr($string, $newPos + strlen($searchFor));
        $newStr = $left . $replaceWith . $right;
        return str_ireplace($searchFor, $replaceWith, $newStr, $newPos + strlen($replaceWith));
      }
    } else {
      return $string;
    }
  }
	*/

  function get_string_between_haken($string) {
    $openhaak       = false;
    $sluithaak      = false;
    $stringarray    = str_split($string);
    $betweenstring  = "";

    foreach($stringarray as $character) {
      if ($character=="(") {
        $openhaak = true;
      }
      if ($character==")"  && !$openhaak ) {
        $sluithaak = true;
      }
      if ($character==")" && $openhaak) {
        $openhaak = false;
      }
      if (!$sluithaak) {
        $betweenstring .= $character;
      }
    }

    return $betweenstring;
  }


  function get_string_restant_rechtehaken($string) {
    $openhaak     = false;
    $sluithaak    = false;
    $stringarray  = str_split($string);
    $betweenstring= "";

    foreach($stringarray as $character) {
      if ($character=="[") {
        $openhaak = true;
      }
      if ($character=="]"  && !$openhaak ) {
        $sluithaak = true;
      }
      if ($character=="]" && $openhaak) {
        $openhaak = false;
      }

      if (!$sluithaak) {
        $betweenstring .= $character;
      }
    }


    return $betweenstring;
  }


  function get_string_restant_accolades($string) {
    $openhaak     = false;
    $sluithaak    = false;
    $stringarray  = str_split($string);
    $betweenstring= "";

    foreach($stringarray as $character) {
      if ($character=="{") {
        $openhaak = true;
      }
      if ($character=="}"  && !$openhaak ) {
        $sluithaak = true;
      }
      if ($character=="}" && $openhaak) {
        $openhaak = false;
      }

      if (!$sluithaak) {
        $betweenstring .= $character;
      }
    }


    return $betweenstring;
  }


  function get_string_restant_brackets($string) {
    $openhaak     = false;
    $sluithaak    = false;
    $stringarray  = str_split($string);
    $betweenstring= "";

    foreach($stringarray as $character) {
      if ($character=="[" || $character=="{") {
        $openhaak = true;
      }
      if (($character=="]" ||$character=="}") && !$openhaak ) {
        $sluithaak = true;
      }
      if (($character=="]" || $character=="}") && $openhaak) {
        $openhaak = false;
      }

      if (!$sluithaak) {
        $betweenstring .= $character;
      }
    }

    return $betweenstring;
  }


  function get_string_between_rechtehakenprocent($string) {
    $openhaak     = 0;
    $sluithaak    = false;
    $stringarray  = str_split($string);
    $betweenstring= "";

    foreach($stringarray as $key=>$character) {
      if ($character=="["
      &&  $stringarray[$key+1]=="%") {
        $openhaak++;
      }

      if ($character=="]"  && $openhaak > 1) {
        $openhaak--;
      }
      if ($character=="]" && $openhaak == 1) {
        $openhaak--;
        $betweenstring .= "]";
        break;
      }

      if ($openhaak > 0 && !$sluithaak) {
        $betweenstring .= $character;
      }
    }

    return $betweenstring;
  }


  function get_string_between_brackets($string) {
    $openhaak     = 0;
    $stringarray  = str_split($string);
    $resultstring = "";

    foreach($stringarray as $character) {
      if ($character=="]"
      ||  $character=="}") {
        $openhaak--;
      }

      if ($openhaak > 0) {
        $resultstring .= $character;
      }

      if ($character=="["
      ||  $character=="{") {
        $openhaak++;
      }

    }

    return $resultstring;
  }


  function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = stripos($string,$start);

    if ($ini == 0) return "";
    $ini += strlen($start);

    $len = stripos($string,$end,$ini) - $ini;

    return substr($string,$ini,$len);
  }


  function addslashes_singlequotes($string) {
    return str_replace("'", "\'", $string);
  }


  function removeBOM($str=""){
          if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
                  $str=substr($str, 3);
          }
          return $str;
  }


  function removebrackets($string) {
    return str_replace(["{","}", "[", "]", "(", ")", "<", ">"], "", $string);
  }


  function removebraces($string) {
    return str_replace(["{","}"], "", $string);
  }


  function replace_between_brackets($string, $search, $replace, $replaceprefix="", $casesensitive=false) {
    $returnstring = $string;

    if (!is_array($search)) {
      $search = array($search);
    }
    if (!is_array($replace)) {
      $replace = array($replace);
    }

    foreach($search as $key=>$searchvalue) {
      $search[$key] = '/[\[|\{]' . preg_quote($replaceprefix.$searchvalue) . '[\]|\}]/' . (!$casesensitive ? "i" : "");
    }

    $returnstring = preg_replace($search, $replace, $returnstring);

    return $returnstring;
  }


  function alert($str){
    $strwithoutlinebreaks = preg_replace("/(\r?\n)/", "", $str);
    printlinejs("<SCRIPT type='text/javascript' language='javascript'>");
    printlinejs("  alert('$strwithoutlinebreaks');");
    printlinejs("</SCRIPT>");
    //     window.open('windowalert.php','This is the new page','scrollbars,resizable,width=400,height=200');
  }


  function jsalert($str){
    $strwithoutlinebreaks = preg_replace("/(\r?\n)/", "", $str);
    echo "alert('".$strwithoutlinebreaks."');";
  }


  function sqlwindow($sql) {
    windowalert(sqlstring($sql));
  }


  function windowalert($str){
    $strwithoutlinebreaks = left(preg_replace("/(\r?\n)/", "", $str), 20000);

//    alert($strwithoutlinebreaks);
    printlinejs("    <SCRIPT type='text/javascript' language='javascript'>");

    printlinejs("      var windowwidth  = 400;");
    printlinejs("  		 var windowheight = 200;");
    printlinejs("      var windowleft   = (screen.width-windowwidth)/2;");
    printlinejs("  		 var windowtop    = (screen.height-windowheight)/2;");
    printlinejs("      var winsettings  = 'left=windowleft,top=windowtop,width=windowwidth,height=windowheight,status=no,resizable=yes';");

    printlinejs("      var url = \"/windowalert.php?info=$strwithoutlinebreaks\";");

    printlinejs("      result = window.open(url,'',winsettings);");
    printlinejs("    </SCRIPT>");
  }

  function jswindowalert($str){
    $strwithoutlinebreaks = left(preg_replace("/(\r?\n)/", "", $str), 10000);

    printlinejs("      var windowwidth  = 400;");
    printlinejs("  		 var windowheight = 200;");
    printlinejs("      var windowleft   = (screen.width-windowwidth)/2;");
    printlinejs("  		 var windowtop    = (screen.height-windowheight)/2;");
    printlinejs("      var winsettings  = 'left=windowleft,top=windowtop,width=windowwidth,height=windowheight,status=no,resizable=yes';");

    printlinejs("      var url = \"windowalert.php?info=".$strwithoutlinebreaks."\";");

    printlinejs("      result = window.open(url,'',winsettings);");
  }

  function evalphp($string) {
//    $string = preg_replace('/\<\?.*\?\>\s*/i',"",$string);
 //   $testarray = array();
    $returnstring = '';
    preg_match('/\<\?.*\?\>/i',$string, $matches);
    foreach($matches as $match) {
      $evalmatch = substr($match, 2, strlen($match)-4);
//      $returnstring .= $evalmatch;

//$gebruikerid = 1;
      $evaluatedmatch = '';
      eval("\$evaluatedmatch = \"$evalmatch\";");
//      $evaluatedmatch = eval($evalmatch);//eval("\$evalmatch;");
//      $returnstring = $evalmatch;
      $returnstring .= str_ireplace($match, $evaluatedmatch, $string);
//      $returnstring .= str_ireplace($match, $evalmatch, $string);
    }
//    windowalert($testarray[0]);
/*

//	  $string = str_ireplace('<?%?>','test',$string);
//	  $string = str_ireplace('?>','',$string);

    winalert($string);
*/
//alert($returnstring);
    return $returnstring;
  }

  function isalphanumeric($value) {
    return ctype_alnum($value);
  }

  function isbool($value) {
    $returnvalue = false;

    if (comparetext($value, "true")
    ||  comparetext($value, "false")) {
      $returnvalue = true;
    } else {
      $returnvalue = false;
    }

    return $returnvalue;
  }

  function isboolstr($value) {
    $returnvalue = false;

    if (gettype($value) == "string") {
      if (comparetext($value, "true")
      ||  comparetext($value, "false")) {
        $returnvalue = true;
      }
    }

    return $returnvalue;
  }


  function is_set($value) {
    return isset($value);
  }


  function isempty($value) {
    return is_empty($value);
  }


  function is_empty($var) {
    $returnvalue = null;

    switch (gettype($var)) {
      case "string":
        if (!comparetext(trim($var), "")
        &&  !comparetext(trim($var), "0")
        &&  !comparetext(trim($var), "null")
        &&  !comparetext(trim($var), "{null}") ) {
          $returnvalue = false;
        } else {
          $returnvalue = true;
        }
        break;

      case "boolean":
        $returnvalue = !$var;
        break;

      case "integer":
      case "double":
        $returnvalue = ($var == 0);
        break;

      case "object" :
        switch (get_class($var)) {
          case "SimpleXMLElement" :
            $returnvalue = $var->count() == 0;
            break;

          default:
            $returnvalue = false;
            break;
        }
        break;

      case "resource" :
        $returnvalue = false;
        break;

      case "array" :
        $returnvalue = (empty($var));
        break;

      case "NULL" :
        $returnvalue = true;
        break;
    }

    return $returnvalue;
  }


  function is_nothing($var) {
    $returnvalue = null;

    switch (gettype($var)) {
      case "string":
        if (!comparetext(trim($var), "")) {
          $returnvalue = false;
        } else {
          $returnvalue = true;
        }
        break;

      case "boolean":
        $returnvalue = false;
        break;

      case "integer":
      case "double":
        $returnvalue = false;
        break;

      case "object" :
        $returnvalue = false;
        break;

      case "resource" :
        $returnvalue = false;
        break;

      case "array" :
        $returnvalue = false;
        break;

      case "NULL" :
        $returnvalue = true;
        break;
    }

    return $returnvalue;
  }

  function isnotempty($var) {
    $returnvalue = null;

    switch (gettype($var)) {
      case "string":
        if (!comparetext($var, "")
        &&  !comparetext($var, "null")
        &&  !comparetext($var, "{null}") ) {
          $returnvalue = true;
        } else {
          $returnvalue = false;
        }
        break;

      case "integer":
      case "double":
        $returnvalue = ($var != 0);
        break;

      case "boolean":
        $returnvalue = $var;
        break;

      case "object" :
        $returnvalue = true;
        break;

      case "resource" :
        $returnvalue = TRUE;
        break;

      case "array" :
        $returnvalue = (!empty($var));
        break;

      case "NULL" :
        $returnvalue = false;
        break;
    }

    return $returnvalue;
  }


  function is_evaluation($var) {
    if (is_string($var)) {
      if (stripos($var, "eval_") !== false) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }


  function isnull($value, $default) {
    $returnvalue = $default;
    if (!comparetext($value, "")) {
      $returnvalue = $value;
    }
    return $returnvalue;
  }


  function isnotnull($value, $default) {
    if (!comparetext($value,'')) {
      $returnvalue = $default;
    } else {
      $returnvalue = '';
    }
    return $returnvalue;
  }

  function isnullthen($value, $nullvalue) {
    if (is_null($value)) {
      $returnvalue = $nullvalue;
    } else {
      $returnvalue = $value;
    }

    return $returnvalue;
  }

  function isnullthenelse($value, $nullvalue, $notnullvalue) {
    if (comparetext($value,'')) {
      $returnvalue = $nullvalue;
    } else {
      $returnvalue = $notnullvalue;
    }
    return $returnvalue;
  }

  function isnotnullthenelse($value, $notnullvalue, $nullvalue) {
    if (comparetext($value,'')) {
      $returnvalue = $nullvalue;
    } else {
      $returnvalue = $notnullvalue;
    }
    return $returnvalue;
  }


  function coalesce($value, $default1=null, $default2=false, $default3=false, $default4=false) {
    //TODO: variabel aantal defaults
    return isnull(isnull(isnull(isnull($value, $default1), $default2), $default3), $default4);
  }


  function coalescenull($value, $default1) {
    //TODO: variabel aantal defaults
    if (is_null($value)) {
      return $default1;
    } else {
      return $value;
    }
  }

/*
  function array_separator($array, $separator) {
    $count = 0;
    $returnvalue = '';
    foreach($array as $item) {
      if ($count>0) {
        $returnvalue .= $item.$separator;
      }
      $returnvalue .= $item;
      $count++;
    }
    return $returnvalue;
  }
*/


  function istruethen($value, $truevalue) {
    if ($value) {
      $returnvalue = $truevalue;
    } else {
      $returnvalue = false;
    }

    return $returnvalue;
  }

  function istruethenelse($value, $truevalue, $nottruevalue) {
    if ($value) {
      $returnvalue = $truevalue;
    } else {
      $returnvalue = $nottruevalue;
    }
    return $returnvalue;
  }

  function istrueorelse($value, $elsevalue) {
    if ($value) {
      $returnvalue = $value;
    } else {
      $returnvalue = $elsevalue;
    }

    return $returnvalue;
  }

  function booltostr($bool, $default="true") {
    $str = $default;

    if (is_bool($bool)) {
      if ($bool) {
        $str = "true";
      } else {
        $str = "false";
      }
    } else if (is_string($bool)) {
      $str = $bool;
    } else if (is_number($bool)) {
      $str = ($bool ==0) ? "false" : "true";
    }

    return $str;
  }

  function booltostr2($bool) {
    $str = "false";
    if ($bool) {
      $str = "true";
    }
    return $str;
  }

  function inttobool($int) {
    $returnvalue = true;

    if ($int != 1) {
      $returnvalue = false;
    }

    return $returnvalue;
  }

  function inttobool2($int) {
    $returnvalue = false;

    if ($int == 1) {
      $returnvalue = true;
    }

    return $returnvalue;
  }

  function vartobool($var) {
    $returnvalue=false;
    if (is_bool($var)) {
      $returnvalue = $var;
    } elseif (is_string($var)) {
      $returnvalue = strtobool2($var);
    } elseif (is_int($var)) {
      $returnvalue = inttobool2($var);
    }

    return $returnvalue;
  }


  /*
  function strtobool($str) {
    $bool = true;
    if (comparetext($str, 'FALSE')||comparetext($str,'NO')||$str=='0') {
      $bool = false;
    }
    return $bool;
  }

  function strtobool2($str) {
    $bool = false;

    if (comparetext($str, "TRUE")
    ||  comparetext($str, "YES")
    ||  comparetext($str, "1")   ) {
      $bool = true;
    }
    return $bool;
  }

  function strtobool3($str) {
    $bool = false;
    if (comparetext($str, "TRUE")
    ||  comparetext($str, "YES")
    ||  comparetext($str, "1")
    ||  comparetext($str, "!FALSE")
    ||  comparetext($str, "!NO")) {
      $bool = true;
    }
    return $bool;
  }
  */


  function strtoint($s){
    return(int)preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$s);
  }


  function strtobool($var, $default=true) {
    $returnvalue = $default;

    if (is_string($var)) {
      if (comparetext($var, "TRUE")
      ||  comparetext($var, "YES")
      ||  comparetext($var, "ON")
      ||  comparetext($var, "1") ) {
        $returnvalue = true;
      } elseif (comparetext($var, "FALSE")
      ||  comparetext($var, "NO")
      ||  comparetext($var, "OFF")
      ||  comparetext($var, "0")
      ||  comparetext($var, "") ) {
        $returnvalue = false;
      }
    } else if (is_bool($var)) {
      $returnvalue  = $var;
    } else if (is_number($var)) {
      $returnvalue  = ($var == 0 || $var == 1);
    } else if (is_null($var)) {
      $returnvalue  = false;
    }

    return $returnvalue;
  }

  function strtobool2($var) {
    $returnvalue = false;

    if (is_string($var)) {
      if (comparetext($var, "TRUE")
      ||  comparetext($var, "YES")
      ||  comparetext($var, "1")   ) {
        $returnvalue = true;
      }
    } else if (is_bool($var)) {
      $returnvalue  = $var;
    } else if (is_number($var)) {
      $returnvalue  = ($var != 0);
    } else if (is_null($var)) {
      $returnvalue  = false;
    }

    return $returnvalue;
  }


  function strtobool3($var) {
    $returnvalue = false;

    if (is_string($var)) {
      if (comparetext($var, "TRUE")
      ||  comparetext($var, "YES")
      ||  comparetext($var, "1")
      ||  comparetext($var, "!FALSE")
      ||  comparetext($var, "!NO")) {
        $returnvalue = true;
      }
    } else if (is_bool($var)) {
      $returnvalue  = $var;
    } else if (is_number($var)) {
      $returnvalue  = ($var != 0);
    } else if (is_null($var)) {
      $returnvalue  = false;
    }

    return $returnvalue;
  }


  function loremipsum($numberofwords) {
    $lorem = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum";

    $lorem = str_repeat($lorem, round($numberofwords/70));

    $returnstring = '';
    $loremarray = explode(' ', $lorem);
    for ($i = 0; $i < $numberofwords; $i++) {
      $returnstring .= $loremarray[rand(0, $numberofwords-1)]." ";
    }
    return trim($returnstring);
  }

  function euroformat($number) {
    return "ÃƒÆ’Ã‚Â¯Ãƒâ€šÃ‚Â¿Ãƒâ€šÃ‚Â½&nbsp;".number_format($number,2,',','.');
  }


  function hyperlink($text) {
//    $text = preg_replace( "#(http://(?:www\.)?[^\s\.]*\.[^\s$]+)#i" , "<a href=\"$1\" style=\"font-weight: bold;\">$1</a>", $text );


    $regex = "/(?#WebOrIP)((?#protocol)((http|https):\/\/)?(?#subDomain)(([a-zA-Z0-9]+\.(?#domain)[a-zA-Z0-9\-]+(?#TLD)(\.[a-zA-Z]+){1,2})|(?#IPAddress)((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])))+(?#Port)(:[1-9][0-9]*)?)+(?#Path)((\/((?#dirOrFileName)[a-zA-Z0-9_\-\%\~\+]+)?)*)?(?#extension)(\.([a-zA-Z0-9_]+))?(?#parameters)(\?([a-zA-Z0-9_\-]+\=[a-z-A-Z0-9_\-\%\~\+]+)?(?#additionalParameters)(\&([a-zA-Z0-9_\-]+\=[a-z-A-Z0-9_\-\%\~\+]+)?)*)?/";
    $text = preg_replace( $regex, "<a href=\"http://$4\" style=\"font-weight: bold;\" target='_new'>$1</a>", $text );

/*
    // match protocol://address/path/
    $text = ereg_replace("[a-zA-Z]+://([.]?[a-zA-Z0-9_/-])*", "<a href=\"\\0\">\\0</a>", $text);

    // match www.something
    $text = ereg_replace("(^| )(www([.]?[a-zA-Z0-9_/-])*)", "\\1<a href=\"http://\\2\">\\2</a>", $text);
*/
    return $text;
  }

  function arraystring($array, $separator) {
    $count = 0;
    $returnstring = '';
    if (count($array)>0) {
      foreach($array as $value) {
        if ($count>0) {
          $returnstring .= $separator;
        }
        $returnstring .= $value;
        $count++;
      }
    }
    return $returnstring;
  }


  function arraykeystring($array, $separator) {
    $count = 0;
    $returnstring = '';
    if (count($array)>0) {
      foreach($array as $key=>$value) {
        if ($count>0) {
          $returnstring .= $separator;
        }
        $returnstring .= $key;
        $count++;
      }
    }
    return $returnstring;
  }


  function propertyarray($array, $property) {
    $returnarray = array();
    foreach($array as $item) {
      if ($item->$property) {
        $returnarray[] = $item->$property;
      }
    }
    return $returnarray;
  }


  function classarraypropertystring($array, $property, $separator) {
    $count = 0;
    $returnstring = '';
    if (count($array)>0) {
      foreach($array as $item) {
        if ($item->$property) {
          if ($count>0) {
            $returnstring .= $separator;
          }
          $returnstring .= $item->$property;
          $count++;
        }
      }
    }
    return $returnstring;
  }

  /*
  function is_serialized($data){
    if (is_string($data)) {
      if (trim($data) == "") {
          return false;
      }
      if (preg_match("/^(i|s|a|o|d)(.*);/si",$data)) {
          return true;
      }

      return false;
    } else {
      return false;
    }
  }
  */

  function is_true($var) {
    if (is_bool($var)) {
      return $var === true;
    } else if (is_string($var)) {
      return comparetext($var, "true") || comparetext($var, "1") || comparetext($var, "yes") || comparetext($var, "on");
    } else if (is_number($var)) {
      return $var == 1;
    } else {
      return false;
    }
  }


  function is_false($var) {
    if (is_bool($var)) {
      return $var === false;
    } else if (is_string($var)) {
      return comparetext($var, "false") || comparetext($var, "0") || comparetext($var, "no") || comparetext($var, "off");
    } else if (is_number($var)) {
      return $var == 0;
    } else {
      return false;
    }
  }


  function is_json($string) {
    json_decode($string);

    return (json_last_error() == JSON_ERROR_NONE);
  }


  function is_number($var) {
    return is_int($var) || is_float($var) || is_double($var);
  }


  function is_numberic($var) {
    if (is_number($var)) {
      return true;
    } else if (is_numeric($var)) {
      return true;
    } else if (is_string($var)) {
      $result = null;

      preg_match_all('/[^0-9\.\,]/i', trim($var), $result);

      return (is_array($result) && !is_empty($result)) ? is_empty($result[0]) : true;
    } else {
      return false;
    }
  }


  function is_alphanumeric($var) {
    if (is_number($var)) {
      return true;
    } else if (is_numeric($var)) {
      return true;
    } elseif (is_string($var)) {
      return !preg_match('/[^a-z0-9]/i', $var, $result);
    } else {
      return false;
    }
  }

  function is_currency($var) {
    if (is_string($var)
    && !is_empty(trim($var))) {
      $result   = null;

      $varhtml  = htmlspecialchars(htmlentities(trim($var)));
      $first    = trim($var)[0];
      $firstten = substr($varhtml, 0, 10);
      $rest     = trim(str_replace("€", "", str_replace("$", "", $var)));

      if ($firstten == "&amp;euro;"
      ||  $first == "$") {
        if (is_numberic($rest)) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }


  function is_money($var) {
    return is_currency($var);
  }


  function is_arrayic($value) {
    if (is_array($value)) {
      return true;
    } else if (is_string($value)) {
      if (strlen($value)) {
        if ($value[0] == "["
        &&  $value[strlen($value) - 1] == "]") {
          return true;
        }
      } else {
        return false;
      }
    }

    return false;
  }


  function is_objectic($value) {
    if (is_object($value)) {
      return true;
    } else if (is_string($value)) {
      if (strlen($value)) {
        if ($value[0] == "{"
        &&  $value[strlen($value) - 1] == "}"
        &&  $value != "{null}") {
          return true;
        }
      } else {
        return false;
      }
    }

    return false;
  }


  function is_nullic($value) {
    if (is_null($value)) {
      return true;
    } else if (is_string($value)) {
      if (comparetext($value, "null")
      ||  comparetext($value, "{null}")) {
        return true;
      }
    }

    return false;
  }


  function is_boolic($value) {
    if (is_bool($value)) {
      return true;
    } else if (is_string($value)) {
      if (comparetext($value, "true")
      ||  comparetext($value, "false")) {
        return true;
      }
    } else if (is_numeric($value)) {
      if ($value == 1
      ||  $value == 0) {
        return true;
      }
    }

    return false;
  }


  function is_boolexpression($value) {
    //preg_match_all('/^(([ \-+><!01])|(&&)|(==)|(\x7C\x7C)|(!=)|(<=)|(>=)|(true)||(false))+$/i', $value, $result);
    preg_match('/^(([ \-+><!01])|(&&)|(==)|(\x7C\x7C)|(!=)|(<=)|(>=)|(true)||(false))+$/i', $value, $result);

    return is_array($result) && !is_empty($result);
  }


  function is_stringable($var) {
    if  (
          ( !is_array( $var ) ) &&
          ( ( !is_object( $var ) && settype( $var, 'string' ) !== false ) ||
          ( is_object( $var ) && method_exists( $var, '__toString' ) ) )
        ) {
      return true;
    } else {
      return false;
    }
  }


  function upfirst($string) {
    $returnvalue = "";

    if (is_string($string)) {
      $returnvalue = strtoupper(substr($string, 0, 1)) . strtolower(substr($string, 1));
    }

    return $returnvalue;
  }


  function isVisibleStr($needle, $haystack) {
    $returnvalue = false;
    $pos = stripos($haystack, $needle);

    if ($pos !== false) {
      if ($pos > 0) {
        if (substr($haystack, $pos-1, 1) != "!") {
          $returnvalue = true;
        }
      } else {
        $returnvalue = true;
      }
    }

    return $returnvalue;
  }


  function isEnabledStr($needle, $haystack) {
    $returnvalue = false;
    $pos = stripos($haystack, $needle);

    if ($pos !== false) {
      if ($pos > 0) {
        if (substr($haystack, $pos-1, 1) != "~"
        &&  substr($haystack, $pos-1, 1) != "!") {
          $returnvalue = true;
        }
      } else {
        $returnvalue = true;
      }
    }

    return $returnvalue;
  }


  function convert_to_iso($str) {
    //$returnstring = utf8_encode($str);
    //$returnstring = $str;
    //$returnstring = mb_convert_encoding($str, "ISO-8859-15", "UTF-8");
    //$returnstring = iconv("UTF-8", "CP1252", $str);
    $returnstring = iconv("UTF-8", "CP1252", $str);

    return $returnstring;
  }


  function convert_to_utf8($str) {
    //$returnstring = utf8_decode($str);
    //$returnstring = $str;
    //$returnstring = mb_convert_encoding($str, "UTF-8", "ISO-8859-15");
    $returnstring = iconv("CP1252", "UTF-8", $str);

    return $returnstring;
  }


  function moneystringtofloat($str) {
    if ($str) {
      if (!(left(right($str,3),1)==".")) {
        //haal thousand separators weg
        $str = str_ireplace('.','', $str);
        $str = str_ireplace(',','.', $str);
      } else {
        //vervang komma's door punten
      }

      return floatval(ereg_replace("[^-0-9\.]","",$str));
    } else {
      return $str;
    }
  }


  function parseInt($string) {
    if(preg_match('/(\d+)/', $string, $array)) {
      return $array[1];
    } else {
      return 0;
    }
  }

  /*
  function is_serialized($string) {
    if (is_string($string)
    &&  !is_empty($string)) {
      if (is_urlEncoded($string)) {
        $string=urldecode($string);
      }

      try {
        return (@unserialize($string) !== false);
      } catch (Exception $e) {
        return false;
      }
    } else {
      return false;
    }
  }
  */

  function is_serialized( $data ) {
    // if it isn't a string, it isn't serialized
    if ( !is_string( $data ) )
        return false;
    $data = trim( $data );
    if ( 'N;' == $data )
        return true;
    if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
        return false;
    switch ( $badions[1] ) {
        case 'a' :
        case 'O' :
        case 's' :
            if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                return true;
            break;
        case 'b' :
        case 'i' :
        case 'd' :
            if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                return true;
            break;
    }
    return false;
  }


  /*
  function is_serialized($value, &$result = null)  {
    // Bit of a give away this one
    if (!is_string($value))
    {
      return false;
    }

    // Serialized false, return true. unserialize() returns false on an
    // invalid string or it could return false if the string is serialized
    // false, eliminate that possibility.
    if ($value === 'b:0;')
    {
      $result = false;
      return true;
    }

    $length = strlen($value);
    $end  = '';

    if ($length) {
      switch ($value[0])
      {
        case 's':
          if ($value[$length - 2] !== '"')
          {
            return false;
          }
        case 'b':
        case 'i':
        case 'd':
          // This looks odd but it is quicker than isset()ing
          $end .= ';';
        case 'a':
        case 'O':
          $end .= '}';

          if ($value[1] !== ':')
          {
            return false;
          }

          switch ($value[2])
          {
            case 0:
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            break;

            default:
              return false;
          }
        case 'N':
          $end .= ';';

          if ($value[$length - 1] !== $end[0])
          {
            return false;
          }
        break;

        default:
          return false;
      }
    } else {
      return false;
    }

    if (($result = @unserialize($value)) === false)
    {
      $result = null;
      return false;
    }
    return true;
  }
  */


  function forceUTF8var($var) {
    if (is_string($var)) {
      return forceUTF8($var);
    } elseif (is_array($var)) {
      foreach ($var as $key=>$value) {
        $var[$key] = forceUTF8var($value);
      }

      return $var;
    } else {
      return $var;
    }
  }


  /**
   * @author   "SebastiÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡n Grignoli" <grignoli@framework2.com.ar>
   * @package  forceUTF8
   * @version  1.1
   * @link     http://www.framework2.com.ar/dzone/forceUTF8-es/
   * @example  http://www.framework2.com.ar/dzone/forceUTF8-es/
    */

  function forceUTF8($text){
  /**
   * Function forceUTF8
   *
   * This function leaves UTF8 characters alone, while converting almost all non-UTF8 to UTF8.
   *
   * It may fail to convert characters to unicode if they fall into one of these scenarios:
   *
   * 1) when any of these characters:   ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÆ’Ã†â€™ÃƒÂ¯Ã‚Â¿Ã‚Â½ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€¹Ã¢â‚¬Â ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â°ÃƒÆ’Ã†â€™Ãƒâ€¦Ã‚Â ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¹ÃƒÆ’Ã†â€™Ãƒâ€¦Ã¢â‚¬â„¢ÃƒÆ’Ã†â€™ÃƒÂ¯Ã‚Â¿Ã‚Â½ÃƒÆ’Ã†â€™Ãƒâ€¦Ã‚Â½ÃƒÆ’Ã†â€™ÃƒÂ¯Ã‚Â¿Ã‚Â½ÃƒÆ’Ã†â€™ÃƒÂ¯Ã‚Â¿Ã‚Â½ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‹Å“ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…â€œÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã¯Â¿Â½ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Å“ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬ï¿½ÃƒÆ’Ã†â€™Ãƒâ€¹Ã…â€œÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂºÃƒÆ’Ã†â€™Ãƒâ€¦Ã¢â‚¬Å“ÃƒÆ’Ã†â€™ÃƒÂ¯Ã‚Â¿Ã‚Â½ÃƒÆ’Ã†â€™Ãƒâ€¦Ã‚Â¾ÃƒÆ’Ã†â€™Ãƒâ€¦Ã‚Â¸
   *    are followed by any of these:  ("group B")
   *                                    ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â£ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¤ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¥ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¦ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â§ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¨ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â©ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂªÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â«ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â­ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â®ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¯ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â°ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â±ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â²ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â³ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â´ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂµÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¶ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¸ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¹ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚ÂºÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â»ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¼ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â½ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¾ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¿
   * For example:   %ABREPRESENT%C9%BB. ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â«REPRESENTÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â°ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â»
   * The "ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â«" (%AB) character will be converted, but the "ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â°" followed by "ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â»" (%C9%BB)
   * is also a valid unicode character, and will be left unchanged.
   *
   * 2) when any of these: ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â£ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¤ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¥ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¦ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â§ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚ÂªÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â«ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â­ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â®ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¯  are followed by TWO chars from group B,
   * 3) when any of these: ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â°ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â±ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â²ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³  are followed by THREE chars from group B.
   *
   * @name forceUTF8
   * @param string $text  Any string.
   * @return string  The same string, UTF8 encoded
   *
   */

    if(is_array($text))
      {
        foreach($text as $k => $v)
      {
        $text[$k] = forceUTF8($v);
      }
        return $text;
      }

      $max = strlen($text);
      $buf = "";
      for($i = 0; $i < $max; $i++){
          $c1 = $text{$i};
          if($c1>="\xc0"){ //Should be converted to UTF8, if it's not UTF8 already
            $c2 = $i+1 >= $max? "\x00" : $text{$i+1};
            $c3 = $i+2 >= $max? "\x00" : $text{$i+2};
            $c4 = $i+3 >= $max? "\x00" : $text{$i+3};
              if($c1 >= "\xc0" & $c1 <= "\xdf"){ //looks like 2 bytes UTF8
                  if($c2 >= "\x80" && $c2 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                      $buf .= $c1 . $c2;
                      $i++;
                  } else { //not valid UTF8.  Convert it.
                      $cc1 = (chr(ord($c1) / 64) | "\xc0");
                      $cc2 = ($c1 & "\x3f") | "\x80";
                      $buf .= $cc1 . $cc2;
                  }
              } elseif($c1 >= "\xe0" & $c1 <= "\xef"){ //looks like 3 bytes UTF8
                  if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                      $buf .= $c1 . $c2 . $c3;
                      $i = $i + 2;
                  } else { //not valid UTF8.  Convert it.
                      $cc1 = (chr(ord($c1) / 64) | "\xc0");
                      $cc2 = ($c1 & "\x3f") | "\x80";
                      $buf .= $cc1 . $cc2;
                  }
              } elseif($c1 >= "\xf0" & $c1 <= "\xf7"){ //looks like 4 bytes UTF8
                  if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                      $buf .= $c1 . $c2 . $c3;
                      $i = $i + 2;
                  } else { //not valid UTF8.  Convert it.
                      $cc1 = (chr(ord($c1) / 64) | "\xc0");
                      $cc2 = ($c1 & "\x3f") | "\x80";
                      $buf .= $cc1 . $cc2;
                  }
              } else { //doesn't look like UTF8, but should be converted
                      $cc1 = (chr(ord($c1) / 64) | "\xc0");
                      $cc2 = (($c1 & "\x3f") | "\x80");
                      $buf .= $cc1 . $cc2;
              }
          } elseif(($c1 & "\xc0") == "\x80"){ // needs conversion
                  $cc1 = (chr(ord($c1) / 64) | "\xc0");
                  $cc2 = (($c1 & "\x3f") | "\x80");
                  $buf .= $cc1 . $cc2;
          } else { // it doesn't need convesion
              $buf .= $c1;
          }
      }

      //euro sign? werkt niet !
      //$buf = str_replace(chr(0xC2).chr(0x80) , chr(0xE2).chr(0x82).chr(0xAC),  $buf); // ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬

      return $buf;
  }


  function forceLatin1($text) {
    if(is_array($text)) {
      foreach($text as $k => $v) {
        $text[$k] = forceLatin1($v);
      }
      return $text;
    }
    return utf8_decode(forceUTF8($text));
  }


  function fixUTF8($text){
    if(is_array($text)) {
      foreach($text as $k => $v) {
        $text[$k] = fixUTF8($v);
      }
      return $text;
    }

    $last = "";
    while($last <> $text){
      $last = $text;
      $text = forceUTF8(utf8_decode(forceUTF8($text)));
    }
    return $text;
  }

  function is_utf8($str) {
      $c=0; $b=0;
      $bits=0;
      $len=strlen($str);
      for($i=0; $i<$len; $i++){
          $c=ord($str[$i]);
          if($c > 128){
              if(($c >= 254)) return false;
              elseif($c >= 252) $bits=6;
              elseif($c >= 248) $bits=5;
              elseif($c >= 240) $bits=4;
              elseif($c >= 224) $bits=3;
              elseif($c >= 192) $bits=2;
              else return false;
              if(($i+$bits) > $len) return false;
              while($bits > 1){
                  $i++;
                  $b=ord($str[$i]);
                  if($b < 128 || $b > 191) return false;
                  $bits--;
              }
          }
      }
      return true;
  }


  /**
   * Converts a csv file into an array of lines and columns.
   * khelibert@gmail.com
   * @param $fileContent String
   * @param string $escape String
   * @param string $enclosure String
   * @param string $delimiter String
   * @return array
  */
  function csvToArray($fileContent, $delimiter = ',', $enclosure = '"', $escape = '\\') {
      $lines = array();
      $fields = array();

      if($escape == $enclosure) {
          $escape = '\\';
          $fileContent = str_replace(array('\\',$enclosure.$enclosure,"\r\n","\r"),
                      array('\\\\',$escape.$enclosure,"\\n","\\n"),$fileContent);
      } else {
          $fileContent = str_replace(array("\r\n","\r"),array("\\n","\\n"),$fileContent);
      }

      $nb = strlen($fileContent);
      $field = '';
      $inEnclosure = false;
      $previous = '';

      for($i = 0;$i<$nb; $i++) {
          $c = $fileContent[$i];
          if ($c === $enclosure) {
              if($previous !== $escape) {
                  $inEnclosure ^= true;
              } else {
                  $field .= $enclosure;
              }
          } else if($c === $escape) {
              $next = $fileContent[$i+1];
              if($next != $enclosure && $next != $escape)
                  $field .= $escape;
          } else if($c === $delimiter) {
              if($inEnclosure) {
                  $field .= $delimiter;
              } else {
                //end of the field
                $fields[] = $field;
                $field = '';
              }
          } else if($c === "\n") {
              $fields[] = $field;
              $field = '';
              $lines[] = $fields;
              $fields = array();
          } else {
              $field .= $c;
              $previous = $c;
          }
      }

      //we add the last element
      if(true || $field !== '') {
          $fields[] = $field;
          $lines[] = $fields;
      }

      return $lines[0];
  }


  function addfirstslash($filename) {
    $returnvalue = $filename;
    if (left($filename,1)!='/' || left($filename,1)!=chr(92)) {
      $returnvalue = '/'.stripfirstslash($filename);
    }
    return $returnvalue;
  }


  function addlastslash($filename) {
    $returnvalue = $filename;
    if (right($filename,1)!='/' || right($filename,1)!=chr(92)) {
      $returnvalue = striplastslash($filename).'/';
    }
    return $returnvalue;
  }


  function stripfirstslash($filename) {
    return removefirstslash($filename);
  }


  function removefirstslash($string) {
    $returnvalue = $string;
    if (left($string,1)=='/' || left($string,1)==chr(92)) {
      $returnvalue = rightfrom($string, 1);
    }
    return $returnvalue;
  }


  function striplastslash($string) {
    return removelastslash($string);
  }


  function removelastslash($string) {
    $returnvalue = $string;
    if (right($string,1)=='/' || right($string,1)==chr(92)) {
      $returnvalue = leftfrom($string, 1);
    }
    return $returnvalue;
  }


  function stripouterslashes($string) {
    return removeouterslashes($string);
  }


  function removeouterslashes($string) {
    $returnvalue = stripfirstslash(striplastslash($string));

    return $returnvalue;
  }


  function stripfirstquote($string) {
    return removefirstquote($string);
  }


  function removefirstquote($string) {
    $returnvalue = $string;
    if (left($string,1)=='\'' || left($string,1)=='"') {
      $returnvalue = rightfrom($string, 1);
    }
    return $returnvalue;
  }


  function striplastquote($string) {
    return removelastquote($string);
  }


  function removelastquote($string) {
    $returnvalue = $string;
    if (right($string,1)=='\'' || right($string,1)=='"') {
      $returnvalue = leftfrom($string, 1);
    }
    return $returnvalue;
  }


  function stripouterquotes($string) {
    return removeouterquotes($string);
  }


  function removeouterquotes($string) {
    $returnvalue = stripfirstquote(striplastquote($string));

    return $returnvalue;
  }


  function stripquotes($string) {
    return removequotes($string);
  }


  function stripspaces($string) {
    return removespaces($string);
  }


  function removespaces($string, $includenbsp=false) {
    if ($includenbsp) {
      return preg_replace('/(\s|&nbsp;)/i', '', $string);
    } else {
      return str_replace(" ", "", $string);
    }
  }

  function removetabs($str) {
    //$str = str_ireplace("\\t","",$str);
    $str = preg_replace('/\s+/', ' ',$str);

    return $str;
  }


  function removewhitespace($str) {
   $str = removespaces(removetabs(removelinebreaks(removereturns($str))));

   return $str;
  }


  function getkeyvalue($string, $key, $defaultvalue=false, $delimiter=";,") {
    $keyvaluearray = multi_explode($delimiter, $string);

    foreach ($keyvaluearray as $keyvaluepair) {
      $stringkey   = leftpart($keyvaluepair, "=");
      $stringvalue = rightpart($keyvaluepair, "=");

      if (comparetext($key,$stringkey)) {
        return $stringvalue;
      }
    }

    return $defaultvalue;
  }


  function remove_betweenparts($string, $leftseparator=" ", $rightseparator=" ") {
    $leftseparator = ($leftseparator == " ") ? "\s" : "\\" . $leftseparator;
    $rightseparator = ($rightseparator == " ") ? "\s" : "\\" . $rightseparator;

    return preg_replace("/" . $leftseparator . "[^" . $rightseparator . "]+" . $rightseparator . "/i", "", $string);
  }


  function is_urlEncoded($string){
    //$test_string = $string;
    //while(urldecode($test_string) != $test_string){
    //  $test_string = urldecode($test_string);
    //}

    //return (urlencode($test_string) == $string)?True:False;

    if (is_string($string)) {
      return (urldecode($string) != $string);
    } else {
      return false;
    }
  }


  function camelCase($string, $capitalizeFirstCharacter = false) {
    $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

    if (!$capitalizeFirstCharacter) {
        $str[0] = strtolower($str[0]);
    }

    return $str;
  }


  function randomchar($includevowels=true,$includeconsonants=true,$includenumbers=false) {
    $length = 1;

    if ($includevowels
    &&  $includeconsonants
    &&  $includenumbers) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    } else if ($includevowels
           &&  $includeconsonants
           && !$includenumbers) {
      $characters = 'abcdefghijklmnopqrstuvwxyz';
    } else if ($includevowels
           && !$includeconsonants
           && !$includenumbers) {
      $characters = 'aeiouy';
    } else if (!$includevowels
           &&  $includeconsonants
           && !$includenumbers) {
      $characters = 'bcdfghjklmnpqrstvwxz';
    }

    $randomChar = '';
    for ($i = 0; $i < $length; $i++) {
        $randomChar .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomChar;
}


function randomstring($length = 10, $includenumbers=true,$includecapitals=true) {
    if ($includenumbers
    &&  $includecapitals) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    } else if ($includenumbers
           && !$includecapitals) {
      $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    } else if (!$includenumbers
           &&  !$includecapitals) {
      $characters = 'abcdefghijklmnopqrstuvwxyz';
    }

    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }


  function utf8_encode_deep(&$input) {
    if (is_string($input)) {
        $input = utf8_encode($input);
    } else if (is_array($input)) {
        foreach ($input as &$value) {
            utf8_encode_deep($value);
        }

        unset($value);
    } else if (is_object($input)) {
        $vars = array_keys(get_object_vars($input));

        foreach ($vars as $var) {
            utf8_encode_deep($input->$var);
        }
    }
  }

  function singular($string) {
    if (contains($string, "|")) {
      $parts = explode("|",$string);
      return $parts[0];
    } else {
      return $string;
    }
  }

  function plural($string) {
    if (contains($string, "|")) {
      $parts = explode("|",$string);
      if (startswith($parts[1], "-")) {
        return $parts[0].rightfrom($parts[1],1);
      } else {
        return $parts[1];
      }
    } else {
      return $string;
    }
  }
?>