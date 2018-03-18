<?php
namespace LiveInbox\Framework\Support;

use LiveInbox\Framework\Factory\AdapterFactory;

class Helpers {
    /**
     * Dump and Die
     *
     * @param  dynamic  mixed
     * @return void
     */
    public function dd()
    {
        echo '<pre>';
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        die;
    }

    /**
     * Pretty Print
     *
     * @param  dynamic  mixed
     * @return void
     */
    public function pp()
    {
        echo '<pre>';
        array_map(function ($x) {
            print_r($x);
        }, func_get_args());
    }

    public function backtrace()
    {
        echo "<pre>";
        var_dump(array_map(function ($x) {
            return array($x['file'] => $x['line']);
        }, debug_backtrace()));
        die;
    }

    /**
     * Shuffle array and preserve key => value relation
     *
     *
     * @param array $array
     * @return array
     */
    public function shuffleAssoc(array $array)
    {
        $keys = array_keys($array);
        shuffle($keys);
        $random = [];
        foreach ($keys as $key) {
            $random[$key] = $array[$key];
        }

        return $random;
    }

    /**
     * Get key from array or get default value
     *
     *
     * @param array $array
     * @param mixed $key
     * @param string $default
     * @return mixed
     */
    public function get(array $array, $key, $default = null)
    {
        if (self::contains($key, '.')) {
            return self::getArrayDotNotation($key, $array, $default);
        }
        return isset($array[$key]) ? $array[$key] : $default;
    }

    /**
     * Return the passed object. Useful for chaining.
     *
     * @param Object $object
     * @return Object
     */
    public function chain($object)
    {
        return $object;
    }

    /**
     * Checks if a string ends with a given substring
     *
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    public function endsWith($haystack, $needle)
    {
        return $needle == substr($haystack, -strlen($needle));
    }

    /**
     * Checks if a string starts with a given substring
     *
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    public function startsWith($haystack, $needle)
    {
        return $needle != '' && strpos($haystack, $needle) === 0;
    }

    /**
     * Checks if a string contains a given substring
     *
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    public function contains($haystack, $needle)
    {
        return $needle != '' && strpos($haystack, $needle);
    }

    public function delimiterToCamelCase($string, $delimiter, $isFirstUpperCase = false)
    {
        $result = str_replace($delimiter, '', ucwords($string, $delimiter));

        if (! $isFirstUpperCase) {
            $result = lcfirst($result);
        }

        return $result;
    }

    /**
     * Convert strings with underscores into CamelCase
     *
     * @param    string $string The string to convert
     * @param    bool $first_char_caps camelCase or CamelCase
     * @return    string    The converted string
     */
    public function underscoreToCamelCase($string, $first_char_caps = false)
    {
        return self::delimiterToCamelCase($string, '_', $first_char_caps);
    }

    /**
     * Converts camelCase string to its under_score representation.
     * The method works only on standard latin characters.
     *
     * @param string $camelCased
     * @return string
     */
    public function camelCaseToUnderScore($camelCased)
    {
        $underscored = preg_replace('/([A-Z])/', '_$0', $camelCased);
        $underscored = mb_convert_case($underscored, MB_CASE_LOWER);

        return $underscored;
    }

    /**
     * Converts camelCase string to its human readable representation.
     * The method works only on standard latin characters.
     *
     * @param string $camelCased
     * @return string
     */
    public function camelCaseToSpaces($camelCased)
    {
        $spaced = preg_replace('/([A-Z])/', ' $0', $camelCased);
        $spaced = mb_convert_case($spaced, MB_CASE_LOWER);

        return $spaced;
    }

    /**
     * Converts human readable string to its camelCase representation.
     * The method works only on standard latin characters.
     *
     * @param string $camelCased
     * @return string
     */
    public function spacesToCamelCase($spaced, $first_char_caps = false)
    {
        return self::delimiterToCamelCase($spaced, ' ', $first_char_caps);
    }

    public function getRealIpAddress()
    {
        if (! empty($_SERVER['HTTP_CLIENT_IP']) && ($_SERVER['HTTP_CLIENT_IP'] != 'unknown')) {
            $ip = $_SERVER['HTTP_CLIENT_IP']; // share internet
        } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR']) && ($_SERVER['HTTP_X_FORWARDED_FOR'] != 'unknown')) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; // pass from proxy
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Get DB date
     *
     *
     * @return number
     */
    public function getCurrentDate()
    {
        return AdapterFactory::create()->currentDate;
    }

    /**
     * Get DB timestamp
     *
     *
     * @return number
     */
    public function getCurrentTimestamp()
    {
        return AdapterFactory::create()->currentTimestamp;
    }

    /**
     * Checks if array is associative
     *
     * @param array $array
     * @return boolean
     */
    public function isAssoc(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    /**
     * Flatten multidimentional array
     *
     * @param array $array
     * @return array
     */
    public function arrayFlatten(array $array)
    {
        $return = [];
        $callback = function ($a) use (&$return) {
            $return[] = $a;
        };
        array_walk_recursive($array, $callback);
        return $return;
    }

    /**
     * Flatten multidimentional array and keep keys with dot notation
     * http://doc4dev.net/doc/Laravel/4/source-public function-array_flatten.html#245-258
     * @param array $array
     * @return array
     */
    public function arrayFlattenDot(array $array, $prepend = '')
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, self::arrayFlattenDot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }
        return $results;
    }

