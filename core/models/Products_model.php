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
		$inners = [];
		$and['products.account'] = Session::get_value('vkye_account')['id'];
		$and['products.type'] = $type;

		if ($to_use == true)
		{
			$fields = [
				'products.id',
				'products.name'
			];

			$and['products.blocked'] = false;
		}
		else
		{
			$inners = [
				'[>]products_unities(products_inputs_unities)' => [
					'input_unity' => 'id'
				],
				'[>]products_unities(products_storages_unities)' => [
					'storage_unity' => 'id'
				]
			];

			$fields = [
				'products.id',
				'products.avatar',
				'products.name',
				'products.token',
				'products_inputs_unities.name(input_unity)',
				'products_storages_unities.name(storage_unity)',
				'products.price',
				'products.blocked'
			];
		}

		$query = System::decode_json_to_array($this->database->select('products', $inners, $fields, [
			'AND' => $and,
			'ORDER' => [
				'products.name' => 'ASC'
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
			'input_unity',
			'storage_unity',
			'price',
			'gain_margin',
			'weight',
			'categories',
			'supplies',
			'recipes',
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
			'avatar' => ($data['type'] == 'sale_menu') ? (!empty($data['avatar']['name']) ? Fileloader::up($data['avatar']) : null) : null,
			'name' => $data['name'],
			'type' => $data['type'],
			'token' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['token'] : null,
			'input_unity' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? (!empty($data['input_unity']) ? $data['input_unity'] : null) : null,
			'storage_unity' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['storage_unity'] : null,
			'price' => ($data['type'] == 'sale_menu') ? $data['price'] : null,
			'gain_margin' => ($data['type'] == 'sale_menu') ? json_encode([
				'amount' => !empty($data['gain_margin_amount']) ? $data['gain_margin_amount'] : null,
				'type' => !empty($data['gain_margin_type']) ? $data['gain_margin_type'] : null
			]) : null,
			'weight' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply') ? json_encode([
				'full' => !empty($data['weight_full']) ? $data['weight_full'] : null,
				'empty' => !empty($data['weight_empty']) ? $data['weight_empty'] : null
			]) : null,
			'categories' => json_encode((!empty($data['categories']) ? $data['categories'] : [])),
			'supplies' => ($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') ? json_encode((!empty($data['supplies']) ? $data['supplies'] : [])) : null,
			'recipes' => ($data['type'] == 'sale_menu') ? json_encode((!empty($data['recipes']) ? $data['recipes'] : [])) : null,
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
				'avatar' => ($data['type'] == 'sale_menu') ? (!empty($data['avatar']['name']) ? Fileloader::up($data['avatar']) : null) : null,
				'name' => $data['name'],
				'token' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['token'] : null,
				'input_unity' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? (!empty($data['input_unity']) ? $data['input_unity'] : null) : null,
				'storage_unity' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['storage_unity'] : null,
				'price' => ($data['type'] == 'sale_menu') ? $data['price'] : null,
				'gain_margin' => ($data['type'] == 'sale_menu') ? json_encode([
					'amount' => !empty($data['gain_margin_amount']) ? $data['gain_margin_amount'] : null,
					'type' => !empty($data['gain_margin_type']) ? $data['gain_margin_type'] : null
				]) : null,
				'weight' => ($data['type'] == 'sale_menu' OR $data['type'] == 'supply') ? json_encode([
					'full' => !empty($data['weight_full']) ? $data['weight_full'] : null,
					'empty' => !empty($data['weight_empty']) ? $data['weight_empty'] : null
				]) : null,
				'categories' => json_encode((!empty($data['categories']) ? $data['categories'] : [])),
				'supplies' => ($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') ? json_encode((!empty($data['supplies']) ? $data['supplies'] : [])) : null,
				'recipes' => ($data['type'] == 'sale_menu') ? json_encode((!empty($data['recipes']) ? $data['recipes'] : [])) : null,
				'inventory' => ($data['type'] == 'sale_menu' OR $data['type'] = 'supply' OR $data['type'] == 'work_material') ? (!empty($data['inventory']) ? true : false) : false
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

	public function read_products_categories($to_use = false, $type = null)
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
				$type => true,
				'blocked' => false
			];
		}
		else
		{
			$fields = [
				'id',
				'name',
				'level',
				'sale_menu',
				'supply',
				'recipe',
				'work_material',
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
			'level',
			'sale_menu',
			'supply',
			'recipe',
			'work_material'
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
			'sale_menu' => !empty($data['sale_menu']) ? true : false,
			'supply' => !empty($data['supply']) ? true : false,
			'recipe' => !empty($data['recipe']) ? true : false,
			'work_material' => !empty($data['work_material']) ? true : false,
			'blocked' => false
		]);

		return $query;
	}

	public function update_product_category($data)
	{
		$query = $this->database->update('products_categories', [
			'name' => $data['name'],
			'level' => $data['level'],
			'sale_menu' => !empty($data['sale_menu']) ? true : false,
			'supply' => !empty($data['supply']) ? true : false,
			'recipe' => !empty($data['recipe']) ? true : false,
			'work_material' => !empty($data['work_material']) ? true : false
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
