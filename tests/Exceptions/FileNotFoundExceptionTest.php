<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Tests;

use NorseBlue\Seidr\Exceptions\FileNotFoundException;
use PHPUnit\Framework\TestCase;

class FileNotFoundExceptionTest extends TestCase
{
    /**
     * @test that the exception contains the given values.
     */
    public function exception_contains_given_value()
    {
        $exception = new FileNotFoundException('Exception message.', 'foo');

        $this->assertEquals('foo', $exception->getFilePath());
        $this->assertContains(sprintf("%s: Exception message.", FileNotFoundException::class), (string)$exception);
        $this->assertContains(sprintf("File path: %s", 'foo'), (string)$exception);
    }
}
