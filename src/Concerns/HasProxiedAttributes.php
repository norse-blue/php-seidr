<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Concerns;

use NorseBlue\Seidr\Exceptions\AttributeAccessException;
use ReflectionClass;
use ReflectionException;

/**
 * Trait HasProxiedAttributes
 *
 * @package NorseBlue\Seidr\Concerns
 */
trait HasProxiedAttributes
{
    //region ========== Properties ==========
    /**
     * @var ReflectionClass The reflection class to be used to retrieve property visibility.
     */
    protected static $reflection;
    //endregion

    //region ========== Methods ==========
    /**
     * Gets the attribute's value if it has a proxied attribute accesor method or if it is public.
     *
     * @param  string $attribute
     *
     * @return mixed
     */
    public function __get(string $attribute)
    {
        $proxiedMethod = $this->getProxiedAttributeMethodName('get', $attribute);
        if (method_exists($this, $proxiedMethod)) {
            return $this->{$proxiedMethod}();
        }

        $this->throwAttributeAccessException($attribute);
    }

    /**
     * Sets the attribute's value if it has a proxied attribute mutator method or if it is public.
     *
     * @param string $attribute
     * @param mixed  $value
     */
    public function __set(string $attribute, $value): void
    {
        $proxiedMethod = $this->getProxiedAttributeMethodName('set', $attribute);
        if (method_exists($this, $proxiedMethod)) {
            $this->{$proxiedMethod}($value);
            return;
        }

        $this->throwAttributeAccessException($attribute);
    }

    /**
     * Gets the proxied attribute accessor or mutator method name.
     *
     * @param string $accessor_mutator
     * @param string $attribute
     *
     * @return string
     */
    protected function getProxiedAttributeMethodName(string $accessor_mutator, string $attribute): string
    {
        return sprintf(
            '%s%sAttribute',
            strtolower($accessor_mutator),
            str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $attribute)))
        );
    }

    /**
     * Gets the class reflection object.
     *
     * @return mixed
     * @throws ReflectionException
     */
    protected function getReflection()
    {
        if (!isset(static::$reflection)) {
            static::$reflection = new ReflectionClass(static::class);
        }

        return static::$reflection;
    }

    /**
     * Throws the attribute access exception.
     *
     * @param string $attribute
     *
     * @throws AttributeAccessException
     */
    protected function throwAttributeAccessException(string $attribute)
    {
        try {
            $reflectionProperty = $this->getReflection()->getProperty($attribute);
            throw new AttributeAccessException(sprintf(
                'Cannot access %s property: %s::$%s',
                (($reflectionProperty->isProtected()) ? 'protected' : 'private'),
                static::class,
                $attribute
            ), $attribute);
        } catch (ReflectionException $e) {
        }
        throw new AttributeAccessException(
            sprintf('Undefined property: %s::$%s', static::class, $attribute),
            $attribute
        );
    }
    //endregion
}
