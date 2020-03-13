<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones opcionales para acceder a la información de sistema en el dashboard general.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 01 de enero, 2019.
* <@update> 08 de marzo, 2020.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

class System
{
    /**
    * @summary: Entrega una cadena de texto aleatoria.
    *
    * @param string $length: (<numbers>) Número de caracteres en el retornará la cadena.
    * @param string $letter: (uppercase, lowercase, allcase) Formato en el que retornará la cadena.
    *
    * @return string
    */
    public static function random($length = 8, $lettercase = 'allcase')
    {
        $security = new Security;

        if ($lettercase == 'uppercase')
            return strtoupper($security->random_string($length));
        else if ($lettercase == 'lowercase')
            return strtolower($security->random_string($length));
        else if ($lettercase == 'allcase')
            return $security->random_string($length);
    }

    /**
    * @summary: Entrega una cadena de texto encriptada bajo el estandar Vkye Password.
    *
    * @param string $string: Cadena a encriptar.
    *
    * @return string
    */
    public static function encrypted($string)
    {
        $security = new Security;

        return $security->create_password($string);
    }

    /**
    * @summary: Entrega una cadena de texto recortada.
    *
    * @param string $string: Cadena a recortar.
    * @param string $length: (<numbers>) Número de caracteres en el retornará la cadena.
    *
    * @return string
    */
    public static function shortened($string, $length = '400')
	{
		return (strlen(strip_tags($string)) > $length) ? substr(strip_tags($string), 0, $length) . '...' : substr(strip_tags($string), 0, $length);
    }

    /**
    * @summary: Entrega una cadena de limpia para colocar en una URL.
    *
    * @param string $string: Cadena a limpiar.
    *
    * @return string
    */
    public static function cleaned_url($string)
	{
		return strtolower(str_replace(' ', '', $string));
    }

    /**
    * @summary: Entrega un array unidimensional/multimensional json decodificado hasta 2 niveles.
    *
    * @param string-array $json: Array a decodificar.
    *
    * @return array
    */
    public static function decoded_query_array($json)
    {
        if (is_array($json))
        {
            foreach ($json as $key => $value)
            {
                if (is_array($json[$key]))
                {
                    foreach ($json[$key] as $subkey => $subvalue)
                        $json[$key][$subkey] = (is_array(json_decode($json[$key][$subkey], true)) AND (json_last_error() == JSON_ERROR_NONE)) ? json_decode($json[$key][$subkey], true) : $json[$key][$subkey];
                }
                else
                    $json[$key] = (is_array(json_decode($json[$key], true)) AND (json_last_error() == JSON_ERROR_NONE)) ? json_decode($json[$key], true) : $json[$key];
            }

            return $json;
        }
        else
            return (is_array(json_decode($json, true)) AND (json_last_error() == JSON_ERROR_NONE)) ? json_decode($json, true) : $json;
    }

    /**
    * @summary Entrega los países traidos desde la base de datos.
    *
    * @return array
    */
    static public function countries()
    {
        $database = new Medoo();

        return System::decoded_query_array($database->select('sys_countries', [
            'name',
            'code',
            'lada'
        ]));
    }

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
}
