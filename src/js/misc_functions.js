//GLOBAL variables
if (typeof window.FALSE == 'undefined')  {window.FALSE  = false;};
if (typeof window.TRUE == 'undefined')   {window.TRUE   = true;};
if (typeof window.NULL == 'undefined')   {window.NULL   = null;};

//FUNCTION is_numeric
is_numeric = function(mixed_var) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: David
  // +   improved by: taith
  // +   bugfixed by: Tim de Koning
  // *     example 1: is_numeric(186.31);
  // *     returns 1: true
  // *     example 2: is_numeric('Kevin van Zonneveld');
  // *     returns 2: false
  // *     example 3: is_numeric('+186.31e2');
  // *     returns 3: true
  // *     example 4: is_numeric('');
  // *     returns 4: false

  if (mixed_var === '') {
    return false;
  }

  return !isNaN(mixed_var * 1);
};


//FUNCTION is_number
is_number = function(mixed_var){
  //return typeof mixed_var == 'number' || !isNaN(parseFloat(mixed_var));
  return typeof mixed_var == 'number' && !isNaN(parseFloat(mixed_var));
};


//FUNCTION is_null
is_null = function(mixed_var){
  // Returns true if variable is null
  //
  // version: 810.114
  // discuss at: http://phpjs.org/functions/is_null
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // *     example 1: is_null('23');
  // *     returns 1: false
  // *     example 2: is_null(null);
  // *     returns 2: true
    return (mixed_var === null);
};


//FUNCTION is_string
is_string = function is_string(s){
  return (typeof(s) != 'undefined') ? (typeof(s) === 'string' || s instanceof String) : false;
};


//FUNCTION is_object
is_object = function is_object(mixed_var) {
  return (typeof mixed_var =='object');
};


//FUNCTION is_function
is_function = function is_function(mixed_var) {
  return (typeof mixed_var =='function');
};


//FUNCTION is_true
is_true = function is_true(mixed_var) {
  if (is_string(mixed_var)) {
    return comparetext(mixed_var, 'true') || comparetext(mixed_var, '1') || comparetext(mixed_var, 'yes') || comparetext(mixed_var, 'on');
  } else if (is_number(mixed_var)) {
    return mixed_var != 0;
  } else if (is_bool(mixed_var)) {
    return mixed_var === true;
  } else {
    return false;
  }
};


//FUNCTION is_false
is_false = function is_false(mixed_var) {
  if (is_string(mixed_var)) {
    return comparetext(mixed_var, 'false') || comparetext(mixed_var, '0') || comparetext(mixed_var, 'no')  || comparetext(mixed_var, 'off');
  } else if (is_number(mixed_var)) {
    return mixed_var == 0;
  } else if (is_bool(mixed_var)) {
    return mixed_var === false;
  } else {
    return false;
  }
};


//FUNCTION is_nan
is_nan = function is_nan(mixed_var) {
  return isNaN(mixed_var);
};

//FUNCTION is_undefined
is_undefined = function is_undefined(mixed_var) {
  return (typeof mixed_var == 'undefined');
};


//FUNCTION is_empty
is_emptytext = function is_emptytext(mixed_var, trimtext) {
  trimtext = typeof trimtext == 'undefined' ? false : trimtext;

  if (typeof mixed_var == 'string') {
    if (trimtext) {
      return mixed_var.trim() === '';
      } else {
      return mixed_var === '';
    }
  } else {
    return false;
  }
};


