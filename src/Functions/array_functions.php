<?
  //NAMESPACE
  namespace SB\Functions;

  //INCLUDES  functions
  include_once __DIR__ . "/string_functions.php";
  include_once __DIR__ . "/eval_functions.php";

  //use
  use SB\Functions as sb;

  //FUNCTIONS
  function in_array_case($needle, $haystack, $casesensitive=false) {
    if ($casesensitive) {
      return in_array($needle, $haystack);
    } else {
      return in_array(strtolower($needle), array_map("strtolower", $haystack));
    }
  }


  function compare_in_array($string, $array, $keys=false) {
    $returnvalue = false;
    if ($array) {
	    foreach($array as $key=>$value) {
	      if ($keys) {
  	      if (SB\Functions\comparetext($string, $key)) {
  	        $returnvalue = true;
  	      }
	      } else {
  	      if (comparetext($string, $value)) {
  	        $returnvalue = true;
  	      }
	      }
	    }
    }

    return $returnvalue;
  }


  function array_key_get($key, $search) {
    return $search[$key];
  }


  function array_key_get_case($key, $search, $casesensitive=false) {
    if ($casesensitive) {
    	return $search[$key];
    } else {
        foreach ($search as $arraykey=>$arrayvalue) {
          if (strtolower($arraykey) == strtolower($key)) {
            return $arrayvalue;
          }
        }
    }

    return false;
  }


  function array_key_set_case($key, $value, $search, $casesensitive=false) {
    if ($casesensitive) {
      $search[$key] = $value;
    } else {
      foreach ($search as $arraykey=>$arrayvalue) {
        if (strtolower($arraykey) == strtolower($key)) {
          $search[$arraykey] = $value;
        }
      }
    }

    return false;
  }


  function array_key_exists_case($key, $search, $casesensitive=false) {
    if (is_array($search)) {
      if ($casesensitive) {
        return array_key_exists($key, $search);
      } else {
        return in_array(strtolower($key), array_map('strtolower', array_keys($search)));
      }
    } else {
      return false;
    }
  }


  function array_key_value_exists($key, $val, $search) {
    foreach ($search as $item) {
      if (is_array($item)) {
        if (isset($item[$key])
        && $item[$key] == $val) {
          return true;
        }
      } elseif (is_object($item)) {
        if (property_exists($item, $key)
        && $item->$key == $val) {
          return true;
        }
      }
    }

    return false;
  }


  function array_insert_array(&$array, $position, $insert_array) {
      if (!is_int($position)) {
          $i = 0;
          foreach ($array as $key => $value) {
              if ($key == $position) {
                  $position = $i;
                  break;
              }
              $i++;
          }
      }
      $first_array = array_splice ($array, 0, $position);
      $array = array_merge ($first_array, $insert_array, $array);
  }


  function array_setvalue($array, $key, $value) {
    if (is_array($array)) {
      $array[$key] = $value;
//      printbreak($key."=".$array[$key]);
    }
  }


  function dom2array($node) {
    $res = array();
//    print $node->nodeType.'<br/>';
    if($node->nodeType == XML_TEXT_NODE){
        $res = $node->nodeValue;
    }
    else{
        if($node->hasAttributes()){
            $attributes = $node->attributes;
            if(!is_null($attributes)){
                $res['@attributes'] = array();
                foreach ($attributes as $index=>$attr) {
                    $res['@attributes'][$attr->name] = $attr->value;
                }
            }
        }
        if($node->hasChildNodes()){
            $children = $node->childNodes;
            for($i=0;$i<$children->length;$i++){
                $child = $children->item($i);
                $res[$child->nodeName] = dom2array($child);
            }
        }
    }
    return $res;
  }


  function xmlToArray($n) {
    $return=array();

    foreach($n->childNodes as $nc){
        if( $nc->hasChildNodes() ){
            if( $n->firstChild->nodeName== $n->lastChild->nodeName&&$n->childNodes->length>1){
                $item = $n->firstChild;
                $return[$nc->nodeName][]=xmlToArray($item);
            }
            else{
                 $return[$nc->nodeName]=xmlToArray($nc);
            }
       }
       else{
           $return=$nc->nodeValue;
       }
    }
    return $return;
  }


  /**
   * @return array
   * @param array $src
   * @param array $in
   * @param int|string $pos
  */
  function array_push_before($src,$in,$pos){
    if(is_int($pos)) {
      $R=array_merge(array_slice($src,0,$pos), $in, array_slice($src,$pos));
    } else {
      foreach($src as $k=>$v){
          if($k==$pos)$R=array_merge($R,$in);
          $R[$k]=$v;
      }
    }
    return $R;
  }


  /**
   * @return array
   * @param array $src
   * @param array $in
   * @param int|string $pos
  */
  function array_push_after($src,$in,$pos){
    if(is_int($pos)) {
      $R=array_merge(array_slice($src,0,$pos+1), $in, array_slice($src,$pos+1));
    } else {
      foreach($src as $k=>$v){
          $R[$k]=$v;
          if($k==$pos)$R=array_merge($R,$in);
      }
    }
    return $R;
  }


  /*
  function array_search($needle, $array) {
    $returnvalue  = false;

    // Make sure a valid array was passed
    if(!is_array($array)) {
      // Loop through each part of the array
      foreach ($array as $key => $item) {
        // Inner loop
        foreach($item as $entity) {
            // Found a match, return the key
            if ($entity == $needle) {
                $returnvalue = $key;
            }
        }
      }
    }

    return $returnvalue;
  }
  */


  function explode_assoc($delimiter, $string) {
    //explodeert een string in een associatieve array o.b.v. key=value
    $array = explode($delimiter, $string);
    $array2= array();

    foreach($array as  $val)  {
      $pos=strpos($val, "=");
      $key=substr($val, 0, $pos);

      if (!is_empty($key)
      &&  !is_empty($val)) {
        $array2[$key] = substr($val, $pos+1,strlen($val));
      }
    }

    return $array2;
  }


  function implode_assoc($delimiter, $array) {
    //implodeert een associatieve array naar een string o.b.v. key=value
    if (is_array($array)) {
      return http_build_query($array,"",";");
    } else {
      return "";
    }
  }


  function objectSort(&$data, $key) {
    for ($i = count($data) - 1; $i >= 0; $i--) {
      $swapped = false;
      for ($j = 0; $j < $i; $j++) {
        if ($data[$j]->$key > $data[$j + 1]->$key) {
          $tmp = $data[$j];
          $data[$j] = $data[$j + 1];
          $data[$j + 1] = $tmp;
          $swapped = true;
        }
      }

      if (!$swapped) return;
    }
  }


  function change_case_recursive($arr){
    foreach ($arr as $key=>$val){
        if (!is_array($arr[$key])){
            $arr[$key]=mb_strtolower($arr[$key]);
        }else{
            $arr[$key]=change_case_recursive($arr[$key]);
        }
    }
    return $arr;
  }


  function array_has_children($arr, $key, $keyvalue) {
    $returnvalue = false;

  	if (isnotempty($keyvalue)) {
	  	foreach ($arr as $arrkey=>$arrvalue) {
	  		if ($arr[$arrkey][$key] == $keyvalue) {
	  			$returnvalue = true;
	  			break;
	  		}
	  	}
  	}

  	return $returnvalue;
  }


  function array_value($array, $index, $emptyvalue='') {
    $returnvalue = $emptyvalue;

    if (is_array($array)) {
      if (isset($array[$index])) {
    		$returnvalue = $array[$index];
    	} else {
    	  $returnvalue = $emptyvalue;
    	}
    }

  	return $returnvalue;
  }


  function array_remove() {
    if ($stack = func_get_args()) {
      $input = array_shift($stack);
      foreach ($stack as $key) {
        unset($input[$key]);
      }
      return $input;
    }
    return false;
  }


  function array_remove_keys($array, $keys = array()) {
    // If array is empty or not an array at all, don't bother
    // doing anything else.
    if(empty($array) || (! is_array($array))) {
        return $array;
    }

    // If $keys is a comma-separated list, convert to an array.
    if(is_string($keys)) {
        $keys = explode(',', $keys);
    }

    // At this point if $keys is not an array, we can't do anything with it.
    if(! is_array($keys)) {
        return $array;
    }

    // array_diff_key() expected an associative array.
    $assocKeys = array();
    foreach($keys as $key) {
        $assocKeys[$key] = true;
    }

    return array_diff_key($array, $assocKeys);
  }


  function array_remove_nulls($arr) {
    foreach($arr as $key => $value) {
      if(is_null($value)) {
        unset($arr[$key]);
      }
    }
    return array_values($arr);
  }


  function array_remove_empty($arr) {
    foreach($arr as $key => $value) {
      if(sb\is_empty($value)) {
        unset($arr[$key]);
      }
    }
    return array_values($arr);
  }


  function array_unshift_assoc(&$arr, $key, $val) {
    //zelfde als array_unshift maar dan voor associatieve arrays
    $arr = array_reverse($arr, true);
    $arr[$key] = $val;
    $arr = array_reverse($arr, true);
    return count($arr);
  }


  function array_contains_only_nulls($arr) {
    $returnvalue = true;

    if (is_array($arr)) {
      foreach ($arr as $key => $value) {
        if (is_array($value)) {
          $returnvalue = array_contains_only_nulls($value);
        } else if (!is_null($value)) {
          $returnvalue = false;
          break;
        }
      }
    }

    return $returnvalue;
  }


  function array_contains_only_emptyvalues($arr) {
    $returnvalue = true;

    if (is_array($arr)) {
      foreach ($arr as $key => $value) {
        if (is_array($value)) {
          $returnvalue = array_contains_only_emptyvalues($value);
        } else if (!is_empty($value)) {
          $returnvalue = false;
          break;
        }
      }
    }

    return $returnvalue;
  }


  /* all permutations of an array */
  function power_perms($arr) {
    $power_set = power_set($arr);
    $result = array();
    foreach($power_set as $set) {
      $perms = perms($set);
      $result = array_merge($result,$perms);
    }
    return $result;
  }


  function power_set($in,$minLength = 1) {

    $count = count($in);
    $members = pow(2,$count);
    $return = array();
    for ($i = 0; $i < $members; $i++) {
      $b = sprintf("%0".$count."b",$i);
      $out = array();
      for ($j = 0; $j < $count; $j++) {
        if ($b{$j} == '1') $out[] = $in[$j];
      }
      if (count($out) >= $minLength) {
        $return[] = $out;
      }
    }

    //usort($return,"cmp");  //can sort here by length
    return $return;
  }


  function factorial($int){
    if($int < 2) {
      return 1;
    }

    for($f = 2; $int-1 > 1; $f *= $int--);

    return $f;
  }


  function perm($arr, $nth = null) {

    if ($nth === null) {
      return perms($arr);
    }

    $result = array();
    $length = count($arr);

    while ($length--) {
      $f = factorial($length);
      $p = floor($nth / $f);
      $result[] = $arr[$p];
      array_delete_by_key($arr, $p);
      $nth -= $p * $f;
    }

    $result = array_merge($result,$arr);
    return $result;
  }


  function perms($arr) {
    $p = array();
    for ($i=0; $i < factorial(count($arr)); $i++) {
      $p[] = perm($arr, $i);
    }
    return $p;
  }


  function array_delete_by_key(&$array, $delete_key, $use_old_keys = FALSE) {

    unset($array[$delete_key]);

    if(!$use_old_keys) {
      $array = array_values($array);
    }

    return TRUE;
  }


  function array_sort($array, $type='asc'){
      $result=array();
      foreach($array as $var => $val){
          $set=false;
          foreach($result as $var2 => $val2){
              if($set==false){
                  if($val>$val2 && $type=='desc' || $val<$val2 && $type=='asc'){
                      $temp=array();
                      foreach($result as $var3 => $val3){
                          if($var3==$var2) $set=true;
                          if($set){
                              $temp[$var3]=$val3;
                              unset($result[$var3]);
                          }
                      }
                      $result[$var]=$val;
                      foreach($temp as $var3 => $val3){
                          $result[$var3]=$val3;
                      }
                  }
              }
          }
          if(!$set){
              $result[$var]=$val;
          }
      }

      return $result;
  }


  function array_object_propertyexists($array, $propertyname) {
    $returnvalue = true;
    foreach ($array as $key=>$item) {
      if (is_object($item)) {
        if (!property_exists($item, $propertyname)) {
          $returnvalue = false;
          break;
        }
      }
    }

    return $returnvalue;
  }


  class array_object_sort_class {
      private static $propertyname;

      static function sort(&$array, $propertyname) {
         self::$propertyname = $propertyname;
         usort($array, array("array_object_sort_class", "cmp_method"));
      }

      static function cmp_method($a, $b) {
         $propertyname = self::$propertyname; //access meta data

         return strcmp((string)$a->$propertyname, (string)$b->$propertyname);
      }

  }


  function array_object_sort($array, $propertyname, $type="asc") {
    if (is_array($array)) {
      if (array_object_propertyexists($array, $propertyname)) {
        array_object_sort_class::sort($array, $propertyname);
      }
    }

    return $array;
  }


  function implode_r($glue, $pieces){
        $return = "";

        if(!is_array($glue)){
            $glue = array($glue);
        }

        $thisLevelGlue = array_shift($glue);

        if(!count($glue)) $glue = array($thisLevelGlue);

        if(!is_array($pieces)){
            return (string) $pieces;
        }

        foreach($pieces as $sub){
            $return .= implode_r($glue, $sub) . $thisLevelGlue;
        }

        if(count($pieces)) $return = substr($return, 0, strlen($return) -strlen($thisLevelGlue));

        return $return;
  }


  function array_get_longest_value($array) {
    //$timerstart=timer_start();

    $arraycopy  = array_map('strlen',$array);
    asort($arraycopy);
    $max = end($arraycopy);

    //fb_timer_end($timerstart, 0, "get longest value");

    return $array[key($arraycopy)];
  }

  /*
  function array_get_default($array, $key, $default="", $format="default") {
    $returnvalue =  (array_key_exists_case($key, $array)) ? array_key_get_case($key, $array) : $default;

    if ($format == "string") {
      if (is_bool($returnvalue)) {
        $returnvalue = booltostr2($returnvalue);
      }
    }

    return $returnvalue;
  }
  */


  function array_get($array, $key, $type="string", $default="") {
    $returnvalue = $default;

    if (is_array($array)) {
      if (array_key_exists($key, $array)) {
        $returnvalue =  array_key_get($key, $array);

        switch ($type) {
          case "string"     :
            if (is_bool($returnvalue)) {
              $returnvalue = booltostr($returnvalue);
            } else if (is_string($returnvalue)) {
              $returnvalue = $returnvalue;
            } else if (is_number($returnvalue)) {
              $returnvalue = (string)$returnvalue;
            } else if (is_null($returnvalue)) {
              $returnvalue = "NULL";
            }
            break;

          case "bool"       :
          case "boolean"    :
            if (is_bool($returnvalue)) {
              $returnvalue = $returnvalue;
            } else if (is_string($returnvalue)) {
              $returnvalue = strtobool($returnvalue, $default);
            } else if (is_number($returnvalue)) {
              $returnvalue = ($returnvalue != 0);
            } else if (is_null($returnvalue)) {
              $returnvalue = false;
            }
            break;

          case "int"        :
          case "integer"    :
            if (is_bool($returnvalue)) {
              $returnvalue = ($returnvalue) ? 1 : 0;
            } else if (is_string($returnvalue)) {
              $returnvalue = intval($returnvalue);
            } else if (is_number($returnvalue)) {
              $returnvalue = intval($returnvalue);
            } else if (is_null($returnvalue)) {
              $returnvalue = 0;
            }
            break;

          case "float"        :
          case "number"    :
            if (is_bool($returnvalue)) {
              $returnvalue = ($returnvalue) ? 1 : 0;
            } else if (is_string($returnvalue)) {
              $returnvalue = floatval($returnvalue);
            } else if (is_number($returnvalue)) {
              $returnvalue = floatval($returnvalue);
            } else if (is_null($returnvalue)) {
              $returnvalue = 0;
            }
            break;

          default:
            break;
        }
      }
    }

    return $returnvalue;
  }


  function array_get_case($array, $key, $type="string", $default="") {
    $returnvalue = $default;

    if (is_array($array)) {
      if (array_key_exists_case($key, $array)) {
        $returnvalue =  array_key_get_case($key, $array);

        switch ($type) {
          case "string"     :
            if (is_bool($returnvalue)) {
              $returnvalue = booltostr($returnvalue);
            } else if (is_string($returnvalue)) {
              $returnvalue = $returnvalue;
            } else if (is_number($returnvalue)) {
              $returnvalue = (string)$returnvalue;
            } else if (is_null($returnvalue)) {
              $returnvalue = "NULL";
            }
            break;

          case "bool"       :
          case "boolean"    :
            if (is_bool($returnvalue)) {
              $returnvalue = $returnvalue;
            } else if (is_string($returnvalue)) {
              $returnvalue = strtobool($returnvalue, $default);
            } else if (is_number($returnvalue)) {
              $returnvalue = ($returnvalue != 0);
            } else if (is_null($returnvalue)) {
              $returnvalue = false;
            }
            break;

          case "int"        :
          case "integer"    :
            if (is_bool($returnvalue)) {
              $returnvalue = ($returnvalue) ? 1 : 0;
            } else if (is_string($returnvalue)) {
              $returnvalue = intval($returnvalue);
            } else if (is_number($returnvalue)) {
              $returnvalue = intval($returnvalue);
            } else if (is_null($returnvalue)) {
              $returnvalue = 0;
            }
            break;

          case "float"        :
          case "number"    :
            if (is_bool($returnvalue)) {
              $returnvalue = ($returnvalue) ? 1 : 0;
            } else if (is_string($returnvalue)) {
              $returnvalue = floatval($returnvalue);
            } else if (is_number($returnvalue)) {
              $returnvalue = floatval($returnvalue);
            } else if (is_null($returnvalue)) {
              $returnvalue = 0;
            }
            break;

          default:
            break;
        }
      }
    }

    return $returnvalue;
  }

  function array_object_sum($array, $prop) {
    $sum = 0;
    $props  = null;

    if (is_string($prop)) {
      $props = explode(",",$prop);
    } else if (is_array($prop)) {
      $props = $prop;
    }

    if (is_array($props)) {
      foreach($array as $index => $arrayitem) {
        if (is_object($arrayitem)) {
          foreach($props as $propindex=>$propitem) {
            if (property_exists($arrayitem, $propitem)) {
              $sum += $arrayitem->$propitem;
            }
          }
        }
      }
    }

    return $sum;
  }

  function array_objectvisible_sum($array, $prop) {
    $sum    = 0;
    $props  = null;

    if (is_string($prop)) {
      $props = explode(",",$prop);
    } else if (is_array($prop)) {
      $props = $prop;
    }

    if (is_array($props)) {
      foreach($array as $index => $arrayitem) {
        if (is_object($arrayitem)) {
          if (property_exists($arrayitem, "Visible")
          &&  boolOrEval($arrayitem->Visible, false)) {
            foreach($props as $propindex=>$propitem) {
              if (property_exists($arrayitem, $propitem)) {
                $sum += $arrayitem->$propitem;
              }
            }
          }
        }
      }
    }

    return $sum;
  }


  function array_object_search($array, $propertyname, $propertyvalue) {
    $item = null;

    foreach($array as $index => $arrayitem) {
      if (is_object($arrayitem)) {
        if (property_exists($arrayitem, $propertyname)) {
          if ($arrayitem->$propertyname == $propertyvalue) {
            $item = $arrayitem;
            break;
          }
        }
      }
    }

    return $item;
  }


  /*
  function array_object_search($array, $propertynames, $propertyvalues) {
    $items      = [];
    $itemfound  = false;

    if (is_string($propertynames)) {
      $propertynames = explode(",", $propertynames);
    } else if (is_array($propertynames)) {
      $propertynames = $propertynames;
    } else {
      $propertynames = false;
    }

    if (is_string($propertyvalues)) {
      $propertyvalues = explode(",", $propertyvalues);
    } else if (is_array($propertyvalues)) {
      $propertyvalues = $propertyvalues;
    } else if (is_number($propertyvalues)) {
      $propertyvalues = $propertyvalues;
    } else {
      $propertyvalues = false;
    }

    foreach($array as $index => $arrayitem) {
      if (is_object($arrayitem)) {
        foreach ($propertynames as $propertykey=>$propertyname) {
          if (property_exists($arrayitem, $propertyname)) {
            if ($arrayitem->$propertyname == $propertyvalues[$propertykey]) {
              $itemfound = true;
              break;
            } else {
              $itemfound = false;
              break;
            }
          } else {
            $itemfound = false;
            break;
          }
        }

        if ($itemfound) {
          $items[] = $arrayitem;
        }
      }
    }

    if (count($items) == 0) {
      return null;
    } else if (count($items) == 1) {
      return $items[0];
    } else {
      return $items;
    }
  }
  */

  function in_array_object($array, $propertyname, $propertyvalue) {
    return !is_null(array_object_search($array, $propertyname, $propertyvalue));
  }


  function array_object_search_multiple($array, $propertynamearray, $propertyvaluearray) {
    $item = null;

    foreach($array as $index => $arrayitem) {
      if (is_object($arrayitem)) {
        $count = 0;
        foreach ($propertynamearray as $propertykey=>$propertyname) {
          if (property_exists($arrayitem, $propertyname)) {
            if ($arrayitem->$propertyname == $propertyvaluearray[$propertykey]) {
              $count++;
            }
          }
        }

        if ($count == count($propertynamearray)) {
          $item = $arrayitem;
          break;
        }
      }
    }

    return $item;
  }


  function array_search_assoc($array, $assoc, $key) {
    foreach($array as $index => $arrayitem) {
      if($arrayitem[$assoc] == $key) return $index;
    }
    return FALSE;
  }


  function array_dimensioncount($array) {
    if (is_array(reset($array))) {
      $return = array_dimensioncount(reset($array)) + 1;
    } else {
      $return = 1;
    }

    return $return;
  }


  function array_copy(&$arr) {
    $newArray = array();
    foreach($arr as $key => $value) {
        if(is_array($value)) $newArray[$key] = array_copy($value);
        else if(is_object($value)) $newArray[$key] = clone $value;
        else $newArray[$key] = $value;
    }
    return $newArray;
  }


  function array_clone($array) {
    return array_map(function($element) {
        return ((is_array($element))
            ? call_user_func(__FUNCTION__, $element)
            : ((is_object($element))
                ? clone $element
                : $element
            )
        );
    }, $array);
  }


  function is_objectarray($array) {
    $returnvalue  = true;

    if (is_array($array)) {
      foreach($array as $index => $arrayitem) {
        if (!is_object($arrayitem)) {
          $returnvalue = false;
          break;
        }
      }
    } else {
      $returnvalue = false;
    }

    return $returnvalue;
  }


  function is_objecticarray($array) {
    $returnvalue  = true;

    if (is_array($array)) {
      foreach($array as $index => $arrayitem) {
        if (is_string($arrayitem)) {
          if (!endswith($arrayitem, "_object")) {
            $returnvalue = false;
            break;
          }
        } else if (!is_object($arrayitem)) {
          $returnvalue = false;
          break;
        }
      }
    } else {
      $returnvalue = false;
    }

    return $returnvalue;
  }


  function output_array($array, $separator="<br />") {
    foreach($array as $arrayitem) {
      echo $arrayitem . $separator;
    }
  }


  function is_numeric_array($array) {
    foreach ($array as $a => $b) {
      if (!is_int($a)) {
        return false;
      }
    }
    return true;
  }


  function is_associative_array($array) {
  	return !is_numeric_array($array);
  }


  function object_to_array($obj) {
    if(is_object($obj)) $obj = (array) $obj;
    if(is_array($obj)) {
      $new = array();
      foreach($obj as $key => $val) {
        $new[$key] = object_to_array($val);
      }
    }
    else $new = $obj;
    return $new;
  }
?>
