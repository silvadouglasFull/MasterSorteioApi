<?php

namespace App\Services;


class isValid
{
    /**
     * Checks if the provided array contains valid data.
     *
     * @param array $array The array to be validated.
     * @return bool Returns true if the array contains valid data, otherwise false.
     */
    public function isValid($array)
    {
        $newData = [];
        foreach ($array as $value) {
            // Verifica se $value é um array
            if (is_array($value)) {
                $key = key($value);
                // Verifica se o valor no índice $key é uma string vazia
                if ($value[$key] === "") {
                    array_push($newData, $key);
                }
            }
        }
        // Retorna verdadeiro se não houver dados inválidos
        return empty($newData);
    }
    /**
     * Checks if the provided array contains valid objects.
     *
     * @param array $array The array containing objects to be validated.
     * @return bool Returns true if all objects in the array are valid, otherwise false.
     */
    public function isValidObject($array)
    {
        $newData = [];
        foreach ($array as $value) {
            $key = key((array)$value);
            if ($value === "" || !$value) {
                array_push($newData, "oi");
            }
        }
        if (count($newData) > 0) {
            return false;
        }
        return true;
    }
}
