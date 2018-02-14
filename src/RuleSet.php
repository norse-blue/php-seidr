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
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use NorseBlue\Seidr\Concerns\HasProxiedAttributes;
use NorseBlue\Seidr\Contracts\RuleSetDefinition;
use NorseBlue\Sekkr\Arr;

/**
 * Class RuleSet
 *
 * @package NorseBlue\Seidr
 * @property Arr[Rule] $rules
 */
class RuleSet implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable, RuleSetDefinition
{
    use HasProxiedAttributes;

    //region ========== Properties ==========
    /**
     * @var Arr[Rule] The rules that this RuleSet holds.
     */
    protected $rules;
    //endregion

    //region ========== Constructor ==========
    /**
     * RuleSet constructor.
     *
     * @param array|RuleSetDefinition $rules
     */
    public function __construct($rules = [])
    {
        $this->load($rules);
    }
    //endregion

    //region ========== Static ==========
    /**
     * Converts the given array or RuleSetDefinition into an Arr instance.
     *
     * @param array|RuleSetDefinition $definition
     *
     * @return Arr
     * @throws InvalidArgumentException
     */
    protected static function convertToArr($definition): Arr
    {
        if ($definition instanceof RuleSetDefinition) {
            return ext_arr($definition->toDefinition());
        }

        if (!is_array($definition)) {
            throw new InvalidArgumentException(sprintf(
                'Parameter $definition should be of type \'array|RuleSetDefinition\', but \'%s\' given.',
                gettype($definition)
            ));
        }

        return ext_arr($definition);
    }

    /**
     * Parses the given definition into a RuleSet instance.
     *
     * @param array|RuleSetDefinition $definition
     *
     * @return RuleSet
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
     * Attribute $rules accessor.
     *
     * @return Arr[Rule]
     */
    protected function getRulesAttribute(): Arr
    {
        return $this->rules;
    }
    //endregion

    //region ========== Mutators ==========
    /**
     * Attribute $rules mutator.
     *
     * @param array|RuleSetDefinition $value
     */
    protected function setRulesAttribute($value): void
    {
        $this->rules = ext_arr([]);
        foreach ($value as $key => $rule) {
            $this->rules[$key] = Rule::parse($rule);
        }
    }
    //endregion

    //region ========== Methods ==========
    /**
     * Loads the given definition into this instance.
     *
     * @param array|RuleSetDefinition $definition
     */
    public function load($definition): void
    {
        $definition = static::convertToArr($definition);

        $this->setRulesAttribute($definition->all());
    }
    //endregion

    //region ========== Implements: ArrayAccess ==========
    /**
     * Checks if the given offset exists.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->rules->all());
    }

    /**
     * Returns the value of the given offset.
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->rules[$offset];
    }

    /**
     * Sets the given offset to value.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->rules[$offset] = $value;
    }

    /**
     * Unsets the given offset.
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->rules[$offset]);
    }
    //endregion

    //region ========== Implements: Countable ==========
    /**
     * Returns the number of items.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->rules);
    }
    //endregion

    //region ========== Implements IteratorAggregate ==========
    /**
     * Get an iterator for the items.
     *
     * @return iterable
     */
    public function getIterator(): iterable
    {
        return new ArrayIterator($this->rules->getIterator());
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

    //region ========== Implements: RuleSetDefinition ==========
    /**
     * Returns the rule contained in the rule set specified by the given key or null if not found.
     *
     * @param string $key
     *
     * @return Rule|null
     */
    public function rule(string $key): ?Rule
    {
        return $this->rules->get($key);
    }

    /**
     * Returns the rule set definition as an array. This should conform to the rule set specification.
     *
     * @return array
     */
    public function toDefinition(): array
    {
        $definition = [];
        foreach ($this->rules as $key => $rule) {
            $definition[$key] = $rule->toDefinition();
        }

        return $definition;
    }
    //endregion
}
