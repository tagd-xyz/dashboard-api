#!/usr/bin/env bash

if [[ ! -z ${CI_COMMIT_TAG} ]] && [[ ! ${CI_COMMIT_TAG} =~ ${PRODUCTION_TAG_REGEX} ]]; then
  echo "Can not run pipelines on tag ${CI_COMMIT_TAG}"
  exit 1
fi

source ${DTK_HOME}/lib/laravel.sh

gitlabci_bundle
