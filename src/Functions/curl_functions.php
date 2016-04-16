<?
  //NAMESPACE
  namespace SB\Functions;

  //INCLUDES
  include_once __DIR__ . "/string_functions.php";

  //FUNCTIONS
  function getHtml($url, $post = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    if(!empty($post)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }

  function get_remote_data($url, $post_paramtrs = false) {
    $c = curl_init ();
    curl_setopt ( $c, CURLOPT_URL, $url );
    curl_setopt ( $c, CURLOPT_RETURNTRANSFER, 1 );
    if ($post_paramtrs) {
      curl_setopt ( $c, CURLOPT_POST, TRUE );
      curl_setopt ( $c, CURLOPT_POSTFIELDS, "var1=bla&" . $post_paramtrs );
    }
    curl_setopt ( $c, CURLOPT_SSL_VERIFYHOST, false );
    curl_setopt ( $c, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt ( $c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0" );
    curl_setopt ( $c, CURLOPT_COOKIE, 'CookieName1=Value;' );
    curl_setopt ( $c, CURLOPT_MAXREDIRS, 10 );
    $follow_allowed = (ini_get ( 'open_basedir' ) || ini_get ( 'safe_mode' )) ? false : true;
    if ($follow_allowed) {
      curl_setopt ( $c, CURLOPT_FOLLOWLOCATION, 1 );
    }
    curl_setopt ( $c, CURLOPT_CONNECTTIMEOUT, 9 );
    curl_setopt ( $c, CURLOPT_REFERER, $url );
    curl_setopt ( $c, CURLOPT_TIMEOUT, 60 );
    curl_setopt ( $c, CURLOPT_AUTOREFERER, true );
    curl_setopt ( $c, CURLOPT_ENCODING, 'gzip,deflate' );
    $data = curl_exec ( $c );
    $status = curl_getinfo ( $c );
    curl_close ( $c );
    preg_match ( '/(http(|s)):\/\/(.*?)\/(.*\/|)/si', $status ['url'], $link );
    $data = preg_replace ( '/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si', '$1=$2' . $link [0] . '$3$4$5', $data );
    $data = preg_replace ( '/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si', '$1=$2' . $link [1] . '://' . $link [3] . '$3$4$5', $data );
    if ($status ['http_code'] == 200) {
      return $data;
    } elseif ($status ['http_code'] == 301 || $status ['http_code'] == 302) {
      if (! $follow_allowed) {
        if (empty ( $redirURL )) {
          if (! empty ( $status ['redirect_url'] )) {
            $redirURL = $status ['redirect_url'];
          }
        }
        if (empty ( $redirURL )) {
          preg_match ( '/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m );
          if (! empty ( $m [2] )) {
            $redirURL = $m [2];
          }
        }
        if (empty ( $redirURL )) {
          preg_match ( '/href\=\"(.*?)\"(.*?)here\<\/a\>/si', $data, $m );
          if (! empty ( $m [1] )) {
            $redirURL = $m [1];
          }
        }
        if (! empty ( $redirURL )) {
          $t = debug_backtrace ();
          return call_user_func ( $t [0] ["function"], trim ( $redirURL ), $post_paramtrs );
        }
      }
    }
    return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:" . json_encode ( $status ) . "<br/><br/>Last data got<br/>:$data";
  }

  function curl_get_file_contents($url) {
    $ch = curl_init();
    if (startswith($url, "https")) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $contents = curl_exec($ch);
    curl_close($ch);

    if ($contents) return $contents;
        else return FALSE;
  }

  function curl_getcontents($url, $proxy='', $referer='', $timeout=3, $header=1, $agent='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($proxy) {
      curl_setopt($ch, CURLOPT_PROXY, $proxy);
      curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
    }
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $contents = curl_exec($ch);
//    $result['INF'] = curl_getinfo($ch);
//    $result['ERR'] = curl_error($ch);

//    printbreak(curl_getinfo($ch));
//    printbreak(curl_error($ch));

    curl_close($ch);

    return $contents;
  }

  function curl_contents($url, $proxy_address='', $proxy_user='', $proxy_password='', $proxy_port='') {

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL,$url);
//    curl_setopt($ch,CURLOPT_POST, count($fields));
//    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    if ($proxy_address) {
      curl_setopt($ch,CURLOPT_HTTPPROXYTUNNEL, 1);
      if ($proxy_port) {
        curl_setopt($ch,CURLOPT_PROXY, "$proxy_address:$proxy_port");
      } else {
        curl_setopt($ch,CURLOPT_PROXY, "$proxy_address");
      }
      if ($proxy_user) {
        curl_setopt($ch,CURLOPT_PROXYUSERPWD, "$proxy_user:$proxy_password");
      }
    }

    //execute post
    $contents = curl_exec($ch);
//    printhtml($contents);
    //close connection
    curl_close($ch);

    if ($contents) {
      return $contents;
    } else {
      return FALSE;
    }

  }

  function do_post_request($url, $data, $optional_headers = null) {
    $params = array('http' => array(
                        'method' => 'POST',
                        'content' => $data
                      )
                   );
    if ($optional_headers !== null) {
      $params['http']['header'] = $optional_headers;
    }
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rt', false, $ctx);

    if (!$fp) {
      throw new Exception("Problem with $url, $php_errormsg");
    }
    $response = @stream_get_contents($fp);
    if ($response === false) {
      throw new Exception("Problem reading data from $url, $php_errormsg");
    }
    return $response;
  }


  function ping($host, $port, $timeout) {
    $tB = microtime(true);
    $fP = fsockopen($host, $port, $errno, $errstr, $timeout);
    if (!$fP) { return "down"; }
    $tA = microtime(true);
    return round((($tA - $tB) * 1000), 0)." ms";
  }

  function download_remote_file_with_curl($file_url, $save_to) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch,CURLOPT_URL,$file_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $file_content = curl_exec($ch);
    curl_close($ch);

    $downloaded_file = fopen($save_to, 'w');
    fwrite($downloaded_file, $file_content);
    fclose($downloaded_file);

  }

  function get_web_page( $url ) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => true,    // return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    //$header['errno']   = $err;
   // $header['errmsg']  = $errmsg;
    //$header['content'] = $content;
    //print($header[0]);
    return $header;
  }

  function get_redirected_url($URL) {
    $ch = curl_init($URL);

    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);


    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

    curl_exec($ch);

    $code = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

    curl_close($ch);

    return $code;
  }

  function get_domain($url) {
    $urlData = parse_url($url);
    $host = $urlData['host'];

    return $host;
  }

  function curl_save_image($url,$saveto){
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'User-agent: Mozilla/5.0 (Windows NT 5.1; rv:13.0) Gecko/20100101 Firefox/13.0 FirePHP/0.7.1'
    ));
    if (startswith($url, "https")) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }

    $raw=curl_exec($ch);
    curl_close ($ch);
    if(file_exists($saveto)){
        unlink($saveto);
    }
    $fp = fopen($saveto,'x');
    fwrite($fp, $raw);
    fclose($fp);
  }

?>