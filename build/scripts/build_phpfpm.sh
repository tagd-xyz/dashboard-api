#!/usr/bin/env bash

source ${DTK_HOME}/lib/laravel.sh

if [[ ${CI_COMMIT_TAG} =~ ${PRODUCTION_TAG_REGEX} ]]; then
  tags="${CI_REGISTRY_IMAGE}/phpfpm:latest,${CI_REGISTRY_IMAGE}/phpfpm:${CI_COMMIT_TAG}"
else
  ENV=${CI_COMMIT_REF_NAME//-*/}
  tags="${CI_REGISTRY_IMAGE}/phpfpm:${ENV}-${CI_PIPELINE_ID}"
fi

gitlabci_build_phpfpm "${tags}" build/phpfpm/8.1/Dockerfile
