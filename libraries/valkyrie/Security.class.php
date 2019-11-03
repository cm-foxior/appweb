<?php
defined('_EXEC') or die;

class Security
{
    public function __construct()
	{
	}

    public function cleanUrl($url = false)
    {
        if($url !== false)
        {
            $url = strtolower($url);

            $find   = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
            $repl   = array('a', 'e', 'i', 'o', 'u', 'n');
            $url    = str_replace($find, $repl, $url);

            $find   = array(' ', '&', '\r\n', '\n', '+');
            $url    = str_replace ($find, '-', $url);

            $find   = array('/[^a-z0-9\/-_<>]/', '/[\-]+/', '/<[^>]*>/');
            $repl   = array('', '-', '');
            $url    = preg_replace($find, $repl, $url);

            return $url;
        }
    }

    public static function directorySeparator($path)
    {
        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

    public static function protocol()
    {
        if(isset($_SERVER['HTTPS']))
            return 'https://';
        else
            return 'http://';
    }

    public static function checkEncoder($encoder, $string = false)
	{
		switch ($encoder)
		{
			case 'base64':
				if (base64_encode(base64_decode($string, true)) === $string)
					return true;
				else
					return false;
				break;

			case 'json':
				if (json_encode(json_decode($string, true)) === $string)
					return true;
				else
					return false;
				break;
		}
	}

    public function createHash($algorithm, $data)
	{
		$context = hash_init($algorithm, HASH_HMAC, Configuration::$secret);
		hash_update($context, $data);

		return hash_final($context);
	}

	public function createPassword($string)
	{
		$salt = $this->randomString(64);
		$password = $this->createHash('sha1', $string . $salt);
		return $password . ':' . $salt;
	}

    public function getIp()
	{
		if (isset($_SERVER["HTTP_CLIENT_IP"]))
            return $_SERVER["HTTP_CLIENT_IP"];
		elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
		elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
            return $_SERVER["HTTP_X_FORWARDED"];
		elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
            return $_SERVER["HTTP_FORWARDED_FOR"];
		elseif (isset($_SERVER["HTTP_FORWARDED"]))
            return $_SERVER["HTTP_FORWARDED"];
		else
            return $_SERVER["REMOTE_ADDR"];
	}

	public function browser()
	{
		$browser = array("IE", "OPERA", "MOZILLA", "NETSCAPE", "FIREFOX", "SAFARI", "CHROME");
		$os = array("WIN", "MAC", "LINUX");

		$info['browser'] = "OTHER";
		$info['os'] = "OTHER";

		foreach ($browser as $parent)
		{
			$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
			$f = $s + strlen($parent);
			$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
			$version = preg_replace('/[^0-9,.]/', '', $version);

			if ($s)
			{
				$info['browser'] = $parent;
				$info['version'] = $version;
			}
		}

		foreach ($os as $val)
        {
			if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $val) !== false)
			$info['os'] = $val;
		}

		return $info;
	}

	public static function checkMail($email)
	{
		return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? 1 : 0;
	}

    public static function checkIsFloat($number)
    {
        $explode = explode('.', $number);

        if (count($explode) > 1)
            return true;
        else
            return false;
    }

    public static function checkIfExistSpaces($string)
    {
        $explode = explode(' ', $string);

        if (count($explode) > 1)
            return true;
        else
            return false;
    }

    public function randomBytes($length = 16)
	{
		$sslStr = '';

		if (function_exists('openssl_random_pseudo_bytes') && (version_compare(PHP_VERSION, '5.3.4') >= 0 || IS_WIN))
		{
			$sslStr = openssl_random_pseudo_bytes($length, $strong);

			if ($strong)
				return $sslStr;
		}

		$bitsPerRound = 2;
		$maxTimeMicro = 400;
		$shaHashLength = 20;
		$randomStr = '';
		$total = $length;

		$urandom = false;
		$handle = null;

		if (function_exists('stream_set_read_buffer') && @is_readable('/dev/urandom'))
		{
			$handle = @fopen('/dev/urandom', 'rb');

			if ($handle)
				$urandom = true;
		}

		while ($length > strlen($randomStr))
		{
			$bytes = ($total > $shaHashLength) ? $shaHashLength : $total;
			$total -= $bytes;

			$entropy = rand() . uniqid(mt_rand(), true) . $sslStr;
			$entropy .= implode('', @fstat(fopen(__FILE__, 'r')));
			$entropy .= memory_get_usage();
			$sslStr = '';

			if ($urandom)
			{
				stream_set_read_buffer($handle, 0);
				$entropy .= @fread($handle, $bytes);
			}
			else
			{
				$samples = 3;
				$duration = 0;

				for ($pass = 0; $pass < $samples; ++$pass)
				{
					$microStart = microtime(true) * 1000000;
					$hash = sha1(mt_rand(), true);

					for ($count = 0; $count < 50; ++$count)
						$hash = sha1($hash, true);

					$microEnd = microtime(true) * 1000000;
					$entropy .= $microStart . $microEnd;

					if ($microStart > $microEnd)
						$microEnd += 1000000;

					$duration += $microEnd - $microStart;
				}

				$duration = $duration / $samples;

				$rounds = (int) (($maxTimeMicro / $duration) * 50);

				$iter = $bytes * (int) ceil(8 / $bitsPerRound);

				for ($pass = 0; $pass < $iter; ++$pass)
				{
					$microStart = microtime(true);
					$hash = sha1(mt_rand(), true);

					for ($count = 0; $count < $rounds; ++$count)
                        $hash = sha1($hash, true);

					$entropy .= $microStart . microtime(true);
				}
			}

			$randomStr .= sha1($entropy, true);
		}

		if ($urandom)
			@fclose($handle);

		return substr($randomStr, 0, $length);
	}

	public function randomString($length = 8)
	{
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$base = strlen($salt);
		$stringRandom = '';

		$random = $this->randomBytes($length + 1);
		$shift = ord($random[0]);

		for ($i = 1; $i <= $length; ++$i)
		{
			$stringRandom .= $salt[($shift + ord($random[$i])) % $base];
			$shift += ord($random[$i]);
		}

		return $stringRandom;
	}
}
