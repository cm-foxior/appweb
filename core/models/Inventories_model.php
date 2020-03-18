<?php

defined('_EXEC') or die;

class Inventories_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function read_inventories_types($slt = false)
	{
		if ($slt == true)
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

		$query = $this->database->select('inventories_types', [
			'id',
			'name',
			'movement',
			'blocked'
		], $where);

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

	public function read_inventories_locations($slt = false)
	{
		if ($slt == true)
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

		$query = $this->database->select('inventories_locations', [
			'id',
			'name',
			'blocked'
		], $where);

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

	public function read_inventories_categories($cbx = false)
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

		$query = $this->database->select('inventories_categories', [
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

	public function read_inventories_categories_levels()
	{
		$query = $this->database->select('inventories_categories', [
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
