<?php

namespace TheCodingMachine\FluidSchema;

use Doctrine\DBAL\Schema\Schema;
use PHPUnit\Framework\TestCase;

class TdbmFluidJunctionTableOptionsTest extends TestCase
{

    public function testGraphql()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $fluid->table('posts')->uuid();
        $fluid->table('users')->uuid();

        $fluid->junctionTable('posts', 'users')->graphql();

        $this->assertSame("\n@TheCodingMachine\\GraphQLite\\Annotations\\Field", $schema->getTable('posts_users')->getOptions()['comment']);
    }
}
