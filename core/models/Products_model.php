<?php

defined('_EXEC') or die;

class Products_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Productos
	--------------------------------------------------------------------------- */
	public function getAllProducts()
	{
		$query = $this->database->select('products', '*', ['id_subscription' => Session::getValue('id_subscription'),'ORDER' => 'name ASC']);
		return $query;
	}

	public function getAllProductsIntoAllInventories($idBranchOffice, $searchDate = null)
	{
		$products = [];
		$currentAllInventories = [];

		if (isset($idBranchOffice) AND !empty($idBranchOffice))
		{
			$inventories = $this->database->select('inventories', '*', [
				'AND' => [
					'status' => true,
					'id_branch_office' => $idBranchOffice,
					'id_subscription' => Session::getValue('id_subscription'),
				]
			]);
		}
		else
			$inventories = $this->database->select('inventories', '*' , ['AND' => ['status' => true, 'id_subscription' => Session::getValue('id_subscription')]]);

		foreach ($inventories as $inventory)
		{
			if (isset($searchDate) AND !empty($searchDate))
			{
				$searchDate = $searchDate . ' 00:00:00';

				$allInputs = $this->database->select('inventories_inputs', '*', [
					'AND' => [
						'input_date_time[>=]' => $searchDate,
						'id_inventory' => $inventory['id_inventory']
					]
				]);
			}
			else
				$allInputs = $this->database->select('inventories_inputs', '*', ['id_inventory' => $inventory['id_inventory']]);

			$notRepeatedInputs = [];
			$currentInventory = [];

			foreach ($allInputs as $allInputsKey)
			{
				$repeated = false;

				foreach ($notRepeatedInputs as $notRepeatedInputsKey)
				{
					if ($allInputsKey['id_product'] == $notRepeatedInputsKey['id_product'])
						$repeated = true;
				}

				if ($repeated == false)
					array_push($notRepeatedInputs, $allInputsKey);
			}

			foreach ($notRepeatedInputs as $notRepeatedInputsKey)
			{
				$product = $this->database->select('products', '*', ['id_product' => $notRepeatedInputsKey['id_product']]);

				$totalCurrentInputs = 0;
				$totalCurrentOutputs = 0;
				$totalCurrent = 0;

				$currentInputs = $this->database->select('inventories_inputs', '*', [
					'AND' => [
						'id_product' => $notRepeatedInputsKey['id_product'],
						'id_inventory' => $inventory['id_inventory']
					]
				]);

				$currentOutputs = $this->database->select('inventories_outputs', '*', [
					'AND' => [
						'id_product' => $notRepeatedInputsKey['id_product'],
						'id_inventory' => $inventory['id_inventory']
					]
				]);

				foreach ($currentInputs as $currentInputsKey)
					$totalCurrentInputs = $totalCurrentInputs + $currentInputsKey['quantify'];

				foreach ($currentOutputs as $currentOutputsKey)
					$totalCurrentOutputs = $totalCurrentOutputs + $currentOutputsKey['quantity'];

				$totalCurrent = $totalCurrentInputs - $totalCurrentOutputs;

				$current = [
					'folio' => $product[0]['folio'],
					'price' => $product[0]['price'],
					// 'inputs' => $totalCurrentInputs,
					// 'outputs' => $totalCurrentOutputs,
					'exists' => $totalCurrent
				];

				array_push($currentInventory, $current);
			}

			array_push($currentAllInventories, $currentInventory);
		}

		foreach ($currentAllInventories as $currentAllInventoriesKey)
		{
			foreach ($currentAllInventoriesKey as $product)
				array_push($products, $product);
		}

		return $products;
	}

	public function getProductById($id)
	{
		$query = $this->database->select('products', '*', ['id_product' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function getProductByFolio($folio)
	{
		$query = $this->database->select('products', ['folio', 'price'], ['folio' => $folio]);
		return !empty($query) ? $query[0] : '';
	}

	public function newProduct($name, $folio, $type, $components, $price, $discount, $coin, $unity, $avatar, $category_one, $category_two, $category_tree, $category_four, $observations)
	{
		if (isset($avatar))
		{
			$this->component->loadComponent('uploader');

			$_com_uploader = new Upload;
			$_com_uploader->SetFileName($avatar['name']);
			$_com_uploader->SetTempName($avatar['tmp_name']);
			$_com_uploader->SetFileType($avatar['type']);
			$_com_uploader->SetFileSize($avatar['size']);
			$_com_uploader->SetUploadDirectory(PATH_IMAGES . 'products');
			$_com_uploader->SetValidExtensions(['jpg', 'jpeg', 'png']);
			$_com_uploader->SetMaximumFileSize('unlimited');

			$avatar = $_com_uploader->UploadFile();
		}

		if ($avatar['status'] == 'success' OR !isset($avatar))
		{
			if ($avatar['status'] == 'success')
			{
				$this->format->cutImage($avatar['route'], 1920, 1080, 60);
				$avatar = $avatar['file'];
			}

			$query = $this->database->insert('products', [
				'name' => $name,
				'folio' => $folio,
				'price' => $price,
				'discount' => $discount,
				'coin' => $coin,
				'unity' => $unity,
				'type' => $type,
				'components' => $components,
				'avatar' => $avatar,
				'id_product_category_one' => $category_one,
				'id_product_category_two' => $category_two,
				'id_product_category_tree' => $category_tree,
				'id_product_category_four' => $category_four,
				'observations' => $observations,
				'id_subscription' => Session::getValue('id_subscription')
			]);

			return $query;
		}
		else
			return null;
	}

	public function importFromExcel($xlsx)
	{
		$this->component->loadComponent('uploader');

		$_com_uploader = new Upload;
		$_com_uploader->SetFileName($xlsx['name']);
		$_com_uploader->SetTempName($xlsx['tmp_name']);
		$_com_uploader->SetFileType('application/xlsx');
		$_com_uploader->SetFileSize($xlsx['size']);
		$_com_uploader->SetUploadDirectory(PATH_UPLOADS . 'xlsx');
		$_com_uploader->SetValidExtensions(['xlsx', 'vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
		$_com_uploader->SetMaximumFileSize('unlimited');

		$xlsx = $_com_uploader->UploadFile();

		if ($xlsx['status'] == 'success')
		{
	        $this->component->loadComponent('simplexlsx');

			$xlsx = new SimpleXLSX(PATH_UPLOADS . 'xlsx/' . $xlsx['file']);

			$inserts = [];
			$fullErrors1 = [];
			$fullErrors2 = [];
			// $xlsxRow = 1;

			foreach ($xlsx->rows() as $value)
			{
				$errors = [];

				// if (empty($value[0]))
				// 	array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El nombre no puede estár vacío']);
				//
				// if (empty($value[1]))
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El email no puede estár vacío']);
	            // else if (Security::checkMail($value[1]) == false)
				// 	array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El formato del email es incorrecto']);
				//
				// if (empty($value[2]))
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. La clave del país no puede estár vacía']);
	            // else if (!is_numeric($value[2]))
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. La clave del país tiene que ser números']);
	            // else if ($value[2] < 0)
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. La clave del país no puede ser menor a 0']);
	            // else if (Security::checkIsFloat($value[2]) == true)
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. La clave del país no puede contener números decimáles']);
				// else if (Security::checkIfExistSpaces($value[2]) == true)
				// 	array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. La clave del país no puede contener espacios']);
				//
				// if (empty($value[3]))
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico no puede estár vacío']);
	            // else if (!is_numeric($value[3]))
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico tiene que ser números']);
	            // else if ($value[3] < 0)
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico no puede ser menor a 0']);
	            // else if ($value[4] == 'Móvil' AND strlen($value[3]) != 10)
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico tiene que ser de 10 dígitos']);
	            // else if ($value[4] == 'Local' AND strlen($value[3]) != 7)
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico tiene que ser de 7 dígitos']);
	            // else if (Security::checkIsFloat($value[3]) == true)
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico no puede contener números decimáles']);
				// else if (Security::checkIfExistSpaces($value[3]) == true)
				// 	array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico no puede contener espacios']);
				//
				// if (empty($value[4]))
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El tipo de teléfono no puede quedar vacío']);
	            // else if ($value[4] != 'Local' AND $value[4] != 'Móvil')
	            //     array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El tipo de teléfono solo puede ser Local o Móvil']);

				if (!empty($errors))
					array_push($fullErrors1, $errors);
				else
				{
					// $exist = $this->checkExistProspect(null, $value[1], $phoneNumber, 'new');
					//
					// if ($exist['status'] == true)
					// 	array_push($fullErrors1, [['xlsx', 'Linea ' . $xlsxRow . '. Este registro ya ha sido ingresado previamente']]);
					// else
					// 	array_push($inserts, ['name' => $value[0], 'email' => $value[1], 'phone_number' => $phoneNumber, 'address' => $value[5]]);

					array_push($inserts, [
						'name' => $value[0]
					]);

					// array_push($inserts, [
					// 	'name' => $value[1],
					// 	'folio' => $value[0],
					// 	'price' => !empty($value[5]) ? $value[5] : 0,
					// 	'unity' => $value[2],
					// 	'id_product_category_one' => $value[3],
					// 	'id_product_category_two' => $value[4]
					// ]);
				}

				// $xlsxRow = $xlsxRow + 1;
			}

			if (!empty($fullErrors1))
			{
				foreach ($fullErrors1 as $fullErrors)
				{
					foreach ($fullErrors as $error)
						array_push($fullErrors2, $error);
				}

				return ['status' => 'error', 'errors' => $fullErrors2];
			}
			else
			{
				foreach ($inserts as $insert)
				{
					$query = $this->database->insert('products_categories_two', [
						'name' => $insert['name'],
						'id_subscription' => 9
					]);

					// $query = $this->database->insert('products', [
					// 	'name' => $insert['name'],
					// 	'folio' => $insert['folio'],
					// 	'price' => json_encode([
					// 		'base_price' => $insert['price'],
					// 		'pref_price' => 0,
					// 		'public_price' => 0
					// 	]),
					// 	'discount' => null,
					// 	'coin' => 1,
					// 	'unity' => $insert['unity'],
					// 	'type' => 3,
					// 	'components' => null,
					// 	'status' => 1,
					// 	'avatar' => null,
					// 	'id_product_category_one' => $insert['id_product_category_one'],
					// 	'id_product_category_two' => $insert['id_product_category_two'],
					// 	'id_product_category_tree' => null,
					// 	'id_product_category_four' => null,
					// 	'observations' => null,
					// 	'to_ecommerce' => 0,
					// 	'id_subscription' => 9
					// ]);
				}

				return ['status' => 'success'];
			}
		}
		else
			return ['status' => 'error', 'errors' => [['xlsx', 'No se pudo subir el XLSX adecuadamente']]];
	}

	public function editProduct($id, $name, $folio, $type, $components, $price, $discount, $coin, $unity, $avatar, $category_one, $category_two, $category_tree, $category_four, $observations)
	{
		if (isset($avatar))
		{
			$this->component->loadComponent('uploader');

			$_com_uploader = new Upload;
			$_com_uploader->SetFileName($avatar['name']);
			$_com_uploader->SetTempName($avatar['tmp_name']);
			$_com_uploader->SetFileType($avatar['type']);
			$_com_uploader->SetFileSize($avatar['size']);
			$_com_uploader->SetUploadDirectory(PATH_IMAGES . 'products');
			$_com_uploader->SetValidExtensions(['jpg', 'jpeg', 'png']);
			$_com_uploader->SetMaximumFileSize('unlimited');

			$avatar = $_com_uploader->UploadFile();
		}

		if (!isset($avatar) OR $avatar['status'] == 'success')
		{
			if (!isset($avatar))
			{
				$product = $this->database->select('products', '*', ['id_product' => $id]);
				$avatar  = !empty($product[0]['avatar']) ? $product[0]['avatar'] : null;
			}
			else if ($avatar['status'] == 'success')
			{
				$this->format->cutImage($avatar['route'], 1920, 1080, 60);
				$avatar = $avatar['file'];
			}

			$query = $this->database->update('products', [
				'name' => $name,
				'folio' => $folio,
				'price' => $price,
				'discount' => $discount,
				'coin' => $coin,
				'unity' => $unity,
				'type' => $type,
				'components' => $components,
				'avatar' => $avatar,
				'id_product_category_one' => $category_one,
				'id_product_category_two' => $category_two,
				'id_product_category_tree' => $category_tree,
				'id_product_category_four' => $category_four,
				'observations' => $observations
			], ['id_product' => $id]);

			return $query;
		}
		else
			return null;
	}

	public function changeStatusProducts($selection, $status)
    {
		$query = $this->database->update('products', [
            'status' => $status
        ], ['id_product' => $selection]);

        return $query;
    }

	public function deleteProducts($selection)
    {
		$query = $this->database->delete('products', [
            'id_product' => $selection
        ]);

        return $query;
    }

	public function postProductsToEcommerce($selection)
    {
		$query = $this->database->update('products', [
            'to_ecommerce' => true
        ], [
			'id_product' => $selection
		]);

        return $query;
    }

	public function unpostProductsToEcommerce($selection)
    {
		$query = $this->database->update('products', [
            'to_ecommerce' => false
        ], [
			'id_product' => $selection
		]);

        return $query;
    }

	public function checkExistProduct($id, $name, $category_one, $category_two, $category_tree, $category_four, $folio, $type, $action)
	{
		$query1 = $this->database->select('products', '*', [
			'AND' => [
				'name' => $name,
				'type' => $type,
				'id_product_category_one' => $category_one,
				'id_product_category_two' => $category_two,
				'id_product_category_tree' => $category_tree,
				'id_product_category_four' => $category_four,
				'id_subscription' => Session::getValue('id_subscription')
			],
		]);

		$query2 = $this->database->select('products', '*', [
			'AND' => [
				'folio' => $folio,
				'id_subscription' => Session::getValue('id_subscription')
			],
		]);

		$query = array_merge($query1, $query2);

		if (!empty($query))
		{
			$errorFolio = false;
			$errorName = false;

			foreach ($query as $data)
			{
				if ($action == 'new' AND $folio == $data['folio'])
					$errorFolio = true;
				else if ($action == 'edit' AND $folio == $data['folio'] AND $id != $data['id_product'])
					$errorFolio = true;

				if ($action == 'new' AND $name == $data['name'] AND $category_one == $data['id_product_category_one'] AND $category_two == $data['id_product_category_two'] AND $category_tree == $data['id_product_category_tree'] AND $category_four == $data['id_product_category_four'] AND $type == $data['type'])
					$errorName = true;
				else if ($action == 'edit' AND $name == $data['name'] AND $category_one == $data['id_product_category_one'] AND $category_two == $data['id_product_category_two'] AND $category_tree == $data['id_product_category_tree'] AND $category_four == $data['id_product_category_four'] AND $type == $data['type'] AND $id != $data['id_product'])
					$errorName = true;
			}

			if ($errorFolio == true OR $errorName == true)
			{
				return [
					'status' => true,
					'errors' => [
						'errorFolio' => $errorFolio,
						'errorName' => $errorName
					]
				];
			}
			else
				return ['status' => false];
		}
		else
			return ['status' => false];
	}

	/* Components
	--------------------------------------------------------------------------- */
	public function getAllComponents()
	{
		$query = $this->database->select('products', [
			'[>]products_categories_one' => ['id_product_category_one' => 'id_product_category_one'],
			'[>]products_categories_two' => ['id_product_category_two' => 'id_product_category_two'],
			'[>]products_categories_tree' => ['id_product_category_tree' => 'id_product_category_tree'],
			'[>]products_categories_four' => ['id_product_category_four' => 'id_product_category_four'],
		], [
			'products.id_product',
			'products.folio',
			'products.name',
			'products_categories_one.name(category_one)',
			'products_categories_two.name(category_two)',
			'products_categories_tree.name(category_tree)',
			'products_categories_four.name(category_four)',
		], [
			'AND' => [
				'products.type' => '3',
				'products.id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'products.name ASC'
		]);

		return $query;
	}

	public function newComponent($id, $components)
	{
		$query = $this->database->update('products', [
			'components' => $components
		], ['id_product' => $id]);

		return $query;
	}

	public function deleteComponent($idProduct, $idComponent)
    {
		$components = $this->getProductById($idProduct)['components'];
		$components = json_decode($components, true);

		foreach ($components as $component)
		{
			if ($component['product'] == $idComponent)
			{
				$index = array_search($component, $components);
				unset($components[$index]);
			}
		}

		$components = json_encode($components);

		$query = $this->database->update('products', [
			'components' => $components
		], ['id_product' => $idProduct]);

        return $query;
    }

	/* Categorías
	--------------------------------------------------------------------------- */
	public function getAllCategories($number)
	{
		$query = $this->database->select('products_categories_' . $number, '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

	public function getCategoryById($id, $number)
	{
		$query = $this->database->select('products_categories_' . $number, '*', ['id_product_category_' . $number => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newCategory($name, $number, $avatar)
	{
		if ($number == 'one')
		{
			if (isset($avatar))
			{
				$this->component->loadComponent('uploader');

				$_com_uploader = new Upload;
				$_com_uploader->SetFileName($avatar['name']);
				$_com_uploader->SetTempName($avatar['tmp_name']);
				$_com_uploader->SetFileType($avatar['type']);
				$_com_uploader->SetFileSize($avatar['size']);
				$_com_uploader->SetUploadDirectory(PATH_IMAGES . 'products/categories');
				$_com_uploader->SetValidExtensions(['jpg', 'jpeg', 'png']);
				$_com_uploader->SetMaximumFileSize('unlimited');

				$avatar = $_com_uploader->UploadFile();
			}

			if ($avatar['status'] == 'success' OR !isset($avatar))
			{
				if ($avatar['status'] == 'success')
					$avatar = $avatar['file'];

				$query = $this->database->insert('products_categories_one', [
					'name' => $name,
					'avatar' => $avatar,
					'id_subscription' => Session::getValue('id_subscription')
				]);

				return $query;
			}
			else
				return null;
		}
		else
		{
			$query = $this->database->insert('products_categories_' . $number, [
				'name' => $name,
				'id_subscription' => Session::getValue('id_subscription')
			]);

			return $query;
		}
	}

	public function editCategory($id, $name, $number, $avatar)
	{
		if ($number == 'one')
		{
			if (isset($avatar))
			{
				$this->component->loadComponent('uploader');

				$_com_uploader = new Upload;
				$_com_uploader->SetFileName($avatar['name']);
				$_com_uploader->SetTempName($avatar['tmp_name']);
				$_com_uploader->SetFileType($avatar['type']);
				$_com_uploader->SetFileSize($avatar['size']);
				$_com_uploader->SetUploadDirectory(PATH_IMAGES . 'products/categories');
				$_com_uploader->SetValidExtensions(['jpg', 'jpeg', 'png']);
				$_com_uploader->SetMaximumFileSize('unlimited');

				$avatar = $_com_uploader->UploadFile();
			}

			if ($avatar['status'] == 'success' OR !isset($avatar))
			{
				if ($avatar['status'] == 'success')
				{
					$avatar = $avatar['file'];

					$query = $this->database->update('products_categories_one', [
						'name' => $name,
						'avatar' => $avatar,
						'id_subscription' => Session::getValue('id_subscription')
					], [
						'id_product_category_one' => $id
					]);
				}
				else if (!isset($avatar))
				{
					$query = $this->database->update('products_categories_one', [
						'name' => $name,
						'id_subscription' => Session::getValue('id_subscription')
					], [
						'id_product_category_one' => $id
					]);
				}

				return $query;
			}
			else
				return null;
		}
		else
		{
			$query = $this->database->update('products_categories_' . $number, [
				'name' => $name
			], ['id_product_category_' . $number => $id]);

			return $query;
		}
	}

	public function deleteCategories($selection)
    {
		foreach ($selection as $value)
		{
			$selection = explode('_', $value);

			$query = $this->database->delete("products_categories_" . $selection[1], [
	            'id_product_category_' . $selection[1] => $selection[0]
	        ]);
		}

        return $query;
    }

	public function checkExistCategory($id, $name, $action, $number)
	{
		$query = $this->database->select('products_categories_' . $number, '*', [
			'AND' => [
				'name' => $name,
				'id_subscription' => Session::getValue('id_subscription')
			]
		]);

		if (!empty($query) AND $action == 'new')
			return true;
		else if (!empty($query) AND $action == 'edit' AND $id != $query[0]['id_product_category_' . $number])
			return true;
		else
			return false;
	}

	/*
	--------------------------------------------------------------------------- */
	public function getAllSettings()
    {
        $query = $this->database->select('settings', '*', ['id_subscription' => Session::getValue('id_subscription')]);
        return $query[0];
    }
}
