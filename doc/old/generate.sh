mkdir -p ./render
# npx redoc-cli build-docs openapi.yml -o index.html
#  \
#  -t ./template.hbs \
#  --options.theme.colors.primary.main="#E42E30" \
#  -o ./render/index.html


docker run --rm -v ${PWD}:/local openapitools/openapi-generator:cli-latest-release generate -i /local/openapi.yml -g html -o /local/render

# openapi-generator-cli generate -i openapi.yml -g html -o /render