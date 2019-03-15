<?php

namespace TheCodingMachine\FluidSchema;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

class TdbmFluidColumnGraphqlOptionsTest extends TestCase
{
    public function testGraphql()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $column = $posts->column('foo');
        $columnOptions = $column->integer();

        $graphqlOptions = $columnOptions->graphqlField();

        $graphqlOptions->fieldName('bar')
                       ->logged(true)
                       ->right('CAN_EDIT')
                       ->failWith(null);

        $this->assertSame("\n@TheCodingMachine\GraphQLite\Annotations\Field(name = \"bar\")
@TheCodingMachine\GraphQLite\Annotations\Logged
@TheCodingMachine\GraphQLite\Annotations\Right(name = \"CAN_EDIT\")
@TheCodingMachine\GraphQLite\Annotations\FailWith(null)", $schema->getTable('posts')->getColumn('foo')->getComment());

        $graphqlOptions->logged(false)
            ->outputType('ID');


        $this->assertSame("\n@TheCodingMachine\GraphQLite\Annotations\Right(name = \"CAN_EDIT\")
@TheCodingMachine\GraphQLite\Annotations\FailWith(null)
@TheCodingMachine\GraphQLite\Annotations\Field(name = \"bar\", outputType = \"ID\")", $schema->getTable('posts')->getColumn('foo')->getComment());

        $this->assertSame($columnOptions, $graphqlOptions->endGraphql());

        $column2 = $graphqlOptions->column('foo');
        $this->assertSame($column2, $column);

        $this->assertContains('@TheCodingMachine\GraphQLite\Annotations\Type', $schema->getTable('posts')->getOptions()['comment']);

        $idColumn = $posts->id()->graphqlField();
        $this->assertContains('outputType = "ID"', $schema->getTable('posts')->getColumn('id')->getComment());

        $users = $fluid->table('users');
        $uuidColumn = $users->uuid()->graphqlField();
        $this->assertContains('outputType = "ID"', $schema->getTable('users')->getColumn('uuid')->getComment());

    }
}
