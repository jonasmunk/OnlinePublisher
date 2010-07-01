//Browsercheck (needed) ***************
function lib_bwcheck(){ 
	this.ver=navigator.appVersion
	this.agent=navigator.userAgent
	this.dom=document.getElementById?1:0
	this.opera5=this.agent.indexOf("Opera 5")>-1
	this.ie5=(this.ver.indexOf("MSIE 5")>-1 && this.dom && !this.opera5)?1:0; 
	this.ie6=(this.ver.indexOf("MSIE 6")>-1 && this.dom && !this.opera5)?1:0;
	this.ie4=(document.all && !this.dom && !this.opera5)?1:0;
	this.ie=this.ie4||this.ie5||this.ie6
	this.mac=this.agent.indexOf("Mac")>-1
	this.ns6=(this.dom && parseInt(this.ver) >= 5) ?1:0; 
	this.ns4=(document.layers && !this.dom)?1:0;
	this.bw=(this.ie6||this.ie5||this.ie4||this.ns4||this.ns6||this.opera5)
	return this
}
bw=new lib_bwcheck() //Browsercheck object

//Debug function ******************
function lib_message(txt){alert(txt); return false}

//Lib objects  ********************
function LibraryObject(obj,nest){ 
	if(!bw.bw) return lib_message('Old browser')
	nest=(!nest) ? "":'document.'+nest+'.'
	this.evnt=bw.dom ? document.getElementById(obj) :
		bw.ie4 ? document.all[obj] : bw.ns4 ? eval(nest+"document.layers." +obj) : 0;	
	if(!this.evnt) return lib_message('The layer does not exist ('+obj+')' 
		+'- \nIf your using Netscape please check the nesting of your tags!')
	this.css=bw.dom||bw.ie4?this.evnt.style:this.evnt; 
	this.ref=bw.dom||bw.ie4?document:this.css.document;
	this.x=parseInt(this.css.left)||this.css.pixelLeft||this.evnt.offsetLeft||0;
	this.y=parseInt(this.css.top)||this.css.pixelTop||this.evnt.offsetTop||0
	this.w=this.evnt.offsetWidth||this.css.clip.width||
		this.ref.width||this.css.pixelWidth||0; 
	this.h=this.evnt.offsetHeight||this.css.clip.height||
		this.ref.height||this.css.pixelHeight||0
	this.c=0 //Clip values
	if((bw.dom || bw.ie4) && this.css.clip) {
		this.c=this.css.clip;
		this.c=this.c.slice(5,this.c.length-1); 
		this.c=this.c.split(' ');
		for(var i=0;i<4;i++){
			this.c[i]=parseInt(this.c[i])
		}
	}
	this.ct=this.css.clip.top||this.c[0]||0; 
	this.cr=this.css.clip.right||this.c[1]||this.w||0
	this.cb=this.css.clip.bottom||this.c[2]||this.h||0; 
	this.cl=this.css.clip.left||this.c[3]||0
	this.obj = obj + "Object"; eval(this.obj + "=this")
	return this
}

//Moving object to **************
LibraryObject.prototype.moveTo = function(x,y){
	this.x=x;
	this.y=y;
	this.css.left=x;
	this.css.top=y
}

//Moving object by ***************
LibraryObject.prototype.moveBy = function(x,y){
	this.css.left=this.x+=x;
	this.css.top=this.y+=y
}

//Showing object ************
LibraryObject.prototype.show = function(){
	this.css.visibility="visible"
}

//Hiding object **********
LibraryObject.prototype.hide = function(){
	this.css.visibility="hidden"
}

//Changing backgroundcolor ***************
LibraryObject.prototype.setBackgroundColor = function(color){ 
	if(bw.opera) this.css.background=color;
	else if(bw.dom || bw.ie4) this.css.backgroundColor=color;
	else if(bw.ns4) this.css.bgColor=color  ;
}

//Writing content to object ***
LibraryObject.prototype.writeIt = function(text,startHTML,endHTML){
	if(bw.ns4){
		if(!startHTML){
			startHTML="";
			endHTML="";
		}
		this.ref.open("text/html"); 
		this.ref.write(startHTML+text+endHTML); 
		this.ref.close()
	}
	else this.evnt.innerHTML=text;
}

