<?php

defined('_EXEC') or die;

class Urls_registered_vkye
{
    static public $home_page_default = '/';

    static public function urls()
    {
        return [
            '/' => [
                'controller' => 'Index',
                'method' => 'index'
            ],
            '/system' => [
                'controller' => 'System',
                'method' => 'index'
            ],
            '/login' => [
                'controller' => 'Login',
                'method' => 'index'
            ],
            '/dashboard' => [
                'controller' => 'Dashboard',
                'method' => 'index'
            ],
            '/inventories/movements' => [
                'controller' => 'Inventories',
                'method' => 'movements'
            ],
            '/inventories/audits' => [
                'controller' => 'Inventories',
                'method' => 'audits'
            ],
            '/inventories/audits/%param%' => [
                'controller' => 'Inventories',
                'method' => 'audits'
            ],
            '/inventories/audits/%param%/%param%' => [
                'controller' => 'Inventories',
                'method' => 'audits'
            ],
            '/inventories/periods' => [
                'controller' => 'Inventories',
                'method' => 'periods'
            ],
            '/inventories/periods/%param%' => [
                'controller' => 'Inventories',
                'method' => 'periods'
            ],
            '/inventories/periods/%param%/%param%' => [
                'controller' => 'Inventories',
                'method' => 'periods'
            ],
            '/inventories/types' => [
                'controller' => 'Inventories',
                'method' => 'types'
            ],
            '/inventories/locations' => [
                'controller' => 'Inventories',
                'method' => 'locations'
            ],
            '/inventories/categories' => [
                'controller' => 'Inventories',
                'method' => 'categories'
            ],
            '/products/%param%' => [
                'controller' => 'Products',
                'method' => 'index'
            ],
            '/products/categories' => [
                'controller' => 'Products',
                'method' => 'categories'
            ],
            '/products/unities' => [
                'controller' => 'Products',
                'method' => 'unities'
            ],
            '/products/contents' => [
                'controller' => 'Products',
                'method' => 'contents'
            ],
            '/providers' => [
                'controller' => 'Providers',
                'method' => 'index'
            ],
            '/branches' => [
                'controller' => 'Branches',
                'method' => 'index'
            ]
        ];
    }
}