    /**
     * Convern array keys to camelCase
     * @param array $array
     * @return array
     */
    public function arrayKeysToCamelCase(array $array)
    {
        $return = $array;
        if (empty($return)) {
            return $return;
        }

        foreach ($array as $key => $value) {
            $return[self::underscoreToCamelCase($key)] = $value;
        }

        return $return;
    }

    /**
     * Convern array keys to under_score
     * @param array $array
     * @return array
     */
    public function arrayKeysToUnderScore(array $array)
    {
        $return = $array;
        if (empty($return)) {
            return $return;
        }

        foreach ($array as $key => $value) {
            $return[self::camelCaseToUnderScore($key)] = $value;
        }

        return $return;
    }

    /**
     * Return the given value casted to INTEGER
     * NOTE - php public function intval returns 0 if null is passed
     * Used mostly to remain the possibility to unsetNullValues()
     * @param mixed $value
     * @return int | null
     */
    public function toInt($value)
    {
        return is_null($value) ? null : intval($value);
    }

    /**
     * Return the given value casted to FLOAT
     * NOTE - php public function floatval returns 0 if null is passed
     * Used mostly to remain the possibility to unsetNullValues()
     * @param mixed $value
     * @return float | null
     */
    public function toFloat($value)
    {
        return is_null($value) ? null : floatval($value);
    }

    /**
     * Set values deep in the array using DOT notation
     *
     *
     * @param string $path
     * @param mixed $value
     * @param array $arr
     */
    public function setArrayDotNotation($path, $value, array &$arr)
    {
        $addToArray = false;
        if (self::endsWith($path, '[]')) {
            $addToArray = true;
            $path = preg_replace('/\[\]/', '', $path);
        }
        $keys = explode('.', $path);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (! isset($arr[$key])) {
                $arr[$key] = [];
            }
            $arr = &$arr[$key];
        }

        $key = reset($keys);
        if ($addToArray) {
            $arr[$key][] = $value;
        } else {
            $arr[$key] = $value;
        }
    }

    /**
     * Get values from array using DOT notation
     *
     *
     * @param string $path
     * @param array $arr
     * @param mixed $default
     * @return mixed
     */
    public function getArrayDotNotation($path, array $arr, $default = null)
    {
        $keys = explode('.', $path);
        foreach ($keys as $key) {
            if (isset($arr[$key])) {
                $arr = $arr[$key];
            } else {
                return $default;
            }
        }

        return $arr;
    }

    /**
     * Format the memory (in bytes)
     * If no memory is supplied current memory allocated by the PHP process will be used
     *
     *
     * @param int $size
     * @return string
     */
    public function readMemory($size = null)
    {
        if (is_null($size)) {
            $size = memory_get_usage();
        }
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    /**
     * Return classname without namespace
     *
     *
     * @param string $className
     * @return string
     */
    public function stripNamespaceFromClassName(string $className)
    {
        return array_pop(explode('\\', $className));
    }

    /**
     *
     * @param number $from
     * @param number $percent
     * @return number
     */
    public function calcPercent($from, $percent, $round = true, $positive = true)
    {
        $return = $from * ($percent / 100);
        if ($round) {
            $return = self::toInt(round($return));
        }
        if ($positive) {
            $return = max($return, 0);
        }

        return $return;
    }

    /**
     * Check if number is odd
     *
     * @param unknown $number
     * @return bool
     */
    public function isOdd($number)
    {
        return ((int)$number % 2) != 0;
    }

    /**
     * Check if a number is even
     *
     * @param unknown $number
     * @return bool
     */
    public function isEven($number)
    {
        return ((int)$number % 2) == 0;
    }

    /**
     *    clear input variable (anti hack)
     *
     * @param string $value
     * @return string
     */
    public function clearInput($data)
    {
        $data = trim(addslashes(strip_tags($data)));

        return $data;
    }

    /**
     * Get first element in array
     *
     * @author elitsa.ilieva
     * @param array $array
     */
    public function first(array $array)
    {
        reset($array);
        return current($array);
    }

    /**
     * Get last element in array
     *
     * @author elitsa.ilieva
     * @param array $array
     */
    public function last(array $array)
    {
        reset($array);
        return end($array);
    }

    /**
     * Convert all keys of array to camelCase
     *
     *
     * @author elitsa.ilieva
     * @param array $array
     * @return string|unknown
     */
    public function underscoreToCamelCaseAssoc(array $array)
    {
        $keys = array_map(function ($k) use ($array) {
            return self::underscoreToCamelCase($k);
        }, array_keys($array));
        $array = array_combine($keys, $array);
        return $array;
    }


    /**
     * Recursive directory search
     *
     *
     * @param string $folder
     * @param RegEx $pattern
     * @return multitype:
     */
    public function rsearch($folder, $pattern = '/.*\.php/')
    {
        $dir = new RecursiveDirectoryIterator($folder);
        $ite = new RecursiveIteratorIterator($dir);
        $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
        $fileList = [];

        foreach ($files as $file) {
            $fileList = array_merge($fileList, $file);
        }

        return $fileList;
    }

    public function sortArrayBySubarrayValue(array &$array, $fieldName)
    {
        usort($array, function ($a, $b) use ($fieldName) {
            if (! is_array($a)) {
                return 1;
            }
            if (! is_array($b)) {
                return -1;
            }
            if (! isset($a[$fieldName])) {
                return 1;
            }
            if (! isset($b[$fieldName])) {
                return -1;
            }
            return $a[$fieldName] >= $b[$fieldName];
        });

        return $array;
    }
}
