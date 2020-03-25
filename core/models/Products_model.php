<?php

defined('_EXEC') or die;

class Products_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function read_products($type, $to_use = false)
	{
		$and['account'] = Session::get_value('vkye_account')['id'];
		$and['type'] = $type;

		if ($to_use == true)
		{
			$fields = [
				'id',
				'name'
			];

			$and['blocked'] = false;
		}
		else
		{
			$fields = [
				'id',
				'avatar',
				'name',
				'token',
				'price',
				'blocked'
			];
		}

		$query = System::decode_json_to_array($this->database->select('products', $fields, [
			'AND' => $and,
			'ORDER' => [
				'name' => 'ASC'
			]
		]));

		return $query;
	}

	public function read_product($id)
	{
		$query = System::decode_json_to_array($this->database->select('products', [
			'avatar',
			'name',
			'type',
			'token',
			'price',
			'unity',
			'weight',
			'recipes',
			'supplies',
			'categories',
			'inventory',
			'blocked'
		], [
			'id' => $id
		]));

		return !empty($query) ? $query[0] : null;
	}

	public function create_product($data)
	{
		$query = $this->database->insert('products', [
			'account' => Session::get_value('vkye_account')['id'],
			'avatar' => ($data['type'] == 'sale_menu') ? Fileloader::up($data['avatar']) : '',
			'name' => $data['name'],
			'type' => $data['type'],
			'token' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['token'] : '',
			'price' => ($data['type'] == 'sale_menu') ? $data['price'] : '',
			'unity' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['unity'] : '',
			'weight' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply') ? json_encode([
				'empty' => $data['weight_empty'],
				'full' => $data['weight_full']
			]) : '',
			'supplies' => ($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') ? json_encode((!empty($data['supplies']) ? $data['supplies'] : [])) : '',
			'recipes' => ($data['type'] == 'sale_menu') ? json_encode((!empty($data['recipes']) ? $data['recipes'] : [])) : '',
			'categories' => json_encode((!empty($data['categories']) ? $data['categories'] : [])),
			'inventory' => ($data['type'] == 'sale_menu' OR $data['type'] = 'supply' OR $data['type'] == 'work_material') ? (!empty($data['inventory']) ? true : false) : false,
			'blocked' => false
		]);

		return $query;
	}

	public function update_product($data)
	{
		$query = null;

		$edited = $this->database->select('products', [
			'avatar',
			'type'
		], [
			'id' => $data['id']
		]);

        if (!empty($edited))
        {
            $query = $this->database->update('products', [
				'avatar' => ($edited[0]['type'] == 'sale_menu') ? Fileloader::up($data['avatar']) : '',
				'name' => $data['name'],
				'token' => ($edited[0]['type'] == 'sale_menu' OR $edited[0]['type'] == 'supply' OR $edited[0]['type'] == 'work_material') ? $data['token'] : '',
				'price' => ($edited[0]['type'] == 'sale_menu') ? $data['price'] : '',
				'unity' => ($edited[0]['type'] == 'sale_menu' OR $edited[0]['type'] == 'supply' OR $edited[0]['type'] == 'work_material') ? $data['unity'] : '',
				'weight' => ($edited[0]['type'] == 'sale_menu' OR $edited[0]['type'] == 'supply') ? json_encode([
					'empty' => $data['weight_empty'],
					'full' => $data['weight_full']
				]) : '',
				'supplies' => ($edited[0]['type'] == 'sale_menu' OR $edited[0]['type'] == 'recipe') ? json_encode((!empty($data['supplies']) ? $data['supplies'] : [])) : '',
				'recipes' => ($edited[0]['type'] == 'sale_menu') ? json_encode((!empty($data['recipes']) ? $data['recipes'] : [])) : '',
				'categories' => json_encode((!empty($data['categories']) ? $data['categories'] : [])),
				'inventory' => ($edited[0]['type'] == 'sale_menu' OR $edited[0]['type'] = 'supply' OR $edited[0]['type'] == 'work_material') ? (!empty($data['inventory']) ? true : false) : false
            ], [
                'id' => $data['id']
            ]);

			if (!empty($query) AND $edited[0]['type'] == 'sale_menu' AND !empty($data['avatar']['name']) AND !empty($edited[0]['avatar']))
				Fileloader::down($edited[0]['avatar']);
        }

        return $query;
	}

	public function block_product($id)
	{
		$query = $this->database->update('products', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_product($id)
	{
		$query = $this->database->update('products', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_product($id)
    {
        $query = null;

        $deleted = $this->database->select('products', [
            'avatar',
			'type'
        ], [
            'id' => $id
        ]);

        if (!empty($deleted))
        {
            $query = $this->database->delete('products', [
                'id' => $id
            ]);

			if (!empty($query) AND $deleted[0]['type'] == 'sale_menu' AND !empty($deleted[0]['avatar']))
                Fileloader::down($deleted[0]['avatar']);
        }

        return $query;
    }

	public function read_products_categories($to_use = false)
	{
		if ($to_use == true)
		{
			$fields = [
				'id',
				'name',
				'level'
			];

			$where['AND'] = [
				'account' => Session::get_value('vkye_account')['id'],
				'blocked' => false
			];
		}
		else
		{
			$fields = [
				'id',
				'name',
				'level',
				'blocked'
			];

			$where['account'] = Session::get_value('vkye_account')['id'];
		}

		$where['ORDER'] = [
			'level' => 'ASC',
			'name' => 'ASC'
		];

		$query = $this->database->select('products_categories', $fields, $where);

		if ($to_use == true)
		{
			$return = [];

			foreach ($query as $key => $value)
			{
				if (array_key_exists($value['level'], $return))
					array_push($return[$value['level']], $value);
				else
					$return[$value['level']] = [$value];
			}

			return $return;
		}
		else
			return $query;
	}

	public function read_product_category($id)
	{
		$query = $this->database->select('products_categories', [
			'name',
			'level'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function create_product_category($data)
	{
		$query = $this->database->insert('products_categories', [
			'account' => Session::get_value('vkye_account')['id'],
			'name' => $data['name'],
			'level' => $data['level'],
			'blocked' => false
		]);

		return $query;
	}

	public function update_product_category($data)
	{
		$query = $this->database->update('products_categories', [
			'name' => $data['name'],
			'level' => $data['level']
		], [
			'id' => $data['id']
		]);

        return $query;
	}

	public function block_product_category($id)
	{
		$query = $this->database->update('products_categories', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_product_category($id)
	{
		$query = $this->database->update('products_categories', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_product_category($id)
    {
		$query = $this->database->delete('products_categories', [
			'id' => $id
		]);

        return $query;
    }

	public function read_products_unities($to_use = false)
	{
		if ($to_use == true)
		{
			$fields = [
				'id',
				'name'
			];

			$where['AND'] = [
				'account' => Session::get_value('vkye_account')['id'],
				'blocked' => false
			];
		}
		else
		{
			$fields = [
				'id',
				'name',
				'blocked'
			];

			$where['account'] = Session::get_value('vkye_account')['id'];
		}

		$where['ORDER'] = [
			'name' => 'ASC'
		];

		$query = $this->database->select('products_unities', $fields, $where);

		return $query;
	}

	public function read_product_unity($id)
	{
		$query = $this->database->select('products_unities', [
			'name'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function create_product_unity($data)
	{
		$query = $this->database->insert('products_unities', [
			'account' => Session::get_value('vkye_account')['id'],
			'name' => $data['name'],
			'blocked' => false
		]);

		return $query;
	}

	public function update_product_unity($data)
	{
		$query = $this->database->update('products_unities', [
			'name' => $data['name']
		], [
			'id' => $data['id']
		]);

        return $query;
	}

	public function block_product_unity($id)
	{
		$query = $this->database->update('products_unities', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_product_unity($id)
	{
		$query = $this->database->update('products_unities', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_product_unity($id)
    {
		$query = $this->database->delete('products_unities', [
			'id' => $id
		]);

        return $query;
    }
}
