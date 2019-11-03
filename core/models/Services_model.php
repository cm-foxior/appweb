<?php

defined('_EXEC') or die;

class Services_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Servicios
	--------------------------------------------------------------------------- */
	public function getAllServices()
	{
		$query = $this->database->select('services', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

	public function getServiceById($id)
	{
		$query = $this->database->select('services', '*', ['id_service' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newService($name, $folio, $price, $discount, $coin, $components, $warranty, $category, $observations)
	{
        $query = $this->database->insert('services', [
            'name' => $name,
            'folio' => $folio,
            'price' => $price,
            'discount' => $discount,
			'coin' => $coin,
			'components' => $components,
            'id_warranty' => $warranty,
            'id_service_category' => $category,
            'observations' => $observations,
			'id_subscription' => Session::getValue('id_subscription')
        ]);

        return $query;
	}

	public function editService($id, $name, $folio, $price, $discount, $coin, $components, $warranty, $category, $observations)
	{
        $query = $this->database->update('services', [
            'name' => $name,
			'folio' => $folio,
            'price' => $price,
			'discount' => $discount,
			'coin' => $coin,
			'components' => $components,
            'id_warranty' => $warranty,
            'id_service_category' => $category,
            'observations' => $observations
        ], ['id_service' => $id]);

        return $query;
	}

	public function changeStatusServices($selection, $status)
    {
		$query = $this->database->update('services', [
            'status' => $status
        ], ['id_service' => $selection]);

        return $query;
    }

	public function deleteServices($selection)
    {
		$query = $this->database->delete('services', [
            'id_service' => $selection
        ]);

        return $query;
    }

	public function checkExistService($id, $name, $folio, $action)
	{
		$query = $this->database->select('services', '*', [
			'AND' => [
				'OR' => [
					'name' => $name,
					'folio' => $folio
				],
				'id_subscription' => Session::getValue('id_subscription')
			]
		]);

		if (!empty($query))
		{
			$errorName	= false;
			$errorFolio	= false;

			foreach ($query as $data)
			{
				if ($action == 'new' AND $name == $data['name'])
					$errorName = true;
				else if ($action == 'edit' AND $name == $data['name'] AND $id != $data['id_service'])
					$errorName = true;

				if ($action == 'new' AND $folio == $data['folio'])
					$errorFolio = true;
				else if ($action == 'edit' AND $folio == $data['folio'] AND $id != $data['id_service'])
					$errorFolio = true;
			}

			if ($errorName == true OR $errorFolio == true)
				return ['status' => true, 'errors' => ['errorName' => $errorName, 'errorFolio' => $errorFolio]];
			else
				return ['status' => false];
		}
		else
			return ['status' => false];
	}

	/* Componentes
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

	public function getComponentById($id)
	{
		$query = $this->database->select('products', '*', ['id_product' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newComponent($id, $components)
	{
		$query = $this->database->update('services', [
			'components' => $components
		], ['id_service' => $id]);

		return $query;
	}

	public function deleteComponent($idService, $idComponent)
    {
		$components = $this->getServiceById($idService)['components'];
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

		$query = $this->database->update('services', [
			'components' => $components
		], ['id_service' => $idService]);

        return $query;
    }

	/* Categorías
	--------------------------------------------------------------------------- */
	public function getAllCategories()
	{
		$query = $this->database->select('services_categories', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

	public function getCategoryById($id)
	{
		$query = $this->database->select('services_categories', '*', ['id_service_category' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newCategory($name)
	{
		$query = $this->database->insert('services_categories', [
			'name' => $name,
			'id_subscription' => Session::getValue('id_subscription')
		]);

		return $query;
	}

	public function editCategory($id, $name)
	{
		$query = $this->database->update('services_categories', [
			'name' => $name
		], ['id_service_category' => $id]);

		return $query;
	}

	public function deleteCategories($selection)
    {
		$query = $this->database->delete("services_categories", [
            'id_service_category' => $selection
        ]);

        return $query;
    }

	public function checkExistCategory($id, $name, $action)
	{
		$query = $this->database->select('services_categories', '*', [
			'AND' => [
				'name' => $name,
				'id_subscription' => Session::getValue('id_subscription')
			]
		]);

		if (!empty($query) AND $action == 'new')
			return true;
		else if (!empty($query) AND $action == 'edit' AND $id != $query[0]['id_service_category'])
			return true;
		else
			return false;
	}

	public function checkCategoryRelationships($id)
	{
		$query = $this->database->select('services', '*', ['id_service_category' => $id]);
		return (!empty($query)) ? true : false;
	}

	/* Garantías
	--------------------------------------------------------------------------- */
	public function getAllWarranties()
	{
		$query = $this->database->select('warranties', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'time_frame ASC']);
		return $query;
	}

	public function getWarrantyById($id)
	{
		$query = $this->database->select('warranties', '*', ['id_warranty' => $id]);
		return !empty($query) ? $query[0] : '';
	}
}
