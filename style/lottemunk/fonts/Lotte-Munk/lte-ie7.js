/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'Lotte-Munk\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-linkedin' : '&#xe000;',
			'icon-twitter' : '&#xe001;',
			'icon-facebook' : '&#xe002;',
			'icon-wikipedia' : '&#xe003;',
			'icon-phone' : '&#xe004;',
			'icon-map' : '&#xe005;',
			'icon-mail' : '&#xe006;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};