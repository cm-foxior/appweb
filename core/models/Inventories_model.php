<?php

defined('_EXEC') or die;

class Inventories_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function read_branches()
	{
		$query = $this->database->select('branches', [
			'id',
			'name'
		], [
			'account' => Session::get_value('vkye_account')['id'],
			'ORDER' => [
				'name' => 'ASC'
			]
		]);

		if (Permissions::user(['view_branches']) == true)
		{
			foreach ($query as $key => $value)
			{
				if (Permissions::branch($value['id']) == false)
					unset($query[$key]);
			}

			$query = array_values($query);
		}

		return $query;
	}

	public function read_branch($id)
	{
		$query = $this->database->select('branches', [
			'id',
			'name'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function read_inventories()
	{
		$query = System::decode_json_to_array($this->database->select('inventories', [
			'[>]inventories_types' => [
				'type' => 'id'
			],
			'[>]products' => [
				'product' => 'id'
			],
			'[>]products_unities' => [
				'products.storage_unity' => 'id'
			],
			'[>]inventories_locations' => [
				'location' => 'id'
			],
			'[>]bills' => [
				'bill' => 'id'
			]
		], [
			'inventories.movement',
			'inventories_types.name(type_name)',
			'inventories_types.system(type_system)',
			'products.name(product_name)',
			'products_unities.name(product_storage_unity)',
			'inventories.storage_quantity',
			'inventories.date',
			'inventories.hour',
			'inventories_locations.name(location)',
			'bills.token(bill)'
		], [
			'AND' => [
				'inventories.account' => Session::get_value('vkye_account')['id'],
				'inventories.branch' => Functions::temporal('get', 'inventories', 'branch')['id']
			],
			'ORDER' => [
				'inventories.date' => 'DESC',
				'inventories.hour' => 'DESC'
			]
		]));

		return $query;
	}

	public function read_providers()
	{
		$query = $this->database->select('providers', [
			'id',
			'name'
		], [
			'AND' => [
				'account' => Session::get_value('vkye_account')['id'],
				'blocked' => false
			],
			'ORDER' => [
				'name' => 'ASC'
			]
		]);

		return $query;
	}

	public function read_products()
	{
		$query = $this->database->select('products', [
			'id',
			'avatar',
			'name',
			'type',
			'token'
		], [
			'AND' => [
				'account' => Session::get_value('vkye_account')['id'],
				'inventory' => true,
				'blocked' => false
			],
			'ORDER' => [
				'type' => 'ASC',
				'name' => 'ASC'
			]
		]);

		return $query;
	}

	public function read_product($id)
	{
		$query = $this->database->select('products', [
			'[>]products_unities(products_inputs_unities)' => [
				'input_unity' => 'id'
			],
			'[>]products_unities(products_storages_unities)' => [
				'storage_unity' => 'id'
			]
		], [
			'products.id',
			'products.name',
			'products.type',
			'products.token',
			'products_inputs_unities.name(input_unity)',
			'products_storages_unities.name(storage_unity)'
		], [
			'products.id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function create_inventory_input($data)
	{
		$go = false;

		if ($data['saved'] == 'bill')
		{
			$bill = $this->database->select('bills', [
				'id'
			], [
				'token' => $data['bill_token']
			]);

			if (!empty($bill))
			{
				$bill = $bill[0]['id'];
				$go = true;
			}
			else
			{
				$bill = $this->database->insert('bills', [
					'token' => $data['bill_token'],
					'payment' => json_encode([
						'way' => $data['bill_payment_way'],
						'method' => null
					])
				]);

				if (!empty($bill))
				{
					$bill = $this->database->id();
					$go = true;
				}
			}
		}
		else if ($data['saved'] == 'free')
			$go = true;


		if ($go == true)
		{
			foreach (Functions::temporal('get', 'inventories', 'inputs') as $value)
			{
				$query = $this->database->insert('inventories', [
					'account' => Session::get_value('vkye_account')['id'],
					'branch' => Functions::temporal('get', 'inventories', 'branch')['id'],
					'movement' => 'input',
					'type' => $data['type'],
					'product' => $value['product']['id'],
					'input_quantity' => !empty($value['product']['input_unity']) ? $value['input_quantity'] : null,
					'transform_quantity' => !empty($value['product']['input_unity']) ? $value['transform_quantity'] : null,
					'storage_quantity' => $value['storage_quantity'],
					'date' => $data['date'],
					'hour' => $data['hour'],
					'price' => $value['price'],
					'location' => $value['location']['id'],
					'categories' => json_encode((!empty($value['categories']) ? array_map('current', $value['categories']) : [])),
					'provider' => !empty($data['provider']) ? $data['provider'] : null,
					'bill' => ($data['saved'] == 'bill') ? $bill : null
				]);
			}
		}

		return $go;
	}

	public function read_inventories_types($to_use = false, $movement = '')
	{
		$and['OR'] = [
			'account' => Session::get_value('vkye_account')['id'],
			'system' => true
		];

		if ($to_use == true)
		{
			$fields = [
				'id',
				'name',
				'system'
			];

			$and['movement'] = $movement;
			$and['blocked'] = false;
		}
		else
		{
			$fields = [
				'id',
				'name',
				'movement',
				'system',
				'blocked'
			];
		}

		$query = System::decode_json_to_array($this->database->select('inventories_types', $fields, [
			'AND' => $and,
			'ORDER' => [
				'name' => 'ASC'
			]
		]));

		return $query;
	}

	public function read_inventory_type($id)
	{
		$query = $this->database->select('inventories_types', [
			'name',
			'movement'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function create_inventory_type($data)
	{
		$query = $this->database->insert('inventories_types', [
			'account' => Session::get_value('vkye_account')['id'],
			'name' => $data['name'],
			'movement' => $data['movement'],
			'system' => false,
			'blocked' => false
		]);

		return $query;
	}

	public function update_inventory_type($data)
	{
		$query = $this->database->update('inventories_types', [
			'name' => $data['name'],
			'movement' => $data['movement']
		], [
			'id' => $data['id']
		]);

        return $query;
	}

	public function block_inventory_type($id)
	{
		$query = $this->database->update('inventories_types', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_inventory_type($id)
	{
		$query = $this->database->update('inventories_types', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_inventory_type($id)
    {
		$query = $this->database->delete('inventories_types', [
			'id' => $id
		]);

        return $query;
    }

	public function read_inventories_locations($to_use = false)
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

		$query = $this->database->select('inventories_locations', $fields, $where);

		return $query;
	}

	public function read_inventory_location($id)
	{
		$query = $this->database->select('inventories_locations', [
			'id',
			'name'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function create_inventory_location($data)
	{
		$query = $this->database->insert('inventories_locations', [
			'account' => Session::get_value('vkye_account')['id'],
			'name' => $data['name'],
			'blocked' => false
		]);

		return $query;
	}

	public function update_inventory_location($data)
	{
		$query = $this->database->update('inventories_locations', [
			'name' => $data['name']
		], [
			'id' => $data['id']
		]);

        return $query;
	}

	public function block_inventory_location($id)
	{
		$query = $this->database->update('inventories_locations', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_inventory_location($id)
	{
		$query = $this->database->update('inventories_locations', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_inventory_location($id)
    {
		$query = $this->database->delete('inventories_locations', [
			'id' => $id
		]);

        return $query;
    }

	public function read_inventories_categories($to_use = false)
	{
		if (is_array($to_use) OR $to_use == true)
		{
			$fields = [
				'id',
				'name',
				'level'
			];

			if (is_array($to_use))
				$where['id'] = $to_use;
			else if ($to_use == true)
			{
				$where['AND'] = [
					'account' => Session::get_value('vkye_account')['id'],
					'blocked' => false
				];
			}
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

		$query = $this->database->select('inventories_categories', $fields, $where);

		if (is_array($to_use) OR $to_use == true)
		{
			if (is_array($to_use))
				return $query;
			else if ($to_use == true)
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
		}
		else
			return $query;
	}

	public function read_inventory_category($id)
	{
		$query = $this->database->select('inventories_categories', [
			'name',
			'level'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function create_inventory_category($data)
	{
		$query = $this->database->insert('inventories_categories', [
			'account' => Session::get_value('vkye_account')['id'],
			'name' => $data['name'],
			'level' => $data['level'],
			'blocked' => false
		]);

		return $query;
	}

	public function update_inventory_category($data)
	{
		$query = $this->database->update('inventories_categories', [
			'name' => $data['name'],
			'level' => $data['level']
		], [
			'id' => $data['id']
		]);

        return $query;
	}

	public function block_inventory_category($id)
	{
		$query = $this->database->update('inventories_categories', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_inventory_category($id)
	{
		$query = $this->database->update('inventories_categories', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_inventory_category($id)
    {
		$query = $this->database->delete('inventories_categories', [
			'id' => $id
		]);

        return $query;
    }
}
