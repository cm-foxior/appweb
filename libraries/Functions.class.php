<?php

defined('_EXEC') or die;

class Functions
{
    static public function conversion($type, $quantity, $unity_1, $unity_2)
    {
        $conversion = 0;

        if ($unity_1 == 'HY763H8O') // Piezas
        {

        }
        else if ($unity_1 == 'MKJHTYIA') // Kilogramos
        {
            if ($unity_2 == 'HY763H8O') // Piezas
            {

            }
            else if ($unity_2 == 'MKJHTYIA') // Kilogramos
                $conversion = $quantity * 1;
            else if ($unity_2 == 'SHBJ9876') // Gramos
                $conversion = $quantity * 1000;
            else if ($unity_2 == '098YH65W') // Miligramos
                $conversion = $quantity * 1000000;
            else if ($unity_2 == '456789HY') // Kilolitros
            {

            }
            else if ($unity_2 == 'JU76GF59') // Litros
            {

            }
            else if ($unity_2 == 'AXDE5TB2') // Mililitros
            {

            }
            else if ($unity_2 == 'WDTG34CF') // Onza fluida
            {

            }
            else if ($unity_2 == '4FT5BQ7K') // Onza de peso
            {

            }
        }
        else if ($unity_1 == 'SHBJ9876') // Gramos
        {
            if ($unity_2 == 'HY763H8O') // Piezas
            {

            }
            else if ($unity_2 == 'MKJHTYIA') // Kilogramos
                $conversion = $quantity / 1000;
            else if ($unity_2 == 'SHBJ9876') // Gramos
                $conversion = $quantity / 1;
            else if ($unity_2 == '098YH65W') // Miligramos
                $conversion = $quantity * 1000;
            else if ($unity_2 == '456789HY') // Kilolitros
                $conversion = $quantity / 1000000;
            else if ($unity_2 == 'JU76GF59') // Litros
                $conversion = $quantity / 1000;
            else if ($unity_2 == 'AXDE5TB2') // Mililitros
                $conversion = $quantity / 1;
            else if ($unity_2 == 'WDTG34CF') // Onza fluida
            {

            }
            else if ($unity_2 == '4FT5BQ7K') // Onza de peso
            {

            }
        }
        else if ($unity_1 == '098YH65W') // Miligramos
        {
            if ($unity_2 == 'HY763H8O') // Piezas
            {

            }
            else if ($unity_2 == 'MKJHTYIA') // Kilogramos
                $conversion = $quantity / 1000000;
            else if ($unity_2 == 'SHBJ9876') // Gramos
                $conversion = $quantity / 1000;
            else if ($unity_2 == '098YH65W') // Miligramos
                $conversion = $quantity / 1;
            else if ($unity_2 == '456789HY') // Kilolitros
            {

            }
            else if ($unity_2 == 'JU76GF59') // Litros
            {

            }
            else if ($unity_2 == 'AXDE5TB2') // Mililitros
            {

            }
            else if ($unity_2 == 'WDTG34CF') // Onza fluida
            {

            }
            else if ($unity_2 == '4FT5BQ7K') // Onza de peso
            {

            }
        }
        else if ($unity_1 == '456789HY') // Kilolitros
        {
            if ($unity_2 == 'HY763H8O') // Piezas
            {

            }
            else if ($unity_2 == 'MKJHTYIA') // Kilogramos
            {

            }
            else if ($unity_2 == 'SHBJ9876') // Gramos
            {

            }
            else if ($unity_2 == '098YH65W') // Miligramos
            {

            }
            else if ($unity_2 == '456789HY') // Kilolitros
                $conversion = $quantity / 1;
            else if ($unity_2 == 'JU76GF59') // Litros
                $conversion = $quantity * 1000;
            else if ($unity_2 == 'AXDE5TB2') // Mililitros
                $conversion = $quantity * 1000000;
            else if ($unity_2 == 'WDTG34CF') // Onza fluida
            {

            }
            else if ($unity_2 == '4FT5BQ7K') // Onza de peso
            {

            }
        }
        else if ($unity_1 == 'JU76GF59') // Litros
        {
            if ($unity_2 == 'HY763H8O') // Piezas
            {

            }
            else if ($unity_2 == 'MKJHTYIA') // Kilogramos
            {

            }
            else if ($unity_2 == 'SHBJ9876') // Gramos
            {

            }
            else if ($unity_2 == '098YH65W') // Miligramos
            {

            }
            else if ($unity_2 == '456789HY' AND $type == 'unity') // Kilolitros
                $conversion = $quantity / 1000;
            else if ($unity_2 == '456789HY' AND $type == 'cost') // Kilolitros
                $conversion = $quantity * 1000;
            else if ($unity_2 == 'JU76GF59' AND $type == 'unity') // Litros
                $conversion = $quantity / 1;
            else if ($unity_2 == 'JU76GF59' AND $type == 'cost') // Litros
                $conversion = $quantity * 1;
            else if ($unity_2 == 'AXDE5TB2') // Mililitros
                $conversion = $quantity * 1000;
            else if ($unity_2 == 'WDTG34CF') // Onza fluida
            {

            }
            else if ($unity_2 == '4FT5BQ7K') // Onza de peso
            {

            }
        }
        else if ($unity_1 == 'AXDE5TB2') // Mililitros
        {
            if ($unity_2 == 'HY763H8O') // Piezas
            {

            }
            else if ($unity_2 == 'MKJHTYIA') // Kilogramos
            {

            }
            else if ($unity_2 == 'SHBJ9876') // Gramos
            {

            }
            else if ($unity_2 == '098YH65W') // Miligramos
            {

            }
            else if ($unity_2 == '456789HY' AND $type == 'unity') // Kilolitros
                $conversion = $quantity / 1000000;
            else if ($unity_2 == '456789HY' AND $type == 'cost') // Kilolitros
                $conversion = $quantity * 1000000;
            else if ($unity_2 == 'JU76GF59' AND $type == 'unity') // Litros
                $conversion = $quantity / 1000;
            else if ($unity_2 == 'JU76GF59' AND $type == 'cost') // Litros
                $conversion = $quantity * 1000;
            else if ($unity_2 == 'AXDE5TB2' AND $type == 'unity') // Mililitros
                $conversion = $quantity / 1;
            else if ($unity_2 == 'AXDE5TB2' AND $type == 'cost') // Mililitros
                $conversion = $quantity * 1;
            else if ($unity_2 == 'WDTG34CF') // Onza fluida
            {

            }
            else if ($unity_2 == '4FT5BQ7K') // Onza de peso
            {

            }
        }
        else if ($unity_1 == 'WDTG34CF') // Onza fluida
        {

        }
        else if ($unity_1 == '4FT5BQ7K') // Onza de peso
        {

        }

        return $conversion;
    }

    static public function payments_ways()
    {
        $database = new Medoo();

        return System::decode_json_to_array($database->select('system_payments_ways', [
            'name',
            'code'
        ], [
            'blocked' => false,
            'ORDER' => [
                'order' => 'ASC'
            ]
        ]));
    }

    static public function countries()
    {
        $database = new Medoo();

        return System::decode_json_to_array($database->select('system_countries', [
            'name',
            'code',
            'lada'
        ], [
            'ORDER' => [
                'name' => 'ASC'
            ]
        ]));
    }

    static public function states($country)
    {
        $database = new Medoo();

        return System::decode_json_to_array($database->select('system_states', [
            'id',
            'name'
        ], [
            'country' => $country,
            'ORDER' => [
                'name' => 'ASC'
            ]
        ]));
    }

    static public function formulas()
    {
        $database = new Medoo();

        return System::decode_json_to_array($database->select('system_formulas', [
            'code',
            'name'
        ], [
            'blocked' => false,
            'ORDER' => [
                'order' => 'ASC'
            ]
        ]));
    }
}