//FUNCTION is_empty
is_empty = function is_empty(mixed_var) {
  var name;

  if (typeof mixed_var == 'undefined') {
    //undefined = empty
    return true;

  } else if (typeof mixed_var == 'object' && mixed_var instanceof Object) {
    if (is_null(mixed_var)) {
      //null = empty
      return true;
    } else if (is_array(mixed_var)) {
      //[] = empty
      return mixed_var.length == 0;
    } else {
      //{} = empty
      for (name in mixed_var ) {
        return false;
      }
      return true;
    }

  } else if (typeof mixed_var == 'string') {
    //"", "null", "NULL" = empty
    mixed_var = mixed_var.trim();

    if (mixed_var != 'null'
    &&  mixed_var != 'NULL'
    &&  mixed_var != '{null}'
    &&  mixed_var != 'false'
    &&  mixed_var != 'FALSE'
    &&  mixed_var != 'NaN'
    &&  mixed_var != 'undefined'
    &&  mixed_var != ''
    &&  mixed_var != '<xml></xml>'
    &&  mixed_var != '<xml><record></record></xml>') {
    //&&  mixed_var != '-1'
        return false;
    } else {
      return true;
    }

  } else if (typeof mixed_var == 'number') {
    //0 = empty
    if (isNaN(mixed_var)) {
      return true;
    } else if (mixed_var != -1
           &&  mixed_var != 0) {
      return false;
    } else {
      return true;
    }

  } else if (typeof mixed_var == 'boolean') {
    //false = empty
    return !mixed_var;

  } else if (typeof mixed_var == 'function') {
    //false = empty
    return false;

  } else {
    return true;
  }
};


//FUNCTION is_bool
is_bool = function(mixed_var) {
  if (typeof mixed_var == 'undefined') {
    return false;

  } else if (typeof mixed_var == 'object') {
    return false;

  } else if (typeof mixed_var == 'string') {
    if (mixed_var.toLowerCase() == 'true'
    ||  mixed_var.toLowerCase() == 'false'
    ||  mixed_var == '1'
    ||  mixed_var == '0') {
      return true;
    } else {
      return false;
    }

  } else if (typeof mixed_var == 'number') {
    if (mixed_var == 1
    ||  mixed_var == 0) {
      return true;
    } else {
      return false;
    }

  } else if (typeof mixed_var == 'boolean') {
    return true;
  } else {
    return false;
  }
};


//FUNCTION is_tempid
is_tempid = function is_tempid(s){
  if (is_string(s)) {
    if (s.slice(0, 5) == 'zzzzz') {
      return true;
    } else {
      return false;
    }
  } else {
    return is_empty(s);
  }
};


//FUNCTION compare_null
compare_null = function(var1, var2) {
  if (     (var1 == null     && var2 == null)
        || (var1 == null     && var2 == 'null')
        || (var1 == null     && var2 == '{null}')
        || (var1 == 'null'   && var2 == null)
        || (var1 == 'null'   && var2 == 'null')
        || (var1 == 'null'   && var2 == '{null}')
        || (var1 == '{null}' && var2 == null)
        || (var1 == '{null}' && var2 == 'null')
        || (var1 == '{null}' && var2 == '{null}')
  ) {
    return true;
  } else {
    return false;
  }
};


