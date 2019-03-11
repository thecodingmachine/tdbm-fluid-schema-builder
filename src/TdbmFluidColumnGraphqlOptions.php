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
        $this->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Field', '(name="'.addslashes($name).'")');
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
        $this->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Right', '(name="'.addslashes($rightName).'")');
        return $this;
    }

    public function failWith($value): self
    {
        $this->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\FailWith', '('.var_export($value, true).')');
        return $this;
    }

    public function addAnnotation(string $annotation, string $content = '', bool $replaceExisting = true): self
    {
        $this->tdbmFluidColumnOptions->addAnnotation($annotation, $content, $replaceExisting);
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
