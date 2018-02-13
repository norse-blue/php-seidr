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
use NorseBlue\Seidr\RuleSet;
use NorseBlue\Seidr\RuleSets\EmptyRuleSet;
use NorseBlue\Sekkr\Arr;
use PHPUnit\Framework\TestCase;

/**
 * Class RuleSetTest
 *
 * @package NorseBlue\Seidr\Tests
 */
class RuleSetTest extends TestCase
{
    //region ========== Constructor ==========
    /**
     * @test that an empty rule set is created.
     * @covers NorseBlue\Seidr\RuleSet::__construct
     */
    public function empty_rule_set_created()
    {
        $rule_set = new RuleSet;

        $this->assertEquals(0, count($rule_set));
        $this->assertEquals([], $rule_set->rules->all());
    }

    /**
     * @test that a rule set is created with the given rules.
     * @covers NorseBlue\Seidr\RuleSet::__construct
     */
    public function rule_set_created_with_given_rules()
    {
        $modifier = function($value) {
            return $value;
        };
        $rule_set = new RuleSet([
            'simple_key' => [
                'seek' => 'path.to.seek',
                'default' => 'not found',
                'options' => [
                    'ignore-case' => true,
                ],
            ],
            'composite.key' => [
                'seek' => 'other.path.to.seek',
                'modifier' => $modifier,
                'options' => [
                    'omit' => true,
                ],
            ],
        ]);

        $this->assertEquals(2, count($rule_set));
        $this->assertEquals([
            'simple_key',
            'composite.key'
        ], array_keys($rule_set->rules->all()));
        $this->assertTrue(isset($rule_set['simple_key']));
        $this->assertTrue(isset($rule_set['composite.key']));
        foreach($rule_set as $key=>$rule) {
            $this->assertInstanceOf(Rule::class, $rule);
            switch($key) {
                case 'simple_key':
                    $this->assertEquals('path.to.seek', $rule->seek);
                    $this->assertEquals('not found', $rule->default);
                    $this->assertNull($rule->modifier);
                    $this->assertTrue($rule->options['ignore-case']);
                    $this->assertFalse($rule->options['omit']);
                    $this->assertSame($rule, $rule_set[$key]);
                    break;
                case 'composite.key':
                    $this->assertEquals('other.path.to.seek', $rule->seek);
                    $this->assertNull($rule->default);
                    $this->assertEquals($modifier, $rule->modifier);
                    $this->assertFalse($rule->options['ignore-case']);
                    $this->assertTrue($rule->options['omit']);
                    $this->assertSame($rule, $rule_set[$key]);
                    break;
            }
        }
    }
    //endregion

    //region ========== Static ==========
    /**
     * @test that the rule set is parsed from array.
     * @covers NorseBlue\Seidr\RuleSet::parse
     */
    public function rule_set_is_parsed_from_array()
    {
        $modifier = function() { };
        $rule_set = RuleSet::parse([
            [
                'seek' => 'foo',
                'default' => 'bar',
                'modifier' => $modifier,
                'options' => [
                    'ignore_case' => true,
                    'omit' => false,
                ],
            ],
            [
                'seek' => 'baz.qux',
                'default' => 'corge',
                'modifier' => $modifier,
                'options' => [
                    'ignore_case' => false,
                    'omit' => true,
                ],
            ],
        ]);

        $this->assertInstanceOf(RuleSet::class, $rule_set);
        $this->assertEquals([
            [
                'seek' => 'foo',
                'default' => 'bar',
                'modifier' => $modifier,
                'options' => [
                    'ignore_case' => true,
                    'omit' => false,
                ],
            ],
            [
                'seek' => 'baz.qux',
                'default' => 'corge',
                'modifier' => $modifier,
                'options' => [
                    'ignore_case' => false,
                    'omit' => true,
                ],
            ],
        ], $rule_set->toDefinition());
    }

    /**
     * @test that the rule set is parsed from rule set definition.
     * @covers NorseBlue\Seidr\RuleSet::parse
     */
    public function rule_set_is_parsed_from_rule_set_definition()
    {
        $rule_set = RuleSet::parse(new EmptyRuleSet);

        $this->assertInstanceOf(RuleSet::class, $rule_set);
        $this->assertEquals([], $rule_set->toDefinition());
    }

    /**
     * @test that an exception is thrown when parsing from unsupported type.
     * @covers NorseBlue\Seidr\RuleSet::parse
     */
    public function exception_is_thrown_when_parsing_from_unsupported_type()
    {
        $this->expectException(InvalidArgumentException::class);

        $rule = RuleSet::parse(9);
    }
    //endregion

