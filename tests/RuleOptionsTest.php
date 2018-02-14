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
use NorseBlue\Seidr\RuleOptions;
use NorseBlue\Seidr\RuleOptions\DefaultRuleOptions;
use PHPUnit\Framework\TestCase;

/**
 * Class RuleOptionsTest
 *
 * @package NorseBlue\Seidr\Tests
 */
class RuleOptionsTest extends TestCase
{
    //region ========== Constructor ==========
    /**
     * @test   that the rule options is created with the default values.
     * @covers NorseBlue\Seidr\RuleOptions::__construct
     */
    public function rule_options_is_created_with_default_values()
    {
        $options = new RuleOptions;

        $this->assertEquals(RuleOptions::DEFAULT['ignore_case'], $options->ignore_case);
        $this->assertEquals(RuleOptions::DEFAULT['omit'], $options->omit);
    }

    /**
     * @test   that the rule options is created with the given values.
     * @covers NorseBlue\Seidr\RuleOptions::__construct
     */
    public function rule_options_is_created_with_given_values()
    {
        $options = new RuleOptions(true, false);

        $this->assertTrue($options->ignore_case);
        $this->assertFalse($options->omit);
    }
    //endregion

    //region ========== Static ==========
    /**
     * @test that the options are parsed from array.
     * @covers NorseBlue\Seidr\RuleOptions::parse
     */
    public function options_are_parsed_from_array()
    {
        $options = RuleOptions::parse([
            'ignore_case' => true,
            'omit' => true,
        ]);

        $this->assertInstanceOf(RuleOptions::class, $options);
        $this->assertTrue($options->ignore_case);
        $this->assertTrue($options->omit);
    }

    /**
     * @test that the options are parsed from rule options definition.
     * @covers NorseBlue\Seidr\RuleOptions::parse
     */
    public function options_are_parsed_from_rule_options_definition()
    {
        $options = RuleOptions::parse(new DefaultRuleOptions);

        $this->assertInstanceOf(RuleOptions::class, $options);
        $this->assertFalse($options->ignore_case);
        $this->assertFalse($options->omit);
    }

    /**
     * @test that an exception is thrown when parsing from unsupported type.
     * @covers NorseBlue\Seidr\RuleOptions::parse
     */
    public function exception_is_thrown_when_parsing_from_unsupported_type()
    {
        $this->expectException(InvalidArgumentException::class);

        $options = RuleOptions::parse(9);
    }
    //endregion

    //region ========== Accessors ==========
    /**
     * @test that the attribute ignore_case can be retrieved.
     * @covers NorseBlue\Seidr\RuleOptions::getIgnoreCaseAttribute
     */
    public function attribute_ignore_case_can_be_retrieved()
    {
        $options = new RuleOptions(true);

        $this->assertTrue($options->ignore_case);
    }

    /**
     * @test that the attribute omit can be retrieved.
     * @covers NorseBlue\Seidr\RuleOptions::getOmitAttribute
     */
    public function attribute_omit_can_be_retrieved()
    {
        $options = new RuleOptions(false, true);

        $this->assertTrue($options->omit);
    }
    //endregion

    //region ========== Mutators ==========
    /**
     * @test that the attribute ignore_case can be set.
     * @covers NorseBlue\Seidr\RuleOptions::setIgnoreCaseAttribute
     */
    public function attribute_ignore_case_can_be_set()
    {
        $options = new RuleOptions(false);

        $options->ignore_case = true;

        $this->assertTrue($options->ignore_case);
    }

    /**
     * @test that the attribute omit can be set.
     * @covers NorseBlue\Seidr\RuleOptions::setOmitAttribute
     */
    public function attribute_omit_can_be_set()
    {
        $options = new RuleOptions(false, false);

        $options->omit = true;

        $this->assertTrue($options->omit);
    }
    //endregion

    //region ========== Methods ==========
    /**
     * @test   that the rule options are loaded into the instance from array.
     * @covers NorseBlue\Seidr\RuleOptions::load
     * @covers NorseBlue\Seidr\RuleOptions::convertToArr
     */
    public function options_are_loaded_into_instance_from_array()
    {
        $options = new RuleOptions;

        $options->load([
            'ignore_case' => true,
            'omit' => true,
        ]);

        $this->assertTrue($options->ignore_case);
        $this->assertTrue($options->omit);
    }

    /**
     * @test that the options are loaded into the instance from rule options definition.
     * @covers NorseBlue\Seidr\RuleOptions::load
     * @covers NorseBlue\Seidr\RuleOptions::convertToArr
     */
    public function options_are_loaded_into_instance_from_rule_options_definition()
    {
        $options = new RuleOptions(true, true);

        $options->load(new DefaultRuleOptions);

        $this->assertInstanceOf(RuleOptions::class, $options);
        $this->assertFalse($options->ignore_case);
        $this->assertFalse($options->omit);
    }

    /**
     * @test that an exception is thrown when loading from unsupported type.
     * @covers NorseBlue\Seidr\RuleOptions::load
     * @covers NorseBlue\Seidr\RuleOptions::convertToArr
     */
    public function exception_is_thrown_when_loading_from_unsupported_type()
    {
        $this->expectException(InvalidArgumentException::class);
        $options = new RuleOptions;

        $options->load(9);
    }
    //endregion

    //region ========== Implements: ArrayAccess ==========
    /**
     * @test that isset with array access returns the correct value.
     * @covers NorseBlue\Seidr\RuleOptions::offsetExists
     */
    public function isset_with_array_access_returns_correct_value()
    {
        $options = new RuleOptions;

        $this->assertTrue(isset($options['ignore_case']));
        $this->assertTrue(isset($options['omit']));
        $this->assertFalse(isset($options['foo']));
    }

    /**
     * @test that an attribute can be retrieved using array access.
     * @covers NorseBlue\Seidr\RuleOptions::offsetGet
     */
    public function attribute_can_be_retrieved_with_array_access()
    {
        $options = new RuleOptions(true, true);

        $this->assertTrue($options['ignore_case']);
        $this->assertTrue($options['omit']);
    }

    /**
     * @test that an attribute can be set using array access.
     * @covers NorseBlue\Seidr\RuleOptions::offsetSet
     */
    public function attribute_can_be_set_with_array_access()
    {
        $options = new RuleOptions;

        $options['ignore_case'] = true;

        $this->assertTrue($options->ignore_case);
    }

    /**
     * @test that an attribute can be unset (reverts to the default value) using array access.
     * @covers NorseBlue\Seidr\RuleOptions::offsetUnset
     */
    public function attribute_can_be_unset_with_array_access()
    {
        $options = new RuleOptions(true);

        unset($options['ignore_case']);

        $this->assertFalse($options->ignore_case);
    }
    //endregion

    //region ========== Implements: JsonSerializable ==========
    /**
     * @test that the options can be serialized into JSON.
     * @covers NorseBlue\Seidr\RuleOptions::jsonSerialize
     */
    public function options_can_be_serialized_into_json()
    {
        $options = new RuleOptions;

        $json = json_encode($options);

        $this->assertEquals('{"ignore_case":false,"omit":false}', $json);
    }
    //endregion

    //region ========== Implements: RuleOptionsDefinition ==========
    /**
     * @test that the rule options are converted to a definition array.
     * @covers NorseBlue\Seidr\RuleOptions::toDefinition
     */
    public function rule_options_are_converted_to_definition_array()
    {
        $options = new RuleOptions(true, true);

        $this->assertEquals([
            'ignore_case' => true,
            'omit' => true,
        ], $options->toDefinition());
    }
    //endregion
}
