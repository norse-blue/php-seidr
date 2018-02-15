<?php
/**
 * Seidr - A framework agnostic package for easy XML data extraction.
 *
 * @author  Axel Pardemann <axel.pardemann@norse.blue>
 * @link    https://github.com/NorseBlue/Seidr
 * @license https://github.com/NorseBlue/Seidr/blob/master/LICENSE.md
 */

namespace NorseBlue\Seidr\Rules;

/**
 * Class MappingTokenizer
 *
 * @package NorseBlue\Seidr\Rules
 */
class MappingTokenizer
{
    /**
     * Tokenizes the rule's mapping.
     *
     * @param string $mapping
     *
     * @return array
     */
    public static function tokenize(string $mapping)
    {
        $parsed = [];
        if (empty($mapping)) {
            return $parsed;
        }

        $length = mb_strlen($mapping);
        $cursor = 0;
        do {
            list($symbol, $cursor, $type) = static::getToken($mapping, $cursor);
            $parsed[] = ['type' => $type, 'symbol' => $symbol];
        } while (++$cursor < $length && $type !== 'attr');

        return $parsed;
    }

    /**
     * Gets the token from the rule's mapping that starts at offset.
     *
     * @param string $mapping
     * @param int    $offset
     *
     * @return array
     */
    protected static function getToken(string $mapping, int $offset): array
    {
        if ($offset > mb_strlen($mapping)) {
            return [null, null, null];
        }

        $char = $mapping[$offset];
        switch ($char) {
            case '@':
                // Tokenize attribute
                $cursor = static::getNextCursorPos($mapping, '.', $offset);
                $symbol = static::getSymbol($mapping, $offset + 1, $cursor);
                $type = 'attr';
                break;
            case '[':
                // Tokenize Collection
                $cursor = static::getNextCursorPos($mapping, ']', $offset);
                $symbol = static::getSymbol($mapping, $offset + 1, $cursor);
                $type = 'collection';
                break;
            case '.':
                $offset++;
                // If it's a . skip to the next char and carry on to tokenize a node.
            default:
                // Tokenize Node
                $cursor = static::getNextCursorPos($mapping, '.[@', $offset);
                $symbol = static::getSymbol($mapping, $offset, $cursor--);
                $type = 'node';
                break;
        }

        return [$symbol, $cursor, $type];
    }

    /**
     * Gets the next cursor position (the position that closes the token).
     *
     * @param string $haystack
     * @param string $needles
     * @param int    $offset
     * @param string $encoding
     *
     * @return int
     */
    protected static function getNextCursorPos(
        string $haystack,
        $needles = '',
        int $offset = 0,
        string $encoding = 'UTF-8'
    ) {
        if (is_string($needles)) {
            $needles = str_split($needles);
        }

        $pos = collect($needles)
            ->map(function ($needle) use ($haystack, $offset, $encoding) {
                return mb_strpos($haystack, $needle, $offset, $encoding);
            })
            ->filter(function ($pos) {
                return ($pos !== false);
            })
            ->min();

        return ($pos) ? $pos : mb_strlen($haystack);
    }

    /**
     * Gets the token symbol.
     * 
     * @param string $mapping
     * @param        $offset
     * @param        $cursor
     *
     * @return string
     */
    protected static function getSymbol(string $mapping, $offset, $cursor)
    {
        $length = $cursor - $offset;
        $symbol = mb_substr($mapping, $offset, $length);
        if ($length == 0 && empty($symbol)) {
            $symbol = '*';
        }

        return $symbol;
    }
}
