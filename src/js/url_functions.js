//FUNCTION url_question_ampersand
url_question_ampersand = function url_question_ampersand(url) {
  var questionampersand = '';

  if (url.indexOf('?') == -1) {
    questionampersand='?';
   } else {
    questionampersand='&';
  }

  return questionampersand;
};


//FUNCTION url_query
url_query = function url_query(mixed, separator) {
  separator   = typeof separator=='undefined' ? '?' : separator;

  var returnvalue = separator;

  if (is_string(mixed)) {
    mixed = strip_prefix(mixed, '?');
    mixed = strip_prefix(mixed, '&');
    mixedarray = mixed.split(';');
    for (i = 0; i < mixedarray.length; ++i) {
      returnvalue = separator + mixedarray[i];
      separator   = '&';
    }
  } else if (is_array(mixed)) {
    for (i = 0; i < mixed.length; ++i) {
      returnvalue += separator + 'item' + i + '=' + mixed[i];
      separator = '&';
    };
  } else if (is_object(mixed)) {
    for (var property in mixed) {
      if (object.hasOwnProperty(property)) {
        returnvalue += separator + property + i + '=' + mixed[property];
        separator = '&';
      }
    };
  } else {
    returnvalue = '';
  }

  return returnvalue;
};


//FUNCTION url_query_data
url_query_data = function url_query_data(mixed) {
  var urlquery = url_query(mixed, '');
  var urlquerydata  = {};
  var urlqueryarray = urlquery.split('&');

  for (var i=0; i < urlqueryarray.length; i++) {
    if (urlqueryarray[i].contains('=')) {
      urlquerydata[urlqueryarray[i].leftpart('=')]  = urlqueryarray[i].rightpart('=');
    }
  }

  return urlquerydata;
};
