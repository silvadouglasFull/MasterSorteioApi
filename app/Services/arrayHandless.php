<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class arrayHandless
{
    /**
     * Filters an array of associative arrays based on a specified field and value using the given operator.
     *
     * @param array  $array     The array to be filtered.
     * @param string $field     The field/key to filter the array by.
     * @param mixed  $value     The value to compare against.
     * @param string $operator  The comparison operator ('bigger_then', 'less_and_equal', 'equal').
     *
     * @return array            The filtered array containing only the elements that satisfy the condition.
     */
    public function filterArryByValue(array $array, string $field, $value, string $operator)
    {
        if ($operator === 'bigger_then') {
            $new_array = array_filter($array, function ($obj) use ($field, $value) {
                if ($obj[$field] > $value) {
                    return $obj;
                }
            });
            return array_values($new_array);
        }
        if ($operator === 'less_and_equal') {
            $new_array = array_filter($array, function ($obj) use ($field, $value) {
                if ($obj[$field] <= $value) {
                    return $obj;
                }
            });
            return array_values($new_array);
        }
        $new_array = array_filter($array, function ($obj) use ($field, $value) {
            if ($obj[$field] === $value) {
                return $obj;
            }
        });
        return array_values($new_array);
    }
    /**
     * Finds the first associative array in the given array whose specified field matches the provided value.
     *
     * @param array  $array   The array to search.
     * @param string $field   The field/key to search for.
     * @param mixed  $value   The value to compare against.
     *
     * @return object|false    Returns the matching associative array as an object or false if no match is found.
     */
    public function findByValue(array $array, string $field, $value)
    {
        try {
            $objects = $array;
            $fieldToFilter = $field;
            $valueToFind = $value;

            foreach ($objects as $object) {
                if (!isset($object[$fieldToFilter])) {
                    Log::error("Não existe a field $fieldToFilter dentro de objetc");
                    Log::error(json_encode($objects));
                    return false;
                }
                if ($object[$fieldToFilter] === $valueToFind) {
                    return (object) $object;
                }
            }
            return false;
        } catch (\Throwable $th) {
            Log::error($th);
            Log::info($array);
            return false;
        }
    }

    /**
     * Checks if the specified value exists in the specified parameter of the given array using the specified comparator.
     *
     * @param object $array       The array of objects to search.
     * @param string $params      The parameter/key to check for existence.
     * @param mixed  $value       The value to compare against.
     * @param string $comparator  The comparator to determine existence ("equal" or "not_equal").
     *
     * @return bool|int[]|false   Returns an array of keys where the specified value exists or false if not found.
     */

    public function isExist(object $array, string $params, $value, string $comparator)
    {
        $isExist = [];
        if ($comparator === "equal") {
            foreach ($array as $key => $val) {
                if ($val[$params] === $value) {
                    return array_push($isExist, $key);
                }
            }
        } else {
            foreach ($array as $key => $val) {
                if ($val[$params] !== $value) {
                    return array_push($isExist, $key);
                }
            }
        }
        $count = count($isExist);
        if ($count === 0) {
            return false;
        }
        return true;
    }
    public function sortArray(array $array, string $field)
    {
        $result = usort(
            $array,
            function ($a, $b) use ($field) {
                if ($a[$field] == $b[$field]) return 0;
                return (($a[$field] < $b[$field]) ? -1 : 1);
            }
        );
        return $result;
    }
    /**
     * Filter and remove elements from the given array based on a property and its value.
     *
     * This function filters an array of objects, removing elements where the specified property
     * does not match the given value.
     *
     * @param array $array The array of objects to filter.
     * @param string $property The property to compare against.
     * @param mixed $value The value to compare against.
     * @return array The filtered array of objects.
     */
    function filterAndRemove(array $array, string $propriedade, $valor): array
    {
        return (array) array_filter($array, function ($objeto) use ($propriedade, $valor) {
            return $objeto[$propriedade] !== $valor;
        });
    }
    /**
     * Filters an array of items based on whether the specified property contains the given value.
     *
     * @param array $array The array to be filtered.
     * @param string $property The property to check within each item.
     * @param int $value The value to search for within the property.
     * @return array The filtered array containing only items where the specified property contains the given value.
     */
    function filterArrayWithInArray(array $array, string $property, int $value): array
    {
        if ($property === "" || $value === null) {
            return [];
        }
        return array_filter($array, function ($item) use ($property, $value) {
            return in_array($value, $item[$property]);
        });
    }
    /**
     * Filters and removes repeated items from an array based on their string representation.
     *
     * @param array|null $data The array from which repeated items should be removed.
     * @return array|null A new array with repeated items removed, or null if the input array is falsy.
     * @exemple
     * // Example usage:
     *$uniqueArray = filterAndRemoveRepeated([1, 2, 2, 3, 4, 4, 5]);
     *print_r($uniqueArray); // Output: [1, 2, 3, 4, 5]
     */
    function filterAndRemoveRepeated(array $data = null): ?array
    {
        if (!$data) {
            return null;
        }

        // Object used to track unique items based on their string representation
        $uniqueItemsMap = [];

        // Filter function to check for and remove repeated items
        $filterFunction = function ($a) use (&$uniqueItemsMap) {
            $stringRepresentation = json_encode($a);

            // Check if the item is unique based on its string representation
            if (!isset($uniqueItemsMap[$stringRepresentation])) {
                $uniqueItemsMap[$stringRepresentation] = true;
                return true;
            }
            return false;
        };

        return array_filter($data, $filterFunction);
    }
    /**
     * Groups an array of objects by a specific property.
     *
     * @param array $array - The array of objects to be grouped.
     * @param string $key - The property by which objects will be grouped.
     * @return array An array where the keys are the unique values of the property
     * and the values are arrays containing the corresponding objects.
     *
     * @example
     * $data = [
     *     ['id' => 1, 'category' => 'A', 'value' => 10],
     *     ['id' => 2, 'category' => 'B', 'value' => 20],
     *     ['id' => 3, 'category' => 'A', 'value' => 30],
     * ];
     *
     * $groupByCategory = groupBy($data, 'category');
     * print_r($groupByCategory);
     * // Expected output:
     * // Array
     * // (
     * //     [A] => Array
     * //         (
     * //             [0] => Array
     * //                 (
     * //                     [id] => 1
     * //                     [category] => A
     * //                     [value] => 10
     * //                 )
     * //
     * //             [1] => Array
     * //                 (
     * //                     [id] => 3
     * //                     [category] => A
     * //                     [value] => 30
     * //                 )
     * //
     * //         )
     * //
     * //     [B] => Array
     * //         (
     * //             [0] => Array
     * //                 (
     * //                     [id] => 2
     * //                     [category] => B
     * //                     [value] => 20
     * //                 )
     * //
     * //         )
     * //
     * // )
     */
    function groupBy(array $array, string $key): array
    {
        $result = [];
        foreach ($array as $item) {
            if (strpos($key, '.') !== false) {
                $keys = explode('.', $key);
                $current = $item;
                foreach ($keys as $innerKey) {
                    $current = $current[$innerKey];
                }
                $result[$current][] = $item;
            } else {
                $result[$item[$key]][] = $item;
            }
        }
        return $result;
    }
    /**
     * Filters an array to include only items that are exactly the same.
     *
     * This function takes an array as input and returns a new array containing only the items
     * that appear more than once in the input array.
     *
     * @param array $array The input array to filter.
     * @return array The filtered array containing only items that are exactly the same.
     */
    function filterExactlySameItems(array $array): array
    {
        // Count the occurrences of each item in the array
        $counts = array_count_values($array);

        // Initialize an empty array to store items that are exactly the same
        $result = [];

        // Iterate through the counts to find items that appear more than once
        foreach ($counts as $item => $count) {
            if ($count > 1) {
                // Add the item to the result array if it appears more than once
                $result[] = $item;
            }
        }

        // Return the filtered array
        return $result;
    }
    /**
     * Removes duplicate elements from an array.
     *
     * This function takes an array as input and removes duplicate elements, 
     * preserving the order of the elements in the original array.
     *
     * @param array $arrayObject The input array from which duplicate elements will be removed.
     * @return array The array with duplicate elements removed, preserving the original order.
     */
    public function removeElements($arrayObject)
    {
        $final  = array();
        foreach ($arrayObject as $current) {
            if (!in_array($current, $final)) {
                $final[] = $current;
            }
        }
        return $final;
    }
    /**
     * Flatten an array of arrays into a single array.
     *
     * @param array $arrayOfArrays The array of arrays to be flattened.
     * @return array The flattened array.
     */
    function flattenArray(array $arrayOfArrays): array
    {
        return array_reduce($arrayOfArrays, function ($acc, $val) {
            return array_merge($acc, $val);
        }, []);
    }
    /**
     * Filters an array of objects by removing duplicate items based on a specific property.
     *
     * @param array $array The array of objects to be filtered.
     * @param string $prop The property by which duplicate items will be removed.
     * @return array The filtered array without duplicate items based on the specified property.
     *
     * @example
     * $arrayOfObjects = [...]; // Your array of objects
     * $result = filterAndRemoveRepeatedByProps($arrayOfObjects, 'name');
     */
    function filterAndRemoveRepeatedByProps(array $array, string $prop): array
    {
        if (empty($array)) {
            return $array; // Returns the original array if it is empty
        }

        $seenValues = [];
        $result = [];

        foreach ($array as $object) {
            $value = $object[$prop];

            if (!in_array($value, $seenValues)) {
                // Adds the value to the seen values array to track repetitions
                $seenValues[] = $value;
                // Includes the object in the result
                $result[] = $object;
            }
        }

        return $result;
    }
}
