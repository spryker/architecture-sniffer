# Architecture Sniffer
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)

Architecture Sniffer for Spryker core and applications.

### Running it
You can run the Architecture Sniffer from console by using:
`vendor/bin/phpmd vendor/spryker/spryker/Bundles/%bundle name% (xml|text|html) vendor/spryker/architecture-sniffer/src/ruleset.xml`

### Including the sniffer in PHPStorm
Add a new custom ruleset under `Editor -> Inspections -> PHP -> Code Style -> PHP Mess Detector validation`.
Name it `Architecture Sniffer` for example.

The customer ruleset is defined in `vendor/spryker/architecture-sniffer/src/ruleset.xml`

## Writing new sniffs
Add them to inside src folder and add tests in `tests` with the same folder structure.
Don't forget to update `ruleset.xml`.

Don't forget to test your changes:

    php phpunit.phar

### Running code-sniffer on this project
Make sure this repository is Spryker coding standard conform:
```
vendor/bin/phpcs . -v --standard=vendor/spryker/code-sniffer/Spryker/ruleset.xml --ignore=architecture-sniffer/vendor/
```
If you want to fix the fixable errors, use
```
vendor/bin/phpcbf . --standard=vendor/spryker/code-sniffer/Spryker/ruleset.xml --ignore=architecture-sniffer/vendor/
```
Once everything is green you can make a PR with your changes.
