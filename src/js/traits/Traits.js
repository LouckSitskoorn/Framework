function Trait (methods) {
  this.traits = [methods];
};

Trait.prototype = {
    constructor: Trait

  , uses: function (trait) {
      this.traits = this.traits.concat (trait.traits);
      return this;
    }

  , useBy: function (obj) {
      for (var i = 0; i < this.traits.length; ++i) {
        var methods = this.traits [i];
        for (var prop in methods) {
          if (methods.hasOwnProperty (prop)) {
            obj [prop] = obj [prop] || methods [prop];
          }
        }
      }
    }
};

Trait.unimplemented = function (obj, traitName) {
  if (obj === undefined || traitName === undefined) {
    throw new Error ("Unimplemented trait property.");
  }
  throw new Error (traitName + " is not implemented for " + obj);
};


/* //EXAMPLE
  var TEq = new Trait ({
      equalTo: function (x) {
        Trait.unimplemented (this, "equalTo");
      }

    , notEqualTo: function (x) {
        return !this.equalTo (x);
      }
  });

  var TOrd = new Trait ({
      lessThan: function (x) {
        Trait.unimplemented (this, "lessThan");
      }

    , greaterThan: function (x) {
        return !this.lessThanOrEqualTo (x);
      }

    , lessThanOrEqualTo: function (x) {
        return this.lessThan (x) || this.equalTo (x);
      }

    , greaterThanOrEqualTo: function (x) {
        return !this.lessThan (x);
      }
  }).uses (TEq);


  function Rational (numerator, denominator) {
    if (denominator < 0) {
      numerator *= -1;
      denominator *= -1;
    }
    this.numerator = numerator;
    this.denominator = denominator;
  }

  Rational.prototype = {
      constructor: Rational

    , equalTo: function (q) {
        return this.numerator * q.numerator === this.denominator * q.denominator;
      }

    , lessThan: function (q) {
        return this.numerator * q.denominator < q.numerator * this.denominator;
      }
  };

  TOrd.useBy (Rational.prototype);

  var x = new Rational (1, 5);
  var y = new Rational (1, 2);

  [x.notEqualTo (y), x.lessThan (y)]; // [true, true]
*/