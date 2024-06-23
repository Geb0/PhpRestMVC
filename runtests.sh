#!/bin/bash

echo "Run PHPUnit tests for PhpRestMVC"
echo "--------------------------------"

./vendor/bin/phpunit --display-warnings tests
