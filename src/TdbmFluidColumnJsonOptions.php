<?php


namespace TheCodingMachine\FluidSchema;


use function addslashes;
use Doctrine\DBAL\Types\Type;
use function var_export;

class TdbmFluidColumnJsonOptions
{
    /**
     * @var TdbmFluidColumnOptions
     */
    private $tdbmFluidColumnOptions;

    public function __construct(TdbmFluidColumnOptions $tdbmFluidColumnOptions)
    {
        $this->tdbmFluidColumnOptions = $tdbmFluidColumnOptions;
    }

    public function key(string $name): self
    {
        $this->addAnnotation('JsonKey', ['key' => $name]);
        return $this;
    }

    public function datetimeFormat(string $format): self
    {
        $this->addAnnotation('JsonFormat', ['date' => $format]);
        return $this;
    }

    /**
     * @param int|null $decimals The number of decimals
     * @param string|null $point The decimal point
     * @param string|null $separator The thousands separator
     * @param string|null $unit The suffix to append after a number
     */
    public function numericFormat(?int $decimals = null, ?string $point = null, ?string $separator = null, ?string $unit = null): self
    {
        $params = [];
        if ($decimals !== null) {
            $params['decimals'] = $decimals;
        }
        if ($point !== null) {
            $params['point'] = $point;
        }
        if ($separator !== null) {
            $params['separator'] = $separator;
        }
        if ($unit !== null) {
            $params['unit'] = $unit;
        }
        $this->addAnnotation('JsonFormat', $params);
        return $this;
    }

    /**
     * Serialize in JSON by getting the property from an object.
     */
    public function formatUsingProperty(string $property): self
    {
        $this->addAnnotation('JsonFormat', ['property' => $property]);
        return $this;
    }

    /**
     * Serialize in JSON by calling a method from an object.
     */
    public function formatUsingMethod(string $method): self
    {
        $this->addAnnotation('JsonFormat', ['method' => $method]);
        return $this;
    }

    public function ignore(): self
    {
        $this->addAnnotation('JsonIgnore');
        return $this;
    }

    public function include(): self
    {
        $this->addAnnotation('JsonInclude');
        return $this;
    }

    public function recursive(): self
    {
        $this->addAnnotation('JsonRecursive');
        return $this;
    }

    public function collection(string $key): self
    {
        $this->addAnnotation('JsonCollection', ['key' => $key]);
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

    public function endJsonSerialize(): TdbmFluidColumnOptions
    {
        return $this->tdbmFluidColumnOptions;
    }

    public function then(): TdbmFluidTable
    {
        return $this->tdbmFluidColumnOptions->then();
    }

    public function column(string $name): TdbmFluidColumn
    {
        return $this->tdbmFluidColumnOptions->column($name);
    }
}
