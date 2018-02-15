<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Tests;

use InvalidArgumentException;
use NorseBlue\Seidr\Rule;
use NorseBlue\Seidr\RuleOptions;
use NorseBlue\Seidr\Rules\DefaultRule;
use PHPUnit\Framework\TestCase;

/**
 * Class RuleTest
 *
 * @package NorseBlue\Seidr\Tests
 */
class RuleTest extends TestCase
{
    //region ========== Constructor ==========
    /**
     * @test   that the rule is created with the default values.
     * @covers NorseBlue\Seidr\Rule::__construct
     */
    public function rule_is_created_with_default_values()
    {
        $rule = new Rule;

        $this->assertEquals(Rule::DEFAULT['maps'], $rule->maps);
        $this->assertEquals(Rule::DEFAULT['default'], $rule->default);
        $this->assertEquals(Rule::DEFAULT['modifier'], $rule->modifier);
        $this->assertEquals(Rule::DEFAULT['options'], $rule->options->toDefinition());
    }

    /**
     * @test   that the rule is created with the given values.
     * @covers NorseBlue\Seidr\Rule::__construct
     */
    public function rule_is_created_with_given_values()
    {
        $modifier = function() { };
        $rule = new Rule('foo', 'bar', $modifier, ['ignore_case' => true]);

        $this->assertEquals('foo', $rule->maps);
        $this->assertEquals('bar', $rule->default);
        $this->assertSame($modifier, $rule->modifier);
        $this->assertEquals([
            'ignore_case' => true,
            'omit' => false,
        ], $rule->options->toDefinition());
    }
    //endregion

    //region ========== Static ==========
    /**
     * @test that the rule is parsed from array.
     * @covers NorseBlue\Seidr\Rule::parse
     */
    public function rule_is_parsed_from_array()
    {
        $modifier = function() { };
        $rule = Rule::parse([
            'maps' => 'foo',
            'default' => 'bar',
            'modifier' => $modifier,
            'options' => [
                'ignore_case' => true,
                'omit' => true,
            ],
        ]);

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('foo', $rule->maps);
        $this->assertEquals('bar', $rule->default);
        $this->assertSame($modifier, $rule->modifier);
        $this->assertEquals([
            'ignore_case' => true,
            'omit' => true,
        ], $rule->options->toDefinition());
    }

    /**
     * @test that the rule is parsed from rule definition.
     * @covers NorseBlue\Seidr\Rule::parse
     */
    public function rule_is_parsed_from_rule_definition()
    {
        $rule = Rule::parse(new DefaultRule);

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('', $rule->maps);
        $this->assertNull($rule->default);
        $this->assertNull($rule->modifier);
        $this->assertEquals([
            'ignore_case' => false,
            'omit' => false,
        ], $rule->options->toDefinition());
    }

    /**
     * @test that an exception is thrown when parsing from unsupported type.
     * @covers NorseBlue\Seidr\Rule::parse
     */
    public function exception_is_thrown_when_parsing_from_unsupported_type()
    {
        $this->expectException(InvalidArgumentException::class);

        $rule = Rule::parse(9);
    }
    //endregion

    //region ========== Accessors ==========
    /**
     * @test that the attribute maps can be retrieved.
     * @covers NorseBlue\Seidr\Rule::getmapsAttribute
     */
    public function attribute_maps_can_be_retrieved()
    {
        $rule = new Rule('foo');

        $this->assertEquals('foo', $rule->maps);
    }

    /**
     * @test that the attribute default can be retrieved.
     * @covers NorseBlue\Seidr\Rule::getDefaultAttribute
     */
    public function attribute_default_can_be_retrieved()
    {
        $rule = new Rule('', 'foo');

        $this->assertEquals('foo', $rule->default);
    }

    /**
     * @test that the attribute modifier can be retrieved.
     * @covers NorseBlue\Seidr\Rule::getModifierAttribute
     */
    public function attribute_modifier_can_be_retrieved()
    {
        $modifier = function() { };
        $rule = new Rule('', null, $modifier);

        $this->assertSame($modifier, $rule->modifier);
    }

    /**
     * @test that the attribute options can be retrieved.
     * @covers NorseBlue\Seidr\Rule::getOptionsAttribute
     */
    public function attribute_options_can_be_retrieved()
    {
        $rule = new Rule('', null, null, [
            'ignore_case' => true,
            'omit' => true,
        ]);

        $this->assertEquals([
            'ignore_case' => true,
            'omit' => true,
        ], $rule->options->toDefinition());
    }
    //endregion

    //region ========== Mutators ==========
    /**
     * @test that the attribute maps can be set.
     * @covers NorseBlue\Seidr\Rule::setmapsAttribute
     */
    public function attribute_maps_can_be_set()
    {
        $rule = new Rule('');

        $rule->maps = 'foo';

        $this->assertEquals('foo', $rule->maps);
    }

    /**
     * @test that the attribute default can be set.
     * @covers NorseBlue\Seidr\Rule::setDefaultAttribute
     */
    public function attribute_default_can_be_set()
    {
        $rule = new Rule('', '');

        $rule->default = 'foo';

        $this->assertEquals('foo', $rule->default);
    }

    /**
     * @test that the attribute modifier can be set.
     * @covers NorseBlue\Seidr\Rule::setModifierAttribute
     */
    public function attribute_modifier_can_be_set()
    {
        $modifier = function() { };
        $rule = new Rule('', null, null);

        $rule->modifier = $modifier;

        $this->assertSame($modifier, $rule->modifier);
    }

