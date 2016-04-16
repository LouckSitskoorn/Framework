//GLOBAL VARS
var tagsToReplace = {
   '&': '&amp;',
   '<': '&lt;',
   '>': '&gt;'
};


//PROTOTYPES
if (!String.prototype.trim)   {String.prototype.trim  = function() { return this.replace(/^\s*([\S\s]*?)\s*$/, "$1"); };}
if (!String.prototype.ltrim)  {String.prototype.ltrim = function() { return this.replace(/^ */,""); };}
if (!String.prototype.rtrim)  {String.prototype.rtrim = function() { return this.replace(/ *$/,""); };}

if (!String.prototype.toUpperCaseFirst) {String.prototype.toUpperCaseFirst = function() {return this.charAt(0).toUpperCase() + this.substr(1);};}
if (!String.prototype.toLowerCaseFirst) {String.prototype.toLowerCaseFirst = function() {return this.charAt(0).toLowerCase() + this.substr(1);};}
if (!String.prototype.toUpperCaseFirstAll) {
  String.prototype.toUpperCaseFirstAll = function() {
    return (this + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
  };
}

if (!String.prototype.manipulateCase) {
  String.prototype.manipulateCase = function(manipulator) {
    manipulator = manipulator.toLowerCase();

    if (manipulator!='' && typeof manipulator != 'undefined') {
//      alert(this, manipulator);
    }

    if (manipulator=='' || typeof manipulator == 'undefined') {
      return this;
    } else if (manipulator.contains('upfirstall')) {
      return this.toUpperCaseFirstAll();
    } else if (manipulator.contains('upfirst')) {
      return this.toUpperCaseFirst();
    } else if (manipulator.contains('up')) {
      return this.toUpperCase();
    } else if (manipulator.contains('low')) {
      return this.toLowerCase();
    } else {
      return this;
    }
  };
}


if (!String.prototype.csvcontains)   {
  String.prototype.csvcontains = function(str) {
    if (this.split(',').indexOf(str) == -1) {
      return false;
    } else {
      return true;
    }
  };
};


if (!String.prototype.contains) {
  String.prototype.contains = function(substr) {
    return (this.indexOf(substr) != -1);
  };
};


if (!String.prototype.left) {
  String.prototype.left  = function(n){
    if (n <= 0)
      return '';
    else if (n > this.length)
      return str;
    else
      return this.substring(0, n);
  };
};


if (!String.prototype.right) {
  String.prototype.right = function(n){
    if (n <= 0)
      return '';
    else if (n > this.length)
      return str;
    else {
      var len = this.length;
      return this.substring(len, len - n);
    }
  };
};

if (!String.prototype.leftpart) {
  String.prototype.leftpart = function(delim){
    var splitarray = this.split(delim);
    if (typeof splitarray[0] != 'undefined') {
      return splitarray[0];
    } else {
      return '';
    }
  };
};

if (!String.prototype.rightpart) {
  String.prototype.rightpart = function(delim){
    var splitarray = this.split(delim);
    if (typeof splitarray[1] != 'undefined') {
      //return splitarray[1];
      return splitarray.slice(1).join(delim);
    } else {
      return '';
    }
  };
};


if (!String.prototype.betweenpart) {
  String.prototype.betweenpart = function(delimleft,delimright) {
    return this.substring(this.lastIndexOf(delimleft) + delimleft.length, this.lastIndexOf(delimright));
  };
}


if (!String.prototype.replaceAll) {
  String.prototype.replaceAll = function(target, replacement) {
    return this.split(target).join(replacement);
  };
};

if (!String.prototype.toBoolean) {
  String.prototype.toBoolean = function(defaultvalue){
  	switch (this.toLowerCase()) {
  		case 'true'   :
  		case 'yes'    :
      case 'on'     :
  		case '1'      :
  			return true;
  			break;

  	  case ''      :
  		case 'false'  :
  		case 'no'     :
      case 'off'    :
  		case '0'      :
  		case 'null'   :
      case '{null}' :
  			return false;
  			break;

  		default:
  			return defaultvalue;
  			break;
  	}
  };
};

if (!String.prototype.encodeHTML) {
  String.prototype.encodeHTML = function () {
    return this.replace(/</g, '&lt;')
               .replace(/>/g, '&gt;');

    //return this.replace(/&/g, '&amp;')
    //           .replace(/</g, '&lt;')
    //           .replace(/>/g, '&gt;')
    //           .replace(/"/g, '&quot;');
  };
};

/*
if (!String.prototype.key) {
  String.prototype.key = function() {
    return (this.indexOf('=') >= 0) ? this.split('=')[0] : this;
  };
};
*/

/*
if (!String.prototype.value) {
  String.prototype.value = function() {
    return (this.indexOf('=') >= 0) ? this.split('=')[1] : this;
  };
};
*/

if (typeof String.prototype.startsWith != 'function') {
  String.prototype.startsWith = function (str){
    return this.lastIndexOf(str, 0) === 0;
  };
}

if (typeof String.prototype.endsWith != 'function') {
  String.prototype.endsWith = function(suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
  };
};

if (typeof String.prototype.decodeHTML != 'function') {
  String.prototype.decodeHTML = function() {
      var map = {   'gt':'>'
                  , 'lt':'<'
                };

      return this.replace(/&(#(?:x[0-9a-f]+|\d+)|[a-z]+);?/gi, function($0, $1) {
          if ($1[0] === "#") {
              return String.fromCharCode($1[1].toLowerCase() === "x" ? parseInt($1.substr(2), 16)  : parseInt($1.substr(1), 10));
          } else {
              return map.hasOwnProperty($1) ? map[$1] : $0;
          }
      });
  };
};

if (typeof String.prototype.escapeSpecialChars != 'function') {
 String.prototype.escapeSpecialChars = function() {
    return this.replace(/\\n/g, "\\n")
               .replace(/\\'/g, "\\'")
               .replace(/\\"/g, '\\"')
               .replace(/\\&/g, "\\&")
               .replace(/\\r/g, "\\r")
               .replace(/\\t/g, "\\t")
               .replace(/\\b/g, "\\b")
               .replace(/\\f/g, "\\f");
  };
};

if (!String.prototype.toDate) {
  String.prototype.toDate = function() {
    // Split timestamp into [ Y, M, D, h, m, s ]
    var t = this.split(/[- :]/);

    // Apply each element to the Date function
    var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

    return d;
  };
};


if (!String.prototype.singular) {
  String.prototype.singular = function() {
    var parts = [];
    if (this.indexOf('|') !== -1) {
      parts = this.split('|');
      return parts[0];
    } else {
      return this;
    }
  };
};

if (!String.prototype.plural) {
  String.prototype.plural = function() {
    var parts = [];
    if (this.indexOf('|') !== -1) {
      parts = this.split('|');
      if (parts.length > 1) {
        if (parts[1][0] == '-') {
          return parts[0] + parts[1].rightpart('-');
        } else {
          return parts[1];
        }
      } else {
        return this;
      }
    } else {
      return this;
    }
  };
};


//FUNCTION isVisibleStr
isVisibleStr = function(needle, haystack) {
  var returnvalue = false;
  var pos = (haystack.toUpperCase().indexOf(needle.toUpperCase()));
  if (pos >= 0) {
    if (pos > 0) {
      if (haystack.substring(pos-1, pos) != "!") {
        returnvalue = true;
      }
    } else {
      returnvalue = true;
    }
  }

  return returnvalue;
};


//FUNCTION isEnabledStr
isEnabledStr = function(needle, haystack) {
  var returnvalue = false;

  if (is_string(haystack)) {
    var pos = (haystack.toUpperCase().indexOf(needle.toUpperCase()));

    if (pos >= 0) {
      if (pos > 0) {
        if (haystack.substring(pos-1, pos) != "~"
        &&  haystack.substring(pos-1, pos) != "!") {
          returnvalue = true;
        }
      } else {
        returnvalue = true;
      }
    }
  }

  return returnvalue;
};


//FUNCTION basename
basename = function(path, suffix) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Ash Searle (http://hexmen.com/blog/)
  // +   improved by: Lincoln Ramsay
  // +   improved by: djmix
  // *     example 1: basename('/www/site/home.htm', '.htm');
  // *     returns 1: 'home'

  var b = path.replace(/^.*[\/\\]/g, '');

  if (typeof(suffix) == 'string' && b.substr(b.length-suffix.length) == suffix) {
    b = b.substr(0, b.length-suffix.length);
  }

  return b;
};


//FUNCTION comparetext
comparetext = function(string1, string2) {
  var string1 = objectToString(string1);
  var string2 = objectToString(string2);

  if (coalesce(string1,' ').toLowerCase().trim()==coalesce(string2,' ').toLowerCase().trim()) {
    return true;
  } else {
    return false;
  }
};


//FUNCTION addslashes
addslashes = function(str) {
  if(typeof str == 'string') {
    str=str.replace(/\\/g,'\\\\');
    str=str.replace(/\'/g,'\\\'');
    str=str.replace(/\"/g,'\\"');
    str=str.replace(/\0/g,'\\0');
    str=str.replace(/\{/g,'\\{');
    str=str.replace(/\}/g,'\\}');
    str=str.replace(/\:/g,'\\:');
    str=str.replace(/\&/g,'\\&');
  }
  return str;
};


//FUNCTION stripslashes
stripslashes = function(str) {
  if(typeof str == 'string') {
    str=str.replace(/\\'/g,'\'');
    str=str.replace(/\\"/g,'"');
    str=str.replace(/\\0/g,'\0');
    str=str.replace(/\\\\/g,'\\');
  }

  return str;
};


//FUNCTION inputlimit_numbers
inputlimit_numbers = function(myfield, e, dec) {
  var key;
  var keychar;

  if (window.event)
     key = window.event.keyCode;
  else if (e)
     key = e.which;
  else
     return true;
  keychar = String.fromCharCode(key);

  // control keys
  if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
     return true;

  // numbers
  else if ((("0123456789.,").indexOf(keychar) > -1))
     return true;
  else
     return false;
};


//FUNCTION inputlimit_integers
inputlimit_integers = function(myfield, e, dec) {
  var key;
  var keychar;

  if (window.event)
     key = window.event.keyCode;
  else if (e)
     key = e.which;
  else
     return true;
  keychar = String.fromCharCode(key);

  // control keys
  if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
     return true;

  // numbers
  else if ((("0123456789").indexOf(keychar) > -1))
     return true;
  else
     return false;
};


//FUNCTION replaceTag
replaceTag = function(tag) {
   return tagsToReplace[tag] || tag;
};


//FUNCTION safe_tags_replace
safe_tags_replace = function(str) {
   return (typeof str=='string') ? str.replace(/[&<>]/g, replaceTag) : str;
};


//FUNCTION strip_tags
striptags = function (str) {
  var tmp = document.createElement('DIV');
  tmp.innerHTML = str;

  return tmp.textContent || tmp.innerText;
};


//FUNCTION add_prefix
add_prefix = function add_prefix(str, prefix) {
  return prefix + strip_prefix(str, prefix);
};


//FUNCTION strip trailing
strip_trailing = function strip_trailing(str, prefix) {
  return strip_prefix(str, prefix);
};


//FUNCTION strip prefix
strip_prefix = function strip_prefix(str, prefix) {
  if (is_string(str)
  &&  is_string(prefix)
  &&  str.length >= prefix.length) {
    if (str.substr(0, prefix.length) == prefix) {
      return str.slice(prefix.length);
    }
  }

  return str;
};


//FUNCTION strip suffix
strip_suffix = function strip_suffix(str, suffix) {
  if (is_string(str)
  &&  str.length >= suffix.length) {
    if (str.substr(str.length - suffix.length, suffix.length) == suffix) {
      return str.slice(0, str.length - suffix.length);
    }
  }

  return str;
};


//FUNCTION striplastslash
striplastslash = function striplastslash(str) {
  return strip_suffix(str, '/');
};


//FUNCTION stripfirstslash
stripfirstslash = function stripfirstslash(str) {
  return strip_prefix(str, '/');
};


//FUNCTION unquote
unquote = function(str) {
  if (typeof str == 'string'
  &&  str.length > 0) {
    if ((str[0] == "'" && str[str.length-1] == "'")
    ||  (str[0] == '"' && str[str.length-1] == '"')) {
      return str.substring(1, myString.length()-1);
    } else {
      return str;
    }
  } else {
    return str;
  }
};


//FUNCTION quote
quote = function(str, quotestr) {
  quotestr = (typeof quotestr=='undefined') ? '"' : quotestr;

  if (typeof str == 'string'
  &&  str.length > 0) {
    if ((str[0] == "'" && str[str.length-1] == "'")
    ||  (str[0] == '"' && str[str.length-1] == '"')) {
      return str;
    } else {
      return quotestr + str + quotestr;
    }
  } else {
    return str;
  }
};


//FUNCTION serialize
serialize = function (mixed_value) {
  // http://kevin.vanzonneveld.net
  // +   original by: Arpad Ray (mailto:arpad@php.net)
  // +   improved by: Dino
  // +   bugfixed by: Andrej Pavlovic
  // +   bugfixed by: Garagoth
  // +      input by: DtTvB (http://dt.in.th/2008-09-16.string-length-in-bytes.html)
  // +   bugfixed by: Russell Walker (http://www.nbill.co.uk/)
  // +   bugfixed by: Jamie Beck (http://www.terabit.ca/)
  // +      input by: Martin (http://www.erlenwiese.de/)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
  // +   improved by: Le Torbi (http://www.letorbi.de/)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
  // +   bugfixed by: Ben (http://benblume.co.uk/)
  // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
  // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
  // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
  // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
  // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
  // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
  var val, key, okey,
    ktype = '', vals = '', count = 0,
    _utf8Size = function (str) {
      var size = 0,
        i = 0,
        l = str.length,
        code = '';
      for (i = 0; i < l; i++) {
        code = str.charCodeAt(i);
        if (code < 0x0080) {
          size += 1;
        }
        else if (code < 0x0800) {
          size += 2;
        }
        else {
          size += 3;
        }
      }
      return size;
    },
    _getType = function (inp) {
      var match, key, cons, types, type = typeof inp;

      if (type === 'object' && !inp) {
        return 'null';
      }
      if (type === 'object') {
        if (!inp.constructor) {
          return 'object';
        }
        cons = inp.constructor.toString();
        match = cons.match(/(\w+)\(/);
        if (match) {
          cons = match[1].toLowerCase();
        }
        types = ['boolean', 'number', 'string', 'array'];
        for (key in types) {
          if (cons == types[key]) {
            type = types[key];
            break;
          }
        }
      }
      return type;
    },
    type = _getType(mixed_value)
  ;

  switch (type) {
    case 'function':
      val = '';
      break;
    case 'boolean':
      val = 'b:' + (mixed_value ? '1' : '0');
      break;
    case 'number':
      val = (Math.round(mixed_value) == mixed_value ? 'i' : 'd') + ':' + mixed_value;
      break;
    case 'string':
      val = 's:' + _utf8Size(mixed_value) + ':"' + mixed_value + '"';
      break;
    case 'array': case 'object':
      val = 'a';
  /*
        if (type === 'object') {
          var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
          if (objname == undefined) {
            return;
          }
          objname[1] = this.serialize(objname[1]);
          val = 'O' + objname[1].substring(1, objname[1].length - 1);
        }
        */

      for (key in mixed_value) {
        if (mixed_value.hasOwnProperty(key)) {
          ktype = _getType(mixed_value[key]);
          if (ktype === 'function') {
            continue;
          }

          okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
          vals += this.serialize(okey) + this.serialize(mixed_value[key]);
          count++;
        }
      }
      val += ':' + count + ':{' + vals + '}';
      break;
    case 'undefined':
      // Fall-through
    default:
      // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
      val = 'N';
      break;
  }
  if (type !== 'object' && type !== 'array') {
    val += ';';
  }
  return val;
};


charstring = function(chr, num) {
  var str = '';
  for (var i=0;i< num;i++) {
    str += chr;
  }

  return str;
};


htmlEncode = function htmlEncode(value){
  //create a in-memory div, set it's inner text(which jQuery automatically encodes)
  //then grab the encoded contents back out.  The div never exists on the page.
  return $('<div/>').text(value).html();
};


htmlDecode = function htmlDecode(value){
  return $('<div/>').html(value).text();
};




deCase = function deCase(s) {
  return s.replace(/[A-Z]/g, function(a) {
    return '-' + a.toLowerCase();
  });
};


upfirst = function upfirst(string, lowrest) {
  if (is_string(string)) {
    if (lowrest) {
      string = string.toLowerCase();
    }

    return string.charAt(0).toUpperCase() + string.slice(1);
  } else {
    return string;
  }
};

lowfirst = function lowfirst(string, uprest) {
  if (is_string(string)) {
    if (uprest) {
      string = string.toUpperCase();
    }

    return string.charAt(0).toLowerCase() + string.slice(1);
  } else {
    return string;
  }
};

upfirstall = function upfirstall(string) {
    return string.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};

lowfirstall = function lowfirstall(string) {
    return string.replace(/\w\S*/g, function(txt){return txt.charAt(0).toLowerCase() + txt.substr(1).toLowerCase();});
};

is_email = function is_email(string) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(string);
};

var decodeEntities = (function() {
  // this prevents any overhead from creating the object each time
  var element = document.createElement('div');

  function decodeHTMLEntities (str) {
    if(str && typeof str === 'string') {
      // strip script/html tags
      str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
      str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
      element.innerHTML = str;
      str = element.textContent;
      element.textContent = '';
    }

    return str;
  }

  return decodeHTMLEntities;
})();



//FUNCTION strtobool
strtobool =  function strtobool(string, defaultvalue) {
  if (is_string(string)) {
    switch(string.toLowerCase()){
      case "true":
      case "yes":
      case "on":
      case "1":
        return true;
        break;

      case "false"  :
      case "no"     :
      case "0"      :
      case null     :
        return false;
        break;

      default       :
        return defaultvalue;
    }
  } else if (is_bool(string)) {
    return string;
  } else {
    return defaultvalue;
  }
};


//FUNCTION strtodate
strtodate =  function strtodate(string, defaultvalue) {
  if (is_string(string)) {
    // Split timestamp into [ Y, M, D, h, m, s ]
    var t = string.split(/[- :]/);

    // Apply each element to the Date function
    var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

    return d;
  }
};

//FUNCTION propfromstring
propfromstring =  function propfromstring(obj, string) {
  var arr           = string.split('.');
  var returnobject  = obj;

  for (i = 0; i < arr.length; i++) {
    if (returnobject[arr[i]]) {
      returnobject = returnobject[arr[i]];
    }
  }

  return returnobject;
};
