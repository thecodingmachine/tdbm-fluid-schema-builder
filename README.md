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

// Customize the visibility of a column
$db->table('posts')
   ->column('user_id')->references('users')
                      ->protectedGetter() // The Post.getUser() method is protected
                      ->protectedSetter() // The Post.setUser() method is protected
                      ->protectedOneToMany() // The User.getPosts() method is protected

// Customize implemented interfaces
$db->table('posts')
   ->implementsInterface('App\\PostInterface')  // The generated bean will implement interface App\\PostInterface
   ->implementsInterfaceOnDao('App\\PostDaoInterface'); // The generated DAO will implement interface App\\PostDaoInterface

// The "posts" table will generate a GraphQL type (i.e. the bean will be annotated with the GraphQLite @Type annotation).
$posts = $db->table('posts')->graphqlType();

// You can pass a new 'v1' or 'v4' parameter to uuid().
// This will generate a @UUID TDBM annotation that will help TDBM autogenerate the UUID 
$posts = $db->table('posts')->column('title')->string(50)->graphqlField() // The column is a GraphQL field
            ->fieldName('the_title') // Let's set the name of the field to a different value 
            ->logged() // The user must be logged to view the field
            ->right('CAN_EDIT') // The user must have the 'CAN_EDIT' right to view the field
            ->failWith(null) // If the user is not logged or has no right, let's serve 'null'
            ->endGraphql();

// You can pass instructions on how JSON serialization occurs.
// This will generate a set of JSONxxx annotations.
$nodes = $db->table('nodes')
    ->column('id')->integer()->primaryKey()->autoIncrement()->jsonSerialize()->ignore()
    ->column('alias_id')->references('nodes')->null()->jsonSerialize()->recursive()
    ->column('parent_id')->references('nodes')->null()->jsonSerialize()->include()
    ->column('root_id')->references('nodes')->null()->jsonSerialize()->ignore()
    ->column('owner_id')->references('authors')->null()->jsonSerialize()->formatUsingProperty('name')->include()
    ->column('owner_country')->references('authors')->null()->jsonSerialize()->formatUsingMethod('getCountryName')->include()
    ->column('name')->string()->jsonSerialize()->key('basename')
    ->column('size')->integer()->notNull()->default(0)->jsonSerialize()->numericFormat(null, null, null, ' o')
    ->column('weight')->float()->null()->jsonSerialize()->numericFormat(2, ',', '.', 'g')
    ->column('created_at')->date()->null()->jsonSerialize()->datetimeFormat("Y-m-d")
    ->column('another_parent')->references('nodes')->comment('@JsonCollection("entries") @JsonFormat(property="entry")');

$db->junctionTable('posts', 'users')->graphqlField(); // Expose the many-to-many relationship as a GraphQL field.
```
