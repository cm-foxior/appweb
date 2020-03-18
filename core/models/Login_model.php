<?php

defined('_EXEC') or die;

class Login_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_login($email)
	{
		$login['user'] = System::decoded_json_array($this->database->select('users', [
			'id',
			'avatar',
			'firstname',
			'lastname',
			'email',
			'password',
			'language',
			'accounts'
		], [
			'email' => $email
		]));

		if (!empty($login['user']))
		{
			foreach ($login['user'][0]['accounts'] as $key => $value)
			{
				$value['account'] = System::decoded_json_array($this->database->select('accounts', [
					'id',
					'avatar',
					'name',
					'type',
					'token',
					'email',
					'description',
					'website',
					'zip_code',
					'country',
					'city',
					'time_zone',
					'currency',
					'language',
					'fiscal',
					'work_team',
					'permissions',
					'settings',
					'status'
				], [
					'id' => $value['id']
				]));

				if (!empty($value['account']))
				{
					if ($value['account'][0]['type'] == 'business')
					{
						if ($value['permissions'] != 'all')
						{
							foreach ($value['permissions'] as $subkey => $subvalue)
							{
								$subvalue = $this->database->select('users_permissions', [
									'code'
								], [
									'id' => $subvalue
								]);

								if (!empty($subvalue))
									$value['permissions'][$subkey] = $subvalue[0]['code'];
								else
									unset($value['permissions'][$subkey]);
							}
						}

						$value['account'][0]['user']['permissions'] = $value['permissions'];
						$value['account'][0]['user']['branches'] = $value['branches'];
					}

					foreach ($value['account'][0]['permissions'] as $subkey => $subvalue)
					{
						$subvalue = $this->database->select('accounts_permissions', [
							'code'
						], [
							'id' => $subvalue
						]);

						if (!empty($subvalue))
							$value['account'][0]['permissions'][$subkey] = $subvalue[0]['code'];
						else
							unset($value['account'][0]['permissions'][$subkey]);
					}

					$login['user'][0]['accounts'][$key] = $value['account'][0];
				}
				else
					unset($login['user'][0]['accounts'][$key]);
			}

			$login['user'][0]['accounts'] = array_values($login['user'][0]['accounts']);

			if (!empty($login['user'][0]['accounts']))
			{
				if ($login['user'][0]['accounts'][0]['type'] == 'business')
				{
					$login['user'][0]['permissions'] = $login['user'][0]['accounts'][0]['user']['permissions'];
					$login['user'][0]['branches'] = $login['user'][0]['accounts'][0]['user']['branches'];
				}

				foreach ($login['user'][0]['accounts'] as $key => $value)
					unset($login['user'][0]['accounts'][$key]['user']);

				$login['account'] = $login['user'][0]['accounts'][0];
			}
			else
				$login['account'] = null;
			
			$login['user'] = $login['user'][0];

			return $login;
		}
		else
			return null;
	}
}
