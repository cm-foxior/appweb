<?php

defined('_EXEC') or die;

class Functions
{
    public static function temporal($option, $module, $key, $value = null)
    {
        $tmp = (Session::exists_var('vkye_temporal') == true) ? Session::get_value('vkye_temporal') : [];

        if ($option == 'set_forced' OR $option == 'set_if_not_exist')
        {
            if ($option == 'set_forced')
                $tmp[$module][$key] = $value;
            else if ($option == 'set_if_not_exist')
            {
                if (!array_key_exists($key, $tmp[$module]) OR empty($tmp[$module][$key]))
                    $tmp[$module][$key] = $value;
            }

            Session::set_value('vkye_temporal', $tmp);
        }
        else if ($option == 'get')
            return array_key_exists($key, $tmp[$module]) ? $tmp[$module][$key] : [];
    }

    public static function summation($data, $key)
    {
        $sum = 0;

        foreach ($data as $value)
        {
            if (isset($key) AND !empty($key))
                $sum += $value[$key];
            else
                $sum += $value;
        }

        return $sum;
    }

    static public function countries()
    {
        $database = new Medoo();

        return System::decode_json_to_array($database->select('system_countries', [
            'name',
            'code',
            'lada'
        ]));
    }
}
