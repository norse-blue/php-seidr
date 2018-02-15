<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Tests\Rules;

use NorseBlue\Seidr\Rules\MappingTokenizer;
use PHPUnit\Framework\TestCase;

/**
 * Class MappingTokenizerTest
 *
 * @package NorseBlue\Seidr\Tests\Rules
 */
class MappingTokenizerTest extends TestCase
{
    //region ========== Elements ==========
    /**
     * @test
     */
    public function parses_mapping_with_one_element()
    {
        $mapping = 'foo';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo'],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_two_elements()
    {
        $mapping = 'foo.bar';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_ten_elements()
    {
        $mapping = 'foo.bar.baz.qux.corge.grault.garply.waldo.fred.plugh';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'node', 'symbol' => 'plugh',],
        ], $parsed);
    }
    //endregion

    //region ========== Attributes ==========
    /**
     * @test
     */
    public function parses_mapping_with_attribute_on_element_one()
    {
        $mapping = 'foo@bar';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo'],
            ['type' => 'attr', 'symbol' => 'bar'],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_attribute_star_on_element_one()
    {
        $mapping = 'foo@*';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo'],
            ['type' => 'attr', 'symbol' => '*'],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_attribute_on_element_two()
    {
        $mapping = 'foo.bar@*';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'attr', 'symbol' => '*',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_attribute_star_on_element_two()
    {
        $mapping = 'foo.bar@baz';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'attr', 'symbol' => 'baz',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_attribute_on_element_ten()
    {
        $mapping = 'foo.bar.baz.qux.corge.grault.garply.waldo.fred.plugh@thud';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'node', 'symbol' => 'plugh',],
            ['type' => 'attr', 'symbol' => 'thud',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_attribute_star_on_element_ten()
    {
        $mapping = 'foo.bar.baz.qux.corge.grault.garply.waldo.fred.plugh@*';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'node', 'symbol' => 'plugh',],
            ['type' => 'attr', 'symbol' => '*',],
        ], $parsed);
    }
    //endregion

    //region ========== Collections ==========
    /**
     * @test
     */
    public function parses_mapping_with_collection_on_element_one()
    {
        $mapping = 'foo[]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo'],
            ['type' => 'collection', 'symbol' => '*'],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_star_on_element_one()
    {
        $mapping = 'foo[*]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo'],
            ['type' => 'collection', 'symbol' => '*'],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_index_on_element_one()
    {
        $mapping = 'foo[3]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo'],
            ['type' => 'collection', 'symbol' => '3'],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_indexes_on_element_one()
    {
        $mapping = 'foo[1,1,2,3,5,8,13]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo'],
            ['type' => 'collection', 'symbol' => '1,1,2,3,5,8,13'],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_keyword_on_element_one()
    {
        $mapping = 'foo[odd]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo'],
            ['type' => 'collection', 'symbol' => 'odd'],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_on_element_two()
    {
        $mapping = 'foo.bar[]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'collection', 'symbol' => '*',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_star_on_element_two()
    {
        $mapping = 'foo.bar[*]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'collection', 'symbol' => '*',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_index_on_element_two()
    {
        $mapping = 'foo.bar[3]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'collection', 'symbol' => '3',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_indexes_on_element_two()
    {
        $mapping = 'foo.bar[1,1,2,3,5,8,13]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'collection', 'symbol' => '1,1,2,3,5,8,13',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_keyword_on_element_two()
    {
        $mapping = 'foo.bar[odd]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'collection', 'symbol' => 'odd',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_on_element_ten()
    {
        $mapping = 'foo.bar.baz.qux.corge.grault.garply.waldo.fred.plugh[]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'node', 'symbol' => 'plugh',],
            ['type' => 'collection', 'symbol' => '*',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_star_on_element_ten()
    {
        $mapping = 'foo.bar.baz.qux.corge.grault.garply.waldo.fred.plugh[*]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'node', 'symbol' => 'plugh',],
            ['type' => 'collection', 'symbol' => '*',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_index_on_element_ten()
    {
        $mapping = 'foo.bar.baz.qux.corge.grault.garply.waldo.fred.plugh[3]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'node', 'symbol' => 'plugh',],
            ['type' => 'collection', 'symbol' => '3',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_indexes_on_element_ten()
    {
        $mapping = 'foo.bar.baz.qux.corge.grault.garply.waldo.fred.plugh[1,1,2,3,5,8,13]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'node', 'symbol' => 'plugh',],
            ['type' => 'collection', 'symbol' => '1,1,2,3,5,8,13',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_keyword_on_element_ten()
    {
        $mapping = 'foo.bar.baz.qux.corge.grault.garply.waldo.fred.plugh[odd]';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'node', 'symbol' => 'plugh',],
            ['type' => 'collection', 'symbol' => 'odd',],
        ], $parsed);
    }
    //endregion

    //region ========== Collections + Attributes ==========
    /**
     * @test
     */
    public function parses_mapping_with_collection_and_attribute_on_element_one()
    {
        $mapping = 'foo[]@bar';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo'],
            ['type' => 'collection', 'symbol' => '*'],
            ['type' => 'attr', 'symbol' => 'bar'],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_index_and_attribute_star_on_element_one()
    {
        $mapping = 'foo[3]@*';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo'],
            ['type' => 'collection', 'symbol' => '3'],
            ['type' => 'attr', 'symbol' => '*'],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_and_attribute_on_element_two()
    {
        $mapping = 'foo.bar[]@baz';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'collection', 'symbol' => '*',],
            ['type' => 'attr', 'symbol' => 'baz',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_index_and_attribute_star_on_element_two()
    {
        $mapping = 'foo.bar[3]@*';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'collection', 'symbol' => '3',],
            ['type' => 'attr', 'symbol' => '*',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_and_attribute_on_element_ten()
    {
        $mapping = 'foo.bar.baz.qux.corge.grault.garply.waldo.fred.plugh[]@thud';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'node', 'symbol' => 'plugh',],
            ['type' => 'collection', 'symbol' => '*',],
            ['type' => 'attr', 'symbol' => 'thud',],
        ], $parsed);
    }

    /**
     * @test
     */
    public function parses_mapping_with_collection_index_and_attribute_star_on_element_ten()
    {
        $mapping = 'foo.bar.baz.qux.corge.grault.garply.waldo.fred.plugh[3]@*';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'node', 'symbol' => 'plugh',],
            ['type' => 'collection', 'symbol' => '3',],
            ['type' => 'attr', 'symbol' => '*',],
        ], $parsed);
    }
    //endregion

    //region ========== Multiple Collections ==========
    /**
     * @test
     */
    public function parses_mapping_with_multiple_collections()
    {
        $mapping = 'foo.bar.baz[].qux.corge.grault[3].garply.waldo.fred[odd].plugh@thud';

        $parsed = MappingTokenizer::tokenize($mapping);

        $this->assertEquals([
            ['type' => 'node', 'symbol' => 'foo',],
            ['type' => 'node', 'symbol' => 'bar',],
            ['type' => 'node', 'symbol' => 'baz',],
            ['type' => 'collection', 'symbol' => '*',],
            ['type' => 'node', 'symbol' => 'qux',],
            ['type' => 'node', 'symbol' => 'corge',],
            ['type' => 'node', 'symbol' => 'grault',],
            ['type' => 'collection', 'symbol' => '3',],
            ['type' => 'node', 'symbol' => 'garply',],
            ['type' => 'node', 'symbol' => 'waldo',],
            ['type' => 'node', 'symbol' => 'fred',],
            ['type' => 'collection', 'symbol' => 'odd',],
            ['type' => 'node', 'symbol' => 'plugh',],
            ['type' => 'attr', 'symbol' => 'thud',],
        ], $parsed);
    }
    //endregion
}
