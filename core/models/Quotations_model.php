<?php

defined('_EXEC') or die;

class Quotations_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllSales()
	{
		$query = $this->database->select('quotations', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'date_time DESC']);
		return $query;
	}

	public function getSaleById($id)
	{
		$query = $this->database->select('quotations', '*', ['id_quotation' => $id]);
        return !empty($query) ? $query[0] : '';
	}

	public function getAllSalesByBranchOffice($branchOffice)
	{
		$query = $this->database->select('quotations', '*', [
			'AND' => [
				'id_branch_office' => $branchOffice,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'date_time DESC'
		]);
		return $query;
	}

	public function getProductByFolio($folio)
    {
        $query = $this->database->select('products', '*', [
			'AND' => [
				'folio' => $folio,
				'type' => ['1','2'],
				'status' => true
			]
		]);

        return !empty($query) ? $query[0] : '';
    }

	public function getServiceByFolio($folio)
    {
        $query = $this->database->select('services', '*', [
			'AND' => [
				'folio' => $folio,
				'status' => true
			]
		]);

        return !empty($query) ? $query[0] : '';
    }

	public function getInventoryToDiscount($idProduct, $idBranchOffice)
	{
		$settings = $this->getAllSettings();
		$inventoriesConfig = json_decode($settings['sales'], true)['pdis'];

		$idInventory = '';

		foreach ($inventoriesConfig as $config)
		{
			if ($config[0] == $idProduct AND $config[2] == $idBranchOffice)
			{
				$exist = true;
				$idInventory = $config[1];
			}
		}

		return $idInventory;
	}

	public function newSale($ticketFolio, $totals, $deferred_payments_array, $sales, $ticketDate, $expiration, $user, $client, $branchOffice)
	{
		$query = $this->database->insert('quotations', [
			'folio' => $ticketFolio,
			'totals' => $totals,
			'deferred_payments' => $deferred_payments_array,
			'items' => $sales,
			'date_time' => $ticketDate,
			'date_expiration' => $expiration,
			'id_user' => $user,
			'id_client' => $client,
			'id_branch_office' => $branchOffice,
			'id_subscription' => Session::getValue('id_subscription')
		]);

		return $query;
	}

	public function checkExistIntoInventory($quantity, $idProduct, $idInventory)
	{
		$inventory = $this->getInventoryById($idInventory);

		if ($inventory['status'] == true)
		{
			$errorNotExistIntoInventory = false;
			$errorExceed = false;

			$inputs = $this->database->select('inventories_inputs', '*', [
				'AND' => [
					'id_product' => $idProduct,
					'id_inventory' => $idInventory
				]
			]);

			if (empty($inputs))
			{
				$errorNotExistIntoInventory = true;
			}
			else
			{
				$outputs = $this->database->select('inventories_outputs', '*', [
					'AND' => [
						'id_product' => $idProduct,
						'id_inventory' => $idInventory
					]
				]);

				$totalInputQuantity = 0;
				$totalOutputQuantity = 0;
				$totalQuantity = 0;

				foreach ($inputs as $data)
					$totalInputQuantity = $totalInputQuantity + $data['quantify'];

				foreach ($outputs as $data)
					$totalOutputQuantity = $totalOutputQuantity + $data['quantity'];

				$totalQuantity = $totalInputQuantity - $totalOutputQuantity;

				if ($quantity > $totalQuantity)
					$errorExceed = true;
			}

			if ($errorNotExistIntoInventory == true OR $errorExceed == true)
				return ['status' => false, 'errors' => ['errorNotExistIntoInventory' => $errorNotExistIntoInventory, 'errorExceed' => $errorExceed, 'errorInventoryDeactivate' => false]];
			else
				return ['status' => true];
		}
		else
			return ['status' => false, 'errors' => ['errorNotExistIntoInventory' => false, 'errorExceed' => false, 'errorInventoryDeactivate' => true]];
	}

	/*
	--------------------------------------------------------------------------- */
	public function getAllSettings()
    {
        $query = $this->database->select('settings', '*', ['id_subscription' => Session::getValue('id_subscription')]);
        return $query[0];
    }

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

	public function getWarrantyById($id)
    {
        $query = $this->database->select('warranties', '*', ['id_warranty' => $id]);
        return !empty($query) ? $query[0] : '';
    }

	public function getUserById($id)
    {
        $query = $this->database->select('users', '*', ['id_user' => $id]);
        return !empty($query) ? $query[0] : '';
    }

	public function getUserLogged()
    {
        $query = $this->database->select('users', '*', ['id_user' => $_SESSION['id_user']]);
        return !empty($query) ? $query[0] : '';
    }

	public function getAllUsersByLevel($level)
    {
        $query = $this->database->select('users', '*', [
			'AND' => [
				'level' => $level,
				'status' => true,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);

        return $query;
    }

	public function getAllProducts()
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
			'products.id_warranty',
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
				'products.type' => ['1','2'],
				'products.status' => true,
				'products.id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);

		return $query;
	}

	public function getAllServices()
	{
		$query = $this->database->select('services', [
			'[>]services_categories' => ['id_service_category' => 'id_service_category'],
		], [
			'services.id_service',
			'services.name',
			'services.folio',
			'services.price',
			'services.discount',
			'services.coin',
			'services.components',
			'services.status',
			'services.id_warranty',
			'services.id_service_category',
			'services.observations',
			'services_categories.name(category)',
		], [
			'AND' => [
				'services.status' => true,
				'services.id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);

		return $query;
	}

	public function getAllClients()
	{
		$query = $this->database->select('clients', '*', [
			'AND' => [
				'prospect' => false,
				'status' => true,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);

		return $query;
	}

	public function getClientById($id)
    {
        $query = $this->database->select('clients', '*', ['id_client' => $id]);
        return !empty($query) ? $query[0] : '';
    }

	public function sendEmail($email, $name, $subject, $message)
	{
		$this->component->loadComponent('phpmailer');

		send_email(
			[
				$email => $name
			],
			FALSE,
			FALSE,
			FALSE,
			FALSE,
			$subject,
			$message,
			FALSE
		);
	}
}
