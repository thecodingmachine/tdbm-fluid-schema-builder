<?php

namespace TheCodingMachine\FluidSchema;

use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{

    public function testRemoveAnnotation()
    {
        $comment = new Comment(<<<EOF
Foo bar

@Yop
@Yop("toto")
@Yop ()
@Yop     
@Foo
EOF
);
        $this->assertTrue($comment->hasAnnotation('Yop'));
        $this->assertTrue($comment->hasAnnotation('@Yop'));
        $comment->removeAnnotation('Yop');
        $this->assertFalse($comment->hasAnnotation('Yop'));
        $this->assertFalse($comment->hasAnnotation('@Yop'));
        $this->assertSame(<<<EOF
Foo bar

@Foo
EOF
            , $comment->getComment());
    }

    public function testAddAnnotation()
    {
        $comment = new Comment(<<<EOF
Foo
@Yop
EOF
        );
        $comment->addAnnotation('Yop', '(true)');
        $this->assertSame(<<<EOF
Foo
@Yop(true)
EOF
            , $comment->getComment());
    }
}
