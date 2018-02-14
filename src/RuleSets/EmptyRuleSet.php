<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\RuleSets;

use NorseBlue\Seidr\Contracts\RuleSetDefinition;
use NorseBlue\Seidr\Rule;

/**
 * Class EmptyRuleSet
 *
 * @package NorseBlue\Seidr\RuleSets
 * @codeCoverageIgnore
 */
class EmptyRuleSet implements RuleSetDefinition
{
    //region ========== Methods ==========
    /**
     * Returns the rule contained in the rule set specified by the given key or null if not found.
     *
     * @param string $key
     *
     * @return Rule|null
     */
    public function rule(string $key): ?Rule
    {
        return null;
    }

    /**
     * Returns the rule set definition as an array. This should conform to the specification.
     *
     * @return array
     */
    public function toDefinition(): array
    {
        return [];
    }
    //endregion
}