    //region ========== Accessors ==========
    /**
     * @test that the attribute rules can be retrieved.
     * @covers NorseBlue\Seidr\RuleSet::getRulesAttribute
     */
    public function attribute_seek_can_be_retrieved()
    {
        $rule_set = new RuleSet;

        $this->assertInstanceOf(Arr::class, $rule_set->rules);
        $this->assertEquals([], $rule_set->rules->all());
    }
    //endregion

    //region ========== Mutators ==========
    /**
     * @test that the attribute rules can be set.
     * @covers NorseBlue\Seidr\RuleSet::setRulesAttribute
     */
    public function attribute_seek_can_be_set()
    {
        $rule_set = new RuleSet([
            'simple_key' => [
                'seek' => 'path.to.seek',
                'default' => 'not found',
                'options' => [
                    'ignore-case' => true,
                ],
            ],
        ]);

        $rule_set->rules = [];

        $this->assertInstanceOf(Arr::class, $rule_set->rules);
        $this->assertEquals([], $rule_set->rules->all());
    }
    //endregion

    //region ========== Methods ==========
    /**
     * @test   that the rule set is loaded into the instance from array.
     * @covers NorseBlue\Seidr\RuleSet::load
     * @covers NorseBlue\Seidr\RuleSet::convertToArr
     */
    public function rule_set_is_loaded_into_instance_from_array()
    {
        $modifier = function() { };
        $rule_set = new RuleSet;

        $rule_set->load([
            [
                'seek' => 'foo',
                'default' => 'bar',
                'modifier' => $modifier,
                'options' => [
                    'ignore_case' => true,
                    'omit' => false,
                ],
            ],
            [
                'seek' => 'baz.qux',
                'default' => 'corge',
                'modifier' => $modifier,
                'options' => [
                    'ignore_case' => false,
                    'omit' => true,
                ],
            ],
        ]);

        $this->assertInstanceOf(RuleSet::class, $rule_set);
        $this->assertEquals([
            [
                'seek' => 'foo',
                'default' => 'bar',
                'modifier' => $modifier,
                'options' => [
                    'ignore_case' => true,
                    'omit' => false,
                ],
            ],
            [
                'seek' => 'baz.qux',
                'default' => 'corge',
                'modifier' => $modifier,
                'options' => [
                    'ignore_case' => false,
                    'omit' => true,
                ],
            ],
        ], $rule_set->toDefinition());
    }

    /**
     * @test that the rule set is loaded into the instance from rule set definition.
     * @covers NorseBlue\Seidr\RuleSet::load
     * @covers NorseBlue\Seidr\RuleSet::convertToArr
     */
    public function rule_set_is_loaded_into_instance_from_rule_set_definition()
    {
        $rule_set = new RuleSet;

        $rule_set->load(new EmptyRuleSet);

        $this->assertInstanceOf(RuleSet::class, $rule_set);
        $this->assertEquals([], $rule_set->toDefinition());
    }

    /**
     * @test that an exception is thrown when loading from unsupported type.
     * @covers NorseBlue\Seidr\RuleSet::load
     * @covers NorseBlue\Seidr\RuleSet::convertToArr
     */
    public function exception_is_thrown_when_loading_from_unsupported_type()
    {
        $this->expectException(InvalidArgumentException::class);
        $rule_set = new RuleSet;

        $rule_set->load(9);
    }
    //endregion

    //region ========== Implements: ArrayAccess ==========
    /**
     * @test that isset with array access returns the correct value.
     */
    public function isset_with_array_access_returns_correct_value()
    {
        $rule_set = new RuleSet([
            'empty.rule' => new Rule,
        ]);

        $this->assertTrue(isset($rule_set['empty.rule']));
    }

    /**
     * @test that an attribute can be retrieved using array access.
     */
    public function attribute_can_be_retrieved_with_array_access()
    {
        $rule = new Rule;
        $rule_set = new RuleSet([
            'empty.rule' => $rule,
        ]);

        $this->assertEquals($rule, $rule_set['empty.rule']);
    }

    /**
     * @test that an attribute can be set using array access.
     */
    public function attribute_can_be_set_with_array_access()
    {
        $rule = new Rule;
        $rule_set = new RuleSet;

        $rule_set['empty.rule'] = $rule;

        $this->assertEquals($rule, $rule_set->rules['empty.rule']);
    }

    /**
     * @test that an attribute can be unset (reverts to the default value) using array access.
     */
    public function attribute_can_be_unset_with_array_access()
    {
        $rule_set = new RuleSet([
            'empty.rule' => new Rule,
        ]);

        unset($rule_set['empty.rule']);

        $this->assertNotContains('empty.rule', $rule_set->rules);
    }
    //endregion
}
