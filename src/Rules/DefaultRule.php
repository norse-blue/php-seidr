<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Rules;

use NorseBlue\Seidr\Contracts\RuleDefinition;

/**
 * Class DefaultRule
 *
 * @package NorseBlue\Seidr\Rules
 * @codeCoverageIgnore
 */
class DefaultRule implements RuleDefinition
{
    //region ========== Methods ==========
    /**
     * Returns the rule definition as an array. This should conform to the rule specification.
     *
     * @return array
     */
    public function toDefinition(): array
    {
        return static::DEFAULT;
    }
    //endregion
}
