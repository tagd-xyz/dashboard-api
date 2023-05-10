#!/usr/bin/env bash

source ${DTK_HOME}/lib/laravel.sh

if [[ ${CI_COMMIT_TAG} =~ ${PRODUCTION_TAG_REGEX} ]]; then
  tags="${CI_REGISTRY_IMAGE}/proxy:latest,${CI_REGISTRY_IMAGE}/proxy:${CI_COMMIT_TAG}"
else
  ENV=${CI_COMMIT_REF_NAME//-*/}
  tags="${CI_REGISTRY_IMAGE}/proxy:${ENV}-${CI_PIPELINE_ID}"
fi

gitlabci_build_nginx "${tags}"
