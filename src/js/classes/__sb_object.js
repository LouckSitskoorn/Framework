SB_Object = Object.extend(function() {
  this.constructor = function constructor(options, callbacksuccess, callbackerror) {
    //published properties
    this.id                   = '';
    this.Timing               = false;
    this.TimingLimit          = 0.1;

    //public properties
    this.Inited               = false;
    this.PutProperties        = true;   //array van propertynames die opgeslagen moeten worden (true = alle properties, false = geen enkele)

    //public properties
    this.element              = null;

    //read properties
    for (var i in options) {
      this[i] = options[i];
    }

    //element
    if (typeof this.id == 'string') {
      this.element  = $('#' + this.id);

      if (!this.element.length) {
        this.element = null;
      }
    }

    //callback
    if (callbacksuccess) {
      callbacksuccess();
    }
  };


  this.init = function init(callbacksuccess, callbackerror) {
    //log
    //console_log('SB_Object.init', (arguments.callee.caller) ? arguments.callee.caller : 'root');

    //callback
    if (callbacksuccess) {
      callbacksuccess();
    }
  };


  /*
  this.reinit = function reinit(callbacksuccess, callbackerror) {
    //log
    //console_log('SB_Object.reinit', (arguments.callee.caller) ? arguments.callee.caller : 'root');

    this.Inited = false;
    this.init(callbacksuccess, callbackerror);
  };
  */


  this.getProperties = function getProperties(putproperties) {
    if (typeof putproperties == 'undefined') {
      putproperties = this.PutProperties;
    }

    return getObjectProperties(this, putproperties);
  };


  this.setProperties = function setProperties(json, putproperties) {
    if (typeof putproperties == 'undefined') {
      putproperties = this.PutProperties;
    }

    return setObjectProperties(this, json, putproperties);
  };


  this.test = function test(testing) {
  };
});