//FUNCTION compare_empty
compare_empty = function(var1, var2) {
  if (     (var1 == null              && var2 == null)
        || (var1 == null              && var2 == '')
        || (var1 == null              && var2 == 'null')
        || (var1 == null              && var2 == 'NULL')
        || (var1 == null              && var2 == '{null}')
        || (var1 == null              && var2 == -1)
        || (var1 == null              && var2 == '-1')
        || (var1 == null              && typeof var2 == 'undefined')
        || (var1 == 'null'            && var2 == null)
        || (var1 == 'null'            && var2 == '')
        || (var1 == 'null'            && var2 == 'null')
        || (var1 == 'null'            && var2 == 'NULL')
        || (var1 == 'null'            && var2 == '{null}')
        || (var1 == 'null'            && var2 == -1)
        || (var1 == 'null'            && var2 == '-1')
        || (var1 == 'null'            && typeof var2 == 'undefined')
        || (var1 == 'NULL'            && var2 == null)
        || (var1 == 'NULL'            && var2 == '')
        || (var1 == 'NULL'            && var2 == 'null')
        || (var1 == 'NULL'            && var2 == 'NULL')
        || (var1 == 'NULL'            && var2 == '{null}')
        || (var1 == 'NULL'            && var2 == -1)
        || (var1 == 'NULL'            && var2 == '-1')
        || (var1 == 'NULL'            && typeof var2 == 'undefined')
        || (var1 == '{null}'          && var2 == null)
        || (var1 == '{null}'          && var2 == '')
        || (var1 == '{null}'          && var2 == 'null')
        || (var1 == '{null}'          && var2 == 'NULL')
        || (var1 == '{null}'          && var2 == '{null}')
        || (var1 == '{null}'          && var2 == -1)
        || (var1 == '{null}'          && var2 == '-1')
        || (var1 == '{null}'          && typeof var2 == 'undefined')
        || (var1 == ''                && var2 == null)
        || (var1 == ''                && var2 == '')
        || (var1 == ''                && var2 == 'null')
        || (var1 == ''                && var2 == 'NULL')
        || (var1 == ''                && var2 == '{null}')
        || (var1 == ''                && var2 == -1)
        || (var1 == ''                && var2 == '-1')
        || (var1 == ''                && typeof var2 == 'undefined')
        || (var1 == '-1'              && var2 == null)
        || (var1 == '-1'              && var2 == '')
        || (var1 == '-1'              && var2 == 'null')
        || (var1 == '-1'              && var2 == 'NULL')
        || (var1 == '-1'              && var2 == '{null}')
        || (var1 == '-1'              && var2 == -1)
        || (var1 == '-1'              && var2 == '-1')
        || (var1 == '-1'              && typeof var2 == 'undefined')
        || (var1 == -1                && var2 == null)
        || (var1 == -1                && var2 == '')
        || (var1 == -1                && var2 == 'null')
        || (var1 == -1                && var2 == 'NULL')
        || (var1 == -1                && var2 == '{null}')
        || (var1 == -1                && var2 == -1)
        || (var1 == -1                && var2 == '-1')
        || (var1 == -1                && typeof var2 == 'undefined')
        || (typeof var1=='undefined'  && typeof var2=='undefined')
        || (typeof var1=='undefined'  && var2 == null)
        || (typeof var1=='undefined'  && var2 == '')
        || (typeof var1=='undefined'  && var2 == 'null')
        || (typeof var1=='undefined'  && var2 == 'NULL')
        || (typeof var1=='undefined'  && var2 == '{null}')
        || (typeof var1=='undefined'  && var2 == '-1')
  ) {
    return true;
  } else {
    return false;
  }
};

random =  function random(max) {
  anynumber = 1+ parseInt(100000000*Math.random()) % max;
  return(anynumber);
};

//FUNCTION Random10
Random10 = function() {
   return (Math.random()*10).toString().substring(0,1);
};


//FUNCTION S1
S1 = function S1() {
   return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
};


//function S4
S4 = function S4() {
   return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
};


//FUNCTION uuid
uuid = function uuid() {
   return (S4()+S4()+S4()+S4()+S4()+S4()+S4()+S4());
};


//FUNCTION guid
//guid = function() {
//   return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
//};


//FUNCTION guid
guid = function guid() {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
    return v.toString(16);
  });
};


//FUNCTION timerstart
timerStart = function timerStart() {
  return new Date().valueOf();
};


//FUNCTION timerend
timerEnd = function(timerstart) {
  return new Date().valueOf() - timerstart;
};


//FUNCTION timerendstring
timerEndString = function timerEndString(timerstart) {
  var timerend = new Date().valueOf();
  var duration = duration2String(timerend - timerstart);

  return duration;
};


//FUNCTION timerendstring
timerEndAlert = function timerEndAlert(timerstart) {
  var timerend = new Date().valueOf();
  var duration = duration2String(timerend - timerstart);

  alert(duration);

  return duration;
};


duration2Short = function duration2Short(duration) {
  var sReturn, nDur;
  var nDur = new Date(duration);

  sReturn = nDur.getMinutes() + ':' + nDur.getSeconds() + ':' + nDur.getMilliseconds();

  return sReturn;
};


