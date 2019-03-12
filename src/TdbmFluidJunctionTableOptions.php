<?php


namespace TheCodingMachine\FluidSchema;


use function addslashes;
use function var_export;

class TdbmFluidJunctionTableOptions
{

    /**
     * @var TdbmFluidTable
     */
    private $tdbmFluidTable;

    public function __construct(TdbmFluidTable $tdbmFluidTable)
    {
        $this->tdbmFluidTable = $tdbmFluidTable;
    }

    public function graphql(): void
    {
        $this->tdbmFluidTable->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Field');
    }
}
