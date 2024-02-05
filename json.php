<?php

namespace JSON;

/**
 * Class json<br>
 * An easy way for using json encoded strings in php.
 * @author Esmaeil Taheri
 * @license MIT
 * @link https://github.com/es-taheri/json
 */
class json
{
    /**
     * Returns the JSON representation of a value
     *
     * @param mixed $data
     * The value being encoded. Can be any type except a resource.
     * All string data must be UTF-8 encoded.
     * PHP implements a superset of JSON - it will also encode and decode scalar types and NULL.
     * he JSON standard only supports these values when they are nested inside an array or an object.
     * @param int|null $flag
     * [optional]
     * Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE.
     * JSON_THROW_ON_ERROR The behaviour of these constants is described on the JSON constants page.
     * @param int $depth
     * [optional]
     * Set the maximum depth. Must be greater than zero.
     *
     * @return false|string a JSON encoded string on success or FALSE on failure.
     */
    public static function _out(mixed $data, int $flag = 0, int $depth = 512): false|string
    {
        return json_encode($data, $flag, $depth);
    }

    /**
     * Decodes a JSON string
     *
     * @param string $data
     * The json string being decoded.
     * This function only works with UTF-8 encoded strings.
     * PHP implements a superset of JSON - it will also encode and decode scalar types and NULL.
     * The JSON standard only supports these values when they are nested inside an array or an object.
     * @param bool|null $associative
     * When TRUE, returned objects will be converted into associative arrays.
     * @param int $depth
     * [optional]
     * User specified recursion depth.
     * @param int $flags
     * [optional]
     * Bitmask of JSON decode options:
     * <code>JSON_BIGINT_AS_STRING</code> decodes large integers as their original string value.
     * <code>JSON_INVALID_UTF8_IGNORE<code> ignores invalid UTF-8 characters,
     * <code>JSON_INVALID_UTF8_SUBSTITUTE<code> converts invalid UTF-8 characters to \0xfffd,
     * <code>JSON_OBJECT_AS_ARRAY<code> decodes JSON objects as PHP array, since 7.2.0 used by default if $assoc parameter is null,
     * <code>JSON_THROW_ON_ERROR<code> when passed this flag, the error behaviour of these functions is changed.
     * The global error state is left untouched, and if an error occurs that would otherwise set it, these functions instead throw a JsonException
     *
     * @return mixed
     * the value encoded in json in appropriate PHP type.
     * Values true, false and null (case-insensitive) are returned as TRUE, FALSE and NULL respectively.
     * NULL is returned if the json cannot be decoded or if the encoded data is deeper than the recursion limit.
     */
    public static function _in(string $data, bool $associative = null, int $depth = 512, int $flags = 0): mixed
    {
        if (is_object($data)) $data = json_encode($data);
        $out = json_decode($data, $associative, $depth, $flags);
        if ($associative && !is_array($out)) $out = json_decode($out, true);
        return $out;
    }

    /**
     * Check if a string is json encoded string or not?
     *
     * @param string $string
     * string to being checked
     * @return bool
     * <code>TRUE</code> if the string is json encoded or <code>FALSE</code> if not.
     */
    public static function _is(string $string): bool
    {
        if (is_numeric($string)) {
            if (is_object($string) === false) {
                return false;
            } else {
                return true;
            }
        } else {
            json_decode($string);
            return json_last_error() === JSON_ERROR_NONE;
        }
    }

    /**
     * Update or add an object in encoded json string
     * @param string $data
     * Json encoded string
     * @param array $update
     * Array of keys you want to update with its value.<br>
     * Example: ['foo'=>'bar','foo2'=>123,'foo3'=>['bar1','bar2','bar3']
     * @return false|string
     * <code>FALSE</code> if <code>$data</code> isn't json encoded string or json encoded string of updated <code>$data</code> on success.
     */
    public static function update(string $data, array $update): false|string
    {
        if (self::_is($data)):
            $data = self::_in($data, true);
            if (array_values($update) === $update):
                $data[] = $update;
            else:
                foreach ($data as $key => $value):
                    $data[$key] = $value;
                endforeach;
            endif;
            return self::_out($data);
        else:
            return false;
        endif;
    }

    /**
     * Get the value of a key/keys form json encoded string.
     * @param string $data
     * Json encoded string
     * @param string|array $key
     * array of keys or string of a key you want to get the value of it.
     * @return mixed
     * <code>FALSE</code> if <code>$data</code> isn't json encoded string or the value of key or keys.<br>
     * Note: If you want to get the values of keys it will return an array of keys.
     */
    public static function get(string $data, string|array $key): mixed
    {
        if (self::_is($data)):
            $data = self::_in($data);
            if (is_array($key)):
                $return = [];
                foreach ($key as $a_key):
                    if (isset($data->{$a_key})) $return[$a_key] = $data->{$a_key};
                endforeach;
            else:
                $return = $data[$key] ?? false;
            endif;
        else:
            $return = false;
        endif;
        return $return;
    }

    /**
     * Removes a key/keys with its value from json encoded string.
     * @param string $data
     * Json encoded string
     * @param string|array $key
     * array of keys or string of a key you want to remove it with its value.
     * @return string|false
     * <code>FALSE</code> if <code>$data</code> isn't json encoded string or json encoded string of removed <code>$key</code> on success.
     */
    public static function remove(string $data, string|array $key): string|false
    {
        if (self::_is($data)):
            $data = self::_in($data, true);
            if (is_array($key)):
                foreach ($key as $a_key):
                    if (isset($data[$a_key])) unset($data[array_search($a_key, $data)]);
                endforeach;
            else:
                unset($data[array_search($key, $data)]);
            endif;
            return self::_out($data);
        else:
            return false;
        endif;
    }
}