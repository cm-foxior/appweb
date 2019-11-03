<?php

defined('_EXEC') or die;

class Search_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getProducts()
	{
		$query = $this->database->select('products', [
			'[>]products_categories_one' => ['id_product_category_one' => 'id_product_category_one'],
			'[>]products_categories_two' => ['id_product_category_two' => 'id_product_category_two'],
			'[>]products_categories_tree' => ['id_product_category_tree' => 'id_product_category_tree'],
			'[>]products_categories_four' => ['id_product_category_four' => 'id_product_category_four']
		], [
			'products.id_product',
			'products.name',
			'products.folio',
			'products_categories_one.name(category_one)',
			'products_categories_two.name(category_two)',
			'products_categories_tree.name(category_tree)',
			'products_categories_four.name(category_four)'
		], [
			'AND' => [
				'products.type' => '1',
				'products.status' => true,
				'products.id_subscription' => Session::getValue('id_subscription')
			]
		]);

		return $query;
	}

	public function getProductById($idProduct)
	{
		$query = $this->database->select('products', [
			'[>]warranties' => ['id_warranty' => 'id_warranty'],
			'[>]products_categories_one' => ['id_product_category_one' => 'id_product_category_one'],
			'[>]products_categories_two' => ['id_product_category_two' => 'id_product_category_two'],
			'[>]products_categories_tree' => ['id_product_category_tree' => 'id_product_category_tree'],
			'[>]products_categories_four' => ['id_product_category_four' => 'id_product_category_four']
		], [
			'products.id_product',
			'products.name',
			'products.folio',
			'products.price',
			'products.discount',
			'products.coin',
			'products.unity',
			'warranties.quantity',
			'warranties.time_frame',
			'products_categories_one.name(category_one)',
			'products_categories_two.name(category_two)',
			'products_categories_tree.name(category_tree)',
			'products_categories_four.name(category_four)',
			'products.observations'
		], [
			'products.id_product' => $idProduct
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function getExistence($idProduct)
	{
		$existence = [];

		$inventories = $this->database->select('inventories', [
			'[>]branch_offices' => [
				'id_branch_office' => 'id_branch_office'
			]
		], [
			'inventories.id_inventory',
			'inventories.name',
			'branch_offices.name(branch_office)'
		], [
			'AND' => [
				'inventories.status' => true,
				'inventories.id_subscription' => Session::getValue('id_subscription')
			]
		]);

		foreach ($inventories as $inventory)
		{
			$inputsTotal = 0;
			$outputsTotal = 0;
			$available = 0;

			$inputs = $this->database->select('inventories_inputs', [
				'quantify'
			], [
				'AND' => [
					'id_product' => $idProduct,
					'id_inventory' => $inventory['id_inventory']
				]
			]);

			if (!empty($inputs))
			{
				$outputs = $this->database->select('inventories_outputs', [
					'quantity'
				], [
					'AND' => [
						'id_product' => $idProduct,
						'id_inventory' => $inventory['id_inventory']
					]
				]);

				foreach ($inputs as $input)
					$inputsTotal = $inputsTotal + $input['quantify'];

				if (!empty($outputs))
				{
					foreach ($outputs as $output)
						$outputsTotal = $outputsTotal + $output['quantity'];
				}

				$available = $inputsTotal - $outputsTotal;

				array_push($existence, [
					'available' => $available,
					'inputs' => $inputsTotal,
					'outputs' => $outputsTotal,
					'inventory' => [
						'id_inventory' => $inventory['id_inventory'],
						'name' => $inventory['name'],
						'branch_office' => $inventory['branch_office']
					]
				]);
			}
		}

		return $existence;
	}

	public function getBranchOffices()
	{
		$query = $this->database->select('branch_offices', [
			'id_branch_office',
			'name'
		], [
			'AND' => [
				'status' => true,
				'id_subscription' => Session::getValue('id_subscription')
			]
		]);

		return $query;
	}

	public function getSettings()
    {
        $query = $this->database->select('settings', '*', ['id_subscription' => Session::getValue('id_subscription')]);
        return $query[0];
    }

	public function getUserLogged()
    {
        $query = $this->database->select('users', ['id_branch_office'], ['id_user' => $_SESSION['id_user']]);
        return !empty($query) ? $query[0] : '';
    }
}
