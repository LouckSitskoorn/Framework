//METHOD indexOf
if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function(o,v,n) {
    v = (v==null)?null:v;
    n = (n==null)?0:n;

    var m = this.length;
    for(var i = n; i < m; i++) {
      if (is_object(this[i])) {
        if (this[i][o]) {
          if (this[i][o] == v) {
            return i;
          }
        }
      } else if (is_string(this[i])
             ||  is_number(this[i])
             ||  is_bool(this[i])) {
        if(this[i] == o) {
           return i;
        }
      }
    }

    return -1;
  };
};


//METHOD indexOfObject
if (!Array.prototype.indexOfObject) {
  Array.prototype.indexOfObject = function(p,v,o) {
    o = (typeof o != 'undefined') ? o : '==';

    var m = this.length;
    for(var i = 0; i < m; i++) {
      switch (o) {
        case '='  :
        case '==' :
          if(this[i][p] == v) {
            return i;
          }
          break;
        case '>=' :
          if(this[i][p] >= v) {
            return i;
          }
          break;
        case '<=' :
          if(this[i][p] <= v) {
            return i;
          }
          break;
        case '>'  :
          if(this[i][p] > v) {
            return i;
          }
          break;
        case '<'  :
          if(this[i][p] < v) {
            return i;
          }
          break;
      }
    }

    return false;
  };
};


//METHOD objectOf
if (!Array.prototype.objectOf) {
  Array.prototype.objectOf = function(p,v,o) {
    o = (typeof o != 'undefined') ? o : '==';

    var m = this.length;
    for(var i = 0; i < m; i++) {
      switch (o) {
        case '='  :
        case '==' :
          if (this[i])
          if(this[i]
          && this[i][p] == v) {
            return this[i];
          }
          break;
        case '>=' :
          if(this[i]
          && this[i][p] >= v) {
            return this[i];
          }
          break;
        case '<=' :
          if(this[i]
          && this[i][p] <= v) {
            return this[i];
          }
          break;
        case '>'  :
          if(this[i]
          && this[i][p] > v) {
            return this[i];
          }
          break;
        case '<'  :
          if(this[i]
          && this[i][p] < v) {
            return this[i];
          }
          break;
      }
    }

    return false;
  };
};



//METHOD objectsOf
if (!Array.prototype.objectsOf) {
  Array.prototype.objectsOf = function(p,v,o) {
    o = (typeof o != 'undefined') ? o : '==';

    var a = [];

    for(var i=0; i < this.length; i++) {
      if (is_array(v)) {
        //check multiple possible values
        for(var i2=0; i2 < v.length; i2++) {
          if(this[i][p] == v[i2]) {
            a.push(this[i]);
          }
        }
      } else {
        //check single value
        switch (o) {
          case '='  :
          case '==' :
            if(this[i][p] == v) {
              a.push(this[i]);
            }
            break;
          case '>=' :
            if(this[i][p] >= v) {
              a.push(this[i]);
            }
            break;
          case '<=' :
            if(this[i][p] <= v) {
              a.push(this[i]);
            }
            break;
          case '>'  :
            if(this[i][p] > v) {
              a.push(this[i]);
            }
            break;
          case '<'  :
            if(this[i][p] < v) {
              a.push(this[i]);
            }
            break;
        }
      }
    }

    return a;
  };
};


//METHOD indexOfPart
if (!Array.prototype.indexOfPart) {
  Array.prototype.indexOfPart = function(v,n) {
    n = (n==null) ? 0 : n;

    var m = this.length;
    for(var i = n; i < m; i++)
      //if(this[i].search(/v/i) >= 0)
      if(this[i].search(v) >= 0)
         return i;
    return -1;
  };
};


//METHOD inArray
if (!Array.prototype.inArray) {
  Array.prototype.inArray = function(value){
    var i;
    for(i=0; i < this.length; i++){
      if(this[i] === value)
        return true;
    };
    return false;
  };
};


//METHOD unique
if (!Array.prototype.unique) {
  Array.prototype.unique = function() {
    var a = [];
    var l = this.length;
    for(var i=0; i<l; i++) {
      for(var j=i+1; j<l; j++) {
        // If this[i] is found later in the array
        if (this[i] === this[j])
          j = ++i;
      }
      a.push(this[i]);
    }
    return a;
  };
};

//METHOD isNull
if (!Array.prototype.isNull) {
  Array.prototype.isNull = function (){
      return this.join().replace(/,/g,'').length === 0;
  };
}

