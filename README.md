# Architecture Sniffer
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)

Architecture Sniffer for Spryker core and applications.

## Usage

Make sure you include the sniffer as `require-dev` dependency:
```
composer require --dev spryker/architecture-sniffer
```

### Running it from this repo

```
vendor/bin/phpmd /folder/to/check text src/ruleset.xml
```

### Running it from any project

```
vendor/bin/phpmd /folder/to/check text vendor/spryker/architecture-sniffer/Spryker/ruleset.xml
```

### Convenience wrapper for Spryker projects
This checks the project code itself and excludes vendor automatically:
```
vendor/bin/console code:phpmd
```

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
