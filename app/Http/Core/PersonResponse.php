<?php

namespace App\Http\Core;

class PersonResponse
{
    protected int $count;
    protected array $links;
    /**
     * Return a formatted response for objects.
     *
     * This function checks if the input result is an object. If it's not an object,
     * it returns an array with total count 0 and data as null. If it's an object,
     * it calculates the count of the object and returns an array with total count
     * and the original object data.
     *
     * @param object $result The result object to be formatted.
     * @return array The formatted response array with total count and data.
     */
    public function returnResponse(object $result)
    {
        if (!is_object($result)) {
            return [
                'total' => 0,
                'data' => null
            ];
        }
        $this->count = count($result);
        return [
            'total' => $this->count,
            'data' => $result
        ];
    }

    /**
     * Return a formatted response for arrays.
     *
     * This function checks if the input result is an array. If it's not an array,
     * it returns an array with total count 0 and data as null. If it's an array,
     * it calculates the count of the array and returns an array with total count
     * and the original array data.
     *
     * @param array $result The result array to be formatted.
     * @return array The formatted response array with total count and data.
     */
    public function returnResponseArray(array $result)
    {
        if (!is_array($result)) {
            return [
                'total' => 0,
                'data' => null
            ];
        }
        $this->count = count($result);
        return [
            'total' => $this->count,
            'data' => $result
        ];
    }

    /**
     * Return a formatted response for paginated objects.
     *
     * This function checks if the input result is an object. If it's not an object,
     * it returns an array with total count 0 and data as null. If it's an object,
     * it gets the total count of the paginated object and returns the original
     * paginated object.
     *
     * @param object $result The paginated result object to be formatted.
     * @return object|array The formatted response object or array with total count and data.
     */
    public function returnResponsePaginate(object $result)
    {
        if (!is_object($result)) {
            return [
                'total' => 0,
                'data' => null
            ];
        }
        $this->count = $result->total();
        return $result;
    }
}
