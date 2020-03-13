<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones para acceder a la información de sistema en el dashboard general.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 08 de marzo, 2020.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

class System
{
    /**
    * @summary Entrega los emails.
    *
    * @param string $key: Información a entregar.
    *
    * @return string
    */
    static public function emails($key)
    {
        $data = [
            'contact' => [
                'es' => 'contacto@codemonkey.com.mx',
                'en' => 'contact@codemonkey.com.mx'
            ],
            'support' => [
                'es' => 'soporte@codemonkey.com.mx',
                'en' => 'support@codemonkey.com.mx'
            ],
            'billing' => [
                'es' => 'facturación@codemonkey.com.mx',
                'en' => 'billing@codemonkey.com.mx'
            ]
        ];

        return $data[$key][Session::get_value('vkye_lang')];
    }

    /**
    * @summary Entrega las redes sociales.
    *
    * @param string $key: Información a entregar.
    *
    * @return string
    */
    static public function social_media($key)
    {
        $data = [

        ];

        return $data[$key];
    }

    /**
    * @summary Entrega la configuración SEO.
    *
    * @param string $key: Información a entregar.
    *
    * @return string
    */
    static public function seo($key)
    {
        $data = [
            'title' => [
                'index' => [
                    'es' => 'Inicio',
                    'en' => 'Home'
                ]
            ],
            'keywords' => [
                'index' => [
                    'es' => 'Lorem, ipsum, dolor, sit, amet.',
                    'en' => 'Lorem, ipsum, dolor, sit, amet.'
                ]
            ],
            'description' => [
                'index' => [
                    'es' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commo.',
                    'en' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commo.'
                ]
            ]
        ];

        return array_key_exists($GLOBALS['_vkye_module'], $data[$key]) ? $data[$key][$GLOBALS['_vkye_module']][Session::get_value('vkye_lang')] : '';
    }

    /**
    * @summary Entrega los países traidos desde la base de datos.
    *
    * @return array
    */
    static public function countries()
    {
        $database = new Medoo();

        return Functions::get_array_json_decoded($database->select('sys_countries', [
            'name',
            'code',
            'lada'
        ]));
    }
}
