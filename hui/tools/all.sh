#!/bin/bash

DIR=$(dirname $0)
CSS_PATH=${DIR}"/../css/"
JS_LIB_PATH=${DIR}"/../lib/"
JS_PATH=${DIR}"/../js/"
BIN_PATH=${DIR}"/../bin/"

${DIR}/compile.sh
${DIR}/docs.sh
${DIR}/docs_xsl.sh
${DIR}/icons.sh
${DIR}/test_files.sh