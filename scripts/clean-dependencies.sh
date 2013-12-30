#!/bin/sh

PARENT_DIR=$(dirname $(cd "$(dirname "$0")"; pwd))

cd $PARENT_DIR

rm -rf composer.lock

clean_dependency () {
        rm -rf "$1"
}

clean_dependency "vendor/"
