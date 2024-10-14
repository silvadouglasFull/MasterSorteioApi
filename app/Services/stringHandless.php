<?php

namespace App\Services;

use Illuminate\Support\Str;

class stringHandless
{

    /**
     * Remove os acentos de uma string.
     *
     * Esta função substitui os caracteres acentuados por suas respectivas formas sem acento.
     *
     * @param string $string A string da qual os acentos serão removidos.
     * @return string A string sem acentos.
     */
    public function tirarAcentos($string)
    {
        return preg_replace(
            array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"),
            explode(" ", "a A e E i I o O u U n N"),
            $string
        );
    }

    /**
     * Limpa uma string removendo acentos, espaços e caracteres especiais.
     *
     * Esta função remove os acentos da string, em seguida remove os espaços e, por fim, remove
     * todos os caracteres que não são letras ou números.
     *
     * @param string $string A string a ser limpa.
     * @return string A string limpa sem acentos, espaços ou caracteres especiais.
     */
    public function limparString($string)
    {
        // Remover acentos
        $string = preg_replace('/[^\p{L}\p{N}\s]/u', '', iconv('UTF-8', 'ASCII//TRANSLIT', $string));

        // Remover espaços
        $string = preg_replace('/\s+/', '', $string);

        // Remover caracteres especiais
        $string = preg_replace('/[^A-Za-z0-9]/', '', $string);

        return $this->tirarAcentos($string);
    }
    /**
     * Added a period to every three digits of a number.
     * @param int $number - The number that will be formatted.
     * @param int $padNumber - After how many digits you want to add the periods.
     * @return string A string containing the number formatted with periods every three digits.
     * @example
     * $number = 1234567890;
     * $formattedNumber = addPointToThreeDigits($number, 3);
     * echo $formattedNumber; // Output: "1,234,567,890"
     */
    function addDots($number, $padNumber)
    {
        $numeroString = strval($number);
        $partes = [];
        $temp = '';
        for ($i = strlen($numeroString) - 1; $i >= 0; $i--) {
            $temp = $numeroString[$i] . $temp;
            if (strlen($temp) === $padNumber || $i === 0) {
                array_unshift($partes, $temp);
                $temp = '';
            }
        }
        $resultado = implode('.', $partes);
        return $resultado;
    }
    /**
     * Generate a random number with the specified number of digits.
     *
     * This function generates a random number with the specified number of digits.
     *
     * @param int $numDigits The number of digits the random number should have.
     * @return string The randomly generated number.
     * @throws string null If the number of digits is not a positive integer.
     * @example
     * $randomNumber = generateRandomNumber(8);
     * echo $randomNumber; // Output: Random 8-digit number
     */
    function generateRandomNumber($numDigits)
    {
        if (!is_int($numDigits) || $numDigits <= 0) {
            return "";
        }
        $min = pow(10, $numDigits - 1);
        $max = pow(10, $numDigits) - 1;
        $result = mt_rand($min, $max);
        return (string) $result;
    }
    /**
     * Generates a random password with 8 characters.
     *
     * @return string The generated password.
     */
    function generatePassword(): string
    {
        $length = 8;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $password;
    }
    /**
     * Generate a random filename with the given extension.
     *
     * @param string $extension The file extension (e.g., 'txt', 'pdf').
     * @param int $length The length of the random filename (excluding extension).
     * @return string The randomly generated filename with extension.
     */
    public function generateRandomFileName(string $extension, int $length = 10): string
    {
        // Generate a random filename using Laravel's Str class
        $randomName = Str::random($length);

        // Append the file extension to the random name
        $filename = $randomName . '.' . $extension;

        return $filename;
    }
    /**
     * Checks if the target string contains the specified keyword.
     *
     * @param string $targetString The string to search for the keyword.
     * @param string $keyword The keyword to search for in the target string.
     * @return bool True if the keyword is found in the target string, false otherwise.
     */
    public function checkForKeyword(string $targetString, string $keyword): bool
    {
        // Convert both strings to lowercase for a case-insensitive search
        $targetString = strtolower($targetString);
        $keyword = strtolower($keyword);

        // Check if the keyword exists in the target string
        return strpos($targetString, $keyword) !== false;
    }
    /**
     * Convert a value to double and format it to a specific number of decimal places.
     *
     * @param mixed $value The value to be converted and formatted.
     * @param int $decimalPlaces The number of decimal places.
     * @return string The formatted value.
     */
    function formatDouble($value, $decimalPlaces = 2)
    {
        $doubleValue = doubleval($value);
        return number_format($doubleValue, $decimalPlaces);
    }
    /**
     * Abbreviates each word in a given string by taking the first 3 letters of each word.
     *
     * This function splits the input string into individual words, then creates a new string
     * where each word is shortened to its first 3 characters (or less, if the word is shorter).
     * The resulting abbreviation maintains the word order and adds a space between the abbreviated words.
     *
     * @param string $string The input string to be abbreviated. This string can consist of multiple words separated by spaces.
     *
     * @return string A new string with each word abbreviated to its first 3 characters, separated by spaces.
     *
     * @example
     * // Returns "Joã da Sil San"
     * $abbreviated = abbreviateWord("João da Silva Santos");
     */
    function abbreviateWord($string)
    {
        // Split the string into words
        $palavras = explode(" ", $string);

        $abreviacao = "";

        foreach ($palavras as $palavra) {
            // Take the first 3 letters or fewer if the word is shorter
            $abreviacao .= substr($palavra, 0, 3) . " ";
        }

        return trim($abreviacao);
    }
    function mod($dividendo, $divisor)
    {
        return round($dividendo - (floor($dividendo / $divisor) * $divisor));
    }

