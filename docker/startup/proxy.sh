#!/usr/bin/env bash

export DTK_VERSION=${DTK_VERSION}
export DTK_HOME=/opt/dtk
curl -s https://dtk.totallydev.com/install.sh | bash 2>/dev/null

source ${DTK_HOME}/lib/laravel.sh

nginx_start_dev
