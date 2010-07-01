addary = new Array();           //red
addary[0] = new Array(0,1,0);   //red green
addary[1] = new Array(-1,0,0);  //green
addary[2] = new Array(0,0,1);   //green blue
addary[3] = new Array(0,-1,0);  //blue
addary[4] = new Array(1,0,0);   //red blue
addary[5] = new Array(0,0,-1);  //red
addary[6] = new Array(255,1,1);
clrary = new Array(360);
for(i = 0; i < 6; i++)
for(j = 0; j < 60; j++) {
  clrary[60 * i + j] = new Array(3);
  for(k = 0; k < 3; k++) {
    clrary[60 * i + j][k] = addary[6][k];
    addary[6][k] += (addary[i][k] * 4);
    }
  }
hexary = new Array("#666666", "#555555", "#545657");

function capture() {
	hoverColor();
	if(document.layers) {
		layobj = document.layers['wheel'];
		layobj.document.captureEvents(Event.MOUSEMOVE);
		layobj.document.onmousemove = mouseMoved;
	}
	else if (document.all) {
		layobj = document.all["wheel"];
		layobj.onmousemove = mouseMoved;
	}
	else if (document.getElementById) {
		window.document.getElementById("wheel").onmousemove = mouseMoved;
	}
}

function mouseMoved(e) {
	if (document.layers) {
		x = 4 * e.layerX;
		y = 4 * e.layerY;
	}
	else if (document.all) {
		x = 4 * event.offsetX;
		y = 4 * event.offsetY;
	}
	else if (document.getElementById) {
		x = 4 * (e.pageX - getElementLeft("wheel"));
		y = 4 * (e.pageY - getElementTop("wheel"));
	}
	sx = x - 512;
	sy = y - 512;
	qx = (sx < 0)?0:1;
	qy = (sy < 0)?0:1;
	q = 2 * qy + qx;
	quad = new Array(-180,360,180,0);
	xa = Math.abs(sx);
	ya = Math.abs(sy);
	d = ya * 45 / xa;
	if(ya > xa) d = 90 - (xa * 45 / ya);
	deg = Math.floor(Math.abs(quad[q] - d));
	n = 0;
	sx = Math.abs(x - 512);
	sy = Math.abs(y - 512);
	r = Math.sqrt((sx * sx) + (sy * sy));
	if(x == 512 & y == 512) {c = "000000";}
	else {
	for(i = 0; i < 3; i++) {
		r2 = clrary[deg][i] * r / 256;
		if(r > 256) r2 += Math.floor(r - 256);
		if(r2 > 255) r2 = 255;
		n = 256 * n + Math.floor(r2);
	}
	c = n.toString(16);
	while(c.length < 6) c = "0" + c;
	}
			hexary[1] = "#" + c.charAt(0) + c.charAt(0) + c.charAt(2) + c.charAt(2) + c.charAt(4) + c.charAt(4);
			hexary[2] = "#" + c;
			hexary[0] = safetyFirst(c);
	hoverColor();
	return false;
}

function hoverColor() {
	hover(hexary[2]);
	return false;
}

function pickColor() {
	update(hexary[2]);
}

function safetyFirst(c) {
    cary = new Array(c.charAt(0),c.charAt(2),c.charAt(4));
    for (ci = 0; ci < 3; ci++) {
       switch (cary[ci]) {
         case "1":
         cary[ci]="0";
         break;
         case "2":
         cary[ci]="3";
         break;
         case "4":
         cary[ci]="3";
         break;
         case "5":
         cary[ci]="6";
         break;
         case "7":
         cary[ci]="6";
         break;
         case "8":
         cary[ci]="9";
         break;
         case "a":
         cary[ci]="9";
         break;
         case "b":
         cary[ci]="c";
         break;
         case "d":
         cary[ci]="c";
         break;
         case "e":
         cary[ci]="f";
         break;
      }
   }
  safecolor = "#" + cary[0]+cary[0] + cary[1]+cary[1] + cary[2]+cary[2];
  return safecolor;
}