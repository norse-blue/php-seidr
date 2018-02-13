<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Tests\Concerns;

use NorseBlue\Seidr\Concerns\HasProxiedAttributes;
use NorseBlue\Seidr\Exceptions\AttributeAccessException;
use PHPUnit\Framework\TestCase;

class ProxiedAttributesObject
{
    use HasProxiedAttributes;

    private $private_property = 'Default private value';
    protected $protected_property = 'Default protected value';
    public $public_property = 'Default public value';

    protected function getPrivateAttribute()
    {
        return $this->private_property;
    }

    protected function getProtectedAttribute()
    {
        return $this->protected_property;
    }

    protected function getPublicAttribute()
    {
        return $this->public_property;
    }

    protected function setPrivateAttribute($value)
    {
        $this->private_property = $value;
    }

    protected function setProtectedAttribute($value)
    {
        $this->protected_property = $value;
    }

    protected function setPublicAttribute($value)
    {
        $this->public_property = $value;
    }
}

class HasProxiedAttributesTest extends TestCase
{
    /**
     * @test that the private property is accessible through accessor and mutator.
     */
    public function private_property_is_accessible_through_accessor_and_mutator()
    {
        $obj = new ProxiedAttributesObject;

        $obj->private = 'New private value';

        $this->assertEquals('New private value', $obj->private);
    }

    /**
     * @test that the protected property is accessible through accessor and mutator.
     */
    public function protected_property_is_accessible_through_accessor_and_mutator()
    {
        $obj = new ProxiedAttributesObject;

        $obj->protected = 'New protected value';

        $this->assertEquals('New protected value', $obj->protected);
    }

    /**
     * @test that the public property is accessible through accessor and mutator.
     */
    public function public_property_is_accessible_through_accessor_and_mutator()
    {
        $obj = new ProxiedAttributesObject;

        $obj->public = 'New public value';

        $this->assertEquals('New public value', $obj->public);
    }

    /**
     * @test that the private property is not directly accessible.
     */
    public function private_property_is_not_directly_accessible()
    {
        $this->expectException(AttributeAccessException::class);
        $obj = new ProxiedAttributesObject;

        $value = $obj->private_property;
    }

    /**
     * @test that the private property is not directly mutable.
     */
    public function private_property_is_not_directly_mutable()
    {
        $this->expectException(AttributeAccessException::class);
        $obj = new ProxiedAttributesObject;

        $obj->private_property = 'New private value';
    }

    /**
     * @test that the protected property is not directly accessible.
     */
    public function protected_property_is_not_directly_accessible()
    {
        $this->expectException(AttributeAccessException::class);
        $obj = new ProxiedAttributesObject;

        $value = $obj->protected_property;
    }

    /**
     * @test that the protected property is not directly mutable.
     */
    public function protected_property_is_not_directly_mutable()
    {
        $this->expectException(AttributeAccessException::class);
        $obj = new ProxiedAttributesObject;

        $obj->protected_property = 'New protected value';
    }

    /**
     * @test that the public property is directly accessible.
     */
    public function public_property_is_directly_accessible()
    {
        $obj = new ProxiedAttributesObject;

        $value = $obj->public_property;

        $this->assertEquals('Default public value', $value);
    }

    /**
     * @test that the public property is directly mutable.
     */
    public function public_property_is_directly_mutable()
    {
        $obj = new ProxiedAttributesObject;

        $obj->public_property = 'New public value';

        $this->assertEquals('New public value', $obj->public_property);
    }

    /**
     * @test that an undefined property is not accessible.
     */
    public function undefined_property_is_not_accessible()
    {
        $this->expectException(AttributeAccessException::class);
        $obj = new ProxiedAttributesObject;

        $value = $obj->undefined;
    }
}
