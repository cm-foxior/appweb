<?php
defined('_EXEC') or die;

class Pager
{
    private $data;
    private $pagination;
    private $database;

    public function __construct()
    {
    	$this->data = array();
    	$this->pagination = array();
    	$this->database = new medoo();
        $this->page = 1;
    }

    public function pager($query, $page = false, $limit = false)
    {
    	if ($limit && is_numeric($limit))
    	    $limit = $limit;
    	else
    	    $limit = 10;

    	if ($page && is_numeric($page))
        {
    	    $this->page = $page;
    	    $initiation = ($this->page - 1) * $limit;
    	}
        else
            $initiation = 0;

        $rows = $this->database->query($query)->rowCount();
        $total = ceil($rows / $limit);
        $query = $query . " LIMIT " . $initiation . ", " . $limit;
        $this->data = $this->database->query($query)->fetchAll(PDO::FETCH_ASSOC);

    	$pager = array();
    	$pager['current'] = $this->page;
    	$pager['total'] = $total;

    	if ($this->page > 1)
        {
    	    $pager["first"] = 1;
    	    $pager["previous"] = $this->page - 1;
    	}
        else
        {
    	    $pager["first"] = "";
    	    $pager["previous"] = "";
    	}

        if ($this->page < $total)
        {
            $pager["last"] = $total;
            $pager["next"] = $this->page + 1;
        }
        else
        {
            $pager["next"] = "";
            $pager["last"] = "";
        }

        $this->pagination = $pager;

        return $this->data;
    }

    public function getPager()
    {
        if ($this->pagination)
            return $this->pagination;
        else
            return false;
    }

    public function getPagerStyle($names = array(), $classCss = false, $link = false)
    {
        $params = $this->getPager();

        $class = '';
    	if ($classCss != false)
    	    $class = ' class="' . $classCss . '"';

        $pagerStyle  = '<ul' . $class . '>';
        $pagerStyle .= '<li>';

        if ($params["first"])
            $pagerStyle .= '<a href="' . $link . $params["first"] . '">' . $names[0] . '</a>';
        else
            $pagerStyle .= '<a>' . $names[0] . '</a>';

        $pagerStyle .= '</li>';
        $pagerStyle .= '<li>';

        if ($params["previous"])
            $pagerStyle .= '<a href="' . $link . $params["previous"] . '">' . $names[1] . '</a>';
        else
            $pagerStyle .= '<a>' . $names[1] . '</a>';

        $pagerStyle .= '</li>';

        $startCountButtons = $this->page - 2;
        $endCountButtons = $this->page + 2;

        if ($startCountButtons < 1)
        {
            $startCountButtons = 1;
            $endCountButtons = 5;
        }

        if ($endCountButtons > $params['total'])
        {
            $startCountButtons = $params['total'] - 4;
            $endCountButtons = $params['total'];
        }

        if ($startCountButtons < 1)
            $startCountButtons = 1;

        for ($i = $startCountButtons; $i <= $endCountButtons; $i++)
        {
            if ($params['current'] != $i)
                $pagerStyle .= '<li><a href="' . $link . $i . '">' . $i . '</a></li>';
            else
               $pagerStyle .= '<li><a>' . $i . '</a></li>';
        }

        $pagerStyle .= '<li>';

    	if ($params["next"])
    	    $pagerStyle .= '<a href="' . $link . $params["next"] . '">' . $names[2] . '</a>';
    	else
    	    $pagerStyle .= '<a>' . $names[2] . '</a>';

        $pagerStyle .= '</li>';
        $pagerStyle .= '<li>';

        if ($params["last"])
            $pagerStyle .= '<a href="' . $link . $params["last"] . '">' . $names[3] . '</a>';
        else
            $pagerStyle .= '<a>' . $names[3] . '</a>';

        $pagerStyle .= '</li>';
        $pagerStyle .= '</ul>';

        return $pagerStyle;
    }

}
