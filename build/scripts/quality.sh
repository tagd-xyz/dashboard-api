#!/usr/bin/env bash

source ${DTK_HOME}/lib/phpfpm.sh

COMPOSER_INSTALL_NO_DEV=0

install_composer

gitlabci_composer_install

# composer run-script pint_check

