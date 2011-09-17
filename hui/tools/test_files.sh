#! /usr/bin/env python

import os
import re
import codecs
import json
from xml.sax.saxutils import escape

def hey(dir,title) :
	xml ='    <item icon="common/folder" title="'+title+'" value="'+title+'">\n'
	groups = os.listdir(dir)
	for file in groups :
		if not os.path.isdir(dir+'/'+file) and file[0] != '.' :
			splitted = os.path.splitext(file)
			if splitted[1] == '.html' or splitted[1] == '.xml' :
				xml+='        <item icon="common/page" title="'+splitted[0]+'" value="'+os.path.basename(dir)+'/'+file+'"/>\n'

	xml+='    </item>\n'
	return xml


base = os.getcwd()+'/..'

items = '<?xml version="1.0"?>\n<items>\n'
items+='    <title title="Tests"/>\n'
items+=hey(base+'/test/unittests','Unit tests')
items+=hey(base+'/test/html','HTML tests')
items+=hey(base+'/test/xml','XML tests')
items+=hey(base+'/test/guis','Complete GUIs')
items+='</items>'

dest = codecs.open(base+'/test/navigation.xml', mode='w', encoding='utf-8')
dest.write(items)
print items
dest.close()

