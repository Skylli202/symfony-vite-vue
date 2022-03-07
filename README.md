# symfony-vite-vue

This is simple project to try to use Vite and VueJS within a Symfony project :)

# Useful command line

## phpunit
To run specific tests
> php vendor/bin/phpunit path/to/folder  \
> php vendor/bin/phpunit path/to/file  \
> php vendor/bin/phpunit --testsuite testsuiteName  \
> php vendor/bin/phpunit --filter testFunctionName

For a nice command line output
> php vendor/bin/phpunit --testdox  \
> Remark: `testdox` option will replace all camelCase or snakeCase function's name format into regular sentences with space.

To run code coverage
> php vendor/bin/phpunit --coverage-html path/to/folder/for/result

## phpstan
> php vendor/bin/phpstan analyze path/to/first/folder path/to/second/folder
