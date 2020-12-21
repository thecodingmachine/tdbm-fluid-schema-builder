<?php


namespace TheCodingMachine\FluidSchema;


class TdbmFluidColumnOptions
{
    /**
     * @var TdbmFluidTable
     */
    private $tdbmFluidTable;
    /**
     * @var FluidColumn
     */
    private $fluidColumn;
    /**
     * @var FluidColumnOptions
     */
    private $fluidColumnOptions;

    public function __construct(TdbmFluidTable $tdbmFluidTable, FluidColumn $fluidColumn, FluidColumnOptions $fluidColumnOptions)
    {
        $this->tdbmFluidTable = $tdbmFluidTable;
        $this->fluidColumn = $fluidColumn;
        $this->fluidColumnOptions = $fluidColumnOptions;
    }

    /**
     * Makes the column not nullable.
     * @return FluidColumnOptions
     */
    public function notNull(): self
    {
        $this->fluidColumnOptions->notNull();
        return $this;
    }

    /**
     * Makes the column nullable.
     * @return self
     */
    public function null(): self
    {
        $this->fluidColumnOptions->null();
        return $this;
    }

    /**
     * Automatically add a unique constraint for the column.
     *
     * @return self
     */
    public function unique(?string $indexName = null): self
    {
        $this->tdbmFluidTable->getDbalTable()->addUniqueIndex([$this->fluidColumn->getDbalColumn()->getName()], $indexName);
        return $this;
    }

    /**
     * Automatically add an index for the column.
     *
     * @return self
     */
    public function index(): self
    {
        $this->fluidColumnOptions->index();
        return $this;
    }
    public function comment(string $comment): self
    {
        $this->fluidColumnOptions->comment($comment);
        return $this;
    }

    public function autoIncrement(): self
    {
        $this->fluidColumnOptions->autoIncrement();
        $this->addAnnotation('Autoincrement');
        return $this;
    }

    public function primaryKey(?string $indexName = null): self
    {
        $this->fluidColumnOptions->primaryKey($indexName);
        return $this;
    }

    public function default($defaultValue): self
    {
        $this->fluidColumnOptions->default($defaultValue);
        return $this;
    }

    public function then(): TdbmFluidTable
    {
        return $this->tdbmFluidTable;
    }

    public function column(string $name): TdbmFluidColumn
    {
        return $this->tdbmFluidTable->column($name);
    }

    public function graphqlField(): TdbmFluidColumnGraphqlOptions
    {
        $this->tdbmFluidTable->graphqlType();
        return new TdbmFluidColumnGraphqlOptions($this, $this->fluidColumn);
    }

    public function jsonSerialize(): TdbmFluidColumnJsonOptions
    {
        return new TdbmFluidColumnJsonOptions($this);
    }

    public function protectedGetter(): self
    {
        $this->addAnnotation('TheCodingMachine\\TDBM\\Utils\\Annotation\\ProtectedGetter');
        return $this;
    }

    public function protectedSetter(): self
    {
        $this->addAnnotation('TheCodingMachine\\TDBM\\Utils\\Annotation\\ProtectedSetter');
        return $this;
    }

    public function protectedOneToMany(): self
    {
        $this->addAnnotation('TheCodingMachine\\TDBM\\Utils\\Annotation\\ProtectedOneToMany');
        return $this;
    }

    private function getComment(): Comment
    {
        $comment = $this->fluidColumn->getDbalColumn()->getComment();

        return new Comment($comment ?? '');
    }

    private function saveComment(Comment $comment): self
    {
        $this->fluidColumn->getDbalColumn()->setComment($comment->getComment());
        return $this;
    }

    /**
     * @param string $annotation
     * @param mixed $content
     * @param bool $replaceExisting
     * @return TdbmFluidColumnOptions
     */
    public function addAnnotation(string $annotation, $content = null, bool $replaceExisting = true, bool $explicitNull = false): self
    {
        $comment = $this->getComment()->addAnnotation($annotation, $content, $replaceExisting, $explicitNull);
        $this->saveComment($comment);
        return $this;
    }

    public function removeAnnotation(string $annotation): self
    {
        $comment = $this->getComment()->removeAnnotation($annotation);
        $this->saveComment($comment);
        return $this;
    }
}
