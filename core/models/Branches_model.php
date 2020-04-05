<?php

defined('_EXEC') or die;

class Branches_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function read_branches()
	{
		$query = System::decode_json_to_array($this->database->select('branches', [
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

	public function read_branch($id)
	{
		$query = System::decode_json_to_array($this->database->select('branches', [
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

	public function create_branch($data)
	{
		$query = $this->database->insert('branches', [
			'account' => Session::get_value('vkye_account')['id'],
			'avatar' => !empty($data['avatar']['name']) ? Fileloader::up($data['avatar']) : null,
			'name' => $data['name'],
			'email' => !empty($data['email']) ? $data['email'] : null,
			'phone' => json_encode([
                'country' => !empty($data['phone_country']) ? $data['phone_country'] : null,
                'number' => !empty($data['phone_number']) ? $data['phone_number'] : null
            ]),
			'country' => !empty($data['country']) ? $data['country'] : null,
			'address' => !empty($data['address']) ? $data['address'] : null,
			'fiscal' => json_encode([
                'id' => !empty($data['fiscal_id']) ? $data['fiscal_id'] : null,
                'name' => !empty($data['fiscal_name']) ? $data['fiscal_name'] : null,
                'country' => !empty($data['fiscal_country']) ? $data['fiscal_country'] : null,
                'address' => !empty($data['fiscal_address']) ? $data['fiscal_address'] : null
            ]),
			'blocked' => false
		]);

		return $query;
	}

	public function update_branch($data)
	{
		$query = null;

        $edited = $this->database->select('branches', [
            'avatar'
        ], [
            'id' => $data['id']
        ]);

        if (!empty($edited))
        {
            $query = $this->database->update('branches', [
				'avatar' => !empty($data['avatar']['name']) ? Fileloader::up($data['avatar']) : null,
				'name' => $data['name'],
				'email' => !empty($data['email']) ? $data['email'] : null,
				'phone' => json_encode([
	                'country' => !empty($data['phone_country']) ? $data['phone_country'] : null,
	                'number' => !empty($data['phone_number']) ? $data['phone_number'] : null
	            ]),
				'country' => !empty($data['country']) ? $data['country'] : null,
				'address' => !empty($data['address']) ? $data['address'] : null,
				'fiscal' => json_encode([
	                'id' => !empty($data['fiscal_id']) ? $data['fiscal_id'] : null,
	                'name' => !empty($data['fiscal_name']) ? $data['fiscal_name'] : null,
	                'country' => !empty($data['fiscal_country']) ? $data['fiscal_country'] : null,
	                'address' => !empty($data['fiscal_address']) ? $data['fiscal_address'] : null
	            ])
            ], [
                'id' => $data['id']
            ]);

            if (!empty($query) AND !empty($data['avatar']['name']) AND !empty($edited[0]['avatar']))
                Fileloader::down($edited[0]['avatar']);
        }

        return $query;
	}

	public function block_branch($id)
	{
		$query = $this->database->update('branches', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_branch($id)
	{
		$query = $this->database->update('branches', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_branch($id)
    {
        $query = null;

        $deleted = $this->database->select('branches', [
            'avatar'
        ], [
            'id' => $id
        ]);

        if (!empty($deleted))
        {
            $query = $this->database->delete('branches', [
                'id' => $id
            ]);

            if (!empty($query) AND !empty($deleted[0]['avatar']))
                Fileloader::down($deleted[0]['avatar']);
        }

        return $query;
    }
}