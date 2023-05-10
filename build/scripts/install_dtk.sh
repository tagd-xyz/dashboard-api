#!/usr/bin/env sh
apk --upgrade --no-cache add bash curl
export DTK_VERSION=${DTK_VERSION}
curl -s https://dtk.totallydev.com/install.sh | bash 2>/dev/null
