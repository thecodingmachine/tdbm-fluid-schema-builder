<?php

namespace TheCodingMachine\FluidSchema;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

class TdbmFluidJunctionTableGraphqlOptionsTest extends TestCase
{
    public function testGraphql()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $fluid->table('posts')->uuid();
        $fluid->table('users')->uuid();

        $junctionTableOptions = $fluid->junctionTable('posts', 'users');
        $graphqlOptions = $junctionTableOptions->graphql();

        $graphqlOptions->logged(true)
                       ->right('CAN_EDIT')
                       ->failWith(null);

        $this->assertSame("\n@TheCodingMachine\GraphQLite\Annotations\Field
@TheCodingMachine\GraphQLite\Annotations\Logged
@TheCodingMachine\GraphQLite\Annotations\Right(name = \"CAN_EDIT\")
@TheCodingMachine\GraphQLite\Annotations\FailWith(null)", $schema->getTable('posts_users')->getOptions()['comment']);

        $graphqlOptions->logged(false);

        $this->assertSame("\n@TheCodingMachine\GraphQLite\Annotations\Field
@TheCodingMachine\GraphQLite\Annotations\Right(name = \"CAN_EDIT\")
@TheCodingMachine\GraphQLite\Annotations\FailWith(null)", $schema->getTable('posts_users')->getOptions()['comment']);

        $this->assertSame($junctionTableOptions, $graphqlOptions->endGraphql());
    }
}
