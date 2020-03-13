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
            '/login' => [
                'controller' => 'Login',
                'method' => 'index'
            ],
            '/dashboard' => [
                'controller' => 'Dashboard',
                'method' => 'index'
            ],
            '/branches' => [
                'controller' => 'Branches',
                'method' => 'index'
            ],
            '/providers' => [
                'controller' => 'Providers',
                'method' => 'index'
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
            '/products/barcodes' => [
                'controller' => 'Products',
                'method' => 'barcodes'
            ]
        ];
    }
}
