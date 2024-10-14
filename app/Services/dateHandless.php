<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class dateHandless
{
    protected $now;
    public function __construct()
    {
        $this->now = Carbon::now(env("APP_TIMEZONE"));
    }
    /**
     * Checks if a specific date is before another date.
     *
     * @param string $date_init The specific date to compare.
     * @param string $date_fim  The reference date. If empty or null, current date and time will be used.
     *
     * @return bool Returns true if the specific date is before the reference date, otherwise false.
     */
    public function isBeforeDate(string $date_init, string $date_fim)
    {
        try {
            // Crie um objeto Carbon para a data específica
            $dataEspecifica = Carbon::parse($date_init, env("APP_TIMEZONE"));
            if ($date_fim === "" || $date_fim === null) {
                // Obtenha a data e hora atual
                $dataAtual = $this->now;
            } else {
                $dataAtual = Carbon::parse($date_fim, env("APP_TIMEZONE"));
            }
            // Verifique se a data específica é menor que a data atual
            if ($dataEspecifica->gt($dataAtual)) {
                return false;
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return false;
        }
    }
    public function diffdays(string $date_init, string $date_fim, bool $returnNewDate)
    {
        try {
            if ($date_init === null || $date_init === "") {
                $dataAtual = $this->now;
            } else {
                $dataAtual = Carbon::parse($date_init, env("APP_TIMEZONE"));
            }
            if ($date_fim === null || $date_fim === "") {
                $dataEspecifica = $this->now;
            } else {
                $dataEspecifica = Carbon::parse($date_fim, env("APP_TIMEZONE"));
            }
            $diferencaEmDias = $dataAtual->diffInDays($dataEspecifica);
            if (!$returnNewDate) {
                return $diferencaEmDias;
            }
            $novaData = $dataAtual->addDays($diferencaEmDias);
            return $novaData;
        } catch (\Throwable $th) {
            Log::error($th);
            return false;
        }
    }
    /**
     * Converts a string representing a date to a formatted date string.
     *
     * This function takes a string representing a date and converts it to a formatted date string
     * with the year and month preserved, but the day set to the last day of the month.
     *
     * @param string $string A string representing a date (e.g., "2024-04-15").
     * @return string The formatted date string with the year and month preserved and the day set to the last day of the month (e.g., "2024-04-30").
     */
    public function convertStringDate($string)
    {
        // Convert the string to a timestamp
        $timestamp = strtotime($string);

        // Extract the year and month from the timestamp
        $year = date('Y', $timestamp);
        $month = date('m', $timestamp);

        // Calculate the last day of the month
        $last_day = date('t', mktime(0, 0, 0, $month, 1, $year));

        // Create the final date string with the year, month, and last day
        $final_date = date('Y-m', $timestamp) . '-' . $last_day;

        // Return the formatted date string
        return $final_date;
    }

    /**
     * Calculate the difference in months between two formatted dates.
     *
     * This function calculates the difference in months between two dates formatted as "Y-m-d".
     *
     * @param string $date1 The first formatted date (YYYY-MM-DD).
     * @param string $date2 The second formatted date (YYYY-MM-DD).
     * @return int The difference in months between the two dates.
     *
     * @example
     * $date1 = '2024-06-01';
     * $date2 = '2025-12-15';
     * $differenceInMonths = monthDifference($date1, $date2);
     */
    public function monthDifference(string $date1, string $date2): int
    {
        try {
            // Parse the formatted dates into Carbon instances
            $carbonDate1 = Carbon::createFromFormat('Y-m-d', $date1);
            $carbonDate2 = Carbon::createFromFormat('Y-m-d', $date2);

            // Calculate the difference in months
            $differenceInMonths = $carbonDate1->diffInMonths($carbonDate2);

            return $differenceInMonths;
        } catch (\Throwable $th) {
            Log::error($th);
            return 0;
        }
    }
    /**
     * Divides a specified number of months by a percentage and returns an array of objects representing each month with its corresponding percentage.
     *
     * This function takes an initial date and the total number of months as input and calculates a percentage share for each month. It then creates an array of objects containing the calculated percentage and the date for each month, iterating through the specified number of months and adding one month to the initial date in each iteration. Finally, the function sorts the resulting array by date in ascending order.
     *
     * @param int $differenceInMonths The total number of months to divide.
     * @param string $date_init The initial date in Y-m-d format.
     * @return array An array of objects containing 'acob_perc_prev' (percentage) and 'acob_date_prev' (date) properties for each month.
     * @throws Throwable If an unexpected error occurs during the process.
     */
    public function divideMonthByPercentage(int $differenceInMonths, string $date_init): array
    {
        try {
            if ($differenceInMonths === 0) {
                return [];
            }
            $acob_perc_prev = 100 / $differenceInMonths;
            if ($acob_perc_prev === 0) {
                return [];
            }
            $temp_acob_date_prev = Carbon::createFromFormat('Y-m-d', $date_init);
            $result = [];
            for ($i = 0; $i <= $differenceInMonths; $i++) {
                if ($i === 0) {
                    // Clone a data inicial para evitar referências
                    $acob_date_prev = $temp_acob_date_prev->copy();
                } else {
                    $lass_prev_fim = $result[count($result) - 1]->acob_date_prev_fim;
                    $acob_date_prev = Carbon::parse($lass_prev_fim, env("APP_TIMEZONE"));
                }
                $acob_date_prev_fim = $acob_date_prev->copy();
                // Adicione 30 dias à data atual
                $acob_date_prev_fim->addDays(30);

                // Adicione o objeto ao array de resultados
                $result[] = (object)[
                    "acob_perc_prev" => $i === 0 ? 0 : doubleval($acob_perc_prev * $i),
                    "acob_date_prev" => $acob_date_prev->format('Y-m-d'),
                    "acob_date_prev_fim" => $acob_date_prev_fim->format('Y-m-d')
                ];
                usort($result, function ($a, $b) {
                    return strtotime($a->acob_date_prev) - strtotime($b->acob_date_prev);
                });
            }
            usort($result, function ($a, $b) {
                return strtotime($a->acob_date_prev) - strtotime($b->acob_date_prev);
            });
            Log::info(json_encode($result));
            return $result;
        } catch (\Throwable $th) {
            Log::error($th);
            return [];
        }
    }
}