    function cpf($compontos)
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - ($this->mod($d1, 11));

        if ($d1 >= 10) {
            $d1 = 0;
        }

        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - ($this->mod($d2, 11));

        if ($d2 >= 10) {
            $d2 = 0;
        }

        $retorno = '';

        if ($compontos == 1) {
            $retorno = '' . $n1 . $n2 . $n3 . "." . $n4 . $n5 . $n6 . "." . $n7 . $n8 . $n9 . "-" . $d1 . $d2;
        } else {
            $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $d1 . $d2;
        }

        return $retorno;
    }

    function cnpj($compontos)
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = 0;
        $n10 = 0;
        $n11 = 0;
        $n12 = 1;
        $d1 = $n12 * 2 + $n11 * 3 + $n10 * 4 + $n9 * 5 + $n8 * 6 + $n7 * 7 + $n6 * 8 + $n5 * 9 + $n4 * 2 + $n3 * 3 + $n2 * 4 + $n1 * 5;
        $d1 = 11 - ($this->mod($d1, 11));

        if ($d1 >= 10) {
            $d1 = 0;
        }
        $d2 = $d1 * 2 + $n12 * 3 + $n11 * 4 + $n10 * 5 + $n9 * 6 + $n8 * 7 + $n7 * 8 + $n6 * 9 + $n5 * 2 + $n4 * 3 + $n3 * 4 + $n2 * 5 + $n1 * 6;
        $d2 = 11 - ($this->mod($d2, 11));

        if ($d2 >= 10) {
            $d2 = 0;
        }

        $retorno = '';

        if ($compontos == 1) {
            $retorno = '' . $n1 . $n2 . "." . $n3 . $n4 . $n5 . "." . $n6 . $n7 . $n8 . "/" . $n9 . $n10 . $n11 . $n12 . "-" . $d1 . $d2;
        } else {
            $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $n10 . $n11 . $n12 . $d1 . $d2;
        }

        return $retorno;
    }
    public function mask_CPF_CNPJ(string $valor)
    {
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        return $valor;
    }
}
