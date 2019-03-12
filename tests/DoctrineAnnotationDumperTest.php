<?php

namespace TheCodingMachine\FluidSchema;

use PHPUnit\Framework\TestCase;

class DoctrineAnnotationDumperTest extends TestCase
{

    public function testExportValues()
    {
        $this->assertSame('', DoctrineAnnotationDumper::exportValues(null));
        $this->assertSame('({})', DoctrineAnnotationDumper::exportValues([]));
        $this->assertSame('("foo")', DoctrineAnnotationDumper::exportValues("foo"));
        $this->assertSame('(foo = "bar")', DoctrineAnnotationDumper::exportValues(["foo"=>"bar"]));
        $this->assertSame('(foo = {"bar":"baz", "baz":"bar"})', DoctrineAnnotationDumper::exportValues(["foo"=>["bar"=>"baz","baz"=>"bar"]]));
    }
}
