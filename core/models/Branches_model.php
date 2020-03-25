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
			'avatar' => Fileloader::up($data['avatar']),
			'name' => $data['name'],
			'email' => $data['email'],
			'phone' => json_encode([
                'country' => $data['phone_country'],
                'number' => $data['phone_number']
            ]),
			'country' => $data['country'],
			'address' => $data['address'],
			'fiscal' => json_encode([
                'id' => $data['fiscal_id'],
                'name' => $data['fiscal_name'],
                'country' => $data['fiscal_country'],
                'address' => $data['fiscal_address']
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
				'avatar' => Fileloader::up($data['avatar']),
				'name' => $data['name'],
				'email' => $data['email'],
				'phone' => json_encode([
	                'country' => $data['phone_country'],
	                'number' => $data['phone_number']
	            ]),
				'country' => $data['country'],
				'address' => $data['address'],
				'fiscal' => json_encode([
	                'id' => $data['fiscal_id'],
	                'name' => $data['fiscal_name'],
	                'country' => $data['fiscal_country'],
	                'address' => $data['fiscal_address']
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
