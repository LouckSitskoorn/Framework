function ping(ip, timeout, callback) {
    /*
    if (!this.inUse) {
        this.status = 'unchecked';
        this.inUse = true;
        this.callback = callback;
        this.ip = ip;
        var _that = this;
        this.img = new Image();
        this.img.onload = function () {
            _that.inUse = false;
            _that.callback('responded');

        };
        this.img.onerror = function (e) {
            if (_that.inUse) {
                _that.inUse = false;
                _that.callback('responded', e);
            }

        };
        this.start = new Date().getTime();
        this.img.src = "http://" + ip;
        this.timer = setTimeout(function () {
            if (_that.inUse) {
                _that.inUse = false;
                _that.callback('timeout');
            }
        }, timeout);
    }
    */

    $.ajax({url: "http://" + ip,
      type: "HEAD",
      timeout:timeout,
      statusCode: {
        200: function (response, e) {
          callback('responded', e);
        },
        400: function (response, e) {
          callback('timeout', e);
        },
        0: function (response, e) {
          callback('strange', e);
        }
      }
    });
}
