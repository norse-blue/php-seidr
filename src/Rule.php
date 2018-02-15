<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr;

use ArrayAccess;
use InvalidArgumentException;
use JsonSerializable;
use NorseBlue\Seidr\Concerns\HasProxiedAttributes;
use NorseBlue\Seidr\Contracts\RuleDefinition;
use NorseBlue\Seidr\Contracts\RuleOptionsDefinition;
use NorseBlue\Sekkr\Arr;

/**
 * Class Rule
 *
 * @package NorseBlue\Seidr
 * @property mixed       $default
 * @property callable    $modifier
 * @property RuleOptions $options
 * @property string      $maps
 */
class Rule implements ArrayAccess, JsonSerializable, RuleDefinition
{
    use HasProxiedAttributes;

    //region ========== Properties ==========
    /**
     * @var mixed The rule's default value if the data is not found.
     */
    protected $default;

    /**
     * @var callable The rule's modifier to transform the data value.
     */
    protected $modifier;

    /**
     * @var RuleOptions The rule's options.
     */
    protected $options;

    /**
     * @var string The rule's maps value (the path to the data in the XML content).
     */
    protected $maps;
    //endregion

    //region ========== Constructor ==========
    /**
     * Rule constructor.
     *
     * @param string                      $maps
     * @param mixed                       $default
     * @param callable|null               $modifier
     * @param array|RuleOptionsDefinition $options
     */
    public function __construct(
        string $maps = self::DEFAULT['maps'],
        $default = self::DEFAULT['default'],
        ?callable $modifier = self::DEFAULT['modifier'],
        $options = self::DEFAULT['options']
    ) {
        $this->maps = $maps;
        $this->default = $default;
        $this->modifier = $modifier;
        $this->options = RuleOptions::parse($options);
    }
    //endregion

    //region ========== Static ==========
    /**
     * Converts the given array or RuleDefinition into an Arr instance.
     *
     * @param array|RuleDefinition $definition
     *
     * @return Arr
     * @throws InvalidArgumentException
     */
    protected static function convertToArr($definition): Arr
    {
        if ($definition instanceof RuleDefinition) {
            return ext_arr($definition->toDefinition());
        }

        if (!is_array($definition)) {
            throw new InvalidArgumentException(sprintf(
                'Parameter $definition should be of type \'array|RuleDefinition\', but \'%s\' given.',
                gettype($definition)
            ));
        }

        return ext_arr($definition);
    }

    /**
     * Parses the given definition into a Rule instance.
     *
     * @param array|RuleDefinition $definition
     *
     * @return Rule
     */
    public static function parse($definition): self
    {
        $instance = new static;
        $instance->load($definition);

        return $instance;
    }
    //endregion

    //region ========== Accessors ==========
    /**
     * Attribute $default accessor.
     *
     * @return mixed
     */
    protected function getDefaultAttribute()
    {
        return $this->default;
    }

    /**
     * Attribute $modifier accessor.
     *
     * @return callable|null
     */
    protected function getModifierAttribute(): ?callable
    {
        return $this->modifier;
    }

    /**
     * Attribute $options accessor.
     *
     * @return RuleOptions
     */
    protected function getOptionsAttribute(): RuleOptions
    {
        return $this->options;
    }

    /**
     * Attribute $maps accessor.
     *
     * @return string
     */
    protected function getmapsAttribute(): string
    {
        return $this->maps;
    }
    //endregion

    //region ========== Mutators ==========
    /**
     * Attribute $default mutator.
     *
     * @param mixed $value
     */
    protected function setDefaultAttribute($value): void
    {
        $this->default = $value;
    }

    /**
     * Attribute $modifier mutator.
     *
     * @param callable|null $value
     */
    protected function setModifierAttribute(?callable $value): void
    {
        $this->modifier = $value;
    }

    /**
     * Attribute $options mutator.
     *
     * @param array|RuleOptions $value
     */
    protected function setOptionsAttribute($value): void
    {
        $this->options = RuleOptions::parse($value);
    }

    /**
     * Attribute $maps mutator.
     *
     * @param string $value
     */
    protected function setmapsAttribute(string $value): void
    {
        $this->maps = $value;
    }
    //endregion

    //region ========== Methods ==========
    /**
     * Loads the given definition into this instance.
     *
     * @param array|RuleDefinition $definition
     */
    public function load($definition): void
    {
        $definition = static::convertToArr($definition);

        $definition->has('maps') && $this->setmapsAttribute($definition['maps']);
        $definition->has('default') && $this->setDefaultAttribute($definition['default']);
        $definition->has('modifier') && $this->setModifierAttribute($definition['modifier']);
        $definition->has('options') && $this->setOptionsAttribute(RuleOptions::parse($definition['options']));
    }
    //endregion

    //region ========== Implements: ArrayAccess ==========
    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, static::DEFAULT);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->{$this->getProxiedAttributeMethodName('get', $offset)}();
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->{$this->getProxiedAttributeMethodName('set', $offset)}($value);
    }

    /**
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        $this->{$this->getProxiedAttributeMethodName('set', $offset)}(Rule::DEFAULT[$offset]);
    }
    //endregion

    //region ========== Implements: JsonSerializable ==========
    /**
     * Returns array for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toDefinition();
    }
    //endregion

    //region ========== Implements: RuleDefinition ==========
    /**
     * Returns the rule definition as an array. This should conform to the rule specification.
     *
     * @return array
     */
    public function toDefinition(): array
    {
        return [
            'maps' => $this->maps,
            'default' => $this->default,
            'modifier' => $this->modifier,
            'options' => $this->options->toDefinition(),
        ];
    }
    //endregion
}
