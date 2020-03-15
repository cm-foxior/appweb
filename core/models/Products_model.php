<?php

defined('_EXEC') or die;

class Products_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function read_products($type, $cbx = false)
	{
		$and['account'] = Session::get_value('vkye_account')['id'];
		$and['type'] = $type;

		if ($cbx == true)
			$and['blocked'] = false;

		$query = System::decoded_json_array($this->database->select('products', [
			'id',
			'avatar',
			'name',
			'token',
			'price',
			'blocked'
		], [
			'AND' => $and,
			'ORDER' => [
				'name' => 'ASC'
			]
		]));

		return $query;
	}

	public function read_product($id)
	{
		$query = System::decoded_json_array($this->database->select('products', [
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
			'avatar' => ($data['type'] == 'sale' AND !empty($data['avatar']['name'])) ? Uploader::up($data['avatar']) : null,
			'name' => $data['name'],
			'type' => $data['type'],
			'token' => ($data['type'] == 'sale' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['token'] : null,
			'price' => ($data['type'] == 'sale') ? $data['price'] : null,
			'unity' => ($data['type'] == 'sale' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['unity'] : null,
			'weight' => ($data['type'] == 'sale' OR $data['type'] == 'supply') ? json_encode([
				'empty' => !empty($data['weight_empty']) ? $data['weight_empty'] : '',
				'full' => !empty($data['weight_full']) ? $data['weight_full'] : ''
			]) : null,
			'recipes' => ($data['type'] == 'sale') ? json_encode((!empty($data['recipes']) ? $data['recipes'] : [])) : null,
			'supplies' => ($data['type'] == 'recipe') ? json_encode((!empty($data['supplies']) ? $data['supplies'] : [])) : null,
			'categories' => json_encode((!empty($data['categories']) ? $data['categories'] : [])),
			'blocked' => false
		]);

		return $query;
	}

	public function update_product($data)
	{
		$query = null;

        $edited = $this->database->select('products', [
            'avatar'
        ], [
            'id' => $data['id']
        ]);

        if (!empty($edited))
        {
            $query = $this->database->update('products', [
				'avatar' => ($data['type'] == 'sale' AND !empty($data['avatar']['name'])) ? Uploader::up($data['avatar']) : $edited[0]['avatar'],
				'name' => $data['name'],
				'type' => $data['type'],
				'token' => ($data['type'] == 'sale' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['token'] : null,
				'price' => ($data['type'] == 'sale') ? $data['price'] : null,
				'unity' => ($data['type'] == 'sale' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['unity'] : null,
				'weight' => ($data['type'] == 'sale' OR $data['type'] == 'supply') ? json_encode([
					'empty' => !empty($data['weight_empty']) ? $data['weight_empty'] : '',
					'full' => !empty($data['weight_full']) ? $data['weight_full'] : ''
				]) : null,
				'recipes' => ($data['type'] == 'sale') ? json_encode((!empty($data['recipes']) ? $data['recipes'] : [])) : null,
				'supplies' => ($data['type'] == 'recipe') ? json_encode((!empty($data['supplies']) ? $data['supplies'] : [])) : null,
				'categories' => json_encode((!empty($data['categories']) ? $data['categories'] : []))
            ], [
                'id' => $data['id']
            ]);

            if (!empty($query) AND !empty($data['avatar']['name']) AND !empty($edited[0]['avatar']))
                Uploader::down($edited[0]['avatar']);
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
            'avatar'
        ], [
            'id' => $id
        ]);

        if (!empty($deleted))
        {
            $query = $this->database->delete('products', [
                'id' => $id
            ]);

            if (!empty($query) AND !empty($deleted[0]['avatar']))
                Uploader::down($deleted[0]['avatar']);
        }

        return $query;
    }

	public function read_products_categories($cbx = false)
	{
		if ($cbx == true)
		{
			$where['AND'] = [
				'account' => Session::get_value('vkye_account')['id'],
				'blocked' => false
			];
		}
		else
			$where['account'] = Session::get_value('vkye_account')['id'];

		$where['ORDER'] = [
			'level' => 'ASC',
			'name' => 'ASC'
		];

		$query = $this->database->select('products_categories', [
			'id',
			'name',
			'level',
			'blocked'
		], $where);

		if ($cbx == true)
		{
			$cbx = [];

			foreach ($query as $key => $value)
			{
				if (array_key_exists($value['level'], $cbx))
					array_push($cbx[$value['level']], $value);
				else
					$cbx[$value['level']] = [$value];
			}

			return $cbx;
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

	public function read_products_categories_levels()
	{
		$query = $this->database->select('products_categories', [
			'level'
		], [
			'account' => Session::get_value('vkye_account')['id'],
			'ORDER' => [
				'level' => 'ASC'
			]
		]);

		$query = array_map('current', $query);
		$query = array_unique($query);
		$query = array_values($query);

		return $query;
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

	public function read_products_unities($slct = false)
	{
		if ($slct == true)
		{
			$where['AND'] = [
				'account' => Session::get_value('vkye_account')['id'],
				'blocked' => false
			];
		}
		else
			$where['account'] = Session::get_value('vkye_account')['id'];

		$where['ORDER'] = [
			'name' => 'ASC'
		];

		$query = $this->database->select('products_unities', [
			'id',
			'name',
			'blocked'
		], $where);

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
