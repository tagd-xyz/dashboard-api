#!/usr/bin/env bash

if [[ ! -z ${CI_COMMIT_TAG} ]] && [[ ! ${CI_COMMIT_TAG} =~ ${PRODUCTION_TAG_REGEX} ]]; then
  echo "Can not run pipelines on tag ${CI_COMMIT_TAG}"
  exit 1
fi

source ${DTK_HOME}/lib/laravel.sh

gitlabci_prepare_composer

composer clear-cache

if [ -n "${PACKAGE_CORE_VERSION}" ]; then
  echo "Forcing tagd/core:${PACKAGE_CORE_VERSION}"
  gitlabci_composer_update_package_version "tagd/core" ${PACKAGE_CORE_VERSION}
fi

gitlabci_bundle
