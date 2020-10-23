<?php
declare(strict_types=1);

namespace App\Domain;

abstract class JsonHelper
{

    public static function intvalnull($val): ?int{
        if (is_numeric($val)){
            return intval($val);
        }
        return null;
    }

    public static function booleanval($val): int {
        if (is_bool($val)){
            return ($val ? 1 : 0);
        }
        else if (is_string($val)){
            return ($val == "1" ? 1 : 0);
        }
        return (boolval($val) ? 1 : 0);
    }

    public static function booleanvalundefined($object, $key, int $default): int {
        if (is_object($object) && property_exists($object, $key)){
            return self::booleanval($object->$key);
        }
        else if (is_array($object) && array_key_exists($key, $object)){
            return self::booleanval($object[$key]);
        }
        return $default;
    }

    public static function intvalundefined($object, $key): ?int
    {
        if (is_object($object) && property_exists($object, $key)){
            return self::intvalnull($object->$key);
        }
        else if (is_array($object) && array_key_exists($key, $object)){
            return self::intvalnull($object[$key]);
        }
        return null;
    }

    public static function timestamp($timestamp): ?int {
        if (is_int($timestamp)){
            return $timestamp;
        }
        else {
            $strtotime = strtotime($timestamp);
            if (!$strtotime){
                return null;
            }
            return $strtotime;
        }
    }
}