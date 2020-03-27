<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones para revisar los permisos de acceso a los módulos y funcionalidades de sistema.
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
    * @summary Valida los permisos de acceso de la url deseada deacuerdo a los permisos del usuario logueado ó de la cuenta en linea.
    *
    * @param string $option: Tipo de permiso a validar.
    * @param string $path: Url a validar.
    *
    * @return boolean
    */
    static public function urls($option, $path)
    {
        $access = false;
        $paths = [];

        array_push($paths, '/Dashboard/index');

        if ($option == 'account')
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

                            case 'ebilling' :
                                break;

                            case 'inventories' :
                                array_push($paths, '/Inventories/index');
                                array_push($paths, '/Inventories/types');
                                array_push($paths, '/Inventories/locations');
                                array_push($paths, '/Inventories/categories');
                                array_push($paths, '/Products/index');
                                array_push($paths, '/Products/categories');
                                array_push($paths, '/Products/unities');
                                array_push($paths, '/Products/barcodes');
                                array_push($paths, '/Providers/index');
                                array_push($paths, '/Branches/index');
                                break;

                            case 'selling_point' :
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
        else if ($option == 'user')
        {
            if (Session::get_value('vkye_account')['type'] == 'business')
            {
                if (Session::get_value('vkye_user')['permissions'] != 'all')
                {
                    foreach (Session::get_value('vkye_user')['permissions'] as $key => $value)
                    {
                        switch ($value)
                        {
                            case 'input_inventories' :
                                array_push($paths, '/Inventories/index');
                                break;

                            case 'output_inventories' :
                                array_push($paths, '/Inventories/index');
                                break;

                            case 'transfer_inventories' :
                                array_push($paths, '/Inventories/index');
                                break;

                            case 'create_inventories_types' :
                                array_push($paths, '/Inventories/types');
                                break;

                            case 'update_inventories_types' :
                                array_push($paths, '/Inventories/types');
                                break;

                            case 'block_inventories_types' :
                                array_push($paths, '/Inventories/types');
                                break;

                            case 'unblock_inventories_types' :
                                array_push($paths, '/Inventories/types');
                                break;

                            case 'delete_inventories_types' :
                                array_push($paths, '/Inventories/types');
                                break;

                            case 'create_inventories_locations' :
                                array_push($paths, '/Inventories/locations');
                                break;

                            case 'update_inventories_locations' :
                                array_push($paths, '/Inventories/locations');
                                break;

                            case 'block_inventories_locations' :
                                array_push($paths, '/Inventories/locations');
                                break;

                            case 'unblock_inventories_locations' :
                                array_push($paths, '/Inventories/locations');
                                break;

                            case 'delete_inventories_locations' :
                                array_push($paths, '/Inventories/locations');
                                break;

                            case 'create_inventories_categories' :
                                array_push($paths, '/Inventories/categories');
                                break;

                            case 'update_inventories_categories' :
                                array_push($paths, '/Inventories/categories');
                                break;

                            case 'block_inventories_categories' :
                                array_push($paths, '/Inventories/categories');
                                break;

                            case 'unblock_inventories_categories' :
                                array_push($paths, '/Inventories/categories');
                                break;

                            case 'delete_inventories_categories' :
                                array_push($paths, '/Inventories/categories');
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
    * @param array $data: Códigos de los permisos permitidos.
    *
    * @return boolean
    */
    static public function account($data)
    {
        $access = false;

        if (!empty(Session::get_value('vkye_account')))
        {
            foreach ($data as $value)
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
    * @param array $data: Códigos de los permisos permitidos.
    * @param boolean $group: Identificador para saber si se van a validar un permiso único o un grupo de permisos.
    *
    * @return boolean
    */
    static public function user($data, $group = false)
    {
        $access = false;

        if (Session::get_value('vkye_account')['type'] == 'business')
        {
            if (Session::get_value('vkye_user')['permissions'] != 'all')
            {
                foreach ($data as $value)
                {
                    if ($group == true)
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
    * @summary Revisa los permisos de acceso del usuario a una sucursal.
    *
    * @param array $data: Id de la sucursal a revisar.
    *
    * @return boolean
    */
    static public function branch($id)
    {
        $access = false;

        if (Session::get_value('vkye_account')['type'] == 'business')
        {
            if (Session::get_value('vkye_user')['permissions'] != 'all')
            {
                if (in_array($id, Session::get_value('vkye_user')['branches']))
                    $access = true;
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
        if (empty(Session::get_value('vkye_user')['accounts']))
            return '/accounts';
        else if (Session::get_value('vkye_account')['status'] == false)
            return '/account/payment';
        else
            return '/dashboard';
    }
}
