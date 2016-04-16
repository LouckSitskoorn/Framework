<?
  //NAMESPACE
  namespace SB\Functions;

  //USES
  use SB\Functions as sb;

  //INCLUDES  functions
  include_once __DIR__ . "/string_functions.php";

  //FUNCTIONS
  function uuid() {
    //return md5(getmypid().uniqid(rand(), true).$_SERVER['SERVER_NAME']);
    return md5(getmypid().uniqid(rand(), true));
  }


  function crypt_convert($str,$key='') {
    if($key=='')return $str;
    $key=str_replace(chr(32),'',$key);
    if(strlen($key)<8)exit('key error');
    $kl=strlen($key)<32?strlen($key):32;
    $k=array();for($i=0;$i<$kl;$i++){
    $k[$i]=ord($key{$i})&0x1F;}
    $j=0;for($i=0;$i<strlen($str);$i++){
    $e=ord($str{$i});
    $str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e);
    $j++;$j=$j==$kl?0:$j;}
    return $str;
  }


  function encrypt_convert($str,$key=''){
    return crypt_convert($str, $key);
  }


  function decrypt_convert($str,$key=''){
    return crypt_convert($str, $key);
  }


  function encrypt_rijndael256($str, $key) {
    if ($key=="") return $str;

    $str = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $str, MCRYPT_MODE_CBC, md5(md5($key))));

    return $str;
  }

  function decrypt_rijndael256($str, $key) {
    if ($key=="") return $str;

    $str = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($str), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

    return $str;
  }

  //encodes the string. Returns an array with the
  //string as the first element and the initialization
  //vector as the second element
  function encrypt_easy($string, $key){
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM);
    $string = mcrypt_encrypt(MCRYPT_BLOWFISH, $key,
                            $string, MCRYPT_MODE_CBC, $iv);

    return array(base64_encode($string), base64_encode($iv));
  }


  //decodes a string
  //the first argument is an array as returned by easy_encrypt()
  function decrypt_easy($cyph_arr, $key){
   $out = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, base64_decode($cyph_arr[0]),
                         MCRYPT_MODE_CBC, base64_decode($cyph_arr[1]));

   return trim($out);
  }


  //FUNCTIONS
  function cryptConvert($string, $plakstring="crid", $usesession=true, $encodefunction='base64url_encode', $decodefunction='base64url_decode') {
    global $_SESSION;

    //sessionid aan key toevoegen?
    $key          = "louckyuri" . (($usesession) ? session_id() : "");
    $komma        = "";
    $returnstring = "";
    $strings      = explode(",", $string);

    foreach($strings as $string) {
      if (is_encrypted($string, $plakstring)) {
        //detach plakstring
        $string = trimstringleft($string, $plakstring);

        //decode string
        if (function_exists($decodefunction)) {
          $string = ($decodefunction($string));
        }

        //decrypt string
        $string = decrypt_rijndael256($string, $key);

      } else {
        //encrypt string
        $string = encrypt_rijndael256($string, $key);

        //encode string
        if (function_exists($encodefunction)) {
          $string = ($encodefunction($string));
        }

        //attach plakstring
        $string = $plakstring . $string;
      }

      $returnstring .= $komma . $string;
      $komma = ",";
    }


    return $returnstring;
  }


  function is_encrypted($string, $plakstring="crid") {
    return startswith($string, $plakstring);
  }


  function cryptConvertCheck($string, $plakstring="crid", $usesession=true) {
    if (!is_empty($string)
    &&  !comparetext($string, "-1")
    &&  !contains($string, ",") ) {
      return cryptConvert($string, $plakstring, $usesession);
    } else {
      return $string;
    }
  }


  function encryptConvert($string, $plakstring="crid", $usesession=true) {
    if (is_encrypted($string) ) {
      return $string;
    } else {
      return cryptConvert($string, $plakstring, $usesession);
    }
  }


  function encryptConvertInteger($string, $plakstring="crid", $usesession=true) {
    if (is_encrypted($string) ) {
      return $string;
    } else {
      if (is_number($string)) {
        return cryptConvert($string, $plakstring, $usesession);
      } else {
        error_logger(E_USER_WARNING, "Non-numeric encryption attempt", "String : " . $string . "\nPlakstring : " . $plakstring, basename(__FILE__), __LINE__);

        return false;
      }
    }
  }


  function decryptConvert($string, $plakstring="crid", $usesession=true) {
    if (is_encrypted($string) ) {
      return cryptConvert($string, $plakstring, $usesession);
    } else {
      return $string;
    }
  }


  function decryptSafe($string, $plakstring="crid", $usesession=true) {
    if (is_encrypted($string)) {
      $decryptedvalue = cryptConvert($string, "crid", $usesession);

      if (!is_alphanumeric($decryptedvalue)) {
        $decryptedvalue = decryptConvert($string, "crid", false);
      }
    } else {
      $decryptedvalue = $string;
    }

    return $decryptedvalue;
  }


  function decryptError($string, $plakstring="crid", $usesession=true) {
    if (is_encrypted($string) ) {
      return cryptConvert($string, $plakstring, $usesession);
    } if ($string == "{null}") {
      return $string;
    } else {
      return "DECRYPTERROR";
    }
  }


  function decryptForce($string, $plakstring="crid", $usesession=true) {
    return cryptConvert($string, $plakstring, $usesession);
  }


  function cryptConvertParts($string, $separator=",", $plakstring="crid", $usesession=true) {
    $stringreturn       =  "";
    $stringarray        = explode($separator, $string);
    $stringseparator    = "";
    foreach ($stringarray as $stringpart) {
      $stringpart = cryptConvert($stringpart, $plakstring, $usesession);

      $stringreturn   .= $stringseparator . $stringpart;
      $stringseparator = $separator;
    }

    return $stringreturn;
  }


  function encryptConvertParts($string, $separator=",", $plakstring="crid", $usesession=true) {
    $stringreturn       =  "";
    $stringarray        = explode($separator, $string);
    $stringseparator    = "";
    foreach ($stringarray as $stringpart) {
      $stringpart = encryptConvert($stringpart, $plakstring, $usesession);

      $stringreturn   .= $stringseparator . $stringpart;
      $stringseparator = $separator;
    }

    return $stringreturn;
  }

  function decryptConvertParts($string, $separator=",", $plakstring="crid", $usesession=true) {
    $stringreturn       =  "";
    $stringarray        = explode($separator, $string);
    $stringseparator    = "";
    foreach ($stringarray as $stringpart) {
      $stringpart = decryptConvert($stringpart, $plakstring, $usesession);

      $stringreturn   .= $stringseparator . $stringpart;
      $stringseparator = $separator;
    }

    return $stringreturn;
  }

  function cryptConvertPartsCheck($string, $separator=",", $plakstring="crid", $usesession=true) {
    $stringreturn       =  "";
    $stringarray        = explode($separator, $string);
    $stringseparator    = "";
    foreach ($stringarray as $stringpart) {
      $stringpart = cryptConvertCheck($stringpart, $plakstring, $usesession);

      $stringreturn   .= $stringseparator . $stringpart;
      $stringseparator = $separator;
    }

    return $stringreturn;
  }

  function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }

  function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
  }

  function base64zip_encode($string) {
    return strtr(base64_encode(addslashes(gzcompress(serialize($string),9))), '+/=', '-_,');
  }

  function base64zip_decode($encoded) {
    return unserialize(gzuncompress(stripslashes(base64_decode(strtr($encoded, '-_,', '+/=')))));
  }

  function custom_hash($input, $length, $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUFWXIZ0123456789') {
    $output = '';
    $input = md5($input); //this gives us a nice random hex string regardless of input

    do {
      foreach (str_split($input,8) as $chunk){
        srand(hexdec($chunk));
        $output .= substr($charset, rand(0,strlen($charset)), 1);
      }
      $input = md5($input);

    } while(strlen($output) < $length);

    return substr($output,0,$length);
  }

  function getGUID($removebrackets=true,$removehyphens=false){
	    if (function_exists('com_create_guid')){
	        $returnvalue =  com_create_guid();
	    }else{
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = chr(45);// "-"
	        $uuid = chr(123)// "{"
	            .substr($charid, 0, 8).$hyphen
	            .substr($charid, 8, 4).$hyphen
	            .substr($charid,12, 4).$hyphen
	            .substr($charid,16, 4).$hyphen
	            .substr($charid,20,12)
	            .chr(125);// "}"
	        $returnvalue =  $uuid;
	    }

		if ($removebrackets) {
			$returnvalue = removeouterhooks($returnvalue);
		}

		if ($removehyphens) {
			$returnvalue = str_replace("-", "", $returnvalue);
		}

		return $returnvalue;
	}


	function encryptLaravelResult($result, $fieldid="ID"){
    foreach ($result as $recordkey=>$record) {
      foreach ($record as $fieldname=>$fieldvalue) {
        if (sb\contains($fieldname, $fieldid)) {
          $result[$recordkey]->$fieldname = encryptConvert($fieldvalue);
        }
      }
    }

    return $result;
  }

?>