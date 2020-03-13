<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones opcionales para validaciones.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 01 de enero, 2019.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

class Validations
{
    /**
    * @summary: Valida que la variable este establecida y no vacía.
    *
    * @param string $data: Variable a validar.
    *
    * @return boolean
    */
    public static function empty($data, $group = false)
    {
        if ($group == true)
        {
            $check = true;
            $count = 0;

            foreach ($data as $value)
            {
                if (!isset($value) OR empty($value))
                {
                    $check = false;
                    $count = $count + 1;
                }
            }

            $check = ($count == count($data)) ? true : $check;
        }
        else
            $check = (isset($data) AND !empty($data)) ? true : false;

        return $check;
    }

    /**
    * @summary: Valida que la variable no contenga espacios vacíos.
    *
    * @param string $data: Variable a validar.
    * @param string $empty: Identificador si la variable puede regresar positivo si está vacía.
    *
    * @return boolean
    */
    public static function spaces($data, $empty = false)
    {
        $break = ($empty == true AND !isset($data) OR $empty == true AND empty($data)) ? true : false;
        $check = ($break == true) ? true : ((count(explode(' ', $data)) <= 1) ? true : false);

        return $check;
    }

    /**
    * @summary: Valida que la variable no contenga caracteres especiales.
    *
    * @param string $data: Variable a validar.
    * @param string $empty: Identificador si la variable puede regresar positivo si está vacía.
    *
    * @return boolean
    */
    public static function special_characters($data, $empty = false)
    {
        $break = ($empty == true AND !isset($data) OR $empty == true AND empty($data)) ? true : false;
        $check = true;

        if ($break == false)
        {
            $string = ' ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyz0123456789';

            for ($i = 0; $i < strlen($data); $i++)
            {
                if (strpos($string, substr($data, $i, 1)) == false)
                    $check = false;
            }
        }

        return $check;
    }

    /**
    * @summary: Valida que la variable sea un número entero o un número flotante.
    *
    * @param string $data: Variable a validar.
    * @param string $empty: Identificador si la variable puede regresar positivo si está vacía.
    *
    * @return boolean
    */
    public static function number($option, $data, $empty = false)
    {
        $break = ($empty == true AND !isset($data) OR $empty == true AND empty($data)) ? true : false;
        $check = true;

        if ($break == false)
        {
            if ($option == 'int')
                $data = (int) $data;
            else if ($option == 'float')
                $data = (float) $data;

            if (!is_numeric($data))
                $check = false;
            else if ($option == 'int' AND !is_int($data))
                $check = false;
            else if ($option == 'float' AND !is_float($data))
                $check = false;
            else if ($data < 1)
                $check = false;
        }

        return $check;
    }

    /**
    * @summary: Valida que un correo electrónico este escrito correctamente.
    *
    * @param string $email: Correo electrónico a validar.
    * @param string $empty: Identificador si la variable puede regresar positivo si está vacía.
    *
    * @return boolean
    */
    public static function email($data, $empty = false)
    {
        $break = ($empty == true AND !isset($data) OR $empty == true AND empty($data)) ? true : false;
        $check = ($break == true) ? true : ((filter_var($data, FILTER_VALIDATE_EMAIL)) ? true : false);

        return $check;
    }
}
