<?php


namespace TheCodingMachine\FluidSchema;


use function explode;
use function implode;
use function strpos;

class Comment
{
    /**
     * @var string
     */
    private $comment;

    public function __construct(string $comment)
    {
        $this->comment = $comment;
    }

    public function removeAnnotation(string $annotation): self
    {
        $annotation = ltrim($annotation, '@');
        $commentLines = explode("\n", $this->comment);
        $newLines = [];

        foreach ($commentLines as $commentLine) {
            if (strpos($commentLine, '@') === 0) {
                $newCommentLine = substr($commentLine, 1);
                $annotationName = strtok($newCommentLine, " \t(\n");
                if ($annotationName === $annotation) {
                    continue;
                }
            }
            $newLines[] = $commentLine;
        }
        $this->comment = implode("\n", $newLines);
        return $this;
    }

    public function hasAnnotation(string $annotation): bool
    {
        $annotation = ltrim($annotation, '@');
        $commentLines = explode("\n", $this->comment);

        foreach ($commentLines as $commentLine) {
            if (strpos($commentLine, '@') === 0) {
                $newCommentLine = substr($commentLine, 1);
                $annotationName = strtok($newCommentLine, " \t(\n");
                if ($annotationName === $annotation) {
                    return true;
                }
            }
        }
        return false;
    }

    public function addAnnotation(string $annotation, string $content = '', bool $replaceExisting = true): self
    {
        if ($replaceExisting === true) {
            $this->removeAnnotation($annotation);
        }
        $annotation = ltrim($annotation, '@');
        $this->comment .= "\n@".$annotation.$content;
        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}