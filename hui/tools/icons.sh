#! /usr/bin/env python

import os
import re
import codecs
import json
from xml.sax.saxutils import escape

def hey(dir,title) :
	xml ='<item icon="common/folder" title="'+title+'" value="'+title+'">'
	groups = os.listdir(dir)
	for file in groups :
		if not os.path.isdir(dir+'/'+file) and file[0] != '.' :
			splitted = os.path.splitext(file)
			if splitted[1] == '.html' or splitted[1] == '.xml' :
				xml+='<item icon="common/page" title="'+splitted[0]+'" value="'+os.path.basename(dir)+'/'+file+'"/>'

	xml+='</item>'
	return xml


base = '/Users/jbm/Sites/onlinepublisher/hui'

base = os.getcwd()+'/..'

iconReg = re.compile('([^0-9]+)([0-9]+).png')


dictionary = {}

dir = base+"/icons/"
groups = os.listdir(dir)
for group in groups :
	if os.path.isdir(dir+group) and group[0]!='.':
		dictionary[group] = {}
		icons = os.listdir(dir+group)
		for icon in icons :
			if icon[0]!='.' :
				match = iconReg.search(icon)
				if (match) :
					iconName = match.group(1)
					size = int(match.group(2))
					if (iconName not in dictionary[group]) : 
						dictionary[group][iconName] = []
					dictionary[group][iconName].append(size)
				

dest = codecs.open(base+'/info/icons.json', mode='w', encoding='utf-8')
js = json.dumps(dictionary, sort_keys=True, indent=4) 
dest.write(js)
dest.close()

items = '<?xml version="1.0"?><items>'
items+='<title title="Tests"/>'
items+=hey(base+'/test/unittests','Unit tests')
items+=hey(base+'/test/html','HTML tests')
items+=hey(base+'/test/xml','XML tests')
items+=hey(base+'/test/guis','Complete GUIs')
items+='</items>'

dest = codecs.open(base+'/test/navigation.xml', mode='w', encoding='utf-8')
dest.write(items)
print items
dest.close()

