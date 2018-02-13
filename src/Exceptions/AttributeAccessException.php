<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Class AttributeAccessException
 *
 * @package NorseBlue\Seidr\Exceptions
 */
class AttributeAccessException extends RuntimeException
{
    //region ========== Properties ==========
    protected $attribute;
    //endregion

    //region ========== Constructor ==========
    /**
     * FileNotFoundException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param string         $attribute
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", string $attribute = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->attribute = $attribute;
    }
    //endregion

    //region ========== Accessors ==========
    /**
     * Attribute $attribute accesor.
     *
     * @return string
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }
    //endregion

    //region ========== Methods ==========
    /**
     * Returns the string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        $str = parent::__toString();

        !empty($this->attribute) && $str .= sprintf("\nAttribute: %s", $this->attribute);

        return $str;
    }
    //endregion
}
