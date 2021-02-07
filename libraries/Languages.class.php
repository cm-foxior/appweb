<?php

defined('_EXEC') or die;

class Languages
{
    static public function email($key, $subkey = null)
    {
        $langs = [
            '' => [
                'es' => '',
                'en' => ''
            ]
        ];

        return !empty($subkey) ? $langs[$key][$subkey] : $langs[$key];
    }
}
