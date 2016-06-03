# Architecture Sniffer
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)

Architecture Sniffer for Spryker core and applications.

## Usage

This tool is currently designed to run as a standalone tool - included in your IDE.
So clone this repository somewhere outside of your project.
```
git clone https://github.com/spryker/architecture-sniffer.git
```
Then make sure all dependencies are available:
```
composer update
```
If composer is not installed globally, you can also download and use a phar file here.


### Running it from this repo

Create a new watcher under `Tools -> FileWatchers`.
Name it `Architecture Sniffer` for example.

Set `immediate file synchronization` to true and `Show Console` to `never`.
Select `PHP` as file type and `Project files` as scope.

Let's assume you cloned the repo into `/home/yourname/architecture-sniffer/`.

Then set your own path to phpmd as `Program` path:
```
/home/yourname/architecture-sniffer/vendor/bin/phpmd
```

As arguments use type [text/xml/html] and your rule set path.
```
$FilePath$ text /home/yourname/architecture-sniffer/src/ruleset.xml
```

It is recommended to also append `--minimumpriority=4` or some higher value to reduce some
of the noise. For major bundle releases it is wise to lover this number again so less
important issues can be found and fixed here, too.


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
