if(window.hui=window.hui||{},function(e,i,t){e.KEY_BACKSPACE=8,e.KEY_TAB=9,e.KEY_RETURN=13,e.KEY_ESC=27,e.KEY_LEFT=37,e.KEY_UP=38,e.KEY_RIGHT=39,e.KEY_DOWN=40,e.KEY_DELETE=46,e.KEY_HOME=36,e.KEY_END=35,e.KEY_PAGEUP=33,e.KEY_PAGEDOWN=34,e.KEY_INSERT=45
var n=e.browser={}
n.msie=!/opera/i.test(i)&&/MSIE/.test(i)||/Trident/.test(i),n.msie6=-1!==i.indexOf("MSIE 6"),n.msie7=-1!==i.indexOf("MSIE 7"),n.msie8=-1!==i.indexOf("MSIE 8"),n.msie9=-1!==i.indexOf("MSIE 9"),n.msie9compat=n.msie7&&-1!==i.indexOf("Trident/5.0"),n.msie10=-1!==i.indexOf("MSIE 10"),n.msie11=-1!==i.indexOf("Trident/7.0"),n.webkit=-1!==i.indexOf("WebKit"),n.safari=-1!==i.indexOf("Safari"),n.webkitVersion=null,n.gecko=!n.webkit&&!n.msie&&-1!==i.indexOf("Gecko"),n.ipad=n.webkit&&-1!==i.indexOf("iPad"),n.windows=-1!==i.indexOf("Windows"),n.opacity=!n.msie6&&!n.msie7&&!n.msie8,n.mediaQueries=n.opacity,n.animation=!(n.msie6||n.msie7||n.msie8||n.msie9),n.wordbreak=!n.msie6&&!n.msie7&&!n.msie8,n.touch=!!("ontouchstart"in t||"onmsgesturechange"in t&&t.navigator.maxTouchPoints)
var o=/Safari\/([\d.]+)/.exec(i)
o&&(n.webkitVersion=parseFloat(o[1]))}(hui,navigator.userAgent,window),hui.log=function(e){window.console&&window.console.log&&(1==arguments.length?console.log(e):2==arguments.length?console.log(arguments[0],arguments[1]):console.log(arguments))},hui._demanded=[],hui.define=function(e,i){for(var t=hui._demanded,n=t.length-1;n>=0;n--){var o=t[n]
hui.array.remove(o.requirements,e),0==o.requirements.length&&(o.callback(),t.splice(n,1))}},hui.demand=function(){for(var e=arguments[0],i=arguments[1],t=[],n=0;n<e.length;n++){var o=hui.evaluate(e[n])
if(!o){t=!1
break}t[n]=o}t?i(t):(hui.log("deferring: "+e),hui._demanded.push({requirements:e,callback:i}))},hui.evaluate=function(e){for(var i=e.split("."),t=window,n=0;n<i.length&&void 0!==t;n++)t=t[i[n]]
return t},hui.defer=function(e,i){i&&(e=e.bind(i)),window.setTimeout(e)},hui.extend=function(e,i){function t(){this.constructor=e}var n=e.prototype
for(var o in i)i.hasOwnProperty(o)&&(e[o]=i[o])
if(t.prototype=i.prototype,e.prototype=new t,n)for(var o in n)e.prototype[o]=n[o]},hui.override=function(e,i){if(i)for(var t in i)e[t]=i[t]
return e},hui.each=function(e,i){var t
if(hui.isArray(e))for(t=0;t<e.length;t++)i(e[t],t)
else if(e instanceof NodeList)for(t=0;t<e.length;t++)i(e.item(t),t)
else for(var n in e)i(n,e[n])},hui.when=function(e,i){return e?i:""},hui.intOrString=function(e){if(hui.isDefined(e)){var i=/[0-9]+/.exec(e)
if(null!==i&&i[0]==e&&parseInt(e,10)==e)return parseInt(e,10)}return e},hui.between=function(e,i,t){var n=Math.min(t,Math.max(e,i))
return isNaN(n)?e:n},hui.fit=function(e,i,t){t=t||{}
var n,o,s=e.width/e.height,u=i.width/i.height
return t.upscale===!1&&e.width<=i.width&&e.height<=i.height?(n=e.width,o=e.height):s>u?(n=i.width,o=Math.round(i.width/e.width*e.height)):(n=Math.round(i.height/e.height*e.width),o=i.height),{width:n,height:o}},hui.isBlank=function(e){return null===e||"undefined"==typeof e||""===e?!0:"string"==typeof e&&0===hui.string.trim(e).length},hui.isDefined=function(e){return null!==e&&"undefined"!=typeof e},hui.isString=function(e){return"string"==typeof e},hui.isNumber=function(e){return"number"==typeof e},hui.isArray=function(e){return null===e||void 0===e?!1:e.constructor==Array?!0:"[object Array]"===Object.prototype.toString.call(e)},hui.string={startsWith:function(e,i){return"string"!=typeof e||"string"!=typeof i?!1:e.match("^"+i)==i},endsWith:function(e,i){return"string"!=typeof e||"string"!=typeof i?!1:e.match(i+"$")==i},camelize:function(e){if(-1==e.indexOf("-"))return e
for(var i=e.split("-"),t=0===e.indexOf("-")?i[0].charAt(0).toUpperCase()+i[0].substring(1):i[0],n=1,o=i.length;o>n;n++){var s=i[n]
t+=s.charAt(0).toUpperCase()+s.substring(1)}return t},trim:function(e){return null===e||void 0===e?"":("string"!=typeof e&&(e=String(e)),e.replace(/^[\s\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000]+|[\s\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000]+$/g,""))},wrap:function(e){return null===e||void 0===e?"":e.split("").join("​")},shorten:function(e,i){if(hui.isNumber(e))e+=""
else if(!hui.isString(e))return""
return e.length>i?e.substring(0,i-3)+"...":e},escapeHTML:function(e){return null===e||void 0===e?"":hui.build("div",{text:e}).innerHTML},escape:function(e){if(!hui.isString(e))return e
var i={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"}
return e.replace(/[&<>'`"]/g,function(e){return i[e]||e})},fromJSON:function(e){try{return JSON.parse(e)}catch(i){return hui.log(i),null}},toJSON:function(e){return JSON.stringify(e)}},hui.array={add:function(e,i){if(i.constructor==Array)for(var t=0;t<i.length;t++)hui.array.contains(e,i[t])||e.push(i)
else hui.array.contains(e,i)||e.push(i)},contains:function(e,i){return-1!==hui.array.indexOf(e,i)},flip:function(e,i){hui.array.contains(e,i)?hui.array.remove(e,i):e.push(i)},remove:function(e,i){for(var t=e.length-1;t>=0;t--)e[t]==i&&e.splice(t,1)},indexOf:function(e,i){for(var t=0;t<e.length;t++)if(e[t]===i)return t
return-1},toIntegers:function(e){for(var i=e.split(","),t=i.length-1;t>=0;t--)i[t]=parseInt(i[t],10)
return i}},hui.dom={isElement:function(e,i){return 1==e.nodeType&&(void 0===i?!0:e.nodeName.toLowerCase()==i)},isDefinedText:function(e){return 3==e.nodeType&&e.nodeValue.length>0},addText:function(e,i){e.appendChild(document.createTextNode(i))},firstChild:function(e){for(var i=e.childNodes,t=0;t<i.length;t++)if(1==i[t].nodeType)return i[t]
return null},parse:function(e){var i=hui.build("div",{html:e})
return hui.get.firstChild(i)},clear:function(e){for(var i=e.childNodes,t=i.length-1;t>=0;t--)i[t].parentNode.removeChild(i[t])},remove:function(e){e.parentNode&&e.parentNode.removeChild(e)},replaceNode:function(e,i){i.parentNode&&i.parentNode.removeChild(i),e.parentNode.insertBefore(i,e),e.parentNode.removeChild(e)},changeTag:function(e,i){for(var t=hui.build(i);e.firstChild;)t.appendChild(e.firstChild)
for(var n=e.attributes.length-1;n>=0;--n)t.attributes.setNamedItem(e.attributes[n].cloneNode())
return e.parentNode.insertBefore(t,e),e.parentNode.removeChild(e),t},insertBefore:function(e,i){e.parentNode.insertBefore(i,e)},insertAfter:function(e,i){var t=e.nextSibling
t?t.parentNode.insertBefore(i,t):e.parentNode.appendChild(i)},replaceHTML:function(e,i){e=hui.get(e),e.innerHTML=i},runScripts:function(node){if(hui.dom.isElement(node))if(hui.dom.isElement(node,"script"))eval(node.innerHTML)
else for(var scripts=node.getElementsByTagName("script"),i=0;i<scripts.length;i++)eval(scripts[i].innerHTML)},setText:function(e,i){void 0!==i&&null!==i||(i="")
for(var t=e.childNodes,n=!1,o=t.length-1;o>=0;o--)n||3!==t[o].nodeType?e.removeChild(t[o]):(t[o].nodeValue=i,n=!0)
n||hui.dom.addText(e,i)},getText:function(e){for(var i="",t=e.childNodes,n=0;n<t.length;n++)3===t[n].nodeType&&null!==t[n].nodeValue?i+=t[n].nodeValue:1==t[n].nodeType&&(i+=hui.dom.getText(t[n]))
return i},isVisible:function(e){for(;e;){if(e.style&&("none"===hui.style.get(e,"display")||"hidden"===hui.style.get(e,"visibility")))return!1
e=e.parentNode}return!0},isDescendantOrSelf:function(e,i){for(;e;){if(e==i)return!0
e=e.parentNode}return!1}},hui.form={getValues:function(e){for(var i={},t=e.getElementsByTagName("input"),n=0;n<t.length;n++)hui.isDefined(t[n].name)&&(i[t[n].name]=t[n].value)
return i}},hui.get=function(e){return"string"==typeof e?document.getElementById(e):e},hui.get.children=function(e){for(var i=[],t=e.childNodes,n=0;n<t.length;n++)hui.dom.isElement(t[n])&&i.push(t[n])
return i},hui.get.ancestors=function(e){for(var i=[],t=e.parentNode;t;)i[i.length]=t,t=t.parentNode
return i},hui.get.firstAncestorByClass=function(e,i){for(;e;){if(hui.cls.has(e,i))return e
e=e.parentNode}return null},hui.get.next=function(e){if(!e)return null
if(e.nextElementSibling)return e.nextElementSibling
if(!e.nextSibling)return null
for(var i=e.nextSibling;i&&1!=i.nodeType;)i=i.nextSibling
return i&&1==i.nodeType?i:null},hui.get.previous=function(e){if(!e)return null
if(e.previousElementSibling)return e.previousElementSibling
if(!e.previousSibling)return null
for(var i=e.previousSibling;i&&1!=i.nodeType;)i=i.previousSibling
return i&&1==i.nodeType?i:null},hui.get.before=function(e){var i=[]
if(e)for(var t=e.parentNode.childNodes,n=0;n<t.length&&t[n]!=e;n++)1===t[n].nodeType&&i.push(t[n])
return i},hui.get.after=function(e){for(var i=[],t=hui.get.next(e);t;)i.push(t),t=hui.get.next(t)
return i},hui.get.firstByClass=function(e,i,t){if(e=hui.get(e)||document.body,e.querySelector)return e.querySelector((t?t+".":".")+i)
for(var n=e.getElementsByTagName(t||"*"),o=0;o<n.length;o++)if(hui.cls.has(n[o],i))return n[o]
return null},hui.get.byClass=function(e,i,t){e=hui.get(e)||document.body
var n
if(e.querySelectorAll){var o=e.querySelectorAll((t?t+".":".")+i),s=[]
for(n=0,ll=o.length;n!=ll;s.push(o[n++]));return s}var u=e.getElementsByTagName(t||"*"),a=[]
for(n=0;n<u.length;n++)hui.cls.has(u[n],i)&&(a[a.length]=u[n])
return a},hui.get.byTag=function(e,i){for(var t=e.getElementsByTagName(i),n=[],o=0,s=t.length;o!=s;n.push(t[o++]));return n},hui.get.byId=function(e,i){for(var t=e.childNodes,n=t.length-1;n>=0;n--){if(1===t[n].nodeType&&t[n].getAttribute("id")===i)return t[n]
var o=hui.get.byId(t[n],i)
if(o)return o}return null},hui.get.firstParentByTag=function(e,i){for(var t=e;t;){if(t.tagName&&t.tagName.toLowerCase()==i)return t
t=t.parentNode}return null},hui.get.firstParentByClass=function(e,i){for(var t=e;t;){if(hui.cls.has(t))return t
t=t.parentNode}return null},hui.get.firstByTag=function(e,i){if(e=hui.get(e)||document.body,e.querySelector&&"*"!==i)return e.querySelector(i)
var t=e.getElementsByTagName(i)
return t[0]},hui.get.firstChild=hui.dom.firstChild,hui.find=function(e,i){return(i||document).querySelector(e)},hui.findAll=function(e,i){for(var t=(i||document).querySelectorAll(e),n=[],o=0,s=t.length;o!=s;n.push(t[o++]));return n},document.querySelector||(hui.find=function(e,i){return i=i||document.documentElement,"."==e[0]?hui.get.firstByClass(i,e.substr(1)):hui.get.firstByTag(i,e)}),hui.collect=function(e,i){var t={}
for(key in e)t[key]=hui.find(e[key],i)
return t},hui.build=function(e,i,t){t=t||document
var n=""
if(-1!==e.indexOf(".")){var o=e.split(".")
e=o[0]
for(var s=1;s<o.length;s++)s>1&&(n+=" "),n+=o[s]}var u=t.createElement(e)
if(n&&(u.className=n),i)for(var a in i)"text"==a?u.appendChild(t.createTextNode(i.text)):"html"==a?u.innerHTML=i.html:"parent"==a&&hui.isDefined(i.parent)?i.parent.appendChild(u):"parentFirst"==a?0===i.parentFirst.childNodes.length?i.parentFirst.appendChild(u):i.parentFirst.insertBefore(u,i.parentFirst.childNodes[0]):"before"==a?i.before.parentNode.insertBefore(u,i.before):"className"==a?u.className=i.className:"class"==a?u.className=i["class"]:"style"==a&&"object"==typeof i[a]?hui.style.set(u,i[a]):"style"==a&&(hui.browser.msie7||hui.browser.msie6)?u.style.setAttribute("cssText",i[a]):hui.isDefined(i[a])&&u.setAttribute(a,i[a])
return u},hui.position={getTop:function(e){if(e=hui.get(e)){for(var i=e.offsetTop,t=e.offsetParent;null!==t;)i+=t.offsetTop,t=t.offsetParent
return i}return 0},getLeft:function(e){if(e=hui.get(e)){for(var i=e.offsetLeft,t=e.offsetParent;null!==t;)i+=t.offsetLeft,t=t.offsetParent
return i}return 0},get:function(e){return{left:hui.position.getLeft(e),top:hui.position.getTop(e)}},getScrollOffset:function(e){e=hui.get(e)
var i=0,t=0
do if(i+=e.scrollTop||0,t+=e.scrollLeft||0,e=e.parentNode,e&&"HTML"===e.tagName)break
while(e)
return{top:i,left:t}},place:function(e){var i=0,t=0,n=hui.get(e.source.element),o=hui.get(e.target.element),s={left:hui.position.getLeft(o),top:hui.position.getTop(o)}
if(i=s.left+o.clientWidth*(e.target.horizontal||0),t=s.top+o.clientHeight*(e.target.vertical||0),i-=n.clientWidth*(e.source.horizontal||0),t-=n.clientHeight*(e.source.vertical||0),e.top&&(t+=e.top),e.left&&(i+=e.left),e.insideViewPort){var u=hui.window.getViewWidth()
i+n.clientWidth>u&&(i=u-n.clientWidth-(e.viewPartMargin||0)),0>i&&(i=0),0>t&&(t=0)
var a=(hui.window.getViewHeight(),hui.window.getScrollTop()+hui.window.getViewHeight()-n.clientHeight),r=hui.window.getScrollTop()
t=Math.max(Math.min(t,a),r)}n.style.top=Math.round(t)+"px",n.style.left=Math.round(i)+"px"},getRemainingHeight:function(e){for(var i=e.parentNode.clientHeight,t=e.parentNode.childNodes,n=0;n<t.length;n++){var o=t[n]
o!==e&&hui.dom.isElement(t[n])&&"absolute"!=hui.style.get(o,"position")&&(i-=o.offsetHeight)}return i}},hui.window={getScrollTop:function(){return window.pageYOffset?window.pageYOffset:document.documentElement&&document.documentElement.scrollTop?document.documentElement.scrollTop:document.body?document.body.scrollTop:0},getScrollLeft:function(){return window.pageYOffset?window.pageXOffset:document.documentElement&&document.documentElement.scrollTop?document.documentElement.scrollLeft:document.body?document.body.scrollLeft:0},scrollTo:function(e){e=hui.override({duration:0,top:0},e)
var i=e.element,t=hui.position.get(i),n=hui.window.getScrollTop(),o=hui.window.getViewHeight(),s=n+o
if(n<t.top+i.clientHeight||t.top<s){var u=t.top-Math.round((o-i.clientHeight)/2)
u-=e.top/2,u=Math.max(0,u)
var a,r=(new Date).getTime()
a=function(){var i=((new Date).getTime()-r)/e.duration
i>1&&(i=1),window.scrollTo(0,n+Math.round((u-n)*hui.ease.fastSlow(i))),1>i&&window.setTimeout(a)},a()}},getViewHeight:function(){return window.innerHeight?window.innerHeight:document.documentElement&&document.documentElement.clientHeight?document.documentElement.clientHeight:document.body?document.body.clientHeight:void 0},getViewWidth:function(){return window.innerWidth?window.innerWidth:document.documentElement&&document.documentElement.clientWidth?document.documentElement.clientWidth:document.body?document.body.clientWidth:void 0}},hui.cls={has:function(e,i){if(e=hui.get(e),!e||!e.className)return!1
if(e.hasClassName)return e.hasClassName(i)
if(e.className==i)return!0
if(void 0!==e.className.animVal)return!1
for(var t=e.className.split(/\s+/),n=0;n<t.length;n++)if(t[n]==i)return!0
return!1},add:function(e,i){e=hui.get(e),e&&(e.addClassName&&e.addClassName(i),hui.cls.remove(e,i),e.className+=" "+i)},remove:function(e,i){if(e=hui.get(e),e&&e.className){if(e.removeClassName&&e.removeClassName(i),e.className==i)return void(e.className="")
for(var t="",n=e.className.split(/\s+/),o=0;o<n.length;o++)n[o]!=i&&(o>0&&(t+=" "),t+=n[o])
e.className=t}},toggle:function(e,i){hui.cls.has(e,i)?hui.cls.remove(e,i):hui.cls.add(e,i)},set:function(e,i,t){t?hui.cls.add(e,i):hui.cls.remove(e,i)}},hui.on=function(e,i,t,n){if("tap"==i){n&&(t=t.bind(n))
var o=!1,s=!1
hui.listen(e,"touchstart",function(){s=!0,o=!1},n),hui.listen(e,"touchmove",function(){o=!0},n),hui.listen(e,"touchcancel",function(){console.log("cancel")},n),hui.listen(e,"touchend",function(e){s=!1,o||(s=!0,t(e))},n),hui.listen(e,"click",function(e){o||s||t(e)},n)}else hui.listen(e,i,t,n)},hui.listen=function(e,i,t,n){e=hui.get(e),e&&(n&&(t=t.bind(n)),document.addEventListener?e.addEventListener(i,t):e.attachEvent("on"+i,t))},hui.listenOnce=function(e,i,t){var n=null
n=function(o){hui.unListen(e,i,n),t(o)},hui.listen(e,i,n)},hui.unListen=function(e,i,t,n){e=hui.get(e),document.removeEventListener?e.removeEventListener(i,t,!!n):e.detachEvent("on"+i,t)},hui.event=function(e){return void 0!==e&&e.huiEvent===!0?e:new hui.Event(e)},hui.Event=function(e){this.huiEvent=!0,this.event=e=e||window.event,e||hui.log("No event"),this.element=e.target?e.target:e.srcElement,this.shiftKey=e.shiftKey,this.altKey=e.altKey,this.metaKey=e.metaKey,this.returnKey=13==e.keyCode,this.escapeKey=27==e.keyCode,this.spaceKey=32==e.keyCode,this.backspaceKey=8==e.keyCode,this.upKey=38==e.keyCode,this.downKey=40==e.keyCode,this.leftKey=37==e.keyCode,this.rightKey=39==e.keyCode,this.keyCode=e.keyCode},hui.Event.prototype={getLeft:function(){var e=0
return this.event&&(this.event.pageX?e=this.event.pageX:this.event.clientX&&(e=this.event.clientX+hui.window.getScrollLeft())),e},getTop:function(){var e=0
return this.event&&(this.event.pageY?e=this.event.pageY:this.event.clientY&&(e=this.event.clientY+hui.window.getScrollTop())),e},getElement:function(){return this.element},findByClass:function(e){return hui.get.firstAncestorByClass(this.element,e)},findByTag:function(e){for(var i=this.element;i;){if(i.tagName&&i.tagName.toLowerCase()==e)return i
i=i.parentNode}return null},find:function(e){for(var i=this.element;i;){if(i.tagName&&i.tagName.toLowerCase()==tag&&e(i))return i
i=i.parentNode}return null},isDescendantOf:function(e){for(var i=this.element;i;){if(i===e)return!0
i=i.parentNode}return!1},stop:function(){hui.stop(this.event)}},hui.stop=function(e){e||(e=window.event),e.stopPropagation&&e.stopPropagation(),e.preventDefault&&e.preventDefault(),e.cancelBubble=!0,e.stopped=!0},hui._=hui._||[],hui._ready="complete"==document.readyState,hui.onReady=function(e){hui._ready?e():hui._.push(e)},hui.onDraw=function(e){window.setTimeout(e,13)},hui.onDraw=function(e,i){for(var t=i.requestAnimationFrame,n=0;n<e.length&&!t;++n)t=i[e[n]+"RequestAnimationFrame"]
return t?t.bind(i):hui.onDraw}(["ms","moz","webkit","o"],window),hui._onReady=function(e){if("interactive"==document.readyState)window.setTimeout(e)
else if(window.addEventListener)window.addEventListener("DOMContentLoaded",e,!1)
else if("undefined"!=typeof document.addEventListener)document.addEventListener("load",e,!1)
else if("undefined"!=typeof window.attachEvent)window.attachEvent("onload",e)
else if("function"==typeof window.onload){var i=window.onload
window.onload=function(){i(),e()}}else window.onload=e},hui.request=function(e){e=hui.override({method:"POST",async:!0,headers:{Ajax:!0}},e)
var i=new XMLHttpRequest
if(i){i.onreadystatechange=function(){4==i.readyState&&(200==i.status&&e.$success?e.$success(i):403==i.status&&e.$forbidden?e.$forbidden(i):0!==i.status&&e.$failure?e.$failure(i):0===i.status&&e.$abort&&e.$abort(i),e.$finally&&e.$finally())}
var t=e.method.toUpperCase()
i.open(t,e.url,e.async)
var n=null
if("POST"==t&&e.file){if(n=new FormData,n.append("file",e.file),e.parameters)for(var o in e.parameters)n.append(o,e.parameters[o])
e.$progress&&i.upload.addEventListener("progress",function(i){e.$progress(i.loaded,i.total)},!1),e.$load&&i.upload.addEventListener("load",function(i){e.$load()},!1)}else if("POST"==t&&e.files){n=new FormData
for(var s=0;s<e.files.length;s++)n.append("file"+s,e.files[s])}else"POST"==t&&e.parameters?(n=hui.request._buildPostBody(e.parameters),i.setRequestHeader("Content-type","application/x-www-form-urlencoded; charset=utf-8")):n=""
if(e.headers)for(var u in e.headers)i.setRequestHeader(u,e.headers[u])
return i.send(n),i}},hui.request.isXMLResponse=function(e){return e.responseXML&&e.responseXML.documentElement&&"parsererror"!=e.responseXML.documentElement.nodeName},hui.request._buildPostBody=function(e){if(!e)return null
var i,t=""
if(hui.isArray(e))for(var n=0;n<e.length;n++)i=e[n],n>0&&(t+="&"),t+=encodeURIComponent(i.name)+"=",void 0!==i.value&&null!==i.value&&(t+=encodeURIComponent(i.value))
else for(i in e)t.length>0&&(t+="&"),t+=encodeURIComponent(i)+"=",void 0!==e[i]&&null!==e[i]&&(t+=encodeURIComponent(e[i]))
return t},hui.style={copy:function(e,i,t){for(var n=0;n<t.length;n++){var o=t[n],s=hui.style.get(e,o)
s&&(i.style[hui.string.camelize(o)]=s)}},set:function(e,i){for(var t in i)"transform"===t?e.style.webkitTransform=i[t]:"opacity"===t?hui.style.setOpacity(e,i[t]):e.style[t]=i[t]},get:function(e,i){e=hui.get(e)
var t=hui.string.camelize(i),n=e.style[t]
if(!n)if(document.defaultView&&document.defaultView.getComputedStyle){var o=document.defaultView.getComputedStyle(e,null)
n=o?o.getPropertyValue(i):null}else e.currentStyle&&(n=e.currentStyle[t])
return window.opera&&hui.array.contains(["left","top","right","bottom"],i)&&"static"==hui.style.get(e,"position")&&(n="auto"),"auto"==n?"":n},setOpacity:function(e,i){hui.browser.opacity?e.style.opacity=i:1==i?e.style.filter=null:e.style.filter="alpha(opacity="+100*i+")"},length:function(e){return"number"==typeof e?e+"px":e}},hui.frame={getDocument:function(e){return e.contentDocument?e.contentDocument:e.contentWindow?e.contentWindow.document:e.document?e.document:void 0},getWindow:function(e){return e.defaultView?e.defaultView:e.contentWindow?e.contentWindow:void 0}},hui.selection={clear:function(){var e
document.selection&&document.selection.empty?document.selection.empty():window.getSelection&&(e=window.getSelection(),e&&e.removeAllRanges&&e.removeAllRanges())},getText:function(e){return e=e||document,e.getSelection?e.getSelection()+"":e.selection?e.selection.createRange().text:""},getNode:function(e){if(e=e||document,e.getSelection){var i=e.getSelection().getRangeAt(0)
return"function"==typeof i.commonAncestorContainer?i.commonAncestorContainer():i.commonAncestorContainer}return null},get:function(e){return{node:hui.selection.getNode(e),text:hui.selection.getText(e)}},enable:function(e){document.onselectstart=e?null:function(){return!1},document.body.style.webkitUserSelect=e?null:"none"}},hui.effect={makeFlippable:function(e){hui.browser.webkit?(hui.cls.add(e.container,"hui_flip_container"),hui.cls.add(e.front,"hui_flip_front"),hui.cls.add(e.back,"hui_flip_back")):(hui.cls.add(e.front,"hui_flip_front_legacy"),hui.cls.add(e.back,"hui_flip_back_legacy"))},flip:function(e){if(hui.browser.webkit){var i=hui.get(e.element),t=e.duration||"1s",n=hui.get.firstByClass(i,"hui_flip_front"),o=hui.get.firstByClass(i,"hui_flip_back")
n.style.webkitTransitionDuration=t,o.style.webkitTransitionDuration=t,hui.cls.toggle(e.element,"hui_flip_flipped")}else hui.cls.toggle(e.element,"hui_flip_flipped_legacy")},bounceIn:function(e){var i=e.element
hui.browser.msie?hui.style.set(i,{display:"block",visibility:"visible"}):(hui.style.set(i,{display:"block",opacity:0,visibility:"visible"}),hui.animate(i,"transform","scale(0.1)",0),window.setTimeout(function(){hui.animate(i,"opacity",1,300),hui.animate(i,"transform","scale(1)",400,{ease:hui.ease.backOut})}))},fadeIn:function(e){var i=e.element
"none"==hui.style.get(i,"display")&&hui.style.set(i,{opacity:0,display:"inherit"}),hui.animate({node:i,css:{opacity:1},delay:e.delay||null,duration:e.duration||500,$complete:e.onComplete||e.$complete})},fadeOut:function(e){hui.animate({node:e.element,css:{opacity:0},delay:e.delay||null,duration:e.duration||500,hideOnComplete:!0,complete:e.onComplete||e.$complete})},wiggle:function(e){hui.ui.getElement(e.element)
hui.cls.add(e.element,"hui_effect_wiggle"),window.setTimeout(function(){hui.cls.remove(e.element,"hui_effect_wiggle")},e.duration||1e3)},shake:function(e){this._do(e.element,"hui_effect_shake",e.duration||1e3)},tada:function(e){this._do(e.element,"hui_effect_tada",1e3)},_do:function(e,i,t){e=hui.ui.getElement(e),hui.cls.add(e,i),window.setTimeout(function(){hui.cls.remove(e,i)},t)}},hui.document={getWidth:function(){return Math.max(document.body.clientWidth,document.documentElement.clientWidth,document.documentElement.scrollWidth)},getHeight:function(){if(hui.browser.msie6){for(var e=Math.max(document.body.clientHeight,document.documentElement.clientHeight,document.documentElement.scrollHeight),i=document.body.childNodes,t=0;t<i.length;t++)hui.dom.isElement(i[t])&&(e=Math.max(e,i[t].clientHeight))
return e}return window.scrollMaxY&&window.innerHeight?window.scrollMaxY+window.innerHeight:Math.max(document.body.clientHeight,document.documentElement.clientHeight,document.documentElement.scrollHeight)}},hui.drag={register:function(e){var i=e.touch&&hui.browser.touch
hui.listen(e.element,i?"touchstart":"mousedown",function(i){i=hui.event(i),e.$check&&e.$check(i)===!1||(i.stop(),hui.drag.start(e,i))})},start:function(e,i){var t=hui.browser.msie?document:window,n=e.touch&&hui.browser.touch
e.onStart&&e.onStart()
var o,s,u=({x:i.getLeft(),y:i.getTop(),time:Date.now()},!1)
o=function(i){i=hui.event(i),i.stop(i),!u&&e.onBeforeMove&&e.onBeforeMove(i),u=!0,e.onMove(i)}.bind(this),hui.listen(t,n?"touchmove":"mousemove",o),s=function(){hui.unListen(t,n?"touchmove":"mousemove",o),hui.unListen(t,n?"touchend":"mouseup",s),e.onEnd&&e.onEnd(),u&&e.onAfterMove&&e.onAfterMove(),!u&&e.onNotMoved&&e.onNotMoved(),hui.selection.enable(!0)}.bind(this),hui.listen(t,n?"touchend":"mouseup",s),hui.selection.enable(!1)},_nativeListeners:[],_activeDrop:null,listen:function(e){hui.browser.msie||(hui.drag._nativeListeners.push(e),hui.drag._nativeListeners.length>1||(hui.listen(document.body,"dragenter",function(e){for(var i=hui.drag._nativeListeners,t=null,n=0;n<i.length;n++){var o=i[n].element
if(hui.dom.isDescendantOrSelf(e.target,o)){t=i[n],null!==hui.drag._activeDrop&&hui.drag._activeDrop==t||hui.cls.add(o,t.hoverClass)
break}}hui.drag._activeDrop&&(hui.drag._activeDrop!=t?(hui.cls.remove(hui.drag._activeDrop.element,hui.drag._activeDrop.hoverClass),hui.drag._activeDrop.$leave&&hui.drag._activeDrop.$leave(e)):hui.drag._activeDrop.$hover&&hui.drag._activeDrop.$hover(e)),hui.drag._activeDrop=t}),hui.listen(document.body,"dragover",function(e){hui.stop(e),hui.drag._activeDrop&&hui.drag._activeDrop.$hover&&hui.drag._activeDrop.$hover(e)}),hui.listen(document.body,"dragend",function(e){hui.log("drag end")}),hui.listen(document.body,"dragstart",function(e){hui.log("drag start")}),hui.listen(document.body,"drop",function(e){var i=hui.event(e)
i.stop()
var t=hui.drag._activeDrop
if(hui.drag._activeDrop=null,t&&(hui.cls.remove(t.element,t.hoverClass),t.$drop&&t.$drop(e,{event:i}),e.dataTransfer))if(hui.log(e.dataTransfer.types),t.$dropFiles&&e.dataTransfer.files&&e.dataTransfer.files.length>0)t.$dropFiles(e.dataTransfer.files,{event:i})
else if(t.$dropURL&&null!==e.dataTransfer.types&&(hui.array.contains(e.dataTransfer.types,"public.url")||hui.array.contains(e.dataTransfer.types,"text/uri-list"))){var n=e.dataTransfer.getData("public.url"),o=e.dataTransfer.getData("text/uri-list")
n&&!hui.string.startsWith(n,"data:")?t.$dropURL(n,{event:i}):o&&!hui.string.startsWith(n,"data:")&&t.$dropURL(o,{event:i})}else t.$dropText&&null!==e.dataTransfer.types&&hui.array.contains(e.dataTransfer.types,"text/plain")&&t.$dropText(e.dataTransfer.getData("text/plain"),{event:i})})))}},hui.Preloader=function(e){this.options=e||{},this.delegate={},this.images=[],this.loaded=0},hui.Preloader.prototype={addImages:function(e){if("object"==typeof e)for(var i=0;i<e.length;i++)this.images.push(e[i])
else this.images.push(e)},setDelegate:function(e){this.delegate=e},load:function(e){e=e||0
var i=this
this.obs=[]
for(var t=function(){i._imageChanged(this.huiPreloaderIndex,"imageDidLoad")},n=function(){i._imageChanged(this.huiPreloaderIndex,"imageDidGiveError")},o=function(){i._imageChanged(this.huiPreloaderIndex,"imageDidAbort")},s=e;s<this.images.length+e;s++){var u=s
u>=this.images.length&&(u-=this.images.length)
var a=new Image
a.huiPreloaderIndex=u,a.onload=t,a.onerror=n,a.onabort=o,a.src=(this.options.context?this.options.context:"")+this.images[u],this.obs.push(a)}},_imageChanged:function(e,i){this.loaded++,this.delegate[i]&&this.delegate[i](this.loaded,this.images.length,e),this.loaded==this.images.length&&this.delegate.allImagesDidLoad&&this.delegate.allImagesDidLoad()}},hui.cookie={set:function(e,i,t){var n
if(t){var o=new Date
o.setTime(o.getTime()+24*t*60*60*1e3),n="; expires="+o.toGMTString()}else n=""
document.cookie=e+"="+i+n+"; path=/"},get:function(e){for(var i=e+"=",t=document.cookie.split(";"),n=0;n<t.length;n++){for(var o=t[n];" "==o.charAt(0);)o=o.substring(1,o.length)
if(0===o.indexOf(i))return o.substring(i.length,o.length)}return null},clear:function(e){this.set(e,"",-1)}},hui.location={getParameter:function(e){for(var i=hui.location.getParameters(),t=0;t<i.length;t++)if(i[t].name==e)return i[t].value
return null},setParameter:function(e,i){for(var t=hui.location.getParameters(),n=!1,o=0;o<t.length;o++)if(t[o].name==e){t[o].value=i,n=!0
break}n||t.push({name:e,value:i}),hui.location.setParameters(t)},hasHash:function(e){var i=document.location.hash
return""!==i?i=="#"+e:!1},getHash:function(){var e=document.location.hash
return""!==e?e.substring(1):null},getHashParameter:function(e){var i=document.location.hash
if(""!==i){var t=i.indexOf(e+"=")
if(-1!==t){var n=i.substring(t+e.length+1)
return-1!==n.indexOf("&")?n.substring(0,n.indexOf("&")):n}}return null},clearHash:function(){document.location.hash="#"},setParameters:function(e){for(var i="",t=0;t<e.length;t++)i+=0===t?"?":"&",i+=e[t].name+"="+e[t].value
document.location.search=i},getBoolean:function(e){var i=hui.location.getParameter(e)
return"true"==i||"1"==i},getInt:function(e){var i=parseInt(hui.location.getParameter(e))
return isNaN(i)?null:i},getParameters:function(){for(var e=document.location.search.substring(1).split("&"),i=[],t=0;t<e.length;t++){var n=e[t].split("="),o=unescape(n[0]).replace(/^\s*|\s*$/g,""),s=unescape(n[1]).replace(/^\s*|\s*$/g,"")
o&&i.push({name:o,value:s})}return i}},window.define&&define("hui",hui),hui._onReady(function(){hui._ready=!0
for(var e=0;e<hui._.length;e++)hui._[e]()
delete hui._}),hui=window.hui||{},hui.animate=function(e,i,t,n,o){if("string"==typeof e||hui.dom.isElement(e))hui.animation.get(e).animate(null,t,i,n,o)
else{var s=hui.animation.get(e.node)
if(e.property)s.animate(null,e.value,e.property,e.duration,e)
else if(e.css){var u=e
for(var a in e.css)s.animate(null,e.css[a],a,e.duration,u),u=hui.override({},e),u.$complete=void 0}else s.animate(null,"","",e.duration,e)}},hui.animation={objects:{},running:!1,latestId:0,get:function(e){return e=hui.get(e),e.huiAnimationId||(e.huiAnimationId=this.latestId++),this.objects[e.huiAnimationId]||(this.objects[e.huiAnimationId]=new hui.animation.Item(e)),this.objects[e.huiAnimationId]},start:function(){this.running||hui.animation._render()}},hui.animation._lengthUpater=function(e,i,t){e.style[t.property]=t.from+(t.to-t.from)*i+(null!=t.unit?t.unit:"")},hui.animation._transformUpater=function(e,i,t){var n=t.transform,o=""
n.rotate&&(o+=" rotate("+(n.rotate.from+(n.rotate.to-n.rotate.from)*i)+n.rotate.unit+")"),n.scale&&(o+=" scale("+(n.scale.from+(n.scale.to-n.scale.from)*i)+")"),e.style[hui.animation.TRANSFORM]=o},hui.animation._colorUpater=function(e,i,t){var n=Math.round(t.from.red+(t.to.red-t.from.red)*i),o=Math.round(t.from.green+(t.to.green-t.from.green)*i),s=Math.round(t.from.blue+(t.to.blue-t.from.blue)*i)
value="rgb("+n+","+o+","+s+")",e.style[t.property]=value},hui.animation._propertyUpater=function(e,i,t){e[t.property]=Math.round(t.from+(t.to-t.from)*i)},hui.animation._ieOpacityUpdater=function(e,i,t){var n=t.from+(t.to-t.from)*i
1==n?e.style.removeAttribute("filter"):e.style.filter="alpha(opacity="+100*n+")"},hui.animation._render=function(){hui.animation.running=!0
var e=!1,i=Date.now()
for(var t in hui.animation.objects){var n=hui.animation.objects[t]
if(n.work)for(var o=n.element,s=0;s<n.work.length;s++){var u=n.work[s]
if(!u.finished){var a=(i-u.start)/(u.end-u.start)
if(0>a)e=!0
else{isNaN(a)||a>1?a=1:1>a&&(e=!0)
var r=a
u.delegate&&u.delegate.ease&&(r=u.delegate.ease(r)),u.delegate&&u.delegate.$render?u.delegate.$render(o,r):u.delegate&&u.delegate.callback?u.delegate.callback(o,r):u.updater&&u.updater(o,r,u),1==a&&(u.finished=!0,u.delegate&&u.delegate.$complete?window.setTimeout(u.delegate.$complete):u.delegate&&u.delegate.onComplete?window.setTimeout(u.delegate.onComplete):u.delegate&&u.delegate.hideOnComplete&&(o.style.display="none"))}}}}e?hui.onDraw(hui.animation._render):hui.animation.running=!1},hui.animation._parseStyle=function(e){var i,t={type:null,value:null,unit:null}
if(!hui.isDefined(e))return t
if(isNaN(e))if(i=e.match(/([\-]?[0-9\.]+)(px|pt|%)/))t.type="length",t.value=parseFloat(i[1]),t.unit=i[2]
else if(i=e.match(/rgb\(([0-9]+),[ ]?([0-9]+),[ ]?([0-9]+)\)/))t.type="color",t.value={red:parseInt(i[1]),green:parseInt(i[2]),blue:parseInt(i[3])}
else{var n=new hui.Color(e)
n.ok&&(t.type="color",t.value={red:n.r,green:n.g,blue:n.b})}else t.value=parseFloat(e)
return t},hui.animation.Item=function(e){this.element=e,this.work=[]},hui.animation.Item.prototype.animate=function(e,i,t,n,o){var s=this.getWork(hui.string.camelize(t))
s.delegate=o,s.finished=!1
var u=!("scrollLeft"==t||"scrollTop"==t||""==t)
if(null!==e)s.from=e
else if("transform"==t)s.transform=hui.animation.Item.parseTransform(i,this.element)
else if(hui.browser.opacity||"opacity"!=t)if(u){var a=hui.style.get(this.element,t),r=hui.animation._parseStyle(a)
s.from=r.value}else s.from=this.element[t]
else s.from=this._getIEOpacity(this.element)
if(u){var h=hui.animation._parseStyle(i)
s.to=h.value,s.unit=h.unit,hui.browser.opacity||"opacity"!=t?"transform"==t?s.updater=hui.browser.msie?function(){}:hui.animation._transformUpater:"color"==h.type?s.updater=hui.animation._colorUpater:s.updater=hui.animation._lengthUpater:s.updater=hui.animation._ieOpacityUpdater}else s.to=i,s.unit=null,s.updater=hui.animation._propertyUpater
s.start=Date.now(),o&&o.delay&&(s.start+=o.delay),s.end=s.start+n,hui.animation.start()},hui.animation.TRANSFORM=function(){var e=navigator.userAgent,i=-1!==e.indexOf("Gecko")&&-1===e.indexOf("WebKit")
return i?"MozTransform":"WebkitTransform"}(),hui.animation.Item.parseTransform=function(e,i){var t,n,o={},s=/rotate\(([0-9\.]+)([a-z]+)\)/i,u=e.match(s)
u&&(t=0,i.style[hui.animation.TRANSFORM]&&(n=i.style[hui.animation.TRANSFORM].match(s),n&&(t=parseFloat(n[1]))),o.rotate={from:t,to:parseFloat(u[1]),unit:u[2]})
var a=/scale\(([0-9\.]+)\)/i,r=e.match(a)
return r&&(t=1,i.style[hui.animation.TRANSFORM]&&(n=i.style[hui.animation.TRANSFORM].match(a),n&&(t=parseFloat(n[1]))),o.scale={from:t,to:parseFloat(r[1])}),o},hui.animation.Item.prototype._getIEOpacity=function(e){var i,t=hui.style.get(e,"filter").toLowerCase()
return(i=t.match(/opacity=([0-9]+)/))?parseFloat(i[1])/100:1},hui.animation.Item.prototype.getWork=function(e){for(var i=this.work.length-1;i>=0;i--)if(this.work[i].property===e)return this.work[i]
var t={property:e}
return this.work[this.work.length]=t,t},hui.animation.Loop=function(e){this.recipe=e,this.position=-1,this.running=!1},hui.animation.Loop.prototype.next=function(){this.position++,this.position>=this.recipe.length&&(this.position=0)
var e=this.recipe[this.position]
"function"==typeof e?e():e.element&&hui.animate(e.element,e.property,e.value,e.duration,{ease:e.ease})
var i=this,t=e.duration||0
void 0!==e.wait&&(t=e.wait),window.setTimeout(function(){i.next()},t)},hui.animation.Loop.prototype.start=function(){this.running=!0,this.next()},hui.ease={slowFastSlow:function(e){var i=1.6,t=1.4
return-1*Math.pow(Math.cos(Math.PI/2*Math.pow(e,i)),Math.pow(Math.PI,t))+1},fastSlow:function(e){var i=.5,t=.7
return-1*Math.pow(Math.cos(Math.PI/2*Math.pow(e,i)),Math.pow(Math.PI,t))+1},elastic:function(e){return 1-hui.ease.elastic2(1-e)},elastic2:function(e,i,t){if(0>=e||e>=1)return e
t||(t=.45)
var n
return!i||1>i?(i=1,n=t/4):n=t/(2*Math.PI)*Math.asin(1/i),-(i*Math.pow(2,10*(e-=1))*Math.sin((e-n)*(2*Math.PI)/t))},bounce:function(e){return 1/2.75>e?7.5625*e*e:2/2.75>e?7.5625*(e-=1.5/2.75)*e+.75:2.5/2.75>e?7.5625*(e-=2.25/2.75)*e+.9375:7.5625*(e-=2.625/2.75)*e+.984375},flicker:function(e){return 1==e?1:Math.random()*e},quadIn:function(e){return Math.pow(e,2)},quadOut:function(e){return e*(e-2)*-1},quadInOut:function(e){return e=2*e,1>e?Math.pow(e,2)/2:-1*(--e*(e-2)-1)/2},cubicIn:function(e){return Math.pow(e,3)},cubicOut:function(e){return Math.pow(e-1,3)+1},cubicInOut:function(e){return e=2*e,1>e?Math.pow(e,3)/2:(e-=2,(Math.pow(e,3)+2)/2)},quartIn:function(e){return Math.pow(e,4)},quartOut:function(e){return-1*(Math.pow(e-1,4)-1)},quartInOut:function(e){return e=2*e,1>e?Math.pow(e,4)/2:(e-=2,-0.5*(Math.pow(e,4)-2))},quintIn:function(e){return Math.pow(e,5)},quintOut:function(e){return Math.pow(e-1,5)+1},quintInOut:function(e){return e=2*e,1>e?Math.pow(e,5)/2:(e-=2,(Math.pow(e,5)+2)/2)},sineIn:function(e){return-1*Math.cos(e*(Math.PI/2))+1},sineOut:function(e){return Math.sin(e*(Math.PI/2))},sineInOut:function(e){return-1*(Math.cos(Math.PI*e)-1)/2},expoIn:function(e){return 0==e?0:Math.pow(2,10*(e-1))},expoOut:function(e){return 1==e?1:-1*Math.pow(2,-10*e)+1},expoInOut:function(e){return 0==e?0:1==e?1:(e=2*e,1>e?Math.pow(2,10*(e-1))/2:(--e,(-1*Math.pow(2,-10*e)+2)/2))},circIn:function(e){return-1*(Math.sqrt(1-Math.pow(e,2))-1)},circOut:function(e){return e-=1,Math.sqrt(1-Math.pow(e,2))},circInOut:function(e){return e=2*e,1>e?-0.5*(Math.sqrt(1-Math.pow(e,2))-1):(e-=2,.5*(Math.sqrt(1-Math.pow(e,2))+1))},backIn:function(e){var i=1.70158
return Math.pow(e,2)*((i+1)*e-i)},backOut:function(e){e-=1
var i=1.70158
return Math.pow(e,2)*((i+1)*e+i)+1},backInOut:function(e){var i=2.5949095
return e=2*e,1>e?Math.pow(e,2)*((i+1)*e-i)/2:(e-=2,(Math.pow(e,2)*((i+1)*e+i)+2)/2)},elasticIn:function(e){if(0==e)return 0
if(1==e)return 1
var i=.3,t=i/4
return e-=1,-1*Math.pow(2,10*e)*Math.sin((e-t)*(2*Math.PI)/i)},elasticOut:function(e){if(0==e)return 0
if(1==e)return 1
var i=.3,t=i/4
return Math.pow(2,-10*e)*Math.sin((e-t)*(2*Math.PI)/i)+1},elasticInOut:function(e){if(0==e)return 0
if(e=2*e,2==e)return 1
var i=.3*1.5,t=i/4
return 1>e?(e-=1,-.5*(Math.pow(2,10*e)*Math.sin((e-t)*(2*Math.PI)/i))):(e-=1,.5*(Math.pow(2,-10*e)*Math.sin((e-t)*(2*Math.PI)/i))+1)},bounceIn:function(e){return 1-hui.ease.bounceOut(1-e)},bounceOut:function(e){var i,t=7.5625,n=2.75
return 1/n>e?i=t*Math.pow(e,2):2/n>e?(e-=1.5/n,i=t*Math.pow(e,2)+.75):2.5/n>e?(e-=2.25/n,i=t*Math.pow(e,2)+.9375):(e-=2.625/n,i=t*Math.pow(e,2)+.984375),i},bounceInOut:function(e){return.5>e?hui.ease.bounceIn(2*e)/2:hui.ease.bounceOut(2*e-1)/2+.5}},Date.now||(Date.now=function(){return(new Date).getTime()}),hui.define&&hui.define("hui.animation",hui.animation),hui.Color=function(e){if(this.ok=!1,!hui.isBlank(e)){"#"==e.charAt(0)&&(e=e.substr(1,6)),e=e.replace(/ /g,""),e=e.toLowerCase()
for(var i in hui.Color.table)e==i&&(e=hui.Color.table[i])
for(var t=[{re:/^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/,process:function(e){return[parseInt(e[1]),parseInt(e[2]),parseInt(e[3])]}},{re:/^rgb\((\d{1,3})%,\s*(\d{1,3})%,\s*(\d{1,3})%\)$/,process:function(e){return[Math.round(parseInt(e[1])/100*255),Math.round(parseInt(e[2])/100*255),Math.round(parseInt(e[3])/100*255)]}},{re:/^(\w{2})(\w{2})(\w{2})$/,process:function(e){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}},{re:/^(\w{1})(\w{1})(\w{1})$/,process:function(e){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}}],n=0;n<t.length;n++){var o=t[n].re,s=t[n].process,u=o.exec(e)
if(u){var a=s(u)
this.r=a[0],this.g=a[1],this.b=a[2],this.ok=!0
break}}this.r=this.r<0||isNaN(this.r)?0:this.r>255?255:this.r,this.g=this.g<0||isNaN(this.g)?0:this.g>255?255:this.g,this.b=this.b<0||isNaN(this.b)?0:this.b>255?255:this.b}},hui.Color.prototype={toRGB:function(){return"rgb("+this.r+", "+this.g+", "+this.b+")"},isDefined:function(){return!(void 0===this.r||void 0===this.g||void 0===this.b)},toHex:function(){if(!this.isDefined())return null
var e=this.r.toString(16),i=this.g.toString(16),t=this.b.toString(16)
return 1==e.length&&(e="0"+e),1==i.length&&(i="0"+i),1==t.length&&(t="0"+t),"#"+e+i+t}},hui.Color.table={white:"ffffff",black:"000000",red:"ff0000",green:"00ff00",blue:"0000ff"},hui.Color.hex2rgb=function(e){if(hui.isBlank(e))return null
if("#"==e[0]&&(e=e.substr(1)),3==e.length){var i=e
e="",i=/^([a-f0-9])([a-f0-9])([a-f0-9])$/i.exec(i).slice(1)
for(var t=0;3>t;t++)e+=i[t]+i[t]}var n=/^([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i.exec(e).slice(1)
return{r:parseInt(n[0],16),g:parseInt(n[1],16),b:parseInt(n[2],16)}},hui.Color.hsv2rgb=function(e,i,t){var n,o,s,u=e/360
if(0===i)n=255*t,o=255*t,s=255*t
else{var a,r,h,l=6*u,d=Math.floor(l),c=t*(1-i),f=t*(1-i*(l-d)),p=t*(1-i*(1-(l-d)))
0===d?(a=t,r=p,h=c):1===d?(a=f,r=t,h=c):2==d?(a=c,r=t,h=p):3==d?(a=c,r=f,h=t):4==d?(a=p,r=c,h=t):(a=t,r=c,h=f),n=Math.round(255*a),o=Math.round(255*r),s=Math.round(255*h)}return new Array(n,o,s)},hui.Color.rgb2hsv=function(e,i,t){e/=255,i/=255,t/=255
var n,o,s=Math.min(Math.min(e,i),t),u=Math.max(Math.max(e,i),t),a=u
return u==s?o=0:u==e?o=60*((i-t)/(u-s))%360:u==i?o=60*((t-e)/(u-s))+120:u==t&&(o=60*((e-i)/(u-s))+240),0>o&&(o+=360),n=0===u?0:1-s/u,[Math.round(o),Math.round(100*n),Math.round(100*a)]},hui.Color.rgb2hex=function(e){for(var i="#",t=0;3>t;t++){var n=parseInt(e[t]).toString(16)
n.length<2&&(n="0"+n),i+=n}return i},hui.define&&hui.define("hui.Color",hui.Color),!function(e,i,t){function n(e,i){m(e,function(e){return!i(e)})}function o(e,t){var n=i.createElement("script"),o=l
n.onload=n.onerror=n[g]=function(){n[f]&&!/^c|loade/.test(n[f])||o||(n.onload=n[g]=null,o=1,t())},n.async=1,n.src=e,s.insertBefore(n,s.firstChild)}var s=i.getElementsByTagName("head")[0],u={},a={},r={},h={},l=!1,d="push",c="DOMContentLoaded",f="readyState",p="addEventListener",g="onreadystatechange",m=function(e,i){for(var t=0,n=e.length;n>t;++t)if(!i(e[t]))return l
return 1}
!i[f]&&i[p]&&(i[p](c,function w(){i.removeEventListener(c,w,l),i[f]="complete"},l),i[f]="loading")
var v=function(e,i,s){function l(e){return e.call?e():u[e]}function c(){if(!--y){u[g]=1,p&&p()
for(var e in r)m(e.split("|"),l)&&!n(r[e],l)&&(r[e]=[])}}e=e[d]?e:[e]
var f=i&&i.call,p=f?i:s,g=f?e.join(""):i,y=e.length
return t(function(){n(e,function(e){return h[e]?(g&&(a[g]=1),void c()):(h[e]=1,g&&(a[g]=1),void o(v.path?v.path+e+".js":e,c))})},0),v}
v.get=o,v.ready=function(e,i,t){e=e[d]?e:[e]
var o=[]
return!n(e,function(e){u[e]||o[d](e)})&&m(e,function(e){return u[e]})?i():!function(e){r[e]=r[e]||[],r[e][d](i),t&&t(o)}(e.join("|")),v}
var y=e.$script
v.noConflict=function(){return e.$script=y,this},"undefined"!=typeof module&&module.exports?module.exports=v:e.hui.require=v}(this,document,setTimeout),hui.parallax={_listeners:[],_init:function(){this._listening||(this._listening=!0,hui.listen(window,"scroll",this._scroll.bind(this)),hui.listen(window,"resize",this._resize.bind(this)),hui.onReady(this._resize.bind(this)))},_resize:function(){for(var e=this._listeners.length-1;e>=0;e--){var i=this._listeners[e]
i.$resize&&i.$resize(hui.window.getViewWidth(),hui.window.getViewHeight())}this._scroll()},_scroll:function(){for(var e=hui.window.getScrollTop(),i=hui.window.getViewHeight(),t=this._listeners.length-1;t>=0;t--){var n=this._listeners[t]
if(n.$scroll)if(n.debug&&!n.debugElement&&(n.debugElement=hui.build("div",{style:"position: absolute; border-top: 1px solid red; left: 0; right: 0;",parent:document.body})),n.element){var o=hui.position.getTop(n.element)
o+=n.element.clientHeight/2
var s=o-e,u=s/i
n.debugElement&&(n.debugElement.style.top=o+"px",n.debugElement.innerHTML="<span>"+u+"</span>"),n.$scroll(u)}else{var a=(e-n.min)/(n.max-n.min),r=hui.between(0,a,1)
n._latest!==r&&(n.$scroll(r),n._latest=r)}}},listen:function(e){this._listeners.push(e),this._init()}},hui.ui={domReady:!1,context:"",language:"en",objects:{},delegates:[],state:"default",latestObjectIndex:0,latestIndex:500,latestPanelIndex:1e3,latestAlertIndex:1500,latestTopIndex:2e3,toolTips:{},confirmOverlays:{},delayedUntilReady:[],texts:{request_error:{en:"An error occurred on the server",da:"Der skete en fejl på serveren"},"continue":{en:"Continue",da:"Fortsæt"},reload_page:{en:"Reload page",da:"Indæs siden igen"},access_denied:{en:"Access denied, maybe you are nolonger logged in",da:"Adgang nægtet, du er måske ikke længere logget ind"}}},hui.ui.get=function(e){return e?e.element?e:hui.ui.objects[e]:void 0},hui.ui.is=function(e,i){return e.__proto__==i.prototype},hui.ui.onReady=function(e){return hui.ui.domReady?e():hui.browser.gecko&&hui.string.endsWith(document.baseURI,"xml")?void window.setTimeout(e,1e3):void hui.ui.delayedUntilReady.push(e)},hui.ui._frameLoaded=function(e){hui.ui.callSuperDelegates(this,"frameLoaded",e)},hui.ui._resize=function(){hui.ui.reLayout(),window.clearTimeout(this._delayedResize),hui.ui._resizeFirst||(this._delayedResize=window.setTimeout(hui.ui._afterResize,500))},hui.ui._afterResize=function(){hui.onDraw(function(){hui.ui.callSuperDelegates(hui.ui,"$afterResize")})},hui.ui.confirmOverlay=function(e){var i,t=e.element
t||(t=document.body),e.widget&&(t=e.widget.getElement()),hui.ui.confirmOverlays[t]?(i=hui.ui.confirmOverlays[t],i.clear()):(i=hui.ui.Overlay.create({modal:!0}),hui.ui.confirmOverlays[t]=i),e.text&&i.addText(hui.ui.getTranslated(e.text))
var n=hui.ui.Button.create({text:hui.ui.getTranslated(e.okText)||"OK",highlighted:"true"})
n.click(function(){e.onOk?e.onOk():e.$ok&&e.$ok(),i.hide()}),i.add(n)
var o=hui.ui.Button.create({text:hui.ui.getTranslated(e.cancelText)||"Cancel"})
o.onClick(function(){e.onCancel?e.onCancel():e.$cancel&&e.$cancel(),i.hide()}),i.add(o),i.show({element:t})},hui.ui.destroy=function(e){"function"==typeof e.destroy&&e.destroy(),delete hui.ui.objects[e.name]},hui.ui.destroyDescendants=function(e){for(var i=hui.ui.getDescendants(e),t=(hui.ui.objects,0);t<i.length;t++)hui.ui.destroy(i[t])},hui.ui.getAncestors=function(e){var i=[],t=e.element
if(t){var n=hui.get.ancestors(t),o=[]
for(var s in hui.ui.objects)o.push(hui.ui.objects[s])
for(var u=0;u<n.length;u++)for(var a=0;a<o.length;a++)o[a].element==n[u]&&i.push(o[a])}return i},hui.ui.getDescendants=function(e){var i=[]
if(e){var t=e.getElement?e.getElement():e
if(t){var n=t.getElementsByTagName("*"),o=[]
for(var s in hui.ui.objects)o.push(hui.ui.objects[s])
for(var u=0;u<n.length;u++)for(var a=0;a<o.length;a++)n[u]==o[a].element&&i.push(o[a])}}return i},hui.ui.getAncestor=function(e,i){for(var t=hui.ui.getAncestors(e),n=0;n<t.length;n++)if(hui.cls.has(t[n].getElement(),i))return t[n]
return null},hui.ui.getComponents=function(e){var i=[],t=hui.ui.objects
for(var n in t)e(t[n])&&i.push(t[n])
return i},hui.ui.changeState=function(e){if(hui.ui.state!==e){var i,t,n=hui.ui.objects
for(i in n)t=n[i],t.options&&t.options.state&&(t.options.state==e?t.show():t.hide())
hui.ui.state=e,this.reLayout()}},hui.ui.reLayout=function(){for(var e=hui.ui.getDescendants(document.body),i=0;i<e.length;i++){var t=e[i]
t.$$layout&&t.$$layout()}},hui.ui.nextIndex=function(){return hui.ui.latestIndex++,hui.ui.latestIndex},hui.ui.nextPanelIndex=function(){return hui.ui.latestPanelIndex++,hui.ui.latestPanelIndex},hui.ui.nextAlertIndex=function(){return hui.ui.latestAlertIndex++,hui.ui.latestAlertIndex},hui.ui.nextTopIndex=function(){return hui.ui.latestTopIndex++,hui.ui.latestTopIndex},hui.ui.showCurtain=function(e){var i=e.widget
if(!i.curtain){i.curtain=hui.build("div",{"class":"hui_curtain",style:"z-index:none"})
var t=hui.get.firstByClass(document.body,"hui_body")
t||(t=document.body),t.appendChild(i.curtain),hui.listen(i.curtain,"click",function(){i.$curtainWasClicked&&i.$curtainWasClicked()})}var n=i.curtain
if(e.transparent)n.style.background="none"
else if(e.color)if("auto"==e.color){var o=hui.style.get(document.body,"background-color")
"transparent"!=o&&"rgba(0, 0, 0, 0)"!=o||(o="#fff"),n.style.backgroundColor=o}else n.style.backgroundColor=e.color
hui.browser.msie?n.style.height=hui.document.getHeight()+"px":(n.style.position="fixed",n.style.top="0",n.style.left="0",n.style.bottom="0",n.style.right="0"),n.style.zIndex=e.zIndex,e.transparent?n.style.display="block":(hui.style.setOpacity(n,0),n.style.display="block",hui.animate(n,"opacity",.7,1e3,{ease:hui.ease.slowFastSlow}))},hui.ui.hideCurtain=function(e){e.curtain&&hui.animate(e.curtain,"opacity",0,200,{hideOnComplete:!0})},hui.ui.getText=function(e){var i=this.texts[e]
return i?i[this.language]?i[this.language]:i.en:e},hui.ui.getTranslated=function(e){if(!hui.isDefined(e)||hui.isString(e)||"number"==typeof e)return e
if(e[hui.ui.language])return e[hui.ui.language]
if(e[null])return e[null]
for(var i in e)return e[i]},hui.ui.confirm=function(e){e.name||(e.name="huiConfirm")
var i,t=hui.ui.get(e.name)
if(t)t.update(e),i=hui.ui.get(name+"_ok"),i.setText(e.ok||"OK"),i.setHighlighted("ok"==e.highlighted),i.clearListeners(),hui.ui.get(name+"_cancel").setText(e.ok||"Cancel"),hui.ui.get(name+"_cancel").setHighlighted("cancel"==e.highlighted),e.cancel&&hui.ui.get(name+"_cancel").setText(e.cancel)
else{t=hui.ui.Alert.create(e)
var n=hui.ui.Button.create({name:name+"_cancel",text:e.cancel||"Cancel",highlighted:"cancel"===e.highlighted})
n.listen({$click:function(){t.hide(),e.onCancel&&e.onCancel(),hui.ui.callDelegates(t,"cancel")}}),t.addButton(n),i=hui.ui.Button.create({name:name+"_ok",text:e.ok||"OK",highlighted:"ok"===e.highlighted}),t.addButton(i)}i.listen({$click:function(){t.hide(),e.onOK&&e.onOK(),hui.ui.callDelegates(t,"ok")}}),t.show()},hui.ui.alert=function(e){this.alertBox?this.alertBox.update(e):(this.alertBox=hui.ui.Alert.create(e),this.alertBoxButton=hui.ui.Button.create({name:"huiAlertBoxButton",text:"OK"}),this.alertBoxButton.listen({$click$huiAlertBoxButton:function(){hui.ui.alertBox.hide(),hui.ui.alertBoxCallBack&&(hui.ui.alertBoxCallBack(),hui.ui.alertBoxCallBack=null)}}),this.alertBox.addButton(this.alertBoxButton)),this.alertBoxCallBack=e.onOK,this.alertBoxButton.setText(e.button?e.button:"OK"),this.alertBox.show()},hui.ui.showMessage=function(e){if("string"==typeof e&&(e={text:e}),e.delay)return void(hui.ui.messageDelayTimer=window.setTimeout(function(){e.delay=null,hui.ui.showMessage(e)},e.delay))
window.clearTimeout(hui.ui.messageDelayTimer),hui.ui.message||(hui.ui.message=hui.build("div",{"class":"hui_message",html:"<div><div></div></div>"}),hui.browser.msie||hui.style.setOpacity(hui.ui.message,0),document.body.appendChild(hui.ui.message))
var i=hui.ui.getTranslated(e.text)||"",t=hui.ui.message.getElementsByTagName("div")[1]
e.icon?(hui.dom.clear(t),t.appendChild(hui.ui.createIcon(e.icon,24)),hui.dom.addText(t,i)):e.busy?(t.innerHTML='<span class="hui_message_busy"></span>',hui.dom.addText(t,i)):hui.dom.setText(t,i),hui.ui.message.style.display="block",hui.ui.message.style.zIndex=hui.ui.nextTopIndex(),hui.ui.message.style.marginLeft=hui.ui.message.clientWidth/-2+"px",hui.ui.message.style.marginTop=hui.window.getScrollTop()+"px",hui.browser.opacity&&hui.animate(hui.ui.message,"opacity",1,300),window.clearTimeout(hui.ui.messageTimer),e.duration&&(hui.ui.messageTimer=window.setTimeout(hui.ui.hideMessage,e.duration))},hui.ui.msg=hui.ui.showMessage,hui.ui.msg.success=function(e){e=hui.override({icon:"common/success",duration:2e3},e),hui.ui.msg(e)},hui.ui.msg.fail=function(e){e=hui.override({icon:"common/warning",duration:3e3},e),hui.ui.msg(e)},hui.ui.hideMessage=function(){window.clearTimeout(hui.ui.messageDelayTimer),hui.ui.message&&(hui.browser.opacity?hui.animate(hui.ui.message,"opacity",0,300,{hideOnComplete:!0}):hui.ui.message.style.display="none")},hui.ui.showToolTip=function(e){var i=e.key||"common",t=hui.ui.toolTips[i]
t||(t=hui.build("div",{"class":"hui_tooltip",style:"display:none;",html:"<div><div></div></div>",parent:document.body}),hui.ui.toolTips[i]=t),t.onclick=function(){hui.ui.hideToolTip(e)}
var n=hui.get(e.element),o=hui.position.get(n)
hui.dom.setText(t.getElementsByTagName("div")[1],e.text),"none"==t.style.display&&hui.browser.opacity&&hui.style.setOpacity(t,0),hui.style.set(t,{display:"block",zIndex:hui.ui.nextTopIndex()}),hui.style.set(t,{left:o.left-t.clientWidth+4+"px",top:o.top+2-t.clientHeight/2+n.clientHeight/2+"px"}),hui.browser.opacity&&hui.animate(t,"opacity",1,300)},hui.ui.hideToolTip=function(e){var i=e?e.key||"common":"common",t=hui.ui.toolTips[i]
t&&(hui.browser.msie?t.style.display="none":hui.animate(t,"opacity",0,300,{hideOnComplete:!0}))},hui.ui.getElement=function(e){return hui.dom.isElement(e)?e:e.getElement?e.getElement():null},hui.ui.isWithin=function(e,i){e=hui.event(e)
var t=hui.position.get(i),n={width:i.offsetWidth,height:i.offsetHeight},o=e.getLeft(),s=e.getTop()
return o>t.left&&o<t.left+n.width&&s>t.top&&s<t.top+n.height},hui.ui.getIconUrl=function(e,i){return hui.ui.context+"/hui/icons/"+e+i+".png"},hui.ui.createIcon=function(e,i,t){return hui.build(t||"span",{"class":"hui_icon hui_icon_"+i,style:"background-image: url("+hui.ui.getIconUrl(e,i)+")"})},hui.ui.wrapInField=function(e){var i=hui.build("div",{"class":"hui_field",html:'<span class="hui_field_top"><span><span></span></span></span><span class="hui_field_middle"><span class="hui_field_middle"><span class="hui_field_content"></span></span></span><span class="hui_field_bottom"><span><span></span></span></span>'})
return hui.get.firstByClass(i,"hui_field_content").appendChild(e),i},hui.ui.addFocusClass=function(e){var i=e.classElement||e.element,t=e["class"]
hui.listen(e.element,"focus",function(){hui.cls.add(i,t),e.widget&&hui.ui.setKeyboardTarget(e.widget)}),hui.listen(e.element,"blur",function(){hui.cls.remove(i,t),e.widget&&hui.ui.setKeyboardTarget(null)})},hui.ui.keyboardTarget=null,hui.ui.setKeyboardTarget=function(e){hui.ui.keyboardTarget=e},hui.ui.stress=function(e){var i=hui.ui.getElement(e)
hui.effect.wiggle({element:i,duration:1e3})},hui.ui.positionAtElement=function(e,i,t){t=t||{},e=hui.get(e),i=hui.get(i)
var n=hui.style.get(e,"display")
"none"==n&&hui.style.set(e,{visibility:"hidden",display:"block"})
var o=hui.position.getLeft(i),s=hui.position.getTop(i),u=t.vertical||null
t.horizontal&&"right"==t.horizontal&&(o=o+i.clientWidth-e.clientWidth),"topOutside"==u?s-=e.clientHeight:"bottomOutside"==u&&(s+=i.clientHeight),o+=t.left||0,s+=t.top||0,hui.style.set(e,{left:o+"px",top:s+"px"}),"none"==n&&hui.style.set(e,{visibility:"visible",display:"none"})},hui.ui.extend=function(e,i){void 0!==i&&(e.options&&(e.options=hui.override(e.options,i)),e.element=hui.get(i.element),e.name=i.name),e.name||(hui.ui.latestObjectIndex++,e.name="unnamed"+hui.ui.latestObjectIndex),hui.ui.registerComponent(e),e.delegates=[],e.listen=function(e){return hui.array.add(this.delegates,e),this},e.unListen=function(e){hui.array.remove(this.delegates,e)},e.clearListeners=function(){this.delegates=[]},e.fire=function(e,i,t){return hui.ui.callDelegates(this,e,i,t)},e.fireValueChange=function(){e.fire("valueChanged",e.value),hui.ui.firePropertyChange(e,"value",e.value),hui.ui.callAncestors(e,"childValueChanged",e.value)},e.fireProperty=function(e,i){hui.ui.firePropertyChange(this,e,i)},e.fireSizeChange=function(){hui.ui.callAncestors(e,"$$childSizeChanged")},e.getElement||(e.getElement=function(){return this.element}),e.destroy||(e.destroy=function(){this.element&&hui.dom.remove(this.element)}),e.valueForProperty||(e.valueForProperty=function(e){return this[e]}),e.nodes&&e.element&&(e.nodes=hui.collect(e.nodes,e.element))},hui.ui.registerComponent=function(e){hui.ui.objects[e.name]&&hui.log("Widget replaced: "+e.name,hui.ui.objects[e.name]),hui.ui.objects[e.name]=e},hui.ui.callAncestors=function(e,i,t,n){"undefined"==typeof t&&(t=e)
for(var o=hui.ui.getAncestors(e),s=0;s<o.length;s++)o[s][i]&&o[s][i](t,n)},hui.ui.callDescendants=function(e,i,t,n){"undefined"==typeof t&&(t=e),"$"!==i[0]&&(i="$"+i)
for(var o=hui.ui.getDescendants(e),s=0;s<o.length;s++)o[s][i]&&o[s][i](t,n)},hui.ui.callVisible=function(e){hui.ui.callDescendants(e,"$visibilityChanged")},hui.ui.listen=function(e){hui.ui.domReady&&e.$ready&&e.$ready(),hui.ui.delegates.push(e)},hui.ui.unListen=function(e){hui.array.remove(hui.ui.delegates,e)},hui.ui.callDelegates=function(e,i,t,n){"undefined"==typeof t&&(t=e)
var o
if(e.delegates)for(var s=0;s<e.delegates.length;s++){var u,a=e.delegates[s],r="$"+i+"$"+e.name
e.name&&a[r]?u=a[r](t,n):a["$"+i]&&(u=a["$"+i](t,n)),void 0===o&&void 0!==u&&"undefined"!=typeof u&&(o=u)}var h=hui.ui.callSuperDelegates(e,i,t,n)
return void 0===o&&void 0!==h&&(o=h),o},hui.ui.tellContainers=function(e,i){if(window.parent!=window)try{window.parent.hui.ui._tellContainers(e,i)}catch(t){}},hui.ui._tellContainers=function(e,i){if(hui.ui.callSuperDelegates({},e,i),window.parent!=window)try{window.parent.hui.ui._tellContainers(e,i)}catch(t){}},hui.ui.callSuperDelegates=function(e,i,t,n){"undefined"==typeof t&&(t=e)
for(var o,s=0;s<hui.ui.delegates.length;s++){var u,a=hui.ui.delegates[s]
e.name&&a["$"+i+"$"+e.name]?u=a["$"+i+"$"+e.name](t,n):a["$"+i]&&(u=a["$"+i](t,n)),void 0===o&&void 0!==u&&"undefined"!=typeof u&&(o=u)}return o},hui.ui.resolveImageUrl=function(e,i,t,n){for(var o=0;o<e.delegates.length;o++)if(e.delegates[o].$resolveImageUrl)return e.delegates[o].$resolveImageUrl(i,t,n)
for(var s=0;s<hui.ui.delegates.length;s++){var u=hui.ui.delegates[s]
if(u.$resolveImageUrl)return u.$resolveImageUrl(i,t,n)}return null},hui.ui.include=function(e){hui.ui.request({url:e.url,$text:function(i){var t=hui.build("div",{html:i,parent:document.body})
hui.dom.runScripts(t),e.$success()}})},hui.ui.firePropertyChange=function(e,i,t){hui.ui.callDelegates(e,"propertyChanged",{property:i,value:t})},hui.ui.bind=function(e,i){if(hui.isString(e)&&"@"==e.charAt(0)){var t=e.substring(1).split("."),n=hui.ui.get(t[0])
if(!n)return void hui.log("Unable to bind to "+e)
var o=t.slice(1).join(".")
return n.listen({$propertyChanged:function(e){e.property==o&&i(e.value)}}),n.valueForProperty(o)}return e},hui.ui.handleRequestError=function(e){hui.log("General request error received")
var i=hui.ui.callSuperDelegates(e||this,"requestError")
i||hui.ui.confirmOverlay({element:document.body,text:hui.ui.getText("request_error"),okText:hui.ui.getText("reload_page"),cancelText:hui.ui.getText("continue"),onOk:function(){document.location.reload()}})},hui.ui.handleForbidden=function(e){hui.log("General access denied received")
var i=hui.ui.callSuperDelegates(e||this,"accessDenied")
i||hui.ui.confirmOverlay({element:document.body,text:hui.ui.getText("access_denied"),okText:hui.ui.getText("reload_page"),cancelText:hui.ui.getText("continue"),onOk:function(){document.location.reload()}})},hui.ui.request=function(e){if(e=hui.override({method:"post",parameters:{}},e),e.json)for(var i in e.json)e.parameters[i]=hui.string.toJSON(e.json[i])
var t=e.$success,n=e.$object,o=e.$text,s=e.$xml,u=e.$failure,a=e.$forbidden,r=e.message
e.$success=function(e){r&&(r.success?hui.ui.showMessage({text:r.success,icon:"common/success",duration:r.duration||2e3}):r.start&&hui.ui.hideMessage())
var i,u
"string"==typeof t?hui.request.isXMLResponse(e)?hui.ui.callDelegates(e,"success$"+t):(i=e.responseText.replace(/^\s+|\s+$/g,""),u=i.length>0?hui.string.fromJSON(e.responseText):"",hui.ui.callDelegates(u,"success$"+t)):s&&hui.request.isXMLResponse(e)?s(e.responseXML):n?(i=e.responseText.replace(/^\s+|\s+$/g,""),u=i.length>0?hui.string.fromJSON(e.responseText):null,n(u)):"function"==typeof t?t(e):o&&o(e.responseText)},e.$failure=function(i){"string"==typeof u?hui.ui.callDelegates(i,"failure$"+u):"function"==typeof u?u(i):(e.message&&e.message.start&&hui.ui.hideMessage(),hui.ui.handleRequestError())},e.$exception=e.$exception||function(e,i){throw hui.log(e),hui.log(i),e},e.$forbidden=function(i){e.message&&e.message.start&&hui.ui.hideMessage(),a?a(i):(e.$failure(i),hui.ui.handleForbidden())},e.message&&e.message.start&&hui.ui.msg({text:e.message.start,busy:!0,delay:e.message.delay}),hui.request(e)},hui.ui.parseItems=function(e){var i=e.documentElement,t=[]
return hui.ui.parseSubItems(i,t),t},hui.ui.parseSubItems=function(e,i){for(var t=e.childNodes,n=0;n<t.length;n++){var o=t[n]
if(1==o.nodeType&&"title"==o.nodeName)i.push({title:o.getAttribute("title"),type:"title"})
else if(1==o.nodeType&&"item"==o.nodeName){var s=[]
hui.ui.parseSubItems(o,s),i.push({text:o.getAttribute("text"),title:o.getAttribute("title"),value:o.getAttribute("value"),icon:o.getAttribute("icon"),kind:o.getAttribute("kind"),badge:o.getAttribute("badge"),children:s})}}},hui.ui.require=function(e,i){for(var t=e.length-1;t>=0;t--)e[t]=hui.ui.context+"hui/js/"+e[t]+".js"
hui.require(e,i)},window.define&&define("hui.ui",hui.ui),hui.onReady(function(){hui.listen(window,"resize",hui.ui._resize),hui.ui.reLayout(),hui.ui.domReady=!0,window.parent&&window.parent.hui&&window.parent.hui.ui&&window.parent.hui.ui._frameLoaded(window)
for(var e=0;e<hui.ui.delayedUntilReady.length;e++)hui.ui.delayedUntilReady[e]()
hui.ui.callSuperDelegates(this,"ready")}),hui.ui.ImageViewer=function(e){this.options=hui.override({maxWidth:800,maxHeight:600,perimeter:100,sizeSnap:100,margin:0,ease:hui.ease.slowFastSlow,easeEnd:hui.ease.bounce,easeAuto:hui.ease.slowFastSlow,easeReturn:hui.ease.cubicInOut,transition:400,transitionEnd:1e3,transitionReturn:300,images:[]},e),this.element=hui.get(e.element),this.box=this.options.box,this.dirty=!1,this.width=600,this.height=460,this.index=0,this.position=0,this.playing=!1,this.name=e.name,this.images=e.images||[],hui.ui.extend(this),this.box.listen(this),this._attach(),this._attachDrag(),e.listener&&this.listen(e.listener)},hui.ui.ImageViewer.create=function(e){e=e||{}
var i=e.element=hui.build("div",{"class":"hui_imageviewer",html:'<div class="hui_imageviewer_viewer"><div class="hui_imageviewer_inner_viewer"></div></div><div class="hui_imageviewer_text"></div><div class="hui_imageviewer_status"></div><div class="hui_imageviewer_controller"><div><div><a class="hui_imageviewer_previous"></a><a class="hui_imageviewer_play"></a><a class="hui_imageviewer_next"></a><a class="hui_imageviewer_close"></a></div></div></div>'}),t=e.box=hui.ui.Box.create({variant:"plain",absolute:!0,modal:!0,closable:!0})
return t.add(i),t.addToDocument(),new hui.ui.ImageViewer(e)},hui.ui.ImageViewer.prototype={nodes:{viewer:".hui_imageviewer_viewer",innerViewer:".hui_imageviewer_inner_viewer",status:".hui_imageviewer_status",text:".hui_imageviewer_text",previous:".hui_imageviewer_previous",controller:".hui_imageviewer_controller",next:".hui_imageviewer_next",play:".hui_imageviewer_play",close:".hui_imageviewer_close"},_attach:function(){var e=this
this.nodes.next.onclick=function(){e.next(!0)},this.nodes.previous.onclick=function(){e.previous(!0)},this.nodes.play.onclick=function(){e.playOrPause()},this.nodes.close.onclick=this.hide.bind(this),this._timer=function(){e.next(!1)},this._keyListener=function(i){i=hui.event(i),i.escapeKey?e.hide():e.zoomed||(i.rightKey?e.next(!0):i.leftKey?e.previous(!0):i.returnKey&&e.playOrPause())},hui.listen(this.nodes.viewer,"mousemove",this._onMouseMove.bind(this)),hui.listen(this.nodes.controller,"mouseover",function(){e.overController=!0}),hui.listen(this.nodes.controller,"mouseout",function(){e.overController=!1}),hui.listen(this.nodes.viewer,"mouseout",function(i){hui.ui.isWithin(i,this.nodes.viewer)||e._hideController()}.bind(this))},_draw:function(e){hui.browser.webkit?this.nodes.innerViewer.style.webkitTransform="translate3d("+this.position+"px,0,0)":this.nodes.innerViewer.style.marginLeft=this.position+"px"},_attachDrag:function(){var e=0,i=0,t=0,n=(this.nodes.viewer,this.nodes.innerViewer,0)
hui.drag.register({touch:!0,element:this.nodes.innerViewer,onBeforeMove:function(i){e=i.getLeft(),t=this.position,n=(this.images.length-1)*this.width*-1}.bind(this),onMove:function(o){i=o.getLeft()
var s=t-(e-i)
s>0&&(s=-80*(Math.exp(s*-.013)-1)),n>s&&(s=80*(Math.exp(.013*(s-n))-1)+n),this.position=s,this._draw()}.bind(this),onAfterMove:function(){var t=0>e-i?Math.floor:Math.ceil
this.index=t(-1*this.position/this.width)
var n=this.images.length-1
this.index==this.images.length?this.index=0:this.index<0?this.index=this.images.length-1:n=1,this._goToImage(!0,n,!1,!0)}.bind(this),onNotMoved:this._zoom.bind(this)})},_onMouseMove:function(){window.clearTimeout(this.ctrlHider),this._shouldShowController()&&(this.ctrlHider=window.setTimeout(this._hideController.bind(this),2e3),hui.browser.opacity?hui.effect.fadeIn({element:this.nodes.controller,duration:200}):this.nodes.controller.style.display="block")},_hideController:function(){this.overController||(hui.browser.opacity?hui.effect.fadeOut({element:this.nodes.controller,duration:500}):this.nodes.controller.style.display="none")},_getLargestSize:function(e,i){return hui.fit(i,e,{upscale:!1})},_calculateSize:function(){var e=this.options.sizeSnap,i=hui.window.getViewWidth()-this.options.perimeter
i=Math.floor(i/e)*e,i=Math.min(i,this.options.maxWidth)
var t=hui.window.getViewHeight()-this.options.perimeter
t=Math.floor(t/e)*e,t=Math.min(t,this.options.maxHeight)
for(var n=0,o=0,s=0;s<this.images.length;s++){var u=this._getLargestSize({width:i,height:t},this.images[s])
n=Math.max(n,u.width),o=Math.max(o,u.height)}t=Math.floor(Math.min(t,o)),i=Math.floor(Math.min(i,n)),i==this.width&&t==this.height||(this.width=i,this.height=t,this.dirty=!0)},_updateUI:function(){if(this.dirty){this.nodes.innerViewer.innerHTML=""
for(var e=0;e<this.images.length;e++){var i=hui.build("div",{"class":"hui_imageviewer_image"})
hui.style.set(i,{width:this.width+this.options.margin+"px",height:this.height-1+"px"}),this.nodes.innerViewer.appendChild(i)}this.nodes.controller.style.display=this._shouldShowController()?"block":"none",this.dirty=!1,this._preload()}},_shouldShowController:function(){return this.images.length>1},_goToImage:function(e,i,t,n){var o=this.position,s=this.position=this.index*(this.width+this.options.margin)*-1
if(e){var u,a
if(n)u=200*i,a=hui.ease.fastSlow,a=hui.ease.quadOut
else if(i>1)u=Math.min(i*this.options.transitionReturn,2e3),a=this.options.easeReturn
else{var r=0==this.index||this.index==this.images.length-1
a=r?this.options.easeEnd:this.options.ease,t||(a=this.options.easeAuto),u=r?this.options.transitionEnd:this.options.transition}hui.animate({node:this.nodes.innerViewer,css:{marginLeft:s+"px"},duration:u,ease:a,$render:function(e,i){this.position=o+(s-o)*i,this._draw()}.bind(this)})}else this._draw()
this._drawText()},_drawText:function(){var e=this.images[this.index].text
e?(this.nodes.text.innerHTML=e,this.nodes.text.style.display="block"):(this.nodes.text.innerHTML="",this.nodes.text.style.display="none")},showById:function(e){for(var i=0;i<this.images.length;i++)if(this.images[i].id==e){this.show(i)
break}},show:function(e){this.index=e||0,this._calculateSize(),this._updateUI()
var i=this.options.margin
hui.style.set(this.element,{width:this.width+i+"px",height:this.height+2*i-1+"px"}),hui.style.set(this.nodes.viewer,{width:this.width+i+"px",height:this.height-1+"px"}),hui.style.set(this.nodes.innerViewer,{width:(this.width+i)*this.images.length+"px",height:this.height-1+"px"}),hui.style.set(this.nodes.controller,{marginLeft:(this.width-160)/2+.5*i+"px",display:"none"}),this.box.show(),this._goToImage(!1,0,!1),hui.listen(document,"keydown",this._keyListener),this.visible=!0,this._setHash(!0)},_setHash:function(e){},_onHashChange:function(){this._changing||(this._changing=!0,hui.location.hasHash("imageviewer")&&!this.visible?this.show():!hui.location.hasHash("imageviewer")&&this.visible&&this.hide(),this._changing=!1)},hide:function(){this._hide()},_hide:function(){this.pause(),this.box.hide(),this._endZoom(),hui.unListen(document,"keydown",this._keyListener),this.visible=!1,this._setHash(!1)},$boxCurtainWasClicked:function(){this.hide()},$boxWasClosed:function(){this.hide()},clearImages:function(){this.images=[],this.dirty=!0},addImages:function(e){for(var i=0;i<e.length;i++)this.addImage(e[i])},addImage:function(e){this.images.push(e),this.dirty=!0},play:function(){this.interval||(this.interval=window.setInterval(this._timer,6e3)),this.next(!1),this.playing=!0,this.nodes.play.className="hui_imageviewer_pause"},pause:function(){window.clearInterval(this.interval),this.interval=null,this.nodes.play.className="hui_imageviewer_play",this.playing=!1},playOrPause:function(){this.playing?this.pause():this.play()},_resetPlay:function(){this.playing&&(window.clearInterval(this.interval),this.interval=window.setInterval(this._timer,6e3))},previous:function(e){var i=1
this.index--,this.index<0&&(this.index=this.images.length-1,i=this.images.length-1),this._goToImage(!0,i,e),this._resetPlay()},next:function(e){var i=1
this.index++,this.index==this.images.length&&(this.index=0,i=this.images.length-1),this._goToImage(!0,i,e),this._resetPlay()},_preload:function(){var e=new hui.Preloader
e.addImages(hui.ui.context+"/hui/gfx/imageviewer_controls.png")
var i=this
e.setDelegate({allImagesDidLoad:function(){i._preloadImages()}}),e.load()},_preloadImages:function(){var e=new hui.Preloader
e.setDelegate(this)
for(var i=0;i<this.images.length;i++){var t=hui.ui.resolveImageUrl(this,this.images[i],this.width,this.height)
null!==t&&e.addImages(t)}this.nodes.status.innerHTML="0%",this.nodes.status.style.display="",e.load(this.index)},allImagesDidLoad:function(){this.nodes.status.style.display="none"},imageDidLoad:function(e,i,t){this.nodes.status.innerHTML=Math.round(e/i*100)+"%"
var n=hui.ui.resolveImageUrl(this,this.images[t],this.width,this.height)
n=n.replace(/&amp;/g,"&"),this.nodes.innerViewer.childNodes[t].style.backgroundImage="url('"+n+"')",hui.cls.set(this.nodes.innerViewer.childNodes[t],"hui_imageviewer_image_abort",!1),hui.cls.set(this.nodes.innerViewer.childNodes[t],"hui_imageviewer_image_error",!1)},imageDidGiveError:function(e,i,t){hui.cls.set(this.nodes.innerViewer.childNodes[t],"hui_imageviewer_image_error",!0)},imageDidAbort:function(e,i,t){hui.cls.set(this.nodes.innerViewer.childNodes[t],"hui_imageviewer_image_abort",!0)},zoomed:!1,_zoom:function(e){var i=this.images[this.index]
if(!(i.width<=this.width&&i.height<=this.height)){this.zoomer||(this.zoomer=hui.build("div",{"class":"hui_imageviewer_zoomer",style:"width:"+this.nodes.viewer.clientWidth+"px;height:"+this.nodes.viewer.clientHeight+"px"}),this.element.insertBefore(this.zoomer,hui.dom.firstChild(this.element)),hui.listen(this.zoomer,"mousemove",this._onZoomMove.bind(this)),hui.listen(this.zoomer,"click",this._endZoom.bind(this))),this._hideController(),this.pause()
var t=this._getLargestSize({width:2e3,height:2e3},i),n=hui.ui.resolveImageUrl(this,i,t.width,t.height),o=Math.max(0,Math.round((this.nodes.viewer.clientHeight-t.height)/2))
this.zoomer.innerHTML='<div style="width:'+t.width+"px;height:"+t.height+'px; margin: 0 auto;"><img src="'+n+'" style="margin-top: '+o+'px" /></div>',this.zoomer.style.display="block",this.zoomInfo={width:t.width,height:t.height},this._onZoomMove(e),this.zoomed=!0}},_onZoomMove:function(e){if(this.zoomInfo){var i=hui.position.get(this.zoomer)
e=new hui.Event(e)
var t=(e.getLeft()-i.left)/this.zoomer.clientWidth*(this.zoomInfo.width-this.zoomer.clientWidth),n=(e.getTop()-i.top)/this.zoomer.clientHeight*(this.zoomInfo.height-this.zoomer.clientHeight)
this.zoomer.scrollLeft=t,this.zoomer.scrollTop=n}},_endZoom:function(){this.zoomer&&(this.zoomer.style.display="none",this.zoomed=!1)}},window.define&&define("hui.ui.ImageViewer",hui.ui.ImageViewer),hui.ui.Box=function(e){this.options=hui.override({},e),this.name=e.name,this.element=hui.get(e.element),this.visible=!this.options.absolute,hui.ui.extend(this),this.nodes.close&&hui.listen(this.nodes.close,"click",this._close.bind(this))},hui.ui.Box.create=function(e){e=e||{}
var i=e.variant||"standard",t="plain"!==i,n=e.closable?'<a class="hui_box_close hui_box_close_'+i+'" href="#"></a>':""
return t&&(n+='<div class="hui_box_top"><div><div></div></div></div><div class="hui_box_middle"><div class="hui_box_middle">'),e.title&&(n+='<div class="hui_box_header"><strong class="hui_box_title">'+hui.string.escape(hui.ui.getTranslated(e.title))+"</strong></div>"),n+='<div class="hui_box_body" style="'+(e.padding?"padding: "+e.padding+"px;":"")+(e.width?"width: "+e.width+"px;":"")+'"></div>',t&&(n+='</div></div><div class="hui_box_bottom"><div><div></div></div></div>'),e.element=hui.build("div",{"class":"hui_box hui_box_"+i,html:n,style:e.width?e.width+"px":null}),e.absolute&&hui.cls.add(e.element,"hui_box_absolute"),i&&hui.cls.add(e.element,"hui_box_"+i),new hui.ui.Box(e)},hui.ui.Box.prototype={nodes:{body:".hui_box_body",close:".hui_box_close"},_close:function(e){hui.stop(e),this.hide(),this.fire("boxWasClosed"),this.fire("close")},shake:function(){hui.effect.shake({element:this.element})},addToDocument:function(){document.body.appendChild(this.element)},add:function(e){var i=this.nodes.body
e.getElement?i.appendChild(e.getElement()):i.appendChild(e)},show:function(){var e=this.element
if(this.options.modal){var i=hui.ui.nextPanelIndex()
e.style.zIndex=i+1,hui.ui.showCurtain({widget:this,zIndex:i})}if(this.options.absolute){hui.style.set(e,{display:"block",visibility:"hidden"})
var t=e.clientWidth,n=(hui.window.getViewHeight()-e.clientHeight)/2+hui.window.getScrollTop()
hui.style.set(e,{marginLeft:t/-2+"px",top:n+"px",display:"block",visibility:"visible"})}else e.style.display="block"
this.visible=!0,hui.ui.callVisible(this)},isVisible:function(){return this.visible},$$layout:function(){if(this.options.absolute&&this.visible){var e=this.element,i=e.clientWidth,t=(hui.window.getViewHeight()-e.clientHeight)/2+hui.window.getScrollTop()
hui.style.set(e,{marginLeft:i/-2+"px",top:t+"px"})}},hide:function(){hui.ui.hideCurtain(this),this.element.style.display="none",this.visible=!1,hui.ui.callVisible(this)},$curtainWasClicked:function(){this.fire("boxCurtainWasClicked"),this.options.curtainCloses&&this._close()}},hui.ui.SearchField=function(e){this.options=hui.override({expandedWidth:null},e),this.element=hui.get(e.element),this.name=e.name,this.field=hui.get.firstByTag(this.element,"input"),this.value=this.field.value,this.adaptive=hui.cls.has(this.element,"hui_searchfield_adaptive"),this.initialWidth=null,hui.ui.extend(this),this._addBehavior(),""!==this.value&&this._updateClass()},hui.ui.SearchField.create=function(e){return e=e||{},e.element=hui.build("span",{"class":e.adaptive?"hui_searchfield hui_searchfield_adaptive":"hui_searchfield",html:'<em class="hui_searchfield_placeholder"></em><a href="javascript:void(0);" class="hui_searchfield_reset"></a><span><span><input type="text"/></span></span>'}),new hui.ui.SearchField(e)},hui.ui.SearchField.prototype={_addBehavior:function(){var e=this
hui.listen(this.field,"keyup",this._onKeyUp.bind(this))
var i=hui.get.firstByTag(this.element,"a")
if(i.tabIndex=-1,hui.browser.ipad){var t=function(){e.field.focus()}
hui.listen(hui.get.firstByTag(this.element,"em"),"click",t)}else{var t=function(){e.field.focus(),e.field.select()}
hui.listen(this.element,"mousedown",t),hui.listen(this.element,"mouseup",t),hui.listen(hui.get.firstByTag(this.element,"em"),"mousedown",t)}hui.listen(i,"mousedown",function(i){hui.stop(i),e.reset(),t()}),hui.listen(this.field,"focus",this._onFocus.bind(this)),hui.listen(this.field,"blur",this._onBlur.bind(this))},_onFocus:function(){hui.ui.setKeyboardTarget(this),this.focused=!0,this._updateClass(),this.options.expandedWidth>0&&(null==this.initialWidth&&(this.initialWidth=parseInt(hui.style.get(this.element,"width"))),hui.animate(this.element,"width",this.options.expandedWidth+"px",500,{ease:hui.ease.slowFastSlow}))},_onBlur:function(){hui.ui.setKeyboardTarget(null),this.focused=!1,this._updateClass(),null!==this.initialWidth&&hui.animate(this.element,"width",this.initialWidth+"px",500,{ease:hui.ease.slowFastSlow,delay:100})},_onKeyUp:function(e){this._fieldChanged(),e.keyCode===hui.KEY_RETURN&&this.fire("submit")},focus:function(){this.field.focus()},setValue:function(e){this.field.value=void 0===e||null===e?"":e,this._fieldChanged()},getValue:function(){return this.field.value},isEmpty:function(){return""==this.field.value},isBlank:function(){return hui.isBlank(this.field.value)},reset:function(){this.field.value="",this._fieldChanged()},_updateClass:function(){var e="hui_searchfield"
this.adaptive&&(e+=" hui_searchfield_adaptive"),this.focused&&""!=this.value?e+=" hui_searchfield_focus_dirty":this.focused?e+=" hui_searchfield_focus":""!=this.value&&(e+=" hui_searchfield_dirty"),this.element.className=e},_fieldChanged:function(){this.field.value!=this.value&&(this.value=this.field.value,this._updateClass(),this.fireValueChange())}},window.define&&define("hui.ui.SearchField",hui.ui.SearchField),hui.ui.Overlay=function(e){this.options=e,this.element=hui.get(e.element),this.content=hui.get.byClass(this.element,"hui_inner_overlay")[1],this.name=e.name,this.icons={},this.visible=!1,hui.ui.extend(this),this._addBehavior()},hui.ui.Overlay.create=function(e){e=e||{}
var i=e.element=hui.build("div",{className:"hui_overlay"+(e.variant?" hui_overlay_"+e.variant:""),style:"display:none",html:'<div class="hui_inner_overlay"><div class="hui_inner_overlay"></div></div>'})
return document.body.appendChild(i),new hui.ui.Overlay(e)},hui.ui.Overlay.prototype={_addBehavior:function(){},addIcon:function(e,i){var t=this,n=hui.build("div",{className:"hui_overlay_icon"})
n.style.backgroundImage="url("+hui.ui.getIconUrl(i,32)+")",hui.listen(n,"click",function(i){t._iconWasClicked(e,i)}),this.icons[e]=n,this.content.appendChild(n)},addText:function(e){this.content.appendChild(hui.build("span",{"class":"hui_overlay_text",text:e}))},add:function(e){this.content.appendChild(e.getElement())},hideIcons:function(e){for(var i=0;i<e.length;i++)this.icons[e[i]].style.display="none"},showIcons:function(e){for(var i=0;i<e.length;i++)this.icons[e[i]].style.display=""},_iconWasClicked:function(e,i){hui.ui.callDelegates(this,"iconWasClicked",e,i)},showAtElement:function(e,i){if(i=i||{},hui.ui.positionAtElement(this.element,e,i),i.autoHide&&this._autoHide(e),!this.visible){hui.browser.msie?this.element.style.display="block":(hui.style.set(this.element,{display:"block",opacity:0}),hui.animate(this.element,"opacity",1,150))
var t=void 0===i.zIndex?i.zIndex:hui.ui.nextAlertIndex()
this.options.modal?(this.element.style.zIndex=hui.ui.nextAlertIndex(),hui.ui.showCurtain({widget:this,zIndex:t})):this.element.style.zIndex=t,this.visible=!0}},_autoHide:function(e){hui.cls.add(e,"hui_overlay_bound"),hui.unListen(document.body,"mousemove",this._hider),this._hider=function(i){if(!hui.ui.isWithin(i,e)&&!hui.ui.isWithin(i,this.element))try{hui.unListen(document.body,"mousemove",this._hider),hui.cls.remove(e,"hui_overlay_bound"),this.hide()}catch(i){hui.log("unable to stop listening: document="+document)}}.bind(this),hui.listen(document.body,"mousemove",this._hider)},show:function(e){if(e=e||{},this.visible||hui.style.set(this.element,{display:"block",visibility:"hidden"}),e.element&&hui.position.place({source:{element:this.element,vertical:0,horizontal:.5},target:{element:e.element,vertical:.5,horizontal:.5},insideViewPort:!0,viewPartMargin:9}),e.autoHide&&e.element&&this._autoHide(e.element),!this.visible&&(hui.effect.bounceIn({element:this.element}),this.visible=!0,this.options.modal)){var i=hui.ui.nextAlertIndex()
this.element.style.zIndex=i+1,hui.ui.showCurtain({widget:this,zIndex:i,color:"auto"})}},$curtainWasClicked:function(){this.hide()},hide:function(){hui.ui.hideCurtain(this),this.element.style.display="none",this.visible=!1},clear:function(){hui.ui.destroyDescendants(this.content),this.content.innerHTML=""}},hui.ui.Button=function(e){this.options=e,this.name=e.name,this.element=hui.get(e.element),this.enabled=!hui.cls.has(this.element,"hui_button_disabled"),hui.ui.extend(this),this._attach(),e.listener&&this.listen(e.listener),e.listen&&this.listen(e.listen)},hui.ui.Button.create=function(e){e=hui.override({text:"",highlighted:!1,enabled:!0},e)
var i="hui_button"+(e.highlighted?" hui_button_highlighted":"")
e.variant&&(i+=" hui_button_"+e.variant),e.small&&e.variant&&(i+=" hui_button_small_"+e.variant),e.small&&(i+=" hui_button_small"+(e.highlighted?" hui_button_small_highlighted":"")),e.enabled||(i+=" hui_button_disabled")
var t=e.text?hui.ui.getTranslated(e.text):null
e.title&&(t=hui.ui.getTranslated(e.title))
var n=e.element=hui.build("a",{"class":i,href:"javascript://"}),o=hui.build("span",{parent:hui.build("span",{parent:n})})
if(e.icon){var s=hui.build("em",{parent:o,"class":"hui_button_icon",style:"background-image:url("+hui.ui.getIconUrl(e.icon,16)+")"})
t||hui.cls.add(s,"hui_button_icon_notext")}return t&&hui.dom.addText(o,t),new hui.ui.Button(e)},hui.ui.Button.prototype={_attach:function(){var e=this
hui.listen(this.element,"mousedown",function(e){hui.stop(e)}),hui.listen(this.element,"click",function(i){hui.stop(i),e._onClick(i)})},_onClick:function(e){if(this.enabled){var i=!1
e&&(i=hui.event(e).altKey),this.options.confirm&&!i?hui.ui.confirmOverlay({widget:this,text:this.options.confirm.text,okText:this.options.confirm.okText,cancelText:this.options.confirm.cancelText,onOk:this._fireClick.bind(this)}):this._fireClick()}else this.element.blur()},_fireClick:function(){if(this.fire("click",this),this.options.submit){var e=hui.ui.getAncestor(this,"hui_formula")
e?e.submit():hui.log("No form found to submit")}},click:function(e){return e?(this.listen({$click:e}),this):void this._onClick()},focus:function(){this.element.focus()},onClick:function(e){this.listen({$click:e})},setEnabled:function(e){this.enabled=e,this._updateUI()},enable:function(){this.setEnabled(!0)},disable:function(){this.setEnabled(!1)},setHighlighted:function(e){hui.cls.set(this.element,"hui_button_highlighted",e)},_updateUI:function(){hui.cls.set(this.element,"hui_button_disabled",!this.enabled)},setText:function(e){hui.dom.setText(this.element.getElementsByTagName("span")[1],hui.ui.getTranslated(e))},getData:function(){return this.options.data},getRole:function(){return this.options.role}},hui.ui.Buttons=function(e){this.name=e.name,this.element=hui.get(e.element),this.body=hui.get.firstByClass(this.element,"hui_buttons_body"),hui.ui.extend(this)},hui.ui.Buttons.create=function(e){e=hui.override({top:0},e)
var i=e.element=hui.build("div",{"class":"hui_buttons"})
return"right"===e.align&&hui.cls.add(i,"hui_buttons_right"),"center"===e.align&&hui.cls.add(i,"hui_buttons_center"),e.top>0&&(i.style.paddingTop=e.top+"px"),hui.build("div",{"class":"hui_buttons_body",parent:i}),new hui.ui.Buttons(e)},hui.ui.Buttons.prototype={add:function(e){return this.body.appendChild(e.element),this}},!op)var op={}
op.preview=!1,op.page=op.page||{id:null,path:null,template:null},op.ignite=function(){if(this.preview||(document.onkeydown=function(e){if(e=hui.event(e),e.returnKey&&e.shiftKey){e.stop()
var i
i=function(e){e=hui.event(e),e.returnKey&&(hui.unListen(document,"keyup",i),hui.browser.msie||op.user.internal?window.location=op.page.path+"Editor/index.php?page="+op.page.id:(e.stop(),op.showLogin()))},hui.listen(document,"keyup",i)}return!0},hui.request({url:op.context+"services/statistics/",parameters:{page:op.page.id,referrer:document.referrer,uri:document.location.href}})),hui.browser.msie7&&hui.onReady(function(){hui.cls.add(document.body.parentNode,"msie7")}),hui.browser.msie7||hui.browser.msie6){for(var e=hui.get.byClass(document.body,"shared_frame"),i=0;i<e.length;i++)e[i].style.width=e[i].clientWidth+"px",e[i].style.display="block"
for(var t=hui.get.byClass(document.body,"document_row"),i=t.length-1;i>=0;i--){var n=t[i].style.cssText,o=10,s=n.match(/border-spacing: ([0-9]+)/m)
s&&(o=s[1])
for(var u=hui.build("table",{"class":t[i].className,style:n,cellSpacing:o}),a=hui.build("tbody",{parent:u}),r=hui.build("tr",{parent:a}),h=hui.get.byClass(t[i],"document_column"),l=0;l<h.length;l++)for(var d=h[l],c=hui.build("td",{"class":d.className,parent:r,style:d.style.cssText});d.firstChild;)c.appendChild(d.firstChild)
t[i].parentNode.insertBefore(u,t[i]),hui.dom.remove(t[i])}}},op.showLogin=function(){if(this.loginBox)this.loginBox.show(),this.loginForm.focus()
else{if(this.loadingLogin)return void hui.log("Aborting, the box is loading")
this.loadingLogin=!0,hui.ui.showMessage({text:{en:"Loading...",da:"Indlæser..."},busy:!0,delay:300}),hui.ui.require(["Formula","Button","TextField"],function(){hui.ui.hideMessage()
var e=this.loginBox=hui.ui.Box.create({width:300,title:{en:"Access control",da:"Adgangskontrol"},modal:!0,absolute:!0,closable:!0,curtainCloses:!0,padding:10})
this.loginBox.addToDocument()
var i=this.loginForm=hui.ui.Formula.create()
i.listen({$submit:function(){if(e.isVisible()){var t=i.getValues()
op.login(t.username,t.password)}}})
var t=i.buildGroup(null,[{type:"TextField",options:{label:{en:"Username",da:"Brugernavn"},key:"username"}},{type:"TextField",options:{label:{en:"Password",da:"Kodeord"},key:"password",secret:!0}}]),n=t.createButtons(),o=hui.ui.Button.create({text:{en:"Forgot password?",da:"Glemt kode?"}})
o.listen({$click:function(){document.location=op.context+"Editor/Authentication.php?forgot=true"}}),n.add(o)
var s=hui.ui.Button.create({text:{en:"Cancel",da:"Annuller"}})
s.listen({$click:function(){i.reset(),e.hide(),document.body.focus()}}),n.add(s),n.add(hui.ui.Button.create({text:{en:"Log in",da:"Log ind"},highlighted:!0,submit:!0})),this.loginBox.add(i),this.loginBox.show(),window.setTimeout(function(){i.focus()},100),this.loadingLogin=!1,op.startListening()
var u=new hui.Preloader({context:hui.ui.context+"hui/icons/"})
u.addImages("common/success24.png"),u.load()}.bind(this))}},op.startListening=function(){hui.listen(window,"keyup",function(e){if(e=hui.event(e),e.escapeKey&&this.loginBox.isVisible()){this.loginBox.hide()
var i=hui.get.firstByTag(document.body,"a")
i&&(i.focus(),i.blur()),document.body.blur()}}.bind(this))},op.login=function(e,i){return hui.isBlank(e)||hui.isBlank(i)?(hui.ui.showMessage({text:{en:"Please fill in both fields",da:"Udfyld venligst begge felter"},duration:3e3}),void this.loginForm.focus()):void hui.ui.request({message:{start:{en:"Logging in...",da:"Logger ind..."},delay:300},url:op.context+"Editor/Services/Core/Authentication.php",parameters:{username:e,password:i},$object:function(e){e.success?(hui.ui.showMessage({text:{en:"You are now logged in",da:"Du er nu logget ind"},icon:"common/success",duration:4e3}),op.igniteEditor()):hui.ui.showMessage({text:{en:"The user was not found",da:"Brugeren blev ikke fundet"},icon:"common/warning",duration:4e3})},$failure:function(){hui.ui.showMessage({text:{en:"An internal error occurred",da:"Der skete en fejl internt i systemet"},icon:"common/warning",duration:4e3})}})},op.igniteEditor=function(){window.location=op.page.path+"Editor/index.php?page="+op.page.id},op.showImage=function(e){this.imageViewer||(this.imageViewer=hui.ui.ImageViewer.create({maxWidth:2e3,maxHeight:2e3,perimeter:40,sizeSnap:10}),this.imageViewer.listen(op.imageViewerDelegate)),this.imageViewer.clearImages(),this.imageViewer.addImage(e),this.imageViewer.show()},op.registerImageViewer=function(e,i){hui.get(e).onclick=function(){return op.showImage(i),!1}},op.imageViewerDelegate={$resolveImageUrl:function(e,i,t){var n=e.width?Math.min(i,e.width):i,o=e.height?Math.min(t,e.height):t
return op.page.path+"services/images/?id="+e.id+"&width="+n+"&height="+o+"&format=jpg&quality=100"}},void 0===op.part&&(op.part={}),op.feedback=function(e){hui.require(op.page.path+"style/basic/js/Feedback.js",function(){op.feedback.Controller.init(e)})},window.define&&define("op"),op.part.Formula=function(e){this.element=hui.get(e.element),this.id=e.id,this.inputs=e.inputs,hui.listen(this.element,"submit",this._send.bind(this))},op.part.Formula.prototype={_send:function(e){hui.stop(e)
for(var i=[],t=0;t<this.inputs.length;t++){var n=this.inputs[t],o=hui.get(n.id),s=n.validation
if(s.required&&hui.isBlank(o.value))return hui.ui.showMessage({text:s.message,duration:2e3}),void o.focus()
if("email"==s.syntax&&!hui.isBlank(o.value)){var u=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\\n".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA\n-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
if(!u.test(o.value))return hui.ui.showMessage({text:s.message,duration:2e3}),void o.focus()}i.push({label:n.label,value:o.value})}var a=op.page.path+"services/parts/formula/",r={id:this.id,fields:i}
hui.ui.showMessage({text:"Sender besked...",busy:!0}),hui.ui.request({url:a,json:{data:r},$success:this._success.bind(this),$failure:this._failure.bind(this)})},_success:function(){hui.ui.showMessage({text:"Beskeden er nu sendt",icon:"common/success",duration:2e3}),this.element.reset()},_failure:function(){hui.ui.showMessage({text:"Beskeden kunne desværre ikke afleveres",duration:5e3})}},window.define&&define("op.part.Formula"),op.part.Image=function(e){var i=this.element=hui.get(e.element),t=(i.src,i.parentNode)
t.style.position="relative",t.style.display="block"
var n=hui.build("img",{src:i.src+"&contrast=-20&brightness=80&blur=30",style:"position: absolute; left: 0; top: 0;",parent:t})
hui.animate({node:n,duration:1e3,delay:1e3,ease:hui.ease.flicker,css:{opacity:0}}),hui.listen(n,"mouseover",function(){hui.animate({node:n,duration:500,delay:0,ease:hui.ease.fastSlow,css:{opacity:1}})}),hui.listen(n,"mouseout",function(){hui.animate({node:n,duration:1e3,delay:1e3,ease:hui.ease.flicker,css:{opacity:0}})})},window.define&&define("op.part.Image"),op.part.Poster=function(e){this.options=hui.override({duration:1500,interval:5e3,delay:0},e),this.name=e.name,this.element=hui.get(e.element),this.container=hui.get.firstByClass(this.element,"part_poster_pages"),this.pages=hui.get.byClass(this.element,"part_poster_page"),this.index=0,this.indicators=[],this._buildNavigator(),this.options.editmode||window.setTimeout(this._callNext.bind(this),this.options.delay),hui.listen(this.element,"click",this._onClick.bind(this)),hui.ui.extend(this)},op.part.Poster.prototype={_buildNavigator:function(){this.navigator=hui.build("div",{"class":"part_poster_navigator",parent:this.element})
for(var e=0;e<this.pages.length;e++)this.indicators.push(hui.build("a",{parent:this.navigator,data:e,href:"javascript://","class":0==e?"part_poster_current":""}))},next:function(){var e=this.index+1
e>=this.pages.length&&(e=0),this.goToPage(e)},previous:function(){var e=this.index-1
0>e&&(e=this.pages.length-1),this.goToPage(e)},setPage:function(e){if(!(null===e||void 0===e||e==this.index||this.pages.length-1<e)){this.pages[this.index].style.display="none",this.pages[e].style.display="block",this.index=e
for(var i=0;i<this.indicators.length;i++)hui.cls.set(this.indicators[i],"part_poster_current",i==e)}},goToPage:function(e){if(e!=this.index){window.clearTimeout(this.timer)
var i={container:this.container,duration:this.options.duration}
i.hide={element:this.pages[this.index],effect:"slideLeft"},hui.cls.remove(this.indicators[this.index],"part_poster_current"),this.index=e,i.show={element:this.pages[this.index],effect:"slideRight"},hui.cls.add(this.indicators[this.index],"part_poster_current"),hui.transition(i),this.options.editmode||this._callNext(),this.fire("pageChanged",e)}},_callNext:function(){this.timer=window.setTimeout(this.next.bind(this),this.options.interval)},_onClick:function(e){e=hui.event(e)
var i=e.findByTag("a")
i&&hui.cls.has(i.parentNode,"part_poster_navigator")&&this.goToPage(parseInt(i.getAttribute("data")))}},window.define&&define("op.part.Poster"),op.part.Map=function(e){this.options=hui.override({maptype:"roadmap",zoom:8},e),this.container=hui.get(e.element),hui.ui.onReady(this.initialize.bind(this))},op.part.Map.defered=[],op.part.Map.onReady=function(e){hui.log("onReady... loaded:"+this.loaded),this.loaded?e():this.defered.push(e),void 0===this.loaded&&(this.loaded=!1,window.opMapReady=function(){hui.log("ready")
for(var e=0;e<this.defered.length;e++)this.defered[e]()
window.opMapReady=null,this.loaded=!0}.bind(this),hui.require("https://maps.googleapis.com/maps/api/js?sensor=false&callback=opMapReady"))},op.part.Map.types={roadmap:"ROADMAP",terrain:"TERRAIN"},op.part.Map.prototype={initialize:function(){hui.log("init"),op.part.Map.onReady(this.ready.bind(this))},ready:function(){var e={zoom:this.options.zoom,center:new google.maps.LatLng(-34.397,150.644),mapTypeId:google.maps.MapTypeId[this.options.type.toUpperCase()],scrollwheel:!1}
this.options.markers
if(this.options.center&&(e.center=new google.maps.LatLng(this.options.center.latitude,this.options.center.longitude)),this.map=new google.maps.Map(this.container,e),this.options.center){var i=new google.maps.Marker({position:new google.maps.LatLng(this.options.center.latitude,this.options.center.longitude),map:this.map,icon:new google.maps.MarkerImage(op.context+"style/basic/gfx/part_map_pin.png",new google.maps.Size(29,30),new google.maps.Point(0,0),new google.maps.Point(8,26))}),t=hui.get.firstByClass(this.element,"part_map_text")
if(t){var n=new google.maps.InfoWindow({content:hui.build("div",{text:t.innerHTML,"class":"part_map_bubble"})})
n.open(this.map,i)}return
var i}}},window.define&&define("op.part.Map"),op.part.Movie=function(e){this.options=e,this.element=hui.get(e.element),this._attach()},op.part.Movie.prototype={_attach:function(){hui.listen(this.element,"click",this._activate.bind(this))
var e=hui.get.firstByClass(this.element,"part_movie_poster")
if(e){var i=e.getAttribute("data-id")
if(i)window.setTimeout(function(){var t=window.devicePixelRatio||1,n=op.context+"services/images/?id="+i+"&width="+e.clientWidth*t+"&height="+e.clientHeight*t
e.style.backgroundImage="url("+n+")"},500)
else{var t=e.getAttribute("data-vimeo-id")
t&&this._vimeo(t,e)}}},_activate:function(){var e=hui.get.firstByClass(this.element,"part_movie_body"),i=hui.get.firstByTag(this.element,"noscript")
i&&(e.innerHTML=hui.dom.getText(i)),e.style.background=""},_vimeo:function(e,i){var t="callback_"+e,n="http://vimeo.com/api/v2/video/"+e+".json?callback="+t
window[t]=function(e){i.style.backgroundImage="url("+e[0].thumbnail_large+")"}
hui.build("script",{type:"text/javascript",src:n,parent:document.head})}},window.define&&define("op.part.Movie"),hui.transition=function(e){var i=e.hide,t=e.show,n=hui.transition[t.effect],o=hui.transition[i.effect]
hui.style.set(e.container,{height:e.container.clientHeight+"px",position:"relative"}),hui.style.set(i.element,{width:e.container.clientWidth+"px",position:"absolute",boxSizing:"border-box"}),hui.style.set(t.element,{width:e.container.clientWidth+"px",position:"absolute",display:"block",visibility:"hidden",boxSizing:"border-box"}),hui.animate({node:e.container,css:{height:t.element.clientHeight+"px"},duration:e.duration+10,ease:hui.ease.slowFastSlow,onComplete:function(){hui.style.set(e.container,{height:"",position:""})}}),o.beforeHide(i.element),o.hide(i.element,e.duration,function(){hui.style.set(i.element,{display:"none",position:"static",width:""})}),n.beforeShow(t.element),hui.style.set(t.element,{display:"block",visibility:"visible"}),n.show(t.element,e.duration,function(){hui.style.set(t.element,{position:"static",width:""})})},hui.transition.css=function(e){this.options=e},hui.transition.css.prototype={beforeShow:function(e){hui.style.set(e,this.options.hidden)},show:function(e,i,t){hui.animate({node:e,css:this.options.visible,duration:i,ease:hui.ease.slowFastSlow,onComplete:t})},beforeHide:function(e){hui.style.set(e,this.options.visible)},hide:function(e,i,t){hui.animate({node:e,css:this.options.hidden,duration:i,ease:hui.ease.slowFastSlow,onComplete:function(){t(),hui.style.set(e,this.options.visible)}.bind(this)})}},hui.transition.dissolve=new hui.transition.css({visible:{opacity:1},hidden:{opacity:0}}),hui.transition.scale=new hui.transition.css({visible:{opacity:1,transform:"scale(1)"},hidden:{opacity:0,transform:"scale(.9)"}}),hui.transition.slideLeft=new hui.transition.css({visible:{opacity:1,marginLeft:"0%"},hidden:{opacity:0,marginLeft:"-100%"}}),hui.transition.slideRight=new hui.transition.css({visible:{opacity:1,marginLeft:"0%"},hidden:{opacity:0,marginLeft:"100%"}}),op.SearchField=function(e){e=this.options=hui.override({placeholderClass:"placeholder",placeholder:""},e),this.field=hui.get(e.element),this.field.onfocus=function(){this.field.value==e.placeholder?(this.field.value="",hui.cls.add(this.field,e.placeholderClass)):this.field.select()}.bind(this),this.field.onblur=function(){""==this.field.value&&(hui.cls.add(this.field,e.placeholderClass),this.field.value=e.placeholder)}.bind(this),this.field.onblur()},window.define&&define("op.SearchField"),op.Dissolver=function(e){e=this.options=hui.override({wait:4e3,transition:2e3,delay:0},e),this.pos=Math.floor(Math.random()*(e.elements.length-1e-5)),this.z=1,e.elements[this.pos].style.display="block",window.setTimeout(this.next.bind(this),e.wait+e.delay)},op.Dissolver.prototype={next:function(){this.pos++,this.z++
var e=this.options.elements
this.pos==e.length&&(this.pos=0)
var i=e[this.pos]
hui.style.setOpacity(i,0),hui.style.set(i,{display:"block",zIndex:this.z}),hui.animate(i,"opacity",1,this.options.transition,{ease:hui.ease.slowFastSlow,onComplete:function(){window.setTimeout(this.next.bind(this),this.options.wait)}.bind(this)})}},window.define&&define("op.Dissolver")
