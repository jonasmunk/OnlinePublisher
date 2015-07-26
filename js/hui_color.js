/** @constructor
 * @param str The color like red or rgb(255, 0, 0) or #ff0000 or rgb(100%, 0%, 0%)
 */
hui.Color = function(str) {
    this.ok = false;
  if (hui.isBlank(str)) {
    return;
  }
    // strip any leading #
    if (str.charAt(0) == '#') { // remove # if any
        str = str.substr(1,6);
    }

    str = str.replace(/ /g,'');
    str = str.toLowerCase();
    
    for (var key in hui.Color.table) {
        if (str == key) {
            str = hui.Color.table[key];
        }
    }
    // emd of simple type-in colors

    // array of color definition objects
    var color_defs = [
        {
            re: /^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/,
            process: function (bits){
                return [
                    parseInt(bits[1]),
                    parseInt(bits[2]),
                    parseInt(bits[3])
                ];
            }
        },
        {
            re: /^rgb\((\d{1,3})%,\s*(\d{1,3})%,\s*(\d{1,3})%\)$/ ,
            process: function (bits){
                return [
                    Math.round(parseInt(bits[1])/100*255),
                    Math.round(parseInt(bits[2])/100*255),
                    Math.round(parseInt(bits[3])/100*255)
                ];
            }
        },
        {
            re: /^(\w{2})(\w{2})(\w{2})$/,
            process: function (bits){
                return [
                    parseInt(bits[1], 16),
                    parseInt(bits[2], 16),
                    parseInt(bits[3], 16)
                ];
            }
        },
        {
            re: /^(\w{1})(\w{1})(\w{1})$/,
            process: function (bits){
                return [
                    parseInt(bits[1] + bits[1], 16),
                    parseInt(bits[2] + bits[2], 16),
                    parseInt(bits[3] + bits[3], 16)
                ];
            }
        }
    ];

    // search through the definitions to find a match
    for (var i = 0; i < color_defs.length; i++) {
        var re = color_defs[i].re,
      processor = color_defs[i].process,
      bits = re.exec(str);
        if (bits) {
            var channels = processor(bits);
            this.r = channels[0];
            this.g = channels[1];
            this.b = channels[2];
            this.ok = true;
      break;
        }
    }

    // validate/cleanup values
    this.r = (this.r < 0 || isNaN(this.r)) ? 0 : ((this.r > 255) ? 255 : this.r);
    this.g = (this.g < 0 || isNaN(this.g)) ? 0 : ((this.g > 255) ? 255 : this.g);
    this.b = (this.b < 0 || isNaN(this.b)) ? 0 : ((this.b > 255) ? 255 : this.b);
};

hui.Color.prototype = {
  /** Get the color as rgb(255,0,0) */
  toRGB : function () {
        return 'rgb(' + this.r + ', ' + this.g + ', ' + this.b + ')';
    },
  isDefined : function() {
    return !(this.r===undefined || this.g===undefined || this.b===undefined);
  },
  /** Get the color as #ff0000 */
  toHex : function() {
    if (!this.isDefined()) {return null;}
        var r = this.r.toString(16);
        var g = this.g.toString(16);
        var b = this.b.toString(16);
        if (r.length == 1) {
      r = '0' + r;
    }
        if (g.length == 1) {
      g = '0' + g;
    }
        if (b.length == 1) {
      b = '0' + b;
    }
        return '#' + r + g + b;
  }
};

hui.Color.table = {
  white : 'ffffff',
  black : '000000',
  red : 'ff0000',
  green : '00ff00',
  blue : '0000ff'
};

hui.Color.hex2rgb = function(hex) {
  if (hui.isBlank(hex)) {
    return null;
  }
  if (hex[0]=="#") {
    hex=hex.substr(1);
  }
  if (hex.length==3) {
    var temp=hex;
    hex='';
    temp = /^([a-f0-9])([a-f0-9])([a-f0-9])$/i.exec(temp).slice(1);
    for (var i=0;i<3;i++) {
      hex+=temp[i]+temp[i];
    }
  }
  var triplets = /^([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i.exec(hex).slice(1);
  return {
    r:   parseInt(triplets[0],16),
    g: parseInt(triplets[1],16),
    b:  parseInt(triplets[2],16)
  };
};

hui.Color.hsv2rgb = function (Hdeg,S,V) {
  var H = Hdeg/360,R,G,B;     // convert from degrees to 0 to 1
  if (S===0) {       // HSV values = From 0 to 1
    R = V*255;     // RGB results = From 0 to 255
    G = V*255;
    B = V*255;
  } else {
    var h = H*6,
    var_r,var_g,var_b;
    var i = Math.floor( h );
    var var_1 = V*(1-S);
    var var_2 = V*(1-S*(h-i));
    var var_3 = V*(1-S*(1-(h-i)));
    if (i===0) {
      var_r=V ;
      var_g=var_3;
      var_b=var_1;
    }
    else if (i===1) {
      var_r=var_2;
      var_g=V;
      var_b=var_1;
    }
    else if (i==2) {
      var_r=var_1;
      var_g=V;
      var_b=var_3;
    }
    else if (i==3) {
      var_r=var_1;
      var_g=var_2;
      var_b=V;
    }
    else if (i==4) {
      var_r=var_3;
      var_g=var_1;
      var_b=V;
    }
    else {
      var_r=V;
      var_g=var_1;
      var_b=var_2;
    }
    R = Math.round(var_r*255);   //RGB results = From 0 to 255
    G = Math.round(var_g*255);
    B = Math.round(var_b*255);
  }
  return new Array(R,G,B);
};

hui.Color.rgb2hsv = function(r, g, b) {

  r = (r / 255);
  g = (g / 255);
  b = (b / 255);  

  var min = Math.min(Math.min(r, g), b),
    max = Math.max(Math.max(r, g), b),
    value = max,
    saturation,
    hue;

  // Hue
  if (max == min) {
    hue = 0;
  } else if (max == r) {
    hue = (60 * ((g-b) / (max-min))) % 360;
  } else if (max == g) {
    hue = 60 * ((b-r) / (max-min)) + 120;
  } else if (max == b) {
    hue = 60 * ((r-g) / (max-min)) + 240;
  }

  if (hue < 0) {
    hue += 360;
  }

  // Saturation
  if (max === 0) {
    saturation = 0;
  } else {
    saturation = 1 - (min/max);
  }

  return [Math.round(hue), Math.round(saturation * 100), Math.round(value * 100)];
};

hui.Color.rgb2hex = function(rgbary) {
  var c = '#';
  for (var i=0; i < 3; i++) {
    var str = parseInt(rgbary[i]).toString(16);
    if (str.length < 2) {
      str = '0'+str;
    }
    c+=str;
  }
  return c;
};