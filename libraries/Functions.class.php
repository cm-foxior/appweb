<?php

defined('_EXEC') or die;

class Functions
{
    public static function temporal($option, $module, $key, $value = null)
    {
        $temporal = Session::get_value('vkye_temporal');

        if ($option == 'set_forced' OR $option == 'set_if_not_exist')
        {
            if ($option == 'set_forced')
                $temporal[$module][$key] = $value;
            else if ($option == 'set_if_not_exist')
            {
                if (!array_key_exists($key, $temporal[$module]) OR empty($temporal[$module][$key]))
                    $temporal[$module][$key] = $value;
            }

            Session::set_value('vkye_temporal', $temporal);
        }
        else if ($option == 'get')
            return array_key_exists($key, $temporal[$module]) ? $temporal[$module][$key] : [];
    }

    public static function summation($option, $data, $key)
    {
        if ($option == 'math' OR $option == 'count')
            $sum = 0;
        else if ($option == 'string')
            $sum = '';

        foreach ($data as $value)
        {
            if (isset($key) AND !empty($key))
            {
                if ($option == 'math')
                    $sum += $value[$key];
                else if ($option == 'count')
                {
                    foreach ($value as $subvalue)
                        $sum += 1;
                }
                else if ($option == 'string')
                    $sum .= $value[$key] . ', ';
            }
            else
            {
                if ($option == 'math')
                    $sum += $value;
                else if ($option == 'count')
                    $sum += 1;
                else if ($option == 'string')
                    $sum .= $value . ', ';
            }
        }

        if ($option == 'math' OR $option == 'count')
            return $sum;
        else if ($option == 'string')
            return substr($sum, 0, -2);
    }

    static public function payments_ways()
    {
        $database = new Medoo();

        return System::decode_json_to_array($database->select('system_payments_ways', [
            'name',
            'code'
        ], [
            'ORDER' => [
                'name' => 'ASC'
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
}
