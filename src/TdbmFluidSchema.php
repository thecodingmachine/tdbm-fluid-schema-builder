<?php


namespace TheCodingMachine\FluidSchema;


use Doctrine\DBAL\Schema\Schema;

class TdbmFluidSchema
{
    /**
     * @var FluidSchema
     */
    private $fluidSchema;

    /**
     * @var NamingStrategyInterface
     */
    private $namingStrategy;

    /**
     * @var TdbmFluidTable[]
     */
    private $tdbmFluidTables;

    /**
     * @param Schema $schema
     * @param null|NamingStrategyInterface $namingStrategy
     */
    public function __construct(Schema $schema, ?NamingStrategyInterface $namingStrategy = null)
    {
        $this->namingStrategy = $namingStrategy ?: new DefaultNamingStrategy();
        $this->fluidSchema = new FluidSchema($schema, $this->namingStrategy);
    }

    public function table(string $name): TdbmFluidTable
    {
        $name = $this->namingStrategy->quoteIdentifier($name);

        if (isset($this->tdbmFluidTables[$name])) {
            return $this->tdbmFluidTables[$name];
        }

        $this->tdbmFluidTables[$name] = new TdbmFluidTable($this, $this->fluidSchema->table($name), $this->namingStrategy);

        return $this->tdbmFluidTables[$name];
    }

    /**
     * Creates a table joining 2 other tables through a foreign key.
     *
     * @param string $table1
     * @param string $table2
     * @return FluidSchema
     */
    public function junctionTable(string $table1, string $table2): TdbmFluidSchema
    {
        $this->fluidSchema->junctionTable($table1, $table2);

        return $this;
    }

    /**
     * Returns the underlying schema.
     * @return Schema
     */
    public function getDbalSchema(): Schema
    {
        return $this->fluidSchema->getDbalSchema();
    }
}
