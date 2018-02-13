<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Contracts;

/**
 * Interface RuleOptionsDefinition
 *
 * @package NorseBlue\Seidr
 */
interface RuleOptionsDefinition
{
    //region ========== Constants ==========
    /**
     * @const array The rule option's default definition.
     */
    public const DEFAULT = [
        'ignore_case' => false,
        'omit' => false,
    ];
    //endregion

    //region ========== Methods ==========
    /**
     * Returns the rule options definition as an array. This should conform to the options specification.
     *
     * @return array
     */
    public function toDefinition(): array;
    //endregion
}
