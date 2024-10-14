<?php

namespace App\Services;

use Illuminate\Support\Facades\DB as FacedesDB;

class DB
{
    public function getFKsFromTabela(object $infoTable)
    {
        if (!isset($infoTable->table_name) || !isset($infoTable->constraint_name)) {
            return [];
        }
        $table_name = $infoTable->table_name;
        $constraint_name = $infoTable->constraint_name;
        $resul = FacedesDB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = '$table_name' AND CONSTRAINT_NAME IS NOT NULL AND REFERENCED_TABLE_NAME IS NOT NULL;");
        $classarrayHandless = new arrayHandless();
        $result = array_map(function ($item) {
            $CONSTRAINT_NAME = $item->CONSTRAINT_NAME;
            return [
                "constraint_name" => $CONSTRAINT_NAME,
            ];
        }, $resul);
        $findOjbect = $classarrayHandless->findByValue($result, 'constraint_name', $constraint_name);
        return (object) $findOjbect;
    }
    public function dropForeign(string $constraint_name, string $table_name)
    {
        $FacedesDB = FacedesDB::statement("ALTER TABLE artesp.$table_name DROP FOREIGN KEY $constraint_name;");
        return $FacedesDB;
    }
    public function getTablesNames(string $table_name)
    {
        $tablesNames = FacedesDB::select("SELECT table_name FROM information_schema.tables;");

        $result =  array_map(function ($item) {
            $table_name = $item->TABLE_NAME;
            return [
                "table_name" => $table_name,
            ];
        }, $tablesNames);
        if ($table_name !== "") {
            $array_table_info = (array) $tablesNames;
            $classarrayHandless = new arrayHandless();
            $findOjbect = $classarrayHandless->filterArryByValue($array_table_info, 'table_name', $table_name, "less_and_equal");
            return $findOjbect;
        }
        return $result;
    }
    public function getColumnsName(string $table_name)
    {
        $TABLE_SCHEMA = env("DB_DATABASE");
        $Table = FacedesDB::select("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`= '$TABLE_SCHEMA' AND `TABLE_NAME`= '$table_name'");
        return array_map(function ($item) {
            $column_name = $item->COLUMN_NAME;
            return [
                "column_name" => $column_name,
            ];
        }, $Table);
    }
}
