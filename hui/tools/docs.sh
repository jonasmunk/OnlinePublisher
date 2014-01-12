#!/bin/bash

DIR=$(dirname $0)

${DIR}/join.sh

echo "Deleting old symbols"
rm -rf ${DIR}/../api/symbols/*.html

echo "Documenting"

java -Xmx256m -jar ${DIR}/jsdoc_toolkit/jsrun.jar ${DIR}/jsdoc_toolkit/app/run.js -a -s -t=${DIR}/jsdoc_toolkit/templates/clean -d=${DIR}/../api ${DIR}/../bin/all.js


#		-a or --allfunctions
#		      Include all functions, even undocumented ones.
#
#		-c or --conf
#		      Load a configuration file.
#
#		-d=<PATH> or --directory=<PATH>
#		      Output to this directory (defaults to "out").
#
#		-D="myVar:My value" or --define="myVar:My value"
#		    Multiple. Define a variable, available in JsDoc as JSDOC.opt.D.myVar
#
#		-e=<ENCODING> or --encoding=<ENCODING>
#		    Use this encoding to read and write files.
#
#		-h or --help
#		    Show this message and exit.
#
#		-n or --nocode
#		    Ignore all code, only document comments with @name tags.
#
#		-o=<PATH> or --out=<PATH>
#		    Print log messages to a file (defaults to stdout).
#
#		-p or --private
#		    Include symbols tagged as private, underscored and inner symbols.
#
#		-r=<DEPTH> or --recurse=<DEPTH>
#		    Descend into src directories.
#
#		-s or --suppress
#		    Suppress source code output.
#
#		-t=<PATH> or --template=<PATH>
#		    Required. Use this template to format the output.
#
#		-T or --test
#		    Run all unit tests and exit.
#
#		-v or --verbose
#		    Provide verbose feedback about what is happening.
#
#		-x=<EXT>[,EXT]... or --ext=<EXT>[,EXT]...
#		    Scan source files with the given extension/s (defaults to js).