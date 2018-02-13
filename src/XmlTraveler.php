<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr;

use NorseBlue\Seidr\Concerns\HasProxiedAttributes;
use SimpleXMLElement;

class XmlTraveler
{
    use HasProxiedAttributes;

    //region ========== Properties ==========
    /**
     * @var SimpleXMLElement The xml object to use to travel.
     */
    protected $xml;
    //endregion

    //region ========== Constructor ==========
    /**
     * XmlTraveler constructor.
     *
     * @param SimpleXMLElement $xml
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }
    //endregion

    //region ========== Accessors ==========
    /**
     * Attribute $xml accessor.
     *
     * @return SimpleXMLElement
     */
    public function getXmlAttribute(): SimpleXMLElement
    {
        return $this->xml;
    }
    //endregion

    //region ========== Mutators ==========
    /**
     * Attribute $xml mutator.
     *
     * @param SimpleXMLElement $xml
     */
    public function setXml(SimpleXMLElement $xml): void
    {
        $this->xml = $xml;
    }
    //endregion

    //region ========== Methods ==========
    /**
     * Extracts data from the XML object following the given $rule and storing it in the referenced
     * $extracted array under the given $key using the $global_options.
     *
     * @param array  $extracted
     * @param string $key
     * @param Rule   $rule
     * @param array  $global_options
     *
     * @return bool Returns true if the element could be found, false otherwise regardless on the options given.
     */
    public function fetch(array &$extracted, string $key, Rule $rule, ?array $global_options = null): bool
    {

    }
    //endregion
}
