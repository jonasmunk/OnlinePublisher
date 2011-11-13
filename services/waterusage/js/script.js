hui.ui.listen({
	
	
	$ready : function() {
		hui.require([hui.ui.context+'hui/lib/json2.js',hui.ui.context+'hui/lib/date.js',hui.ui.context+'hui/js/Input.js'],this._initialize.bind(this));
	},
	_initialize : function() {
		this.latest = hui.get('latest');
		this._latestDefault = hui.dom.getText(this.latest);
		this.number = new hui.ui.Input({element:'number',name:'number',validator:this._validateNumber});
		this.date = new hui.ui.Input({element:'date',validator:this._validateDate});
		this.value = new hui.ui.Input({element:'value',validator:this._validateNumber});
		this.submit = new hui.ui.Input({element:'submit',name:'submit'});
		this._reset();
	},
	_validateDate : {
		_dateFormats : ['d-m-Y','d/m-Y','d/m/Y','d-m-Y H:i:s','d/m-Y H:i:s','d/m/Y H:i:s','d-m-Y H:i','d/m-Y H:i','d/m/Y H:i','d-m-Y H','d/m-Y H','d/m/Y H','d-m','d/m','d','Y','m-d-Y','m-d','m/d'],
		validate : function(value) {
			for (var i=0; i < this._dateFormats.length; i++) {
				var fmt = this._dateFormats[i];
				var parsed = Date.parseDate(value,fmt);
				
				if (parsed) {
					return {valid:true,value:parsed.dateFormat('d-m-Y')};
				}
			};
			return {valid:false,value:''};
		}
	},
	_validateNumber : {
		validate : function(value) {
			var valid = false;
			if (value) {
				var pattern = /[0-9]+/g;
				var matches = value.match(pattern);
				if (matches) {
					value = matches.join('');
				} else {
					value = '';
					valid = false;
				}			
			}
			return {valid:true,value:value}
		}
	},
	_pendingNumber : null,
	_busy : false,
	$valueChanged$number : function(value) {
		if (this._busy) {
			this._pendingNumber = value;
			hui.log('pending: '+value);
			return;
		}
		this._busy = true;
		hui.dom.setText(this.latest,'Henter...');
		hui.log('Fetching: '+value)
		hui.ui.request({
			url : op.context+'services/waterusage/latest.php',
			parameters : {number:value},
			onJSON : function(obj) {
				this._busy = false;
				if (this._pendingNumber) {
					hui.log('pending number exists: '+value);
					this.$valueChanged$number(this._pendingNumber);
					this._pendingNumber = null;
				} else {
					this._updateLatest(obj);
				}
			}.bind(this)
		})
	},
	_updateLatest : function(obj) {
		if (obj.found) {
			hui.log('Found: '+obj.value);
			hui.dom.setText(this.latest,obj.value+' ('+hui.date.format('d-m-Y',new Date(obj.date*1000))+')');
		} else {
			hui.log('Npt found');
			hui.dom.setText(this.latest,'Ikke fundet');
		}
	},
	_check : function() {
		var number = this.number.getValue(),
			date = this.date.getValue(),
			value = this.value.getValue(),
			valid = true;
		if (number.length<8) {
			this.number.setError('Skal være mindst 8 cifre');
			valid = false;
		} else {
			this.number.setError()
		}
		if (hui.isBlank(date)) {
			this.date.setError('Skal udfyldes');
			valid = false;
		} else {
			this.date.setError();
		}
		if (hui.isBlank(value)) {
			this.value.setError('Skal udfyldes');
			valid = false;
		} else {
			this.value.setError();
		}
		return valid;
	},
	_sending : false,
	$click$submit : function(e) {
		hui.stop(e);
		if (this._sending) {
			return;
		}
		if (!this._check()) {
			return;
		}
		var params = {
			number : this.number.getValue(),
			date : this.date.getValue(),
			value : this.value.getValue()
		}
		this._sending = true;
		hui.ui.request({
			message : {start:'Sender aflæsning...'},
			url : op.context+'services/waterusage/register.php',
			parameters : params,
			onFailure : function(obj) {
				hui.ui.showMessage({text:'Der skete en fejl i systemet, aflæsningen er måske ikke registreret',icon:'common/warning',duration:4000})
				this._sending = false;
			},
			onJSON : function(obj) {
				if (obj.success) {
					hui.ui.showMessage({text:'Aflæsningen er registreret',icon:'common/success',duration:4000})
					window.setTimeout(function() {
						document.location = op.context+'services/waterusage/receipt.php?id='+obj.id;
					},1000)
				}
				this._reset();
			}.bind(this),
			onException : function(obj) {
				hui.log(obj);
				hui.ui.showMessage({text:'Der skete en fejl i systemet, aflæsningen er måske ikke registreret',icon:'common/warning',duration:4000})
				this._sending = false;
			}
		});
	},
	_reset : function() {
		this._sending = false;
		hui.dom.setText(this.latest,this._latestDefault);
		this.number.setValue();
		this.date.setValue(hui.date.format('d-m-Y',new Date()));
		this.value.setValue();
	}
})







