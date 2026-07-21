# Architecture Sniffer
[![CI](https://github.com/spryker/architecture-sniffer/workflows/CI/badge.svg?branch=master)](https://github.com/spryker/architecture-sniffer/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/spryker/architecture-sniffer/branch/master/graph/badge.svg?token=4AKCKMRg3G)](https://codecov.io/gh/spryker/architecture-sniffer)
[![Latest Stable Version](https://poser.pugx.org/spryker/architecture-sniffer/v/stable.svg)](https://packagist.org/packages/spryker/architecture-sniffer)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/spryker/architecture-sniffer/license.svg)](https://packagist.org/packages/spryker/architecture-sniffer)
[![Total Downloads](https://poser.pugx.org/spryker/architecture-sniffer/d/total.svg)](https://packagist.org/packages/spryker/architecture-sniffer)

Architecture Sniffer for Spryker core, eco-system and applications.

The package ships two independent rulesets. Use the one that matches what you are working on:

| Ruleset | For | Path |
|---|---|---|
| Core | Spryker core, eco-system and module development | `vendor/spryker/architecture-sniffer/src/ruleset.xml` |
| Project | Application (project) development | `vendor/spryker/architecture-sniffer/src/Project/ruleset.xml` |

Make sure the sniffer is installed as a `require-dev` dependency:
```
composer require --dev spryker/architecture-sniffer
```

Common to both: lower priorities (higher numbers) always include the higher priorities (lower numbers) — a run at priority `3` also reports `1` and `2`.


## Spryker Core Development

Use the core ruleset `src/ruleset.xml`.

### Priority Levels
- `1`: API and critical
- `2`: Non critical (nice to have)
- `3`: Experimental (inspected code needs further fixing)

We use and recommend minimum priority `2` by default for local and CI checks.

### Usage
When using Spryker you can use the Spryker CLI console command:
```
console code:sniff:architecture [-m ModuleName] [optional-sub-path] -v [-p priority]
```
Verbose output is recommended here.

Or run it manually:
```
vendor/bin/phpmd src/Pyz/ (xml|text|html) vendor/spryker/architecture-sniffer/src/ruleset.xml --minimumpriority=2
```

### Including the sniffer in PHPStorm
Add a new custom ruleset under `Editor -> Inspections -> PHP -> PHP Mess Detector validation` and name it `Architecture Sniffer`.
The ruleset is defined in `vendor/spryker/architecture-sniffer/src/ruleset.xml`.

Under `Framework & Languages -> PHP -> Mess Detector` set the path to your phpmd (`vendor/bin/phpmd`), then run `Validate` to confirm it works.


## Spryker Project Development

Use the project ruleset `src/Project/ruleset.xml`. It bundles adapted PHPMD, Spryker architecture, and project-only rules.

### Priority Levels
- `1`: Critical
- `2`: Major
- `3`: Medium
- `4`: Minor

Recommended minimum priority per project maturity:
- `1` and `2`: recommended for **all** projects, including those with legacy code.
- `3`: recommended for all **new** projects.
- `4`: recommended for a **modern AI-assisted development** flow.

### Usage
```
vendor/bin/phpmd src/ (json|text|html) vendor/spryker/architecture-sniffer/src/Project/ruleset.xml --minimumpriority=4
```

### Setup for the project & customizing rules
The project ruleset is meant to be tuned per project. Copy it (and the rulesets it references) to the project level so it can be edited — exclude modules, change priorities, or adjust rule properties without touching the vendor package:
```
vendor/bin/spryker-architecture setup-project [<destination>]
```
`<destination>` defaults to `architecture-sniffer`. After setup, run phpmd against the copied ruleset instead of the vendor one, and change it freely for project needs:
```
vendor/bin/phpmd src/ (json|text|html) architecture-sniffer/ruleset.xml --minimumpriority=4
```
The commands below use this project-level path.

`setup-project` copies these files into `<destination>/`:
- `ruleset.xml` — entry ruleset that references all of the below
- `PhpMd/ruleset.xml` — adapted PHPMD rules (Clean Code, Code Size, Controversial, Design, Naming, Unused Code)
- `Common/ruleset.xml` — cross-layer Spryker and project rules
- `Client/ruleset.xml`, `Glue/ruleset.xml`, `Service/ruleset.xml`, `Shared/ruleset.xml`, `Yves/ruleset.xml`, `Zed/ruleset.xml` — layer-specific rules
- `SprykerProject/ruleset.xml`

### Local Code Review Usage
For a local review, save the report to JSON and format it into a grouped, human-readable summary.

Save the report (scan all priorities for an AI-assisted review):
```
vendor/bin/phpmd src/ json vendor/spryker/architecture-sniffer/src/Project/ruleset.xml --minimumpriority 4 --reportfile architecture-results.json
```
or
```
vendor/bin/phpmd src/ json architecture-sniffer/ruleset.xml --minimumpriority 4 --reportfile architecture-results.json
```

Format it:
```
vendor/bin/spryker-architecture format-project-results architecture-results.json [<output.txt>]
```
Without `<output.txt>` the formatted report is printed to stdout.

### Baseline
Adopt the ruleset on an existing project without refactoring legacy code first: generate a baseline of the current violations and only fail on new ones.
```
# generate phpmd.baseline.xml next to the ruleset
vendor/bin/phpmd src/ text architecture-sniffer/ruleset.xml --generate-baseline

# subsequent runs ignore baselined violations
vendor/bin/phpmd src/ text architecture-sniffer/ruleset.xml --baseline-file phpmd.baseline.xml
```
Use `--update-baseline` to drop violations that no longer exist. Store the baseline in version control and shrink it over time.

### Debugging
Enable Xdebug for phpmd to step through rule code:
```
docker/sdk cli -x
```
```
PHPMD_ALLOW_XDEBUG=true vendor/bin/phpmd src/Pyz/ text architecture-sniffer/ruleset.xml
```

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