//Clipping object to ******
LibraryObject.prototype.clipTo = function(t,r,b,l,setwidth){ 
	this.ct=t; this.cr=r; this.cb=b; this.cl=l
	if (bw.ns4){
		this.css.clip.top=t;
		this.css.clip.right=r;
		this.css.clip.bottom=b;
		this.css.clip.left=l;
	} else {
		if (t<0) t=0;
		if (r<0) r=0;
		if (b<0) b=0;
		if (l<0) l=0;
		this.css.clip="rect("+t+","+r+","+b+","+l+")";
		if(setwidth){
			this.css.pixelWidth=this.css.width=r;
			this.css.pixelHeight=this.css.height=b
		}
	}
}

//Clipping object by ******
LibraryObject.prototype.clipBy = function(t,r,b,l,setwidth){ 
  this.clipTo(this.ct+t,this.cr+r,this.cb+b,this.cl+l,setwidth);
}

//Clip animation ************
LibraryObject.prototype.slideClipTo = function(t,r,b,l,step,fn,wh){
	tstep = Math.max(
					Math.max(Math.abs((t-this.ct)/step),Math.abs((r-this.cr)/step)),
					Math.max(Math.abs((b-this.cb)/step),Math.abs((l-this.cl)/step))
			)
	if(!this.clipactive){
		this.clipactive=true; if(!wh) wh=0; if(!fn) fn=0
		this.clip(t,r,b,l,(t-this.ct)/tstep,(r-this.cr)/tstep,
		(b-this.cb)/tstep,(l-this.cl)/tstep,tstep,0, fn,wh)
	}
}

LibraryObject.prototype.clip = function(t,r,b,l,ts,rs,bs,ls,tstep,astep,fn,wh){
	if(astep<tstep){
		if(wh) eval(wh); 
		astep++;
		this.clipBy(ts,rs,bs,ls,1);
		setTimeout(
			this.obj+".clip("+t+","+r+","+b+","+l+","+ts+","+rs+","
			+bs+","+ls+","+tstep+","+astep+",'"+fn+"','"+wh+"')",50
			);
	} else {
		this.clipactive=false; this.clipTo(t,r,b,l,1);
		if(fn) eval(fn);
	}
}

//Slide animation ***********
LibraryObject.prototype.slideTo = function(endx,endy,inc,speed,fn,wh){
	if(!this.slideactive){
		var distx = endx - this.x;
		var disty = endy - this.y;
		var num = Math.sqrt(Math.pow(distx,2)+Math.pow(disty,2))/inc;
		var dx = distx/num; var dy = disty/num;
		this.slideactive = 1; 
		if(!wh) wh=0;
		if(!fn) fn=0;
		this.slide(dx,dy,endx,endy,speed,fn,wh);
    }
}

LibraryObject.prototype.slide = function(dx,dy,endx,endy,speed,fn,wh) {
	if(	this.slideactive &&
		(Math.floor(Math.abs(dx))<Math.floor(Math.abs(endx-this.x))|| 
		Math.floor(Math.abs(dy))<Math.floor(Math.abs(endy-this.y)))
	) {
		this.moveBy(dx,dy); 
		if(wh) eval(wh);
		setTimeout(this.obj+".slide("+dx+","+dy+","+endx+","+endy+","+speed+",'"
			+fn+"','"+wh+"')",speed);
	} else {
		this.slideactive = 0; 
		this.moveTo(endx,endy);
		if(fn) eval(fn)
	}
}

//Circle animation ****************
LibraryObject.prototype.circleIt = function(rad,ainc,a,enda,xc,yc,speed,fn) {
	if((Math.abs(ainc)<Math.abs(enda-a))) {
		a += ainc;
		var x = xc + rad*Math.cos(a*Math.PI/180);
		var y = yc - rad*Math.sin(a*Math.PI/180);
		this.moveTo(x,y);
		setTimeout(this.obj+".circleIt("+rad+","+ainc+","+a+","+enda+","
			+xc+","+yc+","+speed+",'"+fn+"')",speed);
	}
	else if(fn&&fn!="undefined") eval(fn);
}

