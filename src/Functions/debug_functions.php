<?
  //NAMESPACE
  namespace SB\Functions;

  //GLOBALS
  if (!isset($FIREPHP_ENABLED)) {
    $FIREPHP_ENABLED = (isset($firephp) && is_bool($firephp)) ? $firephp : (isset($_REQUEST["firephp"]) ? strtobool($_REQUEST["firephp"], TRUE) : (isset($_SESSION["firephp"]) ? $_SESSION["firephp"] : (isset($_SESSION["project"]) && isset($_SESSION["project"]["firephp"]) ? $_SESSION["project"]["firephp"] : TRUE)));
  }

  //INCLUDES libraries
  /*
  if (!isset($disable_firebug)
  ||  $disable_firebug != true) {
    require_once __DIR__ . "/_firebug_functions.php";
  } else {
    if(!function_exists('fb')){
      function fb() {
        //dummy fb function
      }
    }
  }
  */

  //INCLUDES functions
  require_once __DIR__ . "/browserdetection_functions.php";


  //CLASSES
  class SQLObject {
    public $Title;
    public $Time;
    public $SQL;
  }


  class XMLObject {
    public $Title;
    public $XML;
  }


  //FUNCTIONS
  function console_log($var) {
  	$browsername  = browser_detection( "browser" );

    if ($browsername == "moz") {
      console_firefox($var);
    } else if ($browsername == "webkit") {
      console_chrome($var);
    } else {
    }
  }


  function console_firefox($var) {
    fbb($var);
  }


  function console_chrome($var) {
    ChromePhp::log($var);
  }


  //REDEFINE fb function
  /*
  if (function_exists("runkit_function_redefine")) {
    //TODO: fb_redefined mag niet rechtstreeks fb() aanroepen ivm recursie
    runkit_function_redefine('fb','','fb_redefined(func_get_args());');
  }


  function fb_redefined($arr) {
    global $FIREPHP_ENABLED;

    if (!headers_sent()) {
      if ($FIREPHP_ENABLED) {
        foreach ($arr as $arritem) {
          FB::send($arritem);
        }
      }
    }
  }
  */

  function fbb() {
    global $FIREPHP_ENABLED;

    if (!headers_sent()) {
      if ($FIREPHP_ENABLED) {
        $argnum  = func_num_args();
        $arglist = func_get_args();

        for ($i = 0; $i < $argnum; $i++) {
          fb($arglist[$i]);
        }

        //fb(get_caller_info());
      }
    }
  }


  function fb_sql($sql, $title='', $time=0, $removecomments=false) {
    if ($removecomments) {
      $sql = removecomments($sql);
    }

    $object = new SQLObject();
    $object->SQL = forceUTF8($sql);
    $object->Title = $title;
    $object->Time  = round($time, 4);

    fbb($object);

    //appendfile("C:/Temp/sqlsql.txt", $sql . PHP_EOL . PHP_EOL);
  }


  function fb_xml($xml, $title='') {
    $object = new XMLObject();
    $object->XML = $xml;
    $object->Title = $title;

    fbb($object);
  }


  function fb_array($str) {
    fbb(array($str));
  }


  function timer_include($includefile) {
    $timerstart = timer_start();

    include $includefile;

    fb_timer_end($timerstart, 0, "include: ". $includefile);
  }


  function timer_start() {
    $mtime          = microtime();
    $mtimearr       = explode(" ", $mtime);
    $mtimenum       = $mtimearr[1] + $mtimearr[0];

    $timerstart     = $mtimenum;

    return $timerstart;
  }



  function timer_end($timerstart, $tekst='') {
    $mtime          = microtime();
    $mtimearr       = explode(" ", $mtime);
    $mtimenum       = $mtimearr[1] + $mtimearr[0];

    $timerend       = $mtimenum;
    $timertotal     = ($timerend - $timerstart);

    if ($tekst) {
      $tekst = $tekst.':';
    }

    return $tekst . $timertotal;
  }


  function timer_end_duration($timerstart, $tekst='', $millisecs=false, $millidecimals=5) {
    $mtime          = microtime();
    $mtimearr       = explode(" ", $mtime);
    $mtimenum       = $mtimearr[1] + $mtimearr[0];

    $timerend       = $mtimenum;
    $timertotal     = round(($timerend - $timerstart), 5);

    //$timerduration  = timer_duration($timertotal, false, (($millisecs) ? (floor($timertotal) > 0) ? floor($timertotal) - $timertotal : $timertotal : 0), $millidecimals);
    $timerduration  = timer_duration($timertotal, false, $millisecs, $millidecimals);

    if ($tekst) {
      $tekst = $tekst . " : ";
    }

    return $tekst . $timerduration;
  }


  function fb_file($filename, $tekst) {
    $tekst =  date('l jS \of F Y h:i:s A') . PHP_EOL . $tekst . PHP_EOL;

    appendfile($filename, $tekst);
  }


  function fb_timer_end($timerstart, $limit=0, $tekst="", $millisecs=true, $millidecimals=5) {
    $mtime          = microtime();
    $mtimearr       = explode(" ", $mtime);
    $mtimenum       = $mtimearr[1] + $mtimearr[0];

    $timerend       = $mtimenum;
    $timertotal     = round(($timerend - $timerstart), 5);

    if ($timertotal >= $limit || $limit==0) {
      fbb(timer_end_duration($timerstart, $tekst, $millisecs, $millidecimals));
    }

    return true;
  }


  function fb_memory_end($memorystart, $limit=0, $tekst="") {
    $memorydifference  = memory_get_usage() - $memorystart;

    if ($memorydifference >= $limit || $limit==0) {
      fbb(memory_end_difference($memorydifference, $tekst));
    }
  }


  function timer_alert($timerstart, $tekst='') {
    $timerend = timer_end($timerstart);

    if ($tekst) {
    	$tekst = $tekst.':\n';
    }

    alert($tekst.$timerend." seconden.");
  }


  function timer_alerttekst($alert, $timerstart) {
    $timerend = timer_end($timerstart);
    alert($alert.'\n'.$timerend." seconden.");
  }


  function timer_windowalert($alert, $timerstart) {
    $timerend = timer_end($timerstart);
    windowalert($alert.$timerend." seconden.");
  }


  function timer_echo($alert, $timerstart) {
    $timerend = timer_end($timerstart);
    echo $alert.$timerend." seconden.";
  }


  function timer_eval($evaluation, $message="", $alert=false) {
    $mtime = microtime();
    $mtime = explode(' ', $mtime);
    $mtime = $mtime[1] + $mtime[0];
    $timerstart = $mtime;

    eval($evaluation);

    $timerend = timer_end($timerstart);

    if ($alert) {
      alert($message. " : " . $timerend . " seconden.");
    } else {
      echo $message . " : " . $timerend . " seconden.";
    }
  }


  function print_r_html($data) {
    print "<pre>-----------------------\n";
    if(is_array($data)) { //If the given variable is an array, print using the print_r function.
      print_r($data);
    } elseif (is_object($data)) {
  #    print "<pre>==========================\n";
      var_dump($data);
  #    print "===========================</pre>";
    } else {
  #    print "=========&gt; ";
      var_dump($data);
  #    print " &lt;=========";
    }
    print "-----------------------</pre>";
  }


  function dprint_r($input, $return = FALSE, $name = NULL, $function = 'print_r') {
	  if ($name) {
	    $name .= ' => ';
	  }
	  ob_start();
	  $function($input);
	  $printed_value = '<pre>' . $name . check_plain(ob_get_clean()) . '</pre>';
	  if ($return) {
	    return $printed_value;
	  } else {
	    print $printed_value;
	  }
	}


  function debugtest($filename, $teststring) {
    $filehandle = fopen($filename, "w+");
    //$serializedpage = serialize($page);
    fwrite($filehandle, $teststring);
    fclose($filehandle);
  }


  function var_format($v) // pretty-print var_export
  {
    return (str_replace(
        array("\n"," ","array"),
        array("<br>","&nbsp;","&nbsp;<i>array</i>"),
        var_export($v,true)
      ) ."<br>");
  }


  /*
  function myDie($info)
  {
    $mysqlerr=strpos($info,"ERROR=You have an error in your SQL syntax");
    if($mysqlerr>0)$info=substr($info,0,$mysqlerr)." mySql format error";
    $out="<br>MSG='$info'<br>".var_format($_REQUEST)."<br>";
    $bt=debug_backtrace();
    $sp=0;
    $trace= "";
    $file = "";
    $line = "";
    $function = "";
    foreach($bt as $k=>$v)
    {
        extract($v);
        $file=substr($file,1+strrpos($file,"/"));
        if($file=="db.php")continue; // the db object
        $trace.=str_repeat("&nbsp;",++$sp); //spaces(++$sp);
        $trace.="file=$file, line=$line, function=$function<br>";
    }
    $out.="<br>".backTrace();
    if(substr($info,0,4)=="XXX ") // special errrors when db is inaccessible
    {
        $out=str_replace("<br>","\n",$out);
        $out=str_replace("&nbsp;"," ",$out);
        mail("me@example.com","Database Execution Error for user ".$REMOTE_ADDR,"$out");
        exit("Database Access Error. Please try again later.");
    }
    mail("test@servicebeheer.nl",'Error Monitor','Execution Error',$out);
    exit("DANG! An execution error in the program has been sent to the webmaster. If you don't get an email from him soon, please call him.");
  }
  */


  function get_caller_method() {
    $traces = debug_backtrace();

    if (isset($traces[2])) {
        return $traces[2]['function'];
    }

    return null;
  }


  /* This function will return the name string of the function that called $function. To return the
      caller of your function, either call get_caller(), or get_caller(__FUNCTION__).
  */
  function get_caller($function = NULL, $use_stack = NULL) {
      if ( is_array($use_stack) ) {
          // If a function stack has been provided, used that.
          $stack = $use_stack;
      } else {
          // Otherwise create a fresh one.
          $stack = debug_backtrace();
          echo "\nPrintout of Function Stack: \n\n";
          print_r($stack);
          echo "\n";
      }

      if ($function == NULL) {
          // We need $function to be a function name to retrieve its caller. If it is omitted, then
          // we need to first find what function called get_caller(), and substitute that as the
          // default $function. Remember that invoking get_caller() recursively will add another
          // instance of it to the function stack, so tell get_caller() to use the current stack.
          $function = get_caller(__FUNCTION__, $stack);
      }

      if ( is_string($function) && $function != "" ) {
          // If we are given a function name as a string, go through the function stack and find
          // it's caller.
          for ($i = 0; $i < count($stack); $i++) {
              $curr_function = $stack[$i];
              // Make sure that a caller exists, a function being called within the main script
              // won't have a caller.
              if ( $curr_function["function"] == $function && ($i + 1) < count($stack) ) {
                  return $stack[$i + 1]["function"];
              }
          }
      }

      // At this stage, no caller has been found, bummer.
      return "";
  }


  function get_caller_info($completeTrace=false, $completefactor=2) {
    $trace  = debug_backtrace();
    $caller = false;

    if($completeTrace) {
      $str = '';
      foreach($trace as $caller) {
        $str .= " -- Called by {$caller['function']}";
        if (isset($caller['class'])) {
          $str .= " From Class {$caller['class']}";
        }
      }
    } else {
      if (count($trace)) {
        if (isset($trace[min(array_keys($trace)) + $completefactor])) {
          $caller = $trace[min(array_keys($trace)) + $completefactor];
        } else {
          $caller = $trace[max(array_keys($trace))];
        }
      }

      if ($caller) {
        $str = "Called by {$caller['function']}";
        if (isset($caller['object'])
        &&  property_exists($caller["object"], "ID")) {
          $str .= " from : " . $caller["object"]->ID . " ";
        }
        if (isset($caller['class'])) {
          $str .= " (class : {$caller['class']})";
        }
        if (isset($caller['file'])) {
          $str .= " (file : {$caller['file']}";
        }
        if (isset($caller['line'])) {
          $str .= "  line : {$caller['line']} )";
        }
      } else {
        $str = "Unknown caller";
      }
    }
    return $str;
  }


  function timer_duration($duration, $maxperiods=false, $millisecs=false, $millidecimals=5) {
    $vals = array(
      "weken"     => (int) ($duration / 86400 / 7),
      "dagen"     => $duration / 86400 % 7,
      "uur"       => $duration / 3600 % 24,
      "minuten"   => $duration / 60 % 60,
      //"seconden"  => $duration % 60 + ($millisecs) ? round($duration, $millidecimals) : 0)
      "seconden"  => ($duration % 60) + (($millisecs) ? round(($duration-floor($duration)), $millidecimals) : 0)
    );

    $ret = array();

    $addperiods = 0;
    $added      = false;
    foreach ($vals as $k => $v) {
      if (($v > 0 || $added || $k=="seconden")
      &&  ($addperiods < $maxperiods || !$maxperiods)) {
        $added = true;
        $addperiods++;

        $ret[] = $v . " " . $k;
      }
    }

    return join(" ", $ret);
  }


  function memory_start() {
    return memory_get_usage();
  }


  function memory_end($memorystart, $tekst='') {
    $memorytotal  = memory_get_usage() - $memorystart;

    if ($tekst) {
      $tekst = $tekst.':';
    }

    return $tekst . $memorytotal;
  }

  function memory_end_difference($memdiff, $tekst="", $precision = 2) {
    $base = log($memdiff) / log(1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

    $memorydifference = round(pow(1024, $base - floor($base)), $precision) . " " . $suffixes[floor($base)];

    if ($tekst) {
      $tekst = $tekst . " : ";
    }

    return $tekst . $memorydifference;
  }

  function object2json($object, $ignore=array(), $recursionstring="" ) {

  	$associative = is_associative_array($object);

  	$komma = "";
  	$returnstring = $recursionstring ? $recursionstring : "";
   	foreach($object as $key=>$value) {
   		if (!in_array($key, $ignore)) {
       	$returnstring .= $komma;
       	if ($associative) {
     			$returnstring .= '"'.$key.'":';
       	}
     		if (is_array($value)) {
     		  $returnstring .= '[';
     		  $returnstring .= object2json($value, $ignore);
     		  $returnstring .= ']';
     		} else {
     			if (is_bool($value)) {
       		  $returnstring .= booltostr($value);
     			} else {
       		  $returnstring .= '"'.$value.'"';
     			}
     		}
     		$komma = ",";
   		}
   	}
    return $returnstring;
  }

?>