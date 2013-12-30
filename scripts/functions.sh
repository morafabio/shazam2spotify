#!/bin/sh

command_exists () {
    type "$1" >/dev/null 2>&1 ;
}

download_composer () {
  OUTPUT_DIR="${2:-$(pwd)}"

  if [ ! -d "$OUTPUT_DIR" ]; then
    mkdir "$OUTPUT_DIR"
  fi

  if command_exists "wget" ; then
    wget -q -P "$OUTPUT_DIR" "$1"
  elif command_exists "curl" ; then
    ( cd "$OUTPUT_DIR" > /dev/null ; curl --silent -O "$1" ; )
  else
    echo "Cannot download $1 [missing wget or curl]"
    exit 1
  fi
}
