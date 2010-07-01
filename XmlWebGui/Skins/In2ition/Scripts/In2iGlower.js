var GlowerHandler = new Object();
GlowerHandler.nextId = 0;

function Glower(id) {
	this.id = GlowerHandler.nextId++;
	GlowerHandler[this.id] = this;
	this.element = document.getElementById(id);
	this.revealing = false;
	this.min = 0.4;
	this.max = 1;
}

Glower.prototype.start = function() {
	var agent = navigator.userAgent.toLowerCase();
	if (agent.indexOf('gecko') != -1) {
		window.setInterval('GlowerHandler['+this.id+']._glow()',20);
	}
}

Glower.prototype._glow = function() {
	var value = parseFloat(this.element.style.opacity);
	if (!value) value=1;
	if (this.revealing) {
		value=value+0.01;
	} else {
		value=value-0.01;
	}
	if (value>=this.max) this.revealing = false;
	if (value<=this.min) this.revealing = true;
	this.element.style.opacity = value;
	//this.element.style.filter = 'alpha(opacity='+(value*100)+')';
	
}