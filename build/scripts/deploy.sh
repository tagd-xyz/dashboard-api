#!/usr/bin/env bash

source ${DTK_HOME}/lib/helm.sh

NAMESPACE=tagd

if [[ ${ENV} == "production" ]]; then
  image_tag=${CI_COMMIT_TAG}
  release="dash-api"
  kube_config=${KUBE_CONFIG_PROD}
else
  image_tag="${ENV}-${CI_PIPELINE_ID}"
  release="dash-api-${ENV}"
  kube_config=${KUBE_CONFIG_UAT}
  env_secret="dash-api-env-${ENV}"
  migrations_secret="dash-api-env-${ENV}"
fi

set_chart_values () {
  # images
  set_chart_value image.apiProxy "${CI_REGISTRY_IMAGE}/proxy:${image_tag}"
  set_chart_value image.api "${CI_REGISTRY_IMAGE}/phpfpm:${image_tag}"

  # secrets
  set_chart_value imagePullSecret.api "gitlab-pull-secret"

  # hosts
  set_chart_value host.api ${HOST}

  # secrets
  set_chart_value secret.api.env ${env_secret}
  set_chart_value secret.api.migrationsEnv ${migrations_secret}

  # replica counts
  set_chart_value replicas.api ${REPLICAS}
  set_chart_value replicas.apiProxy ${REPLICAS}

  # other
  set_chart_value env ${ENV}
  set_chart_value jobId ${CI_JOB_ID}

  # run migrations
  set_chart_value migrations.run false
}

deploy () {
  export KUBECTRL_CONFIG=${kube_config}
  export HELM_CHART_REPO=${HELM_CHART_REPO}

  if ! helm_deploy "${release}" ${NAMESPACE}; then
    exit 1
  fi

  echo "--------------------------------------------------------------------------------------------"
  echo "Deployed to ${ENV}:"
  echo "--------------------------------------------------------------------------------------------"
  echo " * https://${HOST}"
  echo "--------------------------------------------------------------------------------------------"
}

set_chart_values

deploy