//FUNCTION duration2String
duration2String = function duration2String(nMS) {
  var sReturn, sComp, nDur;

  nDur = nMS % 1000;
  sComp = nDur.toString();
  while (sComp.length<3)
    sComp = "0" + sComp;
  sReturn = "." + sComp + " seconds";

  // Strip off last component
  nMS -= nDur;
  nMS /= 1000;

  nDur = nMS % 60;
  if (nDur)
    sReturn = nDur.toString() + sReturn;
  else
    sReturn = "0" + sReturn;

  // Strip off last component
  nMS -= nDur;
  nMS /= 60;

  nDur = nMS % 60;
  if (nDur > 0) {
    sReturn = nDur.toString() + " minutes, and " + sReturn;
  }

  // Strip off last component
  nMS -= nDur;
  nMS /= 60;

  if (nMS > 0) {
    sReturn = nMS.toString() + " hours, " + sReturn;
  }

  return sReturn;
};


//FUNCTION coalesce
//TODO: oneindig aantal arguments kunnen meegeven
coalesce = function coalesce(object, defaultobject) {
  //if (object==null
  //||  typeof(object) == 'undefined') {
  if (is_empty(object)) {
    return defaultobject;
  } else {
    return object;
  }
};


//FUNCTION objectToString
objectToString = function objectToString(object) {
  if (typeof(object) == 'undefined'
	||  is_null(object)) {
    return '';
  } else {
    return object.toString();
  }
};


//FUNCTION valueOrBool
valueOrBool = function valueOrBool(value) {
  var returnvalue = value;

  if (typeof value == 'string') {
    if (value.toLowerCase() == 'true') {
      returnvalue = true;
    } else if (value.toLowerCase() == 'false') {
      returnvalue = false;
    } else {
      switch (value.toLowerCase()) {
        case "true":
        case "yes":
        case "1":
          returnvalue = true;
        case "false":
        case "no":
        case "0":
          returnvalue = false;
        default:
          returnvalue = value;
      }
    }
  } else if (typeof value == 'number') {
    if (value == 0) {
      returnvalue =  false;
    } else {
      returnvalue =  true;
    }
  } else if (typeof value == 'boolean') {
    returnvalue = value;
  }

  return returnvalue;
};


//FUNCTION valueOrFloat
valueOrFloat = function valueOrFloat(value, floatval) {
  var returnvalue = false;

  if (!isNaN(parseFloat(value))) {
    returnvalue = parseFloat(value);
  } else {
    returnvalue = floatval;
  }

  if (returnvalue === true) {returnvalue = 1;}

  return returnvalue;
};


//FUNCTION valueToString
valueToString = function valueToString(value) {
  var returnvalue = '';

  switch (typeof value) {
    case 'string'   :
      returnvalue = value;
      break;

    case 'number'   :
    case 'boolean'  :
      returnvalue = value.toString();
      break;

    case 'object'   :
      if (is_array(value)) {
        returnvalue = value.toString();
      } else if (is_null(value)) {
        returnvalue = '';
      } else {
        returnvalue = value.toString();
      }

      break;
  }

  return returnvalue;
};


//FUNCTION urlencode
urlencode = function urlencode(str) {
  return escape(str).replace('+', '%2B').replace('%20', '+').replace('*', '%2A').replace('/', '%2F').replace('@', '%40');
};


//FUNCTION urldecode
urldecode = function urldecode(str) {
  return unescape(str.replace('+', ' '));
};


//FUNCTION cursorSet
cursorSet = function cursorSet(str) {
  document.body.style.cursor = str;
};


//FUNCTION cursorClear
cursorClear = function cursorClear() {
  document.body.style.cursor = 'default';
};


