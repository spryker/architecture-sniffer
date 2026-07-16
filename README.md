# Architecture Sniffer
[![CI](https://github.com/spryker/architecture-sniffer/workflows/CI/badge.svg?branch=master)](https://github.com/spryker/architecture-sniffer/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/spryker/architecture-sniffer/branch/master/graph/badge.svg?token=4AKCKMRg3G)](https://codecov.io/gh/spryker/architecture-sniffer)
[![Latest Stable Version](https://poser.pugx.org/spryker/architecture-sniffer/v/stable.svg)](https://packagist.org/packages/spryker/architecture-sniffer)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/spryker/architecture-sniffer/license.svg)](https://packagist.org/packages/spryker/architecture-sniffer)
[![Total Downloads](https://poser.pugx.org/spryker/architecture-sniffer/d/total.svg)](https://packagist.org/packages/spryker/architecture-sniffer)

Architecture Sniffer for Spryker core, eco-system and applications.

This package ships **two rulesets**:

- The **core ruleset** (`src/ruleset.xml`) — for developers of Spryker core, eco-system and
  standalone modules. Unchanged.
- The **project ruleset** (`src/Project/ruleset.xml`) — for project teams validating their own
  `src/Pyz/` application code. It merges the [project architecture sniffer rules](documentations/PROJECTrules.md),
  the [adapted core-Spryker rules](documentations/SPRYKERrules.md), and a curated set of
  [adapted PHPMD rules](documentations/PHPMDrules.md).

## Priority Levels

- `1`: API and critical
- `2`: Non critical (nice to have)
- `3`: Experimental (inspected code needs further fixing)
- `4`: Minor

We use and recommend minimum priority `2` by default for the **core** ruleset.
For the **project** ruleset we recommend minimum priority `3` by default for local and CI checks.

Note: Lower priorities (higher numbers) always include the higher priorities (lower numbers).

## Content

The project ruleset aggregates:

- `35` adapted [PHPMD rules](documentations/PHPMDrules.md)
- `30` adapted [Spryker Architecture sniffer rules](documentations/SPRYKERrules.md)
- `13` new [Project Architecture sniffer rules](documentations/PROJECTrules.md)

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

### Running the core ruleset (core / eco-system / module developers)
Validate core-style code against the unchanged core ruleset:
```
vendor/bin/phpmd src/Spryker/ text vendor/spryker/architecture-sniffer/src/ruleset.xml --minimumpriority 2
```

### Running the project ruleset (project teams)
Validate your project application code (`src/Pyz/`) against the project ruleset:
```
PHPMD_ALLOW_XDEBUG=true vendor/bin/phpmd src/Pyz/ text vendor/spryker/architecture-sniffer/src/Project/ruleset.xml --minimumpriority 3
```

Note: Lower priorities always include the higher priorities in the validation process.

### Rulesets

| Ruleset | Path | Contains |
|---|---|---|
| Core | `vendor/spryker/architecture-sniffer/src/ruleset.xml` | Core Spryker architecture rules (unchanged). |
| Project — all | `vendor/spryker/architecture-sniffer/src/Project/ruleset.xml` | Everything: PHPMD + adapted Spryker + new project rules. |
| Project — Spryker + project only | `vendor/spryker/architecture-sniffer/src/Project/SprykerProject/ruleset.xml` | Adapted Spryker rules + new project rules, no PHPMD. |
| Project — PHPMD only | `vendor/spryker/architecture-sniffer/src/Project/PhpMd/ruleset.xml` | The 35 adapted PHPMD rules only. |

Note: the Glue project rules are referenced via `vendor/spryker/architecture-sniffer/src/Project/Glue/ruleset.xml`.
It is wired in through the aggregating rulesets and does not need to be invoked directly.

### Baseline

Existing projects and demo-shops may already contain rule violations.
The decision to refactor existing violations is at the discretion of each project.
To integrate the project ruleset immediately without failing on pre-existing debt, generate a
[baseline](https://phpmd.org/documentation/#baseline), commit it, and have CI run against it so
only **new** violations fail the build.

Generate the baseline:
```
vendor/bin/phpmd src/Pyz/ baseline vendor/spryker/architecture-sniffer/src/Project/ruleset.xml --baseline-file phpmd.project.baseline.xml
```

Commit the generated `phpmd.project.baseline.xml`. Then run CI with the baseline so only new
violations fail:
```
PHPMD_ALLOW_XDEBUG=true vendor/bin/phpmd src/Pyz/ text vendor/spryker/architecture-sniffer/src/Project/ruleset.xml --minimumpriority 3 --baseline-file phpmd.project.baseline.xml
```

It is also permissible to [suppress rules](https://phpmd.org/documentation/suppress-warnings.html)
on a case-by-case basis.

### Local Code Review Usage

Produce a JSON report, then render it into a readable summary with the bundled formatter:
```
vendor/bin/phpmd src/Pyz/ json vendor/spryker/architecture-sniffer/src/Project/ruleset.xml --minimumpriority 4 --reportfile results.json

php vendor/spryker/architecture-sniffer/tools/formatProjectResults.php results.json
```

### Debugging

PHPMD disables Xdebug by default. To run with Xdebug attached (for example when stepping through a
rule), allow it explicitly:
```
PHPMD_ALLOW_XDEBUG=true vendor/bin/phpmd src/Pyz/ text vendor/spryker/architecture-sniffer/src/Project/ruleset.xml --minimumpriority 3
```

Inside the dockerized Spryker SDK, start the CLI container with Xdebug enabled:
```
docker/sdk cli -x
```

### Including the sniffer in PHPStorm
Add a new custom ruleset under `Editor -> Inspections -> PHP -> PHP Mess Detector validation`.
Name it `Architecture Sniffer` for example.

The custom ruleset is defined in `vendor/spryker/architecture-sniffer/src/ruleset.xml` (core) or
`vendor/spryker/architecture-sniffer/src/Project/ruleset.xml` (project).

### Check Mess Detector Settings
Under `Framework & Languages -> PHP -> Mess Detector` you need to define the configuration and set the path to your phpmd (vendor/bin/phpmd). Use local and run `Validate` to see if it works.


## Writing new sniffs
Add them inside the `src` folder and add tests in `tests` with the same folder structure.
Most project-specific rules live under `src/Project/<Layer>/` in the `ArchitectureSniffer\Project\` namespace.
The exception is a rule that belongs to an existing core per-layer family (e.g. the Glue
`DependencyProviderMethodNameRule` lives at `src/Glue/DependencyProvider/` in the
`ArchitectureSniffer\Glue\DependencyProvider` namespace) — extend the core family rather than duplicating it.
Don't forget to update the relevant `ruleset.xml`.

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
