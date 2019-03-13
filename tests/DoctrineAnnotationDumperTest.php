<?php

namespace TheCodingMachine\FluidSchema;

use Exception;
use PHPUnit\Framework\TestCase;

class DoctrineAnnotationDumperTest extends TestCase
{

    public function testExportValues()
    {
        $this->assertSame('', DoctrineAnnotationDumper::exportValues(null));
        $this->assertSame('({})', DoctrineAnnotationDumper::exportValues([]));
        $this->assertSame('("foo")', DoctrineAnnotationDumper::exportValues("foo"));
        $this->assertSame('(foo = null)', DoctrineAnnotationDumper::exportValues(["foo"=>null]));
        $this->assertSame('(foo = 42)', DoctrineAnnotationDumper::exportValues(["foo"=>42]));
        $this->assertSame('(foo = "bar")', DoctrineAnnotationDumper::exportValues(["foo"=>"bar"]));
        $this->assertSame('(foo = {"bar":"baz", "baz":"bar"})', DoctrineAnnotationDumper::exportValues(["foo"=>["bar"=>"baz","baz"=>"bar"]]));
        $this->assertSame('(foo = {"baz", "bar"})', DoctrineAnnotationDumper::exportValues(["foo"=>["baz","bar"]]));

        $this->expectException(\RuntimeException::class);
        DoctrineAnnotationDumper::exportValues(new Exception());
    }
}
