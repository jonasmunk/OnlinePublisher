In2iGui.onDomReady(function() {
	Poster.getInstance().preload();
});

Poster = function() {
	this.poster = $('poster');
	this.left = $('poster_left');
	this.left.scrollLeft = 450;
	this.right = $('poster_right');
	this.progress = $('poster_loader');
	this.context = 'style/in2isoft2007/gfx/';
	this.leftImages = ['poster_in2isoft_image.png','poster_publisher_image.png','poster_in2igui_image.png','poster_onlineobjects_image.png'];
	this.rightImages = ['poster_in2isoft_text.png','poster_publisher_text.png','poster_in2igui_text.png','poster_onlineobjects_text.png'];
	this.links = ['om/','produkter/onlinepublisher/','teknologi/in2iGui/','produkter/onlineobjects/'];
	this.leftPos = 0;
	this.rightPos = 0;
	var self = this;
	this.poster.onclick = function() {
		document.location=op.page.path+self.links[self.leftPos];
	}
}

Poster.getInstance = function() {
	if (!Poster.instance) {
		Poster.instance = new Poster();
	}
	return Poster.instance;
}

Poster.prototype.start = function() {
	var self = this;
	var base = op.page.path+this.context;
	var leftRecipe = [
		function() {
			self.leftPos++;
			if (self.leftPos>=self.leftImages.length) self.leftPos=0;
			$('poster_inner_left').style.backgroundImage='url(\''+base+self.leftImages[self.leftPos]+'\')';
		},
		{duration:500},
		{element:this.left,property:'scrollLeft',value:'0',duration:1000,ease:n2i.ease.slowFastSlow},
		{duration:4000},
		{element:this.left,property:'scrollLeft',value:'450',duration:1000,ease:n2i.ease.slowFastSlow}
	];
	var leftLoop = new n2i.animation.Loop(leftRecipe);
	leftLoop.start();
	
	var rightRecipe = [
		function() {
			self.rightPos++;
			if (self.rightPos>=self.rightImages.length) self.rightPos=0;
			$('poster_inner_right').style.backgroundImage='url(\''+base+self.rightImages[self.rightPos]+'\')';
		},
		{duration:500},
		{element:this.right,property:'scrollLeft',value:'450',duration:1000,ease:n2i.ease.slowFastSlow},
		{duration:4000},
		{element:this.right,property:'scrollLeft',value:'0',duration:1000,ease:n2i.ease.slowFastSlow}
	];
	var rightLoop = new n2i.animation.Loop(rightRecipe);
	rightLoop.start();
}

Poster.prototype.preload = function() {
	var loader = new n2i.Preloader({context:op.page.path+this.context});
	loader.setDelegate(this);
	loader.addImages(this.leftImages);
	loader.addImages(this.rightImages);
	loader.load();
}

Poster.prototype.allImagesDidLoad = function() {
	this.progress.style.display='none';
	this.start();
}

Poster.prototype.imageDidLoad = function(loaded,total) {
	this.progress.innerHTML=Math.round(loaded/total*100)+'%';
}