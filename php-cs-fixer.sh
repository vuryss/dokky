#!/bin/bash

/library/vendor/bin/php-cs-fixer fix --dry-run --diff -v
exitCode=$?
/library/vendor/bin/php-cs-fixer fix -q
exit $exitCode
