<?php


namespace TheCodingMachine\FluidSchema;


use function addslashes;
use function array_map;
use function implode;
use function is_array;
use function is_numeric;
use function is_string;
use function str_replace;
use function var_export;

class DoctrineAnnotationDumper
{
    /**
     * Write a string representing the parameter passed in.
     * @param mixed $item
     */
    public static function exportValues($item): string
    {
        if ($item === null) {
            return '';
        }
        if ($item === []) {
            return '({})';
        }
        return '('.self::innerExportValues($item, true).')';
    }

    private static function innerExportValues($item, bool $first): string
    {
        if ($item === null) {
            return 'null';
        }
        if (is_string($item)) {
            return '"'.str_replace('"', '""', $item).'"';
        }
        if (is_numeric($item)) {
            return $item;
        }
        if (is_bool($item)) {
            return $item ? 'true' : 'false';
        }
        if (is_array($item)) {
            if (self::isAssoc($item)) {
                if ($first) {
                    array_walk($item, function(&$value, $key) {
                        $value = $key.' = '.self::innerExportValues($value, false);
                    });
                } else {
                    array_walk($item, function(&$value, $key) {
                        $value = '"'.addslashes($key).'":'.self::innerExportValues($value, false);
                    });
                }
                $result = implode(', ', $item);
                if (!$first) {
                    $result = '{'.$result.'}';
                }
                return $result;
            } else {
                array_walk($item, function(&$value, $key) {
                    $value = self::innerExportValues($value, false);
                });
                $result = implode(', ', $item);
                if (!$first) {
                    $result = '{'.$result.'}';
                }
                return $result;
            }
        }
        throw new \RuntimeException('Cannot serialize value in Doctrine annotation.');
    }

    private static function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
