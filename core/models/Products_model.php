<?php

defined('_EXEC') or die;

class Products_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function create_product($data)
	{
		$query = $this->database->insert('products', [
			'account' => Session::get_value('vkye_account')['id'],
			'type' => $data['type'],
			'avatar' => ($data['type'] == 'sale_menu' AND !empty($data['avatar']['name'])) ? Fileloader::up($data['avatar']) : null,
			'name' => $data['name'],
			'token' => $data['token'],
			'inventory' => (($data['type'] == 'sale_menu' AND $data['inventory'] == 'yes') OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? true : false,
			'unity' => (($data['type'] == 'sale_menu' AND $data['inventory'] == 'yes') OR $data['type'] == 'recipe' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['unity'] : null,
			'price' => ($data['type'] == 'sale_menu') ? $data['price'] : null,
			'portion' => ($data['type'] == 'recipe') ? $data['portion'] : null,
			'formula' => ($data['type'] == 'sale_menu' AND $data['inventory'] == 'not') ? json_encode([
				'code' => !empty($data['formula_code']) ? $data['formula_code'] : '',
				'parent' => !empty($data['formula_code']) ? $data['formula_parent'] : '',
				'quantity' => (!empty($data['formula_code']) AND $data['formula_code'] == 'SHG78K9H') ? $data['formula_quantity'] : ''
			]) : null,
			'contents' => (($data['type'] == 'sale_menu' AND $data['inventory'] == 'yes') OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? json_encode((!empty($data['contents']) ? $data['contents'] : [])) : null,
			'supplies' => ($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') ? json_encode((!empty($data['supplies']) ? $data['supplies'] : [])) : null,
			'categories' => json_encode((!empty($data['categories']) ? $data['categories'] : [])),
			'blocked' => false
		]);

		return $query;
	}

	public function read_products($type, $to_use = false)
	{
		$AND = [
			'products.account' => Session::get_value('vkye_account')['id'],
			'products.type' => $type
		];

		if ($to_use == 'parent')
			$AND['products.inventory'] = true;

		if ($to_use == true OR $to_use == 'parent')
			$AND['products.blocked'] = false;

		$query = System::decode_json_to_array($this->database->select('products', [
			'[>]products_unities(products_unities)' => [
				'unity' => 'id'
			]
		], [
			'products.id',
			'products.type',
			'products.avatar',
			'products.name',
			'products.token',
			'products.inventory',
			'products_unities.name(unity_name)',
			'products_unities.system(unity_system)',
			'products.price',
			'products.supplies',
			'products.categories',
			'products.blocked'
		], [
			'AND' => $AND,
			'ORDER' => [
				'products.name' => 'ASC'
			]
		]));

		foreach ($query as $key => $value)
		{
			$cost1 = 0;
			$cost2 = 0;

			if ($value['type'] == 'sale_menu' OR $value['type'] == 'supply' OR $value['type'] == 'workmaterial')
				$cost1 = $this->read_product_cost_average($value['id']);

			if ($value['type'] == 'sale_menu' OR $value['type'] == 'recipe')
			{
				if (!empty($value['supplies']))
				{
					foreach ($value['supplies'] as $subkey => $subvalue)
					{
						$cost = $this->read_product_cost_average($subkey);
						$cost = ($cost * $subvalue['quantity']);
						$cost2 += $cost;
					}
				}
			}

			$query[$key]['cost'] = ($cost1 + $cost2);

			if (!empty(System::temporal('get', 'products', 'categories')))
			{
				foreach (System::temporal('get', 'products', 'categories') as $subkey => $subvalue)
				{
					if (!in_array($subvalue, $value['categories']))
						unset($query[$key]);
				}
			}
		}

		return $query;
	}

	public function read_product($id)
	{
		$query = System::decode_json_to_array($this->database->select('products', [
			'[>]products_unities' => [
				'unity' => 'id'
			]
		], [
			'products.type',
			'products.avatar',
			'products.name',
			'products.token',
			'products.inventory',
			'products_unities.id(unity_id)',
			'products_unities.name(unity_name)',
			'products_unities.system(unity_system)',
			'products.price',
			'products.portion',
			'products.formula',
			'products.contents',
			'products.supplies',
			'products.categories',
			'products.blocked'
		], [
			'products.id' => $id
		]));

		if (!empty($query))
		{
			$cost1 = 0;
			$cost2 = 0;

			if ($query[0]['type'] == 'sale_menu' OR $query[0]['type'] == 'supply' OR $query[0]['type'] == 'workmaterial')
				$cost1 = $this->read_product_cost_average($id);

			if ($query[0]['type'] == 'sale_menu' OR $query[0]['type'] == 'recipe')
			{
				if (!empty($query[0]['supplies']))
				{
					foreach ($query[0]['supplies'] as $key => $value)
					{
						$cost = $this->read_product_cost_average($key);
						$cost = ($cost * $value['quantity']);
						$cost2 += $cost;
					}
				}
			}

			$query[0]['cost'] = ($cost1 + $cost2);

			if ($query[0]['inventory'] == false AND $query[0]['formula']['code'] == 'SHG78K9H')
			{
				$query[0]['formula']['parent'] = System::decode_json_to_array($this->database->select('products', [
					'[>]products_unities' => [
						'unity' => 'id'
					]
				], [
					'products.id',
					'products_unities.id(unity_id)',
					'products_unities.name(unity_name)',
					'products_unities.system(unity_system)'
				], [
					'products.id' => $query[0]['formula']['parent']
				]))[0];
			}

			return $query[0];
		}
		else
			return null;
	}

	public function read_product_cost_average($id)
	{
		$sum = 0;
		$division = 0;
		$average = 0;

		$query = $this->database->select('inventories', [
			'cost'
		], [
			'AND' => [
				'movement' => 'input',
				'product' => $id
			]
		]);

		foreach ($query as $value)
		{
			if ($value['cost'] > 0)
			{
				$sum += $value['cost'];
				$division += 1;
			}
		}

		if ($sum > 0 AND $division > 0)
			$average = ($sum / $division);

		return $average;
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
				'avatar' => ($data['type'] == 'sale_menu' AND !empty($data['avatar']['name'])) ? Fileloader::up($data['avatar']) : $edited[0]['avatar'],
				'name' => $data['name'],
				'token' => $data['token'],
				'inventory' => (($data['type'] == 'sale_menu' AND $data['inventory'] == 'yes') OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? true : false,
				'unity' => (($data['type'] == 'sale_menu' AND $data['inventory'] == 'yes') OR $data['type'] == 'recipe' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? $data['unity'] : null,
				'price' => ($data['type'] == 'sale_menu') ? $data['price'] : null,
				'portion' => ($data['type'] == 'recipe') ? $data['portion'] : null,
				'formula' => ($data['type'] == 'sale_menu' AND $data['inventory'] == 'not') ? json_encode([
					'code' => !empty($data['formula_code']) ? $data['formula_code'] : '',
					'parent' => !empty($data['formula_code']) ? $data['formula_parent'] : '',
					'quantity' => (!empty($data['formula_code']) AND $data['formula_code'] == 'SHG78K9H') ? $data['formula_quantity'] : ''
				]) : null,
				'contents' => (($data['type'] == 'sale_menu' AND $data['inventory'] == 'yes') OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? json_encode((!empty($data['contents']) ? $data['contents'] : [])) : null,
				'supplies' => ($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') ? json_encode((!empty($data['supplies']) ? $data['supplies'] : [])) : null,
				'categories' => json_encode((!empty($data['categories']) ? $data['categories'] : []))
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

	public function read_products_categories($type = null, $to_use = false)
	{
		$where = [];

		if ($to_use == true)
		{
			$where['AND'] = [
				'account' => Session::get_value('vkye_account')['id'],
				'blocked' => false
			];

			if (!empty($type))
				$where['AND'][$type] = true;
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
			'sale_menu',
			'supply',
			'recipe',
			'work_material',
			'blocked'
		], $where);

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

	public function create_product_unity($data)
	{
		$query = $this->database->insert('products_unities', [
			'account' => Session::get_value('vkye_account')['id'],
			'name' => $data['name'],
			'blocked' => false
		]);

		return $query;
	}

	public function read_products_unities($to_use = false)
	{
		$fields = [
			'id',
			'name',
			'system',
			'blocked'
		];

		$AND1 = [
			'system' => true
		];

		$AND2 = [
			'account' => Session::get_value('vkye_account')['id']
		];

		if ($to_use == true)
		{
			$AND1['blocked'] = false;
			$AND2['blocked'] = false;
		}

		$query1 = System::decode_json_to_array($this->database->select('products_unities', $fields, [
			'AND' => $AND1,
			'ORDER' => [
				'order' => 'ASC'
			]
		]));

		$query2 = System::decode_json_to_array($this->database->select('products_unities', $fields, [
			'AND' => $AND2,
			'ORDER' => [
				'name' => 'ASC'
			]
		]));

		return array_merge($query1, $query2);
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

	public function create_product_content($data)
	{
		$query = $this->database->insert('products_contents', [
			'account' => Session::get_value('vkye_account')['id'],
			'amount' => $data['amount'],
			'unity' => $data['unity'],
			'blocked' => false
		]);

		return $query;
	}

	public function read_products_contents($to_use = false)
	{
		$AND = [
			'products_contents.account' => Session::get_value('vkye_account')['id']
		];

		if ($to_use == true)
			$AND['products_contents.blocked'] = false;

		$query = System::decode_json_to_array($this->database->select('products_contents', [
			'[>]products_unities' => [
				'unity' => 'id'
			]
		], [
			'products_contents.id',
			'products_contents.amount',
			'products_unities.name(unity_name)',
			'products_unities.system(unity_system)',
			'products_contents.blocked'
		], [
			'AND' => $AND,
			'ORDER' => [
				'products_contents.amount' => 'ASC'
			]
		]));

		return $query;
	}

	public function read_product_content($id)
	{
		$query = $this->database->select('products_contents', [
			'amount',
			'unity'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function update_product_content($data)
	{
		$query = $this->database->update('products_contents', [
			'amount' => $data['amount'],
			'unity' => $data['unity']
		], [
			'id' => $data['id']
		]);

        return $query;
	}

	public function block_product_content($id)
	{
		$query = $this->database->update('products_contents', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_product_content($id)
	{
		$query = $this->database->update('products_contents', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_product_content($id)
    {
		$query = $this->database->delete('products_contents', [
			'id' => $id
		]);

        return $query;
    }
}
