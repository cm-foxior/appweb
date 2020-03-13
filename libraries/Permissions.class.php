<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones para revisar los permisos de acceso a los módulos y funcionalidades del dashboard general.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 08 de marzo, 2020.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

class Permissions
{
    /**
    * @summary Revisa los permisos de acceso de la url deseada deacuerdo a los permisos del usuario logueado ó de la cuenta en linea.
    *
    * @param string $path: Url deseada.
    *
    * @return boolean
    */
    static public function urls($path, $type)
    {
        $access = false;
        $paths = [];

        array_push($paths, '/Dashboard/index');

        if ($type == 'account')
        {
            if (!empty(Session::get_value('vkye_account')))
            {
                if (Session::get_value('vkye_account')['status'] == true)
                {
                    foreach (Session::get_value('vkye_account')['permissions'] as $key => $value)
                    {
                        switch ($value)
                        {
                            case 'accounting' :
                                break;

                            case 'billing' :
                                break;

                            case 'inventories' :
                                array_push($paths, '/Branches/index');
                                array_push($paths, '/Providers/index');
                                array_push($paths, '/Products/index');
                                array_push($paths, '/Products/categories');
                                array_push($paths, '/Products/unities');
                                array_push($paths, '/Products/barcodes');
                                break;

                            case 'sales' :
                                break;

                            case 'ecommerce' :
                                break;

                            default:
                                break;
                        }
                    }
                }
            }

            $paths = array_unique($paths);
            $paths = array_values($paths);
            $access = in_array($path, $paths) ? true : false;
        }
        else if ($type == 'user')
        {
            if (Session::get_value('vkye_account')['type'] == 'business')
            {
                if (Session::get_value('vkye_user')['permissions'] != 'all')
                {
                    foreach (Session::get_value('vkye_user')['permissions'] as $key => $value)
                    {
                        switch ($value)
                        {
                            case 'create_branches' :
                                array_push($paths, '/Branches/index');
                                break;

                            case 'update_branches' :
                                array_push($paths, '/Branches/index');
                                break;

                            case 'block_branches' :
                                array_push($paths, '/Branches/index');
                                break;

                            case 'unblock_branches' :
                                array_push($paths, '/Branches/index');
                                break;

                            case 'delete_branches' :
                                array_push($paths, '/Branches/index');
                                break;

                            case 'create_providers' :
                                array_push($paths, '/Providers/index');
                                break;

                            case 'update_providers' :
                                array_push($paths, '/Providers/index');
                                break;

                            case 'block_providers' :
                                array_push($paths, '/Providers/index');
                                break;

                            case 'unblock_providers' :
                                array_push($paths, '/Providers/index');
                                break;

                            case 'delete_providers' :
                                array_push($paths, '/Providers/index');
                                break;

                            case 'create_products' :
                                array_push($paths, '/Products/index');
                                break;

                            case 'update_products' :
                                array_push($paths, '/Products/index');
                                break;

                            case 'block_products' :
                                array_push($paths, '/Products/index');
                                break;

                            case 'unblock_products' :
                                array_push($paths, '/Products/index');
                                break;

                            case 'delete_products' :
                                array_push($paths, '/Products/index');
                                break;

                            case 'create_products_categories' :
                                array_push($paths, '/Products/categories');
                                break;

                            case 'update_products_categories' :
                                array_push($paths, '/Products/categories');
                                break;

                            case 'block_products_categories' :
                                array_push($paths, '/Products/categories');
                                break;

                            case 'unblock_products_categories' :
                                array_push($paths, '/Products/categories');
                                break;

                            case 'delete_products_categories' :
                                array_push($paths, '/Products/categories');
                                break;

                            case 'create_products_unities' :
                                array_push($paths, '/Products/unities');
                                break;

                            case 'update_products_unities' :
                                array_push($paths, '/Products/unities');
                                break;

                            case 'block_products_unities' :
                                array_push($paths, '/Products/unities');
                                break;

                            case 'unblock_products_unities' :
                                array_push($paths, '/Products/unities');
                                break;

                            case 'delete_products_unities' :
                                array_push($paths, '/Products/unities');
                                break;

                            case 'print_products_barcodes' :
                                array_push($paths, '/Products/barcodes');
                                break;

                            default:
                                break;
                        }
                    }

                    $paths = array_unique($paths);
                    $paths = array_values($paths);
                    $access = in_array($path, $paths) ? true : false;
                }
                else
                    $access = true;
            }
            else
                $access = true;
        }

        return $access;
    }

    /**
    * @summary Revisa los permisos de acceso de la cuenta en linea.
    *
    * @param array $permissions: Códigos de los permisos permitidos.
    *
    * @return boolean
    */
    static public function account($permissions)
    {
        $access = false;

        if (!empty(Session::get_value('vkye_account')))
        {
            foreach ($permissions as $value)
            {
                if (in_array($value, Session::get_value('vkye_account')['permissions']))
                    $access = true;
            }
        }

        return $access;
    }

    /**
    * @summary Revisa los permisos de acceso del usuario logueado.
    *
    * @param array $permissions: Códigos de los permisos permitidos.
    *
    * @return boolean
    */
    static public function user($permissions, $module = false)
    {
        $access = false;

        if (Session::get_value('vkye_account')['type'] == 'business')
        {
            if (Session::get_value('vkye_user')['permissions'] != 'all')
            {
                foreach ($permissions as $value)
                {
                    if ($module == true)
                    {
                        foreach (Session::get_value('vkye_user')['permissions'] as $subvalue)
                        {
                            $subvalue = explode('_', $subvalue, 2);

                            if ($value == $subvalue[1])
                                $access = true;
                        }
                    }
                    else
                    {
                        if (in_array($value, Session::get_value('vkye_user')['permissions']))
                            $access = true;
                    }
                }
            }
            else
                $access = true;
        }
        else
            $access = true;

        return $access;
    }

    /**
    * @summary Redirige a la url corresponidiente de acuerdo a los permisos de acceso del usuario logueado y la cuenta en linea.
    *
    * @return string
    */
    static public function redirection()
    {
        return '/dashboard';
    }
}
