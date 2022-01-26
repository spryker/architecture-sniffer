# Architecture Sniffer
[![CI](https://github.com/spryker/architecture-sniffer/workflows/CI/badge.svg?branch=master)](https://travis-ci.org/spryker/architecture-sniffer)
[![Coverage](https://codecov.io/gh/spryker/architecture-sniffer/branch/master/graph/badge.svg?token=4AKCKMRg3G)](https://codecov.io/gh/spryker/architecture-sniffer)
[![Latest Stable Version](https://poser.pugx.org/spryker/architecture-sniffer/v/stable.svg)](https://packagist.org/packages/spryker/architecture-sniffer)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/spryker/architecture-sniffer/license.svg)](https://packagist.org/packages/spryker/architecture-sniffer)
[![Total Downloads](https://poser.pugx.org/spryker/architecture-sniffer/d/total.svg)](https://packagist.org/packages/spryker/architecture-sniffer)

Architecture Sniffer for Spryker core, eco-system and applications.


## Priority Levels

- `1`: API and critical
- `2`: Non critical (nice to have)
- `3`: Experimental (inspected code needs further fixing)

We use and recommend minimum priority `2` by default for local and CI checks.

Note: Lower priorities (higher numbers) always include the higher priorities (lower numbers).

## Usage

Make sure you include the sniffer as `require-dev` dependency:
```
composer require --dev spryker/architecture-sniffer
```

### Spryker Usage
When using Spryker you can use the Spryker CLI console command for it:
```
console code:sniff:architecture [-m ModuleName] [optional-sub-path] -v [-p priority]
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

Every sniff needs a description as full sentence:
```php
    protected const RULE = 'Every Foo needs Bar.';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return static::RULE;
    }
```

Every sniff needs to implement either the `ClassAware`, `FunctionAware`, `InterfaceAware`, or `MethodAware` interface to be recognised.
To validate that sniffer recognises your rule, check if your rule is listed in Zed UI > Maintenance > Architecture sniffer.


Also note:
- The rule names must be unique across the rulesets.
- Each rule should contain only one "check".
- Each rule always outputs also the reason (violation), not just the occurrence.

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
