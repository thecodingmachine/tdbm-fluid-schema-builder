<?php

namespace TheCodingMachine\FluidSchema;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

class TdbmFluidColumnOptionsTest extends TestCase
{
    public function testOptions()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $column = $posts->column('foo');
        $columnOptions = $column->integer();

        $dbalColumn = $schema->getTable('posts')->getColumn('foo');

        $columnOptions->null();
        $this->assertSame(false, $dbalColumn->getNotnull());

        $columnOptions->notNull();
        $this->assertSame(true, $dbalColumn->getNotnull());

        $columnOptions->unique('unique_foo');
        $this->assertSame(true, $posts->getDbalTable()->getIndex('unique_foo')->isUnique());

        $columnOptions->comment('foo');
        $this->assertSame('foo', $dbalColumn->getComment());

        $columnOptions->autoIncrement();
        $this->assertSame(true, $dbalColumn->getAutoincrement());

        $columnOptions->default(42);
        $this->assertSame(42, $dbalColumn->getDefault());

        $columnOptions->protectedGetter();
        $this->assertContains('@TheCodingMachine\\TDBM\\Utils\\Annotation\\ProtectedGetter', $dbalColumn->getComment());

        $columnOptions->protectedSetter();
        $this->assertContains('@TheCodingMachine\\TDBM\\Utils\\Annotation\\ProtectedSetter', $dbalColumn->getComment());

        $columnOptions->protectedOneToMany();
        $this->assertContains('@TheCodingMachine\\TDBM\\Utils\\Annotation\\ProtectedOneToMany', $dbalColumn->getComment());

        $this->assertSame($posts, $columnOptions->then());

        $columnOptions->column('bar');
        $this->assertTrue($schema->getTable('posts')->hasColumn('bar'));
    }

    public function testIndex()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->column('foo')->integer()->index();

        $this->assertCount(1, $schema->getTable('posts')->getIndexes());
    }

    public function testPrimaryKey()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->column('id')->integer()->primaryKey('pkname');

        $this->assertTrue($schema->getTable('posts')->hasPrimaryKey());
        $this->assertTrue($schema->getTable('posts')->hasIndex('pkname'));
    }
}