    /**
     * @test that the attribute options can be set.
     * @covers NorseBlue\Seidr\Rule::setOptionsAttribute
     */
    public function attribute_options_can_be_set()
    {
        $rule = new Rule('', null, null, [
            'ignore_case' => false,
            'omit' => false,
        ]);

        $rule->options = [
            'ignore_case' => true,
            'omit' => true,
        ];

        $this->assertEquals([
            'ignore_case' => true,
            'omit' => true,
        ], $rule->options->toDefinition());
    }
    //endregion

    //region ========== Methods ==========
    /**
     * @test   that the rule is loaded into the instance from array.
     * @covers NorseBlue\Seidr\Rule::load
     * @covers NorseBlue\Seidr\Rule::convertToArr
     */
    public function rule_is_loaded_into_instance_from_array()
    {
        $modifier = function() { };
        $rule = new Rule('', '', null, [
            'ignore_case' => false,
            'omit' => false,
        ]);

        $rule->load([
            'maps' => 'foo',
            'default' => 'bar',
            'modifier' => $modifier,
            'options' => [
                'ignore_case' => true,
                'omit' => true,
            ],
        ]);

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('foo', $rule->maps);
        $this->assertEquals('bar', $rule->default);
        $this->assertSame($modifier, $rule->modifier);
        $this->assertEquals([
            'ignore_case' => true,
            'omit' => true,
        ], $rule->options->toDefinition());
    }

    /**
     * @test that the rule is loaded into the instance from rule definition.
     * @covers NorseBlue\Seidr\Rule::load
     * @covers NorseBlue\Seidr\Rule::convertToArr
     */
    public function rule_is_loaded_into_instance_from_rule_definition()
    {
        $rule = new Rule('foo', 'bar', function() { }, [
            'ignore_case' => true,
            'omit' => true,
        ]);

        $rule->load(new DefaultRule);

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('', $rule->maps);
        $this->assertNull($rule->default);
        $this->assertNull($rule->modifier);
        $this->assertEquals([
            'ignore_case' => false,
            'omit' => false,
        ], $rule->options->toDefinition());
    }

    /**
     * @test that an exception is thrown when loading from unsupported type.
     * @covers NorseBlue\Seidr\Rule::load
     * @covers NorseBlue\Seidr\Rule::convertToArr
     */
    public function exception_is_thrown_when_loading_from_unsupported_type()
    {
        $this->expectException(InvalidArgumentException::class);
        $rule = new Rule;

        $rule->load(9);
    }
    //endregion

    //region ========== Implements: ArrayAccess ==========
    /**
     * @test that isset with array access returns the correct value.
     * @covers NorseBlue\Seidr\Rule::offsetExists
     */
    public function isset_with_array_access_returns_correct_value()
    {
        $rule = new Rule;

        $this->assertTrue(isset($rule['maps']));
        $this->assertTrue(isset($rule['default']));
        $this->assertTrue(isset($rule['modifier']));
        $this->assertTrue(isset($rule['options']));
        $this->assertFalse(isset($rule['foo']));
    }

    /**
     * @test that an attribute can be retrieved using array access.
     * @covers NorseBlue\Seidr\Rule::offsetGet
     */
    public function attribute_can_be_retrieved_with_array_access()
    {
        $modifier = function() { };
        $rule = new Rule('foo', 'bar', $modifier, [
            'ignore_case' => true,
            'omit' => true,
        ]);

        $this->assertEquals('foo', $rule['maps']);
        $this->assertEquals('bar', $rule['default']);
        $this->assertSame($modifier, $rule['modifier']);
        $this->assertEquals([
            'ignore_case' => true,
            'omit' => true,
        ], $rule['options']->toDefinition());
    }

    /**
     * @test that an attribute can be set using array access.
     * @covers NorseBlue\Seidr\Rule::offsetSet
     */
    public function attribute_can_be_set_with_array_access()
    {
        $rule = new Rule;

        $rule['maps'] = 'foo';

        $this->assertEquals('foo', $rule->maps);
    }

    /**
     * @test that an attribute can be unset (reverts to the default value) using array access.
     * @covers NorseBlue\Seidr\Rule::offsetUnset
     */
    public function attribute_can_be_unset_with_array_access()
    {
        $rule = new Rule('foo');

        unset($rule['maps']);

        $this->assertEquals('', $rule->maps);
    }
    //endregion

    //region ========== Implements: JsonSerializable ==========
    /**
     * @test that the rule can be serialized into JSON.
     * @covers NorseBlue\Seidr\Rule::jsonSerialize
     */
    public function rule_can_be_serialized_into_json()
    {
        $rule = new Rule;

        $json = json_encode($rule);

        $this->assertEquals('{"maps":"","default":null,"modifier":null,"options":{"ignore_case":false,"omit":false}}', $json);
    }
    //endregion

    //region ========== Implements: RuleDefinition ==========
    /**
     * @test that the rule is converted to a definition array.
     * @covers NorseBlue\Seidr\Rule::toDefinition
     */
    public function rule_is_converted_to_definition_array()
    {
        $modifier = function() { };
        $rule = new Rule('foo', 'bar', $modifier, [
            'ignore_case' => true,
            'omit' => true,
        ]);

        $this->assertEquals([
            'maps' => 'foo',
            'default' => 'bar',
            'modifier' => $modifier,
            'options' => [
                'ignore_case' => true,
                'omit' => true,
            ],
        ], $rule->toDefinition());
    }
    //endregion

    //region ========== Other Tests ==========
    /**
     * @test that the rule options object is parsed instead of used directly.
     * @coversNothing
     */
    public function rule_options_object_is_parsed_instead_of_used_directly()
    {
        $rule_options = new RuleOptions;
        $rule = Rule::parse([
            'options' => $rule_options,
        ]);

        $this->assertNotSame($rule_options, $rule->options);
        $this->assertEquals($rule_options, $rule->options);
    }
    //endregion
}
