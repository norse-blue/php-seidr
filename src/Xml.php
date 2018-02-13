<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr;

use InvalidArgumentException;
use NorseBlue\Seidr\Concerns\HasProxiedAttributes;
use NorseBlue\Seidr\Contracts\RuleSetDefinition;
use NorseBlue\Seidr\Exceptions\FileNotFoundException;
use SimpleXMLElement;

/**
 * Class Xml
 *
 * @package NorseBlue\Seidr
 * @property RuleSet $rule_set
 */
class Xml
{
    use HasProxiedAttributes;

    //region ========== Properties ==========
    /**
     * @var RuleSet The base rule set to use to extract XML data.
     */
    protected $rule_set;

    /**
     * @var SimpleXMLElement The parsed XML contents.
     */
    protected $xml;
    //endregion

    //region ========== Static ==========
    /**
     * Creates an Xml instance with the given $ruleSet and reads the $xmlContents.
     *
     * @param string                 $xml_contents
     * @param RuleSetDefinition|null $rule_set
     *
     * @return Xml
     */
    public static function load(string $xml_contents, ?RuleSetDefinition $rule_set = null): self
    {
        return (new self($rule_set))->read($xml_contents);
    }

    /**
     * Creates an Xml instance with the given $ruleSet and reads the contents of the file specified by $xmlPath.
     *
     * @param string                 $xml_path
     * @param RuleSetDefinition|null $rule_set
     *
     * @return Xml
     */
    public static function loadFile(string $xml_path, ?RuleSetDefinition $rule_set = null): self
    {
        return (new self($rule_set))->readFile($xml_path);
    }
    //endregion

    //region ========== Constructor ==========
    /**
     * Xml constructor.
     *
     * @param  $rule_set
     */
    public function __construct($rule_set = null)
    {
        $this->setRuleSetAttribute($rule_set);
    }
    //endregion

    //region ========== Accessors ==========
    /**
     * Attribute $ruleSet accessor.
     *
     * @return RuleSet
     */
    public function getRuleSetAttribute(): RuleSet
    {
        return $this->rule_set;
    }
    //endregion

    //region ========== Mutators ==========
    /**
     * Attribute $ruleSet mutator.
     *
     * @param array|RuleSet|RuleSetDefinition|null $value
     */
    public function setRuleSetAttribute($value): void
    {
        if (is_null($value)) {
            $value = [];
        }

        if ($value instanceof RuleSet || $value instanceof RuleSetDefinition) {
            $value = $value->toDefinition();
        }

        if (!is_array($value)) {
            throw new InvalidArgumentException(sprintf(
                'The $ruleSet should be of type \'array|RuleSet|RuleSetDefinition\', but \'%s\' given.',
                gettype($value)
            ));
        }

        $this->rule_set = $this->parseRuleSet($value);
    }
    //endregion

    //region ========== Methods ==========
    /**
     * Extracts the XML data defined in the object's rule set merged with the given $ruleSet.
     * If the $globalOptions is set, then those existing options will be used for all rules.
     * If an option does not exist in the $globalOptions, the defined option for each rule will be used
     * or the default in case it does not exist.
     *
     * @param array|RuleSetDefinition|null     $rule_set
     * @param array|RuleOptionsDefinition|null $global_options
     *
     * @return array
     */
    public function extract($rule_set = null, $global_options = null): array
    {
        $rule_set = $this->rule_set->merge($rule_set);
        $global_options = $this->parseGlobalOptions($global_options);

        $extracted = [];
        $traveler = new XmlTraveler($this->xml);
        foreach ($rule_set as $key => $rule) {
            $traveler->fetch($extracted, $key, $rule);
        }

        return $extracted;
    }

    /**
     * Parses the given array into a rule set.
     *
     * @param array $rule_set
     *
     * @return RuleSet
     */
    protected function parseRuleSet(array $rule_set = []): RuleSet
    {
        $parsed_rule_set = new RuleSet($rule_set);

        return $parsed_rule_set;
    }

    /**
     * Reads and parses the $xmlContents.
     * The method returns the instance for chaining method calls.
     *
     * @param string $xml_contents
     *
     * @return Xml
     */
    public function read(string $xml_contents): self
    {
        $this->xml = simplexml_load_string($xml_contents);

        return $this;
    }

    /**
     * Reads and parses the contents of the file specified by $xmlPath.
     * The method returns the instance for chaining method calls.
     *
     * @param $xml_path
     *
     * @return Xml
     */
    public function readFile($xml_path): self
    {
        if (!file_exists($xml_path)) {
            throw new FileNotFoundException('The file could not be found.', $xml_path);
        }

        return $this->read(file_get_contents($xml_path));
    }
    //endregion
}
