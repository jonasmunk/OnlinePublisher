#! /usr/bin/env python

import os
import re
import codecs
from xml.sax.saxutils import escape



base = '/Users/jbm/Sites/onlinepublisher/hui'

base = os.getcwd()+'/..'

start = re.compile('<!--doc*')
end = re.compile('-->*')

titleReg = re.compile('title:\'([^\']+)\'')
classReg = re.compile('class:\'([^\']+)\'')

dest = codecs.open(base+'/api/xml/index.html', mode='w', encoding='utf-8')

dest.write('<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">')
dest.write('<html>\n<head>\n<meta http-equiv="content-type" content="text/html; charset=utf-8"/>\n')
dest.write('<link rel="stylesheet" href="stylesheet.css" type="text/css"/>')
dest.write('<title>HUI XML format docs</title>\n</head>\n<body>')

dir = base+"/icons/"
groups = os.listdir(dir)
for group in groups :
	
	if os.path.isdir(dir+group) and group[0]!='.':
		icons = os.listdir(dir+group)
		for icon in icons :
			if icon[0]!='.' :
				print group+'/'+icon

#stuff = os.walk(dir);
#for thing in stuff :
#	print thing

dir = base+"/xslt/"
files = os.listdir(dir)

for file in files :
	if os.path.splitext(file)[1] == '.xsl' :
		print '# '+file
		filename = os.path.join(dir,file)
		f = codecs.open(filename, mode='r', encoding='utf-8')
		lines = f.readlines()
		indoc = False
		for line in lines:
			if start.match(line) : 
				indoc=True
				m = titleReg.search(line)
				print ' - '+m.group(1)
				if m : dest.write('<h2>'+m.group(1)+'</h2>\n')
				else : dest.write('<h2>!!!</h2>\n')
				m = classReg.search(line)
				if m : dest.write('<p>Class: <a href="../symbols/'+m.group(1)+'.html">'+m.group(1)+'</a></p>\n')
				dest.write('<p>File: '+file+'</p>')
				dest.write('<div><pre>\n')
			elif end.match(line) : 
				indoc=False
				dest.write('</pre></div>\n')
			elif indoc :
				
				dest.write(escape(line))
		f.close();
		
#filename = os.environ.get('PYTHONSTARTUP')

#f = codecs.open('unicode.rst')

dest.write('</body>\n</html>')
dest.close()
#print lines
