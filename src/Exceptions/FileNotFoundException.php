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
 * Class FileNotFoundException
 *
 * @package NorseBlue\Seidr\Exceptions
 */
class FileNotFoundException extends RuntimeException
{
    //region ========== Properties ==========
    protected $filePath;
    //endregion

    //region ========== Constructor ==========
    /**
     * FileNotFoundException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param string         $filePath
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", string $filePath = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->filePath = $filePath;
    }
    //endregion

    //region ========== Accessors ==========
    /**
     * Attribute $filePath accesor.
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
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

        !empty($this->filePath) && $str .= sprintf("\nFile path: %s", $this->filePath);

        return $str;
    }
    //endregion
}
