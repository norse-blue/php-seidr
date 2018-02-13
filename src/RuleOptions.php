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
use NorseBlue\Seidr\Contracts\RuleOptionsDefinition;
use NorseBlue\Sekkr\Arr;

/**
 * Class RuleOptions
 *
 * @package NorseBlue\Seidr
 * @property $ignore_case
 * @property $omit
 */
class RuleOptions implements ArrayAccess, JsonSerializable, RuleOptionsDefinition
{
    use HasProxiedAttributes;

    //region ========== Properties ==========
    /**
     * @var bool The ignore case option.
     */
    protected $ignore_case;

    /**
     * @var bool The omit option.
     */
    protected $omit;
    //endregion

    //region ========== Constructor ==========
    /**
     * RuleOptions constructor.
     *
     * @param bool $ignore_case
     * @param bool $omit
     */
    public function __construct(bool $ignore_case = self::DEFAULT['ignore_case'], bool $omit = self::DEFAULT['omit'])
    {
        $this->ignore_case = $ignore_case;
        $this->omit = $omit;
    }
    //endregion

    //region ========== Static ==========
    /**
     * Converts the given array or RuleOptionsDefinition into an Arr instance.
     *
     * @param array|RuleOptionsDefinition $definition
     *
     * @return Arr
     * @throws InvalidArgumentException
     */
    protected static function convertToArr($definition): Arr
    {
        if ($definition instanceof RuleOptionsDefinition) {
            return ext_arr($definition->toDefinition());
        }

        if (!is_array($definition)) {
            throw new InvalidArgumentException(sprintf(
                'Parameter $options should be of type \'array|RuleOptionsDefinition\', but \'%s\' given.',
                gettype($definition)
            ));
        }

        return ext_arr($definition);
    }

    /**
     * Parses the given definition into a RuleOptions instance.
     *
     * @param array|RuleOptionsDefinition $definition
     *
     * @return RuleOptions
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
     * Attribute $ignore_case accessor.
     *
     * @return bool
     */
    public function getIgnoreCaseAttribute(): bool
    {
        return $this->ignore_case;
    }

    /**
     * Attribute $omit accessor.
     *
     * @return bool
     */
    public function getOmitAttribute(): bool
    {
        return $this->omit;
    }
    //endregion

    //region ========== Mutators ==========
    /**
     * Attribute $ignore_case mutator.
     *
     * @param bool $value
     *
     * @return void
     */
    public function setIgnoreCaseAttribute(bool $value): void
    {
        $this->ignore_case = $value;
    }

    /**
     * Attribute $omit mutator.
     *
     * @param bool $value
     *
     * @return void
     */
    public function setOmitAttribute(bool $value): void
    {
        $this->omit = $value;
    }
    //endregion

    //region ========== Methods ==========
    /**
     * Loads the given definition into this instance.
     *
     * @param array|RuleOptionsDefinition $definition
     */
    public function load($definition): void
    {
        $definition = static::convertToArr($definition);

        $definition->has('ignore_case') && $this->setIgnoreCaseAttribute($definition['ignore_case']);
        $definition->has('omit') && $this->setOmitAttribute($definition['omit']);
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
        return array_key_exists($offset, static::DEFAULT);
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
        return $this->{$this->getProxiedAttributeMethodName('get', $offset)}();
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
        $this->{$this->getProxiedAttributeMethodName('set', $offset)}($value);
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
        $this->{$this->getProxiedAttributeMethodName('set', $offset)}(RuleOptions::DEFAULT[$offset]);
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

    //region ========== Implements: RuleOptionsDefinition ==========
    /**
     * Returns the rule options definition as an array. This should conform to the options specification.
     *
     * @return array
     */
    public function toDefinition(): array
    {
        return [
            'ignore_case' => $this->ignore_case,
            'omit' => $this->omit,
        ];
    }
    //endregion
}
