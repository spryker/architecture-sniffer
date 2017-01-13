# Architecture Sniffer
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/spryker/architecture-sniffer/license.svg)](https://packagist.org/packages/spryker/architecture-sniffer)

Architecture Sniffer for Spryker core, eco-system and applications.

### Running it
You can run the Architecture Sniffer from console by using:
`vendor/bin/phpmd vendor/spryker/spryker/Bundles/%bundle name% (xml|text|html) vendor/spryker/architecture-sniffer/src/ruleset.xml`

### Including the sniffer in PHPStorm
Add a new custom ruleset under `Editor -> Inspections -> PHP -> PHP Mess Detector validation`.
Name it `Architecture Sniffer` for example.

The customer ruleset is defined in `vendor/spryker/architecture-sniffer/src/ruleset.xml`

### Check Mess Detector Settings
Under `Framework & Languages -> PHP -> Mess Detector` you need to define the configuration and set the path to your phpmd (vendor/bin/phpmd). Use local and run `Validate` to see if it works.

## Writing new sniffs
Add them to inside src folder and add tests in `tests` with the same folder structure.
Don't forget to update `ruleset.xml`.

### Setup
Run
```
./setup.sh
```
and
```
php composer.phar install
```

### Testing
Don't forget to test your changes:
```
php phpunit.phar
```

### Running code-sniffer on this project
Make sure this repository is Spryker coding standard conform:
```
php composer.phar cs-check
```
If you want to fix the fixable errors, use
```
php composer.phar cs-fix
```
Once everything is green you can make a PR with your changes.
