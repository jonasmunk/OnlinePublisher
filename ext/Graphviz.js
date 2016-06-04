/** Grphviz viewer
 * @constructor
 */
hui.ui.Graphviz = function(element,name,options) {
		this.maxXdotVersion = 1.2;
		this.systemScale = 4/3;
		this.scale = 1;
		this.padding = 8;
		this.element = hui.get(element);
		this.texts = hui.get.firstByClass(this.element,'hui_graphviz_texts');
		this.canvas = hui.get.firstByTag(this.element,'canvas');
		this.ctx = this.canvas.getContext('2d');

		this.images = {};
		this.numImages = 0;
		this.numImagesFinished = 0;
		hui.ui.extend(this);
}

hui.ui.Graphviz.create = function(name,options) {
	var element = hui.build('div',{'class':'hui_graphviz'});
	var texts = hui.build('div',{'class':'hui_graphviz_texts',style:'position:relative;'});
	element.appendChild(texts);
	element.appendChild(hui.build('canvas'));
	return new hui.ui.Graphviz(element,name,options);
}

hui.ui.Graphviz.prototype = {
	setImagePath: function(imagePath) {
		this.imagePath = imagePath;
	},
	load: function(url) {
		var self = this;
		new hui.request({url:url,$success:function(t) {self.parse(t)}});
	},
	zoom : function(zoom) {
		this.scale=this.scale*zoom;
		this.draw();
	},
	parse: function(request) {
		this.xdotversion = false;
		this.commands = new Array();
		this.width = 0;
		this.height = 0;
		this.maxWidth = false;
		this.maxHeight = false;
		this.bbEnlarge = false;
		this.bbScale = 1;
		this.orientation = 'portrait';
		this.bgcolor = '#ffffff';
		this.dashLength = 6;
		this.dotSpacing = 4;
		this.fontName = 'Times New Roman';
		this.fontSize = 14;
		var graph_src = request.responseText;
		var lines = graph_src.split('\n');
		var i = 0;
		var line, lastchar, matches, is_graph, entity, params, param_name, param_value;
		var container_stack = new Array();
		while (i < lines.length) {
			line = lines[i++].replace(/^\s+/, '');
			if ('' != line && '#' != line.substr(0, 1)) {
				while (i < lines.length && ';' != (lastchar = line.substr(line.length - 1, line.length)) && '{' != lastchar && '}' != lastchar) {
					if ('\\' == lastchar) {
						line = line.substr(0, line.length - 1);
					}
					line += lines[i++];
				}
				// hui.ui.Graphviz.debug(line);
				matches = line.match(/^(.*?)\s*{$/);
				if (matches) {
					container_stack.push(matches[1]);
					// hui.ui.Graphviz.debug('begin container ' + container_stack.last());
				} else if ('}' == line) {
					// hui.ui.Graphviz.debug('end container ' + container_stack.last());
					container_stack.pop();
				} else {
					// matches = line.match(/^(".*?[^\\]"|\S+?)\s+\[(.+)\];$/);
					matches = line.match(/^(.*?)\s+\[(.+)\];$/);
					if (matches) {
						is_graph = ('graph' == matches[1]);
						// entity = this.unescape(matches[1]);
						entity = matches[1];
						params = matches[2];
						do {
							matches = params.match(/^(\S+?)=(""|".*?[^\\]"|<(<[^>]+>|[^<>]+?)+>|\S+?)(?:,\s*|$)/);
							if (matches) {
								params = params.substr(matches[0].length);
								param_name = matches[1];
								param_value = this.unescape(matches[2]);
// hui.ui.Graphviz.debug(param_name + ' ' + param_value);
								if (is_graph && 1 == container_stack.length) {
									switch (param_name) {
										case 'bb':
											var bb = param_value.split(/,/);
											this.width  = Number(bb[2]);
											this.height = Number(bb[3]);
											break;
										case 'bgcolor':
											this.bgcolor = this.parseColor(param_value);
											break;
										case 'size':
											var size = param_value.match(/^(\d+|\d*(?:\.\d+)),\s*(\d+|\d*(?:\.\d+))(!?)$/);
											if (size) {
												this.maxWidth  = 72 * Number(size[1]);
												this.maxHeight = 72 * Number(size[2]);
												this.bbEnlarge = ('!' == size[3]);
											} else {
												hui.ui.Graphviz.debug('can\'t parse size');
											}
											break;
										case 'orientation':
											if (param_value.match(/^l/i)) {
												this.orientation = 'landscape';
											}
											break;
										case 'rotate':
											if (90 == param_value) {
												this.orientation = 'landscape';
											}
											break;
										case 'xdotversion':
											this.xdotversion = parseFloat(param_value);
											if (this.maxXdotVersion < this.xdotversion) {
												hui.ui.Graphviz.debug('unsupported xdotversion ' + this.xdotversion + '; this script currently supports up to xdotversion ' + this.maxXdotVersion);
											}
											break;
									}
								}
								switch (param_name) {
									case '_draw_':
									case '_ldraw_':
									case '_hdraw_':
									case '_tdraw_':
									case '_hldraw_':
									case '_tldraw_':
//										hui.ui.Graphviz.debug(entity + ': ' + param_value);
										this.commands.push(param_value);
										break;
								}
							}
						} while (matches);
					}
				}
			}
		}
		if (!this.xdotversion) {
			this.xdotversion = 1.0;
		}
/*
		if (this.maxWidth && this.maxHeight) {
			if (this.width > this.maxWidth || this.height > this.maxHeight || this.bbEnlarge) {
				this.bbScale = Math.min(this.maxWidth / this.width, this.maxHeight / this.height);
				this.width  = Math.round(this.width  * this.bbScale);
				this.height = Math.round(this.height * this.bbScale);
			}
			if ('landscape' == this.orientation) {
				var temp    = this.width;
				this.width  = this.height;
				this.height = temp;
			}
		}
*/
//		hui.ui.Graphviz.debug('done');
		this.draw();
	},
	draw: function(redraw_canvas) {
		if (!redraw_canvas) redraw_canvas = false;
		var width  = Math.round(this.scale * this.systemScale * this.width  + 2 * this.padding);
		var height = Math.round(this.scale * this.systemScale * this.height + 2 * this.padding);
		if (!redraw_canvas) {
			this.canvas.width  = width;
			this.canvas.height = height;
			this.canvas.style.width=width+'px';
			this.canvas.style.height=height+'px';
			this.element.style.width=width+'px';
			this.texts.innerHTML = '';
		}
		this.ctx.save();
		this.ctx.lineCap = 'round';
		this.ctx.fillStyle = this.bgcolor;
		this.ctx.fillRect(0, 0, width, height);
		this.ctx.translate(this.padding, this.padding);
		this.ctx.scale(this.scale * this.systemScale, this.scale * this.systemScale);
		this.ctx.lineWidth = 1 / this.systemScale;
		var i, tokens;
		var entity_id = 0;
		var text_divs = '';
		for (var command_index = 0; command_index < this.commands.length; command_index++) {
			var command = this.commands[command_index];
//			hui.ui.Graphviz.debug(command);
			var tokenizer = new hui.ui.Graphviz.Tokenizer(command);
			var token = tokenizer.takeChars();
			if (token) {
				++entity_id;
				var entity_text_divs = '';
				this.dashStyle = 'solid';
				this.ctx.save();
				while (token) {
//					hui.ui.Graphviz.debug('processing token ' + token);
					switch (token) {
						case 'E': // filled ellipse
						case 'e': // unfilled ellipse
							var filled = ('E' == token);
							var cx = tokenizer.takeNumber();
							var cy = this.height - tokenizer.takeNumber();
							var rx = tokenizer.takeNumber();
							var ry = tokenizer.takeNumber();
							this.render(new Ellipse(cx, cy, rx, ry), filled);
							break;
						case 'P': // filled polygon
						case 'p': // unfilled polygon
						case 'L': // polyline
							var filled = ('P' == token);
							var closed = ('L' != token);
							var num_points = tokenizer.takeNumber();
							tokens = tokenizer.takeNumber(2 * num_points); // points
							var path = new Path();
							for (i = 2; i < 2 * num_points; i += 2) {
								path.addBezier([
									new Point(tokens[i - 2], this.height - tokens[i - 1]),
									new Point(tokens[i],     this.height - tokens[i + 1])
								]);
							}
							if (closed) {
								path.addBezier([
									new Point(tokens[2 * num_points - 2], this.height - tokens[2 * num_points - 1]),
									new Point(tokens[0],                  this.height - tokens[1])
								]);
							}
							this.render(path, filled);
							break;
						case 'B': // unfilled b-spline
						case 'b': // filled b-spline
							var filled = ('b' == token);
							var num_points = tokenizer.takeNumber();
							tokens = tokenizer.takeNumber(2 * num_points); // points
							var path = new Path();
							for (i = 2; i < 2 * num_points; i += 6) {
								path.addBezier([
									new Point(tokens[i - 2], this.height - tokens[i - 1]),
									new Point(tokens[i],     this.height - tokens[i + 1]),
									new Point(tokens[i + 2], this.height - tokens[i + 3]),
									new Point(tokens[i + 4], this.height - tokens[i + 5])
								]);
							}
							this.render(path, filled);
							break;
						case 'I': // image
							var x = tokenizer.takeNumber();
							var y = this.height - tokenizer.takeNumber();
							var w = tokenizer.takeNumber();
							var h = tokenizer.takeNumber();
							var src = tokenizer.takeString();
							if (!this.images[src]) {
								y -= h;
								this.images[src] = new hui.ui.Graphviz.Image(this, src, x, y, w, h);
							}
							this.images[src].draw();
							break;
						case 'T': // text
							var x = Math.round(this.scale * this.systemScale * tokenizer.takeNumber() + this.padding);
							var y = Math.round(height - (this.scale * this.systemScale * (tokenizer.takeNumber() + this.bbScale * this.fontSize) + this.padding));
							var text_align = tokenizer.takeNumber();
							var text_width = Math.round(this.scale * this.systemScale * tokenizer.takeNumber());
							var str = tokenizer.takeString();
							if (!redraw_canvas && !str.match(/^\s*$/)) {
//								hui.ui.Graphviz.debug('draw text ' + str + ' ' + x + ' ' + y + ' ' + text_align + ' ' + text_width);
								str = hui.string.escapeHTML(str);
								do {
									matches = str.match(/ ( +)/);
									if (matches) {
										var spaces = ' ';
										matches[1].length.times(function() {
											spaces += '&nbsp;';
										});
										str = str.replace(/  +/, spaces);
									}
								} while (matches);
								entity_text_divs += '<div style="position: absolute; font:' + Math.round(this.fontSize * this.scale * this.systemScale * this.bbScale) + 'px \'' + this.fontName +'\';color:' + this.ctx.strokeStyle + ';';
								switch (text_align) {
									case -1: //left
										entity_text_divs += 'left:' + x + 'px;';
										break;
									case 1: // right
										entity_text_divs += 'text-align:right;right:' + x + 'px;';
										break;
									case 0: // center
									default:
										entity_text_divs += 'text-align:center;left:' + (x - text_width) + 'px;';
										break;
								}
								entity_text_divs += 'top:' + y + 'px;width:' + (2 * text_width) + 'px">' + str + '</div>';
							}
							break;
						case 'C': // set fill color
						case 'c': // set pen color
							var fill = ('C' == token);
							var color = this.parseColor(tokenizer.takeString());
							if (fill) {
								this.ctx.fillStyle = color;
							} else {
								this.ctx.strokeStyle = color;
							}
							break;
						case 'F': // set font
							this.fontSize = tokenizer.takeNumber();
							this.fontName = tokenizer.takeString();
							switch (this.fontName) {
								case 'Times-Roman':
									this.fontName = 'Times New Roman';
									break;
								case 'Courier':
									this.fontName = 'Courier New';
									break;
								case 'Helvetica':
									this.fontName = 'Arial';
									break;
								default:
									// nothing
							}
//							hui.ui.Graphviz.debug('set font ' + this.fontSize + 'pt ' + this.fontName);
							break;
						case 'S': // set style
							var style = tokenizer.takeString();
							switch (style) {
								case 'solid':
								case 'filled':
									// nothing
									break;
								case 'dashed':
								case 'dotted':
									this.dashStyle = style;
									break;
								case 'bold':
									this.ctx.lineWidth = 2 / this.systemScale;
									break;
								default:
									matches = style.match(/^setlinewidth\((.*)\)$/);
									if (matches) {
										this.ctx.lineWidth = Number(matches[1]) / this.systemScale;
									} else {
										hui.ui.Graphviz.debug('unknown style ' + style);
									}
							}
							break;
						default:
							hui.ui.Graphviz.debug('unknown token ' + token);
							return;
					}
					token = tokenizer.takeChars();
				}
				this.ctx.restore();
				if (entity_text_divs) {
					text_divs += '<div id="entity' + entity_id + '">' + entity_text_divs + '</div>';
				}
			}
		};
		this.ctx.restore();
		if (!redraw_canvas) this.texts.innerHTML = text_divs;
	},
	render: function(path, filled) {
		if (filled) {
			this.ctx.beginPath();
			path.draw(this.ctx);
			this.ctx.fill();
		}
		if (this.ctx.fillStyle != this.ctx.strokeStyle || !filled) {
			switch (this.dashStyle) {
				case 'dashed':
					this.ctx.beginPath();
					path.drawDashed(this.ctx, this.dashLength);
					break;
				case 'dotted':
					var oldLineWidth = this.ctx.lineWidth;
					this.ctx.lineWidth *= 2;
					this.ctx.beginPath();
					path.drawDotted(this.ctx, this.dotSpacing);
					break;
				case 'solid':
				default:
					if (!filled) {
						this.ctx.beginPath();
						path.draw(this.ctx);
					}
			}
			this.ctx.stroke();
			if (oldLineWidth) this.ctx.lineWidth = oldLineWidth;
		}
	},
	unescape: function(str) {
		var matches = str.match(/^"(.*)"$/);
		if (matches) {
			return matches[1].replace(/\\"/g, '"');
		} else {
			return str;
		}
	},
	parseColor: function(color) {
		if (hui.ui.Graphviz.colors[color]) { // named color
			return 'rgb(' + hui.ui.Graphviz.colors[color][0] + ',' + hui.ui.Graphviz.colors[color][1] + ',' + hui.ui.Graphviz.colors[color][2] + ')';
		} else {
			var matches = color.match(/^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i);
			if (matches) { // rgba
				return 'rgba(' + parseInt(matches[1], 16) + ',' + parseInt(matches[2], 16) + ',' + parseInt(matches[3], 16) + ',' + (parseInt(matches[4], 16) / 255) + ')';
			} else {
				matches = color.match(/(\d+(?:\.\d+)?)\s+(\d+(?:\.\d+)?)\s+(\d+(?:\.\d+)?)/);
				if (matches) { // hsv
					return this.hsvToRgbColor(matches[1], matches[2], matches[3]);
				} else if (color.match(/^#[0-9a-f]{6}$/i)) {
					return color;
				}
			}
		}
		hui.ui.Graphviz.debug('unknown color ' + color);
		return '#000000';
	},
	hsvToRgbColor: function(h, s, v) {
		var i, f, p, q, t, r, g, b;
		h *= 360;
		i = Math.floor(h / 60) % 6;
		f = h / 60 - i;
		p = v * (1 - s);
		q = v * (1 - f * s);
		t = v * (1 - (1 - f) * s)
		switch (i) {
			case 0: r = v; g = t; b = p; break;
			case 1: r = q; g = v; b = p; break;
			case 2: r = p; g = v; b = t; break;
			case 3: r = p; g = q; b = v; break;
			case 4: r = t; g = p; b = v; break;
			case 5: r = v; g = p; b = q; break;
		}
		return 'rgb(' + Math.round(255 * r) + ',' + Math.round(255 * g) + ',' + Math.round(255 * b) + ')';
	}
}


hui.ui.Graphviz.Image = function(graph, src, x, y, w, h) {
		this.graph = graph;
		++this.graph.numImages;
		this.src = this.graph.imagePath + '/' + src;
		this.x = x;
		this.y = y;
		this.w = w;
		this.h = h;
		this.loaded = false;
		this.img = new Image();
		this.img.onload = this.succeeded.bind(this);
		this.img.onerror = this.finished.bind(this);
		this.img.onabort = this.finished.bind(this);
		this.img.src = this.src;
	}
	
hui.ui.Graphviz.Image.prototype = {
	succeeded: function() {
		this.loaded = true;
		this.finished();
	},
	finished: function() {
		++this.graph.numImagesFinished;
		if (this.graph.numImages == this.graph.numImagesFinished) {
			this.graph.draw(true);
		}
	},
	draw: function() {
		if (this.loaded) {
			this.graph.ctx.drawImage(this.img, this.x, this.y, this.w, this.h);
		}
	}
}

hui.ui.Graphviz.debug = function(str) {
	hui.log(str);
}


hui.ui.Graphviz.Tokenizer = function(str) {
		this.str = str;
}

hui.ui.Graphviz.Tokenizer.prototype = {
	takeChars: function(num) {
		if (!num) {
			num = 1;
		}
		var tokens = new Array();
		while (num--) {
			var matches = this.str.match(/^(\S+)\s*/);
			if (matches) {
				this.str = this.str.substr(matches[0].length);
				tokens.push(matches[1]);
			} else {
				tokens.push(false);
			}
		}
		if (1 == tokens.length) {
			return tokens[0];
		} else {
			return tokens;
		}
	},
	takeNumber: function(num) {
		if (!num) {
			num = 1;
		}
		if (1 == num) {
			return Number(this.takeChars())
		} else {
			var tokens = this.takeChars(num);
			while (num--) {
				tokens[num] = Number(tokens[num]);
			}
			return tokens;
		}
	},
	takeString: function() {
		var chars = Number(this.takeChars());
		if ('-' != this.str.charAt(0)) {
			return false;
		}
		var str = this.str.substr(1, chars);
		this.str = this.str.substr(1 + chars).replace(/^\s+/, '');
		return str;
	}
}

function Point(x, y) {
		this.x = x;
		this.y = y;
	}

Point.prototype = {
	offset: function(dx, dy) {
		this.x += dx;
		this.y += dy;
	},
	distanceFrom: function(point) {
		var dx = this.x - point.x;
		var dy = this.y - point.y;
		return Math.sqrt(dx * dx + dy * dy);
	},
	draw: function(ctx) {
		ctx.moveTo(this.x, this.y);
		ctx.lineTo(this.x + 0.001, this.y);
	}
}

function Bezier(points) {
		this.points = points;
		this.order = points.length;
}
Bezier.prototype = {
	reset: function() {
		with (Bezier.prototype) {
			this.controlPolygonLength = controlPolygonLength;
			this.chordLength = chordLength;
			this.triangle = triangle;
			this.chordPoints = chordPoints;
			this.coefficients = coefficients;
		}
	},
	offset: function(dx, dy) {
		this.points.each(function(point) {
			point.offset(dx, dy);
		});
		this.reset();
	},
	// Based on Oliver Steele's bezier.js library.
	controlPolygonLength: function() {
		var len = 0;
		for (var i = 1; i < this.order; ++i) {
			len += this.points[i - 1].distanceFrom(this.points[i]);
		}
		return (this.controlPolygonLength = function() {return len;})();
	},
	// Based on Oliver Steele's bezier.js library.
	chordLength: function() {
		var len = this.points[0].distanceFrom(this.points[this.order - 1]);
		return (this.chordLength = function() {return len;})();
	},
	// From Oliver Steele's bezier.js library.
	triangle: function() {
		var upper = this.points;
		var m = [upper]
		for (var i = 1; i < this.order; ++i) {
			var lower = [];
			for (var j = 0; j < this.order - i; ++j) {
				var c0 = upper[j];
				var c1 = upper[j + 1];
				lower[j] = new Point((c0.x + c1.x) / 2, (c0.y + c1.y) / 2);
			}
			m.push(lower);
			upper = lower;
		}
		return (this.triangle = function() {return m;})();
	},
	// Based on Oliver Steele's bezier.js library.
	triangleAtT: function(t) {
		var s = 1 - t;
		var upper = this.points;
		var m = [upper]
		for (var i = 1; i < this.order; ++i) {
			var lower = [];
			for (var j = 0; j < this.order - i; ++j) {
				var c0 = upper[j];
				var c1 = upper[j + 1];
				lower[j] = new Point(c0.x * s + c1.x * t, c0.y * s + c1.y * t);
			}
			m.push(lower);
			upper = lower;
		}
		return m;
	},
	// Returns two beziers resulting from splitting this bezier at t=0.5.
	// Based on Oliver Steele's bezier.js library.
	split: function(t) {
		if ('undefined' == typeof t) t = 0.5;
		var m = (0.5 == t) ? this.triangle() : this.triangleAtT(t);
		var leftPoints  = new Array(this.order);
		var rightPoints = new Array(this.order);
		for (var i = 0; i < this.order; ++i) {
			leftPoints[i]  = m[i][0];
			rightPoints[i] = m[this.order - 1 - i][i];
		}
		return {left: new Bezier(leftPoints), right: new Bezier(rightPoints)};
	},
	// Returns a bezier which is the portion of this bezier from t1 to t2.
	// Thanks to Peter Zin on comp.graphics.algorithms.
	mid: function(t1, t2) {
		return this.split(t2).left.split(t1 / t2).right;
	},
	// Returns points (and their corresponding times in the bezier) that form
	// an approximate polygonal representation of the bezier.
	// Based on the algorithm described in Jeremy Gibbons' dashed.ps.gz
	chordPoints: function() {
		var p = [{tStart: 0, tEnd: 0, dt: 0, p: this.points[0]}].concat(this._chordPoints(0, 1));
		return (this.chordPoints = function() {return p;})();
	},
	_chordPoints: function(tStart, tEnd) {
		var tolerance = 0.001;
		var dt = tEnd - tStart;
		if (this.controlPolygonLength() <= (1 + tolerance) * this.chordLength()) {
			return [{tStart: tStart, tEnd: tEnd, dt: dt, p: this.points[this.order - 1]}];
		} else {
			var tMid = tStart + dt / 2;
			var halves = this.split();
			return halves.left._chordPoints(tStart, tMid).concat(halves.right._chordPoints(tMid, tEnd));
		}
	},
	// Returns an array of times between 0 and 1 that mark the bezier evenly
	// in space.
	// Based in part on the algorithm described in Jeremy Gibbons' dashed.ps.gz
	markedEvery: function(distance, firstDistance) {
		var nextDistance = firstDistance || distance;
		var segments = this.chordPoints();
		var times = [];
		var t = 0; // time
		var dt; // delta t
		var segment;
		var remainingDistance;
		for (var i = 1; i < segments.length; ++i) {
			segment = segments[i];
			segment.length = segment.p.distanceFrom(segments[i - 1].p);
			if (0 == segment.length) {
				t += segment.dt;
			} else {
				dt = nextDistance / segment.length * segment.dt;
				segment.remainingLength = segment.length;
				while (segment.remainingLength >= nextDistance) {
					segment.remainingLength -= nextDistance;
					t += dt;
					times.push(t);
					if (distance != nextDistance) {
						nextDistance = distance;
						dt = nextDistance / segment.length * segment.dt;
					}
				}
				nextDistance -= segment.remainingLength;
				t = segment.tEnd;
			}
		}
		return {times: times, nextDistance: nextDistance};
	},
	// Return the coefficients of the polynomials for x and y in t.
	// From Oliver Steele's bezier.js library.
	coefficients: function() {
		// This function deals with polynomials, represented as
		// arrays of coefficients.  p[i] is the coefficient of n^i.
		
		// p0, p1 => p0 + (p1 - p0) * n
		// side-effects (denormalizes) p0, for convienence
		function interpolate(p0, p1) {
			p0.push(0);
			var p = new Array(p0.length);
			p[0] = p0[0];
			for (var i = 0; i < p1.length; ++i) {
				p[i + 1] = p0[i + 1] + p1[i] - p0[i];
			}
			return p;
		}
		// folds +interpolate+ across a graph whose fringe is
		// the polynomial elements of +ns+, and returns its TOP
		function collapse(ns) {
			while (ns.length > 1) {
				var ps = new Array(ns.length-1);
				for (var i = 0; i < ns.length - 1; ++i) {
					ps[i] = interpolate(ns[i], ns[i + 1]);
				}
				ns = ps;
			}
			return ns[0];
		}
		// xps and yps are arrays of polynomials --- concretely realized
		// as arrays of arrays
		var xps = [];
		var yps = [];
		for (var i = 0, pt; pt = this.points[i++]; ) {
			xps.push([pt.x]);
			yps.push([pt.y]);
		}
		var result = {xs: collapse(xps), ys: collapse(yps)};
		return (this.coefficients = function() {return result;})();
	},
	// Return the point at time t.
	// From Oliver Steele's bezier.js library.
	pointAtT: function(t) {
		var c = this.coefficients();
		var cx = c.xs, cy = c.ys;
		// evaluate cx[0] + cx[1]t +cx[2]t^2 ....
		
		// optimization: start from the end, to save one
		// muliplicate per order (we never need an explicit t^n)
		
		// optimization: special-case the last element
		// to save a multiply-add
		var x = cx[cx.length - 1], y = cy[cy.length - 1];
		
		for (var i = cx.length - 1; --i >= 0; ) {
			x = x * t + cx[i];
			y = y * t + cy[i];
		}
		return new Point(x, y);
	},
	// Render the Bezier to a WHATWG 2D canvas context.
	// Based on Oliver Steele's bezier.js library.
	draw: function (ctx, moveTo) {
		if ('undefined' == typeof moveTo) moveTo = true;
		if (moveTo) ctx.moveTo(this.points[0].x, this.points[0].y);
		var fn = this.drawCommands[this.order];
		if (fn) {
			var coords = [];
			for (var i = 1 == this.order ? 0 : 1; i < this.points.length; ++i) {
				coords.push(this.points[i].x);
				coords.push(this.points[i].y);
			}
			fn.apply(ctx, coords);
		}
	},
	// Wrapper functions to work around Safari, in which, up to at least 2.0.3,
	// fn.apply isn't defined on the context primitives.
	// Based on Oliver Steele's bezier.js library.
	drawCommands: [
		null,
		// This will have an effect if there's a line thickness or end cap.
		function(x, y) {
			this.lineTo(x + 0.001, y);
		},
		function(x, y) {
			this.lineTo(x, y);
		},
		function(x1, y1, x2, y2) {
			this.quadraticCurveTo(x1, y1, x2, y2);
		},
		function(x1, y1, x2, y2, x3, y3) {
			this.bezierCurveTo(x1, y1, x2, y2, x3, y3);
		}
	],
	drawDashed: function(ctx, dashLength, firstDistance, drawFirst) {
		if (!firstDistance) firstDistance = dashLength;
		if ('undefined' == typeof drawFirst) drawFirst = true;
		var markedEvery = this.markedEvery(dashLength, firstDistance);
		if (drawFirst) markedEvery.times.unshift(0);
		var drawLast = (markedEvery.times.length % 2);
		if (drawLast) markedEvery.times.push(1);
		for (var i = 1; i < markedEvery.times.length; i += 2) {
			this.mid(markedEvery.times[i - 1], markedEvery.times[i]).draw(ctx);
		}
		return {firstDistance: markedEvery.nextDistance, drawFirst: drawLast};
	},
	drawDotted: function(ctx, dotSpacing, firstDistance) {
		if (!firstDistance) firstDistance = dotSpacing;
		var markedEvery = this.markedEvery(dotSpacing, firstDistance);
		if (dotSpacing == firstDistance) markedEvery.times.unshift(0);
		markedEvery.times.each(function(t) {
			this.pointAtT(t).draw(ctx);
		}.bind(this));
		return markedEvery.nextDistance;
	}
}

function Path(segments) {
	this.segments = segments || [];
}

Path.prototype = {
	// Based on Oliver Steele's bezier.js library.
	addBezier: function(pointsOrBezier) {
		this.segments.push(pointsOrBezier instanceof Array ? new Bezier(pointsOrBezier) : pointsOrBezier);
	},
	offset: function(dx, dy) {
		this.segments.each(function(segment) {
			segment.offset(dx, dy);
		});
	},
	// Based on Oliver Steele's bezier.js library.
	draw: function(ctx) {
		var moveTo = true;
		for (var i=0; i < this.segments.length; i++) {
			this.segments[i].draw(ctx, moveTo);
			moveTo = false;
		};
		/*
		this.segments.each(function(segment) {
			segment.draw(ctx, moveTo);
			moveTo = false;
		});*/
	},
	drawDashed: function(ctx, dashLength, firstDistance, drawFirst) {
		var info = {
			drawFirst: ('undefined' == typeof drawFirst) ? true : drawFirst,
			firstDistance: firstDistance || dashLength
		};
		this.segments.each(function(segment) {
			info = segment.drawDashed(ctx, dashLength, info.firstDistance, info.drawFirst);
		});
	},
	drawDotted: function(ctx, dotSpacing, firstDistance) {
		if (!firstDistance) firstDistance = dotSpacing;
		for (var i=0; i < this.segments.length; i++) {
			this.segments[i].drawDotted(ctx, dotSpacing, firstDistance);
		};
		/**
		this.segments.each(function(segment) {
			firstDistance = segment.drawDotted(ctx, dotSpacing, firstDistance);
		});*/
	}
}

//var Ellipse = Class.create();
function Ellipse(cx, cy, rx, ry) {
		this.cx = cx; // center x
		this.cy = cy; // center y
		this.rx = rx; // radius x
		this.ry = ry; // radius y
		this.segments = [
			new Bezier([
				new Point(cx, cy - ry),
				new Point(cx + this.KAPPA * rx, cy - ry),
				new Point(cx + rx, cy - this.KAPPA * ry),
				new Point(cx + rx, cy)
			]),
			new Bezier([
				new Point(cx + rx, cy),
				new Point(cx + rx, cy + this.KAPPA * ry),
				new Point(cx + this.KAPPA * rx, cy + ry),
				new Point(cx, cy + ry)
			]),
			new Bezier([
				new Point(cx, cy + ry),
				new Point(cx - this.KAPPA * rx, cy + ry),
				new Point(cx - rx, cy + this.KAPPA * ry),
				new Point(cx - rx, cy)
			]),
			new Bezier([
				new Point(cx - rx, cy),
				new Point(cx - rx, cy - this.KAPPA * ry),
				new Point(cx - this.KAPPA * rx, cy - ry),
				new Point(cx, cy - ry)
			])
		];
}

Ellipse.prototype = new Path();
Ellipse.prototype.KAPPA=0.5522847498;

hui.ui.Graphviz.colors={
	aliceblue:[240,248,255],
	antiquewhite:[250,235,215],
	antiquewhite1:[255,239,219],
	antiquewhite2:[238,223,204],
	antiquewhite3:[205,192,176],
	antiquewhite4:[139,131,120],
	aquamarine:[127,255,212],
	aquamarine1:[127,255,212],
	aquamarine2:[118,238,198],
	aquamarine3:[102,205,170],
	aquamarine4:[69,139,116],
	azure:[240,255,255],
	azure1:[240,255,255],
	azure2:[224,238,238],
	azure3:[193,205,205],
	azure4:[131,139,139],
	beige:[245,245,220],
	bisque:[255,228,196],
	bisque1:[255,228,196],
	bisque2:[238,213,183],
	bisque3:[205,183,158],
	bisque4:[139,125,107],
	black:[0,0,0],
	blanchedalmond:[255,235,205],
	blue:[0,0,255],
	blue1:[0,0,255],
	blue2:[0,0,238],
	blue3:[0,0,205],
	blue4:[0,0,139],
	blueviolet:[138,43,226],
	brown:[165,42,42],
	brown1:[255,64,64],
	brown2:[238,59,59],
	brown3:[205,51,51],
	brown4:[139,35,35],
	burlywood:[222,184,135],
	burlywood1:[255,211,155],
	burlywood2:[238,197,145],
	burlywood3:[205,170,125],
	burlywood4:[139,115,85],
	cadetblue:[95,158,160],
	cadetblue1:[152,245,255],
	cadetblue2:[142,229,238],
	cadetblue3:[122,197,205],
	cadetblue4:[83,134,139],
	chartreuse:[127,255,0],
	chartreuse1:[127,255,0],
	chartreuse2:[118,238,0],
	chartreuse3:[102,205,0],
	chartreuse4:[69,139,0],
	chocolate:[210,105,30],
	chocolate1:[255,127,36],
	chocolate2:[238,118,33],
	chocolate3:[205,102,29],
	chocolate4:[139,69,19],
	coral:[255,127,80],
	coral1:[255,114,86],
	coral2:[238,106,80],
	coral3:[205,91,69],
	coral4:[139,62,47],
	cornflowerblue:[100,149,237],
	cornsilk:[255,248,220],
	cornsilk1:[255,248,220],
	cornsilk2:[238,232,205],
	cornsilk3:[205,200,177],
	cornsilk4:[139,136,120],
	crimson:[220,20,60],
	cyan:[0,255,255],
	cyan1:[0,255,255],
	cyan2:[0,238,238],
	cyan3:[0,205,205],
	cyan4:[0,139,139],
	darkgoldenrod:[184,134,11],
	darkgoldenrod1:[255,185,15],
	darkgoldenrod2:[238,173,14],
	darkgoldenrod3:[205,149,12],
	darkgoldenrod4:[139,101,8],
	darkgreen:[0,100,0],
	darkkhaki:[189,183,107],
	darkolivegreen:[85,107,47],
	darkolivegreen1:[202,255,112],
	darkolivegreen2:[188,238,104],
	darkolivegreen3:[162,205,90],
	darkolivegreen4:[110,139,61],
	darkorange:[255,140,0],
	darkorange1:[255,127,0],
	darkorange2:[238,118,0],
	darkorange3:[205,102,0],
	darkorange4:[139,69,0],
	darkorchid:[153,50,204],
	darkorchid1:[191,62,255],
	darkorchid2:[178,58,238],
	darkorchid3:[154,50,205],
	darkorchid4:[104,34,139],
	darksalmon:[233,150,122],
	darkseagreen:[143,188,143],
	darkseagreen1:[193,255,193],
	darkseagreen2:[180,238,180],
	darkseagreen3:[155,205,155],
	darkseagreen4:[105,139,105],
	darkslateblue:[72,61,139],
	darkslategray:[47,79,79],
	darkslategray1:[151,255,255],
	darkslategray2:[141,238,238],
	darkslategray3:[121,205,205],
	darkslategray4:[82,139,139],
	darkslategrey:[47,79,79],
	darkturquoise:[0,206,209],
	darkviolet:[148,0,211],
	deeppink:[255,20,147],
	deeppink1:[255,20,147],
	deeppink2:[238,18,137],
	deeppink3:[205,16,118],
	deeppink4:[139,10,80],
	deepskyblue:[0,191,255],
	deepskyblue1:[0,191,255],
	deepskyblue2:[0,178,238],
	deepskyblue3:[0,154,205],
	deepskyblue4:[0,104,139],
	dimgray:[105,105,105],
	dimgrey:[105,105,105],
	dodgerblue:[30,144,255],
	dodgerblue1:[30,144,255],
	dodgerblue2:[28,134,238],
	dodgerblue3:[24,116,205],
	dodgerblue4:[16,78,139],
	firebrick:[178,34,34],
	firebrick1:[255,48,48],
	firebrick2:[238,44,44],
	firebrick3:[205,38,38],
	firebrick4:[139,26,26],
	floralwhite:[255,250,240],
	forestgreen:[34,139,34],
	gainsboro:[220,220,220],
	ghostwhite:[248,248,255],
	gold:[255,215,0],
	gold1:[255,215,0],
	gold2:[238,201,0],
	gold3:[205,173,0],
	gold4:[139,117,0],
	goldenrod:[218,165,32],
	goldenrod1:[255,193,37],
	goldenrod2:[238,180,34],
	goldenrod3:[205,155,29],
	goldenrod4:[139,105,20],
	gray:[192,192,192],
	gray0:[0,0,0],
	gray1:[3,3,3],
	gray10:[26,26,26],
	gray100:[255,255,255],
	gray11:[28,28,28],
	gray12:[31,31,31],
	gray13:[33,33,33],
	gray14:[36,36,36],
	gray15:[38,38,38],
	gray16:[41,41,41],
	gray17:[43,43,43],
	gray18:[46,46,46],
	gray19:[48,48,48],
	gray2:[5,5,5],
	gray20:[51,51,51],
	gray21:[54,54,54],
	gray22:[56,56,56],
	gray23:[59,59,59],
	gray24:[61,61,61],
	gray25:[64,64,64],
	gray26:[66,66,66],
	gray27:[69,69,69],
	gray28:[71,71,71],
	gray29:[74,74,74],
	gray3:[8,8,8],
	gray30:[77,77,77],
	gray31:[79,79,79],
	gray32:[82,82,82],
	gray33:[84,84,84],
	gray34:[87,87,87],
	gray35:[89,89,89],
	gray36:[92,92,92],
	gray37:[94,94,94],
	gray38:[97,97,97],
	gray39:[99,99,99],
	gray4:[10,10,10],
	gray40:[102,102,102],
	gray41:[105,105,105],
	gray42:[107,107,107],
	gray43:[110,110,110],
	gray44:[112,112,112],
	gray45:[115,115,115],
	gray46:[117,117,117],
	gray47:[120,120,120],
	gray48:[122,122,122],
	gray49:[125,125,125],
	gray5:[13,13,13],
	gray50:[127,127,127],
	gray51:[130,130,130],
	gray52:[133,133,133],
	gray53:[135,135,135],
	gray54:[138,138,138],
	gray55:[140,140,140],
	gray56:[143,143,143],
	gray57:[145,145,145],
	gray58:[148,148,148],
	gray59:[150,150,150],
	gray6:[15,15,15],
	gray60:[153,153,153],
	gray61:[156,156,156],
	gray62:[158,158,158],
	gray63:[161,161,161],
	gray64:[163,163,163],
	gray65:[166,166,166],
	gray66:[168,168,168],
	gray67:[171,171,171],
	gray68:[173,173,173],
	gray69:[176,176,176],
	gray7:[18,18,18],
	gray70:[179,179,179],
	gray71:[181,181,181],
	gray72:[184,184,184],
	gray73:[186,186,186],
	gray74:[189,189,189],
	gray75:[191,191,191],
	gray76:[194,194,194],
	gray77:[196,196,196],
	gray78:[199,199,199],
	gray79:[201,201,201],
	gray8:[20,20,20],
	gray80:[204,204,204],
	gray81:[207,207,207],
	gray82:[209,209,209],
	gray83:[212,212,212],
	gray84:[214,214,214],
	gray85:[217,217,217],
	gray86:[219,219,219],
	gray87:[222,222,222],
	gray88:[224,224,224],
	gray89:[227,227,227],
	gray9:[23,23,23],
	gray90:[229,229,229],
	gray91:[232,232,232],
	gray92:[235,235,235],
	gray93:[237,237,237],
	gray94:[240,240,240],
	gray95:[242,242,242],
	gray96:[245,245,245],
	gray97:[247,247,247],
	gray98:[250,250,250],
	gray99:[252,252,252],
	green:[0,255,0],
	green1:[0,255,0],
	green2:[0,238,0],
	green3:[0,205,0],
	green4:[0,139,0],
	greenyellow:[173,255,47],
	grey:[192,192,192],
	grey0:[0,0,0],
	grey1:[3,3,3],
	grey10:[26,26,26],
	grey100:[255,255,255],
	grey11:[28,28,28],
	grey12:[31,31,31],
	grey13:[33,33,33],
	grey14:[36,36,36],
	grey15:[38,38,38],
	grey16:[41,41,41],
	grey17:[43,43,43],
	grey18:[46,46,46],
	grey19:[48,48,48],
	grey2:[5,5,5],
	grey20:[51,51,51],
	grey21:[54,54,54],
	grey22:[56,56,56],
	grey23:[59,59,59],
	grey24:[61,61,61],
	grey25:[64,64,64],
	grey26:[66,66,66],
	grey27:[69,69,69],
	grey28:[71,71,71],
	grey29:[74,74,74],
	grey3:[8,8,8],
	grey30:[77,77,77],
	grey31:[79,79,79],
	grey32:[82,82,82],
	grey33:[84,84,84],
	grey34:[87,87,87],
	grey35:[89,89,89],
	grey36:[92,92,92],
	grey37:[94,94,94],
	grey38:[97,97,97],
	grey39:[99,99,99],
	grey4:[10,10,10],
	grey40:[102,102,102],
	grey41:[105,105,105],
	grey42:[107,107,107],
	grey43:[110,110,110],
	grey44:[112,112,112],
	grey45:[115,115,115],
	grey46:[117,117,117],
	grey47:[120,120,120],
	grey48:[122,122,122],
	grey49:[125,125,125],
	grey5:[13,13,13],
	grey50:[127,127,127],
	grey51:[130,130,130],
	grey52:[133,133,133],
	grey53:[135,135,135],
	grey54:[138,138,138],
	grey55:[140,140,140],
	grey56:[143,143,143],
	grey57:[145,145,145],
	grey58:[148,148,148],
	grey59:[150,150,150],
	grey6:[15,15,15],
	grey60:[153,153,153],
	grey61:[156,156,156],
	grey62:[158,158,158],
	grey63:[161,161,161],
	grey64:[163,163,163],
	grey65:[166,166,166],
	grey66:[168,168,168],
	grey67:[171,171,171],
	grey68:[173,173,173],
	grey69:[176,176,176],
	grey7:[18,18,18],
	grey70:[179,179,179],
	grey71:[181,181,181],
	grey72:[184,184,184],
	grey73:[186,186,186],
	grey74:[189,189,189],
	grey75:[191,191,191],
	grey76:[194,194,194],
	grey77:[196,196,196],
	grey78:[199,199,199],
	grey79:[201,201,201],
	grey8:[20,20,20],
	grey80:[204,204,204],
	grey81:[207,207,207],
	grey82:[209,209,209],
	grey83:[212,212,212],
	grey84:[214,214,214],
	grey85:[217,217,217],
	grey86:[219,219,219],
	grey87:[222,222,222],
	grey88:[224,224,224],
	grey89:[227,227,227],
	grey9:[23,23,23],
	grey90:[229,229,229],
	grey91:[232,232,232],
	grey92:[235,235,235],
	grey93:[237,237,237],
	grey94:[240,240,240],
	grey95:[242,242,242],
	grey96:[245,245,245],
	grey97:[247,247,247],
	grey98:[250,250,250],
	grey99:[252,252,252],
	honeydew:[240,255,240],
	honeydew1:[240,255,240],
	honeydew2:[224,238,224],
	honeydew3:[193,205,193],
	honeydew4:[131,139,131],
	hotpink:[255,105,180],
	hotpink1:[255,110,180],
	hotpink2:[238,106,167],
	hotpink3:[205,96,144],
	hotpink4:[139,58,98],
	indianred:[205,92,92],
	indianred1:[255,106,106],
	indianred2:[238,99,99],
	indianred3:[205,85,85],
	indianred4:[139,58,58],
	indigo:[75,0,130],
	ivory:[255,255,240],
	ivory1:[255,255,240],
	ivory2:[238,238,224],
	ivory3:[205,205,193],
	ivory4:[139,139,131],
	khaki:[240,230,140],
	khaki1:[255,246,143],
	khaki2:[238,230,133],
	khaki3:[205,198,115],
	khaki4:[139,134,78],
	lavender:[230,230,250],
	lavenderblush:[255,240,245],
	lavenderblush1:[255,240,245],
	lavenderblush2:[238,224,229],
	lavenderblush3:[205,193,197],
	lavenderblush4:[139,131,134],
	lawngreen:[124,252,0],
	lemonchiffon:[255,250,205],
	lemonchiffon1:[255,250,205],
	lemonchiffon2:[238,233,191],
	lemonchiffon3:[205,201,165],
	lemonchiffon4:[139,137,112],
	lightblue:[173,216,230],
	lightblue1:[191,239,255],
	lightblue2:[178,223,238],
	lightblue3:[154,192,205],
	lightblue4:[104,131,139],
	lightcoral:[240,128,128],
	lightcyan:[224,255,255],
	lightcyan1:[224,255,255],
	lightcyan2:[209,238,238],
	lightcyan3:[180,205,205],
	lightcyan4:[122,139,139],
	lightgoldenrod:[238,221,130],
	lightgoldenrod1:[255,236,139],
	lightgoldenrod2:[238,220,130],
	lightgoldenrod3:[205,190,112],
	lightgoldenrod4:[139,129,76],
	lightgoldenrodyellow:[250,250,210],
	lightgray:[211,211,211],
	lightgrey:[211,211,211],
	lightpink:[255,182,193],
	lightpink1:[255,174,185],
	lightpink2:[238,162,173],
	lightpink3:[205,140,149],
	lightpink4:[139,95,101],
	lightsalmon:[255,160,122],
	lightsalmon1:[255,160,122],
	lightsalmon2:[238,149,114],
	lightsalmon3:[205,129,98],
	lightsalmon4:[139,87,66],
	lightseagreen:[32,178,170],
	lightskyblue:[135,206,250],
	lightskyblue1:[176,226,255],
	lightskyblue2:[164,211,238],
	lightskyblue3:[141,182,205],
	lightskyblue4:[96,123,139],
	lightslateblue:[132,112,255],
	lightslategray:[119,136,153],
	lightslategrey:[119,136,153],
	lightsteelblue:[176,196,222],
	lightsteelblue1:[202,225,255],
	lightsteelblue2:[188,210,238],
	lightsteelblue3:[162,181,205],
	lightsteelblue4:[110,123,139],
	lightyellow:[255,255,224],
	lightyellow1:[255,255,224],
	lightyellow2:[238,238,209],
	lightyellow3:[205,205,180],
	lightyellow4:[139,139,122],
	limegreen:[50,205,50],
	linen:[250,240,230],
	magenta:[255,0,255],
	magenta1:[255,0,255],
	magenta2:[238,0,238],
	magenta3:[205,0,205],
	magenta4:[139,0,139],
	maroon:[176,48,96],
	maroon1:[255,52,179],
	maroon2:[238,48,167],
	maroon3:[205,41,144],
	maroon4:[139,28,98],
	mediumaquamarine:[102,205,170],
	mediumblue:[0,0,205],
	mediumorchid:[186,85,211],
	mediumorchid1:[224,102,255],
	mediumorchid2:[209,95,238],
	mediumorchid3:[180,82,205],
	mediumorchid4:[122,55,139],
	mediumpurple:[147,112,219],
	mediumpurple1:[171,130,255],
	mediumpurple2:[159,121,238],
	mediumpurple3:[137,104,205],
	mediumpurple4:[93,71,139],
	mediumseagreen:[60,179,113],
	mediumslateblue:[123,104,238],
	mediumspringgreen:[0,250,154],
	mediumturquoise:[72,209,204],
	mediumvioletred:[199,21,133],
	midnightblue:[25,25,112],
	mintcream:[245,255,250],
	mistyrose:[255,228,225],
	mistyrose1:[255,228,225],
	mistyrose2:[238,213,210],
	mistyrose3:[205,183,181],
	mistyrose4:[139,125,123],
	moccasin:[255,228,181],
	navajowhite:[255,222,173],
	navajowhite1:[255,222,173],
	navajowhite2:[238,207,161],
	navajowhite3:[205,179,139],
	navajowhite4:[139,121,94],
	navy:[0,0,128],
	navyblue:[0,0,128],
	oldlace:[253,245,230],
	olivedrab:[107,142,35],
	olivedrab1:[192,255,62],
	olivedrab2:[179,238,58],
	olivedrab3:[154,205,50],
	olivedrab4:[105,139,34],
	orange:[255,165,0],
	orange1:[255,165,0],
	orange2:[238,154,0],
	orange3:[205,133,0],
	orange4:[139,90,0],
	orangered:[255,69,0],
	orangered1:[255,69,0],
	orangered2:[238,64,0],
	orangered3:[205,55,0],
	orangered4:[139,37,0],
	orchid:[218,112,214],
	orchid1:[255,131,250],
	orchid2:[238,122,233],
	orchid3:[205,105,201],
	orchid4:[139,71,137],
	palegoldenrod:[238,232,170],
	palegreen:[152,251,152],
	palegreen1:[154,255,154],
	palegreen2:[144,238,144],
	palegreen3:[124,205,124],
	palegreen4:[84,139,84],
	paleturquoise:[175,238,238],
	paleturquoise1:[187,255,255],
	paleturquoise2:[174,238,238],
	paleturquoise3:[150,205,205],
	paleturquoise4:[102,139,139],
	palevioletred:[219,112,147],
	palevioletred1:[255,130,171],
	palevioletred2:[238,121,159],
	palevioletred3:[205,104,137],
	palevioletred4:[139,71,93],
	papayawhip:[255,239,213],
	peachpuff:[255,218,185],
	peachpuff1:[255,218,185],
	peachpuff2:[238,203,173],
	peachpuff3:[205,175,149],
	peachpuff4:[139,119,101],
	peru:[205,133,63],
	pink:[255,192,203],
	pink1:[255,181,197],
	pink2:[238,169,184],
	pink3:[205,145,158],
	pink4:[139,99,108],
	plum:[221,160,221],
	plum1:[255,187,255],
	plum2:[238,174,238],
	plum3:[205,150,205],
	plum4:[139,102,139],
	powderblue:[176,224,230],
	purple:[160,32,240],
	purple1:[155,48,255],
	purple2:[145,44,238],
	purple3:[125,38,205],
	purple4:[85,26,139],
	red:[255,0,0],
	red1:[255,0,0],
	red2:[238,0,0],
	red3:[205,0,0],
	red4:[139,0,0],
	rosybrown:[188,143,143],
	rosybrown1:[255,193,193],
	rosybrown2:[238,180,180],
	rosybrown3:[205,155,155],
	rosybrown4:[139,105,105],
	royalblue:[65,105,225],
	royalblue1:[72,118,255],
	royalblue2:[67,110,238],
	royalblue3:[58,95,205],
	royalblue4:[39,64,139],
	saddlebrown:[139,69,19],
	salmon:[250,128,114],
	salmon1:[255,140,105],
	salmon2:[238,130,98],
	salmon3:[205,112,84],
	salmon4:[139,76,57],
	sandybrown:[244,164,96],
	seagreen:[46,139,87],
	seagreen1:[84,255,159],
	seagreen2:[78,238,148],
	seagreen3:[67,205,128],
	seagreen4:[46,139,87],
	seashell:[255,245,238],
	seashell1:[255,245,238],
	seashell2:[238,229,222],
	seashell3:[205,197,191],
	seashell4:[139,134,130],
	sienna:[160,82,45],
	sienna1:[255,130,71],
	sienna2:[238,121,66],
	sienna3:[205,104,57],
	sienna4:[139,71,38],
	skyblue:[135,206,235],
	skyblue1:[135,206,255],
	skyblue2:[126,192,238],
	skyblue3:[108,166,205],
	skyblue4:[74,112,139],
	slateblue:[106,90,205],
	slateblue1:[131,111,255],
	slateblue2:[122,103,238],
	slateblue3:[105,89,205],
	slateblue4:[71,60,139],
	slategray:[112,128,144],
	slategray1:[198,226,255],
	slategray2:[185,211,238],
	slategray3:[159,182,205],
	slategray4:[108,123,139],
	slategrey:[112,128,144],
	snow:[255,250,250],
	snow1:[255,250,250],
	snow2:[238,233,233],
	snow3:[205,201,201],
	snow4:[139,137,137],
	springgreen:[0,255,127],
	springgreen1:[0,255,127],
	springgreen2:[0,238,118],
	springgreen3:[0,205,102],
	springgreen4:[0,139,69],
	steelblue:[70,130,180],
	steelblue1:[99,184,255],
	steelblue2:[92,172,238],
	steelblue3:[79,148,205],
	steelblue4:[54,100,139],
	tan:[210,180,140],
	tan1:[255,165,79],
	tan2:[238,154,73],
	tan3:[205,133,63],
	tan4:[139,90,43],
	thistle:[216,191,216],
	thistle1:[255,225,255],
	thistle2:[238,210,238],
	thistle3:[205,181,205],
	thistle4:[139,123,139],
	tomato:[255,99,71],
	tomato1:[255,99,71],
	tomato2:[238,92,66],
	tomato3:[205,79,57],
	tomato4:[139,54,38],
	transparent:[255,255,254],
	turquoise:[64,224,208],
	turquoise1:[0,245,255],
	turquoise2:[0,229,238],
	turquoise3:[0,197,205],
	turquoise4:[0,134,139],
	violet:[238,130,238],
	violetred:[208,32,144],
	violetred1:[255,62,150],
	violetred2:[238,58,140],
	violetred3:[205,50,120],
	violetred4:[139,34,82],
	wheat:[245,222,179],
	wheat1:[255,231,186],
	wheat2:[238,216,174],
	wheat3:[205,186,150],
	wheat4:[139,126,102],
	white:[255,255,255],
	whitesmoke:[245,245,245],
	yellow:[255,255,0],
	yellow1:[255,255,0],
	yellow2:[238,238,0],
	yellow3:[205,205,0],
	yellow4:[139,139,0],
	yellowgreen:[154,205,50]
};