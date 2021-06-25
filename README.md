UserBalance service
============

## Stack

- PHP 8.0
- Docker

## Task

Please assume that you are writing business production ready code. The goal of this exercise is to check problem solving
skills, so final goal is not just green tests but clean problem field solution.

## Project Setup

Build environment and initialize composer and project dependencies:

`make build`

Install project dependencies

`composer-install`

Update project dependencies

`composer-update`

Show outdated project dependencies

`composer-outdated`

Validate composer config

`composer-validate`

Full test circle

`make test` - requires fix for tests path to ./test/

Execute tests:

`make tests-unit` - there no Unit tests on the moment

`make tests-integration`

Static code analysis:

`make style`

Code style fixer:

`make coding-standards-fixer`

Code style checker (PHP CS Fixer and PHP_CodeSniffer):

`make coding-standards`

Psalm is a static analysis tool for finding errors in PHP applications, built on top of PHP Parser:

`make psalm`

PHPStan focuses on finding errors in your code without actually running it.

`make phpstan`

Phan is a static analyzer for PHP that prefers to minimize false-positives. Phan attempts to prove incorrectness rather
than correctness.

`make phan`

Enter in php container:

`make php-shell`
