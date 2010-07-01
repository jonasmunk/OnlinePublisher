function In2iMiniGallery(containerPrefix) {
	this.container1 = document.getElementById(containerPrefix+'1');
	this.container2 = document.getElementById(containerPrefix+'2');
	this.images = []; // Array of all images
	this.currentImage = 0; // The image currently shown
	this.time=0; // A timer always incrementing
	this.stop=0; // Latest integer point reached (floor of timer)
	this.state = 2;
	this.step=0;
}

In2iMiniGallery.prototype.addImage = function(url) {
	this.images[this.images.length] = url;
}

In2iMiniGallery.prototype.start = function() {
	this.next();
	var obj = this;
	// First change the image two times to fill the two divs
	this.changeImage();
	this.changeImage();
	// Start time timer
	window.setInterval(
		function() {
			obj.next();
		},50
	);
}


In2iMiniGallery.prototype.next = function() {
	this.time+=0.005;
	var opacity = (Math.cos(this.time*Math.PI)-1)/-2;
	
	if (navigator.userAgent.indexOf('MSIE')==-1) {
		this.container1.style.opacity=opacity;
	} else {
		this.container1.style.display = (this.step/2 == parseInt(this.step/2) ? '' : 'none');
	}
	if (Math.floor(this.time)>this.step) {
		this.step=Math.floor(this.time);
		this.changeImage();
	}
}

In2iMiniGallery.prototype.changeImage = function() {
	if (this.currentImage>=(this.images.length-1)) {
		this.currentImage = 0;
	} else {
		this.currentImage++;
	}
	if (this.state==1) {
		this.container1.style.backgroundImage="url('"+this.images[this.currentImage]+"')";
		this.state=2;
	} else {
		this.container2.style.backgroundImage="url('"+this.images[this.currentImage]+"')";
		this.state=1;
	}
} 