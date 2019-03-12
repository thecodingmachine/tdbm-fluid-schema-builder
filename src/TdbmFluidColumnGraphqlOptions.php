<?php


namespace TheCodingMachine\FluidSchema;


use function addslashes;
use function var_export;

class TdbmFluidColumnGraphqlOptions
{
    /**
     * @var TdbmFluidColumnOptions
     */
    private $tdbmFluidColumnOptions;

    public function __construct(TdbmFluidColumnOptions $tdbmFluidColumnOptions)
    {
        $this->tdbmFluidColumnOptions = $tdbmFluidColumnOptions;
    }

    public function fieldName(string $name): self
    {
        $this->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Field', ['name'=>$name]);
        return $this;
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
