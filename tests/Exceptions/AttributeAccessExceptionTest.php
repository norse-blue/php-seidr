<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Tests;

use NorseBlue\Seidr\Exceptions\AttributeAccessException;
use PHPUnit\Framework\TestCase;

class AttributeAccessExceptionTest extends TestCase
{
    /**
     * @test that the exception contains the given values.
     */
    public function exception_contains_given_value()
    {
        $exception = new AttributeAccessException('Exception message.', 'foo');

        $this->assertEquals('foo', $exception->getAttribute());
        $this->assertContains(sprintf("%s: Exception message.", AttributeAccessException::class), (string)$exception);
        $this->assertContains(sprintf("Attribute: %s", 'foo'), (string)$exception);
    }
}
