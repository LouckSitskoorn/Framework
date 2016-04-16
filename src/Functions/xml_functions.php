<?php
  //NAMESPACE
  namespace SB\Functions;

  /* Find the first element in the document (sub)tree whose id attribute
   has the value $id. By default, the entire document is searched - pass
   a non-NULL value in $node to search only the subtree below $node.
  */
  function getElementById(DOMDocument $doc, /*string*/ $id, DOMNode $node = NULL) {
    if ($node === NULL) return getElementById($doc, $id, $doc->documentElement);

    $children = $node->childNodes;
    for ($i = 0; $i < $children->length; ++$i) {
      $elem = $children->item($i);
      if (!($elem instanceof DOMElement)) continue;
      if ($elem->getAttribute('id') == $id) return $elem;
      $ret = getElementById($doc, $id, $elem);
      if ($ret !== NULL) return $ret;
    }

    return NULL;
  }

  /* Check if $node has REAL childnodes
   * bijvoorbeeld: <tr><td><b>Louck</b></td></tr>
   * tr en td hebben REAL childnodes, b niet
  */
  function hasChildNodes(DOMNode $node) {
    $children = $node->childNodes;
    for ($i = 0; $i < $children->length; ++$i) {
      $elem = $children->item($i);
      if ($elem instanceof DOMElement) return true;
    }

    return false;
  }

  function generate_xml_from_array($array, $node_name, $linebreak="\n") {
  	$xml = '';

  	if (is_array($array) || is_object($array)) {
  		foreach ($array as $key=>$value) {
  			if (is_numeric($key)) {
  				$key = $node_name;
  			}

  			$xml .= '<' . $key . '>' . $linebreak . generate_xml_from_array($value, $node_name, $linebreak) . '</' . $key . '>' . $linebreak;
  		}
  	} else {
  		$xml = htmlspecialchars($array, ENT_QUOTES) . $linebreak;
  	}

  	return $xml;
  }

  function generate_valid_xml_from_array($array, $node_block='nodes', $node_name='node' ,$linebreak="\n") {
  	$xml = '<?xml version="1.0" encoding="UTF-8" ?>' . $linebreak;

  	$xml .= '<' . $node_block . '>' . $linebreak;
  	$xml .= generate_xml_from_array($array, $node_name, $linebreak);
  	$xml .= '</' . $node_block . '>' . $linebreak;

  	return $xml;
  }

  function generate_encoded_xml($string) {
    $string = str_ireplace("&nbsp;", " ", $string);
    $string = preg_replace("/&(?!(amp;|nbsp;|quot;|apos;|lt;|gt;))/i", "&amp;", $string);

    return $string;
  }

  function getXPathValue($xmlobject, $xpathstring) {
    $value      = NULL;
    $valuearray = [];

    if ($xmlobject instanceof SimpleXMLElement) {
      $valuearray = $xmlobject->xpath($xpathstring);

      if (is_array($valuearray)
      && !is_empty($valuearray)) {
        $value = (string)$valuearray[0];
      }
    }

    return $value;
  }


  function is_valid_xml($xml) {
    libxml_use_internal_errors(true);

    $sxe = simplexml_load_string($xml);

    if (!$sxe) {
      libxml_clear_errors();
      libxml_use_internal_errors(false);

      return false;
    } else {
      return true;
    }
  }

  function is_xml_string($string, $roottag="xml") {
    preg_match_all('/<' . $roottag . '>.*<\/' . $roottag . '>/i', $string, $result);

    if (is_array($result)
    &&  is_array($result[0])
    && !is_empty($result[0])) {
      return true;
    } else {
      return false;
    }
  }
?>