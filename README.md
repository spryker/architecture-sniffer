# Architecture Sniffer
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/spryker/architecture-sniffer/license.svg)](https://packagist.org/packages/spryker/architecture-sniffer)

Architecture Sniffer for Spryker core, eco-system and applications.


## Levels

- `1`: API und critical
- `2`: Non critical (nice to have)
- `3`: Experimental (too many false positives, needs further fixing until final)

We use and recommend level `2` by default for local and CI checks.


## Usage

### Spryker Usage
When using Spryker you can use the Spryker CLI console command for it:
```
console code:architecture [-m ModuleName] [optional-path] -v
```
Verbose output is recommended here.

### Manual Usage
You can also manually run the Architecture Sniffer from console by using:
```
vendor/bin/phpmd src/Pyz/ (xml|text|html) vendor/spryker/architecture-sniffer/src/ruleset.xml --minimumpriority=2
```

Note: Lower priorities always include the higher priorities in the validation process.

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
