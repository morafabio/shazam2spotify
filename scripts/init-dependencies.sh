#!/bin/sh

PARENT_DIR=$(dirname $(cd "$(dirname "$0")"; pwd))

cd "$PARENT_DIR"
. "$PARENT_DIR/scripts/functions.sh"

clean_dependencies () {
      . "$PARENT_DIR/scripts/clean-dependencies.sh"
}

initialize_composer () {
      if [ ! -f "composer.phar" ]; then
        echo "Could not find composer.phar, downloading it now..."
        download_composer "http://getcomposer.org/composer.phar"
      fi
      /usr/bin/env php composer.phar install
}

clean_dependencies
initialize_composer

apt-get install nodejs
npm install -g bower
npm install
bower install
