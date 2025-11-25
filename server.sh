#!/bin/env bash

export XDEBUG_MODE=develop,debug
export XDEBUG_CONFIG="client_host=127.0.0.1 client_port=9003 idekey=VSCODE"

while true;
do
      php -S localhost:8000 -t .
      sleep 5
done