//METHOD clean
if (!Array.prototype.clean) {
  Array.prototype.clean = function(deleteValue) {
    for (var i = 0; i < this.length; i++) {
      if (this[i] == deleteValue) {
        this.splice(i, 1);
        i--;
      }
    }
    return this;
  };
}


//FUNCTION is_array
is_array = function(input) {
  return (typeof(input)=='object' && (input instanceof Array)) || (typeof(input)=='object' && !is_null(input) && typeof(input.length) == 'number');
};


//FUNCTION in_array
in_array = function(needle, haystack) {
  for (h in haystack) {
    if (haystack[h] == needle) {
      return h;
      // or if you prefer to get the key of the first found match use
      // return true;
    }
  }
  return false;
};


//METHOD remove
if (!Array.prototype.remove) {
  Array.prototype.remove = function(v) {
    var i = this.indexOf(v);

    if (i > -1) {
      array_splice(i,1);
      return true;
    } else {
      return false;
    }
  };
};

//METHOD removeObjectOf
if (!Array.prototype.removeObjectOf) {
  Array.prototype.removeObjectOf = function(p,v,n) {
    n = (n==null)?0:n;

    var m = this.length;
    for(var i = n; i < m; i++) {
      if(this[i][p] == v) {
        this.splice(i,1);

        return true;
      }
    }

    return false;
  };
};


//FUNCTION array_key_exists
array_key_exists = function( key, search ) {
    // Checks if the given key or index exists in the array
    //
    // version: 909.322
    // discuss at: http://phpjs.org/functions/array_key_exists    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Felix Geisendoerfer (http://www.debuggable.com/felix)
    // *     example 1: array_key_exists('kevin', {'kevin': 'van Zonneveld'});
    // *     returns 1: true
    // input sanitation
    if (!search || (search.constructor !== Array && search.constructor !== Object)){
        return false;
    }

    return key in search;
};


//FUNCTION array_object_search
array_object_search = function array_object_search(array, propertyname, propertyvalue, defaultvalue) {
    propertyvalue = (typeof propertyvalue=='undefined') ? propertyname : propertyvalue;
    defaultvalue  = (typeof defaultvalue=='undefined') ? false : defaultvalue;

    if (array instanceof Array
    ||  array instanceof Object) {
      for(var elem in array) {
        if (array[elem] instanceof Object
        &&  propertyname != null && propertyname != ''
        &&  array[elem].hasOwnProperty(propertyname)) {
          if (array[elem][propertyname] == propertyvalue) {
            return array[elem];
          }
        } else if (this[elem] instanceof String || typeof this[elem] == 'string') {
          if (elem == propertyvalue) {
            return array[elem];
          }
        } else if (this[elem] instanceof Number || typeof this[elem] == 'number') {
          if (elem == propertyvalue) {
            return array[elem];
          }
        }
      };
    }

    return defaultvalue;
};


//FUNCTION in_array_object
in_array_object =  function in_array_object(array, propertyname, propertyvalue) {
    return !is_false(array_object_search(array, propertyname, propertyvalue));
};


//FUNCTION serialize
serialize = function serialize( mixed_val ) {
  // Generates a storable representation of a value
  //
  // +   original by: Ates Goral (http://magnetiq.com)
  // +   adapted for IE: Ilia Kantor (http://javascript.ru)

  switch (typeof(mixed_val)){
    case "number":
      if (isNaN(mixed_val) || !isFinite(mixed_val)){
          return false;
      } else{
          return (Math.floor(mixed_val) == mixed_val ? "i" : "d") + ":" + mixed_val + ";";
      }

    case "string":
      return "s:" + mixed_val.length + ":\"" + mixed_val + "\";";

    case "boolean":
      return "b:" + (mixed_val ? "1" : "0") + ";";

    case "object":
      if (mixed_val == null) {
        return "N;";

      } else if (mixed_val instanceof Array) {
        var idxobj = { idx: -1 };
        var map = [];
        for(var i=0; i<mixed_val.length;i++) {
          idxobj.idx++;
          var ser = serialize(mixed_val[i]);

          if (ser) {
            map.push(serialize(idxobj.idx) + ser);
          }
        }

        return "a:" + mixed_val.length + ":{" + map.join("") + "}";

      } else {
        var class_name = typeof(mixed_val);

        if (class_name == undefined){
          return false;
        }

        var props = new Array();
        for (var prop in mixed_val) {
          var ser = serialize(mixed_val[prop]);

          if (ser) {
              props.push(serialize(prop) + ser);
          }
        }
        return "O:" + class_name.length + ":\"" + class_name + "\":" + props.length + ":{" + props.join("") + "}";
      }

    case "undefined":
        return "N;";
  }

  return false;
};