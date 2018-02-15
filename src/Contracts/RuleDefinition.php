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
 * Interface RuleDefinition
 *
 * @package NorseBlue\Seidr
 */
interface RuleDefinition
{
    //region ========== Constants ==========
    /**
     * @const array The rule's default definition.
     */
    public const DEFAULT = [
        'maps' => '',
        'default' => null,
        'modifier' => null,
        'options' => RuleOptionsDefinition::DEFAULT,
    ];
    //endregion

    //region ========== Methods ==========
    /**
     * Returns the rule definition as an array. This should conform to the rule specification.
     *
     * @return array
     */
    public function toDefinition(): array;
    //endregion
}
