<?php


namespace TheCodingMachine\FluidSchema;


use function addslashes;
use function var_export;

class TdbmFluidJunctionTableGraphqlOptions
{
    /**
     * @var TdbmFluidJunctionTableOptions
     */
    private $tdbmFluidJunctionTableOptions;
    /**
     * @var TdbmFluidTable
     */
    private $tdbmFluidTable;

    public function __construct(TdbmFluidJunctionTableOptions $tdbmFluidJunctionTableOptions, TdbmFluidTable $tdbmFluidTable)
    {
        $this->tdbmFluidJunctionTableOptions = $tdbmFluidJunctionTableOptions;
        $this->tdbmFluidTable = $tdbmFluidTable;
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
     * @return TdbmFluidJunctionTableGraphqlOptions
     */
    public function addAnnotation(string $annotation, $content = null, bool $replaceExisting = true, bool $explicitNull = false): self
    {
        $this->tdbmFluidTable->addAnnotation($annotation, $content, $replaceExisting, $explicitNull);
        return $this;
    }

    public function removeAnnotation(string $annotation): self
    {
        $this->tdbmFluidTable->removeAnnotation($annotation);
        return $this;
    }

    public function endGraphql(): TdbmFluidJunctionTableOptions
    {
        return $this->tdbmFluidJunctionTableOptions;
    }
}
