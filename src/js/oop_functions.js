/*
Object.prototype.propfromstring = function(string) {
  var arr           = string.split('.');
  var returnobject  = this;

  for (i = 0; i < arr.length; i++) {
    if (returnobject[arr[i]]) {
      returnobject = returnobject[arr[i]];
    }
  }

  return returnobject;
};
*/

/*
Object.prototype.clone = Array.prototype.clone = function() {
  if (Object.prototype.toString.call(this) === '[object Array]') {
    var clone = [];
    for (var i=0; i<this.length; i++)
        clone[i] = this[i].clone();

    return clone;
  } else if (typeof(this)=="object") {
    var clone = {};
    for (var prop in this)
        if (this.hasOwnProperty(prop))
            clone[prop] = this[prop].clone();

    return clone;
  } else {
    return this;
  }
};
*/