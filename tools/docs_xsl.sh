#! /usr/bin/env python

import os
import re
import sys
import codecs
from collections import deque
from sets import Set
from xml.sax.saxutils import escape

pathname = os.path.dirname(sys.argv[0])
base = os.path.abspath(pathname)+'/..'

start = re.compile('<!--doc*')
end = re.compile('-->*')

titleReg = re.compile('title:\'([^\']+)\'')
classReg = re.compile('class:\'([^\']+)\'')
moduleReg = re.compile('module:\'([^\']+)\'')

dest = codecs.open(base+'/api/xml/index.html', mode='w', encoding='utf-8')




components = deque()
modules = Set()

dir = base+"/xslt/"
files = os.listdir(dir)

for file in files :
	if os.path.splitext(file)[1] == '.xsl' :
		print '# '+file
		filename = os.path.join(dir,file)
		f = codecs.open(filename, mode='r', encoding='utf-8')
		lines = f.readlines()
		indoc = False
		info = dict()
		for line in lines:
			if start.match(line) : 
				info = dict({'file':file,'sample':'','title':'Untitled'})
				components.append(info)
				indoc=True
				titleMatch = titleReg.search(line)
				if titleMatch : 
					info['title'] = titleMatch.group(1)
				print ' - '+info['title']
				
				classMatch = classReg.search(line)
				if classMatch : info['class'] = classMatch.group(1)
				
				
				moduleMatch = moduleReg.search(line)
				if moduleMatch : 
					info['module'] = moduleMatch.group(1)
					modules.add(moduleMatch.group(1))
				else : print 'NO MODULE: '+info['title']+' / '+file
				
			elif end.match(line) : 
				indoc=False
			elif indoc :
				info['sample'] += line
		f.close();

print modules

dest.write('<!DOCTYPE html>')
dest.write('<html>\n<head>\n<meta http-equiv="content-type" content="text/html; charset=utf-8"/>\n')
dest.write('<link rel="stylesheet" href="stylesheet.css" type="text/css"/>')
dest.write('<link rel="stylesheet" href="../../bin/minimized.css" type="text/css"/>')
dest.write('<script src="../../bin/minimized.js"></script>')
dest.write('<script src="script.js"></script>')
dest.write('<title>HUI XML format docs</title>\n</head>\n<body>')

dest.write('<p class="modules">')
dest.write('<a href="javascript://"><span>All</span></a> ')
for module in modules :
	dest.write('<a href="javascript://" data="'+module+'"><span>'+module.capitalize()+'</span></a> ')
dest.write('</p>')


for component in components :
	dest.write('<div class="component')
	if 'module' in component :
		dest.write(' '+component['module'])
	dest.write('">')
	dest.write('<h2>'+component['title'])
	if 'module' in component :
		dest.write(' <span class="module">'+component['module']+'</span>')
	dest.write('</h2>\n')
	if 'class' in component :
		dest.write('<p class="class">Class: <a href="../symbols/'+component['class']+'.html"><span>'+component['class']+'</span></a></p>\n')
	dest.write('<p class="file">File: <a href="../../xslt/'+component['file']+'"><span>'+component['file']+'</span></a></p>')
	dest.write('<pre>')
	dest.write(escape(component['sample']))
	dest.write('</pre>')
	dest.write('</div>')
	
dest.write('</body>\n</html>')
dest.close()