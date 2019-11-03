<?php
defined('_EXEC') or die;

class Route
{
    private $path;

    public function __construct( $path )
    {
        $this->path = $path;
    }

    public function onChangeStart()
    {
        $publicRoutes = [
            '/Index/index',
            '/Subscriptions/signin',
            '/Login/index'
        ];

        if ( !in_array($this->path, $publicRoutes) && Session::existsVar('session') != true )
            header('Location: /');

        if ( in_array($this->path, $publicRoutes) && Session::existsVar('session') == true )
            header('Location: /dashboard');
    }

    public function onChange()
    {
        if ( isset($_GET['session']) && !empty($_GET['session']) )
        {
            switch ( $_GET['session'] )
            {
                case 'logout':
                    Session::destroy();
                    header('Location: /');
                    break;
            }
        }
    }

    public function onChangeEnd()
    {
    }
}
