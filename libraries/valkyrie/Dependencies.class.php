<?php
defined('_EXEC') or die;

class Dependencies
{
	private $status;
    private $render;

    public function __construct()
    {
        $this->render = new Render();
    }

	public function loadDependencies($data)
	{
		if(isset($this->status))
		{
			$css     = '';
			$js      = '';
			$other   = '';

			if (isset($this->status['css']))
			{
				foreach ($this->status['css'] as $link)
					$css .= '<link rel="stylesheet" href="' . $link . '" type="text/css" media="all" />';
			}

			if (isset($this->status['js']))
			{
				foreach ($this->status['js'] as $link)
					$js .= '<script src="' . $link . '"></script>';
			}

			if (isset($this->status['other']))
			{
				foreach ($this->status['other'] as $content)
					$other .= $content;
			}

			$replace = [
				'{$dependencies.css}'   => $css,
				'{$dependencies.js}'    => $js,
				'{$dependencies.other}' => $other
			];
		}
		else
		{
			$replace = [
                '{$dependencies.css}'   => '',
				'{$dependencies.js}'    => '',
				'{$dependencies.other}' => ''
            ];
		}

        return $this->render->replace($replace, $data);
	}

	public function getDependencies($array)
	{
		$this->status = $array;
	}

}
