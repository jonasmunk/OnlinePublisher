#!/bin/bash

DIR=$(dirname $0)

${DIR}/concat.sh

echo "Documenting"
perl jsdoc/jsdoc.pl -d ${DIR}/../api ${DIR}/../bin/combined.js