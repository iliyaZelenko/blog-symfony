language: php # node_js
sudo: required # false
php:
  - 7.1.9

#node_js:
#    - 8
#    - 6

cache:
  yarn: true
  directories:
    - $HOME/.composer/cache/files
    - ./bin/.phpunit
    - node_modules

env:
  global:
    - SYMFONY_PHPUNIT_DIR=./bin/.phpunit
    - SYMFONY_DEPRECATIONS_HELPER=29

matrix:
  fast_finish: true
  include:
    # - php: 7.1.18
    - php: 7.2
    #- php: 7.3
    - php: nightly
  allow_failures:
    - php: nightly

before_install:
  - '[[ "$TRAVIS_PHP_VERSION" == "nightly" ]] || phpenv config-rm xdebug.ini'
  - composer self-update
  # Yarn
  # Repo for Yarn
  - sudo apt-key adv --fetch-keys http://dl.yarnpkg.com/debian/pubkey.gpg
  - echo "deb http://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
  - sudo apt-get update -qq
  - sudo apt-get install -y -qq yarn=1.13.0-1
#    - curl -o- -L https://yarnpkg.com/install.sh | bash -s -- --version version-number
#    - export PATH="$HOME/.yarn/bin:$PATH"

install:
  - composer install
#  - ./bin/phpunit install

# php-fpm for integration tests: https://docs.travis-ci.com/user/languages/php/#apache--php
#before_script:
#    - sudo apt-get update
#    - sudo apt-get install apache2 libapache2-mod-fastcgi
#    # enable php-fpm
#    - sudo cp ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf
#    - sudo a2enmod rewrite actions fastcgi alias
#    - echo "cgi.fix_pathinfo = 1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
#    - sudo sed -i -e "s,www-data,travis,g" /etc/apache2/envvars
#    - sudo chown -R travis:travis /var/lib/apache2/fastcgi
#    - ~/.phpenv/versions/$(phpenv version-name)/sbin/php-fpm
#    # configure apache virtual hosts
#    - sudo cp -f build/travis-ci-apache /etc/apache2/sites-available/000-default.conf
#    - sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/000-default.conf
#    - sudo service apache2 restart

stages:
  - lint
  - tests
  - release
#    - name: release
#        # это не обязательно
#        if: branch = next

jobs:
  include:
    - stage: lint
      name: "Lint"
      script: ./bin/console lint:yaml config
#        - |
#        # this checks that the source code follows the Symfony Code Syntax rules
#        '[[ "$TRAVIS_PHP_VERSION" == "nightly" ]] || ./vendor/bin/php-cs-fixer fix --diff --dry-run -v'
#        # this checks that the YAML config files contain no syntax errors
#        ./bin/console lint:yaml config
#        # this checks that the Twig template files contain no syntax errors
#        ./bin/console lint:twig templates
#        # this checks that the XLIFF translations contain no syntax errors
#        ./bin/console lint:xliff translations
#        # this checks that the application doesn't use dependencies with known security vulnerabilities
#        ./bin/console security:check
#        # this checks that Doctrine's mapping configurations are valid
#        ./bin/console doctrine:schema:validate --skip-sync -vvv --no-interaction

    - stage: tests
      name: "Unit Tests"
      script: ./bin/phpunit
    # Define the release stage that runs semantic-release
    - stage: release
      node_js: lts/*
      # Advanced: optionally overwrite your default `script` step to skip the tests
      # script: skip
      deploy:
        provider: script
        skip_cleanup: true
        # npx TODO возможно вообще убрать yarn
        script:
          - yarn semantic-release

