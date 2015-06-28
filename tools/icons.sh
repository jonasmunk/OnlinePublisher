#! /usr/bin/env python

import os
import re
import sys
import codecs
import json
from xml.sax.saxutils import escape



pathname = os.path.dirname(sys.argv[0])
base = os.path.abspath(pathname)+'/..'

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
print js
dest.close()

