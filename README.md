[![Latest Stable Version](https://poser.pugx.org/thecodingmachine/tdbm-fluid-schema-builder/v/stable)](https://packagist.org/packages/thecodingmachine/tdbm-fluid-schema-builder)
[![Total Downloads](https://poser.pugx.org/thecodingmachine/tdbm-fluid-schema-builder/downloads)](https://packagist.org/packages/thecodingmachine/tdbm-fluid-schema-builder)
[![Latest Unstable Version](https://poser.pugx.org/thecodingmachine/tdbm-fluid-schema-builder/v/unstable)](https://packagist.org/packages/thecodingmachine/tdbm-fluid-schema-builder)
[![License](https://poser.pugx.org/thecodingmachine/tdbm-fluid-schema-builder/license)](https://packagist.org/packages/thecodingmachine/tdbm-fluid-schema-builder)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecodingmachine/tdbm-fluid-schema-builder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thecodingmachine/tdbm-fluid-schema-builder/?branch=master)
[![Build Status](https://travis-ci.org/thecodingmachine/tdbm-fluid-schema-builder.svg?branch=master)](https://travis-ci.org/thecodingmachine/tdbm-fluid-schema-builder)
[![Coverage Status](https://coveralls.io/repos/thecodingmachine/tdbm-fluid-schema-builder/badge.svg?branch=master&service=github)](https://coveralls.io/github/thecodingmachine/tdbm-fluid-schema-builder?branch=master)

# Fluid schema builder for TDBM

Build and modify your database schema using [DBAL](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/schema-representation.html) and a fluid syntax.
This project allows you to tailor your database schema to TDBM (it will allow you to easily add annotations that TDBM can read).

It is a super-set of [dbal-fluid-schema-builder](https://github.com/thecodingmachine/dbal-fluid-schema-builder/).

If you don't know *dbal-fluid-schema-builder*, [check the documentation first](https://github.com/thecodingmachine/dbal-fluid-schema-builder/).


## Why?

TDBM can read a number of annotations in the schema comments (see [TDBM annotations documentation](https://thecodingmachine.github.io/tdbm/doc/annotations.html)).

This library allows to write these annotations using functions added to the "dbal-fluid-schema-builder".

## What's added?

```php
$db = new TdbmFluidSchema($schema);

// Customize the name of the Bean class
$posts = $db->table('posts')->customBeanName('Article');

// You can pass a new 'v1' or 'v4' parameter to uuid().
// This will generate a @UUID TDBM annotation that will help TDBM autogenerate the UUID 
$posts = $db->table('posts')->uuid('v4');

// You can pass a new 'v1' or 'v4' parameter to uuid().
// This will generate a @UUID TDBM annotation that will help TDBM autogenerate the UUID 
$posts = $db->table('posts')->string('title')->graphql() // The column is a GraphQL field
            ->fieldName('the_title') // Let's set the name of the field to a different value 
            ->logged() // The user must be logged to view the field
            ->right('CAN_EDIT') // The user must have the 'CAN_EDIT' right to view the field
            ->failWith(null) // If the user is not logged or has no right, let's serve 'null'
            ->endGraphql();
```