hui.isNumeric


hui.date = {};

hui.date.format = function(format, timestamp) {
    var that = this,
        jsdate, f, formatChr = /\\?([a-z])/gi,
        formatChrCb,
        // Keep this here (works, but for code commented-out
        // below for file size reasons)
        //, tal= [],
        _pad = function (n, c) {
            if ((n = n + '').length < c) {
                return new Array((++c) - n.length).join('0') + n;
            }
            return n;
        },
        txt_words = ["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    formatChrCb = function (t, s) {
        return f[t] ? f[t]() : s;
    };
    f = {
        // Day
        d: function () { // Day of month w/leading 0; 01..31
            return _pad(f.j(), 2);
        },
        D: function () { // Shorthand day name; Mon...Sun
            return f.l().slice(0, 3);
        },
        j: function () { // Day of month; 1..31
            return jsdate.getDate();
        },
        l: function () { // Full day name; Monday...Sunday
            return txt_words[f.w()] + 'day';
        },
        N: function () { // ISO-8601 day of week; 1[Mon]..7[Sun]
            return f.w() || 7;
        },
        S: function () { // Ordinal suffix for day of month; st, nd, rd, th
            var j = f.j();
            return j < 4 | j > 20 && ['st', 'nd', 'rd'][j%10 - 1] || 'th'; 
        },
        w: function () { // Day of week; 0[Sun]..6[Sat]
            return jsdate.getDay();
        },
        z: function () { // Day of year; 0..365
            var a = new Date(f.Y(), f.n() - 1, f.j()),
                b = new Date(f.Y(), 0, 1);
            return Math.round((a - b) / 864e5) + 1;
        },

        // Week
        W: function () { // ISO-8601 week number
            var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3),
                b = new Date(a.getFullYear(), 0, 4);
            return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
        },

        // Month
        F: function () { // Full month name; January...December
            return txt_words[6 + f.n()];
        },
        m: function () { // Month w/leading 0; 01...12
            return _pad(f.n(), 2);
        },
        M: function () { // Shorthand month name; Jan...Dec
            return f.F().slice(0, 3);
        },
        n: function () { // Month; 1...12
            return jsdate.getMonth() + 1;
        },
        t: function () { // Days in month; 28...31
            return (new Date(f.Y(), f.n(), 0)).getDate();
        },

        // Year
        L: function () { // Is leap year?; 0 or 1
            var j = f.Y();
            return j%4==0 & j%100!=0 | j%400==0;
        },
        o: function () { // ISO-8601 year
            var n = f.n(),
                W = f.W(),
                Y = f.Y();
            return Y + (n === 12 && W < 9 ? -1 : n === 1 && W > 9);
        },
        Y: function () { // Full year; e.g. 1980...2010
            return jsdate.getFullYear();
        },
        y: function () { // Last two digits of year; 00...99
            return (f.Y() + "").slice(-2);
        },

        // Time
        a: function () { // am or pm
            return jsdate.getHours() > 11 ? "pm" : "am";
        },
        A: function () { // AM or PM
            return f.a().toUpperCase();
        },
        B: function () { // Swatch Internet time; 000..999
            var H = jsdate.getUTCHours() * 36e2,
                // Hours
                i = jsdate.getUTCMinutes() * 60,
                // Minutes
                s = jsdate.getUTCSeconds(); // Seconds
            return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
        },
        g: function () { // 12-Hours; 1..12
            return f.G() % 12 || 12;
        },
        G: function () { // 24-Hours; 0..23
            return jsdate.getHours();
        },
        h: function () { // 12-Hours w/leading 0; 01..12
            return _pad(f.g(), 2);
        },
        H: function () { // 24-Hours w/leading 0; 00..23
            return _pad(f.G(), 2);
        },
        i: function () { // Minutes w/leading 0; 00..59
            return _pad(jsdate.getMinutes(), 2);
        },
        s: function () { // Seconds w/leading 0; 00..59
            return _pad(jsdate.getSeconds(), 2);
        },
        u: function () { // Microseconds; 000000-999000
            return _pad(jsdate.getMilliseconds() * 1000, 6);
        },

        // Timezone
        e: function () { // Timezone identifier; e.g. Atlantic/Azores, ...
            // The following works, but requires inclusion of the very large
            // timezone_abbreviations_list() function.
/*              return this.date_default_timezone_get();
*/
            throw 'Not supported (see source code of date() for timezone on how to add support)';
        },
        I: function () { // DST observed?; 0 or 1
            // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
            // If they are not equal, then DST is observed.
            var a = new Date(f.Y(), 0),
                // Jan 1
                c = Date.UTC(f.Y(), 0),
                // Jan 1 UTC
                b = new Date(f.Y(), 6),
                // Jul 1
                d = Date.UTC(f.Y(), 6); // Jul 1 UTC
            return 0 + ((a - c) !== (b - d));
        },
        O: function () { // Difference to GMT in hour format; e.g. +0200
            var tzo = jsdate.getTimezoneOffset(),
                a = Math.abs(tzo);
            return (tzo > 0 ? "-" : "+") + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
        },
        P: function () { // Difference to GMT w/colon; e.g. +02:00
            var O = f.O();
            return (O.substr(0, 3) + ":" + O.substr(3, 2));
        },
        T: function () { // Timezone abbreviation; e.g. EST, MDT, ...
            // The following works, but requires inclusion of the very
            // large timezone_abbreviations_list() function.
/*              var abbr = '', i = 0, os = 0, default = 0;
            if (!tal.length) {
                tal = that.timezone_abbreviations_list();
            }
            if (that.php_js && that.php_js.default_timezone) {
                default = that.php_js.default_timezone;
                for (abbr in tal) {
                    for (i=0; i < tal[abbr].length; i++) {
                        if (tal[abbr][i].timezone_id === default) {
                            return abbr.toUpperCase();
                        }
                    }
                }
            }
            for (abbr in tal) {
                for (i = 0; i < tal[abbr].length; i++) {
                    os = -jsdate.getTimezoneOffset() * 60;
                    if (tal[abbr][i].offset === os) {
                        return abbr.toUpperCase();
                    }
                }
            }
*/
            return 'UTC';
        },
        Z: function () { // Timezone offset in seconds (-43200...50400)
            return -jsdate.getTimezoneOffset() * 60;
        },

        // Full Date/Time
        c: function () { // ISO-8601 date.
            return 'Y-m-d\\Th:i:sP'.replace(formatChr, formatChrCb);
        },
        r: function () { // RFC 2822
            return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
        },
        U: function () { // Seconds since UNIX epoch
            return jsdate / 1000 | 0;
        }
    };
    this.date = function (format, timestamp) {
        that = this;
        jsdate = (timestamp == null ? new Date() : // Not provided
        (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
        new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
        );
        return format.replace(formatChr, formatChrCb);
    };
    return this.date(format, timestamp);
}





