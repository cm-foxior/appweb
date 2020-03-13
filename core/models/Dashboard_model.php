<?php

defined('_EXEC') or die;

class Dashboard_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_session($account)
	{
		$session['user'] = Functions::get_array_json_decoded($this->database->select('users', [
			'id',
			'avatar',
			'firstname',
			'lastname',
			'email',
			'password',
			'language',
			'accounts'
		], [
			'id' => Session::get_value('vkye_user')['id']
		]));

		foreach ($session['user'][0]['accounts'] as $key => $value)
		{
			$value['account'] = Functions::get_array_json_decoded($this->database->select('accounts', [
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

				$session['user'][0]['accounts'][$key] = $value['account'][0];
			}
			else
				unset($session['user'][0]['accounts'][$key]);
		}

		$session['user'][0]['accounts'] = array_values($session['user'][0]['accounts']);

		if (!empty($session['user'][0]['accounts']))
		{
			$bigkey = null;

			foreach ($session['user'][0]['accounts'] as $key => $value)
			{
				if ($account == $value['id'])
				{
					$bigkey = $key;

					if ($value['type'] == 'business')
					{
						$session['user'][0]['permissions'] = $value['user']['permissions'];
						$session['user'][0]['branches'] = $value['user']['branches'];
					}
				}

				unset($session['user'][0]['accounts'][$key]['user']);
			}

			if (isset($bigkey) AND $bigkey >= 0)
			{
				$session['account'] = $session['user'][0]['accounts'][$bigkey];
				$session['user'] = $session['user'][0];
			}
			else
				$session = null;
		}
		else
			$session = null;

		return $session;
	}

	public function xlsx()
	{
		$components = new Components;
		$components->load_component('simplexlsx');
        $xlsx = new SimpleXLSX(PATH_UPLOADS . 'products.xlsx');

        foreach ($xlsx->rows() as $value)
        {
			$this->database->insert('products', [
				'account' => Session::get_value('vkye_account')['id'],
				'avatar' => null,
				'name' => $value[0],
				'type' => 'sale',
				'token' => strtoupper(Functions::get_random_string(8)),
				'price' => $value[1],
				'unity' => 1,
				'weight' => json_encode([
					'empty' => '',
					'full' => ''
				]),
				'recipes' => json_encode([]),
				'supplies' => null,
				'categories' => json_encode([$value[2],$value[3]]),
				'blocked' => false
			]);
        }
	}
}
