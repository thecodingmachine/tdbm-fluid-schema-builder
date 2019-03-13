<?php


namespace TheCodingMachine\FluidSchema;


use function addslashes;
use Doctrine\DBAL\Types\Type;
use function var_export;

class TdbmFluidColumnGraphqlOptions
{
    /**
     * @var TdbmFluidColumnOptions
     */
    private $tdbmFluidColumnOptions;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $outputType;
    /**
     * @var FluidColumn
     */
    private $fluidColumn;

    public function __construct(TdbmFluidColumnOptions $tdbmFluidColumnOptions, FluidColumn $fluidColumn)
    {
        $this->tdbmFluidColumnOptions = $tdbmFluidColumnOptions;
        $this->fluidColumn = $fluidColumn;
        if (!$this->getComment()->hasAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Field')) {
            $this->generateFieldAnnotation();
        }
    }

    private function getComment(): Comment
    {
        $comment = $this->fluidColumn->getDbalColumn()->getComment();

        return new Comment($comment ?? '');
    }

    public function fieldName(string $name): self
    {
        $this->name = $name;
        $this->generateFieldAnnotation();
        return $this;
    }

    public function outputType(string $outputType): self
    {
        $this->outputType = $outputType;
        $this->generateFieldAnnotation();
        return $this;
    }

    private function generateFieldAnnotation(): void
    {
        $outputType = null;
        if ($this->outputType !== null) {
            $outputType = $this->outputType;
        } elseif ($this->fluidColumn->getDbalColumn()->getType() === Type::getType(Type::GUID)) {
            $outputType = 'ID';
        } else {
            // If the column is the primary key, let's add an ID type
            $pk = $this->tdbmFluidColumnOptions->then()->getDbalTable()->getPrimaryKey();
            if ($pk !== null && $pk->getColumns() === [$this->fluidColumn->getDbalColumn()->getName()]) {
                $outputType = 'ID';
            }
        }

        $parameters = array_filter([
            'name' => $this->name,
            'outputType' => $outputType
        ]);
        if (empty($parameters)) {
            $parameters = null;
        }
        $this->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Field', $parameters);
    }

    public function logged(bool $mustBeLogged = true): self
    {
        if ($mustBeLogged) {
            $this->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Logged');
        } else {
            $this->removeAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Logged');
        }
        return $this;
    }

    public function right(string $rightName): self
    {
        $this->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Right', ['name'=>$rightName]);
        return $this;
    }

    public function failWith($value): self
    {
        $this->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\FailWith', $value, true, true);
        return $this;
    }

    /**
     * @param string $annotation
     * @param mixed $content
     * @param bool $replaceExisting
     * @return TdbmFluidColumnGraphqlOptions
     */
    public function addAnnotation(string $annotation, $content = null, bool $replaceExisting = true, bool $explicitNull = false): self
    {
        $this->tdbmFluidColumnOptions->addAnnotation($annotation, $content, $replaceExisting, $explicitNull);
        return $this;
    }

    public function removeAnnotation(string $annotation): self
    {
        $this->tdbmFluidColumnOptions->removeAnnotation($annotation);
        return $this;
    }

    public function endGraphql(): TdbmFluidColumnOptions
    {
        return $this->tdbmFluidColumnOptions;
    }

    public function column(string $name): TdbmFluidColumn
    {
        return $this->tdbmFluidColumnOptions->column($name);
    }
}
