<?php


namespace TheCodingMachine\FluidSchema;

use function addslashes;
use Doctrine\DBAL\Schema\Table;
use function in_array;

class TdbmFluidTable
{
    /**
     * @var FluidSchema|TdbmFluidSchema
     */
    private $schema;
    /**
     * @var FluidTable
     */
    private $fluidTable;
    /**
     * @var NamingStrategyInterface
     */
    private $namingStrategy;

    /**
     * @var array<string, TdbmFluidColumn>
     */
    private $tdbmFluidColumns = [];

    public function __construct(TdbmFluidSchema $schema, FluidTable $fluidTable, NamingStrategyInterface $namingStrategy)
    {
        $this->schema = $schema;
        $this->fluidTable = $fluidTable;
        $this->namingStrategy = $namingStrategy;
    }

    public function column(string $name): TdbmFluidColumn
    {
        if (!isset($this->tdbmFluidColumns[$name])) {
            $this->tdbmFluidColumns[$name] = new TdbmFluidColumn($this, $this->fluidTable->column($name), $this->namingStrategy);
        }
        return $this->tdbmFluidColumns[$name];
    }

    public function index(array $columnNames): TdbmFluidTable
    {
        $this->fluidTable->index($columnNames);
        return $this;
    }

    public function unique(array $columnNames): TdbmFluidTable
    {
        $this->fluidTable->unique($columnNames);
        return $this;
    }

    public function primaryKey(array $columnNames, ?string $indexName = null): TdbmFluidTable
    {
        $this->fluidTable->primaryKey($columnNames, $indexName);
        return $this;
    }

    public function id(): TdbmFluidColumnOptions
    {
        $this->fluidTable->id();
        return $this->column('id')->integer();
    }

    public function uuid(string $version = 'v4'): TdbmFluidColumnOptions
    {
        if ($version !== 'v1' && $version !== 'v4') {
            throw new FluidSchemaException('UUID version must be one of "v1" or "v4"');
        }
        $this->fluidTable->uuid();

        $this->column('uuid')->guid()->addAnnotation('UUID', $version);
        return $this->column('uuid')->guid();
    }

    public function timestamps(): self
    {
        $this->fluidTable->timestamps();
        return $this;
    }

    /**
     * @throws FluidSchemaException
     */
    public function extends(string $tableName): self
    {
        $this->fluidTable->extends($tableName);
        return $this;
    }

    /**
     * Adds a "Bean" annotation to the table.
     */
    public function customBeanName(string $beanName): self
    {
        $this->addAnnotation('Bean', ['name'=>$beanName]);
        return $this;
    }

    /**
     * Adds a "Type" annotation.
     */
    public function graphqlType(): self
    {
        $this->addAnnotation('TheCodingMachine\\GraphQLite\\Annotations\\Type');
        return $this;
    }

    /**
     * Makes the generated bean implement the interface $interfaceName
     *
     * @param string $interfaceName The fully qualified name of the PHP interface to implement.
     * @return TdbmFluidTable
     */
    public function implementsInterface(string $interfaceName): self
    {
        $this->addAnnotation('AddInterface', ['name' => $interfaceName], false);
        return $this;
    }

    /**
     * Makes the generated DAO implement the interface $interfaceName
     *
     * @param string $interfaceName The fully qualified name of the PHP interface to implement.
     * @return TdbmFluidTable
     */
    public function implementsInterfaceOnDao(string $interfaceName): self
    {
        $this->addAnnotation('AddInterfaceOnDao', ['name' => $interfaceName], false);
        return $this;
    }

    /**
     * Makes the generated bean implement the trait $traitName
     *
     * @param string $traitName
     * @param string[] $modifiers An array of modifiers. For instance: ['A::foo insteadof B']
     * @return TdbmFluidTable
     */
    public function useTrait(string $traitName, array $modifiers = []): self
    {
        $this->addAnnotation('AddTrait', ['name' => $traitName, 'modifiers' => $modifiers], false);
        return $this;
    }

    /**
     * Makes the generated DAO implement the trait $traitName
     *
     * @param string $traitName
     * @param string[] $modifiers An array of modifiers. For instance: ['A::foo insteadof B']
     * @return TdbmFluidTable
     */
    public function useTraitOnDao(string $traitName, array $modifiers = []): self
    {
        $this->addAnnotation('AddTraitOnDao', ['name' => $traitName, 'modifiers' => $modifiers], false);
        return $this;
    }

    private function getComment(): Comment
    {
        $options = $this->fluidTable->getDbalTable()->getOptions();
        $comment = $options['comment'] ?? '';

        return new Comment($comment);
    }

    private function saveComment(Comment $comment): self
    {
        $this->fluidTable->getDbalTable()->addOption('comment', $comment->getComment());
        return $this;
    }

    /**
     * @param string $annotation
     * @param mixed $content
     * @param bool $replaceExisting
     * @return TdbmFluidTable
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

    public function getDbalTable(): Table
    {
        return $this->fluidTable->getDbalTable();
    }
}
