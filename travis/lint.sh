# this checks that the source code follows the Symfony Code Syntax rules
'[[ "$TRAVIS_PHP_VERSION" == "nightly" ]] || ../vendor/bin/php-cs-fixer fix --diff --dry-run -v'

# this checks that the YAML config files contain no syntax errors
./bin/console lint:yaml config

# this checks that the Twig template files contain no syntax errors
./bin/console lint:twig templates

# this checks that the XLIFF translations contain no syntax errors
./bin/console lint:xliff translations



# Эта команда выдает ошибку:
# this checks that the application doesn't use dependencies with known security vulnerabilities
# ./bin/console security:check



# this checks that Doctrine's mapping configurations are valid
./bin/console doctrine:schema:validate --skip-sync -vvv --no-interaction
