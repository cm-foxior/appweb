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

	public function read_inventories($branch)
	{
		$query = $this->database->select('inventories', [
			'[>]inventories_types' => [
				'type' => 'id'
			],
			'[>]products' => [
				'product' => 'id'
			],
			'[>]products_unities' => [
				'products.unity' => 'id'
			],
			'[>]bills' => [
				'bill' => 'id'
			],
			'[>]remissions' => [
				'remission' => 'id'
			],
			'[>]inventories_locations' => [
				'location' => 'id'
			]
		], [
			'inventories.movement',
			'inventories_types.name(type)',
			'products.name(product_name)',
			'products_unities.name(product_unity)',
			'inventories.quantity',
			'inventories.date',
			'inventories.hour',
			'bills.token(bill)',
			'remissions.token(remission)',
			'inventories_locations.name(location)'
		], [
			'AND' => [
				'inventories.account' => Session::get_value('vkye_account')['id'],
				'inventories.branch' => $branch
			],
			'ORDER' => [
				'inventories.date' => 'DESC',
				'inventories.hour' => 'DESC'
			]
		]);

		return $query;
	}

	public function read_products()
	{
		$query = $this->database->select('products', [
			'[>]products_unities' => [
				'unity' => 'id'
			]
		], [
			'products.id',
			'products.avatar',
			'products.name',
			'products.type',
			'products.token',
			'products_unities.name(unity)'
		], [
			'AND' => [
				'products.account' => Session::get_value('vkye_account')['id'],
				'products.inventory' => true,
				'products.blocked' => false
			],
			'ORDER' => [
				'products.type' => 'ASC',
				'products.name' => 'ASC'
			]
		]);

		return $query;
	}

	public function create_inventory($data)
	{
		// foreach (Session::get_value('tmp')['products'] as $value)
		// {
		// 	$query = $this->database->insert('inventories', [
		// 		'account' => Session::get_value('vkye_account')['id'],
		// 		'branch' => Session::get_value('tmp')['branch']['id'],
		// 		'movement' => 'input',
		// 		'type' => $data['type'],
		// 		'product' => $value['product'],
		// 		'quantity' => $value['quantity'],
		// 		'date' => $data['date'],
		// 		'hour' => $data['hour'],
		// 		'price' => $value['price'],
		// 	]);
		// }
		//
		// return $query;
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
				'name'
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

		$query = $this->database->select('inventories_types', $fields, [
			'AND' => $and,
			'ORDER' => [
				'name' => 'ASC'
			]
		]);

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

		$query = $this->database->select('inventories_categories', $fields, $where);

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
