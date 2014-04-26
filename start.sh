#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR

php -S 'localhost:8888' & 

echo $! > php_server.pid