//FUNCTION waitDialogShow
waitDialogShow = function waitDialogShow(text, title, modal) {
  //log
  //console_log('waitDialogShow', (arguments.callee.caller) ? arguments.callee.caller : 'root', title);

  //parameters
  text                  = (typeof text  == 'undefined' || is_empty(text))   ? 'Moment a.u.b.' : text;
  title                 = (typeof title == 'undefined' || is_empty(title))  ? 'Ogenblikje geduld' : title;
  modal                 = (typeof modal == 'undefined' || is_empty(modal))  ? false : modal;

  //create stack?
  if (typeof window['waitdialogstack'] == 'undefined') {
    window['waitdialogstack'] = 0;
  }

  window['waitdialogstack']++;

  if (typeof $.fn.dialog != 'undefined') {
    window['waitdialog' + window['waitdialogstack']]  = $('<div id="waitdialog'+window['waitdialogstack']+'" class="sb-wait-dialog" ><div class="sb-wait-dialog-content" >' + text + '</div></div>').dialog({
        autoOpen  : true
      , modal     : modal
      , resizable : false
      , title     : title || 'Ogenblikje geduld'
      , open      : function() {
          $('#waitdialog' + window['waitdialogstack']).spin({position: 'absolute', top: '75%', lines: 9, length: 4, width: 6, radius: 8 });
        }
      , close     : function() {
          $('#waitdialog' + window['waitdialogstack']).spin(false);
        }
    });
  }
};


//FUNCTION waitDialogHide
waitDialogHide = function waitDialogHide() {
  //log
  //console_log('waitDialogHide', (arguments.callee.caller) ? arguments.callee.caller : 'root');

  //create stack?
  if (typeof window['waitdialogstack'] == 'undefined') {
    window['waitdialogstack'] = 0;
  }

  if (typeof $.fn.dialog != 'undefined') {
    if (typeof window['waitdialog'+ window['waitdialogstack']] != 'undefined') {
      if (window['waitdialog'+ window['waitdialogstack']].data('ui-dialog')) {
        window['waitdialog'+ window['waitdialogstack']].dialog('destroy');
        window['waitdialog'+ window['waitdialogstack']] = undefined;

        window['waitdialogstack']--;
      }
    }
  }
};


//FUNCTION getInternetExplorerVersion
getInternetExplorerVersion = function getInternetExplorerVersion()
// Returns the version of Internet Explorer or a -1
// (indicating the use of another browser).
{
  var rv = -1; // Return value assumes failure.
  if (navigator.appName == 'Microsoft Internet Explorer')
  {
    var ua = navigator.userAgent;
    var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
      rv = parseFloat( RegExp.$1 );
  }
  return rv;
};


//FUNCTION checkVersion
var checkVersion = function checkVersion() {
  var msg = "You're not using Internet Explorer.";
  var ver = getInternetExplorerVersion();

  if ( ver > -1 )
  {
    if ( ver >= 8.0 )
      msg = "You're using a recent copy of Internet Explorer.";
    else
      msg = "You should upgrade your copy of Internet Explorer.";
  }
  alert( msg );
};


//FUNCTION wrap
var wrap = function wrap(functionToWrap, before, after, thisObject) {
    return function () {
        var args = Array.prototype.slice.call(arguments),
            result;
        if (before) before.apply(thisObject || this, args);
        result = functionToWrap.apply(thisObject || this, args);
        if (after) after.apply(thisObject || this, args);
        return result;
    };
};


//FUNCTION pause
pause = function pause(millis) {
  var date = new Date();
  var curDate = null;
  do { curDate = new Date(); }
  while(curDate-date < millis);
};


//FUNCTION twoDigits
twoDigits = function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
};


isMobile = {
    Android: function() {
    return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
    return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
    return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
    return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};


alertObject = function alertObject(obj){
  for(var key in obj) {
    alert('key: ' + key + '\n' + 'value: ' + obj[key]);
    if( typeof obj[key] === 'object' ) {
        alertObject(obj[key]);
    }
  }
};


sleep = function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
};

die = function die(msg) {
  throw new Error(msg);
};

