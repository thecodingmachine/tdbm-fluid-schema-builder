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

    public function graphqlField(): TdbmFluidJunctionTableGraphqlOptions
    {
        $this->tdbmFluidTable->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Field');
        return new TdbmFluidJunctionTableGraphqlOptions($this, $this->tdbmFluidTable);
    }
}
