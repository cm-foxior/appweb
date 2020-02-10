<?php

defined('_EXEC') or die;

class Inventories_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Inventarios
	--------------------------------------------------------------------------- */
	public function getAllInventories()
	{
		$query = $this->database->select('inventories', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

	public function getAllInventoriesByBranchOffice($id)
	{
		$query = $this->database->select('inventories', '*', [
			'AND' => [
				'id_branch_office' => $id,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);
		return $query;
	}

	public function getAllInventoriesByBranchOffice2($id)
	{
		$query = $this->database->select('inventories', '*', [
			'AND' => [
				'status' => true,
				'id_branch_office' => $id,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);

		return $query;
	}

	public function getInventoryById($id)
	{
		$query = $this->database->select('inventories', [
			'[>]branch_offices' => ['id_branch_office' => 'id_branch_office']
		], [
			'inventories.id_inventory',
			'inventories.name',
			'inventories.type',
			'inventories.registration_date',
			'inventories.status',
			'inventories.id_branch_office',
			'branch_offices.name(branch_office)',
			'inventories.id_subscription',
		], [
			'id_inventory' => $id
		]);

		return !empty($query) ? $query[0] : '';
	}

	public function newInventory($name, $type, $branchOffice)
	{
        $today = date('Y-m-d');

        $query = $this->database->insert('inventories', [
            'name' => $name,
            'type' => $type,
            'registration_date' => $today,
            'id_branch_office' => $branchOffice,
			'id_subscription' => Session::getValue('id_subscription')
        ]);

        return $query;
	}

    public function editInventory($id, $name, $type, $branchOffice)
	{
        $query = $this->database->update('inventories', [
            'name' => $name,
            'type' => $type,
            'id_branch_office' => $branchOffice
        ], ['id_inventory' => $id]);

        return $query;
	}

	public function changeStatusInventories($selection, $status)
    {
		$query = $this->database->update('inventories', [
            'status' => $status
        ], ['id_inventory' => $selection]);

        return $query;
    }

	public function deleteInventories($selection)
    {
		$query = $this->database->delete('inventories', [
            'id_inventory' => $selection
        ]);

        return $query;
    }

	public function checkExistInventory($id, $name, $type, $branchOffice, $action)
	{
        $query = $this->database->select('inventories', '*', [
			'AND' => [
				'name' => $name,
				'type' => $type,
				'id_branch_office' => $branchOffice,
				'id_subscription' => Session::getValue('id_subscription')
			]
		]);

		return !empty($query) ? true : false;
	}

    /* Entradas al inventario
	--------------------------------------------------------------------------- */
    public function getAllInputs($id)
	{
		$query = $this->database->select('inventories_inputs', '*', [
			'AND' => [
				'id_inventory' => $id,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'input_date_time DESC'
		]);
		return $query;
	}

	public function getInputById($id)
	{
		$query = $this->database->select('inventories_inputs', '*', ['id_inventory_input' => $id]);
		return !empty($query) ? $query[0] : '';
	}

    public function newInput($product, $quantify, $type, $bill, $price, $payment, $provider, $datetime, $id)
	{
		if (!isset($datetime) OR empty($datetime))
			$datetime = Format::getDateHour();

        $query = $this->database->insert('inventories_inputs', [
            'quantify' => $quantify,
			'type' => $type,
            'input_date_time' => $datetime,
            'id_product' => $product,
            'id_provider' => $provider,
            'id_inventory' => $id,
            'id_inventory_transfer' => null,
			'bill' => !empty($bill) ? $bill : null,
            'price' => !empty($price) ? $price : null,
            'payment' => !empty($payment) ? $payment : null,
			'id_subscription' => Session::getValue('id_subscription')
        ]);

        return $query;
	}

    public function editInput($id, $product, $quantify, $type, $bill, $price, $payment, $provider, $datetime)
	{
		$query = $this->database->update('inventories_inputs', [
            'quantify' => $quantify,
			'type' => $type,
			'input_date_time' => $datetime,
            'id_product' => $product,
            'id_provider' => $provider,
			'bill' => !empty($bill) ? $bill : null,
			'price' => !empty($price) ? $price : null,
			'payment' => !empty($payment) ? $payment : null
        ], ['id_inventory_input' => $id]);

        return $query;
	}

	/* Salidas del inventario
	--------------------------------------------------------------------------- */
    public function getAllOutputs($id)
	{
		$query = $this->database->select('inventories_outputs', '*', [
			'AND' => [
				'id_inventory' => $id,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'output_date_time DESC'
		]);
		return $query;
	}

	public function getOutputById($id)
	{
		$query = $this->database->select('inventories_outputs', '*', ['id_inventory_output' => $id]);
		return !empty($query) ? $query[0] : '';
	}

    public function newOutput($product, $quantity, $type, $client, $datetime, $id)
	{
		$errors = [];
		$product = $this->database->select('products', '*', ['id_product' => $product]);

		if (!empty($product))
		{
			if (!isset($datetime) OR empty($datetime))
				$datetime = Format::getDateHour();

			if ($product[0]['flirt'] == true)
			{
				$flirts = $this->database->select('products_flirts', '*', ['id_product_1' => $product[0]['id_product']]);

				foreach ($flirts as $value)
				{
					$a1 = $quantity;
					$a2 = $value['stock_base'];
					$a3 = $value['stock_actual'];
					$a4 = $a3 + $a1;

					if ($a4 < $a2)
					{
						$a5 = $this->getProductById($value['id_product_2']);

						$query = $this->database->update('products_flirts', [
							'stock_actual' => $a4
						], [
							'id_product_flirt' => $value['id_product_flirt']
						]);

						if (empty($query))
							array_push($errors, $a5['name'] . ': No se pudo actualizar este stock actual.');
					}
					else if ($a4 >= $a2)
					{
						$a5 = $a4 / $a2;
						$a5 = explode('.', $a5);
						$a4 = $a4 - ($a2 * $a5[0]);
						$a6 = $this->checkExistInInventory($value['id_product_2'], $a5[0], $id, null, 'new');
						$a7 = $this->getProductById($value['id_product_2']);

						if ($a6['status'] == true)
						{
							if ($a6['errors']['errorNotExistInInventory'] == true)
								array_push($errors, $a7['name'] . ': No hay ninguna entrada al inventario con este producto');

							if ($a6['errors']['errorExceed'] == true)
								array_push($errors, $a7['name'] . ': Ha exedido la cantidad que existe en el inventario');
						}
						else
						{
							$query = $this->database->insert('inventories_outputs', [
					            'quantity' => $a5[0],
								'type' => $type,
								'output_date_time' => $datetime,
					            'id_product' => $value['id_product_2'],
					            'id_inventory' => $id,
					            'id_client' => ($type == '6') ? $client : null,
								'id_subscription' => Session::getValue('id_subscription')
					        ]);

							if (!empty($query))
							{
								$query = $this->database->update('products_flirts', [
									'stock_actual' => $a4
								], [
									'id_product_flirt' => $value['id_product_flirt']
								]);

								if (empty($query))
									array_push($errors, $a7['name'] . ': No se pudo actualizar este stock actual.');
							}
							else
								array_push($errors, $a7['name'] . ': No se pudo descontar este producto del inventario.');
						}
					}
				}
			}
			else
			{
				$query = $this->database->insert('inventories_outputs', [
					'quantity' => $quantity,
					'type' => $type,
					'output_date_time' => $datetime,
					'id_product' => $product[0]['id_product'],
					'id_inventory' => $id,
					'id_client' => ($type == '6') ? $client : null,
					'id_subscription' => Session::getValue('id_subscription')
				]);

				if (empty($query))
					array_push($errors, 'Error en la operación a la base de datos');
			}
		}
		else
			array_push($errors, 'Error en la operación a la base de datos');

		return $errors;
	}

	public function editOutput($id, $product, $quantity, $type, $client, $datetime)
	{
		$query = $this->database->update('inventories_outputs', [
            'quantity' => $quantity,
			'type' => $type,
			'output_date_time' => $datetime,
            'id_product' => $product,
			'id_client' => ($type == '6') ? $client : null
        ], ['id_inventory_output' => $id]);

        return $query;
	}

	public function transferProduct($product, $quantity, $idInventory, $idTransferInventory)
	{
		$today = Format::getDateHour();

        $query1 = $this->database->insert('inventories_outputs', [
            'quantity' => $quantity,
			'type' => '1',
			'output_date_time' => $today,
            'id_product' => $product,
            'id_inventory' => $idInventory,
            'id_inventory_transfer' => $idTransferInventory,
			'id_subscription' => Session::getValue('id_subscription')
        ]);

		if (!empty($query1))
		{
	        $query2 = $this->database->insert('inventories_inputs', [
	            'quantify' => $quantity,
				'type' => '2',
				'input_date_time' => $today,
	            'id_product' => $product,
	            'id_inventory' => $idTransferInventory,
				'id_inventory_transfer' => $idInventory,
				'id_subscription' => Session::getValue('id_subscription')
	        ]);

			return $query2;
		}
		else
        	return $query1;
	}

	public function checkExistInInventory($product, $quantity, $idInventory, $idOutput, $action)
	{
		$product = $this->database->select('products', '*', ['id_product' => $product]);

		if ($product[0]['flirt'] == false)
		{
			$errorNotExistInInventory = false;
			$errorExceed = false;

			$inputs = $this->database->select('inventories_inputs', '*', [
				'AND' => [
					'id_product' => $product[0]['id_product'],
					'id_inventory' => $idInventory
				]
			]);

			if (empty($inputs))
			{
				$errorNotExistInInventory = true;
			}
			else
			{
				$totalInputQuantity = 0;
				$totalOutputQuantity = 0;
				$totalQuantity = 0;

				$outputs = $this->database->select('inventories_outputs', '*', [
					'AND' => [
						'id_product' => $product[0]['id_product'],
						'id_inventory' => $idInventory
					]
				]);

				foreach ($inputs as $data)
					$totalInputQuantity = $totalInputQuantity + $data['quantify'];

				foreach ($outputs as $data)
				{
					if ($action == 'new')
						$totalOutputQuantity = $totalOutputQuantity + $data['quantity'];
					else if ($action == 'edit' AND $data['id_inventory_output'] != $idOutput)
						$totalOutputQuantity = $totalOutputQuantity + $data['quantity'];
				}

				$totalQuantity = $totalInputQuantity - $totalOutputQuantity;

				if ($quantity > $totalQuantity)
					$errorExceed = true;
			}

			if ($errorNotExistInInventory == true OR $errorExceed == true)
				return ['status' => true, 'errors' => ['errorNotExistInInventory' => $errorNotExistInInventory, 'errorExceed' => $errorExceed]];
			else
				return ['status' => false];
		}
		else
			return ['status' => false];
	}

	/* Stocks
    --------------------------------------------------------------------------- */
    public function getAllStocksByInventory($inventory)
    {
        $query = $this->database->select('inventories_stocks', '*', [
			'AND' => [
				'id_inventory' => $inventory,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'id_product ASC'
		]);
        return $query;
    }

    public function getStockById($id)
    {
        $query = $this->database->select('inventories_stocks', '*', ['id_inventory_stock' => $id]);
        return !empty($query) ? $query[0] : '';
    }

    public function newStock($min, $max, $product, $inventory)
    {
        $query = $this->database->insert('inventories_stocks', [
            'min' => $min,
            'max' => $max,
            'id_product' => $product,
            'id_inventory' => $inventory,
			'id_subscription' => Session::getValue('id_subscription')
        ]);

        return $query;
    }

    public function editStock($id, $min, $max, $product)
    {
        $query = $this->database->update('inventories_stocks', [
            'min' => $min,
            'max' => $max,
            'id_product' => $product
        ], ['id_inventory_stock' => $id]);

        return $query;
    }

    public function deleteStocks($selection)
    {
		$query = $this->database->delete('inventories_stocks', [
            'id_inventory_stock' => $selection
        ]);

        return $query;
    }

    public function checkExistStock($id, $product, $inventory, $action)
	{
		$query = $this->database->select('inventories_stocks', '*', [
            'AND' => [
                'id_product' => $product,
                'id_inventory' => $inventory
            ]
        ]);

		if ($action == 'new' AND !empty($query))
			return true;
		else if ($action == 'edit' AND !empty($query) AND $id != $query[0]['id_inventory_stock'])
			return true;
		else
			return false;
	}

	public function getCurrentStock($idProduct, $idInventory)
	{
		$totalInputs = 0;
		$totalOutputs = 0;
		$total = 0;

		$inputs = $this->database->select('inventories_inputs', '*', [
			'AND' => [
				'id_product' => $idProduct,
				'id_inventory' => $idInventory
			]
		]);

		$outputs = $this->database->select('inventories_outputs', '*', [
			'AND' => [
				'id_product' => $idProduct,
				'id_inventory' => $idInventory
			]
		]);

		foreach ($inputs as $input)
			$totalInputs = $totalInputs + $input['quantify'];

		foreach ($outputs as $output)
			$totalOutputs = $totalOutputs + $output['quantity'];

		$total = $totalInputs - $totalOutputs;

		return $total;
	}

	/*
	--------------------------------------------------------------------------- */
	public function getAllBranchOffices()
	{
		$query = $this->database->select('branch_offices', '*', [
			'AND' => [
				'status' => true,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);
		return $query;
	}

    public function getBranchOfficeById($id)
	{
		$query = $this->database->select('branch_offices', '*', ['id_branch_office' => $id]);
		return !empty($query) ? $query[0] : '';
	}

    public function getAllProducts($type)
	{
		if ($type == '2')
			$type = '3';
		else if ($type == '3')
			$type = '4';

		$query = $this->database->select('products', [
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
			'products.type',
			'products.components',
			'products.status',
			'products.avatar',
			'products.id_product_category_one',
			'products.id_product_category_two',
			'products.id_product_category_tree',
			'products.id_product_category_four',
			'products.observations',
			'products_categories_one.name(category_one)',
			'products_categories_two.name(category_two)',
			'products_categories_tree.name(category_tree)',
			'products_categories_four.name(category_four)',
		], [
			'AND' => [
				'products.type' => $type,
				'products.status' => true,
				'products.id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);

		return $query;
	}

    public function getProductById($id)
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
			'products.price',
			'products.discount',
			'products.coin',
			'products.unity',
			'products.type',
			'products.components',
			'products.status',
			'products.avatar',
			'products.id_product_category_one',
			'products.id_product_category_two',
			'products.id_product_category_tree',
			'products.id_product_category_four',
			'products.observations',
			'products_categories_one.name(category_one)',
			'products_categories_two.name(category_two)',
			'products_categories_tree.name(category_tree)',
			'products_categories_four.name(category_four)',
		], [
			'id_product' => $id
		]);

		return !empty($query) ? $query[0] : '';
	}

    public function getAllProviders()
	{
		$query = $this->database->select('providers', '*', [
			'AND' => [
				'status' => true,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);
		return $query;
	}

	public function getAllClients()
	{
		$query = $this->database->select('clients', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

    public function getProviderById($id)
	{
		$query = $this->database->select('providers', '*', ['id_provider' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function getUserLogged()
    {
        $query = $this->database->select('users', '*', ['id_user' => $_SESSION['id_user']]);
        return !empty($query) ? $query[0] : '';
    }
}
