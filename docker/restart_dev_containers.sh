#!/usr/bin/env bash

PRIMARY_CONTAINER=tagd-dash-api
PACKAGES_DIR=../../../packages
COMPOSER_DIR=..

install_docker_toolkit () {
  export DTK_VERSION=1.7.14
  export DTK_HOME=~/.dtk/${DTK_VERSION}
  curl -s https://dtk.totallydev.com/install.sh | bash 2>/dev/null
  if [[ ! -d ${DTK_HOME} ]]; then
    echo "Dev toolkit not installed"
    exit 1
  fi
  source ${DTK_HOME}/lib/dev.sh 1
}

# create .env
create_dot_env () {
  DB_USERNAME=${USER}
  DB_PASSWORD=${db_pass}

  prepare_env_file ../.env ./conf/.env.dev
}

prepare_composer_auth () {
  cp ${COMPOSER_DIR}/auth.json.example ${COMPOSER_DIR}/auth.json
  read_token=$(vault read -field=read_token secret/dev/tagd/packages)
  sed -i "s/<read_token>/${read_token}/g" ${COMPOSER_DIR}/auth.json
}

install_docker_toolkit
create_dot_env
create_docker_network "dev"
prepare_composer_auth

# create packages directory if it doesn't exist
if [[ ! -d ${PACKAGES_DIR} ]]; then
  echo "Creating ${PACKAGES_DIR}"
  mkdir ${PACKAGES_DIR}
fi

echo "Removing existing containers"
docker rm -f ${PRIMARY_CONTAINER} \
  ${PRIMARY_CONTAINER}-proxy &>/dev/null

echo "Pulling latest versions of images"
docker-compose pull &>/dev/null

echo "Starting services"
docker-compose -p ${PRIMARY_CONTAINER} up -d

echo
echo "Attaching to container \"${PRIMARY_CONTAINER}\" [Ctrl-C to exit]"
echo
docker attach --sig-proxy=false ${PRIMARY_CONTAINER}
