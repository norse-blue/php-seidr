<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Contracts;

use NorseBlue\Seidr\Rule;

/**
 * Interface RuleSetDefinition
 *
 * @package NorseBlue\Seidr
 */
interface RuleSetDefinition
{
    //region ========== Constants ==========
    /**
     * @const array The rule set's default definition.
     */
    public const DEFAULT = [];
    //endregion

    //region ========== Methods ==========
    /**
     * Returns the rule contained in the rule set specified by the given key or null if not found.
     *
     * @param string $key
     *
     * @return Rule|null
     */
    public function rule(string $key): ?Rule;

    /**
     * Returns the rule set definition as an array. This should conform to the rule set specification.
     *
     * @return array
     */
    public function toDefinition(): array;
    //endregion
}