//Document size object ********
function lib_doc_size(){ 
	this.x=0;this.x2=bw.ie && document.body.offsetWidth-20||innerWidth||0;
	this.y=0;this.y2=bw.ie && document.body.offsetHeight-5||innerHeight||0;
	if(!this.x2||!this.y2) return message('Document has no width or height');
	this.x50=this.x2/2;this.y50=this.y2/2;
	return this;
}

//Drag drop functions start *******************
dd_is_active=0; dd_obj=0; dd_mobj=0
function lib_dd(){
	dd_is_active=1;
	if(bw.ns4){
		document.captureEvents(Event.MOUSEMOVE|Event.MOUSEDOWN|Event.MOUSEUP);
	}
	document.onmousemove=lib_dd_move;
	document.onmousedown=lib_dd_down;
	document.onmouseup=lib_dd_up;
}

LibraryObject.prototype.dragdrop = function(obj){
	if(!dd_is_active) lib_dd();
	this.evnt.onmouseover=new Function("lib_dd_over("+this.obj+")");
	this.evnt.onmouseout=new Function("dd_mobj=0");
	if(obj) this.ddobj=obj;
}

LibraryObject.prototype.nodragdrop = function(){
	this.evnt.onmouseover=""; this.evnt.onmouseout="";
	dd_obj=0;
	dd_mobj=0;
}

//Drag drop event functions
function lib_dd_over(obj){
	dd_mobj=obj;
}

function lib_dd_up(e){
	dd_obj=0;
}

function lib_dd_down(e){ //Mousedown
	if(dd_mobj){
		x=(bw.ns4 || bw.ns6)?e.pageX:event.x||event.clientX;
		y=(bw.ns4 || bw.ns6)?e.pageY:event.y||event.clientY;
		dd_obj=dd_mobj;
		dd_obj.clX=x-dd_obj.x; 
		dd_obj.clY=y-dd_obj.y;
	}
}

function lib_dd_move(e,y,rresize){ //Mousemove
	x=(bw.ns4 || bw.ns6)?e.pageX:event.x||event.clientX;
	y=(bw.ns4 || bw.ns6)?e.pageY:event.y||event.clientY;
	if(dd_obj){
		nx=x-dd_obj.clX; ny=y-dd_obj.clY;
		if(dd_obj.ddobj) dd_obj.ddobj.moveTo(nx,ny);
		else dd_obj.moveTo(nx,ny);
	}
	if(!bw.ns4) return false;
}
//Drag drop functions end *************





function getElementHeight(Elem) {
    if(document.getElementById) {
            var elem = document.getElementById(Elem);
    } else if (document.all){
            var elem = document.all[Elem];
    }
    xPos = elem.offsetHeight;
    return xPos;
}

function getElementWidth(Elem) {
    if(document.getElementById) {
            var elem = document.getElementById(Elem);
    } else if (document.all){
            var elem = document.all[Elem];
    }
    xPos = elem.offsetWidth;
    return xPos;
}

function getElementLeft(Elem) {
    var elem;
    if(document.getElementById) {
            var elem = document.getElementById(Elem);
    } else if (document.all){
            var elem = document.all[Elem];
    }
    xPos = elem.offsetLeft;
    tempEl = elem.offsetParent;
    while (tempEl != null) {
            xPos += tempEl.offsetLeft;
            tempEl = tempEl.offsetParent;
    }
    return xPos;
}


function getElementTop(Elem) {
    if(document.getElementById) {   
            var elem = document.getElementById(Elem);
    } else if (document.all) {
            var elem = document.all[Elem];
    }
    yPos = elem.offsetTop;
    tempEl = elem.offsetParent;
    while (tempEl != null) {
            yPos += tempEl.offsetTop;
            tempEl = tempEl.offsetParent;
    }
    return yPos;
}

function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement &&
      ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
}

function shakeWindow(n) {
	netscape = (navigator.appName == "Netscape");
	n4 = netscape && (parseInt(navigator.appVersion) >= 4);
	explorer = (navigator.appName == "Microsoft Internet Explorer");
	ie4 = explorer && (parseInt(navigator.appVersion) >= 4);
	if (n4 || ie4) {
		for (i = 10; i > 0; i--) {
			for (j = n; j > 0; j--) {
				self.moveBy(0,i);
				self.moveBy(i,0);
				self.moveBy(0,-i);
				self.moveBy(-i,0);
			}
		}
	}
}