<?php

defined('_EXEC') or die;

class Functions
{
    /**
    * @summary Agrega o entrega una llave en la variable de sesión tmp.
    *
    * @param string $option: (set, get) Identificador para saber si se va a ingresar o entregar el $key.
    * @param string $key: Llave que se va a ingresar o entregar.
    * @param string $value: Valor que se va a aplicar al $key en dado caso que el $option sea set.
    *
    * @return array
    * @return string
    * @return int
    * @return float
    */
    public static function session($option, $key, $value)
    {
        $tmp = (Session::exists_var('tmp') == true) ? Session::get_value('tmp') ? [];

        if ($option == 'set')
        {
            $tmp[$key] = $value;

            Session::set_value('tmp', $tmp);
        }
        else if ($option == 'get')
        {
            if ($key == 'branch')
                return array_key_exists('branch', $tmp) ? $tmp['branch'] : [];
            else if ($key == 'products')
                return array_key_exists('products', $tmp) ? $tmp['products'] : [];
        }
    }
}
