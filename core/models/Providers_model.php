<?php

defined('_EXEC') or die;

class Providers_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function read_providers()
	{
		$query = Functions::get_array_json_decoded($this->database->select('providers', [
			'id',
			'avatar',
			'name',
			'fiscal',
			'blocked'
		], [
            'account' => Session::get_value('vkye_account')['id'],
            'ORDER' => [
    			'name' => 'ASC'
    		]
        ]));

		return $query;
	}

	public function read_provider($id)
	{
		$query = Functions::get_array_json_decoded($this->database->select('providers', [
            'avatar',
			'name',
			'email',
			'phone',
            'country',
            'address',
			'fiscal'
		], [
			'id' => $id
		]));

		return !empty($query) ? $query[0] : null;
	}

	public function create_provider($data)
	{
		$query = $this->database->insert('providers', [
			'account' => Session::get_value('vkye_account')['id'],
			'avatar' => !empty($data['avatar']['name']) ? Functions::uploader($data['avatar']) : null,
			'name' => $data['name'],
			'email' => !empty($data['email']) ? $data['email'] : null,
			'phone' => json_encode([
                'country' => !empty($data['phone_country']) ? $data['phone_country'] : '',
                'number' => !empty($data['phone_number']) ? $data['phone_number'] : ''
            ]),
			'country' => !empty($data['country']) ? $data['country']: null,
			'address' => !empty($data['address']) ? $data['address']: null,
			'fiscal' => json_encode([
                'id' => !empty($data['fiscal_id']) ? strtoupper($data['fiscal_id']) : '',
                'name' => !empty($data['fiscal_name']) ? $data['fiscal_name'] : '',
                'country' => !empty($data['fiscal_country']) ? $data['fiscal_country'] : '',
                'address' => !empty($data['fiscal_address']) ? $data['fiscal_address'] : ''
            ]),
			'blocked' => false
		]);

		return $query;
	}

	public function update_provider($data)
	{
		$query = null;

        $edited = $this->database->select('providers', [
            'avatar'
        ], [
            'id' => $data['id']
        ]);

        if (!empty($edited))
        {
            $query = $this->database->update('providers', [
    			'avatar' => !empty($data['avatar']['name']) ? Functions::uploader($data['avatar']) : null,
    			'name' => $data['name'],
    			'email' => !empty($data['email']) ? $data['email'] : null,
    			'phone' => json_encode([
                    'country' => !empty($data['phone_country']) ? $data['phone_country'] : '',
                    'number' => !empty($data['phone_number']) ? $data['phone_number'] : ''
                ]),
    			'country' => !empty($data['country']) ? $data['country'] : null,
    			'address' => !empty($data['address']) ? $data['address'] : null,
    			'fiscal' => json_encode([
                    'id' => !empty($data['fiscal_id']) ? strtoupper($data['fiscal_id']) : '',
                    'name' => !empty($data['fiscal_name']) ? $data['fiscal_name'] : '',
                    'country' => !empty($data['fiscal_country']) ? $data['fiscal_country'] : '',
                    'address' => !empty($data['fiscal_address']) ? $data['fiscal_address'] : ''
                ])
            ], [
                'id' => $data['id']
            ]);

            if (!empty($query) AND !empty($data['avatar']['name']) AND !empty($edited[0]['avatar']))
                Functions::undoloader($edited[0]['avatar']);
        }

        return $query;
	}

	public function block_provider($id)
	{
		$query = $this->database->update('providers', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_provider($id)
	{
		$query = $this->database->update('providers', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_provider($id)
    {
        $query = null;

        $deleted = $this->database->select('providers', [
            'avatar'
        ], [
            'id' => $id
        ]);

        if (!empty($deleted))
        {
            $query = $this->database->delete('providers', [
                'id' => $id
            ]);

            if (!empty($query) AND !empty($deleted[0]['avatar']))
                Functions::undoloader($deleted[0]['avatar']);
        }

        return $query;
    }
}
