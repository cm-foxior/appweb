<?php
defined('_EXEC') or die;

class Format
{
	private $security;

	public function __construct()
	{
		$this->security = new Security();
	}

	public static function getTimeZone()
	{
		date_default_timezone_set(Configuration::$timeZone);
	}

	public static function getDateHour()
	{
		self::getTimeZone();
		return date('Y-m-d h:i:s', time());
	}

	public static function getDate()
	{
		return date('Y-m-d');
	}

	public static function getTime()
	{
		self::getTimeZone();
		return date('h:i:s', time());
	}

	public function checkAdmin($urlAdmin, $url)
	{
		return ( $this->adminPath() === true ) ? $this->security->directorySeparator($urlAdmin) : $this->security->directorySeparator($url);
	}

	public function adminPath()
	{
		$cwd = $this->security->directorySeparator(getcwd());
		$pathAdministrator = $this->security->directorySeparator(PATH_ADMINISTRATOR);

		if($cwd === $pathAdministrator)
			return true;
		else
			return false;
	}

    public function imagesBase64($image)
    {
        $type = pathinfo($image, PATHINFO_EXTENSION);
        return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($image));
    }

	public function baseurl()
	{
		$uri = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        $uri = str_replace('index.php', '', $uri);

        $https = ( isset($_SERVER['HTTPS']) ) ? 's' : '';

        return 'http'. $https .'://'. $uri;
	}

    public static function elapsed($hour, $compare)
    {
        $start_date = new DateTime($hour);
        $since_start = $start_date->diff(new DateTime($compare));

        if($since_start->d == 1)
            return Language::getLang('{$lang.state_time}', 'System') . ' ' . $since_start->d . ' ' . Language::getLang('{$lang.day_ago}', 'System');
        if($since_start->d > 1)
            return Language::getLang('{$lang.state_time}', 'System') . ' ' . $since_start->d . ' ' . Language::getLang('{$lang.days_ago}', 'System');

        if($since_start->h == 1)
            return Language::getLang('{$lang.state_time}', 'System') . ' ' . $since_start->h . ' ' . Language::getLang('{$lang.hour_ago}', 'System');
        if($since_start->h > 1)
            return Language::getLang('{$lang.state_time}', 'System') . ' ' . $since_start->h . ' ' . Language::getLang('{$lang.hours_ago}', 'System');

        if($since_start->i == 1)
            return Language::getLang('{$lang.state_time}', 'System') . ' ' . $since_start->i . ' ' . Language::getLang('{$lang.minute_ago}', 'System');
        if($since_start->i > 1)
            return Language::getLang('{$lang.state_time}', 'System') . ' ' . $since_start->i . ' ' . Language::getLang('{$lang.minutes_ago}', 'System');

        if($since_start->s == 1)
            return Language::getLang('{$lang.state_time}', 'System') . ' ' . $since_start->s . ' ' . Language::getLang('{$lang.second_ago}', 'System');
        if($since_start->s > 1)
            return Language::getLang('{$lang.state_time}', 'System') . ' ' . $since_start->s . ' ' . Language::getLang('{$lang.seconds_ago}', 'System');
    }

	public static function existAjaxRequest()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
		    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
			return true;
		return false;
	}

	public static function dieAjax()
	{
		if (self::existAjaxRequest() == TRUE)
			die();
	}

	public function replace($arr, $string)
    {
        return str_replace(array_keys($arr), array_values($arr), $string);
    }

	public function replaceFile($data, $file, $path = false)
	{
		if($path === FALSE)
			$path = $this->checkAdmin(PATH_ADMINISTRATOR_LAYOUTS, PATH_LAYOUTS);

		$route = $path . $file . '.php';

		if(file_exists($route))
		{
			ob_start();

			require_once $route;

			$buffer = ob_get_contents();

			ob_end_clean();

			return str_replace('%{' . $file . '}%', $buffer, $data);
		}
	}

	public function getFile($file)
	{
		ob_start();

		require_once $file;

		$buffer = ob_get_contents();

		ob_end_clean();
		return $buffer;
	}

	public function fileInclude($path, $fileName, $fileType)
	{
		$supportedFileType = ['ini','php','html','json'];

		if (in_array($fileType, $supportedFileType))
		{
			$file = Security::directorySeparator($path . $fileName . '.' . $fileType);

			if (file_exists($file))
			{
				switch ($fileType)
				{
					case 'ini':
						return parse_ini_file($file, true);
						break;

					case 'php':
						require_once $file;
						break;

					case 'html':
						return $this->getFile($file);
						break;

					case 'json':
						return json_decode(file_get_contents($file), true);
						break;
				}
			}
		}
	}

	public function createImage( $src, $path = PATH_IMAGES, $width = false, $height = false, $thumb = false )
	{
		list($type, $src) = explode(';', $src);
		list(, $src)      = explode(',', $src);

		$src  = base64_decode($src);
		$name = $this->security->randomString(32) . '.png';
		$file = $path . $name;

		$success = file_put_contents($file, $src);

		if ($width != false && $height != false)
			$this->cutImage( $file, $width, $height );

		if ($thumb == true)
		{
			$thumb = $path . 'thumb_' . $name;
			$success = file_put_contents($thumb, $src);
			$this->cutImage( $thumb, 500, 500 );
		}

		return $name;
	}

	public function cutImage($path, $minWidthSize = 200, $maxHeightSize = 200)
	{
		$infoImage  = getimagesize($path);
		$sizeWidth  = $infoImage[0];
		$sizeHeight = $infoImage[1];
		$typeImage  = $infoImage['mime'];

		$imageProportion = $sizeWidth / $sizeHeight;
		$thumbProportion = $minWidthSize / $maxHeightSize;

		if ( $imageProportion > $thumbProportion )
		{
			$thumbWidth = $maxHeightSize * $imageProportion;
			$thumbHeight = $maxHeightSize;
		}
		else if ( $imageProportion < $thumbProportion )
		{
			$thumbWidth = $minWidthSize;
			$thumbHeight = $minWidthSize / $imageProportion;
		}
		else
		{
			$thumbWidth = $minWidthSize;
			$thumbHeight = $maxHeightSize;
		}

		$x = ( $thumbWidth - $minWidthSize ) / 2;
		$y = ( $thumbHeight - $maxHeightSize ) / 2;

		switch ( $typeImage )
		{
			case "image/jpg":
			case "image/jpeg":
				$image = imagecreatefromjpeg( $path );
				break;
			case "image/png":
				$image = imagecreatefrompng( $path );
				break;
			case "image/gif":
				$image = imagecreatefromgif( $path );
				break;
		}

		$canvas 	= imagecreatetruecolor( $minWidthSize, $maxHeightSize );
		$tmpCanvas 	= imagecreatetruecolor( $thumbWidth, $thumbHeight );

		imagecopyresampled($tmpCanvas, $image, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $sizeWidth, $sizeHeight);
		imagecopy($canvas, $tmpCanvas, 0,0, $x, $y, $minWidthSize, $maxHeightSize);

		imagejpeg($canvas, $path, 100);
	}
}
